<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    $xRow = 0;  

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxCiudadid']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxCiudadid']) <> ''){ 

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xCiudadid = $_POST['xxCiudadid'];

            $xSQL = "SELECT pres_id AS Codigo, pres_nombre AS Descripcion FROM `expert_prestadora` WHERE pais_id=$xPaisid and empr_id=$xEmprid AND prov_id=$xCiudadid AND pres_estado='A' ";
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