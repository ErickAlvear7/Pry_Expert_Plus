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


?>
<div class="post d-flex flex-column-fluid" id="kt_post">
    <div id="kt_content_container" class="container-xxl">
        <form id="kt_ecommerce_add_product_form" class="form d-flex flex-column flex-lg-row" data-kt-redirect="../../demo1/dist/apps/ecommerce/catalog/products.html">
            <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Thumbnail</h2>
                        </div>
                    </div>
                    <div class="card-body text-center pt-0">
                        <div class="image-input image-input-empty image-input-outline mb-3" data-kt-image-input="true">
                            <div class="image-input-wrapper w-150px h-150px" style="background-image: url(assets/media//stock/ecommerce/78.gif)"></div>
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                <i class="bi bi-pencil-fill fs-7"></i>
                                <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                                <input type="hidden" name="avatar_remove" />
                            </label>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                <i class="bi bi-x fs-2"></i>
                            </span>
                        </div>
                        <div class="text-muted fs-7">Set the product thumbnail image. Only *.png, *.jpg and *.jpeg image files are accepted</div>
                    </div>
                </div>
                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Status</h2>
                        </div>
                        <div class="card-toolbar">
                            <div class="rounded-circle bg-success w-15px h-15px" id="kt_ecommerce_add_product_status"></div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <select class="form-select mb-2" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="kt_ecommerce_add_product_status_select">
                            <option></option>
                            <option value="published" selected="selected">Published</option>
                            <option value="draft">Draft</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <div class="text-muted fs-7">Set the product status.</div>
                        <div class="d-none mt-10">
                            <label for="kt_ecommerce_add_product_status_datepicker" class="form-label">Select publishing date and time</label>
                            <input class="form-control" id="kt_ecommerce_add_product_status_datepicker" placeholder="Pick date &amp; time" />
                        </div>
                    </div>
                </div>
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
                <div class="card card-flush py-4">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Product Template</h2>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <label for="kt_ecommerce_add_product_store_template" class="form-label">Select a product template</label>
                        <select class="form-select mb-2" data-control="select2" data-hide-search="true" data-placeholder="Select an option" id="kt_ecommerce_add_product_store_template">
                            <option></option>
                            <option value="default" selected="selected">Default template</option>
                            <option value="electronics">Electronics</option>
                            <option value="office">Office stationary</option>
                            <option value="fashion">Fashion</option>
                        </select>
                        <div class="text-muted fs-7">Assign a template from your current theme to define how a single product is displayed.</div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-n2">
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_add_product_general">Titular</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_add_product_advanced">Beneficiarios</a>
                    </li>
                    <a href="?page=editcliente&menuid=<?php echo $menuid;?>" class="btn btn-icon btn-light-primary btn-sm ms-auto me-lg-n7">
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
                                        <h2>Datos Titular</h2>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="d-flex flex-wrap gap-5">
                                        <div class="fv-row w-100 flex-md-root">
                                            <label class="required form-label">Tipo Documento</label>
                                            <select class="form-select mb-2" name="tax" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                                                <option></option>
                                                <option value="1" selected="selected">Cedula</option>
                                                <option value="2">Pasaporte</option>
                                            </select>
                                        </div>
                                        <div class="fv-row w-100 flex-md-root">
                                            <label class="required form-label">Nro. Documento</label>
                                            <input type="text" class="form-control mb-2" value="" />
                                        </div>    
                                    </div>
                                    <div class="d-flex flex-wrap gap-5">
                                        <div class="fv-row w-100 flex-md-root">
                                            <label class="required form-label">Primer Nombre</label>
                                            <input type="text" class="form-control mb-2" value="" placeholder="Ingrese Nombre" />
                                        </div>
                                        <div class="fv-row w-100 flex-md-root">
                                            <label class="form-label">Segundo Nombre</label>
                                            <input type="text" class="form-control mb-2" value="" />
                                        </div>   
                                    </div>
                                    <div class="d-flex flex-wrap gap-5">
                                        <div class="fv-row w-100 flex-md-root">
                                            <label class="required form-label">Primer Apellido</label>
                                            <input type="text" class="form-control mb-2" value="" placeholder="Ingrese Apellido" />
                                        </div>
                                        <div class="fv-row w-100 flex-md-root">
                                            <label class="form-label">Segundo Apellido</label>
                                            <input type="text" class="form-control mb-2" value="" />
                                        </div>   
                                    </div>
                                    <div class="d-flex flex-wrap gap-5">
                                        <div class="fv-row w-100 flex-md-root">
                                            <label class="required form-label">Genero</label>
                                            <select class="form-select mb-2" name="tax" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                                                <option></option>
                                                <option value="1" selected="selected">Masculino</option>
                                                <option value="2">Femenino</option>
                                            </select>
                                        </div>
                                        <div class="fv-row w-100 flex-md-root">
                                            <label class="form-label">Estado Civil</label>
                                            <select class="form-select mb-2" name="tax" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                                                <option></option>
                                                <option value="1" selected="selected">Soltero</option>
                                                <option value="2">Casado</option>
                                                <option value="2">Viudo</option>
                                            </select>
                                        </div>      
                                    </div>
                                    <div class="d-flex flex-wrap gap-5">
                                        <div class="fv-row w-100 flex-md-root">
                                            <label class="form-label">Fecha Nacimiento</label>
                                            <input type="date" class="form-control mb-2" value="" />
                                        </div>
                                        <div class="fv-row w-100 flex-md-root">
                                            <label class="form-label">Edad</label>
                                            <input type="text" class="form-control mb-2" value="" />
                                        </div>  
                                    </div>
                                    <div class="d-flex flex-wrap gap-5">
                                        <div class="fv-row w-100 flex-md-root">
                                            <label class="required form-label">Provincia</label>
                                            <select class="form-select mb-2" name="tax" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                                                <option></option>
                                            </select>
                                        </div>
                                        <div class="fv-row w-100 flex-md-root">
                                            <label class="form-label">Ciudad</label>
                                            <select class="form-select mb-2" name="tax" data-control="select2" data-hide-search="true" data-placeholder="Select an option">
                                                <option></option>
                                            </select>
                                        </div>  
                                    </div>
                                    <div class="mb-10 fv-row">
                                       <label class="form-label">Direccion</label>
                                       <textarea class="form-control mb-2" id="" rows="2"></textarea>
                                    </div>
                                    <div class="d-flex flex-wrap gap-5">
                                        <div class="fv-row w-100 flex-md-root">
                                            <label class="form-label">Telefono Casa</label>
                                            <input type="text" class="form-control mb-2 col-md-1" value="" placeholder="022222222" maxlength="9" />
                                        </div>
                                        <div class="fv-row w-100 flex-md-root">
                                            <label class="form-label">Telefono Oficina</label>
                                            <input type="text" class="form-control mb-2 col-md-1" value="" placeholder="022222222" maxlength="9"/>
                                        </div>  
                                    </div>
                                    <div class="d-flex flex-wrap gap-5">
                                        <div class="fv-row w-100 flex-md-root">
                                            <label class="form-label">Telefono Celular</label>
                                            <input type="text" class="form-control mb-2 col-md-1" value="" placeholder="0999999999" maxlength="10" />
                                        </div>
                                        <div class="fv-row w-100 flex-md-root">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control mb-2 col-md-1" value="" placeholder="micorreo@gmail.com" maxlength="10" />
                                        </div>  
                                    </div>
                                    <div class="d-flex flex-wrap gap-5">
                                        <div class="fv-row w-100 flex-md-root">
                                            <label class="form-label">Inicio Cobertura</label>
                                            <input type="date" class="form-control mb-2" value="" />
                                        </div>
                                        <div class="fv-row w-100 flex-md-root">
                                            <label class="form-label">Fin Cobertura</label>
                                            <input type="date" class="form-control mb-2" value="" />
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
                                    <br>
                                    <div class="separator"></div>
                                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_edit_order_product_table">
                                        <thead>
                                            <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                                <th class="w-25px pe-2"></th>
                                                <th class="min-w-200px">Product</th>
                                                <th class="min-w-100px text-end pe-5">Qty Remaining</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-bold text-gray-600">
                                            <tr>
                                                <td>
                                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="checkbox" value="1" />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center" data-kt-ecommerce-edit-order-filter="product" data-kt-ecommerce-edit-order-id="product_1">
                                                        <a href="../../demo1/dist/apps/ecommerce/catalog/edit-product.html" class="symbol symbol-50px">
                                                            <span class="symbol-label" style="background-image:url(assets/media//stock/ecommerce/1.gif);"></span>
                                                        </a>
                                                        <div class="ms-5">
                                                            <a href="../../demo1/dist/apps/ecommerce/catalog/edit-product.html" class="text-gray-800 text-hover-primary fs-5 fw-bolder">Product 1</a>
                                                            <div class="fw-bold fs-7">Price: $
                                                            <span data-kt-ecommerce-edit-order-filter="price">222.00</span></div>
                                                            <div class="text-muted fs-7">SKU: 01731001</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-5" data-order="0">
                                                    <span class="badge badge-light-danger">Sold out</span>
                                                    <span class="fw-bolder text-danger ms-3">0</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                        <input class="form-check-input" type="checkbox" value="1" />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center" data-kt-ecommerce-edit-order-filter="product" data-kt-ecommerce-edit-order-id="product_2">
                                                        <a href="../../demo1/dist/apps/ecommerce/catalog/edit-product.html" class="symbol symbol-50px">
                                                            <span class="symbol-label" style="background-image:url(assets/media//stock/ecommerce/2.gif);"></span>
                                                        </a>
                                                        <div class="ms-5">
                                                            <a href="../../demo1/dist/apps/ecommerce/catalog/edit-product.html" class="text-gray-800 text-hover-primary fs-5 fw-bolder">Product 2</a>
                                                            <div class="fw-bold fs-7">Price: $
                                                            <span data-kt-ecommerce-edit-order-filter="price">227.00</span></div>
                                                            <div class="text-muted fs-7">SKU: 02952002</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-5" data-order="28">
                                                    <span class="fw-bolder ms-3">28</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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

                                </div>
                            </div>    
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
                        <span class="indicator-label">Save Changes</span>
                        <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>