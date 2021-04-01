$(document).ready(function(){
    $('#botonCorreccion').click(function(){
        var version_id = $(this).data('version');

        $('#modalCorregir').modal('show');
        $('#modalCorregir').ready(function(){
            $(this).find('#version').val(version_id);
        });
    });

    //Modal para aceptar o no la participacion en el trabajo
    $('#aceptarParticipacion, #rechazarParticipacion').click(function(){
        var boton = $(this).attr('id');
        let modal = $('#modalParticipacion');

        modal.modal('show');
        modal.ready(function(){
            let form = $(modal).find('form');

            if(boton == 'rechazarParticipacion'){
                $('#modal-body-text').text('¿Esta seguro de rechazar la participación en este proyecto?');
                form.find('#tipo').val('rechazar');
            }
            else{
                form.find('#tipo').val('aceptar');
            }
        });
    });
});