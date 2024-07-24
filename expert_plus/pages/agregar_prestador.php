<?php
	
	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	    	

	require_once("dbcon/config.php");
	require_once("dbcon/functions.php");

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

	$xSQL = "SELECT DISTINCT provincia AS Descripcion FROM `provincia_ciudad` ";
	$xSQL .= "WHERE pais_id=$xPaisid AND estado='A' ORDER BY provincia ";
    $all_provincia = mysqli_query($con, $xSQL);

    $xSQL = "SELECT pde.pade_nombre AS Nombre, pde.pade_valorV AS Valor, CASE pde.pade_estado WHEN 'A' THEN 'Activo' ELSE 'Inactivo' END AS Estado FROM `expert_parametro_detalle` pde, `expert_parametro_cabecera` pca ";
    $xSQL .= "WHERE pde.paca_id=pca.paca_id AND pca.paca_nombre='Tipo Prestador' AND pca.paca_estado='A' AND pais_id=$xPaisid AND empr_id=$xEmprid ";
    $xSQL .= "ORDER BY pde.pade_orden ";
    $all_tipopresta = mysqli_query($con, $xSQL);
    
    //file_put_contents('log_seguimiento.txt', $xSQL . "\n\n", FILE_APPEND);

?>

<div id="kt_content_container" class="container-xxl">
    <form id="kt_ecommerce_add_product_form" class="form d-flex flex-column flex-lg-row" data-kt-redirect="../../demo1/dist/apps/ecommerce/catalog/products.html">
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="d-flex align-items-center collapsible py-3 toggle mb-0" data-bs-toggle="collapse" data-bs-target="#view_logo">														<!--begin::Icon-->
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
                        <h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">Logo</h4>
                    </div>
                </div>
                <div id="view_logo" class="collapse show fs-6 ms-1">
                    <div class="card-body text-center pt-0">
                        <div class="image-input image-input-empty image-input-outline mb-3" data-kt-image-input="true">
                            <div class="image-input-wrapper w-150px h-150px" style="background-image: url(assets/images/prestadores/logo.png);" id="imgfile"></div>
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Cargar Logo">
                                <i class="bi bi-pencil-fill fs-7"></i>
                                <input type="file" name="imglogo" id="imglogo" accept=".png, .jpg, .jpeg" />
                                <input type="hidden" name="avatar_remove" />
                            </label>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancelar Logo">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                        </div>
                        <div class="text-muted fs-7">Imagenes aceptadas (*jpg,*.png y *.jpeg) </div>
                    </div>
                </div>
            </div>
            <div class="card card-flush py-4">
                <div class="card-header">
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
                    <div class="card-body pt-0">
                        <div class="d-grid gap-2">
                            <button type="button" id="btnModalAsis" class="btn btn-light-primary btn-sm border border-primary"><i class="fa fa-users me-1" aria-hidden="true"></i>                                                          
                                Nuevo Tipo Asistencia
                            </button> 
                        </div>                          
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-n2">
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_add_product_general">
                       <i class="fa fa-tasks fa-1x me-2" aria-hidden="true"></i>  
                        Datos Generales
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_add_product_advanced">
                        <i class="fa fa-user fa-1x me-2" aria-hidden="true"></i>
                        Servicios Prestador
                    </a>
                </li>
                <a href="?page=prestador_admin&menuid=<?php echo $menuid;?>" class="btn btn-icon btn-light-primary btn-sm ms-auto me-lg-n7" title="Regresar" data-bs-toggle="tooltip" data-bs-placement="left">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </a>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="kt_ecommerce_add_product_general" role="tab-panel">
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2 class="fw-normal">Datos Prestador</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                                    <div class="col">
                                        <div class="fv-row mb-7">
                                            <label class="fs-6 fw-bold form-label mt-3">
                                                <span class="required">Provincia</span>
                                                <!-- <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Enter the contact's email."></i> -->
                                            </label>
                                            <select name="cboProvincia" id="cboProvincia" aria-label="Seleccione Provincia" data-control="select2" data-placeholder="Seleccione Provincia" data-dropdown-parent="#kt_ecommerce_add_product_general" class="form-select mb-2" >
                                                <option></option>
                                                <?php foreach ($all_provincia as $prov) : ?>
                                                    <option value="<?php echo $prov['Descripcion'] ?>"><?php echo mb_strtoupper($prov['Descripcion']) ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="fv-row mb-7">
                                            <label class="fs-6 fw-bold form-label mt-3">
                                                <span class="required">Ciudad</span>
                                            </label>
                                            <select name="cboCiudad" id="cboCiudad" aria-label="Seleccione Ciudad" data-control="select2" data-placeholder="Seleccione Ciudad" data-dropdown-parent="#kt_ecommerce_add_product_general" class="form-select mb-2">
                                                <option></option>
                                            </select>                                                      
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-5 fv-row">
                                    <label class="required form-label">Prestador</label>
                                    <input type="text" name="txtPrestador" id="txtPrestador" class="form-control mb-2 text-uppercase" maxlength="150" placeholder="Nombre del Prestador" value="" />
                                    <div class="text-muted fs-7">El Prestador puede ser Clinica/Centro Medico/Estudio/Consultorio/Otros..</div>
                                </div>   
                                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-0 row-cols-lg-2 mb-2">
                                    <div class="col">
                                        <div class="fv-row mb-0">
                                            <label class="fs-6 fw-bold form-label mt-3">
                                                <span class="required">Sector</span>
                                                <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Ubicacion geografica del prestador"></i>
                                            </label>
                                            <select name="cboSector" id="cboSector" aria-label="Seleccione Sector" data-control="select2" data-placeholder="Seleccione Sector" data-dropdown-parent="#kt_ecommerce_add_product_general" class="form-select mb-2">
                                                <option></option>
                                                <?php 
                                                $xSQL = "SELECT pde.pade_valorV AS Codigo,pde.pade_nombre AS Descripcion FROM `expert_parametro_detalle` pde,`expert_parametro_cabecera` pca WHERE pca.pais_id=$xPaisid ";
                                                $xSQL .= "AND pca.paca_nombre='Tipo Sector' AND pca.paca_id=pde.paca_id AND pca.paca_estado='A' AND pade_estado='A' ";
                                                $all_datos =  mysqli_query($con, $xSQL);
                                                foreach ($all_datos as $datos){ ?>
                                                    <option value="<?php echo $datos['Codigo'] ?>"><?php echo mb_strtoupper($datos['Descripcion']); ?></option>
                                                <?php } ?>
                                            </select>                                                      
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="fv-row mb-0">
                                            <label class="fs-6 fw-bold form-label mt-3">
                                                <span class="required">Tipo Prestador</span>
                                                <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Definicion del prestador"></i>
                                            </label>
                                            <select name="cboTipo" id="cboTipo" aria-label="Seleccione Tipo Prestador" data-control="select2" data-placeholder="Seleccione Tipo Prestador" data-dropdown-parent="#kt_ecommerce_add_product_general" class="form-select mb-2">
                                                <option></option>
                                                <?php 
                                                $xSQL = "SELECT pde.pade_valorV AS Codigo,pde.pade_nombre AS Descripcion FROM `expert_parametro_detalle` pde,`expert_parametro_cabecera` pca WHERE pca.pais_id=$xPaisid ";
                                                $xSQL .= "AND pca.paca_nombre='Tipo Prestador' AND pca.paca_id=pde.paca_id AND pca.paca_estado='A' AND pade_estado='A' ";
                                                $all_datos =  mysqli_query($con, $xSQL);
                                                foreach ($all_datos as $datos){ ?>
                                                    <option value="<?php echo $datos['Codigo'] ?>"><?php echo mb_strtoupper($datos['Descripcion']) ?></option>
                                                <?php } ?>                                                        
                                            </select>                                                      
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2 fv-row">
                                    <label class="form-label">Direccion</label>
                                    <textarea class="form-control mb-2 text-uppercase" name="txtDireccion" id="txtDireccion" maxlength="250" onkeydown="return (event.keyCode!=13);"></textarea>
                                </div>
                                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-0 row-cols-lg-2 mb-2">
                                    <div class="col">
                                        <div class="fv-row mb-0">
                                            <label class="fs-6 fw-bold form-label mt-3">
                                                <i class="fa fa-map-marker fa-1x me-2" style="color:#F46D55;" aria-hidden="true"></i>
                                                <span class="">Ubicacion Prestador</span>   
                                            </label>
                                            <textarea class="form-control mb-2 text-uppercase" name="txtUbi" id="txtUbi" rows="3" maxlength="250" onkeydown="return (event.keyCode!=13);"></textarea>   
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="fv-row mb-0">
                                            <label class="fs-6 fw-bold form-label mt-3">
                                                <i class="fa fa-globe fa-1x me-2" style="color:#5AD1F1;" aria-hidden="true"></i>
                                                <span class="">Pagina Web Prestador</span>   
                                            </label>
                                            <input type="text" class="form-control mb-2 text-lowercase" name="txtUrl" id="txtUrl" maxlength="150" placeHolder="https://wwww.prestador.com" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="fs-6 fw-bold form-label mt-3">
                                            <i class="fa fa-phone fa-1x me-2" style="color:#7DF57D;" aria-hidden="true"></i>
                                            <span class="">Telefono 1</span>   
                                        </label>
                                        <input type="text" class="form-control mb-2 w-150px" name="txtFono1" id="txtFono1" maxlength="9" placeholder="022222222" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="fs-6 fw-bold form-label mt-3">
                                            <i class="fa fa-phone fa-1x me-2" style="color:#7DF57D;" aria-hidden="true"></i>
                                            <span class="">Telefono 2</span>   
                                        </label>
                                        <input type="text" class="form-control mb-2 w-150px" name="txtFono2" id="txtFono2" maxlength="9" placeholder="" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="fs-6 fw-bold form-label mt-3">
                                            <i class="fa fa-mobile fa-1x me-2" style="color:#5AD1F1;" aria-hidden="true"></i>
                                            <span class="">Celular</span>   
                                        </label>
                                        <input type="text" class="form-control mb-2 w-150px" name="txtCelular1" id="txtCelular1" maxlength="10" placeholder="0999999999" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="" />
                                    </div>
                                </div>
                                <div class="d-flex align-items-center collapsible py-3 toggle collapsed mb-0" data-bs-toggle="collapse" data-bs-target="#view_emails">
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
                                    <label class="fs-6 fw-bold form-label mt-3">Emails</label>  
                                </div>
                                <div id="view_emails" class="collapse fs-6 ms-1">
                                    <div class="row mb-2">
                                        <div class="col-md-8">
                                            <label class="form-label">Email 1</label>
                                            <input type="email" name="txtEmail1" id="txtEmail1" maxlength="150" placeholder="micorre@dominio.com" class="form-control mb-2 text-lowercase" value="" />
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-check form-switch form-check-custom form-check-solid mt-5">
                                                <input class="form-check-input mt-5" name="chkEnviar1" id="chkEnviar1" type="checkbox" />
                                                <span id="spanEnv1" class="form-check-label fw-bold text-muted mt-3" for="chkEnviar1">No Enviar</span>
                                            </label>    
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <label class="form-label">Email 2</label>
                                            <input type="email" name="txtEmail2" id="txtEmail2" maxlength="150" placeholder="" class="form-control mb-2 text-lowercase" value="" />   
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-check form-switch form-check-custom form-check-solid mt-5">
                                                <input class="form-check-input mt-5" name="chkEnviar2" id="chkEnviar2" type="checkbox" />
                                                <span id="spanEnv2" class="form-check-label fw-bold text-muted mt-3" for="chkEnviar2">No Enviar</span>
                                            </label>    
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <label class="form-label">Email 3</label>
                                            <input type="email" name="txtEmail3" id="txtEmail3" maxlength="150" placeholder="" class="form-control mb-2 text-lowercase" value="" />   
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-check form-switch form-check-custom form-check-solid mt-5">
                                                <input class="form-check-input mt-5" name="chkEnviar3" id="chkEnviar3" type="checkbox" />
                                                <span id="spanEnv3" class="form-check-label fw-bold text-muted mt-3" for="chkEnviar3">No Enviar</span>
                                            </label>    
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-8">
                                            <label class="form-label">Email 4</label>
                                            <input type="email" name="txtEmail4" id="txtEmail4" maxlength="150" placeholder="" class="form-control mb-2 text-lowercase" value="" />   
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-check form-switch form-check-custom form-check-solid mt-5">
                                                <input class="form-check-input mt-5" name="chkEnviar4" id="chkEnviar4" type="checkbox" />
                                                <span id="spanEnv4" class="form-check-label fw-bold text-muted mt-3" for="chkEnviar4">No Enviar</span>
                                            </label>    
                                        </div>
                                    </div>
                                </div>                                      
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="kt_ecommerce_add_product_advanced" role="tab-panel">
                    <div class="d-flex flex-column gap-7">
                        <div class="form-group">
                            <button type="button" data-repeater-create="" class="btn btn-light-primary btn-sm border border-primary" id="btnAddServicio"><i class="fa fa-users me-1" aria-hidden="true"></i>
                                Agregar Tipo Servicio
                            </button>
                        </div>
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="fw-normal">Servicios Asignados</h3>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="d-flex justify-content-center flex-column gap-5">
                                    <table id="tblEspecialidad" class="table table-sm table-hover table-bordered">
                                        <thead>
                                            <tr class="text-start text-gray-800 fw-bolder fs-7 gs-0">
                                                <th style="display: none;">Id</th>
                                                <th>TIPO ASISTENCIA</th>
                                                <th>TIPO ATENCION</th>
                                                <th>RED</th>
                                                <th>PVP</th>
                                                <th>OPCIONES</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-bold text-gray-600">
                                        </tbody>
                                    </table>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="button" id="btnSave" class="btn btn-sm btn-primary"><i class="fa fa-hdd me-1"></i>Grabar</button>
            </div>
        </div>
    </form>
</div>
<!--Modal Nueva Asistencia -->
<div class="modal fade" id="modal_new_asistencia" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Nuevo Tipo Asistencia</h2>
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
                    <div class="card-body pt-0" id="kt_modal_new_card_form">
                        <div class="row mb-7">
                            <div class="col">
                                <label class="required form-label">Seleccione Asistencia</label>
                                <select name="cboAsistencia" id="cboAsistencia" aria-label="Seleccione Tipo" data-control="select2" data-placeholder="Seleccione Tipo" data-dropdown-parent="#kt_modal_new_card_form" class="form-select mb-2">
                                    <option></option>
                                    <?php 
                                    $xSQL = "SELECT pde.pade_valorV AS Codigo,pde.pade_nombre AS Descripcion FROM `expert_parametro_detalle` pde,`expert_parametro_cabecera` pca WHERE pca.pais_id=$xPaisid ";
                                    $xSQL .= "AND pca.paca_nombre='Asistencia' AND pca.paca_id=pde.paca_id AND pca.paca_estado='A' AND pade_estado='A' ";
                                    $all_datos =  mysqli_query($con, $xSQL);
                                    foreach ($all_datos as $datos){ ?>
                                        <option value="<?php echo $datos['Codigo'] ?>"><?php echo mb_strtoupper($datos['Descripcion']) ?></option>
                                    <?php } ?>
                                </select>
                            </div> 
                        </div>  
                        <div class="row mb-5">
                            <div class="col">
                                 <label class="required form-label">Nombre Tipo Asistencia</label>
                                 <input type="text" class="form-control text-uppercase" maxlength="300" placeholder="Ingrese Asistencia" name="txtTipoAsistencia" id="txtTipoAsistencia" />
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col">
                                <label class="form-label">Descripcion</label>
                                <textarea class="form-control text-uppercase" name="txtDescripcion" id="txtDescripcion" rows="2" maxlength="300" onkeydown="return (event.keyCode!=13);"></textarea>
                            </div>
                        </div>
                     
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger border border-danger" data-bs-dismiss="modal"><i class="fa fa-times me-1" aria-hidden="true"></i>Cerrar</button>
                <button type="button" id="btnSaveAsistencia" class="btn btn-sm btn-light-primary border border-primary"><i class="fa fa-hdd me-1"></i>Grabar</button>
            </div>
        </div>
    </div>
</div>  
<!-- <div class="modal fade" id="modal-new-tipoprestador" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Nuevo Tipo Prestador</h2>
                <i class="fa fa-window-close fa-2x" aria-hidden="true" data-bs-dismiss="modal"></i>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form id="kt_modal_new_card_form" class="form">
                    <div class="d-flex flex-column mb-7 fv-row">
                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span class="required">Tipo Prestador</span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Especifique el nombre de la especialidad"></i>
                        </label>
                        <input type="text" class="form-control mb-2" maxlength="150" placeholder="Tipo Prestador" name="txtTipoPrestador" id="txtTipoPrestador" />
                    </div>

                    <div class="d-flex flex-column mb-7 fv-row">
                        <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                            <span>Valor</span>
                        </label>
                        <input type="text" class="form-control mb-2" maxlength="100" placeholder="ValorV" name="txtValor" id="txtValor" />
                    </div>
                    <div class="d-flex flex-column mb-7 fv-row">
                        <div class="mb-10">
                            <div class="fs-6 fw-bold mb-2">Tipo Prestadores</div>
                            <div class="mh-300px scroll-y me-n7 pe-7">
                                <table id="tblTipoPrestador" class="table align-middle table-row-dashed fs-6 gy-5" style="width: 100%;">
                                    <thead>
                                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                            <th>Tipo Prestador</th>
                                            <th>Valor</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bold text-gray-600">
                                        <?php 
                                
                                            foreach($all_tipopresta as $presta){
                                                $xNombre = $presta['Nombre'];
                                                $xValor = $presta['Valor'];
                                                $xEstado = $presta['Estado'];
                                            ?>
                                                <?php                     
                                                    if($xEstado == 'Activo'){
                                                        $xTextColor = "badge badge-light-primary";
                                                    }else{
                                                        $xTextColor = "badge badge-light-danger";
                                                    }                    
                                                ?>
                                                <tr>
                                                    <td><?php echo $xNombre; ?></td>
                                                    <td><?php echo $xValor; ?></td>                                                            
                                                    <td>
                                                        <div class="<?php echo $xTextColor; ?>"><?php echo $xEstado; ?></div>
                                                    </td>                                                            
                                                </tr>
                                        <?php } ?>                                                  
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="text-center pt-15">
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-secondary">Cerrar</button>
                        <button type="button" id="btnSaveTipo" class="btn btn-primary">
                            <span class="indicator-label">Grabar</span>
                            <span class="indicator-progress">Espere un momento...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>   -->
<!--Modal Agregar Servicio -->
<div class="modal fade" id="agregar_servicio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Datos Nuevo Servicio</h2>
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
                <div class="card card-flush py-2">
                    <div class="card-body pt-0">
                        <div class="mb-5 fv-row">
                            <label class="required form-label">Tipo Asistencia</label>
                            <select name="cboAsis" id="cboAsis" aria-label="Seleccione Tipo Asistencia" data-control="select2" data-placeholder="Seleccione Tipo Asistencia" data-dropdown-parent="#kt_ecommerce_add_product_advanced" class="form-select mb-2">
                                <option></option>
                                <?php 
                                $xSQL = "SELECT asis_id AS Codigo,asis_nombre AS TipoAsistencia FROM `expert_tipo_asistencia` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND asis_estado='A' ";
                                $all_datos =  mysqli_query($con, $xSQL);
                                foreach ($all_datos as $datos){ ?>
                                    <option value="<?php echo $datos['Codigo'] ?>"><?php echo $datos['TipoAsistencia'] ?></option>
                                <?php } ?>                                                        
                            </select>                                             
                        </div>
                        <div class="row mb-5">
                            <div class="col">
                                <label class="required form-label">Tipo Atencion</label>
                                <textarea class="form-control text-uppercase" name="txtTipoAtencion" id="txtTipoAtencion" rows="2" maxlength="300" onkeydown="return (event.keyCode!=13);"></textarea>
                            </div>
                        </div>
                        <div class="mb-2 fv-row">
                            <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                                <div class="col">
                                    <label class="required form-label">Costo Red</label>
                                    <input type="text" name="txtRed" id="txtRed" class="form-control mb-2" placeholder="Red (0.00)" min="0" maxlength = "6" />
                                </div>
                                <div class="col">
                                    <label class="required form-label">Pvp</label>
                                    <input type="text" name="txtPvp" id="txtPvp" class="form-control mb-2" placeholder="Precio al Publico (0.00)" min="0" maxlength = "6" />
                                </div>    
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger border border-danger" data-bs-dismiss="modal"><i class="fa fa-times me-1" aria-hidden="true"></i>Cerrar</button>
                <button type="button" id="btnAgregar" class="btn btn-sm btn-light-primary border border-primary"><i class="fa fa-plus me-1"></i>Agregar</button>
            </div>
        </div>
    </div>
</div>        

<script>
    $(document).ready(function(){

        var _paisid = "<?php echo $xPaisid; ?>";
        var _emprid = "<?php echo $xEmprid; ?>";
        var _usuaid = "<?php echo $xUsuaid; ?>";
        _result = [];
        var _continuar = true;
        _enviar1 = 'NO';
        _enviar2 = 'NO';

        $('#cboProvincia').change(function(){
                
            _cboid = $(this).val(); //obtener el id seleccionado
            $("#cboCiudad").empty();

            var _parametros = {
                xxPaisid: _paisid,
                xxEmprid: _emprid,
                xxComboid: _cboid,
                xxOpcion: 0
            }

            var _respuesta = $.post("codephp/cargar_combos.php", _parametros);
            _respuesta.done(function(response) {
                //document.getElementById("city").className = "form-control";
                $("#cboCiudad").html(response);
                
            });
            _respuesta.fail(function() {
            });
            _respuesta.always(function() {
            });                

        });

        
        //Levantar Modal Asistencia

        $("#btnModalAsis").click(function(){
            
            $("#modal_new_asistencia").find("input,textarea").val("");
            $("#modal_new_asistencia").modal("show");
            $('#modal_new_asistencia').modal('handleUpdate');
            $("#cboAsistencia").val(0).change();    
        }); 

        $("#btnNuevoTipo").click(function(){
            
            $("#modal-new-tipoprestador").find("input,textarea").val("");
            $("#modal-new-tipoprestador").modal("show");
            $('#modal-new-tipoprestador').modal('handleUpdate');
        });  
        
        
        //Modal Grabar Nuevo Tipo Asistencia 
        $('#btnSaveAsistencia').click(function(e){

            var _cboasistencia = $('#cboAsistencia').val();
            var _txtasistencia = $("#cboAsistencia option:selected").text();
            var _tipoasistencia = $.trim($("#txtTipoAsistencia").val());
            var _descripcion = $.trim($("#txtDescripcion").val());
            
            if(_txtasistencia == ''){
                toastSweetAlert("top-end",3000,"warning","Seleccione Asistencia..!!");
                return;
            }


            if(_tipoasistencia == ''){
                toastSweetAlert("top-end",3000,"warning","Ingrese Tipo Asistencia..!!");
                return;
            }

            var _parametros = {
                xxPaisid: _paisid,
                xxEmprid: _emprid,
                xxUsuaid: _usuaid,
                xxAsistencia: _cboasistencia,
                xxTipoAsistencia: _tipoasistencia,
                xxDescripcion: _descripcion,
            }                    

            var xrespuesta = $.post("codephp/grabar_tipoasistencia.php", _parametros);
            xrespuesta.done(function(response){
                if(response.trim() == 'EXISTE'){
                    toastSweetAlert("top-end",3000,"warning","Tipo Asistencia ya Existe..!!");
                }else{
                    if(response.trim() != 'ERR'){
                        toastSweetAlert("top-end",3000,"success","Agregado");
                        $("#cboAsis").empty();
                        $("#cboAsis").html(response);
                        $("#modal_new_asistencia").modal("hide");
                    }
                }
            });
        }); 
        
        $('#btnSaveTipo').click(function(e){

            var _tipoprestador = $.trim($("#txtTipoPrestador").val());
            var _valorv = $.trim($("#txtValor").val());

            if(_tipoprestador == ''){
                toastSweetAlert("top-end",3000,"warning","Ingrese Tipo Prestador..!!");
                return;
            }

            if(_valorv == ''){
                toastSweetAlert("top-end",3000,"warning","Ingrese Valor..!!");
                return;
            }

            var _parametros = {
                xxPaisId: _paisid,
                xxEmprId: _emprid,
                xxUsuaId: _usuaid,
                xxTipoPrestador: _tipoprestador,
                xxValor: _valorv
            }

            var xrespuesta = $.post("codephp/grabar_tipoprestador.php", _parametros);
            xrespuesta.done(function(response){
                if(response.trim() == 'EXISTE'){
                    toastSweetAlert("top-end",3000,"warning","Tipo Prestador/Valor ya existe..!!");
                }else{
                    if(response.trim() != 'ERR'){
                        toastSweetAlert("top-end",3000,"warning","Tipo Prestador Agregado");
                        $("#cboTipo").empty();
                        $("#cboTipo").html(response);
                        $("#modal-new-tipoprestador").modal("hide");
                    }
                }
            });
        });
        
        $(document).on("click","#chkEnviar1",function(){
            
            var _chanspan = document.getElementById("spanEnv1");
            var _email1 =  $.trim($('#txtEmail1').val());

            if(_email1 != ''){
                var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
            
                if (regex.test($('#txtEmail1').val().trim())){
                    if($("#chkEnviar1").is(":checked")){
                        _chanspan.innerHTML = '<span id="spanEnv1" class="form-check-label fw-bold" for="chkEnviar1"><strong>Enviar</strong></span>';
                        _enviar1 = 'SI';
                    }else{
                        _chanspan.innerHTML = '<span id="spanEnv1" class="form-check-label fw-bold text-muted" for="chkEnviar1">No Enviar</span>';
                        _enviar1 = 'NO';
                    }
                }else{
                    $('#chkEnviar1').prop('checked','');
                    toastSweetAlert("top-end",3000,"error","Email Invalido..!!");
                    _enviar1 = 'SI';
                    return;
                }
            }else{
                $('#chkEnviar1').prop('checked','');
                _enviar1 = 'NO';
            }
        });
        
        $(document).on("click","#chkEnviar2",function(){
            
            var _chanspan = document.getElementById("spanEnv2");
            var _email2 =  $.trim($('#txtEmail2').val());

            if(_email2 != ''){
                var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
            
                if (regex.test($('#txtEmail2').val().trim())){
                    if($("#chkEnviar2").is(":checked")){
                        _chanspan.innerHTML = '<span id="spanEnv2" class="form-check-label fw-bold" for="chkEnviar2"><strong>Enviar</strong></span>';
                        _enviar2 = 'SI';
                    }else{
                        _chanspan.innerHTML = '<span id="spanEnv2" class="form-check-label fw-bold text-muted" for="chkEnviar2">No Enviar</span>';
                        _enviar2 = 'NO';
                    }                            
                }else{
                    $('#chkEnviar2').prop('checked','');
                    toastSweetAlert("top-end",3000,"error","Email Invalido..!!");
                    _enviar2 = 'NO';
                    return;
                }
            }else{
                $('#chkEnviar2').prop('checked','');
                _enviar2 = 'NO';
            }
        });
        
        $(document).on("click","#chkEnviar3",function(){
            
            var _chanspan = document.getElementById("spanEnv3");
            var _email3 =  $.trim($('#txtEmail3').val());

            if(_email3 != ''){
                var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
            
                if (regex.test($('#txtEmail3').val().trim())){
                    if($("#chkEnviar3").is(":checked")){
                        _chanspan.innerHTML = '<span id="spanEnv3" class="form-check-label fw-bold" for="chkEnviar3"><strong>Enviar</strong></span>';
                        _enviar3 = 'SI';
                    }else{
                        _chanspan.innerHTML = '<span id="spanEnv3" class="form-check-label fw-bold text-muted" for="chkEnviar3">No Enviar</span>';
                        _enviar3 = 'NO';
                    }                            
                }else{
                    $('#chkEnviar3').prop('checked','');
                    toastSweetAlert("top-end",3000,"error","Email Invalido..!!");
                    _enviar3 = 'NO';
                    return;
                }
            }else{
                $('#chkEnviar3').prop('checked','');
                _enviar3 = 'NO';
            }
        }); 

        $(document).on("click","#chkEnviar4",function(){
            
            var _chanspan = document.getElementById("spanEnv4");
            var _email4 =  $.trim($('#txtEmail4').val());

            if(_email4 != ''){
                var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
            
                if (regex.test($('#txtEmail4').val().trim())){
                    if($("#chkEnviar4").is(":checked")){
                        _chanspan.innerHTML = '<span id="spanEnv4" class="form-check-label fw-bold" for="chkEnviar4"><strong>Enviar</strong></span>';
                        _enviar4 = 'SI';
                    }else{
                        _chanspan.innerHTML = '<span id="spanEnv4" class="form-check-label fw-bold text-muted" for="chkEnviar4">No Enviar</span>';
                        _enviar4 = 'NO';
                    }                            
                }else{
                    $('#chkEnviar4').prop('checked','');
                    toastSweetAlert("top-end",3000,"error","Email Invalido..!!");
                    _enviar4 = 'NO';
                    return;
                }
            }else{
                $('#chkEnviar4').prop('checked','');
                _enviar4 = 'NO';
            }
        }); 
        
        //Levantar Modal Tipo Servicio
        $("#btnAddServicio").click(function(){
            
            $("#agregar_servicio").find("input,textarea").val("");
            $("#agregar_servicio").modal("show");
            $('#agregar_servicio').modal('handleUpdate');
            $("#cboAsis").val(0).change();    
        }); 

        //Agregar Nuevo Servicio Modal
        $('#btnAgregar').click(function(e){

            var _cboasistencia = $('#cboAsis').val();
            var _txttipoasistencia = $("#cboAsis option:selected").text();
            var _txttipoatencion = $.trim($('#txtTipoAtencion').val()).toUpperCase();
            var _red = $.trim($("#txtRed").val());
            var _pvp = $.trim($("#txtPvp").val());
            var  _continuar = true;
            var _count = 0;
           
            if(_txttipoasistencia == ''){
                toastSweetAlert("top-end",3000,"warning","Seleccione Tipo Asistencia..!!");
                return;
            }

            if(_txttipoatencion == ''){
                toastSweetAlert("top-end",3000,"warning","Ingrese Tipo Atencion..!!");
                return;
            }

            if(_red == ''){
                toastSweetAlert("top-end",3000,"warning","Ingrese Costo de Red..!!");
                return;
            }

            if(_pvp == ''){
                toastSweetAlert("top-end",3000,"warning","Ingrese Pvp..!!");
                return;
            }

            _red = parseFloat(_red);
            _pvp = parseFloat(_pvp);
            
        
            $.each(_result,function(i,item){
                if(item.arryatencion == _txttipoatencion)
                {                  
                    toastSweetAlert("top-end",3000,"warning","Tipo Atencion ya Existe..!!");   
                    $("#cboAsis").val(0).change();
                    $("#txtTipoAtencion").val('');
                    $("#txtRed").val('');
                    $("#txtPvp").val('');                           
                    _continuar = false;
                    return false;
                }
            });

            if(_continuar){
                
                //_count = _count + 1;
                _output = '<tr id="row_' + _cboasistencia + '">';
                _output += '<td style="display: none;">' + _cboasistencia + '</td>';                
                _output += '<td>' + _txttipoasistencia + '</td>';
                _output += '<td>' + _txttipoatencion + '</td>';
                _output += '<td>' + _red + '</td>';
                _output += '<td>' + _pvp + '</td>';
                _output += '<td><div class=""><div class="btn-group">';
                _output += '<button type="button" name="btnDelete" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1 btnDelete" id="' + _cboasistencia + '"><i class="fa fa-trash"></i></button></div></div></td>';
                _output += '</tr>';

                $('#tblEspecialidad').append(_output);

                console.log(_output);

                _objeto = {
                    arryid: _cboasistencia,
                    arryasistencia: _txttipoasistencia,
                    arryatencion: _txttipoatencion,
                    arryred: _red,
                    arrypvp: _pvp
                }

                _result.push(_objeto);
                $("#cboAsis").val(0).change();
                $("#txtTipoAtencion").val('');
                $("#txtRed").val('');
                $("#txtPvp").val('');
                $("#agregar_servicio").modal("hide");

            }
        });
        


        $('#btnSave').click(function(e){
           
           var _provid = $('#cboProvincia').val();
           var _ciudid = $('#cboCiudad').val();
           var _prestador = $.trim($('#txtPrestador').val());
           var _sector = $('#cboSector').val();
           var _tipopresta = $('#cboTipo').val();
           var _direccion = $.trim($('#txtDireccion').val());
           var _ubicacion = $.trim($('#txtUbi').val());
           var _url = $.trim($('#txtUrl').val());
           var _telefono1 = $.trim($('#txtFono1').val());
           var _telefono2 = $.trim($('#txtFono2').val());
           var _celular1 = $.trim($('#txtCelular1').val());
           var _email1 =  $.trim($('#txtEmail1').val());
           var _email2 =  $.trim($('#txtEmail2').val());
           var _email3 =  $.trim($('#txtEmail3').val());
           var _email4 =  $.trim($('#txtEmail4').val());
           var _selecc = 'NO';
           _respuesta = 'OK';

           if(_provid == ''){
               toastSweetAlert("top-end",3000,"warning","Seleccione Provincia..!!");
               return; 
           }

           if(_ciudid == ''){
               toastSweetAlert("top-end",3000,"warning","Seleccione Ciudad..!!");
               return; 
           }

           if(_prestador == ''){
               toastSweetAlert("top-end",3000,"warning","Ingrese Prestador..!!");
               return;                         
           }

           if(_sector == ''){
               toastSweetAlert("top-end",3000,"warning","Seleccione Sector..!!");
               return; 
           }
           
           if(_tipopresta == ''){
               toastSweetAlert("top-end",3000,"warning","Seleccione Tipo Prestador..!!");
               return; 
           }                       
           
           if(_url != ''){
               try{
                   new URL(_url);
               }catch(err){
                   toastSweetAlert("top-end",3000,"error","Direccion URL Incorrecta...!!");
                   return false;
               }
           }

           if(_telefono1 != '')
	        {
                _valor = document.getElementById("txtFono1").value;
                if( !(/^(\d{7}|\d{9})$/.test(_valor)) ) {
                    toastSweetAlert("top-end",3000,"error","Telefono 1 Incorrecto..!!");  
                    return;
                }
            } 

            if(_telefono2 != '')
	        {
                _valor = document.getElementById("txtFono2").value;
                if( !(/^(\d{7}|\d{9})$/.test(_valor)) ) {
                    toastSweetAlert("top-end",3000,"error","Telefono 2 Incorrecto..!!");  
                    return;
                }
            } 

           
           if(_celular1 != '')
           {
               _valor = document.getElementById("txtCelular1").value;
               if( !(/^\d{10}$/.test(_valor)) ) {
                   toastSweetAlert("top-end",3000,"error","Celular 1 Incorrecto..!!");
                   return;
               }
           }                     
                             

           if(_result.length == 0){
                toastSweetAlert("top-end",3000,"warning","Agregue un Servicio al Prestador..!!");
                return;
           }

           
            var _imgfile = document.getElementById("imgfile").style.backgroundImage;
            var _urlimg = _imgfile.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
            var _pos = _urlimg.trim().indexOf('.');
            var _ext = _urlimg.trim().substr(_pos, 5);

            if(_ext.trim() != '.png' && _ext.trim() != '.jpg' && _ext.trim() != '.jpeg'){
				_selecc = 'SI';
			}   

            if(_selecc == 'SI'){
				var _imagen = document.getElementById("imglogo");
				var _file = _imagen.files[0];
				var _fullPath = document.getElementById('imglogo').value;
				_ext = _fullPath.substring(_fullPath.length - 4);
				_ext = _ext.toLowerCase();   
			}else{
                _file='';
            }

			if(_ext.trim() != '.png' && _ext.trim() != '.jpg' && _ext.trim() != 'jpeg'){
				toastSweetAlert("top-end",3000,"error","El archivo seleccionado no es una imagen..!");
				return;
			}
                                
            form_data = new FormData();
            form_data.append('xxPaisid', _paisid);
            form_data.append('xxEmprid', _emprid);
            form_data.append('xxProvid', _ciudid);
            form_data.append('xxPrestador', _prestador);
            form_data.append('xxSector', _sector);
            form_data.append('xxTipo', _tipopresta);
            form_data.append('xxDireccion', _direccion);
            form_data.append('xxUbicacion', _ubicacion);
            form_data.append('xxUrl', _url);
            form_data.append('xxFono1', _telefono1);
            form_data.append('xxFono2', _telefono2);
            form_data.append('xxCelular1', _celular1);
            form_data.append('xxEmail1', _email1);
            form_data.append('xxEnviar1', _enviar1);
            form_data.append('xxEmail2', _email2);
            form_data.append('xxEnviar2', _enviar2);
            form_data.append('xxEmail3', _email3);
            form_data.append('xxEnviar3', _enviar3);
            form_data.append('xxEmail4', _email4);
            form_data.append('xxEnviar4', _enviar4);
            form_data.append('xxFile', _file);
            form_data.append('xxUsuaid', _usuaid);

            var xrespuesta = $.post("codephp/consultar_prestador.php", { xxPaisid: _paisid, xxEmprid: _emprid, xxProvid: _ciudid, xxPrestador: _prestador });
            xrespuesta.done(function(response){
                
                if(response.trim() == '0'){

                    $.ajax({
                        url: "codephp/grabar_prestador.php",
                        type: "post",
                        data: form_data,
                        processData: false,
                        contentType: false,
                        dataType: "html",
                        success: function(dataid){
                            console.log(dataid)
                            if(dataid != 0){
                                //debugger;

                                var xresultado = $.post("codephp/grabar_prestaespeci.php", { xxPaisid: _paisid, xxEmprid: _emprid, xxUsuaid: _usuaid, xxPresid: dataid, xxResult: _result });
                                xresultado.done(function(xrespose){

                                    if(xrespose.trim() == 'OK'){
                                        _detalle = 'Grabado con Exito';
                                        _respuesta = 'OK'; 
                                    }else{
                                        _detalle = 'Error creacion de especialidades';
                                        _respuesta = 'ERR';                                
                                    }

                                    /**PARA CREAR REGISTRO DE LOGS */
                                    /*_parametros = {
                                        "xxPaisid" : _paisid,
                                        "xxEmprid" : _emprid,
                                        "xxUsuaid" : _usuaid,
                                        "xxDetalle" : _detalle,
                                    }					
        
                                    $.post("codephp/new_log.php", _parametros, function(response){
                                    });*/     
                                    
                                    if(_respuesta == 'OK'){
                                        $.redirect('?page=prestador_admin&menuid=<?php echo $menuid; ?>', {'mensaje': _detalle}); //POR METODO POST
                                    }else{
                                        toastSweetAlert("top-end",3000,"warning",_detalle);
                                    }
                                });    
                              
                            }else{
                                _detalle = 'Error creacion nuevo prestador';
                                //_respuesta = 'ERR';     
                                toastSweetAlert("top-end",3000,"warning",_detalle);
                            }
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    });   

                }else{
                    toastSweetAlert("top-end",3000,"warning","Prestador ya Existe..!!");
                }
            });                    
       });                

    });

    function setTwoNumberDecimal(event) {
        this.value = parseFloat(this.value).toFixed(2);
    }

    $(document).on("click",".btnDelete",function(){
        row_id = $(this).attr("id");
        
        $.each(_result,function(i,item){
            if(item.arryid == row_id)
            {
                _result.splice(i, 1);
                return false;
            }else{
                continuar = true;
            }
        });  

        $('#row_' + row_id + '').remove();

    });            

    //Desplazar-modal
    $("#modal-new-especialidad").draggable({
        handle: ".modal-header"
    });    
    
    $("#agregar_especialidad").draggable({
        handle: ".modal-header"
    });  

</script>
