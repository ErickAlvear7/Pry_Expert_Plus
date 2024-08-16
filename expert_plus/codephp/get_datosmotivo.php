<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxPrseid'])  ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxPrseid']) <> '' ){ 

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xPrseid = $_POST['xxPrseid'];
            
            $xSQL = "SELECT * FROM `expert_motivos_especialidad` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND prse_id=$xPrseid  ";
            $all_motivos = mysqli_query($con, $xSQL);
            $resultado = mysqli_fetch_all($all_motivos,MYSQLI_ASSOC);
        }
    }
    
    mysqli_close($con);
    print json_encode($resultado);

?>