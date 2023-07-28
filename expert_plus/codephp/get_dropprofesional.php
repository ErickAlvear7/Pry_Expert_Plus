<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    
    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxTipoProfe']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxTipoProfe']) <> '' ){

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xTipoProfe = $_POST['xxTipoProfe'];

        	$xSQL = "SELECT * FROM `expert_profesional` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND prof_tipoprofesion='$xTipoProfe' AND prof_estado='A' ";
            $all_datos = mysqli_query($con, $xSQL);
            $options ='<option></option>';
            foreach($all_datos as $datos){

                $xId = $datos["prof_id"];
                $xNombres = $datos["prof_nombres"] . " " . $datos["prof_apellidos"];
                
                $options .='<option value="'.$xId.'">' . $xNombres.'</option>';

            }
        }
    }
    
    mysqli_close($con);
    echo $options;
    
?>