<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    $yEmprid = $_POST['Emprid'];
    $xPerfil = $_POST['Perfil'];

    $xSql = "SELECT * FROM `expert_perfil` WHERE per.empr_id=" . $yEmprid . " AND per.perf_descripcion='" . $xPerfil . "'";

    $all_perfiles = mysqli_query($con, $xSql);
    foreach ($all_perfiles as $perfil){
        $xName = $perfil["Perfil"];
    }
	
?>	