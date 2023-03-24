<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();
    $resultado = "ERR";

    if(isset($_POST['xxPaisId']) and isset($_POST['xxUsuaId']) and isset($_POST['xxEmprId']) and isset($_POST['xxParametro']) and isset($_POST['xxResultado'])
              and isset($_POST['xxEstado']) and isset($_POST['xxDescripcion'])){

        $xEmprid = $_POST['xxEmprId'];
        $xPaisid = $_POST['xxPaisId'];
        $xUsuaid = $_POST['xxUsuaId'];
        $xParametro = safe($_POST['xxParametro']);
        $xDescricpion = safe($_POST['xxDescripcion']);
        $xEstado = safe($_POST['xxEstado']);
        $xResult = ($_POST['xxResultado']);


        $xSQL = "INSERT INTO `expert_parametro_cabecera` (pais_id,empr_id,paca_nombre,paca_descripcion,paca_estado, ";
        $xSQL .= "fechacreacion,usuariocreacion,terminalcreacion) ";
        $xSQL .= "VALUES ($xPaisid,$xEmprid,'$xParametro','$xDescricpion','$xEstado','{$xFecha}',$xUsuaid,'$xTerminal')";


        if(mysqli_query($con, $xSQL)){

            $last_id = mysqli_insert_id($con);
            
        }

        foreach($xResult as $drfila){

            $xNomdet = $drfila['arrydetalle'];
            $xvalorV = $drfila['arryvalorv'];
            $xvalorI = $drfila['arryvalori'];
            $xorden =  $drfila['arryorden'];
          
            $xSQL = "INSERT INTO `expert_parametro_detalle` (paca_id,pade_orden,pade_nombre,pade_valorV, ";
            $xSQL .= "pade_valorI,pade_estado) ";
            $xSQL .= "VALUES ($last_id,$xorden,'$xNomdet','$xvalorV',$xvalorI,'A')";
            mysqli_query($con, $xSQL);
          
        }

     
       
    }

    echo $last_id;

?>