<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    $xRow = 0;  

    if(isset($_POST['xxPacaId']) and isset($_POST['xxDetalle']) and isset($_POST['xxValorV']) and isset($_POST['xxValorI']) ){
        if(isset($_POST['xxPacaId']) <> '' and isset($_POST['xxDetalle']) <> '' and isset($_POST['xxValorV']) <> '' and isset($_POST['xxValorI']) <> ''){ 

            $xPacaid = $_POST['xxPacaId'];
            $xDetalle = $_POST['xxDetalle'];
            $xValorv = $_POST['xxValorV']; 
            $xValori = $_POST['xxValorI']; 

            $xDetalleold = $_POST['xxDetalleold'];
            $xValorvold = $_POST['xxValorVold']; 
            $xValoriold = $_POST['xxValorIold']; 
            
            if(strtoupper($xDetalle) != strtoupper($xDetalleold)){
                $xSQL = "SELECT * FROM `expert_parametro_detalle` pade WHERE pade.paca_id=$xPacaid AND pade.pade_nombre='$xDetalle'";
                $all_det = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
                $xRow = mysqli_num_rows($all_det);                
            }

            if($xValorv != ''){
                if(strtoupper($xValorv) != strtoupper($xValorvold)){
                    $xSQL = "SELECT * FROM `expert_parametro_detalle` pade WHERE pade.paca_id=$xPacaid AND pade.pade_valorV='$xValorv'";
                    $all_det = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
                    $xRow = mysqli_num_rows($all_det);                    
                }
            }

            if($xValori != ''){
                if($xValori != $xValoriold){
                    $xSQL = "SELECT * FROM `expert_parametro_detalle` pade WHERE pade.paca_id=$xPacaid AND pade.pade_valorI='$xValori'";
                    $all_det = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
                    $xRow = mysqli_num_rows($all_det);                    
                }
            }            
        }
    }
    
    echo $xRow;

?>