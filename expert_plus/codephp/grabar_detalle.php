<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	


    $resultado = "ERR";

    if(isset($_POST['xxPacaId']) and isset($_POST['xxDetalle']) and isset($_POST['xxValorV']) and isset($_POST['xxValorI'])
        and isset($_POST['xxEstado']) and isset($_POST['xxOrden'])){

        $xPacaid = $_POST['xxPacaId'];
        $xDetalle = safe($_POST['xxDetalle']);
        $xValorV = safe($_POST['xxValorV']);
        $xValorI = safe($_POST['xxValorI']);
        $xEstado = safe($_POST['xxEstado']);
        $xOrden = $_POST['xxOrden'];

        $xSQL = "INSERT INTO `expert_parametro_detalle` (paca_id,pade_orden,pade_nombre, ";
        $xSQL .= "pade_valorV,pade_valorI,pade_estado) ";
        $xSQL .= "VALUES ($xPacaid,$xOrden,'$xDetalle','$xValorV','$xValorI','$xEstado')";

        if(mysqli_query($con, $xSQL)){

            $last_id = mysqli_insert_id($con);
            
        }
    }

    echo $last_id;

?>