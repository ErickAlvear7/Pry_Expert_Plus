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

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxGrupo'])){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxGrupo']) <> ''){    

            $xPaisid= $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xGrupo = trim(mb_strtoupper(safe($_POST['xxGrupo'])));

            $xSQL = "SELECT * FROM `expert_grupos` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND grup_nombre='$xGrupo' ";
            $all_param = mysqli_query($con, $xSQL);
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