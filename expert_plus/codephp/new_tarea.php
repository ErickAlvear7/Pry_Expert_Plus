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
    $last_id = 0;
    $yOrden = 0;

    if(isset($_POST['xxEmprid']) and isset($_POST['xxUsuaid']) and isset($_POST['xxTarea']) and isset($_POST['xxRuta']) ){
        if(isset($_POST['xxEmprid']) <> '' and isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxTarea']) <> '' and isset($_POST['xxRuta']) <> ''){

            $yEmprid = $_POST['xxEmprid'];
            $yUsuaid = $_POST['xxUsuaid'];
            $xTarea = $_POST['xxTarea'];
            $xRuta = safe($_POST['xxRuta']);

            $xSQL = "SELECT tare_orden+1 AS Orden FROM `expert_tarea` WHERE empr_id=$yEmprid ORDER BY tare_orden DESC LIMIT 1";
            $all_orden = mysqli_query($con, $xSQL);
            foreach($all_orden as $orden){
                $yOrden = $orden['Orden'];
            }

            $xSQL ="INSERT INTO `expert_tarea` (empr_id,tare_nombre,tare_ruta,tare_estado,tare_orden,fechacreacion, ";
            $xSQL .= "usuariocreacion,terminalcreacion)";
            $xSQL .="VALUES ($yEmprid,'$xTarea','$xRuta','A',$yOrden,'{$xFecha}',$yUsuaid,'$xTerminal') ";
            if(mysqli_query($con, $xSQL)){
                $last_id = mysqli_insert_id($con);
            }
        }
    }

    //print json_encode($last_id, JSON_UNESCAPED_UNICODE); 
    echo $last_id;

?>