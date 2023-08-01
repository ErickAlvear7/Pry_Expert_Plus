<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());
    $xTerminal = gethostname();   
    $xRespuesta = 'ERR';

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxPfesid']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxPfesid']) <> '' ){

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xPfesid = $_POST['xxPfesid'];

            $xSQL = "SELECT * FROM `expert_horarios_profesional` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pfes_id=$xPfesid ";
            $all_datos = mysqli_query($con, $xSQL);
            $xRow = mysqli_num_rows($all_datos);
            if($xRow == 0){
                $xSQL = "DELETE FROM `expert_profesional_especi` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pfes_id=$xPfesid ";
                mysqli_query($con, $xSQL);
                $xRespuesta = 'OK';
            }
        }
    }
    
    mysqli_close($con);
    echo $xRespuesta;

?>