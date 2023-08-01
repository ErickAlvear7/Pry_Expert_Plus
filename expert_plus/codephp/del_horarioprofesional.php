<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());
    $xTerminal = gethostname();   
    $xRespuesta = 'ERR';

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxHoraid']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxHoraid']) <> '' ){

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xUsuaid = $_POST['xxUsuaid'];
            $xHoraid = $_POST['xxHoraid'];

            $xSQL = "DELETE FROM `expert_horarios_profesional` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND hora_id=$xHoraid ";
            mysqli_query($con, $xSQL);
            $xRespuesta = 'OK';

            $xSQL = "INSERT INTO `expert_logs`(log_detalle,usua_id,pais_id,empr_id,log_fechacreacion,log_terminalcreacion) ";
            $xSQL .= "VALUES('Turno/Horario Eliminado',$xUsuaid,$xPaisid,$xEmprid,'{$xFecha}','$xTerminal') ";
            mysqli_query($con, $xSQL);              
        }
    }
    
    mysqli_close($con);
    echo $xRespuesta;

?>