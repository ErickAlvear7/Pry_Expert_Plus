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

    $xFechaActual = strftime('%Y-%m-%d', time());

    $xSQL = "SELECT usua_id AS Idusuario, CONCAT(usua_nombres,' ',usua_apellidos) AS Nombres, usua_login AS Email, CASE usua_estado WHEN 'A' THEN 'Activo' ";
	$xSQL .= "ELSE 'Inactivo' END AS Estado, usua_caducapass AS CaducaPass, (SELECT pais_nombre AS Pais FROM `expert_pais` pai WHERE pai.pais_id=usu.pais_id) AS Pais FROM `expert_usuarios` usu WHERE perf_id>1";
	$all_usuarios = mysqli_query($con, $xSQL);

    $xSQL = "SELECT pais_id AS IdPais, pais_nombre AS Pais, pais_flag AS Bandera FROM `expert_pais` ";
	$xSQL .= " ORDER BY IdPais ";
    $all_pais = mysqli_query($con, $xSQL);

	$xSQL = "SELECT p.perf_descripcion AS Descripcion,p.perf_id AS Codigo FROM `expert_perfil` p ";
	$xSQL .= " WHERE pais_id=$xPaisid AND empr_id=$xEmprid AND perf_estado='A' ";
	$xSQL .= " ORDER BY Codigo ";
    $all_perfil = mysqli_query($con, $xSQL);
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
							<a class="nav-link text-active-primary ms-0 me-10 py-5 " href="?page=supmenu&menuid=0">Menu</a>
						</li>
						<li class="nav-item mt-2">
							<a class="nav-link text-active-primary ms-0 me-10 py-5" href="?page=supperfil&menuid=0">Perfil</a>
						</li>
						<li class="nav-item mt-2">
							<a class="nav-link text-active-primary ms-0 me-10 py-5 active" href="?page=supusuario&menuid=0">Usuarios</a>
						</li>
					</ul>
				</div>
			</div>
			<!--begin::hasta aqui la cabecera-->
			<div class="row g-5 g-xxl-8">
                <div class="card card-flush">
                    <div class="card-toolbar">
                        <button class="btn btn-sm btn-light-primary" id="btnNuevo">
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
                        <!--<div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                            <div class="w-150px">
                                <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Estado" data-kt-ecommerce-order-filter="status">
                                    <option></option>
                                    <option value="all">Todos</option>
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>-->
                    </div>
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_report_shipping_table" style="width: 100%;">
                            <thead>
                                <tr class="text-start text-gray-800 fw-bolder fs-7 gs-0">
                                    <th style="display:none;">Id</th>
                                    <th>Usuario</th>
                                    <th>Login</th>
                                    <th>Estado</th>
                                    <th>Status</th>
                                    <th>Pais</th>
                                    <th style="text-align: center;">Opciones</th>
                                    
                                </tr>
                            </thead>
                            <tbody class="fw-bold text-gray-600">
                                <?php 
                                
                                foreach($all_usuarios as $usu){
                                    $idusuario = $usu['Idusuario'];
                                    $estado = $usu['Estado'];
                                    $usuario = $usu['Nombres'];
                                ?>
                                    <?php 

                                        $cheking = '';
                                        $chkEstado = '';
                                        $xDisabledEdit = '';
                                        $xDisabledReset = '';

                                        if($estado == 'Activo'){
                                            $cheking = 'checked="checked"';
                                            $xTextColor = "badge badge-light-primary";
                                        }else{
                                            $xTextColor = "badge badge-light-danger";
                                            $xDisabledEdit = 'disabled';
                                            $xDisabledReset = 'disabled';
                                        }

                                    ?>
                                <tr>
                                    <td style="display:none;"><?php echo $usu['Idusuario'] ?></td>
                                    <td><?php echo $usu['Nombres']; ?></td>
                                    <td><?php echo $usu['Email']; ?></td>
                                    <td id="<?php echo $usu['Idusuario']; ?>">
                                        <div class="<?php echo $xTextColor; ?>"><?php echo $usu['Estado']; ?></div>
                                    </td>
                                    
                                    <td>
                                        <div class="text-center">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input <?php echo $cheking; ?> class="form-check-input h-20px w-20px border-primary btnEstado" <?php echo $chkEstado; ?> type="checkbox" id="chk<?php echo $idusuario; ?>" 
                                                    onchange="f_Check(<?php echo $xEmprid; ?>,<?php echo $usu['Idusuario']; ?>)" value="<?php echo $idusuario; ?>"/>
                                            </div>
                                        </div>
                                    </td>       
                                    <td><?php echo $usu['Pais']; ?></td>
                                    <td>
                                        <div class="text-center">
                                            <div class="btn-group">
                                                <button id="btnReset" onclick="f_ResetPass(<?php echo $idusuario; ?>,<?php echo $xEmprid; ?>)" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" <?php echo $xDisabledReset;?> title='Resetear Password'>
                                                    <i class='fa fa-key'></i>
                                                </button>		
                                                <button id="btnEditar" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" <?php echo $xDisabledEdit; ?> title='Editar Usuario'>
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
		</div>
		<!--end::Container-->

        <div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <form class="form" id="frm_datos">
                        <div class="modal-header" id="kt_modal_add_customer_header">
                            <h3 class="modal-title" id="modalLabel"></h3>
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
                        </div>
                        <div class="modal-body py-10 px-lg-17">
                            <div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_customer_header" data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                                <div class="fw-bolder fs-3 rotate collapsible mb-7" data-bs-toggle="collapse" href="#kt_modal_add_customer_billing_info" role="button" aria-expanded="false" aria-controls="kt_customer_view_details">Informacion Usuario
                                    <span class="ms-2 rotate-180">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                            </svg>
                                        </span>
                                    </span>
                                </div>
                                <div id="kt_modal_add_customer_billing_info" class="collapse show">
                                    <div class="row g-9 mb-7">
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
                                            <input type="text" class="form-control form-control-solid" id="txtApellido" name="txtApellido" minlength="5" maxlength="100" placeholder="Ingrese Apellido" />
                                        </div>
                                    </div>

                                    <div class="row g-9 mb-7">
                                        <div class="col-md-6 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                                <span class="required">Email</span>
                                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="especifique el nick del usuario"></i>
                                            </label>
                                            <input type="email" class="form-control form-control-solid text-lowercase" id="txtLogin" name="txtLogin" minlength="1" maxlength="100"  placeholder=""  />
                                        </div>
                                        <div class="col-md-6 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                                <span class="required">Password</span>
                                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Ingrese Password"></i>
                                            </label>
                                            <input type="password" class="form-control form-control-solid" id="txtPassword" name="subject" minlength="1" maxlength="100" />
                                        </div>                                        

                                    </div>

                                    <div class="row g-9 mb-7">
                                        <div class="col-md-6 fv-row">
                                            <label class="required fs-6 fw-bold mb-2">Pais</label>
                                            <select id="cboPais" name="cboPais" data-placeholder="Seleccione Pais" class="form-select form-select-solid fw-bolder" onchange="f_Perfil(this)" >
                                                <option value="0">--Seleccione Pais--</option>
                                                <?php foreach ($all_pais as $pais) : 
                                                    $flag = ' data-kt-select2-country=' . '"assets/media/flags/' . $pais['Bandera'] . '"';
                                                ?>
                                                    <option value="<?php echo $pais['IdPais']; ?>"<?php echo $flag; ?>><?php echo $pais['Pais']; ?></option>
                                                <?php endforeach ?>						
                                            </select>
                                        </div>

                                        <div class="col-md-6 fv-row">
                                            <label class="required fs-6 fw-bold mb-2">Perfil</label>
                                            <select id="cboPerfil" name="cboPerfil" aria-label="Seleccione Perfil..." data-control="select2"  data-dropdown-parent="#kt_modal_add_customer" class="form-select form-select-solid fw-bolder">
                                                <option value="0" selected="selected">--Seleccione Perfil--</option>
                                               <?php foreach ($all_perfil as $per) : ?>
                                                    <option value="<?= $per['Codigo'] ?>"><?= $per['Descripcion'] ?></option>
                                                <?php endforeach ?>                                                
                                            </select>
                                        </div>
                                    </div>                                    

                                    <div class="row g-9 mb-7">
                                        <div class="col-md-6 fv-row text-center">
                                            <label class="fs-6 fw-bold mb-2">Password Caduca</label>
                                        </div>
                                        <div class="col-md-6 fv-row text-center">
                                            <label class="fs-6 fw-bold mb-2">Cambiar Password</label>  
                                        </div>
                                    </div>

                                    <div class="row g-9 mb-7">
                                        <div class="col-md-6 fv-row text-center">
                                            <input class="form-check-input h-20px w-20px border-primary" type="checkbox" id="chkCaducaPass" name="chkCaducaPass" value=""  />
                                            <label class="form-check-label" id="lblCaducaPass">NO</label>
                                        </div>
                                        <div class="col-md-6 fv-row text-center">
                                            <input class="form-check-input h-20px w-20px border border-primary" type="checkbox" id="chkCamPass" name="chkCamPass" value="" />
                                            <label class="form-check-label" id="lblCamPass">NO</label>
                                        </div>
                                    </div>

                                    <div class="row g-9 mb-7">
                                        <div class="col-xl-6 fv-row text-center" id="content" style="display: none;">
                                            <div class="position-relative d-flex align-items-center" >
                                                <!--begin::Svg Icon | path: icons/duotune/general/gen014.svg-->
                                                <span class="svg-icon position-absolute ms-4 mb-1 svg-icon-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path opacity="0.3" d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z" fill="currentColor" />
                                                        <path d="M6 6C5.4 6 5 5.6 5 5V3C5 2.4 5.4 2 6 2C6.6 2 7 2.4 7 3V5C7 5.6 6.6 6 6 6ZM11 5V3C11 2.4 10.6 2 10 2C9.4 2 9 2.4 9 3V5C9 5.6 9.4 6 10 6C10.6 6 11 5.6 11 5ZM15 5V3C15 2.4 14.6 2 14 2C13.4 2 13 2.4 13 3V5C13 5.6 13.4 6 14 6C14.6 6 15 5.6 15 5ZM19 5V3C19 2.4 18.6 2 18 2C17.4 2 17 2.4 17 3V5C17 5.6 17.4 6 18 6C18.6 6 19 5.6 19 5Z" fill="currentColor" />
                                                        <path d="M8.8 13.1C9.2 13.1 9.5 13 9.7 12.8C9.9 12.6 10.1 12.3 10.1 11.9C10.1 11.6 10 11.3 9.8 11.1C9.6 10.9 9.3 10.8 9 10.8C8.8 10.8 8.59999 10.8 8.39999 10.9C8.19999 11 8.1 11.1 8 11.2C7.9 11.3 7.8 11.4 7.7 11.6C7.6 11.8 7.5 11.9 7.5 12.1C7.5 12.2 7.4 12.2 7.3 12.3C7.2 12.4 7.09999 12.4 6.89999 12.4C6.69999 12.4 6.6 12.3 6.5 12.2C6.4 12.1 6.3 11.9 6.3 11.7C6.3 11.5 6.4 11.3 6.5 11.1C6.6 10.9 6.8 10.7 7 10.5C7.2 10.3 7.49999 10.1 7.89999 10C8.29999 9.90003 8.60001 9.80003 9.10001 9.80003C9.50001 9.80003 9.80001 9.90003 10.1 10C10.4 10.1 10.7 10.3 10.9 10.4C11.1 10.5 11.3 10.8 11.4 11.1C11.5 11.4 11.6 11.6 11.6 11.9C11.6 12.3 11.5 12.6 11.3 12.9C11.1 13.2 10.9 13.5 10.6 13.7C10.9 13.9 11.2 14.1 11.4 14.3C11.6 14.5 11.8 14.7 11.9 15C12 15.3 12.1 15.5 12.1 15.8C12.1 16.2 12 16.5 11.9 16.8C11.8 17.1 11.5 17.4 11.3 17.7C11.1 18 10.7 18.2 10.3 18.3C9.9 18.4 9.5 18.5 9 18.5C8.5 18.5 8.1 18.4 7.7 18.2C7.3 18 7 17.8 6.8 17.6C6.6 17.4 6.4 17.1 6.3 16.8C6.2 16.5 6.10001 16.3 6.10001 16.1C6.10001 15.9 6.2 15.7 6.3 15.6C6.4 15.5 6.6 15.4 6.8 15.4C6.9 15.4 7.00001 15.4 7.10001 15.5C7.20001 15.6 7.3 15.6 7.3 15.7C7.5 16.2 7.7 16.6 8 16.9C8.3 17.2 8.6 17.3 9 17.3C9.2 17.3 9.5 17.2 9.7 17.1C9.9 17 10.1 16.8 10.3 16.6C10.5 16.4 10.5 16.1 10.5 15.8C10.5 15.3 10.4 15 10.1 14.7C9.80001 14.4 9.50001 14.3 9.10001 14.3C9.00001 14.3 8.9 14.3 8.7 14.3C8.5 14.3 8.39999 14.3 8.39999 14.3C8.19999 14.3 7.99999 14.2 7.89999 14.1C7.79999 14 7.7 13.8 7.7 13.7C7.7 13.5 7.79999 13.4 7.89999 13.2C7.99999 13 8.2 13 8.5 13H8.8V13.1ZM15.3 17.5V12.2C14.3 13 13.6 13.3 13.3 13.3C13.1 13.3 13 13.2 12.9 13.1C12.8 13 12.7 12.8 12.7 12.6C12.7 12.4 12.8 12.3 12.9 12.2C13 12.1 13.2 12 13.6 11.8C14.1 11.6 14.5 11.3 14.7 11.1C14.9 10.9 15.2 10.6 15.5 10.3C15.8 10 15.9 9.80003 15.9 9.70003C15.9 9.60003 16.1 9.60004 16.3 9.60004C16.5 9.60004 16.7 9.70003 16.8 9.80003C16.9 9.90003 17 10.2 17 10.5V17.2C17 18 16.7 18.4 16.2 18.4C16 18.4 15.8 18.3 15.6 18.2C15.4 18.1 15.3 17.8 15.3 17.5Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                                <!--end::Svg Icon-->
                                                <input class="form-control form-control-solid ps-12" id="txtFechacaduca" name="txtFechacaduca" placeholder="Seleccione Fecha.." value="<?php echo date('Y-m-d',strtotime($xFechaActual)); ?> " />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer flex-center">
                            <button type="button" id="btnSave" class="btn btn-primary">
                                <span class="indicator-label">Guardar</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>        

        <script>

            $(document).ready(function(){

                $("#txtFechacaduca").flatpickr({
				    dateFormat: "Y-m-d"
                });

                Inputmask({
                    "mask" : "9999-99-99"
                }).mask("#txtFechacaduca");	                

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
               
                //$("#cboPerfil").val('0').change();
                
                var _cambiarPass, _nombre, _apellido, _login, _password, _perfil, _caduca,
                   _fechacaduca, _fila, _addmod, _idusu;

                //abrir-modal-nuevo-usuario
                $("#btnNuevo").click(function(){
                    _addmod = 'add';
                    _caduca = 'NO';
                    _cambiarPass = 'NO';
                    _idusu = 0;
                    _cboPerfil = 0;
                    _cboPais = 0;
                    
                    $("#frm_datos").trigger("reset");
                    $("#kt_modal_add_customer").modal("show");
                    $(".modal-title").text("Nuevo Usuario");
                    $("#btnSave").text("Grabar");
                    $("#chkCaducaPass").prop("checked", false);
                    $("#lblCaducaPass").text("NO");
                    $("#chkCamPass").prop("checked", false);
                    $("#lblCamPass").text("NO");
                    $('#cboPais').val(0).change();
                    $('#cboPerfil').val(0).change();                                        
                    $('#txtPassword').prop('readonly', false);
                    $('#content').css('display','none'); 
                });

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
                        //var today = (day)+'-'+(month)+'-'+now.getFullYear();
                        $('#txtFechacaduca').val(today);
                    }else{
                        element.style.display='none';
                        $("#lblCaducaPass").text("NO");
                        _caduca = 'NO';					
                    }
                });

                $(document).on("click","#chkCamPass",function(){

                    if($("#chkCamPass").is(":checked")){
                        $("#lblCamPass").text("SI");
                        _cambiarPass = 'SI';
                    }else{
                        $("#lblCamPass").text("NO");
                        _cambiarPass = 'NO';
                    }
                });

                //editar modal usuario
                $(document).on("click",".btnEditar",function(){
                    
                    var _emprid = "<?php echo $xEmprid; ?>"
                    _fila = $(this).closest("tr");
                    var _data = $('#kt_ecommerce_report_shipping_table').dataTable().fnGetData(_fila);
                    
                    _idusu = _data[0];
                    _loginold = _data[2];
                    _paisname = _data[5];
                    _addmod = 'mod';                     

                    $parametros = {
                        xxEmprid: _emprid,
                        xxIdUsuario: _idusu
                    }

                    $.ajax({
                        url: "codephp/editar_usuarios.php",
                        type: "POST",
                        dataType: "json",
                        data: $parametros,          
                        success: function(data){ 

                            var _nombres = data[0]['Nombres'];
                            var _apellidos = data[0]['Apellidos'];
                            var _login = data[0]['Login'];
                            var _password = data[0]['Password'];
                            _cboPais = data[0]['CodigoPais'];
                            _cboPerfil = data[0]['CodigoPerfil'];
                            
                            _caduca = data[0]['CaducaPass'];
                            _fechaCaduca = data[0]['FechaCaduca'];
                            _cambiarPass = data[0]['CambiarPass'];

                            $("#txtNombre").val(_nombres);
                            $("#txtApellido").val(_apellidos);
                            $("#txtLogin").val(_login);
                            $("#txtPassword").val(_password);
                            $("#cboPais").val(_cboPais).change();
                            $("#cboPerfil").val(_cboPerfil).change();
                            $("#txtFechacaduca").val(_fechaCaduca);

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
                    
                    $(".modal-title").text("Editar Usuario");				
                    $("#btnSave").text("Modificar");
                    //$("#frm_datos").trigger("reset");
                    $('#txtPassword').prop('readonly', true);
                    //$("#cboPerfil").val(2).change();                    
                    $("#kt_modal_add_customer").modal("show");

                });                

                //Guardar usuario
                $('#btnSave').click(function(e){
                    //e.preventDefault();
                    var _savepaisid = "<?php echo $xPaisid; ?>";
                    var _emprid = "<?php echo $xEmprid; ?>";
                    var _usuaid = "<?php echo $xUsuaid; ?>";
                    var _nombre = $.trim($("#txtNombre").val());
                    var _apellido = $.trim($("#txtApellido").val());
                    var _login = $.trim($("#txtLogin").val());
                    var _password = $.trim($("#txtPassword").val());
                    var _paisid = $('#cboPais').val();                    
                    var _perfilid = $('#cboPerfil').val();                    
                    //var _perfilname = $("#cboPerfil option:selected").text();

                    _paisname = $("#cboPais option:selected").text();
                    _fechacaduca = $.trim($("#txtFechacaduca").val());
                    _buscar = 'SI';
                    //_continuar = 'SI';
                    _respuesta = 'OK';

                    if(_nombre == ''){                        
                        mensajesweetalert("center","warning","Ingrese Nombre de Usuario",false,1800);
                        return;
                    }

                    if(_login == ''){                        
                        mensajesweetalert("center","warning","Ingrese Login/Email",false,1800);
                        return;
                    }
                    
                    var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;
                    if (regex.test(_login.trim())){
                    }else{
                        mensajesweetalert("center","warning","Login/Email incorrecto",false,1800);
                        return;
                    }                    

                    if(_password == ''){                        
                        mensajesweetalert("center","warning","Ingrese Password",false,1800);
                        return;
                    }

                    if(_paisid == '0'){                        
                        mensajesweetalert("center","warning","Seleccione Pais",false,1800);
                        return;
                    }
                    
                    if(_perfilid == '0'){                        
                        mensajesweetalert("center","warning","Seleccione Perfil",false,1800);
                        return;
                    }
                    
                    if(_addmod == 'mod'){
                        if(_loginold.toLowerCase() != _login.toLowerCase()){
                            _buscar = 'SI';
                        }else{
                            _buscar = 'NO';
                        }
                        
                        $datosUser = {
                            xxUsuaid: _idusu,
                            xxEmprid: _emprid,
                            xxNombre: _nombre,
                            xxApellido:_apellido,
                            xxLogin: _login,
                            xxPaisid: _paisid,
                            xxPerfilid: _perfilid,
                            xxCaducaPass: _caduca,
                            xxCambiarPass: _cambiarPass,
                            xxFecha: _fechacaduca
                        }	
                        _ulr = "codephp/actualizar_usuario.php";
                    }else{
                        $datosUser = {
                            xxUsuaid: _usuaid,
                            xxEmprid: _emprid,
                            xxNombre: _nombre,
                            xxApellido:_apellido,
                            xxLogin: _login,
                            xxPassword: _password,
                            xxPaisid: _paisid,
                            xxPerfilid: _perfilid,
                            xxCaducaPass: _caduca,
                            xxCambiarPass: _cambiarPass,
                            xxFecha: _fechacaduca
                        }
                        _ulr = "codephp/grabar_usuarios.php";                        
                    }
                    
                    if(_buscar == 'SI'){
                        var xrespuesta = $.post("codephp/consultar_usuarios.php", {xxLogin: _login});
                        xrespuesta.done(function(response){
                            
                            if(response == 0){

                                $.post(_ulr, $datosUser , function(response){
        
                                    var _userid = response;	
                                    var _usuario = _nombre + ' ' + _apellido;
        
                                    if(_userid != 0){
                                        var _estado = '<td><div class="badge badge-light-primary">Activo</div>' ;
                                        
                                        var _btnchk = '<td style="text-align:center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
                                                    '<input class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk' + _userid + '" checked onchange="f_Check(' +
                                                    _emprid + ',' + _userid + ')"' + ' value="' + _userid + '"' + '/></div></td>';	

                                        var _btnopc = '<td><div class="text-center"><div class="btn-group"><button id="btnReset' + _userid + '"' + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Resetear Perfil" >' + 
                                                        '<i class="fa fa-key"></i></button><button id="btnEditar' + _userid + '"' + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title="Editar Usuario" >' + 
                                                        '<i class="fa fa-edit"></i></button></div></div></td>';
        
                                        TableData = $('#kt_ecommerce_report_shipping_table').DataTable();
        
                                        if(_addmod == 'add'){
                                            //TableData.column(0).visible(0);
                                            TableData.row.add([_userid, _usuario, _login.toLowerCase(), _estado, _btnchk, _paisname, _btnopc ]).draw();
                                            _detalle = 'Nuevo usuario creado desde superadmin';
                                            _mensaje = 'Grabado con Exito';
                                        }
                                        else{
                                            TableData.row(_fila).data([_userid, _usuario, _login.toLowerCase(), _estado, _btnchk, _paisname, _btnopc ]).draw();
                                            _detalle = 'Actualizar usuario desde superadmin';
                                            _mensaje = 'Actualizado con Exito';
                                        } 
                                    }else{
                                        _detalle = 'Error encontrado en sentecia SQL desde superadmin';
        						        _respuesta = 'ERR';                                
                                    }
        
                                    /**PARA CREAR REGISTRO DE LOGS */
                                    // $parametros = {
                                    //     xxPaisid: _savepaisid,
                                    //     xxEmprid: _emprid,
                                    //     xxUsuaid: _usuaid,
                                    //     xxDetalle: _detalle,
                                    // }					
        
                                    // $.post("codephp/new_log.php", $parametros, function(response){
                                    // }); 

									if(_respuesta == 'OK'){
										mensajesweetalert("center", "success", _mensaje, false, 1800);
										
										if(_addmod == 'add'){
											$.redirect('?page=supusuario&menuid=0'); 
										}else{
											$("#kt_modal_add_customer").modal("hide");
										}
									}

                                });                                   
                            }else{
                                mensajesweetalert("center","warning","Login/Email ya existe..!",false,1800);
                                return;
                            }
                        });
                    }else{
                        $.post(_ulr, $datosUser , function(response){
                            
                            var _userid = response;	
                            var _usuario = _nombre + ' ' + _apellido;

                            if(_userid != 0){
                                var _estado = '<td><div class="badge badge-light-primary">Activo</div>' ;
                                
                                var _btnchk = '<td style="text-align:center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
                                            '<input class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk' + _userid + '" checked onchange="f_Check(' +
                                            _emprid + ',' + _userid + ')"' + ' value="' + _userid + '"' + '/></div></td>';	

                                var _btnopc = '<td><div class="text-center"><div class="btn-group"><button id="btnReset' + _userid + '"' + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Resetear Perfil" >' + 
                                                '<i class="fa fa-key"></i></button><button id="btnEditar' + _userid + '"' + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title="Editar Usuario" >' + 
                                                '<i class="fa fa-edit"></i></button></div></div></td>';

                                TableData = $('#kt_ecommerce_report_shipping_table').DataTable();

                                if(_addmod == 'add'){
                                    //TableData.column(0).visible(0);
                                    TableData.row.add([_userid, _usuario, _login, _estado, _btnchk, _paisname, _btnopc ]).draw();
                                    _detalle = 'Nuevo usuario creado desde superadmin';
                                    _mensaje = 'Grabado con Exito';
                                }
                                else{
                                    TableData.row(_fila).data([_userid, _usuario, _login, _estado, _btnchk, _paisname, _btnopc ]).draw();
                                    _detalle = 'Actualizar usuario desde superadmin';
                                    _mensaje = 'Actualizado con Exito';
                                } 
                            }else{
                                _detalle = 'Error encontrado en sentecia SQL desde superadmin';
						        _respuesta = 'ERR';                                
                            }

                            /**PARA CREAR REGISTRO DE LOGS */
                            // $parametros = {
                            //     xxPaisid: _savepaisid,
                            //     xxEmprid: _emprid,
                            //     xxUsuaid: _usuaid,
                            //     xxDetalle: _detalle,
                            // }					

                            // $.post("codephp/new_log.php", $parametros, function(response){
                            // }); 

                            if(_respuesta == 'OK'){
                                mensajesweetalert("center", "success", _mensanoje, false, 1800);
                                
                                if(_addmod == 'add'){
                                    $.redirect('?page=supusuario&menuid=0'); 
                                }else{
                                    $("#kt_modal_add_customer").modal("hide");
                                }
                            }

                        });                          
                    }

                });
            });	

            //cambiar estado y desactivar botones en linea

            $(document).on("click",".btnEstado",function(e){
                
                debugger;
                _fila = $(this).closest("tr");
                _usuario = $(this).closest("tr").find('td:eq(1)').text();
                _login = $(this).closest("tr").find('td:eq(2)').text();
                _paisname = $(this).closest("tr").find('td:eq(5)').text();
                
            });

            //cambiar estado y desactivar botones en linea

            function f_Check(_emprid, _userid){

                let _check = $("#chk" + _userid).is(":checked");
                //let _btn = "btnEditar" + _userid;
                //let _td = "td" + _userid;
                let _checked = "";
                let _disabled = "";
                let _class = "badge badge-light-primary";

                //alert(_paisname);
                
                if(_check){
                    _estado = "Activo";
                    _disabled = "";
                    _checked = "checked='checked'";
                }else{                    
                    _estado = "Inactivo";
                    _disabled = "disabled";
                    _class = "badge badge-light-danger";
                }
    
                var _lblEstado = '<td><div class="' + _class + '">' + _estado + ' </div>';
    
                var _btnchk = '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
                            '<input ' + _checked + 'class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk' + _userid + '"' +
                            'onchange="f_Check(' +_emprid  + ',' + _userid + ')"' + 'value="' + _userid + '"' + '/></div></div></td>';

                var _btnopc = '<td><div class="text-center"><div class="btn-group"><button ' +  _disabled +  ' id="btnReset"' +
                                ' onclick="f_ResetPass(' +_userid + ',' +_emprid + ')"' + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Resetear Password">' +
                                '<i class="fa fa-key"></i></button><button ' +  _disabled +  ' id="btnEditar' + _userid + '"' +		
                                'class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title="Editar Usuario">' +
                                '<i class="fa fa-edit"></i></button></div></div></td>';	
                
                TableData = $('#kt_ecommerce_report_shipping_table').DataTable();

                //TableData.column(0).visible(0);
    
                TableData.row(_fila).data([_userid, _usuario, _login, _lblEstado, _btnchk, _paisname, _btnopc ]).draw();
    
                $parametros = {
                    xxUsuaid: _userid,
                    xxEmprid: _emprid,
                    xxEstado: _estado
                }	
    
                var xrespuesta = $.post("codephp/delnew_usuario.php", $parametros);
                xrespuesta.done(function(response){
                    //console.log(response);
                });	
            }

            //desplazar ventana modal
            $("#kt_modal_add_customer").draggable({
                handle: ".modal-header"
            }); 

            //resetaer password

            function f_ResetPass(_usuaid, _emprid){

                $parametros={
                    xxUsuaid: _usuaid,
                    xxEmprid: _emprid
                }

                $.post("codephp/reset_password.php", $parametros, function(response){

                    if(response.trim() == 'OK'){
                        mensajesweetalert("center","success","Password Resetado con exito..!",false,1800);
                    }

                });                 

                // $.ajax({
                //     url: "codephp/reset_password.php",
                //     type: "POST",
                //     dataType: "json",
                //     data: $parametros,          
                //     success: function(data){ 
                //         if(data == 'OK'){
                //             mensajesweetalert("center","success","password actualizado con exito..!",false,1800);
                //         }                                                                         
                //     },
                //     error: function (error){
                //         console.log(error);
                //     }                            
                // }); 			 
            }
            
            function f_Perfil(obj){
                
                var _emprid = "<?php echo $xEmprid; ?>";
                var _paisid = obj.options[obj.selectedIndex].value;
                
                var xresponse = $.post("codephp/get_PerfilxPais.php", { xxPaisid: _paisid, xxEmprid: _emprid});
                
                xresponse.done(function(respuesta){
                    
                    $("#cboPerfil").html(respuesta);
                    if(_paisid == _cboPais)
                    {
                        $("#cboPerfil").val(_cboPerfil).change();
                    }else{
                        $("#cboPerfil").val(0).change();
                    }

                });
                
                xresponse.fail(function(){
                    mensajesweetalert("center","error","Error al Cargar Perfil",false,1800);
                });
            }
            
	    </script> 	        