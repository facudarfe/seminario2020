$(document).ready(function(){
    $('#tablaDocentes').dataTable({
        dom: "<'row'<'col-sm-6'<'float-sm-left'f>><'col-sm-6 p-0'<'float-sm-right'l>>>"+
                "<'row'<'col-12'rt>>"+
                "<'row'<'col-12'<'float-right'p>>>",
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
        },
        columnDefs: [
            {"targets": 4, "searchable": false, "orderable": false}
        ],
    });

    $('#botonElimina').click(function(){
        var dni = $(this).data('dni');

        $('#deleteModal').modal('show');
        $('#deleteModal').ready(function(){
            $(this).find('form').attr('action', 'docentes/' + dni + '/eliminar')
        });
    });
});