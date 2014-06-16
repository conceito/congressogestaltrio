angular.element(document).ready(function () {
    angular.bootstrap(document, ['app']);
});

var app = angular.module('app', ['ui.sortable', 'textAngular']);

app.controller('AvaliadoresController', ['$scope', 'Avaliadores', 'Avaliacoes',
    function($scope, Avaliadores, Avaliacoes){

        $scope.avaliadores = [];
        $scope.avaliacoes = [];

        Avaliacoes.all().success(function(res){
            console.log('Avaliacoes', res.data);
        });

        Avaliadores.all().success(function(res){
           console.log('Avaliadores', res.data);
        });

    }]);

app.controller('AutoresController', ['$scope', '$timeout', 'Autores', 'uiSortableConfig',
    function ($scope, $timeout, Autores, uiSortableConfig) {

        uiSortableConfig.handle = ".handle";

        // items
        $scope.opts = [];
        // base url
        $scope.base_url = CMS.base_url;

        // status
        $scope.changed = false;


        Autores.get({
            id: CMS.trabalho.id
        }).success(function (data, status, headers, config) {

            if (data.error) {
                alert(data.msg);
            } else {
                $scope.opts = data.data;
            }
        }).
            error(function (data, status, headers, config) {
                alert("ERRO: Houve um erro na comunicação com o servidor.");
            });


        var resetOrders = function () {
            var i = 0;
            angular.forEach($scope.opts, function (opt) {
                opt.ordem = i;
                i++;
            });
        };

        $scope.update = function () {
            if (CMS.trabalho.id === undefined) {
                alert("ERRO: O ID da opção não foi identificado.");
                return;
            }
            resetOrders();
            Autores.update(CMS.trabalho.id, $scope.opts)
                .success(function (data, status, headers, config) {
                    if (data.error) {
                        alert(data.msg);
                    }
                    $scope.changed = false;
                })
                .error(function (data, status, headers, config) {
                    alert("ERRO: Houve um erro na comunicação com o servidor.");
                });

        };

        /**
         * Remove item da lista
         * @param  {Object} opt
         */
        $scope.remove = function (opt) {
            var index = $scope.opts.indexOf(opt);
            $scope.opts.splice(index, 1);
        };

        $scope.addAuthor = function () {
            var newOpt = {
                id: Date.now(),
                user_id: 0,// if belongs to a cms_usuarios
                ordem: 0,
                nome: "Nome do autor...",
                curriculo: "",
                status: 1
            };
            $scope.opts.unshift(newOpt);
        };


        $scope.$watch('opts', function (newVal, oldVal) {
            if (newVal !== oldVal) {
                $scope.changed = true;
            }
        }, true);

        $scope.sortableOptions = {
            update: function (e, ui) {

//                console.log('updated', ui.item.scope().opt.nome);

            },
            stop: function (e, ui) {
//                console.log('stoped', ui.item.scope().opt.nome);
                // resetOrders();
            },
            removed: function (e, ui) {
//                console.log('removed', ui.item.scope().opt.nome);
            },
            axis: 'y'
        };


    }
]);

