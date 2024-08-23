<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');       

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xRespuesta = "ERR";
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();    

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxUsuaid']) and isset($_POST['xxPrseid']) and isset($_POST['xxAtencion'])  ){
        if(isset($_POST['xxPaisid']) <> ''  and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxPrseid']) <> '' and isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxAtencion']) <> '' ){
            
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xUsuaid = $_POST['xxUsuaid'];
            $xPrseid = $_POST['xxPrseid'];
            $xAtencion = safe($_POST['xxAtencion']);
            $xAtencionold = safe($_POST['xxAtencionold']);
            $xRed = $_POST['xxRed'];
            $xPvp = $_POST['xxPvp'];
            $xasisid = 0;

            if($xAtencion != $xAtencionold ){
                $xSQL = "SELECT asis_id FROM `expert_prestadora_servicio` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND prse_id=$xPrseid AND prse_atencion='$xAtencionold' LIMIT 1 ";
                $all_servicio = mysqli_query($con, $xSQL);
                foreach ($all_servicio as $servicio) {
                    $xasisid = $servicio['asis_id'];
                }

                if($xasisid != 0){
                    $xSQL = "SELECT * FROM `expert_prestadora_servicio` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND asis_id=$xasisid AND prse_atencion='$xAtencion' ";
                    $all_servicio = mysqli_query($con, $xSQL);
                    if(mysqli_num_rows($all_servicio) > 0 ){
                        $xRespuesta = "ERR";
                    }else{
                        $xSQL = "UPDATE `expert_prestadora_servicio` SET prse_atencion='$xAtencion', prse_red=$xRed , prse_pvp=$xPvp  WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND prse_id=$xPrseid ";
                        mysqli_query($con, $xSQL);

                        $xSQL = "INSERT INTO `expert_logs`(log_detalle,usua_id,pais_id,empr_id,log_fechacreacion,log_terminalcreacion) ";
                        $xSQL .= "VALUES('Actualizar datos prestadora servicios',$xUsuaid,$xPaisid,$xEmprid,'{$xFecha}','$xTerminal') ";
                        mysqli_query($con, $xSQL);

                        $xRespuesta = "OK";
                    }
                }else{
                    $xRespuesta = "ERR";
                }
            }else{
                $xSQL = "UPDATE `expert_prestadora_servicio` SET prse_atencion='$xAtencion', prse_red=$xRed , prse_pvp=$xPvp  WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND prse_id=$xPrseid ";
                mysqli_query($con, $xSQL);

                $xSQL = "INSERT INTO `expert_logs`(log_detalle,usua_id,pais_id,empr_id,log_fechacreacion,log_terminalcreacion) ";
                $xSQL .= "VALUES('Actualizar datos prestadora servicios',$xUsuaid,$xPaisid,$xEmprid,'{$xFecha}','$xTerminal') ";
                mysqli_query($con, $xSQL);

                $xRespuesta = "OK";
            }
        }
    }

    echo $xRespuesta;

?>