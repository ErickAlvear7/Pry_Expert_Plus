<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    
    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxPfesid'])  ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxPfesid']) <> '' ){

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xPfesid = $_POST['xxPfesid'];

        	$xSQL = "SELECT hora_id,(SELECT xpx.intervalo FROM `expert_profesional_especi` xpx WHERE xpx.pais_id=$xPaisid AND xpx.empr_id=$xEmprid AND xpx.pfes_id=$xPfesid ) AS Intevalo,codigo_dia,hora_desde,hora_hasta FROM `expert_horarios_profesional`  ";
        	$xSQL .= "WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pfes_id=$xPfesid ";
            $all_datos = mysqli_query($con, $xSQL);
            foreach($all_datos as $datos){

                $xDia = $datos["codigo_dia"];
                $xHorainicio = $datos["hora_desde"];
                $xHorafin = $datos["hora_hasta"];
                $xIntervalo = $datos["Intevalo"];

                $xResultado[] = array(
                    'daysOfWeek' => $xDia, 
                    'startTime' => $xHorainicio,
                    'endTime' => $xHorafin, 
                    'intervalo' => $xIntervalo );
            }
        }
    }
    
    mysqli_close($con);
    print json_encode($xResultado, JSON_UNESCAPED_UNICODE);
    
?>