<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    
    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxPfesid']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxPfesid']) <> '' ){

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xPfesid = $_POST['xxPfesid'];

        	$xSQL = "SELECT hora_id,hora_intervalo,hora_desde,hora_hasta,(SELECT pde.pade_nombre FROM `expert_parametro_detalle` pde WHERE pde.pade_valorV=hora_dia AND pde.paca_id=(SELECT pca.paca_id FROM `expert_parametro_cabecera` pca WHERE pca.paca_nombre='Dias Semana' AND pca.pais_id=$xPaisid AND pca.empr_id=$xEmprid )) AS Dia FROM `expert_horarios_profesional`  ";
        	$xSQL .= "WHERE pais_id=$xPaisid AND empr_id=$xEmprid ";
            $all_datos = mysqli_query($con, $xSQL);
            foreach($all_datos as $datos){

                $xHoraid = $datos["hora_id"];
                $xDia = $datos["Dia"];
                $xIntervalo = $datos["hora_intervalo"];
                $xHorainicio = $datos["hora_desde"];
                $xHorafin = $datos["hora_hasta"];

                $xResultado[] = array(
                    'Id' => $xHoraid, 
                    'Dia' => $xDia, 
                    'Intervalo' => $xIntervalo,
                    'HoraDesde' => $xHorainicio,
                    'HoraHasta' => $xHorafin );                 
            }
        }
    }
    
    mysqli_close($con);
    print json_encode($xResultado, JSON_UNESCAPED_UNICODE);
    
?>