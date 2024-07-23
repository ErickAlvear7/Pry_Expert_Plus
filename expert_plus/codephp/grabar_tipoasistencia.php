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

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxUsuaid']) and isset($_POST['xxAsistencia']) and isset($_POST['xxTipoAsistencia']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxAsistencia']) <> '' and isset($_POST['xxTipoAsistencia']) <> ''){    

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];            
            $xUsuaid = $_POST['xxUsuaid'];            
            $xAsistencia = $_POST['xxAsistencia'];
            $xTipoAsistencia = trim(mb_strtoupper(safe($_POST['xxTipoAsistencia'])));
            $xDescripcion = trim(mb_strtoupper(safe($_POST['xxDescripcion'])));

            $xSQL = "SELECT * FROM `expert_tipo_asistencia` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND asis_nombre='$xTipoAsistencia' ";
            $all_asistencia = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
            if(mysqli_num_rows($all_asistencia) == 0){
            
                $xSQL = "INSERT INTO `expert_tipo_asistencia` (pais_id,empr_id,asis_nombre,asis_descripcion,asis_tipo,";
                $xSQL .= "fechacreacion,usuariocreacion,terminalcreacion)";
                $xSQL .= "VALUES ($xPaisid,$xEmprid,'$xTipoAsistencia','$xDescripcion','$xAsistencia','{$xFecha}',$xUsuaid,'$xTerminal')";
    
                if(mysqli_query($con, $xSQL)){
    
                    $xSQL = "SELECT asis_id AS Codigo,asis_nombre AS TipoAsistencia FROM `expert_tipo_asistencia` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND asis_estado='A' ";
                    $all_datos =  mysqli_query($con, $xSQL);
                    $resultado = '<option></option>';
                    foreach ($all_datos as $especi){ 
                        $resultado .='<option value="'.$especi["Codigo"].'">' . $especi["TipoAsistencia"].'</option>';
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