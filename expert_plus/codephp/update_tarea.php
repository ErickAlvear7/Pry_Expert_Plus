<?php
    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();

    if(isset($_POST['xxEmprid']) and isset($_POST['xxTareaId']) and isset($_POST['xxTarea']) and isset($_POST['xxTarea']) and isset($_POST['xxRuta']) ){
        if(isset($_POST['xxEmprid']) <> '' and isset($_POST['xxTareaId']) <> '' and isset($_POST['xxUsuaCodigo']) <> '' and isset($_POST['xxTarea']) <> '' and isset($_POST['xxRuta']) <> ''){

            $yEmprid = $_POST['xxEmprid'];
            $yUsuaCodigo = $_POST['xxUsuaid'];
            $xTareaid = $_POST['xxTareaId'];
            $xTarea = $_POST['xxTarea'];
            $xRuta = safe($_POST['xxRuta']);

            $xSQL ="UPDATE `expert_tarea` SET tare_nombre='$xTarea',tare_ruta='$xRuta' WHERE tare_id=$xTareaid";
            mysqli_query($con, $xSQL);
            print json_encode($xTareaid, JSON_UNESCAPED_UNICODE);        
        }
    }
?>