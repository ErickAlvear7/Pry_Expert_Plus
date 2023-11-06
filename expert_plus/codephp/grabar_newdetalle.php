<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();
    $resultado = "ERR";
  
    if(isset($_POST['xxPacaid']) and isset($_POST['xxPaisid']) and isset($_POST['xxOrden']) and isset($_POST['xxDetalle']) and isset($_POST['xxValorV'])){
        if(isset($_POST['xxPacaid']) <> '' and isset($_POST['xxPaisid']) <> '' and isset($_POST['xxOrden']) <> '' and isset($_POST['xxDetalle']) <> '' and isset($_POST['xxValorV']) <> ''){ 

            $xPacaid = $_POST['xxPacaid'];
            $xPaisid = $_POST['xxPaisid'];
            $xOrden = $_POST['xxOrden'];
            $xDetalle = trim(mb_strtoupper(safe($_POST['xxDetalle']))); 
            $xValorV = trim(mb_strtoupper(safe($_POST['xxValorV'])));
            
            
            $xSQL = "INSERT INTO `expert_parametro_detalle` (paca_id,pade_orden,pade_nombre, ";
            $xSQL .= "pade_valorV) ";
            $xSQL .= "VALUES ($xPacaid,$xOrden,'$xDetalle','$xValorV')";
            mysqli_query($con, $xSQL); 

            $xSQL = "SELECT pde.pade_valorV AS Codigo,UPPER(pde.pade_nombre) AS Descripcion FROM `expert_parametro_detalle` pde,`expert_parametro_cabecera` pca ";
            $xSQL .="WHERE pca.pais_id=$xPaisid AND pca.paca_nombre='Parentesco' AND pca.paca_id=pde.paca_id AND pca.paca_estado='A' AND pade_estado='A' ORDER BY pde.pade_nombre ";
            $all_datos =  mysqli_query($con, $xSQL);
            $resultado = '<option></option>';
            foreach ($all_datos as $pare){ 
                $resultado .='<option value="'.$pare["Codigo"].'">' . $pare["Descripcion"].'</option>';
            }  
            
        }       
    }

    mysqli_close($con);
    echo $resultado;

?>