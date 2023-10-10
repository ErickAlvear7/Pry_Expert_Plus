<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');

    $xRespuesta = "ERR";
    
    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxPfesid']) and isset($_POST['xxCodDia']) and isset($_POST['xHini']) and isset($_POST['xHfin'])  ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxPfesid']) <> '' and isset($_POST['xxCodDia']) <> '' and isset($_POST['xHini']) <> '' and isset($_POST['xHfin']) <> '' ){

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xPfesid = $_POST['xxPfesid'];
            $xCodDia = $_POST['xxCodDia'];
            $xHdesde = $_POST['xHini'];
            $xHhasta = $_POST['xHfin'];

        	$xSQL = "SELECT codigo_dia FROM `expert_horarios_profesional` ";
        	$xSQL .= "WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pfes_id=$xPfesid AND codigo_dia=$xCodDia AND '$xHhasta' BETWEEN hora_desde AND hora_hasta ";
            $all_datos = mysqli_query($con, $xSQL);
            if(mysqli_num_rows($all_datos) > 0 ){
                $xRespuesta = "OK";
                //BUSCAR SI TIENE EN RESERVA
            }
        }
    }
    
    mysqli_close($con);
    echo $xRespuesta;
    
?>