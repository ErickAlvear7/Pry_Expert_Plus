<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxPfesid']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxPfesid']) <> '' ){

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xPfesid = $_POST['xxPfesid'];

        	$xSQL = "SELECT (SELECT pmd.pade_nombre FROM `expert_parametro_detalle` pmd WHERE pmd.pade_valorV=xpr.prof_tipodoc AND ";
        	$xSQL .= "pmd.paca_id=(SELECT pca.paca_id FROM `expert_parametro_cabecera` pca WHERE pca.paca_nombre='Tipo Documento')) AS TipoDoc, ";
            $xSQL .= "xpr.prof_numdoc AS Documento,CONCAT(xpr.prof_nombres,' ',xpr.prof_apellidos) AS Nombres FROM `expert_profesional` xpr ";
            $xSQL .= "INNER JOIN `expert_profesional_especi` xes ON xpr.prof_id = xes.prof_id WHERE xes.pais_id=$xPaisid AND xes.empr_id=$xEmprid AND xes.pfes_id=$xPfesid ";
            $all_datos = mysqli_query($con, $xSQL);
            $resultado = mysqli_fetch_all($all_datos,MYSQLI_ASSOC);
        }
    }
    
    mysqli_close($con);
    echo json_encode($resultado);
    
?>