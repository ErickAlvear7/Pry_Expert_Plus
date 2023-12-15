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

    $xSQL = "SELECT DISTINCT provincia AS Descripcion FROM `provincia_ciudad` ";
	$xSQL .= "WHERE pais_id=$xPaisid AND estado='A' ORDER BY provincia ";
    $all_provincia = mysqli_query($con, $xSQL);


    $xSQL = "SELECT per.pers_id AS Idper,per.pers_numerodocumento AS Docu,CONCAT(per.pers_nombres,' ',per.pers_apellidos) AS Persona,per.pers_imagen AS Imagen, per.pers_fechanacimiento AS Fecha, ";
    $xSQL .="per.pers_ciudad AS Ciudad,per.pers_estado AS Estado FROM `expert_persona` per WHERE per.pers_id = $perid AND per.pais_id=$xPaisid AND per.empr_id=$xEmprid ";
    $persona = mysqli_query($con, $xSQL);

    foreach($persona as $per){
        $xPerid = $per['Idper'];
        $xDocumento = $per['Docu'];
        $xPersona = $per['Persona'];
        $xImagen = $per['Imagen'];
        $xFecha = $per['Fecha'];
        $xCiudad = $per['Ciudad'];
        $xEstado = $per['Estado'];
    }

    if($xEstado=='A'){
        $xestado='ACTIVO';
    }

    $xSQL = "SELECT ciudad AS Ciuper FROM `provincia_ciudad` WHERE prov_id=$xCiudad ";
    $ciudad = mysqli_query($con, $xSQL);

    foreach($ciudad as $ciu){
        $xCiuper = $ciu['Ciuper'];
    }


    $xSQL = "SELECT bene_id AS Beneid,bene_numerodocumento AS Docu,CONCAT(bene_nombres,' ',bene_apellidos) AS Beneficiario, bene_ciudad AS Ciudadben, bene_parentesco AS Parentesco, bene_estado AS Estadoben ";
    $xSQL .= "FROM `expert_beneficiario` WHERE titu_id=$tituid";
    $all_Beneficiario = mysqli_query($con, $xSQL);

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


?>

<div id="kt_content_container" class="container-xxl">
    <input type="hidden" id="mensaje" value="<?php echo $mensaje ?>">
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
            <div class="card mb-5 mb-xl-8">
                <div class="card-body">
                    <div class="d-flex flex-center flex-column py-5">
                        <div class="symbol symbol-100px symbol-circle mb-7">
                            <img src="persona/<?php echo $xImagen; ?>" alt="image" />
                        </div>
                        <label class="fs-3 text-gray-800 fw-bolder mb-3"><?php echo $xPersona; ?></label>
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
                        <div class="fw-bolder rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_details" role="button" aria-expanded="false" aria-controls="kt_user_view_details">Detalle
                        <span class="ms-2 rotate-180">
                            <span class="svg-icon svg-icon-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                </svg>
                            </span>
                        </span></div>
                        <button type="button" id="btnEditarPer_<?php echo $xPerid; ?>" class="btn btn-light-primary btn-sm mb-10 btnEditarPer">
                            <span class="svg-icon svg-icon-2">
                                <!-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                                    <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
                                </svg> -->
                            </span>                                                                
                           Editar
                        </button>
                    </div>
                    <div class="separator"></div>
                    <div id="kt_user_view_details" class="collapse">
                        <div class="pb-5 fs-6">
                            <div class="fw-bolder mt-5">CEDULA</div>
                            <div class="text-gray-600"><?php echo $xDocumento; ?></div>
                            <div class="fw-bolder mt-5">CIUDAD</div>
                            <div class="text-gray-600 text-uppercase"><?php echo $xCiuper; ?></div>
                            <div class="fw-bolder mt-5">FECHA DE NACIMIENTO</div>
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
                      <h2>Opciones</h2>
                    </div>
                </div>
                <div class="separator"></div>
                <div class="card-body pt-2">
                    <button type="button" id="btnNewParen" class="btn btn-light-primary btn-sm mb-10">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                                <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
                            </svg>
                        </span>                                                                
                        Nuevo Parentesco
                    </button>
                </div>   
            </div>
        </div>
        <div id="tab_Addbeneficiarios" class="flex-lg-row-fluid ms-lg-15">
            <div class="d-flex flex-stack fs-4 py-3">
                <div class="d-flex justify-content-start">
                    <a href="#" class="btn btn-light-primary btn-sm" id="btnAgregarbene">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                                <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
                            </svg>
                        </span>                                       
                    Agregar Beneficiario
                    </a>
                </div>
                <button type="button" id="btnRegresar" onclick="f_Regresar(<?php echo $clieid; ?>,<?php echo $prodid; ?>,<?php echo $grupid; ?>)" class="btn btn-icon btn-light-primary btn-sm ms-auto me-lg-n7" title="Regresar" data-bs-toggle="tooltip" data-bs-placement="left">
                    <span class="svg-icon svg-icon-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M11.2657 11.4343L15.45 7.25C15.8642 6.83579 15.8642 6.16421 15.45 5.75C15.0358 5.33579 14.3642 5.33579 13.95 5.75L8.40712 11.2929C8.01659 11.6834 8.01659 12.3166 8.40712 12.7071L13.95 18.25C14.3642 18.6642 15.0358 18.6642 15.45 18.25C15.8642 17.8358 15.8642 17.1642 15.45 16.75L11.2657 12.5657C10.9533 12.2533 10.9533 11.7467 11.2657 11.4343Z" fill="currentColor" />
                        </svg>
                    </span>
                </button>
            </div>
            <div class="card pt-4 mb-6 mb-xl-9">                    
                <div class="card-header border-0">                         
                    <div class="card-title">
                        <h2>Lista de Beneficiarios</h2>
                    </div>   
                </div>
                <div class="card-body pt-0 pb-5">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-row-dashed gy-5" id="tblBeneficiario">
                            <thead class="border-bottom border-gray-200 fs-7 fw-bolder">
                                <tr class="text-start text-muted text-uppercase gs-0">
                                    <th class="min-w-90px">CIUDAD</th>
                                    <th>NOMBRES</th>
                                    <th>DOCUMENTO</th>
                                    <th>PARENTESCO</th>
                                    <th>ESTADO</th>
                                    <th>ESTATUS</th>
                                    <th style="text-align: center;">OPCIONES</th>
                                </tr>
                            </thead>
                            <tbody class="fs-6 fw-bold text-gray-600">
                                <?php 
                                    foreach($all_Beneficiario as $ben){
                                    $xBeneid = $ben['Beneid'];
                                    $xDocu = $ben['Docu'];
                                    $xBeneficiario = $ben['Beneficiario'];
                                    $xCiuben = $ben['Ciudadben'];
                                    $xParenben = $ben['Parentesco'];
                                    $xEstadoBen = $ben['Estadoben'];
                                ?>
                                <?php 

                                    $xSQL = "SELECT ciudad AS Ciuben FROM `provincia_ciudad` WHERE prov_id=$xCiuben ";
                                    $ciudadben = mysqli_query($con, $xSQL);

                                    foreach($ciudadben as $ciuben){
                                        $xCiubene = $ciuben['Ciuben'];
                                    }

                                    $xSQL = "SELECT pade_nombre AS NombrePare FROM `expert_parametro_detalle` WHERE pade_valorV='$xParenben' ";
                                    $parenben = mysqli_query($con, $xSQL);

                                    foreach($parenben as $pare){
                                        $xPareben = $pare['NombrePare'];
                                    }      
                                
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
                                    <td class="text-uppercase"><?php echo $xCiubene; ?></td>
                                    <td><?php echo $xBeneficiario; ?></td>
                                    <td><?php echo $xDocu; ?></td>
                                    <td><?php echo $xPareben; ?></td>
                                    <td id="td_<?php echo $xBeneid; ?>">
                                        <div class="<?php echo $xTextColor; ?>">
                                            <?php echo $xEstadoBen; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input <?php echo $xCheking; ?> class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk<?php echo $xBeneid; ?>" 
                                            onchange="f_UpdateEstado(<?php echo $xBeneid; ?>,<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xUsuaid; ?>)" value=""/>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <div class="btn-group">	
                                                <button type="button" id="btnEditarBe_<?php echo $xBeneid; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditarBe" <?php echo $xDisabledEdit;?> title="Editar Beneficiario" data-bs-toggle="tooltip" data-bs-placement="left" >
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" id="btnAgendar_<?php echo $xBeneid; ?>" name="btnAgendar" onclick="f_Agendar(<?php echo $xBeneid; ?>)" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" <?php echo $xDisabledEdit;?> title='Agendar' data-bs-toggle="tooltip" data-bs-placement="left">
                                                    <i class="fa fa-user-plus"></i>
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
    </div>
</div>
<!--Modal Editar Titular -->
<div class="modal fade" id="modal_persona" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-900px">
        <div class="modal-content"> 
            <div class="modal-header">
                <h2 class="fw-bolder">Editar Titular</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body py-lg-10 px-lg-10">
                <div class="card mb-1 mb-xl-1">
                    <div class="card-header border-0">
                        <div class="card-title">
                            <div class="fw-bolder collapsible collapsed rotate" data-bs-toggle="collapse" href="#view_imagen_titular" role="button" aria-expanded="false" aria-controls="view_imagen_titular">Foto Titular
                                <span class="ms-2 rotate-180">
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                </span>
                            </div> 
                        </div>
                    </div>
                    <div id="view_imagen_titular" class="collapse">
                        <div class="card card-flush py-4">
                            <div class="card-body pt-0">
                                <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('img/account.png')">
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url(img/account.png);" id="imgfile"></div>
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
                        </div>
                    </div>
                </div>
                <div class="card mb-5 mb-xl-8">
                    <div class="card-header border-0">
                        <div class="card-title">
                            <div class="fw-bolder collapsible collapsed rotate" data-bs-toggle="collapse" href="#view_datos_titular" role="button" aria-expanded="false" aria-controls="view_datos_titular">Datos Titular
                                <span class="ms-2 rotate-180">
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                </span>
                            </div> 
                        </div>
                    </div>
                    <div id="view_datos_titular" class="collapse show">
                        <div class="card card-flush py-4">
                            <div class="card-body pt-0">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="required form-label">Nombres</label>
                                        <input type="text" class="form-control form-control-solid text-uppercase" id="txtNombre" name="txtNombre" minlength="5" maxlength="100"  value="" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="required form-label">Apellidos</label>
                                        <input type="text" class="form-control form-control-solid text-uppercase" id="txtApellido" name="txtApellido" minlength="5" maxlength="100" value="" />
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <label class="form-label">Direccion</label>
                                        <textarea class="form-control mb-2" id="txtDireccion" placeholder="Ingrese Direccion" style="text-transform: uppercase;" maxlength="250" rows="1" onkeydown="return(event.keyCode!=13);"></textarea>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Telefono Casa</label>
                                        <input type="text" class="form-control form-control-solid" id="txtTelcasa"  maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" placeholder="Ingrese Telefono Casa" value=""/>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Telefono Oficina</label>
                                        <input type="text" class="form-control form-control-solid" id="txtTelofi"  maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" placeholder="Ingrese Telefono Oficina" value=""/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Celular</label>
                                        <input type="text" class="form-control form-control-solid" id="txtCel"  maxlength="10" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" placeholder="Ingrese Celular" value=""/>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control form-control-solid text-lowercase" id="txtEmail"  minlength="5" maxlength="100" placeholder="Ingrese Email" value=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" id="btnSaveTit" class="btn btn-primary">Modificar</button>
            </div>
        </div>
    </div>
</div>
<!--Modal Editar Beneficiario -->
<div class="modal fade" id="modal_beneficiario" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-900px">
       <div class="modal-content">
            <div class="modal-header">
                <h2>Editar Beneficiario</h2>
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
                <div class="card card-flush py-4">
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="required form-label">Nombres</label>
                                <input type="text" class="form-control form-control-solid text-uppercase" id="txtNombreBeMo" name="txtNombre" minlength="5" maxlength="100"/>
                            </div>
                            <div class="col-md-6">
                                <label class="required form-label">Apellidos</label>
                                <input type="text" class="form-control form-control-solid text-uppercase" id="txtApellidoBeMo" name="txtApellido" minlength="5" maxlength="100" value=""/>
                            </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12">
                                <label class="form-label">Direccion</label>
                                <textarea class="form-control mb-2" id="txtDireccionBeMo" placeholder="Ingrese Direccion" style="text-transform: uppercase;" maxlength="250" rows="1" onkeydown="return(event.keyCode!=13);"></textarea>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                               <label class="form-label">Telefono Casa</label>
                               <input type="text" class="form-control form-control-solid" id="txtTelcasaBeMo" maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" placeholder="Ingrese Telefono Casa" value=""/>
                           </div>
                           <div class="col-md-6">
                               <label class="form-label">Telefono Oficina</label>
                               <input type="text" class="form-control form-control-solid" id="txtTelofiBeMo" maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" placeholder="Ingrese Telefono Oficina" value=""/>
                            </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                               <label class="form-label">Telefono Celular</label>
                               <input type="text" class="form-control form-control-solid" id="txtCelularBeMo" maxlength="10" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" placeholder="Ingrese Celular" value=""/>
                           </div>
                           <div class="col-md-6">
                               <label class="form-label">Email</label>
                               <input type="email" class="form-control form-control-solid" id="txtEmailBeMo"  minlength="5" maxlength="100" placeholder="Ingrese Email" value=""/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btnSaveBene" onclick="f_EditarBene(<?php echo $xUsuaid;?>,<?php echo $xPaisid; ?>,<?php echo $xEmprid;?>)"> 
                    <span class="indicator-label">Modificar</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!--Modal Agregar Parentesco-Beneficiario -->
<div class="modal fade" id="modal_new_paren" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Nuevo Parentesco</h2>
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
                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                    <span class="required">Detalle</span>
                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Especifique el nombre del detalle"></i>
                    </label>
                    <input type="text" class="form-control form-control-solid text-uppercase" id="txtDetalle" name="txtDetalle" minlength="2" maxlength="80" placeholder="nombre del detalle" value="" />
                </div>
                <div class="fv-row mb-15">
                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                    <span class="required">Valor Texto</span>
                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="valor texto ejemplo FFF"></i>
                    </label>
                    <input type="text" class="form-control form-control-solid text-uppercase" id="txtValorV" name="txtValorV" minlength="3" maxlength="3" placeholder="valor texto" value="" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" id="btnGuardar" onclick="f_GuardarParen(<?php echo $xPacaid; ?>,<?php echo $xOrdenDet; ?>)" class="btn btn-primary">Grabar</button>
            </div>
        </div>
    </div>
</div>
<!--Modal Agregar Beneficiario -->
<div class="modal fade" id="modal_addbeneficiario" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-900px">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Agregar Beneficiario</h2>
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
                <div id="view_data" class="card card-flush py-4">
                    <div class="card-body pt-0">
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
                                <select class="form-select mb-2" id="cboAddDocumentoBe" data-control="select2" data-hide-search="true" data-placeholder="Seleccione Tipo Documento">
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
                        <div class="row mb-3">
                           <div class="col-md-3">
                                <label class="required form-label">Nro. Documento</label>
                                <input type="text" class="form-control mb-2" id="txtAddDocumentoBe" value="" maxlength="13" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                           </div>
                           <div class="col-md-4">
                                <label class="required form-label">Genero</label>
                                <select class="form-select mb-2" id="cboAddGeneroBe" data-control="select2" data-hide-search="true" data-placeholder="Seleccione Genero">
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
                                <select class="form-select mb-2" id="cboAddEstadoCivilBe" data-control="select2" data-hide-search="true" data-placeholder="Seleccione Estado Civil">
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
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="required form-label">Nombres</label>
                                <input type="text" class="form-control mb-2" id="txtAddNombreBe" value="" style="text-transform: uppercase;" maxlength="80" placeholder="Ingrese Nombres" />   
                            </div>
                            <div class="col-md-4">
                                <label class="required form-label">Apellidos</label>
                                <input type="text" class="form-control mb-2" id="txtAddApellidoBe" value="" style="text-transform: uppercase;" maxlength="80" placeholder="Ingrese Apellidos" />  
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Fecha de Nacimiento</label>
                                <input type="date" id="txtAddFechaNacimientoBe" class="form-control mb-2" value="" />   
                            </div>
                        </div>
                        <div class="row mb-3">
                           <div class="col-md-6">
                                <label class="required form-label">Provincia</label>
                                <select id="cboProvinciaBe" aria-label="Seleccione Provincia" data-control="select2" data-placeholder="Seleccione Provincia" data-dropdown-parent="#view_data" class="form-select mb-2" >
                                        <option></option>
                                        <?php foreach ($all_provincia as $prov) : ?>
                                            <option value="<?php echo $prov['Descripcion'] ?>"><?php echo mb_strtoupper($prov['Descripcion']) ?></option>
                                        <?php endforeach ?>
                                </select>
                           </div>
                           <div class="col-md-6">
                                <label class="required form-label">Ciudad</label>
                                <select id="cboCiudadBe" aria-label="Seleccione Ciudad" data-control="select2" data-placeholder="Seleccione Ciudad" data-dropdown-parent="#view_data" class="form-select mb-2">
                                        <option></option>
                                </select> 
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Direccion</label>
                                <textarea class="form-control mb-2" id="txtAddDireccionBe" style="text-transform: uppercase;" rows="1" onkeydown="return(event.keyCode!=13);"></textarea> 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Telefono Casa</label>
                                <input type="text" id="txtAddTelCasaBe" class="form-control mb-2 col-md-1" value="" placeholder="022222222" maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;"/>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Telefono Oficina</label>
                                <input type="text" id="txtAddTelOfiBe" class="form-control mb-2 col-md-1" value="" placeholder="022222222" maxlength="9" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;"/>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Telefono Celular</label>
                                <input type="text" id="txtAddCelularBe" class="form-control mb-2 col-md-1" value="" placeholder="0999999999" maxlength="10" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Email</label>
                                <input type="email" id="txtAddEmailBe" class="form-control mb-2 col-md-1 text-lowercase" value="" placeholder="micorreo@gmail.com" maxlength="80" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" id="btnAgregar" class="btn btn-primary">Grabar</button>
            </div>
        </div>
    </div>

</div>

<script>

    var _tituid='<?php echo $tituid; ?>',_prodid='<?php echo $prodid; ?>',_grupid='<?php echo $grupid; ?>',_paisid = '<?php echo $xPaisid; ?>',_emprid = '<?php echo $xEmprid; ?>', 
    _usuaid='<?php echo $xUsuaid ; ?>',_clieid = '<?php echo $clieid; ?>';

    $(document).ready(function(){

        var _mensaje = $('input#mensaje').val();

        if(_mensaje != ''){
            mensajesalertify(_mensaje,"S","top-center",3); 
        }

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

    $("#btnNewParen").click(function(){

      $("#modal_new_paren").modal("show");
    });


    $("#modal_new_paren").draggable({
       handle: ".modal-header"
    });


     // Desplazar Modal
    $("#modal_beneficiario").draggable({
        handle: ".modal-header"
    });

    $("#modal_persona").draggable({
       handle: ".modal-header"
    });

    $("#modal_addbeneficiario").draggable({
       handle: ".modal-header"
    });

    // Funcion de regreso de pagina 
    function f_Regresar(_clieid,_prodid,_grupid){

        $.redirect('?page=addtitular&menuid=<?php echo $menuid; ?>', {
            'idclie': _clieid,
            'idprod': _prodid,
            'idgrup': _grupid
        });
    
    }

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

    //Agregar beneficiario modal
    $('#btnAgregarbene').click(function(){

        $("#modal_addbeneficiario").find('input,textarea').val('').end();
        $("#cboParentesco").val('').change();
        $("#cboAddDocumentoBe").val('').change();
        $("#cboAddGeneroBe").val('').change();
        $("#cboAddGeneroBe").val('').change();
        $("#cboAddEstadoCivilBe").val('').change();
        $("#cboProvinciaBe").val('').change();
        $("#cboCiudadBe").val(0).change();

        $("#modal_addbeneficiario").modal("show");

    });

    // Modal editar titular
   $(document).on("click",".btnEditarPer",function(){

        $("#modal_persona").find("input").val('');

        _persid = $(this).attr("id");
        _persid = _persid.substring(13);
        _paisid = '<?php echo $xPaisid;?>';
        _emprid = '<?php echo $xEmprid;?>';

        $parametros = {
            xxPerid: _persid,
            xxPaisid: _paisid,
            xxEmprid: _emprid
        }

        $.ajax({
            url: "codephp/get_datospersona.php",
            type: "POST",
            dataType: "json",
            data: $parametros,          
            success: function(data){ 
           
                var _nombre = data[0]['Nombres'];
                var _apellido = data[0]['Apellidos'];
                _avatar = data[0]['Imagen'] == '' ? 'imaadd.png' : data[0]['Imagen'];
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

    // Guardar Editar Titular
    $('#btnSaveTit').click(function(e){

        var _nombre = $.trim($("#txtNombre").val());
        var _apellido = $.trim($("#txtApellido").val()); 
        var _direccion = $.trim($("#txtDireccion").val()); 
        var _telcasa = $.trim($("#txtTelcasa").val()); 
        var _telofi = $.trim($("#txtTelofi").val()); 
        var _celular = $.trim($("#txtCel").val()); 
        var _email = $.trim($("#txtEmail").val());
        var _selecc = 'NO';

        var _imgfile = document.getElementById("imgfile").style.backgroundImage;
        var _url = _imgfile.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
        var _pos = _url.trim().indexOf('.');
        var _ext = _url.trim().substr(_pos, 5);

        if(_ext.trim() != '.png' && _ext.trim() != '.jpg' && _ext.trim() != '.jpeg'){
            _selecc = 'SI';
        }                    

        if(_selecc == 'SI'){
            var _imagen = document.getElementById("imgavatar");
            var _file = _imagen.files[0];
            var _fullPath = document.getElementById('imgavatar').value;
            _ext = _fullPath.substring(_fullPath.length - 4);
            _ext = _ext.toLowerCase();   
        }

        if(_ext.trim() != '.png' && _ext.trim() != '.jpg' && _ext.trim() != 'jpeg'){
            mensajesweetalert("center","warning","El archivo seleccionado no es una Imagen..!",false,1800);
            return;
        }

        var form_data = new FormData();
        form_data.append('xxPersid', _persid);           
        form_data.append('xxPaisid', _paisid);
        form_data.append('xxEmprid', _emprid);
        form_data.append('xxUsuaid', _usuaid);
        form_data.append('xxNombre', _nombre);
        form_data.append('xxApellido', _apellido);
        form_data.append('xxDireccion', _direccion);
        form_data.append('xxTelcasa', _telcasa);
        form_data.append('xxTelofi', _telofi);
        form_data.append('xxCelular', _celular);
        form_data.append('xxEmail', _email);
        form_data.append('xxSelecc', _selecc);
        form_data.append('xxAvatar', _avatar);
        form_data.append('xxFile', _file);

        $.ajax({
            url:"codephp/update_titular.php",
            type: "post",
            data: form_data,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response){

                if($.trim(response)=='OK'){

                    $.redirect('?page=edittitular&menuid=<?php echo $menuid; ?>', 
                    { 
                      'idper': _persid,
                      'idtit': _tituid,
                      'idcli': _clieid,
                      'idpro': _prodid,
                      'idgru': _grupid

                    }); //POR METODO POST

                }                            
                                                     
            },								
            error: function (error){
                console.log(error);
            }
        });

    });


    // Modal editar beneficiario
    $(document).on("click",".btnEditarBe",function(){
      
        $("#modal_beneficiario").find("input").val("");

        _rowid = $(this).attr("id");
        _rowid = _rowid.substring(12);
        _paisid = '<?php echo $xPaisid;?>';
        _emprid = '<?php echo $xEmprid;?>';

        var xrespuesta = $.post("codephp/get_datosbeneficiario.php", { xxBeneid: _rowid,xxPaisid:_paisid,xxEmprid: _emprid});
        xrespuesta.done(function(response){

            var _datos = JSON.parse(response);

            _documento = _datos[0].Docu;
            _ciudadben = _datos[0].Ciudad;
            _perenben = _datos[0].Parentesco;
            _estadoben = _datos[0].Estado;

            $("#txtNombreBeMo").val(_datos[0].Nombres);
            $('#txtApellidoBeMo').val(_datos[0].Apellidos);
            $('#txtDireccionBeMo').val(_datos[0].Direccion);
            $('#txtTelcasaBeMo').val(_datos[0].Telcasa);
            $('#txtTelofiBeMo').val(_datos[0].Telofi);
            $('#txtCelularBeMo').val(_datos[0].Celular);
            $('#txtEmailBeMo').val(_datos[0].Email);

            $("#modal_beneficiario").modal("show");

        });
    });

    // Guardar Editar Beneficiario

    function f_EditarBene(_usuaid,_paisid,_emprid){

        var _output;
        var _beneid = _rowid;
        var _nombrebe= $.trim($("#txtNombreBeMo").val());
        var _apellidobe= $.trim($("#txtApellidoBeMo").val());
        var _nombrescombe = _nombrebe.toUpperCase() + ' ' + _apellidobe.toUpperCase();
        var _direccionbe = $.trim($("#txtDireccionBeMo").val());
        var _telcasabe = $.trim($("#txtTelcasaBeMo").val());
        var _telofibe = $.trim($("#txtTelofiBeMo").val()); 
        var _celularbe = $.trim($("#txtCelularBeMo").val()); 
        var _emailbe = $.trim($("#txtEmailBeMo").val());

        if(_nombrebe == ''){
            mensajesalertify("Ingrese Nombre..!!","W","top-right",3);
            return false;
        }

        if(_apellidobe == ''){
            mensajesalertify("Ingrese Apellido..!!","W","top-right",3);
            return false;
        }


        var _parametros = {
            "xxBeneid" : _beneid,
            "xxUsuaid" : _usuaid,
            "xxPaisid" : _paisid,
            "xxEmprid" : _emprid,
            "xxNombre" : _nombrebe,
            "xxApellido" : _apellidobe,
            "xxDireccion" : _direccionbe,
            "xxTelcasa" : _telcasabe,
            "xxTelofi" : _telofibe,
            "xxCelular" : _celularbe,
            "xxEmail" : _emailbe       
        }

        var xrespuesta = $.post("codephp/update_beneficiario.php", _parametros);
        xrespuesta.done(function(response){
         
            if(response.trim() == 'OK'){

                _output ='<td class="text-uppercase">' + _ciudadben + '</td>';
                _output +='<td>' +_nombrescombe + '</td>';
                _output +='<td>' +_documento + '</td>';
                _output +='<td>' +_perenben + '</td>';
                _output +='<td id="td_'+ _beneid + '"><div class="badge badge-light-primary">ACTIVO</div></td>';
                _output +='<td><div class="form-check form-check-sm form-check-custom form-check-solid">';
                _output +='<input checked="checked" class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk'+ _beneid +'" ';
                _output +='onchange="f_UpdateEstado('+ _beneid + ',' + _paisid + ',' + _emprid + ',' + _usuaid + ')" value=""/></div></td>';
                _output +='<td><div class="text-center"><div class="btn-group">';
                _output +='<button type="button" id="btnEditarBe_' + _beneid + '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditarBe" title="Editar Producto" data-bs-toggle="tooltip" data-bs-placement="left">';
                _output +='<i class="fa fa-edit"></i></button>';
                _output +='<button type="button" id="btnAgendar_' + _beneid + '" name="btnAgendar" onclick="f_Agendar('+ _beneid + ')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" ';
                _output +='title="Agendar" data-bs-toggle="tooltip" data-bs-placement="left"><i class="fa fa-user-plus"></i></button></div></div></td>';
              
                $('#row_' + _beneid + '').html(_output);

                $("#modal_beneficiario").modal("hide");
            }
            
        });
    } 

    //Agregar Nuevo Beneficiario directo a la BDD
    
    $('#btnAgregar').click(function(){

        var _cboAddDocumentoBe = $('#cboAddDocumentoBe').val();
        var _txtAddDocumentoBe = $('#txtAddDocumentoBe').val();
        var _txtAddNombreBe = $.trim($("#txtAddNombreBe").val());
        var _txtAddApellidoBe =  $.trim($('#txtAddApellidoBe').val());
        var _txtAddnombresCompletos =  _txtAddNombreBe.toUpperCase() + ' ' + _txtAddApellidoBe.toUpperCase();
        var _cboAddGeneroBe = $('#cboAddGeneroBe').val();
        var _cboAddEstadoCivilBe = $('#cboAddEstadoCivilBe').val();
        var _cboProvinciaBe = $('#cboProvinciaBe').val();
        var _cboCiudadBe = $('#cboCiudadBe').val();
        var _txtCiudadBe = $('#cboCiudadBe').find('option:selected').text();
            _txtCiudadBe.toUpperCase();
        var _txtAddDireccionBe =  $.trim($('#txtAddDireccionBe').val());
        var _txtAddTelCasaBe = $('#txtAddTelCasaBe').val();
        var _txtAddTelOfiBe = $('#txtAddTelOfiBe').val();
        var _txtAddTelCelularBe = $('#txtAddCelularBe').val();
        var _txtAddEmailBe =  $.trim($('#txtAddEmailBe').val());
        var _cboParentesco = $('#cboParentesco').val();
        var _txtParentesco = $('#cboParentesco').find('option:selected').text();
            _txtParentesco.toUpperCase();
        var _fechaAddNacimientoBe = $('#txtAddFechaNacimientoBe').val();

        if(_cboParentesco == ''){
            mensajesalertify("Seleccione Parentesco..!", "W", "top-right", 3);
            return; 
        }

        if(_cboAddDocumentoBe == ''){
            mensajesalertify("Seleccione Tipo Documento..!", "W", "top-right", 3);
            return; 
        }

        if(_txtAddDocumentoBe == ''){
            mensajesalertify("Ingrese Numero de Documento..!", "W", "top-right", 3);
            return; 
        }

        if(_txtAddDocumentoBe.length < 10){
            mensajesalertify("Documento Incorrecto..!", "W", "top-right", 3);
            return; 
        }

        if(_cboAddGeneroBe == ''){
            mensajesalertify("Seleccione Genero..!", "W", "top-right", 3);
            return; 
        }


        if(_txtAddNombreBe == ''){
            mensajesalertify("Ingrese Nombre..!", "W", "top-right", 3);
            return; 
        }

        if(_txtAddApellidoBe == ''){
            mensajesalertify("Ingrese Apellido..!", "W", "top-right", 3);
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

        if(_txtAddTelCelularBe != '')
        {
            _valor = document.getElementById("txtAddCelularBe").value;
            if( !(/^\d{10}$/.test(_valor)) ) {
                mensajesalertify("Celular incorrecto..!" ,"W", "top-right", 3); 
                return;
            }
        }
        
        if(_txtAddEmailBe != ''){
            var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
            if (regex.test(_txtAddEmailBe.trim())){
            }else{
                mensajesalertify("Email Incorrecto..!!","W","top-right",3);
                return false;
            }  
        }


        var _parametro = {
            
            xxProdid: _prodid,
            xxPaisid: _paisid,
            xxEmprid: _emprid,
            xxDocumento: _txtAddDocumentoBe
        }

        var xrespuesta = $.post("codephp/consultar_beneficiario.php", _parametro);
        xrespuesta.done(function(response){

            if(response == 0){
                var _parametros ={

                    "xxTituid" : _tituid,
                    "xxPaisid" : _paisid,
                    "xxEmprid" : _emprid,
                    "xxProdid" : _prodid,
                    "xxUsuaid" : _usuaid,
                    "xxTipodocu" : _cboAddDocumentoBe,
                    "xxDocumento" : _txtAddDocumentoBe,
                    "xxNombres" : _txtAddNombreBe,
                    "xxApellidos" : _txtAddApellidoBe,
                    "xxGenero" : _cboAddGeneroBe,
                    "xxEstadocicvil" : _cboAddEstadoCivilBe,
                    "xxCiudad" : _cboCiudadBe,
                    "xxDireccion" : _txtAddDireccionBe,
                    "xxTelcasa" : _txtAddTelCasaBe,
                    "xxTelofi" : _txtAddTelOfiBe,
                    "xxCelular" : _txtAddTelCelularBe,
                    "xxEmail" : _txtAddEmailBe,
                    "xxParentesco" : _cboParentesco,
                    "xxFechanaci" : _fechaAddNacimientoBe
                }
                
                var xrespuesta = $.post("codephp/grabar_newbeneficiario.php", _parametros);
                xrespuesta.done(function(response){

                    if(response != 0){

                        _id = response;
                            
                        _output = '<tr id="row_' + _id + '">';
                        _output +='<td class="text-uppercase">' + _txtCiudadBe + '</td>';
                        _output +='<td>' +_txtAddnombresCompletos + '</td>';
                        _output +='<td>' +_txtAddDocumentoBe + '</td>';
                        _output +='<td>' +_txtParentesco + '</td>';
                        _output +='<td id="td_'+ _id + '"><div class="badge badge-light-primary">ACTIVO</div></td>';
                        _output +='<td><div class="form-check form-check-sm form-check-custom form-check-solid">';
                        _output +='<input checked="checked" class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk'+ _id +'" ';
                        _output +='onchange="f_UpdateEstado('+ _id + ',' + _paisid + ',' + _emprid + ',' + _usuaid + ')" value=""/></div></td>';
                        _output +='<td><div class="text-center"><div class="btn-group">';
                        _output +='<button type="button" id="btnEditarBe_' + _id + '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditarBe" title="Editar Producto" data-bs-toggle="tooltip" data-bs-placement="left">';
                        _output +='<i class="fa fa-edit"></i></button>';
                        _output +='<button type="button" id="btnAgendar_' + _id + '" name="btnAgendar" onclick="f_Agendar('+ _id + ')" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" ';
                        _output +='title="Agendar" data-bs-toggle="tooltip" data-bs-placement="left"><i class="fa fa-user-plus"></i></button></div></div></td>';
                        _output +='</tr>';

                        $('#tblBeneficiario').append(_output);
                        //mensajesalertify('Agregado Correctamente..!', 'S', 'top-center', 3)

                        //console.log(_output);

                        $("#modal_addbeneficiario").modal("hide");
                        mensajesweetalert('top-center','success','Agregado Correctamente',false,3000);
                    }


                });

            }else{
                mensajesalertify("Beneficiario ya Existe..!!","W","top-right",3);
                return false;
            }

        });
    });

    //Update Estado Beneficiario 
    function f_UpdateEstado(_beneid,_paisid,_emprid,_usuaid){

        var _check = $("#chk" + _beneid).is(":checked");
        var _checked = "";
        var _class = "badge badge-light-primary";
        var _td = "td_" + _beneid;
        var _btnedit = "btnEditarBe_" + _beneid;
        var _btnagen = "btnAgendar_" + _beneid;

        if(_check){
            var _estado = 'ACTIVO';
            _checked = "checked='checked'";
            $('#'+_btnedit).prop("disabled",false);
            $('#'+_btnagen).prop("disabled",false);
                
        }else{
            _estado = 'INACTIVO';
            _class = "badge badge-light-danger";
            $('#'+_btnedit).prop("disabled",true);
            $('#'+_btnagen).prop("disabled",true);
        }

        var _changetd = document.getElementById(_td);
            _changetd.innerHTML = '<div class="' + _class + '">' + _estado + ' </div>';

            var _parametros = {
                "xxBeneid" : _beneid,
                "xxPaisid" : _paisid,
                "xxEmprid" : _emprid,
                "xxUsuaid" : _usuaid,
                "xxEstado" : _estado
            } 

            var xrespuesta = $.post("codephp/update_estadobeneficiario.php", _parametros);
                xrespuesta.done(function(response){
            });	

    }

    //Redireccionar Agendar Beneficiarios
    function f_Agendar(_beneid){
     $.redirect('?page=adminagenda&menuid=<?php echo $menuid; ?>', { 'tituid': _beneid, 'prodid': _prodid, 'grupid': _grupid });
    }  

</script>