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
                        <h2>Product Details</h2>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <label class="form-label">Categories</label>
                    <select class="form-select mb-2" data-control="select2" data-placeholder="Select an option" data-allow-clear="true" multiple="multiple">
                        <option></option>
                        <option value="Computers">Computers</option>
                        <option value="Watches">Watches</option>
                        <option value="Headphones">Headphones</option>
                        <option value="Footwear">Footwear</option>
                        <option value="Cameras">Cameras</option>
                        <option value="Shirts">Shirts</option>
                        <option value="Household">Household</option>
                        <option value="Handbags">Handbags</option>
                        <option value="Wines">Wines</option>
                        <option value="Sandals">Sandals</option>
                    </select>
                    <div class="text-muted fs-7 mb-7">Add product to a category.</div>
                    <a href="../../demo1/dist/apps/ecommerce/catalog/add-category.html" class="btn btn-light-primary btn-sm mb-10">
                    <span class="svg-icon svg-icon-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                            <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
                        </svg>
                    </span>
                    Create new category</a>
                    <label class="form-label d-block">Tags</label>
                    <input id="kt_ecommerce_add_product_tags" name="kt_ecommerce_add_product_tags" class="form-control mb-2" value="new, trending, sale" />
                    <div class="text-muted fs-7">Add tags to a product.</div>
                </div>
            </div>
            <br>
            <div class="card card-flush py-4">
                <div class="card-header">
                    <div class="card-title">
                        <h2>Order Details</h2>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex flex-column gap-10">
                        <div class="fv-row">
                            <label class="form-label">Order ID</label>
                            <div class="fw-bolder fs-3">#13111</div>
                        </div>
                        <div class="fv-row">
                            <label class="required form-label">Payment Method</label>
                            <select class="form-select mb-2" data-control="select2" data-hide-search="true" data-placeholder="Select an option" name="payment_method" id="kt_ecommerce_edit_order_payment">
                                <option></option>
                                <option value="cod">Cash on Delivery</option>
                                <option value="visa">Credit Card (Visa)</option>
                                <option value="mastercard">Credit Card (Mastercard)</option>
                                <option value="paypal">Paypal</option>
                            </select>
                            <div class="text-muted fs-7">Set the date of the order to process.</div>
                        </div>
                        <div class="fv-row">
                            <label class="required form-label">Shipping Method</label>
                            <select class="form-select mb-2" data-control="select2" data-hide-search="true" data-placeholder="Select an option" name="shipping_method" id="kt_ecommerce_edit_order_shipping">
                                <option></option>
                                <option value="none">N/A - Virtual Product</option>
                                <option value="standard">Standard Rate</option>
                                <option value="express">Express Rate</option>
                                <option value="speed">Speed Overnight Rate</option>
                            </select>
                            <div class="text-muted fs-7">Set the date of the order to process.</div>
                        </div>
                        <div class="fv-row">
                            <label class="required form-label">Order Date</label>
                            <input id="kt_ecommerce_edit_order_date" name="order_date" placeholder="Select a date" class="form-control mb-2" value="" />
                            <div class="text-muted fs-7">Set the date of the order to process.</div>
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
                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_add_product_advanced">Beneficiarios</a>
                </li>
                <div class="d-flex justify-content-end">
                    <button type="button" id="btnRegresar" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title='Regresar'>
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M11.2657 11.4343L15.45 7.25C15.8642 6.83579 15.8642 6.16421 15.45 5.75C15.0358 5.33579 14.3642 5.33579 13.95 5.75L8.40712 11.2929C8.01659 11.6834 8.01659 12.3166 8.40712 12.7071L13.95 18.25C14.3642 18.6642 15.0358 18.6642 15.45 18.25C15.8642 17.8358 15.8642 17.1642 15.45 16.75L11.2657 12.5657C10.9533 12.2533 10.9533 11.7467 11.2657 11.4343Z" fill="currentColor" />
                            </svg>
                        </span>
                    </button>
                </div> 
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
                                        <input type="text" id="txtDocumento" class="form-control mb-2" value="" maxlength="13" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;"  />
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
                                        <input type="date" id="txtFinCobertura" class="form-control mb-2" value="" />
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
                                    <input type="text" data-kt-ecommerce-edit-order-filter="search" class="form-control form-control-solid w-100 w-lg-50 ps-14" placeholder="Search Products" />
                                </div>
                                <br>
                                <div class="scroll-y me-n7 pe-7" id="parametro_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#parametro_header" data-kt-scroll-wrappers="#parametro_scroll" data-kt-scroll-offset="300px">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_edit_order_product_table">
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
                                            <tr>
                                                <td>
                                                    Quito
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center" data-kt-ecommerce-edit-order-filter="product" data-kt-ecommerce-edit-order-id="product_1">
                                                        <a href="../../demo1/dist/apps/ecommerce/catalog/edit-product.html" class="symbol symbol-50px">
                                                            <span class="symbol-label" style="background-image:url(assets/media//stock/ecommerce/1.gif);"></span>
                                                        </a>
                                                        <div class="ms-5">
                                                        Erick Alvear
                                                        </div>
                                                    </div>
                                                </td>
                                                <td id="td_">   
                                                    <div class="">
                                                        Activo
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                        <input class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk" value=""/>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <div class="btn-group">	
                                                            <button type="button" id="btnEditar_" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar"  title='Editar Titular'>
                                                                <i class="fa fa-edit"></i>
                                                            </button> 
                                                            <button type="button" id="btnTitular" onclick="" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"  title='Agendar'>
                                                                <i class="fa fa-user-plus"></i>
                                                            </button> 
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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
                                        <select id="cboProvinciaBe" class="form-select mb-2"  data-control="select2" data-hide-search="true" data-placeholder="Seleccione Provincia">
                                        <!-- <select  id="cboProvinciaBe" aria-label="Seleccione Provincia" data-control="select2" data-placeholder="Seleccione Provincia" data-dropdown-parent="#kt_ecommerce_add_product_general" class="form-select mb-2" > -->
                                                <option></option>
                                                <?php foreach ($all_provincia as $prov) : ?>
                                                    <option value="<?php echo $prov['Descripcion'] ?>"><?php echo mb_strtoupper($prov['Descripcion']) ?></option>
                                                <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="fv-row w-100 flex-md-root">
                                        <label class="form-label">Ciudad</label>
                                        <select id="cboCiudadBe" class="form-select mb-2"  data-control="select2" data-hide-search="true" data-placeholder="Seleccione Ciudad">
                                        <!-- <select id="cboCiudadBe" aria-label="Seleccione Ciudad" data-control="select2" data-placeholder="Seleccione Ciudad" data-dropdown-parent="#kt_ecommerce_add_product_general" class="form-select mb-2"> -->
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
                                    <h2>Beneficiarios Agregados</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="d-flex flex-column gap-10">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="tblBeneficiario">
                                        <thead>
                                            <tr class="text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                                <th>Ciudad</th>
                                                <th>Nombres</th>
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
    
    var _prodid = '<?php echo $prodid; ?>', _grupid = '<?php echo $grupid; ?>', _userid = '<?php echo $xUsuaid; ?>',
        _idclie = '<?php echo $clieid; ?>',_result = [];

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
        var _count =0;
        var _cboDocumentoBe = $('#cboDocumentoBe').val();
        var _txtDocumentoBe = $('#txtDocumentoBe').val();
        var _txtNombreBe = $.trim($("#txtNombreBe").val());
            _txtNombreBe.toUpperCase();
        var _txtApellidoBe =  $.trim($('#txtApellidoBe').val());
            _txtApellidoBe.toUpperCase();
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
        var _fechaNacimientoBe = $('#txtFechaNacimientoBe').val();

        if(_cboDocumentoBe == ''){
            mensajesalertify("Seleccione Tipo Documento..!", "W", "top-right", 3);
            return; 
        }

        if(_txtDocumentoBe == ''){
            mensajesalertify("Ingrese Numero de Documento..!", "W", "top-right", 3);
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
                    _output += '<td>' + _txtnombresCompletos + ' <input type="hidden" class="form-control mb-2 text-uppercase" id="txtNombres' + _count + '" value="' + _txtnombresCompletos + '" /></td>';
                    _output += '<td>';
                    _output += '<button type="button" title="Eliminar Beneficiario" name="btnDelete" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1 btnDelete" id="' + _count + '"><i class="fa fa-trash"></i></button></td>';
                    _output += '</tr>';

                    //console.log(_output);

                    $('#tblBeneficiario').append(_output);

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
        
                }

            }



        });


        //alert('hola');

        //console.log(_txtCiudadBe);

    });




        //Agregar Persona - Titular 

    $('#btnGrabar').click(function(){

        //debugger;
        var _usuaid = <?php echo $xUsuaid; ?>;
        var _prodid = <?php echo $prodid; ?>;
        var _grupid = <?php echo $grupid; ?>;
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
            mensajesalertify("Seleccione Genero..!", "W", "top-right", 3);
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

        if(_fechaFinCobertura == ''){
            mensajesalertify("Seleccione Fecha fin de Cobertura..!", "W", "top-right", 3);
            return; 
        }

        var _parametros = {
            
            xxDocumento: _txtDocumento,
        
        }

        
        var xrespuesta = $.post("codephp/consultar_persona.php",_parametros );
        xrespuesta.done(function(response){

            if(response == 0){

                //debugger

                var form_data = new FormData();
                form_data.append('xxUsuaid', _usuaid);
                form_data.append('xxProdid', _prodid);
                form_data.append('xxGrupid', _grupid);              
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

                            if(_result != ''){
                                var xrespuesta = $.post("codephp/grabar_beneficiario.php", { xxPaisid: _idpais, xxEmprid: _idempr,xxUsuaid: _iduser,xxClieid: dataid, xxResult: _result });
                                    xrespuesta.done(function(response){
                                            
                                    if(response == 'OK'){

                                        $.redirect('?page=admin_clienteproducto&menuid=<?php echo $menuid; ?>', {'mensaje': 'Grabado con Éxito..!'}); //POR METODO POST
                            
                                    }

                                });
                            }

                            $.redirect('?page=admin_clienteproducto&menuid=<?php echo $menuid; ?>', {'mensaje': 'Grabado con Éxito..!'}); //POR METODO POST
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





</script>
					