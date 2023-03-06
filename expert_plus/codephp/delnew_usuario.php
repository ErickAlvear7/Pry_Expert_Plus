<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

	require_once("../dbcon/config.php");
	require_once("../dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	
    
    $xresultado = "ERR";  

    if(isset($_POST['xxUsuId']) and isset($_POST['xxEmpr']) and isset($_POST['xxEstado']) ){
        if(isset($_POST['xxUsuId']) <> '' and isset($_POST['xxEmpr']) <> '' and isset($_POST['xxEstado']) <> ''){  
            
            $yUsuId = $_POST['xxUsuId'];
            $yEmprId = $_POST['xxEmpr'];
            $xEstado = $_POST['xxEstado'];

            if($xEstado == 'Activo'){
                $xSql = "UPDATE `expert_usuarios` SET usua_estado='A' WHERE empr_id=$yEmprId AND usua_id=$yUsuId";
            }else if($xEstado == 'Inactivo'){
                $xSql = "UPDATE `expert_usuarios` SET usua_estado='I' WHERE empr_id=$yEmprId AND usua_id=$yUsuId";
            }

            if(mysqli_query($con, $xSql)){
                $xresultado="OK";
            }            
        }
    }

    echo $xresultado;
	
?>