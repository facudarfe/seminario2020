$('#changePassword').click(function(e){
    e.preventDefault();
    $('#modalPerfil').modal('hide');
    $('#modalPassword').modal('show');
});

//Validacion del formulario para cambiar la contraseña
$(document).ready(function(){
    $('#formChangePassword').validate({
        rules:{
            oldpassword: {
                required: true,
                remote: {
                    url: '/verificarPassword',
                    type: 'POST'
                }
            },
            newpassword: {
                required: true,
                minlength: 7
            },
            repeatnewpassword: {
                required: true,
                equalTo: '#newpassword'
            }
        },
        messages: {
            oldpassword: {
                remote: 'La contraseña introducida no coincide con la contraseña actual'
            },
            repeatnewpassword: {
                equalTo: 'Las contraseñas no coinciden'
            }
        }
    });
});

$('#confirmChangePassword').click(function(){
    $('#formChangePassword').submit();
});

