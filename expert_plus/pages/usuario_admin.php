<?php
	

	require_once("dbcon/config.php");
	require_once("dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
	mysqli_set_charset($con,'utf8');	

	$xServidor = $_SERVER['HTTP_HOST'];
	$xFecha = strftime("%Y-%m-%d %H:%M:%S", time());

	$yEmprid = 1;

    $xSQL = "SELECT usua_id AS Idusuario, CONCAT(usua_nombres,' ',usua_apellidos) AS Nombres, usua_login AS Log, CASE usua_estado WHEN 'A' THEN 'Activo' ";
	$xSQL .= "ELSE 'Inactivo' END AS Estado, usua_caducapass AS CaducaPass FROM `expert_usuarios`";
	$expertusuario = mysqli_query($con, $xSQL);

	$xSQL = "SELECT p.perf_descripcion AS Descripcion,p.perf_id AS Codigo FROM `expert_perfil` p ";
	$xSQL .= " WHERE empr_id= $yEmprid AND perf_estado = 'A' ";
	$xSQL .= " UNION SELECT '  --Seleccione Perfil--  ',0";
	$xSQL .= " ORDER BY Codigo ";
    $expertperfil = mysqli_query($con, $xSQL);

?>

<div id="kt_content_container" class="container-xxl">
    <input type="hidden" id="idempr" value="<?php echo $yEmprid; ?>"  />
	<div class="card card-flush">
		<div class="card-toolbar">
			<button class="btn btn-sm btn-light-primary" id="nuevoUsuario">
			<span class="svg-icon svg-icon-2">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
					<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
					<rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
				</svg>
			</span>
		    Nuevo Usuario</button>
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
					    <th style="display:none;">Id</th>
						<th class="min-w-125px">Usuario</th>
						<th class="min-w-125px">Login</th>
						<th class="min-w-125px">Estado</th>
						<th class="min-w-125px">Departamento</th>
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
					    <td style="display:none;"><?php echo $usu['Idusuario'] ?></td>
						<td><?php echo $usu['Nombres']; ?></td>
						<td><?php echo $usu['Log']; ?></td>
						<td id="<?php echo $usu['Idusuario']; ?>">
							<div class="<?php echo $xTextColor; ?>"><?php echo $usu['Estado']; ?></div>
						</td>
						<td>Administracion</td>
						<td>
							<div class="text-center">
								<div class="btn-group">
									<button id="btnReset<?php echo $idusuario; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title='Resetear Password'>
										<i class='fa fa-key'></i>
									</button>																															 
								</div>
							</div>
						</td>
						<td>
							<div class="text-center">
								<div class="btn-group">
									<button id="btnEditar<?php echo $idusuario; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title='Editar Usuario'>
										<i class='fa fa-edit'></i>
									</button>																															 
								</div>
							</div>
						</td>
						<td>
							<div class="text-center">
								<div class="form-check form-check-sm form-check-custom form-check-solid">
									<input <?php echo $cheking; ?> class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk<?php echo $idusuario; ?>" 
									    onchange="f_Check(<?php echo $yEmprid; ?>,<?php echo $usu['Idusuario']; ?>)" value="<?php echo $idusuario; ?>"/>
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
<div class="modal fade" id="user_modal" tabindex="-1" aria-hidden="true">
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
				<input class="form-control form-control-solid" type="hidden" id="txtid" name="txtid" />
				<form id="frm_user" class="form">
					<div class="mb-13 text-center">
					    <h3 class="modal-title" id="modalLabel"></h3>
					</div>
					
					<div class="row g-9 mb-8">
					   <div class="col-md-6 fv-row">
						    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
								<span class="required">Nombre</span>
								<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="especifique el nombre del usuario"></i>
							</label>
							<input type="text" class="form-control form-control-solid" id="txtNombre" name="txtNombre" minlength="5" maxlength="100" placeholder="Ingrese Nombre" value="" />
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
							<select class="form-select form-select-solid" id="cboDepart" name="cboDepart" data-control="select2" data-hide-search="true" data-placeholder="Seleccione Departamento" name="departamento">
								<option value=""><--Seleccione--></option>
								<option value="2">Call-Center</option>
							</select>
						</div>
						<div class="col-md-6 fv-row">
							<label class="required fs-6 fw-bold mb-2">Perfil</label>
							<select class="form-select form-select-solid" id="cboPerfil" name="cboPerfil" data-control="select2" data-hide-search="true" data-placeholder="Seleccione Perfil" name="perfil">
							    <?php foreach ($expertperfil as $per) : ?>
								  <option value="<?= $per['Codigo'] ?>"><?= $per['Descripcion'] ?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>
					<!-- <div class="d-flex flex-column mb-8 fv-row">
						<label class="fs-6 fw-bold mb-2">Email</label>
						<input type="email" class="form-control form-control-solid" id="txtEmail" name="txtEmail" placeholder="ejemplo@email.com" />
					</div> -->
					<br/>
					<br/>
					<div class="row g-9 mb-8 text-center">
						<div class="col-md-6 fv-row">
							<label class="fs-6 fw-bold mb-2">Passward Caduca</label>
						</div>
						<div class="col-md-6 fv-row">
						    <label class="fs-6 fw-bold mb-2">Cambiar Password</label>   
						</div>
					</div>
					<div class="row g-9 mb-4 text-center">
						<div class="col-md-6 fv-row">
						    <input class="form-check-input h-20px w-20px border-primary" type="checkbox" id="chkCaducaPass" name="chkCaducaPass" value=""  />
							<label class="form-check-label" id="lblCaducaPass">NO</label>
						</div>
						<div class="col-md-6 fv-row">
						    <input class="form-check-input h-20px w-20px border border-primary" type="checkbox" id="chkCamPass" name="chkCamPass" value="email" />
							<label class="form-check-label" id="lblCamPass">NO</label>
						</div>
					</div>
					<div class="row g-9 mb-4 text-center">
						<div class="col-md-4 fv-row" id="content" style="display: none;">
							<input type="date" class="form-control" id="txtFechacaduca" name="fechacaduca">
						</div>
					</div>
					<br/>
					<br/>
					<div class="text-center">
						<button type="button" id="btnSave" class="btn btn-primary">
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

			var _emprid,cambiarpass, _estado,caduca,_campass,_nombre,_apellido,_login,_password,_perfil,estado,_caduca,
			_fechacaduca,_cambiarpass,_log,_usu,_dep,_fila,_tipo;

		
			//abrir-modal-nuevo-usuario
			$("#nuevoUsuario").click(function(){

				$("#frm_user").trigger("reset");
				$("#user_modal").modal("show");
				$(".modal-title").text("Nuevo Usuario");
				$("#btnSave").text("Guardar");
				$("#chkCaducaPass").prop("checked", false);
				$("#lblCaducaPass").text("NO");
				$("#chkCamPass").prop("checked", false);
				$("#lblCamPass").text("NO");  
				estado = 'A';
				_fecha = new Date();
				_fechacaduca = moment(_fecha).format("YYYY/MM/DD");
				_addmod = 'add';

				//alert(_fechacaduca);
			});

			//cambiar label -SI-NO

			caduca = 'NO';
			cambiarpass = 'NO';

			$(document).on("click","#chkCaducaPass",function(){
                
				element = document.getElementById("content");
				if($("#chkCaducaPass").is(":checked")){
					element.style.display='block';
					$("#lblCaducaPass").text("SI");
					caduca = 'SI';
				}else{
					element.style.display='none';
					$("#lblCaducaPass").text("NO");
					caduca = 'NO';					
				}

			});

			$(document).on("click","#chkCamPass",function(){

				if($("#chkCamPass").is(":checked")){
					$("#lblCamPass").text("SI");
					cambiarpass = 'SI';
				}else{
					$("#lblCamPass").text("NO");
					cambiarpass = 'NO';
				}

			});

			//Guardar usuario

			$('#btnSave').click(function(e){
				e.preventDefault();

				_emprid = $('#idempr').val();
				_estado = estado
				_nombre = $.trim($("#txtNombre").val());
				_apellido = $.trim($("#txtApellido").val());
				_login = $.trim($("#txtLogin").val());
				_password = $.trim($("#txtPassword").val());
				_perfil = $('#cboPerfil').val();
				_perfilname = $("#cboPerfil option:selected").text();				
				_departamento = $('#cboDepart').val();
				_caduca = caduca;
				_cambiarpass = cambiarpass;
				_fechacaduca = $.trim($("#txtFechacaduca").val());
				_buscar = 'SI';
				_continuar = 'SI';

				//alert(_emprid);
				//debugger;

				if(_nombre == ''){                        
					mensajesweetalert("center","warning","ingrese un nombre",false,1800);
					return;
				}

				if(_apellido == ''){                        
					mensajesweetalert("center","warning","ingrese un apellido",false,1800);
					return;
				}

				if(_login == ''){                        
					mensajesweetalert("center","warning","ingrese un login",false,1800);
					return;
				}

				if(_password == ''){                        
					mensajesweetalert("center","warning","ingrese una contraseña",false,1800);
					return;
				}

				
				if(_perfil == 0){                        
					mensajesweetalert("center","warning","ingrese un perfil",false,1800);
					return;
				}

				$parametros = {
					xxEmprid: _emprid,
					xxLogin: _login
				}
				
				if(_addmod == 'mod'){
					if(_loginold != _login){
						_buscar = 'SI';
					}else{
						_buscar = 'NO';
					}
				}

				if(_buscar == 'SI'){
					var xrespuesta = $.post("codephp/consultar_usuarios.php", $parametros);
					xrespuesta.done(function(response){
						if(response == 0){
							_continuar = 'SI'
						}else{
							_continuar = 'NO'
							mensajesweetalert("center","warning","Nombre del Usuario ya Existe..!",false,1800);
							return;
						}
					});
				}

				if(_continuar == 'SI'){
					
					if(_addmod == 'add'){
						$datosUser = {
							xxEmprid: _emprid,
							xxEstado: _estado,
							xxNombre: _nombre,
							xxApellido:_apellido,
							xxLogin: _login,
							xxPassword: _password,
							xxPerfil: _perfil,
							xxCaducaPass: _caduca,
							xxCambiarPass: _cambiarpass,
							xxFecha: _fechacaduca
						}
						_ulr = "codephp/grabar_usuarios.php";	
					}else{
						_userid = $('#txtid').val();

						$datosUser = {
							xxEmprid: _emprid,
							xxUserid: _userid,
							xxEstado: _estado,
							xxNombre: _nombre,
							xxApellido:_apellido,
							xxLogin: _login,
							xxPerfil: _perfil,
							xxCaducaPass: _caduca,
							xxCambiarPass: _cambiarpass,
							xxFecha: _fechacaduca
						}	
						_ulr = "codephp/editar_usuarios.php";
					}
					
					$.ajax({
							url: _ulr,
							type: "POST",
							dataType: "json",
							data: $datosUser,          
							success: function(data){ 
								if(data != 0){

									_userid = data;										
									_usuario = _nombre + ' ' + _apellido;

									var _estado = '<td><div class="badge badge-light-primary">Activo</div>' ;

									var _btnreset = '<td><div class="text-center"><div class="btn-group"><button id="btnReset' + _userid + '"' + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Resetear Perfil" >' + 
				 						'<i class="fa fa-key"></i></button></div></div></td>';

									var _btnedit = '<td><div class="text-center"><div class="btn-group"><button id="btnEditar' + _userid + '"' + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title="Editar Usuario" >' + 
				 						'<i class="fa fa-edit"></i></button></div></div></td>';

									var _btnchk = '<td style="text-align:center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
												'<input class="form-check-input btnEstado" type="checkbox" id="chk' + _userid + '" checked onchange="f_Check(' +
												_emprid + ',' + _userid + ')"' + ' value="' + _userid + '"' + '/></div></td>';	
												
									TableData = $('#kt_ecommerce_report_shipping_table').DataTable();

									TableData.column(0).visible(0);
										
									if(_addmod == 'add'){
										TableData.row.add([_userid, _usuario, _login, _estado, _perfilname, _btnreset, _btnedit, _btnchk]).draw();
									}
									else{
										TableData.row(_fila).data([_userid, _usuario, _login, _estado, _perfilname, _btnreset, _btnedit, _btnchk]).draw();
									} 

									$("#user_modal").modal("hide");									

								}                                                                         
							},
							error: function (error){
								console.log(error);
							}                            
						}); 					

				}

				// var xrespuesta = $.post("codephp/consultar_usuarios.php", $parametros);
				// xrespuesta.done(function(response){
				// 	//console.log(response);
				// 	if(response == 0){

				// 		$datosUser = {
				// 			xxEmprid: _emprid,
				// 			xxEstado: _estado,
				// 			xxNombre: _nombre,
				// 			xxApellido:_apellido,
				// 			xxLogin: _login,
				// 			xxPassword: _password,
				// 			xxPerfil: _perfil,
				// 			xxCaducaPass: _caduca,
				// 			xxCambiarPass: _cambiarpass,
				// 			xxFecha: _fechacaduca
				// 		}

				// 		if(_addmod == 'add'){
				// 			_ulr = "codephp/grabar_usuarios.php";							
				// 		}else{
				// 			_ulr = "codephp/editar_usuarios.php";							
				// 		}

				// 		$.ajax({
				// 			url: _ulr,
				// 			type: "POST",
				// 			dataType: "json",
				// 			data: $datosUser,          
				// 			success: function(data){ 
				// 				if(data != 0){

				// 					if(_addmod == 'add'){
				// 						_userid = data;
				// 					}else{
				// 						_userid = $('#txtid').val();
				// 					}
									
				// 					_usuario = _nombre + ' ' + _apellido;

				// 					var _estado = '<td><div class="badge badge-light-primary">Activo</div>' ;

				// 					var _btnreset = '<td><div class="text-center"><div class="btn-group"><button id="btnReset' + _userid + '"' + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Resetear Perfil" >' + 
				//  						'<i class="fa fa-key"></i></button></div></div></td>';

				// 					var _btnedit = '<td><div class="text-center"><div class="btn-group"><button id="btnEditar' + _userid + '"' + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title="Editar Usuario" >' + 
				//  						'<i class="fa fa-edit"></i></button></div></div></td>';

				// 					var _btnchk = '<td style="text-align:center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
				// 								'<input class="form-check-input btnEstado" type="checkbox" id="chk' + _userid + '" checked onchange="f_Check(' +
				// 								_emprid + ',' + _userid + ')"' + ' value="' + _userid + '"' + '/></div></td>';	
												
				// 					TableData = $('#kt_ecommerce_report_shipping_table').DataTable();

				// 					TableData.column(0).visible(0);
										

				// 					if(_addmod == 'add'){

				// 						TableData.row.add([_userid, _usuario, _login, _estado, _perfilname, _btnreset, _btnedit, _btnchk]).draw();
				// 					}
				// 					else{
				// 						TableData.row(_fila).data([_userid, _usuario, _login, _estado, _perfilname, _btnreset, _btnedit, _btnchk]).draw();
				// 					} 

				// 					$("#user_modal").modal("hide");									
				// 				}                                                                         
				// 			},
				// 			error: function (error){
				// 				console.log(error);
				// 			}                            
				// 		}); 
				// 	}else{
				// 		mensajesweetalert("center","warning","Nombre del Usuario ya Existe..!",false,1800);
				// 	}
				// });

			});

			//editar modal usuario

			$(document).on("click",".btnEditar",function(){
                
				_fila = $(this).closest("tr");
				_data = $('#kt_ecommerce_report_shipping_table').dataTable().fnGetData(_fila);
				_idusu = _data[0];
				_loginold = _data[2];
				_addmod = 'mod';
				
				$parametros = {
					xxIdUsuario: _idusu,
				}

				$.ajax({
					url: "codephp/editar_usuarios.php",
					type: "POST",
					dataType: "json",
					data: $parametros,          
					success: function(data){ 
						//console.log(data);
						var _nombres = data[0]['Nombres'];
						var _apellidos = data[0]['Apellidos'];
						var _login = data[0]['Login'];
						var _password = data[0]['Password'];
						var _cboPerfil = data[0]['CodigoPerfil'];
						var _passCaduca = data[0]['CaducaPass'];
						var _fechaCaduca = data[0]['FechaCaduca'];
						var _cambiarPass = data[0]['CambiarPass'];

						$("#txtNombre").val(_nombres);
						$("#txtApellido").val(_apellidos);
						$("#txtApellido").val(_apellidos);
						$("#txtLogin").val(_login);
						$("#txtPassword").val(_password);
						$("#cboPerfil").val(_cboPerfil).change();
						$("#txtFechacaduca").val(_fechaCaduca);

						if(_passCaduca == 'SI'){
							$("#chkCaducaPass").prop("checked", true);
							$("#lblCaducaPass").text("SI");                
						}

						if(_cambiarPass == 'SI'){
							$("#chkCamPass").prop("checked", true);
							$("#lblCamPass").text('SI');
						}
						                                                                      
					},
					error: function (error){
						console.log(error);
					}                            
				}); 
				
                $(".modal-title").text("Editar Usuario");				
                $("#btnSave").text("Modificar");
				$("#txtid").val(_idusu);
				$("#frm_user").trigger("reset");
			    $("#user_modal").modal("show");
				

			});

		
		});	

		$(document).on("click",".btnEstado",function(e){
				_fila = $(this).closest("tr");
				_usu = $(this).closest("tr").find('td:eq(1)').text();
				_log = $(this).closest("tr").find('td:eq(2)').text();  
				_dep = $(this).closest("tr").find('td:eq(4)').text(); 
				//console.log(_usuario);
		});

		function f_Check(_emprid, _userid){
                
				let _check = $("#chk" + _userid).is(":checked");
				let _btn = "btnEditar" + _userid;
				let _td = "td" + _userid;
				let _checked = "";
				let _disabled = "";
				let _class = "badge badge-light-primary";
	
				//alert(_td);
	
				if(_check){
					_tipo = "Activo";
					_checked = "checked='checked'";
				}else{
						_tipo = "Inactivo";
						_disabled = "disabled";
						_class = "badge badge-light-danger";
				}
	
				var _lblEstado = '<td><div class="' + _class + '">' + _tipo + ' </div>';
	
				var _btnEdit = '<td><div class="text-center"><div class="btn-group"><button ' + _disabled + 'id="btnEditar' + _userid + '"' +
								  'class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title="Editar Usuario">' +
								  '<i class="fa fa-edit"></i></button></div></div></td>';
				
				var _btnReset = '<td><div class="text-center"><div class="btn-group"><button ' + _disabled + 'id="btnReset' + _userid + '"' +
								'class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Resetear Password">' +
								'<i class="fa fa-key"></i></button></div></div></td>';
	
				var _btnchk = '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
							  '<input ' + _checked + 'class="form-check-input h-20px w-20px border-primary" type="checkbox" id="chk' + _userid + '"' +
							  'onchange="f_Check(' +_emprid  + ',' + _userid + ')"' + 'value="' + _userid + '"' + '/></div></div></td>';
	
				//console.log(_fila);
	
	
				TableData = $('#kt_ecommerce_report_shipping_table').DataTable();
	
				TableData.row(_fila).data([_userid,_usu,_log,_lblEstado,_dep,_btnReset,_btnEdit,_btnchk ]).draw();
	
						$parametros = {
						  xxUsuId: _userid,
						  xxEmpr: _emprid,
						  xxTipo: _tipo
						}	
	
				var xrespuesta = $.post("codephp/delnew_usuario.php", $parametros);
				xrespuesta.done(function(response){
					//console.log(response);
				});	
								   
			}

            //desplazar ventana modal
			$("#user_modal").draggable({
				handle: ".modal-header"
			}); 


	</script> 	