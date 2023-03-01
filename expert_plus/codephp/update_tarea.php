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

    if(isset($_POST['xxEmprid']) and isset($_POST['xxTareaId']) and isset($_POST['xxTarea']) and isset($_POST['xxTarea']) and isset($_POST['xxRuta']) ){
        if(isset($_POST['xxEmprid']) <> '' and isset($_POST['xxTareaId']) <> '' and isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxTarea']) <> '' and isset($_POST['xxRuta']) <> ''){

            $yEmprid = $_POST['xxEmprid'];
            $yTareaid = $_POST['xxTareaId'];
            $xTarea = $_POST['xxTarea'];
            $xRuta = safe($_POST['xxRuta']);

            $xSQL ="UPDATE `expert_tarea` SET tare_nombre='$xTarea',tare_ruta='$xRuta' WHERE tare_id=$yTareaid AND empr_id=$yEmprid";
            mysqli_query($con, $xSQL);

            //print json_encode($xTareaid, JSON_UNESCAPED_UNICODE);
            echo $yTareaid;
        }
    }
    
?>