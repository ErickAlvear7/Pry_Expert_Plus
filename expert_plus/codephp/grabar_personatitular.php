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
    $last_id_persona = 0;
    $last_id_titular = 0;

    if(isset($_POST['xxUsuaid']) and isset($_POST['xxProdid']) and isset($_POST['xxGrupid']) and isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxTipoDocumento']) and isset($_POST['xxDocumento']) and isset($_POST['xxNombre']) and isset($_POST['xxApellido']) and isset($_POST['xxGenero'])){
        if(isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxProdid']) <> '' and isset($_POST['xxGrupid']) <> '' and isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxTipoDocumento']) <> '' and isset($_POST['xxDocumento']) <> '' and isset($_POST['xxNombre']) <> '' and isset($_POST['xxApellido']) <> '' and isset($_POST['xxGenero']) <> ''){
            
            $xUsuaid = $_POST['xxUsuaid'];
            $xProdid = $_POST['xxProdid'];
            $xGrupid = $_POST['xxGrupid'];
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xTipoDocumento =  $_POST['xxTipoDocumento'];
            $xDocumento = $_POST['xxDocumento'];
            $xNombre = trim(mb_strtoupper(safe($_POST['xxNombre'])));
            $xApellido = trim(mb_strtoupper(safe($_POST['xxApellido'])));
            $xGenero = $_POST['xxGenero'];
            $xEstadoCivil = $_POST['xxEstadoCivil'];
            $xFechaNacimiento = $_POST['xxFechaNacimiento'];
            $xCiudadId = $_POST['xxCiudadId'];
            $xDireccion = trim(mb_strtoupper(safe($_POST['xxDireccion'])));
            $xTelCasa= $_POST['xxTelCasa'];
            $xTelOfi = $_POST['xxTelOfi'];
            $xCelular = $_POST['xxCelular'];
            $xEmail = safe($_POST['xxEmail']);
            $xFechaIniCobertura = $_POST['xxFechaIniCobertura'];
            $xFechaFinCobertura = $_POST['xxFechaFinCobertura'];

            $xFile = (isset($_FILES['xxImgTitu']["name"])) ? $_FILES['xxImgTitu']["name"] : '';

            $xPath = "../logos/";

            $xFechafile = new DateTime();
            $xNombreFile = ($xFile != "") ? $xFechafile->getTimestamp() . "_" . $_FILES["xxImgTitu"]["name"] : "";  
            
            if($xFile != ''){
                $xTmpFile = $_FILES["xxImgTitu"]["tmp_name"];

                if($xTmpFile != ""){
                    move_uploaded_file($xTmpFile,$xPath.$xNombreFile);
                }
            }else{
                $xNombreFile = "companyname.png";
            } 

            
            $xSQL = "INSERT INTO `expert_persona` (pais_id,empr_id,pers_tipoidentificacion,pers_numerodocumento,pers_nombres,pers_apellidos,pers_imagen,pers_genero, ";
            $xSQL .= "pers_estadocivil,pers_fechanacimiento,pers_ciudad,pers_direccion,pers_telefonocasa,pers_telefonoficina, ";
            $xSQL .= "pers_celular,pers_email,fechacreacion,usuariocreacion,terminalcreacion ) ";
            $xSQL .= "VALUES($xPaisid,$xEmprid,'$xTipoDocumento','$xDocumento','$xNombre','$xApellido','$xNombreFile','$xGenero','$xEstadoCivil','{$xFechaNacimiento}',$xCiudadId, ";
            $xSQL .= "'$xDireccion','$xTelCasa','$xTelOfi','$xCelular','$xEmail','{$xFecha}',$xUsuaid,'$xTerminal') ";
          
            if(mysqli_query($con, $xSQL)){ 
                $last_id_persona = mysqli_insert_id($con);  
            }

            $xSQL ="INSERT INTO `expert_titular` (pais_id,empr_id,pers_id,prod_id,grup_id,titu_fechainiciocobertura,titu_fechafincobertura, ";
            $xSQL .="fechacreacion,usuariocreacion,terminalcreacion) ";
            $xSQL .="VALUES($xPaisid,$xEmprid,$last_id_persona,$xProdid,$xGrupid,'{$xFechaIniCobertura}','{$xFechaFinCobertura}','{$xFecha}',$xUsuaid,'$xTerminal' )";

            if(mysqli_query($con, $xSQL)){ 
                $last_id_titular = mysqli_insert_id($con);  
            }

        }
    }

    echo $last_id_titular;
?>