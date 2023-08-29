<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "error_consultaprod.txt";
    $xRow = 0;
    $xLastid = 0;  

    if(isset($_POST['xxClieid']) and isset($_POST['xxGrupid']) and isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxProducto']) ){
        if(isset($_POST['xxClieid']) <> '' and isset($_POST['xxGrupid']) <> '' and isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxProducto']) <> '' ){

            $xClieid = $_POST['xxClieid'];
            $xGrupid = $_POST['xxGrupid'];
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xProducto = trim(mb_strtoupper(safe($_POST['xxProducto'])));
            $xDescrip = trim(mb_strtoupper(safe($_POST['xxDesc'])));
            $xCosto = $_POST['xxCosto'];
            $xAsisMes = $_POST['xxAsisMes'];
            $xAsisAnu = $_POST['xxAsisAnu'];
            $xCobertura = $_POST['xxCober'];
            $xSistema = $_POST['xxSist'];
            $xGerencial = $_POST['xxGeren'];

            $xSQL = "SELECT * FROM `expert_productos` WHERE prod_nombre = '$xProducto' AND clie_id = $xClieid AND pais_id =$xPaisid AND empr_id= $xEmprid";
            $all_datos = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
            $xRow = mysqli_num_rows($all_datos); 
            
            if($xRow == 0){
                $xSQL = "INSERT INTO `expert_productos`(clie_id,grup_id,pais_id,empr_id,prod_nombre,prod_descripcion,prod_costo, ";
                $xSQL .="prod_asistmes,prod_asistanu,prod_cobertura,prod_sistema,prod_gerencial ) ";
                $xSQL .= "VALUES($xClieid,$xGrupid,$xPaisid,$xEmprid,'$xProducto','$xDescrip',$xCosto,$xAsisMes,$xAsisAnu, ";
                $xSQL .="'$xCobertura','$xSistema',' $xGerencial') ";
                mysqli_query($con, $xSQL);                    
                $xLastid = mysqli_insert_id($con);
            }
        }
    }
    
    mysqli_close($con);
    echo $xLastid;

?>