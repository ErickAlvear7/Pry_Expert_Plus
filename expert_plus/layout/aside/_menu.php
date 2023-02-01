<?php
    
	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	

	$page = isset($_GET['page']) ? $_GET['page'] : 'index';

	session_start();

	/*if ($_SESSION["s_usuario"] === null){
	  header("Location: ../logut.php");
	}*/

	//file_put_contents('log_errores.txt', $xNombreusuario . "\n\n", FILE_APPEND);

	require_once("dbcon/config.php");
	require_once("dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xServidor = $_SERVER['HTTP_HOST'];
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());
	//$yUsuaCodigo = $_SESSION["i_codigousuario"];	
	$yUsuaCodigo = 1;

	$xSql = "SELECT (SELECT mpa.mepa_id FROM `expert_menu_padre` mpa WHERE mpa.mepa_id=men.mepa_id) AS CodigoMenuPadre,";
	$xSql .= "(SELECT mpa.mepa_descripcion FROM `expert_menu_padre` mpa WHERE mpa.mepa_id=men.mepa_id) AS MenuPadre," ;
	$xSql .= "(SELECT mpa.mepa_icono FROM `expert_menu_padre` mpa WHERE mpa.mepa_id=men.mepa_id) AS IcoMenuPadre,";
	$xSql .= "men.menu_id AS MenuId,men.menu_descripcion AS Menu,men.menu_icono AS Icono,tar.tare_nombre AS SubMenu,tar.tare_ruta AS Pagina ";
	$xSql .= "FROM `expert_usuarios` usu, `expert_perfil` per, `expert_perfil_menu_tarea` pmt, `expert_menu` men,`expert_menu_tarea` mnt, `expert_tarea` tar ";
	$xSql .= "WHERE usu.perf_id = per.perf_id AND per.perf_id = pmt.perf_id AND pmt.meta_id = mnt.meta_id AND mnt.menu_id = men.menu_id AND ";
	$xSql .= "mnt.tare_id = tar.tare_id AND men.menu_estado='A' AND tar.tare_estado='A' AND USU.usua_id=" . $yUsuaCodigo . " AND men.mepa_id>0 ";
	$xSql .= "ORDER BY men.menu_orden,mnt.meta_orden";

	$all_menupadre = mysqli_query($con, $xSql);


	$xSql = "SELECT (SELECT mpa.mepa_id FROM `expert_menu_padre` mpa WHERE mpa.mepa_id=men.mepa_id) AS CodigoMenuPadre,";
	$xSql .= "(SELECT mpa.mepa_descripcion FROM `expert_menu_padre` mpa WHERE mpa.mepa_id=men.mepa_id) AS MenuPadre," ;
	$xSql .= "(SELECT mpa.mepa_icono FROM `expert_menu_padre` mpa WHERE mpa.mepa_id=men.mepa_id) AS IcoMenuPadre,";
	$xSql .= "men.menu_id AS MenuId,men.menu_descripcion AS Menu,men.menu_icono AS Icono,tar.tare_nombre AS SubMenu,tar.tare_ruta AS Pagina ";
	$xSql .= "FROM `expert_usuarios` usu, `expert_perfil` per, `expert_perfil_menu_tarea` pmt, `expert_menu` men,`expert_menu_tarea` mnt, `expert_tarea` tar ";
	$xSql .= "WHERE usu.perf_id = per.perf_id AND per.perf_id = pmt.perf_id AND pmt.meta_id = mnt.meta_id AND mnt.menu_id = men.menu_id AND ";
	$xSql .= "mnt.tare_id = tar.tare_id AND men.menu_estado='A' AND tar.tare_estado='A' AND USU.usua_id=" . $yUsuaCodigo;
	$xSql .= " ORDER BY men.menu_orden,mnt.meta_orden";

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
												echo "<div data-kt-menu-trigger='click' class='menu-item here show menu-accordion'>";
													echo "<span class='menu-link'>";
														// echo "<span class='menu-icon'>";
														// 	echo "<span class='svg-icon svg-icon-2'>";
														// 		echo "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'>";																	
														// 		echo "</svg>";
														// 	echo "</span>";
														// echo "</span>";
														echo "<span class='menu-title'>" . $menurow['Menu'] . "</span>";
														echo "<span class='menu-arrow'></span>";
													echo "</span>";
													echo "<div class='menu-sub menu-sub-accordion menu-active-bg'>";
														echo "<div class='menu-item'>";
														$xActivo = '';
														foreach ($all_menu as $submenu){
															$xPagina = $submenu["Pagina"];
															if($page == $xPagina){
																$xActivo = 'active';
															}
															if ($submenu["MenuId"] == $menurow["MenuId"]){
																echo "<a class='menu-link " . $xActivo . "' ";
																	echo "href=" . "'?page=" . $xPagina . "'> ";
																	echo "<span class='menu-bullet'> ";
																		echo "<span class='bullet bullet-dot'></span> ";
																	echo "</span>";
																	echo "<span class='menu-title'>" . $submenu["SubMenu"] . "</span> ";
																echo "</a>";
															}
														}												
														echo "</div>";
													echo "</div>";
												echo "</div>";
											}
										}else{

										}
										$tempmenu = $menurow['MenuId'];
										$menusuperior = $menurow['CodigoMenuPadre'];
									}
								 ?>
								 <div data-kt-menu-trigger="click" class="menu-item here show menu-accordion">
									<span class="menu-link">
										<span class="menu-title">Seguridad</span>
										<span class="menu-arrow"></span>
									</span>
									<div class="menu-sub menu-sub-accordion menu-active-bg">
										<div class="menu-item">
											<a class="menu-link <?php if($page == 'usr_usuariorol') { echo 'active';} ?>" href="?page=usr_usuariorol">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Usuarios/Rol</span>
											</a>
										</div>
									</div>
								</div>

								<!-- <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
									<span class="menu-link">

										<span class="menu-title">Pages</span>
										<span class="menu-arrow"></span>
									</span>
									<div class="menu-sub menu-sub-accordion menu-active-bg">
										<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
											<span class="menu-link">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">User Profile</span>
												<span class="menu-arrow"></span>
											</span>
											<div class="menu-sub menu-sub-accordion menu-active-bg">
												<div class="menu-item">
													<a class="menu-link" href="?page=pages/user-profile/overview">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Overview</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=pages/user-profile/projects">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Projects</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=pages/user-profile/campaigns">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Campaigns</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=pages/user-profile/documents">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Documents</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=pages/user-profile/followers">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Followers</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=pages/user-profile/activity">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Activity</span>
													</a>
												</div>
											</div>
										</div>
										<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
											<span class="menu-link">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Blog</span>
												<span class="menu-arrow"></span>
											</span>
											<div class="menu-sub menu-sub-accordion menu-active-bg">
												<div class="menu-item">
													<a class="menu-link" href="?page=pages/blog/home">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Blog Home</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=pages/blog/post">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Blog Post</span>
													</a>
												</div>
											</div>
										</div>
										<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
											<span class="menu-link">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Pricing</span>
												<span class="menu-arrow"></span>
											</span>
											<div class="menu-sub menu-sub-accordion menu-active-bg">
												<div class="menu-item">
													<a class="menu-link" href="?page=pages/pricing/pricing-1">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Pricing 1</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=pages/pricing/pricing-2">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Pricing 2</span>
													</a>
												</div>
											</div>
										</div>
										<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
											<span class="menu-link">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Careers</span>
												<span class="menu-arrow"></span>
											</span>
											<div class="menu-sub menu-sub-accordion menu-active-bg">
												<div class="menu-item">
													<a class="menu-link" href="?page=pages/careers/list">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Careers List</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=pages/careers/apply">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Careers Apply</span>
													</a>
												</div>
											</div>
										</div>
										<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
											<span class="menu-link">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">FAQ</span>
												<span class="menu-arrow"></span>
											</span>
											<div class="menu-sub menu-sub-accordion menu-active-bg">
												<div class="menu-item">
													<a class="menu-link" href="?page=pages/faq/classic">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Classic</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=pages/faq/extended">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Extended</span>
													</a>
												</div>
											</div>
										</div>
										<div class="menu-item">
											<a class="menu-link" href="?page=pages/about">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">About Us</span>
											</a>
										</div>
										<div class="menu-item">
											<a class="menu-link" href="?page=pages/contact">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Contact Us</span>
											</a>
										</div>
										<div class="menu-item">
											<a class="menu-link" href="?page=pages/team">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Our Team</span>
											</a>
										</div>
										<div class="menu-item">
											<a class="menu-link" href="?page=pages/licenses">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Licenses</span>
											</a>
										</div>
										<div class="menu-item">
											<a class="menu-link" href="?page=pages/sitemap">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Sitemap</span>
											</a>
										</div>
									</div>
								</div> -->


								<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
									<span class="menu-link">
										<span class="menu-icon">
											<!--begin::Svg Icon | path: icons/duotune/technology/teh004.svg-->
											<span class="svg-icon svg-icon-2">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<path opacity="0.3" d="M21 10.7192H3C2.4 10.7192 2 11.1192 2 11.7192C2 12.3192 2.4 12.7192 3 12.7192H6V14.7192C6 18.0192 8.7 20.7192 12 20.7192C15.3 20.7192 18 18.0192 18 14.7192V12.7192H21C21.6 12.7192 22 12.3192 22 11.7192C22 11.1192 21.6 10.7192 21 10.7192Z" fill="currentColor" />
													<path d="M11.6 21.9192C11.4 21.9192 11.2 21.8192 11 21.7192C10.6 21.4192 10.5 20.7191 10.8 20.3191C11.7 19.1191 12.3 17.8191 12.7 16.3191C12.8 15.8191 13.4 15.4192 13.9 15.6192C14.4 15.7192 14.8 16.3191 14.6 16.8191C14.2 18.5191 13.4 20.1192 12.4 21.5192C12.2 21.7192 11.9 21.9192 11.6 21.9192ZM8.7 19.7192C10.2 18.1192 11 15.9192 11 13.7192V8.71917C11 8.11917 11.4 7.71917 12 7.71917C12.6 7.71917 13 8.11917 13 8.71917V13.0192C13 13.6192 13.4 14.0192 14 14.0192C14.6 14.0192 15 13.6192 15 13.0192V8.71917C15 7.01917 13.7 5.71917 12 5.71917C10.3 5.71917 9 7.01917 9 8.71917V13.7192C9 15.4192 8.4 17.1191 7.2 18.3191C6.8 18.7191 6.9 19.3192 7.3 19.7192C7.5 19.9192 7.7 20.0192 8 20.0192C8.3 20.0192 8.5 19.9192 8.7 19.7192ZM6 16.7192C6.5 16.7192 7 16.2192 7 15.7192V8.71917C7 8.11917 7.1 7.51918 7.3 6.91918C7.5 6.41918 7.2 5.8192 6.7 5.6192C6.2 5.4192 5.59999 5.71917 5.39999 6.21917C5.09999 7.01917 5 7.81917 5 8.71917V15.7192V15.8191C5 16.3191 5.5 16.7192 6 16.7192ZM9 4.71917C9.5 4.31917 10.1 4.11918 10.7 3.91918C11.2 3.81918 11.5 3.21917 11.4 2.71917C11.3 2.21917 10.7 1.91916 10.2 2.01916C9.4 2.21916 8.59999 2.6192 7.89999 3.1192C7.49999 3.4192 7.4 4.11916 7.7 4.51916C7.9 4.81916 8.2 4.91918 8.5 4.91918C8.6 4.91918 8.8 4.81917 9 4.71917ZM18.2 18.9192C18.7 17.2192 19 15.5192 19 13.7192V8.71917C19 5.71917 17.1 3.1192 14.3 2.1192C13.8 1.9192 13.2 2.21917 13 2.71917C12.8 3.21917 13.1 3.81916 13.6 4.01916C15.6 4.71916 17 6.61917 17 8.71917V13.7192C17 15.3192 16.8 16.8191 16.3 18.3191C16.1 18.8191 16.4 19.4192 16.9 19.6192C17 19.6192 17.1 19.6192 17.2 19.6192C17.7 19.6192 18 19.3192 18.2 18.9192Z" fill="currentColor" />
												</svg>
											</span>
											<!--end::Svg Icon-->
										</span>
										<span class="menu-title">Authentication</span>
										<span class="menu-arrow"></span>
									</span>
									<div class="menu-sub menu-sub-accordion menu-active-bg">
										<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
											<span class="menu-link">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Basic Layout</span>
												<span class="menu-arrow"></span>
											</span>
											<div class="menu-sub menu-sub-accordion menu-active-bg">
												<div class="menu-item">
													<a class="menu-link" href="?page=authentication/layouts/basic/sign-in">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Sign-in</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=authentication/layouts/basic/sign-up">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Sign-up</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=authentication/layouts/basic/two-steps">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Two-steps</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=authentication/layouts/basic/password-reset">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Password Reset</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=authentication/layouts/basic/new-password">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">New Password</span>
													</a>
												</div>
											</div>
										</div>
										<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
											<span class="menu-link">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Aside Layout</span>
												<span class="menu-arrow"></span>
											</span>
											<div class="menu-sub menu-sub-accordion menu-active-bg">
												<div class="menu-item">
													<a class="menu-link" href="?page=authentication/layouts/aside/sign-in">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Sign-in</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=authentication/layouts/aside/sign-up">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Sign-up</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=authentication/layouts/aside/two-steps">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Two-steps</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=authentication/layouts/aside/password-reset">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Password Reset</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=authentication/layouts/aside/new-password">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">New Password</span>
													</a>
												</div>
											</div>
										</div>
										<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
											<span class="menu-link">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Dark Layout</span>
												<span class="menu-arrow"></span>
											</span>
											<div class="menu-sub menu-sub-accordion menu-active-bg">
												<div class="menu-item">
													<a class="menu-link" href="?page=authentication/layouts/dark/sign-in">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Sign-in</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=authentication/layouts/dark/sign-up">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Sign-up</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=authentication/layouts/dark/two-steps">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Two-steps</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=authentication/layouts/dark/password-reset">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Password Reset</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=authentication/layouts/dark/new-password">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">New Password</span>
													</a>
												</div>
											</div>
										</div>
										<div class="menu-item">
											<a class="menu-link" href="?page=authentication/extended/multi-steps-sign-up">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Multi-steps Sign-up</span>
											</a>
										</div>
										<div class="menu-item">
											<a class="menu-link" href="?page=authentication/extended/two-factor-authentication">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Two Factor Auth</span>
											</a>
										</div>
										<div class="menu-item">
											<a class="menu-link" href="?page=authentication/extended/free-trial-sign-up">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Free Trial Sign-up</span>
											</a>
										</div>
										<div class="menu-item">
											<a class="menu-link" href="?page=authentication/extended/coming-soon">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Coming Soon</span>
											</a>
										</div>
										<div class="menu-item">
											<a class="menu-link" href="?page=authentication/general/welcome">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Welcome Message</span>
											</a>
										</div>
										<div class="menu-item">
											<a class="menu-link" href="?page=authentication/general/verify-email">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Verify Email</span>
											</a>
										</div>
										<div class="menu-item">
											<a class="menu-link" href="?page=authentication/general/password-confirmation">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Password Confirmation</span>
											</a>
										</div>
										<div class="menu-item">
											<a class="menu-link" href="?page=authentication/general/deactivation">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Account Deactivation</span>
											</a>
										</div>
										<div class="menu-item">
											<a class="menu-link" href="?page=authentication/general/error-404">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Error 404</span>
											</a>
										</div>
										<div class="menu-item">
											<a class="menu-link" href="?page=authentication/general/error-500">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Error 500</span>
											</a>
										</div>
										<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
											<span class="menu-link">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Email Templates</span>
												<span class="menu-arrow"></span>
											</span>
											<div class="menu-sub menu-sub-accordion menu-active-bg">
												<div class="menu-item">
													<a class="menu-link" href="?page=authentication/email/verify-email" target="blank">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Verify Email</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=authentication/email/invitation" target="blank">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Account Invitation</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=authentication/email/password-reset" target="blank">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Password Reset</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=authentication/email/password-change" target="blank">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Password Changed</span>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
									<span class="menu-link">
										<span class="menu-icon">
											<!--begin::Svg Icon | path: icons/duotune/art/art009.svg-->
											<span class="svg-icon svg-icon-2">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<path opacity="0.3" d="M21 18.3V4H20H5C4.4 4 4 4.4 4 5V20C10.9 20 16.7 15.6 19 9.5V18.3C18.4 18.6 18 19.3 18 20C18 21.1 18.9 22 20 22C21.1 22 22 21.1 22 20C22 19.3 21.6 18.6 21 18.3Z" fill="currentColor" />
													<path d="M22 4C22 2.9 21.1 2 20 2C18.9 2 18 2.9 18 4C18 4.7 18.4 5.29995 18.9 5.69995C18.1 12.6 12.6 18.2 5.70001 18.9C5.30001 18.4 4.7 18 4 18C2.9 18 2 18.9 2 20C2 21.1 2.9 22 4 22C4.8 22 5.39999 21.6 5.79999 20.9C13.8 20.1 20.1 13.7 20.9 5.80005C21.6 5.40005 22 4.8 22 4Z" fill="currentColor" />
												</svg>
											</span>
											<!--end::Svg Icon-->
										</span>
										<span class="menu-title">Utilities</span>
										<span class="menu-arrow"></span>
									</span>
									<div class="menu-sub menu-sub-accordion menu-active-bg">
										<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
											<span class="menu-link">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Modals</span>
												<span class="menu-arrow"></span>
											</span>
											<div class="menu-sub menu-sub-accordion menu-active-bg">
												<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
													<span class="menu-link">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">General</span>
														<span class="menu-arrow"></span>
													</span>
													<div class="menu-sub menu-sub-accordion menu-active-bg">
														<div class="menu-item">
															<a class="menu-link" href="?page=utilities/modals/general/invite-friends">
																<span class="menu-bullet">
																	<span class="bullet bullet-dot"></span>
																</span>
																<span class="menu-title">Invite Friends</span>
															</a>
														</div>
														<div class="menu-item">
															<a class="menu-link" href="?page=utilities/modals/general/view-users">
																<span class="menu-bullet">
																	<span class="bullet bullet-dot"></span>
																</span>
																<span class="menu-title">View Users</span>
															</a>
														</div>
														<div class="menu-item">
															<a class="menu-link" href="?page=utilities/modals/general/select-users">
																<span class="menu-bullet">
																	<span class="bullet bullet-dot"></span>
																</span>
																<span class="menu-title">Select Users</span>
															</a>
														</div>
														<div class="menu-item">
															<a class="menu-link" href="?page=utilities/modals/general/upgrade-plan">
																<span class="menu-bullet">
																	<span class="bullet bullet-dot"></span>
																</span>
																<span class="menu-title">Upgrade Plan</span>
															</a>
														</div>
														<div class="menu-item">
															<a class="menu-link" href="?page=utilities/modals/general/share-earn">
																<span class="menu-bullet">
																	<span class="bullet bullet-dot"></span>
																</span>
																<span class="menu-title">Share &amp; Earn</span>
															</a>
														</div>
													</div>
												</div>
												<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
													<span class="menu-link">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Forms</span>
														<span class="menu-arrow"></span>
													</span>
													<div class="menu-sub menu-sub-accordion menu-active-bg">
														<div class="menu-item">
															<a class="menu-link" href="?page=utilities/modals/forms/new-target">
																<span class="menu-bullet">
																	<span class="bullet bullet-dot"></span>
																</span>
																<span class="menu-title">New Target</span>
															</a>
														</div>
														<div class="menu-item">
															<a class="menu-link" href="?page=utilities/modals/forms/new-card">
																<span class="menu-bullet">
																	<span class="bullet bullet-dot"></span>
																</span>
																<span class="menu-title">New Card</span>
															</a>
														</div>
														<div class="menu-item">
															<a class="menu-link" href="?page=utilities/modals/forms/new-address">
																<span class="menu-bullet">
																	<span class="bullet bullet-dot"></span>
																</span>
																<span class="menu-title">New Address</span>
															</a>
														</div>
														<div class="menu-item">
															<a class="menu-link" href="?page=utilities/modals/forms/create-api-key">
																<span class="menu-bullet">
																	<span class="bullet bullet-dot"></span>
																</span>
																<span class="menu-title">Create API Key</span>
															</a>
														</div>
														<div class="menu-item">
															<a class="menu-link" href="?page=utilities/modals/forms/bidding">
																<span class="menu-bullet">
																	<span class="bullet bullet-dot"></span>
																</span>
																<span class="menu-title">Bidding</span>
															</a>
														</div>
													</div>
												</div>
												<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
													<span class="menu-link">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Wizards</span>
														<span class="menu-arrow"></span>
													</span>
													<div class="menu-sub menu-sub-accordion menu-active-bg">
														<div class="menu-item">
															<a class="menu-link" href="?page=utilities/modals/wizards/create-app">
																<span class="menu-bullet">
																	<span class="bullet bullet-dot"></span>
																</span>
																<span class="menu-title">Create App</span>
															</a>
														</div>
														<div class="menu-item">
															<a class="menu-link" href="?page=utilities/modals/wizards/create-campaign">
																<span class="menu-bullet">
																	<span class="bullet bullet-dot"></span>
																</span>
																<span class="menu-title">Create Campaign</span>
															</a>
														</div>
														<div class="menu-item">
															<a class="menu-link" href="?page=utilities/modals/wizards/create-account">
																<span class="menu-bullet">
																	<span class="bullet bullet-dot"></span>
																</span>
																<span class="menu-title">Create Business Acc</span>
															</a>
														</div>
														<div class="menu-item">
															<a class="menu-link" href="?page=utilities/modals/wizards/create-project">
																<span class="menu-bullet">
																	<span class="bullet bullet-dot"></span>
																</span>
																<span class="menu-title">Create Project</span>
															</a>
														</div>
														<div class="menu-item">
															<a class="menu-link" href="?page=utilities/modals/wizards/top-up-wallet">
																<span class="menu-bullet">
																	<span class="bullet bullet-dot"></span>
																</span>
																<span class="menu-title">Top Up Wallet</span>
															</a>
														</div>
														<div class="menu-item">
															<a class="menu-link" href="?page=utilities/modals/wizards/offer-a-deal">
																<span class="menu-bullet">
																	<span class="bullet bullet-dot"></span>
																</span>
																<span class="menu-title">Offer a Deal</span>
															</a>
														</div>
														<div class="menu-item">
															<a class="menu-link" href="?page=utilities/modals/wizards/two-factor-authentication">
																<span class="menu-bullet">
																	<span class="bullet bullet-dot"></span>
																</span>
																<span class="menu-title">Two Factor Auth</span>
															</a>
														</div>
													</div>
												</div>
												<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
													<span class="menu-link">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Search</span>
														<span class="menu-arrow"></span>
													</span>
													<div class="menu-sub menu-sub-accordion menu-active-bg">
														<div class="menu-item">
															<a class="menu-link" href="?page=utilities/modals/search/users">
																<span class="menu-bullet">
																	<span class="bullet bullet-dot"></span>
																</span>
																<span class="menu-title">Users</span>
															</a>
														</div>
														<div class="menu-item">
															<a class="menu-link" href="?page=utilities/modals/search/select-location">
																<span class="menu-bullet">
																	<span class="bullet bullet-dot"></span>
																</span>
																<span class="menu-title">Select Location</span>
															</a>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
											<span class="menu-link">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Search</span>
												<span class="menu-arrow"></span>
											</span>
											<div class="menu-sub menu-sub-accordion menu-active-bg">
												<div class="menu-item">
													<a class="menu-link" href="?page=utilities/search/horizontal">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Horizontal</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=utilities/search/vertical">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Vertical</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=utilities/search/users">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Users</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=utilities/search/select-location">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Location</span>
													</a>
												</div>
											</div>
										</div>
										<div data-kt-menu-trigger="click" class="menu-item menu-accordion">
											<span class="menu-link">
												<span class="menu-bullet">
													<span class="bullet bullet-dot"></span>
												</span>
												<span class="menu-title">Wizards</span>
												<span class="menu-arrow"></span>
											</span>
											<div class="menu-sub menu-sub-accordion menu-active-bg">
												<div class="menu-item">
													<a class="menu-link" href="?page=utilities/wizards/horizontal">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Horizontal</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=utilities/wizards/vertical">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Vertical</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=utilities/wizards/two-factor-authentication">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Two Factor Auth</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=utilities/wizards/create-app">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Create App</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=utilities/wizards/create-campaign">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Create Campaign</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=utilities/wizards/create-account">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Create Account</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=utilities/wizards/create-project">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Create Project</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=utilities/modals/wizards/top-up-wallet">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Top Up Wallet</span>
													</a>
												</div>
												<div class="menu-item">
													<a class="menu-link" href="?page=utilities/wizards/offer-a-deal">
														<span class="menu-bullet">
															<span class="bullet bullet-dot"></span>
														</span>
														<span class="menu-title">Offer a Deal</span>
													</a>
												</div>
											</div>
										</div>
									</div>
								</div>

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
							<!--end::Menu-->
						</div>
						<!--end::Aside Menu-->
						