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
    $xRespuesta = 0;

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxUsuaid']) and isset($_POST['xxPrseid']) and isset($_POST['xxMotivo'])  ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxPrseid']) <> '' and isset($_POST['xxMotivo']) <> ''  ){
            
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xUsuaid = $_POST['xxUsuaid'];
            $xPrseid = $_POST['xxPrseid'];
            $xMotivo =  trim(mb_strtoupper(safe($_POST['xxMotivo'])));
            
            $xSQL = "INSERT INTO `expert_motivos_especialidad`(pais_id,empr_id,prse_id,motivos_especialidad,mtes_estado,fechacreacion,usuariocreacion,terminalcreacion) ";
            $xSQL .= "VALUES($xPaisid,$xEmprid,$xPrseid,'$xMotivo','A','{$xFecha}',$xUsuaid,'$xTerminal')";
            if(mysqli_query($con, $xSQL)){
    
                $xId = mysqli_insert_id($con);

                $xSQL = "INSERT INTO `expert_logs`(log_detalle,usua_id,pais_id,empr_id,log_fechacreacion,log_terminalcreacion) ";
                $xSQL .= "VALUES('Nuevo Motivo Agregado',$xUsuaid,$xPaisid,$xEmprid,'{$xFecha}','$xTerminal') ";
                mysqli_query($con, $xSQL);  
                
                $xRespuesta = $xId;
            }else{
                $xRespuesta = 0;
            }
        }
    }
    
    echo $xRespuesta;
	
?>	