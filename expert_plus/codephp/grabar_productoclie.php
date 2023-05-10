<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');    

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

 
    $xresultado = "ERR";

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxClieid']) and isset($_POST['xxResult'])){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxClieid']) <> '' and isset($_POST['xxResult']) <> ''){

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xClieid = $_POST['xxClieid'];
            $xResult = $_POST['xxResult'];


            foreach($xResult as $drfila){

                $xProducto = $drfila['arryproducto'];
                $xDesc = $drfila['arrydescripcion'];
                $xCosto = $drfila['arrycosto'];
                $xGrupo = $drfila['arrygrupo'];
                $xCober = $drfila['arrycober'];
                $xSist =  $drfila['arrysist'];
                $xAsismes = $drfila['arryasismes'];
                $xAsisanu = $drfila['arryasisanu'];
                $xEstado =  $drfila['arryestado'];
                $xGerencial = $drfila['arrygerencial'];
            
                $xSQL = "INSERT INTO `expert_productos` (clie_id,pais_id,empr_id,prod_nombre,prod_descripcion,prod_costo,prod_grupo, ";
                $xSQL .= "prod_asistmes,prod_asistanu,prod_cobertura,prod_sistema,prod_gerencial,prod_estado) ";
                $xSQL .= "VALUES ($xClieid,$xPaisid,$xEmprid,'$xProducto','$xDesc','$xCosto','$xGrupo',$xAsismes,$xAsisanu,'$xCober', ";
                $xSQL .= "'$xSist','$xGerencial','$xEstado' )";
                mysqli_query($con, $xSQL);
            
            }

            $xresultado="OK";
        }    
    }

    echo $xresultado;

?>