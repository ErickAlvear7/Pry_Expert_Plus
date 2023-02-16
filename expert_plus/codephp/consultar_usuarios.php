<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    $xRow = 0;  

    if(isset($_POST['xxEmprid']) and isset($_POST['xxNombre']) ){
        if(isset($_POST['xxEmprid']) <> '' and isset($_POST['xxNombre']) <> ''){ 

            $yEmprid = $_POST['xxEmprid'];
            $xNombre = $_POST['xxNombre'];            

            $xSql = "SELECT * FROM `expert_usuarios` WHERE CONCAT(usua_nombres,' ',usua_apellidos) = '$xNombre' AND empr_id = $yEmprid ";
            $all_user = mysqli_query($con, $xSql) or die (error_log(mysqli_error($con), 3, $log_file));
            $xRow = mysqli_num_rows($all_user);
                


             echo $xRow;

        }


    }


?>