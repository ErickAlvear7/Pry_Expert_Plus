<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');
    
    $respuesta = 'ERR';

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxUserid']) and isset($_POST['xxMode']) and isset($_POST['xxIndex'])){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxUserid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxMode']) <> '' and isset($_POST['xxIndex']) <> ''){

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xUserid = $_POST['xxUserid'];
            $xMode = safe($_POST['xxMode']);
            $xIndex = safe($_POST['xxIndex']);

            if($xIndex == 'Menu'){
                $xSQL = "UPDATE `expert_parametro_paginas` SET index_menu='$xMode' WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND usua_id=$xUserid ";    
            }else{
                $xSQL = "UPDATE `expert_parametro_paginas` SET index_content='$xMode' WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND usua_id=$xUserid ";
            }

            mysqli_query($con, $xSQL);
            $respuesta = 'OK';
        }
    }

    echo $respuesta;
?>