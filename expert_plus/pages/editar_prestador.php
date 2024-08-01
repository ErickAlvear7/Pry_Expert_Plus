<?php
	
	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

   	//file_put_contents('log_seguimiento.txt', $xSQL . "\n\n", FILE_APPEND);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	    	

	require_once("dbcon/config.php");
	require_once("dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
	mysqli_set_charset($con,'utf8');	

	//$xServidor = $_SERVER['HTTP_HOST'];
	$page = isset($_GET['page']) ? $_GET['page'] : "index";
	$menuid = $_GET['menuid'];

    if(!isset($_POST['id'])){
        header("Location: ./logout.php");
        exit();
    }

    $xPresid = $_POST['id'];

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

    $xSQL = "SELECT * FROM `expert_prestadora` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pres_id=$xPresid ";
    $all_prestador = mysqli_query($con, $xSQL);
    foreach ($all_prestador as $presta){
        $xProvid = $presta['prov_id'];
        $xNombre = $presta['pres_nombre'];
        $xSector = $presta['pres_sector'];
        $xTipoPresta = $presta['pres_tipoprestador'];
        $xDireccion = $presta['pres_direccion'];
        $xUbicacion = $presta['pres_ubicacion'];
        $xUrl = $presta['pres_url'];
        $xFono1 = $presta['pres_fono1'];
        $xFono2 = $presta['pres_fono2'];
        $xCelu1 = $presta['pres_celular1'];
        $xEmail1 = $presta['pres_email1'];
        $xEnviar1 = $presta['pres_enviar1'];
        $xEmail2 = $presta['pres_email2'];
        $xEnviar2 = $presta['pres_enviar2'];
        $xEmail3 = $presta['pres_email3'];
        $xEnviar3 = $presta['pres_enviar3'];
        $xEmail4 = $presta['pres_email4'];
        $xEnviar4 = $presta['pres_enviar4'];
        $xLogo = $presta['pres_logo'];
    }

	$xSQL = "SELECT * FROM `provincia_ciudad` WHERE pais_id=$xPaisid AND prov_id=$xProvid ";
    $cbo_provincia = mysqli_query($con, $xSQL);    
    foreach ($cbo_provincia as $prov){
        $xCboProv = $prov['provincia'];
    }

	$xSQL = "SELECT * FROM `provincia_ciudad` WHERE pais_id=$xPaisid AND provincia='$xCboProv' ";
    $cbo_ciudad = mysqli_query($con, $xSQL);    

    $xSQL = "SELECT pde.pade_nombre AS Nombre, pde.pade_valorV AS Valor, CASE pde.pade_estado WHEN 'A' THEN 'Activo' ELSE 'Inactivo' END AS Estado FROM `expert_parametro_detalle` pde, `expert_parametro_cabecera` pca ";
    $xSQL .= "WHERE pde.paca_id=pca.paca_id AND pca.paca_nombre='Tipo Prestador' AND pca.paca_estado='A' AND pais_id=$xPaisid AND empr_id=$xEmprid ";
    $xSQL .= "ORDER BY pde.pade_orden ";
    $all_tipopresta = mysqli_query($con, $xSQL);

    $xSQL = "SELECT (SELECT tpa.asis_nombre FROM `expert_tipo_asistencia` tpa WHERE tpa.asis_id=prs.asis_id) AS Asistencia,";
    $xSQL .="prs.prse_id AS Id,prs.prse_atencion AS Atencion, CASE prs.prse_estado WHEN 'A' THEN 'ACTIVO' ELSE 'INACTIVO' END AS Estado, prs.prse_red AS Red,";
    $xSQL .="prs.prse_pvp AS Pvp FROM `expert_prestadora_servicio` prs WHERE prs.pres_id=$xPresid";
    $all_prestaservicio = mysqli_query($con, $xSQL);


?>

<div id="kt_content_container" class="container-xxl">
    <div id="formPresta" class="form d-flex flex-column flex-lg-row">
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
                            <div class="image-input-wrapper w-150px h-150px" id="imgfile"></div>
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
                            <button type="button" id="btnAsistencia" class="btn btn-light-primary btn-sm mb-5 border border-primary">
                                <i class="fa fa-users me-1" aria-hidden="true"></i>
                                Nuevo Tipo Asistencia
                            </button>
                            <button type="button" id="btnNuevaProfesion" class="btn btn-light-primary btn-sm mb-5 border border-primary">
                                <i class="fa fa-briefcase me-1" aria-hidden="true"></i>                                                               
                                Nuevo Tipo Profesion
                            </button>                                 
                            
                            <button type="button" id="btnNuevoProfesional" class="btn btn-light-primary btn-sm mb-5 border border-primary">
                                <i class="fa fa-user-circle me-1" aria-hidden="true"></i>
                                Nuevo Profesional
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
                                                <?php foreach ($cbo_ciudad as $ciudad) : ?>
                                                    <option value="<?php echo $ciudad['prov_id'] ?>"><?php echo mb_strtoupper($ciudad['ciudad']) ?></option>
                                                <?php endforeach ?>
                                            </select> 
                                            <input type="hidden" name="txtcbociudad" id="txtcbociudad" class="form-control mb-2" value="<?php echo $xProvid; ?>"  />
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-5 fv-row">
                                    <label class="required form-label">Prestador</label>
                                    <input type="text" name="txtPrestador" id="txtPrestador" class="form-control mb-2 text-uppercase" maxlength="150" placeholder="Nombre del Prestador" value="<?php echo $xNombre; ?> " />
                                    <div class="text-muted fs-7">El Prestador puede ser Clinica/Centro Medico/Estudio/Consultorio/Otros..</div>
                                    <input type="hidden" name="txtPrestaant" id="txtPrestaant" class="form-control mb-2" value="<?php echo $xNombre; ?>" />
                                </div>   
                                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                                    <div class="col">
                                        <div class="fv-row mb-7">
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
                                        <div class="fv-row mb-1">
                                            <label class="fs-6 fw-bold form-label mt-3">
                                                <span class="required">Tipo Prestador</span>
                                                <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Definicion del prestador"></i>
                                            </label>
                                            <select name="cboTipo" id="cboTipo" aria-label="Seleccione Tipo Prestador" data-control="select2" data-placeholder="Seleccione Tipo Prestador" data-dropdown-parent="#kt_ecommerce_add_product_general" class="form-select">
                                                <option></option>
                                                <?php 
                                                $xSQL = "SELECT pde.pade_valorV AS Codigo,pde.pade_nombre AS Descripcion FROM `expert_parametro_detalle` pde,`expert_parametro_cabecera` pca WHERE pca.pais_id=$xPaisid ";
                                                $xSQL .= "AND pca.paca_nombre='Tipo Prestador' AND pca.paca_id=pde.paca_id AND pca.paca_estado='A' AND pade_estado='A' ";
                                                $all_datos =  mysqli_query($con, $xSQL);
                                                foreach ($all_datos as $datos){ ?>
                                                    <option value="<?php echo $datos['Codigo'] ?>"><?php echo mb_strtoupper($datos['Descripcion']); ?></option>
                                                <?php } ?>                                                        
                                            </select>                                                      
                                        </div>
                                    </div>
                                </div> 
                                <div class="mb-2 fv-row">
                                    <label class="form-label">Direccion</label>
                                    <textarea class="form-control mb-2 text-uppercase" name="txtDireccion" id="txtDireccion" maxlength="250" onkeydown="return (event.keyCode!=13);"><?php echo $xDireccion; ?></textarea>
                                </div>
                                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-0 row-cols-lg-2 mb-2">
                                    <div class="col">
                                        <div class="fv-row mb-0">
                                            <label class="fs-6 fw-bold form-label mt-3">
                                                <i class="fa fa-map-marker fa-1x me-2" style="color:#F46D55;" aria-hidden="true"></i>
                                                <span class="">Ubicacion Prestador</span>   
                                            </label>
                                            <textarea class="form-control mb-2 text-uppercase" name="txtUbi" id="txtUbi" rows="3" maxlength="250" onkeydown="return (event.keyCode!=13);"><?php echo $xUbicacion; ?></textarea>   
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="fv-row mb-0">
                                            <label class="fs-6 fw-bold form-label mt-3">
                                                <i class="fa fa-globe fa-1x me-2" style="color:#5AD1F1;" aria-hidden="true"></i>
                                                <span class="">Pagina Web Prestador</span>   
                                            </label>
                                            <input type="text" class="form-control mb-2 text-lowercase" name="txtUrl" id="txtUrl" maxlength="150" placeHolder="https://wwww.prestador.com" value="<?php echo $xUrl; ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="fs-6 fw-bold form-label mt-3">
                                            <i class="fa fa-phone fa-1x me-2" style="color:#7DF57D;" aria-hidden="true"></i>
                                            <span class="">Telefono 1</span>   
                                        </label>
                                        <input type="text" class="form-control mb-2 w-150px" name="txtFono1" id="txtFono1" maxlength="9" placeholder="022222222" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xFono1; ?>" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="fs-6 fw-bold form-label mt-3">
                                            <i class="fa fa-phone fa-1x me-2" style="color:#7DF57D;" aria-hidden="true"></i>
                                            <span class="">Telefono 2</span>   
                                        </label>
                                        <input type="text" class="form-control mb-2 w-150px" name="txtFono2" id="txtFono2" maxlength="9" placeholder="" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xFono2; ?>" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="fs-6 fw-bold form-label mt-3">
                                            <i class="fa fa-mobile fa-1x me-2" style="color:#5AD1F1;" aria-hidden="true"></i>
                                            <span class="">Celular</span>   
                                        </label>
                                        <input type="text" class="form-control mb-2 w-150px" name="txtCelular1" id="txtCelular1" maxlength="10" placeholder="0999999999" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xCelu1; ?>" />
                                    </div>
                                </div>
                                <div class="d-flex align-items-center collapsible py-3 toggle mb-0" data-bs-toggle="collapse" data-bs-target="#kt_job_2_1">														<!--begin::Icon-->
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
                                    <h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">Emails</h4>
                                </div> 
                                <div id="kt_job_2_1" class="collapse show fs-6 ms-1">
                                    <div class="row mb-2">
                                        <div class="col-md-8">
                                            <label class="form-label">Email 1</label>
                                            <input type="email" name="txtEmail1" id="txtEmail1" maxlength="150" placeholder="micorre@dominio.com" class="form-control mb-2 text-lowercase" value="<?php echo $xEmail1; ?>" />
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-check form-switch form-check-custom form-check-solid mt-5">
                                                <input class="form-check-input mt-5" name="chkEnviar1" id="chkEnviar1" type="checkbox"<?php if($xEmail1 != '') echo 'checked'; ?> />
                                                <span id="spanEnv1" class="form-check-label fw-bold text-muted mt-3" for="chkEnviar1"><?php if($xEmail1 != '') { echo 'Si Enviar'; }else { echo 'No Enviar'; } ?></span>
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
                                                <input class="form-check-input mt-5" name="chkEnviar2" id="chkEnviar2" type="checkbox"<?php if($xEmail2 != '') echo 'checked'; ?> />
                                                <span id="spanEnv2" class="form-check-label fw-bold text-muted mt-3" for="chkEnviar2"><?php if($xEmail2 != '') { echo 'Si Enviar'; }else { echo 'No Enviar'; } ?></span>
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
                                                <input class="form-check-input mt-5" name="chkEnviar3" id="chkEnviar3" type="checkbox"<?php if($xEmail3 != '') echo 'checked'; ?> />
                                                <span id="spanEnv3" class="form-check-label fw-bold text-muted mt-3" for="chkEnviar3"><?php if($xEmail3 != '') { echo 'Si Enviar'; }else { echo 'No Enviar'; } ?></span>
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
                                                <input class="form-check-input mt-5" name="chkEnviar4" id="chkEnviar4" type="checkbox"<?php if($xEmail4 != '') echo 'checked'; ?> />
                                                <span id="spanEnv4" class="form-check-label fw-bold text-muted mt-3" for="chkEnviar4"><?php if($xEmail4 != '') { echo 'Si Enviar'; }else { echo 'No Enviar'; } ?></span>
                                            </label>    
                                        </div>
                                    </div>
                                </div>                                       
                            </div>   
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-7">
                        <button type="button" id="btnSave" class="btn btn-sm btn-primary"><i class="fa fa-hdd me-1"></i>
                           Grabar
                        </button>
                    </div>                            
                </div>
                <div class="tab-pane fade" id="kt_ecommerce_add_product_advanced" role="tab-panel">
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <div class="d-flex justify-content-start">
                            <button type="button" data-repeater-create="" class="btn btn-light-primary btn-sm mb-1 border border-primary" id="btnServicio">
                                <i class="fa fa-user-plus me-1" aria-hidden="true"></i>
                                Agregar Tipo Servicio
                            </button>
                        </div>
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Servicios Asignados</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0" id="kt_contacts_list_body">
                                <div class="d-flex flex-column gap-10">
                                    <div class="scroll-y me-n7 pe-7" id="parametro_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#parametro_header" data-kt-scroll-wrappers="#parametro_scroll" data-kt-scroll-offset="300px">
                                        <table id="tblEspecialidad" class="table align-middle table-row-dashed table-hover fs-6 gy-5" style="width: 100%;">
                                            <thead>
                                                <tr class="text-start text-gray-800 fw-bolder fs-7 text-uppercase gs-0">
                                                    <th style="display: none;">Id</th>
                                                    <th class="">TIPO ASISTENCIA</th>
                                                    <th class="min-w-125px">TIPO ATENCION</th>
                                                    <th>RED</th>
                                                    <th>PVP</th>
                                                    <th class="min-w-125px">Estado</th>
                                                    <th>Status</th>
                                                    <th class="min-w-125px" style="text-align: center;">Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fw-bold text-gray-600">

                                                <?php 
                                        
                                                    foreach($all_prestaservicio as $serv){
                                                        $xServid = $serv['Id'];
                                                        $xAsistencia = $serv['Asistencia'];
                                                        $xAtencion = trim($serv['Atencion']);
                                                        $xEstado = trim($serv['Estado']);
                                                        $xRed = trim($serv['Red']);
                                                        $xPvp = trim($serv['Pvp']);
                                                        
                                                    ?>
                                                        <?php 
                        
                                                            $chkEstado = '';
                                                            $xDisabledEdit = '';
                                                            $xDisabledPerson = '';
                                                            $xDisabledMotivos = '';
                        
                                                            if($xEstado == 'ACTIVO'){
                                                                $chkEstado = 'checked="checked"';
                                                                $xTextColor = "badge badge-light-primary";
                                                            }else{
                                                                $xTextColor = "badge badge-light-danger";
                                                                $xDisabledEdit = 'disabled';
                                                                $xDisabledPerson = 'disabled';
                                                                $xDisabledMotivos = 'disabled';
                                                            }
                        
                                                        ?>
                                                        <tr id="row_<?php echo $xServid; ?>">
                                                            <td>  
                                                                <?php echo $xAsistencia; ?>
                                                                <input type="hidden" id="txtAsistencia" value="<?php echo $xAsistencia; ?>" />
                                                            </td>
                                                            <td> <?php echo $xAtencion; ?></td>
                                                            <td><?php echo $xRed; ?> </td>
                                                            <td><?php echo $xPvp; ?> </td>                                
                                                            <td id="td_<?php echo $xId; ?>"> 
                                                                <div class="<?php echo $xTextColor; ?>"><?php echo $xEstado; ?></div> 
                                                            </td>
                                                            <td>
                                                                <div class="text-center">
                                                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                        <input class="form-check-input h-20px w-20px border-primary" <?php echo $chkEstado; ?> type="checkbox" id="chk<?php echo $xServid; ?>" 
                                                                            onchange="f_UpdateEstado(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xServid; ?>)" value="<?php echo $xServid; ?>"/>
                                                                    </div>
                                                                </div>
                                                            </td> 													
                                                            <td>
                                                                <div class="text-center">
                                                                    <div class="btn-group">
                                                                        <button id="btnEditar_" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" <?php echo $xDisabledEdit; ?> title='Editar Especialidad' data-bs-toggle="tooltip" data-bs-placement="left" >
                                                                            <i class='fa fa-edit'></i>
                                                                        </button>	
                                                                        <button id="btnPerson_" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" <?php echo $xDisabledPerson; ?> onclick='f_AgregarProfesional()' title='Agregar Profesional' data-bs-toggle="tooltip" data-bs-placement="left" >
                                                                            <i class="fas fa-user"></i>
                                                                        </button>	
                                                                        <button id="btnMotivos_" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" <?php echo $xDisabledMotivos; ?> onclick='f_AgregarMotivos()' title='Agregar Motivos' data-bs-toggle="tooltip" data-bs-placement="left" >
                                                                            <i class="fas fa-book"></i>
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
            </div>
        </div>
    </div>
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
                                    <label class="required form-label">Valor Red</label>
                                    <input type="text" name="txtRed" id="txtRed" class="form-control mb-2" placeholder="Red (0.00)" min="0" maxlength = "6" />
                                </div>
                                <div class="col">
                                    <label class="required form-label">Valor Pvp</label>
                                    <input type="text" name="txtPvp" id="txtPvp" class="form-control mb-2" placeholder="Precio al Publico (0.00)" min="0" maxlength = "6" />
                                </div>    
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger border border-danger" data-bs-dismiss="modal"><i class="fa fa-times me-1" aria-hidden="true"></i>Cerrar</button>
                <button type="button" id="btnAddServicio" class="btn btn-sm btn-light-primary border border-primary"><i class="fa fa-plus me-1"></i>Agregar</button>
            </div>
        </div>
    </div>
</div>  


<script>
    $(document).ready(function(){
               
        _presid = "<?php echo $xPresid; ?>"
        _paisid = "<?php echo $xPaisid; ?>";
        _emprid = "<?php echo $xEmprid; ?>";
        _usuaid = "<?php echo $xUsuaid; ?>";
        _logo  = "<?php echo $xLogo; ?>";
        
        $('#cboProvincia').val("<?php echo $xCboProv; ?>").change();
        $('#cboCiudad').val(<?php echo $xProvid; ?>).change();
        $('#cboSector').val("<?php echo $xSector; ?>").change();
        $('#cboTipo').val("<?php echo $xTipoPresta; ?>").change();

        
        _logo = _logo == '' ? 'logo.png' : _logo;
        document.getElementById('imgfile').style.backgroundImage="url(assets/images/prestadores/" + _logo + ")";


        $('#cboProvincia').change(function(){
            
            _cboid = $(this).val(); //obtener el texto selecionado en el combo
            $("#cboCiudad").empty();

            var _parametros = {
                "xxPaisid" : _paisid,
                "xxEmprid" : _emprid,
                "xxComboid" : _cboid,
                "xxOpcion" : 0
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
    }); 


    //Levantqr Modal Nueva Asistencia
    $('#btnAsistencia').click(function(){

        $("#cboAsistencia").val(0).change(); 
        $("#modal_new_asistencia").find("input,textarea").val("");
        $('#modal_new_asistencia').modal('handleUpdate');
        $("#modal_new_asistencia").modal("show");

    });

    //Levantar Modal Tipo Servicio
    $("#btnServicio").click(function(){
            
            $("#agregar_servicio").find("input,textarea").val("");
            $("#agregar_servicio").modal("show");
            $('#agregar_servicio').modal('handleUpdate');
            $("#cboAsis").val(0).change();    
        }); 

    //Guardar Nuevo Tipo Asistencia Modal
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
                    toastSweetAlert("top-end",3000,"success","Asistencia Agregada");
                    $("#cboAsis").empty();
                    $("#cboAsis").html(response);
                    $("#modal_new_asistencia").modal("hide");
                }
            }
        });
    });
    
    //Agregar Servicio Directo a la BDD
    $('#btnAddServicio').click(function(){

        var _asistid = $('#cboAsis').val();
        var _txtasistencia = $("#cboAsis option:selected").text();
        var _txtatencion = $.trim($('#txtTipoAtencion').val()).toUpperCase();
        var _red = $.trim($("#txtRed").val());
        var _pvp = $.trim($("#txtPvp").val());
        
        if(_txtasistencia == ''){
            toastSweetAlert("top-end",3000,"warning","Seleccione Tipo Asistencia..!!");
            return;
        }

        if(_txtatencion == ''){
            toastSweetAlert("top-end",3000,"warning","Ingrese Tipo Atencion..!!");
            return;
        }

        if(_red == ''){
            toastSweetAlert("top-end",3000,"warning","Ingrese Valor de Red..!!");
            return;
        }

        if(_pvp == ''){
            toastSweetAlert("top-end",3000,"warning","Ingrese Valor Pvp..!!");
            return;
        }

      
        _red = _red.replace(/[a-z]/g,'0');
        _pvp = _pvp.replace(/[a-z]/g,'0');

        _red = parseFloat(_red)
        _pvp = parseFloat(_pvp)

        var _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxPresid" : _presid,
            "xxAsisid" : _asistid,
            "xxAtencion" : _txtatencion,
            "xxRed" : _red,
            "xxPvp" : _pvp,  
            "xxUsuaid" : _usuaid,                             
        }

        var xrespuesta = $.post("codephp/grabar_prestaservicio.php", _parametros);
        xrespuesta.done(function(response){

                if(response != 0){
                    
                }
        });


    });

</script>

