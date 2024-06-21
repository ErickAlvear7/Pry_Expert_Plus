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
    $xRow = 0;
    $xRespuesta = "ERR";

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxPresid']) and isset($_POST['xxProvid']) and isset($_POST['xxPrestador']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxPresid']) <> '' and isset($_POST['xxProvid']) <> '' and isset($_POST['xxPrestador']) <> '' ){
            
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xPresid = $_POST['xxPresid'];
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
            $xCambiarlogo = trim(safe($_POST['xxCambiarlogo']));
            $xLogo = trim(safe($_POST['xxLogo']));

            $xProvidant = $_POST['xxProvidant'];
            $xPrestadorant = trim(mb_strtoupper(safe($_POST['xxPrestadorant'])));

            if($xCambiarlogo == 'SI'){
                $xFile = (isset($_FILES['xxFile']["name"])) ? $_FILES['xxFile']["name"] : '';

                $xPath = "../assets/images/prestadores/";            

                $xFechafile = new DateTime();
                $xNombreFile = ($xFile != "") ? $xFechafile->getTimestamp() . "_" . $_FILES["xxFile"]["name"] : "";            
    
                if($xFile != ''){
                    $xTmpFile = $_FILES["xxFile"]["tmp_name"];
    
                    if($xTmpFile != ""){
                        move_uploaded_file($xTmpFile,$xPath.$xNombreFile);
                    }
                }

            }else{
                $xNombreFile = $xLogo;
            }

            if($xProvid != $xProvidant){
                $xSQL = "SELECT * FROM `expert_prestadora` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND prov_id=$xProvid AND pres_nombre='$xPrestador' ";
                $all_datos = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
                $xRow = mysqli_num_rows($all_datos);                
            }else{
                if($xPrestador != $xPrestadorant){
                    $xSQL = "SELECT * FROM `expert_prestadora` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND prov_id=$xProvid AND pres_nombre='$xPrestador' ";
                    $all_datos = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
                    $xRow = mysqli_num_rows($all_datos);                       
                }
            }

            if($xRow == 0){

                $xSQL = "UPDATE `expert_prestadora` SET prov_id=$xProvid,pres_nombre='$xPrestador',pres_sector='$xSector',pres_tipoprestador='$xTipoPesta',";
                $xSQL .= "pres_direccion='$xDireccion',pres_url='$xUrl',pres_fono1='$xFono1',pres_fono2='$xFono2',pres_fono3='$xFono3',pres_celular1='$xCelular1',";
                $xSQL .= "pres_celular2='$xCelular2',pres_celular3='$xCelular3',pres_email1='$xEmail1',pres_enviar1='$xEnviar1',pres_email2='$xEmail2',pres_enviar2='$xEnviar2',";
                $xSQL .= "pres_logo='$xNombreFile' WHERE pais_id= $xPaisid AND empr_id=$xEmprid AND pres_id=$xPresid ";
                mysqli_query($con, $xSQL);
                $xRespuesta = "OK";
                
                if(mysqli_query($con, $xSQL)){

                    $xSQL = "INSERT INTO `expert_logs`(log_detalle,usua_id,pais_id,empr_id,log_fechacreacion,log_terminalcreacion) ";
                    $xSQL .= "VALUES('Cambio Datos Prestador',$xUsuaid,$xPaisid,$xEmprid,'{$xFecha}','$xTerminal') ";
                    mysqli_query($con, $xSQL);                
                }
            }
        }
    }
    
    //echo $xRespuesta;
    print json_encode($xRespuesta, JSON_UNESCAPED_UNICODE);
	
?>	