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

    if(isset($_POST['xxClieid']) and isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxUsuaid']) and isset($_POST['xxCliente']) ){
        if(isset($_POST['xxClieid']) <> '' and isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxCliente']) <> '' ){
            
            $xClieid = $_POST['xxClieid'];
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xUsuaid = $_POST['xxUsuaid']; 
            $xProvid = $_POST['xxProvid']; 
            $xCliente = trim(mb_strtoupper(safe($_POST['xxCliente'])));
            $xDesc = trim(safe($_POST['xxDescrip']));
            $xDireccion = trim(mb_strtoupper(safe($_POST['xxDirec'])));
            $xUrl = trim(safe($_POST['xxUrl']));
            $xFono1 = trim(safe($_POST['xxFono1']));
            $xFono2 = trim(safe($_POST['xxFono2']));
            $xFono3 = trim(safe($_POST['xxFono3']));
            $xCelular1 = trim(safe($_POST['xxCel1']));
            $xCelular2 = trim(safe($_POST['xxCel2']));
            $xCelular3 = trim(safe($_POST['xxCel3']));
            $xEmail1 = trim(safe($_POST['xxEmail1']));
            $xEmail2 = trim(safe($_POST['xxEmail2']));
            $xCambiarcab = trim(safe($_POST['xxCambiarcab']));
            $xCambiarpie = trim(safe($_POST['xxCambiarpie']));
        

            $xProvidant = $_POST['xxProvidant'];
            $xCienteant = trim(mb_strtoupper(safe($_POST['xxClieant'])));

            if($xCambiarcab == 'SI'){
                $xFile = (isset($_FILES['xxFileCab']["name"])) ? $_FILES['xxFileCab']["name"] : '';
                $xPath = "../logos/";

                $xFechafile = new DateTime();
                $xNombreFile = ($xFile != "") ? $xFechafile->getTimestamp() . "_" . $_FILES["xxFileCab"]["name"] : "";  
                
                if($xFile != ''){
                    $xTmpFile = $_FILES["xxFileCab"]["tmp_name"];
    
                    if($xTmpFile != ""){
                        move_uploaded_file($xTmpFile,$xPath.$xNombreFile);
                    }
                }else{
                    $xNombreFile = "companyname.png";
                } 
    
                 

            }

            if($xCambiarpie == 'SI'){

                $xFile = (isset($_FILES['xxFilePie']["name"])) ? $_FILES['xxFilePie']["name"] : '';
                $xPath = "../logos/";

                $xFechafile = new DateTime();
                $xNombreFilePie = ($xFilepie != "") ? $xFechafile->getTimestamp() . "_" . $_FILES["xxFilePie"]["name"] : ""; 

                if($xFilepie != ''){
                    $xTmpFile = $_FILES["xxFilePie"]["tmp_name"];
    
                    if($xTmpFile != ""){
                        move_uploaded_file($xTmpFile,$xPath.$xNombreFilePie);
                    }
                }else{
                    $xNombreFilePie = "companyname.png";
                } 

            }

            if($xProvid != $xProvidant){
                $xSQL = "SELECT * FROM `expert_cliente` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND clie_nombre='$xCliente' ";
                $all_datos = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
                $xRow = mysqli_num_rows($all_datos);                
            }else{
                if($xCliente != $xCienteant){
                    $xSQL = "SELECT * FROM `expert_cliente` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND clie_nombre='$xCliente' ";
                    $all_datos = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
                    $xRow = mysqli_num_rows($all_datos);                       
                }
            }

            if($xRow == 0){

                $xSQL = "UPDATE `expert_cliente` SET prov_id=$xProvid,clie_nombre='$xCliente',clie_descripcion ='$xDesc',clie_direccion='$xDireccion', ";
                $xSQL .= "clie_url='$xUrl',clie_tel1='$xFono1',clie_tel2='$xFono2',clie_tel3='$xFono3',clie_cel1='$xCelular1',clie_cel2='$xCelular2', ";
                $xSQL .= "clie_cel3='$xCelular3',clie_email1='$xEmail1',clie_email2='$xEmail12',clie_imgcab='$xNombreFile',clie_imgpie='$xNombreFilePie' ";
                $xSQL .= "WHERE clie_id=$xClieid AND pais_id=$xPaisid ";
                mysqli_query($con, $xSQL);
                $xRespuesta = "OK";
                
                if(mysqli_query($con, $xSQL)){

                    $xSQL = "INSERT INTO `expert_logs`(log_detalle,usua_id,pais_id,empr_id,log_fechacreacion,log_terminalcreacion) ";
                    $xSQL .= "VALUES('Cambio Datos CLiente',$xUsuaid,$xPaisid,$xEmprid,'{$xFecha}','$xTerminal') ";
                    mysqli_query($con, $xSQL);                
                }
            }

     
        }
    }
    
    //echo $xRespuesta;
    print json_encode($xRespuesta, JSON_UNESCAPED_UNICODE);
	
?>	