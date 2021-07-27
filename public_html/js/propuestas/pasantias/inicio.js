$(document).ready(function(){
    table = $('#tablaTemas').DataTable({
        "order": [[1, 'desc']],
        "columnDefs": [
            {"targets": [0,8], "searchable": false, "orderable": false},
        ],
        dom: "<'row'<'col-sm-6'<'float-sm-left'f>><'col-sm-6 p-0'<'float-sm-right'l>>>"+
                "<'row'<'col-12'rt>>"+
                "<'row'<'col-12'<'float-right'p>>>",
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
        },
        
    });

    //Darse de baja de una pasantia
    $(document).on('click', '#botonBaja', function(){
        var id = $(this).data('id');
        $('#liberarModal').modal('show');
        $('#liberareModal').ready(function(){
            $('#darBaja').attr('href', '/pasantias/'+id+'/liberar');
        });
    });

    //Eliminar una pasantia
    $(document).on('click', '#botonElimina', function(){
        var id = $(this).data('id');
        $('#deleteModal').modal('show');
        $('#deleteModal').ready(function(){
            let form = $(this).find('form');
            form.attr('action', '/pasantias/' + id + '/eliminar');
        });
    });

    //Codigo para habilitar o deshabilitar una pasantia a traves de AJAX
    $('#habilitar, #deshabilitar').on('click', function(e){
        e.preventDefault();

        var id = $(this).data('id');
        $.ajax({
            url: 'pasantias/' + id + '/' + $(this).attr('id'),
            method: 'PATCH',
            success: function(){
                location.reload(); //Recargamos la pagina una vez se actualizo el estado
            }
        });
    });
});