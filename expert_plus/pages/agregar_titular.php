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
        <div class="flex-lg-row-fluid ms-lg-15">
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8">
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_settings_general">
                    <span class="svg-icon svg-icon-2 me-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M11 2.375L2 9.575V20.575C2 21.175 2.4 21.575 3 21.575H9C9.6 21.575 10 21.175 10 20.575V14.575C10 13.975 10.4 13.575 11 13.575H13C13.6 13.575 14 13.975 14 14.575V20.575C14 21.175 14.4 21.575 15 21.575H21C21.6 21.575 22 21.175 22 20.575V9.575L13 2.375C12.4 1.875 11.6 1.875 11 2.375Z" fill="currentColor" />
                        </svg>
                    </span>
                    Titular</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_settings_customers">
                    <span class="svg-icon svg-icon-2 me-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M16.0173 9H15.3945C14.2833 9 13.263 9.61425 12.7431 10.5963L12.154 11.7091C12.0645 11.8781 12.1072 12.0868 12.2559 12.2071L12.6402 12.5183C13.2631 13.0225 13.7556 13.6691 14.0764 14.4035L14.2321 14.7601C14.2957 14.9058 14.4396 15 14.5987 15H18.6747C19.7297 15 20.4057 13.8774 19.912 12.945L18.6686 10.5963C18.1487 9.61425 17.1285 9 16.0173 9Z" fill="currentColor" />
                            <rect opacity="0.3" x="14" y="4" width="4" height="4" rx="2" fill="currentColor" />
                            <path d="M4.65486 14.8559C5.40389 13.1224 7.11161 12 9 12C10.8884 12 12.5961 13.1224 13.3451 14.8559L14.793 18.2067C15.3636 19.5271 14.3955 21 12.9571 21H5.04292C3.60453 21 2.63644 19.5271 3.20698 18.2067L4.65486 14.8559Z" fill="currentColor" />
                            <rect opacity="0.3" x="6" y="5" width="6" height="6" rx="3" fill="currentColor" />
                        </svg>
                    </span>
                    Beneficiario</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="kt_ecommerce_settings_general" role="tabpanel">
                    <div class="card card-flush">
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <div class="card-title">
                                <h2>Datos Titular</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <form id="kt_ecommerce_settings_general_form" class="form" action="#">

                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="kt_ecommerce_settings_customers" role="tabpanel">
                <div class="card card-flush">
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <div class="card-title">
                                <h2>Datos Beneficiario</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <form id="kt_ecommerce_settings_general_store" class="form" action="#">

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>