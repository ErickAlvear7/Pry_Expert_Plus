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

    //ID POST
    $xTituid = $_POST['tituid'];
    $xBeneid = $_POST['beneid'];
    $xPresid = $_POST['presid'];
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

    //DATOS TITULAR
    $xSQL = "SELECT CONCAT(per.pers_nombres, ' ', per.pers_apellidos) AS Nombres, per.pers_imagen AS Avatar,per.pers_estado AS Estado, ";
    $xSQL .="(SELECT UPPER(ciudad) FROM `provincia_ciudad` WHERE prov_id = $xCiudid) AS Ciudad FROM  `expert_persona` per INNER JOIN `expert_titular` tit ON per.pers_id = tit.pers_id WHERE tit.titu_id = $xTituid AND ";
    $xSQL .="tit.prod_id = $xProdid AND tit.grup_id = $xGrupid AND tit.pais_id = $xPaisid AND tit.empr_id = $xEmprid ";
    $all_titular = mysqli_query($con, $xSQL);
    foreach ($all_titular as $datos) {

        $xNombres = $datos['Nombres'];
        $xAvatar = $datos['Avatar'];
        $xEstado = $datos['Estado'];
        $xCiudad = $datos['Ciudad'];
 
        if($xEstado == 'A'){
            $xEstado = 'Activo'; 
        }else{
            $xEstado = 'Inactivo';
        }
    }

    //DATOS PRODUCTO Y GRUPO
    $xSQL = "SELECT pro.prod_nombre AS Producto, pro.prod_descripcion AS DescPro, gru.grup_nombre AS Grupo, gru.grup_descripcion AS DescGru ";
    $xSQL .= "FROM `expert_productos` pro INNER JOIN `expert_grupos` gru ON pro.grup_id = gru.grup_id WHERE pro.prod_id = $xProdid AND ";
    $xSQL .= "pro.grup_id = $xGrupid AND pro.pais_id = $xPaisid AND pro.empr_id = $xEmprid ";
    $all_progru = mysqli_query($con, $xSQL);
    foreach ($all_progru as $datos) {

        $xProducto = $datos['Producto'];
        $xDescPro = $datos['DescPro'];
        $xGrupo = $datos['Grupo'];
        $xDescGru = $datos['DescGru'];

    }

    //DATOS PRESTADOR
    $xSQL = "SELECT pres_nombre AS Prestador, pres_tipoprestador AS Tipo, pres_sector AS Sector FROM `expert_prestadora` WHERE pres_id = $xPresid ";
    $xSQL .= "AND pais_id = $xPaisid AND empr_id = $xEmprid ";
    $all_prestador = mysqli_query($con, $xSQL);
    foreach ($all_prestador as $datos) {

        $xPrestador = $datos['Prestador'];
        $xTipo = $datos['Tipo'];
        $xSector = $datos['Sector'];

    }

     //DATOS ESPECIALIDAD
     $xSQL = "SELECT espe_nombre AS Especialidad, espe_descripcion AS Descripcion, espe_pvp AS Costo FROM `expert_especialidad` WHERE espe_id = $xEspeid ";
     $xSQL .= "AND pais_id = $xPaisid AND empr_id = $xEmprid";
     $all_especialidad = mysqli_query($con, $xSQL);
     foreach ($all_especialidad as $datos) {

        $xEspecialidad = $datos['Especialidad'];
        $xDescripcion = $datos['Descripcion'];
        $xCosto = $datos['Costo'];

    }

    //DATOS PROFESIONAL
    $xSQL = " SELECT (SELECT CONCAT(pro.prof_nombres,' ',pro.prof_apellidos) FROM `expert_profesional` pro WHERE esp.prof_id = pro.prof_id) AS Profesional ";
    $xSQL .= "FROM `expert_profesional_especi` esp WHERE esp.pfes_id = $xPfesid AND esp.pais_id = $xPaisid AND esp.empr_id =$xEmprid  ";
    $all_profesional = mysqli_query($con, $xSQL);
    foreach ($all_profesional as $datos) {
       $xProfesional = $datos['Profesional'];
   }

    //INTERRVALOS DE ATENCION DEL PROFESIONAL
    $xSQL = "SELECT * FROM `expert_profesional_especi` ";
	$xSQL .= "WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pfes_id=$xPfesid  ";
    $all_intervalo = mysqli_query($con, $xSQL);
    foreach ($all_intervalo as $datos) {
        $xIntervalo = $datos['intervalo'];
    }

?>

<div id="kt_content_container" class="container-xxl">
    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-1">
        <button type="button" id="btnRegresar" class="btn btn-icon btn-light-primary btn-sm ms-auto me-lg-n7" title="Regresar" data-bs-toggle="tooltip" data-bs-placement="left">
            <i class="fa fa-arrow-left" aria-hidden="true"></i>
        </button>
    </ul>   
    <div class="card mb-6">
        <div class="card-body pt-9 pb-0">
            <div class="d-flex flex-wrap flex-sm-nowrap">
                <div class="me-7 mb-4">
                    <div class="image-input image-input-empty image-input-outline mb-3" data-kt-image-input="true" style="background-image: url(assets/media/svg/files/blank-image.svg)">
                        <div class="image-input-wrapper w-150px h-150px" style="background-image: url(assets/media/svg/files/blank-image.svg);" id="imgfiletitular"></div>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-gray-900 text-hover-primary fs-2 fw-bolder me-1"><?php echo $xNombres; ?></span>
                                <a href="#">
                                    <span class="svg-icon svg-icon-1 svg-icon-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
                                            <path d="M10.0813 3.7242C10.8849 2.16438 13.1151 2.16438 13.9187 3.7242V3.7242C14.4016 4.66147 15.4909 5.1127 16.4951 4.79139V4.79139C18.1663 4.25668 19.7433 5.83365 19.2086 7.50485V7.50485C18.8873 8.50905 19.3385 9.59842 20.2758 10.0813V10.0813C21.8356 10.8849 21.8356 13.1151 20.2758 13.9187V13.9187C19.3385 14.4016 18.8873 15.491 19.2086 16.4951V16.4951C19.7433 18.1663 18.1663 19.7433 16.4951 19.2086V19.2086C15.491 18.8873 14.4016 19.3385 13.9187 20.2758V20.2758C13.1151 21.8356 10.8849 21.8356 10.0813 20.2758V20.2758C9.59842 19.3385 8.50905 18.8873 7.50485 19.2086V19.2086C5.83365 19.7433 4.25668 18.1663 4.79139 16.4951V16.4951C5.1127 15.491 4.66147 14.4016 3.7242 13.9187V13.9187C2.16438 13.1151 2.16438 10.8849 3.7242 10.0813V10.0813C4.66147 9.59842 5.1127 8.50905 4.79139 7.50485V7.50485C4.25668 5.83365 5.83365 4.25668 7.50485 4.79139V4.79139C8.50905 5.1127 9.59842 4.66147 10.0813 3.7242V3.7242Z" fill="#00A3FF" />
                                            <path class="permanent" d="M14.8563 9.1903C15.0606 8.94984 15.3771 8.9385 15.6175 9.14289C15.858 9.34728 15.8229 9.66433 15.6185 9.9048L11.863 14.6558C11.6554 14.9001 11.2876 14.9258 11.048 14.7128L8.47656 12.4271C8.24068 12.2174 8.21944 11.8563 8.42911 11.6204C8.63877 11.3845 8.99996 11.3633 9.23583 11.5729L11.3706 13.4705L14.8563 9.1903Z" fill="white" />
                                        </svg>
                                    </span>
                                </a>
                            </div>
                            <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                <span class="svg-icon svg-icon-4 me-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z" fill="currentColor" />
                                        <path d="M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z" fill="currentColor" />
                                    </svg>
                                </span>
                                Developer</a>
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
                                
                                <span class="svg-icon svg-icon-4 me-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor" />
                                        <path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor" />
                                    </svg>
                                </span>
                                SF, Bay Area</a>
                                <a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
                                <span class="svg-icon svg-icon-4 me-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path opacity="0.3" d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z" fill="currentColor" />
                                        <path d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z" fill="currentColor" />
                                    </svg>
                                </span>
                                max@kt.com</a>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap flex-stack">
                        <div class="d-flex flex-column flex-grow-1 pe-8">
                            <div class="d-flex flex-wrap">

                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <span class="svg-icon svg-icon-3 svg-icon-success me-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
                                                <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor" />
                                            </svg>
                                        </span>
                                        <div class="fs-2 fw-bolder" data-kt-countup="true" data-kt-countup-value="4500" data-kt-countup-prefix="$">0</div>
                                    </div>
                                    <div class="fw-bold fs-6 text-gray-400">Earnings</div>
                                </div>

                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <span class="svg-icon svg-icon-3 svg-icon-danger me-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                                                <path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z" fill="currentColor" />
                                            </svg>
                                        </span>
                                        <div class="fs-2 fw-bolder" data-kt-countup="true" data-kt-countup-value="80">0</div>
                                    </div>
                                    <div class="fw-bold fs-6 text-gray-400">Projects</div>
                                </div>

                                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <span class="svg-icon svg-icon-3 svg-icon-success me-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
                                                <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor" />
                                            </svg>
                                        </span>
                                        <div class="fs-2 fw-bolder" data-kt-countup="true" data-kt-countup-value="60" data-kt-countup-prefix="%">0</div>
                                    </div>
                                    <div class="fw-bold fs-6 text-gray-400">Success Rate</div>
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

<!--MODAL AGENDAMIENTO -->
<div class="modal fade" id="modal_new_agenda" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-900px">
        <div id="modal_new_agenda_parent" class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Angendar Cita</h2>
                <i class="fa fa-window-close fa-2x" aria-hidden="true" data-bs-dismiss="modal"></i>
            </div>
            <div class="modal-body py-lg-10 px-lg-10">
                <div class="card card-flush py-4">
                    <div class="card-body pt-0">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="required fs-6 fw-bold form-label">Tipo Registro</label>
                                <select name="cboTipoRegistro" id="cboTipoRegistro" aria-label="Seleccione Registro" data-control="select2" data-placeholder="Seleccione Registro" data-dropdown-parent="#modal_new_agenda_parent" class="form-select mb-2">
                                    <option value=""></option>
                                    <option value="Agendar">AGENDAR</option>
                                    <option value="Informacion">INFORMACION</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="required fs-6 fw-bold form-label">Motivo</label>
                                <select name="cboMotivo" id="cboMotivo" aria-label="Seleccione Motivo" data-control="select2" data-placeholder="Seleccione Motivo" data-dropdown-parent="#modal_new_agenda_parent" class="form-select mb-2">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                           <div class="col-md-12">
                                <label class="required fs-6 fw-bold form-label">Observacion</label>
                                <textarea class="form-control text-uppercase" name="txtObservacion" id="txtObservacion" maxlength="500" onkeydown="return (event.keyCode!=13);"></textarea>
                           </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="fs-6 fw-bold form-label">Fecha Inicio</label>
                                <input class="form-control form-control-solid" name="fecha_inicio" id="fecha_inicio" disabled />
                            </div>
                            <div class="col-md-6">
                                <label class="fs-6 fw-bold form-label">Hora Inicio</label>
                                <input class="form-control form-control-solid" name="hora_inicio" id="hora_inicio" disabled />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fs-6 fw-bold form-label">Fecha Fin</label>
                                <input class="form-control form-control-solid" name="fecha_fin" id="fecha_fin" disabled />

                            </div>
                            <div class="col-md-6">
                                <label class="fs-6 fw-bold form-label">Hora Fin</label>
                                <input class="form-control form-control-solid" name="hora_fin" id="hora_fin" disabled />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger" data-bs-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i>Cerrar</button>
                <button type="button" id="btnAgendar" class="btn btn-sm btn-light-primary"><i class="fa fa-check"></i>Agendar</button>
            </div>
        </div>
    </div>
</div> 

<!--MODAL RESERVA -->
<div class="modal fade" id="modal_new_reserva" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-900px">
        <div id="modal_new_reserva_parent" class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Reserva Cita</h2>
                <i class="fa fa-window-close fa-2x" aria-hidden="true" data-bs-dismiss="modal"></i>
            </div>
            <div class="modal-body py-lg-10 px-lg-10">
                <div class="card card-flush py-4">
                    <div class="card-body pt-0">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="required fs-6 fw-bold form-label">Tipo Registro</label>
                                <select name="cboTipoRegistroRe" id="cboTipoRegistroRe" aria-label="Seleccione Registro" data-control="select2" data-placeholder="Seleccione Registro" data-dropdown-parent="#modal_new_reserva_parent" class="form-select mb-2">
                                    <option value=""></option>
                                    <option value="Agendar">AGENDAR</option>
                                    <option value="Informacion">INFORMACION</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="required fs-6 fw-bold form-label">Motivo</label>
                                <select name="cboMotivoRe" id="cboMotivoRe" aria-label="Seleccione Motivo" data-control="select2" data-placeholder="Seleccione Motivo" data-dropdown-parent="#modal_new_reserva_parent" class="form-select mb-2">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                           <div class="col-md-12">
                                <label class="required fs-6 fw-bold form-label">Observacion</label>
                                <textarea class="form-control text-uppercase" name="txtObservacionRe" id="txtObservacionRe" maxlength="500" onkeydown="return (event.keyCode!=13);"></textarea>
                           </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="fs-6 fw-bold form-label">Fecha Inicio</label>
                                <input class="form-control form-control-solid" name="fecha_iniciore" id="fecha_iniciore" disabled />
                            </div>
                            <div class="col-md-6">
                                <label class="fs-6 fw-bold form-label">Hora Inicio</label>
                                <input class="form-control form-control-solid" name="hora_iniciore" id="hora_iniciore" disabled />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="fs-6 fw-bold form-label">Fecha Fin</label>
                                <input class="form-control form-control-solid" name="fecha_finre" id="fecha_finre" disabled />

                            </div>
                            <div class="col-md-6">
                                <label class="fs-6 fw-bold form-label">Hora Fin</label>
                                <input class="form-control form-control-solid" name="hora_finre" id="hora_finre" disabled />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnCancelarRe" class="btn btn-sm btn-light-danger" ><i class="las la-eraser"></i>Eliminar Reserva</button>
                <button type="button" id="btnAgendarRe" class="btn btn-sm btn-light-primary" ><i class="fa fa-check"></i>Agendar</button>
            </div>
        </div>
    </div>
</div>  

<script>

    $(document).ready(function(){

        var _paisid = "<?php echo $xPaisid; ?>";
        var _emprid = "<?php echo $xEmprid; ?>";
        var _tituid = "<?php echo $xTituid; ?>";
        var _presid = "<?php echo $xPresid; ?>";
        var _espeid = "<?php echo $xEspeid; ?>";
        var _pfesid = "<?php echo $xPfesid; ?>";
        var _prodid = "<?php echo $xProdid; ?>";
        var _grupid = "<?php echo $xGrupid; ?>";
        var _ciudid = "<?php echo $xCiudid; ?>";
        var _usuaid = "<?php echo $xUsuaid; ?>";            
        var _interval = "<?php echo $xIntervalo; ?>";

        var _idrsrvid = 0;
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
                        hidePopovers();
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
                    eventClick: function (arg) {
                        hidePopovers();
                        f_ClickAgenda(arg);
                    },
                    eventMouseEnter: function (arg) {
                        f_ViewDatos(arg);
                    },
                                    
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

            let _horaactual = moment(_fechaactual);
            //let _horaselect = moment(info.endStr);
            let _horaselect = moment(info.startStr);
            
            //_diferenminuts = _horaselect.diff(_horaactual, "m");
            _diferenminuts = _horaactual.diff(_horaselect, "m");
            //SUMAR 10 MINUTOS A LA DIFERENCIA, PARA DARLES 10 MINUTOS MAS
            //_diferenminuts = moment(_diferenminuts).add(10,'m').format("HH:mm");
            
            if(_diferenminuts > 5){
                mensajesalertify("La hora seleccionada esta fuera del intervalo de..! " + _interval + " minutos" , "W", "top-center", 5);
                return;
            }

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
                            _continuar = false;
                            mensajesalertify("El horario seleccionado esta fuera del tiempo programado..!" , "W", "top-center", 5);
                            return;   
                        }
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
                            "xxUsuaid" : _usuaid
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

        function f_ViewDatos(arg){

            hidePopovers();

            let _fechareserva = moment(arg.event.startStr).format("YYYY-MM-DD");

            element = arg.el;
            //console.log(arg.event.title);

            //const popoverHtml = '<div class="fw-bolder mb-2">' + arg.event.extendedProps.description + '</div><div class="fs-7"><span class="fw-bold">Reserva:</span> ' + _fechareserva + '</div><div class="fs-7 mb-4"><span class="fw-bold">End:</span> ' + arg.event.id + '</div><div id="btnViewReserva" type="button" class="btn btn-sm btn-light-primary">View More</div>';
            const popoverHtml = '<div class="fw-bolder mb-2">' + arg.event.extendedProps.description + '</div><div class="fs-7 mb-2"><span class="fw-bold">Fecha Registro:</span> ' + _fechareserva + '</div><div class="fs-7"><span class="fw-bold">Hora Inicio:</span> ' + arg.event.extendedProps.horaini + '</div><div class="fs-7 mb-2"><span class="fw-bold">Hora Fin:</span> ' + arg.event.extendedProps.horafin + '</div><div class="fs-7"><span class="fw-bold">Operador@:</span> ' + arg.event.extendedProps.username + '</div>';

            // Popover options
            var options = {
                container: 'body',
                trigger: 'manual',
                boundary: 'window',
                placement: 'auto',
                dismiss: true,
                html: true,
                title: arg.event.title,
                content: popoverHtml,
            }

            // Initialize popover
            popover = KTApp.initBootstrapPopover(element, options);

            // Show popover
            popover.show();
            popoverState = true;
        }

        function f_ClickAgenda(arg){

            let _continuar = true;
            _idrsrvid = arg.event.id;

            f_LimpiarModalRe();

            let _tipo = arg.event.title;
            let _agenteid = arg.event.extendedProps.usuariocreacion;
            let _fechaactual = new Date();
            let _fechaselec = moment(arg.event.startStr).format("YYYY-MM-DD");
            let _fechaactual_select = moment(_fechaactual).format("YYYY-MM-DD");

            let _formattedDate = _fechaactual.toDateString() + ' ' + arg.event.extendedProps.horaini + ' GMT-0500';
            let _fechaactual_new = new Date(_formattedDate);

            let _formatedatefin = _fechaactual.toDateString() + ' ' + arg.event.extendedProps.horafin + ' GMT-0500';
            let _fechafin_new = new Date(_formatedatefin);

            if(_fechaselec < _fechaactual_select){
                _continuar = false;
                f_DelReserva();
                return;
            }

            if(_fechaselec == _fechaactual_select){
                let _horaactual = moment(_fechaactual);
                let _horaselect = moment(_fechaactual_new);

                let _diferenminuts = _horaactual.diff(_horaselect, "m");

                if(_diferenminuts > 5){
                    _continuar = false;
                    f_DelReserva();
                    return;
                }
            }
            
            if(_continuar){
                if(_tipo == 'RESERVADO'){

                    _fechareserva = moment(arg.event.startStr).format("YYYY-MM-DD");
                    _fechainiciore = moment(arg.event.start).format("YYYY-MM-DD");
                    _fechafinre = moment(arg.event.end).format("YYYY-MM-DD");
                    _horainiciore = moment(_fechaactual_new).format("HH:mm");
                    _horafinre = moment(_fechafin_new).format("HH:mm");
                    _dayselect = arg.event.extendedProps.codigo_dia;

                    $('#fecha_iniciore').val(_fechainiciore);
                    $('#fecha_finre').val(_fechafinre);
                    $('#hora_iniciore').val(_horainiciore);
                    $('#hora_finre').val(_horafinre);

                    $("#modal_new_reserva").modal("show");

                }
            }

            if(_tipo == 'AGENDADO'){
                let _usuaid = "<?php echo $xUsuaid; ?>";

                if(_agenteid == _usuaid){
                }
            }            

        }

        const f_DelReserva = () => {
            var _parametros = {
                "xxRsrvid":  _idrsrvid,
                "xxPaisid" : _paisid,
                "xxEmprid" : _emprid,
                "xxUsuaid" : _usuaid
            }
            
            var _respuesta = $.post("codephp/del_reservatmp_new.php", _parametros);
            _respuesta.done(function(response){
                if(response = 1){
                    calendar.getEventById(_idrsrvid).remove();
                    Swal.fire({
                        text: "Reserva elimina, por tiempo de validez finalizado ",
                        icon: "warning",
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });
                }
            });             

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

        const f_LimpiarModalRe = () => {
            $('#cboTipoRegistroRe').val('').change();
            $('#cboMotivoRe').empty();
            var _html = "<option value=''></option>";
            $("#cboMotivoRe").html(_html);            
           
            $('#txtObservacionRe').val('');
            $('#fecha_iniciore').val('');
            $('#fecha_finre').val('');
            $('#hora_iniciore').val('');
            $('#hora_finre').val('');
        }

        $('#btnRegresar').click(function(){
            $.redirect('?page=agendar_titubeneadmin&menuid=<?php echo $menuid; ?>', {
               'tituid': _tituid,
               'prodid': _prodid,
               'grupid': _grupid,
               'agendaid': 0,
            });
            
        });

        $('#modal_new_agenda').on('hidden.bs.modal', function () {

            var _parametros = {
                "xxPaisid" : _paisid,
                "xxEmprid" : _emprid,
                "xxPresid" : _presid,
                "xxEspeid" : _espeid,
                "xxPfesid" : _pfesid,
                "xxFechaInicio" : _fechainicio,
                "xxFechaFin" : _fechafin,
                "xxCodigoDia" : _dayselect,
                "xxUsuaid" : _usuaid
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
                    "xxPaisid" : _paisid,
                    "xxEmprid" : _emprid,
                    "xxPresid" : _presid,
                    "xxEspeid" : _espeid                    
                }

                var _respuesta = $.post("codephp/cargar_motivos.php", _parametros);
                _respuesta.done(function(response) {
                    $("#cboMotivo").html(response);
                    
                });
                _respuesta.fail(function() {
                });
                _respuesta.always(function() {
                });
            }else{ 
                var _html = "<option value=''></option>";
                //_html += "<option value='Informacion'>Informacion</option>";

                $("#cboMotivo").html(_html);
            }

        });
        
        $('#cboTipoRegistroRe').change(function(){
                    
            _cboid = $(this).val(); //obtener el id seleccionado
            $("#cboMotivoRe").empty();

            if(_cboid == 'Agendar'){
                var _parametros = {
                    "xxPaisid" : _paisid,
                    "xxEmprid" : _emprid,
                    "xxPresid" : _presid,
                    "xxEspeid" : _espeid                    
                }

                var _respuesta = $.post("codephp/cargar_motivos.php", _parametros);
                _respuesta.done(function(response) {
                    $("#cboMotivoRe").html(response);
                    
                });
                _respuesta.fail(function() {
                });
                _respuesta.always(function() {
                });
            }else{ 
                var _html = "<option value=''></option>";
                $("#cboMotivoRe").html(_html);
            }

        });        

        //AGENDAR CITA
        $('#btnAgendar').click(function(){

            var _tiporegistro = $('#cboTipoRegistro').val();
            var _motivo = $('#cboMotivo').val();
            var _observacion = $('#txtObservacion').val();
            var _fechainicio = $('#fecha_inicio').val();
            var _fechafinal = $('#fecha_fin').val();
            var _horainicio = $('#hora_inicio').val();
            var _horafin = $('#hora_fin').val();
            var _tipocliente = "T";
            var _beneid = 0;

            if(_tiporegistro == ''){
                
                toastSweetAlert("top-end",3000,"warning","Seleccione Tipo Registro..!!");
                return;
            }

            if(_motivo == ''){
                
                toastSweetAlert("top-end",3000,"warning","Seleccione Motivo..!!");
                return;
            }

            if(_observacion == ''){
                
                toastSweetAlert("top-end",3000,"warning","Ingrese Observacion..!!");
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
                    toastSweetAlert("top-end",3000,"warning","Hora Fuera del Intervalo de"  + _interval + " minutos");
                    return;
                }
            }

            let _fechainiagenda = _fechainicio + ' ' + _horainicio;
            let _fechafinagenda = _fechafinal + ' ' + _horafin;

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

                    $.redirect('?page=agendar_titubeneadmin&menuid=<?php echo $menuid; ?>', { 'tituid': _tituid, 'beneid': _beneid, 'prodid': _prodid, 'grupid': _grupid, 'agendaid': response });
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

        $('#btnCancelarRe').click(function(){

            var _parametros = {
                "xxRsrvid":  _idrsrvid,
                "xxPaisid" : _paisid,
                "xxEmprid" : _emprid,
                "xxUsuaid" : _usuaid
            }
            
            var _respuesta = $.post("codephp/del_reservatmp_new.php", _parametros);
            _respuesta.done(function(response){
                if(response = 1){
                    calendar.getEventById(_idrsrvid).remove();
                }

                $("#modal_new_reserva").modal("hide");
            });            

        });

        //AGENDAR CITA RESERVADA
        $('#btnAgendarRe').click(function(){

            var _tiporegistro = $('#cboTipoRegistroRe').val();
            var _motivo = $('#cboMotivoRe').val();
            var _observacion = $('#txtObservacionRe').val();
            var _fechainicio = $('#fecha_iniciore').val();
            var _fechafinal = $('#fecha_finre').val();
            var _horainicio = $('#hora_iniciore').val();
            var _horafin = $('#hora_finre').val();
            var _tipocliente = "T";
            var _beneid = 0;

            if(_tiporegistro == ''){
                
                toastSweetAlert("top-end",3000,"warning","Seleccione Tipo Registro..!!");
                return;
            }

            if(_motivo == ''){
                
                toastSweetAlert("top-end",3000,"warning","Seleccione Motivo..!!");
                return;
            }

            if(_observacion == ''){
                
                toastSweetAlert("top-end",3000,"warning","Ingrese Observacion..!!");
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
                    toastSweetAlert("top-end",3000,"warning","Hora Fuera del Intervalo de"  + _interval + " minutos");
                    return;
                }
            }

            console.log(_diferenminuts);
            console.log(_interval);
            
            return;            

            let _fechainiagenda = _fechainicio + ' ' + _horainicio;
            let _fechafinagenda = _fechafinal + ' ' + _horafin;

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

                    $.redirect('?page=agendar_titubeneadmin&menuid=<?php echo $menuid; ?>', { 'tituid': _tituid, 'beneid': _beneid, 'prodid': _prodid, 'grupid': _grupid, 'agendaid': response });
                }else{
                    Swal.fire({
                        text: "Error en el envio del correo, valide la informacion",
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok!",
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


    });

    //Desplazar-modal
    $("#modal_new_agenda").draggable({
        handle: ".modal-header"
    });     
    
    $("#modal_new_reserva").draggable({
        handle: ".modal-header"
    });


</script>