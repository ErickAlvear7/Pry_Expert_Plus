<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');

	require_once("./dbcon/config.php");
	require_once("./dbcon/functions.php");

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
    
	$mensaje = (isset($_POST['mensaje'])) ? $_POST['mensaje'] : '';
    
    $xSQL = "SELECT perf_id AS Id, perf_descripcion AS Perfil, perf_observacion AS Descripcion,CASE perf_estado WHEN 'A' THEN 'Activo' ELSE 'Inactivo' END AS Estado ";
    $xSQL .= "FROM `expert_perfil` WHERE pais_id=$xPaisid AND empr_id=$xEmprid";

    $all_perfiles = mysqli_query($con, $xSQL);
    foreach ($all_perfiles as $perfil){
        $xName = $perfil["Perfil"];
    }
	
?>				
					
<div id="kt_content_container" class="container-xxl">
	<div class="card mb-5 mb-xxl-8">
		<div class="card-body pt-9 pb-0">
			<ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder">
				<li class="nav-item mt-2">
					<a class="nav-link text-active-primary ms-0 me-10 py-5 active" href="?page=seg_perfiladmin&menuid=<?php echo $menuid; ?>">Perfil</a>
				</li>
				<li class="nav-item mt-2">
					<a class="nav-link text-active-primary ms-0 me-10 py-5" href="?page=seg_usuarioadmin&menuid=<?php echo $menuid; ?>">Usuarios</a>
				</li>
			</ul>
		</div>
	</div>
	<div class="card">
		<input type="hidden" id="mensaje" value="<?php echo $mensaje ?>">
		<div class="card-header border-0 pt-6">
			<div class="card-title">					
				<div class="d-flex align-items-center position-relative my-1">
					<span class="svg-icon svg-icon-1 position-absolute ms-4">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
							<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
							<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
						</svg>
					</span>
					<input type="text" data-kt-ecommerce-order-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Buscar Dato" />
				</div>
			</div>

			<div class="card-toolbar">
				<a href="?page=addperfil&menuid=<?php echo $menuid; ?>" class="btn btn-primary">
					<span class="svg-icon svg-icon-2">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
							<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
							<rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
						</svg>
					</span>
				Nuevo Perfil</a>
			</div>
		</div>

		<div class="card-body pt-0">
			<table class="table align-middle table-row-dashed fs-6 gy-5 table-hover" id="kt_ecommerce_report_shipping_table" style="width: 100%;">
				<thead>
					<tr class="text-start text-gray-800 fw-bolder fs-7 gs-0">
							<th>Perfil</th>
							<th>Descipcion</th>                                                                        									
							<th>Estado</th>
							<th>Status</th>
							<th style="text-align:center;">Opciones</th>
					</tr>
				</thead>
				<tbody class="fw-bold text-gray-600">
					<?php

						foreach ($all_perfiles as $perfil){
					?>
					<?php
					
						$xDisabledEdit = '';
						$xDisabledChk = '';

						if ($perfil['Estado'] == 'Inactivo') {
							$xDisabledEdit = 'disabled';
						}

						if($perfil['Perfil'] == 'Administrador'){
							$xDisabledChk = 'disabled';
						}

						if($perfil['Estado'] == 'Activo'){
							$xTextColor = 'badge badge-light-primary';
						}else{
							$xTextColor = 'badge badge-light-danger';
						}
					?>
						<tr>
							<td><?php echo $perfil['Perfil']; ?></td>
							<td><?php echo $perfil['Descripcion']; ?></td>								
							<td id="td_<?php echo $perfil['Id']; ?>">
								<div class="<?php echo $xTextColor; ?>"><?php echo $perfil['Estado']; ?></div>
							</td>								
							<td style="text-align:center">
								<div class="form-check form-check-sm form-check-custom form-check-solid">
									<input class="form-check-input btnEstado" type="checkbox" <?php echo $xDisabledChk; ?> id="chk<?php echo $perfil['Id']; ?>" <?php if ($perfil['Estado'] == 'Activo') {
										echo "checked='checked'";} else {'';} ?> onchange="f_UpdateEstado(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $perfil['Id']; ?>)" value="<?php echo $perfil['Id']; ?>" />
								</div>
							</td>
							<td>
								<div class="text-center">
									<div class="btn-group">
										<button <?php echo $xDisabledEdit ?> onclick="f_Editar(<?php echo $perfil['Id']; ?>)" id="btnEditar_<?php echo $perfil['Id']; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title='Editar Perfil'>
											<i class='fa fa-edit'></i>
										</button>
									</div>
								</div>
							</td>                                
						</tr>
					<?php }
						?>                            
				</tbody>
			</table>
		</div>
	</div>
</div>
	
<script>
	$(document).ready(function(){
		_mensaje = $('input#mensaje').val();

		if(_mensaje != ''){
			//mensajesalertify(_mensaje+"..!","S","top-center",5);
			mensajesweetalert("center","warning",_mensaje,false,1800);  
		}

		$(document).on("click",".btnEstado",function(e){
			_fila = $(this).closest("tr");
			_perfil = $(this).closest("tr").find('td:eq(0)').text(); 
			_descripcion = $(this).closest("tr").find('td:eq(1)').text(); 
		});


	});

	function f_Editar(_perfid){
		$.redirect('?page=editperfil&menuid=<?php echo $menuid; ?>', {'idperfil': _perfid}); //POR METODO POST
	}

	function f_UpdateEstado(_paisid, _emprid, _perfid){
		
		let _check = $("#chk" + _perfid).is(":checked");
		let _td = "td_" + _perfid;
		let _btnedit = "btnEditar_" + _perfid;	

		if(_check){
			_estado = "Activo";
			_class = "badge badge-light-primary";
			$('#'+_btnedit).prop("disabled",false);
		}else{
			_estado = "Inactivo";
			_class = "badge badge-light-danger";
			$('#'+_btnedit).prop("disabled",true);
		}

		var _changetd = document.getElementById(_td);
		_changetd.innerHTML = '<td><div class="' + _class + '">' + _estado + ' </div>';					
		
		var _parametros = {
			"xxPaisid" : _paisid,
			"xxIdPerfil" : _perfid,
			"xxIdMeta" : 0,
			"xxEmprid" : _emprid,
			"xxTipo" : _estado
		}				
		
		var xrespuesta = $.post("codephp/delnew_perfil.php", _parametros);
		xrespuesta.done(function(response){
		});				

	}

</script> 