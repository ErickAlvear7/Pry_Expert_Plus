<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    $resultado = '0';
    
    if(isset($_POST['xxPaisId']) and isset($_POST['xxEmprId']) and isset($_POST['xxEspeId']) ){
        if(isset($_POST['xxPaisId']) <> '' and isset($_POST['xxEmprId']) <> '' and isset($_POST['xxEspeId']) <> ''){ 

            $xPaisid = $_POST['xxPaisId'];
            $xEmprid = $_POST['xxEmprId'];
            $xEspeid = $_POST['xxEspeId'];

        	$xSQL = "SELECT espe_pvp AS Precio FROM `expert_especialidad` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND espe_id=$xEspeid ";
            $all_pvp = mysqli_query($con, $xSQL) or die (error_log(mysql_error($con), 3, $log_file)) ;
            foreach($all_pvp as $precio){                
                $resultado = $precio['Precio'];
            }
        }
    }
    
    mysqli_close($con);
    
    echo $resultado;
?>