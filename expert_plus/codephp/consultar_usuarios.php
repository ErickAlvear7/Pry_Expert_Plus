<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    $xRow = 0;  

    if(isset($_POST['xxEmprid']) and isset($_POST['xxLogin']) ){
        if(isset($_POST['xxEmprid']) <> '' and isset($_POST['xxLogin']) <> ''){ 

            $yEmprid = $_POST['xxEmprid'];
            $xLogin = $_POST['xxLogin'];            

            $xSql = "SELECT * FROM `expert_usuarios` WHERE usua_login='$xLogin' AND empr_id=$yEmprid ";
            $all_user = mysqli_query($con, $xSql) or die (error_log(mysqli_error($con), 3, $log_file));
            $xRow = mysqli_num_rows($all_user);
            
        }
    }
    
    echo $xRow;

?>