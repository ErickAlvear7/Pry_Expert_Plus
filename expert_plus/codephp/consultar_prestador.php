<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    $xRow = 0;  

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxProvid']) and isset($_POST['xxPrestador']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxProvid']) <> '' and isset($_POST['xxPrestador']) <> ''){ 
            
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xProvid = $_POST['xxProvid'];
            $xPrestador = trim(mb_strtoupper(safe($_POST['xxPrestador'])));

            $xSQL = "SELECT * FROM `expert_prestadora` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND prov_id=$xProvid AND pres_nombre='$xPrestador' ";
            $all_datos = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
            $xRow = mysqli_num_rows($all_datos);
            
        }
    }
    
    echo $xRow;

?>