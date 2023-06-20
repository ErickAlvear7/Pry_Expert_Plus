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

    $xSQL = "SELECT clie.clie_id AS IdCliente, clie.clie_nombre AS Cliente, clie.clie_url AS Urll, clie.clie_descripcion AS Descrip,clie.clie_imgcab AS Logo, CASE clie.clie_estado WHEN 'A' THEN 'Activo' ELSE 'Inactivo' END AS Estado, ";
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                        </svg>
                    </span>
                    <input type="text" data-kt-ecommerce-product-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Buscar Datos" />
                </div>
            </div> 
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                <div class="w-100 mw-150px">
                    <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Estado" data-kt-ecommerce-product-filter="status">                                    
                        <option></option>
                        <option value="all">Todos</option>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
               </div>
                <a href="?page=addclienteprod&menuid=<?php echo $menuid; ?>" class="btn btn-primary">
                    <span class="svg-icon svg-icon-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                            <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
                        </svg>
                    </span>
                    Nuevo CLiente
                </a>
		    </div>                       
        </div>
		<div class="card-body pt-0">
			<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_products_table">
				<thead>
					<tr class="text-start text-gray-800 fw-bolder fs-7 gs-0 text-uppercase">
					    <th>Ciudad</th>
						<th>Cliente</th>
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
                            $xLogo = 'companyname.png';
                        }
                        
                    ?>
                     <?php 
                       $xCheking = '';
                       $xDisabledEdit = '';
                       $xTarget = '';

                       
                    

                       if($xEstado == 'Activo'){
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
                                <span class="symbol-label" style="background-image:url(logos/<?php echo $xLogo; ?>);"></span>
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
									<button id="btnEditar_<?php echo $xClieid;?>" onclick="f_Editar(<?php echo $xClieid;?>)" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" <?php echo $xDisabledEdit;?> title='Editar Cliente'>
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

        var _mensaje = $('input#mensaje').val();

        if(_mensaje != ''){
            mensajesalertify(_mensaje,"S","top-center",3); 
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
            var _estado = 'Activo';
            _checked = "checked='checked'";
            $('#'+_btnedit).prop("disabled",false);
            
        }else{
            _estado = 'Inactivo';
            _class = "badge badge-light-danger";
            $('#'+_btnedit).prop("disabled",true);
        }

        var _changetd = document.getElementById(_td);
            _changetd.innerHTML = '<div class="' + _class + '">' + _estado + ' </div>';

            var _parametros = {
                xxClieid: _clieid,
                xxEmprid: _emprid,
                xxPaisid: _paisid,
                xxUsuaid: _usuaid,
                xxEstado: _estado
            } 
            
        var xrespuesta = $.post("codephp/update_estadocliente.php", _parametros);
            xrespuesta.done(function(response){
        });	

    }

    
</script>

   