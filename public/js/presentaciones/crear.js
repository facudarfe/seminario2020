$(document).ready(function(){
    validarFormulario();

    //Opcion por defecto en el select de las modalidades
    $("#modalidad option[value='2']").attr('selected', true);

    //Mostrar checkbox de trabajo grupal si se selecciono como modalidad Seminario
    $('#modalidad').change(function(){
        let modalidad = $('#modalidad option:selected').text();
        
        if(modalidad == 'Seminario')
            $('#trabajoGrupal').removeAttr('hidden');
        else
            $('#trabajoGrupal').attr('hidden', 'true');
    });


    $('#grupo').multiselect({
        buttonWidth: '100%',
        nonSelectedText: 'Ninguno seleccionado',
        allSelectedText: 'Todos seleccionados',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        filterPlaceholder: 'Buscar',
        dropRight: true,
        nSelectedText: 'seleccionados',
    }); //Inicializamos el select con opciones multiples

    //Si se selecciona el checkbox de trabajo grupal se van a mostrar los estudiantes disponibles
    $('#checkGrupal').change(function(){
        if($(this).prop('checked'))
            $('#nombresGrupo').removeAttr('hidden');
        else
            $('#nombresGrupo').attr('hidden', 'true');
    });
});

function validarFormulario(){
    $('#formPresentacion').validate({
        submitHandler: function(form){
            //Mostrar el modal cuando el formulario sea valido
            $('#modalResumen').modal('show');
            $('#modalResumen').ready(function(){
                var titulo = $('#formPresentacion #titulo').val();
                
                /*Como se utiliza el mismo javascript para cuando se crea y se resube una presentacion (en el crear se utiliza
                select y en el resubir se utiliza input para mostrar director, codirector y modalidad) hay que distinguir que elemento
                es para que luego se muestre correctamente el valor en el modal*/
                var directorElement = $('#formPresentacion #director');
                var director = directorElement.is('select') ? directorElement.find('option:selected').text() : directorElement.val();
                var codirectorElement = $('#formPresentacion #codirector');
                var codirector = codirectorElement.is('select') ? codirectorElement.find('option:selected').text() : codirectorElement.val();
                var modalidadElement = $('#formPresentacion #modalidad');
                var modalidad = modalidadElement.is('select') ? modalidadElement.find('option:selected').text() : modalidadElement.val();
                
                var resumen = $('#formPresentacion #resumen').val();
                var tecnologias = $('#formPresentacion #tecnologias').val();
                var descripcion = $('#formPresentacion #descripcion').val();

                $('#modalResumen #titulo').text(titulo);
                $('#modalResumen #director').text(director);
                $('#modalResumen #codirector').text(codirector);
                $('#modalResumen #modalidad').text(modalidad);
                $('#modalResumen #resumen').text(resumen);
                $('#modalResumen #tecnologias').text(tecnologias);
                $('#modalResumen #descripcion').text(descripcion);
                
                $('#confirmar').click(function(){
                    form.submit();
                });
            }); 
        },
        rules: {
            titulo: 'required',
            resumen: 'required',
            tecnologias: 'required',
            descripcion: 'required'
        },
    });
}