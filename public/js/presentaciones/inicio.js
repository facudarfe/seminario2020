$('#modalInforme').ready(function(){
    $('#formInforme').validate({
        rules: {
            informe: {
                required: true,
                extension: "pdf"
            }
        },
        messages: {
            informe: {
                extension: "El archivo debe tener extension .pdf"
            }
        }
    });
});