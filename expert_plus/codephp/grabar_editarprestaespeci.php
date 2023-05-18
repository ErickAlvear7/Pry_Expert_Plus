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
    $xRow = 0;

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprId']) and isset($_POST['xxUsuaid']) and isset($_POST['xxPresid']) and isset($_POST['xxEspeid']) and isset($_POST['xxPvp']) and isset($_POST['xxCosto']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprId']) <> '' and isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxPresid']) <> '' and isset($_POST['xxEspeid']) <> '' and isset($_POST['xxPvp']) <> '' and isset($_POST['xxCosto']) <> ''){
            
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprId'];
            $xUsuaid = $_POST['xxUsuaid'];            
            $xPresid = $_POST['xxPresid'];
            $xEspeid = $_POST['xxEspeid'];
            $xEspeidant = $_POST['xxEspeidant'];
            $xPvp = $_POST['xxPvp'];
            $xCosto = $_POST['xxCosto'];

            if($xEspeid != $xEspeidant){
                $xSQL = "SELECT * FROM `expert_prestadora_especialidad` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pres_id=$xPresid AND espe_id=$xEspeid ";
                $all_datos = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
                $xRow = mysqli_num_rows($all_datos);
            }

            if($xRow == 0){

                $xSQL = "UPDATE `expert_prestadora_especialidad` SET espe_id=$xEspeid,pree_pvp=$xPvp,pree_costo=$xCosto WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pres_id=$xPresid AND espe_id=$xEspeidant ";
                mysqli_query($con, $xSQL);

                $xSQL = "INSERT INTO `expert_logs`(log_detalle,usua_id,pais_id,empr_id,log_fechacreacion,log_terminalcreacion) ";
                $xSQL .= "VALUES('Actualización Especialidad Asignada',$xUsuaid,$xPaisid,$xEmprid,'{$xFecha}','$xTerminal') ";
                mysqli_query($con, $xSQL);            

                $xRespuesta = "OK";
            }else{
                $xRespuesta = "EXISTE";
            }            
        }
    }
        
    mysqli_close($con);
    //print json_encode($xRespuesta, JSON_UNESCAPED_UNICODE);
    echo $xRespuesta;
	
?>	