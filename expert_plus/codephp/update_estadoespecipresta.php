<?php 

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xresultado = "ERR";  

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxPreeid']) and isset($_POST['xxEstado'])){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxPreeid']) <> '' and isset($_POST['xxEstado']) <> ''){  
            
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xPreeid = $_POST['xxPreeid'];
            $xEstado = $_POST['xxEstado'];

            if($xEstado == 'ACTIVO'){
                $xSql = "UPDATE `expert_prestadora_especialidad` SET pree_estado='A' WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pree_id=$xPreeid";
            }else if($xEstado == 'INACTIVO'){
                $xSql = "UPDATE `expert_prestadora_especialidad` SET pree_estado='I' WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pree_id=$xPreeid";
            }

            if(mysqli_query($con, $xSql)){
                $xresultado="OK";
            }            
        }
    }

    echo $xresultado;

?>