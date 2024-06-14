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


            $xSQL = "SELECT pade.pade_nombre AS Nombre, pade.pade_valorV AS ValorV, pade.pade_valorI AS ValorI,(SELECT paca.paca_nombre ";
            $xSQL .="FROM  `expert_parametro_cabecera` paca WHERE paca.paca_id=$xPacaid) AS Parametro ";
            $xSQL .="FROM `expert_parametro_detalle` pade  WHERE pade.pade_id=$xPadeid";
            $consulta = mysqli_query($con, $xSQL);

            foreach ($consulta as $datos){ 
                $xNombre = $datos["Nombre"];
                $xValorV = $datos["ValorV"];
                $xValorI = $datos["ValorI"];
                $xParametro = $datos["Parametro"];


                $xDataDetalle[] = array(
                        'Nombre'=> $xNombre, 
                        'ValorV'=> $xValorV, 
                        'ValorI'=> $xValorI,
                        'Parametro'=> $xParametro,
                    );
                                  
            }

            print json_encode($xDataDetalle, JSON_UNESCAPED_UNICODE);
        
        }
    }

?>