
<?php
	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	

	$page = isset($_GET['page']) ? $_GET['page'] : "index";

	require_once("dbcon/config.php");
	require_once("dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

	//$xServidor = $_SERVER['HTTP_HOST'];
    //$xFecha = strftime("%Y-%m-%d %H:%M:%S", time());

	session_start();

    if(isset($_SESSION["s_usuario"])){
        if($_SESSION["s_loged"] != "loged"){
            header("Location: ./logout.php");
            exit();
        }
    } else{
        header("Location: ./logout.php");
        exit();
    }

	$xUsuaid = $_SESSION["i_usuaid"];
    $xPaisid = $_SESSION["i_paisid"];
    $xEmprid = $_SESSION["i_emprid"];
	$mensaje = (isset($_POST['mensaje'])) ? $_POST['mensaje'] : '';

	$xSQL = "SELECT tare_id AS Id, tare_nombre AS Tarea, tare_pagina AS Pagina, tare_ruta AS Ruta, tare_titulo AS Titulo, tare_descripcion AS Descripcion, CASE tare_estado WHEN 'A' THEN 'Activo' ";
	$xSQL .= "ELSE 'Inactivo' END AS Estado FROM `expert_tarea` WHERE empr_id=$xEmprid ORDER BY tare_orden";
	$all_tareas = mysqli_query($con, $xSQL);
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
							<a class="nav-link text-active-primary ms-0 me-10 py-5 active" href="?page=suptarea&menuid=0">Tareas</a>
						</li>
						<li class="nav-item mt-2">
							<a class="nav-link text-active-primary ms-0 me-10 py-5" href="?page=supmenu&menuid=0">Menu</a>
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
						<button type="button" class="btn btn-primary" id="btnNuevo">
							<span class="svg-icon svg-icon-2">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
									<rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
								</svg>
							</span>
						Nueva Tarea</button>
					</div>

				</div>

				<div class="card-body pt-0">
					<table class="table align-middle table-row-dashed fs-6 gy-5 table-hover" id="kt_ecommerce_report_shipping_table" style="width: 100%;">
						<thead>
							<tr class="text-start text-gray-800 fw-bolder fs-7 gs-0">
								<th style="display:none;">IdTarea</th>
								<th>Tarea</th>
								<th>Pagina</th>
								<th>Ruta</th>
								<th>Titulo</th>
								<th>Descripcion</th>
								<th>Estado</th>
								<th>Status</th>
								<th style="text-align:center;">Opciones</th>
							</tr>
						</thead>
						<tbody class="fw-bold text-gray-600">
							<?php 
							
							foreach($all_tareas as $tareas) { ?>
							
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
									<td><?php echo $tareas['Pagina']; ?></td>
									<td><?php echo $tareas['Ruta']; ?></td>
									<td><?php echo $tareas['Titulo']; ?></td>
									<td><?php echo $tareas['Descripcion']; ?></td>
									<td id="td_<?php echo $tareas['Id']; ?>">
										<div class="<?php  echo $xTextColor; ?>"><?php echo $tareas['Estado']; ?></div>
									</td>
									<td>
										<div class="text-center">
											<div class="form-check form-check-sm form-check-custom form-check-solid">
													<input class="form-check-input btnEstado" type="checkbox" <?php echo $chkEstado; ?> id="chk<?php echo $tareas['Id']; ?>" <?php if ($tareas['Estado'] == 'Activo') {
															echo "checked";} else {'';} ?> value="<?php echo $tareas['Id']; ?>" onchange="f_UpdateEstado(<?php echo $tareas['Id']; ?>,<?php echo $xEmprid; ?>)"/>
											</div>
										</div>
									</td>
									<td>
										<div class="text-center">
											<div class="btn-group">
												<button <?php echo $xDisabledEdit ?> id="btnEditar_<?php echo $tareas['Id']; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title='Editar Tarea'>
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

		<!--end::Modal - Create App-->
		<!--begin::Modal - New Address-->
		<div class="modal fade" id="modal-tarea" tabindex="-1" aria-hidden="true">
			<!--begin::Modal dialog-->
			<div class="modal-dialog modal-dialog-centered mw-650px">
				<div class="modal-content">
					<form class="form" id="frm_datos">
						<div class="modal-header" id="kt_modal_new_address_header">
							<h2 class="modal-title" id="modalLabel"></h2>
							<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
								<span class="svg-icon svg-icon-1">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
										<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
										<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
									</svg>
								</span>
							</div>
						</div>
												
						<div class="modal-body py-10 px-lg-17">
							<div class="scroll-y me-n7 pe-7" id="kt_modal_new_address_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_new_address_header" data-kt-scroll-wrappers="#kt_modal_new_address_scroll" data-kt-scroll-offset="300px">
								<div class="d-flex flex-column mb-5 fv-row">
									<label class="required fs-5 fw-bold mb-2">Tarea</label>
									<input class="form-control form-control-solid" id="txtTarea" name="txtTarea" maxlength="100" placeholder="Ingrese Tarea"  />
								</div>
								
								<div class="d-flex flex-column mb-5 fv-row">
									<label class="required fs-5 fw-bold mb-2">Pagina</label>
									<input class="form-control form-control-solid" id="txtPagina" name="txtPagina" maxlength="150" placeholder="Ingrese Nombre de la pagina" />
								</div>

								<div class="d-flex flex-column mb-5 fv-row">
									<label class="required fs-5 fw-bold mb-2">Ruta</label>
									<input class="form-control form-control-solid" id="txtRuta" name="txtRuta" maxlength="100" placeholder="/../pages/nombre_pagina.php" />
								</div>

								<div class="d-flex flex-column mb-5 fv-row">
									<label class="fs-5 fw-bold mb-2">Titulo</label>
									<input class="form-control form-control-solid" id="txtTitulo" name="txtTitulo" maxlength="150" placeholder="Ingrese Titulo" />
								</div>

								<div class="d-flex flex-column mb-5 fv-row">
									<label class="fs-5 fw-bold mb-2">Descripcion</label>
									<input class="form-control form-control-solid" id="txtDescripcion" name="txtDescripcion" maxlength="150" placeholder="Ingrese Descripcion" />
								</div>


							</div>
						</div>

						<div class="modal-footer flex-center">
							<button type="button" id="btnSave" class="btn btn-primary">
								<span class="indicator-label">Grabar</span>
								<span class="indicator-progress">Please wait...
								<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!--end::Modal - New Address-->
		<!--begin::Modal - Users Search-->		
		
		<script>
			$(document).ready(function(){
				_mensaje = $('input#mensaje').val();

				if(_mensaje != ''){
					mensajesweetalert("center", "success", _mensaje + "..!", false, 1800);
				}

				$("#btnNuevo").click(function(){
					$("#modal-tarea").modal("show");
					$(".modal-title").text("Nueva Tarea");
					$("#btnSave").text("Grabar");
					$("#frm_datos").trigger("reset");
					_addmod = 'add';
					_idtarea = 0;
				});

				$(document).on("click",".btnEditar",function(){
                
					_fila = $(this).closest("tr");
					_data = $('#kt_ecommerce_report_shipping_table').dataTable().fnGetData(_fila);
					_idtarea = _data[0];					
					_tareaold = _data[1];
					_paginaold = _data[2];
					_addmod = 'mod';

					$(".modal-title").text("Editar Tarea");				
					$("#btnSave").text("Modificar");
					$("#frm_datos").trigger("reset");
					$("#modal-tarea").modal("show");
					$("#txtTarea").val(_data[1]);					
					$("#txtPagina").val(_data[2]);
					$("#txtRuta").val(_data[3]);
					$("#txtTitulo").val(_data[4]);
					$("#txtDescripcion").val(_data[5]);

				});				

				$('#btnSave').click(function(e){

					var _paisid = "<?php echo $yPaisid; ?>"
					var _emprid = "<?php echo $xEmprid; ?>"
					var _usuaid = "<?php echo $yUsuaid; ?>"
					var _tarea = $.trim($("#txtTarea").val());
					var _pagina = $.trim($("#txtPagina").val());
					var _ruta = $.trim($("#txtRuta").val());
					var _titulo = $.trim($("#txtTitulo").val());
					var _descripcion = $.trim($("#txtDescripcion").val());
					 _buscar = 'SI';
					_respuesta = 'OK';

					if(_tarea == ''){                        
						mensajesweetalert("center","warning","Ingrese Tarea",false,1800);
						return;
					}

					if(_pagina == ''){
						mensajesweetalert("center","warning","Ingrese Pagina",false,1800);
						return;
					}					

					if(_ruta == ''){
						mensajesweetalert("center","warning","Ingrese Ruta",false,1800);
						return;
					}

					if(_addmod == 'mod'){
						if(_tareaold != _tarea){
							_buscar = 'SI';
						}else{
							_buscar = 'NO';
						}

						if(_paginaold != _pagina){
							_buscar = 'SI';
						}else{
							_buscar = 'NO';
						}
						
						_ulr = "codephp/update_tarea.php";
					}else{
						_ulr = "codephp/new_tarea.php";
					}

					var _datosTarea = {
						"xxEmprid" : _emprid,
						"xxUsuaid" : _usuaid,
						"xxTareaId" : _idtarea,
						"xxTarea" : _tarea,
						"xxPagina" : _pagina,
						"xxRuta" : _ruta,
						"xxTitulo" : _titulo,
						"xxDescripcion" : _descripcion  
					}	

					if(_buscar == 'SI'){
						var xrespuesta = $.post("codephp/consultar_tarea.php", { xxEmprid: _emprid, xxTarea: _tarea, xxPagina: _pagina });
						xrespuesta.done(function(response){							
							if(response == 0){

								$.post(_ulr, _datosTarea , function(data){

									var _tareaid = data;

									if(_tareaid != 0){
										// var _estado = '<td><div class="badge badge-light-primary">Activo</div>' ;

										// var _btnchk = '<td style="text-align:center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
										// 	'<input class="form-check-input btnEstado" type="checkbox" checked id="chk' + _tareaid + ' value="' + _tareaid + '" onchange="f_UpdateEstado(' + _tareaid + ',' + _emprid + ')"' + '/></div></td>';								

										// var _btnedit = '<td><div class="text-center"><div class="btn-group"><button id="btnEditar' + _tareaid + '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title="Editar Tarea" >' + 
										// 	'<i class="fa fa-edit"></i></button></div></div></td>';

										//TableData = $('#kt_ecommerce_report_shipping_table').DataTable();

										if(_addmod == 'add'){
											//TableData.column(0).visible(0);
											//TableData.row.add([_tareaid, _tarea, _ruta, _estado, _btnchk, _btnedit]).draw();
											
											/*var tbl = document.getElementById("tbody");
											tbl.innerHTML += '<tr><td style="display:none;">' + _tareaid + '</td>' + 
															'<td>' + _tarea + '</td>' + '<td>' + _ruta + '</td>' + _estado + _btnchk + _btnedit;*/

											_detalle = 'Nueva tarea creada desde superadmin';
											_mensaje = 'Grabado con Exito';
										}
										else{
											//TableData.row(_fila).data([_tareaid, _tarea, _ruta, _estado, _btnchk, _btnedit]).draw();
											_detalle = 'Actualizar tarea desde superadmin';
											_mensaje = 'Actualizado con Exito';
										} 
									}else{
										//console.log('Error encontrado en sentecia SQL');
										_detalle = 'Error encontrado en sentecia SQL desde superadmin';
										_respuesta = 'ERR';
									}

									/**PARA CREAR REGISTRO DE LOGS */
									var _parametros = {
										"xxPaisid" : _paisid,
										"xxEmprid" : _emprid,
										"xxUsuaid" : _usuaid,
										"xxDetalle" : _detalle,
									}					

									$.post("codephp/new_log.php", _parametros, function(response){
									});

									if(_respuesta == 'OK'){
										$.redirect('?page=suptarea&menuid=0', {'mensaje': _mensaje}); //POR METODO POST
									}

								});	

								//funGrabar(_paisid,_emprid,_usuaid,_tarea,_ruta);
							}else{								
								mensajesweetalert("center","warning","Tarea ya Existe..!",false,1800);
							}
						});						
					}else{

						$.post(_ulr, $datosTarea , function(data){

							var _tareaid = data;

							if(_tareaid != 0){

								if(_addmod == 'add'){
									_detalle = 'Nueva tarea creada desde superadmin';
									_mensaje = 'Grabado con Exito';
								}
								else{
									_detalle = 'Actualizar tarea desde superadmin';
									_mensaje = 'Actualizado con Exito';
								} 
							}else{
								_detalle = 'Error encontrado en sentecia SQL desde superadmin';
								_respuesta = 'ERR';
							}


							/**PARA CREAR REGISTRO DE LOGS */
							var _parametros = {
								"xxPaisid" : _paisid,
								"xxEmprid" : _emprid,
								"xxUsuaid" : _usuaid,
								"xxDetalle" : _detalle,
							}					

							$.post("codephp/new_log.php", _parametros, function(response){
							});

							if(_respuesta == 'OK'){
								$.redirect('?page=suptarea&menuid=0', {'mensaje': _mensaje}); //POR METODO POST																	
							}							

						});	
					}
				});

			});

			$(document).on("click",".btnEstado",function(e){
					_fila = $(this).closest("tr");
					_tarea = $(this).closest("tr").find('td:eq(1)').text();  
					_ruta = $(this).closest("tr").find('td:eq(2)').text(); 					
			});			

			function f_UpdateEstado(_tareaid, _emprid){

				var _paisid = "<?php echo $yPaisid; ?>"
				var _emprid = "<?php echo $xEmprid; ?>"
				var _usuaid = "<?php echo $yUsuaid; ?>"

				let _check = $("#chk" + _tareaid).is(":checked");
				let _checked = "";
				let _disabled = "";
				let _class = "badge badge-light-primary";
				let _estado = "";
                let _td = "td_" + _tareaid;
                let _btnedit = "btnEditar_" + _tareaid;				

				if(_check){
					_estado = "Activo";
					//_disabled = "";
					_checked = "checked='checked'";
					_class = "badge badge-light-primary";
                    $('#'+_btnedit).prop("disabled",false);
				}else{
					_estado = "Inactivo";
					//_disabled = "disabled";
					_class = "badge badge-light-danger";
					$('#'+_btnedit).prop("disabled",true);
				}

                var _changetd = document.getElementById(_td);
                _changetd.innerHTML = '<td><div class="' + _class + '">' + _estado + ' </div>';				

				// var _lblEstado = '<td><div class="' + _class + '">' + _estado + ' </div>';

				// var _btnchk = '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
				// 			'<input class="form-check-input btnEstado" type="checkbox" ' + ' id="chk' + _tareaid + '"' +
				// 			' ' + _checked + ' value="' + _tareaid + '" onchange="f_UpdateEstado(' +_tareaid  + ',' + _emprid + ')"/>' +
				// 			'</div></div></td>';

				// var _btnedit = '<td><div class="text-center"><div class="btn-group"><button ' + _disabled + 
				// 			' id="btnEditar' + _tareaid + '" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title="Editar Tarea">' +
				// 			'<i class="fa fa-edit"></i></button></div></div></td>';

				// TableData = $('#kt_ecommerce_report_shipping_table').DataTable();

				// TableData.row(_fila).data([_tareaid, _tarea, _ruta, _lblEstado, _btnchk, _btnedit]).draw();			

				var _parametros = {
					"xxEmprid" : _emprid,
					"xxTareaId" : _tareaid,
					"xxEstado" : _estado
				}

				$.post("codephp/update_estado_tarea.php", _parametros , function(data){

				});
			}			

			$("#modal-tarea").draggable({
					handle: ".modal-header"
			}); 			

		</script>