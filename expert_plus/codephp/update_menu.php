<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

	require_once("../dbcon/config.php");
	require_once("../dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	
    
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();
    $data = "ERROR";

    if(isset($_POST['xxMenu']) and isset($_POST['xxEmprid']) and isset($_POST['xxIdMenu'])){
        if(isset($_POST['xxMenu']) <> '' and isset($_POST['xxIdMenu']) <> ''){    

            $yMenuId = $_POST['xxIdMenu']; 
            $yEmprid = $_POST['xxEmprid'];
            $yUsario = $_POST['xxUsuario'];
            $xMenu = safe($_POST['xxMenu']);
            $xObservacion = safe($_POST['xxObserva']);

            $xSql = "UPDATE `expert_menu` SET menu_descripcion='$xMenu',menu_observacion='$xObservacion',fechacreacion='{$xFecha}', ";
            $xSql .= "usuariocreacion=$yUsario,terminalcreacion='$xTerminal' ";
            $xSql .= "WHERE empr_id=$yEmprid AND menu_id=$yMenuId";
            if(mysqli_query($con, $xSql)){
                $data = "OK";
            }else{
                $data = "ERROR";
            }


          

            print json_encode($data, JSON_UNESCAPED_UNICODE);
        }
    }
	
?>	