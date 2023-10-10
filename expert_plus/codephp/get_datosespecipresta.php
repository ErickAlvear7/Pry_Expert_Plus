<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    
    $options = '<option value="0">--Seleccione Perfil--</opcion>';

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxPreeid']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxPreeid']) <> '' ){ 

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];            
            $xPreeid = $_POST['xxPreeid'];
            $xDatos = [];

        	$xSQL = "SELECT epr.espe_id AS Espeid,epr.pree_pvp AS Pvp,epr.pree_costo AS Costo,esp.espe_nombre AS Especialidad FROM `expert_prestadora_especialidad` epr, `expert_especialidad` esp ";
        	$xSQL .= " WHERE epr.espe_id=esp.espe_id AND epr.pais_id=$xPaisid AND epr.empr_id=$xEmprid AND epr.pree_id=$xPreeid ";
            $all_datos = mysqli_query($con, $xSQL) or die (error_log(mysql_error($con), 3, $log_file));
            foreach($all_datos as $datos){

                $xEspeid = $datos["Espeid"];
                $xPvp = $datos["Pvp"];
                $xCosto = $datos["Costo"];
                $xEspecialidad = $datos["Especialidad"];

                $xDatos[] = array(
                    'Espeid'=> $xEspeid, 
                    'Pvp'=> $xPvp, 
                    'Costo'=> $xCosto, 
                    'xEspecialidad'=> $xEspecialidad, );                 
            }
        }
    }
    
    mysqli_close($con);
    print json_encode($xDatos, JSON_UNESCAPED_UNICODE);
    
?>