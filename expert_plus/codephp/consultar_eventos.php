<?php
    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xDataDetalle = [];

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> ''){
    
            $xPaisid= $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];

            $xSQL =  "SELECT pade_nombre AS Nombre, pade_valorV AS ValorT, pade_valorI AS ValorI FROM `expert_reserva`  ";
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
        }
    }

    mysqli_close($con);
    print json_encode($xDataDetalle, JSON_UNESCAPED_UNICODE);

?>