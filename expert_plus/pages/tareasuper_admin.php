
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

	$xServidor = $_SERVER['HTTP_HOST'];
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());

	session_start();

	//$xServidor = $_SERVER['HTTP_HOST'];
	$xFecha = strftime("%Y-%m-%d %H:%M:%S", time());

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

	$yUsuaid = $_SESSION["i_usuaid"];
    $yPaisid = $_SESSION["i_paisid"];
    $yEmprid = $_SESSION["i_emprid"];


	$xSQL = "SELECT tare_id AS Id, tare_nombre AS Tarea, tare_ruta AS Accion, CASE tare_estado WHEN 'A' THEN 'Activo' ";
	$xSQL .= "ELSE 'Inactivo' END AS Estado FROM `expert_tarea` WHERE empr_id=$yEmprid ORDER BY tare_orden";
	$all_tareas = mysqli_query($con, $xSQL);
?>	
                <!--begin::Container-->
				<div id="kt_content_container" class="container-xxl">
					<div class="card mb-5 mb-xxl-8">
						<div class="card-body pt-9 pb-0">
							<div class="d-flex flex-wrap flex-sm-nowrap">
								<!-- <div class="me-7 mb-4">
									<div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
										<img src="assets/media/avatars/SuperAdmin.png" alt="image" />
										<div class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-white h-20px w-20px"></div>
									</div>
								</div> -->
								<div class="flex-grow-1">
									<div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
										<div class="d-flex flex-column">
											<!-- <div class="d-flex align-items-center mb-2">
												<a href="#" class="text-gray-900 text-hover-primary fs-2 fw-bolder me-1">Super Administrador</a>
												<a href="#">
													<span class="svg-icon svg-icon-1 svg-icon-primary">
														<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24">
															<path d="M10.0813 3.7242C10.8849 2.16438 13.1151 2.16438 13.9187 3.7242V3.7242C14.4016 4.66147 15.4909 5.1127 16.4951 4.79139V4.79139C18.1663 4.25668 19.7433 5.83365 19.2086 7.50485V7.50485C18.8873 8.50905 19.3385 9.59842 20.2758 10.0813V10.0813C21.8356 10.8849 21.8356 13.1151 20.2758 13.9187V13.9187C19.3385 14.4016 18.8873 15.491 19.2086 16.4951V16.4951C19.7433 18.1663 18.1663 19.7433 16.4951 19.2086V19.2086C15.491 18.8873 14.4016 19.3385 13.9187 20.2758V20.2758C13.1151 21.8356 10.8849 21.8356 10.0813 20.2758V20.2758C9.59842 19.3385 8.50905 18.8873 7.50485 19.2086V19.2086C5.83365 19.7433 4.25668 18.1663 4.79139 16.4951V16.4951C5.1127 15.491 4.66147 14.4016 3.7242 13.9187V13.9187C2.16438 13.1151 2.16438 10.8849 3.7242 10.0813V10.0813C4.66147 9.59842 5.1127 8.50905 4.79139 7.50485V7.50485C4.25668 5.83365 5.83365 4.25668 7.50485 4.79139V4.79139C8.50905 5.1127 9.59842 4.66147 10.0813 3.7242V3.7242Z" fill="#00A3FF" />
															<path class="permanent" d="M14.8563 9.1903C15.0606 8.94984 15.3771 8.9385 15.6175 9.14289C15.858 9.34728 15.8229 9.66433 15.6185 9.9048L11.863 14.6558C11.6554 14.9001 11.2876 14.9258 11.048 14.7128L8.47656 12.4271C8.24068 12.2174 8.21944 11.8563 8.42911 11.6204C8.63877 11.3845 8.99996 11.3633 9.23583 11.5729L11.3706 13.4705L14.8563 9.1903Z" fill="white" />
														</svg>
													</span>
												</a>
											</div> -->

											<!-- <div class="d-flex flex-wrap fw-bold fs-6 mb-4 pe-2">
												<a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
												<span class="svg-icon svg-icon-4 me-1">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
														<path opacity="0.3" d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z" fill="currentColor" />
														<path d="M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z" fill="currentColor" />
													</svg>
												</span>
												Developer</a>
												<a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary me-5 mb-2">
												<span class="svg-icon svg-icon-4 me-1">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
														<path opacity="0.3" d="M18.0624 15.3453L13.1624 20.7453C12.5624 21.4453 11.5624 21.4453 10.9624 20.7453L6.06242 15.3453C4.56242 13.6453 3.76242 11.4453 4.06242 8.94534C4.56242 5.34534 7.46242 2.44534 11.0624 2.04534C15.8624 1.54534 19.9624 5.24534 19.9624 9.94534C20.0624 12.0453 19.2624 13.9453 18.0624 15.3453Z" fill="currentColor" />
														<path d="M12.0624 13.0453C13.7193 13.0453 15.0624 11.7022 15.0624 10.0453C15.0624 8.38849 13.7193 7.04535 12.0624 7.04535C10.4056 7.04535 9.06241 8.38849 9.06241 10.0453C9.06241 11.7022 10.4056 13.0453 12.0624 13.0453Z" fill="currentColor" />
													</svg>
												</span>
												SF, Bay Area</a>
												<a href="#" class="d-flex align-items-center text-gray-400 text-hover-primary mb-2">
												<span class="svg-icon svg-icon-4 me-1">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
														<path opacity="0.3" d="M21 19H3C2.4 19 2 18.6 2 18V6C2 5.4 2.4 5 3 5H21C21.6 5 22 5.4 22 6V18C22 18.6 21.6 19 21 19Z" fill="currentColor" />
														<path d="M21 5H2.99999C2.69999 5 2.49999 5.10005 2.29999 5.30005L11.2 13.3C11.7 13.7 12.4 13.7 12.8 13.3L21.7 5.30005C21.5 5.10005 21.3 5 21 5Z" fill="currentColor" />
													</svg>
												</span>
												max@kt.com</a>
											</div> -->

										</div>
										<!--end::User-->

										<!--begin::Actions-->
										<div class="d-flex my-0">
											<!-- <a href="#" class="btn btn-sm btn-light me-2" id="kt_user_follow_button">
												<span class="svg-icon svg-icon-3 d-none">
													<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
														<path opacity="0.3" d="M10 18C9.7 18 9.5 17.9 9.3 17.7L2.3 10.7C1.9 10.3 1.9 9.7 2.3 9.3C2.7 8.9 3.29999 8.9 3.69999 9.3L10.7 16.3C11.1 16.7 11.1 17.3 10.7 17.7C10.5 17.9 10.3 18 10 18Z" fill="currentColor" />
														<path d="M10 18C9.7 18 9.5 17.9 9.3 17.7C8.9 17.3 8.9 16.7 9.3 16.3L20.3 5.3C20.7 4.9 21.3 4.9 21.7 5.3C22.1 5.7 22.1 6.30002 21.7 6.70002L10.7 17.7C10.5 17.9 10.3 18 10 18Z" fill="currentColor" />
													</svg>
												</span>
												<span class="indicator-label">Enviar</span>
												<span class="indicator-progress">Please wait...
												<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
											</a>
											<a href="#" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#kt_modal_offer_a_deal">Sobre Administrador</a> -->

											<!--begin::Menu-->
											<div class="me-0">
												<button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
													<i class="bi bi-three-dots fs-3"></i>
												</button>
												<!--begin::Menu 3-->
												<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
													<!--begin::Heading-->
													<div class="menu-item px-3">
														<div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Otras Acciones</div>
													</div>
													<!--end::Heading-->
													<!--begin::Menu item-->
													<div class="menu-item px-3">
														<a href="#" class="menu-link px-3">Accion 1</a>
													</div>
													<!--end::Menu item-->
													<!--begin::Menu item-->
													<div class="menu-item px-3">
														<a href="#" class="menu-link flex-stack px-3">Accion 2
														<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference"></i></a>
													</div>
													<!--end::Menu item-->
													<!--begin::Menu item-->
													<div class="menu-item px-3">
														<a href="#" class="menu-link px-3">Accion 3</a>
													</div>
													<!--end::Menu item-->
													<!--begin::Menu item-->
													<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-end">
														<a href="#" class="menu-link px-3">
															<span class="menu-title">Configuraciones</span>
															<span class="menu-arrow"></span>
														</a>
														<!--begin::Menu sub-->
														<div class="menu-sub menu-sub-dropdown w-175px py-4">
															<!--begin::Menu item-->
															<div class="menu-item px-3">
																<a href="#" class="menu-link px-3">Configuracion 1</a>
															</div>
															<!--end::Menu item-->
															<!--begin::Menu item-->
															<div class="menu-item px-3">
																<a href="#" class="menu-link px-3">Configuracion 2</a>
															</div>
															<!--end::Menu item-->
															<!--begin::Menu item-->
															<div class="menu-item px-3">
																<a href="#" class="menu-link px-3">Configuracion 3</a>
															</div>
															<!--end::Menu item-->
															<!--begin::Menu separator-->
															<div class="separator my-2"></div>
															<!--end::Menu separator-->
															<!--begin::Menu item-->
															<div class="menu-item px-3">
																<div class="menu-content px-3">
																	<!--begin::Switch-->
																	<label class="form-check form-switch form-check-custom form-check-solid">
																		<!--begin::Input-->
																		<input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />
																		<!--end::Input-->
																		<!--end::Label-->
																		<span class="form-check-label text-muted fs-6">Accion Cheched</span>
																		<!--end::Label-->
																	</label>
																	<!--end::Switch-->
																</div>
															</div>
															<!--end::Menu item-->
														</div>
														<!--end::Menu sub-->
													</div>
													<!--end::Menu item-->
													<!--begin::Menu item-->
													<div class="menu-item px-3 my-1">
														<a href="#" class="menu-link px-3">Seteos</a>
													</div>
													<!--end::Menu item-->
												</div>
												<!--end::Menu 3-->
											</div>
											<!--end::Menu-->
										</div>
										<!--end::Actions-->
									</div>
									<!--end::Title-->

									<!--begin::Stats-->
									<!-- <div class="d-flex flex-wrap flex-stack">
										<div class="d-flex flex-column flex-grow-1 pe-8">
											<div class="d-flex flex-wrap">
												<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
													<div class="d-flex align-items-center">
														<span class="svg-icon svg-icon-3 svg-icon-success me-2">
															<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
																<path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor" />
															</svg>
														</span>
														<div class="fs-2 fw-bolder" data-kt-countup="true" data-kt-countup-value="5" data-kt-countup-prefix="$">0</div>
													</div>
													<div class="fw-bold fs-6 text-gray-400">Earnings</div>
												</div>
												<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
													<div class="d-flex align-items-center">
														<span class="svg-icon svg-icon-3 svg-icon-danger me-2">
															<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																<rect opacity="0.5" x="11" y="18" width="13" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
																<path d="M11.4343 15.4343L7.25 11.25C6.83579 10.8358 6.16421 10.8358 5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75L11.2929 18.2929C11.6834 18.6834 12.3166 18.6834 12.7071 18.2929L18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25C17.8358 10.8358 17.1642 10.8358 16.75 11.25L12.5657 15.4343C12.2533 15.7467 11.7467 15.7467 11.4343 15.4343Z" fill="currentColor" />
															</svg>
														</span>
														<div class="fs-2 fw-bolder" data-kt-countup="true" data-kt-countup-value="2">0</div>
													</div>
													<div class="fw-bold fs-6 text-gray-400">Projects</div>
												</div>
												<div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
													<div class="d-flex align-items-center">
														<span class="svg-icon svg-icon-3 svg-icon-success me-2">
															<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																<rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor" />
																<path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor" />
															</svg>
														</span>
														<div class="fs-2 fw-bolder" data-kt-countup="true" data-kt-countup-value="6" data-kt-countup-prefix="%">0</div>
													</div>
													<div class="fw-bold fs-6 text-gray-400">Success Rate</div>
												</div>
											</div>
										</div>
										<div class="d-flex align-items-center w-200px w-sm-300px flex-column mt-3">
											<div class="d-flex justify-content-between w-100 mt-auto mb-2">
												<span class="fw-bold fs-6 text-gray-400">Profile Compleation</span>
												<span class="fw-bolder fs-6">80%</span>
											</div>
											<div class="h-5px mx-3 w-100 bg-light mb-3">
												<div class="bg-success rounded h-5px" role="progressbar" style="width: 80%;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
										</div>
									</div> -->
								</div>
								<!--end::Info-->
							</div>
							<!--end::Details-->
							<!--begin::Navs-->
							<ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder">
								<!--begin::Nav item-->
								<li class="nav-item mt-2">
									<a class="nav-link text-active-primary ms-0 me-10 py-5 active" href="?page=suptarea&menuid=0">Tareas</a>
								</li>
								<!--end::Nav item-->
								<!--begin::Nav item-->
								<li class="nav-item mt-2">
									<a class="nav-link text-active-primary ms-0 me-10 py-5" href="?page=supmenu&menuid=0">Menu</a>
								</li>
								<!--end::Nav item-->
								<!--begin::Nav item-->
								<li class="nav-item mt-2">
									<a class="nav-link text-active-primary ms-0 me-10 py-5" href="?page=supperfil&menuid=0">Perfil</a>
								</li>
								<!--end::Nav item-->
								<!--begin::Nav item-->
								<li class="nav-item mt-2">
									<a class="nav-link text-active-primary ms-0 me-10 py-5" href="?page=supusuario&menuid=0">Usuarios</a>
								</li>
								<!--end::Nav item-->
							</ul>
							<!--begin::Navs-->
						</div>
					</div>
					<!--end::Navbar-->
					<!--begin::Row-->
					<div class="row g-5 g-xxl-8">
						<input type="hidden" id="mensaje" value="<?php echo $mensaje ?>">
						<div class="card card-flush">
							<div class="card-toolbar">
								<button class="btn btn-sm btn-light-primary" id="btnNuevo">
									<span class="svg-icon svg-icon-2">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
											<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
											<rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
										</svg>
									</span>
								Nueva Tarea</button>
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
											<th style="display:none;">IdTarea</th>
											<th>Tarea</th>
											<th>Accion</th>
											<th>Estado</th>
											<th style="text-align:center;">Opciones</th>
											<th>Status</th>
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
												<td><?php echo $tareas['Accion']; ?></td>
												<td>
												<div class="<?php  echo $xTextColor; ?>"><?php echo $tareas['Estado']; ?></div>
												</td>
												<td>
													<div class="text-center">
														<div class="btn-group">
															<button <?php echo $xDisabledEdit ?> id="btnEditar<?php echo $tareas['Id']; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title='Editar Tarea'>
																<i class='fa fa-edit'></i>
															</button>																															 
														</div>
													</div>
												</td>
												<td>
													<div class="text-center">
														<div class="form-check form-check-sm form-check-custom form-check-solid">
																<input class="form-check-input btnEstado" type="checkbox" <?php echo $chkEstado; ?> id="chk<?php echo $tareas['Id']; ?>" <?php if ($tareas['Estado'] == 'Activo') {
																		echo "checked";} else {'';} ?> value="<?php echo $tareas['Id']; ?>" onchange="f_UpdateEstado(<?php echo $tareas['Id']; ?>,<?php echo $yEmprid; ?>)"/>
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
					<!--end::Row-->
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
								<!-- <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-9 p-6">
									<span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
											<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
											<rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor" />
											<rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor" />
										</svg>
									</span>
									
									<div class="d-flex flex-stack flex-grow-1">
										<div class="fw-bold">
											<h4 class="text-gray-900 fw-bolder">Warning</h4>
											<div class="fs-6 text-gray-700">Updating address may affter to your
											<a href="#">Tax Location</a></div>
										</div>
									</div>
								</div> -->
								
								<!-- <div class="row mb-5">
									<div class="col-md-6 fv-row">
										<label class="required fs-5 fw-bold mb-2">First name</label>
										<input type="text" class="form-control form-control-solid" placeholder="" name="first-name" />
									</div>
									
									<div class="col-md-6 fv-row">
										<label class="required fs-5 fw-bold mb-2">Last name</label>
										<input type="text" class="form-control form-control-solid" placeholder="" name="last-name" />
									</div>
								</div> -->
								
								<!-- <div class="d-flex flex-column mb-5 fv-row">
									<label class="d-flex align-items-center fs-5 fw-bold mb-2">
										<span class="required">Country</span>
										<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Your payment statements may very based on selected country"></i>
									</label>
									
									<select name="country" data-control="select2" data-dropdown-parent="#modal-tarea" data-placeholder="Select a Country..." class="form-select form-select-solid">
										<option value="">Select a Country...</option>
										<option value="AF">Afghanistan</option>
										<option value="AX">Aland Islands</option>
										<option value="AL">Albania</option>
										<option value="DZ">Algeria</option>
										<option value="AS">American Samoa</option>
										<option value="AD">Andorra</option>
										<option value="AO">Angola</option>
										<option value="AI">Anguilla</option>
										<option value="AG">Antigua and Barbuda</option>
										<option value="AR">Argentina</option>
										<option value="AM">Armenia</option>
										<option value="AW">Aruba</option>
										<option value="AU">Australia</option>
										<option value="AT">Austria</option>
										<option value="AZ">Azerbaijan</option>
										<option value="BS">Bahamas</option>
										<option value="BH">Bahrain</option>
										<option value="BD">Bangladesh</option>
										<option value="BB">Barbados</option>
										<option value="BY">Belarus</option>
										<option value="BE">Belgium</option>
										<option value="BZ">Belize</option>
										<option value="BJ">Benin</option>
										<option value="BM">Bermuda</option>
										<option value="BT">Bhutan</option>
										<option value="BO">Bolivia, Plurinational State of</option>
										<option value="BQ">Bonaire, Sint Eustatius and Saba</option>
										<option value="BA">Bosnia and Herzegovina</option>
										<option value="BW">Botswana</option>
										<option value="BR">Brazil</option>
										<option value="IO">British Indian Ocean Territory</option>
										<option value="BN">Brunei Darussalam</option>
										<option value="BG">Bulgaria</option>
										<option value="BF">Burkina Faso</option>
										<option value="BI">Burundi</option>
										<option value="KH">Cambodia</option>
										<option value="CM">Cameroon</option>
										<option value="CA">Canada</option>
										<option value="CV">Cape Verde</option>
										<option value="KY">Cayman Islands</option>
										<option value="CF">Central African Republic</option>
										<option value="TD">Chad</option>
										<option value="CL">Chile</option>
										<option value="CN">China</option>
										<option value="CX">Christmas Island</option>
										<option value="CC">Cocos (Keeling) Islands</option>
										<option value="CO">Colombia</option>
										<option value="KM">Comoros</option>
										<option value="CK">Cook Islands</option>
										<option value="CR">Costa Rica</option>
										<option value="CI">Côte d'Ivoire</option>
										<option value="HR">Croatia</option>
										<option value="CU">Cuba</option>
										<option value="CW">Curaçao</option>
										<option value="CZ">Czech Republic</option>
										<option value="DK">Denmark</option>
										<option value="DJ">Djibouti</option>
										<option value="DM">Dominica</option>
										<option value="DO">Dominican Republic</option>
										<option value="EC">Ecuador</option>
										<option value="EG">Egypt</option>
										<option value="SV">El Salvador</option>
										<option value="GQ">Equatorial Guinea</option>
										<option value="ER">Eritrea</option>
										<option value="EE">Estonia</option>
										<option value="ET">Ethiopia</option>
										<option value="FK">Falkland Islands (Malvinas)</option>
										<option value="FJ">Fiji</option>
										<option value="FI">Finland</option>
										<option value="FR">France</option>
										<option value="PF">French Polynesia</option>
										<option value="GA">Gabon</option>
										<option value="GM">Gambia</option>
										<option value="GE">Georgia</option>
										<option value="DE">Germany</option>
										<option value="GH">Ghana</option>
										<option value="GI">Gibraltar</option>
										<option value="GR">Greece</option>
										<option value="GL">Greenland</option>
										<option value="GD">Grenada</option>
										<option value="GU">Guam</option>
										<option value="GT">Guatemala</option>
										<option value="GG">Guernsey</option>
										<option value="GN">Guinea</option>
										<option value="GW">Guinea-Bissau</option>
										<option value="HT">Haiti</option>
										<option value="VA">Holy See (Vatican City State)</option>
										<option value="HN">Honduras</option>
										<option value="HK">Hong Kong</option>
										<option value="HU">Hungary</option>
										<option value="IS">Iceland</option>
										<option value="IN">India</option>
										<option value="ID">Indonesia</option>
										<option value="IR">Iran, Islamic Republic of</option>
										<option value="IQ">Iraq</option>
										<option value="IE">Ireland</option>
										<option value="IM">Isle of Man</option>
										<option value="IL">Israel</option>
										<option value="IT">Italy</option>
										<option value="JM">Jamaica</option>
										<option value="JP">Japan</option>
										<option value="JE">Jersey</option>
										<option value="JO">Jordan</option>
										<option value="KZ">Kazakhstan</option>
										<option value="KE">Kenya</option>
										<option value="KI">Kiribati</option>
										<option value="KP">Korea, Democratic People's Republic of</option>
										<option value="KW">Kuwait</option>
										<option value="KG">Kyrgyzstan</option>
										<option value="LA">Lao People's Democratic Republic</option>
										<option value="LV">Latvia</option>
										<option value="LB">Lebanon</option>
										<option value="LS">Lesotho</option>
										<option value="LR">Liberia</option>
										<option value="LY">Libya</option>
										<option value="LI">Liechtenstein</option>
										<option value="LT">Lithuania</option>
										<option value="LU">Luxembourg</option>
										<option value="MO">Macao</option>
										<option value="MG">Madagascar</option>
										<option value="MW">Malawi</option>
										<option value="MY">Malaysia</option>
										<option value="MV">Maldives</option>
										<option value="ML">Mali</option>
										<option value="MT">Malta</option>
										<option value="MH">Marshall Islands</option>
										<option value="MQ">Martinique</option>
										<option value="MR">Mauritania</option>
										<option value="MU">Mauritius</option>
										<option value="MX">Mexico</option>
										<option value="FM">Micronesia, Federated States of</option>
										<option value="MD">Moldova, Republic of</option>
										<option value="MC">Monaco</option>
										<option value="MN">Mongolia</option>
										<option value="ME">Montenegro</option>
										<option value="MS">Montserrat</option>
										<option value="MA">Morocco</option>
										<option value="MZ">Mozambique</option>
										<option value="MM">Myanmar</option>
										<option value="NA">Namibia</option>
										<option value="NR">Nauru</option>
										<option value="NP">Nepal</option>
										<option value="NL">Netherlands</option>
										<option value="NZ">New Zealand</option>
										<option value="NI">Nicaragua</option>
										<option value="NE">Niger</option>
										<option value="NG">Nigeria</option>
										<option value="NU">Niue</option>
										<option value="NF">Norfolk Island</option>
										<option value="MP">Northern Mariana Islands</option>
										<option value="NO">Norway</option>
										<option value="OM">Oman</option>
										<option value="PK">Pakistan</option>
										<option value="PW">Palau</option>
										<option value="PS">Palestinian Territory, Occupied</option>
										<option value="PA">Panama</option>
										<option value="PG">Papua New Guinea</option>
										<option value="PY">Paraguay</option>
										<option value="PE">Peru</option>
										<option value="PH">Philippines</option>
										<option value="PL">Poland</option>
										<option value="PT">Portugal</option>
										<option value="PR">Puerto Rico</option>
										<option value="QA">Qatar</option>
										<option value="RO">Romania</option>
										<option value="RU">Russian Federation</option>
										<option value="RW">Rwanda</option>
										<option value="BL">Saint Barthélemy</option>
										<option value="KN">Saint Kitts and Nevis</option>
										<option value="LC">Saint Lucia</option>
										<option value="MF">Saint Martin (French part)</option>
										<option value="VC">Saint Vincent and the Grenadines</option>
										<option value="WS">Samoa</option>
										<option value="SM">San Marino</option>
										<option value="ST">Sao Tome and Principe</option>
										<option value="SA">Saudi Arabia</option>
										<option value="SN">Senegal</option>
										<option value="RS">Serbia</option>
										<option value="SC">Seychelles</option>
										<option value="SL">Sierra Leone</option>
										<option value="SG">Singapore</option>
										<option value="SX">Sint Maarten (Dutch part)</option>
										<option value="SK">Slovakia</option>
										<option value="SI">Slovenia</option>
										<option value="SB">Solomon Islands</option>
										<option value="SO">Somalia</option>
										<option value="ZA">South Africa</option>
										<option value="KR">South Korea</option>
										<option value="SS">South Sudan</option>
										<option value="ES">Spain</option>
										<option value="LK">Sri Lanka</option>
										<option value="SD">Sudan</option>
										<option value="SR">Suriname</option>
										<option value="SZ">Swaziland</option>
										<option value="SE">Sweden</option>
										<option value="CH">Switzerland</option>
										<option value="SY">Syrian Arab Republic</option>
										<option value="TW">Taiwan, Province of China</option>
										<option value="TJ">Tajikistan</option>
										<option value="TZ">Tanzania, United Republic of</option>
										<option value="TH">Thailand</option>
										<option value="TG">Togo</option>
										<option value="TK">Tokelau</option>
										<option value="TO">Tonga</option>
										<option value="TT">Trinidad and Tobago</option>
										<option value="TN">Tunisia</option>
										<option value="TR">Turkey</option>
										<option value="TM">Turkmenistan</option>
										<option value="TC">Turks and Caicos Islands</option>
										<option value="TV">Tuvalu</option>
										<option value="UG">Uganda</option>
										<option value="UA">Ukraine</option>
										<option value="AE">United Arab Emirates</option>
										<option value="GB">United Kingdom</option>
										<option value="US">United States</option>
										<option value="UY">Uruguay</option>
										<option value="UZ">Uzbekistan</option>
										<option value="VU">Vanuatu</option>
										<option value="VE">Venezuela, Bolivarian Republic of</option>
										<option value="VN">Vietnam</option>
										<option value="VI">Virgin Islands</option>
										<option value="YE">Yemen</option>
										<option value="ZM">Zambia</option>
										<option value="ZW">Zimbabwe</option>
									</select>
									
								</div> -->

								<div class="d-flex flex-column mb-5 fv-row">
									<label class="required fs-5 fw-bold mb-2">Tarea</label>
									<input class="form-control form-control-solid" id="txtTarea" name="txtTarea" maxlength="100" placeholder="Ingrese Tarea"  />
								</div>
								
								<div class="d-flex flex-column mb-5 fv-row">
									<label class="required fs-5 fw-bold mb-2">Accion/Ruta</label>
									<input class="form-control form-control-solid" id="txtRuta" name="txtRuta" maxlength="150" placeholder="Ingrese Ruta" />
								</div>
								
								<!-- <div class="fv-row mb-5">
									<div class="d-flex flex-stack">
										<div class="me-5">
											<label class="fs-5 fw-bold">Use as a billing adderess?</label>
											<div class="fs-7 fw-bold text-muted">If you need more info, please check budget planning</div>
										</div>
										<label class="form-check form-switch form-check-custom form-check-solid">
											<input class="form-check-input" name="billing" type="checkbox" value="1" checked="checked" />
											<span class="form-check-label fw-bold text-muted">Yes</span>
										</label>
									</div>
								</div> -->
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
					//mensajesweetalert("center","success",_mensaje+"..!",false,1800);
					mensajesalertify(_mensaje+"..!","S","top-center",5);
				}

				$("#btnNuevo").click(function(){
					$("#modal-tarea").modal("show");
					$(".modal-title").text("Nueva Tarea");
					$("#frm_datos").trigger("reset");
					_addmod = 'add';
					_idtarea = 0;
				});

				$(document).on("click",".btnEditar",function(){
                
					_fila = $(this).closest("tr");
					_data = $('#kt_ecommerce_report_shipping_table').dataTable().fnGetData(_fila);
					_idtarea = _data[0];					
					_tareaold = _data[1];
					_addmod = 'mod';

					$(".modal-title").text("Editar Tarea");				
					$("#btnSave").text("Modificar");
					$("#frm_datos").trigger("reset");
					$("#modal-tarea").modal("show");
					$("#txtTarea").val(_data[1]);
					$("#txtRuta").val(_data[2]);					
				
				});				

				$('#btnSave').click(function(e){

					var _paisid = "<?php echo $yPaisid; ?>"
					var _emprid = "<?php echo $yEmprid; ?>"
					var _usuaid = "<?php echo $yUsuaid; ?>"
					var _tarea = $.trim($("#txtTarea").val());
					var _ruta = $.trim($("#txtRuta").val());
					var _buscar = 'SI';

					if(_tarea == ''){                        
						mensajesweetalert("center","warning","Ingrese Tarea",false,1800);
						return;
					}

					if(_ruta == ''){                        
						mensajesweetalert("center","warning","Ingrese Accion/Ruta",false,1800);
						return;
					}

					if(_addmod == 'mod'){
						if(_tareaold != _tarea){
							_buscar = 'SI';
						}else{
							_buscar = 'NO';
						}
					}
					
					if(_buscar == 'SI'){
						var xrespuesta = $.post("codephp/consultar_tarea.php", { xxTarea: _tarea, xxEmprid: _emprid });
						xrespuesta.done(function(response){							
							if(response == '0'){
								funGrabar(_paisid,_emprid,_usuaid,_tarea,_ruta);
							}else{								
								mensajesweetalert("center","warning","Tarea ya Existe..!",false,1800);
							}
						});						
					}else{
						funGrabar(_paisid,_emprid,_usuaid,_tarea,_ruta);
					}
				});

				$(document).on("click",".btnEstado",function(e){
					_fila = $(this).closest("tr");
					_tarea = $(this).closest("tr").find('td:eq(1)').text();  
					_ruta = $(this).closest("tr").find('td:eq(2)').text(); 					
				});
				
			});

			function f_UpdateEstado(_tareaid, _emprid){

				var _paisid = "<?php echo $yPaisid; ?>"
				var _emprid = "<?php echo $yEmprid; ?>"
				var _usuaid = "<?php echo $yUsuaid; ?>"
				
				let _check = $("#chk" + _tareaid).is(":checked");
				let _checked = "";
				let _disabled = "";
				let _class = "badge badge-light-primary";
				let _estado = "";

				if(_check){
					_estado = "Activo";
					_disabled = "";
					_checked = "checked='checked'";
					_class = "badge badge-light-primary";
				}else{
					_estado = "Inactivo";
					_disabled = "disabled";
					_class = "badge badge-light-danger";
				}

				var _lblEstado = '<td><div class="' + _class + '">' + _estado + ' </div>';

				var _btnedit = '<td><div class="text-center"><div class="btn-group"><button ' + _disabled + 
							'id="btnEditar"' +_tareaid + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title="Editar Tarea">' +
							'<i class="fa fa-edit"></i></button></div></div></td>';

				var _btnchk = '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
							'<input class="form-check-input btnEstado" type="checkbox" ' + ' id="chk' + _tareaid + '"' +
							' ' + _checked + ' value="' + _tareaid + '" onchange="f_UpdateEstado(' +_tareaid  + ',' + _emprid + ')"/>' +
							'</div></div></td>';

				TableData = $('#kt_ecommerce_report_shipping_table').DataTable();

				TableData.row(_fila).data([_tareaid, _tarea, _ruta, _lblEstado, _btnedit, _btnchk]).draw();

				$parametros = {
					xxEmprid: _emprid,
					xxTareaId: _tareaid,
					xxEstado: _estado
				}

				$.post("codephp/update_estado_tarea.php", $parametros , function(data){

					// $parametros = {
					// 	xxPaisid: _paisid,
					// 	xxEmprid: _emprid,
					// 	xxUsuaid: _usuaid,
					// 	xxDetalle: 'Cambio Estado Tarea',
					// }					

					// var xrespuesta = $.post("codephp/new_log.php", $parametros);						
					// 	xrespuesta.done(function(response) {
					// });	

				});
			}			

			function funGrabar(_paisid,_emprid,_usuaid,_tarea,_ruta){

				var _respuesta = 'OK';

				if(_addmod == 'add'){
					_ulr = "codephp/new_tarea.php";
				}else{
					_ulr = "codephp/update_tarea.php";
				}
				
				$parametros = {
					xxEmprid: _emprid,
					xxUsuaid: _usuaid,
					xxTareaId: _idtarea,
					xxTarea: _tarea,
					xxRuta: _ruta
				}				

				$.post(_ulr, $parametros , function(data){

					var _tareaid = data;

					if(_tareaid != 0){
						var _estado = '<td><div class="badge badge-light-primary">Activo</div>' ;

						var _btnedit = '<td><div class="text-center"><div class="btn-group"><button id="btnEditar' + _tareaid + '"' + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title="Editar Tarea" >' + 
							'<i class="fa fa-edit"></i></button></div></div></td>';

						var _btnchk = '<td style="text-align:center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
									'<input class="form-check-input btnEstado" type="checkbox" id="chk' + _tareaid + '" checked onchange="f_Check(' +
									_emprid + ',' + _tareaid + ')"' + ' value="' + _tareaid + '" onclick="f_UpdateEstado(' + _tareaid + ',' + _emprid + ')"' + '/></div></td>';	
									
						TableData = $('#kt_ecommerce_report_shipping_table').DataTable();

						TableData.column(0).visible(0);
							
						if(_addmod == 'add'){
							TableData.row.add([_tareaid, _tarea, _ruta, _estado, _btnedit, _btnchk]).draw();
							_detalle = 'Crear Nueva Tarea';
						}
						else{
							TableData.row(_fila).data([_tareaid, _tarea, _ruta, _estado, _btnedit, _btnchk]).draw();
							_detalle = 'Modificar Tarea';
						} 
					}else{
						//console.log('Error encontrado en sentecia SQL');
						_detalle = 'Error encontrado en sentecia SQL';
						_respuesta = 'ERR';
					}

					$("#modal-tarea").modal("hide");

					if(_respuesta == 'OK'){
						mensajesweetalert("center","success","Grabado con Exito..!",false,1800);
					}
					
					/**PARA CREAR REGISTRO DE LOGS */
					$parametros = {
						xxPaisid: _paisid,
						xxEmprid: _emprid,
						xxUsuaid: _usuaid,
						xxDetalle: _detalle,
					}					

                    $.post("codephp/new_log.php", $parametros, function(response){
                        //console.log(response);
                    }); 

				});	

				// $.ajax({
				// 	url: _ulr,
				// 	type: "POST",
				// 	dataType: "json",
				// 	data: $parametros,          
				// 	success: function(data){ 
				// 		if(data != 0){

				// 			_tareaid = data;										

				// 			var _estado = '<td><div class="badge badge-light-primary">Activo</div>' ;

				// 			var _btnedit = '<td><div class="text-center"><div class="btn-group"><button id="btnEditar' + _tareaid + '"' + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title="Editar Tarea" >' + 
				// 				'<i class="fa fa-edit"></i></button></div></div></td>';

				// 			var _btnchk = '<td style="text-align:center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
				// 						'<input class="form-check-input btnEstado" type="checkbox" id="chk' + _tareaid + '" checked onchange="f_Check(' +
				// 						_emprid + ',' + _tareaid + ')"' + ' value="' + _tareaid + '"' + '/></div></td>';	
										
				// 			TableData = $('#kt_ecommerce_report_shipping_table').DataTable();

				// 			TableData.column(0).visible(0);
								
				// 			if(_addmod == 'add'){
				// 				TableData.row.add([_tareaid, _tarea, _ruta, _estado, _btnedit, _btnchk]).draw();
				// 			}
				// 			else{
				// 				TableData.row(_fila).data([_tareaid, _tarea, _ruta, _estado, _btnedit, _btnchk]).draw();
				// 			} 

				// 			$("#modal-tarea").modal("hide");									

				// 		}                                                                         
				// 	},
				// 	error: function (error){
				// 		console.log(error);
				// 	}                            
				// });				
			}

			$("#modal-tarea").draggable({
					handle: ".modal-header"
			}); 			

		</script> 			