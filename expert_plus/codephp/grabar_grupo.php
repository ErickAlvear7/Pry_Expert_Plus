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
  
    if(isset($_POST['xxPaisId']) and isset($_POST['xxEmprId']) and isset($_POST['xxUsuaId']) and isset($_POST['xxGrupo'])){
        if(isset($_POST['xxPaisId']) <> '' and isset($_POST['xxEmprId']) <> '' and isset($_POST['xxUsuaId']) <> '' and isset($_POST['xxGrupo']) <> ''){    

            $xPaisid= $_POST['xxPaisId'];
            $xEmprid = $_POST['xxEmprId'];
            $xUsuaid = $_POST['xxUsuaId'];
            $xGrupo = trim(mb_strtoupper(safe($_POST['xxGrupo'])));
            $xDesc = trim(mb_strtoupper(safe($_POST['xxDesc'])));

            $xNumagenda = $_POST['xxNumagenda'];
            $xNumcancela = $_POST['xxNumcancela'];
            $xNumatendido = $_POST['xxNumatendido'];
            $xNumausente = $_POST['xxNumausente'];

            $xSQL = "INSERT INTO `expert_grupos`(pais_id,empr_id,grup_nombre,grup_descripcion,usuariocreacion,terminalcreacion,fechacreacion) ";
            $xSQL .= "VALUES($xPaisid,$xEmprid,'$xGrupo','$xDesc',$xUsuaid,'$xTerminal','{$xFecha}') ";
            mysqli_query($con, $xSQL); 

            $xSQL = "SELECT grup_id AS Codigo,grup_nombre AS NombreGrupo FROM `expert_grupos` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND grup_estado='A' ";
            $all_datos =  mysqli_query($con, $xSQL);
            $resultado = '<option></option>';
            foreach ($all_datos as $grupo){ 
                $resultado .='<option value="'.$grupo["Codigo"].'">' . $grupo["NombreGrupo"].'</option>';
            }  
         
            
        }       
    }

    mysqli_close($con);
    echo $resultado;

?>