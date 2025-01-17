<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');    

	require_once("../dbcon/config.php");
	require_once("../dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	
    
    //file_put_contents('0_logseguimiento.txt', 'Ingreso a Grabar prestadora especi' . "\n\n", FILE_APPEND); 
    
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();
    $respuesta = "ERR";

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxUsuaid']) and isset($_POST['xxPresid']) and isset($_POST['xxResult']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxPresid']) <> '' and isset($_POST['xxResult']) <> ''){
            
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xPresid = $_POST['xxPresid'];
            $xUsuaid = $_POST['xxUsuaid']; 
            $xResult = $_POST['xxResult']; 
            
            foreach($xResult as $drfila){
                $xAsisid = $drfila['arryasisid'];
                $xAsistencia = $drfila['arryasistencia'];
                $xTipoAtencion = $drfila['arryatencion'];
                $xRed = safe($drfila['arryred']);
                $xPvp = safe($drfila['arrypvp']);
                

                $xSQL ="INSERT INTO `expert_prestadora_servicio`(pais_id,empr_id,pres_id,asis_id,prse_atencion,prse_red,";
                $xSQL .="prse_pvp,fechacreacion,usuariocreacion,terminalcreacion)";
                $xSQL .="VALUES($xPaisid,$xEmprid,$xPresid,$xAsisid,'$xTipoAtencion',$xRed,$xPvp,'{$xFecha}',$xUsuaid,'$xTerminal')";
                mysqli_query($con, $xSQL);

                $respuesta = "OK";
            }
        }
    }
    
    echo $respuesta;
	
?>	