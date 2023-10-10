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

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxUsuaid']) and isset($_POST['xxPfesid']) and isset($_POST['xxDia']) and isset($_POST['xxDiaText']) and isset($_POST['xxIntervalo']) and isset($_POST['xxHoraInicio']) and isset($_POST['xxHoraFin'])  ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxDia']) <> '' and isset($_POST['xxDiaText']) <> '' and isset($_POST['xxPfesid']) <> '' and isset($_POST['xxIntervalo']) <> '' and isset($_POST['xxHoraInicio']) <> '' and isset($_POST['xxHoraFin']) <> '' ){
            
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xUsuaid = $_POST['xxUsuaid'];
            $xPfesid = $_POST['xxPfesid'];
            $xDia = $_POST['xxDia'];
            $xDiatext = $_POST['xxDiaText'];
            $xIntervalo =  $_POST['xxIntervalo'];
            $xHoraInicio =  $_POST['xxHoraInicio'];
            $xHoraFin =  $_POST['xxHoraFin'];

            $xSQL = "SELECT * FROM `expert_horarios_profesional` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pfes_id=$xPfesid AND hora_dia='$xDiatext' ";
            $all_datos = mysqli_query($con, $xSQL);
            if(mysqli_num_rows($all_datos) == 0 )
            {
                $xSQL = "INSERT INTO `expert_horarios_profesional`(pais_id,empr_id,pfes_id,hora_dia,codigo_dia,hora_intervalo,hora_desde,hora_hasta,fechacreacion,usuariocreacion,terminalcreacion) ";
                $xSQL .= "VALUES($xPaisid,$xEmprid,$xPfesid,'$xDiatext',$xDia,$xIntervalo,'{$xHoraInicio}','{$xHoraFin}','{$xFecha}',$xUsuaid,'$xTerminal')";
                if(mysqli_query($con, $xSQL)){
    
                    $xId = mysqli_insert_id($con);
    
                    $xSQL = "INSERT INTO `expert_logs`(log_detalle,usua_id,pais_id,empr_id,log_fechacreacion,log_terminalcreacion) ";
                    $xSQL .= "VALUES('Nuevo Turno/Horario Agregados',$xUsuaid,$xPaisid,$xEmprid,'{$xFecha}','$xTerminal') ";
                    mysqli_query($con, $xSQL);  

                    $xRespuesta = $xId;
                }    
            }else{
                $xRespuesta = -1;
            }
        }
    }
    
    echo $xRespuesta;
	
?>	