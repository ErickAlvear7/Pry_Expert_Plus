<?php

	$page = isset($_GET['page']) ? $_GET['page'] : "index";

	$title = 'Dashboard';
	$descripcion = 'Sistema Control de Agendamientos';

	if($page == 'index'){
		$title = 'Inicio';
		$descripcion = 'Tablero de Control - DASHBOARD';
	}else if($page == 'seg_menuadmin'){
		$title = 'REGISTRO DE MENU';
		$descripcion = 'Menú de Opciones';
	}else if($page == 'seg_perfiladmin'){
		$title = 'Administrar Perfil';
		$descripcion = 'Registrar Perfil (Opciones de menú y submenu para el perfil)';
	}else if($page == 'addmenu'){
		$title = 'Crear Menu';
		$descripcion = 'Ingrese Datos para crear opción menú';
	}else if($page == 'addperfil'){
		$title = 'Crear Perfil';
		$descripcion = 'Ingrese Datos para crear opción perfil';
	}else if($page == 'editperfil'){
		$title = 'EDITAR PERFIL';
		$descripcion = 'Edición Datos del Perfil';
	}else if($page =='editmenu'){
		$title = 'EDITAR MENU';
		$descripcion = 'Edición Datos del Menu';
	}else if($page=='seg_usuarioadmin'){
        $title = 'Administrar Usuarios';
		$descripcion = 'Datos del Usuario';
	}else if($page == 'seg_tareadmin'){
        $title = 'Administrar Tareas';
		$descripcion = 'Registro de Tareas';
	}else if($page == 'suptarea'){
		$title = 'Administrar Tareas';
		$descripcion = 'Registro de Tareas';
	}else if($page == 'supmenu'){
		$title = 'Administrar Menu';
		$descripcion = 'Registro Opciones de Menú';
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
								