<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";    

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxPerfilid'])){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxPerfilid']) <> ''){ 

            $xEmprid = $_POST['xxEmprid'];
            $xPaisid = $_POST['xxPaisid'];
            $xPerfilid = $_POST['xxPerfilid'];
            $xDatos = [];

            $xSQL = "SELECT * FROM `expert_perfil` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND perf_id=$xPerfilid ";
            $all_datos = mysqli_query($con, $xSQL) or die (error_log(mysql_error($con), 3, $log_file)) ;
            foreach($all_datos as $datos){

                $Detalle1 = $datos["perf_detalle1"];
                $Detalle2 = $datos["perf_detalle2"];
                $Detalle3 = $datos["perf_detalle3"];
                $Detalle4 = $datos["perf_detalle4"];
                $Detalle5 = $datos["perf_detalle5"];
                
                $xDatos[] = array(
                    'Detalle1'=> $Detalle1, 
                    'Detalle2'=> $Detalle2, 
                    'Detalle3'=> $Detalle3,
                    'Detalle4'=> $Detalle4,
                    'Detalle5'=> $Detalle5 ); 
            }

            print json_encode($xDatos, JSON_UNESCAPED_UNICODE);
        }
    }
    
    mysqli_close($con);
    
?>