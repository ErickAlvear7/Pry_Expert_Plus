<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');       

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xRespuesta = "ERR";
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();    

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxGrupoid']) and isset($_POST['xxUsuaid']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxGrupoid']) <> '' and isset($_POST['xxUsuaid']) <> '' ){
            
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xGrupoid = $_POST['xxGrupoid'];
            $xSecuanagenda = $_POST['xxSecuenAgenda'];
            $xSecuencancela = $_POST['xxSecuenCancela'];
            $xxSecuenAtendido = $_POST['xxSecuenAtendido'];
            $xSecuenAusente = $_POST['xxSecuenAusente'];
            $xUsuaid = $_POST['xxUsuaid'];
            
            $xSQL = "UPDATE `expert_grupos` SET secuencial_agendado=$xSecuanagenda,secuencial_cancelado=$xSecuencancela,secuencial_atendido=$xxSecuenAtendido,secuencial_ausente=$xSecuenAusente  WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND grup_id=$xGrupoid  ";
            mysqli_query($con, $xSQL);

            $xSQL = "INSERT INTO `expert_logs`(log_detalle,usua_id,pais_id,empr_id,log_fechacreacion,log_terminalcreacion) ";
            $xSQL .= "VALUES('Cambio Datos del grupo',$xUsuaid,$xPaisid,$xEmprid,'{$xFecha}','$xTerminal') ";
            mysqli_query($con, $xSQL);   

            $xRespuesta = "OK";
        }
    }

    echo $xRespuesta;

?>