<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    

    if(isset($_POST['xxBeneid'])){
        if(isset($_POST['xxBeneid']) <> ''){

            $xBeneid = $_POST['xxBeneid'];


            $xSQL = "SELECT(SELECT prv.ciudad FROM `provincia_ciudad` prv WHERE prv.prov_id=bne.bene_ciudad) AS Ciudad, bne.bene_nombres AS Nombres, ";
            $xSQL .= "bne.bene_apellidos AS Apellidos, bne.bene_direccion AS Direccion, (SELECT pde.pade_nombre FROM `expert_parametro_detalle` pde, ";
            $xSQL .= "`expert_parametro_cabecera` pca WHERE pde.paca_id=pca.paca_id AND pca.paca_nombre='Parentesco' AND pde.pade_valorv=bne.bene_parentesco) AS Parentesco, ";
            $xSQL .= "bne.bene_telefonocasa AS Telcasa, bne.bene_telefonoficina AS Telofi, bne.bene_celular AS Celular, bne.bene_email AS Email, ";
            $xSQL .= "bne.bene_estado AS Estado FROM `expert_beneficiario` bne WHERE bne.bene_id=$xBeneid";
            $all_datos = mysqli_query($con, $xSQL);

             foreach($all_datos as $pro) {

                $xCiudad = $pro["Ciudad"]; 
                $xNombres = $pro["Nombres"];
                $xApellidos = $pro["Apellidos"];
                $xDireccion = $pro["Direccion"];
                $xParentesco = $pro["Parentesco"];
                $xTelcasa = $pro["Telcasa"];
                $xTelofi = $pro["Telofi"];
                $xCelular = $pro["Celular"];
                $xEmail = $pro["Email"];
                $xEstado = $pro["Estado"];
 
                $xDatos[] = array(
                    'Ciudad'=> $xCiudad, 
                    'Nombres'=> $xNombres, 
                    'Apellidos'=> $xApellidos, 
                    'Direccion'=> $xDireccion, 
                    'Parentesco'=> $xParentesco,
                    'Telcasa'=> $xTelcasa,
                    'Telofi'=> $xTelofi,
                    'Celular'=> $xCelular,
                    'Email'=> $xEmail,
                    'Estado'=> $xEstado,
                
                );    
                
            }
            print json_encode($xDatos, JSON_UNESCAPED_UNICODE);

        }
    }

    mysqli_close($con);
    // echo json_encode($resultado);
    
?>