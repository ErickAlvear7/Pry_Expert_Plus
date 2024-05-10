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

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxGrupoid']) and isset($_POST['xxUsuaid']) and isset($_POST['xxEstado'])){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxGrupoid']) <> '' and isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxEstado']) <> ''){
            
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xGrupoid = $_POST['xxGrupoid'];
            $xUsuaid = $_POST['xxUsuaid'];
            $xEstado = safe($_POST['xxEstado']);

            if($xEstado == 'ACTIVO'){
                $xEstado = 'A';
            }else{
                $xEstado = 'I';
            }

            $xSQL = "UPDATE `expert_grupos` SET grup_estado='$xEstado' WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND grup_id=$xGrupoid  ";
            mysqli_query($con, $xSQL);

            $xSQL = "INSERT INTO `expert_logs`(log_detalle,usua_id,pais_id,empr_id,log_fechacreacion,log_terminalcreacion) ";
            $xSQL .= "VALUES('Cambio de estado grupo',$xUsuaid,$xPaisid,$xEmprid,'{$xFecha}','$xTerminal') ";
            mysqli_query($con, $xSQL);   

            $xRespuesta = "OK";
        }
    }

    echo $xRespuesta;

?>