<?php
	$page = isset($_GET['page']) ? $_GET['page'] : "index";

	require_once("dbcon/config.php");
	require_once("dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

	$xServidor = $_SERVER['HTTP_HOST'];
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());

	$xSQL = "SELECT menu_id AS Idmenu, empr_id AS Empid, menu_descripcion as Menu, CASE menu_estado WHEN 'A' THEN 'Activo' ";
	$xSQL .= "ELSE 'Inactivo' END AS Estado FROM expert_menu";
	$expertmenu = mysqli_query($con, $xSQL);
?>				
					
<div id="kt_content_container" class="container-xxl">
	<div class="card card-flush">
		<div class="card-toolbar">
			<a href="?page=addmenu" class="btn btn-sm btn-light-primary">
				<span class="svg-icon svg-icon-2">
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
						<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
						<rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
					</svg>
				</span>
			Nuevo Menu</a>
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
						<option value="">Activo</option>
						<option value="">Inactivo</option>
					</select>
				</div>
			</div>
		</div>
		<div class="card-body pt-0">
			<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_report_shipping_table" style="width: 100%;">
				<thead>
					<tr class="text-start text-gray-800 fw-bolder fs-7 gs-0">
						<th style="display:none; width: 15px;">Idmenu</th>
						<th style="display:none; width: 15px;">Idemp</th>
						<th style="width: 30px;">Menu</th>
						<th style="width: 30px;">Estado</th>
						<th style="width: 10px; text-align: center">Acción</th>
					</tr>
				</thead>
				<tbody class="fw-bold text-gray-600">
					<?php foreach($expertmenu as $menu){

					?>
					<tr>
						<td style="display:none;"><?php echo $menu['Idmenu']; ?></td>
						<td style="display:none;"><?php echo $menu['Empid']; ?></td>
						<td><?php echo $menu['Menu']; ?></td>
						<td><?php echo $menu['Estado']; ?></td>
						<td>
							<div class="text-center">
								<div class="btn-group">
									<a href="?page=revisolpn&id=2222" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title='Editar Menu'>
										<i class='fa fa-edit'></i>
									</a>
									
									<a href="?page=revisolpn&id=2222" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title='Eliminar Menu'>
									<i class='fas fa-trash'></i>
									</a>																																 
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

		
		