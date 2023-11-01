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

    <!--begin::Container-->
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
                            <button type="button" id="btnProbarAgenda" class="btn btn-primary w-100" >
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z" fill="currentColor" />
                                        <path opacity="0.3" d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z" fill="currentColor" />
                                    </svg>
                                </span>
                                Probar Agenda
                            </button>    

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
                        <!-- <a href="?page=prestador_admin&menuid=<?php echo $menuid;?>" class="btn btn-icon btn-light-primary btn-sm ms-auto me-lg-n7">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M11.2657 11.4343L15.45 7.25C15.8642 6.83579 15.8642 6.16421 15.45 5.75C15.0358 5.33579 14.3642 5.33579 13.95 5.75L8.40712 11.2929C8.01659 11.6834 8.01659 12.3166 8.40712 12.7071L13.95 18.25C14.3642 18.6642 15.0358 18.6642 15.45 18.25C15.8642 17.8358 15.8642 17.1642 15.45 16.75L11.2657 12.5657C10.9533 12.2533 10.9533 11.7467 11.2657 11.4343Z" fill="currentColor" />
                                </svg>
                            </span>
                        </a> -->
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
                                                                <div class="required fs-6 fw-bold mt-2 mb-3">Direccion:</div>
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
                                                        <input type="text" class="form-control mb-2 w-150px" name="txtFono1" id="txtFono1" maxlength="10" placeholder="0299999999" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xFono1; ?>" />
                                                    </div>
                                                    <div class="col">
                                                        <div class="fs-6 fw-bold mt-2 mb-3">Telefono 2:</div>
                                                        <input type="text" class="form-control mb-2 w-150px" name="txtFono2" id="txtFono2" maxlength="10" placeholder="0299999999" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xFono2; ?>" />
                                                    </div> 
                                                    <div class="col">
                                                        <div class="fs-6 fw-bold mt-2 mb-3">Telefono 2:</div>
                                                        <input type="text" class="form-control mb-2 w-150px" name="txtFono2" id="txtFono2" maxlength="10" placeholder="0299999999" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xFono3; ?>" />
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
                                <div class="card card-flush py-4">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>Datos Especialidad</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="mb-10 fv-row">
                                            <label class="required form-label">Especialidad</label>
                                            <select name="cboEspecialidad" id="cboEspecialidad" aria-label="Seleccione Especialidad" data-control="select2" data-placeholder="Seleccione Especialidad" data-dropdown-parent="#kt_ecommerce_add_product_advanced" class="form-select mb-2">
                                                <option></option>
                                                <?php 
                                                $xSQL = "SELECT espe_id AS Codigo,espe_nombre AS NombreEspe FROM `expert_especialidad` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND espe_estado='A' ";
                                                $all_datos =  mysqli_query($con, $xSQL);
                                                foreach ($all_datos as $datos){ ?>
                                                    <option value="<?php echo $datos['Codigo'] ?>"><?php echo $datos['NombreEspe'] ?></option>
                                                <?php } ?>                                                        
                                            </select>                                             
                                        </div>
                                        <div class="mb-10 fv-row">
                                            <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                                                <div class="col">
                                                    <label class="form-label">Pvp</label>
                                                    <input type="number" name="txtPvp" id="txtPvp" class="form-control mb-2" placeholder="Precio al Publico (0.00)" min="0" maxlength = "6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="0.00" step="0.01" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" disabled />
                                                </div>
                                                <div class="col">
                                                    <label class="form-label">Costo Red</label>
                                                    <input type="number" name="txtCosto" id="txtCosto" class="form-control mb-2" placeholder="Precio al Publico (0.00)" min="0" maxlength = "6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="0.00" step="0.01" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group mt-5">
                                            <button type="button" data-repeater-create="" class="btn btn-sm btn-light-primary" id="btnAgregar">
                                                <span class="svg-icon svg-icon-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                                                        <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
                                                    </svg>
                                                </span>
                                            Agregar Especialidad
                                            </button>
                                        </div>                                        
                                    </div>
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
                                                <table id="tblEspecialidad" class="table align-middle table-row-dashed fs-6 gy-5" style="width: 100%;">
                                                    <thead>
                                                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                                            <th style="display: none;">Id</th>
                                                            <th>Especialidad</th>
                                                            <th>Pvp</th>
                                                            <th>Costo</th>
                                                            <th>Estado</th>
                                                            <th>Status</th>
                                                            <th>Opciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="fw-bold text-gray-600">

                                                        <?php 
                                                
                                                            foreach($all_especialidad as $especi){
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
                                
                                                                    if($xEstado == 'A'){
                                                                        $xEstado = 'Activo';
                                                                        $chkEstado = 'checked="checked"';
                                                                        $xTextColor = "badge badge-light-primary";
                                                                    }else{
                                                                        $xEstado = 'Inactivo';
                                                                        $xTextColor = "badge badge-light-danger";
                                                                        $xDisabledEdit = 'disabled';
                                                                        $xDisabledPerson = 'disabled';
                                                                    }
                                
                                                                ?>
                                                                <tr id="row_<?php echo $xId; ?>">
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="ms-5">
                                                                                <span class="fw-bolder"><?php echo $xEspecialidad; ?></span>
                                                                                <input type="hidden" id="txtEspeciPrestador<?php echo $xId; ?>" value="<?php echo $xEspecialidad; ?>" />
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="ms-5">
                                                                                <span class="fw-bolder"><?php echo $xPvp; ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    
                                                                    <td>
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="ms-5">
                                                                                <span class="fw-bolder"><?php echo $xCosto; ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </td>                                    
                                
                                                                    <td id="td_<?php echo $xId; ?>">
                                                                        <div class="d-flex align-items-center">
                                                                            <div class="ms-5">
                                                                                <div class="<?php echo $xTextColor; ?>"><?php echo $xEstado; ?></div>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    
                                                                    <td>
                                                                        <div class="text-center">
                                                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                                <input class="form-check-input h-20px w-20px border-primary" <?php echo $chkEstado; ?> type="checkbox" id="chk<?php echo $xId; ?>" 
                                                                                    onchange="f_UpdateEstado(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xId; ?>)" value="<?php echo $xId; ?>"/>
                                                                            </div>
                                                                        </div>
                                                                    </td> 													
                                
                                                                    <td class="">
                                                                        <div class="">
                                                                            <div class="btn-group">
                                                                                <button id="btnEditar_<?php echo $xId; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" <?php echo $xDisabledEdit; ?> title='Editar Especialidad Asiganada' >
                                                                                    <i class='fa fa-edit'></i>
                                                                                </button>	
                                                                                <button id="btnPerson_<?php echo $xId; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" <?php echo $xDisabledPerson; ?> onclick='f_AgregarProfesional(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xPresid; ?>,<?php echo $xId; ?>)' title='Agregar Profesional' >
                                                                                    <i class="fas fa-user"></i>
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

        <div class="modal fade" id="modal-new-especialidad" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Nueva Especialidad</h2>
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <form id="kt_modal_new_card_form" class="form">
                            <div class="row mb-10">
                                <div class="col-md-12 fv-row">
                                    <label class="required fs-6 fw-bold form-label mb-2">Tipo Especialidad</label>
                                    <div class="row fv-row">
                                        <div class="col-12">
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
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-column mb-7 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                    <span class="required">Especialidad</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Especifique el nombre de la especialidad"></i>
                                </label>
                                <input type="text" class="form-control mb-2 text-uppercase" maxlength="250" placeholder="Nombre Especialidad" name="txtEspecialidad" id="txtEspecialidad" />
                            </div>

                            <div class="fv-row mb-15">
                                <label class="fs-6 fw-bold form-label mb-2">
                                    <span>Descripcion</span>
                                </label>
                                <textarea class="form-control mb-2" name="txtDescripcion" id="txtDescripcion" maxlength="150" onkeydown="return (event.keyCode!=13);"></textarea>
                            </div>  
                            
                            <div class="d-flex flex-column mb-7 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                    <span>Precio (PVP)</span>
                                </label>
                                <input type="number" name="txtPvpNew" id="txtPvpNew" class="form-control mb-2" placeholder="Precio al Publico (0.00)" min="0" maxlength = "6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="0.00" step="0.01" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                            </div>                            

                            <div class="modal-footer pt-15">
                                <button type="reset" data-bs-dismiss="modal" class="btn btn-secondary">Cerrar</button>
                                <button type="button" id="btnSaveNew" class="btn btn-primary"><i class="las la-save"></i>
                                    <span class="indicator-label">Grabar</span>
                                    <span class="indicator-progress">Espere un momento...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-editar-especialidad" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Editar Especialidad-Asignada</h2>
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <form id="kt_modal_new_card_form" class="form">
                            <div class="row mb-10">
                                <div class="col-md-12 fv-row">
                                    <label class="required fs-6 fw-bold form-label mb-2">Especialidad</label>
                                    <div class="row fv-row">
                                        <div class="col-12">
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
                                </div>
                            </div>

                            <div class="mb-10 fv-row">
                                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                                    <div class="col">
                                        <label class="form-label">Pvp</label>
                                        <input type="number" name="txtPvpEdit" id="txtPvpEdit" class="form-control mb-2" placeholder="Precio al Publico (0.00)" min="0" maxlength = "6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="0.00" step="0.01" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                                    </div>
                                    <div class="col">
                                        <label class="form-label">Costo Red</label>
                                        <input type="number" name="txtCostoEdit" id="txtCostoEdit" class="form-control mb-2" placeholder="Precio al Publico (0.00)" min="0" maxlength = "6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="0.00" step="0.01" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                                    </div>
                                    <input type="hidden" name="txtcboespe" id="txtcboespe" class="form-control mb-2"  />
                                </div>
                            </div>

                            <div class="modal-footer pt-15">
                                <button type="reset" data-bs-dismiss="modal" class="btn btn-secondary">Cerrar</button>
                                <button type="button" id="btnEditarEspe" class="btn btn-primary" onclick="f_GrabarEspe(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xPresid; ?>)"><i class="las la-save"></i>
                                    <span class="indicator-label">Grabar</span>
                                    <span class="indicator-progress">Espere un momento...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal_profesional" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-750px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">Agregar Profesional/Configurar Horarios</h2> 
                         
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">                                
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="modal-header">
                        <h5 id="headerTitle"></h5>
                    </div>
                    
                    <div class="modal-body scroll-y mx-lg-5 my-7">
                        <div class="flex-lg-row-fluid ">
                            <div class="d-flex flex-column scroll-y me-n7 pe-7" id="modal_profesional_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#modal_profesional_header" data-kt-scroll-wrappers="#modal_profesional_scroll" data-kt-scroll-offset="150px">
                                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                                    <div class="col">
                                        <div class="fv-row mb-7">
                                            <label class="fs-6 fw-bold form-label mt-3">
                                                <span class="required">Tipo Profesion</span>
                                                <!-- <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Enter the contact's email."></i> -->
                                            </label>
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
                                    </div>
                                    <div class="col">
                                        <div class="fv-row mb-7">
                                            <label class="fs-6 fw-bold form-label mt-3">
                                                <span class="required">Profesional</span>
                                            </label>
                                            <select name="cboProfesional" id="cboProfesional" aria-label="Seleccione Profesional" data-control="select2" data-placeholder="Seleccione Profesional" data-dropdown-parent="#modal_profesional" class="form-select mb-2">
                                                <option></option>
                                            </select>                                            
                                        </div>
                                    </div>
                                         <div class="col">
                                        <div class="fv-row mb-7">
                                            <label class="fs-6 fw-bold form-label mt-3">
                                                <span class="required">Intervalo</span>
                                                <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Intervalo de 10 a 60 minutos"></i>
                                            </label>   
                                            <input type="number" name="txtIntervalo" id="txtIntervalo" min="10" max="60" step="10" class="form-control mb-2" value="10" onKeyPress="if(this.value.length==2) return false;"  pattern="/^-?\d+\.?\d*$/" />
                                        </div>
                                    </div>                               
                                </div>

                                <div class="form-group mt-5">
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

                                <div class="separator my-7"></div>
                                
                                <table id="tblProfesional" class="table align-middle table-row-dashed fs-6 gy-5" style="width: 100%;">
                                    <thead>
                                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                            <th>Profesional</th>
                                            <th>Tipo_Profesion</th>
                                            <th>Intervalo</th>
                                            <th>Estado</th>
                                            <th>Status</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bold text-gray-600">

                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer pt-15">
                                <button type="reset" data-bs-dismiss="modal" class="btn btn-secondary">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>         
                
        <div class="modal fade" id="modal_horarios" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-750px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title">Configurar Horarios/Turnos</h2> 

                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">                                
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="modal-header">
                        <h5 id="headertitu1"></h5>
                    </div>
                    
                    <div class="modal-body scroll-y mx-lg-5 my-7">
                        <div class="flex-lg-row-fluid">
                            <div class="d-flex flex-column scroll-y me-n7 pe-7" id="modal_horarios_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#modal_horarios_header" data-kt-scroll-wrappers="#modal_horarios_scroll" data-kt-scroll-offset="300px">
                                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                                    <div class="col">
                                        <div class="fv-row mb-7">
                                            <label class="fs-6 fw-bold form-label mt-3">
                                                <span class="required">Dia</span>
                                                <!-- <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Enter the contact's email."></i> -->
                                            </label>
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
                                    </div>  
                                    <!-- <div class="col">
                                        <div class="fv-row mb-7">
                                            <label class="fs-6 fw-bold form-label mt-3">
                                                <span class="required">Intervalo</span>
                                                <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Intervalo de 30 a 60 minutos"></i>
                                            </label>   
                                            <input type="number" name="txtIntervalo" id="txtIntervalo" min="10" max="60" step="10" class="form-control mb-2" value="10" />
                                        </div>
                                    </div> -->
                                </div>

                                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                                    <div class="col">
                                        <div class="fv-row mb-7">
                                            <label class="fs-6 fw-bold form-label mt-3">
                                                <span class="required">Hora Desde</span>
                                            </label>
                                            <input class="form-control form-control-solid" name="txtHoraDesde" id="txtHoraDesde" placeholder="Hora Inicio" />
                                        </div>
                                    </div>  
                                    <div class="col">
                                        <div class="fv-row mb-7">
                                            <label class="fs-6 fw-bold form-label mt-3">
                                                <span class="required">Hora Hasta</span>
                                            </label>   
                                            <input class="form-control form-control-solid" name="txtHoraHasta" id="txtHoraHasta" placeholder="Hora Hasta" />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-5">
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
                                
                                <div class="card-header">
                                    <div class="card-title">
                                        <h2>Turnos Asignados</h2>
                                    </div>
                                </div>                                     

                                <table id="tblHorarios" class="table align-middle table-row-dashed fs-6 gy-5" style="width: 100%;">
                                    <thead>
                                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                            <th>Dia</th>
                                            <!-- <th>Intervalo</th> -->
                                            <th>H.Desde</th>
                                            <th>H.Hasta</th>
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bold text-gray-600">

                                    </tbody>
                                </table>

                                <div class="modal-footer pt-15">
                                    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancelar</button>
                                    <!-- <button type="button" class="btn btn-primary" id="btnGrabarHorario" onclick="f_GrabarHorarios(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xUsuaid; ?>)"><i class="las la-save"></i>
                                        <span class="indicator-label">Grabar</span>
                                        <span class="indicator-progress">Por favor espere...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 

        <div class="modal fade" id="modal_new_tipoprofesion" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog mw-650px">
				<div class="modal-content">
                    <div class="modal-header">
                        <h2>Nuevo Tipo Profesional</h2>
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                </svg>
                            </span>
                        </div>
                    </div>
					<div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <div class="d-flex flex-column mb-7 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                <span>Tipo Profesion</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Ingrese Tipo Profesion (Medico-Odontolo-Educador-Plomero-etc.."></i>
                            </label>
                            <input type="text" class="form-control mb-2 text-uppercase" minlength="1" maxlength="150" placeholder="Tipo Profesion" name="txtTipoProfesion" id="txtTipoProfesion" />
                        </div>
                        <div class="d-flex flex-column mb-7 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                <span>Valor/Codigo</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Ingrese Tipo Profesion (Medico-Odontolo-Educador-Plomero-etc.."></i>
                            </label>
                            <input type="text" class="form-control mb-2 text-uppercase" minlength="1" maxlength="100" placeholder="Valor/Codigo" name="txtCodigoTipo" id="txtCodigoTipo" />
                        </div>                        
                        <div class="form-group mt-5">
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

                        <br>
						<div class="mb-10">
							<div class="mh-300px scroll-y me-n7 pe-7">
                                <table id="tblTipoProfesion" class="table align-middle table-row-dashed fs-6 gy-5 table-hover" style="width: 100%;">
                                    <thead>
                                        <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                            <th>Tipo Profesion</th>
                                            <th>Estado</th>
                                            <th>Status</th>                                
                                            <th>Opciones</th>
                                        </tr>
                                    </thead>

                                    <?php 
                                        $xSQL = "SELECT pca.paca_id,pde.pade_id,pde.pade_nombre,pde.pade_estado,pde.pade_valorV FROM `expert_parametro_cabecera` pca, `expert_parametro_detalle` pde WHERE pca.paca_id=pde.paca_id AND pca.pais_id=$xPaisid AND pca.empr_id=$xEmprid AND pca.paca_nombre='Tipo Profesion' AND pca.paca_estado='A' AND pde.pade_estado='A' ORDER BY pde.pade_orden ";
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
                                                        <div class="text-center">
                                                            <div class="btn-group">
                                                                <button id="btnEdiTipo" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" <?php echo $xDisabledEdit; ?> title='Editar Tipo Profesion' onclick="f_EditarTipo(<?php echo $xPacaid; ?>,<?php echo $xPadeid; ?>)">
                                                                    <i class='fa fa-edit'></i>
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

						<div class="separator d-flex flex-center mb-8">
							<span class="text-uppercase bg-body fs-7 fw-bold text-muted px-3"></span>
						</div>

                        <div class="modal-footer">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancelar</button>
                            <!-- <button type="button" class="btn btn-primary" id="btnSaveProf">
                                <span class="indicator-label">Grabar</span>
                                <span class="indicator-progress">Espere un momento...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button> -->
                        </div>
					</div>
				</div>
			</div>
		</div>

        <div class="modal fade" id="modal-new-profesional" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <div class="modal-header" id="modal-new-profesional_header">
                        <h2 class="fw-bolder">Nuevo Profesional</h2>
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <span class="svg-icon svg-icon-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <form id="modal-new-profesional_form" class="form" method="post" enctype="multipart/form-data">
                            <div class="d-flex flex-column scroll-y me-n7 pe-7" id="modal-new-profesional_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px">
                                <div class="fv-row mb-7">
                                    <label class="d-block fw-bold fs-6 mb-5">Avatar</label>
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

                                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                                    <div class="col">
                                        <div class="fv-row mb-7">
                                            <label class="fs-6 fw-bold form-label mt-3">
                                                <span class="required">Tipo Documento</span>
                                                <!-- <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Enter the contact's email."></i> -->
                                            </label>
                                            <?php	
                                                $xSQL = "SELECT pde.pade_valorV AS Codigo,pde.pade_nombre AS Descripcion FROM `expert_parametro_cabecera` pca, `expert_parametro_detalle` pde WHERE pca.paca_id=pde.paca_id AND pca.pais_id=$xPaisid AND pca.empr_id=$xEmprid AND pca.paca_nombre='Tipo Documento' AND pca.paca_estado='A' AND pde.pade_estado='A' ";
                                                $all_parametro = mysqli_query($con, $xSQL);    
                                            ?>
                                            <select name="cboTipoDoc" id="cboTipoDoc" aria-label="Seleccione Tipo Documento" data-control="select2" data-placeholder="Seleccione Tipo Documento" data-dropdown-parent="#modal-new-profesional" class="form-select mb-2" >
                                                <option></option>
                                                <?php foreach ($all_parametro as $parametro) : ?>
                                                    <option value="<?php echo $parametro['Codigo'] ?>"><?php echo $parametro['Descripcion']; ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>  
                                    <div class="col">
                                        <div class="fv-row mb-7">
                                            <label class="fs-6 fw-bold form-label mt-3">
                                                <span class="required">No. Documento</span>
                                            </label>   
                                            <input type="text" name="txtNumDocumento" id="txtNumDocumento" class="form-control mb-2" maxlength="10" placeholder="Numero Documento"  />                                                     
                                        </div>
                                    </div>
                                </div>

                                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                                    <div class="col">
                                        <div class="fv-row mb-7">
                                                <label class="fs-6 fw-bold form-label mt-3">
                                                    <span class="required">Nombres</span>
                                                </label>   
                                                <input type="text" name="txtNombresProf" id="txtNombresProf" class="form-control mb-2 text-uppercase" maxlength="100" placeholder="Nombres"  />
                                            </div>
                                        </div>  
                                    <div class="col">
                                        <div class="fv-row mb-7">
                                            <label class="fs-6 fw-bold form-label mt-3">
                                                <span class="required">Apellidos</span>
                                            </label>   
                                            <input type="text" name="txtApellidosProf" id="txtApellidosProf" class="form-control mb-2 text-uppercase" maxlength="100" placeholder="Apellidos" />
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                                    <div class="col">
                                        <div class="fv-row mb-7">
                                            <label class="fs-6 fw-bold form-label mt-3">
                                                <span class="required">Genero</span>
                                                <!-- <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Enter the contact's email."></i> -->
                                            </label>
                                            <?php	
                                                $xSQL = "SELECT pde.pade_valorV AS Codigo,pde.pade_nombre AS Descripcion FROM `expert_parametro_cabecera` pca, `expert_parametro_detalle` pde WHERE pca.paca_id=pde.paca_id AND pca.pais_id=$xPaisid AND pca.empr_id=$xEmprid AND pca.paca_nombre='Tipo Genero' AND pca.paca_estado='A' AND pde.pade_estado='A' ";
                                                $all_parametro = mysqli_query($con, $xSQL);    
                                            ?>
                                            <select name="cboTipoGenero" id="cboTipoGenero" aria-label="Seleccione Genero" data-control="select2" data-placeholder="Seleccione Genero" data-dropdown-parent="#modal-new-profesional" class="form-select mb-2" >
                                                <option></option>
                                                <?php foreach ($all_parametro as $parametro) : ?>
                                                    <option value="<?php echo $parametro['Codigo'] ?>"><?php echo $parametro['Descripcion']; ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>  
                                    <div class="col">
                                        <div class="fv-row mb-7">
                                            <label class="fs-6 fw-bold form-label mt-3">
                                                <span class="required">Tipo Profesion</span>
                                            </label>   
                                            <?php	
                                                $xSQL = "SELECT pde.pade_valorV AS Codigo,pde.pade_nombre AS Descripcion FROM `expert_parametro_cabecera` pca, `expert_parametro_detalle` pde WHERE pca.paca_id=pde.paca_id AND pca.pais_id=$xPaisid AND pca.empr_id=$xEmprid AND pca.paca_nombre='Tipo Profesion' AND pca.paca_estado='A' AND pde.pade_estado='A' ";
                                                $all_parametro = mysqli_query($con, $xSQL);    
                                            ?>
                                            <select name="cboTipoProfesion" id="cboTipoProfesion" aria-label="Seleccione Profesion" data-control="select2" data-placeholder="Seleccione Profesion" data-dropdown-parent="#modal-new-profesional" class="form-select mb-2" >
                                                <option></option>
                                                <?php foreach ($all_parametro as $parametro) : ?>
                                                    <option value="<?php echo $parametro['Codigo'] ?>"><?php echo $parametro['Descripcion']; ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

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
                                            <div class="row row-cols-1 row-cols-sm-1 rol-cols-md-1 row-cols-lg-1">
                                                <div class="col-xl-10 fv-row">
                                                    <textarea class="form-control mb-2 text-uppercase" name="txtDireccionProf" id="txtDireccionProf" maxlength="250" onkeydown="return (event.keyCode!=13);"> <?php echo $xDireccion; ?> </textarea>
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
                                            <div class="row row-cols-2 row-cols-sm-2 rol-cols-md-2 row-cols-lg-2">
                                                <div class="col">
                                                    <div class="fs-6 fw-bold mt-3 mb-3">Telefono</div>
                                                    <input type="text" class="form-control mb-3 w-150px" name="txtFonoProf" id="txtFonoProf" maxlength="10" placeholder="0299999999" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                                                </div>
                                                <div class="col">
                                                    <div class="fs-6 fw-bold mt-3 mb-3">Celular</div>
                                                    <input type="text" class="form-control mb-3 w-150px" name="txtCelularProf" id="txtCelularProf" maxlength="10" placeholder="0987654321" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
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

                            <div class="modal-footer pt-15">
                                <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancelar</button>
                                <button type="button" class="btn btn-primary" id="btnSaveProf"><i class="las la-save"></i>
                                    <span class="indicator-label">Grabar</span>
                                    <span class="indicator-progress">Espere un momento...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>  
                        </form>
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
                    
                    debugger;
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

                    if(_cbotipoespe == '0'){
                        mensajesalertify('Seleccion Tipo Especialidad', 'W', 'top-center', 3);
                        return;
                    }

                    if(_especialidad == ''){
                        mensajesalertify('Ingrese Especialidad', 'W', 'top-center', 3);
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
                            mensajesalertify('Especialidad ya Existe', 'W', 'top-center', 3);
                        }else{
                            if(response.trim() != 'ERR'){
                                mensajesalertify('Especialidad Agregada', 'S', 'top-center', 3);
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
                        mensajesalertify('Ingrese Tipo Prestador', 'W', 'top-center', 3);
                        return;
                    }

                    if(_valorv == ''){
                        mensajesalertify('Ingrese Valor', 'W', 'top-center', 3);
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
                            mensajesalertify('Tipo Prestador/Valor ya Existe', 'W', 'top-center', 3);
                        }else{
                            if(response.trim() != 'ERR'){
                                mensajesalertify('Tipo Prestador Agregado', 'S', 'top-center', 3);
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
                            mensajesalertify("Email no es Valido", "W", "top-center", 3);
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
                            mensajesalertify("Email no es Valido", "W", "top-center", 3);
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
                        mensajesalertify('Seleccione Especialidad..!', 'W', 'top-center', 3);
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
                            _output += '<td><div class="d-flex align-items-center"><div class="ms-5"><span class="fw-bolder">' + _pvp + '</span></div></div></td>';
                            _output += '<td><div class="d-flex align-items-center"><div class="ms-5"><span class="fw-bolder">' + _costo + '</span></div></div></td>';
                            _output += '<td id="td_' + _id + '"><div class="d-flex align-items-center"><div class="ms-5"><div class="badge badge-light-primary">Activo</div></div></div></td>';                        
                            _output += '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid"> '; 
                            _output += '<input class="form-check-input h-20px w-20px border-primary" checked="checked" type="checkbox" id="chk' + _cboespe + '" onchange="f_UpdateEstado(';
                            _output += _paisid + ',' + _emprid + ',' + _id + ')" value="' + _id + '"/></div></div></td>';
                            _output += '<td class=""><div class=""><div class="btn-group"><button id="btnEditar_' + _id + '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" ';
                            _output += 'title="Editar Especialidad Asiganada" ><i class="fa fa-edit"></i></button></div></div></td></tr>';

                            $('#tblEspecialidad').append(_output);

                            mensajesalertify('Especialidad Agregada Correctamente..!', 'S', 'top-center', 3);
                        }else{
                            mensajesalertify('Especialidad ya está Asignada..!', 'W', 'top-center', 3);
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
                        mensajesalertify('Ingrese Tipo Profesion..!', 'W', 'top-center', 5);
                        return;
                    }

                    if(_valcodigoprof == ''){
                        mensajesalertify('Ingrese Valor/Codigo Tipo Profesion..!', 'W', 'top-center', 5);
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

                            var json = JSON.parse(response);
                            var _pacaid = json.Pacaid;
                            var _padeid = json.Padeid;

                            if(_padeid > 0){

                                if(_btnopctiontipo == 'Add'){
                                    _output = '<tr id="tr_' + _padeid + '">';    
                                    _output += '<td>' + _tipoprofesion.toUpperCase() + '<input type="hidden" id="txtPadeid' + _padeid + '" value="' + _padeid + '"/> <input type="hidden" id="txtTiprofe' + _padeid + '" value="' + _tipoprofesion + '"/> <input type="hidden" id="txtValor' + _padeid + '" value="' + _valcodigoprof  + '"/></td>';
                                    _output += '<td id="td_' + _padeid + '"><div class="d-flex align-items-center"><div class="badge badge-light-primary">Activo</div></div></td>';
                                    _output += '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid"> '; 
                                    _output += '<input class="form-check-input h-20px w-20px border-primary" checked="checked" type="checkbox" id="chk' + _padeid + '" onchange="f_UpdateEstTipo(';
                                    _output += _pacaid + ',' + _padeid + ')" value="' + _padeid + '"/></div></div></td>';
                                    _output += '<td><div class="text-center"><div class="btn-group"><button id="btnEdiTipo" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 " ';
                                    _output += 'title="Editar Tipo Profesion" onclick="f_EditarTipo(' + _pacaid + ',' + _padeid + ')" ><i class="fa fa-edit"></i></button></div></div></td></tr>';
                                }else{
                                    _output = '<td>' + _tipoprofesion.toUpperCase() + '<input type="hidden" id="txtPadeid'  + _padeid + '" value="' + _padeid + '"/> <input type="hidden" id="txtTiprofe'  + _padeid + '" value="' + _tipoprofesion + '"/> <input type="hidden" id="txtValor' + _padeid + '" value="' + _valcodigoprof  + '"/></td>';
                                    _output += '<td id="td_' + _padeid + '"><div class="d-flex align-items-center"><div class="badge badge-light-primary">Activo</div></div></td>';
                                    _output += '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid"> '; 
                                    _output += '<input class="form-check-input h-20px w-20px border-primary" checked="checked" type="checkbox" id="chk' + _padeid + '" onchange="f_UpdateEstTipo(';
                                    _output += _pacaid + ',' + _padeid + ')" value="' + _padeid + '"/></div></div></td>';
                                    _output += '<td><div class="text-center"><div class="btn-group"><button id="btnEdiTipo" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 " ';
                                    _output += 'title="Editar Tipo Profesion" onclick="f_EditarTipo(' + _pacaid + ',' + _padeid + ')" ><i class="fa fa-edit"></i></button></div></div></td>';                                    
                                }

                                if(_btnopctiontipo == 'Add'){
                                    $('#tblTipoProfesion').append(_output);
                                    _mensaje = "Tipo Profesion Agregada Correctamente..!";
                                }else{
                                    $('#tr_' + _padeid + '').html(_output);
                                    _mensaje = "Tipo Profesion Modificada Correctamente..!";
                                }                                
                                mensajesalertify(_mensaje, 'S', 'top-center', 5);

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
                                mensajesalertify('Tipo Profesion ya existe.!', 'W', 'top-center', 5);                            
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
                       mensajesalertify("Seleccione Provincia..!", "W", "top-center", 3);
                       return; 
                   }

                   if(_ciudid == ''){
                       mensajesalertify("Seleccione Ciudad..!", "W", "top-center", 3);
                       return; 
                   }

                   if(_prestador == ''){
                       mensajesalertify("Ingrese Nombre del Prestador..!", "W", "top-center", 3);
                       return;                         
                   }

                   if(_sector == ''){
                       mensajesalertify("Seleccione Sector..!", "W", "top-center", 3);
                       return; 
                   }
                   
                   if(_tipopresta == ''){
                       mensajesalertify("Seleccione Tipo Prestador..!", "W", "top-center", 3);
                       return; 
                   }                       
                   
                   if(_url != ''){
                       try{
                           new URL(_url);
                       }catch(err){
                           mensajesalertify("Direccion URL Incorrecta..!", "W", "top-center", 3);
                           return false;
                       }
                   }
                   
                   if(_telefono1 != '')
                   {
                       _valor = document.getElementById("txtFono1").value;
                       if( !(/^\d{9}$/.test(_valor)) ) {
                           mensajesalertify("Telefono1 incorrecto..!" ,"W", "top-center", 3); 
                           return;
                       }
                   }

                   if(_telefono2 != '')
                   {
                       _valor = document.getElementById("txtFono2").value;
                       if( !(/^\d{9}$/.test(_valor)) ) {
                           mensajesalertify("Telefono2 incorrecto..!" ,"W", "top-center", 3); 
                           return;
                       }
                   }                    

                   if(_telefono3 != '')
                   {
                       _valor = document.getElementById("txtFono3").value;
                       if( !(/^\d{9}$/.test(_valor)) ) {
                           mensajesalertify("Telefono3 incorrecto..!" ,"W", "top-center", 3); 
                           return;
                       }
                   }  
                   
                   if(_celular1 != '')
                   {
                       _valor = document.getElementById("txtCelular1").value;
                       if( !(/^\d{10}$/.test(_valor)) ) {
                           mensajesalertify("Celular1 incorrecto..!" ,"W", "top-center", 3); 
                           return;
                       }
                   }                     
                   
                   if(_celular2 != '')
                   {
                       _valor = document.getElementById("txtCelular2").value;
                       if( !(/^\d{10}$/.test(_valor)) ) {
                           mensajesalertify("Celular1 incorrecto..!" ,"W", "top-center", 3); 
                           return;
                       }
                   }
                   
                   if(_celular3 != '')
                   {
                       _valor = document.getElementById("txtCelular3").value;
                       if( !(/^\d{10}$/.test(_valor)) ) {
                           mensajesalertify("Celular1 incorrecto..!" ,"W", "top-center", 3); 
                           return;
                       }
                   }                    
                   
                   if(_email1 != ''){
                       var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
                   
                       if (regex.test($('#txtEmail1').val().trim())) {
                       }else{
                           mensajesalertify("Email1 no es Valido..!", "W", "top-center", 3);
                           return;
                       }
                   }

                   if(_email2 != ''){
                       var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
                   
                       if (regex.test($('#txtEmail2').val().trim())) {
                       }else{
                           mensajesalertify("Email2 no es Valido..!", "W", "top-center", 3);
                           return;
                       }
                   }

                    var _imgfile = document.getElementById("imgfile").style.backgroundImage;
                    var _urlimg = _imgfile.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
                    var _pos = _urlimg.trim().indexOf('.');
                    var _ext = _urlimg.trim().substr(_pos, 5);

                    if(_ext.trim() != '.png' && _ext.trim() != '.jpg' && _ext.trim() != 'jpeg'){
                        var _imagen = document.getElementById("imglogo");
                        var _file = _imagen.files[0];
                        var _fullPath = document.getElementById('imglogo').value;
                        _ext = _fullPath.substring(_fullPath.length - 4);
                        _ext = _ext.toLowerCase();   

                        if(_ext.trim() == '.png' || _ext.trim() == '.jpg' || _ext.trim() == 'jpeg'){
                            _cambiarlogo = 'SI';
                        }else{
                            mensajesalertify("El archivo seleccionado no es una Imagen..!", "W", "top-center", 3);
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
                                mensajesalertify("Prestador ya Existe..!", "W", "top-center", 3);
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

                debugger;
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
                            _output += _paisid + ',' + _emprid + ',' + _id + ')" title="Configurar Horario" ><i class="fas fa-cogs"></i></button>';
                            _output += '<button id="btnDelProf_' + _id + '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1"' + _disabledbtn2 + ' onclick="f_DelAsigProf(';
                            _output += _paisid + ',' + _emprid + ',' + _id + ')" title="Eliminar Profesional Asignado" ><i class="fa fa-trash"></i></button></div></div></td></tr>'

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
                    mensajesalertify("Seleccione Profesional..!", "W", "top-center", 3);
                    return;
                }

                if(_intervalo == ''){
                    mensajesalertify("Ingrese Internvalo..!", "W", "top-center", 5);
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
                        _output += '<td id="tdprof_' + _id + '"><div class="d-flex align-items-center"><div class="ms-0"><div class="badge badge-light-primary">Activo</div></div></div></td>';
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
                        mensajesalertify("Profesional esta agregado..!", "W", "top-center", 3);
                    }

                    $("#cboTipoProfe").val(0).change(); 
                    $("#cboProfesional").val(0).change(); 

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
                    _estado = "Activo";
                    _checked = "checked='checked'";
                    $('#'+_btnhorario).prop("disabled",false);
                    $('#'+_btnelimina).prop("disabled",false);
                }else{                    
                    _estado = "Inactivo";
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
                        mensajesalertify("Registro Eliminado..!", "E", "top-center", 3);
                        $('#trprof_' + _pfesid).remove();
                    }else{
                        mensajesalertify("El registro tiene horarios configurados..!", "W", "top-center", 3);
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
                        mensajesalertify("Registro Eliminado..!", "E", "top-center", 5);
                        $('#trhorario_' + _horaid).remove();
                    }else{
                        mensajesalertify("Hubo algun error, no se puedo eliminar..!", "W", "top-center", 5);
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
                    mensajesalertify("Seleccione Dia..!", "W", "top-center", 5);
                    return;
                }

                // if(_intervalo == ''){
                //     mensajesalertify("Ingrese Internvalo..!", "W", "top-center", 5);
                //     return;
                // }

                if(_horadesde == ''){
                    mensajesalertify("Seleccion Hora Inicio..!", "W", "top-center", 5);
                    return;
                }

                if(_horahasta == ''){
                    mensajesalertify("Seleccione Hora Final..!", "W", "top-center", 5);
                    return;
                }                

                //VALIDAR LAS HORAS

                var minutos_inicio = _horadesde.split(':').reduce((p, c) => parseInt(p) * 60 + parseInt(c));
                var minutos_final = _horahasta.split(':').reduce((p, c) => parseInt(p) * 60 + parseInt(c));
                
                if (minutos_final < minutos_inicio){
                    mensajesalertify("La Hora Inicio no puede ser mayor a la Hora Final..!", "W", "top-center", 5);
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

                    }else{
                        mensajesalertify("Dia - Turno/Horario ya existe..!", "W", "top-center", 3);
                    }

                    $("#cboTipoProfe").val(0).change(); 
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
                btn.innerHTML = '<span class="svg-icon svg-icon-3"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">																					<path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor" />																					<path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor" /></svg></span>Modificar';

                _padeidact = $('#txtPadeid' + _padeid).val();
                _tipoprofeold = $('#txtTiprofe' + _padeid).val();
                _valorvold = $('#txtValor' + _padeid).val();

                $('#txtTipoProfesion').val(_tipoprofeold);
                $('#txtCodigoTipo').val(_valorvold);
                $('#txtCodigoTipo').attr('disabled',true);

            }

            function f_UpdateEstado(_paisid, _emprid, _preeid){
                
                let _usuaid = "<?php echo $xUsuaid; ?>";
                let _check = $("#chk" + _preeid).is(":checked");
                let _checked = "";
                let _class = "badge badge-light-primary";
                let _td = "td_" + _preeid;
                let _btnedit = "btnEditar_" + _preeid;
    
                if(_check){
                    _estado = "Activo";
                    _checked = "checked='checked'";
                    $('#'+_btnedit).prop("disabled",false);
                }else{                    
                    _estado = "Inactivo";
                    _class = "badge badge-light-danger";
                    $('#'+_btnedit).prop("disabled",true);
                }
    
                var _changetd = document.getElementById(_td);
                _changetd.innerHTML = '<div class="d-flex align-items-center"><div class="ms-5"><div class="' + _class + '">' + _estado + ' </div></div>';
    
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
                        mensajesalertify("Especialidad ya está asignada..!", "W", "top-center", 3);
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
                        mensajesalertify("Email no es Valido", "W", "top-center", 3);
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
                
				var _imgfile = document.getElementById("imgfileprof").style.backgroundImage;
				var _url = _imgfile.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
				var _pos = _url.trim().indexOf('.');
				var _ext = _url.trim().substr(_pos, 5);

				if(_ext.trim() != '.svg' ){
					_selecc = 'SI';
				}

                if(_tipodoc == ''){
                    mensajesalertify("Seleccione Tipo Documento..!", "W", "top-center", 5);
					return;                    
                }

                if(_numdocumento == ''){
                    mensajesalertify("Ingrese Numero de Documento..!", "W", "top-center", 5);
					return;                    
                }

                if(_nombres == ''){
                    mensajesalertify("Ingrese Nombres..!", "W", "top-center", 5);
					return;                    
                }

                if(_genero == ''){
                    mensajesalertify("Seleccione Tipo Genero..!", "W", "top-center", 5);
					return;                    
                }

                if(_tipoprof == ''){
                    mensajesalertify("Seleccione Tipo Profesion..!", "W", "top-center", 5);
					return;                    
                }                 

				if(_selecc == 'SI'){
					var _imagen = document.getElementById("imgavatar");
					var _file = _imagen.files[0];
					var _fullPath = document.getElementById('imgavatar').value;
					_ext = _fullPath.substring(_fullPath.length - 4);
					_ext = _ext.toLowerCase();   

                    if(_ext.trim() != '.png' && _ext.trim() != '.jpg' && _ext.trim() != 'jpeg'){
					    //mensajesweetalert("center","warning","El archivo seleccionado no es una Imagen..!",false,1800);
                        mensajesalertify("El archivo seleccionado no es una Imagen..!", "W", "top-center", 5);
					    return;
				    }                    
				}

                if(_emailprof.trim() != ''){
                    var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
                
                    if (regex.test($('#txtEmailProf').val().trim())) {
                    }else{
                        mensajesalertify("Email no es Valido..!", "W", "top-center", 5);
                        return;
                    }
                }

                if(_enviarprof == 'SI'){
                    if(_emailprof.trim() == ''){
                        mensajesalertify("Ingrese Email..!", "W", "top-center", 5);
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
                            mensajesalertify("Profesional agregado correctamente..!", "W", "top-center", 5);
                            $("#modal-new-profesional").modal("hide");

                        }else{
                            mensajesalertify("Profesional ya Existe..!", "W", "top-center", 5);
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

        </script>

