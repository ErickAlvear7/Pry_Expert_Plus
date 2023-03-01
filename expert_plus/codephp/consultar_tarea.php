<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta_tarea";
    $xRow = 0;  

    if(isset($_POST['xxEmprid']) and isset($_POST['xxTarea']) ){
        if(isset($_POST['xxEmprid']) <> '' and isset($_POST['xxTarea']) <> ''){ 

            $yEmprid = $_POST['xxEmprid'];
            $xTarea = safe($_POST['xxTarea']);            

            $xSql = "SELECT * FROM `expert_tarea` WHERE tare_nombre='$xTarea' AND empr_id=$yEmprid ";
            $all_datos = mysqli_query($con, $xSql) or die (error_log(mysqli_error($con), 3, $log_file));
            $xRow = mysqli_num_rows($all_datos);
        }
    }

    echo $xRow;

?>