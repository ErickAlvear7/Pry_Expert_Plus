<?php

//error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once("../dbcon/config.php");
require_once("../dbcon/functions.php");

mysqli_query($con,'SET NAMES utf8');  
mysqli_set_charset($con,'utf8');	

$xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
$xTerminal = gethostname();
$data = "ERROR";


if(isset($_POST['xxUsuId']) and isset($_POST['xxEmprId'])){

    $yUsuId = $_POST['xxUsuId'];
    $yEmprId = $_POST['xxEmprId'];

    $xSQL ="UPDATE `expert_usuarios` SET usua_password = MD5('12345'),usua_fechacreacion = '{$xFecha}',usua_terminalcreacion= '$xTerminal' ";
    $xSQL .="WHERE usua_id = $yUsuId AND empr_id = $yEmprId ";
    if(mysqli_query($con, $xSQL)){
        $data = "OK";
    }else{
        $data = "ERROR";
    }

    print json_encode($data, JSON_UNESCAPED_UNICODE);
}



?>