<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    $xRow = 0;  

    if(isset($_POST['xxParametro']) ){
        if(isset($_POST['xxParametro']) <> ''){ 

            $xParam = $_POST['xxParametro'];          

            $xSQL = "SELECT * FROM `expert_superparametro_cabecera` paca WHERE paca.paca_nombre='$xParam' ";
            $all_param = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
            $xRow = mysqli_num_rows($all_param);
        }
    }
    
    echo $xRow;

?>