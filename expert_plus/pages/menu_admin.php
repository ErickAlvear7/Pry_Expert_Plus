<?php
	$page = isset($_GET['page']) ? $_GET['page'] : "index";

	require_once("dbcon/config.php");
	require_once("dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

	$xServidor = $_SERVER['HTTP_HOST'];
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());

	$xEmprid = 1;

	$xSQL = "SELECT menu_id AS Idmenu, empr_id AS Empid, menu_descripcion AS Menu, menu_observacion AS Observacion, CASE menu_estado WHEN 'A' THEN 'Activo' ";
	$xSQL .= "ELSE 'Inactivo' END AS Estado FROM `expert_menu`";
	$expertmenu = mysqli_query($con, $xSQL);
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
						<th style="display:none;">Idmenu</th>
						<th style="width: 30px;">Menu</th>
						<th style="width: 30px;">Descripcion</th>
						<th style="width: 30px;">Estado</th>
						<th style="width: 30px; text-align:center;">Opciones</th>
						<th style="width: 10px;">Status</th>
					</tr>
				</thead>
				<tbody class="fw-bold text-gray-600">
					<?php 
					
					foreach($expertmenu as $menu){
                      $xMenu = $menu['Menu'];
					?>
					<?php 

                     $chkEstado = '';
					 $xDisabledEdit = '';

					if($xMenu == 'Seguridad'){
						$chkEstado = 'disabled';
						$xDisabledEdit = 'disabled';
					}elseif($menu['Estado'] == 'Inactivo'){
                          $xDisabledEdit = 'disabled';
					}

					if($menu['Estado'] == 'Activo'){
						$xTextColor = "badge badge-light-primary";
					}else{
						$xTextColor = "badge badge-light-danger";
					}
					
					?>
					<tr>
						<td style="display:none;"><?php echo $menu['Idmenu']; ?></td>
						<td><?php echo $menu['Menu']; ?></td>
						<td><?php echo $menu['Observacion']; ?></td>
						<td>
						   <div class="<?php  echo $xTextColor; ?>"><?php echo $menu['Estado']; ?></div>
						</td>
						<td>
							<div class="text-center">
								<div class="btn-group">
									<button <?php echo $xDisabledEdit ?> onclick="f_Editar(<?php echo $menu['Idmenu']; ?>)" id="btnEditar" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title='Editar Perfil'>
										<i class='fa fa-edit'></i>
									</button>																															 
								</div>
							</div>
						</td>
						<td>
							<div class="text-center">
								<div class="form-check form-check-sm form-check-custom form-check-solid">
										<input class="form-check-input btnEstado" type="checkbox" <?php echo $chkEstado; ?> id="chk<?php echo $menu['Idmenu']; ?>" <?php if ($menu['Estado'] == 'Activo') {
												echo "checked";} else {'';} ?> value="<?php echo $menu['Idmenu']; ?>"  onchange="f_Check(<?php echo $xEmprid; ?>,<?php echo $menu['Idmenu']; ?>)" />
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

	$(document).on("click",".btnEstado",function(e){
			_fila = $(this).closest("tr");
			_menuid = $(this).closest("tr").find('td:eq(0)').text();
			_menu = $(this).closest("tr").find('td:eq(1)').text();  
			_desc = $(this).closest("tr").find('td:eq(2)').text(); 
	});

	function f_Check(_emprid, _menuid){

		let _check = $("#chk" + _menuid).is(":checked");
		let _checked = "";
		let _disabled = "";
		let _class = "badge badge-light-primary";

		if(_check){
			_tipo = "Activo";
			_disabled = "";
			_checked = "checked='checked'";
			_class = "badge badge-light-primary";
		}else{
			_tipo = "Inactivo";
			_disabled = "disabled";
			_class = "badge badge-light-danger";
		}


		var _lblEstado = '<td><div class="' + _class + '">' + _tipo + ' </div>';

		var _btnEdit = '<td><div class="text-center"><div class="btn-group"><button ' + _disabled + ' onclick="f_Editar(' +  _menuid + ')"' +
		               'id="btnEditar" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Editar Perfil">' +
					   '<i class="fa fa-edit"></i></button></div></div></td>';

		var _btnchk = '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
		              '<input class="form-check-input btnEstado" type="checkbox" ' +  _tipo  +  ' id="chk' + _menuid + '"' +
					  ' ' + _checked + ' value="' + _menuid + '" onchange="f_Check(' +_emprid  + ',' + _menuid + ')"/>' +
					  '</div></div></td>';

		//console.log(_btnchk);
		
		TableData = $('#kt_ecommerce_report_shipping_table').DataTable();

		TableData.row(_fila).data([_menuid,_menu,_desc,_lblEstado,_btnEdit,_btnchk ]).draw();

		var _parametros = {
			"xxMenuId" : _menuid,
			"xxEmpr" : _emprid,
			"xxTipo" : _tipo
		}	

		var xrespuesta = $.post("codephp/estado_menu.php", _parametros);
		xrespuesta.done(function(response){
			//console.log(response);
		});	


	}


</script> 	

		
		