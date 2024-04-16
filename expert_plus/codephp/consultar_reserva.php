<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');      

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();

    $xRow = 0;

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxUsuaid']) and isset($_POST['xxPresid']) and isset($_POST['xxEspeid']) and isset($_POST['xxPfesid']) and isset($_POST['xxFechaInicio']) and isset($_POST['xxFechaFin']) and isset($_POST['xxCodigoDia'])  ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxPresid']) <> '' and isset($_POST['xxEspeid']) <> '' and isset($_POST['xxPfesid']) <> '' and isset($_POST['xxFechaInicio']) <> '' and isset($_POST['xxFechaFin']) <> '' and isset($_POST['xxCodigoDia']) <> '' ){ 

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xUsuaid = $_POST['xxUsuaid'];
            $xPresid = $_POST['xxPresid'];
            $xEspeid = $_POST['xxEspeid'];
            $xPfesid = $_POST['xxPfesid'];
            $xCiudid = $_POST['xxCiudid'];
            $xFechaInicio = $_POST['xxFechaInicio'];
            $xFechaFin = $_POST['xxFechaFin'];
            $xHoraDesde = $_POST['xxHoraDesde'];
            $xHoraHasta = $_POST['xxHoraHasta'];
            $xCodigoDia = $_POST['xxCodigoDia'];
            $xDia = $_POST['xxDia'];
            
            $xSQL = "SELECT * FROM `expert_reserva_tmp` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pres_id=$xPresid AND espe_id=$xEspeid AND pfes_id=$xPfesid AND fecha_inicio='$xFechaInicio' AND fecha_fin='$xFechaFin' AND codigo_dia=$xCodigoDia ";
            $all_reserva = mysqli_query($con, $xSQL);
            if(mysqli_num_rows($all_reserva) > 0){
                $xRow = 1;
            }else{
                $xSQL = "INSERT INTO `expert_reserva_tmp`(pais_id,empr_id,pres_id,espe_id,pfes_id,ciud_id,fecha_inicio,fecha_fin,hora_desde,hora_hasta,codigo_dia,dia,color,textcolor,fechacreacion,usuariocreacion,terminalcreacion) ";
                $xSQL .= "VALUES($xPaisid,$xEmprid,$xPresid,$xEspeid,$xPfesid,$xCiudid,'{$xFechaInicio}','{$xFechaFin}','{$xHoraDesde}','{$xHoraHasta}',$xCodigoDia,'$xDia','#0CD5F9','#060606','{$xFecha}',$xUsuaid,'$xTerminal') ";
                if(mysqli_query($con, $xSQL)){
                    $xRow = 0;
                }else{
                    $xRow = 1;
                }
            }
        }
    }
    
    mysqli_close($con);
    echo $xRow;

?>