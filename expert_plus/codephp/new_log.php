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
    $xTerminal = gethostname();
    $response = "ERR";

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxUsuaid']) and  isset($_POST['xxDetalle']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxUsuaid']) <> '' and  isset($_POST['xxDetalle']) <> ''){

            $yPaisid = $_POST['xxPaisid'];
            $yEmprid = $_POST['xxEmprid'];
            $yUsuaid = $_POST['xxUsuaid'];
            $xDetalle = $_POST['xxDetalle'];

            $xSQL = "INSERT INTO `expert_logs` (log_detalle,usua_id,pais_id,empr_id,log_fechacreacion,log_terminalcreacion) ";
            $xSQL .= "VALUES ('$xDetalle',$yUsuaid,$yPaisid,$yEmprid,'{$xFecha}','$xTerminal')";
            mysqli_query($con, $xSQL);

            $response = 'OK';
        }
    }

    echo $response;

?>