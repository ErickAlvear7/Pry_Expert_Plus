<?php
    
	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	
    
    //file_put_contents('log_seguimiento.txt', '1ero' . "\n\n", FILE_APPEND);

	//$xServidor = $_SERVER['HTTP_HOST'];
	
	$page = isset($_GET['page']) ? $_GET['page'] : 'index';
	$menuid = isset($_GET['menuid']) ? $_GET['menuid'] : '200001';

	if($page == 'addperfil' || $page == 'editperfil'){
		$page = 'seg_perfiladmin';
	}

	if($page == 'editparametro'){
		$page = 'param_generales';
	}
	
	if($page == 'addprestador'){
		$page = 'prestador_admin';
	}
	
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
	$xUsuaid = $_SESSION["i_usuaid"];	
	$xPerfilid = $_SESSION["i_perfilid"];
	$xPerfilName = $_SESSION["s_perfdesc"];

	$xIcono = "";
	$xActivo = "";
	$xPagina = "index";

	require_once("./dbcon/config.php");
	require_once("./dbcon/functions.php");
	
    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

	$xSQL = "SELECT distinct (SELECT mpa.mepa_id FROM `expert_menu_padre` mpa WHERE mpa.mepa_id=men.mepa_id) AS CodigoMenuPadre,";
	$xSQL .= "(SELECT mpa.mepa_descripcion FROM `expert_menu_padre` mpa WHERE mpa.mepa_id=men.mepa_id) AS MenuPadre," ;
	$xSQL .= "men.menu_id AS MenuId,men.menu_descripcion AS Menu FROM `expert_usuarios` usu, `expert_perfil_menu_tarea` pmt, `expert_menu_tarea` mta, `expert_menu` men ";
	$xSQL .= "WHERE usu.pais_id=$xPaisid AND usu.perf_id=pmt.perf_id AND pmt.meta_id=mta.meta_id AND mta.menu_id=men.menu_id AND pmt.meta_estado='A' ";
	$xSQL .= "AND men.menu_estado='A' AND usu.usua_id=$xUsuaid AND usu.perf_id=$xPerfilid ORDER BY men.menu_orden";
	
	$all_menu = mysqli_query($con, $xSQL);


?>
				<!--begin::Aside Menu-->
				<div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="0">
					<!--begin::Menu-->
					<div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true" data-kt-menu-expand="false">
						<?php
							$tempmenu = null;
							$menupadre = null;
							foreach ($all_menu as $menurow){
								if ($menurow["CodigoMenuPadre"] == null){
									if ($tempmenu != $menurow["MenuId"]){
										$xIcono = '';
										$xSQL = "SELECT * FROM `expert_iconos_menu` WHERE menu_id=" . $menurow["MenuId"];
										$dataicono = mysqli_query($con, $xSQL);

										foreach ($dataicono as $data){
											$xIcono = $data["icono"];
										}

										if($menuid  == $menurow["MenuId"]){
											$xActiveHere = 'here show';											
										}else{
											$xActiveHere = '';
										}

										echo "<div data-kt-menu-trigger='click' class='menu-item $xActiveHere menu-accordion'>";
											echo "<span class='menu-link'>";
												if(strlen($xIcono) > 100 ){
													echo "<span class='menu-icon'>";
													echo "<span class='svg-icon svg-icon-2'>";
														echo $xIcono;
													echo "</span>";
													echo "</span>";															
												}

												echo "<span class='menu-title'>" . $menurow['Menu'] . "</span>";
												echo "<span class='menu-arrow'></span>";
											echo "</span>";
											echo "<div class='menu-sub menu-sub-accordion menu-active-bg'>";
												
												$xSQL = "SELECT mnt.menu_id AS MenuId,tar.tare_nombre AS SubMenu,tar.tare_pagina AS Pagina ";
												$xSQL .= "FROM `expert_menu_tarea` mnt, `expert_perfil_menu_tarea` pmt, `expert_tarea` tar ";
												$xSQL .= "WHERE mnt.menu_id=" . $menurow["MenuId"] . " AND pmt.meta_id=mnt.meta_id AND pmt.perf_id=$xPerfilid AND mnt.tare_id=tar.tare_id ";
												$xSQL .= "AND tar.tare_estado='A' AND pmt.meta_estado='A' ORDER BY tar.tare_orden";
												$all_submenu = mysqli_query($con, $xSQL);

												foreach ($all_submenu as $submenu){
													$xPagina = $submenu["Pagina"];
													$xMenuId = $submenu["MenuId"];
													if($page == $xPagina){
														$xActivo = "active";
													}else{
														$xActivo = "";
													}
													echo "<div class='menu-item'>";
													echo "<a class='menu-link " . $xActivo . "' ";
														echo "href=" . "'?page=" . $xPagina . "&menuid=$xMenuId" . "'> ";
														echo "<span class='menu-bullet'> ";
															echo "<span class='bullet bullet-dot'></span> ";
														echo "</span>";
														echo "<span class='menu-title'>" . $submenu["SubMenu"] . "</span> ";
													echo "</a></div>";
												}												
											echo "</div>";
										echo "</div>";
									}
								}else{
								}
									$tempmenu = $menurow['MenuId'];
									$menusuperior = $menurow['CodigoMenuPadre'];
								}
							?>

						<?php
							if($xPerfilName == 'Super Administrador' and $xPerfilid == 1 ) { ?>

								<div data-kt-menu-trigger="click" class="menu-item <?php if($menuid == '0'){echo 'here show';} ?>  menu-accordion mb-1">
									<span class="menu-link">
										<span class="menu-icon">
											<span class="svg-icon svg-icon-2">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<path opacity="0.3" d="M20.5543 4.37824L12.1798 2.02473C12.0626 1.99176 11.9376 1.99176 11.8203 2.02473L3.44572 4.37824C3.18118 4.45258 3 4.6807 3 4.93945V13.569C3 14.6914 3.48509 15.8404 4.4417 16.984C5.17231 17.8575 6.18314 18.7345 7.446 19.5909C9.56752 21.0295 11.6566 21.912 11.7445 21.9488C11.8258 21.9829 11.9129 22 12.0001 22C12.0872 22 12.1744 21.983 12.2557 21.9488C12.3435 21.912 14.4326 21.0295 16.5541 19.5909C17.8169 18.7345 18.8277 17.8575 19.5584 16.984C20.515 15.8404 21 14.6914 21 13.569V4.93945C21 4.6807 20.8189 4.45258 20.5543 4.37824Z" fill="currentColor" />
													<path d="M14.854 11.321C14.7568 11.2282 14.6388 11.1818 14.4998 11.1818H14.3333V10.2272C14.3333 9.61741 14.1041 9.09378 13.6458 8.65628C13.1875 8.21876 12.639 8 12 8C11.361 8 10.8124 8.21876 10.3541 8.65626C9.89574 9.09378 9.66663 9.61739 9.66663 10.2272V11.1818H9.49999C9.36115 11.1818 9.24306 11.2282 9.14583 11.321C9.0486 11.4138 9 11.5265 9 11.6591V14.5227C9 14.6553 9.04862 14.768 9.14583 14.8609C9.24306 14.9536 9.36115 15 9.49999 15H14.5C14.6389 15 14.7569 14.9536 14.8542 14.8609C14.9513 14.768 15 14.6553 15 14.5227V11.6591C15.0001 11.5265 14.9513 11.4138 14.854 11.321ZM13.3333 11.1818H10.6666V10.2272C10.6666 9.87594 10.7969 9.57597 11.0573 9.32743C11.3177 9.07886 11.6319 8.9546 12 8.9546C12.3681 8.9546 12.6823 9.07884 12.9427 9.32743C13.2031 9.57595 13.3333 9.87594 13.3333 10.2272V11.1818Z" fill="currentColor" />
												</svg>											</span>
										</span>
										<span class="menu-title">Seguridad Master</span>
										<span class="menu-arrow"></span>
									</span>
									<div class="menu-sub menu-sub-accordion">
										<div class="menu-item">
											<a class="menu-link <?php if($page == 'suptarea'){echo 'active';} ?>" href="?page=suptarea&menuid=0">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Tarea</span>
											</a>
										</div>										
										<div class="menu-item">
											<a class="menu-link <?php if($page == 'supmenu' || $page == 'addmenu' || $page == 'editmenu'){echo 'active';} ?>" href="?page=supmenu&menuid=0">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Menu</span>
											</a>
										</div>
										<div class="menu-item">
											<a class="menu-link <?php if($page == 'supperfil' || $page == 'addsuperperfil' || $page == 'editsuperperfil'){echo 'active';} ?>" href="?page=supperfil&menuid=0">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Perfil</span>
											</a>
										</div>
										<div class="menu-item">
											<a class="menu-link <?php if($page == 'supusuario'){echo 'active';} ?>" href="?page=supusuario&menuid=0">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Usuarios</span>
											</a>
										</div>																									
									</div>
								</div>
							<?php } ?>

						<div class="menu-item">
							<div class="menu-content pt-8 pb-2">
								<span class="menu-section text-muted text-uppercase fs-8 ls-1">Apps</span>
							</div>
						</div>

						<div class="menu-item">
							<div class="menu-content">
								<div class="separator mx-1 my-4"></div>
							</div>
						</div>

					</div>
				</div>
			
						