$(document).ready(function(){
    table = $('#tablaTemas').DataTable({
        "order": [[1, 'desc']],
        "columnDefs": [
            {"targets": [0,6], "searchable": false, "orderable": false},
        ],
        dom: "<'row'<'col-sm-6'<'float-sm-left'f>><'col-sm-6 p-0'<'float-sm-right'l>>>"+
                "<'row'<'col-12'rt>>"+
                "<'row'<'col-12'<'float-right'p>>>",
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
        },
        
    });

    //Codigo para eliminar un tema
    $(document).on('click', '#botonElimina', function(){
        var id = $(this).data('id');
        $('#deleteModal').modal('show');
        $('#deleteModal').ready(function(){
            let form = $(this).find('form');
            form.attr('action', '/temas/' + id + '/eliminar');
        });
    });
});