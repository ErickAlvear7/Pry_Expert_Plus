<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $resultado = "ERR";
    $xRow = 0;  


    if(isset($_POST['xxPaisId']) and isset($_POST['xxEmprId']) and isset($_POST['xxGrupo'])){
        if(isset($_POST['xxPaisId']) <> '' and isset($_POST['xxEmprId']) <> '' and isset($_POST['xxGrupo']) <> ''){    

            $xPaisid= $_POST['xxPaisId'];
            $xEmprid = $_POST['xxEmprId'];
            $xGrupo = trim(mb_strtoupper(safe($_POST['xxGrupo'])));

            $xSQL = "SELECT * FROM `expert_grupos` gru WHERE gru.pais_id=$xPaisid AND gru.empr_id=$xEmprid AND gru.grup_nombre='$xGrupo' ";
            $all_param = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
            $xRow = mysqli_num_rows($all_param);

            if($xRow == 0){

                $resultado ="OK";

            }else{

                $resultado ="EXISTE";
            }
            
        }       
    }

    mysqli_close($con);
    echo $resultado;

?>