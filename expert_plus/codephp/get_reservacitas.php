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

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxPresid']) and isset($_POST['xxEspeid']) and isset($_POST['xxPfesid']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxPresid']) <> '' and isset($_POST['xxEspeid']) <> '' and isset($_POST['xxPfesid']) <> '' ){ 

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xPresid = $_POST['xxPresid'];
            $xEspeid = $_POST['xxEspeid'];
            $xPfesid = $_POST['xxPfesid'];
            
            $xSQL = "SELECT rsrv_id as id,'ReservaTMP' AS title,'Horario Reservado Temporalmente' AS description,fecha_inicio AS start,fecha_fin AS end,hora_desde AS horaini,hora_hasta AS horafin,(SELECT usu.usua_login FROM `expert_usuarios` usu WHERE usu.pais_id=$xPaisid and usu.empr_id=$xEmprid AND usu.usua_id=usuariocreacion) AS username,color,textcolor FROM `expert_reserva_tmp` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pres_id=$xPresid AND espe_id=$xEspeid AND pfes_id=$xPfesid ";
            $all_reserva = mysqli_query($con, $xSQL);
            $resultado = mysqli_fetch_all($all_reserva,MYSQLI_ASSOC);

        }
    }
    
    mysqli_close($con);
    echo json_encode($resultado);

?>