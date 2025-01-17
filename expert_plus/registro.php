<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	

	require_once("./dbcon/config.php");

	mysqli_query($con,'SET NAMES utf8');  
	mysqli_set_charset($con,'utf8');
	
	$xFecha = strftime("%Y-%m-%d %H:%M:%S", time());
    $xTerminal = gethostname();
    $yEmprid = 1;

	$log_file = "error_conexion";
	$mode = 'dark';
	
	$xSQL = "SELECT * FROM `expert_parametro_paginas` WHERE empr_id=0 AND usua_id=0 AND estado='A'";
	$all_datos = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));	

	if(mysqli_num_rows($all_datos)>0) {
		foreach ($all_datos as $datos){ 
			$mode = $datos['index_content'];
		}
	}	

	$xSQL = "SELECT pais_id AS IdPais, pais_nombre AS Pais, pais_flag AS Bandera FROM `expert_pais` ";
	$xSQL .= " ORDER BY pais_id ";
    $resultado = mysqli_query($con, $xSQL);

	$respuesta = 'ERR';

	if(isset($_POST['pais']) and isset($_POST['nombre']) and isset($_POST['apellido']) and isset($_POST['email']) and isset($_POST['password'])){
		if(isset($_POST['pais']) <> '' and isset($_POST['nombre']) <> '' and isset($_POST['apellido']) <> '' and isset($_POST['email']) <> '' and isset($_POST['password']) <> ''){

          $yPais = $_POST['pais'];
		  $xNombre = $_POST['nombre'];
		  $xApellido = $_POST['apellido'];
		  $xEmail = $_POST['email'];
		  $xPass = $_POST['password'];
		  $xNewPass = md5($xPass);

		  $xSQL = "SELECT * FROM `expert_usuarios` WHERE usua_login = '$xEmail '";
		  $login = mysqli_query($con, $xSQL);
		  $rowcount=mysqli_num_rows($login);

		  if($rowcount == 0){

			$xSQL =  "INSERT INTO `expert_usuarios`(perf_id,pais_id,empr_id,usua_nombres,usua_apellidos,usua_login,usua_password, ";
			$xSQL .= "usua_estado,usua_fechacreacion,usua_terminalcreacion )";
			$xSQL .= "VALUES (-1,$yPais,$yEmprid,'$xNombre','$xApellido','$xEmail','$xNewPass','A','{$xFecha}','$xTerminal')";
			$registro = mysqli_query($con, $xSQL);

			$respuesta = 'OK';

		  }

		}

		echo $respuesta;
		exit();
	}


?>
<!DOCTYPE html>

<html lang="en">
	<head>
		<title>Registro-Explert-PLus</title>
		<meta charset="utf-8" />
		<meta name="description" content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 94,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue &amp; Laravel versions. Grab your copy now and get life-time updates for free." />
		<meta name="keywords" content="Metronic, bootstrap, bootstrap 5, Angular, VueJs, React, Laravel, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="Metronic - Bootstrap 5 HTML, VueJS, React, Angular &amp; Laravel Admin Dashboard Theme" />
		<meta property="og:url" content="https://keenthemes.com/metronic" />
		<meta property="og:site_name" content="Keenthemes | Metronic" />
		<link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
		<link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />


		<!--<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" /> -->
		
		<?php
			if($mode == 'dark'){
		?>
			<link href="assets/plugins/global/plugins.dark.bundle.css" rel="stylesheet" type="text/css" />
			<link href="assets/css/style.dark.bundle.css" rel="stylesheet" type="text/css" />		
		<?php	
			}else{
		?>
			<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
			<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<?php
			}
		?>	
		

	</head>
	<body id="kt_body" class="<?php if($mode == 'dark'){ echo ''; }else{ echo 'bg-dark'; } ?>">
		<div class="d-flex flex-column flex-root">
			<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-image: url(assets/media/illustrations/sketchy-1/14-dark.png)">
				<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
					<a href="../../demo1/dist/index.html" class="mb-12">
						<img alt="Logo" src="assets/media/logos/LogoPresta.png" class="h-100px w-350px" />
					</a>
					<div class="w-lg-600px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
						<form class="form w-100" method="post" novalidate="novalidate" id="kt_sign_up_form">
							<div class="mb-10 text-center">
								<h1 class="text-dark mb-3">Crear Usuario Expert</h1>
							</div>
							<div class="d-flex align-items-center mb-10">
								<div class="border-bottom border-gray-300 mw-50 w-100"></div>
								<span class="fw-bold text-gray-400 fs-7 mx-2"></span>
								<div class="border-bottom border-gray-300 mw-50 w-100"></div>
							</div>
                            <div class="row fv-row mb-7">
                                <div class="col-xl-12">
                                    <label class="form-label fw-bolder text-dark fs-6">Pais</label>
                                    <select id="cboPais" name="cboPais" placeholder="Seleccione Pais" class="form-select form-select-transparent">
										<option value="0">--Seleccione Pais--</option>
										<?php foreach ($resultado as $pais) : 
											
											$flag = ' data-kt-select2-country=' . '"assets/media/flags/' . $pais['Bandera'] . '"';
										?>
											<option value="<?php echo $pais['IdPais']; ?>"<?php echo $flag; ?>><?php echo $pais['Pais']; ?></option>
										<?php endforeach ?>						
                                    </select>
                                </div>
                            </div>
							<div class="row fv-row mb-7">
								<div class="col-xl-6">
									<label class="form-label fw-bolder text-dark fs-6">Nombres</label>
									<input class="form-control form-control-lg form-control-solid" id="txtNombre" type="text" placeholder="ingrese nombre" name="first-name" autocomplete="off" minlength="5" maxlength="50"/>
								</div>
								<div class="col-xl-6">
									<label class="form-label fw-bolder text-dark fs-6">Apellidos</label>
									<input class="form-control form-control-lg form-control-solid"  id="txtApellido" type="text" placeholder="ingrese apellido" name="last-name" autocomplete="off" minlength="5" maxlength="50" />
								</div>
							</div>
							<div class="fv-row mb-7">
								<label class="form-label fw-bolder text-dark fs-6">Email</label>
								<input class="form-control form-control-lg form-control-solid" type="email" placeholder="example@gmail.con" id="email" name="email" autocomplete="off" maxlength="80" />
							</div>
							<div class="mb-10 fv-row" data-kt-password-meter="true">
								<div class="mb-1">
									<label class="form-label fw-bolder text-dark fs-6">Password</label>
									<div class="position-relative mb-3">
										<input class="form-control form-control-lg form-control-solid" type="password" id="password" name="password" autocomplete="off"  maxlength="20">
										<span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" data-kt-password-meter-control="visibility">
											<i class="bi bi-eye-slash fs-2"></i>
											<i class="bi bi-eye fs-2 d-none"></i>
										</span>
									</div>
									<div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
										<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
										<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
										<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
										<div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
									</div>
								</div>
								<div class="text-muted">Use 8 o mas caracteres con letras, numeros &amp; simbolos.</div>
							</div>
							<div class="fv-row mb-5">
								<label class="form-label fw-bolder text-dark fs-6">Confirmar Password</label>
								<input class="form-control form-control-lg form-control-solid" type="password" id="confpass" name="confirm-password" autocomplete="off"  maxlength="20"/>
							</div>
                            <br/>
                            <br/>
							<div class="d-flex flex-wrap justify-content-center pb-lg-0">
								<button type="submit" id="kt_sign_up_submit" class="btn btn-lg btn-primary fw-bolder me-4">
									<span class="indicator-label">Enviar</span>
								</button>
                                <a href="ingreso.php" class="btn btn-lg btn-light-primary fw-bolder">Cancelar</a>
							</div>
						</form>
					</div>
				</div>
				<div class="d-flex flex-center flex-column-auto p-10">
					<div class="d-flex align-items-center fw-bold fs-6">
						<a href="https://keenthemes.com" class="text-muted text-hover-primary px-2">About</a>
						<a href="mailto:support@keenthemes.com" class="text-muted text-hover-primary px-2">Contact</a>
						<a href="https://1.envato.market/EA4JP" class="text-muted text-hover-primary px-2">Contact Us</a>
					</div>
				</div>
			</div>
		</div>
		<script>var hostUrl = "assets/";</script>
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<script src="assets/js/scripts.bundle.js"></script>
		<script src="assets/js/custom/authentication/sign-up/general.js"></script>
		<script src="assets/js/funciones.js"></script>
	</body>
</html>
<script>

	
	var optionFormat = function(item) {
			if ( !item.id ) {
				return item.text;
			}

			var span = document.createElement('span');
			var imgUrl = item.element.getAttribute('data-kt-select2-country');
			var template = '';

			if(item.id != 0){

				template += '<img src="' + imgUrl + '" class="rounded-circle h-20px me-2" alt="image"/>';
			}

			template += item.text;

			span.innerHTML = template;			

			return $(span);
	}	
	
	$('#cboPais').select2({
		templateSelection: optionFormat,
		templateResult: optionFormat
	});


   $('#kt_sign_up_form').submit(function(e){
	    e.preventDefault();

		let _cboPais = $('#cboPais').val();
		let _nombre = $.trim($("#txtNombre").val()); 
		let _apellido = $.trim($("#txtApellido").val());
		let _email = $.trim($("#email").val());
		let _password = $.trim($("#password").val());
		let _confpass = $.trim($("#confpass").val());
		let tampass = _password.length;
	    let tamconfpass = _confpass.length;

	    if(_cboPais == 0){
			mensajesweetalert("center","warning","Seleccione Pais..!",false,1800);
			return false; 
		}
	  
	    if(_nombre == ''){
			mensajesweetalert("center","warning","Ingrese Nombre..!",false,1800);
			return false; 
		}

		if(_apellido == ''){
			mensajesweetalert("center","warning","Ingrese Apellido..!",false,1800);
			return false; 
		}

		if(_email == ''){
			mensajesweetalert("center","warning","Ingrese un Email..!",false,1800);
			return false; 
		}

		if(_password == ''){
			mensajesweetalert("center","warning","Ingrese password..!",false,1800);
			return false; 
		}

		if(_confpass == ''){
			mensajesweetalert("center","warning","Confirme password..!",false,1800);
			return false; 
		}
		

		if(tampass != tamconfpass ){
			mensajesweetalert("center","warning","No coincide el numero de caracteres del password..!",false,1800);
			return false; 
		}
		
			if(_password != _confpass){
			mensajesweetalert("center","warning","No coincide el password ingresado..!",false,1800);
			return false; 
		}
	
		$.post("registro.php", {

			pais:_cboPais, 
			nombre:_nombre,
			apellido:_apellido, 
			email:_email,
			password:_password

		}, function(response){

			if(response == 'OK'){
				
				mensajesweetalert("center","success","Usuario Registrado..!!",false,2000);   
				window.location.href = "ingreso.php";     

			}else{
				mensajesweetalert("center","error","Email se encuentra ya registrado..!!",false,2000);           
			}			

		});

	});
</script>