<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

	require_once("../dbcon/config.php");
	require_once("../dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	
    
    $xresultado = "ERR";  

    if(isset($_POST['xxPadeid']) and isset($_POST['xxEstado'])){
        if(isset($_POST['xxPadeid']) <> '' and isset($_POST['xxEstado']) <> ''){  
            
            $xPadeid = $_POST['xxPadeid'];
            $xEstado = $_POST['xxEstado'];

            if($xEstado == 'ACTIVO'){
                $xSql = "UPDATE `expert_parametro_detalle` SET pade_estado='A' WHERE pade_id=$xPadeid ";
            }else if($xEstado == 'INACTIVO'){
                $xSql = "UPDATE `expert_parametro_detalle` SET pade_estado='I' WHERE  pade_id=$xPadeid ";
            }

            if(mysqli_query($con, $xSql)){
                $xresultado="OK";
            }            
        }
    }

    echo $xresultado;
	
?>