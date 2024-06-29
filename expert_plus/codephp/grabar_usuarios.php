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

    if(isset($_POST['xxPaisid']) and isset($_POST['xxUsuaid']) and isset($_POST['xxEmprid'])){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxEmprid']) <> ''){

            $xPaisid =  $_POST['xxPaisid'];
            $xUsuaid = $_POST['xxUsuaid'];
            $xEmprid = $_POST['xxEmprid'];
            $xNombre = safe($_POST['xxNombre']);
            $xApellido = safe($_POST['xxApellido']);
            $xLogin = safe($_POST['xxLogin']); 
            $xPasword = safe($_POST['xxPassword']);
            $xPass = md5($xPasword); 
            $xPerfilid =  $_POST['xxPerfilid'];
            $xCaducaPass =  $_POST['xxCaducaPass'];
            $xFechaCaduca =  $_POST['xxFecha'];
            $xCambiarPass = $_POST['xxCambiarPass'];
            $xCambiarAvatar = $_POST['xxCambiarAvatar'];

            $xFile = (isset($_FILES['xxFile']["name"])) ? $_FILES['xxFile']["name"] : '';
            $xPath = "../assets/images/users/";

            $xFechafile = new DateTime();
            $xNombreFile = ($xFile != "") ? $xFechafile->getTimestamp() . "_" . $_FILES["xxFile"]["name"] : "";            

            if($xFile != ''){
                $xTmpFile = $_FILES["xxFile"]["tmp_name"];

                if($xTmpFile != ""){
                    move_uploaded_file($xTmpFile,$xPath.$xNombreFile);
                }
            }
                                 
            $xSQL ="INSERT INTO `expert_usuarios` (pais_id,empr_id,perf_id,usua_nombres,usua_apellidos,usua_login,usua_password,";
            $xSQL .= "usua_caducapass,usua_fechacaduca,usua_cambiarpass,usua_avatarlogin,usua_usuariocreacion,";
            $xSQL .= "usua_fechacreacion,usua_terminalcreacion) ";
            $xSQL .="VALUES ($xPaisid,$xEmprid,$xPerfilid,'$xNombre','$xApellido',LOWER('$xLogin'),'$xPass','$xCaducaPass','{$xFechaCaduca}', ";
            $xSQL .= "'$xCambiarPass', '$xNombreFile',$xUsuaid,'{$xFecha}','$xTerminal') ";
            
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