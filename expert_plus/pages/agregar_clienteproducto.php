<?php
	
	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

   	//file_put_contents('log_seguimiento.txt', $xSQL . "\n\n", FILE_APPEND);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	  
    
    $xFechaActual = strftime('%Y-%m-%d', time());

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

    $xSQL = "SELECT grup_id AS Codigo,grup_nombre AS NombreGrupo FROM `expert_grupos` WHERE pais_id=$xPaisid AND empr_id=$xEmprid ";
	$all_grupos =  mysqli_query($con, $xSQL);

    
?>

<div id="kt_content_container" class="container-xxl">
    <form id="kt_ecommerce_add_product_form" class="form d-flex flex-column flex-lg-row">
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="d-flex align-items-center collapsible py-3 toggle collapsed mb-0" data-bs-toggle="collapse" data-bs-target="#view_logo_cabecera">
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
                        <h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">Logo Cabecera</h4>
                    </div>
                </div>
                <div id="view_logo_cabecera" class="collapse fs-6 ms-1">
                    <div class="card-body text-center pt-0">
                        <div class="image-input image-input-empty image-input-outline mb-3" data-kt-image-input="true" style="background-image: url(assets/media/svg/files/blank-image.svg)">
                            <div class="image-input-wrapper w-150px h-150px" style="background-image: url(assets/media/svg/files/blank-image.svg);" id="imgfileCab"></div>
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Cargar Logo">
                                <i class="bi bi-pencil-fill fs-7"></i>
                                <input type="file" name="avatar" id="logoCab" accept=".png, .jpg, .jpeg" />
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
                    <div class="d-flex align-items-center collapsible py-3 toggle collapsed mb-0" data-bs-toggle="collapse" data-bs-target="#view_logo_pie">
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
                        <h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">Logo Pie</h4>
                    </div>
                </div>
                <div id="view_logo_pie" class="collapse fs-6 ms-1">
                    <div class="card-body text-center pt-0">
                        <div class="image-input image-input-empty image-input-outline mb-3" data-kt-image-input="true" style="background-image: url(assets/media/svg/files/blank-image.svg)">
                            <div class="image-input-wrapper w-150px h-150px" style="background-image: url(assets/media/svg/files/blank-image.svg);" id="imgfilePie"></div>
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Cargar Logo">
                                <i class="bi bi-pencil-fill fs-7"></i>
                                <input type="file" name="avatar" id="logoPie" accept=".png, .jpg, .jpeg" />
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
                        <button type="button" id="btnNewGrupo" class="btn btn-light-primary btn-sm">
                            <i class="fa fa-plus-circle"></i>Nuevo Grupo                                                               
                        </button>                                                      
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-n2">
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_add_product_general">Datos Generales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_add_product_advanced">Productos</a>
                </li>
                <a href="?page=admin_clienteproducto&menuid=<?php echo $menuid;?>" class="btn btn-icon btn-light-primary btn-sm ms-auto me-lg-n7" title="Regresar" data-bs-toggle="tooltip" data-bs-placement="left">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </a>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="kt_ecommerce_add_product_general" role="tab-panel">
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Datos Cliente</h2>
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
                                            </select>                                                      
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-5 fv-row">
                                    <label class="required form-label">Cliente</label>
                                    <input class="form-control mb-2 text-uppercase" type="text" name="txtCliente" id="txtCliente"  minlength="5" maxlength="150" placeholder="Ingrese Nombre" value="" />
                                </div>
                                <div class="mb-5 fv-row">
                                    <label class="form-label">Descripcion</label>
                                    <textarea class="form-control mb-2 text-uppercase" name="txtDesc" id="txtDesc" rows="1" maxlength="200" onkeydown="return (event.keyCode!=13);"></textarea>
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
                                            <i class="fa fa-location-arrow fa-1x me-2" style="color:#F46D55;" aria-hidden="true"></i>
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
                                                        <textarea class="form-control mb-2 text-uppercase" name="txtDireccion" id="txtDireccion" rows="1" maxlength="250" onkeydown="return (event.keyCode!=13);"></textarea>
                                                    </div>
                                                </div>
                                                <div class="row mb-8">
                                                    <div class="col-xl-2">
                                                        <div class="fs-6 fw-bold mt-2 mb-3">URL:</div>
                                                    </div>
                                                    <div class="col-xl-10 fv-row">
                                                        <input type="text" class="form-control mb-2 text-lowercase" name="txtUrl" id="txtUrl" maxlength="150" placeholder="https://misitio.com" value="" />
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
                                            <!-- <img src="assets/media/logos/telefono.png" class="w-20px me-3" alt="" /> -->
                                            <i class="fa fa-phone fa-1x me-2" style="color:#7DF57D;" aria-hidden="true"></i>
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
                                                <input type="text" class="form-control mb-2 w-150px" name="txtFono1" id="txtFono1" maxlength="9" placeholder="029999999" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="" />
                                            </div>
                                            <div class="col">
                                                <div class="fs-6 fw-bold mt-2 mb-3">Telefono 2:</div>
                                                <input type="text" class="form-control mb-2 w-150px" name="txtFono2" id="txtFono2" maxlength="9" placeholder="" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="" />
                                            </div> 
                                            <div class="col">
                                                <div class="fs-6 fw-bold mt-2 mb-3">Telefono 3:</div>
                                                <input type="text" class="form-control mb-2 w-150px" name="txtFono3" id="txtFono3" maxlength="9" placeholder="" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="" />
                                            </div>                                                        
                                        </div>
                                        <div class="row row-cols-1 row-cols-sm-3 rol-cols-md-3 row-cols-lg-3">
                                            <div class="col">
                                                <div class="fs-6 fw-bold mt-2 mb-3">Celular 1:</div>
                                                <input type="text" class="form-control mb-2 w-150px" name="txtCelular1" id="txtCelular1" maxlength="10" placeholder="0999999999" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="" />
                                            </div>
                                            <div class="col">
                                                <div class="fs-6 fw-bold mt-2 mb-3">Celular 2:</div>
                                                <input type="text" class="form-control mb-2 w-150px" name="txtCelular2" id="txtCelular2" maxlength="10" placeholder="" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="" />
                                            </div> 
                                            <div class="col">
                                                <div class="fs-6 fw-bold mt-2 mb-3">Celular 3:</div>
                                                <input type="text" class="form-control mb-2 w-150px" name="txtCelular3" id="txtCelular3" maxlength="10" placeholder="" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="" />
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
                                            <i class="fa fa-envelope fa-2x me-2" style="color:#5AD1F1;" aria-hidden="true"></i>
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
                                                <input type="email" name="txtEmail1" id="txtEmail1" maxlength="150" placeholder="correo@dominio.com" class="form-control mb-2 text-lowercase" value="" />
                                            </div>                                                 
                                        </div>
                                        <div class="d-flex flex-wrap gap-5">
                                            <div class="fv-row w-100 flex-md-root">
                                                <label class="form-label">Email 2</label>
                                                <input type="email" name="txtEmail2" id="txtEmail2" maxlength="150" placeholder="" class="form-control mb-2 text-lowercase" value="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="kt_ecommerce_add_product_advanced" role="tab-panel">
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <div class="form-group mt-2 mb-n2">
                            <button type="button" data-repeater-create="" class="btn btn-light-primary btn-sm mb-2" id="btnAddProd">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>Agregar Producto
                            </button>
                        </div>  
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Productos Asignados</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="d-flex flex-column gap-10">
                                    <div class="scroll-y me-n7 pe-7" id="parametro_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#parametro_header" data-kt-scroll-wrappers="#parametro_scroll" data-kt-scroll-offset="300px">
                                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="tblProducto">
                                            <thead>
                                                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                                    <th style="display:none;">Id</th>
                                                    <th>Grupo</th>
                                                    <th>Producto</th>
                                                    <th>Costo</th>
                                                    <th>Opciones</th>
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
            </div>
            <div class="d-flex justify-content-end">
                <button type="button" id="btnGrabar" onclick="f_Guardar(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xUsuaid; ?>)" class="btn btn-primary">
                    <span class="indicator-label">Grabar</span>
                </button>
            </div>
        </div>
    </form>
</div>

<!--MODAL AGREGAR PRODUCTO-->
<div class="modal fade" id="modal_addproducto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Agregar Producto</h2>
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
                        <div class="row">
                            <div class="col-md-12">
                                <label class="required form-label">Producto</label>
                                <input class="form-control mb-2 text-uppercase" type="text" name="txtProducto" id="txtProducto" class="form-control mb-2" maxlength="150" placeholder="Ingrese Producto" value="" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Descripcion</label>
                                <textarea class="form-control mb-2 text-uppercase" name="txtDescripcion" id="txtDescripcion" rows="1" maxlength="200" onkeydown="return(event.keyCode!=13);"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="required form-label">Costo</label>
                                <input type="number" name="txtCosto" id="txtCosto" class="form-control mb-2" placeholder="Precio al Publico (0.00)" min="0" maxlength = "6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="0.00" step="0.01" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                            </div>
                            <div class="col-md-6">
                                <label class="required form-label">Grupo</label>
                                <select name="cboGrupo" id="cboGrupo" aria-label="Seleccione Grupo" data-control="select2" data-placeholder="Seleccione Grupo" data-dropdown-parent="#modal_addproducto" class="form-select mb-2" >
                                    <option></option>
                                    <?php foreach ($all_grupos as $datos) : ?>
                                        <option value="<?php echo $datos['Codigo'] ?>"><?php echo mb_strtoupper($datos['NombreGrupo']) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <div class="col-md-6">
                                <label class="form-label">Asistencia Mes</label>
                                <input type="number" name="txtAsisMes" id="txtAsisMes" class="form-control mb-2" value="1" onkeypress="return isNumberKey(event)" />   
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Asistencia Anual</label>
                                <input type="number" name="txtAsisAnu" id="txtAsisAnu" class="form-control mb-2" placeholder="1" value="1" onkeypress="return isNumberKey(event)" />  
                            </div>
                        </div>
                        <div class="row border border-hover-primary py-lg-4 px-lg-20">
                            <div class="col-md-6">
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" name="chkCobertura" id="chkCobertura" type="checkbox" />
                                    <h5 class="form-check-label" id="lblCobertura" for="chkEnviar1">Cobertura NO</h5>
                                </label>  
                            </div>
                            <div class="col-md-6">
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" name="chkSistema" id="chkSistema" type="checkbox" />
                                    <h5 class="form-check-label" id="lblSistema" for="chkEnviar1">Sistema NO</h5>
                                </label>   
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
<!--MODAL NUEVO GRUPO -->
<!-- <div class="modal fade" id="modal_new_grupo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Nuevo Grupo</h2>
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
                        <span class="required">Grupo</span>
                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Especifique el nombre del grupo"></i>
                    </label>
                    <input type="text" class="form-control mb-2 text-uppercase" minlength="1" maxlength="80" placeholder="Nombre Grupo" name="txtGrupo" id="txtGrupo" />
                </div>
                <div class="fv-row mb-15">
                    <label class="fs-6 fw-bold form-label mb-2">
                        <span>Descripcion</span>
                    </label>
                    <textarea class="form-control mb-2 text-uppercase" name="txtDescGrupo" id="txtDescGrupo" maxlength="150" onkeydown="return(event.keyCode!=13);"></textarea>
                </div> 
                <div class="row mb-7">
                    <div class="col-md-6">
                        <label class="form-label">Secuencial Agenda</label>
                        <input type="number" name="txtnumagenda" id="txtnumagenda" class="form-control mb-2" value="1"  />   
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Secuencial Cancelado</label>
                        <input type="number" name="txtnumcancelado" id="txtnumcancelado" class="form-control mb-2" placeholder="1" value="1" />  
                    </div>
                </div>
                <div class="row mb-7">
                    <div class="col-md-6">
                        <label class="form-label">Secuencial Atendido</label>
                        <input type="number" name="txtnumatendido" id="txtnumatendido" class="form-control mb-2" value="1" />   
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Secuencial Ausente</label>
                        <input type="number" name="txtnumausente" id="txtnumausente" class="form-control mb-2" placeholder="1" value="1" />  
                    </div>
                </div>                                          
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" id="btnGuardar" onclick="f_GuardarGrupo(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xUsuaid; ?>)" class="btn btn-primary">Grabar</button>
            </div>
        </div>
    </div>
</div> -->
<!--MODAL NUEVO GRUPO -->
<div class="modal fade" id="modal_new_grupo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Nuevo Grupo</h2>
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
                        <div class="d-flex flex-column mb-7 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                <span class="required">Grupo</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Especifique el nombre del grupo"></i>
                            </label>
                            <input type="text" class="form-control mb-2 text-uppercase" minlength="1" maxlength="80" placeholder="Nombre Grupo" name="txtGrupo" id="txtGrupo" />
                        </div>
                        <div class="fv-row mb-15">
                            <label class="fs-6 fw-bold form-label mb-2">
                                <span>Descripcion</span>
                            </label>
                            <textarea class="form-control mb-2 text-uppercase" name="txtDescGrupo" id="txtDescGrupo" maxlength="150" onkeydown="return(event.keyCode!=13);"></textarea>
                        </div> 
                        <div class="row mb-7">
                            <div class="col-md-6">
                                <label class="form-label">Secuencial Agenda</label>
                                <input type="number" name="txtnumagenda" id="txtnumagenda" class="form-control mb-2" value="1"  />   
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Secuencial Cancelado</label>
                                <input type="number" name="txtnumcancelado" id="txtnumcancelado" class="form-control mb-2" placeholder="1" value="1" />  
                            </div>
                        </div>
                        <div class="row mb-7">
                            <div class="col-md-6">
                                <label class="form-label">Secuencial Atendido</label>
                                <input type="number" name="txtnumatendido" id="txtnumatendido" class="form-control mb-2" value="1" />   
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Secuencial Ausente</label>
                                <input type="number" name="txtnumausente" id="txtnumausente" class="form-control mb-2" placeholder="1" value="1" />  
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger" data-bs-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i>Cerrar</button>
                <button type="button" id="btnGuardar" onclick="f_GuardarGrupo(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xUsuaid; ?>)" class="btn btn-sm btn-light-primary"><i class="las la-save"></i>Grabar</button>
            </div>
        </div>
    </div>
</div>  

<script>
    
    var _cobertura = "NO",_sistema = "NO", _count = 0, _result = [],_paisid = "<?php echo $xPaisid; ?>",_emprid = "<?php echo $xEmprid; ?>";

    $(document).ready(function(){

        $('#cboProvincia').change(function(){
                              
            var _cboid = $(this).val(); //obtener el id seleccionado
            
            $("#cboCiudad").empty();


            var _parametros = {
                "xxPaisId" : _paisid,
                "xxEmprId" : _emprid,
                "xxComboId" : _cboid,
                "xxOpcion" : 0
            }

            var xrespuesta = $.post("codephp/cargar_combos.php", _parametros);
                xrespuesta.done(function(response) {
            
                $("#cboCiudad").html(response);
                
            });
            xrespuesta.fail(function() {
                
            });
            xrespuesta.always(function() {
                
            });                

        });

        document.getElementById("txtnumagenda").addEventListener("change", function() {
        let v = parseInt(this.value);
        if (v < 1) this.value = 1;
        if (v > 99999) this.value = 1;
        });
        
        document.getElementById("txtnumcancelado").addEventListener("change", function() {
            let v = parseInt(this.value);
            if (v < 1) this.value = 1;
            if (v > 99999) this.value = 1;
        }); 
        
        document.getElementById("txtnumatendido").addEventListener("change", function() {
            let v = parseInt(this.value);
            if (v < 1) this.value = 1;
            if (v > 99999) this.value = 1;
        }); 
        
        document.getElementById("txtnumausente").addEventListener("change", function() {
            let v = parseInt(this.value);
            if (v < 1) this.value = 1;
            if (v > 99999) this.value = 1;
        });          

        // Modal nuevo grupo

        $("#btnNewGrupo").click(function(){

            $("#modal_new_grupo").modal("show");
        });

        $( "#txtCosto" ).blur(function() {
            this.value = parseFloat(this.value).toFixed(2);

        });

        $('#btnAddProd').click(function(){
            $('#modal_addproducto').modal('show');
        });
        
        $(document).on("click","#chkCobertura",function(){

            _cobertura = "NO";

            if($("#chkCobertura").is(":checked")){
                _cobertura = "SI";
                $("#lblCobertura").text("Cobertura SI");
            }else{
                _cobertura = "NO";
                $("#lblCobertura").text("Cobertura NO");
            }    
        });

        $(document).on("click","#chkSistema",function(){

            _sistema = "NO";

            if($("#chkSistema").is(":checked")){
                _sistema = "SI";
                $("#lblSistema").text("Sistema SI");
            }else{
                _sistema = "NO";
                $("#lblSistema").text("Sistema NO");
            }
        });       
    });

        //desplazar ventana modal
    $("#modal_new_grupo").draggable({
        handle: ".modal-header"
    }); 

    //Input type number change valor rangos

    document.getElementById("txtAsisMes").addEventListener("change", function() {
        let v = parseInt(this.value);
        if (v < 1) this.value = 1;
        if (v > 12) this.value = 12;
    });

    document.getElementById("txtAsisAnu").addEventListener("change", function() {
        let v = parseInt(this.value);
        if (v < 1) this.value = 1;
        if (v > 3) this.value = 3;
    });


    //Agregar Productos

    $('#btnAgregar').click(function(){
        
        var _agregarPro = 'add';
        var _gerencial = 'NO';
        var _continuar = true;
        var _output;
        var _emprid = "<?php echo $xEmprid; ?>";
        var _paisid = "<?php echo $xPaisid; ?>";
        var _producto = $.trim($("#txtProducto").val());
        var _productoUpper = _producto.toUpperCase();
        var _descripcion = $.trim($("#txtDescripcion").val());
        var _costo = $.trim($("#txtCosto").val());
        var _cbogrupo = $('#cboGrupo').val();
        var _txtGrupo = $('#cboGrupo').find('option:selected').text();
        var _asistemes = $('#txtAsisMes').val();
        var _asistanu = $('#txtAsisAnu').val();

        
        if(_producto == ''){
            toastSweetAlert("top-end",3000,"warning","Ingrese Producto..!!");
            return false;
        }

        if(_costo == 0){
            toastSweetAlert("top-end",3000,"warning","Ingrese Costo..!!");
            return false;
        }

        if(_txtGrupo == ''){
            toastSweetAlert("top-end",3000,"warning","Seleccione Grupo..!!");
            return false;
        }

        if(_agregarPro == 'add'){
                
            var _parametros = {
                "xxEmprid" : _emprid,
                "xxProducto" : _producto
            }

            var xrespuesta = $.post("codephp/consultar_producto.php", _parametros);
                xrespuesta.done(function(response){

                if(response == 0){

                    $.each(_result,function(i,item){

                        if(item.arryproducto.toUpperCase() == _producto.toUpperCase()){
                            toastSweetAlert("top-end",3000,"warning","Producto ya Existe..!!");
                            _continuar = false;
                            return false;
                        }else{
                            _continuar = true;
                        }

                    });


                    if(_continuar){

                        var _checked = "checked='checked'";
                        _count = _count + 1;
                        _output = '<tr id="row_' + _count + '">';
                        _output += '<td style="display: none;">' + _count + ' <input type="hidden" name="hidden_orden[]" id="orden' + _count + '" value="' + _count + '" /></td>';
                        _output += '<td>' + _txtGrupo + ' <input type="hidden" name="hidden_grupo[]" id="txtGrupo' + _count + '" value="' + _txtGrupo + '" /></td>';
                        _output += '<td>' + _productoUpper + ' <input type="hidden" name="hidden_producto[]" id="txtProducto' + _count + '" value="' + _productoUpper + '" /></td>';
                        _output += '<td>' + _costo + ' <input type="hidden" name="hidden_costo[]" id="txtCosto' + _count + '" value="' + _costo + '" /></td>';
                        _output += '<td>';
                        _output += '<button id="btnDelete' + _count + '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1"  onclick="f_DelProducto('+"'";
                        _output +=  _producto + "'" + ',' + _count + ')"' + ' title="Eliminar Producto" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fa fa-trash"></i></button></td>';
                        _output += '</tr>';

                        $('#tblProducto').append(_output);

                        //console.log(_output);
        
                        var _objeto = {
                            arryproducto: _producto,
                            arrydescripcion: _descripcion,
                            arrycosto: _costo,
                            arrygrupid: _cbogrupo,
                            arrycober: _cobertura,
                            arrysist: _sistema,
                            arryasismes: _asistemes,
                            arryasisanu: _asistanu,
                            arrygerencial: _gerencial
                        }

                        _result.push(_objeto);

                        $("#txtProducto").val("");
                        $("#txtDescripcion").val("");
                        $("#txtCosto").val("0.00");
                        $("#cboGrupo").val(0).change();
                        $("#txtAsisMes").val("1");
                        $("#txtAsisAnu").val("1");
                        document.getElementById("chkCobertura").checked = false;
                        _cobertura = "NO";
                        $("#lblCobertura").text("Cobertura NO");
                        document.getElementById("chkSistema").checked = false;
                        _sistema = "NO";
                        $("#lblSistema").text("Sistema NO");    

                    }
                    
                }else{
                    toastSweetAlert("top-end",3000,"warning","Producto ya Existe..!!");
                    return false;
                }
            });
        }
    });

        
    // Guardar Cliente & Producto
    function f_Guardar(_idpais,_idempr,_iduser){

        var _cboProv = $('#cboProvincia').val();
        var _cboIdProv = $('#cboCiudad').val();
        var _cliente = $.trim($("#txtCliente").val());
        var _desc = $.trim($("#txtDesc").val());
        var _direc = $.trim($("#txtDireccion").val()); 
        var _url = $.trim($("#txtUrl").val()); 
        var _tel1 = $.trim($("#txtFono1").val()); 
        var _tel2 = $.trim($("#txtFono2").val()); 
        var _tel3 = $.trim($("#txtFono3").val()); 
        var _cel1 = $.trim($("#txtCelular1").val()); 
        var _cel2 = $.trim($("#txtCelular2").val()); 
        var _cel3 = $.trim($("#txtCelular3").val()); 
        var _email1 = $.trim($("#txtEmail1").val()); 
        var _email2 = $.trim($("#txtEmail2").val());
        

                //Imagen Cabecera

        var _imgfileCab = document.getElementById("imgfileCab").style.backgroundImage;
        var _urlimgCab = _imgfileCab.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
        var _posCab = _urlimgCab.trim().indexOf('.');
        var _extCab = _urlimgCab.trim().substr(_posCab, 5);

        if(_extCab.trim() != '.svg'){
            var _imgCab = document.getElementById("logoCab");
            var _fileCab = _imgCab.files[0];
            var _fullPathCab = document.getElementById('logoCab').value;
            _extCab = _fullPathCab.substring(_fullPathCab.length - 4);
            _extCab = _extCab.toLowerCase();

            if(_extCab.trim() != '.png' && _extCab.trim() != '.jpg' && _extCab.trim() != 'jpeg'){
                toastSweetAlert("top-end",3000,"error","Archivo no es Imagen..!!");
                return;
            }               
        }

            //Imagen Pie

        var _imgfilePie = document.getElementById("imgfilePie").style.backgroundImage;
        var _urlimgPie = _imgfilePie.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
        var _posPie = _urlimgPie.trim().indexOf('.');
        var _extPie = _urlimgPie.trim().substr(_posPie, 5);

        if(_extPie.trim() != '.svg'){
            var _imgPie = document.getElementById("logoPie");
            var _filePie = _imgPie.files[0];
            var _fullPathPie = document.getElementById('logoPie').value;
            _extPie = _fullPathPie.substring(_fullPathPie.length - 4);
            _extPie = _extPie.toLowerCase();

            if(_extPie.trim() != '.png' && _extPie.trim() != '.jpg' && _extPie.trim() != 'jpeg'){
                toastSweetAlert("top-end",3000,"error","Archivo no es Imagen..!!");
                return;
            }               
        }

        if(_cboProv == ''){
            toastSweetAlert("top-end",3000,"warning","Seleccione Provincia..!!");
            return false;
        }

        if(_cboIdProv == 0){
            toastSweetAlert("top-end",3000,"warning","Seleccione Ciudad..!!");
            return false;
        }

        if(_cliente == ''){
            toastSweetAlert("top-end",3000,"warning","Ingrese Nombre..!!");
            return false;
        }

        if(_count == 0){
            toastSweetAlert("top-end",3000,"warning","Ingrese Producto..!!");
            return false;
        }

        if(_url != ''){
            try{
                new URL(_url);
            }catch(err){
                toastSweetAlert("top-end",3000,"error","Direccion URL Incorrecta..!!");
                return false;
            }
        }
        
        if(_cel1 != '')
        {
            _valor = document.getElementById("txtCelular1").value;
            if( !(/^\d{10}$/.test(_valor)) ) {
                mensajesalertify("Celular 1 incorrecto..!" ,"W", "top-right", 3); 
                return;
            }
        }                     
        
        if(_cel2 != '')
        {
            _valor = document.getElementById("txtCelular2").value;
            if( !(/^\d{10}$/.test(_valor)) ) {
                mensajesalertify("Celular 2 incorrecto..!" ,"W", "top-right", 3); 
                return;
            }
        }
        
        if(_cel3 != '')
        {
            _valor = document.getElementById("txtCelular3").value;
            if( !(/^\d{10}$/.test(_valor)) ) {
                mensajesalertify("Celular 3 incorrecto..!" ,"W", "top-right", 3); 
                return;
            }
        }                    
                
        if(_email1 != ''){
            var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
            if (regex.test(_email1.trim())){
            }else{
                mensajesalertify("Email 1 Incorrecto..!!","W","top-right",3);
                return false;
            }  
        }

        if(_email2 != ''){
            var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
            if (regex.test(_email2.trim())){
            }else{
                mensajesalertify("Email 2 Incorrecto..!!","W","top-right",3);
                return false;
            }  
        }
        
        var _parametros = {            
            "xxPaisId" : _idpais,
            "xxEmprId" : _idempr,
            "xxCliente" : _cliente
        }

        var xrespuesta = $.post("codephp/consultar_cliente.php", _parametros);
            xrespuesta.done(function(response){

            if(response == 0){

                var form_data = new FormData();            
                form_data.append('xxPaisid', _idpais);
                form_data.append('xxEmprid', _idempr);
                form_data.append('xxUsuaid', _iduser);
                form_data.append('xxProv', _cboIdProv);
                form_data.append('xxCliente', _cliente);
                form_data.append('xxDescrip', _desc);
                form_data.append('xxDirec', _direc);
                form_data.append('xxUrl', _url);
                form_data.append('xxTel1', _tel1);
                form_data.append('xxTel2', _tel2);
                form_data.append('xxTel3', _tel3);
                form_data.append('xxCel1', _cel1);
                form_data.append('xxCel2', _cel2);
                form_data.append('xxCel3', _cel3);
                form_data.append('xxEmail1', _email1);
                form_data.append('xxEmail2', _email2);
                form_data.append('xxFileCab', _fileCab);
                form_data.append('xxFilePie', _filePie);

                $.ajax({
                    url: "codephp/grabar_clienteprod.php",
                    type: "post",                
                    data: form_data,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function(dataid){

                        if(dataid != 0){
                            var xrespuesta = $.post("codephp/grabar_productoclie.php", { xxPaisid: _idpais, xxEmprid: _idempr,xxUsuaid: _iduser,xxClieid: dataid, xxResult: _result });
                                xrespuesta.done(function(response){
                                        
                                if(response.trim() == 'OK'){

                                    $.redirect('?page=admin_clienteproducto&menuid=<?php echo $menuid; ?>', {'mensaje': 'Grabado con Éxito..!'}); //POR METODO POST
                        
                                }

                            });

                        }
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });            
            }else{
                mensajesalertify("Cliente ya Existe..!!","W","top-right",3);
                return false;
            }
        });
    }

    //Eliminar Producto en linea

    function f_DelProducto(_prod,_id){
        $('#row_' + _id + '').remove();
        _count--;
        
         $.each(_result,function(i,item){

            if(item.arryproducto == _prod)
            {
                _result.splice(i, 1);
                return false;
            }else{
                continuar = true;
            }
        });
    }

    //Desplazar-modal

    $("#modal_new_grupo").draggable({
        handle: ".modal-header"
    }); 
    
    //Agregar Grupos

    function f_GuardarGrupo(_paisid,_emprid,_usuaid){

        var _nombreGrupo = $.trim($("#txtGrupo").val());
        var _descGrupo = $.trim($("#txtDescGrupo").val());
        var _numagenda = $("#txtnumagenda").val();
        var _numcancela = $("#txtnumcancelado").val();
        var _numatendido = $("#txtnumatendido").val();
        var _numausente = $("#txtnumausente").val();        

        if(_nombreGrupo == ''){
            mensajesalertify("Ingrese Grupo..!!","W","top-right",3);
            return false;
        }

        var _parametros = {
            "xxPaisId" : _paisid,
            "xxEmprId" : _emprid,
            "xxUsuaId" : _usuaid,
            "xxGrupo" : _nombreGrupo,
            "xxDesc" : _descGrupo,
            "xxNumagenda" : _numagenda,
            "xxNumcancela" : _numcancela,
            "xxNumatendido" : _numatendido,
            "xxNumausente" : _numausente            
        }

        var xrespuesta = $.post("codephp/consultar_grupo.php", _parametros);
            xrespuesta.done(function(response){
            if(response.trim() == 'OK'){

                var xrespuesta = $.post("codephp/grabar_grupo.php", _parametros);
                    xrespuesta.done(function(response){
                    if(response.trim() != 'ERR'){

                        mensajesalertify('Nuevo Grupo Agregado', 'S', 'top-center', 3); 
                        
                        $("#txtGrupo").val("");
                        $("#txtDescGrupo").val("");
                        $("#cboGrupo").empty();
                        $("#cboGrupo").html(response);     
                        $("#modal_new_grupo").modal("hide");                    
                    }
                });

            }else  if(response.trim() == 'EXISTE'){
                mensajesalertify('Grupo ya Existe', 'W', 'top-right', 3);

                $("#txtGrupo").val("");
                $("#txtDescGrupo").val("");
                
            }
        });
    }

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }      
    
</script>
