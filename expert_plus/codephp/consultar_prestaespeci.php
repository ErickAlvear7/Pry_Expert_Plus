<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "error_consultaprestaespeci.txt";
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());
    $xTerminal = gethostname();   
    $xRow = 0;
    $xLastid = 0;  

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxUsuaid']) and isset($_POST['xxEspeid']) and isset($_POST['xxPresid']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxEspeid']) <> '' and isset($_POST['xxPresid']) <> '' ){

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xUsuaid = $_POST['xxUsuaid'];
            $xEspeid = $_POST['xxEspeid'];
            $xPresid = $_POST['xxPresid'];
            $xPvp = $_POST['xxPvp'];
            $xCosto = $_POST['xxCosto'];

            $xSQL = "SELECT * FROM `expert_prestadora_especialidad` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pres_id=$xPresid AND espe_id=$xEspeid ";
            $all_datos = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
            $xRow = mysqli_num_rows($all_datos); 
            
            if($xRow == 0){
                $xSQL = "INSERT INTO `expert_prestadora_especialidad`(pais_id,empr_id,pres_id,espe_id,pree_pvp,pree_costo,fechacreacion,usuariocreacion,terminalcreacion) ";
                $xSQL .= "VALUES($xPaisid,$xEmprid,$xPresid,$xEspeid,$xPvp,$xCosto,'{$xFecha}',$xUsuaid,'$xTerminal')";
                mysqli_query($con, $xSQL);                    
                $xLastid = mysqli_insert_id($con);
            }
        }
    }
    
    mysqli_close($con);
    echo $xLastid;

?>