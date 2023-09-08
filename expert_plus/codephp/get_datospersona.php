<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    

    if(isset($_POST['xxPerid']) and isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) ){
        if(isset($_POST['xxPerid']) <> '' and isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> ''){

            $xPerid = $_POST['xxPerid'];
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];

            $xDatos = [];

            $xSQL = "SELECT pers_nombres AS Nombres, pers_apellidos AS Apellidos, pers_imagen AS Imagen, pers_direccion AS Direccion, pers_telefonocasa AS Telcasa, ";
            $xSQL .= "pers_telefonoficina AS Telofi, pers_celular AS Cel, pers_email AS Email ";
            $xSQL .= "FROM `expert_persona` WHERE pers_id=$xPerid AND pais_id=$xPaisid AND empr_id=$xEmprid ";
            $all_datos = mysqli_query($con, $xSQL);

            foreach($all_datos as $per) {

                $xNombre = $per["Nombres"]; 
                $xApellido = $per["Apellidos"];
                $xImagen = $per["Imagen"];
                $xDireccion = $per["Direccion"];
                $xTelcasa = $per["Telcasa"];
                $xTelofi = $per["Telofi"];
                $xCel = $per["Cel"];
                $xEmail = $per["Email"];
 
                $xDatos[] = array(
                    'Nombres'=> $xNombre, 
                    'Apellidos'=> $xApellido, 
                    'Imagen'=> $xImagen, 
                    'Direccion'=> $xDireccion, 
                    'Telcasa'=> $xTelcasa,
                    'Telofi'=> $xTelofi,
                    'Cel'=> $xCel,
                    'Email'=> $xEmail,
                
                );    
                
            }

            print json_encode($xDatos, JSON_UNESCAPED_UNICODE);

        }
    }

    mysqli_close($con);
    
?>