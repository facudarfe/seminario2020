$(document).ready(function(){
    $('#modalCV').ready(function(){
        //Validacion del lado del cliente
        $('#formCV').validate({
            rules: {
                CV: {
                    required: true,
                    extension: "pdf"
                }
            },
            messages: {
                CV: {
                    extension: "El archivo debe tener extension .pdf"
                }
            }
        });
    });
});
