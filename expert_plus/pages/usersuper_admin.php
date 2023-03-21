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
	$xSQL .= "ELSE 'Inactivo' END AS Estado, usua_caducapass AS CaducaPass, usua_avatarlogin AS LogoUser, (SELECT per.perf_descripcion FROM `expert_perfil` per WHERE per.perf_id=usu.perf_id) AS Perfil, (SELECT pais_nombre AS Pais FROM `expert_pais` pai WHERE pai.pais_id=usu.pais_id) AS Pais FROM `expert_usuarios` usu WHERE usu.perf_id>1";
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

            <!--begin::Card-->
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                </svg>
                            </span>
                            <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Search user" />
                        </div>
                    </div>
                                        
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                            <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M19.0759 3H4.72777C3.95892 3 3.47768 3.83148 3.86067 4.49814L8.56967 12.6949C9.17923 13.7559 9.5 14.9582 9.5 16.1819V19.5072C9.5 20.2189 10.2223 20.7028 10.8805 20.432L13.8805 19.1977C14.2553 19.0435 14.5 18.6783 14.5 18.273V13.8372C14.5 12.8089 14.8171 11.8056 15.408 10.964L19.8943 4.57465C20.3596 3.912 19.8856 3 19.0759 3Z" fill="currentColor" />
                                </svg>
                            </span>
                            Filtrar</button>

                            <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                                <div class="px-7 py-5">
                                    <div class="fs-5 text-dark fw-bolder">Opciones</div>
                                </div>
                                <div class="separator border-gray-200"></div>
                                <div class="px-7 py-5" data-kt-user-table-filter="form">
                                    <div class="mb-10">
                                        <label class="form-label fs-6 fw-bold">Perfiles:</label>
                                        <select class="form-select form-select-solid fw-bolder" data-kt-select2="true" data-placeholder="--Seleccione Perfil--" data-allow-clear="true" data-kt-user-table-filter="role" data-hide-search="true">
                                            <option></option>
                                            <?php foreach ($all_perfil as $per) : ?>
                                                <option value="<?= $per['Descripcion'] ?>"><?= $per['Descripcion'] ?></option>
                                            <?php endforeach ?>  
                                        </select>
                                    </div>
                                    <div class="mb-10">
                                        <label class="form-label fs-6 fw-bold">Estado:</label>
                                        <select class="form-select form-select-solid fw-bolder" data-kt-select2="true" data-placeholder="--Seleccione Estado--" data-allow-clear="true" data-kt-user-table-filter="two-step" data-hide-search="true">
                                            <option></option>
                                            <option value="Activo">Activo</option>
                                            <option value="Inactivo">Inactivo</option>
                                        </select>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="reset" class="btn btn-light btn-active-light-primary fw-bold me-2 px-6" data-kt-menu-dismiss="true" data-kt-user-table-filter="reset">Cancelar</button>
                                        <button type="submit" class="btn btn-primary fw-bold px-6" data-kt-menu-dismiss="true" data-kt-user-table-filter="filter">Aplicar</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">
                                <span class="svg-icon svg-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                        <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
                                    </svg>
                                </span>
                                Nuevo Usuario
                            </button>
                        </div>

                        <div class="d-flex justify-content-end align-items-center d-none" data-kt-user-table-toolbar="selected">
                            <div class="fw-bolder me-5">
                            <span class="me-2" data-kt-user-table-select="selected_count"></span>Selected</div>
                            <button type="button" class="btn btn-danger" data-kt-user-table-select="delete_selected">Delete Selected</button>
                        </div>

                        <div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered mw-650px">
                                <div class="modal-content">
                                    <div class="modal-header" id="kt_modal_add_user_header">
                                        <h2 class="fw-bolder">Nuevo Usuario</h2>
                                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                                            <span class="svg-icon svg-icon-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                                </svg>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                                        <form id="kt_modal_add_user_form" class="form" action="#">
                                            <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px">
                                                <div class="fv-row mb-7">
                                                    <label class="d-block fw-bold fs-6 mb-5">Avatar</label>
                                                    <div class="image-input image-input-outline" data-kt-image-input="true" style="background-image: url('assets/media/svg/avatars/blank.svg')">
                                                        <div class="image-input-wrapper w-125px h-125px" style="background-image: url(assets/media/avatars/300-6.jpg);"></div>
                                                        <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                                                            <i class="bi bi-pencil-fill fs-7"></i>
                                                            <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                                                            <input type="hidden" name="avatar_remove" />
                                                        </label>
                                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                                                            <i class="bi bi-x fs-2"></i>
                                                        </span>
                                                        <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                                                            <i class="bi bi-x fs-2"></i>
                                                        </span>
                                                    </div>
                                                    <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                                </div>

                                                <div class="fv-row mb-7">
                                                    <label class="required fw-bold fs-6 mb-2">Full Name</label>
                                                    <input type="text" name="user_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Full name" value="Emma Smith" />
                                                </div>

                                                <div class="fv-row mb-7">
                                                    <label class="required fw-bold fs-6 mb-2">Email</label>
                                                    <input type="email" name="user_email" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="example@domain.com" value="smith@kpmg.com" />
                                                </div>
                                                <div class="mb-7">
                                                    <label class="required fw-bold fs-6 mb-5">Role</label>
                                                    <div class="d-flex fv-row">
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input me-3" name="user_role" type="radio" value="0" id="kt_modal_update_role_option_0" checked='checked' />
                                                            <label class="form-check-label" for="kt_modal_update_role_option_0">
                                                                <div class="fw-bolder text-gray-800">Administrator</div>
                                                                <div class="text-gray-600">Best for business owners and company administrators</div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class='separator separator-dashed my-5'></div>
                                                    <div class="d-flex fv-row">
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input me-3" name="user_role" type="radio" value="1" id="kt_modal_update_role_option_1" />
                                                            <label class="form-check-label" for="kt_modal_update_role_option_1">
                                                                <div class="fw-bolder text-gray-800">Developer</div>
                                                                <div class="text-gray-600">Best for developers or people primarily using the API</div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class='separator separator-dashed my-5'></div>
                                                    <div class="d-flex fv-row">
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input me-3" name="user_role" type="radio" value="2" id="kt_modal_update_role_option_2" />
                                                            <label class="form-check-label" for="kt_modal_update_role_option_2">
                                                                <div class="fw-bolder text-gray-800">Analyst</div>
                                                                <div class="text-gray-600">Best for people who need full access to analytics data, but don't need to update business settings</div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class='separator separator-dashed my-5'></div>
                                                    <div class="d-flex fv-row">
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input me-3" name="user_role" type="radio" value="3" id="kt_modal_update_role_option_3" />
                                                            <label class="form-check-label" for="kt_modal_update_role_option_3">
                                                                <div class="fw-bolder text-gray-800">Support</div>
                                                                <div class="text-gray-600">Best for employees who regularly refund payments and respond to disputes</div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class='separator separator-dashed my-5'></div>
                                                    <div class="d-flex fv-row">
                                                        <div class="form-check form-check-custom form-check-solid">
                                                            <input class="form-check-input me-3" name="user_role" type="radio" value="4" id="kt_modal_update_role_option_4" />
                                                            <label class="form-check-label" for="kt_modal_update_role_option_4">
                                                                <div class="fw-bolder text-gray-800">Trial</div>
                                                                <div class="text-gray-600">Best for people who need to preview content data, but don't need to make any updates</div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-center pt-15">
                                                <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                                    <span class="indicator-label">Submit</span>
                                                    <span class="indicator-progress">Please wait...
                                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               
                <div class="card-body py-4">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                        <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th>Usuario</th>
                                <th>Pais</th>
                                <th>Perfil</th>
                                <th>Estado</th>
                                <th>Status</th>                                
                                <th style="text-align: center;">Opciones</th>
                            </tr>
                        </thead>

                        <tbody class="text-gray-600 fw-bold">

                            <?php 
                                        
                                foreach($all_usuarios as $usu){
                                    $idusuario = $usu['Idusuario'];
                                    $estado = trim($usu['Estado']);
                                    $usuario = trim($usu['Nombres']);
                                    $login = trim($usu['Email']);
                                    $avatar = trim($usu['LogoUser']);
                                    $perfil = trim($usu['Perfil']);
                                    if($avatar == ''){
                                        $avatar = 'man.png';
                                    }
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
                                        
                                        <td class="d-flex align-items-center">
                                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                <a href="?page=editsuper_user&menuid=0&tokeid=<?php echo $idusuario; ?>">
                                                    <div class="symbol-label">
                                                        <img src="assets/media/avatars/<?php echo $avatar; ?>" class="w-100" />
                                                    </div>
                                                </a>
                                            </div>
                                            
                                            <div class="d-flex flex-column">
                                                <a href="?page=editsuper_user&menuid=0&tokeid=<?php echo $idusuario; ?>" class="text-gray-800 text-hover-primary mb-1"><?php echo $usuario; ?></a>
                                                <span><?php echo $login; ?></span>
                                            </div>
                                        </td>

                                        
                                        <td><?php echo $usu['Pais']; ?></td>
                                        <td><?php echo $perfil; ?></td>
                                        
                                        <td id="<?php echo $usu['Idusuario']; ?>">
                                            <div class="<?php echo $xTextColor; ?>"><?php echo $estado; ?></div>
                                        </td>
                                        
                                        <td>
                                            <div class="text-center">
                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input <?php echo $cheking; ?> class="form-check-input h-20px w-20px border-primary btnEstado" <?php echo $chkEstado; ?> type="checkbox" id="chk<?php echo $idusuario; ?>" 
                                                        onchange="f_Check(<?php echo $xEmprid; ?>,<?php echo $usu['Idusuario']; ?>)" value="<?php echo $idusuario; ?>"/>
                                                </div>
                                            </div>
                                        </td> 													

                                        <td class="text-end">
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
        <!--end::Container-->
        
        