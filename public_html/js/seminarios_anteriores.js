$(document).ready(function(){
    // Se activa la opcion del menu
    $('header ul li:nth-child(2)').addClass('active');

    $('#seminariosTable').dataTable({
        dom: "<'row'<'col-sm-6'<'float-sm-left'f>><'col-sm-6 p-0'>>"+
                "<'row'<'col-12'rt>>"+
                "<'row'<'col-12'<'float-right'p>>>",
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
        },
        columnDefs: [
            {"targets": 6, "searchable": false, "orderable": false}
        ],
    });
});