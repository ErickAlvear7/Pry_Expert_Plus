<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxPresid'])  ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxPresid']) <> '' ){ 
            
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xPresid = $_POST['xxPresid'];

            $xSQL = "SELECT pres_nombre,(SELECT pde.pade_nombre FROM `expert_parametro_detalle` pde WHERE pade_valorV=pres_sector AND paca_id=(SELECT pca.paca_id FROM `expert_parametro_cabecera` pca WHERE pca.pais_id=$xPaisid AND pca.empr_id=$xEmprid AND pca.paca_nombre='Tipo Sector')) AS Sector,";
            $xSQL .= "(SELECT pde.pade_nombre FROM `expert_parametro_detalle` pde WHERE pade_valorV=pres_tipoprestador AND paca_id=(SELECT pca.paca_id FROM `expert_parametro_cabecera` pca WHERE pca.pais_id=$xPaisid and pca.empr_id=$xEmprid AND pca.paca_nombre='Tipo Prestador')) AS TipoPrestador,";
            $xSQL .= "pres_direccion,pres_url,pres_fono1,pres_fono2,pres_fono3,pres_celular1,pres_celular2,pres_celular3,pres_email1,pres_enviar1,pres_email2,pres_enviar2,pres_logo FROM `expert_prestadora` pre ";
            $xSQL .= "WHERE pre.pais_id=$xPaisid AND pre.empr_id=$xEmprid AND pres_id=$xPresid ";
            $all_datos = mysqli_query($con, $xSQL);            
            foreach($all_datos as $datos){

                $xPrestador = $datos["pres_nombre"];
                $xSector = $datos["Sector"];
                $xTipoPresta = $datos["TipoPrestador"];
                $xDireccion = $datos["pres_direccion"];
                $xUrl = $datos["pres_url"];
                $xFono1 = $datos["pres_fono1"];
                $xFono2 = $datos["pres_fono2"];
                $xFono3 = $datos["pres_fono3"];
                $xCelular1 = $datos["pres_celular1"];
                $xCelular2 = $datos["pres_celular2"];
                $xCelular3 = $datos["pres_celular3"];
                $xEmail1 = $datos["pres_email1"];
                $xEnviar1 = $datos["pres_enviar1"];
                $xEmail2 = $datos["pres_email2"];
                $xEnviar2 = $datos["pres_enviar2"];
                $xLogo = $datos["pres_logo"];

                $xDatos = array(
                    'Prestador'=> $xPrestador, 
                    'Sector'=> $xSector, 
                    'TipoPrestador'=> $xTipoPresta, 
                    'Direccion'=> $xDireccion, 
                    'Url'=> $xUrl, 
                    'Fono1'=> $xFono1, 
                    'Fono2'=> $xFono2, 
                    'Fono3'=> $xFono3, 
                    'Celu1'=> $xCelular1, 
                    'Celu2'=> $xCelular2, 
                    'Celu3'=> $xCelular3, 
                    'Email1'=> $xEmail1, 
                    'Enviar1'=> $xEnviar1, 
                    'Email2'=> $xEmail2, 
                    'Enviar2'=> $xEnviar2,
                    'Logo'=> $xLogo);                 
            }
        }
    }
    
    mysqli_close($con);
    print json_encode($xDatos, JSON_UNESCAPED_UNICODE);

?>