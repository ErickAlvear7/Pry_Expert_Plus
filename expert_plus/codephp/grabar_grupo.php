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
    $xRow = 0;  
    $log_file = "log_error_grabartipopresta.txt";

    if(isset($_POST['xxPaisId']) and isset($_POST['xxEmprId']) and isset($_POST['xxUsuaId']) and isset($_POST['xxGrupo'])){
        if(isset($_POST['xxPaisId']) <> '' and isset($_POST['xxEmprId']) <> '' and isset($_POST['xxUsuaId']) <> '' and isset($_POST['xxGrupo']) <> ''){    

            $xPaisid= $_POST['xxPaisId'];
            $xEmprid = $_POST['xxEmprId'];
            $xUsuaid = $_POST['xxUsuaId'];
            $xGrupo = $_POST['xxGrupo'];
            $xDesc = $_POST['xxDesc'];

            $xSQL = "SELECT * FROM `expert_grupos` gru WHERE gru.pais_id=$xPaisid AND gru.empr_id=$xEmprid AND gru.grup_nombre='$xGrupo' ";
            $all_param = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
            $xRow = mysqli_num_rows($all_param);

            if($xRow == 0){

                $xSQL = "INSERT INTO `expert_grupos`(pais_id,empr_id,grup_nombre,grup_descripcion,usuariocreacion,terminalcreacion,fechacreacion) ";
                $xSQL .= "VALUES($xPaisid,$xEmprid,'$xGrupo','$xDesc',$xUsuaid,'$xTerminal','{$xFecha}') ";
                mysqli_query($con, $xSQL); 
                
                $resultado = "OK";

            }else{

                $resultado = "EXISTE";
            }
            
        }       
    }

    mysqli_close($con);
    echo $resultado;

?>