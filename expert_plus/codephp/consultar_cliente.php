<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    $xRow = 0;  

    if(isset($_POST['xxPaisId']) and isset($_POST['xxEmprId']) and isset($_POST['xxCliente'])){
        if(isset($_POST['xxPaisId']) <> '' and isset($_POST['xxEmprId']) <> '' and isset($_POST['xxCliente']) <> ''){ 
            
            $xPaisid = $_POST['xxPaisId'];
            $xEmprid = $_POST['xxEmprId'];
            $xCliente= trim(mb_strtoupper(safe($_POST['xxCliente'])));

         
            $xSQL = " SELECT * FROM `expert_cliente` clie ";
            $xSQL .= " WHERE clie.pais_id=$xPaisid AND clie.empr_id=$xEmprid AND clie.clie_nombre='$xCliente' ";
            $all_det = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
            $xRow = mysqli_num_rows($all_det);   
        }
    }
    
    echo $xRow;

?>