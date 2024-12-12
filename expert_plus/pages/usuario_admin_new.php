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
	$xCodigoPerf = 0;
    $xCantidad = 0;
    $xTotalUsuarios = 0;

	$xFechaActual = strftime('%Y-%m-%d', time());
	$mensaje = (isset($_POST['mensaje'])) ? $_POST['mensaje'] : '';

    $xSQL = "SELECT usu.usua_id AS Idusuario, usu.pais_id, CONCAT(usu.usua_nombres,' ',usu.usua_apellidos) AS Nombres, usu.usua_login AS Email, CASE usu.usua_estado WHEN 'A' THEN 'Activo' ";
	$xSQL .= "ELSE 'Inactivo' END AS Estado, usu.usua_caducapass AS CaducaPass, usu.usua_avatarlogin AS LogoUser, (SELECT per.perf_descripcion FROM `expert_perfil` per WHERE per.pais_id=$xPaisid AND per.perf_id=usu.perf_id) AS Perfil FROM `expert_usuarios` usu WHERE usu.pais_id=$xPaisid AND usu.empr_id=$xEmprid AND usu.perf_id>1 ";
	$all_usuarios = mysqli_query($con, $xSQL);

	$xSQL = "SELECT perf_descripcion AS Descripcion, perf_id AS Codigo,perf_observacion AS Observacion FROM `expert_perfil` ";
	$xSQL .= " WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND perf_estado='A' ";
	$xSQL .= " ORDER BY Codigo ";
	//file_put_contents('log_seguimiento.txt', $xSQL . "\n\n", FILE_APPEND);
    $all_perfil = mysqli_query($con, $xSQL);
	foreach($all_perfil as $perfil){ 
		if($perfil['Descripcion'] == 'Administrador' ){
			$xCodigoPerf = $perfil['Codigo'];
		}
	}

    $xSQL = "SELECT COUNT(*) as Contar  FROM `expert_usuarios` usu WHERE usu.pais_id=$xPaisid AND usu.empr_id=$xEmprid AND usu.perf_id>1 ";
	$all_contar = mysqli_query($con, $xSQL);
    foreach($all_contar as $contar){ 
        $xTotalUsuarios = $contar['Contar'];
    }


?>
 	<!--begin::Container-->
<div id="kt_content_container" class="container-xxl">
	<input type="hidden" id="mensaje" value="<?php echo $mensaje ?>">
	<div class="card">
		<div class="card-header border-0 pt-6">
			<div class="card-title">
				<div class="d-flex align-items-center position-relative my-1">
					<span class="svg-icon svg-icon-1 position-absolute ms-6">
					    <i class="fa fa-search fa-1x" style="color:#3B8CEC;" aria-hidden="true"></i> 
					</span>
					<input type="text" data-kt-user-table-filter="search" class="form-control w-250px ps-14" placeholder="Buscar Usuario" />
				</div>
			</div>	
			
            <div class="card-toolbar">
				<button type="button" data-repeater-create="" class="btn btn-primary btn-sm" id="btnNuevo"><i class="fa fa-plus-circle" aria-hidden="true"></i>
						Nuevo Usuario
				</button>
		    </div>			
		</div>
		
		<div class="card-body py-4" >
			<table class="table align-middle table-row-dashed table-hover fs-6 gy-5" id="kt_table_users" style="width: 100%;">
				<thead>
					<tr class="text-start text-gray-800 fw-bolder fs-7 text-uppercase gs-0">
						<th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
					</tr>
				</thead>

				<tbody class="text-gray-600 fw-bold">

					<?php 
							
                        $xCantidad = 0;
						foreach($all_usuarios as $usu){
							$idusuario = $usu['Idusuario'];
							$Estado = trim($usu['Estado']);
							$usuario = trim($usu['Nombres']);
							$login = trim($usu['Email']);
							$avatar = trim($usu['LogoUser']);
							$perfil = trim($usu['Perfil']);
							$xUserpais = $usu['pais_id'];

							if($avatar == ''){
								$avatar = 'user.png';
							}

                            $xCantidad = $xCantidad + 1; 
                           
						?>
							<?php 

								$cheking = '';
								$chkEstado = '';
								$xDisabledEdit = '';
								$xDisabledReset = '';

								if($estado == 'Activo'){
									$cheking = 'checked="checked"';
									$xColor = "text-center text-primary";
								}else{
									$xColor = "text-center text-danger";
									$xDisabledEdit = 'disabled';
									$xDisabledReset = 'disabled';
								}

								$xSQL = "SELECT * FROM `expert_pais` WHERE pais_id=$xUserpais ";
								$all_pais = mysqli_query($con, $xSQL);
								foreach($all_pais as $pais){ 
									$xPaisName = $pais['pais_nombre'];
								}
							?>
                            
                            <?php if($xCantidad == 1 ){ ?>
                            <tr> 
                            <?php } ?>
                                <td style="width: 1%;"></td>
                                <td style="width: 32%;">
                                    
									<div class="card card-flush h-md-100 bg-secondary">
										<div class="card-body d-flex flex-center">
											<div class="symbol symbol-65px symbol-circle mb-5">
												<img src="assets/images/users/<?php echo $avatar; ?>" class="w-150" />
												<div class="bg-success position-absolute border border-4 border-white h-15px w-15px rounded-circle translate-middle start-100 top-100 ms-n3 mt-n3"></div>
											</div>
										</div>
										<div class="card-body d-flex flex-center mt-n5">
											<h2 class="text-primary fw-light fs-2 fst-italic"><?php echo $login; ?></h2>
										</div>
										<div class="card-body mt-n5">
											<div class="form-check">
												<input <?php echo $cheking; ?> class="form-check-input" type="checkbox" value="" id="chk_<?php echo $idusuario; ?>" 
													onchange="f_UpdateEstado(<?php echo $idusuario; ?>,<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>)">
												<label id="lblcolor_<?php echo $idusuario; ?>" class="form-check-label <?php echo $xColor; ?>">
												    <?php echo $Estado; ?>
												</label>	
											</div>
										</div>
										<div class="card-body pt-1">
										    <div class="fw-bold text-gray-800 mb-5"><?php echo $perfil; ?></div>
										</div>
										<div class="card-footer flex-wrap pt-0">
											<div class="row">
												<div class="col">
													<div class="d-grid gap-2">
														<button id="btnedit_<?php echo $idusuario; ?>" <?php echo $xDisabledEdit; ?> type="button" class="btn btn-primary btn-sm" onclick="f_EditarUsuario(<?php echo $idusuario; ?>,'<?php echo $login; ?>')"><i class="las la-pencil-alt me-1" aria-hidden="true"></i>Editar Usuario</button>
													</div>
												</div>
											</div> 
										</div>
									</div>
                                </td>

								<?php if($xCantidad == 3) { $xTotalUsuarios = $xTotalUsuarios - $xCantidad; $xCantidad = 0; ?>
									<td style="width: 1%;"></td>
									</tr>
								<?php } ?>

					<?php 	} ?>  

					<?php
						if($xTotalUsuarios == 1) { ?>
							<td style="width: 1%;"></td>
							<td style="width: 32%;"></td>
							<td style="width: 1%;"></td>
							<td style="width: 32%;"></td>
							<td style="width: 1%;"></td>
							</tr>
					<?php  }elseif($xTotalUsuarios == 2) { ?>
							<td style="width: 1%;"></td>
							<td style="width: 32%;"></td>
							<td style="width: 1%;"></td>
							</tr>
					<?php } ?>

				</tbody>
			</table>
		</div>
	</div>
</div>

<!--Modal Usuario-->
<div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable  mw-900px">
        <div class="modal-content"> 
            <div class="modal-header">
                <h2 id="titulo" class="badge badge-light-primary fw-light fs-2 fst-italic"></h2>
				<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body py-lg-5 px-lg-10 mt-n3">
                <div class="card mb-1 mb-xl-1">
					<div class="d-flex align-items-center collapsible py-3 toggle collapsed mb-0" data-bs-toggle="collapse" data-bs-target="#view_avatar">
						<div class="btn btn-sm btn-icon mw-20px btn-active-color-primary me-5">
							<span class="svg-icon toggle-on svg-icon-primary svg-icon-1">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
									<rect x="6.0104" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
								</svg>
							</span>
							<span class="svg-icon toggle-off svg-icon-1">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
									<rect x="10.8891" y="17.8033" width="12" height="2" rx="1" transform="rotate(-90 10.8891 17.8033)" fill="currentColor" />
									<rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
								</svg>
							</span>
						</div>
						<h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">Avatar</h4>
					</div>
                    <div id="view_avatar" class="collapse fs-6 ms-1">
                        <div class="card card-flush py-2">
                            <div class="card-body pt-0">
								<div class="image-input image-input-outline" data-kt-image-input="true">
									<div class="image-input-wrapper w-125px h-125px" style="background-image: url(assets/images/users/user.png);" id="imgfile"></div>
									<label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Cambiar Avatar">
										<i class="bi bi-pencil-fill fs-7"></i>
										<input type="file" name="avatar" id="imgavatar" accept=".png, .jpg, .jpeg" />
										<input type="hidden" name="avatar_remove" />
									</label>
									<span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancelar Logo">
										<i class="bi bi-x fs-2"></i>
									</span>													
								</div>
								<div class="form-text">Archivos permitidos: png, jpg, jpeg.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-1 mb-xl-1">
					<div class="d-flex align-items-center collapsible py-3 toggle mb-0" data-bs-toggle="collapse" data-bs-target="#view_datos_usuario">
						<div class="btn btn-sm btn-icon mw-20px btn-active-color-primary me-5">
							<span class="svg-icon toggle-on svg-icon-primary svg-icon-1">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
									<rect x="6.0104" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
								</svg>
							</span>
							<span class="svg-icon toggle-off svg-icon-1">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
									<rect x="10.8891" y="17.8033" width="12" height="2" rx="1" transform="rotate(-90 10.8891 17.8033)" fill="currentColor" />
									<rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
								</svg>
							</span>
						</div>
						<h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">Datos Usuario</h4>
					</div>
                    <div id="view_datos_usuario" class="collapse show fs-6 ms-1">
                        <div class="card card-flush py-2">
                            <div class="card-body pt-0">
                                <div class="row mb-4">
                                    <div class="col-md-6">
										<label class="required form-label">Nombres
											<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="especifique el nombre del usuario"></i>
										</label>
										<input type="text" class="form-control" id="txtNombre" name="txtNombre" minlength="5" maxlength="100" placeholder="Ingrese Nombre"  />
                                    </div>
                                    <div class="col-md-6">
									    <label class="required form-label">Apellidos
											<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="especifique el apellido del usuario"></i>
										</label>
										<input type="text" class="form-control" id="txtApellido" name="txtApellido" minlength="5" maxlength="100" placeholder="Ingrese Apellido" />
                                    </div>
                                </div>
								<div class="row">
									<div class="col-md-6">
									    <label class="required form-label">Email
											<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="especifique el nick del usuario"></i>
										</label>
										<input type="email" name="txtLogin" id="txtLogin" class="form-control mb-lg-0 text-lowercase" minlength="10" maxlength="100" placeholder="example@domain.com" />	
									</div>
									<div class="col-md-6">
									    <label class="required form-label">Password
											<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="ingrese clave puede contener numeros y letras"></i>
										</label>
										<input type="password" class="form-control" id="txtPassword" name="subject" minlength="1" maxlength="100" />
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
				<div class="card mb-1 mb-xl-8">
                    <div class="d-flex align-items-center collapsible py-3 toggle mb-0" data-bs-toggle="collapse" data-bs-target="#view_perfiles">
						<div class="btn btn-sm btn-icon mw-20px btn-active-color-primary me-5">
							<span class="svg-icon toggle-on svg-icon-primary svg-icon-1">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
									<rect x="6.0104" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
								</svg>
							</span>
							<span class="svg-icon toggle-off svg-icon-1">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="5" fill="currentColor" />
									<rect x="10.8891" y="17.8033" width="12" height="2" rx="1" transform="rotate(-90 10.8891 17.8033)" fill="currentColor" />
									<rect x="6.01041" y="10.9247" width="12" height="2" rx="1" fill="currentColor" />
								</svg>
							</span>
						</div>
						<h4 class="text-gray-700 fw-bolder cursor-pointer mb-0">Perfiles</h4>
					</div>
					<div id="view_perfiles" class="collapse show">
						<div class="card card-flush py-2">
						    <div class="card-body pt-0">
							    <div class="mb-7" id="divPerfil">
									
									<?php 
										$xcontar = 0;                                                        
										foreach($all_perfil as $perfil){ 
												$xPerfil = $perfil['Descripcion'];
												$xchkCheq = '';
											?>

											<?php
												if($xcontar == 0){
													$xchkCheq = "checked='checked'";
												}
												$xcontar++;
											?>
											<div class="d-flex fv-row">
												<div class="form-check form-check-custom form-check-solid">
													<input class="form-check-input me-3" name="rdbperfil" type="radio" value="<?php echo $perfil['Codigo'] ?>" id="rdboption_<?php echo $perfil['Codigo']; ?>" <?php echo $xchkCheq; ?> />
													<label class="form-check-label" for="kt_modal_update_role_option_<?php echo $perfil['Codigo']; ?>">
														<div class="fw-bolder text-gray-800"><?php echo $perfil['Descripcion']; ?></div>
														<div class="text-gray-600"><?php echo $perfil['Observacion']; ?></div>
													</label>
												</div>
											</div>
											<div class='separator separator-dashed my-5'></div>
											
									<?php } ?>  
						        </div>
							    <div class="row border border-hover-primary py-lg-6 px-lg-20">
									<div class="row mb-4">
										<div class="col-md-6 fv-row text-center">
										    <label class="fs-6 fw-bold mb-2 px-2">Password Caduca</label>
											<input class="form-check-input border border-primary h-20px w-20px" type="checkbox" id="chkCaducaPass" name="chkCaducaPass" value=""  />
											<label class="form-check-label" id="lblCaducaPass">NO</label>
										</div>
										<div class="col-md-6 fv-row text-center">
										    <label class="fs-6 fw-bold mb-2 px-2">Cambiar Password</label> 
											<input class="form-check-input border border-primary h-20px w-20px" type="checkbox" id="chkCamPass" name="chkCamPass" value="" />
											<label class="form-check-label" id="lblCamPass">NO</label>	
										</div>
									</div>
								</div>
								<div class="row col-md-7 mt-3">
									<div class="col-xl-6 fv-row text-center" id="content" style="display: none;">
										<div class="position-relative d-flex align-items-center" >
										    <i class="fa fa-calendar-check fa-2x me-2" style="color:#3B8CEC;" aria-hidden="true"></i>
											<input class="form-control form-control-solid ps-12" id="txtFechacaduca" name="txtFechacaduca" placeholder="Seleccione Fecha.." value="<?php echo date('Y-m-d',strtotime($xFechaActual)); ?> " />
										</div>
									</div>
						        </div>
							</div>	
						</div>
					</div>
				</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal"><i class="fa fa-times me-1" aria-hidden="true"></i>Cerrar</button>
                <button type="button" id="btnSave" class="btn btn-sm btn-light-primary border border-primary"></button> 
            </div>
        </div>
    </div>
</div>

<script>
	$(document).ready(function(){

		var _cambiarPass, _nombre, _apellido, _login, _password, _perfil, _caduca,
				_fechacaduca, _fila, _addmod, _idusu;

		_mensaje = $('input#mensaje').val();

		if(_mensaje != ''){
			toastSweetAlert("top-end",3000,"success",_mensaje); 
		}
		
		$("#txtFechacaduca").flatpickr({
				dateFormat: "Y-m-d"
			});

		Inputmask({
			"mask" : "9999-99-99"
		}).mask("#txtFechacaduca");

		//abrir-modal-nuevo-usuario
		

		// $(document).on("click","#chkCaducaPass",function(){
			
		// 	element = document.getElementById("content");
		// 	if($("#chkCaducaPass").is(":checked")){
		// 		element.style.display='block';
		// 		$("#lblCaducaPass").text("SI");
		// 		_caduca = 'SI';
		// 		var now = new Date();
		// 		var day = ("0" + now.getDate()).slice(-2);
		// 		var month = ("0" + (now.getMonth() + 1)).slice(-2);
		// 		var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
		// 		$('#txtFechacaduca').val(today);
		// 	}else{
		// 		element.style.display='none';
		// 		$("#lblCaducaPass").text("NO");
		// 		_caduca = 'NO';					
		// 	}

		// });

		// $(document).on("click","#chkCamPass",function(){

		// 	if($("#chkCamPass").is(":checked")){
		// 		$("#lblCamPass").text("SI");
		// 		_cambiarPass = 'SI';
		// 	}else{
		// 		$("#lblCamPass").text("NO");
		// 		_cambiarPass = 'NO';
		// 	}

		// });	

		// $("#btnNuevo").click(function(){

		// 	var _codigoperf = "<?php echo $xCodigoPerf; ?>";
		// 	_addmod = 'add';
		// 	_caduca = 'NO';
		// 	_cambiarPass = 'NO';
		// 	_idusu = 0;
		// 	_cboPerfil = 0;
		// 	_avatar = '';

		// 	console.log(_codigoperf);
		// 	$("#titulo").text("Nuevo Usuario");
		// 	document.getElementById("btnSave").innerHTML = '<i class="fa fa-hdd me-1"></i>Grabar';
		// 	document.getElementById('imgfile').style.backgroundImage="url(assets/images/users/user.png)";
		// 	$("#chkCaducaPass").prop("checked", false);
		// 	$("#lblCaducaPass").text("NO");
		// 	$("#chkCamPass").prop("checked", false);
		// 	$("#lblCamPass").text("NO");
		// 	$('#txtPassword').prop('readonly', false);
		// 	$('#content').css('display','none');
		// 	$("#txtNombre").val('');
		// 	$("#txtApellido").val('');
		// 	$("#txtLogin").val('');
		// 	$("#txtPassword").val('');
		// 	$("input").prop('disabled', false);
		// 	$("#rdboption_" + _codigoperf).prop("checked", true);
		// 	$("#kt_modal_add_user").modal("show");	
				
		// });

		// function f_EditarUsuario(userid,userlogin){
			
		// 	$("#titulo").text("Editar Usuario");
		// 	var _emprid = "<?php echo $xEmprid; ?>";
		// 	var _paisid = "<?php echo $xPaisid; ?>";
		// 	_addmod = 'mod';
		// 	_loginant=userlogin;
		// 	_userid=userid;                 

		// 	var _parametros = {
		// 		xxUserid: _userid,
		// 		xxPaisid:_paisid,
		// 		xxEmprid: _emprid,
		// 	}

		// 	$.ajax({
		// 		url: "codephp/editar_usuarios.php",
		// 		type: "POST",
		// 		dataType: "json",
		// 		data: _parametros,          
		// 		success: function(data){ 
		// 			var _nombres = data[0]['Nombres'];
		// 			var _apellidos = data[0]['Apellidos'];
		// 			var _login = data[0]['Login'];
		// 			var _password = data[0]['Password'];

		// 			_cboPerfil = data[0]['CodigoPerfil'];						
		// 			_caduca = data[0]['CaducaPass'];
		// 			_fechaCaduca = data[0]['FechaCaduca'];
		// 			_cambiarPass = data[0]['CambiarPass'];
		// 			_avatar = data[0]['Avatar'] == '' ? 'user.png' : data[0]['Avatar'];

		// 			var _rdboption = 'rdboption_' + _cboPerfil;
		// 			$('#'+_rdboption).prop('checked','checked');
		// 			$("input").prop('disabled', false);	

		// 			$("#txtNombre").val(_nombres);
		// 			$("#txtApellido").val(_apellidos);
		// 			$("#txtLogin").val(_login);
		// 			$("#txtPassword").val(_password);
		// 			$("#cboPerfil").val(_cboPerfil).change();
		// 			$("#txtFechacaduca").val(_fechaCaduca);
		// 			document.getElementById('imgfile').style.backgroundImage="url(assets/images/users/" + _avatar + ")";
					
		// 			if(_login == 'admin@prestasalud.com' && _nombres == 'Administrador'){
		// 				$("input").prop('disabled', true);
		// 			}

		// 			if(_caduca == 'SI'){
		// 				$("#chkCaducaPass").prop("checked", true);
		// 				$("#lblCaducaPass").text("SI");  
		// 				$('#content').css('display','block');       
		// 			}else if(_caduca == 'NO'){
		// 				$("#chkCaducaPass").prop("checked", false);
		// 				$("#lblCaducaPass").text("NO");  
		// 				$('#content').css('display','none');   

		// 			}

		// 			if(_cambiarPass == 'SI'){
		// 				$("#chkCamPass").prop("checked", true);
		// 				$("#lblCamPass").text('SI');
		// 			}else if(_cambiarPass == 'NO'){
		// 				$("#chkCamPass").prop("checked", false);
		// 				$("#lblCamPass").text('NO');	
		// 			}
																							
		// 		},
		// 		error: function (error){
		// 			console.log(error);
		// 		}                            
		// 	}); 
		// 	document.getElementById("btnSave").innerHTML = '<i class="las la-pencil-alt"></i>Modificar';
		// 	$("#kt_modal_add_user").modal("show");			
		// }

	});


	//Modal Nuevo Usuario
	$("#btnNuevo").click(function(){

		var _codigoperf = "<?php echo $xCodigoPerf; ?>";
		_addmod = 'add';
		_caduca = 'NO';
		_cambiarPass = 'NO';
		_idusu = 0;
		_cboPerfil = 0;
		_avatar = '';
		_userid = 0;
		_loginant = '';


		$("#titulo").text("Nuevo Usuario");
		document.getElementById("btnSave").innerHTML = '<i class="fa fa-hdd me-1"></i>Grabar';
		document.getElementById('imgfile').style.backgroundImage="url(assets/images/users/user.png)";
		$("#chkCaducaPass").prop("checked", false);
		$("#lblCaducaPass").text("NO");
		$("#chkCamPass").prop("checked", false);
		$("#lblCamPass").text("NO");
		$('#txtPassword').prop('readonly', false);
		$('#content').css('display','none');
		$("#txtNombre").val('');
		$("#txtApellido").val('');
		$("#txtLogin").val('');
		$("#txtPassword").val('');
		$("input").prop('disabled', false);
		$("#rdboption_" + _codigoperf).prop("checked", true);
		$("#kt_modal_add_user").modal("show");	
			
	});

    //Modal Editar Usuario
	function f_EditarUsuario(userid,userlogin){
		
		$("#titulo").text("Editar Usuario");
		var _emprid = "<?php echo $xEmprid; ?>";
		var _paisid = "<?php echo $xPaisid; ?>";
		_addmod = 'mod';
		_loginant=userlogin;
		_userid=userid; 
		
		//console.log(_addmod);

		var _parametros = {
			xxUserid: _userid,
			xxPaisid:_paisid,
			xxEmprid: _emprid,
		}

		$.ajax({
			url: "codephp/editar_usuarios.php",
			type: "POST",
			dataType: "json",
			data: _parametros,          
			success: function(data){ 
				var _nombres = data[0]['Nombres'];
				var _apellidos = data[0]['Apellidos'];
				var _login = data[0]['Login'];
				var _password = data[0]['Password'];

				_cboPerfil = data[0]['CodigoPerfil'];						
				_caduca = data[0]['CaducaPass'];
				_fechaCaduca = data[0]['FechaCaduca'];
				_cambiarPass = data[0]['CambiarPass'];
				_avatar = data[0]['Avatar'] == '' ? 'user.png' : data[0]['Avatar'];

				var _rdboption = 'rdboption_' + _cboPerfil;
				$('#'+_rdboption).prop('checked','checked');
				$("input").prop('disabled', false);	

				$("#txtNombre").val(_nombres);
				$("#txtApellido").val(_apellidos);
				$("#txtLogin").val(_login);
				$("#txtPassword").val(_password);
				$("#cboPerfil").val(_cboPerfil).change();
				$("#txtFechacaduca").val(_fechaCaduca);
				document.getElementById('imgfile').style.backgroundImage="url(assets/images/users/" + _avatar + ")";
				
				if(_login == 'admin@prestasalud.com' && _nombres == 'Administrador'){
					//$("input").prop('disabled', true);
				}

				if(_caduca == 'SI'){
					$("#chkCaducaPass").prop("checked", true);
					$("#lblCaducaPass").text("SI");  
					$('#content').css('display','block');       
				}else if(_caduca == 'NO'){
					$("#chkCaducaPass").prop("checked", false);
					$("#lblCaducaPass").text("NO");  
					$('#content').css('display','none');   

				}

				if(_cambiarPass == 'SI'){
					$("#chkCamPass").prop("checked", true);
					$("#lblCamPass").text('SI');
				}else if(_cambiarPass == 'NO'){
					$("#chkCamPass").prop("checked", false);
					$("#lblCamPass").text('NO');	
				}
																						
			},
			error: function (error){
				console.log(error);
			}                            
		}); 
		document.getElementById("btnSave").innerHTML = '<i class="las la-pencil-alt"></i>Modificar';
		$("#kt_modal_add_user").modal("show");			
	}

	//Check caduca Password
	$(document).on("click","#chkCaducaPass",function(){
			
		element = document.getElementById("content");
		if($("#chkCaducaPass").is(":checked")){
			element.style.display='block';
			$("#lblCaducaPass").text("SI");
			_caduca = 'SI';
			var now = new Date();
			var day = ("0" + now.getDate()).slice(-2);
			var month = ("0" + (now.getMonth() + 1)).slice(-2);
			var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
			$('#txtFechacaduca').val(today);
		}else{
			element.style.display='none';
			$("#lblCaducaPass").text("NO");
			_caduca = 'NO';					
		}

	});

	//Check Cambiar Password
	$(document).on("click","#chkCamPass",function(){

		if($("#chkCamPass").is(":checked")){
			$("#lblCamPass").text("SI");
			_cambiarPass = 'SI';
		}else{
			$("#lblCamPass").text("NO");
			_cambiarPass = 'NO';
		}

	});	

    	
	//Guardar usuario y editar
	$('#btnSave').click(function(e){
		
		var _paisid = "<?php echo $xPaisid; ?>";
		var _emprid = "<?php echo $xEmprid; ?>";
		var _usuaid = "<?php echo $xUsuaid; ?>";
		var _nombre = $.trim($("#txtNombre").val());
		var _apellido = $.trim($("#txtApellido").val());
		var _login = $.trim($("#txtLogin").val());
		var _password = $.trim($("#txtPassword").val());
		var _perfilid = $("input[type='radio'][name='rdbperfil']:checked").val();
		var _selecc = 'NO';
		var _buscar = 'NO';

		var _imgfile = document.getElementById("imgfile").style.backgroundImage;
		var _url = _imgfile.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
		var _pos = _url.trim().indexOf('.');
		var _ext = _url.trim().substr(_pos, 5);

		if(_ext.trim() != '.png' && _ext.trim() != '.jpg' && _ext.trim() != '.jpeg'){
			_selecc = 'SI';
		}                    

		if(_selecc == 'SI'){
			var _imagen = document.getElementById("imgavatar");
			var _file = _imagen.files[0];
			var _fullPath = document.getElementById('imgavatar').value;
			_ext = _fullPath.substring(_fullPath.length - 4);
			_ext = _ext.toLowerCase();   
		}

		if(_ext.trim() != '.png' && _ext.trim() != '.jpg' && _ext.trim() != 'jpeg'){
			toastSweetAlert("top-end",3000,"error","El archivo seleccionado no es una Imagen..!");
			return;
		}

		_fechacaduca = $.trim($("#txtFechacaduca").val());
		_buscar = 'SI';
		//_continuar = 'SI';
		_respuesta = 'OK';

		if(_nombre == ''){                        
			toastSweetAlert("top-end",3000,"warning","Ingrese Nombre..!!");
			return;
		}

		if(_apellido == ''){                        
			toastSweetAlert("top-end",3000,"warning","Ingrese Apellido..!!");
			return;
		}

		if(_login == ''){                        
			toastSweetAlert("top-end",3000,"warning","Ingrese Email..!!");
			return;
		}
		
		var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
		if(regex.test(_login.trim())){
		}else{
			toastSweetAlert("top-end",3000,"error","Email incorrecto..!!");
			return;
		}                    

		if(_password == ''){                        
			toastSweetAlert("top-end",3000,"warning","Ingrese Password..!!");
			return;
		}

		if(_paisid == '0'){                        
			toastSweetAlert("top-end",3000,"warning","Seleccione Pais..!!");
			return;
		}
		
		if(_perfilid == '0'){                        
			toastSweetAlert("top-end",3000,"warning","Seleccione Perfil..!!");
			return;
		}
		//debugger;
		
		if(_addmod == 'mod'){
			if(_loginant.toLowerCase() != _login.toLowerCase()){
				_buscar = 'SI';
			}else{
				_buscar = 'NO';
			}                        
			_ulr = "codephp/actualizar_usuario.php";
		}else{
			_ulr = "codephp/grabar_usuarios.php";    
		}
        
		form_data = new FormData();            
		form_data.append('xxPaisid', _paisid);
		form_data.append('xxUsuaid', _usuaid);
		form_data.append('xxEmprid', _emprid);
		form_data.append('xxUserid', _userid);
		form_data.append('xxNombre', _nombre);
		form_data.append('xxApellido', _apellido);
		form_data.append('xxLogin', _login);
		form_data.append('xxPassword', _password);
		form_data.append('xxPerfilid', _perfilid);
		form_data.append('xxCaducaPass', _caduca);
		form_data.append('xxFecha', _fechacaduca);
		form_data.append('xxCambiarPass', _cambiarPass);
		form_data.append('xxCambiarAvatar', _selecc);
		form_data.append('xxFile', _file);               
		
		if(_buscar == 'SI'){
			var xrespuesta = $.post("codephp/consultar_usuarios.php", {xxPaisid: _paisid,xxEmprid:_emprid,xxLogin: _login});
			xrespuesta.done(function(response){
				
				if(response == 0){

					$.ajax({
						url: _ulr,
						type: "post",
						data: form_data,
						processData: false,
						contentType: false,
						dataType: "json",
						success: function(response){

							var _userid = response;	
							var _usuario = _nombre + ' ' + _apellido;

							if(_userid != 0){

								if(_addmod == 'add'){
									_detalle = 'Nuevo usuario creado';
									_mensaje = 'Grabado con Exito';
								}
								else{
									_detalle = 'Actualizar usuario';
									_mensaje = 'Actualizado con Exito';
								} 
							}else{
								_detalle = 'Error encontrado en sentecia SQL';
								_respuesta = 'ERR';                                
							}

							/**PARA CREAR REGISTRO DE LOGS */
							$parametros = {
								xxPaisid: _paisid,
								xxEmprid: _emprid,
								xxUsuaid: _usuaid,
								xxDetalle: _detalle
							}					

							$.post("codephp/new_log.php", $parametros, function(response){
							});                                         

							if(_respuesta == 'OK'){
								// if(_addmod == 'add'){
								// }else{
								//     $("#kt_modal_add_user").modal("hide");
								// }
								$.redirect('?page=seg_usuarioadmin&menuid=<?php echo $menuid; ?>', { 'mensaje': _mensaje } ); //POR METODO POST
							}                                        
						},								
						error: function (error){
							console.log(error);
						}
					});
				}else{
					toastSweetAlert("top-end",3000,"warning","Email ya Existe..!!"); 
					return;
				}
			});
		}else{

			$.ajax({
				url: _ulr,
				type: "post",                
				data: form_data,
				processData: false,
				contentType: false,
				dataType: "json",
				success: function(response){   

					var _userid = response;	
					var _usuario = _nombre + ' ' + _apellido;

					if(_userid != 0){

						if(_addmod == 'add'){
							_detalle = 'Nuevo usuario creado';
							_mensaje = 'Agregado con Exito';
						}else{
							_detalle = 'Actualizar usuario';
							_mensaje = 'Actualizado con Exito';
						} 
					}else{
						_detalle = 'Error encontrado en sentecia SQL';
						_respuesta = 'ERR';                                
					}

					// /**PARA CREAR REGISTRO DE LOGS */
					var _parametros = {
						"xxPaisid" : _paisid,
						"xxEmprid" : _emprid,
						"xxUsuaid" : _usuaid,
						"xxDetalle" : _detalle,
					}					

					$.post("codephp/new_log.php", _parametros, function(response){
					});                                         

					if(_respuesta == 'OK'){
						$.redirect('?page=seg_usuarioadmin&menuid=<?php echo $menuid; ?>', {'mensaje': _mensaje}); //POR METODO POST
					}                                        
				},
				error: function (error) {
					console.log(error);
				}
			}); 
		}
	});	

	//cambiar estado y desactivar botones en linea

	$(document).on("click",".btnEstado",function(e){
		_fila = $(this).closest("tr");
		_usuario = $(this).closest("tr").find('td:eq(1)').text();
		_login = $(this).closest("tr").find('td:eq(2)').text();
		_perfilname = $(this).closest("tr").find('td:eq(5)').text();
	});

	//cambiar estado y desactivar botones en linea

	
	function f_UpdateEstado(_userid,_paisid,_emprid, ){
		var _check = $("#chk_" + _userid).is(":checked");
        var _estado = '';
        var _class = '';
        var _lbl = "lblcolor_"+_userid;
        var _btnedit = "btnedit_"+_userid;
	
		if(_check){
			_estado = "A";
			_lblestado = "Activo";
			_class = 'form-check-label text-center text-primary';
			$('#'+_btnedit).prop("disabled",false);                    
		}else{                    
			_estado = "I";
			_lblestado = "Inactivo";
			_disabled = "disabled";
			_class = "form-check-label text-center text-danger";
			$('#'+_btnedit).prop("disabled",true);
		}

		var _lblChanged = document.getElementById(_lbl);
        _lblChanged.innerHTML = '<label class="' + _class + '">' + _lblestado + '</label>';

		var _parametros = {
			"xxUsuaid" : _userid,
			"xxPaisid" : _paisid,
			"xxEmprid" : _emprid,
			"xxEstado" : _estado
		}

		var xrespuesta = $.post("codephp/update_estadousuario.php", _parametros);
		xrespuesta.done(function(response){
		});	
								
	}

	//desplazar ventana modal
	$("#kt_modal_add_user").draggable({
		handle: ".modal-header"
	}); 

	//resetaer password
	function f_ResetPass(_usuaid, _emprid){

		var _parametros = {
			"xxUsuaid" : _usuaid,
			"xxEmprid" : _emprid
		}

		$.post("codephp/reset_password.php", _parametros, function(response){
			if(response.trim() == 'OK'){
				toastSweetAlert("top-end",3000,"success","Password Actualizado");
			}     
		}); 			
	}


</script> 	