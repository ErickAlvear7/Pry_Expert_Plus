<?php
	
	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	    	

	require_once("dbcon/config.php");
	require_once("dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
	mysqli_set_charset($con,'utf8');	

	//$xServidor = $_SERVER['HTTP_HOST'];
	$page = isset($_GET['page']) ? $_GET['page'] : "index";
    $mensaje = (isset($_POST['mensaje'])) ? $_POST['mensaje'] : '';
    
	$menuid = $_GET['menuid'];
	
    @session_start();

    if(isset($_SESSION["s_usuario"])){
        if($_SESSION["s_loged"] != "loged"){
            header("Location: ./logout.php");
            exit();
        }
    } else{
        header("Location: ./logout.php");
        exit();
    }  

    $xPaisid = $_SESSION["i_paisid"];
    $xEmprid = $_SESSION["i_emprid"];
    $xUsuaid = $_SESSION["i_usuaid"];

    $xDatos = "";

    if(isset($_POST["btnBuscar"]) and isset($_POST["txtcriterio"]) ){
        
        $xCriterio = $_POST["txtcriterio"];

        // $xDatos = "OK";
        // $xSQL = "SELECT per.pers_numerodocumento AS Documento,(SELECT prv.ciudad FROM `provincia_ciudad` prv WHERE prv.prov_id=per.pers_ciudad) AS Ciudad,";
        // $xSQL .= "CONCAT(per.pers_nombres,' ',per.pers_apellidos) AS Titular,(SELECT pro.prod_nombre FROM `expert_productos` pro WHERE pro.prod_id=tit.prod_id) AS Producto,";
        // $xSQL .= "CASE tit.titu_estado WHEN 'A' THEN 'Activo' ELSE 'Inactivo' END AS Estado,tit.titu_id AS Tituid,tit.prod_id AS Prodid,";
        // $xSQL .= "tit.grup_id AS Grupid FROM `expert_persona` per INNER JOIN `expert_titular` tit ON tit.pers_id=per.pers_id ";
        // $xSQL .= "WHERE per.pers_estado='A' AND CONCAT(per.pers_apellidos,' ',per.pers_nombres) LIKE '%$xCriterio%' OR per.pers_numerodocumento LIKE '$xCriterio%' ";
        // $all_datos = mysqli_query($con, $xSQL);

        $xDatos = "OK";
        $xSQL = "SELECT per.pers_numerodocumento AS Documento,(SELECT prv.ciudad FROM `provincia_ciudad` prv WHERE prv.prov_id=per.pers_ciudad) AS Ciudad, ";
        $xSQL .= "CONCAT(per.pers_nombres,' ',per.pers_apellidos) AS Titular,(SELECT pro.prod_nombre FROM `expert_productos` pro WHERE pro.prod_id=tit.prod_id) AS Producto, ";
        $xSQL .= "(SELECT cli.clie_nombre FROM `expert_cliente` cli WHERE cli.clie_id = pro.clie_id) AS Cliente,CASE tit.titu_estado WHEN 'A' THEN 'ACTIVO' ELSE 'INACTIVO' END AS Estado, ";
        $xSQL .= "tit.titu_id AS Tituid,tit.prod_id AS Prodid,tit.grup_id AS Grupid FROM `expert_persona` per INNER JOIN `expert_titular`tit  ON tit.pers_id = per.pers_id INNER JOIN `expert_productos` pro ";
        $xSQL .= "ON tit.prod_id = pro.prod_id INNER JOIN `expert_cliente` cli ON pro.clie_id = cli.clie_id WHERE per.pers_estado='A' AND CONCAT(per.pers_apellidos,' ',per.pers_nombres) LIKE '%$xCriterio%' OR per.pers_numerodocumento LIKE '$xCriterio%' ";
        $all_datos = mysqli_query($con, $xSQL);
    }

?>

<div id="kt_content_container" class="container-xxl">
    <input type="hidden" id="mensaje" value="<?php echo $mensaje ?>">
    <form  method="post" enctype="multipart/form-data">
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="mb-5 fv-row">
                    <label class="fs-6 fw-bold form-label mt-3">
                        <span class="required">Criterio de Busqueda</span>
                    </label>
                    <div class="row mb-2">
                        <div class="col-xl-10">
                            <input type="text" class="form-control" id="txtcriterio" name="txtcriterio" placeholder="Buscar por Cedula/Apellidos" />
                        </div>
                        <div class="col-xl-2 fv-row">
                            <button type="submit" name="btnBuscar" id="btnBuscar" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-auto pt-3" title="Buscar" data-bs-toggle="tooltip" data-bs-placement="right">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <span class="svg-icon svg-icon-1 position-absolute ms-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                        </svg>
                    </span>
                    <input type="text" data-kt-ecommerce-product-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Buscar Datos" />
                </div>
            </div> 
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                <div class="w-50 mw-150px">
                    <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Estado" data-kt-ecommerce-product-filter="status">                                    
                        <option></option>
                        <option value="all">Todos</option>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
            </div>                       
        </div>
        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed table-hover fs-6 gy-5" id="kt_ecommerce_products_table">
                <thead>
                    <tr class="text-start text-gray-800 fw-bolder fs-7 text-uppercase gs-0">
                        <th>Ciudad</th>
                        <th>Titular</th>
                        <th>Cliente</th>
                        <th>Producto</th>
                        <th>Estado</th>
                        <th>Status</th> 
                        <th style="text-align: center;">Opciones</th>
                    </tr>
                </thead>

                <?php if($xDatos=='OK')
                    { ?>
                                                    
                    <tbody class="fw-bold text-gray-600">
                        <?php foreach($all_datos as $datos){ 
                            
                            //$xDocumento = $datos['Documento'];
                            $xCiudad = $datos['Ciudad'];
                            $xTitular = $datos['Titular'];
                            $xCliente = $datos['Cliente'];
                            $xProducto = $datos['Producto'];
                            $xTituid = $datos['Tituid'];
                            $xProdid = $datos['Prodid'];
                            $xGrupid = $datos['Grupid'];
                            $xEstado = $datos['Estado'];
                            
                        ?>
                        <?php 

                            $xCheking = '';
                            $xDisabledEdit = '';
                            $xTarget = '';

                            if($xEstado == 'ACTIVO'){
                                $xCheking = 'checked="checked"';
                                $xTextColor = "badge badge-light-primary";
                            }else{
                                $xTextColor = "badge badge-light-danger";
                                $xDisabledEdit = 'disabled';
                            }

                            
                        ?>
                
                        <tr>
                            <td class="text-uppercase"><?php echo $xCiudad; ?></td>
                            <td><?php echo $xTitular; ?></td>
                            <td><?php echo $xCliente; ?></td>
                            <td><?php echo $xProducto; ?></td>
                            <td id="td_<?php echo $xTituid; ?>">
                                <div class="<?php echo $xTextColor; ?>">
                                    <?php echo $xEstado; ?>
                                </div>
                            </td>
                            <td>
                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                    <input <?php echo $xCheking; ?> class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chkestado" 
                                        onchange="f_UpdateEstado(<?php echo $xTituid; ?>,<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xUsuaid; ?>)" value=""/>
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    <div class="btn-group">
                                        <button id="btnAgendar_<?php echo $xTituid;?>" onclick="f_Agendar(<?php echo $xTituid;?>,<?php echo $xProdid;?>,<?php echo $xGrupid;?>)" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" <?php echo $xDisabledEdit;?> title='Agendar Cita' data-bs-toggle="tooltip" data-bs-placement="left">
                                           <i class="fa fa-user-md" aria-hidden="true"></i>
                                        </button>												 
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php } ?> 
                    </tbody>
                <?php }?>
            </table>
        </div>
    </div>
    

</div>

<script>

    $(document).ready(function(){

        var _mensaje = $('input#mensaje').val();

        if(_mensaje != ''){
            //mensajesalertify(_mensaje,"S","top-center",3);
            mensajesweetalert('top-center','success',_mensaje,false,1900); 
        }

    });	

        // Redirect boton editar cliente

    function f_Agendar(_tituid, _prodid, _grupid){

       $.redirect('?page=agendar_titubeneadmin&menuid=<?php echo $menuid; ?>',{'tituid': _tituid, 'prodid': _prodid, 'grupid': _grupid, 'agendaid': 0  }); //POR METODO POST
    }

    //Update Estado cliente

    function f_UpdateEstado(_tituid, _emprid,_paisid,_usuaid){

        var _check = $("#chk" + _tituid).is(":checked");
        var _checked = "";
        var _class = "badge badge-light-primary";
        var _td = "td_" + _tituid;
        var _btnagen = "btnAgendar_" + _tituid;

        if(_check){
            var _estado = 'ACTIVO';
            _checked = "checked='checked'";
            $('#'+_btnagen).prop("disabled",false);
            
        }else{
            _estado = 'INACTIVO';
            _class = "badge badge-light-danger";
            $('#'+_btnagen).prop("disabled",true);
        }

        var _changetd = document.getElementById(_td);
            _changetd.innerHTML = '<div class="' + _class + '">' + _estado + ' </div>';

            var _parametros = {
                "xxClieid" : _tituid,
                "xxEmprid" : _emprid,
                "xxPaisid" : _paisid,
                "xxUsuaid" : _usuaid,
                "xxEstado" : _estado
            } 
            
        var xrespuesta = $.post("codephp/update_estadocliente.php", _parametros);
            xrespuesta.done(function(response){
        });	

    }

    
</script>

   