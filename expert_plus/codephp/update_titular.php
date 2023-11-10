<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    //file_put_contents('log_seguimiento.txt', $xSQL . "\n\n", FILE_APPEND);    

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();   

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	
    $xRespuesta = "ERR";

    if(isset($_POST['xxPersid']) and isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxUsuaid']) and isset($_POST['xxSelecc'])){
        if(isset($_POST['xxPersid']) <> '' and isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxSelecc']) <> ''){
            
            $xPersid = $_POST['xxPersid'];
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xUsuaid = $_POST['xxUsuaid'];
            $xNombre = trim(mb_strtoupper(safe($_POST['xxNombre'])));
            $xApellido = trim(mb_strtoupper(safe($_POST['xxApellido'])));
            $xDireccion = trim(mb_strtoupper(safe($_POST['xxDireccion'])));
            $xTelcasa = trim(safe($_POST['xxTelcasa']));
            $xTelofi = trim(safe($_POST['xxTelofi']));
            $xCelular = trim(safe($_POST['xxCelular']));
            $xEmail = trim(mb_strtolower(safe($_POST['xxEmail'])));
            $xSeleccion = trim($_POST['xxSelecc']);
            $xAvatar = trim($_POST['xxAvatar']);
            $xFile = trim($_POST['xxFile']);

            if($xSeleccion == 'SI'){
                $xFile = (isset($_FILES['xxFile']["name"])) ? $_FILES['xxFile']["name"] : '';

                $xPath = "../persona/";            

                $xFechafile = new DateTime();
                $xNombreFile = ($xFile != "") ? $xFechafile->getTimestamp() . "_" . $_FILES["xxFile"]["name"] : "";            
    
                if($xFile != ''){
                    $xTmpFile = $_FILES["xxFile"]["tmp_name"];
    
                    if($xTmpFile != ""){
                        move_uploaded_file($xTmpFile,$xPath.$xNombreFile);
                    }
                }

                if($xAvatar != 'imaadd.png'){
                    if(file_exists($xPath . $xAvatar)){
                        unlink($xPath . $xAvatar);
                    }
                }
            }else{
                $xNombreFile = $xAvatar;
            }

            $xSQL = "UPDATE `expert_persona` SET pers_nombres='$xNombre',pers_apellidos='$xApellido',pers_imagen='$xNombreFile',pers_direccion='$xDireccion', ";
            $xSQL .= "pers_telefonocasa='$xTelcasa',pers_telefonoficina='$xTelofi',pers_celular='$xCelular',pers_email='$xEmail' WHERE pers_id=$xPersid ";
            mysqli_query($con, $xSQL);
            $xRespuesta = "OK";

            if(mysqli_query($con, $xSQL)){

                $xSQL = "INSERT INTO `expert_logs`(log_detalle,usua_id,pais_id,empr_id,log_fechacreacion,log_terminalcreacion) ";
                $xSQL .= "VALUES('Cambio Datos Titular',$xUsuaid,$xPaisid,$xEmprid,'{$xFecha}','$xTerminal') ";
                mysqli_query($con, $xSQL);                
            }
            
        }
    }

    //echo $xRespuesta;
    print json_encode($xRespuesta, JSON_UNESCAPED_UNICODE);
?>