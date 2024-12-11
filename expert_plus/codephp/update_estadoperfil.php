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


    if(isset($_POST['xxPerfid']) and isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxEstado'])){
        if(isset($_POST['xxPerfid']) <> '' and isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxEstado']) <> ''){

            $xPerfid = $_POST['xxPerfid'];
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xEstado = safe($_POST['xxEstado']);

            $xSQL = "UPDATE `expert_perfil` SET perf_estado='$xEstado' ";
            $xSQL .= " WHERE perf_id=$xPerfid AND pais_id=$xPaisid AND empr_id=$xEmprid";
            mysqli_query($con, $xSQL);

            $xRespuesta = "OK";
        }
    }

    echo $xRespuesta;
?>