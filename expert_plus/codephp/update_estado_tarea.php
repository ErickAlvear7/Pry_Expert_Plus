<?php
    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	    

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $response = 'ERR';

    if(isset($_POST['xxEmprid']) and isset($_POST['xxTareaId']) and isset($_POST['xxEstado']) ){
        if(isset($_POST['xxEmprid']) <> '' and isset($_POST['xxTareaId']) <> '' and isset($_POST['xxEstado']) <> ''){

            $yEmprid = $_POST['xxEmprid'];
            $yTareaid = $_POST['xxTareaId'];
            $xEstado = $_POST['xxEstado'];

            if($xEstado == 'Activo'){
                $xEstado = 'A';
            }else{
                $xEstado = 'I';
            }

            $xSQL ="UPDATE `expert_tarea` SET tare_estado='$xEstado' WHERE tare_id=$yTareaid AND empr_id=$yEmprid";
            mysqli_query($con, $xSQL);

            $response = 'OK';
        }
    }

    echo $response;
    
?>