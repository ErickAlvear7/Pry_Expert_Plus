<?php
	$page = isset($_GET['page']) ? $_GET['page'] : "index";

	require_once("dbcon/config.php");
	require_once("dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
	mysqli_set_charset($con,'utf8');	

	$xServidor = $_SERVER['HTTP_HOST'];
	$xFecha = strftime("%Y-%m-%d %H:%M:%S", time());

    $xSQL = "SELECT usua_id AS Idusuario, CONCAT(usua_nombres,' ',usua_apellidos) AS Nombres, usua_login AS Log, CASE usua_estado WHEN 'A' THEN 'Activo' ";
	$xSQL .= "ELSE 'Inactivo' END AS Estado, usua_caducapass AS CaducaPass FROM `expert_usuarios`";
	$expertusuario = mysqli_query($con, $xSQL);




?>

<div id="kt_content_container" class="container-xxl">
    <input type="hidden" id="mensaje" value="<?php echo $mensaje ?>">
	<div class="card card-flush">
		<div class="card-toolbar">
			<a href="?page=addmenu" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_new_ticket">
			<span class="svg-icon svg-icon-2">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
					<rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
				</svg>
			</span>
		    Nuevo Usuario</a>
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
					<th class="min-w-125px">Usuario</th>
					<th class="min-w-125px">Login</th>
					<th class="min-w-125px">Departamento</th>
					<th class="min-w-125px">Estado</th>
					<th class="min-w-125px" style="text-align: center;">Reset Password</th>
					<th class="min-w-125px" style="text-align: center;">Opciones</th>
					<th class="min-w-125px">Estado</th>
					</tr>
				</thead>
				<tbody class="fw-bold text-gray-600">
					<?php 
					
					foreach($expertusuario as $usu){
						$idusuario = $usu['Idusuario'];
						$estado = $usu['Estado'];
					?>
					<?php 

						$cheking = '';
						if($estado == 'Activo'){
							$cheking = 'checked="checked"';
							$xTextColor = "badge badge-light-primary";
						}else{
							$xTextColor = "badge badge-light-danger";
						}
					?>
					<tr>
						<td><?php echo $usu['Nombres']; ?></td>
						<td><?php echo $usu['Log']; ?></td>
						<td>Administracion</td>
						<td>
							<div class="<?php echo $xTextColor; ?>"><?php echo $usu['Estado']; ?></div>
						</td>
						<td>
							<div class="text-center">
								<div class="btn-group">
									<button onclick="" id="btnResetPass" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title='Resetear Password'>
										<i class='fa fa-key'></i>
									</button>																															 
								</div>
							</div>
						</td>
						<td>
							<div class="text-center">
								<div class="btn-group">
									<button onclick="" id="btnEditar" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title='Editar Usuario'>
										<i class='fa fa-edit'></i>
									</button>																															 
								</div>
							</div>
						</td>
						<td>
							<div class="text-center">
								<div class="form-check form-check-sm form-check-custom form-check-solid">
									<input <?php echo $cheking; ?> class="form-check-input h-20px w-20px border-primary" type="checkbox" id="" />
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
<div class="modal fade" id="kt_modal_new_ticket" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-750px">
		<div class="modal-content rounded">
			<div class="modal-header pb-0 border-0 justify-content-end">
				<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
					<span class="svg-icon svg-icon-1">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
							<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
							<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
						</svg>
					</span>
				</div>
			</div>
			<div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
				<form id="kt_modal_new_ticket_form" class="form" action="#">
					<div class="mb-13 text-center">
						<h1 class="mb-3">Datos Usuario</h1>
					</div>
					<div class="row g-9 mb-8">
					   <div class="col-md-6 fv-row">
						    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
								<span class="required">Nombre</span>
								<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="especifique el nombre del usuario"></i>
							</label>
							<input type="text" class="form-control form-control-solid" id="txtNombre" name="txtNombre" minlength="5" maxlength="100" placeholder="" />
						</div>
						<div class="col-md-6 fv-row">
						    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
								<span class="required">Apellido</span>
								<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="especifique el apellido del usuario"></i>
							</label>
							<input type="text" class="form-control form-control-solid" id="txtApellido" name="txtApellido" minlength="5" maxlength="100" placeholder="" />
						</div>
					</div>
					<div class="row g-9 mb-8">
					   <div class="col-md-6 fv-row">
						    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
								<span class="required">Login</span>
								<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="especifique el nick del usuario"></i>
							</label>
							<input type="text" class="form-control form-control-solid" id="txtLogin" name="txtLogin" minlength="1" maxlength="100"  placeholder=""  />
						</div>
						<div class="col-md-6 fv-row">
						    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
								<span class="required">Password</span>
								<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="especifique la contraseña del usuario"></i>
							</label>
							<input type="password" class="form-control form-control-solid" id="txtPassword" name="subject" minlength="1" maxlength="100" />
						</div>
					</div>
					<div class="row g-9 mb-8">
						<div class="col-md-6 fv-row">
							<label class="required fs-6 fw-bold mb-2">Departamento</label>
							<select class="form-select form-select-solid" id="cboDepartamento" name="cboDepartamento" data-control="select2" data-hide-search="true" data-placeholder="Seleccione Departamento" name="departamento">
								<option value=""><--Seleccione--></option>
								<option value="1">HTML Theme</option>
							</select>
						</div>
						<div class="col-md-6 fv-row">
							<label class="required fs-6 fw-bold mb-2">Perfil</label>
							<select class="form-select form-select-solid" id="cboPerfil" name="cboPerfil" data-control="select2" data-hide-search="true" data-placeholder="Seleccione Perfil" name="perfil">
								<option value=""><--Seleccione--></option>
								<option value="1">Karina Clark</option>
							</select>
						</div>
					</div>
					<div class="d-flex flex-column mb-8 fv-row">
						<label class="fs-6 fw-bold mb-2">Email</label>
						<input type="email" class="form-control form-control-solid" id="txtEmail" name="txtEmail" placeholder="ejemplo@email.com" />
					</div>
					<div class="row g-9 mb-8 text-center">
						<div class="col-md-4 fv-row">
							<label class="fs-6 fw-bold mb-2">Passward Caduca</label>
						</div>
						<div class="col-md-4 fv-row">
						    <label class="fs-6 fw-bold mb-2">Cambiar Password</label>   
						</div>
						<div class="col-md-4 fv-row">
						    <label class="fs-6 fw-bold mb-2">Permisos Especiales</label>   
						</div>
					</div>
					<div class="row g-9 mb-4 text-center">
						<div class="col-md-4 fv-row">
						    <input class="form-check-input h-20px w-20px border-primary" type="checkbox" id="chkCaducaPass" name="chkCaducaPass" value=""  />
							<span class="form-check-label fw-bold">NO</span>
						</div>
						<div class="col-md-4 fv-row">
						    <input class="form-check-input h-20px w-20px border border-primary" type="checkbox" id="chkCamPass" name="chkCamPass" value="email" />
							<span class="form-check-label fw-bold">NO</span>
						</div>
						<div class="col-md-4 fv-row">
						    <input class="form-check-input h-20px w-20px border-primary" type="checkbox" id="chkPerEspe" name="chkPerEspe" value=""  />
							<span class="form-check-label fw-bold">NO</span>
						</div>
					</div>
					<br/>
					<br/>
					<div class="text-center">
						<button type="button" id="kt_modal_new_ticket_submit" class="btn btn-primary">
							<span class="indicator-label">Guardar</span>
						</button>
					</div>
				</form>
			</div>
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