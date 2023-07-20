<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    $xRow = 0;  

    if(isset($_POST['xxDocumento'])){
        if(isset($_POST['xxDocumento']) <> ''){ 
            
            $xDocumento = $_POST['xxDocumento'];

            $xSQL = "SELECT * FROM `expert_persona` WHERE pers_numerodocumento = $xDocumento ";
            $all_persona = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
            $xRow = mysqli_num_rows($all_persona);   
        }
    }
    
    echo $xRow;

?>