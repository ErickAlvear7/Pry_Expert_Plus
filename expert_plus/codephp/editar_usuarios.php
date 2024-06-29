<?php
    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    if(isset($_POST['xxUserid']) and isset($_POST['xxPaisid']) and isset($_POST['xxEmprid'])){
        if(isset($_POST['xxUserid']) <> '' and isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> ''){
            
            $xUserid = $_POST['xxUserid'];
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xDataUsuarios = [];

            $xSQL =  "SELECT usua_nombres AS Nombres, usua_apellidos AS Apellidos, usua_login AS Logi, usua_password AS Pass, perf_id AS CodigoPerf, pais_id AS CodigoPais, CASE ";
            $xSQL .= "usua_caducapass WHEN 'SI' THEN 'SI' ELSE 'NO' END AS Caduca, DATE_FORMAT(usua_fechacaduca,'%Y-%m-%d') AS FechaCaduca, CASE usua_cambiarpass WHEN ";
            $xSQL .= "'SI' THEN 'SI' ELSE 'NO' END AS Cambiar,usua_avatarlogin AS Avatar FROM `expert_usuarios` WHERE usua_id=$xUserid AND pais_id=$xPaisid AND empr_id=$xEmprid;";
            
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
                $xAvatar = $datos["Avatar"];

                $xDataUsuarios[] = array(
                        'Nombres'=> $xNombres, 
                        'Apellidos'=> $xApellidos, 
                        'Login'=> $xLogin, 
                        'Password'=> $xPassword, 
                        'CodigoPais'=> $xCodPais,
                        'CodigoPerfil'=> $xCodPerfil,
                        'CaducaPass'=> $xCaducaPass, 
                        'FechaCaduca'=> $xFechaCaduca, 
                        'CambiarPass'=> $xCambiarPass,
                        'Avatar'=> $xAvatar);                
            }

            print json_encode($xDataUsuarios, JSON_UNESCAPED_UNICODE);
        
        }
    }

?>