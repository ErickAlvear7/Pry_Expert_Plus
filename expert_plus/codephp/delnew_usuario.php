<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

	require_once("../dbcon/config.php");
	require_once("../dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	
    
    $xresultado = "ERR";  

    if(isset($_POST['xxUsuaid']) and isset($_POST['xxEmprid']) and isset($_POST['xxEstado']) ){
        if(isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxEstado']) <> ''){  
            
            $xUsuaid = $_POST['xxUsuaid'];
            $xEmprid = $_POST['xxEmprid'];
            $xEstado = $_POST['xxEstado'];

            if($xEstado == 'ACTIVO'){
                $xSql = "UPDATE `expert_usuarios` SET usua_estado='A' WHERE empr_id=$xEmprid AND usua_id=$xUsuaid";
            }else if($xEstado == 'INACTIVO'){
                $xSql = "UPDATE `expert_usuarios` SET usua_estado='I' WHERE empr_id=$xEmprid AND usua_id=$xUsuaid";
            }

            if(mysqli_query($con, $xSql)){
                $xresultado="OK";
            }            
        }
    }

    echo $xresultado;
	
?>