<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta_tarea";
    $xRow = 0;  

    if(isset($_POST['xxEmprid']) and isset($_POST['xxTarea']) and isset($_POST['xxPagina']) ){
        if(isset($_POST['xxEmprid']) <> '' and isset($_POST['xxTarea']) <> '' and isset($_POST['xxPagina']) <> ''){ 

            $xEmprid = $_POST['xxEmprid'];
            $xTarea = safe($_POST['xxTarea']);
            $xPagina = safe($_POST['xxPagina']);

            $xSql = "SELECT * FROM `expert_tarea` WHERE empr_id=$xEmprid AND tare_nombre='$xTarea' AND tare_pagina='$xPagina' ";
            $all_datos = mysqli_query($con, $xSql) or die (error_log(mysqli_error($con), 3, $log_file));
            $xRow = mysqli_num_rows($all_datos);
        }
    }

    echo $xRow;

?>