<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');    

    //file_put_contents('log_seguimiento_grabarperfil.txt', 'Ingreso a Grabar' . "\n\n", FILE_APPEND); 

	require_once("../dbcon/config.php");
	require_once("../dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	
    
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();    
    $xRespuesta = "ERR";

    if(isset($_POST['xxBeneid']) and isset($_POST['xxTituid']) and isset($_POST['xxUsuaid']) and isset($_POST['xxPaisid']) and isset($_POST['xxEmprid'])){
        if(isset($_POST['xxBeneid']) <> '' and isset($_POST['xxTituid']) <> '' and isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' ){
            
            $xBeneid = $_POST['xxBeneid'];
            $xTituid = $_POST['xxTituid'];
            $xUsuaid = $_POST['xxUsuaid'];
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];

            $xDireccion = trim(mb_strtoupper(safe($_POST['xxDireccion'])));
            $xTelcasa = trim(safe($_POST['xxTelcasa']));
            $xTelofi = trim(safe($_POST['xxTelofi']));
            $xCelular = trim(safe($_POST['xxCelular']));
            $xEmail = trim(safe($_POST['xxEmail']));
    
            $xSQL = "UPDATE `expert_beneficiario` SET bene_direccion ='$xDireccion',bene_telefonocasa ='$xTelcasa',bene_telefonoficina ='$xTelofi',bene_celular ='$xCelular',bene_email ='$xEmail' ";
            $xSQL .= "WHERE bene_id=$xBeneid AND titu_id=$xTituid";
            mysqli_query($con, $xSQL);
            $xRespuesta = "OK";
            
            if(mysqli_query($con, $xSQL)){

                $xSQL = "INSERT INTO `expert_logs`(log_detalle,usua_id,pais_id,empr_id,log_fechacreacion,log_terminalcreacion) ";
                $xSQL .= "VALUES('Beneficiario Editado',$xUsuaid,$xPaisid,$xEmprid,'{$xFecha}','$xTerminal') ";
                mysqli_query($con, $xSQL);                
            }
            

     
        }
    }
    
    //echo $xRespuesta;
    print json_encode($xRespuesta, JSON_UNESCAPED_UNICODE);
	
?>	