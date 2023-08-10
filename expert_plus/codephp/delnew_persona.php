<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

	require_once("../dbcon/config.php");
	require_once("../dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	
    
    $xresultado = "ERR";  

    if(isset($_POST['xxPerid']) and isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxEstado'])){
        if(isset($_POST['xxPerid']) <> '' and isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxEstado']) <> ''){  
            
            $xPerid = $_POST['xxPerid'];
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xEstado = $_POST['xxEstado'];

            if($xEstado == 'Activo'){
                $xSql = "UPDATE `expert_persona` SET pers_estado='A' WHERE pers_id=$xPerid AND pais_id=$xPaisid AND empr_id=$xEmprid ";
            }else if($xEstado == 'Inactivo'){
                $xSql = "UPDATE `expert_persona` SET pers_estado='I' WHERE  pers_id=$xPerid AND pais_id=$xPaisid AND empr_id=$xEmprid ";
            }

            if(mysqli_query($con, $xSql)){
                $xresultado="OK";
            }            
        }
    }

    echo $xresultado;
	
?>