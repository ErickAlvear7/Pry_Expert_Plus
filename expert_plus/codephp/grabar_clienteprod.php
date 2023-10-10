<?php
    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    //file_put_contents('log_seguimiento.txt', $xSql . "\n\n", FILE_APPEND);    
    
    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	 

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();
    $respuesta = "ERR";
    $last_id = 0;

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxUsuaid']) and isset($_POST['xxProv']) and isset($_POST['xxCliente'])){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxProv']) <> '' and isset($_POST['xxCliente']) <> ''){

            $xPaisid =  $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xUsuaid = $_POST['xxUsuaid'];
            $xProvid = $_POST['xxProv'];
            $xCliente = trim(mb_strtoupper(safe($_POST['xxCliente'])));
            $xDesc = trim(mb_strtoupper(safe($_POST['xxDescrip'])));
            $xDirec = trim(mb_strtoupper(safe($_POST['xxDirec'])));
            $xUrl = safe($_POST['xxUrl']);
            $xTel1 = trim($_POST['xxTel1']);
            $xTel2 = trim($_POST['xxTel2']);
            $xTel3 = trim($_POST['xxTel3']);
            $xCel1 = trim($_POST['xxCel1']);
            $xCel2 = trim($_POST['xxCel2']);
            $xCel3 = trim($_POST['xxCel3']);
            $xEmail1 = trim(safe($_POST['xxEmail1']));
            $xEmail2 = trim(safe($_POST['xxEmail2']));

            $xFile = (isset($_FILES['xxFileCab']["name"])) ? $_FILES['xxFileCab']["name"] : '';
            $xFilepie = (isset($_FILES['xxFilePie']["name"])) ? $_FILES['xxFilePie']["name"] : '';

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

            $xNombreFilePie = ($xFilepie != "") ? $xFechafile->getTimestamp() . "_" . $_FILES["xxFilePie"]["name"] : "";  

            if($xFilepie != ''){
                $xTmpFile = $_FILES["xxFilePie"]["tmp_name"];

                if($xTmpFile != ""){
                    move_uploaded_file($xTmpFile,$xPath.$xNombreFilePie);
                }
            }else{
                $xNombreFilePie = "companyname.png";
            } 

            
            $xSQL = "INSERT INTO `expert_cliente` (pais_id,empr_id,prov_id,clie_nombre,clie_descripcion,clie_direccion, ";
            $xSQL .= "clie_url,clie_tel1,clie_tel2,clie_tel3,clie_cel1,clie_cel2,clie_cel3,clie_email1,clie_email2, ";
            $xSQL .= "clie_imgcab,clie_imgpie,usuariocreacion,fechacreacion,terminalcreacion ) ";
            $xSQL .= "VALUES($xPaisid,$xEmprid,$xProvid,'$xCliente','$xDesc','$xDirec','$xUrl','$xTel1','$xTel2', ";
            $xSQL .= "'$xTel3','$xCel1','$xCel2','$xCel3','$xEmail1','$xEmail2','$xNombreFile','$xNombreFilePie',$xUsuaid, ";
            $xSQL .= "'{$xFecha}','$xTerminal') ";

            if(mysqli_query($con, $xSQL)){

                $last_id = mysqli_insert_id($con);
                
            }

        }
    }

    echo $last_id;
?>