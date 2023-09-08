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
    $perid = $_POST['idper'];
    $tituid = $_POST['idtit'];
    $clieid = $_POST['idcli'];
    $prodid = $_POST['idpro'];
    $grupid = $_POST['idgru'];
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

    $xSQL = "SELECT per.pers_id AS Perid, per.pers_numerodocumento AS Documento, CONCAT(per.pers_nombres,' ',per.pers_apellidos) AS Persona, ";
    $xSQL .= "per.pers_imagen AS Imagen, per.pers_fechanacimiento AS Fecha, ciu.ciudad AS Ciudad, per.pers_estado AS Estado ";
    $xSQL .= "FROM `expert_persona` per, `expert_titular` tit, `provincia_ciudad` ciu ";
    $xSQL .= "WHERE per.pers_id=$perid AND tit.pers_id=$tituid AND per.pers_ciudad=ciu.prov_id AND per.pais_id=$xPaisid AND per.empr_id=$xEmprid ";
    $titular = mysqli_query($con, $xSQL);

    foreach($titular as $per){
        $xPerid = $per['Perid'];
        $xDocumento = $per['Documento'];
        $xPersona = $per['Persona'];
        $xImagen = $per['Imagen'];
        $xFecha = $per['Fecha'];
        $xEstado = $per['Estado'];
        $xCiudad = $per['Ciudad'];
    }



    if($xEstado=='A'){
        $xestado='ACTIVO';
    }

    $xSQL = "SELECT ben.bene_id AS Beneid, CONCAT(ben.bene_nombres,' ', ben.bene_apellidos) AS Beneficiario, ciu.ciudad AS Ciudadben, ";
    $xSQL .= "pde.pade_nombre AS Parentesco, ben.bene_estado AS Estadoben ";
    $xSQL .= "FROM `expert_beneficiario` ben, `expert_titular` tit,`provincia_ciudad` ciu, `expert_parametro_detalle` pde ";
    $xSQL .= "WHERE tit.titu_id=$tituid AND ben.titu_id=$tituid AND ben.bene_ciudad=ciu.prov_id AND ben.bene_parentesco=pde.pade_valorV ";
    $all_Beneficiario = mysqli_query($con, $xSQL);

?>

<div id="kt_content_container" class="container-xxl">
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
            <div class="card mb-5 mb-xl-8">
                <div class="card-body">
                    <div class="d-flex flex-center flex-column py-5">
                        <div class="symbol symbol-100px symbol-circle mb-7">
                            <img src="persona/<?php echo $xImagen; ?>" alt="image" />
                        </div>
                        <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bolder mb-3"><?php echo $xPersona; ?></a>
                        <div class="mb-9">
                            <div class="badge badge-lg badge-light-primary d-inline"><?php echo $xestado; ?></div>
                        </div>
                        <div class="d-flex flex-wrap flex-center">
                            <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                <div class="fs-4 fw-bolder text-gray-700">
                                    <span class="w-75px">243</span>
                                    <span class="svg-icon svg-icon-3 svg-icon-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
                                            <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="fw-bold text-muted">Total</div>
                            </div>
                            <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                                <div class="fs-4 fw-bolder text-gray-700">
                                    <span class="w-50px">56</span>
                                    <span class="svg-icon svg-icon-3 svg-icon-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                                            <path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="fw-bold text-muted">Solved</div>
                            </div>
                            <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                <div class="fs-4 fw-bolder text-gray-700">
                                    <span class="w-50px">188</span>
                                    <span class="svg-icon svg-icon-3 svg-icon-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
                                            <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="fw-bold text-muted">Open</div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-stack fs-4 py-3">
                        <div class="fw-bolder rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_details" role="button" aria-expanded="false" aria-controls="kt_user_view_details">Details
                        <span class="ms-2 rotate-180">
                            <span class="svg-icon svg-icon-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                </svg>
                            </span>
                        </span></div>
                        <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Edit customer details">
                            <button type="button" id="btnEditarPer_<?php echo $xPerid; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditarPer">
                                Editar
                            </button> 
                        </span>
                    </div>
                    <div class="separator"></div>
                    <div id="kt_user_view_details" class="collapse show">
                        <div class="pb-5 fs-6">
                            <div class="fw-bolder mt-5">Cedula</div>
                            <div class="text-gray-600"><?php echo $xDocumento; ?></div>
                            <div class="fw-bolder mt-5">Cuidad</div>
                            <div class="text-gray-600"><?php echo $xCiudad; ?></div>
                            <div class="fw-bolder mt-5">Fecha Nacimiento</div>
                            <div class="text-gray-600"><?php echo $xFecha; ?></div>
                            <div class="fw-bolder mt-5">Last Login</div>
                            <div class="text-gray-600">25 Jul 2022, 2:40 pm</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-0">
                    <div class="card-title">
                        <h3 class="fw-bolder m-0">Connected Accounts</h3>
                    </div>
                </div>
                <div class="card-body pt-2">
                    <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 p-6">
                        <span class="svg-icon svg-icon-2tx svg-icon-primary me-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M22 19V17C22 16.4 21.6 16 21 16H8V3C8 2.4 7.6 2 7 2H5C4.4 2 4 2.4 4 3V19C4 19.6 4.4 20 5 20H21C21.6 20 22 19.6 22 19Z" fill="currentColor" />
                                <path d="M20 5V21C20 21.6 19.6 22 19 22H17C16.4 22 16 21.6 16 21V8H8V4H19C19.6 4 20 4.4 20 5ZM3 8H4V4H3C2.4 4 2 4.4 2 5V7C2 7.6 2.4 8 3 8Z" fill="currentColor" />
                            </svg>
                        </span>
                        <div class="d-flex flex-stack flex-grow-1">
                            <div class="fw-bold">
                                <div class="fs-6 text-gray-700">By connecting an account, you hereby agree to our
                                <a href="#" class="me-1">privacy policy</a>and
                                <a href="#">terms of use</a>.</div>
                            </div>
                        </div>
                    </div>
                    <div class="py-2">
                        <div class="d-flex flex-stack">
                            <div class="d-flex">
                                <img src="assets/media/svg/brand-logos/google-icon.svg" class="w-30px me-6" alt="" />
                                <div class="d-flex flex-column">
                                    <a href="#" class="fs-5 text-dark text-hover-primary fw-bolder">Google</a>
                                    <div class="fs-6 fw-bold text-muted">Plan properly your workflow</div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" name="google" type="checkbox" value="1" id="kt_modal_connected_accounts_google" checked="checked" />
                                    <span class="form-check-label fw-bold text-muted" for="kt_modal_connected_accounts_google"></span>
                                </label>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-5"></div>
                        <div class="d-flex flex-stack">
                            <div class="d-flex">
                                <img src="assets/media/svg/brand-logos/github.svg" class="w-30px me-6" alt="" />
                                <div class="d-flex flex-column">
                                    <a href="#" class="fs-5 text-dark text-hover-primary fw-bolder">Github</a>
                                    <div class="fs-6 fw-bold text-muted">Keep eye on on your Repositories</div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" name="github" type="checkbox" value="1" id="kt_modal_connected_accounts_github" checked="checked" />
                                    <span class="form-check-label fw-bold text-muted" for="kt_modal_connected_accounts_github"></span>
                                </label>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-5"></div>
                        <div class="d-flex flex-stack">
                            <div class="d-flex">
                                <img src="assets/media/svg/brand-logos/slack-icon.svg" class="w-30px me-6" alt="" />
                                <div class="d-flex flex-column">
                                    <a href="#" class="fs-5 text-dark text-hover-primary fw-bolder">Slack</a>
                                    <div class="fs-6 fw-bold text-muted">Integrate Projects Discussions</div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                    <input class="form-check-input" name="slack" type="checkbox" value="1" id="kt_modal_connected_accounts_slack" />
                                    <span class="form-check-label fw-bold text-muted" for="kt_modal_connected_accounts_slack"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-0 d-flex justify-content-center pt-0">
                    <button class="btn btn-sm btn-light-primary">Save Changes</button>
                </div>
            </div>
        </div>
        <div class="flex-lg-row-fluid ms-lg-15">
            <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8">
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_user_view_overview_tab">Beneficiario</a>
                </li>
                <button type="button" id="btnRegresar" onclick="f_Regresar(<?php echo $clieid; ?>,<?php echo $prodid; ?>,<?php echo $grupid; ?>)" class="btn btn-icon btn-light-primary btn-sm ms-auto me-lg-n7">
                    <span class="svg-icon svg-icon-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M11.2657 11.4343L15.45 7.25C15.8642 6.83579 15.8642 6.16421 15.45 5.75C15.0358 5.33579 14.3642 5.33579 13.95 5.75L8.40712 11.2929C8.01659 11.6834 8.01659 12.3166 8.40712 12.7071L13.95 18.25C14.3642 18.6642 15.0358 18.6642 15.45 18.25C15.8642 17.8358 15.8642 17.1642 15.45 16.75L11.2657 12.5657C10.9533 12.2533 10.9533 11.7467 11.2657 11.4343Z" fill="currentColor" />
                        </svg>
                    </span>
                </button>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="kt_user_view_overview_tab" role="tabpanel">
                    <div class="card pt-4 mb-6 mb-xl-9">
                        <div class="card-header border-0">
                            <div class="card-title">
                                <h2>Lista de Beneficiarios</h2>
                            </div>   
                        </div>
                        <div class="card-body pt-0 pb-5">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed gy-5" id="kt_table_users_login_session">
                                    <thead class="border-bottom border-gray-200 fs-7 fw-bolder">
                                        <tr class="text-start text-muted text-uppercase gs-0">
                                            <th class="min-w-100px">CIUDAD</th>
                                            <th>NOMBRES</th>
                                            <th>PARENTESCO</th>
                                            <th>ESTADO</th>
                                            <th class="min-w-125px">ESTATUS</th>
                                            <th class="min-w-70px">OPCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fs-6 fw-bold text-gray-600">
                                        <?php 
                                            foreach($all_Beneficiario as $ben){
                                            $xBeneid = $ben['Beneid'];
                                            $xBeneficiario = $ben['Beneficiario'];
                                            $xCiudadBen = $ben['Ciudadben'];
                                            $xParentescoBen = $ben['Parentesco'];
                                            $xEstadoBen = $ben['Estadoben'];
                                        ?>
                                        <?php
                                            if($xEstadoBen=='A'){
                                                $xEstadoBen='ACTIVO';
                                            }else{
                                                $xEstadoBen='INACTIVO';
                                            } 

                                            $xCheking = '';
                                            $xDisabledEdit = '';

                                            if($xEstadoBen == 'ACTIVO'){
                                                $xCheking = 'checked="checked"';
                                                $xTextColor = "badge badge-light-primary";
                                            }else{
                                                $xTextColor = "badge badge-light-danger";
                                                $xDisabledEdit = 'disabled';
                                            }
                                        ?>  
                                        <tr id="row_<?php echo $xBeneid; ?>">
                                            <td><?php echo $xCiudadBen; ?></td>
                                            <td><?php echo $xBeneficiario; ?></td>
                                            <td><?php echo $xParentescoBen; ?></td>
                                            <td><?php echo $xEstadoBen; ?></td>
                                            <td>
                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input <?php echo $xCheking; ?> class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk<?php echo $xBeneid; ?>" 
                                                    onchange="f_UpdateEstado()" value=""/>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <div class="btn-group">	
                                                        <button type="button" id="btnEditar_<?php echo $xBeneid; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title='Editar Beneficiario'>
                                                            <i class="fa fa-edit"></i>
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
                    <div class="card pt-4 mb-6 mb-xl-9">
                        <div class="card-header border-0">
                            <div class="card-title">
                                <h2>Logs</h2>
                            </div>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-sm btn-light-primary">
                                <span class="svg-icon svg-icon-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M19 15C20.7 15 22 13.7 22 12C22 10.3 20.7 9 19 9C18.9 9 18.9 9 18.8 9C18.9 8.7 19 8.3 19 8C19 6.3 17.7 5 16 5C15.4 5 14.8 5.2 14.3 5.5C13.4 4 11.8 3 10 3C7.2 3 5 5.2 5 8C5 8.3 5 8.7 5.1 9H5C3.3 9 2 10.3 2 12C2 13.7 3.3 15 5 15H19Z" fill="currentColor" />
                                        <path d="M13 17.4V12C13 11.4 12.6 11 12 11C11.4 11 11 11.4 11 12V17.4H13Z" fill="currentColor" />
                                        <path opacity="0.3" d="M8 17.4H16L12.7 20.7C12.3 21.1 11.7 21.1 11.3 20.7L8 17.4Z" fill="currentColor" />
                                    </svg>
                                </span>
                                Download Report</button>
                            </div>
                        </div>
                        <div class="card-body py-0">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fw-bold text-gray-600 fs-6 gy-5" id="kt_table_users_logs">
                                    <tbody>
                                        <tr>
                                            <td class="min-w-70px">
                                                <div class="badge badge-light-danger">500 ERR</div>
                                            </td>
                                            <td>POST /v1/invoice/in_5315_4014/invalid</td>
                                            <td class="pe-0 text-end min-w-200px">10 Nov 2022, 10:30 am</td>
                                        </tr>
                                        <tr>
                                            <td class="min-w-70px">
                                                <div class="badge badge-light-success">200 OK</div>
                                            </td>
                                            <td>POST /v1/invoices/in_7445_4506/payment</td>
                                            <td class="pe-0 text-end min-w-200px">25 Jul 2022, 11:30 am</td>
                                        </tr>
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
<div class="modal fade" id="modal_persona" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_user_header">
                <h2 class="fw-bolder">Editar Usuario</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form id="kt_modal_add_user_form" class="form" method="post" enctype="multipart/form-data">
                    <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px">
                        <div class="fw-boldest fs-3 rotate collapsible mb-7" data-bs-toggle="collapse" href="#kt_modal_update_user_user_info" role="button" aria-expanded="false" aria-controls="kt_modal_update_user_user_info">Titular
                        <span class="ms-2 rotate-180">
                            <span class="svg-icon svg-icon-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                </svg>
                            </span>
                        </span></div>
                        <div id="kt_modal_update_user_user_info" class="collapse show">
                            <div class="fv-row mb-7">
                                <label class="d-block fw-bold fs-6 mb-5">Avatar</label>
                                <!-- <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('img/default.png')">
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url(img/default.png);" id="imgfile"></div>
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Cambiar Avatar">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" name="avatar" id="imgavatar" accept=".png, .jpg, .jpeg" />
                                        <input type="hidden" name="avatar_remove" />
                                    </label>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancelar Logo">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>													
                                </div> -->
                                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('img/default.png')">
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url(img/default.png);" id="imgfile"></div>
                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Cambiar Avatar">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" name="avatar" id="imgavatar" accept=".png, .jpg, .jpeg" />
                                        <input type="hidden" name="avatar_remove" />
                                    </label>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancelar Logo">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>													
                                </div>
                                <div class="form-text">Archivos permitidos: png, jpg, jpeg.</div>
                            </div>
                            <div class="row g-9 mb-7">
                                <div class="col-md-6 fv-row">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>Nombres</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" id="txtNombre" name="txtNombre" minlength="5" maxlength="100"  value="" readonly/>
                                </div>
                                <div class="col-md-6 fv-row">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>Apellidos</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" id="txtApellido" name="txtApellido" minlength="5" maxlength="100" value="" readonly/>
                                </div>                                                    
                            </div>
                        </div>
                    <div class="fw-boldest fs-3 rotate collapsible mb-7" data-bs-toggle="collapse" href="#kt_modal_update_user_address" role="button" aria-expanded="false" aria-controls="kt_modal_update_user_address">Informacion Titular
                        <span class="ms-2 rotate-180">
                            <span class="svg-icon svg-icon-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                </svg>
                            </span>
                        </span></div>
                        <div id="kt_modal_update_user_address" class="collapse show">
                            <div class="d-flex flex-column mb-7 fv-row">
                                <label class="fs-6 fw-bold mb-2">Direccion</label>
                                <input class="form-control form-control-solid" id="txtDireccion" placeholder="Ingrese Direccion" value="" />
                            </div>
                            <div class="row g-9 mb-7">
                                <div class="col-md-6 fv-row">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>Telefono Casa</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" id="txtTelcasa"  minlength="5" maxlength="100" placeholder="Ingrese Telefono Casa" value=""/>
                                </div>
                                <div class="col-md-6 fv-row">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>Telefono Oficina</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" id="txtTelofi"  minlength="5" maxlength="100" placeholder="Ingrese Telefono Oficina" value=""/>
                                </div>                                                    
                            </div>
                            <div class="row g-9 mb-7">
                                <div class="col-md-6 fv-row">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>Celular</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" id="txtCel"  minlength="5" maxlength="100" placeholder="Ingrese Celular" value=""/>
                                </div>
                                <div class="col-md-6 fv-row">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>Email</span>
                                    </label>
                                    <input type="email" class="form-control form-control-solid" id="txtEmail"  minlength="5" maxlength="100" placeholder="Ingrese Email" value=""/>
                                </div>                                                    
                            </div>
                        </div>
                    </div>
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnSave">
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
<div class="modal fade" id="modal_beneficiario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_user_header">
                <h2 class="fw-bolder">Editar Beneficiario</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form id="kt_modal_add_user_form" class="form" method="post" enctype="multipart/form-data">
                    <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px">
                        <div class="fw-boldest fs-3 rotate collapsible mb-7" data-bs-toggle="collapse" href="#kt_modal_update_user_user_info" role="button" aria-expanded="false" aria-controls="kt_modal_update_user_user_info">Beneficiario
                        <span class="ms-2 rotate-180">
                            <span class="svg-icon svg-icon-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                </svg>
                            </span>
                        </span></div>
                        <div id="kt_modal_update_user_user_info" class="collapse show">
                            
                            <div class="row g-9 mb-7">
                                <div class="col-md-6 fv-row">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>Nombres</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" id="txtNombre" name="txtNombre" minlength="5" maxlength="100"  value="<?php echo $xNombres; ?>" readonly/>
                                </div>
                                <div class="col-md-6 fv-row">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>Apellidos</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" id="txtApellido" name="txtApellido" minlength="5" maxlength="100" value="<?php echo $xApellidos; ?>" readonly/>
                                </div>                                                    
                            </div>
                        </div>
                    <div class="fw-boldest fs-3 rotate collapsible mb-7" data-bs-toggle="collapse" href="#kt_modal_update_user_address" role="button" aria-expanded="false" aria-controls="kt_modal_update_user_address">Informacion Titular
                        <span class="ms-2 rotate-180">
                            <span class="svg-icon svg-icon-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                </svg>
                            </span>
                        </span>
                    </div>
                        <div id="kt_modal_update_user_address" class="collapse show">
                            <div class="d-flex flex-column mb-7 fv-row">
                                <label class="fs-6 fw-bold mb-2">Direccion</label>
                                <input class="form-control form-control-solid" placeholder="Ingrese Direccion" value="<?php echo $xDireccion; ?>" />
                            </div>
                            <div class="row g-9 mb-7">
                                <div class="col-md-6 fv-row">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>Telefono Casa</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" id="txtNombre" name="txtNombre" minlength="5" maxlength="100" placeholder="Ingrese Telefono Casa" value="<?php echo $xTelcasa; ?>"/>
                                </div>
                                <div class="col-md-6 fv-row">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>Telefono Oficina</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" id="txtApellido" name="txtApellido" minlength="5" maxlength="100" placeholder="Ingrese Telefono Oficina" value="<?php echo $xTelofi; ?>"/>
                                </div>                                                    
                            </div>
                            <div class="row g-9 mb-7">
                                <div class="col-md-6 fv-row">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>Celular</span>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" id="txtNombre" name="txtNombre" minlength="5" maxlength="100" placeholder="Ingrese Celular" value="<?php echo $xCel; ?>"/>
                                </div>
                                <div class="col-md-6 fv-row">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span>Email</span>
                                    </label>
                                    <input type="email" class="form-control form-control-solid" id="txtApellido" name="txtApellido" minlength="5" maxlength="100" placeholder="Ingrese Email" value="<?php echo $xEmail; ?>"/>
                                </div>                                                    
                            </div>
                        </div>
                    </div>
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="btnSave">
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


<script>

$(document).ready(function(){


});



// Funcion de regreso de pagina 
function f_Regresar(_clieid,_prodid,_grupid){

        $.redirect('?page=addtitular&menuid=<?php echo $menuid; ?>', {
            'idclie': _clieid,
            'idprod': _prodid,
            'idgrup': _grupid
		});
    
   }
   $(document).on("click",".btnEditarPer",function(){

        
        $("#modal_persona").find("input,textarea,checkbox").val("");

        var _rowid = $(this).attr("id");
        _rowid = _rowid.substring(13);
        _paisid = '<?php echo $xPaisid;?>';
        _emprid = '<?php echo $xEmprid;?>';

        $parametros = {
            xxPerid: _rowid,
            xxPaisid: _paisid,
            xxEmprid: _emprid
        }

        $.ajax({
					url: "codephp/get_datospersona.php",
					type: "POST",
					dataType: "json",
					data: $parametros,          
					success: function(data){ 
						//console.log(data);
						//debugger;
						var _nombre = data[0]['Nombres'];
						var _apellido = data[0]['Apellidos'];
                        var _avatar = data[0]['Imagen'] == '' ? 'default.png' : data[0]['Imagen'];
						var _direccion = data[0]['Direccion'];
                        var _telcasa = data[0]['Telcasa'];
                        var _telofi = data[0]['Telofi'];
                        var _cel = data[0]['Cel'];
                        var _email = data[0]['Email'];


						$("#txtNombre").val(_nombre);
						$("#txtApellido").val(_apellido);
                        document.getElementById('imgfile').style.backgroundImage="url(persona/" + _avatar + ")";
						$("#txtDireccion").val(_direccion);
						$("#txtTelcasa").val(_telcasa);
                        $("#txtTelofi").val(_telofi);
						$("#txtCel").val(_cel);
                        $("#txtEmail").val(_email);
						                                                                      
					},
					error: function (error){
						console.log(error);
					}                            
				}); 
        $("#modal_persona").modal("show");
    });

   $(document).on("click",".btnEditar",function(){

        
        $("#modal_beneficiario").find("input,textarea,checkbox").val("");

        var _rowid = $(this).attr("id");
        _rowid = _rowid.substring(10);

        $("#modal_beneficiario").modal("show");
  

    });

</script>