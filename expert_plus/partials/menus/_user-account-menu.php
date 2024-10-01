<?php

	@session_start();

	$xUserName = $_SESSION["s_usuario"];
	$xLoginName = $_SESSION["s_login"];
	$xPerfilid = $_SESSION["i_perfilid"];
	$xEmprid = $_SESSION["i_emprid"];
	$xUsuaid = $_SESSION["i_usuaid"];
	$xPaisid = $_SESSION["i_paisid"];
	$xAvatar = $_SESSION["s_avatar"];
	$xMode = "dark";

	if(strlen($xAvatar) < 5){
		$xAvatar = "userlogo.png";
	}

	require_once("./dbcon/config.php");

	$xSQL = "SELECT * FROM `expert_parametro_paginas` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND usua_id=$xUsuaid ";
	$all_paginas = mysqli_query($con, $xSQL);

	foreach ($all_paginas as $pagina) {
		$xMode = $pagina['index_content'];
	}


?>
			<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px" data-kt-menu="true">
				<div class="menu-item px-3">
					<div class="menu-content d-flex align-items-center px-3">
						<div class="symbol symbol-50px me-5">
							<img alt="Logo" src="assets/images/users/<?php echo $xAvatar; ?>" />
						</div>
						<div class="d-flex flex-column">
							<div class="fw-bolder d-flex align-items-center fs-5"><?php echo $xUserName; ?>
							<span class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2"></span></div>
							<a href="#" class="fw-bold text-muted text-hover-primary fs-7"><?php echo $xLoginName; ?></a>
						</div>
					</div>
				</div>
				<div class="separator my-2"></div>
				<div class="menu-item px-5">
					<a href="?page=account/overview" class="menu-link px-5">Mi Perfil</a>
				</div>
				<div class="menu-item px-5">
					<a href="#" class="menu-link px-5">
						<span class="menu-text">Notificaciones</span>
						<span class="menu-badge">
							<span class="badge badge-light-danger badge-circle fw-bolder fs-7">3</span>
						</span>
					</a>
				</div>
								
				<!-- <div class="menu-item px-5" data-kt-menu-trigger="hover" data-kt-menu-placement="left-start">
					<a href="#" class="menu-link px-5">
						<span class="menu-title">My Subscription</span>
						<span class="menu-arrow"></span>
					</a>
					
					<div class="menu-sub menu-sub-dropdown w-175px py-4">
						
						<div class="menu-item px-3">
							<a href="?page=account/referrals" class="menu-link px-5">Referrals</a>
						</div>
						
						<div class="menu-item px-3">
							<a href="?page=account/billing" class="menu-link px-5">Billing</a>
						</div>
						
						<div class="menu-item px-3">
							<a href="?page=account/statements" class="menu-link px-5">Payments</a>
						</div>
												
						<div class="menu-item px-3">
							<a href="?page=account/statements" class="menu-link d-flex flex-stack px-5">Statements
							<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="View your statements"></i></a>
						</div>
												
						<div class="separator my-2"></div>
						<div class="menu-item px-3">
							<div class="menu-content px-3">
								<label class="form-check form-switch form-check-custom form-check-solid">
									<input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />
									<span class="form-check-label text-muted fs-7">Notifications</span>
								</label>
							</div>
						</div>						
					</div>					
				</div> -->
				
				<!-- <div class="menu-item px-5">
					<a href="?page=account/statements" class="menu-link px-5">My Statements</a>
				</div> -->
				
				<div class="separator my-2"></div>
								
				<!-- <div class="menu-item px-5" data-kt-menu-trigger="hover" data-kt-menu-placement="left-start">
					<a href="#" class="menu-link px-5">
						<span class="menu-title position-relative">Language
						<span class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">English
						<img class="w-15px h-15px rounded-1 ms-2" src="assets/media/flags/united-states.svg" alt="" /></span></span>
					</a>
					
					<div class="menu-sub menu-sub-dropdown w-175px py-4">
						
						<div class="menu-item px-3">
							<a href="?page=account/settings" class="menu-link d-flex px-5 active">
							<span class="symbol symbol-20px me-4">
								<img class="rounded-1" src="assets/media/flags/united-states.svg" alt="" />
							</span>English</a>
						</div>
												
						<div class="menu-item px-3">
							<a href="?page=account/settings" class="menu-link d-flex px-5">
							<span class="symbol symbol-20px me-4">
								<img class="rounded-1" src="assets/media/flags/spain.svg" alt="" />
							</span>Spanish</a>
						</div>
												
						<div class="menu-item px-3">
							<a href="?page=account/settings" class="menu-link d-flex px-5">
							<span class="symbol symbol-20px me-4">
								<img class="rounded-1" src="assets/media/flags/germany.svg" alt="" />
							</span>German</a>
						</div>
												
						<div class="menu-item px-3">
							<a href="?page=account/settings" class="menu-link d-flex px-5">
							<span class="symbol symbol-20px me-4">
								<img class="rounded-1" src="assets/media/flags/japan.svg" alt="" />
							</span>Japanese</a>
						</div>
						
						<div class="menu-item px-3">
							<a href="?page=account/settings" class="menu-link d-flex px-5">
							<span class="symbol symbol-20px me-4">
								<img class="rounded-1" src="assets/media/flags/france.svg" alt="" />
							</span>French</a>
						</div>						
					</div>
					
				</div> -->

				<!--end::Menu item-->
				<!--begin::Menu item-->
				<!-- <div class="menu-item px-5 my-1">
					<a href="?page=account/settings" class="menu-link px-5">Account Settings</a>
				</div> -->

				<div class="menu-item px-5">
					<a href="./logout.php" class="menu-link px-5">Sign Out</a>
				</div>
				<div class="separator my-2"></div>
				
				<div class="menu-item px-5">
					<div class="menu-content px-5">
						<label class="form-check form-switch form-check-custom form-check-solid pulse pulse-success" for="kt_user_menu_dark_mode_toggle">
							<input class="form-check-input w-30px h-20px" type="checkbox" <?php if($xMode == 'light') { echo 'checked'; } else { echo ''; } ?> name="chkMode" id="chkMode" onchange="f_ChangeMode(this)" />
							<span class="pulse-ring ms-n1"></span>
							<span class="form-check-label text-gray-600 fs-7" id="tipoMode"><?php if($xMode == 'dark') { echo 'Dark Mode';}else{ echo 'Light Mode'; } ?></span>
						</label>
					</div>
				</div>
			</div> 
										
			<script>

				function f_ChangeMode(obj){

					let _check = $("#chkMode").is(":checked");
					let _emprid = "<?php echo $xEmprid; ?>";
					let _usuaid = "<?php echo $xUsuaid; ?>";
					let _paisid = "<?php echo $xPaisid; ?>";

					if(_check){
						document.getElementById("tipoMode").innerHTML  = "<span class='form-check-label text-gray-600 fs-7' id='tipoMode'>Ligth Mode</span>";
						_mode = "light";

					}else{
						document.getElementById("tipoMode").innerHTML  = "<span class='form-check-label text-gray-600 fs-7' id='tipoMode'>Dark Mode</span>";
						_mode = "dark";
					}

					var _parametros = {
						"xxPaisid": _paisid,
						"xxEmprid": _emprid,
						"xxUserid": _usuaid,
						"xxMode": _mode,
						"xxIndex": 'Content'
					}

					$.post("codephp/update_darklightmode.php", _parametros , function(response){
						//console.log(response);

						if(response.trim() == 'OK'){

                            /**PARA CREAR REGISTRO DE LOGS */
                            var _parametros = {
                                "xxPaisid": _paisid,
                                "xxEmprid": _emprid,
                                "xxUsuaid": _usuaid,
                                "xxDetalle": 'Cambiar Modo a ' + _mode,
                            }					

                            $.post("codephp/new_log.php", _parametros, function(response){
                            }); 

							$.redirect('?page=index');
						}
					});					
				}				

			</script>