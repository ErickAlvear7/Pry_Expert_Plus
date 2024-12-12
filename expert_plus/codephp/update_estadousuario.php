<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

	require_once("../dbcon/config.php");
	require_once("../dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	
    
    $xRespuesta = "ERR";

    if(isset($_POST['xxUsuaid']) and isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxEstado'])){
        if(isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxEstado']) <> ''){  
            
            $xUsuaid = $_POST['xxUsuaid'];
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xEstado = $_POST['xxEstado'];

            $xSQL = "UPDATE `expert_usuarios` SET usua_estado='$xEstado' ";
            $xSQL .= " WHERE usua_id=$xUsuaid AND pais_id=$xPaisid AND empr_id=$xEmprid";
            mysqli_query($con, $xSQL);

            $xRespuesta = "OK";    
        }
    }

    echo $xRespuesta;
	
?>