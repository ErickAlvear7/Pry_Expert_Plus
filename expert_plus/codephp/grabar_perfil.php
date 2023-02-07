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

    if(isset($_POST['xxPerfil']) and isset($_POST['xxEmprid']) and isset($_POST['xxResult']) and isset($_POST['xxObservacion']) ){
        if(isset($_POST['xxPerfil']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxResult']) <> '' and isset($_POST['xxObservacion']) <> ''){    

            
            $yEmprid = $_POST['xxEmprid'];
            $xPerfil = safe($_POST['xxPerfil']); 
            $yUserid = $_POST['xxUserid']; 
            $xResult = $_POST['xxResult']; 
            $xObservacion = trim(mb_strtoupper(safe($_POST['xxObservacion']), 'UTF-8'));
            $xEstado =  $_POST['xxEstado'];

            $xSql = "INSERT INTO `expert_perfil`(empr_id,perf_descripcion,perf_observacion,perf_estado,perf_fechacreacion,perf_usuariocreacion,perf_terminalcreacion) ";
            $xSql .= "VALUES($yEmprid,'$xPerfil','$xObservacion','$xEstado','{$xFecha}',$yUserid,'$xTerminal')";
            if(mysqli_query($con, $xSql)){

                $id = mysqli_insert_id($con);

                foreach($xResult as $drfila){
                    $xSql = "INSERT INTO `expert_perfil_menu_tarea`(empr_id,meta_id,perf_id,meta_estado) ";
                    $xSql .= "VALUES($yEmprid,$drfila,$id,'A')";
                    mysqli_query($con, $xSql);
                }

                $data = "OK";
            }

            print json_encode($data, JSON_UNESCAPED_UNICODE);
            //echo $data;
        }
    }
	
?>	