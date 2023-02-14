<?php
    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();
    $data = "ERROR";

    if(isset($_POST['xxEmprid']) and isset($_POST['xxNombre']) and isset($_POST['xxApellido']) and isset($_POST['xxLogin'])
        and isset($_POST['xxPassword'])  and isset($_POST['xxPerfil'])){
            if(isset($_POST['xxEmprid']) <> '' and isset($_POST['xxNombre']) <> '' and isset($_POST['xxApellido']) <> ''){

                $yEmprid = $_POST['xxEmprid'];
                $xEstado = $_POST['xxEstado'];
                $xNombre = safe($_POST['xxNombre']);
                $xApellido = safe($_POST['xxApellido']);
                $xLogin = $_POST['xxLogin']; 
                $xPasword = $_POST['xxPassword']; 
                $xPerfil =  $_POST['xxPerfil'];
                $xCaducaPass =  $_POST['xxCaducaPass'];
                $xFecha =  $_POST['xxFecha'];
                $xCambiarPass = $_POST['xxCambiarPass'];
                
        
        
                $xSQL ="INSERT INTO `expert_usuarios` () ";
                $xSQL .="VALUES ()";
                if(mysqli_query($con, $xSQL)){
                
            
                    $data = "OK";
                }

        

                    print json_encode($data, JSON_UNESCAPED_UNICODE);
        
        }


    }

?>