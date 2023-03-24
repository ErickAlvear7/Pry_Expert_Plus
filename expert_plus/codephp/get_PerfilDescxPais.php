<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    
    $options = '<option value="0">--Seleccione Perfil--</opcion>';

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> ''){ 

            $xEmprid = $_POST['xxEmprid'];
            $xPaisid = $_POST['xxPaisid'];
            $xDatosPerfil = [];

        	$xSQL = "SELECT perf_descripcion AS Descripcion,perf_id AS Codigo, perf_Observacion AS Observacion FROM `expert_perfil` ";
        	$xSQL .= " WHERE empr_id=$xEmprid AND pais_id=$xPaisid AND perf_estado='A' ";
        	$xSQL .= " ORDER BY Codigo ";
            $all_perfil = mysqli_query($con, $xSQL) or die (error_log(mysql_error($con), 3, $log_file)) ;
            foreach($all_perfil as $perfil){

                $xPerfilid = $perfil["Codigo"];
                $xDescripcion = $perfil["Descripcion"];
                $xObservacion = $perfil["Observacion"];
                
                $xDatosPerfil[] = array(
                    'Perfilid'=> $xPerfilid, 
                    'Descripcion'=> $xDescripcion, 
                    'Observacion'=> $xObservacion, );                 
            }

            print json_encode($xDatosPerfil, JSON_UNESCAPED_UNICODE);
        }
    }
    
    mysqli_close($con);
    
?>