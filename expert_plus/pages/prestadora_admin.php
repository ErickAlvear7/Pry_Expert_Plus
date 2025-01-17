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

	$xFechaActual = strftime('%Y-%m-%d', time());
	$mensaje = (isset($_POST['mensaje'])) ? $_POST['mensaje'] : '';

    $xSQL = "SELECT usu.usua_id AS Idusuario, CONCAT(usu.usua_nombres,' ',usu.usua_apellidos) AS Nombres, usu.usua_login AS Email, CASE usu.usua_estado WHEN 'A' THEN 'Activo' ";
	$xSQL .= "ELSE 'Inactivo' END AS Estado, usu.usua_caducapass AS CaducaPass, usu.usua_avatarlogin AS LogoUser, (SELECT per.perf_descripcion FROM `expert_perfil` per WHERE per.pais_id=$xPaisid AND per.perf_id=usu.perf_id) AS Perfil FROM `expert_usuarios` usu WHERE usu.pais_id=$xPaisid AND usu.empr_id=$xEmprid AND usu.perf_id>1 ";
	$all_usuarios = mysqli_query($con, $xSQL);

	$xSQL = "SELECT perf_descripcion AS Descripcion, perf_id AS Codigo,perf_observacion AS Observacion FROM `expert_perfil` ";
	$xSQL .= " WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND perf_estado='A' ";
	$xSQL .= " ORDER BY Codigo ";
    $all_perfil = mysqli_query($con, $xSQL);

    $xSQL = "SELECT (SELECT prv.ciudad FROM `provincia_ciudad` prv WHERE prv.prov_id=pre.prov_id) AS Ciudad,pre.pres_nombre AS Prestadora,";
    $xSQL .= "(SELECT pde.pade_nombre FROM `expert_parametro_detalle` pde, `expert_parametro_cabecera` pca WHERE pde.paca_id=pca.paca_id AND pca.paca_nombre='Tipo Sector' AND pde.pade_valorv=pre.pres_sector) AS Sector,(SELECT pde.pade_nombre FROM `expert_parametro_detalle` pde, `expert_parametro_cabecera` pca WHERE pde.paca_id=pca.paca_id AND pca.paca_nombre='Tipo Prestador' AND pde.pade_valorv=pre.pres_tipoprestador) AS TipoPrestador,pre.pres_logo AS Logo,pre.pres_estado AS Estado,pre.pres_ubicacion AS Ubicacion,pre.pres_url AS Url,pre.pres_id AS Id FROM `expert_prestadora` pre ";
    $all_prestador = mysqli_query($con, $xSQL);
    
    //file_put_contents('log_seguimiento.txt', $xSQL . "\n\n", FILE_APPEND);

?>

<div id="kt_content_container" class="container-xxl">
    <input type="hidden" id="mensaje" value="<?php echo $mensaje ?>">
    <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <span class="svg-icon svg-icon-1 position-absolute ms-4">
                        <i class="fa fa-search fa-1x" style="color:#3B8CEC;" aria-hidden="true"></i> 
                    </span>
                    <input type="text" data-kt-ecommerce-product-filter="search" class="form-control w-250px ps-14" placeholder="Buscar Datos" />
                </div>
            </div>
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                <div class="w-100 mw-150px">
                    <select class="form-select" data-control="select2" data-hide-search="true" data-placeholder="Estado" data-kt-ecommerce-product-filter="status">                                    
                        <option></option>
                        <option value="all">Todos</option>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
                <button type="button" class="btn btn-light-primary btn-sm" onclick="f_NuevoPrestador()" ><i class="fa fa-plus-circle me-1"></i>Nuevo Prestador</button>
            </div>
        </div>
        <div class="card-body pt-0">
            <table class="table align-middle table-row-dashed table-hover fs-6 gy-5" id="kt_ecommerce_products_table">
                <thead>
                    <tr class="text-start text-gray-800 fw-bolder fs-7 text-uppercase gs-0">
                        <th class="">Ciudad</th>
                        <th class="min-w-125px">Prestador</th>
                        <th class="">Ubicacion</th>
                        <th>Sector</th>
                        <th class="min-w-125px">Tipo Prestador</th>
                        <th>Estado</th>
                        <th>Status</th>
                        <th style="text-align: center;">Opciones</th>
                    </tr>
                </thead>
                <tbody class="fw-bold text-gray-600">
                    <?php 
                                    
                        foreach($all_prestador as $presta){
                            $xId = $presta['Id'];
                            $xCiudad = trim($presta['Ciudad']);
                            $xPrestador = trim($presta['Prestadora']);
                            $xSector = trim($presta['Sector']);
                            $xTipoPresta = trim($presta['TipoPrestador']);
                            $xLogo = trim($presta['Logo']);
                            $xEstado = trim($presta['Estado']);
                            $xUbicacion = trim($presta['Ubicacion']);
                            $xUrl = trim($presta['Url']);

                            if($xLogo == ''){
								$xLogo = 'logo.png';
							}
                        ?>
                            <?php 

                                $chkEstado = '';
                                $xDisabledEdit = '';
                                $xTarget = '';
                                $xTargeturl = '';

                                if($xEstado == 'A'){
                                    $xEstado = 'ACTIVO';
                                    $chkEstado = 'checked="checked"';
                                    $xTextColor = "badge badge-light-primary";
                                }else{
                                    $xEstado = 'INACTIVO';
                                    $xTextColor = "badge badge-light-danger";
                                    $xDisabledEdit = 'disabled';
                                }

                                if($xUrl != ''){
                                    $xTargeturl = 'target="_blank"'.' '. 'rel="noopener noreferrer"';
                                }else{
                                    $xUrl="#";   
                                }
                                
                                if($xUbicacion != ''){
                                    $xTarget =  'target="_blank"' .' '. 'rel="noopener noreferrer"';
                                }else{
                                    $xUbicacion ="#";    
                                }   

                            ?>
                            <tr>
                                <td class="text-uppercase">
                                  <span class="fw-bolder"><?php echo $xCiudad; ?></span>
                                </td>
                                <td class="d-flex align-items-center">
                                    <a href="<?php echo $xUrl; ?>" <?php echo $xTargeturl; ?> class="symbol symbol-50px">
                                        <span class="symbol-label" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="<?php echo $xUrl; ?>" style="background-image:url(assets/images/prestadores/<?php echo $xLogo; ?> );"></span>
                                    </a>
                                    <span class="fw-bolder">&nbsp;&nbsp;<?php echo $xPrestador; ?></span>
                                </td>
                                <td>
                                <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="<?php echo $xUbicacion; ?>">
                                    <a href="<?php echo $xUbicacion; ?>" <?php echo $xTarget; ?> class="symbol symbol-50px">  
                                        <i class="fa fa-map-marker fa-2x ms-7" aria-hidden="true" style="color:#3B8CEC;"></i>  
                                    </a>
                                </span>     
                                </td>
                                <td>
                                  <span class="fw-bolder"><?php echo $xSector; ?></span>
                                </td>
                                <td class="text-uppercase">
                                  <span class="fw-bolder"><?php echo $xTipoPresta; ?></span>
                                </td>                                    
                                <td id="td_<?php echo $xId; ?>">
                                   <div class="<?php echo $xTextColor; ?>">
                                       <?php echo $xEstado; ?>
                                    </div>
                                </td>
                                <td class="text-end">
                                   <div class="text-center">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input class="form-check-input h-20px w-20px border-primary" <?php echo $chkEstado; ?> type="checkbox" id="chk<?php echo $xId; ?>" 
                                                onchange="f_UpdateEstado(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xId; ?>)" value="<?php echo $xId; ?>"/>
                                        </div>
                                    </div>
                                </td> 													
                                <td class="text-end">
                                    <div class="text-center">
                                        <div class="btn-group">
                                            <button id="btnEditar_<?php echo $xId; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" <?php echo $xDisabledEdit; ?> title='Editar Prestador' data-bs-toggle="tooltip" data-bs-placement="left" onclick="f_Editar(<?php echo $xId; ?>)">
                                                <i class='fa fa-edit'></i>
                                            </button>	                                                
                                        </div>
                                    </div>
                                </td>
                            </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<script>

    $(document).ready(function(){

        _mensaje = $('input#mensaje').val();

        if(_mensaje != ''){					
            toastSweetAlert("top-end",3000,"success",_mensaje);
        }
    });	

    function f_UpdateEstado(_paisid, _emprid, _presid){
        
        let _usuaid = "<?php echo $xUsuaid; ?>";
        let _check = $("#chk" + _presid).is(":checked");
        let _checked = "";
        let _class = "badge badge-light-primary";
        let _td = "td_" + _presid;
        let _btnedit = "btnEditar_" + _presid;

        if(_check){
            _estado = "ACTIVO";
            _checked = "checked='checked'";
            $('#'+_btnedit).prop("disabled",false);
        }else{                    
            _estado = "INACTIVO";
            _class = "badge badge-light-danger";
            $('#'+_btnedit).prop("disabled",true);
        }

        var _changetd = document.getElementById(_td);
        _changetd.innerHTML = '<div class="' + _class + '">' + _estado + ' </div>';

        _parametros = {
            "xxPaisid" : _paisid,
            "xxEmprId" : _emprid,
            "xxUsuaid" : _usuaid,
            "xxPresid" : _presid,
            "xxEstado" : _estado
        }	

        var xrespuesta = $.post("codephp/update_estadoprestador.php", _parametros);
        xrespuesta.done(function(response){

            if(response.trim() == 'OK'){
                //$.redirect("?page=prestador_admin&menuid=<?php echo $menuid; ?>");
            }
            
        });	
    }

    function f_Editar(_id){
        //$.redirect('?page=modprestador&menuid=<?php echo $menuid; ?>&id=' + _id); //POR METODO GET
        //location.href='?page=modprestador&menuid=<?php echo $menuid; ?>&id=' + _id; //POR METODO GET
        $.redirect('?page=modprestador&menuid=<?php echo $menuid; ?>', {'id': _id}); //POR METODO POST
    }

    function f_NuevoPrestador(){
        location.href='?page=addprestador&menuid=<?php echo $menuid; ?>' //POR METODO GET
    }

</script>

<style>
    .btn-disabled,
    .btn-disabled[disabled] {
    opacity: .4;
    cursor: default !important;
    pointer-events: none;
    }        
</style>