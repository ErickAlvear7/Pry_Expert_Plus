
<?php
	
	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

   	//file_put_contents('log_1seguimiento.txt', $xSQL . "\n\n", FILE_APPEND);

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
  
   @session_start();

    if(isset($_SESSION["s_usuario"])){
        if($_SESSION["s_loged"] != "loged"){
            header("Location: ./logout.php");
            exit();
        }
    }else{
        header("Location: ./logout.php");
        exit();
    }    

    $xPaisid = $_SESSION["i_paisid"];
    $xEmprid = $_SESSION["i_emprid"];
    $xUsuaid = $_SESSION["i_usuaid"];

    $xTituid = $_POST['tituid'];
    $xBeneid = $_POST['beneid'];
    $xPresid = $_POST['presaid'];
    $xPreeid = $_POST['preeid'];
    $xPfesid = $_POST['pfesid'];
    $xProdid = $_POST['prodid'];
    $xGrupid = $_POST['grupid'];
    $xCiudid = $_POST['ciudid'];
    
    $xSQL = "SELECT * FROM `expert_prestadora_especialidad` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pree_id=$xPreeid ";
    $all_datos = mysqli_query($con, $xSQL);
    foreach ($all_datos as $datos) {
        $xEspeid = $datos['espe_id'];
    }
    
    $xSQL = "SELECT per.pers_numerodocumento,per.pers_nombres,per.pers_apellidos,per.pers_imagen,(SELECT prv.provincia FROM `provincia_ciudad` prv WHERE per.pers_ciudad=prv.prov_id) AS provincia,(SELECT prv.ciudad FROM `provincia_ciudad` prv WHERE per.pers_ciudad=prv.prov_id) AS ciudad,";
    $xSQL .= "(SELECT pro.prod_nombre FROM `expert_productos` pro WHERE pro.prod_id=tit.prod_id AND pro.pais_id=$xPaisid AND pro.empr_id=$xEmprid) AS producto, (SELECT gru.grup_nombre FROM `expert_grupos` gru WHERE gru.grup_id=tit.grup_id AND gru.pais_id=$xPaisid AND gru.empr_id=$xEmprid) AS grupo,";
    $xSQL .= "per.pers_fechanacimiento,per.pers_direccion,per.pers_celular,per.pers_email,per.pers_estado,tit.prod_id,per.pers_ciudad FROM `expert_titular` tit INNER JOIN `expert_persona` per ON per.pers_id=tit.pers_id ";
    $xSQL .= "WHERE tit.pais_id=$xPaisid AND tit.empr_id=$xEmprid AND tit.titu_id=$xTituid ";
    $all_datos = mysqli_query($con, $xSQL);
    foreach ($all_datos as $datos) {
        $xNumDocumento = $datos['pers_numerodocumento'];
        $xNombres = $datos['pers_nombres'];
        $xApellidos = $datos['pers_apellidos'];
        $xAvatar = $datos['pers_imagen'];
        $xProvincia = $datos['provincia'];
        $xCiudad = $datos['ciudad'];
        $xProducto = $datos['producto'];
        $xGrupo = $datos['grupo'];
        $xFechaNacimiento = $datos['pers_fechanacimiento'];
        $xFechaNacimientoText = $datos['pers_fechanacimiento'];
        $xDireccion = $datos['pers_direccion'];
        $xCelular = $datos['pers_celular'];
        $xEmail = $datos['pers_email'];
        $xEstado = $datos['pers_estado'];
        $xProduid = $datos['prod_id'];
        $xCiudadid = $datos['pers_ciudad'];

        if($xEstado == 'A'){
            $xEstado = 'Activo'; 
        }else{
            $xEstado = 'Inactivo';
        }
   
        $xFechaNacimiento = new DateTime($xFechaNacimiento);
        $xFechaHoy = new DateTime(date("Y-m-d"));
        $xDiferencia = $xFechaHoy->diff($xFechaNacimiento);
        $xEdad =  $xDiferencia->format("%y");             

    }    
    
    $xIntervalo = 30;

    $xSQL = "SELECT * FROM `expert_profesional_especi` ";
	$xSQL .= "WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pfes_id=$xPfesid  ";
    $all_datos = mysqli_query($con, $xSQL);
    foreach ($all_datos as $datos) {
        $xIntervalo = $datos['intervalo'];
    }

?>

    <style>
        .fc-time-grid .fc-slats td {
            height: 2.5em;
        }
    </style>


        <!--begin::Container-->
        <div id="kt_content_container" class="container-xxl">
            <div class="d-flex flex-column flex-lg-row">
                <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
                    <div class="card mb-5 mb-xl-8">
                        <div class="card-body">
                            <div class="d-flex flex-center flex-column py-5">
                                <div class="image-input image-input-empty image-input-outline mb-3" data-kt-image-input="true" style="background-image: url(assets/media/svg/files/blank-image.svg)">
                                    <div class="image-input-wrapper w-150px h-150px" style="background-image: url(assets/media/svg/files/blank-image.svg);" id="imgfiletitular"></div>
                                </div>
                                <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bolder mb-3"><?php echo $xNombres . ' ' . $xApellidos  ?></a>
                                <div class="mb-9">
                                    <div class="badge badge-lg badge-light-primary d-inline"><?php echo  $xEstado; ?></div>
                                </div>
                                <div class="fw-bolder mb-3">Resumen de Citas
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="Numero de citas agendadas, canceladas y atnedidas en el ultimo mes."></i></div>
                                
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
                                        <div class="fw-bold text-muted">Agendadas</div>
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
                                        <div class="fw-bold text-muted">Canceladas</div>
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
                                        <div class="fw-bold text-muted">Atendidas</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-stack fs-4 py-3">
                                <div class="fw-bolder collapsible collapsed rotate" data-bs-toggle="collapse" href="#view_datos_titular" role="button" aria-expanded="false" aria-controls="view_datos_titular">Datos Titular
                                <span class="ms-2 rotate-180">
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                </span></div>
                                <!-- <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Edit customer details">
                                    <a href="#" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_update_details">Edit</a>
                                </span> -->
                            </div>
                            
                            <div class="separator"></div>
                            <div id="view_datos_titular" class="collapse ">
                                <div class="pb-5 fs-6">
                                    
                                    <div class="fw-bolder mt-5">No. Documento</div>
                                    <div class="text-gray-600"><?php echo $xNumDocumento; ?>
                                        <br /><?php echo $xFechaNacimientoText; ?>
                                        <br /><?php echo $xEdad; ?> Años
                                    </div>
                                    
                                    <div class="fw-bolder mt-5">Email</div>
                                    <div class="text-gray-600"><?php echo $xEmail; ?></div>
                                    
                                    <div class="fw-bolder mt-5">Cliente/Producto</div>
                                    <div class="text-gray-600"><?php echo $xCiudad; ?>
                                        <br /><?php echo $xProducto; ?>
                                        <br /><?php echo $xGrupo; ?>
                                    </div>

                                    <div class="fw-bolder mt-5">Direccion</div>
                                    <div class="text-gray-600"><?php echo $xDireccion; ?>
                                        <br /><?php echo $xCelular; ?>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-5 mb-xl-8">

                        <div class="card-header border-0">
                            <div class="card-title">
                                <div class="fw-bolder collapsible collapsed rotate" data-bs-toggle="collapse" href="#view_datos_agenda" role="button" aria-expanded="false" aria-controls="view_datos_agenda">Ultimo Agendamiento
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

                        <div class="separator"></div>
                        <div id="view_datos_agenda" class="collapse ">
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
                                            <div class="fs-6 text-gray-700">Los datos mostrados, pertenecen al ultimo agendamiento realizado, que puede estar en estado cancelado o atentdido
                                                <!-- <a href="#" class="me-1">Ver Historial Agendamiento</a>and
                                                <a href="#">Cancelar Agendamiento</a>. -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="py-2">
                                    <div class="d-flex flex-stack">
                                        <div class="d-flex">
                                            <div class="d-flex flex-column">
                                                <div class="fs-5 text-dark text-hover-primary fw-bolder">Prestrador</div>
                                                <div class="fs-6 fw-bold text-muted">CLINICA AMERICANT ASSIST - NORTE</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex flex-stack">
                                        <div class="d-flex">
                                            <div class="d-flex flex-column">
                                                <div class="fs-5 text-dark text-hover-primary fw-bolder">Fecha</div>
                                                <div class="fs-6 fw-bold text-muted">2023-08-10</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-stack">
                                        <div class="d-flex">
                                            <div class="d-flex flex-column">
                                                <div class="fs-5 text-dark text-hover-primary fw-bolder">Hora</div>
                                                <div class="fs-6 fw-bold text-muted">10:00 - 11:00</div>
                                            </div>
                                        </div>
                                    </div>                                
                                    
                                    <div class="separator separator-dashed my-5"></div>

                                    <div class="d-flex flex-stack">
                                        <div class="d-flex">
                                            <div class="d-flex flex-column">
                                                <div class="fs-5 text-dark text-hover-primary fw-bolder">Profesional</div>
                                                <div class="fs-6 fw-bold text-muted">JUAN PEREZ LOPEZ</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-stack">
                                        <div class="d-flex">
                                            <div class="d-flex flex-column">
                                                <div class="fs-5 text-dark text-hover-primary fw-bolder">Especialidad</div>
                                                <div class="fs-6 fw-bold text-muted">BIOMETRIA HEMATICA - </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-stack">
                                        <div class="d-flex">
                                            <div class="d-flex flex-column">
                                                <div class="fs-5 text-dark text-hover-primary fw-bolder">Motivo</div>
                                                <div class="fs-6 fw-bold text-muted">CHEQUEO GENERAL </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="separator separator-dashed my-2"></div>

                                    <div class="d-flex flex-stack">
                                        <div class="d-flex">
                                            <div class="d-flex flex-column">
                                                <div class="fs-5 text-dark text-hover-primary fw-bolder">Estado</div>
                                                <div class="fs-6 text-primary fw-bold ">AGENDADO</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div id="mycalendar"></div>  
                </div>
            </div>


            <!--Nueva Agenda-->
            <div class="modal fade" id="modal_new_agenda" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    
                    <div class="modal-content">
                        <form class="form" action="#" id="modal_new_agenda_form">
                            <div class="modal-header">
                                <h2 class="fw-bolder" id="title_modal">Registro de Agendamiento</h2>
                                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                    <span class="svg-icon svg-icon-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="modal-body py-10 px-lg-17">
                                <div class="fv-row mb-9">
                                    <label class="fs-6 fw-bold required mb-2">Tipo Registro</label>
                                    <div class="row fv-row">
                                        <div class="col-12">
                                            <select name="cboTipoRegistro" id="cboTipoRegistro" aria-label="Seleccione Registro" data-control="select2" data-placeholder="Seleccione Registro" data-dropdown-parent="#modal_new_agenda_form" class="form-select mb-2">
                                                <option value=""></option>
                                                <option value="Agendar">Agendar</option>
                                                <option value="Informacion">Informacion</option>
                                            </select>
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="fv-row mb-9">
                                    <label class="fs-6 fw-bold required mb-2">Motivo</label>
                                    <div class="row fv-row">
                                        <div class="col-12">
                                            <select name="cboMotivo" id="cboMotivo" aria-label="Seleccione Motivo" data-control="select2" data-placeholder="Seleccione Motivo" data-dropdown-parent="#modal_new_agenda_form" class="form-select mb-2">
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>                                    
                                </div>
                                <div class="fv-row mb-9">
                                    <label class="fs-6 fw-bold required mb-2">Observacion</label>
                                    <textarea class="form-control mb-2 text-uppercase" name="txtObservacion" id="txtObservacion" maxlength="500" onkeydown="return (event.keyCode!=13);"></textarea>
                                </div>
                                <div class="row row-cols-lg-2 g-10">
                                    <div class="col">
                                        <div class="fv-row mb-9">
                                            <label class="fs-6 fw-bold mb-2 ">Fecha Inicio</label>
                                            <input class="form-control form-control-solid" name="fecha_inicio" id="fecha_inicio" placeholder="Seleccione Fecha Inicio" disabled  />
                                        </div>
                                    </div>
                                    <div class="col" data-kt-calendar="datepicker">
                                        <div class="fv-row mb-9">
                                            <label class="fs-6 fw-bold mb-2">Hora Inicio</label>
                                            <input class="form-control form-control-solid" name="hora_inicio" id="hora_inicio"  placeholder="Seleccione Hora Inicio" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row row-cols-lg-2 g-10">
                                    <div class="col">
                                        <div class="fv-row mb-9">
                                            <label class="fs-6 fw-bold mb-2 ">Fecha Fin</label>
                                            <input class="form-control form-control-solid" name="fecha_fin" id="fecha_fin" placeholder="Seleccione Fecha Fin" disabled />
                                        </div>
                                    </div>
                                    <div class="col" data-kt-calendar="datepicker">
                                        <div class="fv-row mb-9">
                                            <label class="fs-6 fw-bold mb-2">Hora Fin</label>
                                            <input class="form-control form-control-solid" name="hora_fin" id="hora_fin" placeholder="Seleccione Hora Fin" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer pt-15">
                                <button type="reset" data-bs-dismiss="modal" class="btn btn-light me-3">Cerrar</button>
                                <button type="button" id="btnAgendar" class="btn btn-primary">
                                    <span class="indicator-label">Agendar</span>
                                    <span class="indicator-progress">Espreme un momento...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--end::Modal - New Product-->

            <div class="modal fade" id="kt_modal_add_schedule" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bolder">Add an Event</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--end::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                            <!--begin::Form-->
                            <form id="kt_modal_add_schedule_form" class="form" action="#">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-bold form-label mb-2">Event Name</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" class="form-control form-control-solid" name="event_name" value="" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold form-label mb-2">
                                        <span class="required">Date &amp; Time</span>
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="Select a date &amp; time."></i>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control form-control-solid" placeholder="Pick date &amp; time" name="event_datetime" id="kt_modal_add_schedule_datepicker" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-bold form-label mb-2">Event Organiser</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" class="form-control form-control-solid" name="event_org" value="" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-bold form-label mb-2">Send Event Details To</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input id="kt_modal_add_schedule_tagify" type="text" class="form-control form-control-solid" name="event_invitees" value="smith@kpmg.com, melody@altbox.com" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Actions-->
                                <div class="text-center pt-15">
                                    <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">Discard</button>
                                    <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
                <!--end::Modal dialog-->
            </div>

            <div class="modal fade" id="kt_modal_add_task" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bolder">Add a Task</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--end::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                            <!--begin::Form-->
                            <form id="kt_modal_add_task_form" class="form" action="#">
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-bold form-label mb-2">Task Name</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" class="form-control form-control-solid" name="task_name" value="" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold form-label mb-2">
                                        <span class="required">Task Due Date</span>
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="Select a due date."></i>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control form-control-solid" placeholder="Pick date" name="task_duedate" id="kt_modal_add_task_datepicker" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold form-label mb-2">Task Description</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <textarea class="form-control form-control-solid rounded-3"></textarea>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Actions-->
                                <div class="text-center pt-15">
                                    <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">Discard</button>
                                    <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
                <!--end::Modal dialog-->
            </div>

            <div class="modal fade" id="kt_modal_update_email" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bolder">Update Email Address</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--end::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                            <!--begin::Form-->
                            <form id="kt_modal_update_email_form" class="form" action="#">
                                <!--begin::Notice-->
                                <!--begin::Notice-->
                                <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 p-6">
                                    <!--begin::Icon-->
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen044.svg-->
                                    <span class="svg-icon svg-icon-2tx svg-icon-primary me-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                            <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor" />
                                            <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    <!--end::Icon-->
                                    <!--begin::Wrapper-->
                                    <div class="d-flex flex-stack flex-grow-1">
                                        <!--begin::Content-->
                                        <div class="fw-bold">
                                            <div class="fs-6 text-gray-700">Please note that a valid email address is required to complete the email verification.</div>
                                        </div>
                                        <!--end::Content-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Notice-->
                                <!--end::Notice-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold form-label mb-2">
                                        <span class="required">Email Address</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input class="form-control form-control-solid" placeholder="" name="profile_email" value="smith@kpmg.com" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Actions-->
                                <div class="text-center pt-15">
                                    <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">Discard</button>
                                    <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
                <!--end::Modal dialog-->
            </div>

            <div class="modal fade" id="kt_modal_update_password" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bolder">Update Password</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--end::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                            <!--begin::Form-->
                            <form id="kt_modal_update_password_form" class="form" action="#">
                                <!--begin::Input group=-->
                                <div class="fv-row mb-10">
                                    <label class="required form-label fs-6 mb-2">Current Password</label>
                                    <input class="form-control form-control-lg form-control-solid" type="password" placeholder="" name="current_password" autocomplete="off" />
                                </div>
                                <!--end::Input group=-->
                                <!--begin::Input group-->
                                <div class="mb-10 fv-row" data-kt-password-meter="true">
                                    <!--begin::Wrapper-->
                                    <div class="mb-1">
                                        <!--begin::Label-->
                                        <label class="form-label fw-bold fs-6 mb-2">New Password</label>
                                        <!--end::Label-->
                                        <!--begin::Input wrapper-->
                                        <div class="position-relative mb-3">
                                            <input class="form-control form-control-lg form-control-solid" type="password" placeholder="" name="new_password" autocomplete="off" />
                                            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
                                                <i class="bi bi-eye-slash fs-2"></i>
                                                <i class="bi bi-eye fs-2 d-none"></i>
                                            </span>
                                        </div>
                                        <!--end::Input wrapper-->
                                        <!--begin::Meter-->
                                        <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                            <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                        </div>
                                        <!--end::Meter-->
                                    </div>
                                    <!--end::Wrapper-->
                                    <!--begin::Hint-->
                                    <div class="text-muted">Use 8 or more characters with a mix of letters, numbers &amp; symbols.</div>
                                    <!--end::Hint-->
                                </div>
                                <!--end::Input group=-->
                                <!--begin::Input group=-->
                                <div class="fv-row mb-10">
                                    <label class="form-label fw-bold fs-6 mb-2">Confirm New Password</label>
                                    <input class="form-control form-control-lg form-control-solid" type="password" placeholder="" name="confirm_password" autocomplete="off" />
                                </div>
                                <!--end::Input group=-->
                                <!--begin::Actions-->
                                <div class="text-center pt-15">
                                    <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">Discard</button>
                                    <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
                <!--end::Modal dialog-->
            </div>

            <div class="modal fade" id="kt_modal_update_role" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bolder">Update User Role</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--end::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                            <!--begin::Form-->
                            <form id="kt_modal_update_role_form" class="form" action="#">
                                <!--begin::Notice-->
                                <!--begin::Notice-->
                                <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 p-6">
                                    <!--begin::Icon-->
                                    <!--begin::Svg Icon | path: icons/duotune/general/gen044.svg-->
                                    <span class="svg-icon svg-icon-2tx svg-icon-primary me-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                            <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor" />
                                            <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <!--end::Svg Icon-->
                                    <!--end::Icon-->
                                    <!--begin::Wrapper-->
                                    <div class="d-flex flex-stack flex-grow-1">
                                        <!--begin::Content-->
                                        <div class="fw-bold">
                                            <div class="fs-6 text-gray-700">Please note that reducing a user role rank, that user will lose all priviledges that was assigned to the previous role.</div>
                                        </div>
                                        <!--end::Content-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Notice-->
                                <!--end::Notice-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold form-label mb-5">
                                        <span class="required">Select a user role</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input row-->
                                    <div class="d-flex">
                                        <!--begin::Radio-->
                                        <div class="form-check form-check-custom form-check-solid">
                                            <!--begin::Input-->
                                            <input class="form-check-input me-3" name="user_role" type="radio" value="0" id="kt_modal_update_role_option_0" checked='checked' />
                                            <!--end::Input-->
                                            <!--begin::Label-->
                                            <label class="form-check-label" for="kt_modal_update_role_option_0">
                                                <div class="fw-bolder text-gray-800">Administrator</div>
                                                <div class="text-gray-600">Best for business owners and company administrators</div>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Radio-->
                                    </div>
                                    <!--end::Input row-->
                                    <div class='separator separator-dashed my-5'></div>
                                    <!--begin::Input row-->
                                    <div class="d-flex">
                                        <!--begin::Radio-->
                                        <div class="form-check form-check-custom form-check-solid">
                                            <!--begin::Input-->
                                            <input class="form-check-input me-3" name="user_role" type="radio" value="1" id="kt_modal_update_role_option_1" />
                                            <!--end::Input-->
                                            <!--begin::Label-->
                                            <label class="form-check-label" for="kt_modal_update_role_option_1">
                                                <div class="fw-bolder text-gray-800">Developer</div>
                                                <div class="text-gray-600">Best for developers or people primarily using the API</div>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Radio-->
                                    </div>
                                    <!--end::Input row-->
                                    <div class='separator separator-dashed my-5'></div>
                                    <!--begin::Input row-->
                                    <div class="d-flex">
                                        <!--begin::Radio-->
                                        <div class="form-check form-check-custom form-check-solid">
                                            <!--begin::Input-->
                                            <input class="form-check-input me-3" name="user_role" type="radio" value="2" id="kt_modal_update_role_option_2" />
                                            <!--end::Input-->
                                            <!--begin::Label-->
                                            <label class="form-check-label" for="kt_modal_update_role_option_2">
                                                <div class="fw-bolder text-gray-800">Analyst</div>
                                                <div class="text-gray-600">Best for people who need full access to analytics data, but don't need to update business settings</div>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Radio-->
                                    </div>
                                    <!--end::Input row-->
                                    <div class='separator separator-dashed my-5'></div>
                                    <!--begin::Input row-->
                                    <div class="d-flex">
                                        <!--begin::Radio-->
                                        <div class="form-check form-check-custom form-check-solid">
                                            <!--begin::Input-->
                                            <input class="form-check-input me-3" name="user_role" type="radio" value="3" id="kt_modal_update_role_option_3" />
                                            <!--end::Input-->
                                            <!--begin::Label-->
                                            <label class="form-check-label" for="kt_modal_update_role_option_3">
                                                <div class="fw-bolder text-gray-800">Support</div>
                                                <div class="text-gray-600">Best for employees who regularly refund payments and respond to disputes</div>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Radio-->
                                    </div>
                                    <!--end::Input row-->
                                    <div class='separator separator-dashed my-5'></div>
                                    <!--begin::Input row-->
                                    <div class="d-flex">
                                        <!--begin::Radio-->
                                        <div class="form-check form-check-custom form-check-solid">
                                            <!--begin::Input-->
                                            <input class="form-check-input me-3" name="user_role" type="radio" value="4" id="kt_modal_update_role_option_4" />
                                            <!--end::Input-->
                                            <!--begin::Label-->
                                            <label class="form-check-label" for="kt_modal_update_role_option_4">
                                                <div class="fw-bolder text-gray-800">Trial</div>
                                                <div class="text-gray-600">Best for people who need to preview content data, but don't need to make any updates</div>
                                            </label>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Radio-->
                                    </div>
                                    <!--end::Input row-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Actions-->
                                <div class="text-center pt-15">
                                    <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">Discard</button>
                                    <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
                <!--end::Modal dialog-->
            </div>

            <div class="modal fade" id="kt_modal_add_auth_app" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bolder">Add Authenticator App</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--end::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                            <!--begin::Content-->
                            <div class="fw-bolder d-flex flex-column justify-content-center mb-5">
                                <!--begin::Label-->
                                <div class="text-center mb-5" data-kt-add-auth-action="qr-code-label">Download the
                                <a href="#">Authenticator app</a>, add a new account, then scan this barcode to set up your account.</div>
                                <div class="text-center mb-5 d-none" data-kt-add-auth-action="text-code-label">Download the
                                <a href="#">Authenticator app</a>, add a new account, then enter this code to set up your account.</div>
                                <!--end::Label-->
                                <!--begin::QR code-->
                                <div class="d-flex flex-center" data-kt-add-auth-action="qr-code">
                                    <img src="assets/media/misc/qr.png" alt="Scan this QR code" />
                                </div>
                                <!--end::QR code-->
                                <!--begin::Text code-->
                                <div class="border rounded p-5 d-flex flex-center d-none" data-kt-add-auth-action="text-code">
                                    <div class="fs-1">gi2kdnb54is709j</div>
                                </div>
                                <!--end::Text code-->
                            </div>
                            <!--end::Content-->
                            <!--begin::Action-->
                            <div class="d-flex flex-center">
                                <div class="btn btn-light-primary" data-kt-add-auth-action="text-code-button">Enter code manually</div>
                                <div class="btn btn-light-primary d-none" data-kt-add-auth-action="qr-code-button">Scan barcode instead</div>
                            </div>
                            <!--end::Action-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
                <!--end::Modal dialog-->
            </div>

            <div class="modal fade" id="kt_modal_add_one_time_password" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bolder">Enable One Time Password</h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
                                <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                    </svg>
                                </span>
                                <!--end::Svg Icon-->
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--end::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                            <!--begin::Form-->
                            <form class="form" id="kt_modal_add_one_time_password_form">
                                <!--begin::Label-->
                                <div class="fw-bolder mb-9">Enter the new phone number to receive an SMS to when you log in.</div>
                                <!--end::Label-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold form-label mb-2">
                                        <span class="required">Mobile number</span>
                                        <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="A valid mobile number is required to receive the one-time password to validate your account login."></i>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" class="form-control form-control-solid" name="otp_mobile_number" placeholder="+6123 456 789" value="" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Separator-->
                                <div class="separator saperator-dashed my-5"></div>
                                <!--end::Separator-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold form-label mb-2">
                                        <span class="required">Email</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="email" class="form-control form-control-solid" name="otp_email" value="smith@kpmg.com" readonly="readonly" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Input group-->
                                <div class="fv-row mb-7">
                                    <!--begin::Label-->
                                    <label class="fs-6 fw-bold form-label mb-2">
                                        <span class="required">Confirm password</span>
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="password" class="form-control form-control-solid" name="otp_confirm_password" value="" />
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Actions-->
                                <div class="text-center pt-15">
                                    <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">Cancel</button>
                                    <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                        <span class="indicator-label">Submit</span>
                                        <span class="indicator-progress">Please wait...
                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
                <!--end::Modal dialog-->
            </div>

        </div>



    <script>

        $(document).ready(function(){
            
            var _paisid = "<?php echo $xPaisid; ?>";
            var _emprid = "<?php echo $xEmprid; ?>";
            var _presid = "<?php echo $xPresid; ?>";
            var _espeid = "<?php echo $xEspeid; ?>";
            var _pfesid = "<?php echo $xPfesid; ?>";
            var _usuaid = "<?php echo $xUsuaid; ?>";            
            var _interval = "<?php echo $xIntervalo; ?>";
            var _tituid = "<?php echo $xTituid; ?>";
            var _beneid = "<?php echo $xBeneid; ?>";

            var _prodid = "<?php echo $xProdid; ?>";
            var _grupid = "<?php echo $xGrupid; ?>";
            var _ciudid = "<?php echo $xCiudid; ?>";

            var _dayselect = 0;
            var _fechainicio;
            var _fechafin;
            var _dayname = '';
            var _avatar = "<?php echo $xAvatar; ?>";

            document.getElementById('imgfiletitular').style.backgroundImage="url(persona/" + _avatar + ")";

            var popover;
            var popoverState = false;            

            var calendar;

            var data = {
                id: '',
                eventName: '',
                eventDescription: '',
                startDate: '',
                endDate: '',
                allDay: false
            };            

            //Obtener configuracion de horarios

            var _parametros = {
                "xxPaisid" : _paisid,
                "xxEmprid" : _emprid,
                "xxPfesid" : _pfesid
            }           
            
            /*var _hours = [
                {
                    "daysOfWeek" : "[3]",
                    "startTime" : "08:00",
                    "endTime" : "16:00"
                }
            ]*/

            $.ajax({
                url: "codephp/get_turnoshorarios.php",
                type: "post",
                data: _parametros,
                dataType: "json",
                success: function(response){

                    var _hours = response;
                    var _jsonObj = JSON.stringify(response);
                    var _json = JSON.parse(_jsonObj);
                    var _interval = _json[0].intervalo;

                    //console.log(_hours);

                    /*var hours   = Math.floor(_interval / 3600);
                    var minutes = Math.floor((_interval - (hours * 3600)) / 60);
                    var seconds = _interval - (hours * 3600) - (minutes * 60);

                    if (hours   < 10) {hours   = "0"+hours;}
                    if (minutes < 10) {minutes = "0"+minutes;}
                    if (seconds < 10) {seconds = "0"+seconds;}*/
                    
                    var _slot = '00:' + _interval + ':00';                    
                    
                    var calendarEl = document.getElementById('mycalendar');

                    //$('#mycalendar').fullCalendar();
                    calendar = new FullCalendar.Calendar(calendarEl, {

                        locale: 'es',
                        initialView: 'timeGridWeek',
                        //initialView: 'dayGridMonth',
                        headerToolbar: {
                            left: 'prev, next, today',
                            center: 'title',
                            //right: 'dayGridMonth,timeGridWeek,timeGridDay'
                            right: 'timeGridWeek,timeGridDay'
                        },
                        navLinks: true, // can click day/week names to navigate views
                        editable: true,
                        selectable: true,
                        selectMirror: true,
                        dayMaxEvents: true, // allow "more" link when too many events   
                        select: function(arg) {
                            f_Selecc(arg);
                        },
                        events: {
                            url: 'codephp/get_reservacitas.php',
                            method: 'POST',
                            extraParams: {
                                xxPaisid: _paisid,
                                xxEmprid: _emprid,
                                xxPresid: _presid,
                                xxEspeid: _espeid,
                                xxPfesid: _pfesid
                            },
                            failure: function() {
                                alert('Existe un error en construccion JSON');
                            }
                        
                        },
                        datesSet: function(){
                            hidePopovers();
                        },
                        /*dateClick: function(info) {
                            f_Selecc(info)
                            alert('Clicked on: ' + info.date);
                            alert('Current view: ' + info.view.type);
                            alert('Active Start: ' + info.view.activeStart);
                            alert('Active End: ' + info.view.activeEnd);

                        },*/
                        eventClick: function (arg) {
                            hidePopovers();
                            f_DelAgenda(arg);
                        },
                        eventMouseEnter: function (arg) {
                            f_ViewDatos(arg);
                            /*formatArgs({
                                id: arg.event.id,
                                title: arg.event.title,
                                description: arg.event.extendedProps.description,
                                location: arg.event.extendedProps.location,
                                startStr: arg.event.startStr,
                                endStr: arg.event.endStr,
                                allDay: arg.event.allDay
                            });
                            initPopovers(arg.el);*/
                        },
                        // eventSources: [
                        //     {
                        //         url: "codephp/get_turnoshorarios.php",
                        //         type: "post",
                        //         data: _parametros,
                        //         dataType: "json",
                        //         success: function(response){
                        //             debugger;
                        //             _hours = response;
                        //         },								
                        //         error: function (error){
                        //             console.log(error);
                        //         }                        
                        //     }
                        // ],                                      
                        businessHours: _hours,
                        slotDuration: _slot,
                        slotMinutes: _interval
                        
                    });       
                    calendar.render();             
                },								
                error: function (error){
                    console.log(error);
                }
            });              


            $('#fecha_inicio').flatpickr({
                enableTime: false,
                dateFormat: "Y-m-d",                
            });

            $('#fecha_inicio').flatpickr({
                enableTime: false,
                dateFormat: "Y-m-d",                
            });            

            $('#hora_inicio').flatpickr({
                 enableTime: true,
                 noCalendar: true,
                 dateFormat: "H:i",
            });

            $('#hora_fin').flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
            });

            function f_Selecc(info){

                var _continuar = false;

                var _dateactual = moment(info.date).format("YYYY-MM-DD");
                var _dateselec = moment(info.startStr).format("YYYY-MM-DD");

                if(_dateselec < _dateactual){
                    mensajesalertify("Seleccione una fecha superior o igual a la fecha en curso..!", "W", "top-center", 5);
                    return;
                }

                //validar que ha seleccionado solo el intervalo configurado
                let _horaini = moment(info.startStr);
                let _horafin = moment(info.endStr); 

                let _mindiferen = _horafin.diff(_horaini, "m");
                if(_mindiferen != parseInt(_interval) ){
                    mensajesalertify("Seleccione correctamente el horario de atención, el intervalo configurado es de " + _interval + " minutos" , "W", "top-center", 5);
                    return;
                }

                let _fechaactual = new Date();
                let _daynow = _fechaactual.getDay();
                let _diferenminuts = 0;

                //let _hour = _fechaactual.getHours();
                //let _min = _fechaactual.getMinutes();
                //_min = _min < 10 ? '0' + _min : _min;
                //let _horaactual = _hour + ':' + _min;
                //let _timeselect = new Date(info.endStr);

                let _horaactual = moment(_fechaactual);
                //let _horaselect = moment(info.endStr);
                let _horaselect = moment(info.startStr);
                
                /*let _minuactual = _fechaactual.getMinutes();
                let _minselect = new Date(info.startStr).getMinutes();

                if(_minuactual > _minselect){
                    _diferenminuts = _minuactual - _minselect;
                }else{
                    _diferenminuts = _minselect - _minuactual;
                }*/

                //_diferenminuts = _horaselect.diff(_horaactual, "m");
                _diferenminuts = _horaactual.diff(_horaselect, "m");
                //SUMAR 10 MINUTOS A LA DIFERENCIA, PARA DARLES 10 MINUTOS MAS
                //_diferenminuts = moment(_diferenminuts).add(10,'m').format("HH:mm");
                
                if(_diferenminuts > 5){
                    mensajesalertify("La hora seleccionada esta fuera del intervalo de..! " + _interval + " minutos" , "W", "top-center", 5);
                    return;
                }

                //alert('Current view: ' + info.view.type);
                //alert('Dia: ' + info.view.dateEnv.weekText );
                //alert('Date Now :'  +   dateactual) ;
                //alert('Date Select :'  +   dateselec);

                _timeinicio = moment(info.startStr).format("HH:mm");
                _timefin = moment(info.endStr).format("HH:mm");

                _dayselect = new Date(info.startStr).getDay();

                switch(_dayselect){
                    case 0:
                        _dayname = 'DOMINGO';
                        break;
                    case 1:
                        _dayname = 'LUNES';
                        break;
                    case 2:
                        _dayname = 'MARTES';
                        break;
                    case 3:
                        _dayname = 'MIERCOLES';
                        break;
                    case 4:
                        _dayname = 'JUEVES'; 
                        break;
                    case 5:
                        _dayname = 'VIERNES';
                        break;
                    case 6:
                        _dayname = 'SABADO';
                        break;
                }                

                $("#hora_inicio").prop('disabled','disabled');
                $("#hora_fin").prop('disabled','disabled');             

                var _parametros = {
                    "xxPaisid" : _paisid,
                    "xxEmprid" : _emprid,
                    "xxPfesid" : _pfesid,
                    "xxCodDia" : _dayselect,
                    "xHini" : _timeinicio,
                    "xHfin" : _timefin                    
                }

                _fechainicio = _dateselec + ' ' + _timeinicio;
                _fechafin = _dateselec + ' ' + _timefin;

                var _respuesta = $.post("codephp/get_horariodisponible.php", _parametros);
                _respuesta.done(function(response) {
                    if(response.trim() == 'OK'){
                        _continuar = true;

                        //validar la hora si esta con el tiempo adecuado para agendar, al menos con 1 hora de anticipacion
                        if(_daynow == _dayselect ){
                            if(_diferenminuts > 5){
                                //$('#mycalendar').FullCalendar('unselect');
                                //calendar.unselect();
                                //var calendarEl = document.getElementById('mycalendar');
                                //calendarEl.unselect();
                                _continuar = false;
                                mensajesalertify("El horario seleccionado esta fuera del tiempo programado..!" , "W", "top-center", 5);
                                return;   
                            }
                            /*if(_diferenminuts > 0 && _diferenminuts < 31){
                                _continuar = false;
                                mensajesalertify("El horario seleccionado esta fuera del tiempo programado..!" , "W", "top-center", 10);
                                return;                             
                            }*/
                        }    
                        
                        if(_continuar){

                            //BUSCAR SI NO EXISTE ALGUNA RESERVA ANTES
                            var _buscareserva = {
                                "xxPaisid" : _paisid,
                                "xxEmprid" : _emprid,                
                                "xxPresid" : _presid,
                                "xxEspeid" : _espeid,
                                "xxPfesid" : _pfesid,
                                "xxCiudid" : _ciudid,
                                "xxFechaInicio" : _fechainicio,
                                "xxFechaFin" : _fechafin,
                                "xxHoraDesde" : _timeinicio,
                                "xxHoraHasta" : _timefin,
                                "xxCodigoDia" : _dayselect,
                                "xxDia" : _dayname,
                                "xxUsuaId" : _usuaid
                            }

                            var _consreserva = $.post("codephp/consultar_reserva.php", _buscareserva);
                            _consreserva.done(function(respreserva){
                                
                                if(respreserva == 0){
                                    
                                    f_LimpiarModal();

                                    $("#fecha_inicio").val(_dateselec);
                                    $("#fecha_fin").val(_dateselec);
                                    $("#hora_inicio").val(_timeinicio);
                                    $("#hora_fin").val(_timefin);

                                    $("#modal_new_agenda").modal("show");                                     

                                }else{
                                    mensajesalertify("El horario no está disponible, se encuentra reservado..!" , "W", "top-center", 5);
                                    return; 
                                }
                            });
                        }
                    }else{
                        mensajesalertify("No existe configurado turno del día seleccionado" , "W", "top-center", 5);
                        return;   
                    }
                });

                if(info.view.type == 'dayGridMonth'){
                
                    $.ajax({
                        url: "codephp/get_turnoshorarios.php",
                        type: "post",
                        data: _parametros,
                        dataType: "json",
                        success: function(response){
                            var _hours = response;
                            var _jsonObj = JSON.stringify(response);
                            var _json = JSON.parse(_jsonObj);

                            for (var i = 0; i < _hours.length; i++) {
                                if (_hours[i].daysOfWeek == _dayselect) {
                                    _continuar = true;
                                    break;
                                }
                            }                            

                            if(_continuar){

                                _interval = _json[0].intervalo;
                                _timeinicio = _json[0].startTime;
                                _timefin = _json[0].endTime;

                                //let _todayDate = new Date().toISOString().slice(0, 10);

                                let _fechainistr = _dateselec + ' ' + _timeinicio;
                                let _fechainilst = new Date(_fechainistr);

                                let _fechafinstr = _dateselec + ' ' + _timefin;
                                let _fechafinlst = new Date(_fechafinstr);                            

                                _timeinicio = moment(_fechainistr).format("HH:mm");
                                _timefin = moment(_fechainistr).add(_interval,'m').format("HH:mm");

                                $("#fecha_inicio").val(_dateactual);
                                $("#fecha_fin").val(_dateactual);
                                $("#hora_inicio").val(_timeinicio);
                                $("#hora_fin").val(_timefin);

                                $("#modal_new_agenda").modal("show");
                            }else{
                                mensajesalertify("Profesional no tiene definido horario el dia seleccionado..!", "W", "top-center", 5);
                                return;                                
                            }
                        },								
                        error: function (error){
                            console.log(error);
                        }                        
                    });                 
                }else{
                            
                }
            }

            /*$(document).on('hide.bs.modal','#modal_new_agenda', function () {
            });*/

            $('#modal_new_agenda').on('hidden.bs.modal', function () {

                var _parametros = {
                    "xxPaisid" : _paisid,
                    "xxEmprid" : _emprid,
                    "xxPresid" : _presid,
                    "xxEspeid" : _espeid,
                    "xxPfesid" : _pfesid,
                    "xxFechaInicio" : _fechainicio,
                    "xxFechaFin" : _fechafin,
                    "xxCodigoDia" : _dayselect
                }                

                var _respuesta = $.post("codephp/del_reservatmp.php", _parametros);
                _respuesta.done(function(response){
                });                
            });

            $('#cboTipoRegistro').change(function(){
                    
                _cboid = $(this).val(); //obtener el id seleccionado
                $("#cboMotivo").empty();

                if(_cboid == 'Agendar'){
                    var _parametros = {
                        "xxPaisId" : _paisid,
                        "xxEmprId" : _emprid,
                        "xxComboId" : _espeid,
                        "xxOpcion" : 2
                    }

                    var _respuesta = $.post("codephp/cargar_combos.php", _parametros);
                    _respuesta.done(function(response) {
                        $("#cboMotivo").html(response);
                        
                    });
                    _respuesta.fail(function() {
                    });
                    _respuesta.always(function() {
                    });
                }else{ 
                    var _html = "<option value=''></option>";
                    _html += "<option value='Informacion'>Informacion</option>";

                    $("#cboMotivo").html(_html);
                }

            });            

            $('#btnAgendar').click(function(){

                var _tiporegistro = $('#cboTipoRegistro').val();
                var _motivo = $('#cboMotivo').val();
                var _observacion = $('#txtObservacion').val();
                var _fechainicio = $('#fecha_inicio').val();
                var _fechafinal = $('#fecha_fin').val();
                var _horainicio = $('#hora_inicio').val();
                var _horafin = $('#hora_fin').val();
                var _tipocliente = "T";

                if(_tiporegistro == ''){
                    mensajesalertify("Seleccione Tipo de Registro ", "W", "top-center", 5);
                    return;
                }

                if(_motivo == ''){
                    mensajesalertify("Seleccione Motivo agenda ", "W", "top-center", 5);
                    return;
                }

                if(_observacion == ''){
                    mensajesalertify("Ingrese Observacion de la agenda ", "W", "top-center", 5);
                    return;
                }

                let _fechaselect = _fechainicio + ' ' + _horainicio;
                _fechaselect = new Date(_fechaselect);
                let _fechacatual = new Date();

                let _horaselect = moment(_fechaselect);
                let _horaactual = moment(_fechacatual);

                _diferenminuts = _horaactual.diff(_horaselect, "m");
                //SUMAR 10 MINUTOS A LA DIFERENCIA, PARA DARLES 10 MINUTOS MAS
                //_diferenminuts = moment(_diferenminuts).add(10,'m').format("HH:mm");

                let _fechaAgenda = new Date(_fechaselect);
                let _fechaView = new Date();

                _fechaAgenda = moment(_fechaAgenda).format("YYYY-MM-DD");
                _fechaView = moment(_fechaView).format("YYYY-MM-DD");
                
                if(_fechaAgenda == _fechaView){

                    if(_diferenminuts > 5){
                        mensajesalertify("La hora seleccionada esta fuera del intervalo de..! " + _interval + " minutos" , "W", "top-center", 5);
                        return;
                    }
                }

                let _fechainiagenda = _fechainicio + ' ' + _horainicio;
                let _fechafinagenda = _fechafinal + ' ' + _horafin;

                if(parseInt(_beneid) > 0){
                    _tipocliente = "B";
                }

                var _parametros = {
                    "xxPaisid" : _paisid,
                    "xxEmprid" : _emprid,
                    "xxTipoCliente" : _tipocliente,
                    "xxTituid" : _tituid,
                    "xxBeneid" : _beneid,
                    "xxCiudid" : _ciudid,
                    "xxProdid" : _prodid,
                    "xxGrupid" : _grupid,
                    "xxPresid" : _presid,
                    "xxEspeid" : _espeid,
                    "xxPfesid" : _pfesid,
                    "xxFechaIni" : _fechainiagenda,
                    "xxFechaFin" : _fechafinagenda,
                    "xxCodigoDia" : _dayselect,
                    "xxDia" : _dayname,
                    "xxHoraDesde" : _horainicio,
                    "xxHoraHasta" : _horafin,
                    "xxTipoRegistro" : _tiporegistro,
                    "xxMotivoRegistro" : _motivo,
                    "xxObservacion" : _observacion.toUpperCase(),
                    "xxEstadoAgenda" : "A",
                    "xxColor" : "#117A65",
                    "xxTextColor" : "#060606",
                    "xxUsuaid" : _usuaid,
                }

                var _respuesta = $.post("codephp/agendar_cita.php", _parametros);
                _respuesta.done(function(response){
                    if(response >= 0){
                        $.redirect('?page=adminagenda&menuid=<?php echo $menuid; ?>', { 'tituid': _tituid, 'prodid': _prodid, 'grupid': _grupid });
                    }else{
                        Swal.fire({
                            text: "Error en el envio del correo, valide la informacion",
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok,regresar!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                    
                });
                _respuesta.fail(function() {
                });
                _respuesta.always(function() {
                });

            });            

            function f_ViewDatos(arg){

                hidePopovers();

                let _fechareserva = moment(arg.event.startStr).format("YYYY-MM-DD");
                
                element = arg.el;
                //const popoverHtml = '<div class="fw-bolder mb-2">' + arg.event.extendedProps.description + '</div><div class="fs-7"><span class="fw-bold">Reserva:</span> ' + _fechareserva + '</div><div class="fs-7 mb-4"><span class="fw-bold">End:</span> ' + arg.event.id + '</div><div id="btnViewReserva" type="button" class="btn btn-sm btn-light-primary">View More</div>';
                const popoverHtml = '<div class="fw-bolder mb-2">' + arg.event.extendedProps.description + '</div><div class="fs-7 mb-2"><span class="fw-bold">Reserva:</span> ' + _fechareserva + '</div><div class="fs-7"><span class="fw-bold">Hora Inicio:</span> ' + arg.event.extendedProps.horaini + '</div><div class="fs-7 mb-2"><span class="fw-bold">Hora Fin:</span> ' + arg.event.extendedProps.horafin + '</div><div class="fs-7"><span class="fw-bold">Agent:</span> ' + arg.event.extendedProps.username + '</div>';

                // Popover options
                var options = {
                    container: 'body',
                    trigger: 'manual',
                    boundary: 'window',
                    placement: 'auto',
                    dismiss: true,
                    html: true,
                    title: 'Reserva Temporal',
                    content: popoverHtml,
                }

                // Initialize popover
                popover = KTApp.initBootstrapPopover(element, options);

                // Show popover
                popover.show();     
                popoverState = true;       

            }

            function f_DelAgenda(arg){
                
                //alert('Borrar agenda, si es reservatmp elimina solo el usuario, si es agenda, mostrar form para cancelar');
                /*$("#fecha_inicio").val(_dateactual);
                $("#fecha_fin").val(_dateactual);
                $("#hora_inicio").val(_timeinicio);
                $("#hora_fin").val(_timefin);*/
                let _fechareserva = moment(arg.event.startStr).format("YYYY-MM-DD");

                $("#modal_new_agenda").modal("show");
            }
            
            const hidePopovers = () => {
                if (popoverState) {
                    popover.dispose();
                    popoverState = false;
                }
            }        
            
            const f_LimpiarModal = () => {
                $('#cboTipoRegistro').val('').change();
                $('#cboMotivo').empty();
                var _html = "<option value=''></option>";
                $("#cboMotivo").html(_html);
                $('#txtObservacion').val('');
                $('#fecha_inicio').val('');
                $('#hora_inicio').val('');
                $('#fecha_fin').val('');
                $('#hora_fin').val('');
            }

        });

        //var myModalEl = document.getElementById('modal_new_agenda')
        //    myModalEl.addEventListener('hidden.bs.modal', function (event) {
        //});

        //document.getElementById('imgfiletitular').style.backgroundImage="url(persona/" + _avatar + ")";

        //Desplazar-modal
        $("#modal-prestador").draggable({
            handle: ".modal-header"
        });        

    </script>