$(document).ready(function(){

    var formRenew = $('#renew-password');
    var alertH = formRenew.find('.alert');
    formRenew.validate({
        submitHandler: function(){

            var email = $('#field_renew_email').val();
            $.post(CMS.base_url + 'usuario/passwordrenew', {
                email: email
            }).then(function(res){

                if(res === true || res == 1){
                    alertH.addClass('alert-success').removeClass('hide alert-warning').find('.text').text('Senha redefinida. Veja seu e-mail.');
                } else {
                    console.log('erro', res);
                    alertH.addClass('alert-warning').removeClass('alert-success hide').find('.text').text(res);

                }
            });

        }
    });

});