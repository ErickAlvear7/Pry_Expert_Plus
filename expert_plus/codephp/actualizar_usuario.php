<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    //file_put_contents('log_seguimiento.txt', $xSQL . "\n\n", FILE_APPEND);    

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    if(isset($_POST['xxUsuaid']) and isset($_POST['xxEmprid']) and isset($_POST['xxNombre']) and isset($_POST['xxApellido']) and isset($_POST['xxLogin']) and isset($_POST['xxPerfilid']) and isset($_POST['xxPaisid']) ){
        if(isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxNombre']) <> '' and isset($_POST['xxApellido']) <> ''){

            $xEmprid = $_POST['xxEmprid'];
            $xUsuaid = $_POST['xxUsuaid'];
            $xNombre = safe($_POST['xxNombre']);
            $xApellido = safe($_POST['xxApellido']);
            $xLogin = safe($_POST['xxLogin']); 
            $xPaisid =  $_POST['xxPaisid'];
            $xPerfilid =  $_POST['xxPerfilid'];
            $xCaducaPass =  $_POST['xxCaducaPass'];
            $xFechaCaduca =  $_POST['xxFecha'];
            $xCambiarPass = $_POST['xxCambiarPass'];

            $xSQL = "UPDATE `expert_usuarios` SET perf_id=$xPerfilid,pais_id=$xPaisid,usua_nombres='$xNombre',usua_apellidos='$xApellido',";
            $xSQL .= "usua_login=LOWER('$xLogin'),usua_caducapass='$xCaducaPass',usua_fechacaduca='{$xFechaCaduca}',usua_cambiarpass='$xCambiarPass' WHERE usua_id=$xUsuaid ";
            mysqli_query($con, $xSQL);
            
        }
    }

    echo $xUsuaid;
?>