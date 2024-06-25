<?php

	@session_start();

	$xPaisid = $_SESSION["i_paisid"];
	$xEmprid = $_SESSION["i_emprid"];
	$xUsuaid = $_SESSION["i_usuaid"];
	$xMode = "dark";

	require_once("./dbcon/config.php");
	
	$xSql = "SELECT * FROM `expert_parametro_paginas` WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND usua_id=$xUsuaid ";
	$all_paginas = mysqli_query($con, $xSql);

	foreach ($all_paginas as $pagina) {
		$xMode = $pagina['index_menu'];
	}

?>
			<!--begin::Menu-->
			<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-muted menu-active-bg menu-state-primary fw-bold py-4 fs-6 w-200px" data-kt-menu="true">
				<!--begin::Menu item
				<div class="menu-item px-3 my-1">
					<a href="../../demo1/dist/?page=index" class="menu-link px-3 active">
						<span class="menu-icon">
							<i class="fonticon-sun fs-2"></i>
						</span>
						<span class="menu-title">Fondo Blanco</span>
					</a>
				</div>
				<div class="menu-item px-3 my-1">
					<a href="../../demo1/dist/?page=index&amp;mode=dark" class="menu-link px-3">
						<span class="menu-icon">
							<i class="fonticon-moon fs-2"></i>
						</span>
						<span class="menu-title">Fondo Negro</span>
					</a>
				</div> -->

				<label class="form-check form-switch form-check-custom form-check-solid pulse pulse-success" for="kt_user_menu_dark_mode_toggle">
					<input class="form-check-input w-30px h-20px" type="checkbox" <?php if($xMode == 'light') { echo 'checked'; } else { echo ''; } ?> name="chkModemenu" id="chkModemenu" onchange="f_ChangeModemenu(this)" />
					<span class="pulse-ring ms-n1"></span>
					<span class="form-check-label text-gray-600 fs-7" id="tipoMode"><?php if($xMode == 'dark') { echo 'Dark Mode';}else{ echo 'Light Mode'; } ?></span>
				</label>				
				
			</div>


			<script>

				function f_ChangeModemenu(obj){

					let _check = $("#chkModemenu").is(":checked");
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
						xxPaisid: _paisid,
						xxEmprid: _emprid,
						xxUserid: _usuaid,
						xxMode: _mode,
						xxIndex: 'Menu'
					}

					$.post("codephp/update_darklightmode.php", _parametros , function(response){
						//console.log(response);

						if(response.trim() == 'OK'){

                            /**PARA CREAR REGISTRO DE LOGS */
                            var _parametros = {
                                xxPaisid: _paisid,
                                xxEmprid: _emprid,
                                xxUsuaid: _usuaid,
                                xxDetalle: 'Cambiar Modo a ' + _mode,
                            }					

                            $.post("codephp/new_log.php", _parametros, function(response){
                            }); 

							$.redirect('?page=index');
						}
					});					
				}				

			</script>			
										