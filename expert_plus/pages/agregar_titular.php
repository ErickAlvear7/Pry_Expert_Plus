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

    $xSQL = "SELECT DISTINCT provincia AS Descripcion FROM `provincia_ciudad` ";
	$xSQL .= "WHERE pais_id=$xPaisid AND estado='A' ORDER BY provincia ";
    $all_provincia = mysqli_query($con, $xSQL);

    $xSQL = "SELECT pst.pers_id AS PerId, tit.titu_id AS Tituid, CONCAT(pst.pers_nombres,' ',pst.pers_apellidos) AS Nombres,pst.pers_imagen AS  ";
    $xSQL .="Imagen,CASE pst.pers_estado WHEN 'A' THEN 'ACTIVO' ELSE 'INACTIVO' END AS Estado,pst.pers_ciudad AS CiudadId FROM `expert_persona` pst, `expert_titular` tit ";
    $xSQL .="WHERE tit.pais_id = $xPaisid AND tit.empr_id=$xEmprid AND pst.pers_id=tit.pers_id AND tit.prod_id=$prodid AND tit.grup_id=$grupid ORDER BY pst.pers_nombres ";
    $all_persona = mysqli_query($con, $xSQL);

    $xSQL ="SELECT clie_nombre AS Cliente,clie_email1 AS Email,clie_tel1 AS Telefono,clie_cel1 AS Celular FROM `expert_cliente` WHERE clie_id=$clieid AND pais_id=$xPaisid AND empr_id=$xEmprid ";
    $Cliente = mysqli_query($con, $xSQL);

    foreach($Cliente as $clie){
        $Nombre = $clie['Cliente'];
        $Email = $clie['Email'];
        $Telefono = $clie['Telefono'];
        $Celular = $clie['Celular'];
    }

    if($Email == ''){
        $Email = 'sinemail@gmail.com';
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

    $xSQL = "SELECT per.pers_id AS Perid, per.pers_numerodocumento AS Documento, CONCAT(per.pers_nombres,' ',per.pers_apellidos) AS Persona, per.pers_imagen AS Imagen, per.pers_estadocivil, ";
    $xSQL .= "per.pers_fechanacimiento AS Fecha, per.pers_ciudad AS Ciudad, per.pers_direccion AS Direccion, ";
    $xSQL .="";
    $xSQL .=" FROM `expert_persona` per,  `expert_titular` tit, `expert_beneficiario` ben WHERE "
?>
<div id="kt_content_container" class="container-xxl">
    <form id="kt_ecommerce_edit_order_form" class="form d-flex flex-column flex-lg-row" data-kt-redirect="../../demo1/dist/apps/ecommerce/sales/listing.html">
        <div class="w-100 flex-lg-row-auto w-lg-300px mb-7 me-7 me-lg-10">
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Titular</h2>
                    </div>    
                </div>
                <div class="card-body text-center pt-0">
                    <div class="image-input image-input-empty image-input-outline mb-3" data-kt-image-input="true">
                        <div class="image-input-wrapper w-150px h-150px" id="imgAvatar" style="background-image: url(assets/media/svg/avatars/blank.svg);"></div>
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
            <br>
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h3>Detalle Cliente</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex flex-column gap-10">
                        <div class="d-flex align-items-center">							
                            <i class="bi bi-filter-square text-primary fs-1 me-5"></i>
                            <div class="d-flex flex-column">
                                <h5 class="text-gray-800 fw-bolder">Empresa</h5>
                                <div class="fw-bold">
                                   <label><?php echo $Nombre; ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-envelope-check text-primary fs-1 me-5"></i>
                            <div class="d-flex flex-column">
                                <h5 class="text-gray-800 fw-bolder">Email</h5>
                                <div class="fw-bold">
                                    <a href="#" class="link-primary"><?php echo $Email; ?></a>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">							
                            <i class="bi bi-telephone-outbound text-primary fs-1 me-5"></i>
                            <div class="d-flex flex-column">
                                <h5 class="text-gray-800 fw-bolder">Telefonos</h5>
                                <div class="fw-bold">
                                   <label><?php echo $Telefono; ?> - <?php echo $Celular; ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-header">
                    <div class="card-title">
                        <h3>Detalle Producto</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex flex-column gap-10">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-briefcase-fill text-primary fs-1 me-5"></i>
                            <div class="d-flex flex-column">
                                <h5 class="text-gray-800 fw-bolder">Grupo</h5>
                                <div class="fw-bold">
                                    <label class="badge badge-light-success"><?php echo $NomGrupo; ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">							
                            <i class="bi bi-bag-plus text-primary fs-1 me-5"></i>
                            <div class="d-flex flex-column">
                                <h5 class="text-gray-800 fw-bolder">Producto</h5>
                                <div class="fw-bold">
                                  <label class="badge badge-light-success"><?php echo $NomProd; ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-bank2 text-primary fs-1 me-5"></i>
                            <div class="d-flex flex-column">
                                <h5 class="text-gray-800 fw-bolder">Costo</h5>
                                <div class="fw-bold">
                                   <label class="badge badge-light-success">$<?php echo $Costo; ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-globe2 text-primary fs-1 me-5"></i>
                            <div class="d-flex flex-column">
                                <h5 class="text-gray-800 fw-bolder">Cobertura</h5>
                                <div class="fw-bold">
                                   <input <?php echo $xChekCober; ?> class="form-check-input" name="chkCobertura" id="chkCobertura" type="checkbox" />
                                   <label class="badge badge-light-success"><?php echo $Cobertura; ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-grid-1x2-fill text-primary fs-1 me-5"></i>
                            <div class="d-flex flex-column">
                                <h5 class="text-gray-800 fw-bolder">Sistema</h5>
                                <div class="fw-bold">
                                   <input <?php echo $xChekSis; ?> class="form-check-input" name="chkCobertura" id="chkCobertura" type="checkbox" />
                                   <label class="badge badge-light-success"><?php echo $Sistema; ?></label>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-lg-row-fluid gap-7 gap-lg-10">
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-n2">
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_add_product_general">Titular</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#tab_beneficiarios">Beneficiario</a>
                </li>
                <button type="button" id="btnRegresar" class="btn btn-icon btn-light-primary btn-sm ms-auto me-lg-n7" title="Regresar" data-bs-toggle="tooltip" data-bs-placement="left">
                    <span class="svg-icon svg-icon-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M11.2657 11.4343L15.45 7.25C15.8642 6.83579 15.8642 6.16421 15.45 5.75C15.0358 5.33579 14.3642 5.33579 13.95 5.75L8.40712 11.2929C8.01659 11.6834 8.01659 12.3166 8.40712 12.7071L13.95 18.25C14.3642 18.6642 15.0358 18.6642 15.45 18.25C15.8642 17.8358 15.8642 17.1642 15.45 16.75L11.2657 12.5657C10.9533 12.2533 10.9533 11.7467 11.2657 11.4343Z" fill="currentColor" />
                        </svg>
                    </span>
                </button>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="kt_ecommerce_add_product_general" role="tab-panel">
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Datos Titular</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="d-flex flex-wrap gap-5">
                                    <div class="fv-row w-100 flex-md-root">
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
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="required form-label">Nro. Documento</label>
                                        <input type="text" id="txtDocumento" class="form-control mb-2" value="" minlength="10" maxlength="13" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;"  />
                                    </div>    
                                </div>
                                <div class="d-flex flex-wrap gap-5">
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="required form-label">Nombres</label>
                                        <input type="text" id="txtNombre" class="form-control mb-2" value="" style="text-transform: uppercase;" maxlength="80" placeholder="Ingrese Nombres" />
                                    </div>
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="required form-label">Apellidos</label>
                                        <input type="text" id="txtApellido" class="form-control mb-2" value="" style="text-transform: uppercase;" maxlength="80" placeholder="Ingrese Apellidos" />
                                    </div>   
                                </div>
                                <div class="d-flex flex-wrap gap-5">
                                    <div class="fv-row w-100 flex-md-root">
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
                                    <div class="fv-row w-100 flex-md-root">
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
                                </div>
                                <div class="d-flex flex-wrap gap-5">
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="form-label">Fecha de Nacimiento</label>
                                        <input type="date" id="txtFechaNacimiento" class="form-control mb-2" value="" />
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap gap-5">
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="required form-label">Provincia</label>
                                        <select name="cboProvincia" id="cboProvincia" aria-label="Seleccione Provincia" data-control="select2" data-placeholder="Seleccione Provincia" data-dropdown-parent="#kt_ecommerce_add_product_general" class="form-select mb-2" >
                                                <option></option>
                                                <?php foreach ($all_provincia as $prov) : ?>
                                                    <option value="<?php echo $prov['Descripcion'] ?>"><?php echo mb_strtoupper($prov['Descripcion']) ?></option>
                                                <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="form-label">Ciudad</label>
                                        <select id="cboCiudad" aria-label="Seleccione Ciudad" data-control="select2" data-placeholder="Seleccione Ciudad" data-dropdown-parent="#kt_ecommerce_add_product_general" class="form-select mb-2">
                                                <option></option>
                                        </select> 
                                    </div>  
                                </div>
                                <div class="mb-10 fv-row">
                                    <label class="form-label">Direccion</label>
                                    <textarea class="form-control mb-2" id="txtDireccion" style="text-transform: uppercase;" maxlength="250" rows="1"></textarea>
                                </div>
                                <div class="d-flex flex-wrap gap-5">
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="form-label">Telefono Casa</label>
                                        <input type="text" id="txtTelCasa" class="form-control mb-2 col-md-1" value="" placeholder="022222222" maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                                    </div>
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="form-label">Telefono Oficina</label>
                                        <input type="text" id="txtTelOfi" class="form-control mb-2 col-md-1" value="" placeholder="022222222" maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;"/>
                                    </div>  
                                </div>
                                <div class="d-flex flex-wrap gap-5">
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="form-label">Telefono Celular</label>
                                        <input type="text" id="txtCelular" class="form-control mb-2 col-md-1" value="" placeholder="0999999999" maxlength="10" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                                    </div>
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="form-label">Email</label>
                                        <input type="email" id="txtEmail" class="form-control mb-2 col-md-1 text-lowercase" value="" placeholder="micorreo@gmail.com" maxlength="80" />
                                    </div>  
                                </div>
                                <div class="d-flex flex-wrap gap-5">
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="form-label">Inicio Cobertura</label>
                                        <input type="date" id="txtIniCobertura" class="form-control mb-2" value="<?php 
                                          $dia = date('Y-m-d');
                                          echo date('Y-m-d', strtotime($dia)); ?>" />
                                    </div>
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="form-label">Fin Cobertura</label>
                                        <input type="date" id="txtFinCobertura" class="form-control mb-2" value="<?php echo $xFechaFinCobertura; ?>" />
                                    </div>  
                                </div>                                                          
                            </div>
                        </div>
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Titulares Agregados</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="d-flex align-items-center position-relative mb-n7">
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
                                    <table class="table table-hover align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_edit_order_product_table">
                                        <thead>
                                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                                <th>Ciudad</th>
                                                <th>Nombres</th>
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
                                                            <button type="button" id="btnEditar_<?php echo $xPerid; ?>" onclick="f_Editartitular(<?php echo $xPerid; ?>,<?php echo $xTituid; ?>,<?php echo $clieid; ?>,<?php echo $prodid; ?>,<?php echo $grupid; ?>)" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar"  title='Editar Titular' data-bs-toggle="tooltip" data-bs-placement="left">
                                                                <i class="fa fa-edit"></i>
                                                            </button> 
                                                            <button type="button" id="btnAgendar_<?php echo $xPerid; ?>" onclick="" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"  title='Agendar' data-bs-toggle="tooltip" data-bs-placement="left">
                                                                <i class="fa fa-user-plus"></i>
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
                <div class="tab-pane fade" id="tab_beneficiarios" role="tab-panel">
                    <div class="d-flex flex-column gap-7 gap-lg-10">
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Datos Beneficiario</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="d-flex flex-wrap gap-5">
                                    <div class="fv-row w-100 flex-md-root">
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
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="required form-label">Nro. Documento</label>
                                        <input type="text" class="form-control mb-2" id="txtDocumentoBe" value="" maxlength="13" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                                    </div>    
                                </div>
                                <div class="d-flex flex-wrap gap-5">
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="required form-label">Nombres</label>
                                        <input type="text" class="form-control mb-2" id="txtNombreBe" value="" style="text-transform: uppercase;" maxlength="80" placeholder="Ingrese Nombres" />
                                    </div>
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="required form-label">Apellidos</label>
                                        <input type="text" class="form-control mb-2" id="txtApellidoBe" value="" style="text-transform: uppercase;" maxlength="80" placeholder="Ingrese Apellidos" />
                                    </div>   
                                </div>
                                <div class="d-flex flex-wrap gap-5">
                                    <div class="fv-row w-100 flex-md-root">
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
                                    <div class="fv-row w-100 flex-md-root">
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
                                <div class="d-flex flex-wrap gap-5">
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="required form-label">Provincia</label>
                                        <select  id="cboProvinciaBe" aria-label="Seleccione Provincia" data-control="select2" data-placeholder="Seleccione Provincia" data-dropdown-parent="#tab_beneficiarios" class="form-select mb-2" >
                                                <option></option>
                                                <?php foreach ($all_provincia as $prov) : ?>
                                                    <option value="<?php echo $prov['Descripcion'] ?>"><?php echo mb_strtoupper($prov['Descripcion']) ?></option>
                                                <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="form-label">Ciudad</label>
                                        <select id="cboCiudadBe" aria-label="Seleccione Ciudad" data-control="select2" data-placeholder="Seleccione Ciudad" data-dropdown-parent="#tab_beneficiarios" class="form-select mb-2">
                                                <option></option>
                                        </select> 
                                    </div>  
                                </div>
                                <div class="mb-10 fv-row">
                                    <label class="form-label">Direccion</label>
                                    <textarea class="form-control mb-2" id="txtDireccionBe" style="text-transform: uppercase;" rows="1"></textarea>
                                </div>
                                <div class="d-flex flex-wrap gap-5">
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="form-label">Telefono Casa</label>
                                        <input type="text" id="txtTelCasaBe" class="form-control mb-2 col-md-1" value="" placeholder="022222222" maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;"/>
                                    </div>
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="form-label">Telefono Oficina</label>
                                        <input type="text" id="txtTelOfiBe" class="form-control mb-2 col-md-1" value="" placeholder="022222222" maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;"/>
                                    </div>  
                                </div>
                                <div class="d-flex flex-wrap gap-5">
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="form-label">Telefono Celular</label>
                                        <input type="text" id="txtCelularBe" class="form-control mb-2 col-md-1" value="" placeholder="0999999999" maxlength="10" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                                    </div>
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="form-label">Email</label>
                                        <input type="email" id="txtEmailBe" class="form-control mb-2 col-md-1 text-lowercase" value="" placeholder="micorreo@gmail.com" maxlength="80" />
                                    </div>  
                                </div>
                                <div class="d-flex flex-wrap gap-5">
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="form-label">Parentesco</label>
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
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="form-label">Fecha de Nacimiento</label>
                                        <input type="date" id="txtFechaNacimientoBe" class="form-control mb-2" value="" />
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
                                    Agregar Beneficiario
                                    </button>
                                </div>  
                            </div>
                        </div>
                        <div class="card card-flush py-4">
                            <div class="card-header">
                                <div class="card-title">
                                    <h2>Beneficiario</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="d-flex flex-column gap-10">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="tblBeneficiario">
                                        <thead>
                                            <tr class="text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                                <th>Ciudad</th>
                                                <th>Nombres</th>
                                                <th>Parentesco</th>
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
            <div class="d-flex justify-content-end">
                <button type="button" id="btnGrabar" onclick="" class="btn btn-primary">
                    <span class="indicator-label">Grabar</span>
                </button>
            </div>
        </div>
    </form>
</div>
<script>
    
    var _count =0,_prodid = '<?php echo $prodid; ?>', _grupid = '<?php echo $grupid; ?>', _userid = '<?php echo $xUsuaid; ?>',
        _idclie = '<?php echo $clieid; ?>',_paisid = '<?php echo $xPaisid; ?>',_emprid = '<?php echo $xEmprid; ?>',_result = [];


    $(document).ready(function(){
    
        $('#cboProvincia').change(function(){
                    
            var _paisid = "<?php echo $xPaisid; ?>";
            var _emprid = "<?php echo $xEmprid; ?>";                
            _cboid = $(this).val(); //obtener el id seleccionado
            
            $("#cboCiudad").empty();
    
            var _parametros = {
                xxPaisId: _paisid,
                xxEmprId: _emprid,
                xxComboId: _cboid,
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
                    
            var _paisid = "<?php echo $xPaisid; ?>";
            var _emprid = "<?php echo $xEmprid; ?>";                
            _cboid = $(this).val(); //obtener el id seleccionado
            
            $("#cboCiudadBe").empty();


            var _parametros = {
                xxPaisId: _paisid,
                xxEmprId: _emprid,
                xxComboId: _cboid,
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

        if(_cboDocumentoBe == ''){
            mensajesalertify("Seleccione Tipo Documento..!", "W", "top-right", 3);
            return; 
        }

        if(_txtDocumentoBe == ''){
            mensajesalertify("Ingrese Numero de Documento..!", "W", "top-right", 3);
            return; 
        }

        if(_txtDocumentoBe.length < 10){
            mensajesalertify("Documento Incorrecto..!", "W", "top-right", 3);
            return; 
        }

        if(_txtNombreBe == ''){
            mensajesalertify("Ingrese Nombre..!", "W", "top-right", 3);
            return; 
        }

        if(_txtApellidoBe == ''){
            mensajesalertify("Ingrese Apellido..!", "W", "top-right", 3);
            return; 
        }

        if(_cboGeneroBe == ''){
            mensajesalertify("Seleccione Genero..!", "W", "top-right", 3);
            return; 
        }


        if(_cboProvinciaBe == ''){
            mensajesalertify("Seleccione Provincia..!!","W","top-right",3);
            return false;
        }

        if(_cboCiudadBe == 0){
            mensajesalertify("Seleccione Ciudad..!!","W","top-right",3);
            return false;
        }

        if(_txtTelCasaBe != '')
        {
            _valor = document.getElementById("txtTelCasaBe").value;
            if( !(/^\d{9}$/.test(_valor)) ) {
                mensajesalertify("Telefono casa incorrecto..!" ,"W", "top-right", 3); 
                return;
            }
        }

        if(_txtTelOfiBe != '')
        {
            _valor = document.getElementById("txtTelOfiBe").value;
            if( !(/^\d{9}$/.test(_valor)) ) {
                mensajesalertify("Telefono oficina incorrecto..!" ,"W", "top-right", 3); 
                return;
            }
        }

        if(_txtTelCelularBe != '')
        {
            _valor = document.getElementById("txtCelularBe").value;
            if( !(/^\d{10}$/.test(_valor)) ) {
                mensajesalertify("Celular incorrecto..!" ,"W", "top-right", 3); 
                return;
            }
        }
        
        if(_txtEmailBe != ''){
            var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
            if (regex.test(_txtEmailBe.trim())){
            }else{
                mensajesalertify("Email Incorrecto..!!","W","top-right",3);
                return false;
            }  
        }

        if(_cboParentesco == ''){
            mensajesalertify("Seleccione Parentesco..!", "W", "top-right", 3);
            return; 
        }


        var _parametros = {
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

 

    //Agregar Persona - Titular 

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
            mensajesalertify("Seleccione Tipo Documento..!", "W", "top-right", 3);
            return; 
        }

        if(_txtDocumento == ''){
            mensajesalertify("Ingrese Numero de Documento..!", "W", "top-right", 3);
            return; 
        }

        
        if(_txtDocumento.length < 10){
            mensajesalertify("Documento Incorrecto..!", "W", "top-right", 3);
            return; 
        }

        if(_txtNombre == ''){
            mensajesalertify("Ingrese Nombre..!", "W", "top-right", 3);
            return; 
        }

        if(_txtApellido == ''){
            mensajesalertify("Ingrese Apellido..!", "W", "top-right", 3);
            return; 
        }

        if(_cboGenero == ''){
            mensajesalertify("Seleccione Genero..!", "W", "top-right", 3);
            return; 
        }

        if(_cboEstadoCivil == ''){
            mensajesalertify("Seleccione Estado Civil..!", "W", "top-right", 3);
            return; 
        }

        if(_cboProvincia == ''){
            mensajesalertify("Seleccione Provincia..!!","W","top-right",3);
            return false;
        }

        if(_cboCiudad == 0){
            mensajesalertify("Seleccione Ciudad..!!","W","top-right",3);
            return false;
        }

        
        if(_txtTelCasa != '')
        {
            _valor = document.getElementById("txtTelCasa").value;
            if( !(/^\d{9}$/.test(_valor)) ) {
                mensajesalertify("Telefono casa incorrecto..!" ,"W", "top-right", 3); 
                return;
            }
        }

        if(_txtTelOfi != '')
        {
            _valor = document.getElementById("txtTelOfi").value;
            if( !(/^\d{9}$/.test(_valor)) ) {
                mensajesalertify("Telefono oficina incorrecto..!" ,"W", "top-right", 3); 
                return;
            }
        }

        if(_txtTelCelular != '')
        {
            _valor = document.getElementById("txtCelular").value;
            if( !(/^\d{10}$/.test(_valor)) ) {
                mensajesalertify("Celular incorrecto..!" ,"W", "top-right", 3); 
                return;
            }
        }
        
        if(_txtEmail != ''){
            var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
            if (regex.test(_txtEmail.trim())){
            }else{
                mensajesalertify("Email Incorrecto..!!","W","top-right",3);
                return false;
            }  
        }

        var _parametros = {
            
            xxDocumento: _txtDocumento,
        
        }

        
        var xrespuesta = $.post("codephp/consultar_persona.php",_parametros );
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
                url: "codephp/grabar_personatitular.php",
                type: "post",                
                data: form_data,
                processData: false,
                contentType: false,
                dataType: "json",
                    success: function(dataid){

                        if(dataid != 0){

                            if(_result.length > 0){
                                var xrespuesta = $.post("codephp/grabar_beneficiariotitular.php", { xxTituid: dataid, xxUsuaid: _userid,xxResult: _result });
                                    xrespuesta.done(function(response){
                                            
                                    if(response == 'OK'){

                                        $.redirect('?page=editcliente&menuid=<?php echo $menuid; ?>', 
                                        {'mensaje': 'Grabado con Éxito..!',
                                          'idclie': _idclie
                                        
                                        }); //POR METODO POST
                            
                                    }

                                });
                            }

                            $.redirect('?page=editcliente&menuid=<?php echo $menuid; ?>', 
                            {'mensaje': 'Grabado con Éxito..!',
                              'idclie': _idclie
                            }); //POR METODO POST
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
					