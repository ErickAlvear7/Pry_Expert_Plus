<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    $xRow = 0;  

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxComboid']) and isset($_POST['xxOpcion']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxComboid']) <> '' and isset($_POST['xxOpcion']) <> ''){ 

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xComboid = $_POST['xxComboid'];
            $xOpcion = $_POST['xxOpcion'];

            switch($xOpcion){
                case 0: //LLENAR CIUDAD
                    $xSQL = "SELECT * FROM `provincia_ciudad` WHERE pais_id=$xPaisid AND provincia='$xComboid' AND estado='A' ";        
                    $all_datos =  mysqli_query($con, $xSQL);
                    $options ='<option></option>';
                    foreach ($all_datos as $ciudad){ 
                        $options .='<option value="'.$ciudad["prov_id"].'">' . mb_strtoupper($ciudad["ciudad"]).'</option>';
                    }                             
                    break;
                case 1: //PARAMETROS POR VALOR TEXTO
                    $xSQL = "SELECT * FROM `expert_parametro_detalle` pde,`expert_parametro_cabecera` pca WHERE pca.pais_id=$xPaisid AND pca.empr_id=$xEmprid ";
                    $xSQL .= "AND pca.paca_id=pde.paca_id AND pca.paca_estado='A' AND pade_estado='A' ";
                    $all_datos =  mysqli_query($con, $xSQL);
                    $options ='<option></option>';
                    
                    foreach ($all_datos as $datos){ 
                        $options .='<option value="'.$datos["pade_valorV"].'">' . $datos["pade_nombre"].'</option>';
                    }                       
                    break;
                case 2: //LLENAR MOTIVOS AGENDA
                    $xSQL = "SELECT mtes_id AS Codigo,motivos_especialidad AS Descripcion FROM `expert_motivos_especialidad` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND espe_id=$xComboid AND mtes_estado='A' ";
                    $all_datos =  mysqli_query($con, $xSQL);
                    $options ='<option></option>';
                    
                    foreach ($all_datos as $datos){ 
                        $options .='<option value="'.$datos["Codigo"].'">' . $datos["Descripcion"].'</option>';
                    }                      
                    break;

                case 3: //LLENAR PROVINCIA EN BASE DE CIUDAD 
                    $xSQL = "SELECT * FROM `provincia_ciudad` WHERE pais_id=$xPaisid AND prov_id='$xComboid' AND estado='A' ";        
                    $all_datos =  mysqli_query($con, $xSQL);
                    //$options ='<option></option>';
                    foreach ($all_datos as $provincia){ 
                        $options .='<option value="'.$provincia["prov_id"].'">' . mb_strtoupper($provincia["provincia"]).'</option>';
                    }                        
                    break;
            }
        }
    }

    mysqli_close($con);
    echo $options;

?>