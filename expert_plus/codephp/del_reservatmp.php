<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xRow = 0;

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxPresid']) and isset($_POST['xxEspeid']) and isset($_POST['xxPfesid']) and isset($_POST['xxFechaInicio']) and isset($_POST['xxFechaFin']) and isset($_POST['xxCodigoDia']) and isset($_POST['xxUsuaid'])  ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxPresid']) <> '' and isset($_POST['xxEspeid']) <> '' and isset($_POST['xxPfesid']) <> '' and isset($_POST['xxFechaInicio']) <> '' and isset($_POST['xxFechaFin']) <> '' and isset($_POST['xxCodigoDia']) <> '' and isset($_POST['xxUsuaid']) <> '' ){ 

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xPresid = $_POST['xxPresid'];
            $xEspeid = $_POST['xxEspeid'];
            $xPfesid = $_POST['xxPfesid'];
            $xFechaInicio = $_POST['xxFechaInicio'];
            $xFechaFin = $_POST['xxFechaFin'];
            $xCodigoDia = $_POST['xxCodigoDia'];
            $xUsuaid = $_POST['xxUsuaid'];
            
            $xSQL = "DELETE FROM `expert_reserva_tmp` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pres_id=$xPresid AND espe_id=$xEspeid AND pfes_id=$xPfesid AND fecha_inicio='$xFechaInicio' AND fecha_fin='$xFechaFin' AND codigo_dia=$xCodigoDia AND usuariocreacion=$xUsuaid ";
            if(mysqli_query($con, $xSQL)){
                $xRow = 1;
            }
        }
    }
    
    mysqli_close($con);
    echo $xRow;

?>