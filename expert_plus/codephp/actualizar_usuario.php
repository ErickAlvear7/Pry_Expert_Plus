<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    if(isset($_POST['xxUserid']) and isset($_POST['xxEmprid']) and isset($_POST['xxNombre']) and isset($_POST['xxApellido']) and isset($_POST['xxLogin'])
        and isset($_POST['xxPerfil']) and isset($_POST['xxPais']) ){
        if(isset($_POST['xxUserid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxNombre']) <> '' and isset($_POST['xxApellido']) <> ''){

            $yEmprid = $_POST['xxEmprid'];
            $yUserid = $_POST['xxUserid'];
            $xNombre = safe($_POST['xxNombre']);
            $xApellido = safe($_POST['xxApellido']);
            $xLogin = safe($_POST['xxLogin']); 
            $xPaisid =  $_POST['xxPais'];
            $xPerfilid =  $_POST['xxPerfil'];
            $xCaducaPass =  $_POST['xxCaducaPass'];
            $xFechaCaduca =  $_POST['xxFecha'];
            $xCambiarPass = $_POST['xxCambiarPass'];

            $xSQL = "UPDATE `expert_usuarios` SET perf_id=$xPerfilid,pais_id=$xPaisid,usua_nombres='$xNombre',usua_apellidos='$xApellido',";
            $xSQL .= "usua_login='$xLogin',usua_caducapass='$xCaducaPass',usua_fechacaduca='{$xFechaCaduca}',usua_cambiarpass='$xCambiarPass' WHERE usua_id=$yUserid ";
            mysqli_query($con, $xSQL);
        }
    }

    echo $yUserid;
?>