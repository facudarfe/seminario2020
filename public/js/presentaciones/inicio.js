//Codigo para el DataTable
var rol = $('#dataTable').data('role');
            
if(rol != 'Estudiante'){
    $('#dataTable').DataTable({
        "columnDefs": [
            {"targets": 0, "orderable": false},
            {"targets": 7, "searchable": false, "orderable": false}
        ],
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json",
            searchBuilder: {
                button: '<i class="fas fa-filter"></i> Filtros (%d)',
                add: 'Agregar',
                condition: 'Comparador',
                clearAll: 'Limpiar todo',
                deleteTitle: 'Eliminar',
                data: 'Columna',
                leftTitle: 'Izquierda',
                logicAnd: 'y',
                logicOr: 'ó',
                rightTitle: 'Derecha',
                title: {
                    0: 'Filtros',
                    _: 'Filtros (%d)'
                },
                value: 'Opción',
                valueJoiner: 'et',
                conditions: {
                    string: {
                        equals: 'Igual a',
                        not: 'Diferente de',
                        empty: 'Vacio',
                        notEmpty: 'No vacio',
                        startsWith: 'Comienza con',
                        endsWith: 'Termina con',
                        contains: 'Contiene'
                    },
                    date: {

                    }
                }
            }
        },
        dom: "<'row'<'col-sm-6'f><'col-sm-6 p-0'<'float-sm-right'B>>>"+
        "<'row'<'col-12'rt>>"+
        "<'row'<'col-12'<'float-right'p>>>",
        buttons: [
            {
                //Boton para generar el Excel
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success',
                titleAttr: 'Excel',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6]
                }
            },
            {
                //Boton para generar el PDF
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger',
                titleAttr: 'PDF',
                exportOptions: {
                    columns: [1, 2, 3, 4, 5, 6]
                }
            }
        ]
    });
}

//Subida del informe
$('#botonInforme').click(function(){
    var id = $(this).data('presentacion');

    $('#modalInforme').modal('show');
    $('#modalInforme').ready(function(){
        $('#idPresentacion').val(id);

        //Validacion del lado del cliente
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
});