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
        $xUrl = $presta['pres_url'];
        $xFono1 = $presta['pres_fono1'];
        $xFono2 = $presta['pres_fono2'];
        $xFono3 = $presta['pres_fono3'];
        $xCelu1 = $presta['pres_celular1'];
        $xCelu2 = $presta['pres_celular2'];
        $xCelu3 = $presta['pres_celular3'];
        $xEmail1 = $presta['pres_email1'];
        $xEnviar1 = $presta['pres_enviar1'];
        $xEmail2 = $presta['pres_email2'];
        $xEnviar2 = $presta['pres_enviar2'];
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

    $xSQL = "SELECT * FROM `expert_prestadora_especialidad` xpe, `expert_especialidad` esp  WHERE xpe.espe_id=esp.espe_id AND xpe.pais_id=$xPaisid AND xpe.empr_id=$xEmprid AND xpe.pres_id=$xPresid ";
    $all_especialidad = mysqli_query($con, $xSQL);


?>

<div id="kt_content_container" class="container-xxl">
    <div id="formPresta" class="form d-flex flex-column flex-lg-row">
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Logo Prestador</h2>
                    </div>
                </div>
                <div class="card-body text-center pt-0">
                    <div class="image-input image-input-empty image-input-outline mb-3" data-kt-image-input="true" style="background-image: url(assets/media/svg/files/blank-image.svg)">
                        <div class="image-input-wrapper w-150px h-150px" style="background-image: url(assets/media/svg/files/blank-image.svg);" id="imgfile"></div>
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
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Opciones</h2>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <button type="button" id="btnNuevaEspe" class="btn btn-light-primary btn-sm mb-10">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                                <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
                            </svg>
                        </span>
                        Nueva Especialidad
                    </button>
                    <button type="button" id="btnNuevaProfesion" class="btn btn-light-primary btn-sm mb-10">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                                <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
                            </svg>
                        </span>                                                                
                        Nuevo Tipo Profesion
                    </button>                                 
                    <div class="separator my-7"></div>
                    <button type="button" id="btnNuevoProfesional" class="btn btn-primary w-100" >
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z" fill="currentColor" />
                                <path opacity="0.3" d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z" fill="currentColor" />
                            </svg>
                        </span>
                        Nuevo Profesional
                    </button>    
                    <div class="separator my-7"></div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-n2">
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_add_product_general">Datos Generales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_add_product_advanced">Especialidad Prestador</a>
                </li>
                <a href="?page=prestador_admin&menuid=<?php echo $menuid;?>" class="btn btn-icon btn-light-primary btn-sm ms-auto me-lg-n7" title="Regresar" data-bs-toggle="tooltip" data-bs-placement="left">
                    <span class="svg-icon svg-icon-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M11.2657 11.4343L15.45 7.25C15.8642 6.83579 15.8642 6.16421 15.45 5.75C15.0358 5.33579 14.3642 5.33579 13.95 5.75L8.40712 11.2929C8.01659 11.6834 8.01659 12.3166 8.40712 12.7071L13.95 18.25C14.3642 18.6642 15.0358 18.6642 15.45 18.25C15.8642 17.8358 15.8642 17.1642 15.45 16.75L11.2657 12.5657C10.9533 12.2533 10.9533 11.7467 11.2657 11.4343Z" fill="currentColor" />
                        </svg>
                    </span>
                </a>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="kt_ecommerce_add_product_general" role="tab-panel">
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Datos Prestador</h2>
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
                                                    <option value="<?php echo $datos['Codigo'] ?>"><?php echo $datos['Descripcion'] ?></option>
                                                <?php } ?>
                                            </select>                                                      
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="fv-row mb-7">
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
                                                    <option value="<?php echo $datos['Codigo'] ?>"><?php echo $datos['Descripcion'] ?></option>
                                                <?php } ?>                                                        
                                            </select>                                                      
                                        </div>
                                    </div>
                                </div>                                        
                            </div>  
                        </div>
                        <div class="card pt-4 mb-6 mb-xl-9">
                            <div class="card-header border-0">
                                <div class="card-title">
                                    <h2 class="fw-bolder mb-0">Direccion/Telefono/Mails</h2>
                                </div>
                            </div>
                            <div id="kt_customer_view_payment_method" class="card-body pt-0">
                                <div class="py-0" data-kt-customer-payment-method="row">
                                    <div class="py-3 d-flex flex-stack flex-wrap">
                                        <div class="d-flex align-items-center  collapsible collapsed rotate" data-bs-toggle="collapse" href="#kt_customer_view_payment_method_1" role="button" aria-expanded="false" aria-controls="kt_customer_view_payment_method_1">
                                            <div class="me-3 rotate-90">
                                                <span class="svg-icon svg-icon-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <img src="assets/media/logos/ubicacion.png" class="w-20px me-3" alt="" />
                                            <div class="me-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="text-gray-800 fw-bolder">Direccion</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="kt_customer_view_payment_method_1" class="collapse fs-6 ps-10" data-bs-parent="#kt_customer_view_payment_method">
                                        <div class="d-flex flex-wrap py-5">
                                            <div class="flex-equal me-5">
                                                <div class="row mb-8">
                                                    <div class="col-xl-2">
                                                        <div class="fs-6 fw-bold mt-2 mb-3">Direccion:</div>
                                                    </div>
                                                    <div class="col-xl-10 fv-row">
                                                        <textarea class="form-control mb-2 text-uppercase" name="txtDireccion" id="txtDireccion" maxlength="250" onkeydown="return (event.keyCode!=13);"> <?php echo $xDireccion; ?> </textarea>
                                                    </div>
                                                </div>
                                                <div class="row mb-8">
                                                    <div class="col-xl-2">
                                                        <div class="fs-6 fw-bold mt-2 mb-3">URL:</div>
                                                    </div>
                                                    <div class="col-xl-10 fv-row">
                                                        <input type="text" class="form-control mb-2 text-lowercase" name="txtUrl" id="txtUrl" maxlength="150" placeHolder="https://wwww.dominio.com" value="<?php echo $xUrl; ?>" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="separator separator-dashed"></div>
                                <div class="py-0" data-kt-customer-payment-method="row">
                                    <div class="py-3 d-flex flex-stack flex-wrap">
                                        <div class="d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#kt_customer_view_payment_method_2" role="button" aria-expanded="false" aria-controls="kt_customer_view_payment_method_2">
                                            <div class="me-3 rotate-90">
                                                <span class="svg-icon svg-icon-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <img src="assets/media/logos/telefono.png" class="w-20px me-3" alt="" />
                                            <div class="me-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="text-gray-800 fw-bolder">Telefonos</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="kt_customer_view_payment_method_2" class="collapse fs-6 ps-10" data-bs-parent="#kt_customer_view_payment_method">
                                        <div class="row row-cols-1 row-cols-sm-3 rol-cols-md-3 row-cols-lg-3">
                                            <div class="col">
                                                <div class="fs-6 fw-bold mt-2 mb-3">Telefono 1:</div>
                                                <input type="text" class="form-control mb-2 w-150px" name="txtFono1" id="txtFono1" maxlength="9" placeholder="0299999999" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xFono1; ?>" />
                                            </div>
                                            <div class="col">
                                                <div class="fs-6 fw-bold mt-2 mb-3">Telefono 2:</div>
                                                <input type="text" class="form-control mb-2 w-150px" name="txtFono2" id="txtFono2" maxlength="9" placeholder="0299999999" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xFono2; ?>" />
                                            </div> 
                                            <div class="col">
                                                <div class="fs-6 fw-bold mt-2 mb-3">Telefono 3:</div>
                                                <input type="text" class="form-control mb-2 w-150px" name="txtFono3" id="txtFono3" maxlength="9" placeholder="0299999999" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xFono3; ?>" />
                                            </div>                                                        
                                        </div>
                                        <div class="row row-cols-1 row-cols-sm-3 rol-cols-md-3 row-cols-lg-3">
                                            <div class="col">
                                                <div class="fs-6 fw-bold mt-2 mb-3">Celular 1:</div>
                                                <input type="text" class="form-control mb-2 w-150px" name="txtCelular1" id="txtCelular1" maxlength="10" placeholder="0987654321" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xCelu1; ?>" />
                                            </div>
                                            <div class="col">
                                                <div class="fs-6 fw-bold mt-2 mb-3">Celular 2:</div>
                                                <input type="text" class="form-control mb-2 w-150px" name="txtCelular2" id="txtCelular2" maxlength="10" placeholder="0987654321" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xCelu2; ?>" />
                                            </div> 
                                            <div class="col">
                                                <div class="fs-6 fw-bold mt-2 mb-3">Celular 3:</div>
                                                <input type="text" class="form-control mb-2 w-150px" name="txtCelular3" id="txtCelular3" maxlength="10" placeholder="0987654321" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xCelu3; ?>" />
                                            </div>
                                        </div>                                                
                                    </div>
                                </div>
                                <div class="separator separator-dashed"></div>
                                <div class="py-0" data-kt-customer-payment-method="row">
                                    <div class="py-3 d-flex flex-stack flex-wrap">
                                        <div class="d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#kt_customer_view_payment_method_3" role="button" aria-expanded="false" aria-controls="kt_customer_view_payment_method_3">
                                            <div class="me-3 rotate-90">
                                                <span class="svg-icon svg-icon-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <img src="assets/media/logos/email.png" class="w-20px me-3" alt="" />
                                            <div class="me-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="text-gray-800 fw-bolder">E-mail</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="kt_customer_view_payment_method_3" class="collapse fs-6 ps-10" data-bs-parent="#kt_customer_view_payment_method">
                                        <div class="d-flex flex-wrap gap-5">
                                            <div class="fv-row w-100 flex-md-root">
                                                <label class="form-label">Email 1</label>
                                                <input type="email" name="txtEmail1" id="txtEmail1" maxlength="150" placeholder="micorre@dominio.com" class="form-control mb-2 text-lowercase" value="<?php echo $xEmail1; ?>" />
                                            </div>
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input" name="chkEnviar1" id="chkEnviar1" type="checkbox" <?php if($xEmail1 != '') { echo 'checked'; } ?> />
                                                <span id="spanEnv1" class="form-check-label fw-bold text-muted" for="chkEnviar1"> <?php if($xEmail1 != '') { echo 'Si Enviar'; }else { echo 'No Enviar'; } ?> </span>
                                            </label>                                                    
                                        </div>
                                        <div class="d-flex flex-wrap gap-5">
                                            <div class="fv-row w-100 flex-md-root">
                                                <label class="form-label">Email 2</label>
                                                <input type="email" name="txtEmail2" id="txtEmail2" maxlength="150" placeholder="micorre@dominio.com" class="form-control mb-2 text-lowercase" value="" />
                                            </div>
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input" name="chkEnviar2" id="chkEnviar2" type="checkbox" />
                                                <span class="form-check-label fw-bold text-muted" for="chkEnviar2">No Enviar</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <!--<a href="../../demo1/dist/apps/ecommerce/catalog/products.html" id="kt_ecommerce_add_product_cancel" class="btn btn-light me-5">Cancelar</a>-->
                        <button type="button" id="btnSave" class="btn btn-primary"><i class="las la-save"></i>
                            <span class="indicator-label">Grabar</span>
                            <span class="indicator-progress">Espere un momento...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>                            
                </div>
                <div class="tab-pane fade" id="kt_ecommerce_add_product_advanced" role="tab-panel">
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <div class="d-flex justify-content-start">
                            <a href="#" class="btn btn-light-primary btn-sm" id="btnAddespe">
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                                        <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>                                       
                            Agregar Especialidad
                            </a>
                        </div>
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Especialidades Asignadas</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0" id="kt_contacts_list_body">
                                <div class="d-flex flex-column gap-10">
                                    <div class="scroll-y me-n7 pe-7" id="parametro_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#parametro_header" data-kt-scroll-wrappers="#parametro_scroll" data-kt-scroll-offset="300px">
                                        <table id="tblEspecialidad" class="table align-middle table-row-dashed table-hover fs-6 gy-5" style="width: 100%;">
                                            <thead>
                                                <tr class="text-start text-gray-800 fw-bolder fs-7 text-uppercase gs-0">
                                                    <th style="display: none;">Id</th>
                                                    <th class="min-w-125px">Especialidad</th>
                                                    <th>Pvp</th>
                                                    <th>Costo</th>
                                                    <th class="min-w-125px">Estado</th>
                                                    <th>Status</th>
                                                    <th class="min-w-125px" style="text-align: center;">Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fw-bold text-gray-600">

                                                <?php 
                                        
                                                    foreach($all_especialidad as $especi){
                                                        $xEspeid = $especi['espe_id'];
                                                        $xId = $especi['pree_id'];
                                                        $xEspecialidad = trim($especi['espe_nombre']);
                                                        $xPvp = trim($especi['pree_pvp']);
                                                        $xCosto = trim($especi['pree_costo']);
                                                        $xEstado = trim($especi['pree_estado']);
                                                    ?>
                                                        <?php 
                        
                                                            $chkEstado = '';
                                                            $xDisabledEdit = '';
                                                            $xDisabledPerson = '';
                                                            $xDisabledMotivos = '';
                        
                                                            if($xEstado == 'A'){
                                                                $xEstado = 'ACTIVO';
                                                                $chkEstado = 'checked="checked"';
                                                                $xTextColor = "badge badge-light-primary";
                                                            }else{
                                                                $xEstado = 'INACTIVO';
                                                                $xTextColor = "badge badge-light-danger";
                                                                $xDisabledEdit = 'disabled';
                                                                $xDisabledPerson = 'disabled';
                                                                $xDisabledMotivos = 'disabled';
                                                            }
                        
                                                        ?>
                                                        <tr id="row_<?php echo $xId; ?>">
                                                            <td>  
                                                                <?php echo $xEspecialidad; ?>
                                                                <input type="hidden" id="txtEspeciPrestador<?php echo $xId; ?>" value="<?php echo $xEspecialidad; ?>" />
                                                            </td>
                                                            <td> <?php echo $xPvp; ?></td>
                                                            <td><?php echo $xCosto; ?> </td>                               
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
                                                                        <button id="btnEditar_<?php echo $xId; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" <?php echo $xDisabledEdit; ?> title='Editar Especialidad' data-bs-toggle="tooltip" data-bs-placement="left" >
                                                                            <i class='fa fa-edit'></i>
                                                                        </button>	
                                                                        <button id="btnPerson_<?php echo $xId; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" <?php echo $xDisabledPerson; ?> onclick='f_AgregarProfesional(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xPresid; ?>,<?php echo $xId; ?>)' title='Agregar Profesional' data-bs-toggle="tooltip" data-bs-placement="left" >
                                                                            <i class="fas fa-user"></i>
                                                                        </button>	
                                                                        <button id="btnMotivos_<?php echo $xId; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" <?php echo $xDisabledMotivos; ?> onclick='f_AgregarMotivos(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xId; ?>,<?php echo $xPresid; ?>,<?php echo $xEspeid; ?>)' title='Agregar Motivos' data-bs-toggle="tooltip" data-bs-placement="left" >
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

<!--Modal Nueva Especialidad -->
<div class="modal fade" id="modal-new-especialidad" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-800px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Nueva Especialidad</h2>
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
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <label class="required form-label">Especialidad
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Especifique el nombre de la especialidad"></i>
                                </label>
                                <input type="text" class="form-control mb-2" maxlength="250" placeholder="Nombre Especialidad" name="txtEspecialidad" id="txtEspecialidad" />     
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="required form-label">Tipo Especialidad</label>
                                <select name="cboTipoEspe" id="cboTipoEspe" aria-label="Seleccione Tipo" data-control="select2" data-placeholder="Seleccione Tipo" data-dropdown-parent="#kt_modal_new_card_form" class="form-select mb-2">
                                    <option></option>
                                    <?php 
                                    $xSQL = "SELECT pde.pade_valorV AS Codigo,pde.pade_nombre AS Descripcion FROM `expert_parametro_detalle` pde,`expert_parametro_cabecera` pca WHERE pca.pais_id=$xPaisid ";
                                    $xSQL .= "AND pca.paca_nombre='Tipo Especialidad' AND pca.paca_id=pde.paca_id AND pca.paca_estado='A' AND pade_estado='A' ";
                                    $all_datos =  mysqli_query($con, $xSQL);
                                    foreach ($all_datos as $datos){ ?>
                                        <option value="<?php echo $datos['Codigo'] ?>"><?php echo $datos['Descripcion'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Precio (PVP)</label>
                                <input type="number" name="txtPvpNew" id="txtPvpNew" class="form-control mb-2" placeholder="Precio al Publico (0.00)" min="0" maxlength = "6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="0.00" step="0.01" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label class="form-label">Descripcion</label>
                            <textarea class="form-control mb-2 text-uppercase" name="txtDescripcion" id="txtDescripcion" maxlength="150" rows="1" onkeydown="return (event.keyCode!=13);"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger" data-bs-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i>Cerrar</button>
                <button type="button" id="btnSaveNew" class="btn btn-sm btn-light-primary"><i class="las la-save"></i>Grabar</button>
            </div>
        </div>
    </div>
</div>

<!--Modal Agregar Especialidad -->
<div class="modal fade" id="modal-add-especialidad" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-800px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Editar Especialidad Asignada</h2>
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
                                <label class="required form-label">Especialidad</label>
                                <select name="cboEspecialidad" id="cboEspecialidad" aria-label="Seleccione Especialidad" data-control="select2" data-placeholder="Seleccione Especialidad" data-dropdown-parent="#modal-add-especialidad" class="form-select mb-2">
                                    <option></option>
                                    <?php 
                                    $xSQL = "SELECT espe_id AS Codigo,espe_nombre AS NombreEspe FROM `expert_especialidad` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND espe_estado='A' ";
                                    $all_datos =  mysqli_query($con, $xSQL);
                                    foreach ($all_datos as $datos){ ?>
                                        <option value="<?php echo $datos['Codigo'] ?>"><?php echo $datos['NombreEspe'] ?></option>
                                    <?php } ?>                                                        
                                </select>  
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                               <label class="required form-label">Pvp</label>
                               <input type="number" name="txtPvp" id="txtPvp" class="form-control mb-2" placeholder="Precio al Publico (0.00)" min="0" maxlength = "6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="0.00" step="0.01" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" disabled />
                            </div>
                            <div class="col-md-6">
                               <label class="required form-label">Costo Red</label>
                               <input type="number" name="txtCosto" id="txtCosto" class="form-control mb-2" placeholder="Precio al Publico (0.00)" min="0" maxlength = "6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="0.00" step="0.01" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger" data-bs-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i>Cerrar</button>
                <button type="button" id="btnAgregar" class="btn btn-sm btn-light-primary"><i class="las la-plus"></i>Agregar</button> 
            </div>
        </div>
    </div>
</div>  

<!--Modal Editar Especialidad -->
<div class="modal fade" id="modal-editar-especialidad" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-800px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Editar Especialidad Asignada</h2>
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
                                 <label class="required form-label">Especialidad</label>
                                <select name="cboEspecialidadEdit" id="cboEspecialidadEdit" aria-label="Seleccione Especialidad" data-control="select2" data-placeholder="Seleccione Especialidad" data-dropdown-parent="#modal-editar-especialidad" class="form-select mb-2">
                                    <option></option>
                                    <?php 
                                    $xSQL = "SELECT espe_id AS Codigo,espe_nombre AS NombreEspe FROM `expert_especialidad` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND espe_estado='A' ";
                                    $all_datos =  mysqli_query($con, $xSQL);
                                    foreach ($all_datos as $datos){ ?>
                                        <option value="<?php echo $datos['Codigo'] ?>"><?php echo $datos['NombreEspe'] ?></option>
                                    <?php } ?>                                                        
                                </select>  
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                               <label class="required form-label">Pvp</label>
                               <input type="number" name="txtPvpEdit" id="txtPvpEdit" class="form-control mb-2" placeholder="Precio al Publico (0.00)" min="0" maxlength = "6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="0.00" step="0.01" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                            </div>
                            <div class="col-md-6">
                               <label class="required form-label">Costo Red</label>
                               <input type="number" name="txtCostoEdit" id="txtCostoEdit" class="form-control mb-2" placeholder="Precio al Publico (0.00)" min="0" maxlength = "6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="0.00" step="0.01" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                            </div>
                            <input type="hidden" name="txtcboespe" id="txtcboespe" class="form-control mb-2"  />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger" data-bs-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i>Cerrar</button>
                <button type="button" id="btnEditarEspe" onclick="f_GrabarEspe(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xPresid; ?>)" class="btn btn-sm btn-light-primary"><i class="las la-pencil-alt"></i>Modificar</button>
            </div>
        </div>
    </div>
</div>   

<!--Modal Profesional /Configurar Horarios-->
<div class="modal fade" id="modal_profesional" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-1000px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Agregar Profesional/Configurar Horarios</h2>
                <h2 id="headerTitle" class="fs-6 fw-bold form-label text-primary"></h2>
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
                        <div class="row">
                            <div class="col-md-5">
                                 <label class="required form-label">Tipo Profesion</label>
                                <?php 
                                    $xSQL = "SELECT pde.pade_valorV AS Codigo,pde.pade_nombre AS Descripcion FROM `expert_parametro_detalle` pde,`expert_parametro_cabecera` pca WHERE pca.pais_id=$xPaisid ";
                                    $xSQL .= "AND pca.paca_nombre='Tipo Profesion' AND pca.paca_id=pde.paca_id AND pca.paca_estado='A' AND pade_estado='A' ";
                                    $all_datos =  mysqli_query($con, $xSQL);
                                ?>
                                <select name="cboTipoProfe" id="cboTipoProfe" aria-label="Seleccione Tipo" data-control="select2" data-placeholder="Seleccione Tipo" data-dropdown-parent="#modal_profesional" class="form-select mb-2" onchange="f_GetProfesional(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,this)">
                                    <option></option>
                                    <?php 
                                    foreach ($all_datos as $datos){ ?>
                                        <option value="<?php echo $datos['Codigo'] ?>"><?php echo $datos['Descripcion'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="required form-label">Profesional</label>
                                <select name="cboProfesional" id="cboProfesional" aria-label="Seleccione Profesional" data-control="select2" data-placeholder="Seleccione Profesional" data-dropdown-parent="#modal_profesional" class="form-select mb-2">
                                    <option></option>
                                </select> 
                            </div>
                            <div class="col-md-2">
                                 <label class="required form-label">Intervalo
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Intervalo de 10 a 60 minutos"></i>
                                 </label>
                                 <input type="number" name="txtIntervalo" id="txtIntervalo" min="10" max="60" step="10" class="form-control mb-2" value="10" onKeyPress="if(this.value.length==2) return false;"  pattern="/^-?\d+\.?\d*$/" />
                            </div>
                        </div>
                        <div class="form-group mt-5 mb-4">
                            <button type="button" data-repeater-create="" class="btn btn-sm btn-light-primary" id="btnAgregarProfesional">
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                                        <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>
                            Agregar Profesional
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
                <button type="button" class="btn btn-sm btn-light-danger" data-bs-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i>Cerrar</button>
            </div>
        </div>
    </div>
</div>   

<!--Modal Horarios -->             
<div class="modal fade" id="modal_horarios" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Configurar Horarios/Turnos</h2>
                <h5 class="text-primary" id="headertitu1"></h5>
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
                                 <label class="required form-label">Dia</label>
                                   <?php	
                                        $xSQL = "SELECT pde.pade_valorI AS Codigo,pde.pade_nombre AS Descripcion FROM `expert_parametro_cabecera` pca, `expert_parametro_detalle` pde WHERE pca.paca_id=pde.paca_id AND pca.pais_id=$xPaisid AND pca.empr_id=$xEmprid AND pca.paca_nombre='Dias Semana' AND pca.paca_estado='A' AND pde.pade_estado='A' ";
                                        $all_dias = mysqli_query($con, $xSQL);    
                                    ?>
                                    <select name="cboDias" id="cboDias" aria-label="Seleccione Dia" data-control="select2" data-placeholder="Seleccione Dia" data-dropdown-parent="#modal_horarios" class="form-select mb-2" >
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
                            <button type="button" data-repeater-create="" class="btn btn-sm btn-light-primary" id="btnAgregarHorario">
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                                        <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>
                            Agregar Horario
                            </button>
                        </div>
                         <div class="separator my-7"></div>
                        <h2>Turnos Asignados</h2>
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
                <button type="button" class="btn btn-sm btn-light-danger" data-bs-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i>Cerrar</button>
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
                                <input type="text" class="form-control mb-2" minlength="1" maxlength="150" placeholder="Tipo Profesion" name="txtTipoProfesion" id="txtTipoProfesion" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="required form-label">Valor/Codigo
                                    
                                </label>
                                <input type="text" class="form-control mb-2" minlength="1" maxlength="100" placeholder="Valor/Codigo" name="txtCodigoTipo" id="txtCodigoTipo" />
                            </div>
                        </div>
                        <div class="form-group my-5">
                            <button type="button" data-repeater-create="" class="btn btn-sm btn-light-primary" id="btnAgregarTipo">
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                                        <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>
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
                <button type="button" class="btn btn-sm btn-light-danger" data-bs-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i>Cerrar</button>
            </div>
        </div>
    </div>
</div>   

<!--Modal Nuevo Profesional -->
<div class="modal fade" id="modal-new-profesional" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-900px">
        <div class="modal-content"> 
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Nuevo Profesional</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body py-lg-10 px-lg-10 mt-n3">
                <div class="card mb-1 mb-xl-1">
                    <div class="card-header border-0">
                        <div class="card-title">
                            <div class="fw-bolder collapsible collapsed rotate" data-bs-toggle="collapse" href="#view_avatar" role="button" aria-expanded="false" aria-controls="view_imagen_titular">Avatar
                                <span class="ms-2 rotate-180">
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                </span>
                            </div> 
                        </div>
                    </div>
                    <div id="view_avatar" class="collapse">
                        <div class="card card-flush py-4">
                            <div class="card-body pt-0">
                                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('assets/media/svg/files/blank-image.svg')">
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url(assets/media/svg/files/blank-image.svg);" id="imgfileprof"></div>
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
                        <div class="card-title">
                            <div class="fw-bolder collapsible collapsed rotate" data-bs-toggle="collapse" href="#view_informacion" role="button" aria-expanded="false" aria-controls="view_datos_titular">Informacion
                                <span class="ms-2 rotate-180">
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                </span>
                            </div> 
                        </div>
                    </div>
                    <div id="view_informacion" class="collapse show">
                        <div class="card card-flush py-2">
                            <div id="modal_select" class="card-body pt-0">
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
                                                <option value="<?php echo $parametro['Codigo'] ?>"><?php echo $parametro['Descripcion']; ?></option>
                                            <?php endforeach ?>
                                        </select>
                                        </div>
                                    <div class="col-md-6">
                                        <label class="required form-label">Numero Documento</label>
                                        <input type="text" name="txtNumDocumento" id="txtNumDocumento" class="form-control mb-2" maxlength="10" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" placeholder="Numero Documento"  />
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
                                <div class="row">
                                   <div class="col-md-6">
                                        <label class="required form-label">Genero</label>
                                        <?php	
                                            $xSQL = "SELECT pde.pade_valorV AS Codigo,pde.pade_nombre AS Descripcion FROM `expert_parametro_cabecera` pca, `expert_parametro_detalle` pde WHERE pca.paca_id=pde.paca_id AND pca.pais_id=$xPaisid AND pca.empr_id=$xEmprid AND pca.paca_nombre='Tipo Genero' AND pca.paca_estado='A' AND pde.pade_estado='A' ";
                                            $all_parametro = mysqli_query($con, $xSQL);    
                                        ?>
                                        <select name="cboTipoGenero" id="cboTipoGenero" aria-label="Seleccione Genero" data-control="select2" data-placeholder="Seleccione Genero" data-dropdown-parent="#modal_select" class="form-select mb-2" >
                                            <option></option>
                                            <?php foreach ($all_parametro as $parametro) : ?>
                                                <option value="<?php echo $parametro['Codigo'] ?>"><?php echo $parametro['Descripcion']; ?></option>
                                            <?php endforeach ?>
                                        </select>
                                   </div>
                                   <div class="col-md-6">
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
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-1 mb-xl-1">
                   <div class="card-header border-0">
                       <div class="card-title">
                           <h2 class="fw-bolder mb-0">Direccion/Telefono/Mails</h2>
                       </div>
                   </div>
                   <div id="datos_profesional" class="card-body pt-0">
                        <div class="py-0" data-kt-customer-payment-method="row">
                            <div class="py-3 d-flex flex-stack flex-wrap">
                                <div class="d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#direccion_profesional" role="button" aria-expanded="false" aria-controls="direccion_profesional">
                                    <div class="me-3 rotate-90">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z" fill="currentColor" />
                                            </svg>
                                        </span>
                                    </div>
                                    <img src="assets/media/logos/ubicacion.png" class="w-20px me-3" alt="" />
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
                                        <textarea class="form-control mb-2" name="txtDireccionProf" id="txtDireccionProf" rows="1" maxlength="250" onkeydown="return (event.keyCode!=13);"> <?php echo $xDireccion; ?> </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed"></div>
                        <div class="py-0" data-kt-customer-payment-method="row">
                            <div class="py-3 d-flex flex-stack flex-wrap">
                                <div class="d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#telefono_profesional" role="button" aria-expanded="false" aria-controls="telefono_profesional">
                                    <div class="me-3 rotate-90">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z" fill="currentColor" />
                                            </svg>
                                        </span>
                                    </div>
                                    <img src="assets/media/logos/telefono.png" class="w-20px me-3" alt="" />
                                    <div class="me-3">
                                        <div class="d-flex align-items-center">
                                            <div class="text-gray-800 fw-bolder">Telefonos</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="telefono_profesional" class="collapse fs-6 ps-10" data-bs-parent="#datos_profesional">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="fs-6 fw-bold mt-3 mb-3">Telefono</div>
                                        <input type="text" class="form-control mb-3" name="txtFonoProf" id="txtFonoProf" maxlength="9" placeholder="022222222" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                                    </div>
                                    <div class="col-md-6">
                                        <div class="fs-6 fw-bold mt-3 mb-3">Celular</div>
                                        <input type="text" class="form-control mb-3" name="txtCelularProf" id="txtCelularProf" maxlength="10" placeholder="099999999" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="separator separator-dashed"></div> 
                        <div class="py-0" data-kt-customer-payment-method="row">
                            <div class="py-3 d-flex flex-stack flex-wrap">
                                <div class="d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#email_profesional" role="button" aria-expanded="false" aria-controls="email_profesional">
                                    <div class="me-3 rotate-90">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z" fill="currentColor" />
                                            </svg>
                                        </span>
                                    </div>
                                    <img src="assets/media/logos/email.png" class="w-20px me-3" alt="" />
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
                                        <input type="email" name="txtEmailProf" id="txtEmailProf" maxlength="100" placeholder="micorre@dominio.com" class="form-control mb-2 text-lowercase" />
                                    </div>
                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                        <input class="form-check-input" name="chkEnviarProf" id="chkEnviarProf" type="checkbox" />
                                        <span id="spanEnvProf" class="form-check-label fw-bold text-muted" for="chkEnviarProf">No Enviar </span>
                                    </label>                                                    
                                </div>
                            </div>
                        </div>
                    </div>                                
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger" data-bs-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i>Cerrar</button>
                <button type="button" id="btnSaveProf" class="btn btn-sm btn-light-primary"><i class="las la-save"></i>Grabar</button>
            </div>
        </div>
    </div>
</div>  

<!--Modal MOTIVOS ESPECIALIDAD-->
<div class="modal fade" id="modal-motivos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="mb-2 badge badge-light-primary fw-light fs-2 fst-italic">Motivos Especialidad</h2>
                <h2 id="headerTitleMotivo" class="fs-6 fw-bold form-label mb-2 text-primary"></h2>
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
                        <div class="row">
                            <div class="col-md-10">
                                 <label class="required form-label">Motivo Especialidad</label>
                                 <textarea class="form-control mb-2" name="txtmotivo" id="txtmotivo" maxlength="500" rows="1" onkeydown="return (event.keyCode!=13);"></textarea>
                            </div>
                        </div>
                        <div class="form-group mt-5 mb-4">
                            <button type="button" data-repeater-create="" class="btn btn-sm btn-light-primary" id="btnAgregarMotivo">
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                                        <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>
                            Agregar Motivo
                            </button>
                        </div>
                        <table id="tblMotivo" class="table align-middle table-row-dashed table-hover fs-6 gy-5" style="width: 100%;">
                            <thead>
                                <tr class="text-start text-gray-800 fw-bolder fs-7 text-uppercase gs-0">
                                    <th class="max-w-125px"">Motivo</th>
                                    <th class="min-w-125px">Estado</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody class="fw-bold text-gray-600"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger" data-bs-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i>Cerrar</button>
            </div>
        </div>
    </div>
</div>   

<script>
    $(document).ready(function(){

        flatpickr(txtHoraDesde, {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
        }); 
        
        flatpickr(txtHoraHasta, {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
        });                  

        _paisid = "<?php echo $xPaisid; ?>";
        _emprid = "<?php echo $xEmprid; ?>";
        _usuaid = "<?php echo $xUsuaid; ?>";
        _logo  = "<?php echo $xLogo; ?>";
        _btnopctiontipo = 'Add';
        _selpreeid = 0;
        _selpresid = 0;
        _selespeid = 0;

        _logo = _logo == '' ? 'companyname.png' : _logo;

        $('#cboProvincia').val("<?php echo $xCboProv; ?>").change();
        $('#cboCiudad').val(<?php echo $xProvid; ?>).change();
        $('#cboSector').val("<?php echo $xSector; ?>").change();
        $('#cboTipo').val("<?php echo $xTipoPresta; ?>").change();

        _enviar1 = "<?php echo $xEnviar1; ?>";
        _enviar2 = "<?php echo $xEnviar2; ?>";
        _enviarprof = "";

        document.getElementById('imgfile').style.backgroundImage="url(logos/" + _logo + ")";

        $('#cboProvincia').change(function(){
            
            //debugger;
            _cboid = $(this).val(); //obtener el id seleccionado
            $("#cboCiudad").empty();

            var _parametros = {
                "xxPaisId" : _paisid,
                "xxEmprId" : _emprid,
                "xxComboId" : _cboid,
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

        $( "#txtPvp" ).blur(function() {
            this.value = parseFloat(this.value).toFixed(2);
        });

        $( "#txtCosto" ).blur(function() {
            this.value = parseFloat(this.value).toFixed(2);
        });  
        
        $( "#txtPvpNew" ).blur(function() {
            this.value = parseFloat(this.value).toFixed(2);
        });

        //MODAL AGREGAR ESPECIALIDAD
        $('#btnAddespe').click(function(){

            $("#modal-add-especialidad").modal("show");
            $("#cboEspecialidad").val(0).change();
        });

        $("#btnNuevaEspe").click(function(){
            
            $("#modal-new-especialidad").find("input,textarea").val("");
            $("#modal-new-especialidad").modal("show");
            $('#modal-new-especialidad').modal('handleUpdate');
            $("#txtPvpNew").val("0.00");
            $("#cboTipoEspe").val(0).change();    
        }); 

        $("#btnNuevaProfesion").click(function(){
            
            //$("#modal_new_tipoprofesion").find("input,textarea").val("");
            const btn = document.getElementById('btnAgregarTipo');
            btn.innerHTML = '<span class="svg-icon svg-icon-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" /><rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" /></svg></span>Agregar';

            $("#txtTipoProfesion").val('');
            $("#txtCodigoTipo").val('');
            $("#modal_new_tipoprofesion").modal("show");
            $('#modal_new_tipoprofesion').modal('handleUpdate');
        });
        
        $("#btnNuevoProfesional").click(function(){
            
            //$('[href="#tabDatos"]').tab('show');
            document.getElementById('imgfileprof').style.backgroundImage="url(assets/media/svg/files/blank-image.svg)";
            $("#modal-new-profesional").find("input,textarea").val("");
            $("#modal-new-profesional").modal("show");
            $('#modal-new-profesional').modal('handleUpdate');
            //$("#txtPvpNew").val("0.00");
            $("#cboTipoDoc").val('').change();
            $("#cboTipoGenero").val('').change();
            $("#cboTipoProfesion").val('').change();
        });     
        
        $("#btnProbarAgenda").click(function(){
            $.redirect('?page=adminagenda&menuid=<?php echo $menuid; ?>', { 'tituid': 1, 'prodid': 6, 'grupid': 2 });

        });                 

        $('#btnSaveNew').click(function(e){

            var _cbotipoespe = $('#cboTipoEspe').val();
            var _especialidad = $.trim($("#txtEspecialidad").val());
            var _descripcion = $.trim($("#txtDescripcion").val());
            var _pvpnew = $("#txtPvpNew").val();

            if(_cbotipoespe == ''){
                toastSweetAlert("top-end",3000,"warning","Seleccion Tipo Especialidad..!!");
                return;
            }

            if(_especialidad == ''){
                toastSweetAlert("top-end",3000,"warning","Ingrese Especialidad..!!");
                return;
            }

            if(_pvpnew == ''){
                _pvpnew = '0.00';
            }
            var _parametros = {
                "xxPaisId" : _paisid,
                "xxEmprId" : _emprid,
                "xxUsuaId" : _usuaid,
                "xxEspecialidad" : _especialidad,
                "xxDescripcion" : _descripcion,
                "xxTipoEspe" : _cbotipoespe,
                "xxPrecio" : _pvpnew
            }                    

            var xrespuesta = $.post("codephp/grabar_especialidad.php", _parametros);
            xrespuesta.done(function(response){
                if(response.trim() == 'EXISTE'){
                    toastSweetAlert("top-end",3000,"warning","Especialidad ya Existe..!!");
                }else{
                    if(response.trim() != 'ERR'){
                        toastSweetAlert("top-end",3000,"success","Especialidad Agregada");
                        $("#cboEspecialidad").empty();
                        $("#cboEspecialidad").html(response);
                        $("#modal-new-especialidad").modal("hide");
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
                "xxPaisId" : _paisid,
                "xxEmprId" : _emprid,
                "xxUsuaId" : _usuaid,
                "xxTipoPrestador" : _tipoprestador,
                "xxValor" : _valorv
            }

            var xrespuesta = $.post("codephp/grabar_tipoprestador.php", _parametros);
            xrespuesta.done(function(response){
                if(response.trim() == 'EXISTE'){
                    toastSweetAlert("top-end",3000,"info","Tipo Prestador/Valor ya Existe..!!");
                }else{
                    if(response.trim() != 'ERR'){
                        toastSweetAlert("top-end",3000,"success","Prestador Agregado");
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
                    toastSweetAlert("top-end",3000,"error","Email no es Valido..!!");
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
                    toastSweetAlert("top-end",3000,"error","Email no es Valido..!!");
                    _enviar2 = 'NO';
                    return;
                }
            }else{
                $('#chkEnviar2').prop('checked','');
                _enviar2 = 'NO';
            }
        });                

        $('#btnAgregar').click(function(e){

            var _cboespe = $('#cboEspecialidad').val();
            var _especialidad = $("#cboEspecialidad option:selected").text();
            var _pvp = $.trim($("#txtPvp").val());
            var _costo = $.trim($("#txtCosto").val());
            var _presid = <?php echo $xPresid; ?>;

            if(_especialidad == ''){
                toastSweetAlert("top-end",3000,"warning","Seleccione Especialidad..!!");
                return;
            }

            if(_pvp == ''){
                _pvp = '0.00';
            }

            if(_costo == ''){
                _costo = '0.00';
            }
            
            var _parametros = {
                "xxPaisid" : _paisid,
                "xxEmprid" : _emprid,
                "xxUsuaid" : _usuaid,
                "xxPresid" : _presid,
                "xxEspeid" : _cboespe,                        
                "xxPvp" : _pvp,
                "xxCosto" : _costo
            }

            var xrespuesta = $.post("codephp/consultar_prestaespeci.php", _parametros);
            xrespuesta.done(function(response){

                if(response != 0){

                    _id = response;
                    _output = '<tr id=row_' + _id + '>';
                    _output += '<td><div class="d-flex align-items-center"><div class="ms-5"><span class="fw-bolder">' + _especialidad + '</span><input type="hidden" id="txtEspecialidad' + _id + 'value="' + _especialidad +  '" /></div></div></td>';
                    _output += '<td><div class=""><div class="ms-5"><span class="fw-bolder">' + _pvp + '</span></div></div></td>';
                    _output += '<td><div class=""><div class="ms-5"><span class="fw-bolder">' + _costo + '</span></div></div></td>';
                    _output += '<td id="td_' + _id + '"><div class=""><div class="ms-5"><div class="badge badge-light-primary">ACTIVO</div></div></div></td>';                        
                    _output += '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid"> '; 
                    _output += '<input class="form-check-input h-20px w-20px border-primary" checked="checked" type="checkbox" id="chk' + _cboespe + '" onchange="f_UpdateEstado(';
                    _output += _paisid + ',' + _emprid + ',' + _id + ')" value="' + _id + '"/></div></div></td>';
                    _output += '<td><div class="text-center"><div class="btn-group"><button id="btnEditar_' + _id + '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" ';
                    _output += 'title="Editar Especialidad"><i class="fa fa-edit"></i></button>';
                    _output += '<button id="btnPerson_' + _id + '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" onclick=f_AgregarProfesional(' + _paisid + ',' + _emprid + ',' + _presid + ',' + _id + ') title="Agregar Profesional" data-bs-toggle="tooltip" data-bs-placement="left" >';
                    _output += '<i class="fas fa-user"></i></button>';
                    _output += '<button id="btnMotivos_' + _id + '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" onclick=f_AgregarMotivos(' + _paisid + ',' + _emprid + ',' + _id + ',' + _presid + ',' +_cboespe + ') title="Agregar Motivos" data-bs-toggle="tooltip" data-bs-placement="left" >';
                    _output += '<i class="fas fa-book"></i></button>';
                    _output += '</div></div></td></tr>';

                    $('#tblEspecialidad').append(_output);
                    //console.log(_output);

                    //mensajesalertify('Especialidad Agregada Correctamente..!', 'S', 'top-center', 3); 
                    $("#modal-add-especialidad").modal("hide");
                    toastSweetAlert("top-end",3000,"success","Especialidad Agregada");
                }else{
                    toastSweetAlert("top-end",3000,"error","Especialidad ya Existe..!!");
                }

                $("#cboEspecialidad").val(0).change();
                $("#txtPvp").val('0.00');
                $("#txtCosto").val('0.00');                        
            });
        });
        
        $('#btnAgregarTipo').click(function(e){

            var _tipoprofesion = $.trim($('#txtTipoProfesion').val());
            var _valcodigoprof = $.trim($('#txtCodigoTipo').val());
            var _continuar = true;

            if(_tipoprofesion == ''){
                toastSweetAlert("top-end",3000,"warning","Ingrese Profesion..!!");
                return;
            }

            if(_valcodigoprof == ''){
                toastSweetAlert("top-end",3000,"warning","Ingrese Valor/Codigo..!!");
                return;
            }

            if(_btnopctiontipo == 'Add'){
                _padeidact = 0;
            }else{
                if(_tipoprofesion == _tipoprofeold){
                    _continuar = false;
                }
            }
            
            if(_continuar){
                var _parametros = {
                    "xxPaisid" : _paisid,
                    "xxEmprid" : _emprid,
                    "xxUsuaid" : _usuaid,
                    "xxTipoProfe" : _tipoprofesion,
                    "xxValCodigoProf" : _valcodigoprof,
                    "xxPadeid" : _padeidact
                }

                var xrespuesta = $.post("codephp/grabar_tipoprofesion.php", _parametros);
                xrespuesta.done(function(response){

                    console.log(response);
                    var json = JSON.parse(response);
                    var _pacaid = json.Pacaid;
                    var _padeid = json.Padeid;

                    //console.log(_pacaid);
                    //console.log(_padeid);

                    if(_padeid > 0){

                        if(_btnopctiontipo == 'Add'){
                            _output = '<tr id="tr_' + _padeid + '">';    
                            _output += '<td>' + _tipoprofesion.toUpperCase() + '<input type="hidden" id="txtPadeid' + _padeid + '" value="' + _padeid + '"/> <input type="hidden" id="txtTiprofe' + _padeid + '" value="' + _tipoprofesion + '"/> <input type="hidden" id="txtValor' + _padeid + '" value="' + _valcodigoprof  + '"/></td>';
                            _output += '<td id="td_' + _padeid + '"><div class="d-flex align-items-center"><div class="badge badge-light-success">ACTIVO</div></div></td>';
                            _output += '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid"> '; 
                            _output += '<input class="form-check-input h-20px w-20px border-primary" checked="checked" type="checkbox" id="chk' + _padeid + '" onchange="f_UpdateEstTipo(';
                            _output += _pacaid + ',' + _padeid + ')" value="' + _padeid + '"/></div></div></td>';
                            _output += '<td><div class="btn-group"><button id="btnEdiTipo" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 " ';
                            _output += 'title="Editar Tipo Profesion" onclick="f_EditarTipo(' + _pacaid + ',' + _padeid + ')" ><i class="fa fa-edit"></i></button></div></td></tr>';
                            
                            console.log(_output);

                        }else{
                            _output = '<td>' + _tipoprofesion.toUpperCase() + '<input type="hidden" id="txtPadeid'  + _padeid + '" value="' + _padeid + '"/> <input type="hidden" id="txtTiprofe'  + _padeid + '" value="' + _tipoprofesion + '"/> <input type="hidden" id="txtValor' + _padeid + '" value="' + _valcodigoprof  + '"/></td>';
                            _output += '<td id="td_' + _padeid + '"><div class="d-flex align-items-center"><div class="badge badge-light-success">ACTIVO</div></div></td>';
                            _output += '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid"> '; 
                            _output += '<input class="form-check-input h-20px w-20px border-primary" checked="checked" type="checkbox" id="chk' + _padeid + '" onchange="f_UpdateEstTipo(';
                            _output += _pacaid + ',' + _padeid + ')" value="' + _padeid + '"/></div></div></td>';
                            _output += '<td><div class="btn-group"><button id="btnEdiTipo" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 " ';
                            _output += 'title="Editar Tipo Profesion" onclick="f_EditarTipo(' + _pacaid + ',' + _padeid + ')" ><i class="fa fa-edit"></i></button></div></td>';                                    
                        }

                        if(_btnopctiontipo == 'Add'){
                            $('#tblTipoProfesion').append(_output);
                            //_mensaje = "Tipo Profesion Agregada Correctamente..!";
                            toastSweetAlert("top-end",3000,"success","Profesional Agregado");
                        }else{
                            $('#tr_' + _padeid + '').html(_output);
                            toastSweetAlert("top-end",3000,"success","Profesional Modificado");
                        }                                

                        //Listar Nuevamente los tipos de Profesion
                        var _parametros = {
                            "xxPaisid" : _paisid,
                            "xxEmprid" : _emprid,
                            "xxParametro" : 'Tipo Profesion'
                        }

                        var xtiposprofesion = $.post("codephp/get_parametroxtipo.php", _parametros);
                        xtiposprofesion.done(function(xresponse){
                            $("#cboTipoProfesion").empty();
                            $("#cboTipoProfesion").html(xresponse);
                        });                                
                    }else{
                        toastSweetAlert("top-end",3000,"warning","Profesion ya Existe.!!");                           
                    }

                    $("#txtTipoProfesion").val('');
                    $("#txtCodigoTipo").val('');
                    $('#txtCodigoTipo').attr('disabled',false);
                    _btnopctiontipo = "Add";
                });                        
            }

            $('#txtTipoProfesion').val('');
            $('#txtCodigoTipo').val('');
            const btn = document.getElementById('btnAgregarTipo');
            btn.innerHTML = '<span class="svg-icon svg-icon-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" /><rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" /></svg></span>Agregar';

        });

        $('#cboEspecialidad').change(function(){                    
            _cboid = $(this).val();

            if(_cboid != null){
                var _parametros = {
                    "xxPaisId" : _paisid,
                    "xxEmprId" : _emprid,
                    "xxEspeId" : _cboid
                } 

                var xrespuesta = $.post("codephp/get_DatosEspecialidad.php", _parametros);
                xrespuesta.done(function(response){
                    if(response.trim() == '0'){
                        $("#txtPvp").val('0.00');
                    }else{
                        $("#txtPvp").val(response);
                    }
                });
            }
        });

        $('#btnSave').click(function(e){

            var _presid = "<?php echo $xPresid; ?>";
            var _logo = "<?php echo $xLogo; ?>";
            var _provid = $('#cboProvincia').val();
            var _ciudid = $('#cboCiudad').val();
            var _prestador = $.trim($('#txtPrestador').val());
            var _sector = $('#cboSector').val();
            var _tipopresta = $('#cboTipo').val();
            var _direccion = $.trim($('#txtDireccion').val());
            var _url = $.trim($('#txtUrl').val());
            var _telefono1 = $.trim($('#txtFono1').val());
            var _telefono2 = $.trim($('#txtFono2').val());
            var _telefono3 = $.trim($('#txtFono3').val());
            var _celular1 = $.trim($('#txtCelular1').val());
            var _celular2 = $.trim($('#txtCelular2').val());
            var _celular3 = $.trim($('#txtCelular3').val());
            var _email1 =  $.trim($('#txtEmail1').val());
            var _email2 =  $.trim($('#txtEmail2').val());

            var _providant = $.trim($('#txtcbociudad').val());
            var _prestaant = $.trim($('#txtPrestaant').val());

            var _cambiarlogo = 'NO';
            _respuesta = 'OK';

            if(_provid == ''){
                toastSweetAlert("top-end",3000,"warning","Seleccione Provincia..!"); 
                return; 
            }

            if(_ciudid == ''){
                toastSweetAlert("top-end",3000,"warning","Seleccione Ciudad..!"); 
                return; 
            }

            if(_prestador == ''){
                toastSweetAlert("top-end",3000,"warning","Ingrese Prestador..!"); 
                return;                         
            }

            if(_sector == ''){
                toastSweetAlert("top-end",3000,"warning","Seleccione Sector..!"); 
                return; 
            }
            
            if(_tipopresta == ''){
                toastSweetAlert("top-end",3000,"warning","Seleccione Tipo Prestador..!"); 
                return; 
            }                       
            
            if(_url != ''){
                try{
                    new URL(_url);
                }catch(err){
                    toastSweetAlert("top-end",3000,"error","Direccion URL Incorrecta..!"); 
                    return false;
                }
            }
            
            if(_telefono1 != '')
            {
                _valor = document.getElementById("txtFono1").value;
                if( !(/^(\d{7}|\d{9})$/.test(_valor)) ) {
                    toastSweetAlert("top-end",3000,"error","Telefono 1 incorrecto..!"); 
                    return;
                }
            }

            if(_telefono2 != '')
            {
                _valor = document.getElementById("txtFono2").value;
                if( !(/^(\d{7}|\d{9})$/.test(_valor)) ) {
                    toastSweetAlert("top-end",3000,"error","Telefono 2 incorrecto..!");  
                    return;
                }
            }                    

            if(_telefono3 != '')
            {
            
               _valor = document.getElementById("txtFono3").value;
                if( !(/^(\d{7}|\d{9})$/.test(_valor)) ) {
                    toastSweetAlert("top-end",3000,"error","Telefono 3 incorrecto..!");  
                    return;
                }
            }  
            
            if(_celular1 != '')
            {
                _valor = document.getElementById("txtCelular1").value;
                if( !(/^\d{10}$/.test(_valor)) ) {
                    toastSweetAlert("top-end",3000,"error","Celular 1 incorrecto..!");  
                    return;
                }
            }                     
            
            if(_celular2 != '')
            {
                _valor = document.getElementById("txtCelular2").value;
                if( !(/^\d{10}$/.test(_valor)) ) {
                    toastSweetAlert("top-end",3000,"error","Celular 2 incorrecto..!"); 
                    return;
                }
            }
            
            if(_celular3 != '')
            {
                _valor = document.getElementById("txtCelular3").value;
                if( !(/^\d{10}$/.test(_valor)) ) {
                    toastSweetAlert("top-end",3000,"error","Celular 3 incorrecto..!");  
                    return;
                }
            }                    
            
            if(_email1 != ''){
                var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
            
                if (regex.test($('#txtEmail1').val().trim())) {
                }else{
                    toastSweetAlert("top-end",3000,"error","Email 1 no es Valido..!"); 
                    return;
                }
            }

            if(_email2 != ''){
                var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
            
                if (regex.test($('#txtEmail2').val().trim())) {
                }else{
                    toastSweetAlert("top-end",3000,"error","Email 2 no es Valido..!"); 
                    return;
                }
            }

            var _imgfile = document.getElementById("imgfile").style.backgroundImage;
            var _urlimg = _imgfile.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
            var _pos = _urlimg.trim().indexOf('.');
            var _ext = _urlimg.trim().substr(_pos, 5);

            if(_ext.trim() != '.png' && _ext.trim() != '.jpg' && _ext.trim() != '.jpeg'){
                var _imagen = document.getElementById("imglogo");
                var _file = _imagen.files[0];
                var _fullPath = document.getElementById('imglogo').value;
                _ext = _fullPath.substring(_fullPath.length - 4);
                _ext = _ext.toLowerCase();   

                if(_ext.trim() == '.png' || _ext.trim() == '.jpg' || _ext.trim() == '.jpeg' || _ext.trim() == 'jpeg' ){
                    _cambiarlogo = 'SI';
                }else{
                    toastSweetAlert("top-end",3000,"error","El archivo seleccionado no es una Imagen..!"); 
                    return;
                }
            }

            form_data = new FormData();                    
            form_data.append('xxPaisid', _paisid);
            form_data.append('xxEmprid', _emprid);
            form_data.append('xxUsuaid', _usuaid);
            form_data.append('xxPresid', _presid);
            form_data.append('xxProvid', _ciudid);
            form_data.append('xxProvidant', _providant);
            form_data.append('xxPrestador', _prestador);
            form_data.append('xxPrestadorant', _prestaant);
            form_data.append('xxSector', _sector);
            form_data.append('xxTipo', _tipopresta);
            form_data.append('xxDireccion', _direccion);
            form_data.append('xxUrl', _url);
            form_data.append('xxFono1', _telefono1);
            form_data.append('xxFono2', _telefono2);
            form_data.append('xxFono3', _telefono3);
            form_data.append('xxCelular1', _celular1);
            form_data.append('xxCelular2', _celular2);
            form_data.append('xxCelular3', _celular3);
            form_data.append('xxEmail1', _email1);
            form_data.append('xxEnviar1', _enviar1);
            form_data.append('xxEmail2', _email2);
            form_data.append('xxEnviar2', _enviar2);
            form_data.append('xxFile', _file);
            form_data.append('xxCambiarlogo', _cambiarlogo);
            form_data.append('xxLogo', _logo);

            $.ajax({
                url: "codephp/update_prestador.php",
                type: "post",
                data: form_data,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(response){
                    
                    if(response == 'OK'){
                        $.redirect('?page=prestador_admin&menuid=<?php echo $menuid; ?>', {'mensaje': 'Actualizado con Exito..!'}); //POR METODO POST
                    }else{
                        toastSweetAlert("top-end",3000,"info","Prestador ya Existe..!"); 
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });

    });

    $(document).on("click",".btnEditar",function(){

        _rowid = $(this).attr("id");
        _rowid = _rowid.substring(10);

        var _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxPreeid" : _rowid
        }                

        var xrespuesta = $.post("codephp/get_datosespecipresta.php", _parametros );
        xrespuesta.done(function(response){
            
            var _datos = JSON.parse(response);

            $.each(_datos,function(i,item){

                _espeid =  _datos[i].Espeid;
                _pvp =  _datos[i].Pvp;
                _costo =  _datos[i].Costo;

                $('#cboEspecialidadEdit').val(_espeid).change();
                $('#txtPvpEdit').val(_pvp);
                $('#txtCostoEdit').val(_costo);
                $('#txtcboespe').val(_espeid);

            });
            
            $("#modal-editar-especialidad").modal("show");
        });

    });	
    
    function f_AgregarProfesional(_paisid, _emprid, _presid, _preeid){

        //debugger;
        var tb = document.getElementById('tblProfesional');
            while(tb.rows.length > 1) {
            tb.deleteRow(1);
        }                

        _selpreeid = _preeid;

        _selespecialidad = $('#txtEspeciPrestador' + _preeid).val();
        document.getElementById("headerTitle").innerHTML = "Especialidad: " + _selespecialidad;

        var _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxPreeid" : _preeid
        }

        $.ajax({
            url: "codephp/get_datosprofesional.php",
            type: "POST",
            dataType: "json",
            data: _parametros,
            success: function(response){ 
                $.each(response, function(i, item){

                    _id = item.Id;
                    _nombres = item.Nombres + ' ' + item.Apellidos;
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

                $("#cboTipoProfe").val(0).change(); 
                $("#txtIntervalo").val(10);
                //$("#modal_profesional").find("input,textarea").val("");
                $("#modal_profesional").modal("show");
                $('#modal_profesional').modal('handleUpdate');                           
            },
            error: function (error){
                console.log(error);
            }
        });
    }

    $('#btnAgregarProfesional').click(function(e){

        _profid = $("#cboProfesional").val();

        var _profid = $('#cboProfesional').val();
        var _profesional = $('#cboProfesional option:selected').text();
        var _profesion = $('#cboTipoProfe option:selected').text();
        var _intervalo = $('#txtIntervalo').val();
        

        if(_profid == 0){
            toastSweetAlert("top-end",3000,"warning","Seleccione Profesional..!"); 
            return;
        }

        if(_intervalo == ''){
            toastSweetAlert("top-end",3000,"warning","Ingrese Internvalo..!"); 
            return;
        }                

        var _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxUsuaid" : _usuaid,
            "xxPreeid" : _selpreeid,
            "xxIntervalo" : _intervalo,
            "xxProfid" : _profid
        }	

        var xrespuesta = $.post("codephp/grabar_profesionalespeci.php", _parametros);
        xrespuesta.done(function(response){
            if(response > 0){

                _id = response.trim();

                _output = '<tr id="trprof_' + _id + '">';
                _output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _profesional + '</span><input type="hidden" id="txtProfesional_' + _id + '" value="' + _profesional +  '" /></div></div></td>';
                _output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _profesion + '</span></div></div></td>';
                _output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _intervalo + '</span></div></div></td>';
                _output += '<td id="tdprof_' + _id + '"><div class="d-flex align-items-center"><div class="ms-0"><div class="badge badge-light-success">ACTIVO</div></div></div></td>';
                _output += '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid"> '; 
                _output += '<input class="form-check-input h-20px w-20px border-primary" type="checkbox" checked="checked" id="chkprof' + _id + '" onchange="f_UpdateEstProf(';
                _output += _paisid + ',' + _emprid + ',' + _id + ')" value="' + _id + '"/></div></div></td>';
                _output += '<td class=""><div class=""><div class="btn-group">';
                _output += '<button id="btnHorario_' + _id  + '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" onclick="f_ConfHorario(';
                _output += _paisid + ',' + _emprid + ',' + _id + ')" title="Configurar Horario" ><i class="fas fa-cogs"></i></button>';                        
                _output += '<button id="btnDelProf_' + _id + '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1" onclick="f_DelAsigProf(';
                _output += _paisid + ',' + _emprid + ',' + _id + ')" title="Eliminar Profesional Asignado" ><i class="fa fa-trash"></i></button></div></div></td></tr>'
                
                $('#tblProfesional').append(_output);

            }else{
                toastSweetAlert("top-end",3000,"error","Error en Ajax-grabar_profescionalespeci"); 
            }

            $("#cboTipoProfe").val(0).change(); 
            $("#cboProfesional").val(0).change(); 

        });	                

    });

    function f_AgregarMotivos(_paisid, _emprid, _preeid, _presid, _espeid){

        var tb = document.getElementById('tblMotivo');
            while(tb.rows.length > 1) {
            tb.deleteRow(1);
        }

        _selpreeid = _preeid;
        _selpresid = _presid;
        _selespeid =  _espeid   

        _selespecialidad = $('#txtEspeciPrestador' + _preeid).val();
        document.getElementById("headerTitleMotivo").innerHTML = "Especialidad: " + _selespecialidad;

        var _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxPresid" : _presid,
            "xxEspeid" : _espeid,
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
                        _textcolor = "badge badge-light-success";
                    }else{
                        _textcolor = "badge badge-light-danger";
                    }

                    if(_estado == 'A'){
                        _estado = 'ACTIVO';
                    }else{
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

                $("#modal-motivos").modal("show");
                $('#modal-motivos').modal('handleUpdate');                           
            },
            error: function (error){
                console.log(error);
            }
        });        

    }

    $('#btnAgregarMotivo').click(function(e){

        var _motivo = $('#txtmotivo').val();
        
        if(_motivo == ''){
            toastSweetAlert("top-end",3000,"warning","Ingrese Motivo..!"); 
            return;
        }                

        var _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxUsuaid" : _usuaid,
            "xxPresid" : _selpresid,
            "xxEspeid" : _selespeid,
            "xxMotivo" : _motivo
        }	

        var xrespuesta = $.post("codephp/grabar_motivoespecialidad.php", _parametros);
        xrespuesta.done(function(response){
            if(response > 0){

                _id = response.trim();

                _estado = 'ACTIVO';
                _checked = "checked='checked'";
                _textcolor = "badge badge-light-success";                

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

    function f_UpdateEstProf(_paisid, _emprid, _pfesid){

        let _usuaid = "<?php echo $xUsuaid; ?>";
        let _check = $("#chkprof" + _pfesid).is(":checked");
        let _checked = "";
        let _class = "badge badge-light-primary";
        let _td = "tdprof_" + _pfesid;
        let _btnhorario = "btnHorario_" + _pfesid;
        let _btnelimina = "btnDelProf_" + _pfesid;

        if(_check){
            _estado = "ACTIVO";
            _checked = "checked='checked'";
            $('#'+_btnhorario).prop("disabled",false);
            $('#'+_btnelimina).prop("disabled",false);
        }else{                    
            _estado = "INACTIVO";
            _class = "badge badge-light-danger";
            $('#'+_btnhorario).prop("disabled",true);
            $('#'+_btnelimina).prop("disabled",true);
        }

        var _changetd = document.getElementById(_td);
        _changetd.innerHTML = '<div class="d-flex align-items-center"><div class="ms-0"><div class="' + _class + '">' + _estado + ' </div></div>';

        var _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprId" : _emprid,
            "xxUsuaid" : _usuaid,
            "xxPfesid" : _pfesid,
            "xxEstado" : _estado
        }	

        var xrespuesta = $.post("codephp/update_profesionalespeci.php", _parametros);
            xrespuesta.done(function(response){

        });
    }

    //Cambiar estado Nuevo Tipo Profesion Modal
    function f_UpdateEstTipo(_pacaid, _padeid){  

        alert(_padeid);
        debugger;
        var _check = $("#chktipo" + _padeid).is(":checked");
        var _checked = '';
        var _class = '';
        var _td = 'td_' + _padeid;
        var _estado = 'ACTIVO';
        var _btnedit = 'btnEdiTipo_' + _padeid;

        if(_check){
            _checked = "checked='checked'";
            _class = 'badge badge-light-success';
            $('#'+_btnedit).prop('disabled',false); 
        }else{
            _estado = 'INACTIVO';
            _class = 'badge badge-light-danger';
            $('#'+_btnedit).prop('disabled',true);
        }
        
        var _changetd = document.getElementById(_td);
        _changetd.innerHTML = '<div class="' + _class + '">' + _estado + ' </div>';

        var _parametros = {
            "xxPadeid" : _padeid,
            "xxEstado" : _estado,
        }

        var xrespuesta = $.post("codephp/update_tipoprofesion.php", _parametros);
            xrespuesta.done(function(response){

        });
    }

    function f_UpdateMotivo(_paisid, _emprid, _motid){

        let _usuaid = "<?php echo $xUsuaid; ?>";
        let _check = $("#chkmoti" + _motid).is(":checked");
        let _checked = "";
        let _class = "badge badge-light-success";
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

    function f_ConfHorario(_paisid, _emprid, _pfesid){

        var tb = document.getElementById('tblHorarios');
            while(tb.rows.length > 1) {
            tb.deleteRow(1);
        }                   

        _selecpfesid = _pfesid
        _selprofesional = $('#txtProfesional_' + _pfesid).val();
        document.getElementById("headertitu1").innerHTML = "Especialidad: " + _selespecialidad + "<br><br>" + "Profesional: " + _selprofesional;

        $("#cboDias").val(0).change();
        //$("#txtIntervalo").val(10);
        $("#txtHoraDesde").val('');
        $("#txtHoraHasta").val('');

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

                    //debugger;
                    
                    _id = item.Id;
                    _dia = item.Dia;
                    //_intervalo = item.Intervalo;
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

                $("#modal_profesional").modal("hide");
                $("#modal_horarios").modal("show");

                //$("#modal_horarios").find("input,textarea").val("");
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
    }            
    
    $('#btnAgregarHorario').click(function(e){

        var _dia = $('#cboDias').val();
        var _intervalo = 0;
        //var _intervalo = $('#txtIntervalo').val();
        var _horadesde = $('#txtHoraDesde').val();
        var _horahasta = $('#txtHoraHasta').val();                

        var _diatext = $('#cboDias option:selected').text();

        if(_dia == ''){
            toastSweetAlert("top-end",3000,"warning","Seleccione Dia..!");
            return;
        }

        // if(_intervalo == ''){
        //     mensajesalertify("Ingrese Internvalo..!", "W", "top-center", 5);
        //     return;
        // }

        if(_horadesde == ''){
            toastSweetAlert("top-end",3000,"warning","Seleccion Hora Inicio..!");
            return;
        }

        if(_horahasta == ''){
            toastSweetAlert("top-end",3000,"warning","Seleccione Hora Final..!");
            return;
        }                

        //VALIDAR LAS HORAS

        var minutos_inicio = _horadesde.split(':').reduce((p, c) => parseInt(p) * 60 + parseInt(c));
        var minutos_final = _horahasta.split(':').reduce((p, c) => parseInt(p) * 60 + parseInt(c));
        
        if (minutos_final < minutos_inicio){
            toastSweetAlert("top-end",3000,"question","La Hora Inicio no puede ser mayor a la Hora Final..!!");
            return;
        } 

        // var diferencia = minutos_final - minutos_inicio;

        // if(parseInt(_intervalo) >= diferencia){
        //     mensajesalertify("La diferencia del Intervalo es menor o igual a la horas establecidas..!", "W", "top-center", 5);
        //     return;
        // }

        //var horas = Math.floor(diferencia / 60);
        //var minutos = diferencia % 60;
        //$('#horas_justificacion_real').val(horas + ':' + (minutos < 10 ? '0' : '') + minutos);

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

                _id = response;

                _output = '<tr id="trhorario_' + _id + '">';
                _output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _diatext + '</span></div></div></td>';
                //_output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _intervalo + '</span></div></div></td>';
                _output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _horadesde + '</span></div></div></td>';
                _output += '<td><div class="d-flex align-items-center"><div class="ms-0"><span class="fw-bolder">' + _horahasta + '</span></div></div></td>';
                _output += '<td class=""><div class=""><div class="btn-group">';
                _output += '<button id="btnDelHorario_' + _id + '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1" onclick="f_DelHorario(';
                _output += _paisid + ',' + _emprid + ',' + _id + ')" title="Eliminar Turno/Horario" ><i class="fa fa-trash"></i></button></div></div></td></tr>'

                $('#tblHorarios').append(_output);
                toastSweetAlert("top-end",3000,"success","Horario Agregado");

            }else{
                toastSweetAlert("top-end",3000,"info","Dia - Turno/Horario ya Existe..!!");
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
    
    function f_EditarTipo(_pacaid, _padeid){

        _btnopctiontipo = "Mod";

        const btn = document.getElementById('btnAgregarTipo');
        btn.innerHTML = '<span><i class="las la-pencil-alt">' + '\xa0' + ' Modificar</span>';

        _padeidact = $('#txtPadeid' + _padeid).val();
        _tipoprofeold = $('#txtTiprofe' + _padeid).val();
        _valorvold = $('#txtValor' + _padeid).val();

        $('#txtTipoProfesion').val(_tipoprofeold);
        $('#txtCodigoTipo').val(_valorvold);
        $('#txtCodigoTipo').attr('disabled',true);

    }

    //Update estado Especialidades 
    function f_UpdateEstado(_paisid, _emprid, _preeid){
        
        let _usuaid = "<?php echo $xUsuaid; ?>";
        let _check = $("#chk" + _preeid).is(":checked");
        let _checked = "";
        let _class = "badge badge-light-primary";
        let _td = "td_" + _preeid;
        let _btnedit = "btnEditar_" + _preeid;
        let _btnper = "btnPerson_" + _preeid;
        let _btnmot = "btnMotivos_" + _preeid;
        

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
            "xxEmprId" : _emprid,
            "xxUsuaid" : _usuaid,
            "xxPreeid" : _preeid,
            "xxEstado" : _estado
        }	

        var xrespuesta = $.post("codephp/update_estadoespecipresta.php", _parametros);
            xrespuesta.done(function(response){

        });	
    }            
    
    function f_GrabarEspe(_paisid, _emprid){

        _usuaid = "<?php echo $xUsuaid; ?>";
        _presid = "<?php echo $xPresid; ?>";

        _cboespeci = $('#cboEspecialidadEdit').val();
        _pvp = $('#txtPvpEdit').val();
        _costo = $('#txtCostoEdit').val();
        _espeid = $('#txtcboespe').val();
        _especialidad = $("#cboEspecialidadEdit option:selected").text();

        var _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprId" : _emprid,
            "xxUsuaid" : _usuaid,
            "xxPresid" : _presid,
            "xxEspeid" : _cboespeci,
            "xxEspeidant" : _espeid,
            "xxPvp" : _pvp,
            "xxCosto": _costo
        }

        var xrespuesta = $.post("codephp/grabar_editarprestaespeci.php", _parametros);
        xrespuesta.done(function(response){

            if(response.trim() == 'OK'){
                _output = '<td><div class="d-flex align-items-center"><div class="ms-5"><span class="fw-bolder">' + _especialidad + '</span><input type="hidden" id="txtEspeciPrestador' + _rowid + '" value="' + _especialidad + '"/></div></div></td>';
                _output += '<td><div class="d-flex align-items-center"><div class="ms-5"><span class="fw-bolder">' + _pvp + '</span></div></div></td>';
                _output += '<td><div class="d-flex align-items-center"><div class="ms-5"><span class="fw-bolder">' + _costo + '</span></div></div></td>';
                _output += '<td id="td_' + _rowid + '"><div class="d-flex align-items-center"><div class="ms-5"><div class="badge badge-light-primary">Activo</div></div></div></td>';                        
                _output += '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid"> '; 
                _output += '<input class="form-check-input h-20px w-20px border-primary" checked="checked" type="checkbox" id="chk' + _rowid + '" onchange="f_UpdateEstado(';
                _output += _paisid + ',' + _emprid + ',' + _rowid + ')" value="' + _rowid + '"/></div></div></td>';
                _output += '<td class=""><div class=""><div class="btn-group"><button id="btnEditar_' + _rowid + '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" ';
                _output += 'title="Editar Especialidad Asignada" ><i class="fa fa-edit"></i></button>';
                _output += '<button id="btnPerson_' + _rowid + '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" onclick="f_AgregarProfesional(';
                _output += _paisid + ',' + _emprid + ',' + _presid + ',' + _rowid + ')" title="Agregar Profesional" ><i class="fas fa-user"></i></button>';
                _output += '</div></div></td>';

                $('#row_' + _rowid + '').html(_output);
            }else{
                toastSweetAlert("top-end",3000,"warning","Especialidad ya Existe..!!");
            }
        });	                

        $("#modal-editar-especialidad").modal("hide");

    }

    $(document).on("click","#chkEnviarProf",function(){
            
        var _chanspan = document.getElementById("spanEnvProf");
        var _emailprof =  $.trim($('#txtEmailProf').val());

        if(_emailprof != ''){
            var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
        
            if (regex.test($('#txtEmailProf').val().trim())){
                if($("#chkEnviarProf").is(":checked")){
                    _chanspan.innerHTML = '<span id="spanEnv1" class="form-check-label fw-bold" for="chkEnviar1"><strong>Enviar</strong></span>';
                    _enviarprof = 'SI';
                }else{
                    _chanspan.innerHTML = '<span id="spanEnv1" class="form-check-label fw-bold text-muted" for="chkEnviar1">No Enviar</span>';
                    _enviarprof = 'NO';
                }
            }else{
                $('#chkEnviarProf').prop('checked','');
                toastSweetAlert("top-end",3000,"error","Email no es Valido..!!");
                _enviarprof = 'SI';
                return;
            }
        }else{
            $('#chkEnviarProf').prop('checked','');
            _enviarprof = 'NO';
        }
    });            

    $('#btnSaveProf').click(function(e){

        var _paisid = "<?php echo $xPaisid; ?>";
        var _emprid = "<?php echo $xEmprid; ?>";
        var _usuaid = "<?php echo $xUsuaid; ?>";
        var _tipodoc = $('#cboTipoDoc').val();
        var _numdocumento = $.trim($("#txtNumDocumento").val());
        var _nombres = $.trim($("#txtNombresProf").val());
        var _apellidos = $.trim($("#txtApellidosProf").val());
        var _genero = $('#cboTipoGenero').val();
        var _tipoprof = $('#cboTipoProfesion').val();
        var _direccion = $.trim($("#txtDireccionProf").val());
        var _telefono = $.trim($("#txtFonoProf").val());
        var _celular = $.trim($("#txtCelularProf").val());
        var _emailprof = $.trim($("#txtEmailProf").val());
        var _selecc = 'NO'; 
        var _continuar = true;
        
        var _imgfile = document.getElementById("imgfileprof").style.backgroundImage;
        var _url = _imgfile.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
        var _pos = _url.trim().indexOf('.');
        var _ext = _url.trim().substr(_pos, 5);

        if(_ext.trim() != '.svg' ){
            _selecc = 'SI';
        }

        if(_tipodoc == ''){
            toastSweetAlert("top-end",3000,"warning","Seleccione Tipo Documento..!");
            return;                    
        }

        if(_numdocumento == ''){
            toastSweetAlert("top-end",3000,"warning","Ingrese Documento..!!");
            return;                    
        }

        if(_numdocumento.length < 10){
            toastSweetAlert("top-end",3000,"error","Documento Incorrecto..!!");
            return;                    
        }

        if(_nombres == ''){
            toastSweetAlert("top-end",3000,"warning","Ingrese Nombres..!!");
            return;                    
        }

        if(_genero == ''){
            toastSweetAlert("top-end",3000,"warning","Seleccione Genero..!!");
            return;                    
        }

        if(_tipoprof == ''){
            toastSweetAlert("top-end",3000,"warning","Seleccione Profesion..!!");
       
            return;                    
        }  
        
        if(_telefono != ''){
            if(_telefono.length < 7){
                toastSweetAlert("top-end",3000,"error","Telefono Incorrecto..!!");
                return; 
            }
        }

        if(_celular != ''){
            if(_celular.length < 10){
                toastSweetAlert("top-end",3000,"error","Celular Incorrecto..!!");
                return; 
            }
        }

        if(_selecc == 'SI'){
            var _imagen = document.getElementById("imgavatar");
            var _file = _imagen.files[0];
            var _fullPath = document.getElementById('imgavatar').value;
            _ext = _fullPath.substring(_fullPath.length - 4);
            _ext = _ext.toLowerCase();   

            if(_ext.trim() != '.png' && _ext.trim() != '.jpg' && _ext.trim() != 'jpeg'){
                toastSweetAlert("top-end",3000,"error","Archivo no es Imagen..!!");
                return;
            }                    
        }

        if(_emailprof.trim() != ''){
            var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
        
            if (regex.test($('#txtEmailProf').val().trim())) {
            }else{
                toastSweetAlert("top-end",3000,"error","Email Incorrecto..!!");
                return;
            }
        }

        if(_enviarprof == 'SI'){
            if(_emailprof.trim() == ''){
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
        form_data.append('xxGenero', _genero);
        form_data.append('xxTipoProfesion', _tipoprof);
        form_data.append('xxDireccion', _direccion);
        form_data.append('xxFono', _telefono);
        form_data.append('xxCelular', _celular);
        form_data.append('xxEmail', _emailprof);
        form_data.append('xxEnviar', _enviarprof);
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
                    $("#modal-new-profesional").modal("hide");

                }else{
                    toastSweetAlert("top-end",3000,"warning","Profesional ya Existe..!!");
                }
            },								
            error: function (error){
                console.log(error);
            }
        });
    });

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
    
    $("#modal-new-profesional").draggable({
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

