$(document).ready(function(){
    $('#formContacto').validate({
        rules: {
            asunto: {
                required: true,
                maxlength: 30
            },
            descripcion: {
                required: true,
                maxlength: 500
            }
        }
    });
});