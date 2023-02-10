<?php 

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("dbcon/config.php");
    require_once("dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xServidor = $_SERVER['HTTP_HOST'];
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());

    //$yEmprid = $_SESSION["i_empreid"];
    //$yUserid = $_SESSION["i_userid"];

    $yEmprid = 1;
    $yUserid = 1;

    $idmenu = $_POST['idmenu'];


    $xSQL = "SELECT menu_descripcion AS Menu, menu_observacion AS Observacion, CASE menu_estado WHEN 'A' THEN 'Activo' ";
    $xSQL .= "ELSE 'Inactivo' END AS Estado FROM `expert_menu` WHERE menu_id=$idmenu AND empr_id=$yEmprid";
    $expertmenu = mysqli_query($con, $xSQL);

    foreach($expertmenu as $menu){
        $xMenu = $menu['Menu'];
        $xObservacion = $menu['Observacion'];
    }

    $xSQL="SELECT tar.tare_id AS TareaId, 'SI' as Ckeck, tar.tare_nombre AS Tarea, tar.tare_ruta AS Ruta, CASE tar.tare_estado WHEN 'A' THEN 'Activo' ELSE ";
    $xSQL .="'Inactivo' END AS Estado, met.meta_orden AS Orden FROM `expert_tarea` tar INNER JOIN `expert_menu_tarea` met ON tar.tare_id=met.tare_id WHERE ";
    $xSQL .="met.menu_id=$idmenu AND tar.empr_id=$yEmprid UNION SELECT tar.tare_id AS TareaId, 'NO' as Ckeck, tar.tare_nombre AS Tarea, tar.tare_ruta AS Ruta, CASE ";
    $xSQL .="tar.tare_estado WHEN 'A' THEN 'Activo' ELSE 'Inactivo' END AS Estado, 50000 AS Orden FROM `expert_tarea` tar WHERE tar.tare_id NOT IN(SELECT ";
    $xSQL .="met.tare_id FROM `expert_menu_tarea` met WHERE met.menu_id=$idmenu AND met.empr_id=$yEmprid) AND tar.empr_id=$yEmprid  ORDER BY Orden; ";
    $expertarea = mysqli_query($con, $xSQL);

?>

<div id="kt_content_container" class="container-xxl">
   <div class="card card-flush">
        <div class="card-toolbar d-flex align-self-end">
            <a href="?page=seg_menuadmin" class="btn btn-light-primary"><i class="las la-arrow-left"></i>Regresar</a>
        </div>	
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
                    Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_settings_store">
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
                    Opciones SubMenu</a>
                </li>
            </ul>
        </div>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="kt_ecommerce_settings_general" role="tabpanel">
                <div class="card-header"> 
                    <div class="card-toolbar">
                        <button type="button" name="editar" id="editar" class="btn btn-light-primary" onclick="f_Guardar(<?php echo $yEmprid; ?>,<?php echo $yUserid; ?>)"><i class="las la-save"></i>Guardar</button>
                    </div>
                </div> 
                <div class="card-body">
                    <div class="row fv-row mb-7">
                        <div class="col-md-2 text-md-end">
                            <label class="fs-6 fw-bold form-label mt-3">
                                <span class="required">Menu</span>
                                <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Ingrese Nombre del Menu"></i>
                            </label>
                        </div>
                        <div class="col-md-9">
                            <input type="hidden" class="form-control form-control-solid" name="txtIdMenu" id="txtIdMenu" maxlength="150" value="<?php  echo $idmenu; ?>" />
                            <input type="text" class="form-control form-control-solid" name="txtMenu" id="txtMenu" maxlength="150" value="<?php  echo $xMenu; ?>" />
                        </div>
                    </div>
                    <div class="row fv-row mb-7">
                        <div class="col-md-2  text-md-end">
                            <label class="fs-6 fw-bold form-label mt-3">
                                <span>Descripción</span>
                                <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Ingrese Descripción del Menu"></i>
                            </label>
                        </div>
                        <div class="col-md-9">
                            <textarea class="form-control form-control-solid" name="txtDescripcion" id="txtDescripcion" maxlength="200" onkeydown="return (event.keyCode!=13);" value="<?php echo $xObservacion; ?>"><?php echo $xObservacion; ?></textarea>
                        </div>                                                          
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="kt_ecommerce_settings_store" role="tabpanel">
                <div class="card">
                    <div class="card-header border-0 pt-6">                
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                        <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                    </svg>
                                </span>
                                <input type="text" data-kt-ecommerce-order-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Buscar Dato" />
                            </div>
                        </div>
                        <div class="card-toolbar">
                            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                <div class="w-150px me-3">
                                    <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Estado" data-kt-ecommerce-order-filter="status">
                                        <option></option>
                                        <option value="all">Todos</option>
                                        <option value="Activo">Activo</option>
                                        <option value="Inactivo">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected">
                                <div class="fw-bolder me-5">
                                <span class="me-2" data-kt-customer-table-select="selected_count"></span>Selected</div>
                                <button type="button" class="btn btn-danger" data-kt-customer-table-select="delete_selected">Delete Selected</button>
                            </div>
                        </div>
                                            
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div class="card-body pt-0 ">
                            <table class="table align-middle table-row-dashed fs-6 gy-5 table-hover" id="kt_ecommerce_report_shipping_table" style="width: 100%;">
                            <thead>
                                    <tr class="text-start text-gray-400 fw-bolder fs-7 gs-0">
                                        <th>Seleccionar</th>
                                        <th style="display:none;">IdTarea</th>
                                        <th>SubMenu</th>
                                        <th>Estado</th>
                                        <th>Ruta</th>
                                    </tr>
                                </thead>
                               <tbody class="fw-bold text-gray-600">
                                    <?php
                                        foreach ($expertarea as $tareas){    
                                            $tareaid = $tareas['TareaId'];
                                    ?>
                                        <?php
                                                        
                                            if($tareas['Ckeck'] == 'SI'){
                                                $xTextColor = "badge badge-light-primary";
                                                $Checked = "checked='checked'";
                                            }else{
                                                $xTextColor = "";
                                                $Checked = "";
                                            }
                                        ?>     
                                    <tr>
                                        <td style="text-align: center;" >
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input chkTarea" type="checkbox" id="chk<?php echo $tareas['TareaId']; ?>" <?php echo $Checked; ?> onchange="f_Menu(<?php echo $tareaid; ?>,<?php echo $idmenu; ?>,<?php echo $yEmprid; ?>)"/>
                                            </div>
                                        </td>

                                        <td style="display:none;"><?php echo $tareas['TareaId']; ?></td>
                                        
                                        <td>
                                            <div id="div_<?php echo $tareas['TareaId']; ?>" class="<?php  echo $xTextColor; ?>" >
                                               <?php echo $tareas['Tarea']; ?>
                                             </div>
                                        </td>
                                        <td>
                                           <div  id="est_<?php echo $tareas['TareaId']; ?>" class="<?php echo $xTextColor; ?>"><?php echo $tareas['Estado']; ?></div>
                                        </td>
                                        <td><?php echo $tareas['Ruta']; ?></td>
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
</div>
<script>

$(document).ready(function(){

    $(document).on("click",".chkTarea",function(){
        let _rowid = $(this).attr("id");         
        let _id = _rowid.substring(3);
        let _div = "div_" + _id;              
        let _check = $("#chk" + _id).is(":checked");
        if(_check){
            $("#"+_div).addClass("badge badge-light-primary");
        }else{
            $("#"+_div).removeClass("badge badge-light-primary");
        }                        
    });


}); 

function f_Menu(_idtarea, _idmenu, _idempr){
    let _check = $("#chk" + _idtarea).is(":checked");

    let _tipo = "";

    if(_check){
        _tipo = "Add";
    }else{
        _tipo = "Del";
    }

    $parametros = {
        xxIdTarea: _idtarea,
        xxIdMenu: _idmenu,
        xxEmprid: _idempr,
        xxTipo: _tipo                    
    }

    var xrespuesta = $.post("codephp/delnew_menu.php", $parametros);
    xrespuesta.done(function(response){
        console.log(response);

    });

}

function f_Guardar(_emprid, _userid){

    _idmenu = $('#txtIdMenu').val();
    _menu = $.trim($("#txtMenu").val());
    _observacion = $.trim($("#txtDescripcion").val());

  
    if(_menu == '')
    {       
        mensajesweetalert("center","warning","Ingrese Nombre del Menu..!",false,1800);  
        return;
    }

    $parametros = {
        xxMenu: _menu,
        xxEmprid: _emprid
        
    }  

    var xrespuesta = $.post("codephp/consultar_menu.php", $parametros);
    xrespuesta.done(function(response) {
        //console.log(response);
        if(response == 0){

            $datosMenu = {
                xxMenu: _menu,
                xxObserva: _observacion,
                xxEmprid: _emprid,
                xxUsuario: _userid,
                xxIdMenu: _idmenu
            }

            $.ajax({
                url: "codephp/update_menu.php",
                type: "POST",
                dataType: "json",
                data: $datosMenu,          
                success: function(data){ 
                    //console.log(data);
                    if(data == 'OK'){
                        $.redirect('?page=seg_menuadmin', {'mensaje': 'Actualizado con Exito..!'}); 
                    }                                                                         
                },
                error: function (error){
                    console.log(error);
                }                            
            }); 
        }else{
            mensajesweetalert("center","warning","Nombre del Menu ya Existe..!",false,1800);
        }

    });

}

</script>     

