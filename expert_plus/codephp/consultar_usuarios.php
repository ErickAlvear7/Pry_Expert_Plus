<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    $xRow = 0;  

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxLogin']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxLogin']) <> ''){ 
            
            $xPaisid=$_POST['xxPaisid'];
            $xEmprid=$_POST['xxEmprid'];
            $xLogin=safe($_POST['xxLogin']);            

            $xSQL = "SELECT * FROM `expert_usuarios` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND usua_login='$xLogin'";
            $all_user = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
            $xRow = mysqli_num_rows($all_user);
            
        }
    }
    
    echo $xRow;

?>