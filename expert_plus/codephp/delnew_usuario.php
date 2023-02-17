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

    if(isset($_POST['xxUsuId']) and isset($_POST['xxEmpr']) and isset($_POST['xxTipo']) ){
        if(isset($_POST['xxUsuId']) <> '' and isset($_POST['xxEmpr']) <> '' and isset($_POST['xxTipo']) <> ''){  
            
            $yUsuId = $_POST['xxUsuId'];
            $yEmprId = $_POST['xxEmpr'];
            $xTipo = $_POST['xxTipo'];

            if($xTipo == 'Activo'){
                $xSql = "UPDATE `expert_usuarios` SET usua_estado='A' WHERE empr_id=$yEmprId AND usua_id=$yUsuId";
            }else if($xTipo == 'Inactivo'){
                $xSql = "UPDATE `expert_usuarios` SET usua_estado='I' WHERE empr_id=$yEmprId AND usua_id=$yUsuId";
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