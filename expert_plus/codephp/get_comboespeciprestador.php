<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxPrestaid']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxPrestaid']) <> ''){ 

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xPrestaid = $_POST['xxPrestaid'];

            $xSQL = "SELECT epe.pree_id AS Codigo,(SELECT esp.espe_nombre FROM `expert_especialidad` esp WHERE esp.espe_id=epe.espe_id AND esp.espe_estado='A') AS Descripcion ";
            $xSQL .= "FROM `expert_prestadora_especialidad` epe WHERE epe.pais_id=$xPaisid AND epe.empr_id=$xEmprid AND epe.pres_id=$xPrestaid AND epe.pree_estado='A' AND epe.pree_id in(SELECT pree_id FROM `expert_profesional_especi` pfe  WHERE pfe.pais_id=$xPaisid AND pfe.empr_id=$xEmprid AND pfes_estado='A') ";
            $all_datos =  mysqli_query($con, $xSQL);
            $options ='<option></option>';
            foreach ($all_datos as $datos){ 
                $options .='<option value="' . $datos["Codigo"] . '">' . $datos["Descripcion"] . '</option>';
            }
        }
    }

    mysqli_close($con);
    echo $options;

?>