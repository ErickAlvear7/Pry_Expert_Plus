
<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');

    //$xServidor = $_SERVER['HTTP_HOST'];
    $page = isset($_GET['page']) ? $_GET['page'] : "index";
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());    

    @session_start();

    //$yEmprid = $_SESSION["i_empre_id"];
    $yEmprid = 1;
    $yPerfid = 1;
    $xDisabledEdit = "";
    
    /*if(isset($_SESSION["s_usuario"])){
        if($_SESSION["s_login"] != "loged"){
            header("Location: ./logout.php");
            exit();
        }
    } else{
        header("Location: ./logout.php");
        exit();
    }*/

    $xSql = "SELECT mta.meta_id AS MentId,men.menu_descripcion AS Menu,tar.tare_nombre AS Tarea,'Activo' AS Estado,'NO' AS Ckeck,";
    $xSql .= "men.menu_orden AS OrdenMenu,tar.tare_orden AS OrdenTarea FROM `expert_menu` men INNER JOIN `expert_menu_tarea` mta ON men.menu_id=mta.menu_id ";
    $xSql .= "INNER JOIN `expert_tarea` tar ON mta.tare_id=tar.tare_id WHERE men.empr_id=" . $yEmprid . " AND men.menu_estado= 'A' AND tar.tare_estado= 'A'AND mta.meta_id NOT IN ";
    $xSql .= "(SELECT pmt.meta_id FROM `expert_perfil_menu_tarea` pmt WHERE pmt.perf_id=" . $yPerfid . " AND pmt.empr_id=" . $yEmprid .") UNION SELECT ";
    $xSql .= "mta.meta_id AS MentId,men.menu_descripcion AS Menu,tar.tare_nombre AS Tarea,'Activo' AS Estado,'SI' AS Ckeck,men.menu_orden AS OrdenMenu,";
    $xSql .= "tar.tare_orden AS OrdenTarea FROM `expert_menu` men INNER JOIN `expert_menu_tarea` mta ON men.menu_id=mta.menu_id ";
    $xSql .= "INNER JOIN `expert_tarea` tar ON mta.tare_id=tar.tare_id INNER JOIN expert_perfil_menu_tarea pmt ON mta.meta_id=pmt.meta_id ";
    $xSql .= " WHERE pmt.empr_id=" . $yEmprid . " AND pmt.perf_id=" . $yPerfid . " AND men.menu_estado='A' AND tar.tare_estado='A'";
    $xSql .= "ORDER BY OrdenMenu,OrdenTarea";

    $all_perfiles = mysqli_query($con, $xSql);
    foreach ($all_perfiles as $perfil){
        $xName = $perfil["Perfil"];
    }
	
?>	

        <!--begin::Container-->
                <div id="kt_content_container" class="container-xxl">
                    <div class="card card-flush">
                        <div class="card-header">
                            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8">
                                <li class="nav-item">
                                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_settings_general">											
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M16.0173 9H15.3945C14.2833 9 13.263 9.61425 12.7431 10.5963L12.154 11.7091C12.0645 11.8781 12.1072 12.0868 12.2559 12.2071L12.6402 12.5183C13.2631 13.0225 13.7556 13.6691 14.0764 14.4035L14.2321 14.7601C14.2957 14.9058 14.4396 15 14.5987 15H18.6747C19.7297 15 20.4057 13.8774 19.912 12.945L18.6686 10.5963C18.1487 9.61425 17.1285 9 16.0173 9Z" fill="currentColor" />
                                            <rect opacity="0.3" x="14" y="4" width="4" height="4" rx="2" fill="currentColor" />
                                            <path d="M4.65486 14.8559C5.40389 13.1224 7.11161 12 9 12C10.8884 12 12.5961 13.1224 13.3451 14.8559L14.793 18.2067C15.3636 19.5271 14.3955 21 12.9571 21H5.04292C3.60453 21 2.63644 19.5271 3.20698 18.2067L4.65486 14.8559Z" fill="currentColor" />
                                            <rect opacity="0.3" x="6" y="5" width="6" height="6" rx="3" fill="currentColor" />
                                        </svg>
                                    Perfil</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_settings_store">
                                        <!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm004.svg-->
                                        <span class="svg-icon svg-icon-2 me-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3" d="M18 10V20C18 20.6 18.4 21 19 21C19.6 21 20 20.6 20 20V10H18Z" fill="currentColor" />
                                                <path opacity="0.3" d="M11 10V17H6V10H4V20C4 20.6 4.4 21 5 21H12C12.6 21 13 20.6 13 20V10H11Z" fill="currentColor" />
                                                <path opacity="0.3" d="M10 10C10 11.1 9.1 12 8 12C6.9 12 6 11.1 6 10H10Z" fill="currentColor" />
                                                <path opacity="0.3" d="M18 10C18 11.1 17.1 12 16 12C14.9 12 14 11.1 14 10H18Z" fill="currentColor" />
                                                <path opacity="0.3" d="M14 4H10V10H14V4Z" fill="currentColor" />
                                                <path opacity="0.3" d="M17 4H20L22 10H18L17 4Z" fill="currentColor" />
                                                <path opacity="0.3" d="M7 4H4L2 10H6L7 4Z" fill="currentColor" />
                                                <path d="M6 10C6 11.1 5.1 12 4 12C2.9 12 2 11.1 2 10H6ZM10 10C10 11.1 10.9 12 12 12C13.1 12 14 11.1 14 10H10ZM18 10C18 11.1 18.9 12 20 12C21.1 12 22 11.1 22 10H18ZM19 2H5C4.4 2 4 2.4 4 3V4H20V3C20 2.4 19.6 2 19 2ZM12 17C12 16.4 11.6 16 11 16H6C5.4 16 5 16.4 5 17C5 17.6 5.4 18 6 18H11C11.6 18 12 17.6 12 17Z" fill="currentColor" />
                                            </svg>
                                        </span>
                                    Opciones Perfil</a>
                                </li>
                            </ul>
                        </div>
                                    <div class="card-header"> 
                                        <div class="card-toolbar">
                                            <button type="button" id="btnGuardar" class="btn btn-light-primary" onclick="f_Guardar(<?php echo $yEmprid; ?>)"><i class="las la-save"></i>Guardar</button>
                                        </div>
                                    </div>                              
                        
                            <div class="tab-content" id="myTabContent">                                                                            
                                <div class="tab-pane fade show active" id="kt_ecommerce_settings_general" role="tabpanel">                                                                                        
                                        <div class="card-body pt-0">													
                                            <div class="row fv-row mb-7">
                                                <div class="col-md-3 text-md-end">
                                                    <label class="fs-6 fw-bold form-label mt-3">
                                                        <span class="required">Perfil</span>
                                                        <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Ingrese Nombre del Perfil"></i>
                                                    </label>
                                                </div>
                                                <div class="col-md-9">
                                                    <input type="text" class="form-control form-control-solid" name="txtPerfil" id="txtPerfil" maxlength="80" placeholder="Nombre del Perfil" value="" />
                                                </div>
                                            </div>
                                            <div class="row fv-row mb-7">
                                                <div class="col-md-3 text-md-end">
                                                    <label class="fs-6 fw-bold form-label mt-3">
                                                        <span>Descripción</span>
                                                        <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Ingrese Descripción del Perfil"></i>
                                                    </label>
                                                </div>
                                                <div class="col-md-9">
                                                    <textarea class="form-control form-control-solid" name="txtDescripcion" id="txtDescripcion" maxlength="255" onkeydown="return (event.keyCode!=13);"></textarea>
                                                </div>                                                          
                                            </div>
                                            <div class="row fv-row mb-7">

                                                <div class="col-md-3 text-md-end">
                                                    <label class="fs-6 fw-bold form-label mt-3">
                                                        <span>Parámetros</span>
                                                    </label>
                                                </div>

                                                <div class="col-lg-8 fv-row">
                                                    <div class="d-flex align-items-center mt-3">
                                                        <label class="form-check form-check-inline form-check-solid me-5">
                                                            <input class="form-check-input" name="chkCrear" type="checkbox" />
                                                            <span class="fw-bold ps-2 fs-6">Crear</span>
                                                        </label>
                                                        <label class="form-check form-check-inline form-check-solid">
                                                            <input class="form-check-input" name="chkModificar" type="checkbox" />
                                                            <span class="fw-bold ps-2 fs-6">Modificar</span>
                                                        </label>
                                                        <label class="form-check form-check-inline form-check-solid">
                                                            <input class="form-check-input" name="chkEliminar" type="checkbox" />
                                                            <span class="fw-bold ps-2 fs-6">Eliminar</span>
                                                        </label>                                                                    
                                                    </div>
                                                </div>
                                            </div>                                                        
                                        </div>
                                    
                                </div>
                                <div class="tab-pane fade" id="kt_ecommerce_settings_store" role="tabpanel">
                                    <div class="card card-flush">
                                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                            <div class="card-title">
                                                <h2>Menú de Opciones</h2>
                                            </div>
                                        </div>
                                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">					
                                            <div class="card-title">					
                                                <div class="d-flex align-items-center position-relative my-1">
                                                    <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                                        </svg>
                                                    </span>
                                                    <input type="text" data-kt-ecommerce-order-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Buscar Dato" />
                                                </div>
                                            </div>
                                        </div>                                                    
                                        <div class="card-body pt-0">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5 table-hover" id="kt_ecommerce_report_shipping_table" style="width: 100%;">
                                                <thead>
                                                    <tr class="text-start text-gray-800 fw-bolder fs-7 gs-0">
                                                            <th>Seleccionar</th>
                                                            <th>Menú</th>                                    
                                                            <th>Tarea</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fw-bold text-gray-700">
                                                    <?php
                                                        foreach ($all_perfiles as $perfil){    
                                                    ?>
                                                    <tr id="tr_<?php echo $perfil['MentId']; ?>">
                                                        <td style="text-align:center">
                                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                <input class="form-check-input chkMenu" type="checkbox" name="check[]" id="chk<?php echo $perfil['MentId']; ?>" value="<?php echo $perfil['MentId']; ?>" />
                                                            </div>
                                                        </td>                                                                      
                                                        <td><?php echo $perfil['Menu']; ?></td>
                                                        <td>
                                                            <div id="div_<?php echo $perfil['MentId']; ?>" >
                                                                <?php echo $perfil['Tarea']; ?>
                                                            </div>
                                                        </td>                                                         
                                                    </tr>
                                                    <?php }
                                                        ?>                            
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>                                                                             
                            </div>
                    </div>
                </div>
                


            <script>

                $(document).ready(function(){

                    $(document).on("click",".chkMenu",function(){
                        //let _id = $(this).closest('tr').attr('id');  OBTENER EL ID DEL TR
                        let _rowid = $(this).attr("id");         
                        let _id = _rowid.substring(3);
                        let _div = "div_" + _id;              
                        let _check = $("#chk" + _id).is(":checked");
                        if(_check){
                            $("#"+_div).addClass("badge badge-light-primary");
                        }else{
                            $("#"+_div).removeClass("badge badge-light-primary");
                        }                        
                        //_tarea = $(this).closest("tr").find('td:eq(2)').text();                         
                    });
                    
                    // $('#btnGuardar').click(function(){
                    //     _perfil = $.trim($("#txtPerfil").val());
                    //     _observacion = $.trim($("#txtDescripcion").val());
                    //     _estado = "Activo";

                    //     if(_perfil == '')
                    //     {       
                    //         //mensajesalertify("Ingrese Nombre del Perfil..!!","W","top-right",3);  
                    //         mensajesweetalert("center-start","warning","Ingrese Nombre del Perfil..!",false,1800);  
                    //         return;
                    //     }

                    //     var i = 0;

                    //     $("input[type=checkbox]:checked").map(function(){
                    //         if($(this).val() != 'on'){
                    //             _result[i] = $(this).val();
                    //             i++;
                    //         }
                    //     });

                    //     if(i == 0)
                    //     {                        
                    //         mensajesweetalert("center-start","warning","Seleccione al menos un opción Menu/Tareal",false,1800);
                    //         return;
                    //     }

                    //     $parametros = {

                    //     }

                    // });                     


                }); 

                function f_Guardar(_emprid){
                    _perfil=$.trim($("#txtPerfil").val());
                    _observacion=$.trim($("#txtDescripcion").val());
                    _estado="Activo";
                    _result=[];

                    if(_perfil == '')
                    {       
                        mensajesweetalert("center","warning","Ingrese Nombre del Perfil..!",false,1800);  
                        return;
                    }

                    var _contar = 0;

                    //debugger;
                    /*$("input[type=checkbox]:checked").map(function(){
                        if($(this).val() == 'on'){
                            _result[i] = $(this).val();
                            i++;
                        }
                    });*/

                    /*var tbl = document.getElementById('kt_ecommerce_report_shipping_table');
                    var rCount = tbl.rows.length;
                    var cCount = 1;
                    var allArray = [];
                    for (var i = 1; i <rCount; i++){
                        var rowArray = [];
                        for (var j = 0; j <cCount; j++){                            
                            rowArray.push(tbl.rows[i].cells[j].children[0].value);
                        }
                        allArray.push(rowArray);
                    }
                    console.log(allArray);                 

                    if(i == 0)*/
                    
                    var grid = document.getElementById("kt_ecommerce_report_shipping_table");
                    var checkBoxes = grid.getElementsByTagName("input");
                    for (var i = 0; i < checkBoxes.length; i++) {
                        if (checkBoxes[i].checked) {
                            //var row = checkBoxes[i].parentNode.parentNode;
                            _result.push(checkBoxes[i].value);
                            _contar++
                        }
                    }

                    //console.log( _result);

                    if(_contar == 0){                        
                        mensajesweetalert("center-start","warning","Seleccione al menos un opción Menu/Tareal",false,1800);
                        return;
                    }

                    $parametros = {
                        xxPerfil: _perfil,
                        xxEmprid: _emprid
                    }      
                    
                    //var xrespuesta = $.post("codephp/consultar_perfil.php", $parametros);
                    
                }


            </script> 