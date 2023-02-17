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

    if(isset($_POST['xxMenuId'])  and isset($_POST['xxEmpr']) and isset($_POST['xxTipo'])){
        if(isset($_POST['xxMenuId']) <> '' and isset($_POST['xxEmpr']) <> '' and isset($_POST['xxTipo']) <> ''){    

            $xIdMenu = $_POST['xxMenuId'];
            $yEmprid = $_POST['xxEmpr'];
            $xTipo = $_POST['xxTipo'];

            if($xTipo == 'Activo'){
                $xSql = "UPDATE `expert_menu` SET menu_estado='A' WHERE empr_id=$yEmprid AND menu_id=$xIdMenu";
            }else if($xTipo == 'Inactivo'){
                $xSql = "UPDATE `expert_menu` SET menu_estado='I' WHERE empr_id=$yEmprid AND menu_id=$xIdMenu";
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