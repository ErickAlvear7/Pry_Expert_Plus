<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $respuesta = "ERR";

    if(isset($_POST['xxUsuaid']) and isset($_POST['xxEmprid'])){
        if(isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxEmprid']) <> ''){
            
            $xUsuaid = $_POST['xxUsuaid'];
            $xEmprid = $_POST['xxEmprid'];
    
            $xSQL = "UPDATE `expert_usuarios` SET usua_password=MD5('12345') ";
            $xSQL .= "WHERE usua_id=$xUsuaid AND empr_id=$xEmprid ";
    
            if(mysqli_query($con, $xSQL)){
                $respuesta = "OK";
            }
        }


    }

    echo $respuesta;

?>