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

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxGrupoid'])){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxGrupoid']) <> ''){    

            $xPaisid= $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xGrupoid = $_POST['xxGrupoid'];

            $xSQL = "SELECT * FROM `expert_grupos` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND grup_id=$xGrupoid ";
            $all_grupo = mysqli_query($con, $xSQL); 
            foreach ($all_grupo as $grupo) {
                $xRow = $grupo['secuencial_agendado'];
            }
        }
    }

    mysqli_close($con);
    echo $xRow;

?>