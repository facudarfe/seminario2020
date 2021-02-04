$(document).ready(function(){
    validarFormulario();
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