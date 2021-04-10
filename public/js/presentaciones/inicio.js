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

    // Datatable para los Anexos 2
    $('#tablaAnexos2').DataTable({
        "columnDefs": [
            {"targets": 0, "orderable": false, "searchable": false},
            {"targets": 6, "searchable": false, "orderable": false}
        ],
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json",
        },
        dom: "<'row'<'col-sm-6'f><'col-sm-6 p-0'>>"+
        "<'row'<'col-12'rt>>"+
        "<'row'<'col-12'<'float-right'p>>>",
    });
}

//Subida del informe
$('#botonInforme').click(function(){
    var id = $(this).data('presentacion');

    $('#modalInforme').modal('show');
    $('#modalInforme').ready(function(){
        //Seteamos el atributo 'action' del form a la ruta con el id de la presentacion
        $(this).find('#formInforme').attr('action', '/presentaciones/'+id+'/subirInforme');

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

//Cuando no hay acciones disponibles se deshabilita el boton de accion
$(document).ready(function(){
    $('.activeOptions').each(function(index){
        let cant = $(this).children().length;
        if(cant == 0){
            $(this).prev('a').hide();
        }
    });
});

$('#modalFinal, #modalFinalTribunal').ready(function(){
    $('#fecha_propuesta, #fecha_definitiva').datetimepicker({
        minDate: moment(),
        icons: {
            time: 'fas fa-clock'
        },
        format: "DD/MM/yyyy HH:mm",
        locale: moment.locale('es')
    });

    // Inicializamos el select con opciones multiples
    $('.tribunal').multiselect({
        buttonWidth: '100%',
        nonSelectedText: 'Ninguno seleccionado',
        allSelectedText: 'Todos seleccionados',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        filterPlaceholder: 'Buscar',
        dropRight: true,
        nSelectedText: 'seleccionados',
    }); 

    // Validaciones para la propuesta de fecha
    $('#formFecha').validate({
        rules: {
            fecha_propuesta: {
                required: true,
            }
        }
    });

    // Validaciones para la definicion de fecha y de tribunal
    $('#formFechaTribunal').validate({
        rules: {
            fecha_definitiva: {
                required: true,
            },
            'tribunalTitular[]': {
                required: true,
                minlength: 1
            },
            'tribunalSuplente[]': {
                required: true,
                minlength: 1
            },
        },
        messages: {
            'tribunalTitular[]': {
                minlength: 'Seleccione al menos un docente'
            },
            'tribunalSuplente[]': {
                minlength: 'Seleccione al menos un docente'
            },
        }
    });
});

//Propuesta de fecha
$('#botonPropuestaFecha').click(function(){
    var id = $(this).data('id');

    $('#modalFinal').modal('show');
    $('#modalFinal').ready(function(){
        //Seteamos el atributo 'action' del form a la ruta con el id de la presentacion
        $(this).find('#formFecha').attr('action', '/presentaciones/'+id+'/proponerFecha');
    }); 
});

//Definir fecha y tribunal
$('#botonDefinirFecha').click(function(){
    var id = $(this).data('id');

    $('#modalFinalTribunal').modal('show');
    $('#modalFinalTribunal').ready(function(){
        //Seteamos el atributo 'action' del form a la ruta con el id de la presentacion
        $(this).find('#formFechaTribunal').attr('action', '/anexos2/'+id+'/definirFechaYTribunal');
    }); 
});