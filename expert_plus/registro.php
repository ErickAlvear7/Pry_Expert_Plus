<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	

	$mode = 'dark';
	require_once("./dbcon/config.php");

	$log_file = "error_conexion";


?>
<!DOCTYPE html>

<html lang="en">
	<!--begin::Head-->
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
					<div class="w-lg-600px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
						<form class="form w-100" novalidate="novalidate" id="kt_sign_up_form">
							<div class="mb-10 text-center">
								<h1 class="text-dark mb-3">Crear Usuario Expert</h1>
							</div>
							<div class="d-flex align-items-center mb-10">
								<div class="border-bottom border-gray-300 mw-50 w-100"></div>
								<span class="fw-bold text-gray-400 fs-7 mx-2"></span>
								<div class="border-bottom border-gray-300 mw-50 w-100"></div>
							</div>
                            <div class="row fv-row mb-7">
                                <div class="col-xl-6">
                                    <label class="form-label fw-bolder text-dark fs-6">Pais</label>
                                    <select id="cboPais" name="cboPais" class="form-select" aria-label="Default select example">
                                        <option selected>Open this select menu</option>
                                        <option value="1">Ecuador</option>
                                        <option value="2">Colombia</option>
                                    </select>
								</select>
                                </div>
                            </div>
							<div class="row fv-row mb-7">
								<div class="col-xl-6">
									<label class="form-label fw-bolder text-dark fs-6">Nombres</label>
									<input class="form-control form-control-lg form-control-solid" id="txtNombre" type="text" placeholder="" name="first-name" autocomplete="off" minlength="5" maxlength="50"/>
								</div>
								<div class="col-xl-6">
									<label class="form-label fw-bolder text-dark fs-6">Apellidos</label>
									<input class="form-control form-control-lg form-control-solid"  id="txtApellido" type="text" placeholder="" name="last-name" autocomplete="off" minlength="5" maxlength="50" />
								</div>
							</div>
							<div class="fv-row mb-7">
								<label class="form-label fw-bolder text-dark fs-6">Email</label>
								<input class="form-control form-control-lg form-control-solid" type="email" placeholder="" name="email" autocomplete="off" maxlength="50" />
							</div>
							<div class="mb-10 fv-row" data-kt-password-meter="true">
								<div class="mb-1">
									<label class="form-label fw-bolder text-dark fs-6">Password</label>
									<div class="position-relative mb-3">
										<input class="form-control form-control-lg form-control-solid" type="password" placeholder="" name="password" autocomplete="off"  maxlength="20">
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
								<input class="form-control form-control-lg form-control-solid" type="password" placeholder="" name="confirm-password" autocomplete="off"  maxlength="20"/>
							</div>
                            <br/>
                            <br/>
							<div class="d-flex flex-wrap justify-content-center pb-lg-0">
								<button type="button" id="kt_sign_up_submit" class="btn btn-lg btn-primary fw-bolder me-4">
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
	</body>
</html>