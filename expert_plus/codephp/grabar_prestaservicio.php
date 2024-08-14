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

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxPresid']) and isset($_POST['xxAsisid'])){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxPresid']) <> '' and isset($_POST['xxAsisid']) <> ''){

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xPresid = $_POST['xxPresid'];
            $xAsisid = $_POST['xxAsisid'];
            $xAtencion = trim(mb_strtoupper(safe($_POST['xxAtencion'])));
            $xRed = $_POST['xxRed'];
            $xPvp = $_POST['xxPvp'];
            $xUsuaid = $_POST['xxUsuaid'];

            $xSQL = "SELECT * FROM `expert_prestadora_servicio` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pres_id=$xPresid AND asis_id=$xAsisid AND prse_atencion='$xAtencion'";
            $all_datos = mysqli_query($con, $xSQL);
            $xRow = mysqli_num_rows($all_datos); 
            
            if($xRow == 0){
                $xSQL = "INSERT INTO `expert_prestadora_servicio`(pais_id,empr_id,pres_id,asis_id,prse_atencion,prse_red,prse_pvp,fechacreacion,usuariocreacion,terminalcreacion) ";
                $xSQL .= "VALUES($xPaisid,$xEmprid,$xPresid,$xAsisid,'$xAtencion',$xRed,$xPvp,'{$xFecha}',$xUsuaid,'$xTerminal')";
                mysqli_query($con, $xSQL);                    
                $xLastid = mysqli_insert_id($con);
            }
        }
    }
    
    mysqli_close($con);
    echo $xLastid;

?>