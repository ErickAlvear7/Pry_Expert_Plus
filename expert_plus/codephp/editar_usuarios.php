<?php
    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    if(isset($_POST['xxIdUsuario']) and isset($_POST['xxEmprid'])){
        if(isset($_POST['xxIdUsuario']) <> '' and isset($_POST['xxEmprid']) <> ''){

            $yEmprid = $_POST['xxEmprid'];
            $yIdusua = $_POST['xxIdUsuario'];
            $xDataUsuarios = [];

            $xSQL =  "SELECT usua_nombres AS Nombres, usua_apellidos AS Apellidos, usua_login AS Logi, usua_password AS Pass, perf_id AS CodigoPerf, pais_id AS CodigoPais, CASE ";
            $xSQL .= "usua_caducapass WHEN 'SI' THEN 'SI' ELSE 'NO' END AS Caduca, DATE_FORMAT(usua_fechacaduca,'%Y-%m-%d') AS FechaCaduca, CASE usua_cambiarpass WHEN ";
            $xSQL .= "'SI' THEN 'SI' ELSE 'NO' END AS Cambiar FROM `expert_usuarios` WHERE usua_id= $yIdusua AND empr_id=$yEmprid; ";
            $consulta = mysqli_query($con, $xSQL);

            foreach ($consulta as $datos){ 
                $xNombres = $datos["Nombres"];
                $xApellidos = $datos["Apellidos"];
                $xLogin = $datos["Logi"];
                $xPassword = $datos["Pass"];
                $xCodPerfil = $datos["CodigoPerf"];
                $xCodPais = $datos["CodigoPais"];
                $xCaducaPass = $datos["Caduca"];
                $xFechaCaduca = $datos["FechaCaduca"];
                $xCambiarPass = $datos["Cambiar"];

                $xDataUsuarios[] = array(
                        'Nombres'=> $xNombres, 
                        'Apellidos'=> $xApellidos, 
                        'Login'=> $xLogin, 
                        'Password'=> $xPassword, 
                        'CodigoPais'=> $xCodPais,
                        'CodigoPerfil'=> $xCodPerfil,
                        'CaducaPass'=> $xCaducaPass, 
                        'FechaCaduca'=> $xFechaCaduca, 
                        'CambiarPass'=> $xCambiarPass);                
            }

            print json_encode($xDataUsuarios, JSON_UNESCAPED_UNICODE);
        
        }
    }

?>