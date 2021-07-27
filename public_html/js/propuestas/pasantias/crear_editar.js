$(document).ready(function(){
    $('#formPasantias').validate({
        rules: {
            titulo: {
                required: true,
                maxlength: 255,
            },
            lugar: {
                required: true,
                maxlength: 255,
            },
            descripcion: {
                required: true,
            },
            tutores: {
                required: true,
                maxlength: 255,
            },
            duracion: {
                required: true,
                number: true,
                range: [1,12],
            },
            fecha_fin: {
                required: true,
            },
        }
    });
});