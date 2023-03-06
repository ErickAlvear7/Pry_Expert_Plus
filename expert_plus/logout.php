<?php

    @session_start();

    $_SESSION["s_loged"] = "loged";
    unset($_SESSION["s_loged"]);

	$_SESSION["s_usuario"] = "";
    unset($_SESSION["s_usuario"]);

	$_SESSION["i_usuaid"] = "";
    unset($_SESSION["s_usuario"]);

	$_SESSION["i_paisid"] = "";
    unset($_SESSION["s_usuario"]);

	$_SESSION["i_perfilid"] = "";
    unset($_SESSION["s_usuario"]);

	$_SESSION["i_emprid"] = "";
    unset($_SESSION["s_usuario"]);

	$_SESSION["s_perfdesc"] = "";
    unset($_SESSION["s_usuario"]);

	$_SESSION["s_login"] = "";  
    unset($_SESSION["s_usuario"]);

    @session_unset();
    @session_destroy();
    @session_write_close();
    @setcookie(session_name(),'',0,'/');
    @session_regenerate_id(true);

    header("Location: ingreso.php"); //deberia ser el login
    exit();

?>