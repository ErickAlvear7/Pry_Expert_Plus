<?php
    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	 

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();
    $last_id = 0;
    $yOrden = 0;

    if(isset($_POST['xxUsuaid']) and isset($_POST['xxEmprid']) and isset($_POST['xxNombre']) and isset($_POST['xxApellido']) and isset($_POST['xxLogin'])
        and isset($_POST['xxPerfil']) and isset($_POST['xxPais']) ){
            if(isset($_POST['xxEmprid']) <> '' and isset($_POST['xxNombre']) <> '' and isset($_POST['xxApellido']) <> ''){

                $yEmprid = $_POST['xxEmprid'];
                $yUserid = $_POST['xxUsuaid'];
                $xNombre = safe($_POST['xxNombre']);
                $xApellido = safe($_POST['xxApellido']);
                $xLogin = safe($_POST['xxLogin']); 
                $xPasword = safe($_POST['xxPassword']);
                $xPass = md5('$xPasword'); 
                $xPaisid =  $_POST['xxPais'];
                $xPerfilid =  $_POST['xxPerfil'];
                $xCaducaPass =  $_POST['xxCaducaPass'];
                $xFechaCaduca =  $_POST['xxFecha'];
                $xCambiarPass = $_POST['xxCambiarPass'];

                $xSQL = "INSERT INTO `expert_parametro_paginas` (empr_id,usua_id,index_menu,index_content,estado) ";
                $xSQL .= "VALUES($yEmprid,$yUserid,'dark','dark','A')";
                mysqli_query($con, $xSQL);

                $xSQL ="INSERT INTO `expert_usuarios` (perf_id,pais_id,empr_id,usua_nombres,usua_apellidos,usua_login,usua_password,usua_estado, ";
                $xSQL .= "usua_contador,usua_caducapass,usua_fechacaduca,usua_cambiarpass,usua_estadologin,usua_terminallogin, ";
                $xSQL .= "usua_fechacreacion,usua_terminalcreacion)";
                $xSQL .="VALUES ($xPerfilid,$xPaisid,$yEmprid,'$xNombre','$xApellido','$xLogin','$xPass','A',0,'$xCaducaPass','{$xFechaCaduca}', ";
                $xSQL .= "'$xCambiarPass', 'NO','$xTerminal','{$xFecha}','$xTerminal') ";

                if(mysqli_query($con, $xSQL)){

                    $last_id = mysqli_insert_id($con);
                }

        }
    }

    echo $last_id;
?>