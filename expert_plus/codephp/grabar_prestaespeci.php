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
    $respuesta = "ERR";

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxPresid']) and isset($_POST['xxResult']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxPresid']) <> '' and isset($_POST['xxResult']) <> ''){
            
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xPresid = $_POST['xxPresid'];
            $xUsuaid = $_POST['xxUsuaid']; 
            $xResult = $_POST['xxResult']; 

            foreach($xResult as $drfila){
                $xEspeid = $drfila['arryid'];
                $xPvp = $drfila['arrypvp'];
                $xCosto = $drfila['arrycosto'];

                $xSQL = "INSERT INTO `expert_prestadora_especialidad`(pais_id,empr_id,pres_id,espe_id,pree_pvp,pree_costo,fechacreacion,usuariocreacion,terminalcreacion) ";
                $xSQL .= "VALUES($xPaisid,$xEmprid,$xPresid,$xEspeid,$xPvp,$xCosto,'{$xFecha}',$xUsuaid,'$xTerminal')";
                mysqli_query($con, $xSQL);

                $respuesta = "OK";
            }
        }
    }
    
    echo $respuesta;
	
?>	