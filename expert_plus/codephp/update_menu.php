<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

	require_once("../dbcon/config.php");
	require_once("../dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	
    
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $respuesta = "ERR";

    if(isset($_POST['xxMenu']) and isset($_POST['xxEmprid']) and isset($_POST['xxIdMenu'])){
        if(isset($_POST['xxMenu']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxIdMenu']) <> ''){    

            $yMenuId = $_POST['xxIdMenu']; 
            $yEmprid = $_POST['xxEmprid'];
            $xMenu = safe($_POST['xxMenu']);
            $xObservacion = safe($_POST['xxObserva']);

            $xSql = "UPDATE `expert_menu` SET menu_descripcion='$xMenu',menu_observacion='$xObservacion' ";
            $xSql .= "WHERE empr_id=$yEmprid AND menu_id=$yMenuId";
            mysqli_query($con, $xSql);

            $respuesta = "OK";
        }
    }

    //print json_encode($respuesta, JSON_UNESCAPED_UNICODE);
    echo $respuesta;
	
?>	