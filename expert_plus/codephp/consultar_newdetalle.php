<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    $xRow = 0;  

    if(isset($_POST['xxPacaid']) and isset($_POST['xxDetalle']) and isset($_POST['xxValorV'])){
        if(isset($_POST['xxPacaid']) <> '' and isset($_POST['xxDetalle']) <> '' and isset($_POST['xxValorV']) <> ''){ 

            $xPacaid = $_POST['xxPacaid'];
            $xDetalle = trim(mb_strtoupper(safe($_POST['xxDetalle']))); 
            $xValorV = trim(mb_strtoupper(safe($_POST['xxValorV'])));

            $xSQL = "SELECT * FROM `expert_parametro_detalle` WHERE paca_id=$xPacaid AND pade_nombre='$xDetalle' OR pade_valorV='$xValorV' ";
            $all_det = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
            $xRow = mysqli_num_rows($all_det);
        }           
    }
    
    echo $xRow;

?>