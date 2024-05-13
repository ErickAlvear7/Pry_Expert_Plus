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
                    <button type="button" id="btnNuevaEspe" class="btn btn-light-primary btn-sm mb-10"><i class="fa fa-plus-circle" aria-hidden="true"></i>                                                          
                        Nueva Especialidad
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
                                                <!-- <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Enter the contact's phone number (optional)."></i> -->
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
                                                    <option value="<?php echo $datos['Codigo'] ?>"><?php echo mb_strtoupper($datos['Descripcion']) ?></option>
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
                                        <div class="d-flex align-items-center collapsible rotate" data-bs-toggle="collapse" href="#kt_customer_view_payment_method_1" role="button" aria-expanded="false" aria-controls="kt_customer_view_payment_method_1">
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
                                                        <textarea class="form-control mb-2 text-uppercase" name="txtDireccion" id="txtDireccion" maxlength="250" onkeydown="return (event.keyCode!=13);"></textarea>
                                                    </div>
                                                </div>
                                                <div class="row mb-8">
                                                    <div class="col-xl-2">
                                                        <div class="fs-6 fw-bold mt-2 mb-3">URL:</div>
                                                    </div>
                                                    <div class="col-xl-10 fv-row">
                                                        <input type="text" class="form-control mb-2 text-lowercase" name="txtUrl" id="txtUrl" maxlength="150" placeHolder="https://wwww.dominio.com" />
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
                                                <input type="text" class="form-control mb-2 w-150px" name="txtFono1" id="txtFono1" maxlength="9" placeholder="022222222" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="" />
                                            </div>
                                            <div class="col">
                                                <div class="fs-6 fw-bold mt-2 mb-3">Telefono 2:</div>
                                                <input type="text" class="form-control mb-2 w-150px" name="txtFono2" id="txtFono2" maxlength="9" placeholder="" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="" />
                                            </div> 
                                            <div class="col">
                                                <div class="fs-6 fw-bold mt-2 mb-3">Telefono 3:</div>
                                                <input type="text" class="form-control mb-2 w-150px" name="txtFono3" id="txtFono2" maxlength="9" placeholder="" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="" />
                                            </div>                                                        
                                        </div>
                                        <div class="row row-cols-1 row-cols-sm-3 rol-cols-md-3 row-cols-lg-3">
                                            <div class="col">
                                                <div class="fs-6 fw-bold mt-2 mb-3">Celular 1:</div>
                                                <input type="text" class="form-control mb-2 w-150px" name="txtCelular1" id="txtCelular1" maxlength="10" placeholder="0987654321" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="" />
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
                                                <input type="email" name="txtEmail1" id="txtEmail1" maxlength="150" placeholder="micorre@dominio.com" class="form-control mb-2 text-lowercase" value="" />
                                            </div>
                                            <label class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input" name="chkEnviar1" id="chkEnviar1" type="checkbox" />
                                                <span id="spanEnv1" class="form-check-label fw-bold text-muted" for="chkEnviar1">No Enviar</span>
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
                </div>
                <div class="tab-pane fade" id="kt_ecommerce_add_product_advanced" role="tab-panel">
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <div class="form-group mt-2 mb-n2">
                            <button type="button" data-repeater-create="" class="btn btn-light-primary btn-sm mb-2" id="btnAddEspe"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                                Agregar Especialidad
                            </button>
                        </div>
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="fw-bolder">Especialidades Asignadas</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="d-flex flex-column gap-10">
                                    <table id="tblEspecialidad" class="table align-middle table-row-dashed table-hover fs-6 gy-5" style="width: 100%;">
                                        <thead>
                                            <tr class="text-start text-gray-800 fw-bolder fs-7 text-uppercase gs-0">
                                                <th style="display: none;">Id</th>
                                                <th class="min-w-125px">Especialidad</th>
                                                <th class="min-w-125px">Pvp</th>
                                                <th class="min-w-125px">Costo</th>
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
                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="button" id="btnSave" class="btn btn-sm btn-primary"><i class="las la-save"></i>Grabar</button>
            </div>
        </div>
    </form>
</div>
<!--Modal Nueva Especialidad -->
<div class="modal fade" id="modal-new-especialidad" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-800px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Nueva Especialidad</h2>
                <i class="fa fa-window-close fa-2x" aria-hidden="true" data-bs-dismiss="modal"></i>
            </div>
            <div class="modal-body py-lg-10 px-lg-10">
                <div class="card card-flush py-2">
                    <div class="card-body pt-0" id="kt_modal_new_card_form">
                        <div class="row mb-2">
                            <div class="col-md-12">
                                 <label class="required form-label">Nombres</label>
                                 <input type="text" class="form-control mb-2" maxlength="250" placeholder="Nombre Especialidad" name="txtEspecialidad" id="txtEspecialidad" />
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="required form-label">Tipo Especialidad</label>
                                <select name="cboTipoEspe" id="cboTipoEspe" aria-label="Seleccione Tipo" data-control="select2" data-placeholder="Seleccione Tipo" data-dropdown-parent="#kt_modal_new_card_form" class="form-select mb-2">
                                    <option</option>
                                    <?php 
                                    $xSQL = "SELECT pde.pade_valorV AS Codigo,pde.pade_nombre AS Descripcion FROM `expert_parametro_detalle` pde,`expert_parametro_cabecera` pca WHERE pca.pais_id=$xPaisid ";
                                    $xSQL .= "AND pca.paca_nombre='Tipo Especialidad' AND pca.paca_id=pde.paca_id AND pca.paca_estado='A' AND pade_estado='A' ";
                                    $all_datos =  mysqli_query($con, $xSQL);
                                    foreach ($all_datos as $datos){ ?>
                                        <option value="<?php echo $datos['Codigo'] ?>"><?php echo mb_strtoupper($datos['Descripcion']) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="required form-label">Precio (PVP)</label>
                                <input type="number" name="txtPvpNew" id="txtPvpNew" class="form-control mb-2" placeholder="Precio al Publico (0.00)" min="0" maxlength = "6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="0.00" step="0.01" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Descripcion</label>
                                <textarea class="form-control mb-2 text-uppercase" name="txtDescripcion" id="txtDescripcion" maxlength="150" onkeydown="return (event.keyCode!=13);"></textarea>
                            </div>
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
<div class="modal fade" id="modal-new-tipoprestador" tabindex="-1" aria-hidden="true">
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
</div>  
<!--Modal Agregar Especialidad -->
<div class="modal fade" id="agregar_especialidad" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-800px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Datos Especialidad</h2>
                <i class="fa fa-window-close fa-2x" aria-hidden="true" data-bs-dismiss="modal"></i>
            </div>
            <div class="modal-body py-lg-10 px-lg-10">
                <div class="card card-flush py-2">
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
                        <div class="mb-2 fv-row">
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

        $("#btnNuevoTipo").click(function(){
            
            $("#modal-new-tipoprestador").find("input,textarea").val("");
            $("#modal-new-tipoprestador").modal("show");
            $('#modal-new-tipoprestador').modal('handleUpdate');
        });                 

        $('#btnSaveNew').click(function(e){

            var _cbotipoespe = $('#cboTipoEspe').val();
            var _especialidad = $.trim($("#txtEspecialidad").val());
            var _descripcion = $.trim($("#txtDescripcion").val());
            var _pvpnew = $("#txtPvpNew").val();

            if(_cbotipoespe == '0'){
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
                xxPaisId: _paisid,
                xxEmprId: _emprid,
                xxUsuaId: _usuaid,
                xxEspecialidad: _especialidad,
                xxDescripcion: _descripcion,
                xxTipoEspe: _cbotipoespe,
                xxPrecio: _pvpnew
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
                xxPaisId: _paisid,
                xxEmprId: _emprid,
                xxUsuaId: _usuaid,
                xxTipoPrestador: _tipoprestador,
                xxValor: _valorv
            }

            var xrespuesta = $.post("codephp/grabar_tipoprestador.php", _parametros);
            xrespuesta.done(function(response){
                if(response.trim() == 'EXISTE'){
                    toastSweetAlert("top-end",3000,"warning","Tipo Prestador/Valor ya Existe..!!");
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
        
        //Modal Especialidad
        $("#btnAddEspe").click(function(){
            
            $("#agregar_especialidad").find("input,textarea").val("");
            $("#agregar_especialidad").modal("show");
            $('#agregar_especialidad').modal('handleUpdate');
            $("#cboEspecialidad").val(0).change();    
        }); 

        //Agregar Especialidad
        $('#btnAgregar').click(function(e){

            var _cboespe = $('#cboEspecialidad').val();
            var _especialidad = $("#cboEspecialidad option:selected").text();
            var _pvp = $.trim($("#txtPvp").val());
            var _costo = $.trim($("#txtCosto").val());

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

            $.each(_result,function(i,item){
                if(item.arryid.toUpperCase() == _cboespe.toUpperCase())
                {                  
                    toastSweetAlert("top-end",3000,"warning","Especialidad ya Existe..!!");   
                    $("#cboEspecialidad").val(0).change();
                    $("#txtPvp").val('0.00');
                    $("#txtCosto").val('0.00');                            
                    _continuar = false;
                    return false;
                }
            });

            if(_continuar){
                
                //_count = _count + 1;
                _output = '<tr id="row_' + _cboespe + '">';
                _output += '<td style="display: none;">' + _cboespe + '</td>';                
                _output += '<td>' + _especialidad + '</td>';
                _output += '<td>' + _pvp + '</td>';
                _output += '<td>' + _costo + '</td>';
                _output += '<td><div class=""><div class="btn-group">';
                _output += '<button type="button" name="btnDelete" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1 btnDelete" id="' + _cboespe + '"><i class="fa fa-trash"></i></button></div></div></td>';
                _output += '</tr>';

                $('#tblEspecialidad').append(_output);

                _objeto = {
                    arryid: _cboespe,
                    arryespeci: _especialidad,
                    arrypvp: _pvp,
                    arrycosto: _costo
                }

                _result.push(_objeto);
                $("#cboEspecialidad").val(0).change();
                $("#txtPvp").val('0.00');
                $("#txtCosto").val('0.00');
                $("#agregar_especialidad").modal("hide");

            }
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
           
           
           if(_celular1 != '')
           {
               _valor = document.getElementById("txtCelular1").value;
               if( !(/^\d{10}$/.test(_valor)) ) {
                   toastSweetAlert("top-end",3000,"error","Celular 1 incorrecto..!!");
                   return;
               }
           }                     
           
           if(_celular2 != '')
           {
               _valor = document.getElementById("txtCelular2").value;
               if( !(/^\d{10}$/.test(_valor)) ) {
                   toastSweetAlert("top-end",3000,"error","Celular 2 incorrecto..!!"); 
                   return;
               }
           }
           
           if(_celular3 != '')
           {
               _valor = document.getElementById("txtCelular3").value;
               if( !(/^\d{10}$/.test(_valor)) ) {
                   toastSweetAlert("top-end",3000,"error","Celular 3 Incorrecto..!!");
                   return;
               }
           }                    
           
           if(_email1 != ''){
               var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
           
               if (regex.test($('#txtEmail1').val().trim())) {
               }else{
                   toastSweetAlert("top-end",3000,"error","Email 1 Incorrecto..!!");
                   return;
               }
           }

           if(_email2 != ''){
               var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
           
               if (regex.test($('#txtEmail2').val().trim())) {
               }else{
                   toastSweetAlert("top-end",3000,"error","Email 2 Incorrecto..!!");
                   return;
               }
           }

           if(_result.length == 0){
                toastSweetAlert("top-end",3000,"warning","Agregue una Especialidad..!!");
                return;
           }
           
            var _imgfile = document.getElementById("imgfile").style.backgroundImage;
            var _urlimg = _imgfile.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
            var _pos = _urlimg.trim().indexOf('.');
            var _ext = _urlimg.trim().substr(_pos, 5);

            if(_ext.trim() != '.svg'){
                var _imagen = document.getElementById("imglogo");
                var _file = _imagen.files[0];
                var _fullPath = document.getElementById('imglogo').value;
                _ext = _fullPath.substring(_fullPath.length - 4);
                _ext = _ext.toLowerCase();   

                if(_ext.trim() != '.png' && _ext.trim() != '.jpg' && _ext.trim() != 'jpeg'){
                    toastSweetAlert("top-end",3000,"error","Archivo no es una Imagen..!!");
                    return;
                }
            }
                                
            form_data = new FormData();
            form_data.append('xxPaisid', _paisid);
            form_data.append('xxEmprid', _emprid);
            form_data.append('xxProvid', _ciudid);
            form_data.append('xxPrestador', _prestador);
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
                        dataType: "json",
                        success: function(dataid){   
                            if(dataid != 0){

                                var xrespuesta = $.post("codephp/grabar_prestaespeci.php", { xxPaisid: _paisid, xxEmprid: _emprid, xxUsuaid: _usuaid, xxPresid: dataid, xxResult: _result });
                                xrespuesta.done(function(xrespose){

                                    if(xrespose.trim() == 'OK'){
                                        _detalle = 'Nuevo Prestador Agregado Correctamente';
                                        _respuesta = 'OK'; 
                                    }else{
                                        _detalle = 'Error creacion de especialidades';
                                        _respuesta = 'ERR';                                
                                    }

                                    /**PARA CREAR REGISTRO DE LOGS */
                                    _parametros = {
                                        "xxPaisid" : _paisid,
                                        "xxEmprid" : _emprid,
                                        "xxUsuaid" : _usuaid,
                                        "xxDetalle" : _detalle,
                                    }					
        
                                    $.post("codephp/new_log.php", _parametros, function(response){
                                    });                                              
                                });    
                              
                            }else{
                                _detalle = 'Error creacion nuevo prestador';
                                _respuesta = 'ERR';                                
                            }

                            if(_respuesta == 'OK'){
                                $.redirect('?page=prestador_admin&menuid=<?php echo $menuid; ?>', {'mensaje': 'Grabado con Éxito..!'}); //POR METODO POST
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
