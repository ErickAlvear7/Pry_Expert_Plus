<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxPreeid']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxPreeid']) <> ''){ 

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xPreeid = $_POST['xxPreeid'];

            $xSQL = "SELECT exp.pfes_id AS Codigo,(SELECT CONCAT(ppx.prof_nombres,' ',ppx.prof_apellidos) FROM `expert_profesional` ppx WHERE ppx.prof_id=exp.prof_id) AS Descripcion ";
            $xSQL .= "FROM `expert_profesional_especi` exp WHERE exp.pais_id=$xPaisid AND exp.empr_id=$xEmprid AND exp.pree_id=$xPreeid AND exp.pfes_estado='A' AND ";
            $xSQL .= "exp.pfes_id IN(SELECT xph.pfes_id FROM `expert_horarios_profesional` xph  WHERE xph.pais_id=$xPaisid AND xph.empr_id=$xEmprid )";
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