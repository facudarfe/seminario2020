$(document).ready(function(){
    $('#formTemas').validate({
        rules: {
            titulo: {
                required: true,
                maxlength: 255,
            },
            descripcion: {
                required: true,
                maxlength: 500,
            },
            tecnologias: {
                required: true,
                maxlength: 255,
            }
        }
    });
});