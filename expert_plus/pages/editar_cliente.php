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
    $mensaje = (isset($_POST['mensaje'])) ? $_POST['mensaje'] : '';
    $clieid = $_POST['idclie'];
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

    $xSQL = "SELECT * FROM `expert_cliente` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND clie_id=$clieid ";
    $all_cliente = mysqli_query($con, $xSQL);

    foreach ($all_cliente as $clie){

        $xProvid = $clie['prov_id'];
        $xCliente = $clie['clie_nombre'];
        $xDesc = $clie['clie_descripcion'];
        $xDirec = $clie['clie_direccion'];
        $xUrl = $clie['clie_url'];
        $xTel1 = $clie['clie_tel1'];
        $xTel2 = $clie['clie_tel2'];
        $xTel3 = $clie['clie_tel3'];
        $xCel1 = $clie['clie_cel1'];
        $xCel2 = $clie['clie_cel2'];
        $xCel3 = $clie['clie_cel3'];
        $xEmail1 = $clie['clie_email1'];
        $xEmail2 = $clie['clie_email2'];
        $xImgc = $clie['clie_imgcab'];
        $xImgp = $clie['clie_imgpie'];

    }

    $xSQL = "SELECT DISTINCT provincia AS Descripcion FROM `provincia_ciudad` ";
	$xSQL .= "WHERE pais_id=$xPaisid AND estado='A' ORDER BY provincia ";
    $all_provincia = mysqli_query($con, $xSQL);

    $xSQL = "SELECT * FROM `provincia_ciudad` WHERE pais_id=$xPaisid AND prov_id=$xProvid ";
    $cbo_provincia = mysqli_query($con, $xSQL);    
    foreach ($cbo_provincia as $prov){
        $xCboProv = $prov['provincia'];
    }

    $xSQL = "SELECT * FROM `provincia_ciudad` WHERE pais_id=$xPaisid AND provincia='$xCboProv' ";
    $cbo_ciudad = mysqli_query($con, $xSQL);  

    $xSQL = "SELECT grup_id AS Codigo,grup_nombre AS NombreGrupo FROM `expert_grupos` WHERE pais_id=$xPaisid AND empr_id=$xEmprid ";
	$all_grupos =  mysqli_query($con, $xSQL);


    $xSQL = "SELECT pro.prod_id AS Idprod, pro.prod_nombre AS Producto, pro.prod_descripcion AS Descrip, pro.prod_costo AS Costo, ";
    $xSQL .="pro.prod_asistmes AS AsisMes, pro.prod_asistanu AS AsisAnu, pro.prod_cobertura AS Cobertura, pro.prod_sistema AS Sistema, ";
    $xSQL .="pro.prod_gerencial AS Gerencial,CASE pro.prod_estado WHEN 'A' THEN 'ACTIVO' ELSE 'INACTIVO' END AS Estado, gru.grup_id AS Idgrup,gru.grup_nombre AS Grupo FROM `expert_productos` pro INNER JOIN ";
    $xSQL .="`expert_grupos` gru ON pro.grup_id = gru.grup_id WHERE pro.clie_id =$clieid AND pro.pais_id =$xPaisid AND pro.empr_id =$xEmprid ORDER BY gru.grup_nombre ";
    $all_prod = mysqli_query($con, $xSQL);

?>

<div id="kt_content_container" class="container-xxl">
    <input type="hidden" id="mensaje" value="<?php echo $mensaje ?>">
    <form id="kt_ecommerce_add_product_form" class="form d-flex flex-column flex-lg-row">
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="d-flex align-items-center collapsible py-3 toggle mb-0" data-bs-toggle="collapse" data-bs-target="#view_logo_cabecera">														<!--begin::Icon-->
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
                <div id="view_logo_cabecera" class="collapse show fs-6 ms-1">
                    <div class="card-body text-center pt-0">
                        <div class="image-input image-input-empty image-input-outline mb-3" data-kt-image-input="true">
                            <div class="image-input-wrapper w-150px h-150px" id="imgfileCab"></div>
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Cargar Logo">
                                <i class="bi bi-pencil-fill fs-7"></i>
                                <input type="file" name="avatar" id="logoCab" accept=".png, .jpg, .jpeg" />
                                <input type="hidden" name="avatar_remove" />
                            </label>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancelar Logo">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remover Logo">
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
                        <div class="image-input image-input-empty image-input-outline mb-3" data-kt-image-input="true">
                            <div class="image-input-wrapper w-150px h-150px" id="imgfilePie"></div>
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Cargar Logo">
                                <i class="bi bi-pencil-fill fs-7"></i>
                                <input type="file" name="avatar" id="logoPie" accept=".png, .jpg, .jpeg" />
                                <input type="hidden" name="avatar_remove" />
                            </label>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancelar Logo">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remover Logo">
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
                            <button type="button" id="btnNewGrupo" class="btn btn-light-primary btn-sm mb-5">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                Nuevo Grupo
                            </button>
                            <button type="button" id="btnEditGrupo" class="btn btn-light-primary btn-sm mb-5">
                                <i class="las la-pencil-alt" aria-hidden="true"></i>                                                               
                                Editar Grupo
                            </button>                                  
                        </div>
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
                                    <h2 class="fw-normal">Datos Cliente</h2>
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
                                    <label class="required form-label">Cliente</label>
                                    <input type="text" name="txtCliente" id="txtCliente" class="form-control mb-2" minlength="5" maxlength="150" placeholder="Ingrese Nombre" value="<?php echo $xCliente; ?>" />
                                    <input type="hidden" name="txtClieant" id="txtClieant" class="form-control mb-2" value="<?php echo $xCliente; ?>" />
                                </div>
                                <div class="mb-1 fv-row">
                                    <label class="form-label">Descripcion</label>
                                    <textarea class="form-control mb-2" name="txtDesc" id="txtDesc" maxlength="200" onkeydown="return (event.keyCode!=13);"><?php echo $xDesc; ?></textarea>
                                </div>                                 
                            </div>
                            <div class="card-header border-0">
                                <div class="card-title">
                                    <h2 class="fw-normal">Direccion - Telefonos - Mails</h2>
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
                                    					<textarea class="form-control mb-2" name="txtDireccion" id="txtDireccion" maxlength="250" onkeydown="return (event.keyCode!=13);"> <?php echo $xDirec; ?> </textarea>
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
                                                <input type="text" class="form-control mb-2 w-150px" name="txtFono1" id="txtFono1" maxlength="9" placeholder="022222222" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xTel1; ?>" />
                                            </div>
                                            <div class="col">
                                                <div class="fs-6 fw-bold mt-2 mb-3">Telefono 2:</div>
                                                <input type="text" class="form-control mb-2 w-150px" name="txtFono2" id="txtFono2" maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xTel2; ?>" />
                                            </div> 
                                            <div class="col">
                                                <div class="fs-6 fw-bold mt-2 mb-3">Telefono 3:</div>
                                                <input type="text" class="form-control mb-2 w-150px" name="txtFono3" id="txtFono3" maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xTel3; ?>" />
                                            </div>                                                        
                                        </div>
                                        <div class="row row-cols-1 row-cols-sm-3 rol-cols-md-3 row-cols-lg-3">
                                            <div class="col">
                                                <div class="fs-6 fw-bold mt-2 mb-3">Celular 1:</div>
                                                <input type="text" class="form-control mb-2 w-150px" name="txtCelular1" id="txtCelular1" maxlength="10" placeholder="0999999999" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xCel1; ?>" />
                                            </div>
                                            <div class="col">
                                                <div class="fs-6 fw-bold mt-2 mb-3">Celular 2:</div>
                                                <input type="text" class="form-control mb-2 w-150px" name="txtCelular2" id="txtCelular2" maxlength="10"  onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xCel2; ?>" />
                                            </div> 
                                            <div class="col">
                                                <div class="fs-6 fw-bold mt-2 mb-3">Celular 3:</div>
                                                <input type="text" class="form-control mb-2 w-150px" name="txtCelular3" id="txtCelular3" maxlength="10"  onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xCel3; ?>" />
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
                                            <i class="fa fa-envelope fa-1x me-2" style="color:#5AD1F1;" aria-hidden="true"></i>
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
                                        </div>
                                        <div class="d-flex flex-wrap gap-5">
                                            <div class="fv-row w-100 flex-md-root">
                                                <label class="form-label">Email 2</label>
                                                <input type="email" name="txtEmail2" id="txtEmail2" maxlength="150" class="form-control mb-2 text-lowercase" value="<?php echo $xEmail2; ?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>    
                    </div>
                    <div class="d-flex justify-content-end mt-5">
                		<button type="button" id="btnGrabar" class="btn btn-primary btn-sm"><i class="fa fa-hdd me-1"></i>
                			<span class="indicator-label">Grabar</span>
                		</button>
            	   </div>
                </div>
                <div class="tab-pane fade" id="kt_ecommerce_add_product_advanced" role="tab-panel">
                    <div class="d-flex flex-stack fs-4 py-3 mb-2">
                        <div class="d-flex justify-content-start">
                            <button type="button" id="btnAgregarprod" class="btn btn-light-primary btn-sm mb-2"><i class="fa fa-plus-circle"></i>Agregar Producto</button>
                        </div>
                    </div>
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                        <h2>Productos Asignados</h2> 
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="d-flex flex-column gap-10">
                                    <div class="scroll-y me-n7 pe-7" id="parametro_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#parametro_header" data-kt-scroll-wrappers="#parametro_scroll" data-kt-scroll-offset="300px">
                                        <table class="table align-middle table-row-dashed table-hover fs-6 gy-5" id="tblProducto">
                                            <thead>
                                                <tr class="text-start text-gray-800 fw-bolder fs-7 text-uppercase gs-0">
                                                    <th style="display:none;">Id</th>
                                                    <th class="min-w-125px">Grupo</th>
                                                    <th class="min-w-125px">Producto</th>
                                                    <th class="min-w-125px">Costo</th>
                                                    <th class="min-w-125px">Estado</th>
                                                    <th>Status</th>
                                                    <th style="text-align: center;">Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody class="fw-bold text-gray-600">
                                                <?php 
                                                    foreach($all_prod as $prod){
                                                    $xProdid = $prod['Idprod'];
                                                    $xProducto = $prod['Producto'];
                                                    $xDesc = $prod['Descrip'];
                                                    $xCosto = $prod['Costo'];
                                                    $xAsisMes = $prod['AsisMes'];
                                                    $xAsisAnu = $prod['AsisAnu'];
                                                    $xCober = $prod['Cobertura'];
                                                    $xSist = $prod['Sistema'];
                                                    $xGeren = $prod['Gerencial'];
                                                    $xEstado = $prod['Estado'];
                                                    $xGrupId = $prod['Idgrup'];
                                                    $xGrupo = $prod['Grupo'];

                                                    ?>  
                                                    <?php 
                                                        $xCheking = '';
                                                        $xDisabledEdit = '';

                                                        if($xEstado == 'ACTIVO'){
                                                            $xCheking = 'checked="checked"';
                                                            $xTextColor = "badge badge-light-primary";
                                                        }else{
                                                            $xTextColor = "badge badge-light-danger";
                                                            $xDisabledEdit = 'disabled';
                                                        }

                                                    
                                                        $xSQL = "SELECT COUNT(*) AS Titu FROM `expert_titular` WHERE prod_id = $xProdid ";
                                                        $cont_titu = mysqli_query($con, $xSQL);
                                                        foreach ($cont_titu as $titu){
                                                            $xTitu = $titu['Titu'];
                                                        }
                                                    
                                                    ?>

                                                <tr id="row_<?php echo $xProdid; ?>">
                                                    <td style="display: none;"><?php echo $xProdid; ?></td>
                                                    <td class="text-uppercase"><?php echo $xGrupo; ?></td>
                                                    <td class="text-uppercase"><?php echo $xProducto; ?></td>
                                                    <td><?php echo $xCosto; ?></td>
                                                    <td id="td_<?php echo $xProdid; ?>">   
                                                        <div class="<?php echo $xTextColor; ?>">
                                                            <?php echo $xEstado; ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                            <input <?php echo $xCheking; ?> class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk<?php echo $xProdid; ?>" 
                                                            onchange="f_UpdateEstado(<?php echo $xProdid;?>,<?php echo $xEmprid; ?>,<?php echo $xPaisid; ?>,<?php echo $xUsuaid; ?>)" value=""/>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-center">
                                                            <div class="btn-group">	
                                                                <button type="button" id="btnEditar_<?php echo $xProdid; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" <?php echo $xDisabledEdit; ?> title="Editar Producto" data-bs-toggle="tooltip" data-bs-placement="left">
                                                                    <i class="fa fa-edit"></i>
                                                                </button> 
                                                                <button type="button" id="btnTitular_<?php echo $xProdid; ?>" onclick="f_Titular(<?php echo $xGrupId;?>,<?php echo $xProdid;?>,<?php echo $clieid;?>)" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" <?php echo $xDisabledEdit; ?> title="Agregar Titular (+<?php echo $xTitu; ?> )" data-bs-toggle="tooltip" data-bs-placement="left">
                                                                    <i class="fa fa-user"></i>
                                                                </button> 
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php }?> 
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
    </form>
</div>

<!--MODAL NUEVO GRUPO-->
<div class="modal fade" id="modal_new_grupo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-800px">
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
            <div class="modal-body py-lg-5 px-lg-10">
                <div class="card card-flush py-2">
                    <div class="card-body pt-0">
                        <div class="d-flex flex-column mb-7 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                <span class="required">Grupo</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Especifique el nombre del grupo"></i>
                            </label>
                            <input type="text" class="form-control" maxlength="80" placeholder="Nombre Grupo" name="txtGrupo" id="txtGrupo" />
                        </div>
                        <div class="fv-row mb-15">
                            <label class="fs-6 fw-bold form-label mb-2">
                                <span>Descripcion</span>
                            </label>
                            <textarea class="form-control" name="txtDescGrupo" id="txtDescGrupo" rows="1" maxlength="150" onkeydown="return(event.keyCode!=13);"></textarea>
                        </div>
                        <div class="row mb-10">
                            <div class="col-md-4">
                                <label class="required form-label">Secuencial Agenda</label>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="txtnumagenda" id="txtnumagenda" class="form-control form-control-solid" value="1" onkeypress="return isNumberKey(event)" />   
                            </div>
                            <div class="col-md-4">
                                <label class="required form-label">Secuencial Cancelado</label>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="txtnumcancelado" id="txtnumcancelado" class="form-control form-control-solid" placeholder="1" value="1" onkeypress="return isNumberKey(event)" />  
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="required form-label">Secuencial Atendido</label>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="txtnumatendido" id="txtnumatendido" class="form-control form-control-solid" value="1" onkeypress="return isNumberKey(event)" />   
                            </div>
                            <div class="col-md-4">
                                <label class="required form-label">Secuencial Ausente</label>
                            </div>
                            <div class="col-md-2">
                                <input type="number" name="txtnumausente" id="txtnumausente" class="form-control form-control-solid" placeholder="1" value="1" onkeypress="return isNumberKey(event)" />  
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-sm btn-light-danger border border-danger" data-bs-dismiss="modal"><i class="fa fa-times me-1" aria-hidden="true"></i>Cerrar</button>
                <button type="button" class="btn btn-sm btn-light-primary border border-primary" id="btnGuardar" onclick="f_GuardarGrupo(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xUsuaid; ?>)" ><i class="fa fa-hdd me-1"></i>Grabar</button>
            </div>
        </div>
    </div>
</div>  

<!--MODAL EDITAR GRUPOS-->
<div class="modal fade" id="modal_edit_grupo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Lista de Grupos - Editar Datos</h2>
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
                        <div id="divcampos" style="display: none;" >
                            <input type="hidden" class="form-control mb-2 " maxlength="80" placeholder="ID" name="txteditargrupoid" id="txteditargrupoid" />
                            <div class="d-flex flex-column mb-7 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                    <span class="required">Grupo</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Especifique el nombre del grupo"></i>
                                </label>
                                <input type="text" class="form-control mb-2 text-uppercase" maxlength="80" placeholder="Nombre Grupo" name="txteditarGrupo" id="txteditarGrupo" />
                            </div>
                            <div class="row mb-7">
                                <div class="col-md-6">
                                    <label class="form-label">Secuencial Agenda</label>
                                    <input type="number" name="txteditarnumagenda" id="txteditarnumagenda" class="form-control mb-2" value="0" onkeypress="return isNumberKey(event)" />   
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Secuencial Cancelado</label>
                                    <input type="number" name="txteditarnumcancelado" id="txteditarnumcancelado" class="form-control mb-2"  value="0" onkeypress="return isNumberKey(event)" />  
                                </div>
                            </div>

                            <div class="form-group my-5">
                                <button type="button" id="btneditargrupo" onclick="f_ModificarGrupo(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>)" class="btn btn-sm btn-light-primary border border-primary"><i class="las la-pencil-alt"></i>Modificar</button>
                            </div>
                        </div>
                        <div class="mh-300px scroll-y me-n7 pe-7">
                            <table id="tblGrupo" class="table align-middle table-row-dashed table-hover fs-6 gy-5" style="width: 100%;">
                                <thead>
                                    <tr class="text-start text-gray-800 fw-bolder fs-7 text-uppercase gs-0">
                                        <th class="min-w-125px">Grupo</th>
                                        <th>Secuencial Agenda</th>
                                        <th>Secuencial Cancela</th>
                                        <th class="min-w-125px">Estado</th>
                                        <th>Status</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-bold text-gray-600">
                                    <?php 
                                        $xSQL = "SELECT * FROM `expert_grupos` WHERE pais_id=$xPaisid AND empr_id=$xEmprid  ";
                                        $all_grupos = mysqli_query($con, $xSQL);
                                        foreach($all_grupos as $grupo){
                                            $xId = $grupo['grup_id'];
                                            $xGrupo = $grupo['grup_nombre'];
                                            $xEstado = trim($grupo['grup_estado']);
                                            $xSecAgenda = $grupo['secuencial_agendado'];
                                            $xSecCancela = $grupo['secuencial_cancelado'];

                                            $xChkEstado = '';
                                            $xDisabledEdit = '';
            
                                            if($xEstado == 'A'){
                                                    $xEstado = 'ACTIVO';
                                                $xChkEstado = 'checked="checked"';
                                                $xTextColor = "badge badge-light-primary";
                                            }else{
                                                $xEstado = 'INACTIVO';
                                                $xTextColor = "badge badge-light-danger";
                                                $xDisabledEdit = 'disabled';
                                            }  
                                            ?>
                                            <tr id="trgru_<?php echo $xId; ?>">
                                                <td class="text-uppercase">
                                                    <?php echo $xGrupo; ?>
                                                    <input type="hidden" id="txtgrupoid<?php echo $xId; ?>" value="<?php echo $xId; ?>" />
                                                    <input type="hidden" id="txtgrupo<?php echo $xId; ?>" value="<?php echo $xGrupo; ?>" />
                                                </td>
                                                
                                                <td>
                                                    <?php echo $xSecAgenda; ?>
                                                    <input type="hidden" id="txtsecagenda<?php echo $xId; ?>" value="<?php echo $xSecAgenda; ?>" />
                                                </td>

                                                <td>
                                                    <?php echo $xSecCancela; ?>
                                                    <input type="hidden" id="txtseccancela<?php echo $xId; ?>" value="<?php echo $xSecCancela; ?>" />
                                                </td>

                                                <td id="tdgru_<?php echo $xId; ?>">
                                                    <div class="<?php echo $xTextColor; ?>">
                                                        <?php echo $xEstado; ?>
                                                    </div>
                                                </td>
                                                
                                                <td class="text-end">
                                                    <div class="text-center">
                                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                            <input class="form-check-input h-20px w-20px border-primary" <?php echo $xChkEstado; ?> type="checkbox" id="chkgru<?php echo $xId; ?>" 
                                                            onchange="f_UpdateEstGrupo(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xId; ?>,<?php echo $xUsuaid; ?>)" value="<?php echo $xId; ?>"/>
                                                        </div>
                                                    </div>
                                                </td>
                                    
                                                <td class="text-end">
                                                    <div class="text-center">
                                                        <div class="btn-group">
                                                            <button id="btnEditargru_<?php echo $xId; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" <?php echo $xDisabledEdit; ?> title='Editar Grupo' data-bs-toggle="tooltip" data-bs-placement="left" onclick="f_EditarGrupo(<?php echo $xId; ?>)">
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
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger border border-danger" data-bs-dismiss="modal"><i class="fa fa-times me-1" aria-hidden="true"></i>Cerrar</button>
            </div>
        </div>
    </div>
</div>  

<!--MODAL AGREGAR PRODUCTO-->
<div class="modal fade" id="modal_addproducto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-800px">
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
            <div class="modal-body py-lg-5 px-lg-10">
                <div class="card card-flush py-2">
                    <div class="card-body pt-0">
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="required form-label">Producto</label>
                                <input type="text" name="txtProducto" id="txtProducto" class="form-control" maxlength="150" placeholder="Ingrese Producto" value="" />
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-md-12">
                                <label class="form-label">Descripcion</label>
                                <textarea class="form-control" name="txtDescripcion" id="txtDescripcion" rows="1" maxlength="200" onkeydown="return(event.keyCode!=13);"></textarea>
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-md-1">
                                <label class="required form-label">Grupo</label>
                            </div>
                            <div class="col-md-7">
                               <?php
                                    $xSQL = "SELECT grup_id AS Codigo,grup_nombre AS NombreGrupo FROM `expert_grupos` WHERE pais_id=$xPaisid AND empr_id=$xEmprid ";
                                    $all_grupos =  mysqli_query($con, $xSQL);
                                ?>
                                <select name="cboGrupo" id="cboGrupo" aria-label="Seleccione Grupo" data-control="select2" data-placeholder="Seleccione Grupo" data-dropdown-parent="#modal_addproducto" class="form-select mb-2" >
                                    <option></option>
                                    <?php foreach ($all_grupos as $datos) : ?>
                                        <option value="<?php echo $datos['Codigo'] ?>"><?php echo mb_strtoupper($datos['NombreGrupo']) ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="required form-label">Costo</label>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="txtCosto" id="txtCosto" class="form-control form-control-solid" placeholder="Precio al Publico (0.00)" min="0" maxlength = "6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="0.00" step="0.01" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                            </div> 
                        </div>
                        <div class="row mb-10">
                            <div class="col-md-3">
                                <label class="form-label">Asistencia Mes:</label>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="txtAsisMes" id="txtAsisMes" class="form-control form-control-solid" value="1" />   
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Asistencia Anual:</label>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="txtAsisAnu" id="txtAsisAnu" class="form-control form-control-solid" placeholder="1" value="1" />  
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
                <button type="button" class="btn btn-sm btn-light-danger border border-danger" data-bs-dismiss="modal"><i class="fa fa-times me-1" aria-hidden="true"></i>Cerrar</button>
                <button type="button" id="btnAgregar" class="btn btn-sm btn-light-primary border border-primary"><i class="fa fa-plus me-1"></i>Agregar</button>
            </div>
        </div>   
    </div>
</div>
<!--MODAL EDITAR PRODUCTO-->
<div class="modal fade" id="modal_editproducto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-800px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Editar Producto</h2>
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
                        <div class="row">
                            <div class="col-md-12">
                                <label class="required form-label">Producto</label>
                                <input type="text" name="txtProductoEdit" id="txtProductoEdit" class="form-control" maxlength="150" placeholder="Ingrese Producto" value="" />
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-md-12">
                                <label class="form-label">Descripcion</label>
                                <textarea class="form-control " name="txtDescripcionEdit" id="txtDescripcionEdit" rows="1" maxlength="200" onkeydown="return(event.keyCode!=13);"></textarea>
                            </div>    
                        </div>
                        <div class="row mb-10">
                            <div class="col-md-1">
                                <label class="required form-label">Grupo</label>
                            </div>
                            <div class="col-md-7">
                                <select name="cboGrupoEdit" id="cboGrupoEdit" aria-label="Seleccione Grupo" data-control="select2" data-placeholder="Seleccione Grupo" data-dropdown-parent="#kt_ecommerce_add_product_advanced" class="form-select mb-2" >
                                    <option></option>
                                    <?php 
                                        $xSQL = "SELECT grup_id AS Codigo,grup_nombre AS NombreGrupo FROM `expert_grupos` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND grup_estado='A' ";
                                        $all_datos =  mysqli_query($con, $xSQL);
                                        foreach ($all_datos as $dato){ ?>
                                            <option value="<?php echo $dato['Codigo'] ?>"><?php echo mb_strtoupper($dato['NombreGrupo']) ?></option>
                                        <?php } ?>  
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label class="required form-label">Costo</label>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="txtCostoEdit" id="txtCostoEdit" class="form-control form-control-solid" placeholder="Precio al Publico (0.00)" min="0" maxlength = "6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="0.00" step="0.01" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                            </div>
                        </div>
                        <div class="row mb-10">
                            <div class="col-md-3">
                                <label class="form-label">Asistencia Mes:</label>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="txtAsisMesEdit" id="txtAsisMesEdit" class="form-control form-control-solid" value="1" onkeypress="return isNumberKey(event)" />  
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Asistencia Anual:</label>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="txtAsisAnuEdit" id="txtAsisAnuEdit" class="form-control form-control-solid" value="1" onkeypress="return isNumberKey(event)" />   
                            </div>
                        </div>
                        <div class="row border border-hover-primary py-lg-4 px-lg-10">
                            <div class="col-md-4">    
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" name="chkCoberturaEdit" id="chkCoberturaEdit" type="checkbox" />
                                    <h5 class="form-check-label txtcob" id="lblCoberturaEdit"></h5>
                                </label> 
                            </div>
                            <div class="col-md-4">
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" name="chkSistemaEdit" id="chkSistemaEdit" type="checkbox" />
                                    <h5 class="form-check-label txtsis"></h5>
                                </label> 
                            </div>
                            <div class="col-md-4">   
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" name="chkGerencialEdit" id="chkGerencialEdit" type="checkbox" />
                                    <h5 class="form-check-label txtger"></h5>
                                </label> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger border border-danger" data-bs-dismiss="modal"><i class="fa fa-times me-1" aria-hidden="true"></i>Cerrar</button>
                <button type="button" id="btnGuardar" onclick="f_EditarProd(<?php echo $xPaisid; ?>,<?php echo $xEmprid;?>,<?php echo $xUsuaid;?>)" class="btn btn-sm btn-light-primary border border-primary"><i class="las la-pencil-alt"></i>Modificar</button>
            </div>
        </div>
    </div>
</div>   

<script>

    var _cobertura = 'NO', _sistema = 'NO', _rowid, _seleccab = 'NO', _selecpie = 'NO';

    $(document).ready(function(){

        var _paisid = "<?php echo $xPaisid; ?>";
        var _emprid = "<?php echo $xEmprid; ?>";
        var _usuaid = "<?php echo $xUsuaid; ?>";

        var _mensaje = $('input#mensaje').val();

            if(_mensaje != ''){
                toastSweetAlert("top-end",3000,"success",_mensaje);
            }

        //Cargar imagen logo cabecera
        var _imgCab  = "<?php echo $xImgc; ?>";
        _imgCab = _imgCab == '' ? 'cliente.png' : _imgCab;
        document.getElementById('imgfileCab').style.backgroundImage="url(assets/images/clientes/" + _imgCab + ")"; 
            //Cargar imagen logp pie
        var _imgPie = "<?php echo $xImgp; ?>";
        _imgPie = _imgPie == '' ? 'cliente.png' : _imgPie;  
        document.getElementById('imgfilePie').style.backgroundImage="url(assets/images/clientes/" + _imgPie + ")"; 
        
        $('#cboProvincia').val("<?php echo $xCboProv; ?>").change();
        $('#cboCiudad').val(<?php echo $xProvid; ?>).change();


        $( "#txtCostoEdit" ).blur(function() {
            this.value = parseFloat(this.value).toFixed(2);
        }); 
        
        $("#btnNewGrupo").click(function(){

            $("#modal_new_grupo").modal("show");
        });

        $("#btnEditGrupo").click(function(){

            $('#divcampos').hide();
            $("#modal_edit_grupo").modal("show");
        });        

        //Cambiar valor provincia

        $('#cboProvincia').change(function(){
                
            _cboid = $(this).val(); //obtener el id seleccionado
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

    
    // imput type number

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

     // imput type number modal

    document.getElementById("txtAsisMesEdit").addEventListener("change", function() {
        let v = parseInt(this.value);
        if (v < 1) this.value = 1;
        if (v > 12) this.value = 12;
    });

    document.getElementById("txtAsisAnuEdit").addEventListener("change", function() {
        let v = parseInt(this.value);
        if (v < 1) this.value = 1;
        if (v > 3) this.value = 3;
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
    
    document.getElementById("txteditarnumagenda").addEventListener("change", function() {
        let v = parseInt(this.value);
        if (v < 1) this.value = 1;
        if (v > 99999) this.value = 1;
    });     

    document.getElementById("txteditarnumcancelado").addEventListener("change", function() {
        let v = parseInt(this.value);
        if (v < 1) this.value = 1;
        if (v > 99999) this.value = 1;
    });     


    //check agragar producto

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

    //Modal-Agregar-Producto
    $('#btnAgregarprod').click(function(){

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

        $('#modal_addproducto').modal('show');

    });


    //Agregar Producto directo a la base
    $('#btnAgregar').click(function(){

       debugger;
        var _gerencial = 'NO';
        var _output;
        var _clieid = "<?php echo $clieid; ?>";
        var _cbogrupo = $('#cboGrupo').val();
        var _paisid = "<?php echo $xPaisid; ?>";
        var _emprid = "<?php echo $xEmprid; ?>";
        var _usuaid = "<?php echo $xUsuaid; ?>";
        var _producto = $.trim($("#txtProducto").val());
        var _productoUpper = _producto.toUpperCase();
        var _descripcion = $.trim($("#txtDescripcion").val());
        var _costo = $.trim($("#txtCosto").val());
        var _txtGrupo = $('#cboGrupo').find('option:selected').text();
        var _asistemes = $('#txtAsisMes').val();
        var _asistanu = $('#txtAsisAnu').val();
   
        if(_producto == ''){
            toastSweetAlert("top-end",3000,"warning","Ingrese Producto..!!");
            return false;
        }

        if(_txtGrupo == ''){
            toastSweetAlert("top-end",3000,"warning","Seleccione Grupo..!!");
            return false;
        }

        if(_costo == 0){
            toastSweetAlert("top-end",3000,"warning","Ingrese Costo..!!");
            return false;
        }

      

        var _parametros = {
            
            "xxClieid" : _clieid,
            "xxGrupid" : _cbogrupo,
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxProducto" : _producto,
            "xxDesc" : _descripcion,
            "xxCosto" : _costo,
            "xxAsisMes" : _asistemes,
            "xxAsisAnu" : _asistanu,
            "xxCober" : _cobertura,
            "xxSist" : _sistema,
            "xxGeren" : _gerencial
        }

        var xrespuesta = $.post("codephp/consuin_produtosedit.php", _parametros);
        xrespuesta.done(function(response){
   
            if(response != 0){

                _id = response;
                _output = '<tr id="row_' + _id + '">';
                _output +='<td style="display: none;">' + _id + '</td>';
                _output +='<td>' +_txtGrupo + '</td>';
                _output +='<td>' +_productoUpper + '</td>';
                _output +='<td>' +_costo + '</td>';
                _output +='<td id="td_'+_id + '"><div class="badge badge-light-primary">ACTIVO</div></td>';
                _output +='<td><div class="form-check form-check-sm form-check-custom form-check-solid">';
                _output +='<input class="form-check-input h-20px w-20px border-primary btnEstado" checked="checked" type="checkbox" id="chk'+_id +'" ';
                _output +='onchange="f_UpdateEstado('+_id +','+ _emprid +','+_paisid +','+_usuaid +')" value=""/></div></td>';
                _output +='<td><div class="text-center"><div class="btn-group">';
                _output +='<button type="button" id="btnEditar_'+_id +'" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title="Editar Producto" data-bs-toggle="tooltip" data-bs-placement="left">';
                _output +='<i class="fa fa-edit"></i></button>';
                _output +='<button type="button" id="btnTitular_'+_id +'" onclick="f_Titular('+ _cbogrupo +','+ _id +','+ _clieid +')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Agregar Titular (+0 )" data-bs-toggle="tooltip" data-bs-placement="left"';
                _output +='title="Agregar Titular" data-bs-toggle="tooltip" data-bs-placement="left"><i class="fa fa-user"></i></button></div></div></td>';
                _output +='</tr>';

                $('#tblProducto').append(_output);
             
                $('#modal_addproducto').modal('hide');
                toastSweetAlert("top-end",3000,"success","Producto Agregado");
              

            }else{
                toastSweetAlert("top-end",3000,"error","Producto ya Existe..!!");
                document.getElementById("chkCobertura").checked = false;
                _cobertura = "NO";
                $("#lblCobertura").text("Cobertura NO");
                document.getElementById("chkSistema").checked = false;
                _sistema = "NO";
                $("#lblSistema").text("Sistema NO");   
            }

        });

    });

    //Desplazar-modal

    $("#modal_editproducto").draggable({
        handle: ".modal-header"
    });

    $("#modal_new_grupo").draggable({
        handle: ".modal-header"
    });

    $("#modal_edit_grupo").draggable({
        handle: ".modal-header"
    });    

    $("#modal_addproducto").draggable({
        handle: ".modal-header"
    });


    //Guardar nuevo grupo
    
    function f_GuardarGrupo(_paisid,_emprid,_usuaid){
        //debugger;
        var _nombreGrupo = $.trim($("#txtGrupo").val());
        var _descGrupo = $.trim($("#txtDescGrupo").val());
        var _numagenda = $("#txtnumagenda").val();
        var _numcancela = $("#txtnumcancelado").val();
        var _numatendido = $("#txtnumatendido").val();
        var _numausente = $("#txtnumausente").val();

        if(_nombreGrupo == ''){
            toastSweetAlert("top-end",3000,"warning","Ingrese Grupo..!!");
            return false;
        }

        var _parametros = {

            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxUsuaid" : _usuaid,
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

                        toastSweetAlert("top-end",3000,"success","Grupo Agregado"); 
                        
                        $("#txtGrupo").val("");
                        $("#txtDescGrupo").val("");
                        $("#cboGrupo").empty();
                        $("#cboGrupo").html(response);
                        $("#cboGrupoEdit").empty();
                        $("#cboGrupoEdit").html(response);       
                        $("#modal_new_grupo").modal("hide");
                    
                    }

                });

            }else  if(response.trim() == 'EXISTE'){
                toastSweetAlert("top-end",3000,"warning","Grupo ya Existe..!!");
                $("#txtGrupo").val("");
                $("#txtDescGrupo").val("");
            }
        });
    }

    //Update estado Producto

    function f_UpdateEstado(_prodid, _emprid, _paisid,_usuaid){

        var _check= $('#chk'+_prodid).is(':checked');
        var _checked = "";
        var _class = "badge badge-light-primary";
        var _td = "td_" + _prodid;
        var _btnedit = "btnEditar_" + _prodid;
        var _btntitu = "btnTitular_" + _prodid;
        var _estado;

        if(_check){

            _estado = 'ACTIVO';
            _checked = "checked='checked'";
            $('#'+_btnedit).prop("disabled",false);
            $('#'+_btntitu).prop("disabled",false);

        }else{
            _estado = 'INACTIVO';
            _class = "badge badge-light-danger";
            $('#'+_btnedit).prop("disabled",true);
            $('#'+_btntitu).prop("disabled",true);    
        }

        var _changetd = document.getElementById(_td);
            _changetd.innerHTML = '<div class="' + _class + '">'+_estado+'</div>';

            _parametros = {
                "xxProid" : _prodid,
                "xxEmprid" : _emprid,
                "xxPaisid" : _paisid,
                "xxUsuaid" : _usuaid,
                "xxEstado" : _estado
            } 

        var xrespuesta = $.post("codephp/update_estadoproducto.php", _parametros);
        xrespuesta.done(function(response){
        });	

    }

    function f_UpdateEstGrupo(_paisid, _emprid, _id, _usuaid){

        let _check= $('#chkgru'+_id).is(':checked');
        let _checked = "";
        let _class = "badge badge-light-primary";
        let _td = "tdgru_" + _id;
        let _btnedit = "btnEditargru_" + _id;
        let _estado;

        if(_check){
            _estado = 'ACTIVO';
            _checked = "checked='checked'";
            $('#'+_btnedit).prop("disabled",false);
        }else{
            _estado = 'INACTIVO';
            _class = "badge badge-light-danger";
            $('#'+_btnedit).prop("disabled",true);
        }

        var _changetd = document.getElementById(_td);
        _changetd.innerHTML = '<div class="' + _class + '">'+_estado+'</div>';

        _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxGrupoid" : _id,
            "xxUsuaid" : _usuaid,
            "xxEstado" : _estado
        } 

        var xrespuesta = $.post("codephp/update_estadogrupo.php", _parametros);
            xrespuesta.done(function(response){
        });        

    }

    function f_EditarGrupo(_idgrupo){

        $('#divcampos').show();

        _id = $('#txtgrupoid' + _idgrupo).val();
        _grupoold = $('#txtgrupo' + _idgrupo).val();
        _secuenagenda = $('#txtsecagenda' + _idgrupo).val();
        _secuencancela = $('#txtseccancela' + _idgrupo).val();

        $('#txteditargrupoid').val(_id);
        $('#txteditarGrupo').val(_grupoold);
        $('#txteditarnumagenda').val(_secuenagenda);
        $('#txteditarnumcancelado').val(_secuencancela);

    }

    function f_ModificarGrupo(_paisid,_emprid){
   
        _usuaid = '<?php echo $xUsuaid; ?>';
        _clieid = '<?php echo $clieid; ?>';

        _grupoid = $('#txteditargrupoid').val();
        _gruponew = $('#txteditarGrupo').val();
        _agendanew = $('#txteditarnumagenda').val();
        _cancelanew = $('#txteditarnumcancelado').val();

        _continuar = true;

        if(_gruponew != _grupoold){

            _parametros = {
                "xxPaisid" : _paisid,
                "xxEmprid" : _emprid,
                "xxGrupo" : _gruponew
            }  

            var xrespuesta = $.post("codephp/consultar_grupo.php", _parametros );
            xrespuesta.done(function(response){
                if(response.trim() == 'OK'){
                    _parametros = {
                        "xxPaisid" : _paisid,
                        "xxEmprid" : _emprid,
                        "xxGrupoid" : _grupoid,
                        "xxGrupo" : _gruponew,
                        "xxSecuenAgenda" : _agendanew,
                        "xxSecuenCancela" : _cancelanew,
                        "xxUsuaid" : _usuaid,
                    }      
                    var xresult = $.post("codephp/update_grupo.php", _parametros );   
                    xresult.done(function(response){           

                        if(response.trim() == 'OK'){
                            _output = '<td>' + _gruponew.toUpperCase() + '<input type="hidden" id="txtgrupoid'  + _grupoid + '" value="' + _grupoid + '"/><input type="hidden" id="txtgrupo'  + _grupoid + '" value="' + _gruponew + '"/></td>';
                            _output += '<td>' + _agendanew + '<input type="hidden" id="txtsecagenda'  + _grupoid + '" value="' + _agendanew + '"/></td>';
                            _output += '<td>' + _cancelanew + '<input type="hidden" id="txtseccancela'  + _grupoid + '" value="' + _cancelanew + '"/></td>';
                            _output += '<td id="tdgru_' + _grupoid + '"><div class="badge badge-light-primary">ACTIVO</div></td>';
                            _output += '<td class="text-end"><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid">'; 
                            _output += '<input class="form-check-input h-20px w-20px border-primary" checked="checked" type="checkbox" id="chkgru' + _grupoid + '" onchange="f_UpdateEstGrupo(';
                            _output += _paisid + ',' + _emprid + ',' + _grupoid + ')" value="' + _grupoid + '"/></div></div></td>';
                            _output += '<td class="text-end"><div class="text-center"><div class="btn-group"><button id="btnEditargru_' + _grupoid + '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 " ';
                            _output += 'title="Editar Grupo" onclick="f_EditarGrupo(' + _grupoid + ')"><i class="fa fa-edit"></i></button></div></div></td>';

                            //console.log(_output);

                            $('#trgru_' + _grupoid + '').html(_output);
                                $.redirect('?page=editcliente&menuid=<?php echo $menuid; ?>', {
                                'mensaje': 'Actualizado con Exito',
                                 'idclie': _clieid
                            
                            });

                        }
                    });
                }else{
                    toastSweetAlert("top-end",3000,"warning","Grupo ya Existe..!!");
                }
            });
        }else{
            _parametros = {
                    "xxPaisid" : _paisid,
                    "xxEmprid" : _emprid,
                    "xxGrupoid" : _grupoid,
                    "xxGrupo" : _gruponew,
                    "xxSecuenAgenda" : _agendanew,
                    "xxSecuenCancela" : _cancelanew,
                    "xxUsuaid" : _usuaid,
            }
            var xresult = $.post("codephp/update_grupo.php", _parametros );   
            xresult.done(function(response){           
                
                if(response.trim() == 'OK'){
                    _output = '<td>' + _gruponew.toUpperCase() + '<input type="hidden" id="txtgrupoid'  + _grupoid + '" value="' + _grupoid + '"/> <input type="hidden" id="txtgrupo'  + _grupoid + '" value="' + _gruponew + '"/></td>';
                    _output += '<td>' + _agendanew + '<input type="hidden" id="txtsecagenda'  + _grupoid + '" value="' + _agendanew + '"/></td>';
                    _output += '<td>' + _cancelanew + '<input type="hidden" id="txtseccancela'  + _grupoid + '" value="' + _cancelanew + '"/></td>';
                    _output += '<td id="tdgru_' + _grupoid + '"><div class="badge badge-light-primary">ACTIVO</div></td>';
                    _output += '<td class="text-end"><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid"> '; 
                    _output += '<input class="form-check-input h-20px w-20px border-primary" checked="checked" type="checkbox" id="chkgru' + _grupoid + '" onchange="f_UpdateEstGrupo(';
                    _output += _paisid + ',' + _emprid + ',' + _grupoid + ')" value="' + _grupoid + '"/></div></div></td>';
                    _output += '<td class="text-end"><div class="text-center"><div class="btn-group"><button id="btnEditargru_' + _grupoid + '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 " ';
                    _output += 'title="Editar Grupo" onclick="f_EditarGrupo(' + _grupoid + ')" ><i class="fa fa-edit"></i></button></div></div></td>';

                    //console.log(_output);

                    $('#trgru_' + _grupoid + '').html(_output);

                }
            });
        }

        $('#txteditargrupoid').val(0);
        $('#txteditarGrupo').val('');
        $('#txteditarnumagenda').val(0);
        $('#txteditarnumcancelado').val(0);

        $('#divcampos').hide();
    }

    //cargar datos ventana modal para editar producto

    $(document).on("click",".btnEditar",function(){

        
        $("#modal_editproducto").find("input,textarea,checkbox").val("");

        _rowid = $(this).attr("id");
        _rowid = _rowid.substring(10);
        _paisid = '<?php echo $xPaisid;?>';
        _emprid = '<?php echo $xEmprid;?>';

        var xrespuesta = $.post("codephp/get_datosproductos.php", { xxProid: _rowid,xxPaisid:_paisid,xxEmprid: _emprid  });
        xrespuesta.done(function(response){

            var _datos = JSON.parse(response);

            $.each(_datos,function(i,item){
                _clieid = _datos[i].Clieid;
                _grupid =  _datos[i].Grupid;
                _producto =  _datos[i].Nombre;
                _desc =  _datos[i].Descr;
                _costo =  _datos[i].Costo;
                _grupo =  _datos[i].Grupo;
                _asistmes =  _datos[i].AsistMes;
                _asistanu =  _datos[i].AsistAnu;
                _coberedit =  _datos[i].Cob;
                _sistedit =  _datos[i].Sis;
                _gerenciedit =  _datos[i].Ger;

                $('#cboGrupoEdit').val(_grupid).change();
                $('#txtProductoEdit').val(_producto);
                $('#txtDescripcionEdit').val(_desc);
                $('#txtCostoEdit').val(_costo);
                $('#txtAsisMesEdit').val(_asistmes);
                $('#txtAsisAnuEdit').val(_asistanu);

                if(_coberedit == 'SI'){
                    $('#chkCoberturaEdit').prop('checked', true);
                    $("#lblCoberturaEdit").text("Cobertura SI");
                }else{
                    $('#chkCoberturaEdit').prop('checked', false);
                    $("#lblCoberturaEdit").html("Cobertura NO");
                    
                }

                if(_sistedit == 'SI'){
                    $('#chkSistemaEdit').prop('checked', true); 
                    $(".txtsis").html("Sistema SI");
                }else{
                    $('#chkSistemaEdit').prop('checked', false);
                    $(".txtsis").html("Sistema NO");
                }

                if(_gerenciedit == 'SI'){
                    $('#chkGerencialEdit').prop('checked', true);
                    $(".txtger").html("Gerencial SI");
                }else{
                    $('#chkGerencialEdit').prop('checked', false);
                    $(".txtger").html("Gerencial NO");
                }


                $("#modal_editproducto").modal("show");

            });  

        });

    });

    //Check editar producto-modal

    $(document).on("click","#chkCoberturaEdit",function(){

            if($("#chkCoberturaEdit").is(":checked")){
            _coberedit = "SI";
            $(".txtcob").html("Cobertura SI");                   
                
        }else{
            _coberedit = "NO";
            $(".txtcob").html("Cobertura NO");
        }   
    });

    $(document).on("click","#chkSistemaEdit",function(){

        if($("#chkSistemaEdit").is(":checked")){
            _sistedit = "SI";
            $(".txtsis").html("Sistema SI");
        }else{
            _sistedit = "NO";
            $(".txtsis").html("Sistema NO");
        }    
    });

    $(document).on("click","#chkGerencialEdit",function(){

        if($("#chkGerencialEdit").is(":checked")){
            _gerenciedit = "SI";
            $(".txtger").html("Gerencial SI");
        
                
        }else{
            _gerenciedit = "NO";
            $(".txtger").html("Gerencial NO");
        
        }    
    });

    //Grabar editar producto modal
    
    function f_EditarProd(_paisid,_emprid,_usuaid){

        var _output;
        var _prodid = _rowid;
        var _prodedit= $('#txtProductoEdit').val();
        var _prodeditUpper= _prodedit.toUpperCase();
        var _descredit = $('#txtDescripcionEdit').val();
        var _costoedit = $('#txtCostoEdit').val();
        var _cbogrupoedit = $('#cboGrupoEdit').val();
        var _txtgrupoedit = $("#cboGrupoEdit option:selected").text();
        var _asismesedit = $('#txtAsisMesEdit').val();
        var _asisanuedit = $('#txtAsisAnuEdit').val();
        var _cobedit = _coberedit;
        var _sisedit = _sistedit;
        var _gerenedit = _gerenciedit;


        if(_prodedit == ''){
            toastSweetAlert("top-end",3000,"warning","Ingrese Producto..!!");
            return false;

        }

        if(_costoedit == 0){
            toastSweetAlert("top-end",3000,"warning","Ingrese Costo..!!");
            return false;
        }

        var _parametros = {
            "xxProdid" : _prodid,
            "xxGrupid" : _cbogrupoedit,
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxUsuaid" : _usuaid,
            "xxProdedit" : _prodedit,
            "xxProdant" : _producto,
            "xxDescr" : _descredit,
            "xxCostoedit" : _costoedit,
            "xxAsisMesedit" : _asismesedit,
            "xxAsisAnuedit" : _asisanuedit,
            "xxCobertura" : _cobedit,
            "xxSistema" : _sistedit,
            "xxGerencial" : _gerenedit        
        }

        var xrespuesta = $.post("codephp/grabar_editarproducto.php", _parametros);
        xrespuesta.done(function(response){

            if(response.trim() == 'OK'){

                _output ='<td style="display: none;">' + _prodid + '</td>';
                _output +='<td>' +_txtgrupoedit + '</td>';
                _output +='<td>' +_prodeditUpper + '</td>';
                _output +='<td>' +_costoedit + '</td>';
                _output +='<td id="td_'+ _prodid + '"><div class="badge badge-light-primary">ACTIVO</div></td>';
                _output +='<td><div class="form-check form-check-sm form-check-custom form-check-solid">';
                _output +='<input class="form-check-input h-20px w-20px border-primary btnEstado" checked="checked" type="checkbox" id="chk'+ _prodid +'" ';
                _output +='onchange="f_UpdateEstado('+ _prodid + ',' + _emprid + ',' + _paisid + ',' + _usuaid + ')" value=""/></div></td>';
                _output +='<td><div class="text-center"><div class="btn-group">';
                _output +='<button type="button" id="btnEditar_' + _prodid +'" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title="Editar Producto" data-bs-toggle="tooltip" data-bs-placement="left">';
                _output +='<i class="fa fa-edit"></i></button>';
                _output +='<button type="button" id="btnTitular_' + _prodid +'" onclick="f_Titular('+ _cbogrupoedit +','+ _prodid +','+ _clieid +')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" ';
                _output +='title="Agregar Titular" data-bs-toggle="tooltip" data-bs-placement="left"><i class="fa fa-user"></i></button></div></div></td>';

                //console.log(_output);
              
                $('#row_' + _rowid + '').html(_output);

                $("#modal_editproducto").modal("hide");

            }else{
                toastSweetAlert("top-end",3000,"warning","Producto ya Existe..!!");
            }

        });   

    }

    //Grabar editar cliente
    $('#btnGrabar').click(function(e){

        //debugger;
        var _idclie = '<?php echo $clieid; ?>';
        var _idpais = '<?php echo $xPaisid; ?>';
        var _idempr = '<?php echo $xEmprid; ?>';
        var _iduser = '<?php echo $xUsuaid; ?>';
        var _cboProv = $('#cboProvincia').val();
        var _cboCiudad = $('#cboCiudad').val();
        var _cliente = $.trim($("#txtCliente").val()); 
        var _desc = $.trim($("#txtDesc").val()); 
        var _direc = $.trim($("#txtDireccion").val()); 
        var _url = $.trim($("#txtUrl").val()); 
        var _telefono1 = $.trim($("#txtFono1").val()); 
        var _telefono2 = $.trim($("#txtFono2").val()); 
        var _telefono3 = $.trim($("#txtFono3").val()); 
        var _cel1 = $.trim($("#txtCelular1").val()); 
        var _cel2 = $.trim($("#txtCelular2").val()); 
        var _cel3 = $.trim($("#txtCelular3").val()); 
        var _email1 = $.trim($("#txtEmail1").val()); 
        var _email2 = $.trim($("#txtEmail2").val());

        var _providant = $.trim($('#txtcbociudad').val());
        var _clieant = $.trim($("#txtClieant").val());
        var _seleccab = 'NO';
        var _selecpie = 'NO';
      

        if(_cboCiudad == ''){
            toastSweetAlert("top-end",3000,"warning","Seleccione Ciudad..!!");
            return; 
        }


        if(_cliente == ''){
            toastSweetAlert("top-end",3000,"warning","Ingrese Cliente..!!");
            return false;
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

         if(_telefono3 != '')
	    {
            _valor = document.getElementById("txtFono3").value;
            if( !(/^(\d{7}|\d{9})$/.test(_valor)) ) {
                toastSweetAlert("top-end",3000,"error","Telefono 3 Incorrecto..!!");  
                return;
            }
	    } 
        
        if(_cel1 != '')
        {
            _valor = document.getElementById("txtCelular1").value;
            if( !(/^\d{10}$/.test(_valor)) ) {
                toastSweetAlert("top-end",3000,"error","Celular 1 Incorrecto..!!");
                return;
            }
        }                     
        
        if(_cel2 != '')
        {
            _valor = document.getElementById("txtCelular2").value;
            if( !(/^\d{10}$/.test(_valor)) ) {
                toastSweetAlert("top-end",3000,"error","Celular 2 Incorrecto..!!"); 
                return;
            }
        }
        
        if(_cel3 != '')
        {
            _valor = document.getElementById("txtCelular3").value;
            if( !(/^\d{10}$/.test(_valor)) ) {
                toastSweetAlert("top-end",3000,"error","Celular 3 Incorrecto..!!");
                return;
            }
        }
        
        if(_url != ''){
            try{
                new URL(_url);
            }catch(err){
                toastSweetAlert("top-end",3000,"error","Direccion URL Incorrecta..!!");
                return false;
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

            
        //Log Cabecera
        var _imgfilecab = document.getElementById("imgfileCab").style.backgroundImage;
        var _urlimgcab = _imgfilecab.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
        var _poscab = _urlimgcab.trim().indexOf('.');
        var _extcab = _urlimgcab.trim().substr(_poscab, 5);

        if(_extcab.trim() != '.png' && _extcab.trim() != '.jpg' && _extcab.trim() != 'jpeg'){
            _seleccab = 'SI';
        }  

        if(_seleccab == 'SI'){
            var _imagencab = document.getElementById("logoCab");
            var _filecab = _imagencab.files[0];
            var _fullPathcab = document.getElementById('logoCab').value;
            _extcab = _fullPathcab.substring(_fullPathcab.length - 4);
            _extcab = _extcab.toLowerCase();   
        }else{
            _filecab='';
        }

        if(_extcab.trim() != '.png' && _extcab.trim() != '.jpg' && _extcab.trim() != 'jpeg'){
            toastSweetAlert("top-end",3000,"error","El archivo seleccionado no es una Imagen..!!");
            return;
        }

        //Log Pie
        var _imgfilepie = document.getElementById("imgfilePie").style.backgroundImage;
        var _urlimgpie = _imgfilepie.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
        var _pospie = _urlimgpie.trim().indexOf('.');
        var _extpie = _urlimgpie.trim().substr(_pospie, 5);
     
        if(_extpie.trim() != '.png' && _extpie.trim() != '.jpg' && _extpie.trim() != 'jpeg'){
             _selecpie = 'SI';
        }  

        if(_selecpie == 'SI'){
            var _imagenpie = document.getElementById("logoPie");
            var _filepie = _imagenpie.files[0];
            var _fullPathpie = document.getElementById('logoPie').value;
            _extpie = _fullPathpie.substring(_fullPathpie.length - 4);
            _extpie = _extpie.toLowerCase();   
        }else{
            _filepie='';
        }

        if(_extpie.trim() != '.png' && _extpie.trim() != '.jpg' && _extpie.trim() != 'jpeg'){
            toastSweetAlert("top-end",3000,"error","Pie no es Imagen..!!");
            return;
        }

        var form_data = new FormData();
        form_data.append('xxClieid', _idclie);            
        form_data.append('xxPaisid', _idpais);
        form_data.append('xxEmprid', _idempr);
        form_data.append('xxUsuaid', _iduser);
        form_data.append('xxProvid', _cboCiudad);
        form_data.append('xxProvidant', _providant);
        form_data.append('xxCliente', _cliente);
        form_data.append('xxClieant', _clieant);
        form_data.append('xxDescrip', _desc);
        form_data.append('xxDirec', _direc);
        form_data.append('xxUrl', _url);
        form_data.append('xxFono1', _telefono1);
        form_data.append('xxFono2', _telefono2);
        form_data.append('xxFono3', _telefono3);
        form_data.append('xxCel1', _cel1);
        form_data.append('xxCel2', _cel2);
        form_data.append('xxCel3', _cel3);
        form_data.append('xxEmail1', _email1);
        form_data.append('xxEmail2', _email2);
        form_data.append('xxFileCab', _filecab);
        form_data.append('xxCambiarcab', _seleccab);
        form_data.append('xxFilePie', _filepie);
        form_data.append('xxCambiarpie', _selecpie);

        $.ajax({
            url: "codephp/update_cliente.php",
            type: "post",
            data: form_data,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response){
                if(response == 'OK'){
                    $.redirect('?page=admin_clienteproducto&menuid=<?php echo $menuid; ?>', {'mensaje': 'Actualizado con Exito'}); //POR METODO POST
                }else{
                    toastSweetAlert("top-end",3000,"warning","Cliente ya Existe..!!");
                }
            },
            error: function (error) {
                console.log(error);
            }
        });

    });

   function f_Titular(_idgrup,_idprod,_idclie){
        $.redirect('?page=addtitular&menuid=<?php echo $menuid; ?>', {
          'idgrup': _idgrup,
          'idprod': _idprod,
          'idclie': _idclie
		});
    
   }

   function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }   


</script>
