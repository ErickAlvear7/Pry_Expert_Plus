<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');    

    //file_put_contents('log_seguimiento_grabarperfil.txt', 'Ingreso a Grabar' . "\n\n", FILE_APPEND); 

	require_once("../dbcon/config.php");
	require_once("../dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	
    
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();
    $xId = 0;

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxProvid']) and isset($_POST['xxPrestador']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxProvid']) <> '' and isset($_POST['xxPrestador']) <> '' ){
            
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xProvid = $_POST['xxProvid']; 
            $xUsuaid = $_POST['xxUsuaid']; 
            $xPrestador = trim(mb_strtoupper(safe($_POST['xxPrestador'])));
            $xSector = trim(safe($_POST['xxSector']));
            $xTipoPesta = trim(safe($_POST['xxTipo']));
            $xDireccion = trim(mb_strtoupper(safe($_POST['xxDireccion'])));
            $xUrl = trim(safe($_POST['xxUrl']));
            $xFono1 = trim(safe($_POST['xxFono1']));
            $xFono2 = trim(safe($_POST['xxFono2']));
            $xFono3 = trim(safe($_POST['xxFono3']));
            $xCelular1 = trim(safe($_POST['xxCelular1']));
            $xCelular2 = trim(safe($_POST['xxCelular2']));
            $xCelular3 = trim(safe($_POST['xxCelular3']));
            $xEmail1 = trim(safe($_POST['xxEmail1']));
            $xEnviar1 = trim(safe($_POST['xxEnviar1']));
            $xEmail2 = trim(safe($_POST['xxEmail2']));
            $xEnviar2 = trim(safe($_POST['xxEnviar2']));

            $xFile = (isset($_FILES['xxFile']["name"])) ? $_FILES['xxFile']["name"] : '';
            $xPath = "../assets/images/prestadores/";

            $xFechafile = new DateTime();
            $xNombreFile = ($xFile != "") ? $xFechafile->getTimestamp() . "_" . $_FILES["xxFile"]["name"] : "";            

            if($xFile != ''){
                $xTmpFile = $_FILES["xxFile"]["tmp_name"];
                if($xTmpFile != ""){
                    move_uploaded_file($xTmpFile,$xPath.$xNombreFile);
                }
            }else{
                $xNombreFile = "logo.png";
            }             

            $xSQL = "INSERT INTO `expert_prestadora`(pais_id,empr_id,prov_id,pres_nombre,pres_sector,pres_tipoprestador,pres_direccion,pres_url,pres_fono1,pres_fono2,pres_fono3,pres_celular1,pres_celular2,pres_celular3,pres_email1,pres_enviar1,pres_email2,pres_enviar2,pres_logo,fechacreacion,usuariocreacion,terminalcreacion) ";
            $xSQL .= "VALUES($xPaisid,$xEmprid,$xProvid,'$xPrestador','$xSector','$xTipoPesta','$xDireccion','$xUrl','$xFono1','$xFono2','$xFono3','$xCelular1','$xCelular2','$xCelular3','$xEmail1','$xEnviar1','$xEmail2','$xEnviar2','$xNombreFile','{$xFecha}',$xUsuaid,'$xTerminal')";
            if(mysqli_query($con, $xSQL)){

                $xId = mysqli_insert_id($con);

                $xSQL = "INSERT INTO `expert_logs`(log_detalle,usua_id,pais_id,empr_id,log_fechacreacion,log_terminalcreacion) ";
                $xSQL .= "VALUES('Nuevo Prestador Agregado',$xUsuaid,$xPaisid,$xEmprid,'{$xFecha}','$xTerminal') ";
                mysqli_query($con, $xSQL);                
            }
        }
    }
    
    echo $xId;
	
?>	