<?php
    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    //file_put_contents('log_seguimiento.txt', $xSql . "\n\n", FILE_APPEND);    
    
    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	 

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();
    $last_id = 0;

    if(isset($_POST['xxPaisid']) and isset($_POST['xxUsuaid']) and isset($_POST['xxEmprid']) and isset($_POST['xxNombre']) and isset($_POST['xxApellido']) and isset($_POST['xxLogin']) and isset($_POST['xxPerfilid']) ){
        if(isset($_POST['xxEmprid']) <> '' and isset($_POST['xxNombre']) <> '' and isset($_POST['xxApellido']) <> ''){

            $xEmprid = $_POST['xxEmprid'];
            $xUsuaid = $_POST['xxUsuaid'];
            $xNombre = safe($_POST['xxNombre']);
            $xApellido = safe($_POST['xxApellido']);
            $xLogin = safe($_POST['xxLogin']); 
            $xPasword = safe($_POST['xxPassword']);
            $xPass = md5('$xPasword'); 
            $xPaisid =  $_POST['xxPaisid'];
            $xPerfilid =  $_POST['xxPerfilid'];
            $xCaducaPass =  $_POST['xxCaducaPass'];
            $xFechaCaduca =  $_POST['xxFecha'];
            $xCambiarPass = $_POST['xxCambiarPass'];

            $xSQL ="INSERT INTO `expert_usuarios` (pais_id,empr_id,perf_id,usua_nombres,usua_apellidos,usua_login,usua_password,usua_estado, ";
            $xSQL .= "usua_contador,usua_caducapass,usua_fechacaduca,usua_cambiarpass,usua_estadologin,usua_terminallogin,usua_usuariocreacion, ";
            $xSQL .= "usua_fechacreacion,usua_terminalcreacion)";
            $xSQL .="VALUES ($xPaisid,$xEmprid,$xPerfilid,'$xNombre','$xApellido',LOWER('$xLogin'),'$xPass','A',0,'$xCaducaPass','{$xFechaCaduca}', ";
            $xSQL .= "'$xCambiarPass', '','',$xUsuaid,'{$xFecha}','$xTerminal') ";
            
            if(mysqli_query($con, $xSQL)){

                $last_id = mysqli_insert_id($con);
                
                $xSQL = "INSERT INTO `expert_parametro_paginas` (pais_id,empr_id,usua_id,index_menu,index_content,estado) ";
                $xSQL .= "VALUES($xPaisid,$xEmprid,$last_id,'dark','dark','A')";
                mysqli_query($con, $xSQL);                    
                
            }
        }
    }

    echo $last_id;
?>