<?php

	$page = isset($_GET['page']) ? $_GET['page'] : "index";

	require_once("dbcon/config.php");
	require_once("dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
	mysqli_set_charset($con,'utf8');

	$$title = 'Inicio';
	$descripcion = 'Tablero de Control - DASHBOARD';

	if($page == 'supperfil' || $page == 'addsuperperfil' || $page == 'editsuperperfil'){
        $title = 'Administrar Perfil';
		$descripcion = 'Crear/Modificar Datos del Perfil';
	}

	if($page == 'addmenu' || $page == 'editmenu'){
        $title = 'Administrar Menu';
		$descripcion = 'Crear/Modificar Datos del Menu';
	}	
	
	if($page == 'supusuario'){
        $title = 'Administrar Usuarios';
		$descripcion = 'Crear/Modificar Usuarios del Sistema';
	}
	
	if($page == 'editparametro'){
        $title = 'Administrar Parametros';
		$descripcion = 'Modificar Parametros del Sistema';
	}
	if($page == 'editsuperparametro'){
        $title = 'Administrar Parametros';
		$descripcion = 'Modificar Parametros del Sistema';
	}			

	$xSQL = "SELECT * FROM `expert_tarea` WHERE empr_id=$xEmprid AND tare_pagina='$page' ";
	$all_tareas = mysqli_query($con, $xSQL);	
    foreach($all_tareas as $tareas){
        $title = $tareas['tare_titulo'];
		$descripcion = $tareas['tare_descripcion'];
    }		

?>	

				<!--begin::Page title-->
				<div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
					<!--begin::Title-->
					<h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1"><?php echo $title; ?>
					<!--begin::Separator-->
					<span class="h-20px border-1 border-gray-200 border-start ms-3 mx-2 me-1"></span>
					<!--end::Separator-->
					<!--begin::Description-->
					<span class="text-muted fs-7 fw-bold ms-2"><?php echo $descripcion; ?></span>
					<!--end::Description--></h1>
					<!--end::Title-->
				</div>
				<!--end::Page title-->
								