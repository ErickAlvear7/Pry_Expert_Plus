<?php

//error_reporting(E_ALL);
ini_set('display_errors', 0);

putenv("TZ=America/Guayaquil");
date_default_timezone_set('America/Guayaquil');    

require_once("../dbcon/config.php");
require_once("../dbcon/functions.php");

mysqli_query($con,'SET NAMES utf8');  
mysqli_set_charset($con,'utf8');

$xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
$xTerminal = gethostname();    

$xLastid = 0;  

if(isset($_POST['xxTituid']) and isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxProdid']) and isset($_POST['xxUsuaid'])){
    if(isset($_POST['xxTituid']) <> '' and isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> ''  and isset($_POST['xxProdid']) <> '' and isset($_POST['xxUsuaid']) <> ''){
        
        $xTituid = $_POST['xxTituid'];
        $xPaisid = $_POST['xxPaisid'];
        $xEmprid = $_POST['xxEmprid'];
        $xProdid = $_POST['xxProdid'];
        $xUsuaid = $_POST['xxUsuaid'];
        $xTipodocumento = $_POST['xxTipodocu'];
        $xDocumento = trim($_POST['xxDocumento']);
        $xNombre =  trim(mb_strtoupper(safe($_POST['xxNombres'])));
        $xApellido = trim(mb_strtoupper(safe($_POST['xxApellidos'])));
        $xGenero = $_POST['xxGenero'];
        $xEstadocivil = $_POST['xxEstadocicvil'];
        $xCiudad = $_POST['xxCiudad'];
        $xDireccion = trim(mb_strtoupper(safe($_POST['xxDireccion'])));
        $xTelcasa =  trim($_POST['xxTelcasa']);
        $xTelofi = trim($_POST['xxTelofi']);
        $xCelular = trim($_POST['xxCelular']);
        $xEmail = trim(mb_strtolower(safe($_POST['xxEmail'])));
        $xParentesco = $_POST['xxParentesco'];
        $xFechanacimiento = $_POST['xxFechanaci'];

        
        $xSQL = "INSERT INTO `expert_beneficiario` (titu_id,pais_id,empr_id,prod_id,bene_tipoidentificacion,bene_numerodocumento,bene_nombres,bene_apellidos,  ";
        $xSQL .= "bene_genero,bene_estadocivil,bene_ciudad,bene_direccion,bene_telefonocasa,bene_telefonoficina,bene_celular, ";
        $xSQL .= "bene_email,bene_parentesco,bene_fechanacimiento,fechacreacion,usuariocreacion,terminalcreacion) ";
        $xSQL .= "VALUES ($xTituid,$xPaisid,$xEmprid,$xProdid,'$xTipodocumento','$xDocumento','$xNombre','$xApellido','$xGenero','$xEstadocivil',$xCiudad,'$xDireccion', ";
        $xSQL .= "'$xTelcasa','$xTelofi','$xCelular','$xEmail','$xParentesco','{$xFechanacimiento}','{$xFecha}',$xUsuaid,'$xTerminal')";
        mysqli_query($con, $xSQL);
        $xLastid = mysqli_insert_id($con);

    }    
}

mysqli_close($con);
echo $xLastid;

?>