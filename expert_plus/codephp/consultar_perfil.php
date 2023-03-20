<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

	require_once("../dbcon/config.php");
	require_once("../dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	
    
    $log_file = "err_consulta";
    $xRow = 0;  

    if(isset($_POST['xxPaisid']) and isset($_POST['xxPerfil']) and isset($_POST['xxEmprid']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxPerfil']) <> '' and isset($_POST['xxEmprid']) <> ''){    

            $xPaisid = $_POST['xxPaisid'];
            $yEmprid = $_POST['xxEmprid'];
            $xPerfil = $_POST['xxPerfil'];

            $xSql = "SELECT * FROM `expert_perfil` WHERE pais_id=$xPaisid AND empr_id=$yEmprid AND perf_descripcion='$xPerfil' ";
            $all_perfiles = mysqli_query($con, $xSql) or die (error_log(mysqli_error($con), 3, $log_file));
            $xRow = mysqli_num_rows($all_perfiles);
           
        }
    }
    
	echo $xRow;
	
?>	