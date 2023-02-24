<?php
	$page = isset($_GET['page']) ? $_GET['page'] : "index";

	require_once("dbcon/config.php");
	require_once("dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

	$xServidor = $_SERVER['HTTP_HOST'];
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());

    //$yEmprid = $_SESSION["i_empre_id"];
    $yEmprid = 1;	

	$xSQL = "SELECT tare_id AS Id, tare_nombre AS Tarea, tare_ruta AS Accion, CASE tare_estado WHEN 'A' THEN 'Activo' ";
	$xSQL .= "ELSE 'Inactivo' END AS Estado FROM `expert_tarea` WHERE empr_id=$yEmprid ORDER BY tare_orden";
	$all_tareas = mysqli_query($con, $xSQL);
?>				
					
<div id="kt_content_container" class="container-xxl">
    <input type="hidden" id="mensaje" value="<?php echo $mensaje ?>">
	<div class="card card-flush">
		<div class="card-toolbar">
			<a href="?page=addmenu" class="btn btn-sm btn-light-primary">
				<span class="svg-icon svg-icon-2">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
						<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
						<rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
					</svg>
				</span>
			Nueva Tarea</a>
		</div>
		<div class="card-header align-items-center py-5 gap-2 gap-md-5">
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
				<div id="kt_ecommerce_report_shipping_export" class="d-none"></div>
			</div>
			<div class="card-toolbar flex-row-fluid justify-content-end gap-5">
				<div class="w-150px">
					<select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Estado" data-kt-ecommerce-order-filter="status">
						<option></option>
						<option value="all">Todos</option>
						<option value="Activo">Activo</option>
						<option value="Inactivo">Inactivo</option>
					</select>
				</div>
			</div>
		</div>
		<div class="card-body pt-0">
			<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_report_shipping_table" style="width: 100%;">
				<thead>
					<tr class="text-start text-gray-800 fw-bolder fs-7 gs-0">
						<th style="display:none;">IdTarea</th>
						<th>Tarea</th>
						<th>Accion</th>
						<th>Estado</th>
						<th style="text-align:center;">Opciones</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody class="fw-bold text-gray-600">
					<?php 
					
					foreach($all_tareas as $tareas){
                      
					?>
					<?php 

                     	$chkEstado = '';
					 	$xDisabledEdit = '';
						$xTextColor = "badge badge-light-primary";

						if($tareas['Id'] == '100001' || $tareas['Id'] == "100002" || $tareas['Id'] == "100003" || $tareas['Id'] == "100004"){

							$xDisabledEdit = 'disabled';
							$chkEstado = 'disabled';
						}

						if($tareas['Id'] != '100001' || $tareas['Id'] != "100002" || $tareas['Id'] != "100003" || $tareas['Id'] != "100004"){								
							if ($tareas['Estado'] == 'Inactivo'){
								$xDisabledEdit = 'disabled';
								$xTextColor = "badge badge-light-danger";
							}
						}						
					
					?>
					<tr>
						<td style="display:none;"><?php echo $tareas['Id']; ?></td>
						<td><?php echo $tareas['Tarea']; ?></td>
						<td><?php echo $tareas['Accion']; ?></td>
						<td>
						   <div class="<?php  echo $xTextColor; ?>"><?php echo $tareas['Estado']; ?></div>
						</td>
						<td>
							<div class="text-center">
								<div class="btn-group">
									<button <?php echo $xDisabledEdit ?> id="btnEditar<?php echo $tareas['Id']; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title='Editar Tareas'>
										<i class='fa fa-edit'></i>
									</button>																															 
								</div>
							</div>
						</td>
						<td>
							<div class="text-center">
								<div class="form-check form-check-sm form-check-custom form-check-solid">
										<input class="form-check-input" type="checkbox" <?php echo $chkEstado; ?> id="chk<?php echo $tareas['Id']; ?>" <?php if ($tareas['Estado'] == 'Activo') {
												echo "checked";} else {'';} ?> value="<?php echo $tareas['Id']; ?>" />
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
			//mensajesweetalert("center","success",_mensaje+"..!",false,1800);
			mensajesalertify(_mensaje+"..!","S","top-center",5);
		}
	});

	function f_Editar(_idmenu){
		$.redirect('?page=editmenu', {'idmenu': _idmenu}); //POR METODO POST

	}

</script> 	

		
		