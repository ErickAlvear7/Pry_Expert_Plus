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
    $xSQL .="prs.prse_id AS Id,prs.pres_id AS Idpres,prs.asis_id AS Idasis,prs.prse_atencion AS Atencion, CASE prs.prse_estado WHEN 'A' THEN 'ACTIVO' ELSE 'INACTIVO' END AS Estado, prs.prse_red AS Red,";
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
                                        <table id="tblServicio" class="table align-middle table-row-dashed table-hover fs-6 gy-5" style="width: 100%;">
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
                                                        $xId = $serv['Id'];
                                                        $xIdpres = $serv['Idpres'];
                                                        $xIdasis = $serv['Idasis'];
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
                                                        <tr id="row_<?php echo $xId; ?>">
                                                            <td>  
                                                                <?php echo $xAsistencia; ?>
                                                                <input type="hidden" id="txtAsistencia_<?php echo $xId; ?>" value="<?php echo $xAsistencia; ?>" />
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
                                                                        <input class="form-check-input h-20px w-20px border-primary" <?php echo $chkEstado; ?> type="checkbox" id="chk<?php echo $xId; ?>" 
                                                                            onchange="f_UpdateEstado(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xId; ?>)" value="<?php echo $xId; ?>"/>
                                                                    </div>
                                                                </div>
                                                            </td> 													
                                                            <td>
                                                                <div class="text-center">
                                                                    <div class="btn-group">
                                                                        <button id="btnEditar_<?php echo $xId; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 " <?php echo $xDisabledEdit; ?> title='Editar Servicio' data-bs-toggle="tooltip" data-bs-placement="left" onclick="f_EditarServicio(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xId; ?>)" >
                                                                            <i class='fa fa-edit'></i>
                                                                        </button>	
                                                                        <button id="btnPerson_<?php echo $xId; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" <?php echo $xDisabledPerson; ?> onclick="f_AgregarProfesional(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xId; ?>)" title='Agregar Profesional' data-bs-toggle="tooltip" data-bs-placement="left" >
                                                                            <i class="fas fa-user"></i>
                                                                        </button>	
                                                                        <button id="btnMotivos_<?php echo $xId; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" <?php echo $xDisabledMotivos; ?> onclick="f_AgregarMotivos(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xId; ?>)" title='Agregar Motivos' data-bs-toggle="tooltip" data-bs-placement="left" >
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
                            <rect x="7.41422" y="6" width="16" h6eight="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
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

<!--Modal Editar Servicio -->
<div class="modal fade" id="editar_servicio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-800px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Editar Datos Servicio Prestador</h2>
                <input type="text" name="txtcodprseid" id="txtcodprseid" class="form-control form-control-solid" value=""  />
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
                        <div class="row mb-5">
                            <div class="col-md-12">
                                 <label class="required form-label">Tipo Asistencia</label>
                                 <input type="text" name="txtEditAsis" id="txtEditAsis" class="form-control form-control-solid" readonly  />
                            </div>
                        </div>
                        <div class="row mb-5">
                            <div class="col">
                                <label class="required form-label">Tipo Atencion</label>
                                <textarea class="form-control text-uppercase" name="txtEditAten" id="txtEditAten" rows="2" maxlength="300" onkeydown="return (event.keyCode!=13);"></textarea>
                                <input type="text" name="txtEditAtenold" id="txtEditAtenold" class="form-control mb-2" maxlength="300" />
                            </div>
                        </div>
                        <div class="mb-2 fv-row">
                            <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                                <div class="col">
                                    <label class="required form-label">Valor Red</label>
                                    <input type="text" name="txtEditRed" id="txtEditRed" class="form-control mb-2" placeholder="Red (0.00)" min="0" maxlength = "6" />
                                </div>
                                <div class="col">
                                    <label class="required form-label">Valor Pvp</label>
                                    <input type="text" name="txtEditPvp" id="txtEditPvp" class="form-control mb-2" placeholder="Precio al Publico (0.00)" min="0" maxlength = "6" />
                                </div>    
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger border border-danger" data-bs-dismiss="modal"><i class="fa fa-times me-1" aria-hidden="true"></i>Cerrar</button>
                <button type="button" class="btn btn-sm btn-light-primary border border-primary" id="btneditarservicio"><i class="las la-pencil-alt me-1"></i>Editar</button>
            </div>
        </div>
    </div>
</div> 

<!--Modal Nuevo Profesional -->
<div class="modal fade" id="modal_profesional" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
        <div class="modal-content"> 
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Nuevo Profesional</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body py-lg-10 px-lg-10 mt-n3">
                <div class="card card-flush py-2">
                    <div class="card-header border-0">
                        <div class="d-flex align-items-center collapsible py-1 toggle collapsed mb-0" data-bs-toggle="collapse" data-bs-target="#view_avatar">
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
                            <h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">Foto</h4>
                        </div>
                    </div>
                    <div id="view_avatar" class="collapse fs-6 ms-1">
                        <div class="card card-flush py-4">
                            <div class="card-body pt-0">
                                <div class="image-input image-input-outline" data-kt-image-input="true">
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url(assets/images/users/user.png);" id="imgfileprof"></div>
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Cambiar Foto">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" name="avatar" id="imgavatar" accept=".png, .jpg, .jpeg" />
                                        <input type="hidden" name="avatar_remove" />
                                    </label>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancelar Foto">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                </div>
                                <div class="form-text">Archivos permitidos: png, jpg, jpeg.</div>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="card card-flush py-2">
                    <div class="card-body pt-0">
                        <div class="row mb-4" id="modal_select">
                            <div class="col-md-12">
                                <label class="required form-label">Tipo Profesion</label>
                                 <?php	
                                    $xSQL = "SELECT pde.pade_valorV AS Codigo,pde.pade_nombre AS Descripcion FROM `expert_parametro_cabecera` pca, `expert_parametro_detalle` pde WHERE pca.paca_id=pde.paca_id AND pca.pais_id=$xPaisid AND pca.empr_id=$xEmprid AND pca.paca_nombre='Tipo Profesion' AND pca.paca_estado='A' AND pde.pade_estado='A' ";
                                    $all_parametro = mysqli_query($con, $xSQL);    
                                ?>
                                <select name="cboTipoProfesion" id="cboTipoProfesion" aria-label="Seleccione Profesion" data-control="select2" data-placeholder="Seleccione Profesion" data-dropdown-parent="#modal_select" class="form-select mb-2" >
                                    <option></option>
                                    <?php foreach ($all_parametro as $parametro) : ?>
                                        <option value="<?php echo $parametro['Codigo'] ?>"><?php echo $parametro['Descripcion']; ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="required form-label">Tipo Documento</label>
                                <?php	
                                    $xSQL = "SELECT pde.pade_valorV AS Codigo,pde.pade_nombre AS Descripcion FROM `expert_parametro_cabecera` pca, `expert_parametro_detalle` pde WHERE pca.paca_id=pde.paca_id AND pca.pais_id=$xPaisid AND pca.empr_id=$xEmprid AND pca.paca_nombre='Tipo Documento' AND pca.paca_estado='A' AND pde.pade_estado='A' ";
                                    $all_parametro = mysqli_query($con, $xSQL);    
                                ?>
                                <select name="cboTipoDoc" id="cboTipoDoc" aria-label="Seleccione Tipo Documento" data-control="select2" data-placeholder="Seleccione Tipo Documento" data-dropdown-parent="#modal_select" class="form-select mb-2" >
                                    <option></option>
                                    <?php foreach ($all_parametro as $parametro) : ?>
                                        <option value="<?php echo $parametro['Codigo'] ?>"><?php echo mb_strtoupper($parametro['Descripcion']); ?></option>
                                    <?php endforeach ?>
                                </select>
                                </div>
                            <div class="col-md-6">
                                <label class="required form-label">Numero Documento</label>
                                <input type="text" name="txtNumDocumento" id="txtNumDocumento" class="form-control mb-2" maxlength="13" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" placeholder="Numero Documento"  />
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="required form-label">Nombres</label>
                                <input type="text" name="txtNombresProf" id="txtNombresProf" class="form-control mb-2" maxlength="100" placeholder="Nombres"  />
                            </div>
                            <div class="col-md-6">
                                <label class="required form-label">Apellidos</label>
                                <input type="text" name="txtApellidosProf" id="txtApellidosProf" class="form-control mb-2" maxlength="100" placeholder="Apellidos" />
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="fs-6 fw-bold mt-3 mb-3"><i class="fa fa-phone fa-1x me-2" style="color:#5AD1F1;" aria-hidden="true"></i>Telefono 1</div>
                                <input type="text" class="form-control mb-3" name="txtFono1Prof" id="txtFono1Prof" maxlength="9" placeholder="022222222" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                            </div>
                            <div class="col-md-4">
                                <div class="fs-6 fw-bold mt-3 mb-3"><i class="fa fa-phone fa-1x me-2" style="color:#5AD1F1;" aria-hidden="true"></i>Telefono 2</div>
                                <input type="text" class="form-control mb-3" name="txtFono2Prof" id="txtFono2Prof" maxlength="9" placeholder="022222222" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                            </div>
                            <div class="col-md-4">
                                <div class="fs-6 fw-bold mt-3 mb-3"><i class="fa fa-mobile fa-1x me-2" style="color:#5AD1F1;" aria-hidden="true"></i>Celular</div>
                                <input type="text" class="form-control mb-3" name="txtCelularProf" id="txtCelularProf" maxlength="10" placeholder="0999999999" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-8">
                                <label class="form-label"><i class="fa fa-envelope fa-1x me-2" style="color:#5AD1F1;" aria-hidden="true"></i>Email 1</label>
                                <input type="email" name="txtEmail1Prof" id="txtEmail1Prof" maxlength="100" placeholder="micorre@dominio.com" class="form-control mb-2 text-lowercase" value="<?php echo $xEmail1; ?>" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-check form-switch form-check-custom form-check-solid mt-5">
                                    <input class="form-check-input mt-5" name="chkEnviarprof1" id="chkEnviarprof1" type="checkbox" />
                                    <span id="spanEnv1" class="form-check-label fw-bold text-muted mt-3" for="chkEnviarprof1">No Enviar</span>
                                </label>    
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <label class="form-label"><i class="fa fa-envelope fa-1x me-2" style="color:#5AD1F1;" aria-hidden="true"></i>Email 2</label>
                                <input type="email" name="txtEmail2Prof" id="txtEmail2Prof" maxlength="100" placeholder="" class="form-control mb-2 text-lowercase" value="" />   
                            </div>
                            <div class="col-md-3">
                                <label class="form-check form-switch form-check-custom form-check-solid mt-5">
                                    <input class="form-check-input mt-5" name="chkEnviarprof2" id="chkEnviarprof2" type="checkbox" />
                                    <span id="spanEnv2" class="form-check-label fw-bold text-muted mt-3" for="chkEnviarprof2">No Enviar</span>
                                </label>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger border border-danger" data-bs-dismiss="modal"><i class="fa fa-times me-1" aria-hidden="true"></i>Cerrar</button>
                <button type="button" id="btnSaveProf" class="btn btn-sm btn-light-primary border border-primary"><i class="fa fa-hdd"></i>Grabar</button>
            </div>
        </div>
    </div>
</div>

<!--Modal Profesional /Configurar Horarios-->
<div class="modal fade" id="modal_Agregar_profesional" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Agregar Profesional - Configurar Horarios</h2>
                <h2 id="headerTitle" class="fs-6 fw-light text-primary"></h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body py-lg-2 px-lg-10">
                <div class="card card-flush pt-10 pb-n3">
                    <div class="card-body pt-0">
                        <div class="row mb-4" id="div_modal_profesional">
                            <div class="col-md-12">
                                <input type="hidden" name="txtprseid" id="txtprseid" class="form-control form-control-solid" value=""  />
                                <label class="required form-label">Profesion</label>
                                <?php 
                                    $xSQL = "SELECT pde.pade_valorV AS Codigo,pde.pade_nombre AS Descripcion FROM `expert_parametro_detalle` pde,`expert_parametro_cabecera` pca WHERE pca.pais_id=$xPaisid ";
                                    $xSQL .= "AND pca.paca_nombre='Tipo Profesion' AND pca.paca_id=pde.paca_id AND pca.paca_estado='A' AND pade_estado='A' ";
                                    $all_datos =  mysqli_query($con, $xSQL);
                                ?>
                                <select name="cboTipoProfe" id="cboTipoProfe" aria-label="Seleccione Tipo" data-control="select2" data-placeholder="Seleccione Tipo Profesion" data-dropdown-parent="#modal_Agregar_profesional" class="form-select mb-2" onchange="f_GetProfesional(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,this)">
                                    <option></option>
                                    <?php 
                                    foreach ($all_datos as $datos){ ?>
                                        <option value="<?php echo $datos['Codigo'] ?>"><?php echo $datos['Descripcion'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-md-10">
                                <label class="required form-label">Profesional</label>
                                <select name="cboProfesional" id="cboProfesional" aria-label="Seleccione Profesional" data-control="select2" data-placeholder="Seleccione Profesional" data-dropdown-parent="#modal_Agregar_profesional" class="form-select mb-2">
                                    <option></option>
                                </select> 
                            </div>
                            <div class="col-md-2">
                                 <label class="required form-label">Intervalo
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Intervalo de 10 a 60 minutos"></i>
                                 </label>
                                 <input type="number" name="txtIntervalo" id="txtIntervalo" min="5" max="60" step="5" class="form-control form-control-solid" value="5" onKeyPress="if(this.value.length==2) return false;"  pattern="/^-?\d+\.?\d*$/" />
                            </div>
                        </div>
                        <div class="form-group mt-5 mb-4">
                            <button type="button" data-repeater-create="" class="btn btn-sm btn-light-primary border border-primary" id="btnAgregarProfesional">
                                <i class="fa fa-plus me-1" aria-hidden="true"></i>Agregar Profesional
                            </button>
                        </div>
                        <table id="tblProfesional" class="table align-middle table-row-dashed table-hover fs-6 gy-5" style="width: 100%;">
                            <thead>
                                <tr class="text-start text-gray-800 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="min-w-125px">Profesional</th>
                                    <th class="min-w-125px">Tipo_Profesion</th>
                                    <th>Intervalo</th>
                                    <th class="min-w-125px">Estado</th>
                                    <th>Status</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody class="fw-bold text-gray-600"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger border border-danger" data-bs-dismiss="modal"><i class="fa fa-times me-1" aria-hidden="true"></i>Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!--Modal MOTIVOS ESPECIALIDAD-->
<div class="modal fade" id="modal_motivos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-800px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="mb-2 badge badge-light-primary fw-light fs-2 fst-italic">Motivos Servicios Prestador</h2>
                <h2 id="headerTitleMotivo" class="fs-6 fw-light text-primary"></h2>
                <input type="hidden" name="txtprseidcod" id="txtprseidcod" class="form-control form-control-solid" value=""  />
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body py-lg-2 px-lg-10">
                <div class="card card-flush pt-10 pb-n3">
                    <div class="card-body pt-0">
                        <div class="row mb-4">
                            <div class="col-md-12">
                                 <label class="required form-label">Motivo Servicio</label>
                                 <textarea class="form-control text-uppercase" name="txtmotivo" id="txtmotivo" maxlength="500" rows="2" onkeydown="return (event.keyCode!=13);"></textarea>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <button type="button" data-repeater-create="" class="btn btn-sm btn-light-primary border border-primary" id="btnAgregarMotivo">
                                <i class="fa fa-plus me-1" aria-hidden="true"></i> Agregar Motivo
                            </button>
                        </div>
                        <table id="tblMotivo" class="table align-middle table-row-dashed table-hover fs-6 gy-5" style="width: 100%;">
                            <thead>
                                <tr class="text-start text-gray-800 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="max-w-125px">Motivo</th>
                                    <th class="">Estado</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="fw-bold text-gray-600"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger border border-danger" data-bs-dismiss="modal"><i class="fa fa-times me-1" aria-hidden="true"></i>Cerrar</button>
            </div>
        </div>
    </div>
</div>   

<!--Modal Tipo Porfesion -->      
<div class="modal fade" id="modal_new_tipoprofesion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-800px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Nuevo Tipo Profesion</h2>
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
                            <div class="col-md-12">
                                <label class="required form-label">Tipo Profesion
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Ingrese Tipo Profesion (Medico-Odontolo-Educador-Plomero-etc.."></i>
                                </label>
                                <input type="text" class="form-control mb-2 text-uppercase" minlength="1" maxlength="150" placeholder="Tipo Profesion" name="txtTipoProfesion" id="txtTipoProfesion" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="required form-label">Codigo
                                    
                                </label>
                                <input type="text" class="form-control mb-2 text-uppercase" minlength="1" maxlength="100" placeholder="Codigo" name="txtCodigoTipo" id="txtCodigoTipo" />
                            </div>
                        </div>
                        <div class="form-group my-5">
                            <button type="button" data-repeater-create="" class="btn btn-sm btn-light-primary border border-primary" id="btnAgregarTipo">
                                <i class="fa fa-plus me-1" aria-hidden="true"></i>
                                Agregar
                            </button>
                        </div>
                        <div class="mh-300px scroll-y me-n7 pe-7">
                            <table id="tblTipoProfesion" class="table align-middle table-row-dashed table-hover fs-6 gy-5" style="width: 100%;">
                                <thead>
                                    <tr class="text-start text-gray-800 fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-125px">Tipo Profesion</th>
                                        <th>Estado</th>
                                        <th>Status</th>                                
                                        <th>Opciones</th>
                                    </tr>
                                </thead>

                                <?php 
                                    $xSQL = "SELECT pca.paca_id,pde.pade_id,pde.pade_nombre,pde.pade_estado,pde.pade_valorV FROM `expert_parametro_cabecera` pca, `expert_parametro_detalle` pde WHERE pca.paca_id=pde.paca_id AND pca.pais_id=$xPaisid AND pca.empr_id=$xEmprid AND pca.paca_nombre='Tipo Profesion' ORDER BY pde.pade_orden ";
                                    $all_tipos = mysqli_query($con, $xSQL);
                                ?>
                                <tbody class="text-gray-600 fw-bold">

                                    <?php 
                                                
                                        foreach($all_tipos as $tipo){
                                            $xPacaid = $tipo['paca_id'];
                                            $xPadeid = $tipo['pade_id'];
                                            $xTipoProfe = trim($tipo['pade_nombre']);
                                            $xValorV = trim($tipo['pade_valorV']);
                                            $xEstado = trim($tipo['pade_estado']);
                                        ?>
                                            <?php 

                                                $xChkSelecc = '';
                                                $xDisabledEdit = '';

                                                if($xEstado == 'A'){
                                                    $xChkSelecc = 'checked="checked"';
                                                    $xTextColor = "badge badge-light-primary";
                                                    $xEstadoTxt = 'ACTIVO';
                                                }else{
                                                    $xTextColor = "badge badge-light-danger";
                                                    $xDisabledEdit = 'disabled';
                                                    $xDisabledReset = 'disabled';
                                                    $xEstadoTxt = 'INACTIVO';
                                                }

                                            ?>
                                            <tr id="tr_<?php echo $xPadeid; ?>">
                                                <td>
                                                    <?php echo $xTipoProfe; ?>
                                                    <input type="hidden" id="txtPadeid<?php echo $xPadeid; ?>" value="<?php echo $xPadeid; ?>" />
                                                    <input type="hidden" id="txtTiprofe<?php echo $xPadeid; ?>" value="<?php echo $xTipoProfe; ?>" />
                                                    <input type="hidden" id="txtValor<?php echo $xPadeid; ?>" value="<?php echo $xValorV; ?>" />
                                                </td>
                                                
                                                <td id="td_<?php echo $xPadeid; ?>">
                                                    <div class="<?php echo $xTextColor; ?>"><?php echo $xEstadoTxt; ?></div>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                            <input <?php echo $xChkSelecc; ?> class="form-check-input h-20px w-20px border-primary" type="checkbox" id="chktipo<?php echo $xPadeid; ?>" 
                                                                onchange="f_UpdateEstTipo(<?php echo $xPacaid; ?>,<?php echo $xPadeid; ?>)" />
                                                        </div>
                                                    </div>
                                                </td> 													
                                                <td>
                                                    <div class="btn-group">
                                                        <button id="btnEdiTipo_<?php echo $xPadeid; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" <?php echo $xDisabledEdit; ?> title='Editar Tipo Profesion' data-bs-toggle="tooltip" data-bs-placement="left" onclick="f_EditarTipo(<?php echo $xPacaid; ?>,<?php echo $xPadeid; ?>)">
                                                            <i class='fa fa-edit'></i>
                                                        </button>	                                                
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
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger border border-danger" data-bs-dismiss="modal"><i class="fa fa-times me-1" aria-hidden="true"></i>Cerrar</button>
            </div>
        </div>
    </div>
</div>   

<!--Modal Horarios-Turnos -->             
<div class="modal fade" id="modal_horarios" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Configurar Horarios - Turnos</h2>
                <h5 class="text-primary fw-light" id="headertitu1"></h5>
                <input type="hidden" name="txtcodid" id="txtcodid" class="form-control form-control-solid" value=""  />
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
                        <div class="row">
                            <div class="col-md-4">
                                 <label class="required form-label">Seleccione Dia</label>
                                   <?php	
                                        $xSQL = "SELECT pde.pade_valorI AS Codigo,pde.pade_nombre AS Descripcion FROM `expert_parametro_cabecera` pca, `expert_parametro_detalle` pde WHERE pca.paca_id=pde.paca_id AND pca.pais_id=$xPaisid AND pca.empr_id=$xEmprid AND pca.paca_nombre='Dias Semana' AND pca.paca_estado='A' AND pde.pade_estado='A' ";
                                        $all_dias = mysqli_query($con, $xSQL);    
                                    ?>
                                    <select name="cboDias" id="cboDias" aria-label="Seleccione Dia" data-control="select2" data-placeholder="Dia" data-dropdown-parent="#modal_horarios" class="form-select mb-2" >
                                        <option></option>
                                        <?php foreach ($all_dias as $dias) : ?>
                                            <option value="<?php echo $dias['Codigo'] ?>"><?php echo $dias['Descripcion']; ?></option>
                                        <?php endforeach ?>
                                    </select>
                            </div>
                            <div class="col-md-4">
                                 <label class="required form-label">Hora Desde</label>
                                 <input class="form-control form-control-solid" name="txtHoraDesde" id="txtHoraDesde" placeholder="Hora Inicio" />
                            </div>
                            <div class="col-md-4">
                                 <label class="required form-label">Hora Hasta</label>
                                  <input class="form-control form-control-solid" name="txtHoraHasta" id="txtHoraHasta" placeholder="Hora Hasta" />
                            </div>
                        </div>
                         <div class="form-group my-5">
                            <button type="button" data-repeater-create="" class="btn btn-sm btn-light-primary border border-primary" id="btnAgregarHorario">
                                <i class="fa fa-plus me-1" aria-hidden="true"></i>Agregar Horario
                            </button>
                        </div>
                         <div class="separator my-7"></div>
                        <h2 class="fw-normal">Turnos Asignados</h2>
                        <div class="mh-300px scroll-y me-n7 pe-7">
                            <table id="tblHorarios" class="table align-middle table-row-dashed table-hover fs-6 gy-5" style="width: 100%;">
                                <thead>
                                    <tr class="text-start text-gray-800 fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-125px">Dia</th>
                                        <!-- <th>Intervalo</th> -->
                                        <th class="min-w-125px">H.Desde</th>
                                        <th class="min-w-125px">H.Hasta</th>
                                        <th class="min-w-125px">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-bold text-gray-600">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger border border-danger" data-bs-dismiss="modal"><i class="fa fa-times me-1" aria-hidden="true"></i>Cerrar</button>
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

        _enviarmail1 = "NO";
        _enviarmail2 = "NO";
        
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

        $('#modal_horarios').on('hidden.bs.modal', function () {
            $("#modal_Agregar_profesional").modal("show");

        });


        $('#txtHoraDesde').flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            defaultDate: "07:00"
        });

        $('#txtHoraHasta').flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            defaultDate: "12:00"
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

        var regex = /^\d{1,3}(\.\d{1,3})?$/;
        
        if (regex.test($('#txtRed').val().trim())){
        }else{
            toastSweetAlert("top-end",3000,"warning","Valor ingresado incorrecto (ejmpl: 100.12)..! ");
            $("#txtRed").val('');
            return;
        }

        if (regex.test($('#txtPvp').val().trim())){
        }else{
            toastSweetAlert("top-end",3000,"warning","Valor ingresado incorrecto (ejmpl: 100.12)..!");
            $("#txtPvp").val('');
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

                _id = response;
                _output = '<tr id=row_' + _id + '>';
                _output += '<td>' + _txtasistencia + '<input type="hidden" id="txtAsistencia_' + _asistid + '" value="' + _asistid + '"/></td>';
                _output += '<td>' + _txtatencion  + '</td>';
                _output += '<td>' + _red  + '</td>';
                _output += '<td>' + _pvp  + '</td>';
                _output += '<td id="td_' + _id + '"><div class="badge badge-light-primary">ACTIVO</div></td>';
                _output += '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid">'; 
                _output += '<input class="form-check-input h-20px w-20px border-primary" checked="checked" type="checkbox" id="chk' + _id + '" onchange="f_UpdateEstado(';
                _output += _paisid + ',' + _emprid + ',' + _id + ')" value="' + _id + '"/></div></div></td>';
                _output += '<td><div class="text-center"><div class="btn-group"><button id="btnEditar_' + _id + '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 " ';
                _output += 'title="Editar Servicio" data-bs-toggle="tooltip" data-bs-placement="left" onclick="f_EditarServicio(' ;
                _output += _paisid + ',' + _emprid + ',' + _id + ')"><i class="fa fa-edit"></i></button>';
                _output += '<button id="btnPerson_' + _id + '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" onclick="f_AgregarProfesional(';
                _output += _paisid + ',' + _emprid + ',' + _id + ')" title="Agregar Profesional" data-bs-toggle="tooltip" data-bs-placement="left">' ;
                _output += '<i class="fas fa-user"></i></button>';
                _output += '<button id="btnMotivos_' + _id + '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" onclick="f_AgregarMotivos(' ;
                _output += _paisid + ',' + _emprid + ',' + _id + ')" title="Agregar Motivos" data-bs-toggle="tooltip" data-bs-placement="left">';
                _output += '<i class="fas fa-book"></i></button>';
                _output += '</div></div></td></tr>';
                $('#tblServicio').append(_output);
                
                $("#agregar_servicio").modal("hide");
                $("#cboAsis").val(0).change();   
                $("#agregar_servicio").find("input,textarea").val("");
                toastSweetAlert("top-end",3000,"success","Servicio Agregado");
            }else{
                toastSweetAlert("top-end",3000,"error","Servicio ya Existe..!!");  
            }     
        });

    });

    $('#btneditarservicio').click(function(){

        var _prseid = $('#txtcodprseid').val();
        var _txtatencion = $.trim($('#txtEditAten').val()).toUpperCase();
        var _txtatencionold = $.trim($('#txtEditAtenold').val()).toUpperCase();
        var _red = $.trim($("#txtEditRed").val());
        var _pvp = $.trim($("#txtEditPvp").val());

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

        var regex = /^\d{1,3}(\.\d{1,3})?$/;

        if (regex.test($('#txtEditRed').val().trim())){
        }else{
            toastSweetAlert("top-end",3000,"warning","Valor ingresado incorrecto (ejmpl: 100.12)..! ");
            $("#txtEditRed").val('');
            return;
        }

        if (regex.test($('#txtEditPvp').val().trim())){
        }else{
            toastSweetAlert("top-end",3000,"warning","Valor ingresado incorrecto (ejmpl: 100.12)..!");
            $("#txtEditPvp").val('');
            return;
        }        

        _red = _red.replace(/[a-z]/g,'0');
        _pvp = _pvp.replace(/[a-z]/g,'0');

        _red = parseFloat(_red)
        _pvp = parseFloat(_pvp)

        var _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxUsuaid" : _usuaid,
            "xxPresid" : _prseid,
            "xxAtencion" : _txtatencion,
            "xxAtencionold" : _txtatencionold,
            "xxRed" : _red,
            "xxPvp" : _pvp  
        }

        var xrespuesta = $.post("codephp/update_datosservicio.php", _parametros);
        xrespuesta.done(function(response){
            if(response == 0){
                _id = response;
            }else{
                toastSweetAlert("top-end",3000,"error","Tipo de Atencion ya Existe..!");  
            }     
        });

    });


    //Editar Servicio Modal
    function f_EditarServicio(_paisid,_emprid,_prseid){

        $("#txtcodprseid").val(_prseid);

        var _parametros = {

            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxPrseid" : _prseid
        }  

        var xrespuesta = $.post("codephp/get_datoservipresta.php", _parametros );
        xrespuesta.done(function(response){

            var _datos = JSON.parse(response);
            //console.log(_datos);

            $.each(_datos,function(i,item){
                _asistencia = _datos[i].Asistencia;
                _atencion = _datos[i].Atencion;
                _red = _datos[i].Red;
                _pvp = _datos[i].Pvp;

                $('#txtEditAsis').val(_asistencia);  
                $('#txtEditAten').val(_atencion);
                $('#txtEditAtenold').val(_atencion);
                $('#txtEditRed').val(_red); 
                $('#txtEditPvp').val(_pvp);  

            });
        });
        
        $("#editar_servicio").modal("show");
    }

    function f_ConfHorario(_paisid, _emprid, _pfesid){

        var tb = document.getElementById('tblHorarios');
            while(tb.rows.length > 1) {
            tb.deleteRow(1);
        }

        $("#txtcodid").val(_pfesid);

        var _selprofesional = $('#txtProfesional_' + _pfesid).val();
        //document.getElementById("headertitu1").innerHTML = "Especialidad: " + _selespecialidad + "<br><br>" + "Profesional: " + _selprofesional;
        document.getElementById("headertitu1").innerHTML = "Profesional: " + _selprofesional;

        $("#cboDias").val(0).change();
        //$("#txtIntervalo").val(10);
        $("#txtHoraDesde").val('07:00');
        $("#txtHoraHasta").val('12:00');

        var _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxPfesid" : _pfesid
        }

        $.ajax({
            url: "codephp/get_profesionalhorario.php",
            type: "POST",
            dataType: "json",
            data: _parametros,
            success: function(response){ 
                $.each(response, function(i, item){

                    _id = item.Id;
                    _dia = item.Dia;
                    //_intervalo = item.Intervalo;6
                    _horadesde = item.HoraDesde;
                    _horafdesde = _horadesde.substring(0,5);
                    _horahasta = item.HoraHasta;
                    _horafhasta = _horahasta.substring(0,5);

                    _output = '<tr id="trhorario_' + _id + '">';
                    _output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _dia + '</span></div></div></td>';
                    //_output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _intervalo + '</span></div></div></td>';
                    _output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _horafdesde + '</span></div></div></td>';
                    _output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _horafhasta + '</span></div></div></td>';
                    _output += '<td class=""><div class=""><div class="btn-group">'
                    _output += '<button id="btnDelHorario_' + _id + '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1"  onclick="f_DelHorario(';
                    _output += _paisid + ',' + _emprid + ',' + _id + ')" title="Eliminar Turno/Horario" ><i class="fa fa-trash"></i></button></div></div></td></tr>'

                    $('#tblHorarios').append(_output);

                });

                $("#modal_Agregar_profesional").modal("hide");
                $("#modal_horarios").modal("show");
                $('#modal_horarios').modal('handleUpdate');                           
            },
            error: function (error){
                console.log(error);
            }
        });
    }

    function f_DelAsigProf(_paisid, _emprid, _pfesid){

        _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxPfesid" : _pfesid
        }

        var xrespuesta = $.post("codephp/consultar_horarioprofesional.php", _parametros);
            xrespuesta.done(function(response){
            if(response.trim() == 'OK'){
                toastSweetAlert("top-end",3000,"error","Registro Eliminado");
                $('#trprof_' + _pfesid).remove();
            }else{
                toastSweetAlert("top-end",3000,"warning","El registro tiene horarios configurados..!!");
            }
        });
    }

    function f_DelHorario(_paisid, _emprid, _horaid){

        let _usuaid = "<?php echo $xUsuaid; ?>";

        _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxUsuaid" : _usuaid,
            "xxHoraid" : _horaid
        }

        var xrespuesta = $.post("codephp/del_horarioprofesional.php", _parametros);
            xrespuesta.done(function(response){
            if(response.trim() == 'OK'){
                toastSweetAlert("top-end",3000,"error","Registro Eliminado");
                $('#trhorario_' + _horaid).remove();
            }else{
                toastSweetAlert("top-end",3000,"question","Hubo algun error, no se puedo eliminar..!!");
            }
        });
    }66

    $('#btnAgregarHorario').click(function(e){

        var _dia = $('#cboDias').val();
        var _intervalo = 0;
        //var _intervalo = $('#txtIntervalo').val();
        var _horadesde = $('#txtHoraDesde').val();
        var _horahasta = $('#txtHoraHasta').val();
        var _selecpfesid = $('#txtcodid').val();                

        var _diatext = $('#cboDias option:selected').text();

        if(_dia == null){
            toastSweetAlert("top-end",3000,"warning","Seleccione Dia..!!");
            return;
        }
        
        if(_horadesde == ''){
            toastSweetAlert("top-end",3000,"warning","Seleccione Hora Inicio..!!");
            return;
        }

        if(_horahasta == ''){
            toastSweetAlert("top-end",3000,"warning","Seleccione Hora Final..!!");
            return;
        }                

        //VALIDAR LAS HORAS

        var minutos_inicio = _horadesde.split(':').reduce((p, c) => parseInt(p) * 60 + parseInt(c));
        var minutos_final = _horahasta.split(':').reduce((p, c) => parseInt(p) * 60 + parseInt(c));
        
        if (minutos_final < minutos_inicio || minutos_inicio == minutos_final ){
            toastSweetAlert("top-end",3000,"question","La Hora Inicio no puede ser menor/igual a la Hora Final..!!");
            return;
        }

        var _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxUsuaid" : _usuaid,
            "xxPfesid" : _selecpfesid,
            "xxDia" : _dia,
            "xxDiaText" : _diatext,
            "xxIntervalo" : _intervalo,
            "xxHoraInicio" : _horadesde,
            "xxHoraFin" : _horahasta
        }	

        var xrespuesta = $.post("codephp/grabar_turnohorarios.php", _parametros);
        xrespuesta.done(function(response){
            if(response > 0){

                _id = response.toString().trim();

                _output = '<tr id="trhorario_' + _id.trim() + '">';
                _output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _diatext + '</span></div></div></td>';
                //_output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _intervalo + '</span></div></div></td>';
                _output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _horadesde + '</span></div></div></td>';
                _output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _horahasta + '</span></div></div></td>';
                _output += '<td class=""><div class=""><div class="btn-group">';
                _output += '<button id="btnDelHorario_' + _id + '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1" onclick="f_DelHorario(';
                _output += _paisid + ',' + _emprid + ',' + _id + ')" title="Eliminar Turno/Horario" ><i class="fa fa-trash"></i></button></div></div></td></tr>'

                $('#tblHorarios').append(_output);
                toastSweetAlert("top-end",3000,"success","Horario Agregado");
                $("#txtHoraDesde").val('07:00');
                $("#txtHoraHasta").val('12:00');

            }else{
                toastSweetAlert("top-end",3000,"warning","Dia/Horario ya Existe..!!");
            }

            $("#cboDias").val(0).change(); 
            $("#cboProfesional").val(0).change(); 

        });

    });         

    function f_GetProfesional(_paisid, _emprid, obj){

        _tipoprofe = obj.value;

        _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprid" : _paisid,
            "xxTipoProfe" : _tipoprofe  
        }

        $("#cboProfesional").empty();

        var _respuesta = $.post("codephp/get_dropprofesional.php", _parametros);
        _respuesta.done(function(response) {
            $("#cboProfesional").html(response);
            
        });
    }
    
    $('#btnAgregarProfesional').click(function(e){

        var _tipoprofesion = $("#cboTipoProfe option:selected").text();
        var _profesional = $('#cboProfesional option:selected').text();
        var _intervalo = $('#txtIntervalo').val();
        var _profid = $("#cboProfesional").val();
        var _prseid = $("#txtprseid").val();

        if(_tipoprofesion == ''){
            toastSweetAlert("top-end",3000,"warning","Seleccione Tipo Profesion..!!"); 
            return;
        }

        if(_profesional == ''){
            toastSweetAlert("top-end",3000,"warning","Seleccione Profesional..!!"); 
            return;
        }
            
        var _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxUsuaid" : _usuaid,
            "xxPrseid" : _prseid,
            "xxIntervalo" : _intervalo,
            "xxProfid" : _profid
        }	

        var xrespuesta = $.post("codephp/grabar_profesionalespeci.php", _parametros);
        xrespuesta.done(function(response){
            if(response > 0){

                _id = response.trim();

                _output = '<tr id="trprof_' + _id + '">';
                _output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _profesional + '</span><input type="hidden" id="txtProfesional_' + _id + '" value="' + _profesional +  '" /></div></div></td>';
                _output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _tipoprofesion + '</span></div></div></td>';
                _output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _intervalo + '</span></div></div></td>';
                _output += '<td id="tdprof_' + _id + '"><div class="d-flex align-items-center"><div class="ms-0"><div class="badge badge-light-primary">ACTIVO</div></div></div></td>';
                _output += '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid"> '; 
                _output += '<input class="form-check-input h-20px w-20px border-primary" type="checkbox" checked="checked" id="chkprof' + _id + '" onchange="f_UpdateEstProf(';
                _output += _paisid + ',' + _emprid + ',' + _id + ')" value="' + _id + '"/></div></div></td>';
                _output += '<td class=""><div class=""><div class="btn-group">';
                _output += '<button id="btnHorario_' + _id  + '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" onclick="f_ConfHorario(';
                _output += _paisid + ',' + _emprid + ',' + _id + ')" title="Configurar Horario" ><i class="fas fa-cogs"></i></button>';                        
                _output += '<button id="btnDelProf_' + _id + '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1" onclick="f_DelAsigProf(';
                _output += _paisid + ',' + _emprid + ',' + _id + ')" title="Eliminar Profesional Asignado" ><i class="fa fa-trash"></i></button></div></div></td></tr>'
                
                $('#tblProfesional').append(_output);
                toastSweetAlert("top-end",3000,"success","Agregado Correctamente"); 

            }else{
                toastSweetAlert("top-end",3000,"error","Profesional ya Existe..!!"); 
            }

            $("#cboTipoProfe").val(0).change(); 
            $("#cboProfesional").val(0).change(); 
            $("#txtIntervalo").val('5'); 

        });

    });    

    //Modal Agregar Profesional / Configurar Horarios
    function f_AgregarProfesional(_paisid,_emprid,_prseid){

        $("#txtprseid").val(_prseid);
        $("#cboTipoProfe").val(0).change(); 
        $("#cboProfesional").val(0).change(); 
        $("#txtIntervalo").val('5');

        //$("#modal_Agregar_profesional").modal("show");

        var tb = document.getElementById('tblProfesional');
            while(tb.rows.length > 1) {
            tb.deleteRow(1);
        }                

        //_selpreeid = _preeid;

        //_selespecialidad = $('#txtEspeciPrestador' + _preeid).val();
        //document.getElementById("headerTitle").innerHTML = "Especialidad: " + _selespecialidad;

        var _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxPrseid" : _prseid
        }

        $.ajax({
            url: "codephp/get_datosprofesional.php",
            type: "POST",
            dataType: "json",
            data: _parametros,
            success: function(response){ 
                $.each(response, function(i, item){

                    _id = item.Id;
                    _nombres = item.Nombres;
                    _tipoprofe = item.Profesion;
                    _estado = item.Estado;
                    _intervalo = item.Intervalo;
                    _checked = '';
                    _disabledbtn1 = '';
                    _disabledbtn2 = '';

                    if(_estado == "ACTIVO"){
                        _checked = "checked='checked'";
                        _textcolor = "badge badge-light-primary";
                    }else{
                        _textcolor = "badge badge-light-danger";
                        _disabledbtn1 = 'disabled';
                        _disabledbtn2 = 'disabled';
                    }

                    _output = '<tr id="trprof_' + _id + '">';
                    _output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _nombres + '</span><input type="hidden" id="txtProfesional_' + _id + '" value="' + _nombres + '" /></div></div></td>';
                    _output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _tipoprofe + '</span></div></div></td>';
                    _output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _intervalo + '</span></div></div></td>';
                    _output += '<td id="tdprof_' + _id + '"><div class="d-flex align-items-center"><div class="ms-0"><div class="' + _textcolor + '">' + _estado + '</div></div></div></td>';
                    _output += '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid"> '; 
                    _output += '<input class="form-check-input h-20px w-20px border-primary" ' +  _checked + ' type="checkbox" id="chkprof' + _id + '" onchange="f_UpdateEstProf(';
                    _output += _paisid + ',' + _emprid + ',' + _id + ')" value="' + _id + '"/></div></div></td>';
                    _output += '<td class=""><div class=""><div class="btn-group">'
                    _output += '<button id="btnHorario_' + _id  + '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnhorario"' + _disabledbtn1 + ' onclick="f_ConfHorario(';
                    _output += _paisid + ',' + _emprid + ',' + _id + ')" title="Configurar Horario" data-bs-toggle="tooltip" data-bs-placement="left" ><i class="fas fa-cogs"></i></button>';
                    _output += '<button id="btnDelProf_' + _id + '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1"' + _disabledbtn2 + ' onclick="f_DelAsigProf(';
                    _output += _paisid + ',' + _emprid + ',' + _id + ')" title="Eliminar Profesional Asignado" data-bs-toggle="tooltip" data-bs-placement="left" ><i class="fa fa-trash"></i></button></div></div></td></tr>'

                    $('#tblProfesional').append(_output);

                });

                //$("#cboTipoProfe").val(0).change(); 
                //$("#txtIntervalo").val(10);
                //$("#modal_profesional").find("input,textarea").val("");
                $("#modal_Agregar_profesional").modal("show");
                $('#modal_Agregar_profesional').modal('handleUpdate');                           
            },
            error: function (error){
                console.log(error);
            }
        });

    }

    //Modal Agregar Motivos Servicios
    function f_AgregarMotivos(_paisid, _emprid, _prseid){
        
        //console.log(_prseid);
        $("#txtprseidcod").val(_prseid);
        //$("#modal_motivos").modal("show");

        var tb = document.getElementById('tblMotivo');
            while(tb.rows.length > 1) {
            tb.deleteRow(1);
        }

        var _selespecialidad = $('#txtAsistencia_' + _prseid).val();
        console.log(_selespecialidad);
        document.getElementById("headerTitleMotivo").innerHTML = "Asistencia: " + _selespecialidad;

        var _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxPrseid" : _prseid
        }

        $.ajax({
            url: "codephp/get_datosmotivo.php",
            type: "POST",
            dataType: "json",
            data: _parametros,
            success: function(response){ 
                $.each(response, function(i, item){

                    _id = item.mtes_id;
                    _motivo = item.motivos_especialidad;
                    _estado = item.mtes_estado;
                    _checked = '';
                    _disabledbtn1 = 'disabled';

                    if(_estado == "A"){
                        _checked = "checked='checked'";
                        _textcolor = "badge badge-light-primary";
                        _estado = 'ACTIVO';
                    }else{
                        _textcolor = "badge badge-light-danger";
                        _disabledbtn1 = 'disabled';
                        _disabledbtn2 = 'disabled';
                        _estado = 'INACTIVO';
                    }

                    _output = '<tr id="trmot_' + _id + '">';
                    _output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _motivo + '</span><input type="hidden" id="txtMotivo_' + _id + '" value="' + _motivo + '" /></div></div></td>';
                    _output += '<td id="tdmot_' + _id + '"><div class="d-flex align-items-center"><div class="ms-0"><div class="' + _textcolor + '">' + _estado + '</div></div></div></td>';
                    _output += '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid"> '; 
                    _output += '<input class="form-check-input h-20px w-20px border-primary" ' +  _checked + ' type="checkbox" id="chkmoti' + _id + '" onchange="f_UpdateMotivo(';
                    _output += _paisid + ',' + _emprid + ',' + _id + ')" value="' + _id + '"/></div></div></td></tr>';

                    $('#tblMotivo').append(_output);

                });

                $("#modal_motivos").modal("show");
                $('#modal_motivos').modal('handleUpdate');                           
            },
            error: function (error){
                console.log(error);
            }
        });
      

    }

    //Modal Profesional 
    $('#btnNuevoProfesional').click(function(){

        $("#cboTipoProfesion").val(0).change(); 
        $("#cboTipoDoc").val(0).change();    
        document.getElementById('imgfileprof').style.backgroundImage="url(assets/images/users/user.png)";
        $("#modal_profesional").find("input,textarea").val("");
        $('#modal_profesional').modal('handleUpdate');
        $("#modal_profesional").modal("show");
    });

    $("#btnNuevaProfesion").click(function(){
            
        //$("#modal_new_tipoprofesion").find("input,textarea").val("");
        const btn = document.getElementById('btnAgregarTipo');
        btn.innerHTML = '<i class="fa fa-plus me-1" aria-hidden="true"></i>Agregar';

        $("#txtTipoProfesion").val('');
        $("#txtCodigoTipo").val('');
        $("#modal_new_tipoprofesion").modal("show");
        $('#modal_new_tipoprofesion').modal('handleUpdate');
    });    


    $(document).on("click","#chkEnviarprof1",function(){
        
        var _chanspan = document.getElementById("spanEnv1");
        var _emailprof =  $.trim($('#txtEmail1Prof').val());

        if(_emailprof != ''){
            var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
        
            if (regex.test($('#txtEmail1Prof').val().trim())){
                if($("#chkEnviarprof1").is(":checked")){
                    _chanspan.innerHTML = '<span id="spanEnv1" class="form-check-label fw-bold" for="chkEnviar1"><strong>Enviar</strong></span>';
                    _enviarmail1 = 'SI';
                }else{
                    _chanspan.innerHTML = '<span id="spanEnv1" class="form-check-label fw-bold text-muted" for="chkEnviar1">No Enviar</span>';
                    _enviarmail1 = 'NO';
                }
            }else{
                $('#chkEnviarprof1').prop('checked','');
                toastSweetAlert("top-end",3000,"error","Email no es Valido..!!");
                _enviarmail1 = 'SI';
                return;
            }
        }else{
            $('#chkEnviarprof1').prop('checked','');
            _enviarmail1 = 'NO';
        }
    });

    $(document).on("click","#chkEnviarprof2",function(){
            
            var _chanspan = document.getElementById("spanEnv2");
            var _emailprof =  $.trim($('#txtEmail2Prof').val());
    
            if(_emailprof != ''){
                var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
            
                if (regex.test($('#txtEmail2Prof').val().trim())){
                    if($("#chkEnviarprof2").is(":checked")){
                        _chanspan.innerHTML = '<span id="spanEnv2" class="form-check-label fw-bold" for="chkEnviarprof2"><strong>Enviar</strong></span>';
                        _enviarmail2 = 'SI';
                    }else{
                        _chanspan.innerHTML = '<span id="spanEnv2" class="form-check-label fw-bold text-muted" for="chkEnviarprof2">No Enviar</span>';
                        _enviarmail2 = 'NO';
                    }
                }else{
                    $('#chkEnviarprof2').prop('checked','');
                    toastSweetAlert("top-end",3000,"error","Email no es Valido..!!");
                    _enviarmail2 = 'SI';
                    return;
                }
            }else{
                $('#chkEnviarprof2').prop('checked','');
                _enviarmail2 = 'NO';
            }
        });    


    $('#btnSaveProf').click(function(e){

        var _paisid = "<?php echo $xPaisid; ?>";
        var _emprid = "<?php echo $xEmprid; ?>";
        var _usuaid = "<?php echo $xUsuaid; ?>";
        //var _tipodoc = $("#cboTipoDoc option:selected").text();
        var _tipodoc = $("#cboTipoDoc").val();
        var _numdocumento = $.trim($("#txtNumDocumento").val());
        var _nombres = $.trim($("#txtNombresProf").val());
        var _apellidos = $.trim($("#txtApellidosProf").val());
        //var _genero = $("#cboTipoGenero option:selected").text();
        var _tipoprof = $("#cboTipoProfesion option:selected").text();
        var _tipoprofv = $("#cboTipoProfesion").val();
        //var _direccion = $.trim($("#txtDireccionProf").val());
        var _telefono1 = $.trim($("#txtFono1Prof").val());
        var _telefono2 = $.trim($("#txtFono2Prof").val());
        var _celular = $.trim($("#txtCelularProf").val());
        var _emailprof1 = $.trim($("#txtEmail1Prof").val());
        var _emailprof2 = $.trim($("#txtEmail2Prof").val());
        var _selecc = 'NO'; 
        var _continuar = true;

        var _imgfile = document.getElementById("imgfileprof").style.backgroundImage;
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
        }else{
            _file = '';
        }

        if(_tipodoc == ''){
            toastSweetAlert("top-end",3000,"warning","Seleccione Tipo Documento..!");
            return;                    
        }

        if(_numdocumento == ''){
            toastSweetAlert("top-end",3000,"warning","Ingrese Documento..!!");
            return;                    
        }

        if(_numdocumento != ''){
            _valor = document.getElementById("txtNumDocumento").value;
            if( !(/^(\d{10}|\d{13})$/.test(_valor)) ) {
                toastSweetAlert("top-end",3000,"error","Documento Incorrecto..!!");  
                return;
            }                
        }

        if(_nombres == ''){
            toastSweetAlert("top-end",3000,"warning","Ingrese Nombres..!!");
            return;                    
        }

        if(_apellidos == ''){
            toastSweetAlert("top-end",3000,"warning","Ingrese Apellidos..!!");
            return;                    
        }

        //if(_genero == ''){
        //    toastSweetAlert("top-end",3000,"warning","Seleccione Genero..!!");
        //    return;                    
        //}

        if(_tipoprof == ''){
            toastSweetAlert("top-end",3000,"warning","Seleccione Profesion..!!");
            return;                    
        }  

        if(_telefono1 != '')
        {
            _valor = document.getElementById("txtFono1Prof").value;
            if( !(/^(\d{7}|\d{9})$/.test(_valor)) ) {
                toastSweetAlert("top-end",3000,"error","Telefono Incorrecto..!!");  
                return;
            }
        }   

        if(_telefono2 != '')
        {
            _valor = document.getElementById("txtFono2Prof").value;
            if( !(/^(\d{7}|\d{9})$/.test(_valor)) ) {
                toastSweetAlert("top-end",3000,"error","Telefono Incorrecto..!!");  
                return;
            }
        }         

        if(_celular != '')
        {
            _valor = document.getElementById("txtCelularProf").value;
            if( !(/^\d{10}$/.test(_valor)) ) {
                toastSweetAlert("top-end",3000,"error","Celular Incorrecto..!!"); 
                return;
            }
        }

        if(_emailprof1.trim() != ''){
            var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

            if (regex.test($('#txtEmail1Prof').val().trim())) {
            }else{
                toastSweetAlert("top-end",3000,"error","Email Incorrecto..!!");
                return;
            }
        }

        if(_enviarmail1 == 'SI'){
            if(_emailprof1.trim() == ''){
                toastSweetAlert("top-end",3000,"warning","Ingrese Email..!!");
            }
        }

        if(_emailprof2.trim() != ''){
            var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

            if (regex.test($('#txtEmail2Prof').val().trim())) {
            }else{
                toastSweetAlert("top-end",3000,"error","Email Incorrecto..!!");
                return;
            }
        }

        if(_enviarmail2 == 'SI'){
            if(_emailprof2.trim() == ''){
                toastSweetAlert("top-end",3000,"warning","Ingrese Email..!!");
            }
        }        

        form_data = new FormData();                    
        form_data.append('xxPaisid', _paisid);
        form_data.append('xxEmprid', _emprid);
        form_data.append('xxUsuaid', _usuaid);
        form_data.append('xxTipoDoc', _tipodoc);
        form_data.append('xxNumDoc', _numdocumento);
        form_data.append('xxNombres', _nombres);
        form_data.append('xxApellidos', _apellidos);
        //form_data.append('xxGenero', _genero);
        form_data.append('xxTipoProfesion', _tipoprofv);
        //form_data.append('xxDireccion', _direccion);
        form_data.append('xxFono1', _telefono1);
        form_data.append('xxFono2', _telefono2);
        form_data.append('xxCelular', _celular);
        form_data.append('xxEmail1', _emailprof1);        
        form_data.append('xxEnviar1', _enviarmail1);
        form_data.append('xxEmail2', _emailprof2);        
        form_data.append('xxEnviar2', _enviarmail2);
        form_data.append('xxFile', _file);

        $.ajax({
            url: "codephp/grabar_profesional.php",
            type: "post",
            data: form_data,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response){
                
                if(response.trim() == 'OK'){
                    toastSweetAlert("top-end",3000,"success","Profesional Agregado");
                    $("#modal_profesional").modal("hide");

                }else{
                    toastSweetAlert("top-end",3000,"warning","Profesional ya Existe..!!");
                }
            },								
            error: function (error){
                console.log(error);
            }
        });
    });

    $('#btnAgregarMotivo').click(function(e){

        var _motivo = $('#txtmotivo').val();
        var _selprseid = $('#txtprseidcod').val();

        if(_motivo == ''){
            toastSweetAlert("top-end",3000,"warning","Ingrese Motivo..!"); 
            return;
        }                

        var _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxUsuaid" : _usuaid,
            "xxPrseid" : _selprseid,
            "xxMotivo" : _motivo
        }	

        var xrespuesta = $.post("codephp/grabar_motivoespecialidad.php", _parametros);
        xrespuesta.done(function(response){
            if(response > 0){

                _id = response.trim();

                _estado = 'ACTIVO';
                _checked = "checked='checked'";
                _textcolor = "badge badge-light-primary";                

                _output = '<tr id="trmot_' + _id + '">';
                _output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _motivo.toUpperCase() + '</span><input type="hidden" id="txtMotivo_' + _id + '" value="' + _motivo + '" /></div></div></td>';
                _output += '<td id="tdmot_' + _id + '"><div class="d-flex align-items-center"><div class="ms-0"><div class="' + _textcolor + '">' + _estado + '</div></div></div></td>';
                _output += '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid"> '; 
                _output += '<input class="form-check-input h-20px w-20px border-primary" ' +  _checked + ' type="checkbox" id="chkmoti' + _id + '" onchange="f_UpdateMotivo(';
                _output += _paisid + ',' + _emprid + ',' + _id + ')" value="' + _id + '"/></div></div></td></tr>';

                $('#tblMotivo').append(_output);

            }else{
                toastSweetAlert("top-end",3000,"error","Error en Ajax-grabar_motivoespecialidad"); 
            }

            $("#txtmotivo").val(''); 
        });
    });
    
    function f_UpdateMotivo(_paisid, _emprid, _motid){

        let _usuaid = "<?php echo $xUsuaid; ?>";
        let _check = $("#chkmoti" + _motid).is(":checked");
        let _checked = "";
        let _class = "badge badge-light-primary";
        let _td = "tdmot_" + _motid;

        if(_check){
            _estado = "ACTIVO";
            _checked = "checked='checked'";
        }else{                    
            _estado = "INACTIVO";
            _class = "badge badge-light-danger";
        }

        var _changetd = document.getElementById(_td);
        _changetd.innerHTML = '<div class="d-flex align-items-center"><div class="ms-0"><div class="' + _class + '">' + _estado + ' </div></div>';

        var _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxUsuaid" : _usuaid,
            "xxMotivoid" : _motid,
            "xxEstado" : _estado
        }	

        var xrespuesta = $.post("codephp/update_estadomotivoespeci.php", _parametros);
            xrespuesta.done(function(response){

        });
    }

    //Update estado Especialidades 
    function f_UpdateEstado(_paisid, _emprid, _prseid){

        let _usuaid = "<?php echo $xUsuaid; ?>";
        let _check = $("#chk" + _prseid).is(":checked");
        let _checked = "";
        let _class = "badge badge-light-primary";
        let _td = "td_" + _prseid;
        let _btnedit = "btnEditar_" + _prseid;
        let _btnper = "btnPerson_" + _prseid;
        let _btnmot = "btnMotivos_" + _prseid;


        if(_check){
            _estado = "ACTIVO";
            _checked = "checked='checked'";
            $('#'+_btnedit).prop("disabled",false);
            $('#'+_btnper).prop("disabled",false);
            $('#'+_btnmot).prop("disabled",false);
        }else{                    
            _estado = "INACTIVO";
            _class = "badge badge-light-danger";
            $('#'+_btnedit).prop("disabled",true);
            $('#'+_btnper).prop("disabled",true);
            $('#'+_btnmot).prop("disabled",true);
        }

        var _changetd = document.getElementById(_td);
        _changetd.innerHTML = '<div class="' + _class + '">' + _estado + ' </div>';

        var _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxPreeid" : _preeid,
            "xxEstado" : _estado
        }	

        var xrespuesta = $.post("codephp/update_estadoespecipresta.php", _parametros);
            xrespuesta.done(function(response){

        });	
    }


    //Desplazar-modal
    $("#modal-new-especialidad").draggable({
        handle: ".modal-header"
    }); 

    $("#modal-add-especialidad").draggable({
        handle: ".modal-header"
    });
    
    $("#modal-editar-especialidad").draggable({
        handle: ".modal-header"
    });
    
    $("#modal_new_tipoprofesion").draggable({
        handle: ".modal-header"
    });

    $("#modal_profesional").draggable({
        handle: ".modal-header"
    });

    $("#modal_horarios").draggable({
        handle: ".modal-header"
    }); 
    
    $("#modal-motivos").draggable({
        handle: ".modal-header"
    });     

   

</script>

