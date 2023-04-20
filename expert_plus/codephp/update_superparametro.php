<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	


    $respuesta = "ERR";

    if(isset($_POST['xxPacaId']) and isset($_POST['xxParametro']) and isset($_POST['xxDescripcion'])){

        $xPacaid = $_POST['xxPacaId'];
        $xParametro = safe($_POST['xxParametro']);
        $xDesc = safe($_POST['xxDescripcion']);
      


        $xSQL = "UPDATE `expert_superparametro_cabecera` SET paca_nombre = '$xParametro', paca_descripcion = '$xDesc' ";
        $xSQL .= "WHERE paca_id = $xPacaid ";
        mysqli_query($con, $xSQL);
    
        $respuesta = "OK";
     
       
    }

    echo $respuesta;

?>