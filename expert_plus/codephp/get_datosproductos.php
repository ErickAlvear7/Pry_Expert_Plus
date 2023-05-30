<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $log_file = "err_consulta";
    
    $options = '<option value="0">--Seleccione Perfil--</opcion>';

    if(isset($_POST['xxProid'])){
        if(isset($_POST['xxProid']) <> ''){

            $xProid = $_POST['xxProid'];
            $xDatos = [];

            $xSQL = "SELECT pro.grup_id AS Grupid, pro.prod_nombre AS Nombre, pro.prod_descripcion AS Descr, pro.prod_costo AS Costo,gru.grup_nombre AS Grupo, ";
            $xSQL .= "pro.prod_asistmes AS AsistMes,pro.prod_asistanu AS AsistAnu,pro.prod_cobertura AS Cob,pro.prod_sistema AS Sis,pro.prod_gerencial AS Ger ";
            $xSQL .="FROM `expert_productos` pro, `expert_grupos` gru WHERE pro.grup_id = gru.grup_id AND pro.prod_id = $xProid ";
            $all_datos = mysqli_query($con, $xSQL);

            foreach($all_datos as $pro) {

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