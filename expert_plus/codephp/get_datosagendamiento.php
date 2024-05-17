<?php

    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    if(isset($_POST['xxPaisid']) and isset($_POST['xxEmprid']) and isset($_POST['xxAgendaid']) ){
        if(isset($_POST['xxPaisid']) <> '' and isset($_POST['xxEmprid']) <> '' and isset($_POST['xxAgendaid']) <> '' ){

            $xPaisid = $_POST['xxPaisid'];
            $xEmprid = $_POST['xxEmprid'];
            $xAgendaid = $_POST['xxAgendaid'];

            $xSQL = "SELECT * FROM `expert_agenda` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND agen_id=$xAgendaid  ";
            $all_agenda = mysqli_query($con, $xSQL);
            foreach ($all_agenda as $agenda) {
                $xTipoCliente = $agenda['tipo_cliente'];
                $xTituid = $agenda['titu_id'];
                $xBeneid = $agenda['bene_id'];
                $xProdid = $agenda['prod_id'];
                $xGroupid = $agenda['grup_id'];
                $xPresid = $agenda['pres_id'];
                $xEspeid = $agenda['espe_id'];
                $xPfesid = $agenda['pfes_id'];
                $xCiudid = $agenda['ciud_id'];
                $xFechaInicio = $agenda['fecha_inicio'];
                $xFechaFin = $agenda['fecha_fin'];
                $xDia = $agenda['dia'];
                $xHoraDesde = $agenda['hora_desde'];
                $xHoraHasta = $agenda['hora_hasta'];
                $xObservacion = $agenda['observacion'];
                $xCodigoAgenda = $agenda['codigo_agenda'];
            }

            if($xTipoCliente == "T") {
                $xTipoCliente = "TITULAR";
                $xSQL = "SELECT * FROM `expert_persona` per,`expert_titular` tit WHERE tit.pais_id=$xPaisid AND tit.empr_id=$xEmprid AND tit.titu_id=$xTituid AND tit.pers_id=per.pers_id ";
                $all_titular = mysqli_query($con, $xSQL);
                foreach ($all_titular as $titular) {
                    $xNumDocumento = $titular['pers_numerodocumento'];
                    $xNombres = $titular['pers_nombres'] . ' ' . $titular['pers_apellidos'];
                }
            }else{
                $xTipoCliente = "BENEFICIARIO";
                $xSQL = "SELECT * FROM `expert_beneficiario` ben,`expert_titular` tit WHERE tit.pais_id=$xPaisid AND tit.empr_id=$xEmprid AND tit.titu_id=$xTituid AND tit.titu_id=ben.titu_id AND ben.bene_id=$xBeneid ";
                $all_bene = mysqli_query($con, $xSQL);
                foreach ($all_bene as $bene) {
                    $xNumDocumento = $bene['bene_numerodocumento'];
                    $xNombres = $bene['bene_nombres'] . ' ' . $titular['bene_apellidos'];
                }                
            }

            $xSQL = "SELECT * FROM `expert_productos` pro, `expert_cliente` cli WHERE pro.pais_id=$xPaisid AND pro.empr_id=$xEmprid AND pro.prod_id=$xProdid AND cli.clie_id=pro.clie_id ";
            $all_datos = mysqli_query($con, $xSQL);
            foreach ($all_datos as $datos) {
                $xProducto = $datos['prod_nombre'];
                $xLogoCab = $datos['clie_imgcab'];
            }

            if($xLogoCab == ''){
                $xLogoCab = "companyname.png";
            }

            $xLogoCab = "./logos/$xLogoCab";

            $xSQL = "SELECT * FROM `expert_prestadora` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND pres_id=$xPresid  ";
            $all_datos = mysqli_query($con, $xSQL);
            foreach ($all_datos as $datos) {
                $xPrestadora = $datos['pres_nombre'];
                $xCiudpresta = $datos['prov_id'];
                $xDirecpresta = $datos['pres_direccion'];
                $xFono1presta = $datos['pres_fono1'];
                $xFono2presta = $datos['pres_fono2'];
                $xFono3presta = $datos['pres_fono3'];

                $xSQL = "SELECT * FROM `provincia_ciudad` WHERE prov_id=$xCiudpresta  ";
                $all_ciudad = mysqli_query($con, $xSQL);
                foreach ($all_ciudad as $ciudad) {
                    $xProvincia = $ciudad['provincia'];
                    $xCiudad = $ciudad['ciudad'];
                }
            }

            $xSQL = "SELECT * FROM `expert_especialidad` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND espe_id=$xEspeid  ";
            $all_datos = mysqli_query($con, $xSQL);            
            foreach ($all_datos as $datos) {
                $xEspecialidad = $datos['espe_nombre'];
            }

            $xSQL = "SELECT * FROM `expert_profesional_especi` pfe, `expert_profesional` pro WHERE pfe.pais_id=$xPaisid AND pfe.empr_id=$xEmprid AND pfe.pfes_id=$xPfesid AND  ";
            $xSQL .= "pfe.prof_id=pro.prof_id  ";
            $all_datos = mysqli_query($con, $xSQL);            
            foreach ($all_datos as $datos) {
                $xProfesional = $datos['prof_nombres'] . ' ' . $datos['prof_apellidos'];
            }

            $xDatos = array(
                'TipoCliente'=> $xTipoCliente, 
                'Documento'=> $xNumDocumento, 
                'Nombres'=> $xNombres,
                'Producto'=> $xProducto,
                'Logo'=> $xLogoCab,
                'Prestadora'=> $xPrestadora,
                'DireccionPresta'=> $xDirecpresta,
                'Fono1Presta'=> $xFono1presta,
                'Fono2Presta'=> $xFono2presta,
                'Fono3Presta'=> $xFono3presta,
                'Provincia'=> $xProvincia,
                'Ciudad'=> $xCiudad,
                'Especialidad'=> $xEspecialidad,
                'Profesional'=> $xProfesional,
                'FechaInicio'=> $xFechaInicio,
                'FechaFin'=> $xFechaFin,
                'Dia'=> $xDia,
                'HoraDesde' => $xHoraDesde,
                'HoraHasta' => $xHoraHasta,
                'Observacion'=> $xObservacion,
                'CodigoAgenda'=> $xCodigoAgenda
            );             
        }
    }
    
    mysqli_close($con);
    print json_encode($xDatos, JSON_UNESCAPED_UNICODE);
    
?>