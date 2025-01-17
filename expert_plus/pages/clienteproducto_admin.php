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

    $xSQL = "SELECT clie.clie_id AS IdCliente, clie.clie_nombre AS Cliente, clie.clie_url AS Urll,clie.clie_imgcab AS Logo, CASE clie.clie_estado WHEN 'A' THEN 'ACTIVO' ELSE 'INACTIVO' END AS Estado, ";
    $xSQL .="pro.ciudad AS Ciudad FROM `expert_cliente` clie, `provincia_ciudad` pro WHERE clie.prov_id = pro.prov_id ";
    $all_clie = mysqli_query($con, $xSQL);

?>

<div id="kt_content_container" class="container-xxl">
    <input type="hidden" id="mensaje" value="<?php echo $mensaje ?>">
    <div class="card">
        <div class="card-header border-0 pt-6">
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
                <a href="?page=addclienteprod&menuid=<?php echo $menuid; ?>" class="btn btn-light-primary btn-sm mb-2">
                    <i class="fa fa-plus-circle me-1" aria-hidden="true"></i>Nuevo CLiente 
                </a>
		    </div>                       
        </div>
		<div class="card-body pt-0">
			<table class="table align-middle table-row-dashed table-hover fs-6 gy-5" id="kt_ecommerce_products_table">
				<thead>
					<tr class="text-start text-gray-800 fw-bolder fs-7 text-uppercase gs-0">
					    <th>Ciudad</th>
						<th class="min-w-125px">Cliente</th>
                        <th style="display:none;">Descripcion</th>
                        <th style="display:none;">Logo</th>
                        <th>Estado</th>
						<th>Status</th>
                        <th style="text-align: center;">Opciones</th>
					</tr>
				</thead>
				<tbody class="fw-bold text-gray-600">
                    <?php foreach($all_clie as $clie){ 
                        
                        $xClieid = $clie['IdCliente'];
                        $xCiudad = $clie['Ciudad'];
                        $xCliente = $clie['Cliente'];
                        $xUrl = $clie['Urll'];
                        $xDesc = $clie['Descrip'];
                        $xLogo = $clie['Logo'];
                        $xEstado = $clie['Estado'];
                        if($xLogo == ''){
                            $xLogo = 'cliente.png';
                        }
                        
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

                        if($xUrl != ''){
                            $xTarget =  'target="_blank"' .' '. 'rel="noopener noreferrer"';
                        }else{
                            $xUrl = '#';
                            $xTarget = '';
                        }
                    
                    ?>
			
					<tr>
					    <td class="text-uppercase"><?php echo $xCiudad; ?></td>
                        <td class="d-flex align-items-center">
                            <a href="<?php echo $xUrl; ?>" <?php echo  $xTarget; ?> class="symbol symbol-50px">
                                <span class="symbol-label" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="<?php echo $xUrl; ?>" style="background-image:url(assets/images/clientes/<?php echo $xLogo; ?>);"></span>
                            </a>
                            <span class="fw-bolder">&nbsp;<?php echo $xCliente; ?></span>
                        </td>
                        <td style="display:none;"><?php echo $xDesc; ?></td>
                        <td style="display:none;"></td>
						<td id="td_<?php echo $xClieid; ?>">
                            <div class="<?php echo $xTextColor; ?>">
                                <?php echo $xEstado; ?>
                            </div>
                        </td>
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input <?php echo $xCheking; ?> class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk<?php echo $xClieid;?>" 
                                    onchange="f_UpdateEstado(<?php echo $xClieid; ?>,<?php echo $xEmprid; ?>,<?php echo $xPaisid; ?>,<?php echo $xUsuaid; ?>)" value=""/>
                            </div>
						</td>
						<td>
                            <div class="text-center">
								<div class="btn-group">
									<button id="btnEditar_<?php echo $xClieid;?>" onclick="f_Editar(<?php echo $xClieid;?>)" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" <?php echo $xDisabledEdit;?> title='Editar Cliente' data-bs-toggle="tooltip" data-bs-placement="left">
										<i class="fa fa-edit"></i>
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

        var _mensaje = $('input#mensaje').val();

        if(_mensaje != ''){
            toastSweetAlert("top-end",3000,"success",_mensaje);  
        }

    });	

        // Redirect boton editar cliente

    function f_Editar(_clieid){
        $.redirect('?page=editcliente&menuid=<?php echo $menuid; ?>', {'idclie': _clieid}); //POR METODO POST
    }

    //Update Estado cliente

    function f_UpdateEstado(_clieid, _emprid,_paisid,_usuaid){


        var _check = $("#chk" + _clieid).is(":checked");
        var _checked = "";
        var _class = "badge badge-light-primary";
        var _td = "td_" + _clieid;
        var _btnedit = "btnEditar_" + _clieid;

        if(_check){
            var _estado = 'ACTIVO';
            _checked = "checked='checked'";
            $('#'+_btnedit).prop("disabled",false);
            
        }else{
            _estado = 'INACTIVO';
            _class = "badge badge-light-danger";
            $('#'+_btnedit).prop("disabled",true);
        }

        var _changetd = document.getElementById(_td);
            _changetd.innerHTML = '<div class="' + _class + '">' + _estado + ' </div>';

            var _parametros = {
                "xxClieid" : _clieid,
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

   