<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();
    $resultado = "ERR";
    $log_file = "log_error_especialidad.txt";

    if(isset($_POST['xxPaisId']) and isset($_POST['xxUsuaId']) and isset($_POST['xxEmprId']) and isset($_POST['xxEspecialidad']) and isset($_POST['xxTipoEspe']) ){
        if(isset($_POST['xxPaisId']) <> '' and isset($_POST['xxUsuaId']) <> '' and isset($_POST['xxEmprId']) <> '' and isset($_POST['xxEspecialidad']) <> '' and isset($_POST['xxTipoEspe']) <> ''){    

            $xPaisid = $_POST['xxPaisId'];
            $xEmprid = $_POST['xxEmprId'];            
            $xUsuaid = $_POST['xxUsuaId'];            
            $xEspecialidad = trim(mb_strtoupper(safe($_POST['xxEspecialidad'])));
            $xDescripcion = safe($_POST['xxDescripcion']);
            $xTipoEspe = $_POST['xxTipoEspe'];
            $xPrecio = $_POST['xxPrecio'];

            $xSQL = "SELECT * FROM `expert_especialidad` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND espe_nombre='$xEspecialidad' ";
            $all_especi = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
            if(mysqli_num_rows($all_especi) == 0){
            
                $xSQL = "INSERT INTO `expert_especialidad` (pais_id,empr_id,espe_nombre,espe_descripcion,espe_tipo,espe_pvp, ";
                $xSQL .= "fechacreacion,usuariocreacion,terminalcreacion) ";
                $xSQL .= "VALUES ($xPaisid,$xEmprid,'$xEspecialidad','$xDescripcion','$xTipoEspe','$xPrecio','{$xFecha}',$xUsuaid,'$xTerminal')";
    
                if(mysqli_query($con, $xSQL)){
    
                    $xSQL = "SELECT espe_id AS Codigo,espe_nombre AS NombreEspe FROM `expert_especialidad` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND espe_estado='A' ";
                    $all_datos =  mysqli_query($con, $xSQL);
                    $resultado = '<option></option>';
                    foreach ($all_datos as $especi){ 
                        $resultado .='<option value="'.$especi["Codigo"].'">' . $especi["NombreEspe"].'</option>';
                    }   
                }            
            }else{
                $resultado = "EXISTE";
            }
        }       
    }

    mysqli_close($con);
    echo $resultado;

?>