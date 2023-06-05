<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');    

    //file_put_contents('log_seguimiento_grabarperfil.txt', 'Ingreso a Grabar' . "\n\n", FILE_APPEND); 

	require_once("../dbcon/config.php");
	require_once("../dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	
    
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();
    $xRespuesta = "ERR";
    $xRow = 0;

    if(isset($_POST['xxProdid']) and isset($_POST['xxGrupid']) and isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxProdedit'])){
        if(isset($_POST['xxProdid']) <> '' and isset($_POST['xxGrupid']) <> '' and isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxProdedit']) <> ''){
            
            $xProdid = $_POST['xxProdid'];
            $xGrupid = $_POST['xxGrupid'];
            $xPaisid = $_POST['xxPaisid'];            
            $xEmprid = $_POST['xxEmprid'];
            $xProdnew = safe($_POST['xxProdedit']);
            $xProdant = $_POST['xxProdant'];
            $xDesc = safe($_POST['xxDescr']);
            $xCosto = safe($_POST['xxCostoedit']);
            $xAsisMes = $_POST['xxAsisMesedit'];
            $xAsisAnu = $_POST['xxAsisAnuedit'];
            $xCobertura = $_POST['xxCobertura'];
            $xSistema = $_POST['xxSistema'];
            $xGerencial = $_POST['xxGerencial'];
            

            if($xProdnew != $xProdant){
                $xSQL = "SELECT * FROM `expert_productos` WHERE prod_id = $xProdid AND pais_id = $xPaisid AND empr_id = $xEmprid AND prod_nombre = '$xProdnew' ";
                $all_datos = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
                $xRow = mysqli_num_rows($all_datos);
            }

            if($xRow == 0){

                $xSQL = "UPDATE `expert_productos` SET grup_id = $xGrupid,prod_nombre='$xProdnew',prod_descripcion='$xDesc',prod_costo=$xCosto, " ;
                $xSQL .="prod_asistmes=$xAsisMes,prod_asistanu= $xAsisAnu,prod_cobertura='$xCobertura',prod_sistema='$xSistema',prod_gerencial='$xGerencial' WHERE prod_id = $xProdid ";
                mysqli_query($con, $xSQL);         

                $xRespuesta = "OK";
            }else{
                $xRespuesta = "EXISTE";
            }            
        }
    }
        
    mysqli_close($con);
    //print json_encode($xRespuesta, JSON_UNESCAPED_UNICODE);
    echo $xRespuesta;
	
?>	