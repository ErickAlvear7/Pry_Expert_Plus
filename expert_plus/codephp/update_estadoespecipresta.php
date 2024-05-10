<?php 

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xresultado = "ERR";  

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprId']) and isset($_POST['xxPreeid']) and isset($_POST['xxEstado'])){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprId']) <> '' and isset($_POST['xxPreeid']) <> '' and isset($_POST['xxEstado']) <> ''){  
            
            $xPaisid = $_POST['xxPadeid'];
            $xEmprid = $_POST['xxEstado'];
            $xPresid = $_POST['xxPreeid'];
            $xEstado = $_POST['xxEstado'];

            if($xEstado == 'ACTIVO'){
                $xSql = "UPDATE `expert_prestadora_especialidad` SET pree_estado='A' WHERE pree_id=$xPresid ";
            }else if($xEstado == 'INACTIVO'){
                $xSql = "UPDATE `expert_prestadora_especialidad` SET pree_estado='I' WHERE pree_id=$xPresid ";
            }

            if(mysqli_query($con, $xSql)){
                $xresultado="OK";
            }            
        }
    }

    echo $xresultado;

?>