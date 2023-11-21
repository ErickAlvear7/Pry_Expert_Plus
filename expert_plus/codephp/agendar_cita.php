<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception; 

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');        

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();

    $xAgendado = 0;
    $xCodigoAgenda = -1;

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxTituid']) and isset($_POST['xxProdid']) and isset($_POST['xxGrupid']) and isset($_POST['xxPresid']) and isset($_POST['xxEspeid']) and isset($_POST['xxPfesid']) and isset($_POST['xxFechaIni']) and isset($_POST['xxFechaFin']) and isset($_POST['xxCodigoDia'])  ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxTituid']) <> '' and isset($_POST['xxProdid']) <> '' and isset($_POST['xxGrupid']) <> '' and isset($_POST['xxPresid']) <> '' and isset($_POST['xxEspeid']) <> '' and isset($_POST['xxPfesid']) <> '' and isset($_POST['xxFechaIni']) <> '' and isset($_POST['xxFechaFin']) <> '' and isset($_POST['xxCodigoDia']) <> '' ){ 

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xTipoCliente = $_POST['xxTipoCliente'];
            $xTituid = $_POST['xxTituid'];
            $xBeneid = $_POST['xxBeneid'];
            $xCiudid = $_POST['xxCiudid'];
            $xProdid = $_POST['xxProdid'];
            $xGrupid = $_POST['xxGrupid'];
            $xPresid = $_POST['xxPresid'];
            $xEspeid = $_POST['xxEspeid'];
            $xPfesid = $_POST['xxPfesid'];
            $xFechaIni = $_POST['xxFechaIni'];
            $xFechaFin = $_POST['xxFechaFin'];
            $xCodigoDia = $_POST['xxCodigoDia'];
            $xDia = $_POST['xxDia'];            
            $xHoraDesde = $_POST['xxHoraDesde'];            
            $xHoraHasta = $_POST['xxHoraHasta'];
            $xTipoRegistro = $_POST['xxTipoRegistro'];
            $xMotivoRegistro = $_POST['xxMotivoRegistro'];
            $xObservacion = $_POST['xxObservacion'];
            $xEstadoAgenda = $_POST['xxEstadoAgenda'];
            $xColor = $_POST['xxColor'];
            $xTextColor = $_POST['xxTextColor'];
            $xUsuaid = $_POST['xxUsuaid'];

            $xCodigoAgenda = 1001;
            $xLogoCab = "Agendamiento.png";
            $xLogoFirma = "PrestaSlogin.png";
            $xCliente = "";
            $xProducto = "";
            $XMedicamentos = "NO";
            $xCiudadAgenda = "";
            $xHoraCita = "$xHoraDesde - $xHoraHasta ";
            $xPrestadora = "";
            $xEspecialidad = "";
            $xProfesional = "";
            $CedulaTitu = "";
            $xPaciente = "";
            $xFechaNacimiento = "";
            $xDireccion = "";
            $xTelefono = "";
            $xMotivo = "";
            $xAgendaid = 0;

            $xSQL = "SELECT * FROM `expert_configuracion` WHERE pais_id=$xPaisid ";
            $all_datos = mysqli_query($con, $xSQL);
            foreach ($all_datos as $datos) {
                $xLogoCab = $datos['logo_cabecera'];
                $xLogoFirma = $datos['logo_firma'];
            }

            $xSQL = "SELECT (SELECT cli.clie_nombre FROM `expert_cliente` cli WHERE cli.clie_id=prd.clie_id AND cli.pais_id=$xPaisid AND cli.empr_id=$xEmprid) AS Cliente,";
            $xSQL .= "prod_nombre FROM `expert_productos` prd WHERE prd.pais_id=$xPaisid AND prd.empr_id=$xEmprid AND prd.prod_id=$xProdid ";
            $all_datos = mysqli_query($con, $xSQL);
            foreach ($all_datos as $datos) {
                $xCliente = $datos['Cliente'];
                $xProducto = $datos['prod_nombre'];
            }

            $xSQL = "SELECT CONCAT(provincia,'/',ciudad) AS Ciudad FROM `provincia_ciudad`  WHERE pais_id=$xPaisid AND prov_id=$xCiudid ";
            $all_datos = mysqli_query($con, $xSQL);
            foreach ($all_datos as $datos) {
                $xCiudadAgenda = $datos['Ciudad'];
            }

            $xSQL = "SELECT pres_nombre FROM `expert_prestadora` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pres_id=$xPresid ";
            $all_datos = mysqli_query($con, $xSQL);
            foreach ($all_datos as $datos) {
                $xPrestadora = $datos['pres_nombre'];
            }

            $xSQL = "SELECT espe_nombre FROM `expert_especialidad` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND espe_id=$xEspeid ";
            $all_datos = mysqli_query($con, $xSQL);
            foreach ($all_datos as $datos) {
                $xEspecialidad = $datos['espe_nombre'];
            }

            $xSQL = "SELECT (SELECT CONCAT(pro.prof_nombres,' ',pro.prof_apellidos) FROM `expert_profesional` pro WHERE pro.pais_id=$xPaisid AND pro.empr_id=$xEmprid AND pro.prof_id=pes.prof_id) AS Profesional ";
            $xSQL .= "FROM `expert_profesional_especi` pes WHERE pes.pais_id=$xPaisid AND pes.empr_id=$xEmprid AND pes.pfes_id=$xPfesid ";
            $all_datos = mysqli_query($con, $xSQL);
            foreach ($all_datos as $datos) {
                $xProfesional = $datos['Profesional'];
            }

            $xSQL = "SELECT motivos_especialidad FROM `expert_motivos_especialidad` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND espe_id=$xEspeid AND mtes_id=$xMotivoRegistro ";
            $all_datos = mysqli_query($con, $xSQL);
            foreach ($all_datos as $datos) {
                $xMotivo = $datos['motivos_especialidad'];
            }            

            $xSQL = "SELECT per.pers_numerodocumento,CONCAT(per.pers_nombres,' ',per.pers_apellidos) AS Paciente,per.pers_fechanacimiento,per.pers_direccion,";
            $xSQL .= "CONCAT(per.pers_telefonocasa,'/',per.pers_celular) AS Telefonos FROM `expert_titular` tit, `expert_persona` per WHERE tit.pais_id=$xPaisid AND tit.empr_id=$xEmprid AND tit.titu_id=$xTituid AND tit.pers_id=per.pers_id ";
            $all_datos = mysqli_query($con, $xSQL);
            foreach ($all_datos as $datos) {
                $CedulaTitu = $datos['pers_numerodocumento'];
                $xPaciente = $datos['Paciente'];
                $xFechaNacimiento = $datos['pers_fechanacimiento'];
                $xDireccion = $datos['pers_direccion'];
                $xTelefono = $datos['Telefonos'];
            }

            if($xBeneid != '0'){
                $xSQL = "SELECT bene_numerodocumento,CONCAT(bene_nombres,' ',bene_apellidos) AS Paciente,bene_fechanacimiento `expert_beneficiario` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND titu_id=$xTituid AND bene_id=$xBeneid  ";
                $all_datos = mysqli_query($con, $xSQL);
                foreach ($all_datos as $datos) {
                    $xPaciente = $datos['Paciente'];
                    $xFechaNacimiento = $datos['pers_fechanacimiento'];
                }    
            }

            $xSQL = "SELECT usua_login FROM `expert_usuarios` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND usua_id=$xUsuaid ";
            $all_datos = mysqli_query($con, $xSQL);
            foreach ($all_datos as $datos) {
                $xUserAgent = $datos['usua_login'];
            } 

            if($xTipoRegistro == 'Agendar'){
                $xSQL = "INSERT INTO `expert_agenda`(pais_id,empr_id,tipo_cliente,titu_id,bene_id,prod_id,grup_id,pres_id,espe_id,pfes_id,fecha_inicio,fecha_fin,codigo_dia,dia,hora_desde,hora_hasta,tipo_registro,motivo_registro,observacion,estado_agenda,codigo_agenda,color,textcolor,fechacreacion,usuariocreacion,terminalcreacion) ";
                $xSQL .= "VALUES($xPaisid,$xEmprid,'$xTipoCliente',$xTituid,$xBeneid,$xProdid,$xGrupid,$xPresid,$xEspeid,$xPfesid,'{$xFechaIni}','{$xFechaFin}',$xCodigoDia,'$xDia','{$xHoraDesde}','{$xHoraHasta}','$xTipoRegistro',$xMotivoRegistro,'$xObservacion','$xEstadoAgenda',$xCodigoAgenda,'$xColor','$xTextColor','{$xFecha}',$xUsuaid,'$xTerminal') ";
                if(mysqli_query($con, $xSQL)){
                    
                    $xAgendaid = mysqli_insert_id($con);

                    //BORRAR EL AGENDAMIENTO TEMPORAL
                    
                    $xSQL = "DELETE FROM `expert_reserva_tmp` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pres_id=$xPresid AND espe_id=$xEspeid AND pfes_id=$xPfesid AND fecha_inicio='$xFechaIni' AND fecha_fin='$xFechaFin' AND codigo_dia=$xCodigoDia  ";
                    mysqli_query($con, $xSQL);
                                        
                    $xSQL = "INSERT INTO `expert_logs`(log_detalle,usua_id,pais_id,empr_id,log_fechacreacion,log_terminalcreacion) ";
                    $xSQL .= "VALUES('Registro Agendado',$xUsuaid,$xPaisid,$xEmprid,'{$xFecha}','$xTerminal') ";
                    mysqli_query($con, $xSQL); 
                    
                    $xAgendado = 110;
                }
            }else{
                $xAgendaid = 0;
            }

            //ENVIANDO MAIL DEL AGENDAMIENTO
            if($xAgendado == 110){
                
                if($xTipoCliente == 'T'){
                    $xTipoCliente = 'TITULAR';
                }else{
                    $xTipoCliente = 'BENEFICIARIO';
                }

                require_once '/home/bbplusah/bbplus-ec.com/common/PHPMailer/Exception.php';
                require_once '/home/bbplusah/bbplus-ec.com/common/PHPMailer/PHPMailer.php';
                require_once '/home/bbplusah/bbplus-ec.com/common/PHPMailer/SMTP.php';


                $mail = new PHPMailer(true);
               
                try {
                    $mail->isSMTP();
                    $mail->SMTPDebug = 0;
                    $mail->SMTPAuth   = true;
                    
                    $mail->Host       = 'expertplus.bbplus-ec.com';
                    $mail->Username   = 'agendamiento@expertplus.bbplus-ec.com';
                    $mail->Password   = '37-]9{TkWOJN';
                    $mail->Sender     = 'agendamiento@expertplus.bbplus-ec.com';
                    $mail->SMTPSecure = "ssl";
                    $mail->Port       = 465;

                    $mail->setFrom('agendamiento@expertplus.bbplus-ec.com', 'Agendamiento - PRESTASALUD S.A');
                    $mail->addReplyTo('agendamiento@expertplus.bbplus-ec.com', 'Agendamiento - PRESTASALUD S.A');
                    
                    $mail->addAddress("cfam2212@gmail.com", "$xPaciente");
                    //$mail->addBCC("josh_may-cry@hotmail.com");
                    $mail->addBCC("cfam2006@hotmail.com");
                    //$mail->addBCC("vroldan@prestasalud.com");
                    
                    // -- BODY --
                        $message = "<html lang='es'>
                            <head>
                                <MIME-Version: 1.0'>
                                <Content-type: text/html; charset=iso-8859-1'>
                                <meta charset='UTF-8'>
                                <meta content='width=device-width, initial-scale=1' name='viewport'>
                                <meta name='x-apple-disable-message-reformatting'>
                                <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                                <meta content='telephone=no' name='format-detection'>
                                <title></title>
                                
                                <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i' rel='stylesheet'>
                                <link href='https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i' rel='stylesheet'>                                
                                
                                <style>
                                    #outlook a {
                                        padding: 0;
                                    }
                                    
                                    .ExternalClass {
                                        width: 100%;
                                    }
                                    
                                    .ExternalClass,
                                    .ExternalClass p,
                                    .ExternalClass span,
                                    .ExternalClass font,
                                    .ExternalClass td,
                                    .ExternalClass div {
                                        line-height: 100%;
                                    }
                                    
                                    .es-button {
                                        mso-style-priority: 100 !important;
                                        text-decoration: none !important;
                                    }
                                    
                                    a[x-apple-data-detectors] {
                                        color: inherit !important;
                                        text-decoration: none !important;
                                        font-size: inherit !important;
                                        font-family: inherit !important;
                                        font-weight: inherit !important;
                                        line-height: inherit !important;
                                    }
                                    
                                    .es-desk-hidden {
                                        display: none;
                                        float: left;
                                        overflow: hidden;
                                        width: 0;
                                        max-height: 0;
                                        line-height: 0;
                                        mso-hide: all;
                                    }
                                    
                                    /*
                                    END OF IMPORTANT
                                    */
                                    s {
                                        text-decoration: line-through;
                                    }
                                    
                                    body {
                                        width: 100%;
                                        font-family: 'Open Sans', sans-serif;
                                        -webkit-text-size-adjust: 100%;
                                        -ms-text-size-adjust: 100%;
                                    }
                                    
                                    table {
                                        mso-table-lspace: 1pt;
                                        mso-table-rspace: 1pt;
                                        border-collapse: collapse;
                                        border-spacing: 1px;
                                    }
                                    
                                    table td,
                                    html,
                                    body,
                                    .es-wrapper {
                                        padding: 0;
                                        Margin: 0;
                                    }
                                    
                                    .es-content,
                                    .es-header,
                                    .es-footer {
                                        table-layout: fixed !important;
                                        width: 100%;
                                    }
                                    
                                    img {
                                        display: block;
                                        border: 0;
                                        outline: none;
                                        text-decoration: none;
                                        -ms-interpolation-mode: bicubic;
                                    }
                                    
                                    table tr {
                                        border-collapse: collapse;
                                    }
                                    
                                    p,
                                    hr {
                                        Margin: 0;
                                    }
                                    
                                    h1,
                                    h2,
                                    h3,
                                    h4,
                                    h5 {
                                        Margin: 0;
                                        line-height: 120%;
                                        mso-line-height-rule: exactly;
                                        font-family: roboto, 'helvetica neue', helvetica, arial, sans-serif;
                                    }
                                    
                                    p,
                                    ul li,
                                    ol li,
                                    a {
                                        -webkit-text-size-adjust: none;
                                        -ms-text-size-adjust: none;
                                        mso-line-height-rule: exactly;
                                    }
                                    
                                    .es-left {
                                        float: left;
                                    }
                                    
                                    .es-right {
                                        float: right;
                                    }
                                    
                                    .es-p5 {
                                        padding: 5px;
                                    }
                                    
                                    .es-p5t {
                                        padding-top: 5px;
                                    }
                                    
                                    .es-p5b {
                                        padding-bottom: 5px;
                                    }
                                    
                                    .es-p5l {
                                        padding-left: 5px;
                                    }
                                    
                                    .es-p5r {
                                        padding-right: 5px;
                                    }
                                    
                                    .es-p10 {
                                        padding: 10px;
                                    }
                                    
                                    .es-p10t {
                                        padding-top: 10px;
                                    }
                                    
                                    .es-p10b {
                                        padding-bottom: 10px;
                                    }
                                    
                                    .es-p10l {
                                        padding-left: 10px;
                                    }
                                    
                                    .es-p10r {
                                        padding-right: 10px;
                                    }
                                    
                                    .es-p15 {
                                        padding: 15px;
                                    }
                                    
                                    .es-p15t {
                                        padding-top: 15px;
                                    }
                                    
                                    .es-p15b {
                                        padding-bottom: 15px;
                                    }
                                    
                                    .es-p15l {
                                        padding-left: 15px;
                                    }
                                    
                                    .es-p15r {
                                        padding-right: 15px;
                                    }
                                    
                                    .es-p20 {
                                        padding: 20px;
                                    }
                                    
                                    .es-p20t {
                                        padding-top: 20px;
                                    }
                                    
                                    .es-p20b {
                                        padding-bottom: 20px;
                                    }
                                    
                                    .es-p20l {
                                        padding-left: 20px;
                                    }
                                    
                                    .es-p20r {
                                        padding-right: 20px;
                                    }
                                    
                                    .es-p25 {
                                        padding: 25px;
                                    }
                                    
                                    .es-p25t {
                                        padding-top: 25px;
                                    }
                                    
                                    .es-p25b {
                                        padding-bottom: 25px;
                                    }
                                    
                                    .es-p25l {
                                        padding-left: 25px;
                                    }
                                    
                                    .es-p25r {
                                        padding-right: 25px;
                                    }
                                    
                                    .es-p30 {
                                        padding: 30px;
                                    }
                                    
                                    .es-p30t {
                                        padding-top: 30px;
                                    }
                                    
                                    .es-p30b {
                                        padding-bottom: 30px;
                                    }
                                    
                                    .es-p30l {
                                        padding-left: 30px;
                                    }
                                    
                                    .es-p30r {
                                        padding-right: 30px;
                                    }
                                    
                                    .es-p35 {
                                        padding: 35px;
                                    }
                                    
                                    .es-p35t {
                                        padding-top: 35px;
                                    }
                                    
                                    .es-p35b {
                                        padding-bottom: 35px;
                                    }
                                    
                                    .es-p35l {
                                        padding-left: 35px;
                                    }
                                    
                                    .es-p35r {
                                        padding-right: 35px;
                                    }
                                    
                                    .es-p40 {
                                        padding: 40px;
                                    }
                                    
                                    .es-p40t {
                                        padding-top: 40px;
                                    }
                                    
                                    .es-p40b {
                                        padding-bottom: 40px;
                                    }
                                    
                                    .es-p40l {
                                        padding-left: 40px;
                                    }
                                    
                                    .es-p40r {
                                        padding-right: 40px;
                                    }
                                    
                                    .es-menu td {
                                        border: 0;
                                    }
                                    
                                    .es-menu td a img {
                                        display: inline-block !important;
                                    }
                                    
                                    /* END CONFIG STYLES */
                                    a {
                                        text-decoration: none;
                                    }
                                    
                                    p,
                                    ul li,
                                    ol li {
                                        font-family: 'Open Sans', sans-serif;
                                        line-height: 150%;
                                    }
                                    
                                    ul li,
                                    ol li {
                                        Margin-bottom: 15px;
                                        margin-left: 0;
                                    }
                                    
                                    .es-menu td a {
                                        text-decoration: none;
                                        display: block;
                                        font-family: 'Open Sans', sans-serif;
                                    }
                                    
                                    .es-wrapper {
                                        width: 100%;
                                        height: 100%;
                                        background-repeat: repeat;
                                        background-position: center top;
                                    }
                                    
                                    .es-wrapper-color,
                                    .es-wrapper {
                                        background-color: #fafcfe;
                                    }
                                    
                                    .es-header {
                                        background-color: #0050d8;
                                        background-repeat: repeat;
                                        background-position: center top;
                                    }
                                    
                                    .es-header-body {
                                        background-color: #0c66ff;
                                    }
                                    
                                    .es-header-body p,
                                    .es-header-body ul li,
                                    .es-header-body ol li {
                                        color: #efefef;
                                        font-size: 12px;
                                    }
                                    
                                    .es-header-body a {
                                        color: #ffffff;
                                        font-size: 12px;
                                    }
                                    
                                    .es-content-body {
                                        background-color: #fefefe;
                                    }
                                    
                                    .es-content-body p,
                                    .es-content-body ul li,
                                    .es-content-body ol li {
                                        color: #8492a6;
                                        font-size: 14px;
                                    }
                                    
                                    .es-content-body a {
                                        color: #0c66ff;
                                        font-size: 14px;
                                    }
                                    
                                    .es-footer {
                                        background-color: #141b24;
                                        background-repeat: repeat;
                                        background-position: center top;
                                    }
                                    
                                    .es-footer-body {
                                        background-color: #273444;
                                    }
                                    
                                    .es-footer-body p,
                                    .es-footer-body ul li,
                                    .es-footer-body ol li {
                                        color: #8492a6;
                                        font-size: 12px;
                                    }
                                    
                                    .es-footer-body a {
                                        color: #ffffff;
                                        font-size: 12px;
                                    }
                                    
                                    .es-infoblock,
                                    .es-infoblock p,
                                    .es-infoblock ul li,
                                    .es-infoblock ol li {
                                        line-height: 120%;
                                        font-size: 16px;
                                        color: #ffffff;
                                    }
                                    
                                    .es-infoblock a {
                                        font-size: 16px;
                                        color: #ffffff;
                                    }
                                    
                                    h1 {
                                        font-size: 26px;
                                        font-style: normal;
                                        font-weight: bold;
                                        color: #3c4858;
                                    }
                                    
                                    h2 {
                                        font-size: 18px;
                                        font-style: normal;
                                        font-weight: normal;
                                        color: #3c4858;
                                    }
                                    
                                    h3 {
                                        font-size: 16px;
                                        font-style: normal;
                                        font-weight: bold;
                                        color: #888888;
                                        letter-spacing: 0px;
                                    }
                                    
                                    .es-header-body h1 a,
                                    .es-content-body h1 a,
                                    .es-footer-body h1 a {
                                        font-size: 26px;
                                    }
                                    
                                    .es-header-body h2 a,
                                    .es-content-body h2 a,
                                    .es-footer-body h2 a {
                                        font-size: 18px;
                                    }
                                    
                                    .es-header-body h3 a,
                                    .es-content-body h3 a,
                                    .es-footer-body h3 a {
                                        font-size: 16px;
                                    }
                                    
                                    a.es-button,
                                    button.es-button {
                                        padding: 15px 30px 15px 30px;
                                        display: inline-block;
                                        background: #0c66ff;
                                        border-radius: 0px;
                                        font-size: 14px;
                                        font-family: 'Open Sans', sans-serif;
                                        font-weight: bold;
                                        font-style: normal;
                                        line-height: 120%;
                                        color: #ffffff;
                                        text-decoration: none;
                                        width: auto;
                                        text-align: center;
                                        mso-padding-alt: 0;
                                        mso-border-alt: 10px solid #0c66ff;
                                    }
                                    
                                    .es-button-border {
                                        border-style: solid solid solid solid;
                                        border-color: #0c66ff #0c66ff #0c66ff #0c66ff;
                                        background: #0c66ff;
                                        border-width: 0px 0px 0px 0px;
                                        display: inline-block;
                                        border-radius: 0px;
                                        width: auto;
                                    }
                                    
                                    @media only screen and (max-width: 600px) {
                                    
                                        p,
                                        ul li,
                                        ol li,
                                        a {
                                            line-height: 150% !important;
                                        }
                                    
                                        h1,
                                        h2,
                                        h3,
                                        h1 a,
                                        h2 a,
                                        h3 a {
                                            line-height: 120%;
                                        }
                                    
                                        h1 {
                                            font-size: 28px !important;
                                            text-align: left;
                                        }
                                    
                                        h2 {
                                            font-size: 20px !important;
                                            text-align: left;
                                        }
                                    
                                        h3 {
                                            font-size: 14px !important;
                                            text-align: left;
                                        }
                                    
                                        h1 a {
                                            text-align: left;
                                        }
                                    
                                        .es-header-body h1 a,
                                        .es-content-body h1 a,
                                        .es-footer-body h1 a {
                                            font-size: 28px !important;
                                        }
                                    
                                        h2 a {
                                            text-align: left;
                                        }
                                    
                                        .es-header-body h2 a,
                                        .es-content-body h2 a,
                                        .es-footer-body h2 a {
                                            font-size: 20px !important;
                                        }
                                    
                                        h3 a {
                                            text-align: left;
                                        }
                                    
                                        .es-header-body h3 a,
                                        .es-content-body h3 a,
                                        .es-footer-body h3 a {
                                            font-size: 14px !important;
                                        }
                                    
                                        .es-menu td a {
                                            font-size: 14px !important;
                                        }
                                    
                                        .es-header-body p,
                                        .es-header-body ul li,
                                        .es-header-body ol li,
                                        .es-header-body a {
                                            font-size: 14px !important;
                                        }
                                    
                                        .es-content-body p,
                                        .es-content-body ul li,
                                        .es-content-body ol li,
                                        .es-content-body a {
                                            font-size: 14px !important;
                                        }
                                    
                                        .es-footer-body p,
                                        .es-footer-body ul li,
                                        .es-footer-body ol li,
                                        .es-footer-body a {
                                            font-size: 14px !important;
                                        }
                                    
                                        .es-infoblock p,
                                        .es-infoblock ul li,
                                        .es-infoblock ol li,
                                        .es-infoblock a {
                                            font-size: 14px !important;
                                        }
                                    
                                        [class='gmail-fix'] {
                                            display: none !important;
                                        }
                                    
                                        .es-m-txt-c,
                                        .es-m-txt-c h1,
                                        .es-m-txt-c h2,
                                        .es-m-txt-c h3 {
                                            text-align: center !important;
                                        }
                                    
                                        .es-m-txt-r,
                                        .es-m-txt-r h1,
                                        .es-m-txt-r h2,
                                        .es-m-txt-r h3 {
                                            text-align: right !important;
                                        }
                                    
                                        .es-m-txt-l,
                                        .es-m-txt-l h1,
                                        .es-m-txt-l h2,
                                        .es-m-txt-l h3 {
                                            text-align: left !important;
                                        }
                                    
                                        .es-m-txt-r img,
                                        .es-m-txt-c img,
                                        .es-m-txt-l img {
                                            display: inline !important;
                                        }
                                    
                                        .es-button-border {
                                            display: block !important;
                                        }
                                    
                                        a.es-button,
                                        button.es-button {
                                            font-size: 14px !important;
                                            display: block !important;
                                            border-bottom-width: 20px !important;
                                            border-right-width: 0px !important;
                                            border-left-width: 0px !important;
                                            padding-left: 0px !important;
                                            padding-right: 0px !important;
                                        }
                                    
                                        .es-btn-fw {
                                            border-width: 10px 0px !important;
                                            text-align: center !important;
                                        }
                                    
                                        .es-adaptive table,
                                        .es-btn-fw,
                                        .es-btn-fw-brdr,
                                        .es-left,
                                        .es-right {
                                            width: 100% !important;
                                        }
                                    
                                        .es-content table,
                                        .es-header table,
                                        .es-footer table,
                                        .es-content,
                                        .es-footer,
                                        .es-header {
                                            width: 100% !important;
                                            max-width: 600px !important;
                                        }
                                    
                                        .es-adapt-td {
                                            display: block !important;
                                            width: 100% !important;
                                        }
                                    
                                        .adapt-img {
                                            width: 100% !important;
                                            height: auto !important;
                                        }
                                    
                                        .es-m-p0 {
                                            padding: 0px !important;
                                        }
                                    
                                        .es-m-p0r {
                                            padding-right: 0px !important;
                                        }
                                    
                                        .es-m-p0l {
                                            padding-left: 0px !important;
                                        }
                                    
                                        .es-m-p0t {
                                            padding-top: 0px !important;
                                        }
                                    
                                        .es-m-p0b {
                                            padding-bottom: 0 !important;
                                        }
                                    
                                        .es-m-p20b {
                                            padding-bottom: 20px !important;
                                        }
                                    
                                        .es-mobile-hidden,
                                        .es-hidden {
                                            display: none !important;
                                        }
                                    
                                        tr.es-desk-hidden,
                                        td.es-desk-hidden,
                                        table.es-desk-hidden {
                                            width: auto !important;
                                            overflow: visible !important;
                                            float: none !important;
                                            max-height: inherit !important;
                                            line-height: inherit !important;
                                        }
                                    
                                        tr.es-desk-hidden {
                                            display: table-row !important;
                                        }
                                    
                                        table.es-desk-hidden {
                                            display: table !important;
                                        }
                                    
                                        td.es-desk-menu-hidden {
                                            display: table-cell !important;
                                        }
                                    
                                        table.es-table-not-adapt,
                                        .esd-block-html table {
                                            width: auto !important;
                                        }
                                    
                                        table.es-social {
                                            display: inline-block !important;
                                        }
                                    
                                        table.es-social td {
                                            display: inline-block !important;
                                        }
                                    
                                        .es-desk-hidden {
                                            display: table-row !important;
                                            width: auto !important;
                                            overflow: visible !important;
                                            max-height: inherit !important;
                                        }
                                    }
                                    
                                    /* END RESPONSIVE STYLES */
                                    .es-p-default {
                                        padding-top: 20px;
                                        padding-right: 15px;
                                        padding-bottom: 0px;
                                        padding-left: 15px;
                                    }
                                    
                                    .es-p-all-default {
                                        padding: 0px;
                                    }


                                </style>
                            </head>
                            <center style='width: 100%; background-color: #F5F9F8;'>
                            <body>
                                
                        "; 
                        
                        $message .= "<div dir='ltr' class='es-wrapper-color'>
                                <!--[if gte mso 9]>
                        			<v:background xmlns:v='urn:schemas-microsoft-com:vml' fill='t'>
                        				<v:fill type='tile' color='#fafcfe' origin='0.5, 0' position='0.5, 0'></v:fill>
                        			</v:background>
                        		<![endif]-->
                                <table class='es-wrapper' width='100%' cellspacing='0' cellpadding='0'>
                                    <tbody>
                                        <tr>
                                            <td class='esd-email-paddings' valign='top'>
                                                <center style='width: 100%; background-color: #0a586c;'>
                                                    <table class='esd-header-popover es-header' cellspacing='0' cellpadding='0' align='center'>
                                                        <tbody>
                                                            <tr>
                                                                <td class='esd-stripe' align='center' bgcolor='#0a586c' style='background-color: #0a586c;'>
                                                                    <table class='es-header-body' width='600' cellspacing='0' cellpadding='0' bgcolor='#3aa3d5' align='center' style='background-color: #3aa3d5;'>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class='esd-structure' align='left'>
                                                                                    <table cellspacing='0' cellpadding='0' width='100%'>
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td class='es-m-p0r esd-container-frame' width='600' valign='top' align='center'>
                                                                                                    <table width='100%' cellspacing='0' cellpadding='0'>
                                                                                                        <tbody>
                                                                                                            <tr>
                                                                                                                <td align='center' class='esd-block-image' style='font-size: 0px;'><a target='_blank'><img class='adapt-img' src='https://expertplus.bbplus-ec.com/logos/$xLogoCab' alt style='display: block;' width='600'></a></td>
                                                                                                            </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </center>
                                                
                                                <table class='es-content' cellspacing='0' cellpadding='0' align='center'>
                                                    <tbody>
                                                        <tr>
                                                            <td class='esd-stripe' align='center'>
                                                                <table class='es-content-body' width='600' cellspacing='0' cellpadding='0' bgcolor='#fefefe' align='center'>
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class='esd-structure es-p25t es-p15r es-p15l' align='left'>
                                                                                <table cellpadding='0' cellspacing='0' width='100%'>
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td width='570' class='esd-container-frame' align='center' valign='top'>
                                                                                                <table cellpadding='0' cellspacing='0' width='100%'>
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td align='center' class='esd-block-text'>
                                                                                                                <h1>Hola $xPaciente</h1>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td align='center' class='esd-block-text es-p10t'>
                                                                                                                <p style='color: #6d7f95;'>Datos Para el agendamiento</p>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td align='center' class='esd-block-text es-p10t'>
                                                                                                                <p>Registrar los siguientes datos para agendamiento.</p>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td align='center' class='esd-block-spacer es-p20' style='font-size:0'>
                                                                                                                <table border='0' width='100%' height='100%' cellpadding='0' cellspacing='0'>
                                                                                                                    <tbody>
                                                                                                                        <tr>
                                                                                                                            <td style='border-bottom: 1px solid #cccccc; background: unset; height:1px; width:100%; margin:0px 0px 0px 0px;'></td>
                                                                                                                        </tr>
                                                                                                                    </tbody>
                                                                                                                </table>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <table width='100%'>
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <table align='center'class='table' border='1' style='font-size:14px' width='100%' >
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td style='font-weight:bold;'>Cliente</td>
                                                                                                            <td>$xCliente</td>
                                                                                                            <td style='font-weight:bold;'>Producto</td>
                                                                                                            <td>$xProducto</td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td style='font-weight:bold;'>Medicamentos</td>
                                                                                                            <td>$XMedicamentos</td>
                                                                                                            <td style='font-weight:bold;'>Codigo Cita</td>
                                                                                                            <td>$xCodigoAgenda</td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td style='font-weight:bold;'>Ciudad Cita</td>
                                                                                                            <td>$xCiudadAgenda</td>
                                                                                                            <td style='font-weight:bold;'>Fecha Cita</td>
                                                                                                            <td>$xFechaIni</td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td style='font-weight:bold;'>Hora Cita</td>
                                                                                                            <td>$xHoraCita</td>
                                                                                                            <td style='font-weight:bold;'>Prestadora</td>
                                                                                                            <td>$xPrestadora</td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td style='font-weight:bold;'>Profesional</td>
                                                                                                            <td>$xProfesional</td>
                                                                                                            <td style='font-weight:bold;'>Especialidad</td>
                                                                                                            <td>$xEspecialidad</td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td style='font-weight:bold;'>Motivo</td>
                                                                                                            <td>$xMotivo</td>
                                                                                                            <td style='font-weight:bold;'>Detalle</td>
                                                                                                            <td>$xObservacion</td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td style='font-weight:bold;'>Cedula Titular </td>
                                                                                                            <td>$CedulaTitu</td>
                                                                                                            <td style='font-weight:bold;'>Tipo Cliente</td>
                                                                                                            <td>$xTipoCliente</td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td style='font-weight:bold;'>Cliente</td>
                                                                                                            <td>$xPaciente</td>
                                                                                                            <td style='font-weight:bold;'>Fecha Nacimiento</td>
                                                                                                            <td>$xFechaNacimiento</td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td style='font-weight:bold;'>Direccion</td>
                                                                                                            <td>$xDireccion</td>
                                                                                                            <td style='font-weight:bold;'>Telefonos</td>
                                                                                                            <td>$xTelefono</td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td style='font-weight:bold;'>Usuario</td>
                                                                                                            <td>$xUserAgent</td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                
                                                <br><br>
                                                
                                               <table cellpadding='0' cellspacing='0' align='center' style='background-color: #20415b;' >
                                                    <tbody>
                                                        <tr>
                                                            <td class='esd-stripe' align='center' esd-custom-block-id='155901' style='background-color: #20415b;'>
                                                                <table class='es-footer-body' width='600' cellspacing='0' cellpadding='0' bgcolor='#ffffff' align='center' style='background-color: #20415b;' >
                                                                    <tbody style='color:#FFFFFF' >
                                                                        <tr>
                                                                            <td class='esd-structure es-p5t es-p5r es-p5l' align='left'>
                                                                                <!--[if mso]><table width='590' cellpadding='0' 
                                                                                cellspacing='0'><tr><td width='218' valign='top'><![endif]-->
                                                                                <table class='es-left' cellspacing='0' cellpadding='0' align='left'>
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td class='es-m-p20b esd-container-frame' width='218' align='left'>
                                                                                                <table width='100%' cellspacing='0' cellpadding='0'>
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td align='center' class='esd-block-image' style='font-size: 0px;'><a target='_blank'><img class='adapt-img' src='https://expertplus.bbplus-ec.com/logos/$xLogoFirma' alt style='display: block;' width='218'></a></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                                <!--[if mso]></td><td width='10'></td><td width='362' valign='top'><![endif]-->
                                                                                <table class='es-right' cellspacing='0' cellpadding='0' align='right'>
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td width='362' align='left'>
                                                                                                <table width='100%' cellspacing='0' cellpadding='0'>
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td align='left' >
                                                                                                                <p>Direccion: AV. GONZALEZ SUAREZ N32-90 Y JACINTO BEJARANO<br>Contacto: (593 2) 3959 229<br>Correo: info@prestasalud.com </p>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                                <!--[if mso]></td></tr></table><![endif]-->
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td class='esd-structure es-p5t es-p15r es-p15l' align='left'>
                                                                                <table cellpadding='0' cellspacing='0' width='100%'>
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td width='570' class='esd-container-frame' align='center' valign='top'>
                                                                                                <table cellpadding='0' cellspacing='0' width='100%'>
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td align='center' class='esd-block-text' esd-links-underline='none'>
                                                                                                                <p style='line-height: 150%;'>Copyright © <a href='https://www.prestasalud.com/portal/' target='_blank' style='text-decoration: none; color: #6EF1EE; '>Prestasalud S.A</a> | 2023</p>
                                                                                                                <p style='display: none; line-height: 150%;'><br></p>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> ";

                        $message .= " <br> <br>
                                </center></body>
                                </html>";

                        //file_put_contents('1_logseguimiento.txt', "$message" . "\n\n", FILE_APPEND);
                        // Content
                        //$xLOGO = "/home/bbplusah/expertplus.bbplus-ec.com/logos/$xLogoFirma";
                        //$mail->AddEmbeddedImage($xLOGO, 'LogoFirma', $xLogoFirma);
                        $mail->isHTML(true);      
                        $subject = "Datos Para Agendamiento - $xPaciente ";
                        $subject = utf8_decode($subject);
                        $mail->Subject = $subject;
                        $mail->Body    = utf8_decode($message);
                        //$mail->AddAttachment($destinationpdf);
                        //$mail->AddAttachment($destinationxml);
                        
                        $mail->send();
                        
                        $xSQL = "INSERT INTO `expert_logs`(log_detalle,usua_id,pais_id,empr_id,log_fechacreacion,log_terminalcreacion) ";
                        $xSQL .= "VALUES('Mail Agendamiento Enviado',$xUsuaid,$xPaisid,$xEmprid,'{$xFecha}','$xTerminal') ";
                        mysqli_query($con, $xSQL); 

                }catch (Exception $e) {
                    $xAgendaid = -1;
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    echo $e;
                    $xSQL = "INSERT INTO `expert_logs`(log_detalle,usua_id,pais_id,empr_id,log_fechacreacion,log_terminalcreacion) ";
                    $xSQL .= "VALUES('$e',$xUsuaid,$xPaisid,$xEmprid,'{$xFecha}','$xTerminal') ";
                    mysqli_query($con, $xSQL);                     
                    //file_put_contents('1_logseguimiento.txt', "$xFecha ERROR: - $e - uid[$mail->ErrorInfo] " . "\n\n", FILE_APPEND);
                    exit(0);
                }                
            }
        }
    }
    
    mysqli_close($con);
    echo $xAgendaid;

?>