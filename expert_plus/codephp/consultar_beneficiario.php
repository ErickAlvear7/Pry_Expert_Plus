<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    $xRow = 0;  

    if(isset($_POST['xxProdId']) and isset($_POST['xxPaisId']) and isset($_POST['xxEmprId']) and isset($_POST['xxDocumento'])){
        if(isset($_POST['xxProdId']) <> '' and isset($_POST['xxPaisId']) <> '' and isset($_POST['xxEmprId']) <> '' and isset($_POST['xxDocumento']) <> ''){ 

            $xProdid = $_POST['xxProdId'];
            $xPaisid = $_POST['xxPaisId'];
            $xEmprid = $_POST['xxEmprId'];
            $xDocumento = $_POST['xxDocumento'];

            $xSQL = "SELECT * FROM `expert_beneficiario` bene, `expert_titular` titu WHERE bene.titu_id=titu.titu_id AND titu.prod_id=$xProdid AND bene.bene_numerodocumento='$xDocumento' ";
            $all_bene = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
            $xRow = mysqli_num_rows($all_bene);    
           
        }
    }
    
    echo $xRow;

?>