<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

	require_once("../dbcon/config.php");
	require_once("../dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	
    
    $xData = "ERR";  

    if(isset($_POST['xxMenuId'])  and isset($_POST['xxEmprid']) and isset($_POST['xxEstado'])){
        if(isset($_POST['xxMenuId']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxEstado']) <> ''){    

            $xIdMenu = $_POST['xxMenuId'];
            $xEmprid = $_POST['xxEmprid'];
            $xEstado = $_POST['xxEstado'];

            if($xEstado == 'Activo'){
                $xSql = "UPDATE `expert_menu` SET menu_estado='A' WHERE empr_id=$xEmprid AND menu_id=$xIdMenu";
            }else if($xEstado == 'Inactivo'){
                $xSql = "UPDATE `expert_menu` SET menu_estado='I' WHERE empr_id=$xEmprid AND menu_id=$xIdMenu";
            }

            if(mysqli_query($con, $xSql)){
                $xData="OK";
            }            
        }
    }

    echo $xData;
	
?>