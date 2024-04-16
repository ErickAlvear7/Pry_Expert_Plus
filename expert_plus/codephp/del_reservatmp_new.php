<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time()); 
    $xTerminal = gethostname(); 
    $xRow = 0;

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxRsrvid']) and isset($_POST['xxUsuaid']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxRsrvid']) <> '' and isset($_POST['xxUsuaid']) <> '' ){ 

            $xRsrvid =   $_POST['xxRsrvid'];
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xUsuaid = $_POST['xxUsuaid'];
            
            $xSQL = "DELETE FROM `expert_reserva_tmp` WHERE rsrv_id = $xRsrvid AND pais_id=$xPaisid AND empr_id=$xEmprid  ";
            if(mysqli_query($con, $xSQL)){

                $xSQL = "INSERT INTO `expert_logs`(log_detalle,usua_id,pais_id,empr_id,log_fechacreacion,log_terminalcreacion) ";
                $xSQL .= "VALUES('Registro Agendado',$xUsuaid,$xPaisid,$xEmprid,'{$xFecha}','$xTerminal') ";
                mysqli_query($con, $xSQL);

                $xRow = 1;
            }
        }
    }
    
    mysqli_close($con);
    echo $xRow;

?>