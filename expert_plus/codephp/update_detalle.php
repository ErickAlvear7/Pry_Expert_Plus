<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	


    $respuesta = "ERR";

    if(isset($_POST['xxPacaId']) and isset($_POST['xxPadeId']) and isset($_POST['xxDetalle']) and isset($_POST['xxValorV'])
          and isset($_POST['xxValorI'])){

        $xPacaid = $_POST['xxPacaId'];
        $xPadeid = $_POST['xxPadeId'];
        $xDetalle = safe($_POST['xxDetalle']);
        $xValorV = safe($_POST['xxValorV']);
        $xValorI = safe($_POST['xxValorI']);

        if($xValorV != ''){
            $xSQL = "UPDATE `expert_parametro_detalle` SET pade_nombre='$xDetalle',pade_valorV='$xValorV' ";
            $xSQL .= "WHERE paca_id = $xPacaid AND pade_id = $xPadeid ";
            mysqli_query($con, $xSQL);
        }

        if($xValorI != 0){
            $xSQL = "UPDATE `expert_parametro_detalle` SET pade_nombre='$xDetalle',pade_valorI=$xValorI ";
            $xSQL .= "WHERE paca_id = $xPacaid AND pade_id = $xPadeid ";
            mysqli_query($con, $xSQL);

        }
    
        $respuesta = "OK";
    }

    echo $respuesta;

?>