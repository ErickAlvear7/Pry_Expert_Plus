<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	  

    $xFechaActual = strftime('%Y-%m-%d', time());
    $xFechaFinCobertura = date("Y-m-d",strtotime ( "+1 year" , strtotime ( $xFechaActual)));

    require_once("dbcon/config.php");
    require_once("dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    //$xServidor = $_SERVER['HTTP_HOST'];
    $page = isset($_GET['page']) ? $_GET['page'] : "index";
    $mensaje = (isset($_POST['mensaje'])) ? $_POST['mensaje'] : '';

    $menuid = $_GET['menuid'];
    $clieid = $_POST['idclie'];
    $prodid = $_POST['idprod'];
    $grupid = $_POST['idgrup'];

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

    $xSQL = "SELECT paca_id AS Idpaca FROM `expert_parametro_cabecera` WHERE paca_nombre='Parentesco' ";
    $all_id = mysqli_query($con, $xSQL);

    foreach($all_id as $id){
        $xPacaid = $id['Idpaca'];
    }

    $xSQL = "SELECT  pade_orden AS Orden FROM `expert_parametro_detalle`WHERE paca_id = $xPacaid ORDER BY pade_orden DESC LIMIT 1 ";
    $orden = mysqli_query($con, $xSQL);
    foreach($orden as $ord){
        $xOrdenDet = $ord['Orden'];
    }


    $xSQL = "SELECT DISTINCT provincia AS Descripcion FROM `provincia_ciudad` ";
	$xSQL .= "WHERE pais_id=$xPaisid AND estado='A' ORDER BY provincia ";
    $all_provincia = mysqli_query($con, $xSQL);

    $xSQL = "SELECT pst.pers_id AS PerId, tit.titu_id AS Tituid, CONCAT(pst.pers_nombres,' ',pst.pers_apellidos) AS Nombres,pst.pers_imagen AS Imagen, ";
    $xSQL .= "CASE pst.pers_estado WHEN 'A' THEN 'ACTIVO' ELSE 'INACTIVO' END AS Estado,pst.pers_ciudad AS CiudadId FROM `expert_persona` pst, `expert_titular` tit ";
    $xSQL .= "WHERE tit.pais_id = $xPaisid AND tit.empr_id=$xEmprid AND pst.pers_id=tit.pers_id AND tit.prod_id=$prodid AND tit.grup_id=$grupid ORDER BY pst.pers_nombres ";
    $all_persona = mysqli_query($con, $xSQL);

    $xSQL ="SELECT clie_nombre AS Cliente,clie_tel1 AS Tel1,clie_tel2 AS Tel2,clie_cel1 AS Cel1,clie_cel2 AS Cel2,clie_email1 AS Email1,clie_email2 AS Email2 FROM `expert_cliente` WHERE clie_id=$clieid AND pais_id=$xPaisid AND empr_id=$xEmprid ";
    $Cliente = mysqli_query($con, $xSQL);

    foreach($Cliente as $clie){
        $Nombre = $clie['Cliente'];
        $Telefono1 = $clie['Tel1'];
        $Telefono2 = $clie['Tel2'];
        $Celular1 = $clie['Cel1'];
        $Celular2 = $clie['Cel2'];
        $Email1 = $clie['Email1'];
        $Email2 = $clie['Email2'];
    }

    if($Telefono1 == ''){
        $Telefono1 = ' # ';
    }
    
    if($Telefono2 == ''){
        $Telefono2 = ' # ';
    }

    if($Celular1 == ''){
        $Celular1 = ' # ';
    }

    if($Celular2 == ''){
        $Celular2 = ' # ';
    }

    if($Email1 == ''){
        $Email1 = 'sinemail@gmail.com';
    }

    $xSQL ="SELECT pro.prod_nombre AS Producto,pro.prod_costo AS Costo,pro.prod_asistmes AS AsisMes,pro.prod_asistanu AS AsisAnu,pro.prod_cobertura AS Cobertura, ";
    $xSQL .="pro.prod_sistema AS Sistema,pro.prod_gerencial AS Gerencial,pro.prod_estado AS Estado,gru.grup_nombre AS Grupo FROM `expert_productos`pro,`expert_grupos` gru WHERE pro.grup_id =gru.grup_id AND pro.prod_id=$prodid AND pro.clie_id=$clieid ";
    $xSQL .="AND pro.grup_id=$grupid AND pro.pais_id=$xPaisid AND pro.empr_id=$xEmprid ";
    $Producto = mysqli_query($con, $xSQL);

    foreach($Producto as $prod){
        $NomProd = $prod['Producto'];
        $Costo = $prod['Costo'];
        $AsisMes = $prod['AsisMes'];
        $AsisAnu = $prod['AsisAnu'];
        $Cobertura = $prod['Cobertura'];
        $Sistema = $prod['Sistema'];
        $Gerencial = $prod['Gerencial'];
        $Estado = $prod['Estado'];
        $NomGrupo = $prod['Grupo'];
    }

    $xChekCober = '';
    $xChekSis= '';

    if($Cobertura == 'SI'){
        $xChekCober = 'checked="checked"';
    }

    if($Sistema == 'SI'){
        $xChekSis = 'checked="checked"';
    }

?>
<div id="kt_content_container" class="container-xxl">
    <input type="hidden" id="mensaje" value="<?php echo $mensaje ?>">
    <form id="kt_ecommerce_edit_order_form" class="form d-flex flex-column flex-lg-row" data-kt-redirect="../../demo1/dist/apps/ecommerce/sales/listing.html">
        <div class="w-100 flex-lg-row-auto w-lg-300px mb-7 me-7 me-lg-10">
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-0">
                    <div class="d-flex align-items-center collapsible py-3 toggle collapsed mb-0" data-bs-toggle="collapse" data-bs-target="#view_datos_cliente">
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
                        <h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">Datos Cliente</h4>
                    </div>
                </div>
                <div id="view_datos_cliente" class="collapse fs-6 ms-1">
                    <div class="card-body pt-2">
                        <div class="d-flex flex-column gap-10">
                            <div class="d-flex align-items-center">							
                                <i class="fa fa-building fa-2x me-5" style="color:#55C4F4;" aria-hidden="true"></i>
                                <div class="d-flex flex-column">
                                    <h5 class="text-gray-800 fw-bolder">Empresa</h5>
                                    <label class="text-gray-700"><?php echo $Nombre; ?></label>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fa fa-envelope fa-2x me-5" style="color:#55C4F4;" aria-hidden="true"></i>
                                <div class="d-flex flex-column">
                                    <h5 class="text-gray-800 fw-bolder">Email</h5>
                                    <label class="text-gray-700"><?php echo $Email1; ?></label>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">							
                                <i class="fa fa-phone fa-2x me-5" style="color:#55C4F4;" aria-hidden="true"></i>
                                <div class="d-flex flex-column">
                                    <h5 class="text-gray-800 fw-bolder">Telefonos</h5>
                                    <div class="text-gray-600">Telefono 1:&nbsp;<?php echo $Telefono1; ?></div>
                                    <div class="text-gray-600">Telefono 2:&nbsp;<?php echo $Telefono2; ?></div>
                                    <div class="text-gray-600">Celular 1:&nbsp;<?php echo $Celular1; ?></div>
                                    <div class="text-gray-600">Celular 2:&nbsp;<?php echo $Celular2; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-0">
                    <div class="d-flex align-items-center collapsible py-3 toggle collapsed mb-0" data-bs-toggle="collapse" data-bs-target="#view_datos_producto">
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
                        <h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">Datos Producto</h4>
                    </div>
                </div>
                <div id="view_datos_producto" class="collapse fs-6 ms-1">
                    <div class="card-body pt-2">
                        <div class="d-flex flex-column gap-10">
                            <div class="d-flex align-items-center">
                                <i class="fa fa-users fa-2x me-5" style="color:#55C4F4;" aria-hidden="true"></i>
                                <div class="d-flex flex-column">
                                    <h5 class="text-gray-800 fw-bolder">Grupo</h5>
                                    <label class="text-gray-700 text-uppercase"><?php echo $NomGrupo; ?></label>  
                                </div>
                            </div>
                            <div class="d-flex align-items-center">							
                                <i class="fa fa-briefcase fs-2x me-5" style="color:#55C4F4;" aria-hidden="true"></i>
                                <div class="d-flex flex-column">
                                    <h5 class="text-gray-800 fw-bolder">Producto</h5>
                                    <label class="text-gray-700 text-uppercase"><?php echo $NomProd; ?></label>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fa fa-university fa-2x me-5" style="color:#55C4F4;" aria-hidden="true"></i>
                                <div class="d-flex flex-column">
                                    <h5 class="text-gray-800 fw-bolder">Costo</h5>
                                    <label class="text-gray-700 text-uppercase">$<?php echo $Costo; ?></label>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fa fa-globe fa-2x me-5" style="color:#55C4F4;" aria-hidden="true"></i>
                                <div class="d-flex flex-column">
                                    <h5 class="text-gray-800 fw-bolder">Cobertura</h5>
                                    <div class="fw-bold">
                                        <input <?php echo $xChekCober; ?> class="form-check-input" name="chkCobertura" id="chkCobertura" type="checkbox" />
                                        <label class="badge badge-light-primary"><?php echo $Cobertura; ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="fa fa-cogs fa-2x me-5" style="color:#55C4F4;" aria-hidden="true"></i>
                                <div class="d-flex flex-column">
                                    <h5 class="text-gray-800 fw-bolder">Sistema</h5>
                                    <div class="fw-bold">
                                        <input <?php echo $xChekSis; ?> class="form-check-input" name="chkCobertura" id="chkCobertura" type="checkbox" />
                                        <label class="badge badge-light-primary"><?php echo $Sistema; ?></label>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-0">
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
                    <div class="card-body pt-2">
                        <div class="d-grid gap-2">
                            <button type="button" id="btnNewParen" class="btn btn-light-primary btn-sm mb-2">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i>                                                               
                                Nuevo Parentesco
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-lg-row-fluid gap-7 gap-lg-10">
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-n4">     
                <div class="d-flex justify-content-start">
                    <button type="button" data-repeater-create="" class="btn btn-light-primary btn-sm mb-2" id="btnAgregartitu"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                        Agregar Titular
                    </button>
                </div>
                <button type="button" id="btnRegresar" class="btn btn-icon btn-light-primary btn-sm ms-auto me-lg-n7" title="Regresar" data-bs-toggle="tooltip" data-bs-placement="left">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </button>
            </ul>
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Titulares Agregados</h2>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex align-items-center position-relative mb-1">
                        <span class="svg-icon svg-icon-1 position-absolute ms-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                            </svg>
                        </span>
                        <input type="text" data-kt-ecommerce-edit-order-filter="search" class="form-control form-control-solid w-100 w-lg-50 ps-14" placeholder="Buscar Titular" />
                    </div>
                    <br>
                    <div class="scroll-y me-n7 pe-7" id="parametro_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#parametro_header" data-kt-scroll-wrappers="#parametro_scroll" data-kt-scroll-offset="300px">
                        <table class="table align-middle table-row-dashed table-hover fs-6 gy-5" id="kt_ecommerce_edit_order_product_table">
                            <thead>
                                <tr class="text-start text-gray-800 fw-bolder fs-7 text-uppercase gs-0">
                                    <th>Ciudad</th>
                                    <th class="min-w-125px">Nombres</th>
                                    <th>Estado</th>
                                    <th>Status</th>
                                    <th style="text-align: center;">Opciones</th>
                                </tr>
                            </thead>
                            <tbody class="fw-bold text-gray-600">
                                    <?php 
                                        foreach ($all_persona as $per){

                                        $xPerid = $per['PerId'];
                                        $xTituid = $per['Tituid'];
                                        $xNombres = $per['Nombres'];
                                        $xImagen = $per['Imagen'];
                                        $xEstado = $per['Estado'];
                                        $xProvid = $per['CiudadId'];

                                        if(  $xProvid != 0){

                                            $xSQL = "SELECT * FROM `provincia_ciudad` WHERE pais_id=$xPaisid AND prov_id=$xProvid ";
                                            $all_ciudad = mysqli_query($con, $xSQL);    
                                
                                        }
                                            
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

                                        $xSQL = "SELECT COUNT(*) AS Bene FROM `expert_beneficiario` WHERE titu_id=$xTituid ";
                                        $cont_bene = mysqli_query($con, $xSQL);
                                        foreach ($cont_bene as $ben){
                                            $xBene = $ben['Bene'];
                                        }
                                    ?> 
                                <tr>
                                    <?php
                                        foreach ($all_ciudad as $ciu){
                                            $xCiudad = trim(mb_strtoupper($ciu['ciudad']));
                                    ?>     
                                    <td>
                                    
                                    <?php echo $xCiudad; ?>

                                    </td>
                                    <?php }?>
                                    <td>
                                        <div class="d-flex align-items-center" data-kt-ecommerce-edit-order-filter="product" data-kt-ecommerce-edit-order-id="product_1">
                                            <a href="../../demo1/dist/apps/ecommerce/catalog/edit-product.html" class="symbol symbol-50px">
                                                <span class="symbol-label" style="background-image:url(persona/<?php echo $xImagen; ?>);"></span>
                                            </a>
                                            <div class="ms-5">
                                            <?php echo $xNombres; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="td_<?php echo $xPerid; ?>">   
                                        <div class="<?php echo $xTextColor; ?>">
                                        <?php echo $xEstado; ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input <?php echo $xCheking; ?> class="form-check-input h-20px w-20px border-primary" onchange="f_UpdateEstado(<?php echo $xPerid; ?>,<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>)" type="checkbox" id="chk<?php echo $xPerid; ?>" value=""/>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <div class="btn-group">	
                                                <button type="button" <?php echo $xDisabledEdit;?> id="btnEditar_<?php echo $xPerid; ?>" onclick="f_Editartitular(<?php echo $xPerid; ?>,<?php echo $xTituid; ?>,<?php echo $clieid; ?>,<?php echo $prodid; ?>,<?php echo $grupid; ?>)" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar"  title='Editar Titular (+<?php echo $xBene; ?>B )' data-bs-toggle="tooltip" data-bs-placement="left">
                                                    <i class="fa fa-edit"></i>
                                                </button> 
                                                <button type="button" <?php echo $xDisabledEdit;?> id="btnAgendar_<?php echo $xPerid; ?>" name="btnAgendar" onclick="f_Agendar(<?php echo $xTituid; ?>)" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"  title='Agendar' data-bs-toggle="tooltip" data-bs-placement="left">
                                                    <i class="fa fa-calendar-plus"></i>
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
    </form>
</div>
<!--Modal Nuevo Parentesco-->
<div class="modal fade" id="modal_new_paren" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-700px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Nuevo Parentesco</h2>
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
                        <div class="d-flex flex-column mb-4 fv-row">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                            <span class="required">Detalle</span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Especifique el nombre del detalle"></i>
                            </label>
                            <input type="text" class="form-control mb-2" id="txtDetalle" name="txtDetalle" minlength="2" maxlength="80" placeholder="nombre del detalle" value="" />
                        </div>
                        <div class="fv-row mb-2">
                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                            <span class="required">Valor Texto</span>
                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="valor texto ejemplo FFF"></i>
                            </label>
                            <input type="text" class="form-control" id="txtValorV" name="txtValorV" minlength="3" maxlength="3" placeholder="valor texto" value="" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger" data-bs-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i>Cerrar</button>
                <button type="button" id="btnGuardar" onclick="f_GuardarParen(<?php echo $xPacaid; ?>,<?php echo $xOrdenDet; ?>)" class="btn btn-sm btn-light-primary"><i class="las la-save"></i>Grabar</button>    
            </div>
        </div>
    </div>
</div>   
<!--Modal Crear Titular-->
<div class="modal fade" id="kt_modal_create_app" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Agregar Titular-Beneficiario</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body py-lg-10 px-lg-2">
                <!--begin::Stepper-->
                <div class="stepper stepper-pills flex-xl-row flex-row-fluid" id="kt_modal_create_app_stepper">
                    <div class="card mb-1 mb-xl-1">
                        <!--begin::Nav-->
                        <div class="stepper-nav ps-lg-5 mb-4">
                            <!--begin::Step 1-->
                            <div class="stepper-item current" data-kt-stepper-element="nav">
                                <div class="stepper-line w-40px"></div>
                                <div class="stepper-icon w-40px h-40px">
                                    <i class="stepper-check fas fa-check"></i>
                                    <span class="stepper-number">1</span>
                                </div>
                                <div class="stepper-label">
                                    <h3 class="stepper-title">Titular</h3>
                                </div>
                            </div>
                            <!--begin::Step 2-->
                            <div class="stepper-item" data-kt-stepper-element="nav">
                                <div class="stepper-line w-40px"></div>
                                <div class="stepper-icon w-40px h-40px">
                                    <i class="stepper-check fas fa-check"></i>
                                    <span class="stepper-number">2</span>
                                </div>
                                <div class="stepper-label">
                                    <h3 class="stepper-title">Beneficiario</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--begin::Content-->
                    <div class="card mb-1 mb-xl-1">
                        <form class="form" novalidate="novalidate" id="kt_modal_create_app_form">
                            <!--begin::Step 1-->
                            <div class="current" data-kt-stepper-element="content">
                                <div class="w-100">
                                    <div class="container-fluid">
                                        <div class="card mb-1 mb-xl-2">
                                            <div class="card-header border-0">
                                                <div class="d-flex align-items-center collapsible py-3 toggle collapsed mb-0" data-bs-toggle="collapse" data-bs-target="#view_avatar">
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
                                                    <h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">Avatar</h4>
                                                </div>
                                            </div>
                                            <div id="view_avatar" class="collapse fs-6 ms-1">
                                                <div class="card card-flush py-2">
                                                    <div class="card-body pt-0">
                                                        <div class="image-input image-input-empty image-input-outline mb-3" data-kt-image-input="true">
                                                            <div class="image-input-wrapper w-150px h-150px" id="imgAvatar" style="background-image: url(assets/media/svg/avatars/Addimg.svg);"></div>
                                                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Cargar foto">
                                                                <i class="bi bi-pencil-fill fs-7"></i>    
                                                                <input type="file" id="imgTitular" accept=".png, .jpg, .jpeg" />
                                                                <input type="hidden" name="avatar_remove" />
                                                            </label>
                                                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancelar foto">
                                                                <i class="bi bi-x fs-2"></i>
                                                            </span>
                                                        </div>
                                                        <div class="text-muted fs-7">Imagenes aceptadas (*jpg,*.png y *.jpeg) </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-1 mb-xl-2">
                                            <div class="card-header border-0">
                                                <div class="d-flex align-items-center collapsible py-1 toggle mb-0" data-bs-toggle="collapse" data-bs-target="#view_datos_titular">														<!--begin::Icon-->
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
                                                    <h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">Datos Titular</h4>
                                                </div>
                                            </div>
                                            <div id="view_datos_titular" class="collapse show fs-6 ms-1">
                                                <div class="card card-flush py-1">
                                                    <div class="card-body pt-0">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label class="required form-label">Provincia</label>
                                                                <select name="cboProvincia" id="cboProvincia" aria-label="Seleccione Provincia" data-control="select2" data-placeholder="Seleccione Provincia" data-dropdown-parent="#view_datos_titular" class="form-select mb-2" >
                                                                        <option></option>
                                                                        <?php foreach ($all_provincia as $prov) : ?>
                                                                            <option value="<?php echo $prov['Descripcion'] ?>"><?php echo mb_strtoupper($prov['Descripcion']) ?></option>
                                                                        <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="required form-label">Ciudad</label>
                                                                <select id="cboCiudad" aria-label="Seleccione Ciudad" data-control="select2" data-placeholder="Seleccione Ciudad" data-dropdown-parent="#view_datos_titular" class="form-select mb-2">
                                                                        <option></option>
                                                                </select>    
                                                            </div>  
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-5">
                                                                <label class="required form-label">Tipo Documento</label>
                                                                <select class="form-select mb-2" id="cboDocumento" data-control="select2" data-hide-search="true" data-placeholder="Seleccione Tipo Documento">
                                                                    <option></option>
                                                                    <?php
                                                                        $xSQL = "SELECT pde.pade_valorV AS Codigo,UPPER(pde.pade_nombre) AS Descripcion FROM `expert_parametro_detalle` pde,`expert_parametro_cabecera` pca ";
                                                                        $xSQL .="WHERE pca.pais_id=$xPaisid AND pca.paca_nombre='Tipo Documento' AND pca.paca_id=pde.paca_id AND pca.paca_estado='A' AND pade_estado='A' ";
                                                                        $all_datos =  mysqli_query($con, $xSQL);
                                                                        foreach($all_datos as $datos){?>
                                                                        <option value="<?php echo $datos['Codigo'] ?>"<?php if($datos == 'Cedula') 'selected="selected"' ?>><?php echo $datos['Descripcion'] ?></option>
                                                                    <?php }?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="required form-label">Nro. Documento</label>
                                                                <input type="text" id="txtDocumento" class="form-control mb-2" value="" minlength="10" maxlength="13" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;"  />
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="required form-label">Genero</label>
                                                                <select class="form-select mb-2" id="cboGenero" data-control="select2" data-hide-search="true" data-placeholder="Seleccione Genero">
                                                                    <option></option>
                                                                    <?php
                                                                        $xSQL = "SELECT pde.pade_valorV AS Codigo,UPPER(pde.pade_nombre) AS Descripcion FROM `expert_parametro_detalle` pde,`expert_parametro_cabecera` pca ";
                                                                        $xSQL .="WHERE pca.pais_id=$xPaisid AND pca.paca_nombre='Tipo Genero' AND pca.paca_id=pde.paca_id AND pca.paca_estado='A' AND pade_estado='A' ";
                                                                        $all_datos =  mysqli_query($con, $xSQL);
                                                                        foreach($all_datos as $datos){?>
                                                                        <option value="<?php echo $datos['Codigo'] ?>"><?php echo $datos['Descripcion'] ?></option>
                                                                    <?php }?> 
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-4">
                                                                <label class="required form-label">Nombres</label>
                                                                <input type="text" id="txtNombre" class="form-control mb-2" maxlength="80" placeholder="Ingrese Nombres" />
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="required form-label">Apellidos</label>
                                                                <input type="text" id="txtApellido" class="form-control mb-2" maxlength="80" placeholder="Ingrese Apellidos" />
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">Fecha de Nacimiento</label>
                                                                <input type="date" id="txtFechaNacimiento" class="form-control mb-2" value="" />
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <label class="form-label">Estado Civil</label>
                                                                <select class="form-select mb-2" id="cboEstadoCivil" data-control="select2" data-hide-search="true" data-placeholder="Seleccione Estado Civil">
                                                                    <option></option>
                                                                    <?php
                                                                        $xSQL = "SELECT pde.pade_valorV AS Codigo,UPPER(pde.pade_nombre) AS Descripcion FROM `expert_parametro_detalle` pde,`expert_parametro_cabecera` pca ";
                                                                        $xSQL .="WHERE pca.pais_id=$xPaisid AND pca.paca_nombre='Estado Civil' AND pca.paca_id=pde.paca_id AND pca.paca_estado='A' AND pade_estado='A' ORDER BY pde.pade_nombre ";
                                                                        $all_datos =  mysqli_query($con, $xSQL);
                                                                        foreach($all_datos as $datos){?>
                                                                        <option value="<?php echo $datos['Codigo'] ?>"><?php echo $datos['Descripcion'] ?></option>
                                                                    <?php }?>                   
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">Inicio Cobertura</label>
                                                                <input type="date" id="txtIniCobertura" class="form-control mb-2" />
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">Fin Cobertura</label>
                                                                <input type="date" id="txtFinCobertura" class="form-control mb-2" />
                                                            </div>
                                                        </div>  
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-4 mb-xl-2">
                                            <div class="card card-flush py-1">
                                                <div class="card-body pt-0">
                                                    <div class="py-0" data-kt-customer-payment-method="row">
                                                        <div class="py-3 d-flex flex-stack flex-wrap">
                                                            <div class="d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#direccion_profesional" role="button" aria-expanded="false" aria-controls="direccion_profesional">
                                                                <div class="me-3 rotate-90">
                                                                    <i class="fa fa-chevron-circle-right" style="color:#5AD1F1;" aria-hidden="true"></i>
                                                                </div>
                                                                <i class="fa fa-location-arrow fa-1x me-2" style="color:#F46D55;" aria-hidden="true"></i>
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
                                                                    <textarea class="form-control mb-2" id="txtDireccion" maxlength="250" rows="1" onkeydown="return(event.keyCode!=13);"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="py-0" data-kt-customer-payment-method="row">
                                                        <div class="py-3 d-flex flex-stack flex-wrap">
                                                            <div class="d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#telefono_profesional" role="button" aria-expanded="false" aria-controls="telefono_profesional">
                                                                <div class="me-3 rotate-90">
                                                                    <i class="fa fa-chevron-circle-right" style="color:#5AD1F1;" aria-hidden="true"></i>
                                                                </div>
                                                                <i class="fa fa-phone fa-1x me-2" style="color:#7DF57D;" aria-hidden="true"></i>
                                                                <div class="me-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="text-gray-800 fw-bolder">Telefonos</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="telefono_profesional" class="collapse fs-6 ps-10" data-bs-parent="#datos_profesional">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="fs-6 fw-bold mt-3 mb-3">Telefono 1</div>
                                                                    <input type="text" id="txtTelCasa" class="form-control mb-2 col-md-1" value="" placeholder="022222222" maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="fs-6 fw-bold mt-3 mb-3">Telefono 2</div>
                                                                    <input type="text" id="txtTelOfi" class="form-control mb-2 col-md-1" value="" placeholder="022222222" maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;"/>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="fs-6 fw-bold mt-3 mb-3">Celular</div>
                                                                    <input type="text" id="txtCelular" class="form-control mb-2 col-md-1" value="" placeholder="0999999999" maxlength="10" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="py-0" data-kt-customer-payment-method="row">
                                                        <div class="py-3 d-flex flex-stack flex-wrap">
                                                            <div class="d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#email_profesional" role="button" aria-expanded="false" aria-controls="email_profesional">
                                                                <div class="me-3 rotate-90">
                                                                    <i class="fa fa-chevron-circle-right" style="color:#5AD1F1;" aria-hidden="true"></i>
                                                                </div>
                                                                <i class="fa fa-envelope fa-1x me-2" style="color:#3B8CEC;" aria-hidden="true"></i>
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
                                                                    <input type="email" id="txtEmail" class="form-control mb-2 col-md-1 text-lowercase" value="" placeholder="micorreo@gmail.com" maxlength="80" />
                                                                </div>                                                   
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-1 mb-xl-1 mt-10">
                                            <div class="card-header border-0">
                                                <div class="fv-row">
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                        <h5 class="form-check-label mx-3 fw-light text-primary fst-italic">Desea Agregar un Beneficiario..?</h5>
                                                        <input class="form-check-input" type="checkbox" id="chkCambiar" name="category" value="1" />
                                                        <h5 class="form-check-label" id="lblTexto"></h5>
                                                    </label> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--begin::Step 2-->
                            <div data-kt-stepper-element="content">
                                <div class="w-100">
                                    <div class="container-fluid">
                                        <div class="card mb-1 mb-xl-2">
                                            <div class="card-header border-0">
                                                <div class="d-flex align-items-center collapsible py-3 toggle mb-0" data-bs-toggle="collapse" data-bs-target="#view_datos_beneficiario">														<!--begin::Icon-->
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
                                                    <h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">Datos Beneficiario</h4>
                                                </div>
                                            </div>
                                            <div id="view_datos_beneficiario" class="collapse show fs-6 ms-1">
                                                <div class="card card-flush py-1">
                                                    <div class="card-body pt-0">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label class="required form-label">Provincia</label>
                                                                <select  id="cboProvinciaBe" aria-label="Seleccione Provincia" data-control="select2" data-placeholder="Seleccione Provincia" data-dropdown-parent="#view_datos_beneficiario" class="form-select mb-2" >
                                                                        <option></option>
                                                                        <?php foreach ($all_provincia as $prov) : ?>
                                                                            <option value="<?php echo $prov['Descripcion'] ?>"><?php echo mb_strtoupper($prov['Descripcion']) ?></option>
                                                                        <?php endforeach ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="required form-label">Ciudad</label>
                                                                <select id="cboCiudadBe" aria-label="Seleccione Ciudad" data-control="select2" data-placeholder="Seleccione Ciudad" data-dropdown-parent="#view_datos_beneficiario" class="form-select mb-2">
                                                                        <option></option>
                                                                </select>  
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-4">
                                                                <label class="required form-label">Nombres</label>
                                                                <input type="text" class="form-control mb-2" id="txtNombreBe" value="" style="text-transform: uppercase;" maxlength="80" placeholder="Ingrese Nombres" />
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="required form-label">Apellidos</label>
                                                                <input type="text" class="form-control mb-2" id="txtApellidoBe" value="" style="text-transform: uppercase;" maxlength="80" placeholder="Ingrese Apellidos" />
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label">Fecha de Nacimiento</label>
                                                                <input type="date" id="txtFechaNacimientoBe" class="form-control mb-2" value="" />
                                                            </div>
                                                        </div>
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label class="required form-label">Parentesco</label>
                                                                <select class="form-select mb-2" id="cboParentesco" data-control="select2" data-hide-search="true" data-placeholder="Seleccione Parentesco">
                                                                    <option></option>
                                                                    <?php
                                                                        $xSQL = "SELECT pde.pade_valorV AS Codigo,UPPER(pde.pade_nombre) AS Descripcion FROM `expert_parametro_detalle` pde,`expert_parametro_cabecera` pca ";
                                                                        $xSQL .="WHERE pca.pais_id=$xPaisid AND pca.paca_nombre='Parentesco' AND pca.paca_id=pde.paca_id AND pca.paca_estado='A' AND pade_estado='A' ORDER BY pde.pade_nombre ";
                                                                        $all_datos =  mysqli_query($con, $xSQL);
                                                                        foreach($all_datos as $datos){?>
                                                                        <option value="<?php echo $datos['Codigo'] ?>"><?php echo $datos['Descripcion'] ?></option>
                                                                    <?php }?>                   
                                                                </select>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="required form-label">Tipo Documento</label>
                                                                <select class="form-select mb-2" id="cboDocumentoBe" data-control="select2" data-hide-search="true" data-placeholder="Seleccione Tipo Documento">
                                                                    <option></option>
                                                                    <?php
                                                                        $xSQL = "SELECT pde.pade_valorV AS Codigo,UPPER(pde.pade_nombre) AS Descripcion FROM `expert_parametro_detalle` pde,`expert_parametro_cabecera` pca ";
                                                                        $xSQL .="WHERE pca.pais_id=$xPaisid AND pca.paca_nombre='Tipo Documento' AND pca.paca_id=pde.paca_id AND pca.paca_estado='A' AND pade_estado='A' ";
                                                                        $all_datos =  mysqli_query($con, $xSQL);
                                                                    foreach($all_datos as $datos){?>
                                                                    <option value="<?php echo $datos['Codigo'] ?>"<?php if($datos == 'Cedula') 'selected="selected"' ?>><?php echo $datos['Descripcion'] ?></option>
                                                                    <?php }?>
                                                                </select>
                                                            </div> 
                                                        </div>
                                                        <div class="row mb-1">
                                                            <div class="col-md-3">
                                                                <label class="required form-label">Nro. Documento</label>
                                                                <input type="text" class="form-control mb-2" id="txtDocumentoBe" value="" maxlength="13" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="required form-label">Genero</label>
                                                                <select class="form-select mb-2" id="cboGeneroBe" data-control="select2" data-hide-search="true" data-placeholder="Seleccione Genero">
                                                                    <option></option>
                                                                    <?php
                                                                        $xSQL = "SELECT pde.pade_valorV AS Codigo,UPPER(pde.pade_nombre) AS Descripcion FROM `expert_parametro_detalle` pde,`expert_parametro_cabecera` pca ";
                                                                        $xSQL .="WHERE pca.pais_id=$xPaisid AND pca.paca_nombre='Tipo Genero' AND pca.paca_id=pde.paca_id AND pca.paca_estado='A' AND pade_estado='A' ";
                                                                        $all_datos =  mysqli_query($con, $xSQL);
                                                                        foreach($all_datos as $datos){?>
                                                                        <option value="<?php echo $datos['Codigo'] ?>"><?php echo $datos['Descripcion'] ?></option>
                                                                    <?php }?> 
                                                                </select>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <label class="form-label">Estado Civil</label>
                                                                <select class="form-select mb-2" id="cboEstadoCivilBe" data-control="select2" data-hide-search="true" data-placeholder="Seleccione Estado Civil">
                                                                    <option></option>
                                                                    <?php
                                                                        $xSQL = "SELECT pde.pade_valorV AS Codigo,UPPER(pde.pade_nombre) AS Descripcion FROM `expert_parametro_detalle` pde,`expert_parametro_cabecera` pca ";
                                                                        $xSQL .="WHERE pca.pais_id=$xPaisid AND pca.paca_nombre='Estado Civil' AND pca.paca_id=pde.paca_id AND pca.paca_estado='A' AND pade_estado='A' ORDER BY pde.pade_nombre ";
                                                                        $all_datos =  mysqli_query($con, $xSQL);
                                                                        foreach($all_datos as $datos){?>
                                                                        <option value="<?php echo $datos['Codigo'] ?>"><?php echo $datos['Descripcion'] ?></option>
                                                                    <?php }?>                   
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div> 
                                               </div>
                                            </div>
                                        </div>
                                        <div class="card mb-1 mb-xl-1">
                                            <div class="card card-flush py-2">
                                                <div class="card-body pt-0">
                                                    <div class="py-0" data-kt-customer-payment-method="row">
                                                        <div class="py-3 d-flex flex-stack flex-wrap">
                                                            <div class="d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#direccion_profesional" role="button" aria-expanded="false" aria-controls="direccion_profesional">
                                                                <div class="me-3 rotate-90">
                                                                    <i class="fa fa-chevron-circle-right" style="color:#5AD1F1;" aria-hidden="true"></i>
                                                                </div>
                                                                <i class="fa fa-location-arrow fa-1x me-2" style="color:#F46D55;" aria-hidden="true"></i>
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
                                                                    <textarea class="form-control mb-2" id="txtDireccionBe" style="text-transform: uppercase;" rows="1" onkeydown="return(event.keyCode!=13);"></textarea> 
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="py-0" data-kt-customer-payment-method="row">
                                                        <div class="py-3 d-flex flex-stack flex-wrap">
                                                            <div class="d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#telefono_profesional" role="button" aria-expanded="false" aria-controls="telefono_profesional">
                                                                <div class="me-3 rotate-90">
                                                                    <i class="fa fa-chevron-circle-right" style="color:#5AD1F1;" aria-hidden="true"></i>
                                                                </div>
                                                                <i class="fa fa-phone fa-1x me-2" style="color:#7DF57D;" aria-hidden="true"></i>
                                                                <div class="me-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="text-gray-800 fw-bolder">Telefonos</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="telefono_profesional" class="collapse fs-6 ps-10" data-bs-parent="#datos_profesional">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="fs-6 fw-bold mt-3 mb-3">Telefono 1</div>
                                                                    <input type="text" id="txtTelCasaBe" class="form-control mb-2 col-md-1" value="" placeholder="022222222" maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;"/>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="fs-6 fw-bold mt-3 mb-3">Telefono 2</div>
                                                                    <input type="text" id="txtTelOfiBe" class="form-control mb-2 col-md-1" value="" placeholder="022222222" maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;"/>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="fs-6 fw-bold mt-3 mb-3">Celular</div>
                                                                    <input type="text" id="txtCelularBe" class="form-control mb-2 col-md-1" value="" placeholder="0999999999" maxlength="10" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="py-0" data-kt-customer-payment-method="row">
                                                        <div class="py-3 d-flex flex-stack flex-wrap">
                                                            <div class="d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#email_profesional" role="button" aria-expanded="false" aria-controls="email_profesional">
                                                                <div class="me-3 rotate-90">
                                                                    <i class="fa fa-chevron-circle-right" style="color:#5AD1F1;" aria-hidden="true"></i>
                                                                </div>
                                                                <i class="fa fa-envelope fa-1x me-2" style="color:#3B8CEC;" aria-hidden="true"></i>
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
                                                                    <input type="email" id="txtEmailBe" class="form-control mb-2 col-md-1 text-lowercase" value="" placeholder="mi@gmail.com" minlength="1" maxlength="80" />
                                                                </div>                                                   
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mb-1 mb-xl-1">
                                            <div class="card-header border-0">
                                                <div class="form-group mt-5 mb-4">
                                                    <button type="button" data-repeater-create="" class="btn btn-light-primary btn-sm mb-2" id="btnAgregar"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                                                        Agregar Beneficiario
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card card-flush py-2">
                                                <div class="card-body pt-0">
                                                    <table class="table align-middle table-row-dashed table-hover fs-6 gy-5" id="tblBeneficiario">
                                                        <thead>
                                                            <tr class="text-start text-gray-800 fw-bolder fs-7 text-uppercase gs-0">
                                                                <th>Ciudad</th>
                                                                <th>Nombres</th>
                                                                <th>Parentesco</th>
                                                                <th class="text-center">Opciones</th>
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
                            <!--begin::Actions botones-->
                            <div class="card card-flush py-2">
                                <div class="card-body pt-0">
                                    <div class="d-flex flex-stack pt-10">
                                        <div class="me-2">
                                            <button type="button" class="btn btn-light-primary btn-sm mb-2" data-kt-stepper-action="previous" title="Regresar" data-bs-toggle="tooltip" data-bs-placement="right">
                                               <i class="fa fa-reply fa-2x" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-light-danger" data-bs-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i>Cerrar
                                            </button>
                                            <button type="button" id="btnGrabar" class="btn btn-sm btn-light-primary"><i class="las la-save"></i>
                                                <span class="indicator-label">Grabar</span>
                                            </button>
                                            <button type="button" id="continuar" class="btn btn-sm btn-light-primary" data-kt-stepper-action="next"  title="Continuar" data-bs-toggle="tooltip" data-bs-placement="right">
                                                <i class="fa fa-share fa-2x" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    var _count =0,_prodid = '<?php echo $prodid; ?>', _grupid = '<?php echo $grupid; ?>', _userid = '<?php echo $xUsuaid; ?>',
        _idclie = '<?php echo $clieid; ?>',_paisid = '<?php echo $xPaisid; ?>',_emprid = '<?php echo $xEmprid; ?>',_result = [];
    
    $(document).ready(function(){

        var _mensaje = $('input#mensaje').val();

        if(_mensaje != ''){
            //mensajesalertify(_mensaje,"S","top-center",3);
            mensajesweetalert('top-center','success',_mensaje,false,3000);

        }

        $('#cboProvincia').change(function(){
                    
            _cboid = $(this).val(); //obtener el id seleccionado
            
            $("#cboCiudad").empty();
    
            var _parametros = {
                xxPaisid: _paisid,
                xxEmprid: _emprid,
                xxComboid: _cboid,
                xxOpcion: 0
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

        //Datos Provincia para  Beneficiario
        $('#cboProvinciaBe').change(function(){
                                  
            _cboid = $(this).val(); //obtener el id seleccionado
            
            $("#cboCiudadBe").empty();


            var _parametros = {
                xxPaisid: _paisid,
                xxEmprid: _emprid,
                xxComboid: _cboid,
                xxOpcion: 0
            }

            var xrespuesta = $.post("codephp/cargar_combos.php", _parametros);
                xrespuesta.done(function(response) {
            
                $("#cboCiudadBe").html(response);
                
            });
            xrespuesta.fail(function() {
                
            });
            xrespuesta.always(function() {
                
            });                
        
        });
    });


   //Abrir Modal Parentesco
    $("#btnNewParen").click(function(){

      $("#modal_new_paren").modal("show");
    });

     //Abrir Modal Agregar-Titular
    $("#btnAgregartitu").click(function(){
 
        $("#kt_modal_create_app").find('input,textarea').val('').end();

        $('#cboDocumento').val(null).trigger('change');
        $('#cboGenero').val(null).trigger('change');
        $('#cboEstadoCivil').val(null).trigger('change');
        $('#cboProvincia').val(null).trigger('change');

        $('#cboParentesco').val(null).trigger('change');
        $('#cboDocumentoBe').val(null).trigger('change');
        $('#cboGeneroBe').val(null).trigger('change');
        $('#cboEstadoCivilBe').val(null).trigger('change');
        $('#cboProvinciaBe').val(null).trigger('change');

        $('#chkCambiar').prop('checked',false);

        _ocultar = document.getElementById('continuar');
        _ocultar.classList.add('d-none');

        $("#lblTexto").html('NO');
        
        var _fechainicob = '<?php echo $xFechaActual; ?>';
        $('#txtIniCobertura').val(_fechainicob);

        var _fechafincob = '<?php echo $xFechaFinCobertura; ?>';
        $('#txtFinCobertura').val(_fechafincob);
        
        
        $("#kt_modal_create_app").modal("show");

    });

    //Checkbox para continuar y agregar Beneficiario

    $(document).on("click","#chkCambiar",function(){
       
        if($("#chkCambiar").is(":checked")){
  
            $("#lblTexto").html('SI');
            _ocultar.classList.remove('d-none');           
            
        }else{
            $("#lblTexto").html('NO');
            _ocultar.classList.add('d-none');
        }   
    });


    //Desplazar Modales
    $("#modal_new_paren").draggable({
        handle: ".modal-header"
    });

    $("#kt_modal_create_app").draggable({
        handle: ".modal-header"
    });


    //Gravar Parentesco Modal

    function f_GuardarParen(_idpaca,_ordet){

        if($.trim($('#txtDetalle').val()) == '')
        {           
            mensajesalertify('Ingrese Detalle..!', 'W', 'top-right', 3);
            return false;          
        }

        if($.trim($('#txtValorV').val()) == '')
        {    
            mensajesalertify('Ingrese Valor Texto..!', 'W', 'top-right', 3);
            return false;
        }

     
        var _detalle = $.trim($('#txtDetalle').val());
        var _valorV =  $.trim($('#txtValorV').val());

        var _parametro ={
            "xxPacaid" : _idpaca,
            "xxDetalle" : _detalle,
            "xxValorV" : _valorV,
        }

        var xrespuesta = $.post("codephp/consultar_newdetalle.php", _parametro);
        xrespuesta.done(function(response){


            if(response == 0){

                _ordet++;

                var _parametros ={
                    "xxPacaid" : _idpaca,
                    "xxPaisid" : _paisid,
                    "xxOrden" : _ordet,
                    "xxDetalle" : _detalle,
                    "xxValorV" : _valorV
                }

                var xrespuesta = $.post("codephp/grabar_newdetalle.php", _parametros);
                xrespuesta.done(function(response){

                    if(response.trim() != 'ERR'){

                        mensajesalertify('Nuevo Parentesco Agregado', 'S', 'top-center', 3); 

                        $("#txtDetalle").val("");
                        $("#txtValorV").val("");
                        $("#cboParentesco").empty();
                        $("#cboParentesco").html(response);      
                        $("#modal_new_paren").modal("hide");

                    }

                });

            }else{

                mensajesalertify('Parentesco ya Existe y/o Valor texto', 'W', 'top-right', 3);
                $("#txtDetalle").val("");
                $("#txtValorV").val("");
            }

        });
    }

    //Agendar Titular
    function f_Agendar(_tituid){

        $.redirect('?page=adminagenda&menuid=<?php echo $menuid; ?>', { 'clieid': _idclie, 'tituid': _tituid, 'prodid': _prodid, 'grupid': _grupid, 'agendaid': 0 });
    }    

    //Boton regresar pagina metodo POST

    $('#btnRegresar').click(function(){

        $.redirect('?page=editcliente&menuid=<?php echo $menuid; ?>', {
        'idclie': _idclie
        });

    });


    //Agregar Beneficiario - Titular

    $('#btnAgregar').click(function(){
       
        var _continuar = true;
        var _cboDocumentoBe = $('#cboDocumentoBe').val();
        var _txtDocumentoBe = $('#txtDocumentoBe').val();
        var _txtNombreBe = $.trim($("#txtNombreBe").val());
        var _txtApellidoBe =  $.trim($('#txtApellidoBe').val());
        var _txtnombresCompletos =  _txtNombreBe.toUpperCase() + ' ' + _txtApellidoBe.toUpperCase();
        var _cboGeneroBe = $('#cboGeneroBe').val();
        var _cboEstadoCivilBe = $('#cboEstadoCivilBe').val(); 
        var _cboProvinciaBe = $('#cboProvinciaBe').val();
        var _cboCiudadBe = $('#cboCiudadBe').val();
        var _txtCiudadBe = $('#cboCiudadBe').find('option:selected').text();
            _txtCiudadBe.toUpperCase();
        var _txtDireccionBe =  $.trim($('#txtDireccionBe').val());
        var _txtTelCasaBe = $('#txtTelCasaBe').val();
        var _txtTelOfiBe = $('#txtTelOfiBe').val();
        var _txtTelCelularBe = $('#txtCelularBe').val();
        var _txtEmailBe =  $.trim($('#txtEmailBe').val());
        var _cboParentesco = $('#cboParentesco').val();
        var _txtParentesco = $('#cboParentesco').find('option:selected').text();
            _txtParentesco.toUpperCase();
        var _fechaNacimientoBe = $('#txtFechaNacimientoBe').val();

        if(_cboParentesco == ''){
            mensajesalertify("Seleccione Parentesco Beneficiario..!", "W", "top-right", 3);
            return; 
        }

        if(_cboDocumentoBe == ''){
            mensajesalertify("Seleccione Tipo Documento Beneficiario..!", "W", "top-right", 3);
            return; 
        }

        if(_txtDocumentoBe == ''){
            mensajesalertify("Ingrese Numero de Documento Beneficiario..!", "W", "top-right", 3);
            return; 
        }

        if(_txtDocumentoBe.length < 10){
            mensajesalertify("Documento Incorrecto Beneficiario..!", "W", "top-right", 3);
            return; 
        }

        if(_cboGeneroBe == ''){
            mensajesalertify("Seleccione Genero Beneficiario..!", "W", "top-right", 3);
            return; 
        }

        if(_txtNombreBe == ''){
            mensajesalertify("Ingrese Nombre Beneficiario..!", "W", "top-right", 3);
            return; 
        }

        if(_txtApellidoBe == ''){
            mensajesalertify("Ingrese Apellido Beneficiario..!", "W", "top-right", 3);
            return; 
        }


        if(_cboProvinciaBe == ''){
            mensajesalertify("Seleccione Provincia Beneficiario..!!","W","top-right",3);
            return false;
        }

        if(_cboCiudadBe == 0){
            mensajesalertify("Seleccione Ciudad Beneficiario..!!","W","top-right",3);
            return false;
        }

        if(_txtTelCelularBe != '')
        {
            _valor = document.getElementById("txtCelularBe").value;
            if( !(/^\d{10}$/.test(_valor)) ) {
                mensajesalertify("Celular Incorrecto Beneficiario..!" ,"W", "top-right", 3); 
                return;
            }
        }
        
        if(_txtEmailBe != ''){
            var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
            if (regex.test(_txtEmailBe.trim())){
            }else{
                mensajesalertify("Email Incorrecto Beneficiario..!!","W","top-right",3);
                return false;
            }  
        }

      
        var _parametros = {
            
            xxProdid: _prodid,
            xxPaisid: _paisid,
            xxEmprid: _emprid,
            xxDocumento: _txtDocumentoBe
        }

        var xrespuesta = $.post("codephp/consultar_beneficiario.php", _parametros);
        xrespuesta.done(function(response){
            if(response == 0){

                $.each(_result,function(i,item){

                    if(item.arrydocumento == _txtDocumentoBe){
                            mensajesalertify("Beneficiario ya Existe..!!","W","top-right",3);
                            _continuar = false;
                            return false;
                    }else{
                        _continuar = true;
                    }

                });

                if(_continuar){

                    _count = _count + 1;
                    _output = '<tr id="row_' + _count + '">';
                    _output += '<td>' + _txtCiudadBe + ' <input type="hidden" id="txtCiudad' + _count + '" value="' + _txtCiudadBe + '" /></td>';
                    _output += '<td>' + _txtnombresCompletos + ' <input type="hidden" class="form-control mb-2 text-uppercase" id="txtDocumentoBe' + _count + '" value="' + _txtDocumentoBe + '" /></td>';
                    _output += '<td>' + _txtParentesco + ' <input type="hidden" class="form-control mb-2 text-uppercase" id="txtParentesco' + _count + '" value="' + _txtParentesco + '" /></td>';
                    _output += '<td>';
                    _output += '<button id="btnDelete' + _count + '" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1"  onclick="f_DelBeneficiario('+"'";
                    _output +=  _txtDocumentoBe + "'" + ',' + _count + ')"' + ' title="Eliminar Beneficiario" ><i class="fa fa-trash"></i></button></td>';
                    _output += '</tr>'; 


                    $('#tblBeneficiario').append(_output);
                    //console.log(_output);
                    var _objeto = {

                        arrytipodocumento: _cboDocumentoBe,
                        arrydocumento: _txtDocumentoBe,
                        arrynombre: _txtNombreBe,
                        arryapellido: _txtApellidoBe,
                        arrygenero: _cboGeneroBe,
                        arryestadocivil: _cboEstadoCivilBe,
                        arryciudad: _cboCiudadBe,
                        arrydireccion: _txtDireccionBe,
                        arrytelcasa: _txtTelCasaBe,
                        arrytelofi: _txtTelOfiBe,
                        arrycelular: _txtTelCelularBe,
                        arryemail: _txtEmailBe,
                        arryparentesco: _cboParentesco,
                        arryfechanacimiento: _fechaNacimientoBe
                    }

                    _result.push(_objeto);

                    $("#cboDocumentoBe").val('').change();
                    $("#txtDocumentoBe").val('');
                    $("#txtNombreBe").val('');
                    $("#txtApellidoBe").val('');
                    $("#cboGeneroBe").val('').change();
                    $("#cboEstadoCivilBe").val('').change();
                    $("#cboProvinciaBe").val('').change();
                    $("#cboCiudadBe").val(0).change();
                    $("#txtDireccionBe").val('');
                    $("#txtTelCasaBe").val('');
                    $("#txtTelOfiBe").val('');
                    $("#txtCelularBe").val('');
                    $("#txtEmailBe").val('');
                    $("#cboParentesco").val('').change();
                    $("#txtFechaNacimientoBe").val('');
        
                } 

            }else{
                mensajesalertify("Beneficiario ya Existe..!!","W","top-right",3);
                return false;
            }

        });

    });

    //Eliminar Beneficiario en linea

    function f_DelBeneficiario(_documento,_id){

        $('#row_' + _id + '').remove();
        _count--;
        
         $.each(_result,function(i,item){

            if(item.arrydocumento == _documento)
            {
                _result.splice(i, 1);
                return false;
            }else{
                continuar = true;
            }

        });      

    };


    //Agregar Titular -Beneficiario a la BDD

    $('#btnGrabar').click(function(){

        var _cboDocumento = $('#cboDocumento').val();
        var _txtDocumento = $('#txtDocumento').val();
        var _txtNombre = $.trim($("#txtNombre").val()); 
        var _txtApellido =  $.trim($('#txtApellido').val());
        var _cboGenero = $('#cboGenero').val();
        var _cboEstadoCivil = $('#cboEstadoCivil').val();
        var _fechaNacimiento = $('#txtFechaNacimiento').val();
        var _cboProvincia = $('#cboProvincia').val();
        var _cboCiudad = $('#cboCiudad').val();
        var _txtDireccion =  $.trim($('#txtDireccion').val());
        var _txtTelCasa = $('#txtTelCasa').val();
        var _txtTelOfi = $('#txtTelOfi').val();
        var _txtTelCelular = $('#txtCelular').val();
        var _txtEmail =  $.trim($('#txtEmail').val());
        var _fechaIniCobertura = $('#txtIniCobertura').val();
        var _fechaFinCobertura = $('#txtFinCobertura').val();

        //Imagen Titular
        var _imgfileTitu = document.getElementById("imgAvatar").style.backgroundImage;
        var _urlimgTitu = _imgfileTitu.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
        var _posTitu = _urlimgTitu.trim().indexOf('.');
        var _extTitu = _urlimgTitu.trim().substr(_posTitu, 5);

        if(_extTitu.trim() != '.svg'){
            var _imgTitu = document.getElementById("imgTitular");
            var _fileTitu = _imgTitu.files[0];
            var _fullPathTitu = document.getElementById('imgTitular').value;
            _extTitu = _fullPathTitu.substring(_fullPathTitu.length - 4);
            _extTitu = _extTitu.toLowerCase();

            if(_extTitu.trim() != '.png' && _extTitu.trim() != '.jpg' && _extTitu.trim() != 'jpeg'){
                mensajesalertify("El archivo seleccionado no es una Imagen..!", "W", "top-right", 3);
                return;
            }   
        }

        if(_cboDocumento == ''){
            mensajesalertify("Seleccione Tipo Documento Titular..!", "W", "top-right", 3);
            return; 
        }

        if(_txtDocumento == ''){
            mensajesalertify("Ingrese Numero de Documento Titular..!", "W", "top-right", 3);
            return; 
        }

        if(_txtDocumento.length < 10){
            mensajesalertify("Documento Incorrecto Titular ..!", "W", "top-right", 3);
            return; 
        }

        if(_cboGenero == ''){
            mensajesalertify("Seleccione Genero Titular..!", "W", "top-right", 3);
            return; 
        }

        if(_cboEstadoCivil == ''){
            mensajesalertify("Seleccione Estado Civil Titular..!", "W", "top-right", 3);
            return; 
        }


        if(_txtNombre == ''){
            mensajesalertify("Ingrese Nombre Titular..!", "W", "top-right", 3);
            return; 
        }

        if(_txtApellido == ''){
            mensajesalertify("Ingrese Apellido Titular..!", "W", "top-right", 3);
            return; 
        }


        if(_cboProvincia == ''){
            mensajesalertify("Seleccione Provincia Titular..!!","W","top-right",3);
            return false;
        }

        if(_cboCiudad == 0){
            mensajesalertify("Seleccione Ciudad Titular..!!","W","top-right",3);
            return false;
        }

        if(_txtTelCelular != '')
        {
            _valor = document.getElementById("txtCelular").value;
            if( !(/^\d{10}$/.test(_valor)) ) {
                mensajesalertify("Celular Incorrecto Titular..!" ,"W", "top-right", 3); 
                return;
            }
        }
        
        if(_txtEmail != ''){
            var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
            if (regex.test(_txtEmail.trim())){
            }else{
                mensajesalertify("Email Incorrecto Titular..!!","W","top-right",3);
                return false;
            }  
        }

        var _parametros = {

            xxProdid: _prodid,
            xxPaisid: _paisid,
            xxEmprid: _emprid,
            xxDocumento: _txtDocumento,

        }

        
        var xrespuesta = $.post("./codephp/consultar_persona.php",_parametros );
        xrespuesta.done(function(response){

            if(response == 0){

                var form_data = new FormData();
                form_data.append('xxUsuaid', _userid);
                form_data.append('xxProdid', _prodid);
                form_data.append('xxGrupid', _grupid);
                form_data.append('xxPaisid', _paisid);
                form_data.append('xxEmprid', _emprid);                 
                form_data.append('xxTipoDocumento', _cboDocumento);
                form_data.append('xxDocumento', _txtDocumento);
                form_data.append('xxNombre', _txtNombre);
                form_data.append('xxApellido', _txtApellido);
                form_data.append('xxGenero', _cboGenero);
                form_data.append('xxEstadoCivil', _cboEstadoCivil);
                form_data.append('xxFechaNacimiento', _fechaNacimiento);
                form_data.append('xxCiudadId', _cboCiudad);
                form_data.append('xxDireccion', _txtDireccion);
                form_data.append('xxTelCasa', _txtTelCasa);
                form_data.append('xxTelOfi', _txtTelOfi);
                form_data.append('xxCelular', _txtTelCelular);
                form_data.append('xxEmail', _txtEmail);
                form_data.append('xxFechaIniCobertura', _fechaIniCobertura);
                form_data.append('xxFechaFinCobertura', _fechaFinCobertura);
                form_data.append('xxImgTitu', _fileTitu);

                $.ajax({
                url: "./codephp/grabar_personatitular.php",
                type: "post",                
                data: form_data,
                processData: false,
                contentType: false,
                dataType: "json",
                    success: function(dataid){

                        if(dataid != 0){

                            if(_result.length > 0){
                                var xrespuesta = $.post("./codephp/grabar_beneficiariotitular.php", { xxTituid: dataid, xxUsuaid: _userid,xxPaisid: _paisid,xxEmprid: _emprid,xxProdid: _prodid,xxResult: _result });
                                    xrespuesta.done(function(response){
                                            
                                    if(response == 'OK'){

                                        $("#kt_modal_create_app").modal("hide");

                                        $.redirect('?page=addtitular&menuid=<?php echo $menuid; ?>', 
                                        {'mensaje': 'Grabado con Éxito..!',
                                          'idclie': _idclie,
                                          'idprod' : _prodid,
                                          'idgrup' : _grupid
                                        
                                        }); //POR METODO POST
                            
                                    }

                                });
                            }else{

                                $("#kt_modal_create_app").modal("hide");

                                $.redirect('?page=addtitular&menuid=<?php echo $menuid; ?>', 
                                {'mensaje': 'Grabado con Éxito..!',
                                    'idclie': _idclie,
                                    'idprod' : _prodid,
                                    'idgrup' : _grupid
                                }); //POR METODO POST

                            }
                        }

                    },
                    error: function (error) {
                        console.log(error);
                    }                                 

                });

            }else{
                mensajesalertify("Titular ya Existe..!!","W","top-right",3);
                return false;
            }

        }); 
        

    });
    
     //Cambiar estado persona

    function f_UpdateEstado(_perid,_paisid,_emprid){

        var _check = $("#chk" + _perid).is(":checked");
        var _checked = "";
		var _disabled = "";
        var _class = "badge badge-light-primary";
        var _td = "td_" + _perid;
        var _btnedit = "btnEditar_" + _perid;
        var _btnagen = "btnAgendar_" + _perid;

        if(_check){
            _estado = "ACTIVO";
            _disabled = "";
            _checked = "checked='checked'";
            $('#'+_btnedit).prop("disabled",false);
            $('#'+_btnagen).prop("disabled",false);
        }else{
            _estado = "INACTIVO";
            _disabled = "disabled";
            _class = "badge badge-light-danger";
            $('#'+_btnedit).prop("disabled",true);
            $('#'+_btnagen).prop("disabled",true);
        }

        var cambiar = document.getElementById(_td);
            cambiar.innerHTML = '<div class="' + _class + '">' + _estado + ' </div>';

        var _parametros = {
            "xxPerid" : _perid,
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxEstado" : _estado
        }

        var xrespuesta = $.post("codephp/delnew_persona.php", _parametros);
			xrespuesta.done(function(response){
		});	

    }


    function f_Editartitular(_perid,_tituid,_clieid,_prodid,_grupid){
        $.redirect('?page=edittitular&menuid=<?php echo $menuid; ?>', {
            'idper': _perid,
            'idtit': _tituid,
            'idcli': _clieid,
            'idpro': _prodid,
            'idgru': _grupid
		});
    
   }
 

</script>
					