										
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
			<a href="#" class="btn btn-icon btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end" data-kt-menu-flip="bottom">
				<?php if($xMode == 'light') { ?> 
					<i class="fonticon-sun fs-2"></i>
				<?php }else { ?>
					<i class="fonticon-moon fs-2"></i>
				<?php } ?>
			</a>


<?php include 'partials/theme-mode/__menu.php' ?>										