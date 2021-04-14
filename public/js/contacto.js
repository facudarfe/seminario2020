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
            },
            'receptores[]': {
                required: true,
            },
        }
    });

    //Inicializamos el select con opciones multiples
    $('#receptores').multiselect({
        buttonWidth: '100%',
        nonSelectedText: 'Ninguno seleccionado',
        allSelectedText: 'Todos seleccionados',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        filterPlaceholder: 'Buscar',
        dropRight: true,
        nSelectedText: 'seleccionados',
    });

    // Se muestra el icono del spinner
    $('#botonCarga').click(function(){
        $(this).find('i').removeClass('d-none');
    });
});