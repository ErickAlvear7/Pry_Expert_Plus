<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxPrseid']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxPrseid']) <> '' ){

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xPrseid = $_POST['xxPrseid'];

        	$xSQL = "SELECT CONCAT(prf.prof_nombres,' ', prf.prof_apellidos) AS Nombres,pep.intervalo,CASE pep.pfes_estado WHEN 'A' THEN 'ACTIVO' ELSE 'INACTIVO' END AS Estado,pep.pfes_id AS Id,(SELECT pde.pade_nombre FROM `expert_parametro_detalle` pde WHERE pde.pade_valorV=prf.prof_tipoprofesion AND pde.paca_id=(SELECT pca.paca_id FROM `expert_parametro_cabecera` pca WHERE pca.paca_nombre='Tipo Profesion' AND pca.pais_id=$xPaisid AND pca.empr_id=$xEmprid )) AS Profesion FROM `expert_profesional` prf, `expert_profesional_especi` pep ";
        	$xSQL .= "WHERE prf.prof_estado='A' AND pep.prof_id=prf.prof_id AND pep.prse_id=$xPrseid AND pep.pais_id=$xPaisid AND pep.empr_id=$xEmprid ";
            $all_datos = mysqli_query($con, $xSQL);
            foreach($all_datos as $datos){

                $xPfesid = $datos["Id"];
                $xNombres = $datos["Nombres"];
                //$xApellidos = $datos["Apellidos"];
                $xTipoProf = $datos["Profesion"];
                $xIntervalo = $datos["intervalo"];
                $xEstado = $datos["Estado"];

                $xResultado[] = array(
                    'Id' => $xPfesid, 
                    'Nombres' => $xNombres, 
                    'Profesion' => $xTipoProf,
                    'Intervalo' => $xIntervalo,
                    'Estado' => $xEstado );                 
            }
        }
    }
    
    mysqli_close($con);
    print json_encode($xResultado, JSON_UNESCAPED_UNICODE);
    
?>