$(document).ready(function(){
    $('#formDocentes').validate({
        rules: {
            dni: {
                required: true,
                minlength: 7,
                number: true,
            },
            name: {
                required: true,
                maxlength: 255
            },
            email: {
                required: true,
                email: true,
            },
        }
    });
});