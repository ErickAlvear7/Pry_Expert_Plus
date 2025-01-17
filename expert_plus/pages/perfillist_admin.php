<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');

	require_once("./dbcon/config.php");
	require_once("./dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');		

    //$xServidor = $_SERVER['HTTP_HOST'];
    $page = isset($_GET['page']) ? $_GET['page'] : "index";
    $menuid = $_GET['menuid'];
    
    @session_start();

    if(isset($_SESSION["s_usuario"])){
        if($_SESSION["s_loged"] != "loged"){
            header("Location: ./logout.php");
            exit();
        }
    } else{
        header("Location: ./logout.php");
        exit();
    }

    $xPaisid = $_SESSION["i_paisid"];
    $xEmprid = $_SESSION["i_emprid"];	
	$xUsuaid = $_SESSION["i_usuaid"];
    
	$mensaje = (isset($_POST['mensaje'])) ? $_POST['mensaje'] : '';
    
    $xSQL = "SELECT perf_id AS Id, UPPER(perf_descripcion) AS Perfil, UPPER(perf_observacion) AS Descripcion,CASE perf_estado WHEN 'A' THEN 'Activo' ELSE 'Inactivo' END AS Estado, ";
    $xSQL .= "perf_detalle1,perf_detalle2,perf_detalle3,perf_detalle4,perf_detalle5 FROM `expert_perfil` WHERE pais_id=$xPaisid AND empr_id=$xEmprid";
    $all_perfiles = mysqli_query($con, $xSQL);
	
?>	

<div id="kt_content_container" class="container-xxl">
    <input type="hidden" id="mensaje" value="<?php echo $mensaje ?>">
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-5 g-xl-9">
        <?php 
            foreach ($all_perfiles as $perfil){ 
                $xid = $perfil['Id'];
                $xNamePerfil = $perfil['Perfil'];
                $xEstado = $perfil['Estado'];

                $cheking = "";
                $xDisabledEdit = "";

                if($xEstado == "Activo"){
                    $color = "text-center text-primary";
                    $cheking = 'checked="checked"';
                }else{
                    $color = "text-center text-danger";
                    $cheking = "";
                    $xDisabledEdit = 'disabled';
                }
                   
                $xSQL = "SELECT COUNT(*) AS Total FROM `expert_usuarios` WHERE pais_id=$xPaisid AND perf_id=$xid ";
                $all_users = mysqli_query($con, $xSQL);
                foreach ($all_users as $contar){
                    $xTotal = $contar['Total'];
                }
               
            ?>
                <div class="col-md-4">
                    <div class="card border-secondary h-md-100" style="background-color: #82a4db;">
                        <div class="card-body d-flex flex-center">
                            <h2 class="text-white fst-italic"><?php echo $perfil['Perfil']; ?></h2>
                        </div>
                        <div class="card-body mt-n5">
                            <div class="form-check">
                                <input <?php echo $cheking; ?> class="form-check-input" type="checkbox" value="" id="chk_<?php echo $xid; ?>" 
                                    onchange="f_UpdateEstado(<?php echo $xid; ?>,<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>)">
                                <label id="lblcolor_<?php echo $xid; ?>" class="form-check-label <?php echo $color; ?>">
                                    <?php echo $xEstado; ?>
                                </label>
                            </div>
                        </div>
                        <div class="card-body pt-1">
                            <div class="fw-bolder text-white mb-5">Total Usuarios con el perfil: <?php echo $xTotal; ?></div>
                            <?php if($perfil['perf_detalle1'] != '') { ?>
                                <div class="d-flex align-items-center py-2">
                                    <span class="bullet bg-primary me-3"></span><?php echo $perfil['perf_detalle1']; ?>
                                </div> 
                            <?php } ?>

                            <?php if($perfil['perf_detalle2'] != '') { ?>
                                <div class="d-flex align-items-center py-2">
                                    <span class="bullet bg-primary me-3"></span><?php echo $perfil['perf_detalle2']; ?>
                                </div> 
                            <?php } ?>
                            
                            <?php if($perfil['perf_detalle3'] != '') { ?>
                                <div class="d-flex align-items-center py-2">
                                    <span class="bullet bg-primary me-3"></span><?php echo $perfil['perf_detalle3']; ?>
                                </div> 
                            <?php } ?>
                            
                            <?php if($perfil['perf_detalle4'] != '') { ?>
                                <div class="d-flex align-items-center py-2">
                                    <span class="bullet bg-primary me-3"></span><?php echo $perfil['perf_detalle4']; ?>
                                </div> 
                            <?php } ?>
                            
                            <?php if($perfil['perf_detalle5'] != '') { ?>
                                <div class="d-flex align-items-center py-2">
                                    <span class="bullet bg-primary me-3"></span><?php echo $perfil['perf_detalle5']; ?>
                                </div> 
                            <?php } ?>                                    
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="d-grid gap-2">
                                    <button <?php echo $xDisabledEdit; ?> id="btnedit_<?php echo $xid; ?>" type="button" class="btn btn-outline-primary btn-sm text-black" onclick="f_Editar(<?php echo $perfil['Id']; ?>,'<?php echo $xNamePerfil; ?>','<?php echo $perfil['Descripcion']; ?>')"><i class="las la-pencil-alt me-1" aria-hidden="true" style="color: black"></i>Editar Perfil</button>
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
            <?php }
                ?>                          
        <div class="col-md-4">
            <div class="card">
                <img src="assets/media/illustrations/sketchy-1/4.png" class="card-img-top" alt="..." height="200">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" data-repeater-create="" class="btn btn-primary btn-lg" id="btnNuevo"><i class="fa fa-user-plus me-1" aria-hidden="true"></i>
                        Agregar Perfil
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Agregar Perfil -->     
    <div class="modal fade" id="kt_modal_add_role" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-900px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Nuevo Perfil</h2>                            
                    <div class="btn btn-sm btn-icon btn-active-color-primary" aria-hidden="true" data-kt-roles-modal-action="close">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body py-lg-10 px-lg-10">
                    <div class="card card-flush py-4">
                       <div class="card-body pt-0">
                            <div class="flex-lg-row-fluid ms-lg-15">
                                <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8">
                                    <li class="nav-item">
                                        <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#tabperfiles">Accesos</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#tabdetalles">Caracteristicas</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="tabperfiles" role="tabpanel">
                                        <form id="kt_modal_add_role_form" class="form" >
                                            <div class="fv-row mb-10">
                                                <label class="fs-5 fw-bolder form-label mb-2">
                                                    <span class="required">Nombre Perfil</span>
                                                </label>
                                                <input class="form-control text-uppercase" name="txtPerfil" id="txtPerfil" maxlength="150" placeholder="Ingrese nombre del perfil" />
                                            </div>
                                            <div class="fv-row mb-10">
                                                <label class="fs-5 fw-bolder form-label mb-2">
                                                    <span>Observacion</span>
                                                </label>
                                                <input class="form-control text-uppercase" name="txtObservacion" id="txtObservacion" maxlength="150" placeholder="INGRESE DESCRIPCION DEL PERFIL" />
                                            </div>                                                
                                            <div class="fv-row">
                                                <label class="fs-5 fw-bolder form-label mb-2">Accesos Permitidos</label>
                                                <div class="table-responsive">
                                                    <table id="tblTareas" class="table align-middle table-row-dashed fs-6 gy-5" >
                                                        <tbody class="text-gray-600 fw-bold">
                                                            <tr>
                                                                <td class="text-gray-800">Acceso Total
                                                                    <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Conceder Permisos de Administrador"></i>
                                                                </td>
                                                                <td>
                                                                    <label class="form-check me-9">
                                                                        <input class="form-check-input" type="checkbox" value="" id="kt_roles_select_all" />
                                                                        <span class="form-check-label" for="kt_roles_select_all">Todos</span>
                                                                    </label>
                                                                </td>
                                                            </tr>

                                                            <?php

                                                                $xSQL = "SELECT DISTINCT mta.meta_id AS MentId,tar.tare_nombre AS Tarea,'NO' AS Ckeck FROM `expert_menu_tarea` mta, `expert_perfil_menu_tarea` pmt, `expert_tarea` tar ";
                                                                $xSQL .= "WHERE pmt.meta_id=mta.meta_id AND mta.tare_id=tar.tare_id AND  pmt.pais_id=$xPaisid ";
                                                                $xSQL .= " ORDER BY tar.tare_orden ";
                                                                $all_tareas = mysqli_query($con, $xSQL);
                                                                foreach ($all_tareas as $tareas){ ?>
                                                                    <tr>
                                                                        <td class="text-gray-800"><?php echo $tareas['Tarea']; ?></td>
                                                                        <td>
                                                                            <div class="d-flex">
                                                                                <label class="form-check form-check-sm me-5 me-lg-20">
                                                                                    <input class="form-check-input chkTarea" type="checkbox" name="chkTarea<?php echo $tareas['MentId']; ?>" id="chk<?php echo $tareas['MentId']; ?>" value="<?php echo $tareas['MentId']; ?>" />
                                                                                </label>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?> 	

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>  
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="tabdetalles" role="tabpanel">
                                        <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_role_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_role_header" data-kt-scroll-wrappers="#kt_modal_add_role_scroll" data-kt-scroll-offset="300px">
                                            <div class="fv-row mb-10">
                                                <label class="fs-5 fw-bolder form-label mb-2">
                                                    <span>Caracteristica 1</span>
                                                </label>
                                                <input class="form-control text-uppercase" name="txtDetalle1" id="txtDetalle1" maxlength="100" placeholder="Permite el control de..." />
                                            </div>
                                            <div class="fv-row mb-10">
                                                <label class="fs-5 fw-bolder form-label mb-2">
                                                    <span>Caracteristica 2</span>
                                                </label>
                                                <input class="form-control text-uppercase" name="txtDetalle2" id="txtDetalle2" maxlength="100" placeholder="Permite el control de..." />
                                            </div>
                                            <div class="fv-row mb-10">
                                                <label class="fs-5 fw-bolder form-label mb-2">
                                                    <span>Caracteristica 3</span>
                                                </label>
                                                <input class="form-control text-uppercase" name="txtDetalle3" id="txtDetalle3" maxlength="100" placeholder="Permite el control de..." />
                                            </div>
                                            <div class="fv-row mb-10">
                                                <label class="fs-5 fw-bolder form-label mb-2">
                                                    <span>Caracteristica 4</span>
                                                </label>
                                                <input class="form-control text-uppercase" name="txtDetalle4" id="txtDetalle4" maxlength="100" placeholder="Permite el control de..." />
                                            </div>
                                            <div class="fv-row mb-10">
                                                <label class="fs-5 fw-bolder form-label mb-2">
                                                    <span>Caracteristica 5</span>
                                                </label>
                                                <input class="form-control text-uppercase" name="txtDetalle5" id="txtDetalle5" maxlength="100" placeholder="Permite el control de..." />
                                            </div>                                                                                                                                                                                
                                        </div>
                                    </div>                                 
                                </div>
                            </div>
                       </div>
                   </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-danger btn-sm" data-kt-roles-modal-action="cancel"><i class="fa fa-times me-1" aria-hidden="true"></i>Cerrar</button>
                    <button type="button" class="btn btn-sm btn-light-primary border border-primary" id="btnGrabar" onclick="f_Grabar(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xUsuaid; ?>)"><i class="fa fa-hdd me-1"></i>Grabar</button>
                </div>
            </div>
        </div>
    </div>
   <!--Editar Perfil -->
    <div class="modal fade" id="kt_modal_update_role" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-900px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Editar Perfil</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" aria-hidden="true" data-kt-roles-modal-action="close">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body py-lg-10 px-lg-10">
                    <div class="card card-flush py-4">
                        <div class="card-body pt-0">
                            <div class="flex-lg-row-fluid ms-lg-15">
                                <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8">
                                    <li class="nav-item">
                                        <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#tabeditperfil">Accesos</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#tabeditdetalles">Caracteristicas</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="tabeditperfil" role="tabpanel">
                                        <form id="kt_modal_update_role_form" class="form" >
                                            <div class="fv-row mb-10">
                                                <label class="fs-5 fw-bolder form-label mb-2">
                                                    <span class="required">Nombre Perfil</span>
                                                </label>
                                                <input class="form-control text-uppercase" name="txtPerfiledit" id="txtPerfiledit" maxlength="150" placeholder="Ingrese nombre del perfil" />
                                            </div>
                                            <div class="fv-row mb-10">
                                                <label class="fs-5 fw-bolder form-label mb-2">
                                                    <span>Observacion</span>
                                                </label>
                                                <input class="form-control text-uppercase" name="txtObservacionedit" id="txtObservacionedit" maxlength="150" placeholder="ingrese descripcion del perfil..." />
                                            </div>                                                    
                                            <div class="fv-row">
                                                <label class="fs-5 fw-bolder form-label mb-2">Accesos Permitidos</label>
                                                <div class="table-responsive">
                                                    <table id="tblTareasEdit" class="table align-middle table-row-dashed fs-6 gy-5" >
                                                        <tbody class="text-gray-600 fw-bold" id="tbodyPermisos">
                                                            <tr>
                                                                <td class="text-gray-800">Acceso Total
                                                                    <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Conceder Permisos de Administrador"></i>
                                                                </td>
                                                                <td>
                                                                    <label class="form-check me-9">
                                                                        <input class="form-check-input" type="checkbox" value="" id="kt_roles_select_all" />
                                                                        <span class="form-check-label" for="kt_roles_select_all">Todos</span>
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>  
                                        </form>
                                    </div>
                                    <div class="tab-pane fade" id="tabeditdetalles" role="tabpanel">
                                        <div class="fv-row mb-10">
                                            <label class="fs-5 fw-bolder form-label mb-2">
                                                <span>Caracteristica 1</span>
                                            </label>
                                            <input class="form-control text-uppercase" name="txtDetalleedit1" id="txtDetalleedit1" maxlength="100" placeholder="Permite el control de..." />
                                        </div>
                                        <div class="fv-row mb-10">
                                            <label class="fs-5 fw-bolder form-label mb-2">
                                                <span>Caracteristica 2</span>
                                            </label>
                                            <input class="form-control text-uppercase" name="txtDetalleedit2" id="txtDetalleedit2" maxlength="100" placeholder="Permite el control de..." />
                                        </div>
                                        <div class="fv-row mb-10">
                                            <label class="fs-5 fw-bolder form-label mb-2">
                                                <span>Caracteristica 3</span>
                                            </label>
                                            <input class="form-control text-uppercase" name="txtDetalleedit3" id="txtDetalleedit3" maxlength="100" placeholder="Permite el control de..." />
                                        </div>
                                        <div class="fv-row mb-10">
                                            <label class="fs-5 fw-bolder form-label mb-2">
                                                <span>Caracteristica 4</span>
                                            </label>
                                            <input class="form-control text-uppercase" name="txtDetalleedit4" id="txtDetalleedit4" maxlength="100" placeholder="Permite el control de..." />
                                        </div>
                                        <div class="fv-row mb-10">
                                            <label class="fs-5 fw-bolder form-label mb-2">
                                                <span>Caracteristica 5</span>
                                            </label>
                                            <input class="form-control text-uppercase" name="txtDetalleedit5" id="txtDetalleedit5" maxlength="100" placeholder="Permite el control de..." />
                                        </div>                                                                                                                                                                                  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"> 
                    <button type="reset" class="btn btn-danger btn-sm" data-kt-roles-modal-action="cancel"><i class="fa fa-times me-1" aria-hidden="true"></i>Cerrar</button>
                    <button type="button" id="btnGrabarEdit" class="btn btn-sm btn-light-primary border border-primary" onclick="f_GrabarEditar(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xUsuaid; ?>)"><i class="las la-pencil-alt me-1"></i>Modificar</button>
                </div>
            </div>
        </div>
    </div> 
</div>
       
<script>
    $(document).ready(function(){

        _mensaje = $('input#mensaje').val();

        if(_mensaje != ''){
       
            toastSweetAlert("top-end",3000,"success",_mensaje);  
        }

        $("#btnNuevo").click(function(){

            _addmod = 'add';
            _perfilid = 0;

            $('[href="#tabperfiles"]').tab('show');
            $("#kt_modal_add_role").modal("show");
        });

    });


    function f_Editar(_perfilid, _perfilname, _descripcion){

        var _emprid = "<?php echo $xEmprid; ?>";
        var _paisid = "<?php echo $xPaisid; ?>";                
        _addmod = 'mod';
        _perfilold = _perfilname;
        _idperfil = _perfilid;

        var _detalle1,_detalle2,_detalle3,_detalle4,_detalle5;

        var _mytbody = document.getElementById("tbodyPermisos");

        document.getElementById("tbodyPermisos").innerHTML = '';

        var xresponse = $.post("codephp/get_DatosPerfil.php",{ xxPaisid: _paisid, xxEmprid: _emprid, xxPerfilid: _perfilid })

        xresponse.done(function(respuesta){
            
            var arraydatos = JSON.parse(respuesta);
            
            $.each(arraydatos,function(i,item){
                _detalle1 =  arraydatos[i].Detalle1;
                _detalle2 =  arraydatos[i].Detalle2;
                _detalle3 =  arraydatos[i].Detalle3;
                _detalle4 =  arraydatos[i].Detalle4;
                _detalle5 =  arraydatos[i].Detalle5;                 

                $("#txtDetalleedit1").val(_detalle1);
                $("#txtDetalleedit2").val(_detalle2);
                $("#txtDetalleedit3").val(_detalle3);
                $("#txtDetalleedit4").val(_detalle4);
                $("#txtDetalleedit5").val(_detalle5);                        
            });
        });

        var xresponse = $.post("codephp/get_TareasxPais.php", { xxPaisid: _paisid, xxEmprid: _emprid, xxPerfilid: _perfilid });

        xresponse.done(function(respuesta){

            var arrydatos = JSON.parse(respuesta);
            
            $.each(arrydatos,function(i,item){
                
                _mentid = arrydatos[i].Mentid;
                _tarea = arrydatos[i].Tarea;
                _check = arrydatos[i].Check;

                if(_check == 'SI'){
                    _checked = "checked='checked'";
                    _sicheck = 'SI';
                }else{
                    _checked = '';
                    _sicheck = 'NO';
                }

                if(_tarea == 'Perfil' || _tarea == 'Usuarios'){
                    _disabled = '';
                }else{
                    _disabled = '';
                }

                _mytbody.innerHTML += '<tr><td class="text-gray-800">' + _tarea + '</td>' +
                            '<td><div class="d-flex"><label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">' + 
                            '<input class="form-check-input" type="checkbox" ' + _disabled + ' name="chkTarea' + _mentid + '" id="chk' + _mentid + '" value="' + _mentid + '" ' + _checked + 
                            ' onchange="f_ChkEdit(' + _paisid + ',' + _perfilid + ',' + _mentid + ',' + _emprid + ',' +  "'" + _sicheck + "'"  + ')">' + '</label>' + '</div></td></tr>';

            });

            $('[href="#tabeditperfil"]').tab('show'); 
            $("#kt_modal_update_role").modal("show");
            $("#txtPerfiledit").val(_perfilname);
            $("#txtObservacionedit").val(_descripcion);

        });   
    }

    //ver perfil
    function f_VerPerfil(_idperf){
       
        var _emprid = "<?php echo $xEmprid; ?>";
        var _paisid = "<?php echo $xPaisid; ?>";  

        var _detalle1view,_detalle2view,_detalle3view,_detalle4view,_detalle5view;

        var tbodyview = document.getElementById('tbodyPermisosview');
        document.getElementById("tbodyPermisosview").innerHTML = '';

         //console.log(tbodyview);

         var xresponse = $.post("codephp/get_DatosPerfil.php",{ xxPaisid: _paisid, xxEmprid: _emprid, xxPerfilid: _idperf })

            xresponse.done(function(respuesta){
                
                var arraydatos = JSON.parse(respuesta);
                
                $.each(arraydatos,function(i,item){
                    _detalle1view =  arraydatos[i].Detalle1;
                    _detalle2view =  arraydatos[i].Detalle2;
                    _detalle3view =  arraydatos[i].Detalle3;
                    _detalle4view =  arraydatos[i].Detalle4;
                    _detalle5view =  arraydatos[i].Detalle5;                 

                    $("#txtDetalleview1").val(_detalle1view);
                    $("#txtDetalleview2").val(_detalle2view);
                    $("#txtDetalleview3").val(_detalle3view);
                    $("#txtDetalleview4").val(_detalle4view);
                    $("#txtDetalleview5").val(_detalle5view);                        
                });
            });

        $("#kt_modal_view_role").modal("show");
        
    }

    function f_ChkEdit(_paisid, _idperfil, _idmeta, _emprid, _sicheck){
            let _check = $("#chk" + _idmeta).is(":checked");
            let _tipo = "";

            if(_sicheck == 'SI'){
                _tipo = "Del";
            }else{
                _tipo = "Add";
            }

            $parametros = {
                xxPaisid: _paisid,
                xxIdPerfil: _idperfil,
                xxIdMeta: _idmeta,
                xxEmprid: _emprid,
                xxTipo: _tipo                    
            }

            var xrespuesta = $.post("codephp/delnew_perfil.php", $parametros);
            xrespuesta.done(function(response){
                //console.log(response);

            });

        }            

    function f_Grabar(_paisid, _emprid, _usuaid){

        _perfil = $.trim($("#txtPerfil").val());
        _observacion = $.trim($("#txtObservacion").val());
        _detalle1 = $.trim($("#txtDetalle1").val());
        _detalle2 = $.trim($("#txtDetalle2").val());
        _detalle3 = $.trim($("#txtDetalle3").val());
        _detalle4 = $.trim($("#txtDetalle4").val());
        _detalle5 = $.trim($("#txtDetalle5").val());
        _estado = "A";
        _result=[];

        if(_perfil == '')
        {       
            toastSweetAlert("top-end",3000,"warning","Ingrese Perfil..!!");  
            return;
        }

        var _contar = 0;

        var grid = document.getElementById("tblTareas");
        var checkBoxes = grid.getElementsByTagName("input");
        for (var i = 0; i < checkBoxes.length; i++) {
            if (checkBoxes[i].checked) {
                //var row = checkBoxes[i].parentNode.parentNode;
                _result.push(checkBoxes[i].value);
                _contar++
            }
        }

        if(_contar == 0){                        
            toastSweetAlert("top-end",3000,"warning","Asigne minimo un permiso");
            return;
        }

        var _parametros = {
            "xxPaisid" : _paisid,
            "xxPerfil" : _perfil,
            "xxEmprid" : _emprid
        }      

        var xrespuesta = $.post("codephp/consultar_perfil.php", _parametros);
        xrespuesta.done(function(response) {

            if(response.trim() == 0){

                var _datosperfil = {
                    "xxPaisid" : _paisid,
                    "xxPerfil" : _perfil,
                    "xxEmprid" : _emprid,
                    "xxUsuaid" : _usuaid,
                    "xxObservacion" : _observacion,
                    "xxDetalle1" : _detalle1,
                    "xxDetalle2" : _detalle2,
                    "xxDetalle3" : _detalle3,
                    "xxDetalle4" : _detalle4,
                    "xxDetalle5" : _detalle5,
                    "xxEstado" : _estado,
                    "xxResult" : _result
                }

                $.post("codephp/grabar_perfil.php", _datosperfil, function(response){

                    if(response.trim() == 'OK'){
                        $.redirect('?page=seg_perfiladmin&menuid=<?php echo $menuid; ?>', {'mensaje': 'Perfil Agregado'}); 
                        _detalle = 'Nuevo perfil creado';
                    }else{
                        _detalle = 'Error al grabar perfil';
                    }

                    /**PARA CREAR REGISTRO DE LOGS */
                    var _parametros = {
                        "xxPaisid" : _paisid,
                        "xxEmprid" : _emprid,
                        "xxUsuaid" : _usuaid,
                        "xxDetalle" : _detalle,
                    }					

                    $.post("codephp/new_log.php", _parametros, function(response){
                        //console.log(response);
                    });
                    
                });

            }else{
                toastSweetAlert("top-end",3000,"warning","Perfil ya Existe..!");
            }
        });

    }

    function f_GrabarEditar(_paisid, _emprid, _usuaid){

        var _perfil = $.trim($("#txtPerfiledit").val());
        var _observacion = $.trim($("#txtObservacionedit").val()).toUpperCase();
        
        var _detalle1 = $.trim($("#txtDetalleedit1").val());
        var _detalle2 = $.trim($("#txtDetalleedit2").val());
        var _detalle3 = $.trim($("#txtDetalleedit3").val());
        var _detalle4 = $.trim($("#txtDetalleedit4").val());
        var _detalle5 = $.trim($("#txtDetalleedit5").val());
        
        var _result=[];

        if(_perfil == '')
        {       
            toastSweetAlert("top-end",3000,"warning","Ingrese Perfil..!!");  
            return;
        }

        var _parametros = {
            "xxPaisid" : _paisid,
            "xxPerfil" : _perfil,
            "xxEmprid" : _emprid
        }
        
        if(_perfil != _perfilold){
            var xrespuesta = $.post("codephp/consultar_perfil.php", _parametros);
            xrespuesta.done(function(response) {
                //console.log(response);
                if(response.trim() == 0){

                    var _datosperfil = {
                        "xxPaisid" : _paisid,
                        "xxEmprid" : _emprid,
                        "xxPerfilid" : _idperfil,
                        "xxPerfil" : _perfil,
                        "xxDetalle1" : _detalle1,
                        "xxDetalle2" : _detalle2,
                        "xxDetalle3" : _detalle3,
                        "xxDetalle4" : _detalle4,
                        "xxDetalle5" : _detalle5,
                        "xxObservacion" : _observacion
                    }

                    $.post("codephp/update_perfil.php", _datosperfil, function(response){

                        if(response.trim() == 'OK'){
                            $.redirect('?page=seg_perfiladmin&menuid=<?php echo $menuid; ?>', {'mensaje': 'Perfil Actualizado'}); 
                            _detalle = 'Nuevo perfil creado';
                        }else{
                            _detalle = 'Error al grabar perfil';
                        }

                        /**PARA CREAR REGISTRO DE LOGS */
                        var _parametros = {
                            "xxPaisid" : _paisid,
                            "xxEmprid" : _emprid,
                            "xxUsuaid" : _usuaid,
                            "xxDetalle" : _detalle,
                        }					

                        $.post("codephp/new_log.php", _parametros, function(response){
                            //console.log(response);
                        });
                        
                    });

                }else{
                    toastSweetAlert("top-end",3000,"error","Perfil ya Existe..!!");
                }
            });
        }else{
            var _datosperfil = {
                "xxPaisid" : _paisid,
                "xxEmprid" : _emprid,
                "xxPerfilid" : _idperfil,
                "xxPerfil" : _perfil,
                "xxDetalle1" : _detalle1,
                "xxDetalle2" : _detalle2,
                "xxDetalle3" : _detalle3,
                "xxDetalle4" : _detalle4,
                "xxDetalle5" : _detalle5,
                "xxObservacion" : _observacion
            }

            $.post("codephp/update_perfil.php", _datosperfil, function(response){

                if(response.trim() == 'OK'){
                    $.redirect('?page=seg_perfiladmin&menuid=<?php echo $menuid; ?>', {'mensaje': 'Perfil Actualizado'}); 
                    _detalle = 'Nuevo perfil creado';
                }else{
                    _detalle = 'Error al grabar perfil';
                }

                /**PARA CREAR REGISTRO DE LOGS */
                var _parametros = {
                    "xxPaisid" : _paisid,
                    "xxEmprid" : _emprid,
                    "xxUsuaid" : _usuaid,
                    "xxDetalle" : _detalle,
                }					

                $.post("codephp/new_log.php", _parametros, function(response){
                    //console.log(response);
                });
                
            });
        }
    } 

    //Actualizar estado del Perfil
    function f_UpdateEstado(_idperfil,_idpais,_idempri){

        var _check = $("#chk_" + _idperfil).is(":checked");
        var _estado = '';
        var _class = '';
        var _lbl = "lblcolor_"+_idperfil;
        var _btnedit = "btnedit_"+_idperfil;

        if(_check){
            _estado = 'A';
            _lblestado = "Activo";
            _class = 'form-check-label text-center text-primary';
            $('#'+_btnedit).prop('disabled',false)
        }else{
            _estado = 'I';
            _lblestado = "Inactivo";
            _class = 'form-check-label text-center text-danger';
            $('#'+_btnedit).prop('disabled',true)
        }

        var _lblChanged = document.getElementById(_lbl);
        _lblChanged.innerHTML = '<label class="' + _class + '">' + _lblestado + '</label>';

        var _parametros = {
            "xxPerfid" : _idperfil,
			"xxPaisid" : _idpais,
			"xxEmprid" : _idempri,
            "xxEstado" : _estado
        }

        var xrespuesta = $.post("codephp/update_estadoperfil.php", _parametros);
		xrespuesta.done(function(response){
		});	
         
    }

    //desplazar ventana modal
    $("#kt_modal_add_role").draggable({
        handle: ".modal-header"
    });

    $("#kt_modal_update_role").draggable({
        handle: ".modal-header"
    });


</script>