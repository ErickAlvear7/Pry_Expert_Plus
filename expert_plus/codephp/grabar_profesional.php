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
    $xRespuesta = 'ERR';

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxTipoDoc']) and isset($_POST['xxNumDoc']) and isset($_POST['xxNombres']) and isset($_POST['xxTipoProfesion']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxTipoDoc']) <> '' and isset($_POST['xxNumDoc']) <> '' and isset($_POST['xxNombres']) <> '' and isset($_POST['xxTipoProfesion']) <> '' ){
            
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xUsuaid = $_POST['xxUsuaid']; 
            $xTipoDoc = trim(safe($_POST['xxTipoDoc']));
            $xNumDoc = trim(safe($_POST['xxNumDoc']));
            $xNombres = trim(mb_strtoupper(safe($_POST['xxNombres'])));
            $xApellidos = trim(mb_strtoupper(safe($_POST['xxApellidos'])));
            $xGenero = trim(safe($_POST['xxGenero']));
            $xTipoProf = trim(safe($_POST['xxTipoProfesion']));
            $xDireccion = trim(mb_strtoupper(safe($_POST['xxDireccion'])));
            $xFono = trim(safe($_POST['xxFono']));
            $xCelular = trim(safe($_POST['xxCelular']));
            $xEmail = trim(safe($_POST['xxEmail']));
            $xEnviar = trim(safe($_POST['xxEnviar']));

            $xFile = (isset($_FILES['xxFile']["name"])) ? $_FILES['xxFile']["name"] : '';
            $xPath = "../img/";
            $xNombreFile = "";

            if($xFile != ''){
                @$xTipo = $_FILES['xxFile']["tmp_name"];
                @$xMime = mime_content_type($xTipo);
    
                if($xMime == 'application/pdf'){
                    $xExt = '.pdf';
                }
    
                if($xMime == 'image/jpeg' or $xMime == 'image/pjpeg' or $xMime == 'image/jpg' ){
                    $xExt = '.jpg';
                }
    
                if($xMime == 'image/png'){
                    $xExt = '.png';
                }            
    
                $xFechafile = new DateTime();
                //$xNombreFile = ($xFile != "") ? $xNumDoc . "_$xFechafile->getTimestamp()" . "_" . $_FILES["xxFile"]["name"] : "";
                $xNombreFile = ($xFile != "") ? $xNumDoc . "_" . $xFechafile->getTimestamp() . $xExt : "";
            }

            if($xFile != ''){
                $xTmpFile = $_FILES["xxFile"]["tmp_name"];
                if($xTmpFile != ""){
                    move_uploaded_file($xTmpFile,$xPath.$xNombreFile);
                }
            }

            $xSQL = "SELECT * FROM `expert_profesional` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND prof_numdoc='$xNumDoc' ";
            $all_datos = mysqli_query($con, $xSQL);
            if(mysqli_num_rows($all_datos) == 0 )
            {
                $xSQL = "INSERT INTO `expert_profesional`(pais_id,empr_id,prof_tipodoc,prof_numdoc,prof_nombres,prof_apellidos,prof_genero,prof_tipoprofesion,prof_avatar,prof_direccion,prof_telefono,prof_celular,prof_email,prof_enviarmail,fechacreacion,usuariocreacion,terminalcreacion) ";
                $xSQL .= "VALUES($xPaisid,$xEmprid,'$xTipoDoc','$xNumDoc','$xNombres','$xApellidos','$xGenero','$xTipoProf','$xNombreFile','$xDireccion','$xFono','$xCelular','$xEmail','$xEnviar','{$xFecha}',$xUsuaid,'$xTerminal')";
                if(mysqli_query($con, $xSQL)){
    
                    $xId = mysqli_insert_id($con);
    
                    $xSQL = "INSERT INTO `expert_logs`(log_detalle,usua_id,pais_id,empr_id,log_fechacreacion,log_terminalcreacion) ";
                    $xSQL .= "VALUES('Nuevo Profesional Agregado',$xUsuaid,$xPaisid,$xEmprid,'{$xFecha}','$xTerminal') ";
                    mysqli_query($con, $xSQL);  
                    
                    $xRespuesta = 'OK';
                }    
            }else{
                $xRespuesta = 'ERR';
            }
        }
    }
    
    print json_encode($xRespuesta, JSON_UNESCAPED_UNICODE);
	
?>	