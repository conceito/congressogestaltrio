$(document).ready(function(){

    $('#field_cpf').mask('999.999.999-99');
    $('#field_cep').mask('99.999-999');
    $('#field_tel').mask('(99) 99999999?9');


    /**
     * atualizaçao combo cidades
     */
    var myAj;
    $('.loading-uf').hide();
    $("#field_uf").on('change', function(){

        var uf = $(this).val();

        if(uf.length < 2){
            return;
        }

        if (typeof (myAj) == 'function'){
            myAj.abort();
            return;
        }
        populaCidades(uf, null, 'cidade-ajax');
    });

    /**
     *	POPULA COMBOBOX CIDADES
     */
    function populaCidades(UF, CIDID, obj_target) {
        myAj = $.ajax({
            type: "POST",
            url: CMS.site_url + "cms/cmsutils/comboCidade/" + UF,
            dataType: "html",
            beforeSend: function() {
                $('.loading-uf').show();
            },
            success: function(message) {
                $('.loading-uf').hide();

                if (message.length > 0){
                    $("#"+obj_target).html(message).children().addClass('form-control');


                    if(CIDID){
                        $("#cidade").val(CIDID);
                    }

                } else {
                    $("#"+obj_target).html('<option>Não existe</option>');
                }
            }
        });
    }




    $('#field_cep').on('keyup', function(){

        var val = $(this).val(),
            uf_id = 'field_uf',
            cidade_id = 'cidade',
            bairro_id = 'field_bairro',
            logradouro_id = 'field_logra';

        val = replaceAll(val, '-', '');
        val = replaceAll(val, ',', '');
        val = replaceAll(val, '.', '');
        val = replaceAll(val, '_', '');

        if(val.length < 8){
            return;
        }

        if (typeof (myAj) == 'function'){
            myAj.abort();
            return;
        }

        myAj = $.ajax({
            type: "POST",
            url: CMS.site_url + "inscricao/cep/" + val,
//            dataType: ($.browser.msie) ? "text" : "html",
            dataType: "html",
            beforeSend: function() {
                $('.loading-uf').show();
                //console.log('aguarde...');
            },
            success: function(message) {
                $('.loading-uf').hide();
                myAj.abort();

                if (message.length > 0){

                    // console.log(message);

                    var dados = $.parseJSON(message);
                    console.log(dados);
                    console.log(dados.logradouro);
                    console.log(dados.bairro);
                    console.log(dados.localidade);
                    console.log(dados.uf);
                    console.log(dados.cidade_id);

//                    $('#'+uf_id).val(dados.uf).change();
                    $('#'+uf_id).val(dados.uf);
                    $('#'+bairro_id).val(dados.bairro);
                    $('#'+logradouro_id).val(dados.logradouro);
                    $("#cidade").val(dados.cidade_id).change();
                    populaCidades(dados.uf, dados.cidade_id, 'cidade-ajax');


                } else {
                    $("#cidade").html('<option>Não existe</option>');
                }
            }
        });

    });


});

// substitui strings >> replaceAll(str, '.', ':');
function replaceAll(string, token, newtoken) {
    while (string.indexOf(token) != -1) {
        string = string.replace(token, newtoken);
    }
    return string;
}
