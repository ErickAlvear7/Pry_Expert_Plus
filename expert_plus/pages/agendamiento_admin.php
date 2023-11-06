
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
    $xProdid = $_POST['prodid'];
    $xGrupid = $_POST['grupid'];

    if(!isset($_POST['tituid'])){
        if($_POST['tituid'] == ''){
            header("Location: ./logout.php");
            exit();            
        }
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

    $xSQL = "SELECT DISTINCT provincia AS Descripcion FROM `provincia_ciudad` ";
	$xSQL .= "WHERE pais_id=$xPaisid AND estado='A' ORDER BY provincia ";
    $all_provincia = mysqli_query($con, $xSQL);

?>
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
                                        <br /><?php echo $xEdad; ?> A���os
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

                <div class="flex-lg-row-fluid ms-lg-15">
                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8">
                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4 active" data-kt-countup-tabs="true" data-bs-toggle="tab" href="#tabTitular">Datos Agendamiento</a>
                        </li>                

                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#tabBeneficiario">Datos Beneficiario</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-active-primary pb-4 " data-bs-toggle="tab" href="#tabHistorial">Historial Citas</a>
                        </li>

                        <!-- <li class="nav-item ms-auto">
                            <a href="#" class="btn btn-primary ps-7" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">Actions
                                <span class="svg-icon svg-icon-2 me-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                    </svg>
                                </span>
                            </a>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold py-4 w-250px fs-6" data-kt-menu="true">
                                <div class="menu-item px-5">
                                    <div class="menu-content text-muted pb-2 px-5 fs-7 text-uppercase">Payments</div>
                                </div>
                                <div class="menu-item px-5">
                                    <a href="#" class="menu-link px-5">Create invoice</a>
                                </div>
                                <div class="menu-item px-5">
                                    <a href="#" class="menu-link flex-stack px-5">Create payments
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference"></i></a>
                                </div>
                                <div class="menu-item px-5" data-kt-menu-trigger="hover" data-kt-menu-placement="left-start">
                                    <a href="#" class="menu-link px-5">
                                        <span class="menu-title">Subscription</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-5">Apps</a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-5">Billing</a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-5">Statements</a>
                                        </div>
                                        <div class="separator my-2"></div>
                                        <div class="menu-item px-3">
                                            <div class="menu-content px-3">
                                                <label class="form-check form-switch form-check-custom form-check-solid">
                                                    <input class="form-check-input w-30px h-20px" type="checkbox" value="" name="notifications" checked="checked" id="kt_user_menu_notifications" />
                                                    <span class="form-check-label text-muted fs-6" for="kt_user_menu_notifications">Notifications</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="separator my-3"></div>
                                <div class="menu-item px-5">
                                    <div class="menu-content text-muted pb-2 px-5 fs-7 text-uppercase">Account</div>
                                </div>
                                <div class="menu-item px-5">
                                    <a href="#" class="menu-link px-5">Reports</a>
                                </div>
                                <div class="menu-item px-5 my-1">
                                    <a href="#" class="menu-link px-5">Account Settings</a>
                                </div>
                                <div class="menu-item px-5">
                                    <a href="#" class="menu-link text-danger px-5">Delete customer</a>
                                </div>
                            </div>
                        </li> -->

                    </ul>
                    
                    
                    <div class="tab-content" id="tabOpciones">
                        <!--DATOS AGENDAMIENTO-->
                        <div class="tab-pane fade show active" id="tabTitular" role="tabpanel">
                            
                            <div class="card pt-4 mb-6 mb-xl-9">
                                <div class="card-header border-0">
                                    <div class="card-title">
                                        <h2>Datos para Agendar</h2>
                                    </div>
                                </div>

                                <div class="card-body pt-0 pb-5">
                                    <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                                        <div class="col">
                                            <div class="fv-row mb-7">
                                                <label class="fs-6 fw-bold form-label mt-3">
                                                    <span class="required">Provincia</span>
                                                    <!-- <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Enter the contact's email."></i> -->
                                                </label>
                                                <select name="cboProvincia" id="cboProvincia" aria-label="Seleccione Provincia" data-control="select2" data-placeholder="Seleccione Provincia" data-dropdown-parent="#tabTitular" class="form-select mb-2" >
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
                                                    <!-- <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Enter the contact's phone number (optional)."></i> -->
                                                </label>
                                                <?php 
                                                    $xSQL = "SELECT prov_id AS Codigo, ciudad AS Descripcion FROM `provincia_ciudad` ";
	                                                $xSQL .= "WHERE pais_id=$xPaisid AND provincia='$xProvincia' AND estado='A' ORDER BY ciudad ";
                                                    $all_ciudad = mysqli_query($con, $xSQL);
                                                ?>
                                                <select name="cboCiudad" id="cboCiudad" aria-label="Seleccione Ciudad" data-control="select2" data-placeholder="Seleccione Ciudad" data-dropdown-parent="#tabTitular" class="form-select mb-2">
                                                    <?php foreach ($all_ciudad as $ciudad) : ?>
                                                        <option value="<?php echo $ciudad['Codigo'] ?>"><?php echo mb_strtoupper($ciudad['Descripcion']) ?></option>
                                                    <?php endforeach ?>
                                                </select>                                                      
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-5 fv-row">
                                        <label class="fs-6 fw-bold form-label mt-3">
                                            <span class="required">Prestador</span>
                                            <button type="button" id="btnDatosPrestador" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" data-kt-menu-placement="bottom-end" title="Datos del Prestador">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </label>
                                        <?php 
                                            $xSQL = "SELECT pres_id AS Codigo, pres_nombre AS Descripcion FROM `expert_prestadora` WHERE pais_id=$xPaisid and empr_id=$xEmprid AND prov_id=$xCiudadid AND pres_estado='A' ";
                                            file_put_contents('log_1seguimiento.txt', $xSQL . "\n\n", FILE_APPEND);
                                            $all_prestadora = mysqli_query($con, $xSQL);
                                        ?>                                        
                                        <select name="cboPrestador" id="cboPrestador" aria-label="Seleccione Prestador" data-control="select2" data-placeholder="Seleccione Prestador" data-dropdown-parent="#tabTitular" class="form-select mb-2">
                                            <option></option>
                                            <?php foreach ($all_prestadora as $ciudad) : ?>
                                                <option value="<?php echo $ciudad['Codigo'] ?>"><?php echo mb_strtoupper($ciudad['Descripcion']) ?></option>
                                            <?php endforeach ?>                                            
                                        </select> 
                                    </div>

                                    <div class="mb-5 fv-row">
                                        <label class="fs-6 fw-bold form-label mt-3">
                                            <span class="required">Especialidad</span>
                                        </label>
                                        <select name="cboEspecialidad" id="cboEspecialidad" aria-label="Seleccione Especialidad" data-control="select2" data-placeholder="Seleccione Especialidad" data-dropdown-parent="#tabTitular" class="form-select mb-2">
                                            <option></option>
                                        </select> 
                                    </div>

                                    <div class="mb-5 fv-row">
                                        <label class="fs-6 fw-bold form-label mt-3">
                                            <span class="required">Profesional</span>
                                            <button type="button" id="btnDatosProfesional" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" data-kt-menu-placement="bottom-end" title="Datos del Profesional">
                                                <i class="bi bi-search"></i>
                                            </button>                                            
                                        </label>
                                        <select name="cboProfesional" id="cboProfesional" aria-label="Seleccione Profesional" data-control="select2" data-placeholder="Seleccione Profesional" data-dropdown-parent="#tabTitular" class="form-select mb-2">
                                            <option></option>
                                        </select> 
                                    </div>
                                    

                                </div>                                
                            </div>
                            
                            <!-- <div class="card pt-4 mb-6 mb-xl-9">
                                <div class="card-header border-0">
                                    <div class="card-title flex-column">
                                        <h2 class="mb-1">Two Step Authentication</h2>
                                        <div class="fs-6 fw-bold text-muted">Keep your account extra secure with a second authentication step.</div>
                                    </div>
                                    <div class="card-toolbar">
                                        <button type="button" class="btn btn-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path opacity="0.3" d="M21 10.7192H3C2.4 10.7192 2 11.1192 2 11.7192C2 12.3192 2.4 12.7192 3 12.7192H6V14.7192C6 18.0192 8.7 20.7192 12 20.7192C15.3 20.7192 18 18.0192 18 14.7192V12.7192H21C21.6 12.7192 22 12.3192 22 11.7192C22 11.1192 21.6 10.7192 21 10.7192Z" fill="currentColor" />
                                                    <path d="M11.6 21.9192C11.4 21.9192 11.2 21.8192 11 21.7192C10.6 21.4192 10.5 20.7191 10.8 20.3191C11.7 19.1191 12.3 17.8191 12.7 16.3191C12.8 15.8191 13.4 15.4192 13.9 15.6192C14.4 15.7192 14.8 16.3191 14.6 16.8191C14.2 18.5191 13.4 20.1192 12.4 21.5192C12.2 21.7192 11.9 21.9192 11.6 21.9192ZM8.7 19.7192C10.2 18.1192 11 15.9192 11 13.7192V8.71917C11 8.11917 11.4 7.71917 12 7.71917C12.6 7.71917 13 8.11917 13 8.71917V13.0192C13 13.6192 13.4 14.0192 14 14.0192C14.6 14.0192 15 13.6192 15 13.0192V8.71917C15 7.01917 13.7 5.71917 12 5.71917C10.3 5.71917 9 7.01917 9 8.71917V13.7192C9 15.4192 8.4 17.1191 7.2 18.3191C6.8 18.7191 6.9 19.3192 7.3 19.7192C7.5 19.9192 7.7 20.0192 8 20.0192C8.3 20.0192 8.5 19.9192 8.7 19.7192ZM6 16.7192C6.5 16.7192 7 16.2192 7 15.7192V8.71917C7 8.11917 7.1 7.51918 7.3 6.91918C7.5 6.41918 7.2 5.8192 6.7 5.6192C6.2 5.4192 5.59999 5.71917 5.39999 6.21917C5.09999 7.01917 5 7.81917 5 8.71917V15.7192V15.8191C5 16.3191 5.5 16.7192 6 16.7192ZM9 4.71917C9.5 4.31917 10.1 4.11918 10.7 3.91918C11.2 3.81918 11.5 3.21917 11.4 2.71917C11.3 2.21917 10.7 1.91916 10.2 2.01916C9.4 2.21916 8.59999 2.6192 7.89999 3.1192C7.49999 3.4192 7.4 4.11916 7.7 4.51916C7.9 4.81916 8.2 4.91918 8.5 4.91918C8.6 4.91918 8.8 4.81917 9 4.71917ZM18.2 18.9192C18.7 17.2192 19 15.5192 19 13.7192V8.71917C19 5.71917 17.1 3.1192 14.3 2.1192C13.8 1.9192 13.2 2.21917 13 2.71917C12.8 3.21917 13.1 3.81916 13.6 4.01916C15.6 4.71916 17 6.61917 17 8.71917V13.7192C17 15.3192 16.8 16.8191 16.3 18.3191C16.1 18.8191 16.4 19.4192 16.9 19.6192C17 19.6192 17.1 19.6192 17.2 19.6192C17.7 19.6192 18 19.3192 18.2 18.9192Z" fill="currentColor" />
                                                </svg>
                                            </span>
                                            Add Authentication Step
                                        </button>
                                        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-6 w-200px py-4" data-kt-menu="true">
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_modal_add_auth_app">Use authenticator app</a>
                                            </div>
                                            <div class="menu-item px-3">
                                                <a href="#" class="menu-link px-3" data-bs-toggle="modal" data-bs-target="#kt_modal_add_one_time_password">Enable one-time password</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pb-5">
                                    <div class="d-flex flex-stack">
                                        <div class="d-flex flex-column">
                                            <span>SMS</span>
                                            <span class="text-muted fs-6">+61 412 345 678</span>
                                        </div>
                                        <div class="d-flex justify-content-end align-items-center">
                                            <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto me-5" data-bs-toggle="modal" data-bs-target="#kt_modal_add_one_time_password">
                                                <span class="svg-icon svg-icon-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor" />
                                                        <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                            </button>
                                            <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" id="kt_users_delete_two_step">
                                                <span class="svg-icon svg-icon-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor" />
                                                        <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor" />
                                                        <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-5"></div>
                                    <div class="text-gray-600">If you lose your mobile device or security key, you can
                                    <a href='#' class="me-1">generate a backup code</a>to sign in to your account.</div>
                                </div>
                            </div> -->
                            
                            <div class="card pt-4 mb-6 mb-xl-9">
                                <div class="card-header border-0">
                                    <div class="card-title">
                                        <h2 class="fw-bolder mb-0">Agendamientos Reservados</h2>
                                    </div>
                                    <div class="card-toolbar">
                                        <button class="btn btn-sm btn-flex btn-light-primary" id="btnNuevaAgenda" >
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
                                                    <rect x="10.8891" y="17.8033" width="12" height="2" rx="1" transform="rotate(-90 10.8891 17.8033)" fill="currentColor" />
                                                    <rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
                                                </svg>
                                            </span>
                                            Nuevo Agendamiento
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="card-body pt-0">
                                    <div class="py-0" >
                                        <div class="py-3 d-flex flex-stack flex-wrap">
                                            <div class="d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#agendamiento1" role="button" aria-expanded="false" aria-controls="agendamiento1">
                                                <div class="me-3 rotate-90">
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <path d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z" fill="currentColor" />
                                                        </svg>
                                                    </span>
                                                </div>

                                                <img src="assets/media/svg/card-logos/mastercard.svg" class="w-40px me-3" alt="" />
                                                
                                                <div class="me-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="text-gray-800 fw-bolder">Mastercard</div>
                                                        <div class="badge badge-light-primary ms-5">Primary</div>
                                                    </div>
                                                    <div class="text-muted">Expires Dec 2024</div>
                                                </div>
                                                
                                            </div>
                                            
                                            <div class="d-flex my-3 ms-9">
                                                <a href="#" class="btn btn-icon btn-active-light-primary w-30px h-30px me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_new_card">
                                                    <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Edit">
                                                        <span class="svg-icon svg-icon-3">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor" />
                                                                <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                    </span>
                                                </a>
                                                
                                                <a href="#" class="btn btn-icon btn-active-light-primary w-30px h-30px me-3" data-bs-toggle="tooltip" title="Delete" data-kt-customer-payment-method="delete">
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor" />
                                                            <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor" />
                                                            <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor" />
                                                        </svg>
                                                    </span>
                                                </a>
                                                
                                                <a href="#" class="btn btn-icon btn-active-light-primary w-30px h-30px" data-bs-toggle="tooltip" title="More Options" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="currentColor" />
                                                            <path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="currentColor" />
                                                        </svg>
                                                    </span>
                                                </a>
                                                
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold w-150px py-3" data-kt-menu="true">
                                                    <div class="menu-item px-3">
                                                        <a href="#" class="menu-link px-3" data-kt-payment-mehtod-action="set_as_primary">Set as Primary</a>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                        <div id="agendamiento1" class="collapse fs-6 ps-10" data-bs-parent="#agendamiento1">
                                            <div class="d-flex flex-wrap py-5">
                                                <div class="flex-equal me-5">
                                                    <table class="table table-flush fw-bold gy-1">
                                                        <tr>
                                                            <td class="text-muted min-w-125px w-125px">Name</td>
                                                            <td class="text-gray-800">Emma Smith</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted min-w-125px w-125px">Number</td>
                                                            <td class="text-gray-800">**** 3769</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted min-w-125px w-125px">Expires</td>
                                                            <td class="text-gray-800">12/2024</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted min-w-125px w-125px">Type</td>
                                                            <td class="text-gray-800">Mastercard credit card</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted min-w-125px w-125px">Issuer</td>
                                                            <td class="text-gray-800">VICBANK</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted min-w-125px w-125px">ID</td>
                                                            <td class="text-gray-800">id_4325df90sdf8</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <!--end::Col-->

                                                <!--begin::Col-->
                                                <div class="flex-equal">
                                                    <table class="table table-flush fw-bold gy-1">
                                                        <tr>
                                                            <td class="text-muted min-w-125px w-125px">Billing address</td>
                                                            <td class="text-gray-800">AU</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted min-w-125px w-125px">Phone</td>
                                                            <td class="text-gray-800">No phone provided</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted min-w-125px w-125px">Email</td>
                                                            <td class="text-gray-800">
                                                                <a href="#" class="text-gray-900 text-hover-primary">smith@kpmg.com</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted min-w-125px w-125px">Origin</td>
                                                            <td class="text-gray-800">Australia
                                                            <div class="symbol symbol-20px symbol-circle ms-2">
                                                                <img src="assets/media/flags/australia.svg" />
                                                            </div></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted min-w-125px w-125px">CVC check</td>
                                                            <td class="text-gray-800">Passed
                                                                <span class="svg-icon svg-icon-2 svg-icon-success">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
                                                                        <path d="M10.4343 12.4343L8.75 10.75C8.33579 10.3358 7.66421 10.3358 7.25 10.75C6.83579 11.1642 6.83579 11.8358 7.25 12.25L10.2929 15.2929C10.6834 15.6834 11.3166 15.6834 11.7071 15.2929L17.25 9.75C17.6642 9.33579 17.6642 8.66421 17.25 8.25C16.8358 7.83579 16.1642 7.83579 15.75 8.25L11.5657 12.4343C11.2533 12.7467 10.7467 12.7467 10.4343 12.4343Z" fill="currentColor" />
                                                                    </svg>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="separator separator-dashed"></div>
                                    

                                </div>
                            </div>
                        </div>

                        <!--DATOS BENEFICIARIO-->
                        <div class="tab-pane fade" id="tabBeneficiario" role="tabpanel">
                            <!--begin::Card-->
                            <div class="card pt-4 mb-6 mb-xl-9">
                                <!--begin::Card header-->
                                <div class="card-header border-0">
                                    <!--begin::Card title-->
                                    <div class="card-title">
                                        <h2>Login Sessions</h2>
                                    </div>
                                    <!--end::Card title-->
                                    <!--begin::Card toolbar-->
                                    <div class="card-toolbar">
                                        <!--begin::Filter-->
                                        <button type="button" class="btn btn-sm btn-flex btn-light-primary" id="kt_modal_sign_out_sesions">
                                        <!--begin::Svg Icon | path: icons/duotune/arrows/arr077.svg-->
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <rect opacity="0.3" x="4" y="11" width="12" height="2" rx="1" fill="currentColor" />
                                                <path d="M5.86875 11.6927L7.62435 10.2297C8.09457 9.83785 8.12683 9.12683 7.69401 8.69401C7.3043 8.3043 6.67836 8.28591 6.26643 8.65206L3.34084 11.2526C2.89332 11.6504 2.89332 12.3496 3.34084 12.7474L6.26643 15.3479C6.67836 15.7141 7.3043 15.6957 7.69401 15.306C8.12683 14.8732 8.09458 14.1621 7.62435 13.7703L5.86875 12.3073C5.67684 12.1474 5.67684 11.8526 5.86875 11.6927Z" fill="currentColor" />
                                                <path d="M8 5V6C8 6.55228 8.44772 7 9 7C9.55228 7 10 6.55228 10 6C10 5.44772 10.4477 5 11 5H18C18.5523 5 19 5.44772 19 6V18C19 18.5523 18.5523 19 18 19H11C10.4477 19 10 18.5523 10 18C10 17.4477 9.55228 17 9 17C8.44772 17 8 17.4477 8 18V19C8 20.1046 8.89543 21 10 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3H10C8.89543 3 8 3.89543 8 5Z" fill="#C4C4C4" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->Sign out all sessions</button>
                                        <!--end::Filter-->
                                    </div>
                                    <!--end::Card toolbar-->
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body pt-0 pb-5">
                                    <!--begin::Table wrapper-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-dashed gy-5" id="kt_table_users_login_session">
                                            <!--begin::Table head-->
                                            <thead class="border-bottom border-gray-200 fs-7 fw-bolder">
                                                <!--begin::Table row-->
                                                <tr class="text-start text-muted text-uppercase gs-0">
                                                    <th class="min-w-100px">Location</th>
                                                    <th>Device</th>
                                                    <th>IP Address</th>
                                                    <th class="min-w-125px">Time</th>
                                                    <th class="min-w-70px">Actions</th>
                                                </tr>
                                                <!--end::Table row-->
                                            </thead>
                                            <!--end::Table head-->
                                            <!--begin::Table body-->
                                            <tbody class="fs-6 fw-bold text-gray-600">
                                                <tr>
                                                    <!--begin::Invoice=-->
                                                    <td>Australia</td>
                                                    <!--end::Invoice=-->
                                                    <!--begin::Status=-->
                                                    <td>Chome - Windows</td>
                                                    <!--end::Status=-->
                                                    <!--begin::Amount=-->
                                                    <td>207.26.18.342</td>
                                                    <!--end::Amount=-->
                                                    <!--begin::Date=-->
                                                    <td>23 seconds ago</td>
                                                    <!--end::Date=-->
                                                    <!--begin::Action=-->
                                                    <td>Current session</td>
                                                    <!--end::Action=-->
                                                </tr>
                                                <tr>
                                                    <!--begin::Invoice=-->
                                                    <td>Australia</td>
                                                    <!--end::Invoice=-->
                                                    <!--begin::Status=-->
                                                    <td>Safari - iOS</td>
                                                    <!--end::Status=-->
                                                    <!--begin::Amount=-->
                                                    <td>207.33.48.357</td>
                                                    <!--end::Amount=-->
                                                    <!--begin::Date=-->
                                                    <td>3 days ago</td>
                                                    <!--end::Date=-->
                                                    <!--begin::Action=-->
                                                    <td>
                                                        <a href="#" data-kt-users-sign-out="single_user">Sign out</a>
                                                    </td>
                                                    <!--end::Action=-->
                                                </tr>
                                                <tr>
                                                    <!--begin::Invoice=-->
                                                    <td>Australia</td>
                                                    <!--end::Invoice=-->
                                                    <!--begin::Status=-->
                                                    <td>Chrome - Windows</td>
                                                    <!--end::Status=-->
                                                    <!--begin::Amount=-->
                                                    <td>207.24.50.215</td>
                                                    <!--end::Amount=-->
                                                    <!--begin::Date=-->
                                                    <td>last week</td>
                                                    <!--end::Date=-->
                                                    <!--begin::Action=-->
                                                    <td>Expired</td>
                                                    <!--end::Action=-->
                                                </tr>
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Table wrapper-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card-->
                            <!--begin::Card-->
                            <div class="card pt-4 mb-6 mb-xl-9">
                                <!--begin::Card header-->
                                <div class="card-header border-0">
                                    <!--begin::Card title-->
                                    <div class="card-title">
                                        <h2>Logs</h2>
                                    </div>
                                    <!--end::Card title-->
                                    <!--begin::Card toolbar-->
                                    <div class="card-toolbar">
                                        <!--begin::Button-->
                                        <button type="button" class="btn btn-sm btn-light-primary">
                                        <!--begin::Svg Icon | path: icons/duotune/files/fil021.svg-->
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3" d="M19 15C20.7 15 22 13.7 22 12C22 10.3 20.7 9 19 9C18.9 9 18.9 9 18.8 9C18.9 8.7 19 8.3 19 8C19 6.3 17.7 5 16 5C15.4 5 14.8 5.2 14.3 5.5C13.4 4 11.8 3 10 3C7.2 3 5 5.2 5 8C5 8.3 5 8.7 5.1 9H5C3.3 9 2 10.3 2 12C2 13.7 3.3 15 5 15H19Z" fill="currentColor" />
                                                <path d="M13 17.4V12C13 11.4 12.6 11 12 11C11.4 11 11 11.4 11 12V17.4H13Z" fill="currentColor" />
                                                <path opacity="0.3" d="M8 17.4H16L12.7 20.7C12.3 21.1 11.7 21.1 11.3 20.7L8 17.4Z" fill="currentColor" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->Download Report</button>
                                        <!--end::Button-->
                                    </div>
                                    <!--end::Card toolbar-->
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body py-0">
                                    <!--begin::Table wrapper-->
                                    <div class="table-responsive">
                                        <!--begin::Table-->
                                        <table class="table align-middle table-row-dashed fw-bold text-gray-600 fs-6 gy-5" id="kt_table_users_logs">
                                            <!--begin::Table body-->
                                            <tbody>
                                                <!--begin::Table row-->
                                                <tr>
                                                    <!--begin::Badge=-->
                                                    <td class="min-w-70px">
                                                        <div class="badge badge-light-danger">500 ERR</div>
                                                    </td>
                                                    <!--end::Badge=-->
                                                    <!--begin::Status=-->
                                                    <td>POST /v1/invoice/in_5315_4014/invalid</td>
                                                    <!--end::Status=-->
                                                    <!--begin::Timestamp=-->
                                                    <td class="pe-0 text-end min-w-200px">10 Nov 2022, 10:30 am</td>
                                                    <!--end::Timestamp=-->
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                <tr>
                                                    <!--begin::Badge=-->
                                                    <td class="min-w-70px">
                                                        <div class="badge badge-light-success">200 OK</div>
                                                    </td>
                                                    <!--end::Badge=-->
                                                    <!--begin::Status=-->
                                                    <td>POST /v1/invoices/in_7445_4506/payment</td>
                                                    <!--end::Status=-->
                                                    <!--begin::Timestamp=-->
                                                    <td class="pe-0 text-end min-w-200px">25 Jul 2022, 11:30 am</td>
                                                    <!--end::Timestamp=-->
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                <tr>
                                                    <!--begin::Badge=-->
                                                    <td class="min-w-70px">
                                                        <div class="badge badge-light-success">200 OK</div>
                                                    </td>
                                                    <!--end::Badge=-->
                                                    <!--begin::Status=-->
                                                    <td>POST /v1/invoices/in_4996_5786/payment</td>
                                                    <!--end::Status=-->
                                                    <!--begin::Timestamp=-->
                                                    <td class="pe-0 text-end min-w-200px">25 Oct 2022, 6:05 pm</td>
                                                    <!--end::Timestamp=-->
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                <tr>
                                                    <!--begin::Badge=-->
                                                    <td class="min-w-70px">
                                                        <div class="badge badge-light-success">200 OK</div>
                                                    </td>
                                                    <!--end::Badge=-->
                                                    <!--begin::Status=-->
                                                    <td>POST /v1/invoices/in_3841_7630/payment</td>
                                                    <!--end::Status=-->
                                                    <!--begin::Timestamp=-->
                                                    <td class="pe-0 text-end min-w-200px">21 Feb 2022, 6:05 pm</td>
                                                    <!--end::Timestamp=-->
                                                </tr>
                                                <!--end::Table row-->
                                                <!--begin::Table row-->
                                                <tr>
                                                    <!--begin::Badge=-->
                                                    <td class="min-w-70px">
                                                        <div class="badge badge-light-success">200 OK</div>
                                                    </td>
                                                    <!--end::Badge=-->
                                                    <!--begin::Status=-->
                                                    <td>POST /v1/invoices/in_3822_2935/payment</td>
                                                    <!--end::Status=-->
                                                    <!--begin::Timestamp=-->
                                                    <td class="pe-0 text-end min-w-200px">10 Mar 2022, 8:43 pm</td>
                                                    <!--end::Timestamp=-->
                                                </tr>
                                                <!--end::Table row-->
                                            </tbody>
                                            <!--end::Table body-->
                                        </table>
                                        <!--end::Table-->
                                    </div>
                                    <!--end::Table wrapper-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card-->
                            <!--begin::Card-->
                            <div class="card pt-4 mb-6 mb-xl-9">
                                <!--begin::Card header-->
                                <div class="card-header border-0">
                                    <!--begin::Card title-->
                                    <div class="card-title">
                                        <h2>Events</h2>
                                    </div>
                                    <!--end::Card title-->
                                    <!--begin::Card toolbar-->
                                    <div class="card-toolbar">
                                        <!--begin::Button-->
                                        <button type="button" class="btn btn-sm btn-light-primary">
                                        <!--begin::Svg Icon | path: icons/duotune/files/fil021.svg-->
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3" d="M19 15C20.7 15 22 13.7 22 12C22 10.3 20.7 9 19 9C18.9 9 18.9 9 18.8 9C18.9 8.7 19 8.3 19 8C19 6.3 17.7 5 16 5C15.4 5 14.8 5.2 14.3 5.5C13.4 4 11.8 3 10 3C7.2 3 5 5.2 5 8C5 8.3 5 8.7 5.1 9H5C3.3 9 2 10.3 2 12C2 13.7 3.3 15 5 15H19Z" fill="currentColor" />
                                                <path d="M13 17.4V12C13 11.4 12.6 11 12 11C11.4 11 11 11.4 11 12V17.4H13Z" fill="currentColor" />
                                                <path opacity="0.3" d="M8 17.4H16L12.7 20.7C12.3 21.1 11.7 21.1 11.3 20.7L8 17.4Z" fill="currentColor" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->Download Report</button>
                                        <!--end::Button-->
                                    </div>
                                    <!--end::Card toolbar-->
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body py-0">
                                    <!--begin::Table-->
                                    <table class="table align-middle table-row-dashed fs-6 text-gray-600 fw-bold gy-5" id="kt_table_customers_events">
                                        <!--begin::Table body-->
                                        <tbody>
                                            <!--begin::Table row-->
                                            <tr>
                                                <!--begin::Event=-->
                                                <td class="min-w-400px">Invoice
                                                <a href="#" class="fw-bolder text-gray-900 text-hover-primary me-1">#SEP-45656</a>status has changed from
                                                <span class="badge badge-light-warning me-1">Pending</span>to
                                                <span class="badge badge-light-info">In Progress</span></td>
                                                <!--end::Event=-->
                                                <!--begin::Timestamp=-->
                                                <td class="pe-0 text-gray-600 text-end min-w-200px">25 Oct 2022, 8:43 pm</td>
                                                <!--end::Timestamp=-->
                                            </tr>
                                            <!--end::Table row-->
                                            <!--begin::Table row-->
                                            <tr>
                                                <!--begin::Event=-->
                                                <td class="min-w-400px">
                                                <a href="#" class="text-gray-600 text-hover-primary me-1">Max Smith</a>has made payment to
                                                <a href="#" class="fw-bolder text-gray-900 text-hover-primary">#SDK-45670</a></td>
                                                <!--end::Event=-->
                                                <!--begin::Timestamp=-->
                                                <td class="pe-0 text-gray-600 text-end min-w-200px">24 Jun 2022, 9:23 pm</td>
                                                <!--end::Timestamp=-->
                                            </tr>
                                            <!--end::Table row-->
                                            <!--begin::Table row-->
                                            <tr>
                                                <!--begin::Event=-->
                                                <td class="min-w-400px">
                                                <a href="#" class="text-gray-600 text-hover-primary me-1">Emma Smith</a>has made payment to
                                                <a href="#" class="fw-bolder text-gray-900 text-hover-primary">#XRS-45670</a></td>
                                                <!--end::Event=-->
                                                <!--begin::Timestamp=-->
                                                <td class="pe-0 text-gray-600 text-end min-w-200px">20 Jun 2022, 11:30 am</td>
                                                <!--end::Timestamp=-->
                                            </tr>
                                            <!--end::Table row-->
                                            <!--begin::Table row-->
                                            <tr>
                                                <!--begin::Event=-->
                                                <td class="min-w-400px">Invoice
                                                <a href="#" class="fw-bolder text-gray-900 text-hover-primary me-1">#KIO-45656</a>status has changed from
                                                <span class="badge badge-light-succees me-1">In Transit</span>to
                                                <span class="badge badge-light-success">Approved</span></td>
                                                <!--end::Event=-->
                                                <!--begin::Timestamp=-->
                                                <td class="pe-0 text-gray-600 text-end min-w-200px">22 Sep 2022, 11:30 am</td>
                                                <!--end::Timestamp=-->
                                            </tr>
                                            <!--end::Table row-->
                                            <!--begin::Table row-->
                                            <tr>
                                                <!--begin::Event=-->
                                                <td class="min-w-400px">
                                                <a href="#" class="text-gray-600 text-hover-primary me-1">Max Smith</a>has made payment to
                                                <a href="#" class="fw-bolder text-gray-900 text-hover-primary">#SDK-45670</a></td>
                                                <!--end::Event=-->
                                                <!--begin::Timestamp=-->
                                                <td class="pe-0 text-gray-600 text-end min-w-200px">19 Aug 2022, 5:30 pm</td>
                                                <!--end::Timestamp=-->
                                            </tr>
                                            <!--end::Table row-->
                                            <!--begin::Table row-->
                                            <tr>
                                                <!--begin::Event=-->
                                                <td class="min-w-400px">Invoice
                                                <a href="#" class="fw-bolder text-gray-900 text-hover-primary me-1">#DER-45645</a>status has changed from
                                                <span class="badge badge-light-info me-1">In Progress</span>to
                                                <span class="badge badge-light-primary">In Transit</span></td>
                                                <!--end::Event=-->
                                                <!--begin::Timestamp=-->
                                                <td class="pe-0 text-gray-600 text-end min-w-200px">24 Jun 2022, 6:05 pm</td>
                                                <!--end::Timestamp=-->
                                            </tr>
                                            <!--end::Table row-->
                                            <!--begin::Table row-->
                                            <tr>
                                                <!--begin::Event=-->
                                                <td class="min-w-400px">Invoice
                                                <a href="#" class="fw-bolder text-gray-900 text-hover-primary me-1">#LOP-45640</a>has been
                                                <span class="badge badge-light-danger">Declined</span></td>
                                                <!--end::Event=-->
                                                <!--begin::Timestamp=-->
                                                <td class="pe-0 text-gray-600 text-end min-w-200px">10 Nov 2022, 8:43 pm</td>
                                                <!--end::Timestamp=-->
                                            </tr>
                                            <!--end::Table row-->
                                            <!--begin::Table row-->
                                            <tr>
                                                <!--begin::Event=-->
                                                <td class="min-w-400px">Invoice
                                                <a href="#" class="fw-bolder text-gray-900 text-hover-primary me-1">#DER-45645</a>status has changed from
                                                <span class="badge badge-light-info me-1">In Progress</span>to
                                                <span class="badge badge-light-primary">In Transit</span></td>
                                                <!--end::Event=-->
                                                <!--begin::Timestamp=-->
                                                <td class="pe-0 text-gray-600 text-end min-w-200px">25 Jul 2022, 11:05 am</td>
                                                <!--end::Timestamp=-->
                                            </tr>
                                            <!--end::Table row-->
                                            <!--begin::Table row-->
                                            <tr>
                                                <!--begin::Event=-->
                                                <td class="min-w-400px">Invoice
                                                <a href="#" class="fw-bolder text-gray-900 text-hover-primary me-1">#LOP-45640</a>has been
                                                <span class="badge badge-light-danger">Declined</span></td>
                                                <!--end::Event=-->
                                                <!--begin::Timestamp=-->
                                                <td class="pe-0 text-gray-600 text-end min-w-200px">24 Jun 2022, 10:10 pm</td>
                                                <!--end::Timestamp=-->
                                            </tr>
                                            <!--end::Table row-->
                                            <!--begin::Table row-->
                                            <tr>
                                                <!--begin::Event=-->
                                                <td class="min-w-400px">
                                                <a href="#" class="text-gray-600 text-hover-primary me-1">Sean Bean</a>has made payment to
                                                <a href="#" class="fw-bolder text-gray-900 text-hover-primary">#XRS-45670</a></td>
                                                <!--end::Event=-->
                                                <!--begin::Timestamp=-->
                                                <td class="pe-0 text-gray-600 text-end min-w-200px">24 Jun 2022, 5:20 pm</td>
                                                <!--end::Timestamp=-->
                                            </tr>
                                            <!--end::Table row-->
                                        </tbody>
                                        <!--end::Table body-->
                                    </table>
                                    <!--end::Table-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card-->
                        </div>

                        <!--DATOS HISTORIAL-->
                        <div class="tab-pane fade " id="tabHistorial" role="tabpanel">
                            <!--begin::Card-->
                            <div class="card card-flush mb-6 mb-xl-9">
                                <!--begin::Card header-->
                                <div class="card-header mt-6">
                                    <!--begin::Card title-->
                                    <div class="card-title flex-column">
                                        <h2 class="mb-1">User's Schedule</h2>
                                        <div class="fs-6 fw-bold text-muted">2 upcoming meetings</div>
                                    </div>
                                    <!--end::Card title-->
                                    <!--begin::Card toolbar-->
                                    <div class="card-toolbar">
                                        <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_add_schedule">
                                        <!--SVG file not found: media/icons/duotune/art/art008.svg-->
                                        Add Schedule</button>
                                    </div>
                                    <!--end::Card toolbar-->
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body p-9 pt-4">
                                    <!--begin::Dates-->
                                    <ul class="nav nav-pills d-flex flex-nowrap hover-scroll-x py-2">
                                        <!--begin::Date-->
                                        <li class="nav-item me-1">
                                            <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary" data-bs-toggle="tab" href="#kt_schedule_day_0">
                                                <span class="opacity-50 fs-7 fw-bold">Su</span>
                                                <span class="fs-6 fw-boldest">21</span>
                                            </a>
                                        </li>
                                        <!--end::Date-->
                                        <!--begin::Date-->
                                        <li class="nav-item me-1">
                                            <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary active" data-bs-toggle="tab" href="#kt_schedule_day_1">
                                                <span class="opacity-50 fs-7 fw-bold">Mo</span>
                                                <span class="fs-6 fw-boldest">22</span>
                                            </a>
                                        </li>
                                        <!--end::Date-->
                                        <!--begin::Date-->
                                        <li class="nav-item me-1">
                                            <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary" data-bs-toggle="tab" href="#kt_schedule_day_2">
                                                <span class="opacity-50 fs-7 fw-bold">Tu</span>
                                                <span class="fs-6 fw-boldest">23</span>
                                            </a>
                                        </li>
                                        <!--end::Date-->
                                        <!--begin::Date-->
                                        <li class="nav-item me-1">
                                            <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary" data-bs-toggle="tab" href="#kt_schedule_day_3">
                                                <span class="opacity-50 fs-7 fw-bold">We</span>
                                                <span class="fs-6 fw-boldest">24</span>
                                            </a>
                                        </li>
                                        <!--end::Date-->
                                        <!--begin::Date-->
                                        <li class="nav-item me-1">
                                            <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary" data-bs-toggle="tab" href="#kt_schedule_day_4">
                                                <span class="opacity-50 fs-7 fw-bold">Th</span>
                                                <span class="fs-6 fw-boldest">25</span>
                                            </a>
                                        </li>
                                        <!--end::Date-->
                                        <!--begin::Date-->
                                        <li class="nav-item me-1">
                                            <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary" data-bs-toggle="tab" href="#kt_schedule_day_5">
                                                <span class="opacity-50 fs-7 fw-bold">Fr</span>
                                                <span class="fs-6 fw-boldest">26</span>
                                            </a>
                                        </li>
                                        <!--end::Date-->
                                        <!--begin::Date-->
                                        <li class="nav-item me-1">
                                            <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary" data-bs-toggle="tab" href="#kt_schedule_day_6">
                                                <span class="opacity-50 fs-7 fw-bold">Sa</span>
                                                <span class="fs-6 fw-boldest">27</span>
                                            </a>
                                        </li>
                                        <!--end::Date-->
                                        <!--begin::Date-->
                                        <li class="nav-item me-1">
                                            <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary" data-bs-toggle="tab" href="#kt_schedule_day_7">
                                                <span class="opacity-50 fs-7 fw-bold">Su</span>
                                                <span class="fs-6 fw-boldest">28</span>
                                            </a>
                                        </li>
                                        <!--end::Date-->
                                        <!--begin::Date-->
                                        <li class="nav-item me-1">
                                            <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary" data-bs-toggle="tab" href="#kt_schedule_day_8">
                                                <span class="opacity-50 fs-7 fw-bold">Mo</span>
                                                <span class="fs-6 fw-boldest">29</span>
                                            </a>
                                        </li>
                                        <!--end::Date-->
                                        <!--begin::Date-->
                                        <li class="nav-item me-1">
                                            <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary" data-bs-toggle="tab" href="#kt_schedule_day_9">
                                                <span class="opacity-50 fs-7 fw-bold">Tu</span>
                                                <span class="fs-6 fw-boldest">30</span>
                                            </a>
                                        </li>
                                        <!--end::Date-->
                                        <!--begin::Date-->
                                        <li class="nav-item me-1">
                                            <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary" data-bs-toggle="tab" href="#kt_schedule_day_10">
                                                <span class="opacity-50 fs-7 fw-bold">We</span>
                                                <span class="fs-6 fw-boldest">31</span>
                                            </a>
                                        </li>
                                        <!--end::Date-->
                                    </ul>
                                    <!--end::Dates-->
                                    <!--begin::Tab Content-->
                                    <div class="tab-content">
                                        <!--begin::Day-->
                                        <div id="kt_schedule_day_0" class="tab-pane fade show">
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">14:30 - 15:30
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Creative Content Initiative</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Caleb Donaldson</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">13:00 - 14:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Committee Review Approvals</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">David Stevenson</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">12:00 - 13:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Team Backlog Grooming Session</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">David Stevenson</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">10:00 - 11:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Creative Content Initiative</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Michael Walters</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                        </div>
                                        <!--end::Day-->
                                        <!--begin::Day-->
                                        <div id="kt_schedule_day_1" class="tab-pane fade show active">
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">16:30 - 17:30
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Sales Pitch Proposal</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Naomi Hayabusa</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">13:00 - 14:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Marketing Campaign Discussion</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Kendell Trevor</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">12:00 - 13:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">9 Degree Project Estimation Meeting</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Sean Bean</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                        </div>
                                        <!--end::Day-->
                                        <!--begin::Day-->
                                        <div id="kt_schedule_day_2" class="tab-pane fade show">
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">11:00 - 11:45
                                                    <span class="fs-7 text-muted text-uppercase">am</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Project Review &amp; Testing</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Walter White</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">12:00 - 13:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Team Backlog Grooming Session</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Terry Robins</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">12:00 - 13:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Creative Content Initiative</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Caleb Donaldson</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">9:00 - 10:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Lunch &amp; Learn Catch Up</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Walter White</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                        </div>
                                        <!--end::Day-->
                                        <!--begin::Day-->
                                        <div id="kt_schedule_day_3" class="tab-pane fade show">
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">9:00 - 10:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Project Review &amp; Testing</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Yannis Gloverson</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">12:00 - 13:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Development Team Capacity Review</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Yannis Gloverson</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">9:00 - 10:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Marketing Campaign Discussion</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Sean Bean</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">16:30 - 17:30
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Creative Content Initiative</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Caleb Donaldson</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                        </div>
                                        <!--end::Day-->
                                        <!--begin::Day-->
                                        <div id="kt_schedule_day_4" class="tab-pane fade show">
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">9:00 - 10:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Development Team Capacity Review</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Karina Clarke</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">16:30 - 17:30
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Project Review &amp; Testing</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Caleb Donaldson</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">16:30 - 17:30
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Committee Review Approvals</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Terry Robins</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                        </div>
                                        <!--end::Day-->
                                        <!--begin::Day-->
                                        <div id="kt_schedule_day_5" class="tab-pane fade show">
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">12:00 - 13:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Team Backlog Grooming Session</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Michael Walters</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">14:30 - 15:30
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Weekly Team Stand-Up</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Yannis Gloverson</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">12:00 - 13:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Lunch &amp; Learn Catch Up</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Bob Harris</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">11:00 - 11:45
                                                    <span class="fs-7 text-muted text-uppercase">am</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">9 Degree Project Estimation Meeting</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Yannis Gloverson</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">13:00 - 14:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Committee Review Approvals</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Peter Marcus</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                        </div>
                                        <!--end::Day-->
                                        <!--begin::Day-->
                                        <div id="kt_schedule_day_6" class="tab-pane fade show">
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">11:00 - 11:45
                                                    <span class="fs-7 text-muted text-uppercase">am</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">9 Degree Project Estimation Meeting</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Michael Walters</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">16:30 - 17:30
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Dashboard UI/UX Design Review</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Terry Robins</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">12:00 - 13:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">9 Degree Project Estimation Meeting</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Kendell Trevor</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                        </div>
                                        <!--end::Day-->
                                        <!--begin::Day-->
                                        <div id="kt_schedule_day_7" class="tab-pane fade show">
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">13:00 - 14:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Sales Pitch Proposal</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Yannis Gloverson</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">9:00 - 10:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">9 Degree Project Estimation Meeting</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Kendell Trevor</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">11:00 - 11:45
                                                    <span class="fs-7 text-muted text-uppercase">am</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Weekly Team Stand-Up</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">David Stevenson</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                        </div>
                                        <!--end::Day-->
                                        <!--begin::Day-->
                                        <div id="kt_schedule_day_8" class="tab-pane fade show">
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">10:00 - 11:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Committee Review Approvals</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Terry Robins</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">10:00 - 11:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Committee Review Approvals</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Yannis Gloverson</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">13:00 - 14:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Weekly Team Stand-Up</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Walter White</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">9:00 - 10:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Sales Pitch Proposal</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Terry Robins</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                        </div>
                                        <!--end::Day-->
                                        <!--begin::Day-->
                                        <div id="kt_schedule_day_9" class="tab-pane fade show">
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">13:00 - 14:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Team Backlog Grooming Session</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">David Stevenson</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">11:00 - 11:45
                                                    <span class="fs-7 text-muted text-uppercase">am</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Project Review &amp; Testing</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Sean Bean</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">13:00 - 14:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Sales Pitch Proposal</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Caleb Donaldson</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">14:30 - 15:30
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Team Backlog Grooming Session</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Peter Marcus</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">12:00 - 13:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Committee Review Approvals</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Mark Randall</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                        </div>
                                        <!--end::Day-->
                                        <!--begin::Day-->
                                        <div id="kt_schedule_day_10" class="tab-pane fade show">
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">13:00 - 14:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Committee Review Approvals</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Kendell Trevor</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">10:00 - 11:00
                                                    <span class="fs-7 text-muted text-uppercase">am</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Weekly Team Stand-Up</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Michael Walters</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">11:00 - 11:45
                                                    <span class="fs-7 text-muted text-uppercase">am</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Project Review &amp; Testing</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Mark Randall</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                            <!--begin::Time-->
                                            <div class="d-flex flex-stack position-relative mt-6">
                                                <!--begin::Bar-->
                                                <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0"></div>
                                                <!--end::Bar-->
                                                <!--begin::Info-->
                                                <div class="fw-bold ms-5">
                                                    <!--begin::Time-->
                                                    <div class="fs-7 mb-1">13:00 - 14:00
                                                    <span class="fs-7 text-muted text-uppercase">pm</span></div>
                                                    <!--end::Time-->
                                                    <!--begin::Title-->
                                                    <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary mb-2">Committee Review Approvals</a>
                                                    <!--end::Title-->
                                                    <!--begin::User-->
                                                    <div class="fs-7 text-muted">Lead by
                                                    <a href="#">Yannis Gloverson</a></div>
                                                    <!--end::User-->
                                                </div>
                                                <!--end::Info-->
                                                <!--begin::Action-->
                                                <a href="#" class="btn btn-light bnt-active-light-primary btn-sm">View</a>
                                                <!--end::Action-->
                                            </div>
                                            <!--end::Time-->
                                        </div>
                                        <!--end::Day-->
                                    </div>
                                    <!--end::Tab Content-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card-->
                            <!--begin::Tasks-->
                            <div class="card card-flush mb-6 mb-xl-9">
                                <!--begin::Card header-->
                                <div class="card-header mt-6">
                                    <!--begin::Card title-->
                                    <div class="card-title flex-column">
                                        <h2 class="mb-1">User's Tasks</h2>
                                        <div class="fs-6 fw-bold text-muted">Total 25 tasks in backlog</div>
                                    </div>
                                    <!--end::Card title-->
                                    <!--begin::Card toolbar-->
                                    <div class="card-toolbar">
                                        <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_add_task">
                                        <!--begin::Svg Icon | path: icons/duotune/files/fil005.svg-->
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM16 13.5L12.5 13V10C12.5 9.4 12.6 9.5 12 9.5C11.4 9.5 11.5 9.4 11.5 10L11 13L8 13.5C7.4 13.5 7 13.4 7 14C7 14.6 7.4 14.5 8 14.5H11V18C11 18.6 11.4 19 12 19C12.6 19 12.5 18.6 12.5 18V14.5L16 14C16.6 14 17 14.6 17 14C17 13.4 16.6 13.5 16 13.5Z" fill="currentColor" />
                                                <rect x="11" y="19" width="10" height="2" rx="1" transform="rotate(-90 11 19)" fill="currentColor" />
                                                <rect x="7" y="13" width="10" height="2" rx="1" fill="currentColor" />
                                                <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor" />
                                            </svg>
                                        </span>
                                        <!--end::Svg Icon-->Add Task</button>
                                    </div>
                                    <!--end::Card toolbar-->
                                </div>
                                <!--end::Card header-->
                                <!--begin::Card body-->
                                <div class="card-body d-flex flex-column">
                                    <!--begin::Item-->
                                    <div class="d-flex align-items-center position-relative mb-7">
                                        <!--begin::Label-->
                                        <div class="position-absolute top-0 start-0 rounded h-100 bg-secondary w-4px"></div>
                                        <!--end::Label-->
                                        <!--begin::Details-->
                                        <div class="fw-bold ms-5">
                                            <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary">Create FureStibe branding logo</a>
                                            <!--begin::Info-->
                                            <div class="fs-7 text-muted">Due in 1 day
                                            <a href="#">Karina Clark</a></div>
                                            <!--end::Info-->
                                        </div>
                                        <!--end::Details-->
                                        <!--begin::Menu-->
                                        <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <!--begin::Svg Icon | path: icons/duotune/general/gen019.svg-->
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="currentColor" />
                                                    <path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="currentColor" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </button>
                                        <!--begin::Task menu-->
                                        <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" data-kt-menu-id="kt-users-tasks">
                                            <!--begin::Header-->
                                            <div class="px-7 py-5">
                                                <div class="fs-5 text-dark fw-bolder">Update Status</div>
                                            </div>
                                            <!--end::Header-->
                                            <!--begin::Menu separator-->
                                            <div class="separator border-gray-200"></div>
                                            <!--end::Menu separator-->
                                            <!--begin::Form-->
                                            <form class="form px-7 py-5" data-kt-menu-id="kt-users-tasks-form">
                                                <!--begin::Input group-->
                                                <div class="fv-row mb-10">
                                                    <!--begin::Label-->
                                                    <label class="form-label fs-6 fw-bold">Status:</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <select class="form-select form-select-solid" name="task_status" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-hide-search="true">
                                                        <option></option>
                                                        <option value="1">Approved</option>
                                                        <option value="2">Pending</option>
                                                        <option value="3">In Process</option>
                                                        <option value="4">Rejected</option>
                                                    </select>
                                                    <!--end::Input-->
                                                </div>
                                                <!--end::Input group-->
                                                <!--begin::Actions-->
                                                <div class="d-flex justify-content-end">
                                                    <button type="button" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-users-update-task-status="reset">Reset</button>
                                                    <button type="submit" class="btn btn-sm btn-primary" data-kt-users-update-task-status="submit">
                                                        <span class="indicator-label">Apply</span>
                                                        <span class="indicator-progress">Please wait...
                                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                    </button>
                                                </div>
                                                <!--end::Actions-->
                                            </form>
                                            <!--end::Form-->
                                        </div>
                                        <!--end::Task menu-->
                                        <!--end::Menu-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="d-flex align-items-center position-relative mb-7">
                                        <!--begin::Label-->
                                        <div class="position-absolute top-0 start-0 rounded h-100 bg-secondary w-4px"></div>
                                        <!--end::Label-->
                                        <!--begin::Details-->
                                        <div class="fw-bold ms-5">
                                            <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary">Schedule a meeting with FireBear CTO John</a>
                                            <!--begin::Info-->
                                            <div class="fs-7 text-muted">Due in 3 days
                                            <a href="#">Rober Doe</a></div>
                                            <!--end::Info-->
                                        </div>
                                        <!--end::Details-->
                                        <!--begin::Menu-->
                                        <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <!--begin::Svg Icon | path: icons/duotune/general/gen019.svg-->
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="currentColor" />
                                                    <path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="currentColor" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </button>
                                        <!--begin::Task menu-->
                                        <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" data-kt-menu-id="kt-users-tasks">
                                            <!--begin::Header-->
                                            <div class="px-7 py-5">
                                                <div class="fs-5 text-dark fw-bolder">Update Status</div>
                                            </div>
                                            <!--end::Header-->
                                            <!--begin::Menu separator-->
                                            <div class="separator border-gray-200"></div>
                                            <!--end::Menu separator-->
                                            <!--begin::Form-->
                                            <form class="form px-7 py-5" data-kt-menu-id="kt-users-tasks-form">
                                                <!--begin::Input group-->
                                                <div class="fv-row mb-10">
                                                    <!--begin::Label-->
                                                    <label class="form-label fs-6 fw-bold">Status:</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <select class="form-select form-select-solid" name="task_status" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-hide-search="true">
                                                        <option></option>
                                                        <option value="1">Approved</option>
                                                        <option value="2">Pending</option>
                                                        <option value="3">In Process</option>
                                                        <option value="4">Rejected</option>
                                                    </select>
                                                    <!--end::Input-->
                                                </div>
                                                <!--end::Input group-->
                                                <!--begin::Actions-->
                                                <div class="d-flex justify-content-end">
                                                    <button type="button" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-users-update-task-status="reset">Reset</button>
                                                    <button type="submit" class="btn btn-sm btn-primary" data-kt-users-update-task-status="submit">
                                                        <span class="indicator-label">Apply</span>
                                                        <span class="indicator-progress">Please wait...
                                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                    </button>
                                                </div>
                                                <!--end::Actions-->
                                            </form>
                                            <!--end::Form-->
                                        </div>
                                        <!--end::Task menu-->
                                        <!--end::Menu-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="d-flex align-items-center position-relative mb-7">
                                        <!--begin::Label-->
                                        <div class="position-absolute top-0 start-0 rounded h-100 bg-secondary w-4px"></div>
                                        <!--end::Label-->
                                        <!--begin::Details-->
                                        <div class="fw-bold ms-5">
                                            <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary">9 Degree Project Estimation</a>
                                            <!--begin::Info-->
                                            <div class="fs-7 text-muted">Due in 1 week
                                            <a href="#">Neil Owen</a></div>
                                            <!--end::Info-->
                                        </div>
                                        <!--end::Details-->
                                        <!--begin::Menu-->
                                        <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <!--begin::Svg Icon | path: icons/duotune/general/gen019.svg-->
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="currentColor" />
                                                    <path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="currentColor" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </button>
                                        <!--begin::Task menu-->
                                        <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" data-kt-menu-id="kt-users-tasks">
                                            <!--begin::Header-->
                                            <div class="px-7 py-5">
                                                <div class="fs-5 text-dark fw-bolder">Update Status</div>
                                            </div>
                                            <!--end::Header-->
                                            <!--begin::Menu separator-->
                                            <div class="separator border-gray-200"></div>
                                            <!--end::Menu separator-->
                                            <!--begin::Form-->
                                            <form class="form px-7 py-5" data-kt-menu-id="kt-users-tasks-form">
                                                <!--begin::Input group-->
                                                <div class="fv-row mb-10">
                                                    <!--begin::Label-->
                                                    <label class="form-label fs-6 fw-bold">Status:</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <select class="form-select form-select-solid" name="task_status" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-hide-search="true">
                                                        <option></option>
                                                        <option value="1">Approved</option>
                                                        <option value="2">Pending</option>
                                                        <option value="3">In Process</option>
                                                        <option value="4">Rejected</option>
                                                    </select>
                                                    <!--end::Input-->
                                                </div>
                                                <!--end::Input group-->
                                                <!--begin::Actions-->
                                                <div class="d-flex justify-content-end">
                                                    <button type="button" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-users-update-task-status="reset">Reset</button>
                                                    <button type="submit" class="btn btn-sm btn-primary" data-kt-users-update-task-status="submit">
                                                        <span class="indicator-label">Apply</span>
                                                        <span class="indicator-progress">Please wait...
                                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                    </button>
                                                </div>
                                                <!--end::Actions-->
                                            </form>
                                            <!--end::Form-->
                                        </div>
                                        <!--end::Task menu-->
                                        <!--end::Menu-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="d-flex align-items-center position-relative mb-7">
                                        <!--begin::Label-->
                                        <div class="position-absolute top-0 start-0 rounded h-100 bg-secondary w-4px"></div>
                                        <!--end::Label-->
                                        <!--begin::Details-->
                                        <div class="fw-bold ms-5">
                                            <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary">Dashboard UI &amp; UX for Leafr CRM</a>
                                            <!--begin::Info-->
                                            <div class="fs-7 text-muted">Due in 1 week
                                            <a href="#">Olivia Wild</a></div>
                                            <!--end::Info-->
                                        </div>
                                        <!--end::Details-->
                                        <!--begin::Menu-->
                                        <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <!--begin::Svg Icon | path: icons/duotune/general/gen019.svg-->
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="currentColor" />
                                                    <path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="currentColor" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </button>
                                        <!--begin::Task menu-->
                                        <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" data-kt-menu-id="kt-users-tasks">
                                            <!--begin::Header-->
                                            <div class="px-7 py-5">
                                                <div class="fs-5 text-dark fw-bolder">Update Status</div>
                                            </div>
                                            <!--end::Header-->
                                            <!--begin::Menu separator-->
                                            <div class="separator border-gray-200"></div>
                                            <!--end::Menu separator-->
                                            <!--begin::Form-->
                                            <form class="form px-7 py-5" data-kt-menu-id="kt-users-tasks-form">
                                                <!--begin::Input group-->
                                                <div class="fv-row mb-10">
                                                    <!--begin::Label-->
                                                    <label class="form-label fs-6 fw-bold">Status:</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <select class="form-select form-select-solid" name="task_status" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-hide-search="true">
                                                        <option></option>
                                                        <option value="1">Approved</option>
                                                        <option value="2">Pending</option>
                                                        <option value="3">In Process</option>
                                                        <option value="4">Rejected</option>
                                                    </select>
                                                    <!--end::Input-->
                                                </div>
                                                <!--end::Input group-->
                                                <!--begin::Actions-->
                                                <div class="d-flex justify-content-end">
                                                    <button type="button" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-users-update-task-status="reset">Reset</button>
                                                    <button type="submit" class="btn btn-sm btn-primary" data-kt-users-update-task-status="submit">
                                                        <span class="indicator-label">Apply</span>
                                                        <span class="indicator-progress">Please wait...
                                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                    </button>
                                                </div>
                                                <!--end::Actions-->
                                            </form>
                                            <!--end::Form-->
                                        </div>
                                        <!--end::Task menu-->
                                        <!--end::Menu-->
                                    </div>
                                    <!--end::Item-->
                                    <!--begin::Item-->
                                    <div class="d-flex align-items-center position-relative">
                                        <!--begin::Label-->
                                        <div class="position-absolute top-0 start-0 rounded h-100 bg-secondary w-4px"></div>
                                        <!--end::Label-->
                                        <!--begin::Details-->
                                        <div class="fw-bold ms-5">
                                            <a href="#" class="fs-5 fw-bolder text-dark text-hover-primary">Mivy App R&amp;D, Meeting with clients</a>
                                            <!--begin::Info-->
                                            <div class="fs-7 text-muted">Due in 2 weeks
                                            <a href="#">Sean Bean</a></div>
                                            <!--end::Info-->
                                        </div>
                                        <!--end::Details-->
                                        <!--begin::Menu-->
                                        <button type="button" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                            <!--begin::Svg Icon | path: icons/duotune/general/gen019.svg-->
                                            <span class="svg-icon svg-icon-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="currentColor" />
                                                    <path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="currentColor" />
                                                </svg>
                                            </span>
                                            <!--end::Svg Icon-->
                                        </button>
                                        <!--begin::Task menu-->
                                        <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" data-kt-menu-id="kt-users-tasks">
                                            <!--begin::Header-->
                                            <div class="px-7 py-5">
                                                <div class="fs-5 text-dark fw-bolder">Update Status</div>
                                            </div>
                                            <!--end::Header-->
                                            <!--begin::Menu separator-->
                                            <div class="separator border-gray-200"></div>
                                            <!--end::Menu separator-->
                                            <!--begin::Form-->
                                            <form class="form px-7 py-5" data-kt-menu-id="kt-users-tasks-form">
                                                <!--begin::Input group-->
                                                <div class="fv-row mb-10">
                                                    <!--begin::Label-->
                                                    <label class="form-label fs-6 fw-bold">Status:</label>
                                                    <!--end::Label-->
                                                    <!--begin::Input-->
                                                    <select class="form-select form-select-solid" name="task_status" data-kt-select2="true" data-placeholder="Select option" data-allow-clear="true" data-hide-search="true">
                                                        <option></option>
                                                        <option value="1">Approved</option>
                                                        <option value="2">Pending</option>
                                                        <option value="3">In Process</option>
                                                        <option value="4">Rejected</option>
                                                    </select>
                                                    <!--end::Input-->
                                                </div>
                                                <!--end::Input group-->
                                                <!--begin::Actions-->
                                                <div class="d-flex justify-content-end">
                                                    <button type="button" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-users-update-task-status="reset">Reset</button>
                                                    <button type="submit" class="btn btn-sm btn-primary" data-kt-users-update-task-status="submit">
                                                        <span class="indicator-label">Apply</span>
                                                        <span class="indicator-progress">Please wait...
                                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                    </button>
                                                </div>
                                                <!--end::Actions-->
                                            </form>
                                            <!--end::Form-->
                                        </div>
                                        <!--end::Task menu-->
                                        <!--end::Menu-->
                                    </div>
                                    <!--end::Item-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Tasks-->
                        </div>
                    </div>
                    
                </div>
                
            </div>


            <div class="modal fade" id="modal-prestador" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <div class="modal-content">
                        <div class="modal-header" id="kt_modal-prestador_header">
                            <h2 class="fw-bolder">Informacion Prestador</h2>
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                <span class="svg-icon svg-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                        <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="modal-body py-5 px-lg-17">
                            <div class="d-flex flex-column scroll-y me-n7 pe-7" id="modal-prestador_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#modal-prestador_header" data-kt-scroll-wrappers="#modal-prestador_scroll" data-kt-scroll-offset="300px">
                                <div class="fw-boldest fs-3 rotate collapsible mb-7" data-bs-toggle="collapse" href="#modal-prestador_info" role="button" aria-expanded="false" aria-controls="modal-prestador_info">Datos Generales
                                <span class="ms-2 rotate-180">
                                    <span class="svg-icon svg-icon-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                </span></div>
                                <div id="modal-prestador_info" class="collapse show">
                                    <div class="mb-7">
                                        <div class="mt-1">
                                            <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('assets/media/svg/files/blank-image.svg')">
                                                <div class="image-input-wrapper w-125px h-125px" id="imgfileprestador" style="background-image: url(assets/media/svg/files/blank-image.svg"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-8">
                                        <div class="col-xl-2">
                                            <div class="fs-6 fw-bold mt-2 mb-3">Tipo:</div>
                                        </div>
                                        <div class="col-xl-10 fv-row">
                                            <input type="text" class="form-control" id="txtTipoprestador" name="txtTipoprestador" readonly />
                                        </div>
                                    </div>

                                    <div class="row mb-8">
                                        <div class="col-xl-2">
                                            <div class="fs-6 fw-bold mt-2 mb-3">Sector:</div>
                                        </div>
                                        <div class="col-xl-10 fv-row">
                                            <input type="text" class="form-control" id="txtSector" name="txtSector" readonly />
                                        </div>
                                    </div>                                        

                                </div>

                                <div class="card-header border-0">
                                    <div class="card-title">
                                        <h2 class="fw-bolder mb-0">Direccion/Telefono/Mails</h2>
                                    </div>
                                </div>
                                <div id="view_datos_direccion" class="card-body pt-0">
                                    <div class="py-0" >
                                        <div class="py-3 d-flex flex-stack flex-wrap">
                                            <div class="d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#view_direccion" role="button" aria-expanded="false" aria-controls="view_direccion">
                                                <div class="me-3 rotate-90">
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <path d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z" fill="currentColor" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                <img src="assets/media/logos/ubicacion.png" class="w-20px me-3" />
                                                <div class="me-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="text-gray-800 fw-bolder">Direccion</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="view_direccion" class="collapse fs-6 ps-10" data-bs-parent="#view_datos_direccion">
                                            <div class="d-flex flex-wrap py-5">
                                                <div class="flex-equal me-5">
                                                    <div class="row mb-8">
                                                        <div class="col-xl-2">
                                                            <div class="fs-6 fw-bold mt-2 mb-3">Direccion:</div>
                                                        </div>
                                                        <div class="col-xl-10 fv-row">
                                                            <textarea class="form-control mb-2 text-uppercase" name="txtDireccion" id="txtDireccion" maxlength="250" onkeydown="return (event.keyCode!=13); " readonly ></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-8">
                                                        <div class="col-xl-2">
                                                            <div class="fs-6 fw-bold mt-2 mb-3">URL:</div>
                                                        </div>
                                                        <div class="col-xl-10 fv-row">
                                                            <input type="text" class="form-control mb-2 text-lowercase" name="txtUrl" id="txtUrl" maxlength="150" readonly />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="separator separator-dashed"></div>
                                        <div class="py-0">
                                            <div class="py-3 d-flex flex-stack flex-wrap">
                                                <div class="d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#view_telefonos" role="button" aria-expanded="false" aria-controls="view_telefonos">
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

                                            <div id="view_telefonos" class="collapse fs-6 ps-10" data-bs-parent="#view_datos_direccion">
                                                <div class="row row-cols-1 row-cols-sm-3 rol-cols-md-3 row-cols-lg-3">
                                                    <div class="col">
                                                        <div class="fs-6 fw-bold mt-2 mb-3">Telefono 1:</div>
                                                        <input type="text" class="form-control mb-2 w-150px" name="txtFono1" id="txtFono1" value="" readonly />
                                                    </div>
                                                    <div class="col">
                                                        <div class="fs-6 fw-bold mt-2 mb-3">Telefono 2:</div>
                                                        <input type="text" class="form-control mb-2 w-150px" name="txtFono2" id="txtFono2" value="" readonly />
                                                    </div> 
                                                    <div class="col">
                                                        <div class="fs-6 fw-bold mt-2 mb-3">Telefono 2:</div>
                                                        <input type="text" class="form-control mb-2 w-150px" name="txtFono3" id="txtFono3" value="" readonly />
                                                    </div>                                                        
                                                </div>
                                                <div class="row row-cols-1 row-cols-sm-3 rol-cols-md-3 row-cols-lg-3">
                                                    <div class="col">
                                                        <div class="fs-6 fw-bold mt-2 mb-3">Celular 1:</div>
                                                        <input type="text" class="form-control mb-2 w-150px" name="txtCelular1" id="txtCelular1" value="" readonly />
                                                    </div>
                                                    <div class="col">
                                                        <div class="fs-6 fw-bold mt-2 mb-3">Celular 2:</div>
                                                        <input type="text" class="form-control mb-2 w-150px" name="txtCelular2" id="txtCelular2" value="" readonly />
                                                    </div> 
                                                    <div class="col">
                                                        <div class="fs-6 fw-bold mt-2 mb-3">Celular 3:</div>
                                                        <input type="text" class="form-control mb-2 w-150px" name="txtCelular3" id="txtCelular3" value="" readonly />
                                                    </div>
                                                </div>                                                
                                            </div>
                                        </div>

                                        <div class="separator separator-dashed"></div>                                        
                                        <div class="py-0">
                                            <div class="py-3 d-flex flex-stack flex-wrap">
                                                <div class="d-flex align-items-center collapsible collapsed rotate" data-bs-toggle="collapse" href="#view_mails" role="button" aria-expanded="false" aria-controls="view_mails">
                                                    <div class="me-3 rotate-90">
                                                        <span class="svg-icon svg-icon-3">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                <path d="M12.6343 12.5657L8.45001 16.75C8.0358 17.1642 8.0358 17.8358 8.45001 18.25C8.86423 18.6642 9.5358 18.6642 9.95001 18.25L15.4929 12.7071C15.8834 12.3166 15.8834 11.6834 15.4929 11.2929L9.95001 5.75C9.5358 5.33579 8.86423 5.33579 8.45001 5.75C8.0358 6.16421 8.0358 6.83579 8.45001 7.25L12.6343 11.4343C12.9467 11.7467 12.9467 12.2533 12.6343 12.5657Z" fill="currentColor" />
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <img src="assets/media/logos/email.png" class="w-20px me-3" />
                                                    <div class="me-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="text-gray-800 fw-bolder">E-mail</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="view_mails" class="collapse fs-6 ps-10" data-bs-parent="#view_datos_direccion">
                                                <div class="d-flex flex-wrap gap-5">
                                                    <div class="fv-row w-100 flex-md-root">
                                                        <label class="form-label">Email 1</label>
                                                        <input type="email" name="txtEmail1" id="txtEmail1" maxlength="150" placeholder="micorre@dominio.com" class="form-control mb-2 text-lowercase" value="" readonly />
                                                    </div>
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input" name="chkEnviar1" id="chkEnviar1" type="checkbox" disabled />
                                                        <span id="spanEnv1" class="form-check-label fw-bold text-muted" for="chkEnviar1">No Enviar</span>
                                                    </label>                                                    
                                                </div>
                                                <div class="d-flex flex-wrap gap-5">
                                                    <div class="fv-row w-100 flex-md-root">
                                                        <label class="form-label">Email 2</label>
                                                        <input type="email" name="txtEmail2" id="txtEmail2" maxlength="150" placeholder="micorre@dominio.com" class="form-control mb-2 text-lowercase" value="" readonly />
                                                    </div>
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input" name="chkEnviar2" id="chkEnviar2" type="checkbox" disabled />
                                                        <span id="spanEnv2" class="form-check-label fw-bold text-muted" for="chkEnviar2">No Enviar</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancelar</button>
                        </div>

                    </div>
                </div>
            </div>

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
            var _usuaid = "<?php echo $xUsuaid; ?>";
            var _provincia = "<?php echo $xProvincia; ?>";
            var _ciudadid = "<?php echo $xCiudadid; ?>";
            var _avatar = "<?php echo $xAvatar; ?>";
            var _cboprestaid = 0;
            var _cbopreeid = 0;
            var _cboprofid = 0;


            document.getElementById('imgfiletitular').style.backgroundImage="url(persona/" + _avatar + ")";

            $('#cboProvincia').val(_provincia).change();
            $('#cboCiudad').val(_ciudadid).change();

            $('#cboProvincia').change(function(){
                        
                _cboid = $(this).val(); //obtener el id seleccionado

                $("#cboCiudad").empty();
                $("#cboEspecialidad").empty();
                $("#cboProfesional").empty();

                var _parametros = {
                    xxPaisId: _paisid,
                    xxEmprid: _emprid,
                    xxComboId: _cboid,
                    xxOpcion: 0
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

            $('#cboCiudad').change(function(){
                        
                _ciudadid = $(this).val(); //obtener el id seleccionado

                $("#cboPrestador").empty();
                $("#cboEspecialidad").empty();
                $("#cboProfesional").empty();

                var _parametros = {
                    xxPaisid: _paisid,
                    xxEmprid: _emprid,
                    xxCiudadid: _ciudadid
                }

                var _respuesta = $.post("codephp/get_comboprestador.php", _parametros);
                _respuesta.done(function(response) {
                    //document.getElementById("city").className = "form-control";
                    $("#cboPrestador").html(response);
                    
                });
                _respuesta.fail(function() {
                });
                _respuesta.always(function() {
                });

            }); 

            $('#btnDatosPrestador').click(function(){

                _presid = $('#cboPrestador').val();
                alert(_presid);

                if(_presid == ''){
                    mensajesalertify("Seleccione Prestador", "W", "top-center", 5);
                    return;
                }

                var _parametros = {
                    xxPaisid: _paisid,
                    xxEmprid: _emprid,
                    xxPresid: _presid
                }                

                var xrespuesta = $.post("codephp/get_datosprestadora.php", _parametros);
                xrespuesta.done(function(response){
                    var json = JSON.parse(response);
                    //console.log(json.Logo);
                    if(json.Logo == ''){
                        document.getElementById('imgfileprestador').style.backgroundImage="url(assets/media/svg/files/blank-image.svg)";    
                    }else{
                        document.getElementById('imgfileprestador').style.backgroundImage="url(logos/" + json.Logo + ")";
                    }

                    $("#modal-prestador").find("input,textarea").val("");
                    $("#modal-prestador").modal("show");
                    $('#modal-prestador').modal('handleUpdate');

                    $('#txtTipoprestador').val(json.TipoPrestador);
                    $('#txtSector').val(json.Sector);
                    $('#txtDireccion').val(json.Direccion);
                    $('#txtUrl').val(json.Url);
                    $('#txtFono1').val(json.Fono1);
                    $('#txtFono2').val(json.Fono2);
                    $('#txtFono3').val(json.Fono3);
                    $('#txtCelular1').val(json.Celu1);
                    $('#txtCelular2').val(json.Celu2);
                    $('#txtCelular3').val(json.Celu3);
                    $('#txtEmail1').val(json.Email1);
                    $('#txtEmail2').val(json.Email2);

                    if(json.Enviar1 == 'SI'){                        
                        $('#chkEnviar1').prop('checked','checked');
                        var _span1 = document.getElementById("spanEnv1");
                        _span1.innerHTML = '<span id="spanEnv1" class="form-check-label fw-bold" for="chkEnviar1"><strong>Enviar</strong></span>';                        
                    }

                    if(json.Enviar2 == 'SI'){
                        $('#chkEnviar2').prop('checked','checked');
                        var _span2 = document.getElementById("spanEnv2");
                        _span2.innerHTML = '<span id="spanEnv1" class="form-check-label fw-bold" for="chkEnviar1"><strong>Enviar</strong></span>';                        
                    }                    

                });
            });
            
            $('#cboPrestador').change(function(){

                _cboprestaid = $(this).val();

                $("#cboEspecialidad").empty();
                $("#cboProfesional").empty();

                var _parametros = {
                    xxPaisid: _paisid,
                    xxEmprid: _emprid,
                    xxPrestaid: _cboprestaid
                }

                var _respuesta = $.post("codephp/get_comboespeciprestador.php", _parametros);
                _respuesta.done(function(response) {
                    $("#cboEspecialidad").html(response);
                    
                });
                _respuesta.fail(function() {
                });
                _respuesta.always(function() {
                });
            });

            $('#cboEspecialidad').change(function(){

                _cbopreeid = $(this).val();

                $("#cboProfesional").empty();

                var _parametros = {
                    xxPaisid: _paisid,
                    xxEmprid: _emprid,
                    xxPreeid: _cbopreeid
                }

                var _respuesta = $.post("codephp/get_comboprofesionalespeci.php", _parametros);
                _respuesta.done(function(response) {
                    $("#cboProfesional").html(response);
                    
                });
                _respuesta.fail(function() {
                });
                _respuesta.always(function() {
                });
            });
            
            $('#btnNuevaAgenda').click(function(){

                let _tituid = "<?php echo $xTituid; ?>";
                let _prodid = "<?php echo $xProdid; ?>";
                let _grupid = "<?php echo $xGrupid; ?>";

                let _cboprofid = $("#cboProfesional").val();
                let _cbociudid = $("#cboCiudad").val();
                
                if(_tituid == ''){
                    mensajesalertify("No se ha seleccionado Cliente para Agendamiento", "W", "top-center", 5);
                    return;                    
                }
                
                if(_cboprestaid == 0){
                    mensajesalertify("Seleccione Prestador", "W", "top-center", 5);
                    return;
                }

                if(_cbopreeid == 0){
                    mensajesalertify("Seleccione Especialidad", "W", "top-center", 5);
                    return;
                }

                if(_cboprofid == 0){
                    mensajesalertify("Seleccione Profesional", "W", "top-center", 5);
                    return;
                }                  

                $.redirect('?page=admincalendar&menuid=<?php echo $menuid; ?>', {'tituid': _tituid, 'beneid': 0, 'prodid': _prodid, 'grupid': _grupid, 'presaid': _cboprestaid, 'preeid': _cbopreeid, 'pfesid': _cboprofid, 'ciudid': _cbociudid }); //POR METODO POST

            });
            
        });        
        
        //Desplazar-modal
        $("#modal-prestador").draggable({
            handle: ".modal-header"
        });        

    </script>