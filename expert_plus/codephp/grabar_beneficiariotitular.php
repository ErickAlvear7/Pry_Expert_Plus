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


$xresultado = "ERR";

if(isset($_POST['xxTituid']) and isset($_POST['xxUsuaid']) and isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxResult'])){
    if(isset($_POST['xxTituid']) <> '' and isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxResult']) <> ''){

        $xTituid = $_POST['xxTituid'];
        $xUsuaid = $_POST['xxUsuaid'];
        $xPaisid = $_POST['xxPaisid'];
        $xEmprid = $_POST['xxEmprid'];
        $xResult = $_POST['xxResult'];


        foreach($xResult as $drfila){

            $xTipodocumento = $drfila['arrytipodocumento'];
            $xDocumento = $drfila['arrydocumento'];
            $xNombre = trim(mb_strtoupper(safe($drfila['arrynombre'])));
            $xApellido = trim(mb_strtoupper(safe($drfila['arryapellido'])));
            $xGenero = $drfila['arrygenero'];
            $xEstadocivil = $drfila['arryestadocivil'];
            $xCiudad = $drfila['arryciudad'];
            $xDireccion = trim(mb_strtoupper(safe($drfila['arrydireccion'])));
            $xTelcasa =  $drfila['arrytelcasa'];
            $xTelofi = $drfila['arrytelofi'];
            $xCelular = $drfila['arrycelular'];
            $xEmail = trim(mb_strtolower($drfila['arryemail']));
            $xParentesco = $drfila['arryparentesco'];
            $xFechanacimiento = $drfila['arryfechanacimiento'];
        
            $xSQL = "INSERT INTO `expert_beneficiario` (titu_id,pais_id,empr_id,bene_tipoidentificacion,bene_numerodocumento,bene_nombres,bene_apellidos,  ";
            $xSQL .= "bene_genero,bene_estadocivil,bene_ciudad,bene_direccion,bene_telefonocasa,bene_telefonoficina,bene_celular, ";
            $xSQL .= "bene_email,bene_parentesco,bene_fechanacimiento,fechacreacion,usuariocreacion,terminalcreacion) ";
            $xSQL .= "VALUES ($xTituid,$xPaisid,$xEmprid,'$xTipodocumento','$xDocumento','$xNombre','$xApellido','$xGenero','$xEstadocivil',$xCiudad,'$xDireccion', ";
            $xSQL .= "'$xTelcasa','$xTelofi','$xCelular','$xEmail','$xParentesco','{$xFechanacimiento}','{$xFecha}',$xUsuaid,'$xTerminal')";
            mysqli_query($con, $xSQL);

        
        }

        $xresultado="OK";
    }    
}

echo $xresultado;

?>