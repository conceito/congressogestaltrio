$(document).ready(function(){

	$(".panel-form").sticky({topSpacing:30, bottomSpacing: 50, getWidthFrom: '.col-xs-5'});



});

angular.element(document).ready(function () {
	angular.bootstrap(document, ['app']);
});

var app = angular.module('app', []);

/**
 * tab Dados do trabalho, box Avaliadores
 */
app.controller('AvaliacaoController', ['$scope', 'Avaliadores', 'Avaliacoes',
	function ($scope, Avaliadores, Avaliacoes) {

		$scope.CMS = CMS;
		$scope.avaliador = {};
		$scope.avaliadores = [];
		$scope.toggleAvaliadorDropdown = false;



		$scope.aval = {
			q1: false,
			q2: false,
			q3: false,
			q4: false,
			q5: false,
			q6: false,
			q7: false,
			q8: false,
			q9: false,
			q10: false,
			q11: false,
			q12: false,
			q13: false,
			q14: ''
		};

		/**
		 * populate avaliadores combobox
		 */
//		Avaliadores.all().success(function (res) {
//			if (res.error) {
//				alert(res.msg);
//			}
//			else {
//				$scope.avaliadores = res.data;
//			}
//		});

		/**
		 * list avaliações. Done and undone
		 */
//		Avaliacoes.all().success(function (res) {
//			console.log('Avaliacoes', res.data);
//			$scope.avaliacoes = {
//				finished: res.data.finished,
//				awaiting: res.data.awaiting
//			};
//		});


		$scope.sendEvaluation = function () {
			// get selected
			var avaliador = $scope.avaliador;

			Avaliacoes.sendEvaluation(CMS.avaliacao.id, {form: $scope.aval}).then(function(res){
//				console.log('=========== response =============');
//				console.log(res.data);

				if(res.data.error){
					alert("Houve um erro ao salvar sua avaliação. Por favor, tente mais tarde.");
				} else {
					$('#avalStatus').modal({
						backdrop: 'static',
						keyboard: false
					});
				}

			});

		};


	}]);



app.factory('Avaliadores', ['$http', function($http){

	var endpoint = CMS.base_url + 'cms/api_avaliadores/';

	return {
		all: function(){

			var url = endpoint + 'all';
			return $http.get(url);
		},
		sendInvite: function(avaliador, jobId){
			return $http.post(endpoint + 'invite', {jobid: jobId, userid: avaliador.id});
		}
	};

}]);

app.factory('Avaliacoes', ['$http', function($http){

	var endpoint = CMS.base_url + 'cms/api_avaliacoes/';

	return {
		/**
		 * return all appraisers from a content
		 * @param contentId
		 * @returns {*}
		 */
		all: function(contentId){
			var id = (contentId) ? contentId : CMS.trabalho.id;
			var url = endpoint + 'all/contentId:' + id;
			return $http.get(url);
		},

		remove: function(ava){
			var id = (ava.id) ? ava.id : null;
			var url = endpoint + 'remove/' + id;
			return $http.get(url);
		},

		sendEvaluation: function(id, data){
			var url = endpoint + 'evaluate/' + id;

			return $http.post(url, data, {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded'
				}
			});
		}
	};

}]);
