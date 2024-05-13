<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	
    
    $page = isset($_GET['page']) ? $_GET['page'] : "index";
    
    //file_put_contents('log_seguimiento.txt', $page . "\n\n", FILE_APPEND);

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

	$xUsuaid = $_SESSION["i_usuaid"];	
    $xPaisid = $_SESSION["i_paisid"];	
    $xEmprid = $_SESSION["i_emprid"];	

	$mode = 'dark';
	require_once("./dbcon/config.php");
	
	$log_file = "error_conexion";

	$xSQL = "SELECT * FROM `expert_parametro_paginas` WHERE empr_id=$xEmprid AND usua_id=$xUsuaid AND estado='A'";
	$all_datos = mysqli_query($con, $xSQL) or die (error_log(mysqli_error($con), 3, $log_file));	

	if(mysqli_num_rows($all_datos)>0) {
		foreach ($all_datos as $datos){ 
			$mode = $datos['index_content'];
		}
	}	
	
?>


<!DOCTYPE html>
<!--
Author: Keenthemes
Product Name: Metronic - Bootstrap 5 HTML, VueJS, React, Angular & Laravel Admin Dashboard Theme
Purchase: https://1.envato.market/EA4JP
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">
	<!--begin::Head-->
	<head><base href="">
		<title>Sistema de Agendamientos (Expert Extendent)</title>
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
		<link rel="shortcut icon" href="assets/media/logos/LogoPresta.ico" />
		<!--begin::Fonts-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
		<!--end::Fonts-->
		<!--begin::Page Vendor Stylesheets(used by this page)-->
		<link href="assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
		<!--end::Page Vendor Stylesheets-->
		<!--begin::Global Stylesheets Bundle(used by all pages)-->
		<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		
		<link href="assets/sweetalert2/css/sweetalert2.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/alertify/css/alertify.min.css" rel="stylesheet" type="text/css" />
		
		<?php
			if($mode == 'dark'){
		?>
			<link href="assets/plugins/global/plugins.dark.bundle.css" rel="stylesheet" type="text/css" />
			<link href="assets/css/style.dark.bundle.css" rel="stylesheet" type="text/css" />		
		<?php	
			}else{
		?>
			<link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />			
			<link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
		<?php
			}
		?>	

		<!--end::Global Stylesheets Bundle-->
		<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="https://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>		

		

	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">

<?php include 'layout/master.php' ?>


<?php include 'partials/engage/_main.php' ?>


<?php include 'partials/_scrolltop.php' ?>

		<!--begin::Modals-->

<?php include 'partials/modals/_upgrade-plan.php' ?>


<?php include 'partials/modals/create-app/_main.php' ?>


<?php include 'partials/modals/_invite-friends.php' ?>


<?php include 'partials/modals/users-search/_main.php' ?>

		<script>var hostUrl = "assets/";</script>

		<!--PARA TODAS LAS PAGINAS-->
		<script src="assets/plugins/global/plugins.bundle.js"></script>
		<script src="assets/js/scripts.bundle.js"></script>

		<script src="assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
		<script src="assets/plugins/custom/datatables/datatables.bundle.js"></script>

		<!--begin::Page USUARIOS-->
        <script src="assets/js/custom/apps/user-management/users/list/table.js"></script>

        <script src="assets/js/custom/apps/ecommerce/customers/listing/listing.js"></script>
		<script src="assets/js/custom/apps/contacts/edit-contact.js"></script>
		<script src="assets/js/custom/apps/ecommerce/reports/shipping/shipping.js"></script>

		<!--begin::Page PRESTADORAS-->
		<script src="assets/js/custom/apps/ecommerce/catalog/products.js"></script>
		
		<script src="assets/js/widgets.bundle.js"></script>
		<script src="assets/js/custom/widgets.js"></script>
		<script src="assets/js/custom/apps/chat/chat.js"></script>		
		<script src="assets/js/custom/intro.js"></script>
		<script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
		<script src="assets/js/custom/utilities/modals/users-search.js"></script>
		<script src="assets/js/custom/utilities/modals/create-app.js"></script>

		<!--JS ADICIONALES-->
		<script src="assets/sweetalert2/js/sweetalert2.min.js"></script>
		<script src="assets/alertify/js/alertify.min.js"></script>
		<script src="assets/redirect/js/redirect.js"></script>		
		<script src="assets/js/funciones.js"></script>

		<?php if($page == 'supusuario') { ?>

			<script src="assets/js/custom/apps/user-management/users/list/add.js"></script>
		
		<?php } ?>

		<?php if($page == 'seg_perfiladmin') { ?>

			<script src="assets/js/custom/apps/user-management/roles/list/add.js"></script>
			<script src="assets/js/custom/apps/user-management/roles/list/update-role.js"></script>     	

		<?php } ?>

		<?php if($page == 'addtitular') { ?>
			<script src="assets/js/custom/apps/ecommerce/sales/save-order.js"></script>
		<?php } ?>
		
	</body>
	<!--end::Body-->
</html>

