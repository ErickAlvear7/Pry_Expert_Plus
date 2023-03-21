$(document).ready(function(){
    
    TableData = $('#tabledata').DataTable({
        "columnDefs": [{
            "data": null
        },
        { visible: false, targets: [0] }
    ],
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "sProcessing": "Procesando..."
        }
    });

    TableDataContacto = $('#tblcontacto').DataTable({
        "columnDefs": [{
            "data": null
        },
        { visible: false, targets: [0,3,7] }
    ],
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "sProcessing": "Procesando..."
        }
    });    

    // TableDataContactoNew = $('#tblcontactonew').DataTable({
    //     "columnDefs": [{
    //         "data": null
    //     },
    //     { visible: false, targets: [0,3,7] }
    // ],
    //     "language": {
    //         "lengthMenu": "Mostrar _MENU_ registros",
    //         "zeroRecords": "No se encontraron resultados",
    //         "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
    //         "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
    //         "infoFiltered": "(filtrado de un total de _MAX_ registros)",
    //         "sSearch": "Buscar:",
    //         "oPaginate": {
    //             "sFirst": "Primero",
    //             "sLast": "Último",
    //             "sNext": "Siguiente",
    //             "sPrevious": "Anterior"
    //         },
    //         "sProcessing": "Procesando..."
    //     }
    // });      

    TableDataPerfilCalifica = $('#tblperfilcalifica').DataTable({
        "columnDefs": [{
            "data": null
        },
        { visible: false, targets: [0] }
    ],
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "sProcessing": "Procesando..."
        }
    });     

    TableDataDepa = $('#tabledatadepa').DataTable({
        "columnDefs": [{
            "data": null
        },
        { visible: false, targets: [0,4] }
    ],
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "sProcessing": "Procesando..."
        }
    });

    /*TableNoOrder = $('#tablenoorder').DataTable({
        "order": [],
        "columnDefs" : [{            
            "targets":-1,
            "data":null,
            "render": function(data, type, row, meta)
            {
                let deshabilitaSub = '', deshabilEliminar = '';
                if(row[0] == "200001")
                {
                    deshabilEliminar  = 'disabled="disabled"';
                }
                if(meta.row == 0){
                    deshabilitaSub  = 'disabled="disabled"';
                }
                return '<div class="text-center"><div class="btn-group"><button class="btn btn-outline-primary btn-sm" id="btnSubirNivel" '+
                    deshabilitaSub+'><i class="fa fa-arrow-up"></i></button><button class="btn btn-outline-info btn-sm ml-3" id="btnEditar">'+
                    '<i class="fa fa-pencil-square-o"></i></button><button class="btn btn-outline-danger btn-sm ml-3" id="btnEliminar" ' + 
                    deshabilEliminar +'><i class="fa fa-trash-o"></i></button></div></div>'
            }            
        },
            { targets: 'no-sort' },
            { orderable: false, targets: '_all' },
            { visible: false, targets: [0] }
    ],
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast":"Último",
                "sNext":"Siguiente",
                "sPrevious": "Anterior"
                },
            "sProcessing":"Procesando...",
        }        
    });*/

    TableNoOrder = $('#tablenoorder').DataTable({
        "order": [],
        "columnDefs" : [{            
            "data": null,
        },
            { targets: 'no-sort' },
            { orderable: false, targets: '_all' },
            { visible: false, targets: [0] }
    ],
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast":"Último",
                "sNext":"Siguiente",
                "sPrevious": "Anterior"
                },
            "sProcessing":"Procesando...",
        }        
    });    
    
    TableConOrder = $('#tableconorder').DataTable({
        "order": [],        
        "columnDefs" : [{            
            "targets": -1,
            "data": null,            
            "render": function(data, type, row, meta)
            {
                let deshabilitaSub = '', deshabilitaBaj = ''; 
                if(row[1] == "NO")
                {
                    deshabilitaSub  = 'disabled="disabled"';
                    deshabilitaBaj  = 'disabled="disabled"';
                }
                if(meta.row == 0){
                    deshabilitaSub  = 'disabled="disabled"';
                }
                return '<div class="text-center"><div class="btn-group"><button class="btn btn-outline-primary btn-sm" id="btnSubirNivel" '+
                deshabilitaSub+'><i class="fa fa-arrow-up"></i></button><button class="btn btn-outline-info btn-sm ml-3" id="btnBajarNivel" '+
                deshabilitaBaj+'><i class="fa fa-arrow-down"></i></button></div></div>'
            }
        },
            { targets: 'no-sort' },
            { orderable: false, targets: '_all' },
            { visible: false, targets: [0,1] }
    ],
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast":"Último",
                "sNext":"Siguiente",
                "sPrevious": "Anterior"
                },
            "sProcessing":"Procesando...",
        }        
    });         

    TableDataSup = $('#tabledatasup').DataTable({
        "columnDefs": [{
            "data": null
        },
        { visible: false, targets: [0,1] }
    ],
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "sProcessing": "Procesando..."
        }
    });       

});