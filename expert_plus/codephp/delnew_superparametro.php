<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

	require_once("../dbcon/config.php");
	require_once("../dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	
    
    $xresultado = "ERR";  

    if(isset($_POST['xxPacaid']) and isset($_POST['xxEstado'])){
        if(isset($_POST['xxPacaid']) <> '' and isset($_POST['xxEstado']) <> ''){  
            
            $xPacaid = $_POST['xxPacaid'];
            $xEstado = $_POST['xxEstado'];

            if($xEstado == 'Activo'){
                $xSql = "UPDATE `expert_superparametro_cabecera` SET paca_estado='A' WHERE paca_id=$xPacaid ";
            }else if($xEstado == 'Inactivo'){
                $xSql = "UPDATE `expert_superparametro_cabecera` SET paca_estado='I' WHERE  paca_id=$xPacaid ";
            }

            if(mysqli_query($con, $xSql)){
                $xresultado="OK";
            }            
        }
    }

    echo $xresultado;
	
?>