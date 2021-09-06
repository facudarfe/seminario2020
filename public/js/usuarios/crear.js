function validationData(){
    return {
        operacion: function(){
            return $('#formUsuarios').data('operation')
        },
        id: function(){
            return $('#formUsuarios').find('#id').val();
        }
    };  
}

$(document).ready(function(){
    $('#formUsuarios').validate({
        rules: {
            dni:{
                required: true,
                minlength: 7,
                number: true,
                remote: {
                    url: config.routes.verificarDni,
                    type: 'POST',
                    data: validationData()
                }
            },
            lu: {
                required: false,
                number: true,
                remote: {
                    url: config.routes.verificarLu,
                    type: 'POST',
                    data: validationData()
                }
            },
            name: {
                required: true,
                maxlength: 255
            },
            password: {
                required: true,
                minlength: 7
            },
            email: {
                required: true,
                email: true,
                remote: {
                    url: config.routes.verificarEmail,
                    type: 'POST',
                    data: validationData()
                }
            },
            direccion: {
                required: false,
                maxlength: 255
            },
            telefono: {
                required: false,
                number: true,
                minlength: 9
            }
        },
        messages: {
            dni: {
                remote: "Este DNI ya esta registrado."
            },
            lu: {
                remote: "Este LU ya esta registrado."
            },
            email: {
                remote: "Este email ya esta registrado."
            }
        }
    });

});