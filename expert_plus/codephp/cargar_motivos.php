<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxPresid']) and isset($_POST['xxEspeid']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxPresid']) <> '' and isset($_POST['xxEspeid']) <> ''){ 

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xPresid = $_POST['xxPresid'];
            $xEspeid = $_POST['xxEspeid'];

            $xSQL = "SELECT mtes_id AS Codigo,motivos_especialidad AS Descripcion FROM `expert_motivos_especialidad` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pres_id=$xPresid AND espe_id=$xEspeid AND mtes_estado='A' ";
            $all_datos =  mysqli_query($con, $xSQL);
            $options ='<option></option>';
            
            foreach ($all_datos as $datos){ 
                $options .='<option value="'.$datos["Codigo"].'">' . $datos["Descripcion"].'</option>';
            }  

        }
    }

    mysqli_close($con);
    echo $options;

?>