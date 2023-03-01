<?php
	//error_reporting(E_ALL);
	ini_set('display_errors', 0);

	putenv("TZ=America/Guayaquil");
	date_default_timezone_set('America/Guayaquil');	

	require_once("./dbcon/config.php");

	$log_file = "error_conexion";

	session_start();
	$_SESSION["s_usuario"] = null;
	$_SESSION["i_usuaid"] = null;
	$_SESSION["i_paisid"] = null;
	$_SESSION["i_perfilid"] = null;
	$_SESSION["i_emprid"] = null;
	$_SESSION["s_perfdesc"] = null;
	$_SESSION["s_login"] = null;
	$_SESSION["s_namehost"] = gethostname();

	$respuesta = '';

	if(isset($_POST['usuario']) and isset($_POST['password'])){
		if(isset($_POST['usuario']) <> '' and isset($_POST['password']) <> ''){
              
			$xUsuario = $_POST['usuario'];
			$xPassword = $_POST['password'];
			$xnewPassword = md5($xPassword);


			$xSQL = " SELECT usu.usua_id AS UsuarioId, usu.pais_id AS PaisId, usu.empr_id AS EmprID, usu.usua_login AS NombreLogin, ";
			$xSQL .= " CONCAT(usu.usua_nombres,' ',usu.usua_apellidos) AS NombreUsuario, per.perf_id AS PerfilId, per.perf_descripcion AS PerfilName FROM `expert_usuarios` usu ";
			$xSQL .= " INNER JOIN `expert_perfil` per ON usu.perf_id=per.perf_id WHERE usu.usua_login='$xUsuario'and usua_password = '$xnewPassword' ";
		
			$login = mysqli_query($con, $xSQL);
			$rowcount=mysqli_num_rows($login);
		
			if($rowcount > 0){
			   
				$row= mysqli_fetch_row($login);
		
				$_SESSION["i_usuaid"] = $row[0];
				$_SESSION["i_paisid"] = $row[1];
				$_SESSION["i_emprid"] = $row[2];
				$_SESSION["s_login"] = $row[3];
				$_SESSION["s_usuario"] = $row[4];
				$_SESSION["i_perfilid"] = $row[5];
				$_SESSION["s_perfdesc"] = $row[6];

				$respuesta  = 'ok';

		
			}else{
		
				$_SESSION["s_usuario"] = null;
				$_SESSION["i_usuaid"] = null;
				$_SESSION["i_paisid"] = null;
				$_SESSION["i_perfilid"] = null;
				$_SESSION["i_emprid"] = null;
				$_SESSION["s_perfdesc"] = null;
				$_SESSION["s_login"] = null;

				$respuesta  = 'error';
		
			}
		}
		echo $respuesta;
		exit();
	}



?>

<!DOCTYPE html>


<html lang="en">
	<head>
		<title>Metronic - the world's #1 selling Bootstrap Admin Theme Ecosystem for HTML, Vue, React, Angular &amp; Laravel by Keenthemes</title>
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
		<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
	</head>
	<body id="kt_body" class="bg-dark">
		<div class="d-flex flex-column flex-root">
			<div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed" style="background-image: url(assets/media/illustrations/sketchy-1/14-dark.png)">
				<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
					<a href="../../demo1/dist/index.html" class="mb-12">
						<img alt="Logo" src="assets/media/logos/LogoPresta.png" class="h-40px" />
					</a>
					<div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
						<form class="form w-100" method="post" novalidate="novalidate" id="kt_sign_in_form" data-kt-redirect-url="" action="">
							<div class="text-center mb-10">
								<h1 class="text-dark mb-3">Inicio de Sesion Expert</h1>
								<div class="text-gray-400 fw-bold fs-4">Nuevo Usuario?
								<a href="registro.php" class="link-primary fw-bolder">Crear Cuenta</a></div>
							</div>
							<div class="fv-row mb-10">
								<label class="form-label fs-6 fw-bolder text-dark">Email</label>
								<input class="form-control form-control-lg form-control-solid" type="text" id="email" name="email" autocomplete="off" />
							</div>
							<div class="fv-row mb-10">
								<div class="d-flex flex-stack mb-2">
									<label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
									<a href="recuperarpass.php" class="link-primary fs-6 fw-bolder">Olvido el Password ?</a>
								</div>
								<input class="form-control form-control-lg form-control-solid" type="password" id="password" name="password" autocomplete="off" />
							</div>
							<div class="text-center">
								<button type="submit" id="kt_sign_in_submit" name="login" class="btn btn-lg btn-primary w-100 mb-5">
									<span class="indicator-label">Login</span>
								</button>
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
		<script src="assets/js/custom/authentication/sign-in/general.js"></script>
		<script src="assets/redirect/js/redirect.js"></script>
		<script src="assets/js/funciones.js"></script>
		
	</body>
</html>
<script>
	
	$('#kt_sign_in_form').submit(function(e){
		e.preventDefault();


		var _usuario = $.trim($("#email").val()); 
		var _password = $.trim($("#password").val()); 

		if(_usuario == ''){
			mensajesweetalert("center","warning","Ingrese usuario..!",false,1800);
			return false; 
		}

		if(_password == ''){
			mensajesweetalert("center","warning","Ingrese pasword..!",false,1800);
			return false; 
		}

		   $.post("ingreso.php", {usuario:_usuario, password:_password} , function(response){

			   if(response == 'error'){
                    
					mensajesweetalert("center","warning","Usuario y/o Password incorrecto!",false,1800);
             
                    $("#email").val('');
                    $("#password").val(''); 
					
					

                }else{
                    window.location.href = "index.php";              
                }			

		

			});
		});
</script>

