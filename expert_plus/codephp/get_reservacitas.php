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
            
            //$xSQL = "SELECT rsrv_id as id,'ReservaTMP' AS title,'Horario Reservado Temporalmente' AS description,fecha_inicio AS start,fecha_fin AS end,hora_desde AS horaini,hora_hasta AS horafin,(SELECT usu.usua_login FROM `expert_usuarios` usu WHERE usu.pais_id=$xPaisid and usu.empr_id=$xEmprid AND usu.usua_id=usuariocreacion) AS username,color,textcolor FROM `expert_reserva_tmp` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pres_id=$xPresid AND espe_id=$xEspeid AND pfes_id=$xPfesid ";
            $xSQL = "SELECT rsrv_id as id,'ReservaTMP' AS title,'Horario Reservado Temporalmente' AS description,fecha_inicio AS start,fecha_fin AS end,fecha_inicio,fecha_fin,codigo_dia,dia,usuariocreacion,  ";
            $xSQL .= "hora_desde AS horaini,hora_hasta AS horafin,(SELECT CONCAT(prv.provincia,'/',prv.ciudad) FROM `provincia_ciudad` prv WHERE prv.pais_id=$xPaisid AND prv.prov_id=rsv.ciud_id) AS Provincia,";
            $xSQL .= "(SELECT pre.pres_nombre FROM `expert_prestadora` pre WHERE pre.pais_id=$xPaisid AND pre.empr_id=$xEmprid AND pre.pres_id=rsv.pres_id) AS Prestadora,";
            $xSQL .= "(SELECT esp.espe_nombre FROM `expert_especialidad` esp WHERE esp.pais_id=$xPaisid AND esp.empr_id=$xEmprid AND esp.espe_id=rsv.espe_id) AS Especialidad,";
            $xSQL .= "(SELECT CONCAT(pro.prof_nombres,' ',pro.prof_apellidos) FROM `expert_profesional` pro WHERE pro.pais_id=$xPaisid AND pro.empr_id=$xEmprid AND pro.prof_id=(SELECT pes.prof_id FROM `expert_profesional_especi` pes WHERE pes.pais_id=$xPaisid AND pes.empr_id=$xEmprid and pes.pfes_id=rsv.pfes_id)) AS Profesional,";
            $xSQL .= "(SELECT usu.usua_login FROM `expert_usuarios` usu WHERE usu.pais_id=$xPaisid and usu.empr_id=$xEmprid AND usu.usua_id=rsv.usuariocreacion) AS username,";    
            $xSQL .= "color,textcolor FROM `expert_reserva_tmp` rsv WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pres_id=$xPresid AND espe_id=$xEspeid AND pfes_id=$xPfesid ";
            $xSQL .= "UNION  ";

            $xSQL = "SELECT agen_id as id,'AGENDADO' AS title,'Registro del Agendamiento' AS description,fecha_inicio AS start,fecha_fin AS end,fecha_inicio,fecha_fin,codigo_dia,dia,usuariocreacion,  ";
            $xSQL .= "hora_desde AS horaini,hora_hasta AS horafin,(SELECT CONCAT(prv.provincia,'/',prv.ciudad) FROM `provincia_ciudad` prv WHERE prv.pais_id=$xPaisid AND prv.prov_id=rsv.ciud_id) AS Provincia,";
            $xSQL .= "(SELECT pre.pres_nombre FROM `expert_prestadora` pre WHERE pre.pais_id=$xPaisid AND pre.empr_id=$xEmprid AND pre.pres_id=rsv.pres_id) AS Prestadora,";
            $xSQL .= "(SELECT esp.espe_nombre FROM `expert_especialidad` esp WHERE esp.pais_id=$xPaisid AND esp.empr_id=$xEmprid AND esp.espe_id=rsv.espe_id) AS Especialidad,";
            $xSQL .= "(SELECT CONCAT(pro.prof_nombres,' ',pro.prof_apellidos) FROM `expert_profesional` pro WHERE pro.pais_id=$xPaisid AND pro.empr_id=$xEmprid AND pro.prof_id=(SELECT pes.prof_id FROM `expert_profesional_especi` pes WHERE pes.pais_id=$xPaisid AND pes.empr_id=$xEmprid and pes.pfes_id=rsv.pfes_id)) AS Profesional,";
            $xSQL .= "(SELECT usu.usua_login FROM `expert_usuarios` usu WHERE usu.pais_id=$xPaisid and usu.empr_id=$xEmprid AND usu.usua_id=rsv.usuariocreacion) AS username,";    
            $xSQL .= "color,textcolor FROM `expert_agenda` rsv WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pres_id=$xPresid AND espe_id=$xEspeid AND pfes_id=$xPfesid ";
            

            $all_reserva = mysqli_query($con, $xSQL);
            $resultado = mysqli_fetch_all($all_reserva,MYSQLI_ASSOC);

        }
    }
    
    mysqli_close($con);
    echo json_encode($resultado);

?>