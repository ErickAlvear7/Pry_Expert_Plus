<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    $xRow = 0;  

    if(isset($_POST['xxResultado']) and isset($_POST['xxPaisId']) ){
        if(isset($_POST['xxResultado']) <> '' and isset($_POST['xxPaisId']) <> ''){ 

            $xResult = $_POST['xxResultado'];
            $xPaisid = $_POST['xxPaisId'];

            foreach($xResult as $drfila){

                $xNomdet = $drfila['arrydetalle'];
                $xvalorV = $drfila['arryvalorv'];
                $xvalorI = $drfila['arryvalori'];

                if($xvalorV != ''){

                    $xSQL = " SELECT * FROM `expert_parametro_detalle` pade ";
                    $xSQL .= "INNER JOIN `expert_parametro_cabecera` pac ON pac.paca_id=pade.paca_id ";
                    $xSQL .= " WHERE  pac.pais_id=$xPaisid AND pade.pade_nombre='$xNomdet' OR pade.pade_valorV='$xvalorV' ";
                    $all_det = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
                    $xRow = mysqli_num_rows($all_det);
    
                }else if( $xvalorI != 0){
    
                    $xSQL = " SELECT * FROM `expert_parametro_detalle` pade ";
                    $xSQL .= "INNER JOIN `expert_parametro_cabecera` pac ON pac.paca_id=pade.paca_id ";
                    $xSQL .= " WHERE  pac.pais_id=$xPaisid AND pade.pade_nombre='$xNomdet' OR pade.pade_valorI=$xvalorI ";
                    $all_det = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
                    $xRow = mysqli_num_rows($all_det);
    
                }
            }
       
        }
    }
    
    echo $xRow;

?>