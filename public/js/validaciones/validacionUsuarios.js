$(document).ready(function(){
    $('#formUsuarios').validate({
        rules: {
            dni:{
                required: true,
                minlength: 7,
                number: true,
                remote: {
                    url: '/verificar/dni',
                    type: 'POST'
                }
            },
            lu: {
                required: false,
                number: true,
                remote: {
                    url: '/verificar/lu',
                    type: 'POST'
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
                    url: '/verificar/email',
                    type: 'POST'
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