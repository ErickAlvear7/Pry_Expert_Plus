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
    $resultado = "ERR";
    $log_file = "log_error_grabartipopresta.txt";

    if(isset($_POST['xxPaisId']) and isset($_POST['xxUsuaId']) and isset($_POST['xxEmprId']) and isset($_POST['xxTipoPrestador']) and isset($_POST['xxValor']) ){
        if(isset($_POST['xxPaisId']) <> '' and isset($_POST['xxUsuaId']) <> '' and isset($_POST['xxEmprId']) <> '' and isset($_POST['xxTipoPrestador']) <> '' and isset($_POST['xxValor']) <> ''){    

            $xPaisid = $_POST['xxPaisId'];
            $xEmprid = $_POST['xxEmprId'];            
            $xUsuaid = $_POST['xxUsuaId'];            
            $xTipoPrestador = trim(safe($_POST['xxTipoPrestador']));
            $xValor = safe($_POST['xxValor']);
            $xId = 0;
            $xNeworden = 0;

            $xSQL = "SELECT paca_id FROM `expert_parametro_cabecera` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND paca_nombre='Tipo Prestador' ";
            $all_codigo = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
            foreach ($all_codigo as $codigo) {
                $xId = $codigo['paca_id'];
            }

            if($xId != 0)
            {
                $xSQL = "SELECT * FROM `expert_parametro_detalle` pde, `expert_parametro_cabecera` pca WHERE pca.paca_nombre='Tipo Prestador' AND pde.pade_nombre='$xTipoPrestador' OR pde.pade_valorV='$xValor' AND ";
                $xSQL .= "pde.paca_id=pca.paca_id AND  pca.pais_id=$xPaisid AND pca.empr_id=$xEmprid ";
                $all_tipoprestador = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));
                if(mysqli_num_rows($all_tipoprestador) == 0){

                    $xSQL = "SELECT pade_orden+1 AS Orden FROM `expert_parametro_detalle` WHERE paca_id=$xId ORDER BY pade_orden DESC LIMIT 1 ";
                    $num_orden = mysqli_query($con, $xSQL);
                    foreach($num_orden as $orden){
                        $xNeworden = $orden['Orden'];
                    }
                
                    $xSQL = "INSERT INTO `expert_parametro_detalle` (paca_id,pade_orden,pade_nombre,pade_valorV) ";
                    $xSQL .= "VALUES ($xId,$xNeworden,'$xTipoPrestador','$xValor')";
        
                    if(mysqli_query($con, $xSQL)){

                        $xSQL = "SELECT pde.pade_valorV AS Codigo,pde.pade_nombre AS Descripcion FROM `expert_parametro_detalle` pde,`expert_parametro_cabecera` pca WHERE pca.pais_id=$xPaisid ";
                        $xSQL .= "AND pca.paca_nombre='Tipo Prestador' AND pca.paca_id=pde.paca_id AND pca.paca_estado='A' AND pade_estado='A' ";                        
                        $all_datos =  mysqli_query($con, $xSQL);
                        $resultado = '<option></option>';
                        foreach ($all_datos as $especi){ 
                            $resultado .='<option value="'.$especi["Codigo"].'">' . $especi["Descripcion"].'</option>';
                        }                          
                    }            
                }else{
                    $resultado = "EXISTE";
                }
            }else{
                $resultado = "ERR";
            }
        }       
    }

    mysqli_close($con);
    echo $resultado;

?>