<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    $xRow = 0;  

    if(isset($_POST['xxPacaId']) and isset($_POST['xxDetalle']) and isset($_POST['xxValorV']) ){
        if(isset($_POST['xxPacaId']) <> '' and isset($_POST['xxDetalle']) <> '' and isset($_POST['xxValorV']) <> ''){ 

            $xPacaid = $_POST['xxPacaId'];
            $xDetalle= $_POST['xxDetalle'];
            $xValorV = $_POST['xxValorV'];          

            $xSQL = "SELECT * FROM `expert_parametro_detalle` pade WHERE pade.pade_nombre='$xDetalle'";
            $xSQL .= "INNER JOIN `expert_parametro_cabecera pac` ON pac.paca_id=pade.paca_id AND pac.pais_id=$xPaisid ";
            $xSQL .= " OR pade.pade_valorV = '$xValorV' AND pade.pade_estado = 'A'";
            $all_detalle = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
            $xRow = mysqli_num_rows($all_detalle);
        }
    }
    
    echo $xRow;

?>