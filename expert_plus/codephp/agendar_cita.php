<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception; 

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

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
            $xLogoCab = "citaagendadax.png";
            $xLogoFirma = "LogoPrestasalud.png";
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
    
                    $xSQL = "INSERT INTO `expert_logs`(log_detalle,usua_id,pais_id,empr_id,log_fechacreacion,log_terminalcreacion) ";
                    $xSQL .= "VALUES('Registro Agendado',$xUsuaid,$xPaisid,$xEmprid,'{$xFecha}','$xTerminal') ";
                    mysqli_query($con, $xSQL); 
                    
                    $xAgendado = 110;
                }
            }else{
                $xCodigoAgenda = 0;
            }

            //ENVIANDO MAIL DEL AGENDAMIENTO
            if($xAgendado == 110){

                /*require_once '/home/bbplusah/bbplus-ec.com/common/PHPMailer/Exception.php';
                require_once '/home/bbplusah/bbplus-ec.com/common/PHPMailer/PHPMailer.php';
                require_once '/home/bbplusah/bbplus-ec.com/common/PHPMailer/SMTP.php';*/

                require_once '../PHPMailer/Exception.php';
                require_once '../PHPMailer/PHPMailer.php';
                require_once '../PHPMailer/SMTP.php';

                
                $mail = new PHPMailer(true);
               
                try {
                    $mail->isSMTP();
                    $mail->SMTPDebug = 0;
                    $mail->SMTPAuth   = true;
                    
                    /*$mail->Host       = 'mail.bbplus-ec.com';
                    $mail->Username   = 'noreply@bbplus-ec.com';
                    $mail->Password   = 'wG.ok$sY.E{j';
                    $mail->Sender     = 'noreply@bbplus-ec.com';
                    $mail->SMTPSecure = "ssl";
                    $mail->Port       = 465;*/

                    $mail->SMTPSecure = "tls";
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Username = 'cfam2212@gmail.com';
                    $mail->Password = 'crisalve_2212';
                    $mail->Port = 587;                    
                    
                    $mail->setFrom('cfam2212@gmail.com', 'Agendamiento - PRESTASALUD S.A');
                    $mail->addReplyTo('vroldan@prestasalud.com', 'Agendamiento - PRESTASALUD S.A');
                    
                    //file_put_contents('log_errores_enviarmail.txt', $xPCorreo . "\n\n", FILE_APPEND);
                    
                    $mail->addAddress("xchxch1803@gmail.com", "$xPaciente");
                    $mail->addBCC("vinirol@gmail.com");
                    
                    // -- BODY --
                        $message = "<html lang='es'>
                            <head>
                                <meta charset='utf-8' />
                                
                                <style>
                                    #customers {
                                      font-family: Arial, Helvetica, sans-serif;
                                      border-collapse: collapse;
                                      width: 100%;
                                    }
                                    
                                    #customers td, #customers th {
                                      border: 1px solid #ddd;
                                      padding: 8px;
                                    }
                                    
                                    #customers tr:nth-child(even){background-color: #f2f2f2;}
                                    
                                    #customers tr:hover {background-color: #ddd;}
                                    
                                    #customers th {
                                      padding-top: 12px;
                                      padding-bottom: 12px;
                                      text-align: left;
                                      background-color: #d6dad6;
                                      color: red;
                                    }
                                    
                                </style>
                            </head>
                            <body>
                                
                        "; 
                        
                        $message .= "<br><br>";
                        //$message .= "<img src='https://bbplus-ec.com/img/GraciasPorRegistrarse.png' alt='' style='height: 100%; width: 100%;' /><br><br>";
                        $message .= "<img src='../logos/$xLogoCab' alt='' style='height: 100%; width: 100%;' /><br><br>";
                        $message .= "Nuevo Agendamiento: <strong>$xPaciente</strong><br><br>";
                        $message .= "<hr><br>";
                        $message .= "Registrar los siguientes datos para agendamiento<br><br>";
                        $message .= "<hr><br>";
                        $message .= "
                            <table align='center' class='table' id='customers'>
                                <tbody>
                                    <tr>
                                        <td style='font-weight:bold;'>Cliente</td>
                                        <td style='text-align:center'>:</td>
                                        <td>$xCliente</td>
                                    </tr>
                                    <tr>
                                        <td style='font-weight:bold;'>Producto</td>
                                        <td style='text-align:center;'>:</td>
                                        <td>$xProducto</td>
                                    </tr>
                                    <tr>
                                        <td style='font-weight:bold;'>Medicamentos</td>
                                        <td style='text-align:center;'>:</td>
                                        <td>$XMedicamentos</td>
                                    </tr>
                                    <tr>
                                        <td style='font-weight:bold;'>Codigo Cita</td>
                                        <td style='text-align:center;'>:</td>
                                        <td>$xCodigoAgenda</td>
                                    </tr>
                                    <tr>
                                        <td style='font-weight:bold;'>Ciudad Cita</td>
                                        <td style='text-align:center;'>:</td>
                                        <td>$xCiudadAgenda</td>
                                    </tr>
                                    <tr>
                                        <td style='font-weight:bold;'>Fecha Cita</td>
                                        <td style='text-align:center;'>:</td>
                                        <td>$xFechaIni</td>
                                    </tr>
                                    <tr>
                                        <td style='font-weight:bold;'>Hora Cita</td>
                                        <td style='text-align:center;'>:</td>
                                        <td>$xHoraCita</td>
                                    </tr>
                                    <tr>
                                        <td style='font-weight:bold;'>Prestadora</td>
                                        <td style='text-align:center;'>:</td>
                                        <td>$xPrestadora</td>
                                    </tr>
                                    <tr>
                                        <td style='font-weight:bold;'>Profesional</td>
                                        <td style='text-align:center;'>:</td>
                                        <td>$xProfesional</td>
                                    </tr>
                                    <tr>
                                        <td style='font-weight:bold;'>Especialidad</td>
                                        <td style='text-align:center;'>:</td>
                                        <td>$xEspecialidad</td>
                                    </tr>
                                    <tr>
                                        <td style='font-weight:bold;'>Motivo</td>
                                        <td style='text-align:center;'>:</td>
                                        <td>$xMotivo</td>
                                    </tr>
                                    <tr>                                    
                                        <td style='font-weight:bold;'>Detalle</td>
                                        <td style='text-align:center;'>:</td>
                                        <td>$xObservacion</td>
                                    </tr>
                                    <tr>
                                        <td style='font-weight:bold;'>Cedula Titular </td>
                                        <td style='text-align:center;'>:</td>
                                        <td>$CedulaTitu</td>
                                    </tr>
                                    <tr>
                                        <td style='font-weight:bold;'>Tipo Cliente</td>
                                        <td style='text-align:center;'>:</td>
                                        <td>$xTipoCliente</td>
                                    </tr>
                                    <tr>
                                        <td style='font-weight:bold;'>Cliente</td>
                                        <td style='text-align:center;'>:</td>
                                        <td>$xPaciente</td>
                                    </tr>
                                    <tr>
                                        <td style='font-weight:bold;'>Fecha Nacimiento</td>
                                        <td style='text-align:center;'>:</td>
                                        <td>$xFechaNacimiento</td>
                                    </tr>
                                    <tr>
                                        <td style='font-weight:bold;'>Direccion</td>
                                        <td style='text-align:center;'>:</td>
                                        <td>$xDireccion</td>
                                    </tr>
                                    <tr>
                                        <td style='font-weight:bold;'>Telefonos</td>
                                        <td style='text-align:center;'>:</td>
                                        <td>$xTelefono</td>
                                    </tr>
                                    <tr>
                                        <td style='font-weight:bold;'>Usuario</td>
                                        <td style='text-align:center;'>:</td>
                                        <td>$xUserAgent</td>
                                    </tr>
                                </tbody></table> ";
                        $message .= "<hr><br><br>";
                        $message .= "Estimado Cliente, acuda a su cita agendada 10 minutos antes de la hora registrada.<br><br>";
                        $message .= "Recuerde llevar el documento de identidad del Titular del servicio.";
                        $message .= "<br><br><br><br>";
                        $message .= "Atentamente,<br><br>";
                        $message .= "<img src='cid:LogoFirma' width='280' height='120' />";
                        $message .= "<br><br><hr>";
                        $message .= "Pie de Pagina1 <br>";
                        $message .= "Pie de Pagina2 <br>";
                        $message .= "Pie de Pagina3 <br>";
                        $message .= "Pie de Pagina4 <br>";
                        $message .= '	<table align="center">
                                                <tr>
                                                  <td bgcolor="#ffffff" align="center" style="color:#212b35;font-family:ShopifySans,Helvetica,Arial,sans-serif;font-size:12px;font-weight:400;line-height:22px;padding-top:30px;padding-bottom:30px">
                                                    <p style="padding-top:15px; color: #5d91ab;"> Copyright &copy; <a href="https://http://www.prestasalud.com/" style="padding-top:15px;color:#212b35;text-decoration:none" target="_blank" >PRESTASALUD S.A</a> | Direccion, Telf: +593 99 999 9999>
                                                    <p align="center" style="padding-top:15px"><strong><a href="#" style="color:#212b35;font-size:13px;text-decoration:none" target="_blank" >Anular su suscripción</a></strong></p> 
                                                  </td>
                                            </tr></table>';
                        $message .= " <br> <br>
                                </body>
                                </html>";

                        file_put_contents('log_1seguimiento.txt', "$message" . "\n\n", FILE_APPEND);
                        // Content
                        //$xLogoFirma = "/home/bbplusah/bbplus-ec.com/img/LogoFirmaBBPLUSNew.jpeg";
                        $xFirma = "../logos/$xLogoFirma";
                        $mail->AddEmbeddedImage($xFirma, "LogoFirma", "$xLogoFirma");
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
                    $xCodigoAgenda = -1;
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    echo $e;
                    file_put_contents('log_1seguimiento.txt', "$xFecha ERROR: - $e - uid[$mail->ErrorInfo] " . "\n\n", FILE_APPEND);
                    exit(0);
                }                
            }
        }
    }
    
    mysqli_close($con);
    echo $xCodigoAgenda;

?>