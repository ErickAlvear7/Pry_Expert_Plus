<?php
    
	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	

	//$xServidor = $_SERVER['HTTP_HOST'];
	
	$page = isset($_GET['page']) ? $_GET['page'] : 'index';
	$menuid = isset($_GET['menuid']) ? $_GET['menuid'] : '200001';

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
	$xPerfilName = $_SESSION["s_perfdesc"];

	$xIcono = "";
	$xActivo = "";
	$xPagina = "index";

	//file_put_contents('log_errores.txt', $xNombreusuario . "\n\n", FILE_APPEND);

	require_once("dbcon/config.php");
	require_once("dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

	/*$xSql = "SELECT (SELECT mpa.mepa_id FROM `expert_menu_padre` mpa WHERE mpa.mepa_id=men.mepa_id) AS CodigoMenuPadre,";
	$xSql .= "(SELECT mpa.mepa_descripcion FROM `expert_menu_padre` mpa WHERE mpa.mepa_id=men.mepa_id) AS MenuPadre," ;
	$xSql .= "(SELECT mpa.mepa_icono FROM `expert_menu_padre` mpa WHERE mpa.mepa_id=men.mepa_id) AS IcoMenuPadre,";
	$xSql .= "men.menu_id AS MenuId,men.menu_descripcion AS Menu,men.menu_icono AS Icono,tar.tare_nombre AS SubMenu,tar.tare_ruta AS Pagina ";
	$xSql .= "FROM `expert_usuarios` usu, `expert_perfil` per, `expert_perfil_menu_tarea` pmt, `expert_menu` men,`expert_menu_tarea` mnt, `expert_tarea` tar ";
	$xSql .= "WHERE usu.perf_id = per.perf_id AND per.perf_id = pmt.perf_id AND pmt.meta_id = mnt.meta_id AND mnt.menu_id = men.menu_id AND ";
	$xSql .= "mnt.tare_id = tar.tare_id AND men.menu_estado='A' AND tar.tare_estado='A' AND USU.usua_id=" . $yUsuaCodigo . " AND men.mepa_id>0 ";
	$xSql .= "ORDER BY men.menu_orden,mnt.meta_orden";

	$all_menupadre = mysqli_query($con, $xSql);*/

	// $xSql = "SELECT (SELECT mpa.mepa_id FROM `expert_menu_padre` mpa WHERE mpa.mepa_id=men.mepa_id) AS CodigoMenuPadre,";
	// $xSql .= "(SELECT mpa.mepa_descripcion FROM `expert_menu_padre` mpa WHERE mpa.mepa_id=men.mepa_id) AS MenuPadre," ;
	// $xSql .= "(SELECT mpa.mepa_icono FROM `expert_menu_padre` mpa WHERE mpa.mepa_id=men.mepa_id) AS IcoMenuPadre,";
	// $xSql .= "men.menu_id AS MenuId,men.menu_descripcion AS Menu,men.menu_icono AS Icono,tar.tare_nombre AS SubMenu,tar.tare_ruta AS Pagina ";
	// $xSql .= "FROM `expert_usuarios` usu, `expert_perfil` per, `expert_perfil_menu_tarea` pmt, `expert_menu` men,`expert_menu_tarea` mnt, `expert_tarea` tar ";
	// $xSql .= "WHERE usu.perf_id = per.perf_id AND per.perf_id = pmt.perf_id AND pmt.meta_id = mnt.meta_id AND mnt.menu_id = men.menu_id AND ";
	// $xSql .= "mnt.tare_id = tar.tare_id AND men.menu_estado='A' AND tar.tare_estado='A' AND USU.usua_id=" . $yUsuaCodigo;
	// $xSql .= " ORDER BY men.menu_orden,mnt.meta_orden";

	$xSql = "SELECT distinct (SELECT mpa.mepa_id FROM `expert_menu_padre` mpa WHERE mpa.mepa_id=men.mepa_id) AS CodigoMenuPadre,";
	$xSql .= "(SELECT mpa.mepa_descripcion FROM `expert_menu_padre` mpa WHERE mpa.mepa_id=men.mepa_id) AS MenuPadre," ;
	$xSql .= "men.menu_id AS MenuId,men.menu_descripcion AS Menu FROM `expert_usuarios` usu, `expert_perfil_menu_tarea` pmt, `expert_menu_tarea` mta, `expert_menu` men ";
	$xSql .= "WHERE usu.pais_id=$yPaisid AND usu.perf_id=pmt.perf_id AND pmt.meta_id=mta.meta_id AND mta.menu_id=men.menu_id ";
	$xSql .= "AND men.menu_estado='A' AND usu.usua_id=$yUsuaid ORDER BY men.menu_orden";

	$all_menu = mysqli_query($con, $xSql);


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
										$xSql = "SELECT * FROM `expert_iconos_menu` WHERE menu_id=" . $menurow["MenuId"];
										$dataicono = mysqli_query($con, $xSql);

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
												
												$xSql = "SELECT mnt.menu_id AS MenuId,tar.tare_nombre AS SubMenu,tar.tare_ruta AS Pagina ";
												$xSql .= "FROM `expert_menu_tarea` mnt, `expert_tarea` tar ";
												$xSql .= "WHERE mnt.menu_id=" . $menurow["MenuId"] . " AND mnt.tare_id=tar.tare_id ";
												$xSql .= "AND tar.tare_estado='A' ORDER BY tar.tare_orden";

												$all_submenu = mysqli_query($con, $xSql);


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
							if($xPerfilName == 'Super Administrador' and $yPaisid == 1 ) { ?>

								<div data-kt-menu-trigger="click" class="menu-item <?php if($menuid == '0'){echo 'here show';} ?>  menu-accordion mb-1">
									<span class="menu-link">
										<span class="menu-icon">
											<span class="svg-icon svg-icon-2">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<path d="M13 5.91517C15.8 6.41517 18 8.81519 18 11.8152C18 12.5152 17.9 13.2152 17.6 13.9152L20.1 15.3152C20.6 15.6152 21.4 15.4152 21.6 14.8152C21.9 13.9152 22.1 12.9152 22.1 11.8152C22.1 7.01519 18.8 3.11521 14.3 2.01521C13.7 1.91521 13.1 2.31521 13.1 3.01521V5.91517H13Z" fill="currentColor" />
													<path opacity="0.3" d="M19.1 17.0152C19.7 17.3152 19.8 18.1152 19.3 18.5152C17.5 20.5152 14.9 21.7152 12 21.7152C9.1 21.7152 6.50001 20.5152 4.70001 18.5152C4.30001 18.0152 4.39999 17.3152 4.89999 17.0152L7.39999 15.6152C8.49999 16.9152 10.2 17.8152 12 17.8152C13.8 17.8152 15.5 17.0152 16.6 15.6152L19.1 17.0152ZM6.39999 13.9151C6.19999 13.2151 6 12.5152 6 11.8152C6 8.81517 8.2 6.41515 11 5.91515V3.01519C11 2.41519 10.4 1.91519 9.79999 2.01519C5.29999 3.01519 2 7.01517 2 11.8152C2 12.8152 2.2 13.8152 2.5 14.8152C2.7 15.4152 3.4 15.7152 4 15.3152L6.39999 13.9151Z" fill="currentColor" />
												</svg>
											</span>
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
											<a class="menu-link <?php if($page == 'supperfil' || $page == 'addperfil' || $page == 'editperfil'){echo 'active';} ?>" href="?page=supperfil&menuid=0">
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
			
						