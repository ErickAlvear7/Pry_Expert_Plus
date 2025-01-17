<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	  

    $xFechaActual = strftime('%Y-%m-%d', time());
    $xFechaFinCobertura = date("Y-m-d",strtotime ( "+1 year" , strtotime ( $xFechaActual)));

    require_once("dbcon/config.php");
    require_once("dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    //$xServidor = $_SERVER['HTTP_HOST'];
    $page = isset($_GET['page']) ? $_GET['page'] : "index";
    $mensaje = (isset($_POST['mensaje'])) ? $_POST['mensaje'] : '';
    $perid = $_POST['idper'];
    $tituid = $_POST['idtit'];
    $clieid = $_POST['idcli'];
    $prodid = $_POST['idpro'];
    $grupid = $_POST['idgru'];
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

    $xSQL = "SELECT DISTINCT provincia AS Descripcion FROM `provincia_ciudad` ";
	$xSQL .= "WHERE pais_id=$xPaisid AND estado='A' ORDER BY provincia ";
    $all_provincia = mysqli_query($con, $xSQL);


    $xSQL = "SELECT per.pers_id AS Idper,per.pers_numerodocumento AS Docu,CONCAT(per.pers_nombres,' ',per.pers_apellidos) AS Persona,per.pers_imagen AS Imagen, per.pers_fechanacimiento AS Fecha, ";
    $xSQL .="per.pers_ciudad AS Ciudad,per.pers_estado AS Estado FROM `expert_persona` per WHERE per.pers_id = $perid AND per.pais_id=$xPaisid AND per.empr_id=$xEmprid ";
    $persona = mysqli_query($con, $xSQL);

    foreach($persona as $per){
        $xPerid = $per['Idper'];
        $xDocumento = $per['Docu'];
        $xPersona = $per['Persona'];
        $xImagen = $per['Imagen'];
        $xFecha = $per['Fecha'];
        $xCiudad = $per['Ciudad'];
        $xEstado = $per['Estado'];
    }

    if($xEstado=='A'){
        $xestado='ACTIVO';
    }

    $xSQL = "SELECT ciudad AS Ciuper FROM `provincia_ciudad` WHERE prov_id=$xCiudad ";
    $ciudad = mysqli_query($con, $xSQL);

    foreach($ciudad as $ciu){
        $xCiuper = $ciu['Ciuper'];
    }


    $xSQL = "SELECT bene_id AS Beneid,bene_numerodocumento AS Docu,CONCAT(bene_nombres,' ',bene_apellidos) AS Beneficiario, bene_ciudad AS Ciudadben, bene_parentesco AS Parentesco, bene_estado AS Estadoben ";
    $xSQL .= "FROM `expert_beneficiario` WHERE titu_id=$tituid";
    $all_Beneficiario = mysqli_query($con, $xSQL);

    $xSQL = "SELECT paca_id AS Idpaca FROM `expert_parametro_cabecera` WHERE paca_nombre='Parentesco' ";
    $all_id = mysqli_query($con, $xSQL);

    foreach($all_id as $id){
        $xPacaid = $id['Idpaca'];
    }

    $xSQL = "SELECT  pade_orden AS Orden FROM `expert_parametro_detalle`WHERE paca_id = $xPacaid ORDER BY pade_orden DESC LIMIT 1 ";
    $orden = mysqli_query($con, $xSQL);
    foreach($orden as $ord){
        $xOrdenDet = $ord['Orden'];
    }


?>

<div id="kt_content_container" class="container-xxl">
    <input type="hidden" id="mensaje" value="<?php echo $mensaje ?>">
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
            <div class="card mb-5 mb-xl-8">
                <div class="card-header">
                    <div class="d-flex align-items-center collapsible py-3 toggle collapsed mb-0" data-bs-toggle="collapse" data-bs-target="#view_avatar">
                        <div class="btn btn-sm btn-icon mw-20px btn-active-color-primary me-5">
                            <span class="svg-icon toggle-on svg-icon-primary svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                                    <rect x="6.0104" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                                </svg>
                            </span>
                            <span class="svg-icon toggle-off svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                                    <rect x="10.8891" y="17.8033" width="12" height="2" rx="1" transform="rotate(-90 10.8891 17.8033)" fill="currentColor" />
                                    <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                                </svg>
                            </span>
                        </div>
                        <h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">Avatar</h4>
                    </div>  
                </div>
                <div id="view_avatar" class="collapse fs-6 ms-1">
                    <div class="card-body">
                        <div class="d-flex flex-center flex-column py-2">
                            <div class="image-input image-input-empty image-input-outline mb-3" data-kt-image-input="true">
                                <div class="image-input-wrapper w-150px h-150px" id="imgfiletitular"></div>
                            </div>
                            <label class="fs-3 text-gray-800 fw-bolder mb-3"><?php echo $xPersona; ?></label>
                            <div class="mb-9">
                                <div class="badge badge-lg badge-light-primary d-inline"><?php echo $xestado; ?></div>
                            </div>
                          
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-0">
                    <div class="d-flex align-items-center collapsible py-3 toggle collapsed mb-0" data-bs-toggle="collapse" data-bs-target="#view_titular">
                        <div class="btn btn-sm btn-icon mw-20px btn-active-color-primary me-5">
                            <span class="svg-icon toggle-on svg-icon-primary svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                                    <rect x="6.0104" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                                </svg>
                            </span>
                            <span class="svg-icon toggle-off svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                                    <rect x="10.8891" y="17.8033" width="12" height="2" rx="1" transform="rotate(-90 10.8891 17.8033)" fill="currentColor" />
                                    <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                                </svg>
                            </span>
                        </div>
                        <h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">Datos Titular</h4>
                    </div>
                </div>
                <div id="view_titular" class="collapse fs-6 ms-1">
                    <div class="card-body pt-2">
                        <div class="d-grid gap-2">
                            <div class="d-flex flex-column gap-10">
                                <div class="d-flex align-items-center">							
                                    <i class="fa fa-address-card fa-2x me-5" style="color:#55C4F4;" aria-hidden="true"></i>
                                    <div class="d-flex flex-column">
                                        <h5 class="text-gray-800 fw-bolder">No.Documento</h5>
                                        <div class="fw-bold">
                                            <label><?php echo $xDocumento; ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-map-marker fa-2x me-5" style="color:#55C4F4;" aria-hidden="true"></i>
                                    <div class="d-flex flex-column">
                                        <h5 class="text-gray-800 fw-bolder">Ciudad</h5>
                                        <div class="fw-bold">
                                            <div class="text-gray-600 text-uppercase"><?php echo $xCiuper; ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fa fa-calendar fa-2x me-5" style="color:#55C4F4;" aria-hidden="true"></i>
                                    <div class="d-flex flex-column">
                                        <h5 class="text-gray-800 fw-bolder">Fecha de Nacimiento</h5>
                                        <div class="fw-bold">
                                            <div class="text-gray-600"><?php echo $xFecha; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>      
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-0">
                    <div class="d-flex align-items-center collapsible py-3 toggle mb-0" data-bs-toggle="collapse" data-bs-target="#view_opciones">														<!--begin::Icon-->
                        <div class="btn btn-sm btn-icon mw-20px btn-active-color-primary me-5">
                            <span class="svg-icon toggle-on svg-icon-primary svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                                    <rect x="6.0104" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                                </svg>
                            </span>
                            <span class="svg-icon toggle-off svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                                    <rect x="10.8891" y="17.8033" width="12" height="2" rx="1" transform="rotate(-90 10.8891 17.8033)" fill="currentColor" />
                                    <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                                </svg>
                            </span>
                        </div>
                        <h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">Opciones</h4>
                    </div>
                </div>
                <div id="view_opciones" class="collapse show fs-6 ms-1">
                    <div class="card-body pt-2">
                        <div class="d-grid gap-2">
                            <button type="button" id="btnEditarPer_<?php echo $xPerid; ?>" class="btn btn-light-primary btn-sm btnEditarPer">
                                <i class="las la-pencil-alt" aria-hidden="true"></i>Editar Titular
                            </button>
							<button type="button" id="btnNewParen" class="btn btn-light-primary btn-sm mt-3 mb-2"><i class="fa fa-plus-circle" aria-hidden="true"></i>                                          
								Nuevo Parentesco
							</button>
                        </div>
                    </div>
                </div>   
            </div>
        </div>
        <div id="tab_Addbeneficiarios" class="flex-lg-row-fluid ms-lg-15">
            <div class="d-flex flex-stack fs-4 py-3 mt-n2 mb-2">
                <div class="d-flex justify-content-start">
                    <button type="button" class="btn btn-light-primary btn-sm mb-2" id="btnAgregarbene"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                        Agregar Beneficiario
                    </button>
                </div>
                <button type="button" id="btnRegresar" onclick="f_Regresar(<?php echo $clieid; ?>,<?php echo $prodid; ?>,<?php echo $grupid; ?>)" class="btn btn-icon btn-light-primary btn-sm ms-auto me-lg-n7" title="Regresar" data-bs-toggle="tooltip" data-bs-placement="left">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </button>
            </div>
            <div class="card pt-4 mb-6 mb-xl-9">                    
                <div class="card-header border-0">                         
                    <div class="card-title">
                        <h2>Lista de Beneficiarios</h2>
                    </div>   
                </div>
                <div class="card-body pt-0 pb-5">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed table-hover fs-6 gy-5" id="tblBeneficiario">
                            <thead class="border-bottom border-gray-200 fs-7 fw-bolder">
                                <tr class="text-start text-gray-800 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="class="min-w-125px"">Ciudad</th>
                                    <th class="min-w-125px">Nombres</th>
                                    <th class="min-w-125px">Parentesco</th>
                                    <th class="min-w-125px">Estado</th>
                                    <th>Status</th>
                                    <th style="text-align: center;">OPCIONES</th>
                                </tr>
                            </thead>
                            <tbody class="fs-6 fw-bold text-gray-600">
                                <?php 
                                    foreach($all_Beneficiario as $ben){
                                    $xBeneid = $ben['Beneid'];
                                    $xDocu = $ben['Docu'];
                                    $xBeneficiario = $ben['Beneficiario'];
                                    $xCiuben = $ben['Ciudadben'];
                                    $xParenben = $ben['Parentesco'];
                                    $xEstadoBen = $ben['Estadoben'];
                                ?>
                                <?php 

                                    $xSQL = "SELECT ciudad AS Ciuben FROM `provincia_ciudad` WHERE prov_id=$xCiuben ";
                                    $ciudadben = mysqli_query($con, $xSQL);

                                    foreach($ciudadben as $ciuben){
                                        $xCiubene = $ciuben['Ciuben'];
                                    }

                                    $xSQL = "SELECT pade_nombre AS NombrePare FROM `expert_parametro_detalle` WHERE pade_valorV='$xParenben' ";
                                    $parenben = mysqli_query($con, $xSQL);

                                    foreach($parenben as $pare){
                                        $xPareben = $pare['NombrePare'];
                                    }      
                                
                                ?>
                                <?php
                                    if($xEstadoBen=='A'){
                                        $xEstadoBen='ACTIVO';
                                    }else{
                                        $xEstadoBen='INACTIVO';
                                    } 

                                    $xCheking = '';
                                    $xDisabledEdit = '';

                                    if($xEstadoBen == 'ACTIVO'){
                                        $xCheking = 'checked="checked"';
                                        $xTextColor = "badge badge-light-primary";
                                    }else{
                                        $xTextColor = "badge badge-light-danger";
                                        $xDisabledEdit = 'disabled';
                                    }
                                ?>  
                                <tr id="row_<?php echo $xBeneid; ?>">
                                    <td class="text-uppercase"><?php echo $xCiubene; ?></td>
                                    <td><?php echo $xBeneficiario; ?></td>
                                    <td><?php echo $xPareben; ?></td>
                                    <td id="td_<?php echo $xBeneid; ?>">
                                        <div class="<?php echo $xTextColor; ?>">
                                            <?php echo $xEstadoBen; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input <?php echo $xCheking; ?> class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk<?php echo $xBeneid; ?>" 
                                            onchange="f_UpdateEstado(<?php echo $xBeneid; ?>,<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xUsuaid; ?>)" value=""/>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <div class="btn-group">	
                                                <button type="button" id="btnEditarBe_<?php echo $xBeneid; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditarBe" <?php echo $xDisabledEdit;?> title="Editar Beneficiario" data-bs-toggle="tooltip" data-bs-placement="left" >
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" id="btnAgendar_<?php echo $xBeneid; ?>" name="btnAgendar" onclick="f_Agendar(<?php echo $xBeneid; ?>)" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" <?php echo $xDisabledEdit;?> title='Agendar' data-bs-toggle="tooltip" data-bs-placement="left">
                                                    <i class="fa fa-user-plus"></i>
                                                </button>  
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>      
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>
<!--Modal Editar Titular -->
<div class="modal fade" id="modal_persona" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-900px">
        <div class="modal-content"> 
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Editar Titular</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body py-lg-5 px-lg-10 mt-n3">
                <div class="card mb-1 mb-xl-1">
                    <div class="card-header border-0">
                        <div class="d-flex align-items-center collapsible py-3 toggle collapsed mb-0" data-bs-toggle="collapse" data-bs-target="#view_avatar">
                            <div class="btn btn-sm btn-icon mw-20px btn-active-color-primary me-5">
                                <span class="svg-icon toggle-on svg-icon-primary svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                                        <rect x="6.0104" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>
                                <span class="svg-icon toggle-off svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                                        <rect x="10.8891" y="17.8033" width="12" height="2" rx="1" transform="rotate(-90 10.8891 17.8033)" fill="currentColor" />
                                        <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>
                            </div>
                            <h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">Avatar</h4>
                        </div>
                    </div>
                    <div id="view_avatar" class="collapse fs-6 ms-1">
                        <div class="card card-flush py-2">
                            <div class="card-body pt-0">
                                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('img/account.png')">
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url(img/account.png);" id="imgfile"></div>
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Cambiar Avatar">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" name="avatar" id="imgavatar" accept=".png, .jpg, .jpeg" />
                                        <input type="hidden" name="avatar_remove" />
                                    </label>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancelar Logo">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>													
                                </div>
                                <div class="form-text">Archivos permitidos: png, jpg, jpeg.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-1 mb-xl-1">
                    <div class="card-header border-0">
                        <div class="d-flex align-items-center collapsible py-3 toggle mb-0" data-bs-toggle="collapse" data-bs-target="#view_datos_titular">														<!--begin::Icon-->
                            <div class="btn btn-sm btn-icon mw-20px btn-active-color-primary me-5">
                                <span class="svg-icon toggle-on svg-icon-primary svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                                        <rect x="6.0104" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>
                                <span class="svg-icon toggle-off svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                                        <rect x="10.8891" y="17.8033" width="12" height="2" rx="1" transform="rotate(-90 10.8891 17.8033)" fill="currentColor" />
                                        <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>
                            </div>
                            <h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">Ddatos Titular</h4>
                        </div>
                    </div>
                    <div id="view_datos_titular" class="collapse show fs-6 ms-1">
                        <div class="card card-flush py-1">
                            <div class="card-body pt-0">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="required form-label">Nombres</label>
                                        <input type="text" class="form-control mb-1" id="txtNombre" name="txtNombre" minlength="5" maxlength="100"  value="" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="required form-label">Apellidos</label>
                                        <input type="text" class="form-control mb-1" id="txtApellido" name="txtApellido" minlength="5" maxlength="100" value="" />
                                    </div>
                                </div>
                                <div class="py-0" data-kt-customer-payment-method="row">
                                    <div class="py-3 d-flex flex-stack flex-wrap">
                                        <div class="d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#direccion_profesional" role="button" aria-expanded="false" aria-controls="direccion_profesional">
                                            <div class="me-3 rotate-90">
                                                <i class="fa fa-chevron-circle-right" style="color:#5AD1F1;" aria-hidden="true"></i>
                                            </div>
                                            <i class="fa fa-location-arrow fa-1x me-2" style="color:#F46D55;" aria-hidden="true"></i>
                                            <div class="me-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="text-gray-800 fw-bolder">Direccion</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="direccion_profesional" class="collapse fs-6 ps-12" data-bs-parent="#datos_profesional">
                                        <div class="row mb-4">
                                            <div class="col-md-12">
                                                <textarea class="form-control mb-2" id="txtDireccion" placeholder="Ingrese Direccion" style="text-transform: uppercase;" maxlength="250" rows="1" onkeydown="return(event.keyCode!=13);"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-0" data-kt-customer-payment-method="row">
                                    <div class="py-3 d-flex flex-stack flex-wrap">
                                        <div class="d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#telefono_profesional" role="button" aria-expanded="false" aria-controls="telefono_profesional">
                                            <div class="me-3 rotate-90">
                                                <i class="fa fa-chevron-circle-right" style="color:#5AD1F1;" aria-hidden="true"></i>
                                            </div>
                                            <i class="fa fa-phone fa-1x me-2" style="color:#7DF57D;" aria-hidden="true"></i>
                                            <div class="me-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="text-gray-800 fw-bolder">Telefonos</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="telefono_profesional" class="collapse fs-6 ps-10" data-bs-parent="#datos_profesional">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="fs-6 fw-bold mt-3 mb-3">Telefono 1</div>
                                                <input type="text" class="form-control" id="txtTelcasa"  maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" placeholder="" value=""/>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="fs-6 fw-bold mt-3 mb-3">Telefono 2</div>
                                                <input type="text" class="form-control" id="txtTelofi"  maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" placeholder="" value=""/>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="fs-6 fw-bold mt-3 mb-3">Celular</div>
                                                <input type="text" class="form-control" id="txtCel"  maxlength="10" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" placeholder="" value=""/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-0" data-kt-customer-payment-method="row">
                                    <div class="py-3 d-flex flex-stack flex-wrap">
                                        <div class="d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#email_profesional" role="button" aria-expanded="false" aria-controls="email_profesional">
                                            <div class="me-3 rotate-90">
                                                <i class="fa fa-chevron-circle-right" style="color:#5AD1F1;" aria-hidden="true"></i>
                                            </div>
                                            <i class="fa fa-envelope fa-1x me-2" style="color:#3B8CEC;" aria-hidden="true"></i>
                                            <div class="me-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="text-gray-800 fw-bolder">E-mail</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="email_profesional" class="collapse fs-6 ps-10" data-bs-parent="#datos_profesional">
                                        <div class="d-flex flex-wrap gap-5">
                                            <div class="fv-row w-100 flex-md-root">
                                                <input type="email" class="form-control text-lowercase" id="txtEmail"  minlength="5" maxlength="100" placeholder="ejemplo@gmail.com" value=""/>
                                            </div>                                                   
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger" data-bs-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i>Cerrar</button>
                <button type="button" id="btnSaveTit" class="btn btn-sm btn-light-primary"><i class="las la-pencil-alt"></i>Modificar</button>
            </div>
        </div>
    </div>
</div>
<!--Modal Editar Beneficiario -->
<div class="modal fade" id="modal_beneficiario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-900px">
       <div class="modal-content">
            <div class="modal-header">
                <h2>Editar Beneficiario</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
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
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="required form-label">Nombres</label>
                                <input type="text" class="form-control form-control-solid text-uppercase" id="txtNombreBeMo" name="txtNombre" minlength="5" maxlength="100"/>
                            </div>
                            <div class="col-md-6">
                                <label class="required form-label">Apellidos</label>
                                <input type="text" class="form-control form-control-solid text-uppercase" id="txtApellidoBeMo" name="txtApellido" minlength="5" maxlength="100" value=""/>
                            </div>
                        </div>
                        <div class="row mb-4">
                           <div class="col-md-12">
                                <label class="form-label">Direccion</label>
                                <textarea class="form-control mb-2" id="txtDireccionBeMo" placeholder="Ingrese Direccion" style="text-transform: uppercase;" maxlength="250" rows="1" onkeydown="return(event.keyCode!=13);"></textarea>
                           </div>
                        </div>
                        <div class="row mb-4">
                           <div class="col-md-6">
                               <label class="form-label">Telefono Casa</label>
                               <input type="text" class="form-control form-control-solid" id="txtTelcasaBeMo" maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" placeholder="Ingrese Telefono Casa" value=""/>
                           </div>
                           <div class="col-md-6">
                               <label class="form-label">Telefono Oficina</label>
                               <input type="text" class="form-control form-control-solid" id="txtTelofiBeMo" maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" placeholder="Ingrese Telefono Oficina" value=""/>
                            </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                               <label class="form-label">Telefono Celular</label>
                               <input type="text" class="form-control form-control-solid" id="txtCelularBeMo" maxlength="10" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" placeholder="Ingrese Celular" value=""/>
                           </div>
                           <div class="col-md-6">
                               <label class="form-label">Email</label>
                               <input type="email" class="form-control form-control-solid" id="txtEmailBeMo"  minlength="5" maxlength="100" placeholder="Ingrese Email" value=""/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnSaveBene" onclick="f_EditarBene(<?php echo $xUsuaid;?>,<?php echo $xPaisid; ?>,<?php echo $xEmprid;?>)"> 
                    <span class="indicator-label">Modificar</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--Modal Agregar Parentesco-Beneficiario -->
<div class="modal fade" id="modal_new_paren" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-700px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Nuevo Parentesco</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body py-lg-5 px-lg-10">
                <div class="card card-flush py-2">
                    <div class="card-body pt-0">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="required form-label">Detalle</label>
                                <input type="text" class="form-control" id="txtDetalle" name="txtDetalle" minlength="2" maxlength="80" placeholder="nombre del detalle" value="" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="required form-label">Valor Texto</label>
                                <input type="text" class="form-control" id="txtValorV" name="txtValorV" minlength="3" maxlength="3" placeholder="valor texto" value="" />
                            </div>
                        </div>
                     
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger" data-bs-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i>Cerrar</button>
                <button type="button" class="btn btn-sm btn-light-primary" id="btnGuardar" onclick="f_GuardarParen(<?php echo $xPacaid; ?>,<?php echo $xOrdenDet; ?>)"><i class="las la-save"></i>Grabar</button>
            </div>
        </div>
    </div>
</div>
<!--Modal Agregar Beneficiario -->
<div class="modal fade" id="modal_addbeneficiario" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Agregar Beneficiario</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body py-lg-5 px-lg-10">
                <div class="card mb-1 mb-xl-1">
                    <div class="card-header border-0">
                        <div class="d-flex align-items-center collapsible py-3 toggle mb-0" data-bs-toggle="collapse" data-bs-target="#view_datos_beneficiario">														<!--begin::Icon-->
                            <div class="btn btn-sm btn-icon mw-20px btn-active-color-primary me-5">
                                <span class="svg-icon toggle-on svg-icon-primary svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                                        <rect x="6.0104" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>
                                <span class="svg-icon toggle-off svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                                        <rect x="10.8891" y="17.8033" width="12" height="2" rx="1" transform="rotate(-90 10.8891 17.8033)" fill="currentColor" />
                                        <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>
                            </div>
                            <h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">Agregar Beneficiario</h4>
                        </div>
                    </div>
                    <div id="view_datos_beneficiario" class="collapse show fs-6 ms-1">
                        <div id="view_data" class="card card-flush py-1">
                            <div class="card-body pt-0">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="required form-label">Provincia</label>
                                        <select id="cboProvinciaBe" aria-label="Seleccione Provincia" data-control="select2" data-placeholder="Seleccione Provincia" data-dropdown-parent="#view_data" class="form-select mb-2" >
                                                <option></option>
                                                <?php foreach ($all_provincia as $prov) : ?>
                                                    <option value="<?php echo $prov['Descripcion'] ?>"><?php echo mb_strtoupper($prov['Descripcion']) ?></option>
                                                <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="required form-label">Ciudad</label>
                                        <select id="cboCiudadBe" aria-label="Seleccione Ciudad" data-control="select2" data-placeholder="Seleccione Ciudad" data-dropdown-parent="#view_data" class="form-select mb-2">
                                                <option></option>
                                        </select> 
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="required form-label">Nombres</label>
                                        <input type="text" class="form-control" id="txtAddNombreBe" value="" maxlength="80" placeholder="Ingrese Nombres" />   
                                    </div>
                                    <div class="col-md-4">
                                        <label class="required form-label">Apellidos</label>
                                        <input type="text" class="form-control" id="txtAddApellidoBe" value=""  maxlength="80" placeholder="Ingrese Apellidos" />  
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Fecha de Nacimiento</label>
                                        <input type="date" id="txtAddFechaNacimientoBe" class="form-control" value="" />   
                                    </div>
                                </div>
                                <div class="py-0" data-kt-customer-payment-method="row">
                                    <div class="py-3 d-flex flex-stack flex-wrap">
                                        <div class="d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#direccion_beneficiario" role="button" aria-expanded="false" aria-controls="direccion_profesional">
                                            <div class="me-3 rotate-90">
                                                <i class="fa fa-chevron-circle-right" style="color:#5AD1F1;" aria-hidden="true"></i>
                                            </div>
                                            <i class="fa fa-location-arrow fa-1x me-2" style="color:#F46D55;" aria-hidden="true"></i>
                                            <div class="me-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="text-gray-800 fw-bolder">Direccion</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="direccion_beneficiario" class="collapse fs-6 ps-12" data-bs-parent="#datos_profesional">
                                        <div class="row mb-4">
                                            <div class="col-md-12">
                                                <textarea class="form-control mb-2" id="txtAddDireccionBe" style="text-transform: uppercase;" rows="1" onkeydown="return(event.keyCode!=13);"></textarea> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-0" data-kt-customer-payment-method="row">
                                    <div class="py-3 d-flex flex-stack flex-wrap">
                                        <div class="d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#telefono_beneficiario" role="button" aria-expanded="false" aria-controls="telefono_profesional">
                                            <div class="me-3 rotate-90">
                                                <i class="fa fa-chevron-circle-right" style="color:#5AD1F1;" aria-hidden="true"></i>
                                            </div>
                                            <i class="fa fa-phone fa-1x me-2" style="color:#7DF57D;" aria-hidden="true"></i>
                                            <div class="me-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="text-gray-800 fw-bolder">Telefonos</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="telefono_beneficiario" class="collapse fs-6 ps-10" data-bs-parent="#datos_profesional">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="fs-6 fw-bold mt-3 mb-3">Telefono 1</div>
                                                <input type="text" id="txtAddTelCasaBe" class="form-control" value="" placeholder="022222222" maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;"/>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="fs-6 fw-bold mt-3 mb-3">Telefono 2</div>
                                                <input type="text" id="txtAddTelOfiBe" class="form-control" value="" placeholder="022222222" maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;"/>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="fs-6 fw-bold mt-3 mb-3">Celular</div>
                                                <input type="text" id="txtAddCelularBe" class="form-control" value="" placeholder="0999999999" maxlength="10" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-0" data-kt-customer-payment-method="row">
                                    <div class="py-3 d-flex flex-stack flex-wrap">
                                        <div class="d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#email_beneficiario" role="button" aria-expanded="false" aria-controls="email_profesional">
                                            <div class="me-3 rotate-90">
                                                <i class="fa fa-chevron-circle-right" style="color:#5AD1F1;" aria-hidden="true"></i>
                                            </div>
                                            <i class="fa fa-envelope fa-1x me-2" style="color:#3B8CEC;" aria-hidden="true"></i>
                                            <div class="me-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="text-gray-800 fw-bolder">E-mail</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="email_beneficiario" class="collapse fs-6 ps-10" data-bs-parent="#datos_profesional">
                                        <div class="d-flex flex-wrap gap-5">
                                            <div class="fv-row w-100 flex-md-root">
                                                <input type="email" id="txtAddEmailBe" class="form-control text-lowercase" value="" placeholder="micorreo@gmail.com" maxlength="80" />
                                            </div>                                                   
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger" data-bs-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i>Cerrar</button>
                <button type="button" id="btnAgregar" class="btn btn-sm btn-light-primary"><i class="las la-save"></i>Grabar</button>
            </div>
        </div>
    </div>

</div>

<script>

    var _tituid='<?php echo $tituid; ?>',_prodid='<?php echo $prodid; ?>',_grupid='<?php echo $grupid; ?>',_paisid = '<?php echo $xPaisid; ?>',_emprid = '<?php echo $xEmprid; ?>', 
    _usuaid='<?php echo $xUsuaid ; ?>',_clieid = '<?php echo $clieid; ?>';

    $(document).ready(function(){

        _avatartitu = '<?php echo $xImagen; ?>';

        document.getElementById('imgfiletitular').style.backgroundImage="url(assets/images/persons/" + _avatartitu + ")";

        var _mensaje = $('input#mensaje').val();

        if(_mensaje != ''){
            mensajesalertify(_mensaje,"S","top-center",3); 
        }

        $('#cboProvinciaBe').change(function(){
                        
            _cboid = $(this).val(); //obtener el id seleccionado
            
            $("#cboCiudadBe").empty();


            var _parametros = {
                xxPaisid: _paisid,
                xxEmprid: _emprid,
                xxComboid: _cboid,
                xxOpcion: 0
            }

            var xrespuesta = $.post("codephp/cargar_combos.php", _parametros);
                xrespuesta.done(function(response) {
            
                $("#cboCiudadBe").html(response);
                
            });
            xrespuesta.fail(function() {
                
            });
            xrespuesta.always(function() {
                
            });                
        
        });

    });

    $("#btnNewParen").click(function(){

      $("#modal_new_paren").modal("show");
    });


    $("#modal_new_paren").draggable({
       handle: ".modal-header"
    });


     // Desplazar Modal
    $("#modal_beneficiario").draggable({
        handle: ".modal-header"
    });

    $("#modal_persona").draggable({
       handle: ".modal-header"
    });

    $("#modal_addbeneficiario").draggable({
       handle: ".modal-header"
    });

    // Funcion de regreso de pagina 
    function f_Regresar(_clieid,_prodid,_grupid){

        $.redirect('?page=addtitular&menuid=<?php echo $menuid; ?>', {
            'idclie': _clieid,
            'idprod': _prodid,
            'idgrup': _grupid
        });
    
    }

        //Gravar Parentesco Modal

    function f_GuardarParen(_idpaca,_ordet){

        if($.trim($('#txtDetalle').val()) == '')
        {           
            mensajesalertify('Ingrese Detalle..!', 'W', 'top-right', 3);
            return false;          
        }

        if($.trim($('#txtValorV').val()) == '')
        {    
            mensajesalertify('Ingrese Valor Texto..!', 'W', 'top-right', 3);
            return false;
        }


        var _detalle = $.trim($('#txtDetalle').val());
        var _valorV =  $.trim($('#txtValorV').val());

        var _parametro ={
            "xxPacaid" : _idpaca,
            "xxDetalle" : _detalle,
            "xxValorV" : _valorV,
        }

        var xrespuesta = $.post("codephp/consultar_newdetalle.php", _parametro);
        xrespuesta.done(function(response){


            if(response == 0){

                _ordet++;

                var _parametros ={
                    "xxPacaid" : _idpaca,
                    "xxPaisid" : _paisid,
                    "xxOrden" : _ordet,
                    "xxDetalle" : _detalle,
                    "xxValorV" : _valorV
                }

                var xrespuesta = $.post("codephp/grabar_newdetalle.php", _parametros);
                xrespuesta.done(function(response){

                    if(response.trim() != 'ERR'){

                        mensajesalertify('Nuevo Parentesco Agregado', 'S', 'top-center', 3); 

                        $("#txtDetalle").val("");
                        $("#txtValorV").val("");
                        $("#cboParentesco").empty();
                        $("#cboParentesco").html(response);      
                        $("#modal_new_paren").modal("hide");

                    }

                });

            }else{

                mensajesalertify('Parentesco ya Existe y/o Valor texto', 'W', 'top-right', 3);
                $("#txtDetalle").val("");
                $("#txtValorV").val("");
            }

        });
    }

    //Agregar beneficiario modal
    $('#btnAgregarbene').click(function(){

        $("#modal_addbeneficiario").find('input,textarea').val('').end();
        $("#cboParentesco").val('').change();
        $("#cboAddDocumentoBe").val('').change();
        $("#cboAddGeneroBe").val('').change();
        $("#cboAddGeneroBe").val('').change();
        $("#cboAddEstadoCivilBe").val('').change();
        $("#cboProvinciaBe").val('').change();
        $("#cboCiudadBe").val(0).change();

        $("#modal_addbeneficiario").modal("show");

    });

    // Modal editar titular
   $(document).on("click",".btnEditarPer",function(){

        $("#modal_persona").find("input").val('');

        _persid = $(this).attr("id");
        _persid = _persid.substring(13);
        _paisid = '<?php echo $xPaisid;?>';
        _emprid = '<?php echo $xEmprid;?>';

        $parametros = {
            xxPerid: _persid,
            xxPaisid: _paisid,
            xxEmprid: _emprid
        }

        $.ajax({
            url: "codephp/get_datospersona.php",
            type: "POST",
            dataType: "json",
            data: $parametros,          
            success: function(data){ 
           
                var _nombre = data[0]['Nombres'];
                var _apellido = data[0]['Apellidos'];
                _avatar = data[0]['Imagen'] == '' ? 'imaadd.png' : data[0]['Imagen'];
                var _direccion = data[0]['Direccion'];
                var _telcasa = data[0]['Telcasa'];
                var _telofi = data[0]['Telofi'];
                var _cel = data[0]['Cel'];
                var _email = data[0]['Email'];

                $("#txtNombre").val(_nombre);
                $("#txtApellido").val(_apellido);
                document.getElementById('imgfile').style.backgroundImage="url(assets/images/persons/" + _avatar + ")";
                $("#txtDireccion").val(_direccion);
                $("#txtTelcasa").val(_telcasa);
                $("#txtTelofi").val(_telofi);
                $("#txtCel").val(_cel);
                $("#txtEmail").val(_email);
                                                                                        
            },
            error: function (error){
                console.log(error);
            }                            
        }); 
        $("#modal_persona").modal("show");
    });

    // Guardar Editar Titular
    $('#btnSaveTit').click(function(e){

        var _nombre = $.trim($("#txtNombre").val());
        var _apellido = $.trim($("#txtApellido").val()); 
        var _direccion = $.trim($("#txtDireccion").val()); 
        var _telcasa = $.trim($("#txtTelcasa").val()); 
        var _telofi = $.trim($("#txtTelofi").val()); 
        var _celular = $.trim($("#txtCel").val()); 
        var _email = $.trim($("#txtEmail").val());
        var _selecc = 'NO';

        var _imgfile = document.getElementById("imgfile").style.backgroundImage;
        var _url = _imgfile.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
        var _pos = _url.trim().indexOf('.');
        var _ext = _url.trim().substr(_pos, 5);

        if(_ext.trim() != '.png' && _ext.trim() != '.jpg' && _ext.trim() != '.jpeg'){
            _selecc = 'SI';
        }                    

        if(_selecc == 'SI'){
            var _imagen = document.getElementById("imgavatar");
            var _file = _imagen.files[0];
            var _fullPath = document.getElementById('imgavatar').value;
            _ext = _fullPath.substring(_fullPath.length - 4);
            _ext = _ext.toLowerCase();   
        }

        if(_ext.trim() != '.png' && _ext.trim() != '.jpg' && _ext.trim() != 'jpeg'){
            mensajesweetalert("center","warning","El archivo seleccionado no es una Imagen..!",false,1800);
            return;
        }

        var form_data = new FormData();
        form_data.append('xxPersid', _persid);           
        form_data.append('xxPaisid', _paisid);
        form_data.append('xxEmprid', _emprid);
        form_data.append('xxUsuaid', _usuaid);
        form_data.append('xxNombre', _nombre);
        form_data.append('xxApellido', _apellido);
        form_data.append('xxDireccion', _direccion);
        form_data.append('xxTelcasa', _telcasa);
        form_data.append('xxTelofi', _telofi);
        form_data.append('xxCelular', _celular);
        form_data.append('xxEmail', _email);
        form_data.append('xxSelecc', _selecc);
        form_data.append('xxAvatar', _avatar);
        form_data.append('xxFile', _file);

        $.ajax({
            url:"codephp/update_titular.php",
            type: "post",
            data: form_data,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response){

                if($.trim(response)=='OK'){

                    $.redirect('?page=edittitular&menuid=<?php echo $menuid; ?>', 
                    { 
                      'idper': _persid,
                      'idtit': _tituid,
                      'idcli': _clieid,
                      'idpro': _prodid,
                      'idgru': _grupid

                    }); //POR METODO POST

                }                            
                                                     
            },								
            error: function (error){
                console.log(error);
            }
        });

    });


    // Modal editar beneficiario
    $(document).on("click",".btnEditarBe",function(){
      
        $("#modal_beneficiario").find("input").val("");

        _rowid = $(this).attr("id");
        _rowid = _rowid.substring(12);
        _paisid = '<?php echo $xPaisid;?>';
        _emprid = '<?php echo $xEmprid;?>';

        var xrespuesta = $.post("codephp/get_datosbeneficiario.php", { xxBeneid: _rowid,xxPaisid:_paisid,xxEmprid: _emprid});
        xrespuesta.done(function(response){

            var _datos = JSON.parse(response);

            _documento = _datos[0].Docu;
            _ciudadben = _datos[0].Ciudad;
            _perenben = _datos[0].Parentesco;
            _estadoben = _datos[0].Estado;

            $("#txtNombreBeMo").val(_datos[0].Nombres);
            $('#txtApellidoBeMo').val(_datos[0].Apellidos);
            $('#txtDireccionBeMo').val(_datos[0].Direccion);
            $('#txtTelcasaBeMo').val(_datos[0].Telcasa);
            $('#txtTelofiBeMo').val(_datos[0].Telofi);
            $('#txtCelularBeMo').val(_datos[0].Celular);
            $('#txtEmailBeMo').val(_datos[0].Email);

            $("#modal_beneficiario").modal("show");

        });
    });

    // Guardar Editar Beneficiario

    function f_EditarBene(_usuaid,_paisid,_emprid){

        var _output;
        var _beneid = _rowid;
        var _nombrebe= $.trim($("#txtNombreBeMo").val());
        var _apellidobe= $.trim($("#txtApellidoBeMo").val());
        var _nombrescombe = _nombrebe.toUpperCase() + ' ' + _apellidobe.toUpperCase();
        var _direccionbe = $.trim($("#txtDireccionBeMo").val());
        var _telcasabe = $.trim($("#txtTelcasaBeMo").val());
        var _telofibe = $.trim($("#txtTelofiBeMo").val()); 
        var _celularbe = $.trim($("#txtCelularBeMo").val()); 
        var _emailbe = $.trim($("#txtEmailBeMo").val());

        if(_nombrebe == ''){
            mensajesalertify("Ingrese Nombre..!!","W","top-right",3);
            return false;
        }

        if(_apellidobe == ''){
            mensajesalertify("Ingrese Apellido..!!","W","top-right",3);
            return false;
        }


        var _parametros = {
            "xxBeneid" : _beneid,
            "xxUsuaid" : _usuaid,
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxNombre" : _nombrebe,
            "xxApellido" : _apellidobe,
            "xxDireccion" : _direccionbe,
            "xxTelcasa" : _telcasabe,
            "xxTelofi" : _telofibe,
            "xxCelular" : _celularbe,
            "xxEmail" : _emailbe       
        }

        var xrespuesta = $.post("codephp/update_beneficiario.php", _parametros);
        xrespuesta.done(function(response){
         
            if(response.trim() == 'OK'){

                _output ='<td class="text-uppercase">' + _ciudadben + '</td>';
                _output +='<td>' +_nombrescombe + '</td>';
                _output +='<td>' +_documento + '</td>';
                _output +='<td>' +_perenben + '</td>';
                _output +='<td id="td_'+ _beneid + '"><div class="badge badge-light-primary">ACTIVO</div></td>';
                _output +='<td><div class="form-check form-check-sm form-check-custom form-check-solid">';
                _output +='<input checked="checked" class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk'+ _beneid +'" ';
                _output +='onchange="f_UpdateEstado('+ _beneid + ',' + _paisid + ',' + _emprid + ',' + _usuaid + ')" value=""/></div></td>';
                _output +='<td><div class="text-center"><div class="btn-group">';
                _output +='<button type="button" id="btnEditarBe_' + _beneid + '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditarBe" title="Editar Producto" data-bs-toggle="tooltip" data-bs-placement="left">';
                _output +='<i class="fa fa-edit"></i></button>';
                _output +='<button type="button" id="btnAgendar_' + _beneid + '" name="btnAgendar" onclick="f_Agendar('+ _beneid + ')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" ';
                _output +='title="Agendar" data-bs-toggle="tooltip" data-bs-placement="left"><i class="fa fa-user-plus"></i></button></div></div></td>';
              
                $('#row_' + _beneid + '').html(_output);

                $("#modal_beneficiario").modal("hide");
            }
            
        });
    } 

    //Agregar Nuevo Beneficiario directo a la BDD
    
    $('#btnAgregar').click(function(){

        var _cboAddDocumentoBe = $('#cboAddDocumentoBe').val();
        var _txtAddDocumentoBe = $('#txtAddDocumentoBe').val();
        var _txtAddNombreBe = $.trim($("#txtAddNombreBe").val());
        var _txtAddApellidoBe =  $.trim($('#txtAddApellidoBe').val());
        var _txtAddnombresCompletos =  _txtAddNombreBe.toUpperCase() + ' ' + _txtAddApellidoBe.toUpperCase();
        var _cboAddGeneroBe = $('#cboAddGeneroBe').val();
        var _cboAddEstadoCivilBe = $('#cboAddEstadoCivilBe').val();
        var _cboProvinciaBe = $('#cboProvinciaBe').val();
        var _cboCiudadBe = $('#cboCiudadBe').val();
        var _txtCiudadBe = $('#cboCiudadBe').find('option:selected').text();
            _txtCiudadBe.toUpperCase();
        var _txtAddDireccionBe =  $.trim($('#txtAddDireccionBe').val());
        var _txtAddTelCasaBe = $('#txtAddTelCasaBe').val();
        var _txtAddTelOfiBe = $('#txtAddTelOfiBe').val();
        var _txtAddTelCelularBe = $('#txtAddCelularBe').val();
        var _txtAddEmailBe =  $.trim($('#txtAddEmailBe').val());
        var _cboParentesco = $('#cboParentesco').val();
        var _txtParentesco = $('#cboParentesco').find('option:selected').text();
            _txtParentesco.toUpperCase();
        var _fechaAddNacimientoBe = $('#txtAddFechaNacimientoBe').val();

        if(_cboParentesco == ''){
            mensajesalertify("Seleccione Parentesco..!", "W", "top-right", 3);
            return; 
        }

        if(_cboAddDocumentoBe == ''){
            mensajesalertify("Seleccione Tipo Documento..!", "W", "top-right", 3);
            return; 
        }

        if(_txtAddDocumentoBe == ''){
            mensajesalertify("Ingrese Numero de Documento..!", "W", "top-right", 3);
            return; 
        }

        if(_txtAddDocumentoBe.length < 10){
            mensajesalertify("Documento Incorrecto..!", "W", "top-right", 3);
            return; 
        }

        if(_cboAddGeneroBe == ''){
            mensajesalertify("Seleccione Genero..!", "W", "top-right", 3);
            return; 
        }


        if(_txtAddNombreBe == ''){
            mensajesalertify("Ingrese Nombre..!", "W", "top-right", 3);
            return; 
        }

        if(_txtAddApellidoBe == ''){
            mensajesalertify("Ingrese Apellido..!", "W", "top-right", 3);
            return; 
        }


        if(_cboProvinciaBe == ''){
            mensajesalertify("Seleccione Provincia..!!","W","top-right",3);
            return false;
        }

        if(_cboCiudadBe == 0){
            mensajesalertify("Seleccione Ciudad..!!","W","top-right",3);
            return false;
        }

        if(_txtAddTelCelularBe != '')
        {
            _valor = document.getElementById("txtAddCelularBe").value;
            if( !(/^\d{10}$/.test(_valor)) ) {
                mensajesalertify("Celular incorrecto..!" ,"W", "top-right", 3); 
                return;
            }
        }
        
        if(_txtAddEmailBe != ''){
            var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
            if (regex.test(_txtAddEmailBe.trim())){
            }else{
                mensajesalertify("Email Incorrecto..!!","W","top-right",3);
                return false;
            }  
        }


        var _parametro = {
            
            xxProdid: _prodid,
            xxPaisid: _paisid,
            xxEmprid: _emprid,
            xxDocumento: _txtAddDocumentoBe
        }

        var xrespuesta = $.post("codephp/consultar_beneficiario.php", _parametro);
        xrespuesta.done(function(response){

            if(response == 0){
                var _parametros ={

                    "xxTituid" : _tituid,
                    "xxPaisid" : _paisid,
                    "xxEmprid" : _emprid,
                    "xxProdid" : _prodid,
                    "xxUsuaid" : _usuaid,
                    "xxTipodocu" : _cboAddDocumentoBe,
                    "xxDocumento" : _txtAddDocumentoBe,
                    "xxNombres" : _txtAddNombreBe,
                    "xxApellidos" : _txtAddApellidoBe,
                    "xxGenero" : _cboAddGeneroBe,
                    "xxEstadocicvil" : _cboAddEstadoCivilBe,
                    "xxCiudad" : _cboCiudadBe,
                    "xxDireccion" : _txtAddDireccionBe,
                    "xxTelcasa" : _txtAddTelCasaBe,
                    "xxTelofi" : _txtAddTelOfiBe,
                    "xxCelular" : _txtAddTelCelularBe,
                    "xxEmail" : _txtAddEmailBe,
                    "xxParentesco" : _cboParentesco,
                    "xxFechanaci" : _fechaAddNacimientoBe
                }
                
                var xrespuesta = $.post("codephp/grabar_newbeneficiario.php", _parametros);
                xrespuesta.done(function(response){

                    if(response != 0){

                        _id = response;
                            
                        _output = '<tr id="row_' + _id + '">';
                        _output +='<td class="text-uppercase">' + _txtCiudadBe + '</td>';
                        _output +='<td>' +_txtAddnombresCompletos + '</td>';
                        _output +='<td>' +_txtAddDocumentoBe + '</td>';
                        _output +='<td>' +_txtParentesco + '</td>';
                        _output +='<td id="td_'+ _id + '"><div class="badge badge-light-primary">ACTIVO</div></td>';
                        _output +='<td><div class="form-check form-check-sm form-check-custom form-check-solid">';
                        _output +='<input checked="checked" class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk'+ _id +'" ';
                        _output +='onchange="f_UpdateEstado('+ _id + ',' + _paisid + ',' + _emprid + ',' + _usuaid + ')" value=""/></div></td>';
                        _output +='<td><div class="text-center"><div class="btn-group">';
                        _output +='<button type="button" id="btnEditarBe_' + _id + '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditarBe" title="Editar Producto" data-bs-toggle="tooltip" data-bs-placement="left">';
                        _output +='<i class="fa fa-edit"></i></button>';
                        _output +='<button type="button" id="btnAgendar_' + _id + '" name="btnAgendar" onclick="f_Agendar('+ _id + ')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" ';
                        _output +='title="Agendar" data-bs-toggle="tooltip" data-bs-placement="left"><i class="fa fa-user-plus"></i></button></div></div></td>';
                        _output +='</tr>';

                        $('#tblBeneficiario').append(_output);
                        //mensajesalertify('Agregado Correctamente..!', 'S', 'top-center', 3)

                        //console.log(_output);

                        $("#modal_addbeneficiario").modal("hide");
                        mensajesweetalert('top-center','success','Agregado Correctamente',false,3000);
                    }


                });

            }else{
                mensajesalertify("Beneficiario ya Existe..!!","W","top-right",3);
                return false;
            }

        });
    });

    //Update Estado Beneficiario 
    function f_UpdateEstado(_beneid,_paisid,_emprid,_usuaid){

        var _check = $("#chk" + _beneid).is(":checked");
        var _checked = "";
        var _class = "badge badge-light-primary";
        var _td = "td_" + _beneid;
        var _btnedit = "btnEditarBe_" + _beneid;
        var _btnagen = "btnAgendar_" + _beneid;

        if(_check){
            var _estado = 'ACTIVO';
            _checked = "checked='checked'";
            $('#'+_btnedit).prop("disabled",false);
            $('#'+_btnagen).prop("disabled",false);
                
        }else{
            _estado = 'INACTIVO';
            _class = "badge badge-light-danger";
            $('#'+_btnedit).prop("disabled",true);
            $('#'+_btnagen).prop("disabled",true);
        }

        var _changetd = document.getElementById(_td);
            _changetd.innerHTML = '<div class="' + _class + '">' + _estado + ' </div>';

            var _parametros = {
                "xxBeneid" : _beneid,
                "xxPaisid" : _paisid,
                "xxEmprid" : _emprid,
                "xxUsuaid" : _usuaid,
                "xxEstado" : _estado
            } 

            var xrespuesta = $.post("codephp/update_estadobeneficiario.php", _parametros);
                xrespuesta.done(function(response){
            });	

    }

    //Redireccionar Agendar Beneficiarios
    function f_Agendar(_beneid){
     $.redirect('?page=adminagenda&menuid=<?php echo $menuid; ?>', { 'tituid': _beneid, 'prodid': _prodid, 'grupid': _grupid });
    }  

</script>