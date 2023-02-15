<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    $xRow = 0;  

    if(isset($_POST['xxMenu']) and isset($_POST['xxEmprid']) ){
        if(isset($_POST['xxMenu']) <> '' and isset($_POST['xxEmprid']) <> ''){ 
                $yEmprid = $_POST['xxEmprid'];
                $xMenu = $_POST['xxMenu'];            

                $xSql = "SELECT * FROM `expert_menu` men WHERE men.empr_id=$yEmprid AND men.menu_descripcion='$xMenu'";
                $all_menus = mysqli_query($con, $xSql) or die (error_log(mysqli_error($con), 3, $log_file));
                $xRow = mysqli_num_rows($all_menus);

                // if(mysqli_num_rows($all_perfiles)>0){
                //     $xRow=1;
                // }

                echo $xRow;

        }


    }


?>