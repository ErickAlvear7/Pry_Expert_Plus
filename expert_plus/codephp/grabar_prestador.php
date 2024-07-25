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
            $xTipoPresta = trim(safe($_POST['xxTipo']));
            $xDireccion = trim(mb_strtoupper(safe($_POST['xxDireccion'])));
            $xUbicacion = trim(safe($_POST['xxUbicacion']));
            $xUrl = trim(safe($_POST['xxUrl']));
            $xFono1 = trim(safe($_POST['xxFono1']));
            $xFono2 = trim(safe($_POST['xxFono2']));
            $xCelular1 = trim(safe($_POST['xxCelular1']));
            $xEmail1 = trim(safe($_POST['xxEmail1']));
            $xEnviar1 = trim(safe($_POST['xxEnviar1']));
            $xEmail2 = trim(safe($_POST['xxEmail2']));
            $xEnviar2 = trim(safe($_POST['xxEnviar2']));
            $xEmail3 = trim(safe($_POST['xxEmail3']));
            $xEnviar3 = trim(safe($_POST['xxEnviar3']));
            $xEmail4 = trim(safe($_POST['xxEmail4']));
            $xEnviar4 = trim(safe($_POST['xxEnviar4']));

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

            $xSQL = "INSERT INTO `expert_prestadora`(pais_id,empr_id,prov_id,pres_nombre,pres_sector,pres_tipoprestador,pres_direccion,";
            $xSQL .="pres_ubicacion,pres_url,pres_fono1,pres_fono2,pres_celular1,pres_email1,pres_enviar1,pres_email2,pres_enviar2,";
            $xSQL .="pres_email3,pres_enviar3,pres_email4,pres_enviar4,pres_logo,fechacreacion,usuariocreacion,terminalcreacion)";
            $xSQL .= "VALUES($xPaisid,$xEmprid,$xProvid,'$xPrestador','$xSector','$xTipoPresta','$xDireccion','$xUbicacion','$xUrl','$xFono1',";
            $xSQL .="'$xFono2','$xCelular1','$xEmail1','$xEnviar1','$xEmail2','$xEnviar2','$xEmail3','$xEnviar3','$xEmail4','$xEnviar4',";
            $xSQL .="'$xNombreFile','{$xFecha}',$xUsuaid,'$xTerminal')";

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