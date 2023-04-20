<?php
    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    if(isset($_POST['xxPadeid']) and isset($_POST['xxPacaid'])){
        if(isset($_POST['xxPadeid']) <> '' and isset($_POST['xxPacaid']) <> ''){
    
            $xPadeid= $_POST['xxPadeid'];
            $xPacaid = $_POST['xxPacaid'];
            $xDataDetalle = [];
            $xValorI = '';

            $xSQL =  "SELECT pade_nombre AS Nombre, pade_valorV AS ValorT, pade_valorI AS ValorI FROM `expert_superparametro_detalle`  ";
            $xSQL .= "WHERE pade_id = $xPadeid AND paca_id = $xPacaid ";
            $consulta = mysqli_query($con, $xSQL);

            foreach ($consulta as $datos){ 
                $xNombre = $datos["Nombre"];
                $xValorV = $datos["ValorT"];
                $xValorI = $datos["ValorI"];

                if($xValorI == 0){
                    $xValorI = '';
                }
         

                $xDataDetalle[] = array(
                        'Nombre'=> $xNombre, 
                        'ValorT'=> $xValorV, 
                        'ValorI'=> $xValorI);
                                  
            }

            print json_encode($xDataDetalle, JSON_UNESCAPED_UNICODE);
        
        }
    }

?>