<?php
    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $yEmprid = 1;

    $data = "ERROR";

    if(isset($_POST['xxIdUsuario'])){
        if(isset($_POST['xxIdUsuario']) <> ''){

            $yIdusua = $_POST['xxIdUsuario'];

            $xSQL =  "SELECT usua_nombres AS Nombres, usua_apellidos AS Apellidos, usua_login AS Login, usua_password AS Pass, perf_id AS CodigoPerf, CASE ";
            $xSQL .= "usua_caducapass WHEN 'SI' THEN 'SI' ELSE 'NO' END AS Caduca, DATE_FORMAT(usua_fechacaduca,'%Y/%m/%d') AS FechaCaduca, CASE usua_cambiarpass WHEN ";
            $xSQL .= "'SI' THEN 'SI' ELSE 'NO' END AS Cambiar FROM expert_usuarios WHERE usua_id= $yIdusua AND empr_id=$yEmprid; ";
            $data = mysqli_query($con, $xSQL);
    

                print json_encode($data, JSON_UNESCAPED_UNICODE);
        
        }


    }

?>