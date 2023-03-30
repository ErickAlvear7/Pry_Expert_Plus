<?php
    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');

    @session_start();

    $xTerminal = $_SESSION["s_namehost"];
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());  
    
    $respuesta = "ERR";
    $neworden = 0;

    if(isset($_POST['xxMenu']) and isset($_POST['xxEmprid']) and isset($_POST['xxResult'])){
        if(isset($_POST['xxMenu']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxResult']) <> ''){
            $xEmprid = $_POST['xxEmprid'];
            $xMenu = safe($_POST['xxMenu']);
            $xObservacion = safe($_POST['xxObserva']);
            $xUsuaid = $_POST['xxUsuaid']; 
            $xResult = $_POST['xxResult']; 
            $xEstado =  $_POST['xxEstado'];
        
            $xSQL = "SELECT menu_orden+1 AS Orden FROM `expert_menu` WHERE empr_id=$xEmprid ORDER BY menu_orden DESC LIMIT 1";
            $num_orden = mysqli_query($con, $xSQL);
            foreach( $num_orden as $orden){
                $neworden = $orden['Orden'];
            }            
        
            $xSQL = "INSERT INTO `expert_menu` (empr_id,mepa_id,menu_orden,menu_descripcion,menu_observacion,menu_estado,fechacreacion,usuariocreacion,terminalcreacion) ";
            $xSQL .="VALUES ($xEmprid,-1,$neworden,'$xMenu','$xObservacion','$xEstado','{$xFecha}',$xUsuaid,'$xTerminal')";

            if(mysqli_query($con, $xSQL)){
                $idmenu = mysqli_insert_id($con);
                
                $neworden = 0;
                foreach( $xResult as $tareid){

                    $xSQL = "SELECT meta_orden+1 AS Orden FROM `expert_menu_tarea` WHERE empr_id=$xEmprid AND menu_id=$idmenu ORDER BY meta_orden DESC LIMIT 1";
                    $num_orden = mysqli_query($con, $xSQL);
                    foreach( $num_orden as $orden){
                        $neworden = $orden['Orden'];
                    }

                    $xSQL ="INSERT INTO `expert_menu_tarea`(menu_id,empr_id,tare_id,meta_orden) ";
                    $xSQL .="VALUES ($idmenu,$xEmprid,$tareid,$neworden)";
                    mysqli_query($con, $xSQL);
                }
        
                $respuesta = "OK";
            }
            //print json_encode($data, JSON_UNESCAPED_UNICODE);        
        }
    }

    echo $respuesta;
?>