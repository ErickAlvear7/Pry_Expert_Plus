<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

	require_once("../dbcon/config.php");
	require_once("../dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	
    
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time()); 
    $xTerminal = gethostname();
    $xData = "";  

    if(isset($_POST['xxIdTarea']) and isset($_POST['xxIdMenu']) ){
        if(isset($_POST['xxIdTarea']) <> '' and isset($_POST['xxIdMenu']) <> ''){    

            $xIdTarea = $_POST['xxIdTarea'];
            $xIdMenu = $_POST['xxIdMenu'];
            $yEmprid = $_POST['xxEmprid'];
            $xTipo = $_POST['xxTipo'];
            
            if($xTipo == 'Del'){
                $xSql = "DELETE FROM `expert_menu_tarea` WHERE empr_id=$yEmprid AND menu_id=$xIdMenu AND tare_id=$xIdTarea";
            }elseif($xTipo == 'Add'){
                $xSql = "INSERT INTO `expert_menu_tarea`(empr_id,menu_id,tare_id,meta_orden) ";
                $xSql .= "VALUES($yEmprid,$xIdMenu,$xIdTarea,5)";    
            }elseif($xTipo == 'Activo'){
                $xSql = "UPDATE `expert_perfil` SET perf_estado='A' WHERE empr_id=$yEmprid AND perf_id=$xIdPerfil";
            }elseif($xTipo == 'Inactivo'){
                $xSql = "UPDATE `expert_perfil` SET perf_estado='I' WHERE empr_id=$yEmprid AND perf_id=$xIdPerfil";
            }

            if(mysqli_query($con, $xSql)){
                $xData="OK";
            }else{
                $xData="ERR";
            }

            echo $xData;
        }
    }
	
?>