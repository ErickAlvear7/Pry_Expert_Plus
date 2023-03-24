
<?php
	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	    

	$page = isset($_GET['page']) ? $_GET['page'] : "index";

	require_once("./dbcon/config.php");
	require_once("./dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

	$xUsuaid = $_SESSION["i_usuaid"];	
	$xPaisid = $_SESSION["i_paisid"];	
    $xEmprid = $_SESSION["i_emprid"];

	$mensaje = (isset($_POST['mensaje'])) ? $_POST['mensaje'] : '';
    
	$xSQL = "SELECT menu_id AS Idmenu, empr_id AS Empid, menu_descripcion AS Menu, menu_observacion AS Observacion, CASE menu_estado WHEN 'A' THEN 'Activo' ";
	$xSQL .= "ELSE 'Inactivo' END AS Estado FROM `expert_menu`";
	$all_menu = mysqli_query($con, $xSQL);
	
?>	
		<!--begin::Container-->
		<div id="kt_content_container" class="container-xxl">
			<div class="card mb-5 mb-xxl-8">
				<div class="card-body pt-9 pb-0">
					<div class="d-flex flex-wrap flex-sm-nowrap">
						<div class="flex-grow-1">
							<div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
								<div class="d-flex flex-column">
								</div>
								<div class="d-flex my-0">
									<div class="me-0">
										<button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
											<i class="bi bi-three-dots fs-3"></i>
										</button>
										<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
											<div class="menu-item px-3">
												<div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Otras Acciones</div>
											</div>
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Accion 1</a>
											</div>
											<div class="menu-item px-3">
												<a href="#" class="menu-link flex-stack px-3">Accion 2
												<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference"></i></a>
											</div>
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Accion 3</a>
											</div>
											<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-end">
												<a href="#" class="menu-link px-3">
													<span class="menu-title">Configuraciones</span>
													<span class="menu-arrow"></span>
												</a>
												<div class="menu-sub menu-sub-dropdown w-175px py-4">
													<div class="menu-item px-3">
														<a href="#" class="menu-link px-3">Configuracion 1</a>
													</div>
													<div class="menu-item px-3">
														<a href="#" class="menu-link px-3">Configuracion 2</a>
													</div>
													<div class="menu-item px-3">
														<a href="#" class="menu-link px-3">Configuracion 3</a>
													</div>
													<div class="separator my-2"></div>
													<div class="menu-item px-3">
														<div class="menu-content px-3">
															<label class="form-check form-switch form-check-custom form-check-solid">
																<input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />
																<span class="form-check-label text-muted fs-6">Accion Cheched</span>
															</label>
														</div>
													</div>
												</div>
											</div>
											<div class="menu-item px-3 my-1">
												<a href="#" class="menu-link px-3">Seteos</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder">
						<li class="nav-item mt-2">
							<a class="nav-link text-active-primary ms-0 me-10 py-5" href="?page=suptarea&menuid=0">Tareas</a>
						</li>
						<li class="nav-item mt-2">
							<a class="nav-link text-active-primary ms-0 me-10 py-5 active" href="?page=supmenu&menuid=0">Menu</a>
						</li>
						<li class="nav-item mt-2">
							<a class="nav-link text-active-primary ms-0 me-10 py-5" href="?page=supperfil&menuid=0">Perfil</a>
						</li>
						<li class="nav-item mt-2">
							<a class="nav-link text-active-primary ms-0 me-10 py-5" href="?page=supusuario&menuid=0">Usuarios</a>
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
						<a href="?page=addmenu&menuid=0" class="btn btn-primary">
							<span class="svg-icon svg-icon-2">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
									<rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
								</svg>
							</span>
						Nuevo Menu</a>
					</div>											
				</div>

				<div class="card-body pt-0">
					<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_report_shipping_table" style="width: 100%;">
						<thead>
							<tr class="text-start text-gray-800 fw-bolder fs-7 gs-0">
								<th style="display:none;">Idmenu</th>
								<th>Menu</th>
								<th>Descripcion</th>
								<th>Estado</th>
								<th>Status</th>
								<th style="text-align:center;">Opciones</th>
							</tr>
						</thead>
						<tbody class="fw-bold text-gray-600">
							<?php 
							
								foreach($all_menu as $menu) { 
									$xMenu = $menu['Menu'];
							?>

								<?php 

									$chkEstado = '';
									$xDisabledEdit = '';

									if($menu['Idmenu'] == 200001){
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
										<div class="form-check form-check-sm form-check-custom form-check-solid">
												<input class="form-check-input btnEstado" type="checkbox" <?php echo $chkEstado; ?> id="chk<?php echo $menu['Idmenu']; ?>" <?php if ($menu['Estado'] == 'Activo') {
												echo "checked";} else {'';} ?> value="<?php echo $menu['Idmenu']; ?>"  onchange="f_Check(<?php echo $xEmprid; ?>,<?php echo $menu['Idmenu']; ?>)" />
										</div>
									</div>
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
							</tr>
							<?php } ?>  
						</tbody>
					</table>
				</div>				
			</div>
		</div>
		<!--end::Container-->

		<script>

			$(document).ready(function(){
				_mensaje = $('input#mensaje').val();

				if(_mensaje != ''){
					//mensajesalertify(_mensaje+"..!","S","top-center",5);
					mensajesweetalert("center","warning",_mensaje,false,1800);
				}
			});

			function f_Editar(_idmenu){
				$.redirect('?page=editmenu&menuid=0', {'idmenu': _idmenu}); //POR METODO POST

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
				
				var _btnchk = '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
							'<input class="form-check-input btnEstado" type="checkbox" ' +  _tipo  +  ' id="chk' + _menuid + '"' +
							' ' + _checked + ' value="' + _menuid + '" onchange="f_Check(' +_emprid  + ',' + _menuid + ')"/>' +
							'</div></div></td>';
				
				var _btnEdit = '<td><div class="text-center"><div class="btn-group"><button ' + _disabled + ' onclick="f_Editar(' +  _menuid + ')"' +
							'id="btnEditar" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Editar Perfil">' +
							'<i class="fa fa-edit"></i></button></div></div></td>';

				TableData = $('#kt_ecommerce_report_shipping_table').DataTable();

				TableData.row(_fila).data([_menuid, _menu, _desc, _lblEstado, _btnchk, _btnEdit ]).draw();

				$parametros = {
					xxMenuId: _menuid,
					xxEmpr: _emprid,
					xxTipo: _tipo
				}	

				$.post("codephp/estado_menu.php", $parametros, function(response){
                        //console.log(response);
                }); 				


			}
		</script> 			