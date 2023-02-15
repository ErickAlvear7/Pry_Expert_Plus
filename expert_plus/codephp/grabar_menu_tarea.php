<?php
    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    $xTerminal = gethostname();
    $data = "ERROR";

    if(isset($_POST['xxMenu']) and isset($_POST['xxEmprid']) and isset($_POST['xxResult'])){
        if(isset($_POST['xxMenu']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxResult']) <> ''){
            $yEmprid = $_POST['xxEmprid'];
            $xMenu = safe($_POST['xxMenu']);
            $xObservacion = safe($_POST['xxObserva']);
            $yUserid = $_POST['xxUserid']; 
            $xResult = $_POST['xxResult']; 
            $xEstado =  $_POST['xxEstado'];
        
        
                $xSQL ="INSERT INTO `expert_menu` (empr_id,menu_orden,menu_descripcion,menu_observacion,menu_estado,fechacreacion,usuariocreacion,terminalcreacion) ";
                $xSQL .="VALUES ($yEmprid,2,'$xMenu','$xObservacion','$xEstado','{$xFecha}',$yUserid,'$xTerminal')";
                if(mysqli_query($con, $xSQL)){
                    $idmenu = mysqli_insert_id($con);
        
                    foreach( $xResult as $submenu){
                        $xSQL ="INSERT INTO `expert_menu_tarea`(menu_id,empr_id,tare_id,meta_orden) ";
                        $xSQL .="VALUES ($idmenu,$yEmprid,'$submenu',4)";
                        mysqli_query($con, $xSQL);
                    }
            
                    $data = "OK";
                }

        

            print json_encode($data, JSON_UNESCAPED_UNICODE);
        
        }


    }

?>