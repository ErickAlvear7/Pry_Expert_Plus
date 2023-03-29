<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    //file_put_contents('log_seguimiento_grabarperfil.txt', 'Ingreso a Grabar' . "\n\n", FILE_APPEND); 

	require_once("../dbcon/config.php");
	require_once("../dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	
    
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();
    $respuesta = "ERR";

    if(isset($_POST['xxPaisid']) and isset($_POST['xxPerfil']) and isset($_POST['xxEmprid']) and isset($_POST['xxResult']) and isset($_POST['xxObservacion']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxPerfil']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxResult']) <> '' and isset($_POST['xxObservacion']) <> ''){
            
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xPerfil = safe($_POST['xxPerfil']); 
            $xUsuaid = $_POST['xxUsuaid']; 
            $xResult = $_POST['xxResult']; 
            $xObservacion = trim(safe($_POST['xxObservacion']), 'UTF-8');
            $xEstado =  $_POST['xxEstado'];

            $xSQL = "INSERT INTO `expert_perfil`(pais_id,empr_id,perf_descripcion,perf_observacion,perf_estado,perf_fechacreacion,perf_usuariocreacion,perf_terminalcreacion) ";
            $xSQL .= "VALUES($xPaisid,$xEmprid,'$xPerfil','$xObservacion','$xEstado','{$xFecha}',$xUsuaid,'$xTerminal')";
            if(mysqli_query($con, $xSQL)){

                $id = mysqli_insert_id($con);

                foreach($xResult as $drfila){
                    $xSQL = "INSERT INTO `expert_perfil_menu_tarea`(empr_id,meta_id,perf_id,pais_id,meta_estado) ";
                    $xSQL .= "VALUES($xEmprid,$drfila,$id,$xPaisid,'A')";
                    mysqli_query($con, $xSQL);
                }

                $respuesta = "OK";
            }

            //print json_encode($data, JSON_UNESCAPED_UNICODE);
        }
    }
    
    echo $respuesta;
	
?>	