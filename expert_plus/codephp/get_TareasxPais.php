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
            $xDatosTareas = [];

            $xSQL = "SELECT DISTINCT mta.meta_id AS MentId,tar.tare_nombre AS Tarea,CASE pmt.meta_estado WHEN 'A' THEN 'SI' ELSE 'NO' END AS Ckeck,tar.tare_orden AS TareOrden ";
            $xSQL .= "FROM `expert_menu_tarea` mta, `expert_perfil_menu_tarea` pmt, `expert_tarea` tar ";
            $xSQL .= "WHERE pmt.meta_id=mta.meta_id AND mta.tare_id=tar.tare_id AND pmt.pais_id=$xPaisid AND pmt.perf_id=$xPerfilid ";
            $xSQL .= "UNION SELECT DISTINCT mta.meta_id AS MentId,tar.tare_nombre AS Tarea,'NO' AS Ckeck,tar.tare_orden AS TareOrden ";
            $xSQL .= "FROM `expert_menu_tarea` mta, `expert_perfil_menu_tarea` pmt, `expert_tarea` tar ";
            $xSQL .= "WHERE pmt.meta_id=mta.meta_id AND mta.tare_id=tar.tare_id AND pmt.pais_id=$xPaisid AND ";
            $xSQL .= "mta.meta_id NOT IN(SELECT meta_id FROM `expert_perfil_menu_tarea` WHERE pais_id=$xPaisid AND perf_id=$xPerfilid) ORDER BY TareOrden ";

            $all_tareas = mysqli_query($con, $xSQL) or die (error_log(mysql_error($con), 3, $log_file)) ;
            foreach($all_tareas as $tareas){

                $xMentid = $tareas["MentId"];
                $xTarea = $tareas["Tarea"];
                $xCheck = $tareas["Ckeck"];
                
                $xDatosTareas[] = array(
                    'Mentid'=> $xMentid, 
                    'Tarea'=> $xTarea, 
                    'Check'=> $xCheck, );                 
            }

            print json_encode($xDatosTareas, JSON_UNESCAPED_UNICODE);
        }
    }
    
    mysqli_close($con);
    
?>