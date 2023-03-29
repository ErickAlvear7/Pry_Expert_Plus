<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

	require_once("../dbcon/config.php");
	require_once("../dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	
    
    $xrespuesta = "ERR";

    if(isset($_POST['xxPaisid']) and isset($_POST['xxIdPerfil']) and isset($_POST['xxIdMeta']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxIdPerfil']) <> '' and isset($_POST['xxIdMeta']) <> ''){    

            $xPaisid = $_POST['xxPaisid'];
            $xIdPerfil = $_POST['xxIdPerfil'];
            $xIdMeta = $_POST['xxIdMeta'];
            $xEmprid = $_POST['xxEmprid'];
            $xTipo = $_POST['xxTipo'];
            
            if($xTipo == 'Del'){
                $xSQL = "DELETE FROM `expert_perfil_menu_tarea` WHERE empr_id=$xEmprid AND meta_id=$xIdMeta AND perf_id=$xIdPerfil AND pais_id=$xPaisid";
            }elseif($xTipo == 'Add'){
                $xSQL = "INSERT INTO `expert_perfil_menu_tarea`(empr_id,meta_id,perf_id,pais_id,meta_estado) ";
                $xSQL .= "VALUES($xEmprid,$xIdMeta,$xIdPerfil,$xPaisid,'A')";    
            }elseif($xTipo == 'Activo'){
                $xSQL = "UPDATE `expert_perfil` SET perf_estado='A' WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND perf_id=$xIdPerfil";
            }elseif($xTipo == 'Inactivo'){
                $xSQL = "UPDATE `expert_perfil` SET perf_estado='I' WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND perf_id=$xIdPerfil";
            }

            if(mysqli_query($con, $xSQL)){
                $xrespuesta="OK";
            }
        }
    }
    
    echo $xrespuesta;
	
?>