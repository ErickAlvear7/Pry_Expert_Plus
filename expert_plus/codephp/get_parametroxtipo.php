<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    $resultado = '';
    
    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxParametro']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxParametro']) <> ''){ 

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xParametro = $_POST['xxParametro'];

            $xSQL = "SELECT pde.pade_valorV AS Codigo,pde.pade_nombre AS Descripcion FROM `expert_parametro_cabecera` pca, `expert_parametro_detalle` pde WHERE pca.paca_id=pde.paca_id AND pca.pais_id=$xPaisid AND pca.empr_id=$xEmprid AND pca.paca_nombre='$xParametro' AND pca.paca_estado='A' AND pde.pade_estado='A' ";
            $all_datos =  mysqli_query($con, $xSQL);
            $resultado = '<option></option>';
            foreach ($all_datos as $datos){ 
                $resultado .= '<option value="' . $datos["Codigo"] . '">' . $datos["Descripcion"] . '</option>';
            }
        }
    }
    
    mysqli_close($con);
    
    echo $resultado;
?>