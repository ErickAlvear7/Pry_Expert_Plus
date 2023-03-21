<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

	require_once("../dbcon/config.php");
	require_once("../dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	
    
    $respuesta = "ERR";

    if(isset($_POST['xxPaisid']) and isset($_POST['xxPerfil']) and isset($_POST['xxEmprid']) and isset($_POST['xxIdPerfil']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxPerfil']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxIdPerfil']) <> ''){    

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xPerfil = safe($_POST['xxPerfil']);
            $xIdPerfil =  $_POST['xxIdPerfil'];
            $xObservacion = trim(safe($_POST['xxObservacion']), 'UTF-8');

            $xSQL = "UPDATE `expert_perfil` SET pais_id=$xPaisid,perf_descripcion='$xPerfil',perf_observacion='$xObservacion' ";
            $xSQL .= "WHERE empr_id=$xEmprid AND perf_id=$xIdPerfil";
            
            if(mysqli_query($con, $xSQL)){
                $respuesta = "OK";
            }
        }
    }
    
    echo $respuesta;
	
?>	