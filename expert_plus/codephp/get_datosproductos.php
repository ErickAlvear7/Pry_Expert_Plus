<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    
    $options = '<option value="0">--Seleccione Perfil--</opcion>';

    if(isset($_POST['xxProid']) and isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) ){
        if(isset($_POST['xxProid']) <> '' and isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> ''){

            $xProid = $_POST['xxProid'];
            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];

            $xDatos = [];

            $xSQL = "SELECT pro.clie_id AS Clieid,pro.grup_id AS Grupid, pro.prod_nombre AS Nombre, pro.prod_descripcion AS Descr, pro.prod_costo AS Costo,gru.grup_nombre AS Grupo, ";
            $xSQL .= "pro.prod_asistmes AS AsistMes,pro.prod_asistanu AS AsistAnu,pro.prod_cobertura AS Cob,pro.prod_sistema AS Sis,pro.prod_gerencial AS Ger ";
            $xSQL .="FROM `expert_productos` pro, `expert_grupos` gru,`expert_cliente` clie WHERE pro.grup_id = gru.grup_id AND clie.clie_id = pro.clie_id AND pro.prod_id = $xProid ";
            $xSQL .="AND pro.pais_id = $xPaisid AND pro.empr_id = $xEmprid ";
            $all_datos = mysqli_query($con, $xSQL);

            foreach($all_datos as $pro) {

                $xClieid = $pro["Clieid"]; 
                $xGrupid = $pro["Grupid"];
                $xProducto = $pro["Nombre"];
                $xDesc = $pro["Descr"];
                $xCosto = $pro["Costo"];
                $xGrupo = $pro["Grupo"];
                $xAsistMes = $pro["AsistMes"];
                $xAsistAnu = $pro["AsistAnu"];
                $xCobertura = $pro["Cob"];
                $xSistema = $pro["Sis"];
                $xGerencial = $pro["Ger"];
 
                $xDatos[] = array(
                    'Clieid'=> $xClieid, 
                    'Grupid'=> $xGrupid, 
                    'Nombre'=> $xProducto, 
                    'Descr'=> $xDesc, 
                    'Costo'=> $xCosto,
                    'Grupo'=> $xGrupo,
                    'AsistMes'=> $xAsistMes,
                    'AsistAnu'=> $xAsistAnu,
                    'Cob'=> $xCobertura,
                    'Sis'=> $xSistema,
                    'Ger'=> $xGerencial,
                
                );    
                
            }

            print json_encode($xDatos, JSON_UNESCAPED_UNICODE);

        }
    }

    mysqli_close($con);
    
?>