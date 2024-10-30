<?php 

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xresultado = "ERR";  

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxPresid']) and isset($_POST['xxAsisid']) and isset($_POST['xxEstado'])){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxPresid']) <> '' and isset($_POST['xxAsisid']) <> '' and isset($_POST['xxEstado']) <> ''){  
            
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xPresid = $_POST['xxPresid'];
            $xAsisid = $_POST['xxAsisid'];
            $xEstado = $_POST['xxEstado'];

            if($xEstado == 'ACTIVO' ){
                $xEstado = 'A';
            }else{
                $xEstado = 'I';
            }

            $xSql = "UPDATE `expert_prestadora_servicio` SET prse_estado='$xEstado' WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pres_id=$xPresid AND asis_id=$xAsisid  ";

            if(mysqli_query($con, $xSql)){
                $xresultado="OK";
            }            
        }
    }

    echo $xresultado;

?>