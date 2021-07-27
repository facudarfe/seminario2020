$(document).ready(function(){
    $('#tablaUsuarios').dataTable({
        dom: "<'row'<'col-sm-6'<'float-sm-left'f>><'col-sm-6 p-0'<'float-sm-right'l>>>"+
                "<'row'<'col-12'rt>>"+
                "<'row'<'col-12'<'float-right'p>>>",
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
        },
        columnDefs: [
            {"targets": 6, "searchable": false, "orderable": false}
        ],
    });
    
    $(document).on('click', '#botonElimina', function(){
        var user_id = $(this).attr('data-user_id');
        $('#deleteModal').modal('show');
        $('#deleteModal').ready(function(){
            $('#input_user_id').val(user_id);
        });
    });

    //Cuando no hay acciones disponibles se deshabilita el boton de accion
    $('.activeOptions').each(function(index){
        let cant = $(this).children().length;
        if(cant == 0){
            $(this).prev('a').hide();
        }
    });
});