<?php
    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	    

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    if(isset($_POST['xxEmprid']) and isset($_POST['xxTareaId']) and isset($_POST['xxTarea']) and isset($_POST['xxPagina']) and isset($_POST['xxRuta']) ){
        if(isset($_POST['xxEmprid']) <> '' and isset($_POST['xxTareaId']) <> '' and isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxTarea']) <> '' and isset($_POST['xxPagina']) <> '' and isset($_POST['xxRuta']) <> ''){

            $xEmprid = $_POST['xxEmprid'];
            $xTareaid = $_POST['xxTareaId'];
            $xTarea = safe($_POST['xxTarea']);
            $xPagina = safe($_POST['xxPagina']);
            $xRuta = safe($_POST['xxRuta']);
            $xTitulo = safe($_POST['xxTitulo']);
            $xDescripcion = safe($_POST['xxDescripcion']);

            $xSQL ="UPDATE `expert_tarea` SET tare_nombre='$xTarea',tare_pagina='$xPagina',tare_ruta='$xRuta',tare_titulo='$xTitulo',tare_descripcion='$xDescripcion' WHERE tare_id=$xTareaid AND empr_id=$xEmprid";
            mysqli_query($con, $xSQL);

            //print json_encode($xTareaid, JSON_UNESCAPED_UNICODE);
            echo $xTareaid;
        }
    }
    
?>