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

    if(isset($_POST['xxProid']) and isset($_POST['xxEmprid']) and isset($_POST['xxEstado'])){
        if(isset($_POST['xxProid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxEstado']) <> ''){
            
            $xProid = $_POST['xxProid'];
            $xEmprid = $_POST['xxEmprid'];
            $xEstado = safe($_POST['xxEstado']);

            if($xEstado == 'Activo'){
                $xEstado = 'A';
            }else{
                $xEstado = 'I';
            }

            $xSQL = "UPDATE `expert_productos` SET prod_estado='$xEstado' WHERE prod_id=$xProid AND empr_id=$xEmprid  ";
            mysqli_query($con, $xSQL);

            $xRespuesta = "OK";
        }
    }

    echo $xRespuesta;

?>