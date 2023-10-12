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


            $xSQL = "SELECT bene_nombres AS Nombre, bene_apellidos AS Apellido, bene_direccion AS Direccion, bene_telefonocasa AS Telcasa, bene_telefonoficina AS Telofi, ";
            $xSQL .= "bene_celular AS Celular, bene_email AS Email FROM `expert_beneficiario` WHERE bene_id=$xBeneid ";      
            $all_datos = mysqli_query($con, $xSQL);
            $resultado = mysqli_fetch_all($all_datos,MYSQLI_ASSOC);

        }
    }

    mysqli_close($con);
    //print json_encode($xDatos, JSON_UNESCAPED_UNICODE);
    echo json_encode($resultado);
    
?>