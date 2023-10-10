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
                    <div class="card-title">
                        <h2>Logo Cabecera</h2>
                    </div>
                </div>
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
                <a href="?page=admin_clienteproducto&menuid=<?php echo $menuid;?>" class="btn btn-icon btn-light-primary btn-sm ms-auto me-lg-n7" title="Regresar" data-bs-toggle="tooltip" data-bs-placement="left">
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
                                            <input type="hidden" name="txtcbociudad" id="txtcbociudad" class="form-control mb-2" value="<?php echo $xProvid; ?>"  />                                                      
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-5 fv-row">
                                    <label class="required form-label">Cliente</label>
                                    <input type="text" name="txtCliente" id="txtCliente" class="form-control mb-2 text-uppercase" minlength="5" maxlength="150" placeholder="Ingrese Nombre" value="<?php echo $xCliente; ?>" />
                                    <input type="hidden" name="txtClieant" id="txtClieant" class="form-control mb-2" value="<?php echo $xCliente; ?>" />
                                </div>
                                <div class="mb-5 fv-row">
                                    <label class="required form-label">Descripcion</label>
                                    <textarea class="form-control mb-2 text-uppercase" name="txtDesc" id="txtDesc" maxlength="200" onkeydown="return (event.keyCode!=13);"><?php echo $xDesc; ?></textarea>
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
                                                        <textarea class="form-control mb-2 text-uppercase" name="txtDireccion" id="txtDireccion" maxlength="250" onkeydown="return (event.keyCode!=13);"><?php echo $xDirec; ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="row mb-8">
                                                    <div class="col-xl-2">
                                                        <div class="fs-6 fw-bold mt-2 mb-3">URL:</div>
                                                    </div>
                                                    <div class="col-xl-10 fv-row">
                                                        <input type="text" class="form-control mb-2 text-lowercase" name="txtUrl" id="txtUrl" placeholder="https://misitio.com" maxlength="150" value="<?php echo $xUrl; ?>" />
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
                                                <input type="text" class="form-control mb-2 w-150px" name="txtFono1" id="txtFono1" maxlength="9" placeholder="022222222" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xTel1; ?>" />
                                            </div>
                                            <div class="col">
                                                <div class="fs-6 fw-bold mt-2 mb-3">Telefono 2:</div>
                                                <input type="text" class="form-control mb-2 w-150px" name="txtFono2" id="txtFono2" maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xTel2; ?>" />
                                            </div> 
                                            <div class="col">
                                                <div class="fs-6 fw-bold mt-2 mb-3">Telefono 3:</div>
                                                <input type="text" class="form-control mb-2 w-150px" name="txtFono2" id="txtFono2" maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" value="<?php echo $xTel3; ?>" />
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
                                    <input class="form-control mb-2 text-uppercase" type="text" name="txtProducto" id="txtProducto" class="form-control mb-2" maxlength="150" placeholder="Ingrese Producto" value="" />
                                </div>
                                <div class="mb-5 fv-row">
                                    <label class="form-label">Descripcion</label>
                                    <textarea class="form-control mb-2 text-uppercase" name="txtDescripcion" id="txtDescripcion" rows="1" maxlength="200" onkeydown="return (event.keyCode!=13);"></textarea>
                                </div>
                                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                                    <div class="col">
                                        <label class="required form-label">Costo</label>
                                        <input type="number" name="txtCosto" id="txtCosto" class="form-control mb-2" placeholder="Precio al Publico (0.00)" min="0" maxlength = "6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="0.00" step="0.01" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                                    </div>
                                    <div class="col">
                                        <label class="required form-label">Grupo</label>
                                        <select name="cboGrupo" id="cboGrupo" aria-label="Seleccione Grupo" data-control="select2" data-placeholder="Seleccione Grupo" data-dropdown-parent="#kt_ecommerce_add_product_advanced" class="form-select mb-2" >
                                            <option></option>
                                            <?php foreach ($all_grupos as $datos) : ?>
                                                <option value="<?php echo $datos['Codigo'] ?>"><?php echo mb_strtoupper($datos['NombreGrupo']) ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                                    <div class="col">
                                        <label class="required form-label">Asistencia Mes</label>
                                        <input type="number" name="txtAsisMes" id="txtAsisMes" class="form-control mb-2" value="1" />
                                        <br>
                                        <label class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input" name="chkCobertura" id="chkCobertura" type="checkbox" />
                                            <span class="form-check-label fw-bold text-muted" id="lblCobertura" for="chkEnviar1">Cobertura NO</span>
                                        </label> 
                                    </div>
                                    <div class="col">
                                        <label class="required form-label">Asistencia Anual</label>
                                        <input type="number" name="txtAsisAnu" id="txtAsisAnu" class="form-control mb-2" placeholder="1" value="1" />
                                        <br>
                                        <label class="form-check form-switch form-check-custom form-check-solid">
                                            <input class="form-check-input" name="chkSistema" id="chkSistema" type="checkbox" />
                                            <span class="form-check-label fw-bold text-muted" id="lblSistema" for="chkEnviar1">Sistema NO</span>
                                        </label> 
                                    </div>
                                </div>
                                <br>
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
                                <div class="card-title">
                                        <h2>Productos Asignados</h2> 
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="d-flex flex-column gap-10">
                                    <div class="scroll-y me-n7 pe-7" id="parametro_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#parametro_header" data-kt-scroll-wrappers="#parametro_scroll" data-kt-scroll-offset="300px">
                                        <table class="table table-hover align-middle table-row-dashed fs-6 gy-5" id="tblProducto">
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
                                                    ?>

                                                <tr id="row_<?php echo $xProdid; ?>">
                                                    <td style="display: none;"><?php echo $xProdid; ?></td>
                                                    <td><?php echo $xGrupo; ?></td>
                                                    <td><?php echo $xProducto; ?></td>
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
                                                                <button type="button" id="btnEditar_<?php echo $xProdid; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" <?php echo $xDisabledEdit; ?> title='Editar Producto' data-bs-toggle="tooltip" data-bs-placement="left">
                                                                    <i class="fa fa-edit"></i>
                                                                </button> 
                                                                <button type="button" id="btnTitular_<?php echo $xProdid; ?>" onclick="f_Titular(<?php echo $xGrupId;?>,<?php echo $xProdid;?>,<?php echo $clieid;?>)" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" <?php echo $xDisabledEdit; ?> title='Agregar Titular' data-bs-toggle="tooltip" data-bs-placement="left">
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
            <div class="d-flex justify-content-end">
                <button type="button" id="btnGrabar" class="btn btn-primary">
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
                    <textarea class="form-control mb-2 text-uppercase" name="txtDescGrupo" id="txtDescGrupo" rows="1" maxlength="150" onkeydown="return (event.keyCode!=13);"></textarea>
                </div>                         
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" id="btnGuardar" onclick="f_GuardarGrupo(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xUsuaid; ?>)" class="btn btn-primary">Grabar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_producto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Editar Producto</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body">
                <div class="border border-hover-primary p-7 rounded mb-7 ">
                    <div class="mb-5 fv-row">
                        <label class="required form-label">Producto</label>
                        <input class="form-control mb-2 text-uppercase" type="text" name="txtProductoEdit" id="txtProductoEdit" class="form-control mb-2" maxlength="150" placeholder="Ingrese Producto" value="" />
                    </div>
                    <div class="mb-5 fv-row">
                        <label class="form-label">Descripcion</label>
                        <textarea class="form-control mb-2 text-uppercase" name="txtDescripcionEdit" id="txtDescripcionEdit" rows="1" maxlength="200" onkeydown="return (event.keyCode!=13);"></textarea>
                    </div>
                    <br>
                    <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                        <div class="col">
                            <label class="required form-label">Costo</label>
                            <input type="number" name="txtCostoEdit" id="txtCostoEdit" class="form-control mb-2" placeholder="Precio al Publico (0.00)" min="0" maxlength = "6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="0.00" step="0.01" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                        </div>
                        <div class="col">
                            <label class="required form-label">Grupo</label>
                            <select name="cboGrupoEdit" id="cboGrupoEdit" aria-label="Seleccione Grupo" data-control="select2" data-placeholder="Seleccione Grupo" data-dropdown-parent="#kt_ecommerce_add_product_advanced" class="form-select mb-2" >
                                <option></option>
                                <?php 
                                    $xSQL = "SELECT grup_id AS Codigo,grup_nombre AS NombreGrupo FROM `expert_grupos` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND grup_estado='A' ";
                                    $all_datos =  mysqli_query($con, $xSQL);
                                    foreach ($all_datos as $dato){ ?>
                                        <option value="<?php echo $dato['Codigo'] ?>"><?php echo $dato['NombreGrupo'] ?></option>
                                    <?php } ?>  
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                        <div class="col">
                            <label class="required form-label">Asistencia Mes</label>
                            <input type="number" name="txtAsisMesEdit" id="txtAsisMesEdit" class="form-control mb-2" value="1" />  
                        </div>
                        <div class="col">
                            <label class="required form-label">Asistencia Anual</label>
                            <input type="number" name="txtAsisAnuEdit" id="txtAsisAnuEdit" class="form-control mb-2" value="1" />   
                        </div>
                    </div>
                </div>
                <div class="border border-hover-primary p-7 rounded mb-7 ">
                    <div class="row g-9 mb-8">
                        <div class="col-md-4 fv-row">
                            <h5 class="txtcob" id="lblCoberturaEdit"></h5>
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" name="chkCoberturaEdit" id="chkCoberturaEdit" type="checkbox" />
                            </label> 
                        </div>
                        <div class="col-md-4 fv-row t">
                            <h5 class="txtsis"></h5>
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" name="chkSistemaEdit" id="chkSistemaEdit" type="checkbox" />
                            </label> 
                        </div>
                        <div class="col-md-4 fv-row">
                            <h5 class="txtger"></h5>
                            <label class="form-check form-switch form-check-custom form-check-solid">
                                <input class="form-check-input" name="chkGerencialEdit" id="chkGerencialEdit" type="checkbox" />
                            </label> 
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" id="btnGuardar" onclick="f_EditarProd(<?php echo $xPaisid; ?>,<?php echo $xEmprid;?>,<?php echo $xUsuaid;?>)" class="btn btn-primary">Modificar</button>
                </div>
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
                mensajesalertify(_mensaje,"S","top-center",3); 
            }

        //Cargar imagen logo cabecera
        var _imgCab  = "<?php echo $xImgc; ?>";
        _imgCab = _imgCab == '' ? 'companyname.png' : _imgCab;
        document.getElementById('imgfileCab').style.backgroundImage="url(logos/" + _imgCab + ")"; 
            //Cargar imagen logp pie
        var _imgPie = "<?php echo $xImgp; ?>";
        _imgPie = _imgPie == '' ? 'companyname.png' : _imgPie;  
        document.getElementById('imgfilePie').style.backgroundImage="url(logos/" + _imgPie + ")"; 
        
        $('#cboProvincia').val("<?php echo $xCboProv; ?>").change();
        $('#cboCiudad').val(<?php echo $xProvid; ?>).change();


        $( "#txtCostoEdit" ).blur(function() {
            this.value = parseFloat(this.value).toFixed(2);
        }); 
        
        $("#btnNewGrupo").click(function(){

            $("#modal_new_grupo").modal("show");
        });

        //Cambiar valor provincia

        $('#cboProvincia').change(function(){
                
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


    //Agregar Producto directo a la base
    $('#btnAgregar').click(function(){

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
            mensajesalertify("Ingrese Producto..!!","W","top-right",3);
            return false;
        }

        if(_costo == 0){
            mensajesalertify("Ingrese Costo..!!","W","top-right",3);
            return false;
        }

        if(_txtGrupo == ''){
            mensajesalertify("Seleccione Grupo..!!","W","top-right",3);
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
                _output +='<button type="button" id="btnTitular_'+_id +'" onclick="f_Titular('+ _cbogrupo +','+ _id +','+ _clieid +')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" ';
                _output +='title="Agregar Titular" data-bs-toggle="tooltip" data-bs-placement="left"><i class="fa fa-user"></i></button></div></div></td>';
                _output +='</tr>';

                $('#tblProducto').append(_output);
                mensajesalertify('Agregado Correctamente..!', 'S', 'top-center', 3);

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
              

            }else{
                mensajesalertify('Producto ya está Asignado..!', 'W', 'top-right', 3);
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

    $("#modal_producto").draggable({
        handle: ".modal-header"
    });

    $("#modal_new_grupo").draggable({
        handle: ".modal-header"
    });


    //Guardar nuevo grupo
    
    function f_GuardarGrupo(_paisid,_emprid,_usuaid){

        var _nombreGrupo = $.trim($("#txtGrupo").val());
        var _descGrupo = $.trim($("#txtDescGrupo").val());

        if(_nombreGrupo == ''){
            mensajesalertify("Ingrese Grupo..!!","W","top-right",3);
            return false;
        }

        var _parametros = {

            "xxPaisId" : _paisid,
            "xxEmprId" : _emprid,
            "xxUsuaId" : _usuaid,
            "xxGrupo" : _nombreGrupo,
            "xxDesc" : _descGrupo
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
                        $("#cboGrupoEdit").empty();
                        $("#cboGrupoEdit").html(response);       
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

    //cargar datos ventana modal para editar producto

    $(document).on("click",".btnEditar",function(){

        
        $("#modal_producto").find("input,textarea,checkbox").val("");

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


                $("#modal_producto").modal("show");

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
            mensajesalertify("Ingrese Producto..!!","W","top-right",3);
            return false;

        }

        if(_costoedit == 0){
            mensajesalertify("Ingrese Costo..!!","W","top-right",3);
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

                $("#modal_producto").modal("hide");

            }else{
                mensajesalertify("Producto ya está asignado..!", "W", "top-right", 3);
            }

        });   

    }

    //Grabar editar cliente
    $('#btnGrabar').click(function(e){

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
        var _tel1 = $.trim($("#txtFono1").val()); 
        var _tel2 = $.trim($("#txtFono2").val()); 
        var _tel3 = $.trim($("#txtFono3").val()); 
        var _cel1 = $.trim($("#txtCelular1").val()); 
        var _cel2 = $.trim($("#txtCelular2").val()); 
        var _cel3 = $.trim($("#txtCelular3").val()); 
        var _email1 = $.trim($("#txtEmail1").val()); 
        var _email2 = $.trim($("#txtEmail2").val());

        var _providant = $.trim($('#txtcbociudad').val());
        var _clieant = $.trim($("#txtClieant").val());
      

        if(_cboCiudad == ''){
            mensajesalertify("Seleccione Ciudad..!", "W", "top-right", 3);
            return; 
        }


        if(_cliente == ''){
            mensajesalertify("Ingrese Nombre del Cliente..!!","W","top-right",3);
            return false;
        }

            
        if(_tel1 != '')
        {
            _valor = document.getElementById("txtFono1").value;
            if( !(/^\d{9}$/.test(_valor)) ) {
                mensajesalertify("Telefono 1 incorrecto..!" ,"W", "top-right", 3); 
                return;
            }
        }

        if(_tel2 != '')
        {
            _valor = document.getElementById("txtFono2").value;
            if( !(/^\d{9}$/.test(_valor)) ) {
                mensajesalertify("Telefono 2 incorrecto..!" ,"W", "top-right", 3); 
                return;
            }
        }                    

        if(_tel3 != '')
        {
            _valor = document.getElementById("txtFono3").value;
            if( !(/^\d{9}$/.test(_valor)) ) {
                mensajesalertify("Telefono 3 incorrecto..!" ,"W", "top-right", 3); 
                return;
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
        
        if(_url != ''){
            try{
                new URL(_url);
            }catch(err){
                mensajesalertify("Direccion URL Incorrecta..!", "W", "top-right", 3);
                return false;
            }
        }
        
        if(_email1 != ''){
            var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
        
            if (regex.test($('#txtEmail1').val().trim())) {
            }else{
                mensajesalertify("Email 1 no es Valido..!", "W", "top-right", 3);
                return;
            }
        }

        if(_email2 != ''){
            var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
        
            if (regex.test($('#txtEmail2').val().trim())) {
            }else{
                mensajesalertify("Email 2 no es Valido..!", "W", "top-right", 3);
                return;
            }
        }

            
        //Log Cabecera

        var _logocab = document.getElementById("imgfileCab").style.backgroundImage;
        var _urlcab = _logocab.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
            _extcab = _urlcab.substring(_urlcab.length - 4);

        if(_extcab.trim() != '.png' && _extcab.trim() != '.jpg' && _extcab.trim() != 'jpeg'){
             _seleccab = 'SI';
        }  

        if(_seleccab == 'SI'){
            var _imagencab = document.getElementById("logoCab");
            var _filecab = _imagencab.files[0];
            var _fullPathcab = document.getElementById('logoCab').value;
            _extcab = _fullPathcab.substring(_fullPathcab.length - 4);
            _extcab = _extcab.toLowerCase();   
        }

        if(_extcab.trim() != '.png' && _extcab.trim() != '.jpg' && _extcab.trim() != 'jpeg'){
            //mensajesweetalert("center","warning","El archivo seleccionado no es una Imagen..!",false,1800);
            mensajesalertify("El archivo seleccionado en cabecera no es una Imagen..!", "W", "top-right", 3);
            return;
        }


            //Log Pie
      

        var _logopie = document.getElementById("imgfilePie").style.backgroundImage;
        var _urlpie = _logopie.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
        _extpie = _urlpie.substring(_urlpie.length - 4);
     

        if(_extpie.trim() != '.png' && _extpie.trim() != '.jpg' && _extpie.trim() != 'jpeg'){
             _selecpie = 'SI';
        }  

        if(_selecpie == 'SI'){
            var _imagenpie = document.getElementById("logoPie");
            var _filepie = _imagenpie.files[0];
            var _fullPathpie = document.getElementById('logoPie').value;
            _extpie = _fullPathpie.substring(_fullPathpie.length - 4);
            _extpie = _extpie.toLowerCase();   
        }

        if(_extpie.trim() != '.png' && _extpie.trim() != '.jpg' && _extpie.trim() != 'jpeg'){
            mensajesalertify("El archivo seleccionado en pie no es una Imagen..!", "W", "top-right", 3);
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
        form_data.append('xxFono1', _tel1);
        form_data.append('xxFono2', _tel2);
        form_data.append('xxFono3', _tel3);
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
                    $.redirect('?page=admin_clienteproducto&menuid=<?php echo $menuid; ?>', {'mensaje': 'Actualizado con Exito..!'}); //POR METODO POST
                }else{
                    mensajesalertify("Cliente ya Existe..!", "W", "top-right", 3);
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


</script>
