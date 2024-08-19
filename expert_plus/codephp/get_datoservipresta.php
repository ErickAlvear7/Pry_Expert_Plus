<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    
    $options = '<option value="0">--Seleccione Perfil--</opcion>';

    if( isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxPrseid'])  ){
        if(isset($_POST['xxPrseid']) <> '' and isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> ''  ){ 

            $xPrseid = $_POST['xxPrseid'];
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];            
            $xDatos = [];

        	$xSQL = "SELECT prs.prse_atencion AS Atencion,prs.prse_red AS Red,prs.prse_pvp AS Pvp,(SELECT asis_nombre FROM ";
        	$xSQL .= "`expert_tipo_asistencia` WHERE asis_id=prs.asis_id) AS Asistencia FROM `expert_prestadora_servicio` prs WHERE prs.prse_id=$xPrseid AND ";
            $xSQL .="prs.pais_id=$xPaisid AND prs.empr_id=$xEmprid  ";
            $all_datos = mysqli_query($con, $xSQL);
            foreach($all_datos as $datos){

                $xAtencion = $datos["Atencion"];
                $xRed = $datos["Red"];
                $xPvp = $datos["Pvp"];
                $xAsistencia = $datos["Asistencia"];

                $xDatos[] = array(
                    'Atencion'=> $xAtencion, 
                    'Red'=> $xRed, 
                    'Pvp'=> $xPvp, 
                    'Asistencia'=> $xAsistencia, );                 
            }
        }
    }
    
    mysqli_close($con);
    print json_encode($xDatos, JSON_UNESCAPED_UNICODE);
    
?>