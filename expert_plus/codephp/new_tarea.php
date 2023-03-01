<?php
    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();

    if(isset($_POST['xxEmprid']) and isset($_POST['xxUsuaCodigo']) and isset($_POST['xxTarea']) and isset($_POST['xxRuta']) ){
        if(isset($_POST['xxEmprid']) <> '' and isset($_POST['xxUsuaCodigo']) <> '' and isset($_POST['xxTarea']) <> '' and isset($_POST['xxRuta']) <> ''){

            $yEmprid = $_POST['xxEmprid'];
            $yUsuaCodigo = $_POST['xxUsuaid'];
            $xTarea = $_POST['xxTarea'];
            $xRuta = safe($_POST['xxRuta']);

            $xSQL ="INSERT INTO `expert_tarea` (empr_id,tare_nombre,tare_ruta,tare_estado,tare_orden,fechacreacion, ";
            $xSQL .= "usuariocreacion,terminalcreacion)";
            $xSQL .="VALUES ($yEmprid,'$xTarea','$xRuta','{$xFecha}',$yUsuaCodigo,'$xTerminal') ";
            if(mysqli_query($con, $xSQL)){
                $last_id = mysqli_insert_id($con);
            }

            print json_encode($last_id, JSON_UNESCAPED_UNICODE);        
        }
    }
?>