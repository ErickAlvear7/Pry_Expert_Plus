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

    $xResultado = 0;
    $xPacaid = 0;
    $xOrden = 0;

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxUsuaid']) and isset($_POST['xxTipoProfe']) and isset($_POST['xxValCodigoProf']) ){

        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxUsuaid']) <> '' and isset($_POST['xxTipoProfe']) <> '' and isset($_POST['xxValCodigoProf']) <> '' ){

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = safe($_POST['xxEmprid']);
            $xUsuaid = safe($_POST['xxUsuaid']);
            $xTipoProfe = mb_strtoupper(safe($_POST['xxTipoProfe']));
            $xValCodigo = mb_strtoupper(safe($_POST['xxValCodigoProf']));
            $xPadeid = $_POST['xxPadeid'];

            $xSQL = "SELECT * FROM `expert_parametro_cabecera` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND paca_nombre='Tipo Profesion' ";
            $all_datos = mysqli_query($con, $xSQL);
            if(mysqli_num_rows($all_datos) == 0){
                $xSQL = "INSERT INTO `expert_parametro_cabecera` (pais_id,empr_id,paca_nombre,paca_descripcion,fechacreacion,usuariocreacion,terminalcreacion) ";
                $xSQL .= "VALUES ($xPaisid,$xEmprid,'Tipo Profesion','Tipo de Profesiones registradas','{$xFecha}',$xUsuaid,'$xTerminal')";            
                if(mysqli_query($con, $xSQL)){
                    $xPacaid = mysqli_insert_id($con);
                }
            }else{
                foreach( $all_datos as $datos){
                    $xPacaid = $datos['paca_id'];
                }             
            }

            if($xPacaid > 0 ){
                if($xPadeid == '0'){

                    //BUSCAR SI EXISTE EL NOMBRE Y EL VALOR
                    $xSQL = "SELECT * FROM  `expert_parametro_detalle` WHERE paca_id=$xPacaid AND pade_nombre='$xTipoProfe' ";
                    $all_param1 = mysqli_query($con, $xSQL);
                    if(mysqli_num_rows($all_param1) == 0){
                        $xSQL = "SELECT * FROM  `expert_parametro_detalle` WHERE paca_id=$xPacaid AND pade_valorV='$xValCodigo' ";
                        $all_param2 = mysqli_query($con, $xSQL);
                        if(mysqli_num_rows($all_param2) > 0){
                            $xResultado = 1;
                        }
                    }else{
                        $xResultado = 1;
                    }
    
                    if($xResultado == 0){
                        $xSQL = "SELECT pade_orden+1 AS Orden FROM `expert_parametro_detalle` WHERE paca_id=$xPacaid ORDER BY pade_orden DESC LIMIT 1";
                        $all_orden = mysqli_query($con, $xSQL);
                        foreach( $all_orden as $orden){
                            $xOrden = $orden['Orden'];
                        }
                        
                        $xSQL = "INSERT INTO `expert_parametro_detalle` (paca_id,pade_orden,pade_nombre,pade_valorV) ";
                        $xSQL .= "VALUES ($xPacaid,$xOrden,'$xTipoProfe','$xValCodigo')";
        
                        if(mysqli_query($con, $xSQL)){
                            $last_id = mysqli_insert_id($con);
                        }
                        $xDataDetalle = array(
                            'Pacaid'=> $xPacaid, 
                            'Padeid'=> $last_id);                     
                    }else{
                        $xDataDetalle = array(
                            'Pacaid'=> $xPacaid, 
                            'Padeid'=> -1); 
                    }
                }else{
                    $xSQL = "SELECT * FROM  `expert_parametro_detalle` WHERE paca_id=$xPacaid AND pade_nombre='$xTipoProfe' ";
                    $all_param1 = mysqli_query($con, $xSQL);
                    if(mysqli_num_rows($all_param1) > 0){
                        $xResultado = 1;
                    }                    
                    if($xResultado == 0){

                        $xSQL = "UPDATE `expert_parametro_detalle` SET pade_nombre='$xTipoProfe' WHERE paca_id=$xPacaid AND pade_id=$xPadeid  ";
                        mysqli_query($con, $xSQL);

                        $xDataDetalle = array(
                            'Pacaid'=> $xPacaid,
                            'Padeid'=> $xPadeid);
                    }else{
                        $xDataDetalle = array(
                            'Pacaid'=> $xPacaid,
                            'Padeid'=> -1);
                    }                    
                }
            }else{
                $xDataDetalle = array(
                    'Pacaid'=> -1, 
                    'Padeid'=> -1);                 
            }
        }
    }

    //echo $last_id;
    print json_encode($xDataDetalle, JSON_UNESCAPED_UNICODE);

?>