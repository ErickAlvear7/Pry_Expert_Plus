
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
    $xBeneid = $_POST['beneid'];
    $xProdid = $_POST['prodid'];
    $xGrupid = $_POST['grupid'];
    $xAgendaid = $_POST['agendaid'];

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

    //CONSULTA BENEFICIARIOS DEL TITULAR
    $xSQL  = "SELECT  ben.bene_id AS IdBene,CONCAT(ben.bene_nombres,' ',ben.bene_apellidos) as Nombres,ben.bene_ciudad AS IdCiudad, ben.bene_numerodocumento AS Doumento,ben.bene_estado AS Estado,(SELECT ciudad FROM ";
    $xSQL .= "`provincia_ciudad` ciu WHERE ben.bene_ciudad = ciu.prov_id AND ciu.pais_id=$xPaisid) as Ciudad,(SELECT prv.provincia FROM `provincia_ciudad` prv WHERE ben.bene_ciudad=prv.prov_id) AS Provincia,(SELECT pade_nombre FROM ";
    $xSQL .= "`expert_parametro_detalle` det WHERE ben.bene_parentesco = det.pade_valorV) AS Parentesco FROM  `expert_beneficiario` ben ";
    $xSQL .= "INNER JOIN `expert_productos` pro ON ben.prod_id = pro.prod_id WHERE ben.titu_id=$xTituid AND ben.pais_id=$xPaisid AND ben.prod_id=$xProdid AND pro.grup_id=$xGrupid ";
    $all_beneficiarios = mysqli_query($con, $xSQL);

    $xSQL = "SELECT DISTINCT provincia AS Descripcion FROM `provincia_ciudad` ";
	$xSQL .= "WHERE pais_id=$xPaisid AND estado='A' ORDER BY provincia ";
    $all_provincia = mysqli_query($con, $xSQL);

       //CONSULTAS PARA ULTIMA CITA AGENDADA
       //CONSULTA PRESTADORA-CIUDAD-SECTOR 
    $xSQL = "SELECT xag.pres_id AS Idpres, xpr.pres_nombre AS Prestadora, (SELECT ciudad  FROM `provincia_ciudad` pxc WHERE pxc.prov_id=xpr.prov_id) AS Ciudad, ";
    $xSQL .="xpr.pres_sector AS Sector FROM `expert_agenda` xag INNER JOIN `expert_prestadora` xpr ON xag.pres_id=xpr.pres_id ";
    $xSQL .="ORDER BY xag.fechacreacion DESC LIMIT 1 ";
    $all_UltiAgendamiento = mysqli_query($con, $xSQL);
    foreach ($all_UltiAgendamiento as $datos) {
        $xPresId = $datos['Idpres'];
        $xAgnPrestador = $datos['Prestadora'];
        $xAgnCiudad = $datos['Ciudad'];
        $xAgnSector = $datos['Sector'];
    }

        //COMNSULTA PROFESIONAL-ESPECIALIDAD-OBSERVACION
        $xSQL = "SELECT (SELECT CONCAT(xpf.prof_nombres,' ',xpf.prof_apellidos) FROM `expert_profesional` xpf WHERE ";
        $xSQL .="xpe.prof_id=xpf.prof_id) AS Profesional,(SELECT xes.espe_nombre AS Especialidad FROM `expert_especialidad` xes ";
        $xSQL .="WHERE xag.espe_id=xes.espe_id) AS Especialidad,xag.observacion AS Observacion,xag.pfes_id AS Idproes FROM `expert_agenda` xag ";
        $xSQL .="INNER JOIN `expert_profesional_especi` xpe ON xag.pfes_id=xpe.pfes_id ";
        $xSQL .="ORDER BY xag.fechacreacion DESC LIMIT 1 ";
        $all_UltiAgendamiento = mysqli_query($con, $xSQL);
        foreach ($all_UltiAgendamiento as $datos) {
            $xAgnProfesional = $datos['Profesional'];
            $xAgnEspecialidad = $datos['Especialidad'];
            $xAgnObservacion = $datos['Observacion'];
            $xIdProfesional = $datos['Idproes'];
        }   
    
        //CONSULTA FECHA-HORA-ESTADO
        $xSQL = "SELECT DATE_FORMAT(xag.fecha_inicio,'%d/%m/%Y') AS Fecha, CONCAT(xag.hora_desde,'-',xag.hora_hasta) AS Hora, ";
        $xSQL .="xag.estado_agenda AS Estado FROM `expert_agenda` xag ORDER BY xag.fechacreacion DESC LIMIT 1 ";
        $all_UltiAgendamiento = mysqli_query($con, $xSQL);
        foreach ($all_UltiAgendamiento as $datos) {
            $xAgnFecha = $datos['Fecha'];
            $xAgnHora = $datos['Hora'];
            $xAgnEstado = $datos['Estado'];
        }
    
        $color = "";
    
        if($xAgnEstado == 'A'){
            $xAgnEstado = 'AGENDADO';
            $color = 'fs-6 text-primary fw-bold';
        }else if($xAgnEstado == 'C'){
            $xAgnEstado = 'CANCELADO';
            $color = 'fs-6 text-danger fw-bold';
        }else if($xAgnEstado == 'T'){
            $xAgnEstado = 'ATENDIDO';
            $color = 'fs-6 text-success fw-bold';
        }else if($xAgnEstado == 'S'){
            $xAgnEstado = 'AUSENTE';
            $color = 'fs-6 text-gray fw-bold';
        }


?>
<div id="kt_content_container" class="container-xxl">
    <div class="d-flex flex-column flex-lg-row">
        <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">
            <div class="card mb-5 mb-xl-8">
                <div class="card-header">
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
                    <div class="card-body">
                        <div class="d-flex flex-center flex-column py-2">
                            <div class="image-input image-input-empty image-input-outline mb-3" data-kt-image-input="true" style="background-image: url(assets/media/svg/files/blank-image.svg)">
                                <div class="image-input-wrapper w-150px h-150px" style="background-image: url(assets/media/svg/files/blank-image.svg);" id="imgfiletitular"></div>
                            </div>
                            <div class="fs-4 fw-bolder text-gray-700 text-center mb-3">
                                <span class="w-75px"><?php echo $xNombres . ' ' . $xApellidos; ?></span>
                            </div>
                            <div class="mb-9">
                                <div class="badge badge-lg badge-light-primary d-inline text-uppercase"><?php echo $xEstado; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-0">
                    <div class="d-flex align-items-center collapsible py-3 toggle mb-0" data-bs-toggle="collapse" data-bs-target="#view_grupo">														<!--begin::Icon-->
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
                        <h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">Grupo-Producto</h4>
                    </div> 
                </div>
                <div id="view_grupo" class="collapse show fs-6 ms-1">
                    <div class="card-body pt-2">
                        <div class="d-flex flex-column gap-10">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-briefcase-fill text-primary fs-1 me-5"></i>
                                <div class="d-flex flex-column">
                                    <h5 class="text-gray-800 fw-bolder">Grupo</h5>
                                    <div class="fw-bold">
                                        <label class="text-gray-600"><?php echo $xGrupo; ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">							
                                <i class="bi bi-bag-plus text-primary fs-1 me-5"></i>
                                <div class="d-flex flex-column">
                                    <h5 class="text-gray-800 fw-bolder">Producto</h5>
                                    <div class="fw-bold">
                                       <label class="text-gray-600"><?php echo $xProducto; ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-0">
                    <div class="d-flex align-items-center collapsible py-3 toggle collapsed mb-0" data-bs-toggle="collapse" data-bs-target="#view_titular">
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
                <div id="view_titular" class="collapse fs-6 ms-1">
                    <div class="card-body pt-2">
                       <div class="d-flex flex-column gap-10">
                            <div class="d-flex align-items-center">							
                                <i class="bi bi-filter-square text-primary fs-1 me-5"></i>
                                <div class="d-flex flex-column">
                                    <h5 class="text-gray-800 fw-bolder">No.Documento</h5>
                                    <div class="fw-bold">
                                       <label><?php echo $xNumDocumento; ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-filter-square text-primary fs-1 me-5"></i>
                                <div class="d-flex flex-column">
                                    <h5 class="text-gray-800 fw-bolder">Fecha de Nacimiento</h5>
                                    <div class="fw-bold">
                                        <div class="text-gray-600"><?php echo $xFechaNacimientoText; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-envelope-check text-primary fs-1 me-5"></i>
                                <div class="d-flex flex-column">
                                    <h5 class="text-gray-800 fw-bolder">Email</h5>
                                    <div class="fw-bold">
                                        <div class="text-gray-600"><?php echo $xEmail; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-filter-square text-primary fs-1 me-5"></i>
                                <div class="d-flex flex-column">
                                    <h5 class="text-gray-800 fw-bolder">Ciudad</h5>
                                    <div class="fw-bold">
                                        <div class="text-gray-600 text-uppercase"><?php echo $xCiudad; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-filter-square text-primary fs-1 me-5"></i>
                                <div class="d-flex flex-column">
                                    <h5 class="text-gray-800 fw-bolder">Direccion</h5>
                                    <div class="fw-bold">
                                        <div class="text-gray-600"><?php echo $xDireccion; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">							
                                <i class="bi bi-telephone-outbound text-primary fs-1 me-5"></i>
                                <div class="d-flex flex-column">
                                <h5 class="text-gray-800 fw-bolder">Telefono</h5>
                                    <div class="fw-bold">
                                        <div class="text-gray-600"><?php echo $xCelular; ?></div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-5 mb-xl-8">
                <div class="card-header border-0">
                    <div class="d-flex align-items-center collapsible py-3 toggle collapsed mb-0" data-bs-toggle="collapse" data-bs-target="#view_agenda">
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
                        <h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">Ultima Agenda</h4>
                    </div>
                </div>
                <div id="view_agenda" class="collapse fs-6 ms-1">
                    <div class="card-body pt-2">
                        <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-9 p-6">
                            <span class="svg-icon svg-icon-2tx svg-icon-primary me-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M22 19V17C22 16.4 21.6 16 21 16H8V3C8 2.4 7.6 2 7 2H5C4.4 2 4 2.4 4 3V19C4 19.6 4.4 20 5 20H21C21.6 20 22 19.6 22 19Z" fill="currentColor" />
                                    <path d="M20 5V21C20 21.6 19.6 22 19 22H17C16.4 22 16 21.6 16 21V8H8V4H19C19.6 4 20 4.4 20 5ZM3 8H4V4H3C2.4 4 2 4.4 2 5V7C2 7.6 2.4 8 3 8Z" fill="currentColor" />
                                </svg>
                            </span>
                            <div class="d-flex flex-stack flex-grow-1">
                                <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="
                                     Los datos mostrados, pertenecen al ultimo agendamiento realizado, que puede estar en estado cancelado o atentdido">
                                     <button class="btn btn-primary" type="button" disabled>Importante..!!</button>
                                </span>
                            </div>
                        </div>   
                        <div class="d-flex align-items-center mb-6">
                            <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-success"></span>
                            <div class="flex-grow-1 me-5">
                                <div class="text-gray-800 fw-bold fs-3">Prestador
                                    <span class="text-primary fw-bold fs-7"><?php echo $xAgnPrestador; ?></span>
                                </div>
                                <div class="text-gray-800 fw-bold fs-3">Ciudad/
                                    <span class="text-gray-600 fw-bold fs-7 text-uppercase"><?php echo $xAgnCiudad; ?></span>
                                </div>
                                <div class="text-gray-800 fw-bold fs-3">Sector/
                                    <span class="text-gray-600 fw-bold fs-7"><?php echo $xAgnSector; ?></span>
                                </div>
                            </div>
                            <a href="#" class="btn btn-sm btn-active-light-primary btnPres" title="Ver Prestador" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fa fa-eye"></i></a>
                        </div>
                        <div class="d-flex align-items-center mb-6">
                            <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-150px mh-100 me-4 bg-info"></span>
                            <div class="flex-grow-1 me-5">
                                <div class="text-gray-800 fw-bold fs-3">Profesional</div>
                                <span class="text-gray-600 fw-bold fs-7"><?php echo $xAgnProfesional; ?></span>
                                <div class="text-gray-700 fw-bold fs-3">Especialidad</div>
                                <span class="text-primary fw-bold fs-7"><?php echo $xAgnEspecialidad; ?></span>
                                <div class="text-gray-800 fw-bold fs-3">Observacion</div>
                                <span class="text-gray-600 fw-bold fs-7"><?php echo $xAgnObservacion; ?></span>
                            </div>
                            <a href="#" class="btn btn-sm btn-active-light-primary btnPro" title="Ver Profesional" data-bs-toggle="tooltip" data-bs-placement="right"><i class="fa fa-eye"></i></a>
                        </div>
                        <div class="d-flex align-items-center mb-6">
                            <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-primary"></span>
                            <div class="flex-grow-1 me-5">
                                <div class="text-gray-800 fw-bold fs-3">Fecha:
                                    <span class="text-gray-600 fw-bold fs-7"><?php echo $xAgnFecha; ?></span>
                                </div>
                                <div class="text-gray-800 fw-bold fs-3">Hora:
                                    <span class="text-gray-600 fw-bold fs-7"><?php echo $xAgnHora; ?></span>
                                </div>
                                <div class="text-gray-800 fw-bold fs-3">Estado/
                                    <span class="<?php echo $color; ?> fw-bold fs-7"><?php echo $xAgnEstado; ?></span>
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
                    <a class="nav-link text-active-primary pb-4 active" data-kt-countup-tabs="true" data-bs-toggle="tab" href="#tabTitular">Agendamiento Titular</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4 " data-bs-toggle="tab" href="#tabHistorial">Historial Citas</a>
                </li>              
                <li class="nav-item">
                    <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#tabBeneficiario">Beneficiarios</a>
                </li>
                <button type="button" id="btnRegresar" onclick="" class="btn btn-icon btn-light-primary btn-sm ms-auto me-lg-n7" title="Regresar" data-bs-toggle="tooltip" data-bs-placement="left">
                    <span class="svg-icon svg-icon-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M11.2657 11.4343L15.45 7.25C15.8642 6.83579 15.8642 6.16421 15.45 5.75C15.0358 5.33579 14.3642 5.33579 13.95 5.75L8.40712 11.2929C8.01659 11.6834 8.01659 12.3166 8.40712 12.7071L13.95 18.25C14.3642 18.6642 15.0358 18.6642 15.45 18.25C15.8642 17.8358 15.8642 17.1642 15.45 16.75L11.2657 12.5657C10.9533 12.2533 10.9533 11.7467 11.2657 11.4343Z" fill="currentColor" />
                        </svg>
                    </span>
                </button>
            </ul>
            <div class="tab-content" id="tabOpciones">
                <div class="tab-pane fade show active" id="tabTitular" role="tabpanel">
                    <div class="card pt-4 mb-6 mb-xl-9">
                        <div class="card-header border-0">
                            <div class="card-title">
                                <h2>Datos para Agendar</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0 pb-5">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="fs-6 fw-bold form-label">Ciudad</label>
                                    <?php 
                                        $xSQL = "SELECT prov_id AS Codigo, ciudad AS Descripcion FROM `provincia_ciudad` ";
                                        $xSQL .= "WHERE pais_id=$xPaisid AND estado='A' ORDER BY ciudad ";
                                        $all_ciudad = mysqli_query($con, $xSQL);
                                    ?>
                                    <select name="cboCiudad" id="cboCiudad" aria-label="Seleccione Ciudad" data-control="select2" data-placeholder="Seleccione Ciudad" data-dropdown-parent="#tabTitular" class="form-select mb-2">
                                        <?php foreach ($all_ciudad as $ciudad) : ?>
                                            <option value="<?php echo $ciudad['Codigo'] ?>"><?php echo mb_strtoupper($ciudad['Descripcion']) ?></option>
                                        <?php endforeach ?>
                                    </select>  
                                </div>
                                <div class="col-md-6">
                                    <label class="fs-6 fw-bold form-label">Provincia</label>
                                    <select name="cboProvincia" id="cboProvincia" aria-label="Seleccione Provincia" data-control="select2" data-placeholder="Seleccione Provincia" data-dropdown-parent="#tabTitular" class="form-select mb-2"  >
                                        <option></option>
                                        <?php foreach ($all_provincia as $prov) : ?>
                                            <option value="<?php echo $prov['Descripcion'] ?>"><?php echo mb_strtoupper($prov['Descripcion']) ?></option>
                                        <?php endforeach ?>
                                    </select>  
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="required fs-6 fw-bold form-label">Sector</label>
                                    <select name="cboSector" id="cboSector" aria-label="Seleccione Sector" data-control="select2" data-placeholder="Seleccione Sector" data-dropdown-parent="#tabTitular" class="form-select mb-2">
                                        <option></option>
                                        <?php 
                                        $xSQL = "SELECT pde.pade_valorV AS Codigo,pde.pade_nombre AS Descripcion FROM `expert_parametro_detalle` pde,`expert_parametro_cabecera` pca WHERE pca.pais_id=$xPaisid ";
                                        $xSQL .= "AND pca.paca_nombre='Tipo Sector' AND pca.paca_id=pde.paca_id AND pca.paca_estado='A' AND pade_estado='A' ";
                                        $all_datos =  mysqli_query($con, $xSQL);
                                        foreach ($all_datos as $datos){ ?>
                                            <option value="<?php echo $datos['Codigo'] ?>"><?php echo $datos['Descripcion'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="required fs-6 fw-bold form-label">Prestador</label>
                                    <button type="button" id="btnDatosPrestador" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" data-kt-menu-placement="bottom-end" title="Datos del Prestador" data-bs-toggle="tooltip" data-bs-placement="right">
                                        <i class="bi bi-search"></i>
                                    </button>
                                    <?php 
                                        $xSQL = "SELECT pres_id AS Codigo, pres_nombre AS Descripcion FROM `expert_prestadora` WHERE pais_id=$xPaisid and empr_id=$xEmprid AND prov_id=$xCiudadid AND pres_estado='A' ";
                                        //file_put_contents('log_1seguimiento.txt', $xSQL . "\n\n", FILE_APPEND);
                                        $all_prestadora = mysqli_query($con, $xSQL);
                                    ?>                                        
                                    <select name="cboPrestador" id="cboPrestador" aria-label="Seleccione Prestador" data-control="select2" data-placeholder="Seleccione Prestador" data-dropdown-parent="#tabTitular" class="form-select mb-2">
                                        <option></option>
                                        <?php foreach ($all_prestadora as $ciudad) : ?>
                                            <option value="<?php echo $ciudad['Codigo'] ?>"><?php echo mb_strtoupper($ciudad['Descripcion']) ?></option>
                                        <?php endforeach ?>                                            
                                    </select> 
                                </div>
                            </div>
                            <div class="mb-4 fv-row">
                                <label class="fs-6 fw-bold form-label mt-3">
                                    <span class="required">Especialidad</span>
                                </label>
                                <select name="cboEspecialidad" id="cboEspecialidad" aria-label="Seleccione Especialidad" data-control="select2" data-placeholder="Seleccione Especialidad" data-dropdown-parent="#tabTitular" class="form-select mb-2">
                                    <option></option>
                                </select> 
                            </div>
                            <div class="mb-4 fv-row">
                                <label class="fs-6 fw-bold form-label mt-3">
                                    <span class="required">Profesional</span>
                                    <button type="button" id="btnDatosProfesional" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto" data-kt-menu-placement="bottom-end" title="Datos del Profesional" data-bs-toggle="tooltip" data-bs-placement="right">
                                        <i class="bi bi-search"></i>
                                    </button>                                            
                                </label>
                                <select name="cboProfesional" id="cboProfesional" aria-label="Seleccione Profesional" data-control="select2" data-placeholder="Seleccione Profesional" data-dropdown-parent="#tabTitular" class="form-select mb-2">
                                    <option></option>
                                </select> 
                            </div>   
                        </div>                                
                    </div> 
                    <div class="card-toolbar">
                        <button type="button" data-repeater-create="" class="btn btn-light-primary btn-sm mb-2" id="btnCalendar"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                           Nuevo Agendamiento
                        </button>
                    </div>
                </div>
                <!--DATOS HISTORIAL-->
                <div class="tab-pane fade " id="tabHistorial" role="tabpanel">
                    <div class="card card-flush mb-6 mb-xl-9">
                        <div class="card-header mt-6">
                            <div class="card-title flex-column">
                                <h2 class="mb-1">User's Schedule</h2>
                                <div class="fs-6 fw-bold text-muted">2 upcoming meetings</div>
                            </div>
                            <div class="card-toolbar">
                                <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_add_schedule">
                                Add Schedule</button>
                            </div>
                        </div>
                        <div class="card-body p-9 pt-4">
                            <ul class="nav nav-pills d-flex flex-nowrap hover-scroll-x py-2">
                                <li class="nav-item me-1">
                                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary" data-bs-toggle="tab" href="#kt_schedule_day_0">
                                        <span class="opacity-50 fs-7 fw-bold">Nov-2023</span>
                                        <span class="fs-6 fw-boldest">11</span>
                                    </a>
                                </li>
                                <li class="nav-item me-1">
                                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary active" data-bs-toggle="tab" href="#kt_schedule_day_1">
                                        <span class="opacity-50 fs-7 fw-bold">Mo</span>
                                        <span class="fs-6 fw-boldest">22</span>
                                    </a>
                                </li>
                                <li class="nav-item me-1">
                                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary" data-bs-toggle="tab" href="#kt_schedule_day_2">
                                        <span class="opacity-50 fs-7 fw-bold">Tu</span>
                                        <span class="fs-6 fw-boldest">23</span>
                                    </a>
                                </li>
                                <li class="nav-item me-1">
                                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary" data-bs-toggle="tab" href="#kt_schedule_day_3">
                                        <span class="opacity-50 fs-7 fw-bold">We</span>
                                        <span class="fs-6 fw-boldest">24</span>
                                    </a>
                                </li>
                                <li class="nav-item me-1">
                                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary" data-bs-toggle="tab" href="#kt_schedule_day_4">
                                        <span class="opacity-50 fs-7 fw-bold">Th</span>
                                        <span class="fs-6 fw-boldest">25</span>
                                    </a>
                                </li>
                                <li class="nav-item me-1">
                                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary" data-bs-toggle="tab" href="#kt_schedule_day_5">
                                        <span class="opacity-50 fs-7 fw-bold">Fr</span>
                                        <span class="fs-6 fw-boldest">26</span>
                                    </a>
                                </li>
                                <li class="nav-item me-1">
                                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary" data-bs-toggle="tab" href="#kt_schedule_day_6">
                                        <span class="opacity-50 fs-7 fw-bold">Sa</span>
                                        <span class="fs-6 fw-boldest">27</span>
                                    </a>
                                </li>
                                <li class="nav-item me-1">
                                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary" data-bs-toggle="tab" href="#kt_schedule_day_7">
                                        <span class="opacity-50 fs-7 fw-bold">Su</span>
                                        <span class="fs-6 fw-boldest">28</span>
                                    </a>
                                </li>
                                <li class="nav-item me-1">
                                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary" data-bs-toggle="tab" href="#kt_schedule_day_8">
                                        <span class="opacity-50 fs-7 fw-bold">Mo</span>
                                        <span class="fs-6 fw-boldest">29</span>
                                    </a>
                                </li>
                                <li class="nav-item me-1">
                                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary" data-bs-toggle="tab" href="#kt_schedule_day_9">
                                        <span class="opacity-50 fs-7 fw-bold">Tu</span>
                                        <span class="fs-6 fw-boldest">30</span>
                                    </a>
                                </li>
                                <li class="nav-item me-1">
                                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-40px me-2 py-4 btn-active-primary" data-bs-toggle="tab" href="#kt_schedule_day_10">
                                        <span class="opacity-50 fs-7 fw-bold">We</span>
                                        <span class="fs-6 fw-boldest">31</span>
                                    </a>
                                </li>
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
                <!--DATOS BENEFICIARIO-->
                <div class="tab-pane fade" id="tabBeneficiario" role="tabpanel">
                    <div class="card pt-4 mb-6 mb-xl-9">
                        <div class="card-header border-0">
                            <div class="card-title">
                                <h2>Beneficiarios</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0 pb-5">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle table-row-dashed gy-5" id="kt_table_users_login_session">
                                    <thead class="border-bottom border-gray-200 fs-7 fw-bolder">
                                        <tr class="text-start text-muted text-uppercase gs-0">
                                            <th>Documento</th>
                                            <th>Nombres</th>
                                            <th>ciudad</th>
                                            <th>Parentesco</th>
                                            <th>Estado</th>
                                            <th style="text-align: center;">OPCIONES</th>
                                        </tr>
                                        
                                    </thead>
                                    <tbody class="fs-6 fw-bold text-gray-600 text-uppercase">
                                        <?php 
                                            foreach ($all_beneficiarios as $datos) {
                                                $xIdbene = $datos['IdBene'];
                                                $xDocumento = $datos['Doumento'];
                                                $xNombres = $datos['Nombres'];
                                                $xCiudad = $datos['Ciudad'];
                                                $xIdCiudad = $datos['IdCiudad'];
                                                $xProv = $datos['Provincia'];
                                                $xParentesco = $datos['Parentesco'];
                                                $xEstado = $datos['Estado'];

                                                if($xEstado == 'A'){
                                                    $xEstado = 'ACTIVO';
                                                    $xTextColor = "badge badge-light-primary";
                                                }else{
                                                    $xEstado = 'INACTIVO';
                                                    $xTextColor = "badge badge-light-danger";
                                                }
                                        ?>
                                        <tr>
                                            <td><?php echo $xDocumento; ?></td>
                                            <td><?php echo $xNombres; ?></td>
                                            <td><?php echo $xCiudad; ?></td>
                                            <td><?php echo $xParentesco; ?></td>
                                            <td>
                                                <div class="<?php echo $xTextColor; ?>">
                                                    <?php echo $xEstado; ?>
                                                </div>  
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <div class="btn-group">
                                                        <button id="btnAgendar" onclick="f_Agendar(
                                                            <?php echo $xIdbene; ?>,
                                                            <?php echo $xIdCiudad; ?>,
                                                            <?php echo $xClieid; ?>,
                                                            <?php echo $xTituid; ?>,
                                                            <?php echo $xProdid; ?>,
                                                            <?php echo $xGrupid; ?>)" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar"  title='Agendar' data-bs-toggle="tooltip" data-bs-placement="left">
                                                            <i class="fa fa-calendar-plus"></i>
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
    </div>

    <!--Modal Prestador-->
    <div class="modal fade" id="modal-prestador" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-800px">
            <div class="modal-content"> 
                <div class="modal-header">
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
                <div class="modal-body py-lg-10 px-lg-10 mt-n3">
                    <div class="card mb-1 mb-xl-1">
                        <div class="card-header border-0">
                            <div class="card-title">
                                <div class="fw-bolder collapsible collapsed rotate" data-bs-toggle="collapse" href="#view_imagen_prestador" role="button" aria-expanded="false" aria-controls="view_imagen_titular">Avatar
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
                        <div id="view_imagen_prestador" class="collapse">
                            <div class="card card-flush py-4">
                                <div class="card-body pt-0">
                                    <div class="mt-1">
                                        <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('assets/media/svg/files/blank-image.svg')">
                                            <div class="image-input-wrapper w-125px h-125px" id="imgfileprestador" style="background-image: url(assets/media/svg/files/blank-image.svg)"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-5 mb-xl-8">
                        <div class="card-header border-0">
                            <div class="card-title">
                                <div class="fw-bolder collapsible collapsed rotate" data-bs-toggle="collapse" href="#view_datos_prestador" role="button" aria-expanded="false" aria-controls="view_datos_titular">Datos Prestador
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
                        <div id="view_datos_prestador" class="collapse show">
                            <div class="card card-flush py-2">
                                <div class="card-body pt-0">
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Tipo</label>
                                            <input type="text" class="form-control" id="txtTipoprestador" name="txtTipoprestador" readonly />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Sector</label>
                                            <input type="text" class="form-control" id="txtSector" name="txtSector" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-header border-0">
                                <div class="card-title">
                                    <h2 class="fw-bolder mb-0">Direccion/Telefono/Mails</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
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
                                                <div class="col-md-2">
                                                    <div class="fs-6 fw-bold mt-2 mb-3">Direccion:</div>
                                                </div>
                                                <div class="col-md-10">
                                                    <textarea class="form-control mb-2 text-uppercase" name="txtDireccion" id="txtDireccion" maxlength="250" onkeydown="return (event.keyCode!=13); " readonly ></textarea>
                                                </div>
                                            </div>
                                            <div class="row mb-8">
                                                <div class="col-md-2">
                                                    <div class="fs-6 fw-bold mt-2 mb-3">URL:</div>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control mb-2 text-lowercase" name="txtUrl" id="txtUrl" maxlength="150" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <div class="fs-6 fw-bold mt-2 mb-3">Telefono 1:</div>
                                            <input type="text" class="form-control mb-2" name="txtFono1" id="txtFono1" value="" readonly />
                                        </div>
                                        <div class="col-md-4">
                                            <div class="fs-6 fw-bold mt-2 mb-3">Telefono 2:</div>
                                            <input type="text" class="form-control mb-2" name="txtFono2" id="txtFono2" value="" readonly />
                                        </div> 
                                        <div class="col-md-4">
                                            <div class="fs-6 fw-bold mt-2 mb-3">Telefono 3:</div>
                                            <input type="text" class="form-control mb-2" name="txtFono3" id="txtFono3" value="" readonly />
                                        </div>                                                        
                                    </div>
                                    <div class="row row-cols-1 row-cols-sm-3 rol-cols-md-3 row-cols-lg-3">
                                        <div class="col-md-4">
                                            <div class="fs-6 fw-bold mt-2 mb-3">Celular 1:</div>
                                            <input type="text" class="form-control mb-2" name="txtCelular1" id="txtCelular1" value="" readonly />
                                        </div>
                                        <div class="col-md-4">
                                            <div class="fs-6 fw-bold mt-2 mb-3">Celular 2:</div>
                                            <input type="text" class="form-control mb-2" name="txtCelular2" id="txtCelular2" value="" readonly />
                                        </div> 
                                        <div class="col-md-4">
                                            <div class="fs-6 fw-bold mt-2 mb-3">Celular 3:</div>
                                            <input type="text" class="form-control mb-2" name="txtCelular3" id="txtCelular3" value="" readonly />
                                        </div>
                                    </div>                                                
                                </div>
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
                                <div class="d-flex flex-wrap py-5">
                                        <div class="flex-equal me-5">
                                            <div class="row mb-8">
                                                <div class="col-md-2">
                                                    <div class="fs-6 fw-bold mt-2 mb-3">Email 1:</div>
                                                </div>
                                                <div class="col-md-7">
                                                <input type="email" name="txtEmail1" id="txtEmail1" maxlength="150" placeholder="micorre@dominio.com" class="form-control mb-2 text-lowercase" value="" readonly />
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-check form-switch form-check-custom form-check-solid">
                                                        <input class="form-check-input" name="chkEnviar1" id="chkEnviar1" type="checkbox" disabled />
                                                        <span id="spanEnv1" class="form-check-label fw-bold text-muted" for="chkEnviar1">No Enviar</span>
                                                    </label>   
                                                </div>
                                            </div>
                                            <div class="row mb-8">
                                                <div class="col-md-2">
                                                    <div class="fs-6 fw-bold mt-2 mb-3">Email 2:</div>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="email" name="txtEmail2" id="txtEmail2" maxlength="150" placeholder="micorre@dominio.com" class="form-control mb-2 text-lowercase" value="" readonly />
                                                </div>
                                                <div class="col-md-3">
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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!--Modal Profesional-->
    <div class="modal fade" id="modal-profesional" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-800px">
            <div class="modal-content"> 
                <div class="modal-header">
                    <h2 class="fw-bolder">Informacion Profesional</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body py-lg-10 px-lg-10 mt-n3">
                    <div class="card mb-1 mb-xl-1">
                        <div class="card-header border-0">
                            <div class="card-title">
                                <div class="fw-bolder collapsible collapsed rotate" data-bs-toggle="collapse" href="#view_imagen_profesional" role="button" aria-expanded="false" aria-controls="view_imagen_titular">Avatar
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
                        <div id="view_imagen_profesional" class="collapse">
                            <div class="card card-flush py-4">
                                <div class="card-body pt-0">
                                    <div class="mt-1">
                                        <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('assets/media/svg/files/blank-image.svg')">
                                            <div class="image-input-wrapper w-125px h-125px" id="imgfileprofesional" style="background-image: url(assets/media/svg/files/blank-image.svg)"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-5 mb-xl-8">
                        <div class="card-header border-0">
                            <div class="card-title">
                                <div class="fw-bolder collapsible collapsed rotate" data-bs-toggle="collapse" href="#view_datos_prestador" role="button" aria-expanded="false" aria-controls="view_datos_titular">Datos Profesional
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
                        <div id="view_datos_prestador" class="collapse show">
                            <div class="card card-flush py-2">
                                <div class="card-body pt-0">
                                    <div class="row mb-2">
                                        <div class="col-md-12">
                                            <label class="form-label">Nombres</label>
                                            <input type="text" class="form-control" id="txtProfesional" name="txtProfesional" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-header border-0">
                                <div class="card-title">
                                    <h2 class="fw-bolder mb-0">Direccion/Telefono/Mails</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
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
                                                <div class="col-md-2">
                                                    <div class="fs-6 fw-bold mt-2 mb-3">Direccion:</div>
                                                </div>
                                                <div class="col-md-10">
                                                    <textarea class="form-control mb-2 text-uppercase" name="txtDireccionPro" id="txtDireccionPro" maxlength="250" readonly ></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <div class="fs-6 fw-bold mt-2 mb-3">Telefono:</div>
                                            <input type="text" class="form-control mb-2" name="txtFonoPro" id="txtFonoPro" value="" readonly />
                                        </div>
                                        <div class="col-md-6">
                                            <div class="fs-6 fw-bold mt-2 mb-3">Celular:</div>
                                            <input type="text" class="form-control mb-2" name="txtCelPro" id="txtCelPro" value="" readonly />
                                        </div>                                                        
                                    </div>                                               
                                </div>
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
                                <div class="d-flex flex-wrap py-5">
                                        <div class="flex-equal me-5">
                                            <div class="row mb-8">
                                                <div class="col-md-2">
                                                    <div class="fs-6 fw-bold mt-2 mb-3">Email 1:</div>
                                                </div>
                                                <div class="col-md-10">
                                                <input type="email" name="txtEmailPro" id="txtEmailPro" maxlength="150" placeholder="micorre@dominio.com" class="form-control mb-2 text-lowercase" value="" readonly />
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-md-2">
                                                    <div class="fs-6 fw-bold mt-2 mb-3">Email 2:</div>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="email" name="txtEmail2" id="txtEmail2" maxlength="150" placeholder="micorre@dominio.com" class="form-control mb-2 text-lowercase" value="" readonly />
                                                </div>
                                            </div>   
                                        </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Modal Prestador ver Ultimo Agendamiento-->
    <div class="modal fade" id="modal_prestador_ult" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-800px">
            <div class="modal-content"> 
                <div class="modal-header">
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
                <div class="modal-body py-lg-10 px-lg-10 mt-n3">
                    <div class="card mb-1 mb-xl-1">
                        <div class="card-header border-0">
                            <div class="card-title">
                                <div class="fw-bolder collapsible collapsed rotate" data-bs-toggle="collapse" href="#view_imagen_prestador" role="button" aria-expanded="false" aria-controls="view_imagen_titular">Avatar
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
                        <div id="view_imagen_prestador" class="collapse">
                            <div class="card card-flush py-4">
                                <div class="card-body pt-0">
                                    <div class="mt-1">
                                        <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('assets/media/svg/files/blank-image.svg')">
                                            <div class="image-input-wrapper w-125px h-125px" id="imgfileprestador" style="background-image: url(assets/media/svg/files/blank-image.svg)"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-5 mb-xl-8">
                        <div class="card-header border-0">
                            <div class="card-title">
                                <div class="fw-bolder collapsible collapsed rotate" data-bs-toggle="collapse" href="#view_datos_prestador" role="button" aria-expanded="false" aria-controls="view_datos_titular">Datos Prestador
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
                        <div id="view_datos_prestador" class="collapse show">
                            <div class="card card-flush py-2">
                                <div class="card-body pt-0">
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Tipo</label>
                                            <input type="text" class="form-control" id="txtTipoprestadorUlt" readonly />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Sector</label>
                                            <input type="text" class="form-control" id="txtSectorUlt" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-header border-0">
                                <div class="card-title">
                                    <h2 class="fw-bolder mb-0">Direccion/Telefono/Mails</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
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
                                                <div class="col-md-2">
                                                    <div class="fs-6 fw-bold mt-2 mb-3">Direccion:</div>
                                                </div>
                                                <div class="col-md-10">
                                                    <textarea class="form-control mb-2 text-uppercase" id="txtDireccionUlt" maxlength="250" onkeydown="return (event.keyCode!=13); " readonly ></textarea>
                                                </div>
                                            </div>
                                            <div class="row mb-8">
                                                <div class="col-md-2">
                                                    <div class="fs-6 fw-bold mt-2 mb-3">URL:</div>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control mb-2 text-lowercase" id="txtUrlUlt" maxlength="150" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <div class="fs-6 fw-bold mt-2 mb-3">Telefono 1:</div>
                                            <input type="text" class="form-control mb-2" id="txtFono1Ult" readonly />
                                        </div>
                                        <div class="col-md-4">
                                            <div class="fs-6 fw-bold mt-2 mb-3">Telefono 2:</div>
                                            <input type="text" class="form-control mb-2"  id="txtFono2Ult" readonly />
                                        </div> 
                                        <div class="col-md-4">
                                            <div class="fs-6 fw-bold mt-2 mb-3">Telefono 3:</div>
                                            <input type="text" class="form-control mb-2" id="txtFono3Ult" readonly />
                                        </div>                                                        
                                    </div>
                                    <div class="row row-cols-1 row-cols-sm-3 rol-cols-md-3 row-cols-lg-3">
                                        <div class="col-md-4">
                                            <div class="fs-6 fw-bold mt-2 mb-3">Celular 1:</div>
                                            <input type="text" class="form-control mb-2" id="txtCelular1Ult" readonly />
                                        </div>
                                        <div class="col-md-4">
                                            <div class="fs-6 fw-bold mt-2 mb-3">Celular 2:</div>
                                            <input type="text" class="form-control mb-2" id="txtCelular2Ult" readonly />
                                        </div> 
                                        <div class="col-md-4">
                                            <div class="fs-6 fw-bold mt-2 mb-3">Celular 3:</div>
                                            <input type="text" class="form-control mb-2" id="txtCelular3Ult"  readonly />
                                        </div>
                                    </div>                                                
                                </div>
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
                                <div class="d-flex flex-wrap py-5">
                                        <div class="flex-equal me-5">
                                            <div class="row mb-8">
                                                <div class="col-md-2">
                                                    <div class="fs-6 fw-bold mt-2 mb-3">Email 1:</div>
                                                </div>
                                                <div class="col-md-7">
                                                <input type="email" id="txtEmail1Ult" maxlength="150" placeholder="micorre@dominio.com" class="form-control mb-2 text-lowercase" readonly />
                                                </div>
                                            </div>
                                            <div class="row mb-8">
                                                <div class="col-md-2">
                                                    <div class="fs-6 fw-bold mt-2 mb-3">Email 2:</div>
                                                </div>
                                                <div class="col-md-7">
                                                    <input type="email" name="txtEmail2" id="txtEmail2Ult" maxlength="150" placeholder="micorre@dominio.com" class="form-control mb-2 text-lowercase" readonly />
                                                </div>
                                            </div>   
                                        </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Modal Profesional ver Ultimo Agendamiento-->
    <div class="modal fade" id="modal_profesional_ult" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-800px">
            <div class="modal-content"> 
                <div class="modal-header">
                    <h2 class="fw-bolder">Informacion Profesional</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body py-lg-10 px-lg-10 mt-n3">
                    <div class="card mb-1 mb-xl-1">
                        <div class="card-header border-0">
                            <div class="card-title">
                                <div class="fw-bolder collapsible collapsed rotate" data-bs-toggle="collapse" href="#view_imagen_profesional" role="button" aria-expanded="false" aria-controls="view_imagen_titular">Avatar
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
                        <div id="view_imagen_profesional" class="collapse">
                            <div class="card card-flush py-4">
                                <div class="card-body pt-0">
                                    <div class="mt-1">
                                        <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('assets/media/svg/files/blank-image.svg')">
                                            <div class="image-input-wrapper w-125px h-125px" id="imgfileprofesional" style="background-image: url(assets/media/svg/files/blank-image.svg)"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-5 mb-xl-8">
                        <div class="card-header border-0">
                            <div class="card-title">
                                <div class="fw-bolder collapsible collapsed rotate" data-bs-toggle="collapse" href="#view_datos_prestador" role="button" aria-expanded="false" aria-controls="view_datos_titular">Datos Profesional
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
                        <div id="view_datos_prestador" class="collapse show">
                            <div class="card card-flush py-2">
                                <div class="card-body pt-0">
                                    <div class="row mb-2">
                                        <div class="col-md-12">
                                            <label class="form-label">Nombres</label>
                                            <input type="text" class="form-control" id="txtProfesionalUlt" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-header border-0">
                                <div class="card-title">
                                    <h2 class="fw-bolder mb-0">Direccion/Telefono/Mails</h2>
                                </div>
                            </div>
                            <div class="card-body pt-0">
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
                                                <div class="col-md-2">
                                                    <div class="fs-6 fw-bold mt-2 mb-3">Direccion:</div>
                                                </div>
                                                <div class="col-md-10">
                                                    <textarea class="form-control mb-2 text-uppercase" id="txtDireccionProUlt" maxlength="250" readonly ></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <div class="fs-6 fw-bold mt-2 mb-3">Telefono:</div>
                                            <input type="text" class="form-control mb-2" id="txtFonoProUlt" readonly />
                                        </div>
                                        <div class="col-md-6">
                                            <div class="fs-6 fw-bold mt-2 mb-3">Celular:</div>
                                            <input type="text" class="form-control mb-2" id="txtCelProUlt" readonly />
                                        </div>                                                        
                                    </div>                                               
                                </div>
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
                                <div class="d-flex flex-wrap py-5">
                                        <div class="flex-equal me-5">
                                            <div class="row mb-8">
                                                <div class="col-md-2">
                                                    <div class="fs-6 fw-bold mt-2 mb-3">Email 1:</div>
                                                </div>
                                                <div class="col-md-10">
                                                <input type="email" id="txtEmailProUlt" maxlength="150" placeholder="micorre@dominio.com" class="form-control mb-2 text-lowercase" readonly />
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-md-2">
                                                    <div class="fs-6 fw-bold mt-2 mb-3">Email 2:</div>
                                                </div>
                                                <div class="col-md-10">
                                                    <input type="email" id="txtEmail2Ult" maxlength="150" placeholder="micorre@dominio.com" class="form-control mb-2 text-lowercase" readonly />
                                                </div>
                                            </div>   
                                        </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!--Modal Confirmacion Agendamiento-->
    <div class="modal fade" id="modal_cita" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-800px">
            <div class="modal-content">
                <div class="modal-header">
                    <img src="" id="imgLocation" class="card-img-top" alt="50">
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal"></div>
                </div>
                <div class="modal-body py-lg-10 px-lg-10">
                    <div class="card card-flush py-4">
                        <div class="card-body pt-0">
                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="form-label text-primary">Fecha Cita:</label>
                                    <span class="" id="txtFechaCita"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label text-primary">Nombres:</label>
                                    <span class="" id="txtNombreCita"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-primary">Codigo:</label>
                                    <span class="" id="txtCodCita"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label text-primary">Especialidad:</label>
                                    <span class="" id="txtEspeCita"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-primary">Profesional:</label>
                                    <span class="" id="txtProfCita"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label text-primary">Ciudad:</label>
                                    <span class="text-uppercase" id="txtCiudCita"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-primary">Prestador:</label>
                                    <span class="" id="txtPresCita"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label text-primary">Hora Desde:</label>
                                    <span class="" id="txtDesdeCita"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-primary">Hora Hasta:</label>
                                    <span class="" id="txtHastaCita"></span>
                                </div>
                            </div>         
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-sm btn-light-primary" data-bs-dismiss="modal">OK</button>   
                </div>
            </div>
        </div>
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
        var _tituid = "<?php echo $xTituid; ?>";
        var _prodid = "<?php echo $xProdid; ?>";
        var _grupid = "<?php echo $xGrupid; ?>";
        var _agenid = "<?php echo $xAgendaid; ?>";
        var _cboprestaid = 0;
        var _cbopreeid = 0;
        var _cboprofid = 0;

        // var popover;
        // var popoverState = false; 

        // var data = {
        //     id: '',
        //     eventName: '',
        //     eventDescription: '',
        //     startDate: '',
        //     endDate: '',
        //     allDay: false
        // };  

        //OBTENER DATOS DE AGENDAMIENTO 

        var _parametros = {
            "xxPaisid": _paisid,
            "xxEmprid": _emprid,
            "xxAgendaid": _agenid   
        }

        if(_agenid > 0){
            var _respuesta = $.post("codephp/get_datosagendamiento.php", _parametros);
            _respuesta.done(function(response) {
               
                var _datos = JSON.parse(response);
            
                _documento = _datos['Documento'];
                _nombres = _datos['Nombres'];
                _producto = _datos['Producto'];
                _logo = _datos['Logo'];
                _prestadora = _datos['Prestadora'];
                _ciudad = _datos['Ciudad'];
                _especialidad = _datos['Especialidad'];
                _profesional = _datos['Profesional'];
                _fechainicio = _datos['FechaInicio'];
                _fechafin = _datos['FechaFin'];
                _codigo = _datos['CodigoAgenda'];

                var _fechainicial = moment(_fechainicio).format('YYYY-MM-DD');
                var _horade = moment(_fechainicio).format('HH:mm');
                var _horaHa = moment(_fechafin).format('HH:mm');

                $("#imgLocation").attr("src", _logo).width('20%').height('10%');

                $("#txtFechaCita").text(_fechainicial);
                $("#txtNombreCita").text(_nombres);
                $('#txtCodCita').text(_codigo);
                $('#txtEspeCita').text(_especialidad);
                $('#txtProfCita').text(_profesional);
                $('#txtCiudCita').text(_ciudad);
                $('#txtPresCita').text(_prestadora);
                $('#txtDesdeCita').text(_horade);
                $('#txtHastaCita').text(_horaHa);
               
                toastSweetAlert("top-end",3500,"success","Cita Agendada");
                $("#modal_cita").modal("show");


                // Swal.fire({
                //     title: "Datos Agendamiento",
                //     imageUrl: _logo,
                //     imageWidth: 200,
                //     imageHeight: 100,
                //     html:`    
                //         <div class="card-body">
                //             <h3 class="card-title">Datos Agendamiento</h3>
                //             <div class="row">
                //                 <div class="col-md-5">
                //                     <label class="form-label">Nombres:</label>
                //                 </div>
                //                 <div class="col-md-7">
                //                     <label class="form-label">${_nombres}</label>
                //                 </div>
                //             </div>
                //             <div class="row">
                //                 <div class="col-md-5">
                //                     <label class="form-label">Prestador:</label>
                //                 </div>
                //                 <div class="col-md-7">
                //                     <label class="form-label">${_prestadora}</label>
                //                 </div>
                //             </div>
                //             <div class="row">
                //                 <div class="col-md-5">
                //                     <label class="form-label">Ciudad:</label>
                //                 </div>
                //                 <div class="col-md-7">
                //                     <label class="form-label">${_ciudad}</label>
                //                 </div>
                //             </div>
                //             <div class="row">
                //                 <div class="col-md-5">
                //                     <label class="form-label">Especialidad:</label>
                //                 </div>
                //                 <div class="col-md-7">
                //                     <label class="form-label">${_especialidad}</label>
                //                 </div>
                //             </div>
                //             <div class="row">
                //                 <div class="col-md-5">
                //                     <label class="form-label">Profesional:</label>
                //                 </div>
                //                 <div class="col-md-7">
                //                     <label class="form-label">${_profesional}</label>
                //                 </div>
                //             </div>
                //         </div>     
                    
                //     `,
                //     icon: "success"
                // }); 
                
            });

            _respuesta.fail(function() {
            });

            _respuesta.always(function() {
            });   
                
        }

        document.getElementById('imgfiletitular').style.backgroundImage="url(persona/" + _avatar + ")";

        $('#cboProvincia').val(_provincia).change();
        $('#cboCiudad').val(_ciudadid).change();

        $('#cboCiudad').change(function(){
            
            _cboid = $(this).val(); //obtener el id seleccionado

            $("#cboProvincia").empty();
            $("#cboPrestador").empty();
            $("#cboEspecialidad").empty();
            $("#cboProfesional").empty();

            var _parametros = {
                "xxPaisid": _paisid,
                "xxEmprid": _emprid,
                "xxComboid": _cboid,
                "xxOpcion": 3
            }

            var _respuesta = $.post("codephp/cargar_combos.php", _parametros);
            _respuesta.done(function(response) {
                $("#cboProvincia").html(response);
                
            });
            _respuesta.fail(function() {
            });
            _respuesta.always(function() {
            });                

        }); 

        $('#cboSector').change(function(){
            _cboid = $(this).val();
            _cbociudad = $('#cboCiudad').val();

            var _parametros = {
                "xxPaisid": _paisid,
                "xxEmprid": _emprid,
                "xxCiudadid": _cbociudad,
                "xxSector": _cboid
            }  
            
            var _respuesta = $.post("codephp/get_comboprestador.php", _parametros);
            _respuesta.done(function(response) {
                $("#cboPrestador").html(response);
                
            });
            _respuesta.fail(function() {
            });
            _respuesta.always(function() {
            });                 

        }); 

        $('#btnDatosPrestador').click(function(){

            _presid = $('#cboPrestador').val();

            if(_presid == ''){
                toastSweetAlert("top-end",3000,"warning","Seleccione Prestador..!!");
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

        $('#btnDatosProfesional').click(function(){

            _profid = $('#cboProfesional').val();
            if(_profid == ''){
                mensajesalertify("Seleccione Profesional", "W", "top-center", 5);
                return;
            }
            var _parametros = {
                xxPaisid: _paisid,
                xxEmprid: _emprid,
                xxProfid: _profid
            }                

            var xrespuesta = $.post("codephp/get_datosprofesionalagenda.php", _parametros);
            xrespuesta.done(function(response){
                var json = JSON.parse(response);                    
                console.log(response);
                if(json[0]['Avatar'] == ''){
                    document.getElementById('imgfileprofesional').style.backgroundImage="url(assets/media/svg/files/blank-image.svg)";    
                }else{
                    document.getElementById('imgfileprofesional').style.backgroundImage="url(logos/" + json[0]['Avatar'] + ")";
                }

                $("#modal-profesional").find("input,textarea").val("");
                $("#modal-profesional").modal("show");
                $('#modal-profesional').modal('handleUpdate');

                $('#txtProfesional').val(json[0]['Nombres']);
                $('#txtDireccionPro').val(json[0]['Direccion']);     
                $('#txtFonoPro').val(json[0]['Telefono']);
                $('#txtCelPro').val(json[0]['Celular']);
                $('#txtEmailPro').val(json[0]['Email']);
                              

            });
        });

         //Modal Ver Prestador Ultimo Agendamiento
        $(document).on("click",".btnPres",function(){

            var _paisid = '<?php echo $xPaisid ?>';
            var _emprid = '<?php echo $xEmprid?>';
            var _presidult = '<?php echo $xPresId ?>';

            var _parametros = {
                xxPaisid: _paisid,
                xxEmprid: _emprid,
                xxPresid: _presidult
            }   

            var xrespuesta = $.post("codephp/get_datosprestadora.php", _parametros);
                xrespuesta.done(function(response){
                    var json = JSON.parse(response);

                    $('#txtTipoprestadorUlt').val(json.TipoPrestador);
                    $('#txtSectorUlt').val(json.Sector);
                    $('#txtDireccionUlt').val(json.Direccion);
                    $('#txtUrlUlt').val(json.Url);
                    $('#txtFono1Ult').val(json.Fono1);
                    $('#txtFono2Ult').val(json.Fono2);
                    $('#txtFono3Ult').val(json.Fono3);
                    $('#txtCelular1Ult').val(json.Celu1);
                    $('#txtCelular2Ult').val(json.Celu2);
                    $('#txtCelular3Ult').val(json.Celu3);
                    $('#txtEmail1Ult').val(json.Email1);
                    $('#txtEmail2Ult').val(json.Email2);

                });

            $("#modal_prestador_ult").modal("show");
        });

          //Modal Ver Profesional Ultimo Agendamiento
        $(document).on("click",".btnPro",function(){

            var _paisid = '<?php echo $xPaisid ?>';
            var _emprid = '<?php echo $xEmprid?>';
            var _profidult = '<?php echo $xIdProfesional ?>';

            var _parametros = {
                xxPaisid: _paisid,
                xxEmprid: _emprid,
                xxProfid: _profidult
            }

            var xrespuesta = $.post("codephp/get_datosprofesionalagenda.php", _parametros);
            xrespuesta.done(function(response){
                var json = JSON.parse(response);                    
                //console.log(response);
                // if(json[0]['Avatar'] == ''){
                //     document.getElementById('imgfileprofesionalUlt').style.backgroundImage="url(assets/media/svg/files/blank-image.svg)";    
                // }else{
                //     document.getElementById('imgfileprofesionalUlt').style.backgroundImage="url(logos/" + json[0]['Avatar'] + ")";
                // }

                $('#txtProfesionalUlt').val(json[0]['Nombres']);
                $('#txtFonoProUlt').val(json[0]['Telefono']);
                $('#txtCelProUlt').val(json[0]['Celular']);
                $('#txtEmailProUlt').val(json[0]['Email']);
                $('#txtDireccionProUlt').val(json[0]['Direccion']);  
            }); 


            $("#modal_profesional_ult").modal("show");
        });

        //REDIREC PARA AGENDAR TITULAR
        $('#btnCalendar').click(function(){

            _presid = $('#cboPrestador').val();
            _preeid = $('#cboEspecialidad').val();
            _pfesid = $("#cboProfesional").val();
            _ciudid = $('#cboCiudad').val();
            _sectid = $('#cboSector').val();
            
            if(_sectid == 0){
                toastSweetAlert("top-end",3000,"warning","Seleccione Sector..!!");
                return;
            }

            if(_presid == 0){
                toastSweetAlert("top-end",3000,"warning","Seleccione Prestador..!!");
                return;
            }

            if(_preeid == 0){
                toastSweetAlert("top-end",3000,"warning","Seleccione Especialidad..!!");
                return;
            }

            if(_pfesid == 0){
                toastSweetAlert("top-end",3000,"warning","Seleccione Profesional..!!");
                return;
            }

            $.redirect('?page=agendartitular&menuid=<?php echo $menuid; ?>', {
                'tituid': _tituid,
                'beneid':0,
                'presid': _presid,
                'preeid': _preeid,
                'pfesid': _pfesid,
                'prodid': _prodid,
                'grupid': _grupid,
                'ciudid': _ciudid

            });
            

        });

       //REGRESAR PAGINA ANTERIOR
        $('#btnRegresar').click(function(){
            $.redirect('?page=agendatitular_admin&menuid=<?php echo $menuid; ?>', {
               
            });
        });
  
    });    
    
    
    //Desplazar-modal
    $("#modal-prestador").draggable({
        handle: ".modal-header"
    });     
    
    $("#modal-profesional").draggable({
        handle: ".modal-header"
    });  

    $("#modal_prestador_ult").draggable({
        handle: ".modal-header"
    }); 
    
    $("#modal_profesional_ult").draggable({
        handle: ".modal-header"
    });  

    $("#modal_cita").draggable({
        handle: ".modal-header"
    });  


</script>