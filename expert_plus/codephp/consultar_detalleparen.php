<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    $xRow = 0;  

    if(isset($_POST['xxPaisId']) and isset($_POST['xxDetalle']) and isset($_POST['xxValorV'])){
        if(isset($_POST['xxPaisId']) <> '' and isset($_POST['xxDetalle']) <> '' and isset($_POST['xxValorV']) <> ''){ 

            $xPaisid = $_POST['xxPaisId'];
            $xDetalle= $_POST['xxDetalle'];
            $xValorv = $_POST['xxValorV']; 

            $xSQL = " SELECT * FROM `expert_parametro_detalle` pade ";
            $xSQL .= "INNER JOIN `expert_parametro_cabecera` pac ON pac.paca_id=pade.paca_id ";
            $xSQL .= " WHERE  pac.pais_id=$xPaisid AND pade.pade_nombre='$xDetalle' OR pade.pade_valorV='$xValorv' ";
            $all_det = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
            $xRow = mysqli_num_rows($all_det);
        }
    

}
    
    echo $xRow;

?>