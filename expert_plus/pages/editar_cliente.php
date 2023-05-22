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


    $xSQL = "SELECT pro.prod_id AS Idprod, pro.prod_nombre AS Producto, pro.prod_descripcion AS Descrip, pro.prod_costo AS Costo, ";
    $xSQL .="pro.prod_asistmes AS AsisMes, pro.prod_asistanu AS AsisAnu, pro.prod_cobertura AS Cobertura, pro.prod_sistema AS Sistema, ";
    $xSQL .="pro.prod_gerencial AS Gerencial,CASE pro.prod_estado WHEN 'A' THEN 'Activo' ELSE 'Inactivo' END AS Estado, gru.grup_id AS Idgrup,gru.grup_nombre AS Grupo FROM `expert_productos` pro INNER JOIN ";
    $xSQL .="`expert_grupos` gru ON pro.grup_id = gru.grup_id WHERE pro.clie_id =$clieid AND pro.pais_id =$xPaisid AND pro.empr_id =$xEmprid ";
    $all_prod = mysqli_query($con, $xSQL);



?>

        <div id="kt_content_container" class="container-xxl">
            <form id="kt_ecommerce_add_product_form" class="form d-flex flex-column flex-lg-row">
                <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Logo Cabecera</h2>
                            </div>
                        </div>
                        <div class="card-body text-center pt-0">
                            <div class="image-input image-input-empty image-input-outline mb-3" data-kt-image-input="true" style="background-image: url(Cliente/<?php echo $xImgc; ?>)">
                                <div class="image-input-wrapper w-150px h-150px"></div>
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Cargar Logo">
                                    <i class="bi bi-pencil-fill fs-7"></i>
                                    <input type="file" name="avatar" id="imgCab" accept=".png, .jpg, .jpeg" />
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
                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Logo Pie</h2>
                            </div>
                        </div>
                        <div class="card-body text-center pt-0">
                            <div class="image-input image-input-empty image-input-outline mb-3" data-kt-image-input="true" style="background-image: url(Cliente/<?php echo $xImgp; ?>)">
                                <div class="image-input-wrapper w-150px h-150px"></div>
                                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Cargar Logo">
                                    <i class="bi bi-pencil-fill fs-7"></i>
                                    <input type="file" name="avatar" id="imgPie" accept=".png, .jpg, .jpeg" />
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
                    <div class="card card-flush py-4">
                        <div class="card-header">
                            <div class="card-title">
                                <h2>Opciones</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <button type="button" id="btnNewGrupo" class="btn btn-light-primary btn-sm mb-10">
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                                        <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>                                                                
                                Nuevo Grupo
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
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_add_product_advanced">Productos</a>
                            </li>
                            <a href="?page=admin_clienteproducto&menuid=<?php echo $menuid;?>" class="btn btn-icon btn-light-primary btn-sm ms-auto me-lg-n7">
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
                                                        <?php foreach ($cbo_ciudad as $ciudad) : ?>
                                                            <option value="<?php echo $ciudad['prov_id'] ?>"><?php echo mb_strtoupper($ciudad['ciudad']) ?></option>
                                                        <?php endforeach ?>  
                                                    </select>                                                      
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-5 fv-row">
                                            <label class="required form-label">Cliente</label>
                                            <input type="text" name="txtCliente" id="txtCliente" class="form-control mb-2" minlength="5" maxlength="150" placeholder="Ingrese Nombre" value="<?php echo $xCliente; ?>" />
                                        </div>
                                        <div class="mb-5 fv-row">
                                            <label class="required form-label">Descripcion</label>
                                            <textarea class="form-control mb-2" name="txtDesc" id="txtDesc" maxlength="200" onkeydown="return (event.keyCode!=13);"><?php echo $xDesc; ?></textarea>
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
                                            <div id="kt_customer_view_payment_method_1" class="collapse show fs-6 ps-10" data-bs-parent="#kt_customer_view_payment_method">
                                                <div class="d-flex flex-wrap py-5">
                                                    <div class="flex-equal me-5">
                                                        <div class="row mb-8">
                                                            <div class="col-xl-2">
                                                                <div class="required fs-6 fw-bold mt-2 mb-3">Direccion:</div>
                                                            </div>
                                                            <div class="col-xl-10 fv-row">
                                                                <textarea class="form-control mb-2 text-uppercase" name="txtDireccion" id="txtDireccion" maxlength="250" onkeydown="return (event.keyCode!=13);"><?php echo $xDirec; ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-8">
                                                            <div class="col-xl-2">
                                                                <div class="fs-6 fw-bold mt-2 mb-3">URL:</div>
                                                            </div>
                                                            <div class="col-xl-10 fv-row">
                                                                <input type="text" class="form-control mb-2 text-lowercase" name="txtUrl" id="txtUrl" maxlength="150" value="<?php echo $xUrl; ?>" />
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
                                                        <input type="text" class="form-control mb-2 w-150px" name="txtFono1" id="txtFono1" maxlength="10" placeholder="0299999999" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xTel1; ?>" />
                                                    </div>
                                                    <div class="col">
                                                        <div class="fs-6 fw-bold mt-2 mb-3">Telefono 2:</div>
                                                        <input type="text" class="form-control mb-2 w-150px" name="txtFono2" id="txtFono2" maxlength="10" placeholder="" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xTel2; ?>" />
                                                    </div> 
                                                    <div class="col">
                                                        <div class="fs-6 fw-bold mt-2 mb-3">Telefono 3:</div>
                                                        <input type="text" class="form-control mb-2 w-150px" name="txtFono2" id="txtFono2" maxlength="10" placeholder="" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xTel3; ?>" />
                                                    </div>                                                        
                                                </div>
                                                <div class="row row-cols-1 row-cols-sm-3 rol-cols-md-3 row-cols-lg-3">
                                                    <div class="col">
                                                        <div class="fs-6 fw-bold mt-2 mb-3">Celular 1:</div>
                                                        <input type="text" class="form-control mb-2 w-150px" name="txtCelular1" id="txtCelular1" maxlength="10" placeholder="0999999999" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xCel1; ?>" />
                                                    </div>
                                                    <div class="col">
                                                        <div class="fs-6 fw-bold mt-2 mb-3">Celular 2:</div>
                                                        <input type="text" class="form-control mb-2 w-150px" name="txtCelular2" id="txtCelular2" maxlength="10" placeholder="" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xCel2; ?>" />
                                                    </div> 
                                                    <div class="col">
                                                        <div class="fs-6 fw-bold mt-2 mb-3">Celular 3:</div>
                                                        <input type="text" class="form-control mb-2 w-150px" name="txtCelular3" id="txtCelular3" maxlength="10" placeholder="" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xCel3; ?>" />
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
                        </div>

                        <div class="tab-pane fade" id="kt_ecommerce_add_product_advanced" role="tab-panel">
                            <div class="d-flex flex-column gap-7 gap-lg-10">
                                <div class="card card-flush py-4">
                                    <div class="card-header">
                                        <div class="card-title">
                                            <h2>Producto</h2>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="mb-5 fv-row">
                                            <label class="required form-label">Producto</label>
                                            <input type="text" name="txtProducto" id="txtProducto" class="form-control mb-2" maxlength="150" placeholder="Ingrese Producto" value="" />
                                        </div>
                                        <div class="mb-5 fv-row">
                                            <label class="required form-label">Descripcion</label>
                                            <textarea class="form-control mb-2" name="txtDescripcion" id="txtDescripcion" maxlength="200" onkeydown="return (event.keyCode!=13);"></textarea>
                                        </div>
                                        <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                                            <div class="col">
                                                <label class="required form-label">Costo</label>
                                                <input type="text" name="txtCosto" id="txtCosto" class="form-control mb-2" maxlength="10" placeholder="0000" value="" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                                            </div>
                                            <div class="col">
                                                <label class="required form-label">Grupo</label>
                                                <select name="cboGrupo" id="cboGrupo" aria-label="Seleccione Provincia" data-control="select2" data-placeholder="Seleccione Grupo" data-dropdown-parent="#kt_ecommerce_add_product_general" class="form-select mb-2" >
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                                           <div class="col">
                                                <label class="required form-label">Asistencia Mes</label>
                                                <input type="number" name="txtAsisMes" id="txtAsisMes" class="form-control mb-2" value="1" />
                                                <label class="form-check form-switch form-check-custom form-check-solid">
                                                    <input class="form-check-input" name="chkCobertura" id="chkCobertura" type="checkbox" />
                                                    <span class="form-check-label fw-bold text-muted" id="lblCobertura" for="chkEnviar1">Cobertura NO</span>
                                                </label> 
                                           </div>
                                           <div class="col">
                                                <label class="required form-label">Asistencia Anual</label>
                                                <input type="number" name="txtAsisAnu" id="txtAsisAnu" class="form-control mb-2" placeholder="1" value="1" />
                                                <label class="form-check form-switch form-check-custom form-check-solid">
                                                    <input class="form-check-input" name="chkSistema" id="chkSistema" type="checkbox" />
                                                    <span class="form-check-label fw-bold text-muted" id="lblSistema" for="chkEnviar1">Sistema NO</span>
                                                </label> 
                                           </div>
                                        </div>
                                        </br>
                                        <div class="form-group mt-5">
                                            <button type="button" data-repeater-create="" class="btn btn-sm btn-light-primary" id="btnAgregar">
                                                <span class="svg-icon svg-icon-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                                                        <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
                                                    </svg>
                                                </span>
                                            Agregar Producto
                                            </button>
                                        </div>                                        
                                    </div>
                                </div>
                                <div class="card card-flush py-4">
                                    <div class="card-header">
                                        <!-- <h2>Productos Asignados</h2> -->
                                        <div class="card-title">
                                            <div class="d-flex align-items-center position-relative my-1">
                                                <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                                        <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <input type="text" data-kt-ecommerce-product-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Buscar Datos" />
                                            </div>
                                        </div>
                                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                            <div class="w-100 mw-150px">
                                                <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Estado" data-kt-ecommerce-product-filter="status">                                    
                                                    <option></option>
                                                    <option value="all">Todos</option>
                                                    <option value="Activo">Activo</option>
                                                    <option value="Inactivo">Inactivo</option>
                                                </select>
                                            </div>
                                        </div> 
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="d-flex flex-column gap-10">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
                                                <thead>
                                                    <tr class="text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                                        <th style="display:none;">Id</th>
                                                        <th>Grupo</th>
                                                        <th>Producto</th>
                                                        <th>Costo</th>
                                                        <th>Estado</th>
                                                        <th>Status</th>
                                                        <th style="text-align: center;">Opciones</th>
                                                    </tr>
                                                </thead>
                                                <div class="scroll-y me-n7 pe-7" id="parametro_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#parametro_header" data-kt-scroll-wrappers="#parametro_scroll" data-kt-scroll-offset="300px">
                                                    <tbody class="fw-bold text-gray-600">
                                                        <?php 
                                                            foreach($all_prod as $prod){
                                                            $xProdId = $prod['Idprod'];
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

                                                                if($xEstado == 'Activo'){
                                                                    $xCheking = 'checked="checked"';
                                                                    $xTextColor = "badge badge-light-primary";
                                                                }else{
                                                                    $xTextColor = "badge badge-light-danger";
                                                                    $xDisabledEdit = 'disabled';
                                                                }
                                                            ?>

                                                        <tr id="row_<?php echo $xProdid; ?>">
                                                            <td style="display: none;"><?php echo $xProdid; ?></td>
                                                            <td id="gru_<?php echo $xGrupId; ?>">
                                                                <?php echo $xGrupo; ?>
                                                            </td>
                                                            <td><?php echo $xProducto; ?></td>
                                                            <td><?php echo $xCosto; ?></td>
                                                            <td id="td_<?php echo $xProdId; ?>">   
                                                                <div class="<?php echo $xTextColor; ?>">
                                                                    <?php echo $xEstado; ?>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                                    <input <?php echo $xCheking; ?> class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk<?php echo $xProdId;?>" 
                                                                    onchange="f_UpdateEstado(<?php echo $xProdId;?>,<?php echo $xEmprid; ?>)" value=""/>
                                                                </div>
                                                            </td>
                                                            <td>
                                                            <div class="text-center">
                                                                <div class="btn-group">	
                                                                    <button type="button" id="btnEditar_<?php echo $xProdid;?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title='Editar Producto'>
                                                                        <i class="fa fa-edit"></i>
                                                                    </button> 
                                                                </div>
                                                            </div>
                                                            </td>
                                                        </tr>

                                                        <?php }?>    
                                                    </tbody>
                                                </div>    
                                            </table>
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

        <div class="modal fade" id="modal_new_grupo" tabindex="-1" aria-hidden="true">
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
                            <input type="text" class="form-control mb-2 text-uppercase" maxlength="80" placeholder="Nombre Grupo" name="txtGrupo" id="txtGrupo" />
                        </div>
                        <div class="fv-row mb-15">
                            <label class="fs-6 fw-bold form-label mb-2">
                                <span>Descripcion</span>
                            </label>
                            <textarea class="form-control mb-2" name="txtDescGrupo" id="txtDescGrupo" maxlength="150" onkeydown="return (event.keyCode!=13);"></textarea>
                        </div>                         
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" id="btnGuardar" onclick="f_GuardarGrupo(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xUsuaid; ?>)" class="btn btn-primary">Grabar</button>
                    </div>
                </div>
            </div>
        </div>

        <script>

            var _result = [],_count =0,_cobertura = "NO",_sistema = "NO";

            $(document).ready(function(){

                $("#btnNewGrupo").click(function(){

                   $("#modal_new_grupo").modal("show");
                });

                $('#cboProvincia').val("<?php echo $xCboProv; ?>").change();
                $('#cboCiudad').val(<?php echo $xProvid; ?>).change();


                
            });

            //Agregar Producto directo a la base
            $('#btnAgregar').click(function(){



                //alert('aki');
            });


    

             

            //Desplazar-modal


            $("#modal-new-especialidad").draggable({
                handle: ".modal-header"
            });
            
            function f_GuardarGrupo(_paisid,_emprid,_usuaid){

                var _nombreGrupo = $.trim($("#txtGrupo").val());
                var _descGrupo = $.trim($("#txtDescGrupo").val());

                if(_nombreGrupo == ''){
                    mensajesalertify("Ingrese Grupo..!!","W","top-right",3);
                    return false;
                }


                var _parametros = {

                    xxPaisId: _paisid,
                    xxEmprId: _emprid,
                    xxUsuaId: _usuaid,
                    xxGrupo: _nombreGrupo,
                    xxDesc: _descGrupo
                }

                var xrespuesta = $.post("codephp/grabar_grupo.php", _parametros);
                    xrespuesta.done(function(response){

                        if(response.trim() != 'ERR'){

                            mensajesalertify('Nuevo Grupo Agregado', 'S', 'top-center', 3); 
                            
                            $("#txtGrupo").val("");
                            $("#txtDescGrupo").val("");
                            $("#cboGrupo").empty();
                            $("#cboGrupo").html(response);     
                            $("#modal_new_grupo").modal("hide");

                        }else if(response.trim() == 'EXISTE'){
                            mensajesalertify('Grupo ya Existe', 'W', 'top-right', 3);
                        }

                    });

                //alert(_usuaid);

            }





        </script>
