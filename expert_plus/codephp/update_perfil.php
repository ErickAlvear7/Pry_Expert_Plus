<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

	require_once("../dbcon/config.php");
	require_once("../dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	
    
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();
    $data = "ERROR";

    if(isset($_POST['xxPerfil']) and isset($_POST['xxEmprid']) and isset($_POST['xxUserid']) and isset($_POST['xxIdPerfil']) ){
        if(isset($_POST['xxPerfil']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxUserid']) <> '' and isset($_POST['xxIdPerfil']) <> ''){    

            
            $yEmprid = $_POST['xxEmprid'];
            $xPerfil = safe($_POST['xxPerfil']);
            $xIdPerfil =  $_POST['xxIdPerfil'];
            $yUserid = $_POST['xxUserid']; 
            $xObservacion = trim(mb_strtoupper(safe($_POST['xxObservacion']), 'UTF-8'));

            $xSql = "UPDATE `expert_perfil` SET perf_descripcion='$xPerfil',perf_observacion='$xObservacion' ";
            $xSql .= "WHERE empr_id=$yEmprid AND perf_id=$xIdPerfil";
            if(mysqli_query($con, $xSql)){
                $data = "OK";
            }else{
                $data = "ERROR";
            }

            print json_encode($data, JSON_UNESCAPED_UNICODE);
        }
    }
	
?>	