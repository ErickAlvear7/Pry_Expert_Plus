<?php

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	  

    $xFechaActual = strftime("%Y-%m-%d %H:%M:%S", time());  

    require_once("../dbcon/config.php");
    require_once("../dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');

    //AGENDA
    $xSQL = "DELETE FROM `expert_agenda` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_agenda` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL);

    //BENEFICIARIOS
    $xSQL = "DELETE FROM `expert_beneficiario` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_beneficiario` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL);

    //CLIENTE
    $xSQL = "DELETE FROM `expert_cliente` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_cliente` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL);    

    $xSQL = "DELETE FROM `expert_configuracion` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_configuracion` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL); 

    $xSQL = "DELETE FROM `expert_especialidad` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_especialidad` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL); 

    $xSQL = "DELETE FROM `expert_grupos` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_grupos` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL);     

    $xSQL = "DELETE FROM `expert_historial_agenda` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_historial_agenda` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL);   

    $xSQL = "DELETE FROM `expert_horarios_profesional` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_horarios_profesional` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL);     

    $xSQL = "DELETE FROM `expert_logs` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_logs` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL); 

    $xSQL = "DELETE FROM `expert_perfil` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_perfil` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL);     

    $xSQL = "INSERT INTO `expert_perfil` (pais_id,empr_id,perf_descripcion,perf_observacion,perf_estado,perf_detalle1,perf_fechacreacion,perf_usuariocreacion,perf_terminalcreacion) ";
    $xSQL .= "VALUES(-1,1,'Super Administrador','Administrador Master','A','Permite el control total, para otorgar permisos a otros administradores','$xFechaActual',1,'Sistemas') ";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_perfil` (pais_id,empr_id,perf_descripcion,perf_observacion,perf_estado,perf_detalle1,perf_fechacreacion,perf_usuariocreacion,perf_terminalcreacion) ";
    $xSQL .= "VALUES(1,1,'Administrador','Perfil para administrar en Ecuador','A','','$xFechaActual',1,'Sistemas') ";
    mysqli_query($con, $xSQL);

    //MENU
    $xSQL = "DELETE FROM `expert_menu` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_menu` AUTO_INCREMENT = 200001";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_menu` (empr_id,mepa_id,menu_orden,menu_descripcion,menu_observacion,menu_estado,menu_icono,fechacreacion,usuariocreacion,terminalcreacion) ";
    $xSQL .= "VALUES(1,-1,1,'Seguridad Master','Menu solo para el usuario Super Administrador, esta opción no la pueden ver nadie mas','A','','$xFechaActual',1,'Sistemas') ";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_menu` (empr_id,mepa_id,menu_orden,menu_descripcion,menu_observacion,menu_estado,menu_icono,fechacreacion,usuariocreacion,terminalcreacion) ";
    $xSQL .= "VALUES(1,-1,2,'Seguridad','Opcion para seguridad de los perfiles de administrador','A','','$xFechaActual',1,'Sistemas') ";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_menu` (empr_id,mepa_id,menu_orden,menu_descripcion,menu_observacion,menu_estado,menu_icono,fechacreacion,usuariocreacion,terminalcreacion) ";
    $xSQL .= "VALUES(1,-1,3,'Configuracion General','Opciones para parametros del sistema','A','','$xFechaActual',1,'Sistemas') ";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_menu` (empr_id,mepa_id,menu_orden,menu_descripcion,menu_observacion,menu_estado,menu_icono,fechacreacion,usuariocreacion,terminalcreacion) ";
    $xSQL .= "VALUES(1,-1,4,'Administrar Prestador','Administrar prestadoras y cliente producto','A','','$xFechaActual',1,'Sistemas') ";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_menu` (empr_id,mepa_id,menu_orden,menu_descripcion,menu_observacion,menu_estado,menu_icono,fechacreacion,usuariocreacion,terminalcreacion) ";
    $xSQL .= "VALUES(1,-1,5,'Administrar Titular','Administracion de titulares, crear, modificar y ag...','A','','$xFechaActual',1,'Sistemas') ";
    mysqli_query($con, $xSQL);

    //TAREA
    $xSQL = "DELETE FROM `expert_tarea` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_tarea` AUTO_INCREMENT = 100001";
    mysqli_query($con, $xSQL);    

    $xSQL = "INSERT INTO `expert_tarea` (empr_id,tare_nombre,tare_pagina,tare_ruta,tare_titulo,tare_descripcion,tare_estado,tare_orden,tare_superadmin,fechacreacion,usuariocreacion,terminalcreacion) ";
    $xSQL .= "VALUES(1,'Menu','supmenu','/../pages/menusuper_admin.php','Administrar Menu','Registro Opciones de Menu','A',2,1,'$xFechaActual',1,'Sistemas') ";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_tarea` (empr_id,tare_nombre,tare_pagina,tare_ruta,tare_titulo,tare_descripcion,tare_estado,tare_orden,tare_superadmin,fechacreacion,usuariocreacion,terminalcreacion) ";
    $xSQL .= "VALUES(1,'Perfil','seg_perfiladmin','/../pages/perfillist_admin.php','Administrar Perfil','Registro Opciones de Perfil','A',3,0,'$xFechaActual',1,'Sistemas') ";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_tarea` (empr_id,tare_nombre,tare_pagina,tare_ruta,tare_titulo,tare_descripcion,tare_estado,tare_orden,tare_superadmin,fechacreacion,usuariocreacion,terminalcreacion) ";
    $xSQL .= "VALUES(1,'Tarea','suptarea','/../pages/tareasuper_admin.php','Administrar Tareas','Registro Opciones de Tarea','A',1,1,'$xFechaActual',1,'Sistemas') ";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_tarea` (empr_id,tare_nombre,tare_pagina,tare_ruta,tare_titulo,tare_descripcion,tare_estado,tare_orden,tare_superadmin,fechacreacion,usuariocreacion,terminalcreacion) ";
    $xSQL .= "VALUES(1,'Usuarios','seg_usuarioadmin','/../pages/usuario_admin.php','Administrar Usuarios','Registro Opciones de Usuario','A',4,0,'$xFechaActual',1,'Sistemas' ) ";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_tarea` (empr_id,tare_nombre,tare_pagina,tare_ruta,tare_titulo,tare_descripcion,tare_estado,tare_orden,tare_superadmin,fechacreacion,usuariocreacion,terminalcreacion) ";
    $xSQL .= "VALUES(1,'Administrar Prestadora','prestador_admin','/../pages/prestadora_admin.php','Administrar Prestadora','Listado de Prestadoras','A',5,0,'$xFechaActual',1,'Sistemas' ) ";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_tarea` (empr_id,tare_nombre,tare_pagina,tare_ruta,tare_titulo,tare_descripcion,tare_estado,tare_orden,tare_superadmin,fechacreacion,usuariocreacion,terminalcreacion) ";
    $xSQL .= "VALUES(1,'Parametros Generales','param_generales','/../pages/parametro_admin.php','Administrar Parametros','Crear/Modificar Parametros del Sistema','A',6,0,'$xFechaActual',1,'Sistemas' ) ";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_tarea` (empr_id,tare_nombre,tare_pagina,tare_ruta,tare_titulo,tare_descripcion,tare_estado,tare_orden,tare_superadmin,fechacreacion,usuariocreacion,terminalcreacion) ";
    $xSQL .= "VALUES(1,'Cliente - Producto','admin_clienteproducto','/../pages/clienteproducto_admin.php','Administrar Clientes y Productos','Lista de Clientes y Productos','A',7,0,'$xFechaActual',1,'Sistemas' ) ";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_tarea` (empr_id,tare_nombre,tare_pagina,tare_ruta,tare_titulo,tare_descripcion,tare_estado,tare_orden,tare_superadmin,fechacreacion,usuariocreacion,terminalcreacion) ";
    $xSQL .= "VALUES(1,'Agendar Cita','agendatitular_admin','/../pages/titularagenda_admin.php','Agendar Citas','Agendamiento de citas','A',8,0,'$xFechaActual',1,'Sistemas' ) ";
    mysqli_query($con, $xSQL);

    //MENU PADRE
    $xSQL = "DELETE FROM `expert_menu_padre` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_menu_padre` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL);

    //MENU TAREA
    $xSQL = "DELETE FROM `expert_menu_tarea` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_menu_tarea` AUTO_INCREMENT = 300001";
    mysqli_query($con, $xSQL);
    
    $xSQL = "INSERT INTO `expert_menu_tarea` (empr_id,menu_id,tare_id,meta_orden) ";
    $xSQL .= "VALUES(1,200002,100002,0 ) ";
    mysqli_query($con, $xSQL);    

    $xSQL = "INSERT INTO `expert_menu_tarea` (empr_id,menu_id,tare_id,meta_orden) ";
    $xSQL .= "VALUES(1,200002,100004,1 ) ";
    mysqli_query($con, $xSQL);    

    $xSQL = "INSERT INTO `expert_menu_tarea` (empr_id,menu_id,tare_id,meta_orden) ";
    $xSQL .= "VALUES(1,200003,100006,0 ) ";
    mysqli_query($con, $xSQL);    

    $xSQL = "INSERT INTO `expert_menu_tarea` (empr_id,menu_id,tare_id,meta_orden) ";
    $xSQL .= "VALUES(1,200004,100005,0 ) ";
    mysqli_query($con, $xSQL);    

    $xSQL = "INSERT INTO `expert_menu_tarea` (empr_id,menu_id,tare_id,meta_orden) ";
    $xSQL .= "VALUES(1,200004,100007,1 ) ";
    mysqli_query($con, $xSQL);    

    $xSQL = "INSERT INTO `expert_menu_tarea` (empr_id,menu_id,tare_id,meta_orden) ";
    $xSQL .= "VALUES(1,200005,100008,1 ) ";
    mysqli_query($con, $xSQL);    

    $xSQL = "DELETE FROM `expert_motivos_especialidad` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_motivos_especialidad` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL);  

    $xSQL = "DELETE FROM `expert_perfil_menu_tarea` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_perfil_menu_tarea` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL);  

    $xSQL = "INSERT INTO `expert_perfil_menu_tarea` (empr_id,meta_id,perf_id,pais_id,meta_estado) ";
    $xSQL .= "VALUES(1,300001,2,1'A' ) ";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_perfil_menu_tarea` (empr_id,meta_id,perf_id,pais_id,meta_estado) ";
    $xSQL .= "VALUES(1,300002,2,1'A' ) ";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_perfil_menu_tarea` (empr_id,meta_id,perf_id,pais_id,meta_estado) ";
    $xSQL .= "VALUES(1,300003,2,1'A' ) ";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_perfil_menu_tarea` (empr_id,meta_id,perf_id,pais_id,meta_estado) ";
    $xSQL .= "VALUES(1,300004,2,1'A' ) ";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_perfil_menu_tarea` (empr_id,meta_id,perf_id,pais_id,meta_estado) ";
    $xSQL .= "VALUES(1,300005,2,1'A' ) ";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_perfil_menu_tarea` (empr_id,meta_id,perf_id,pais_id,meta_estado) ";
    $xSQL .= "VALUES(1,300006,2,1'A' ) ";
    mysqli_query($con, $xSQL);

    $xSQL = "DELETE FROM `expert_persona` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_persona` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL); 
    
    $xSQL = "DELETE FROM `expert_prestadora` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_prestadora` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL); 

    $xSQL = "DELETE FROM `expert_prestadora_especialidad` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_prestadora_especialidad` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL); 

    $xSQL = "DELETE FROM `expert_productos` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_productos` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL); 

    $xSQL = "DELETE FROM `expert_profesional` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_profesional` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL); 

    $xSQL = "DELETE FROM `expert_profesional_especi` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_profesional_especi` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL); 

    $xSQL = "DELETE FROM `expert_reserva` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_reserva` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL);

    $xSQL = "DELETE FROM `expert_reserva_tmp` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_reserva_tmp` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL);

    $xSQL = "DELETE FROM `expert_titular` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_titular` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL);

    $xSQL = "DELETE FROM `expert_usuarios` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_usuarios` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_usuarios` (perf_id,pais_id,empr_id,usua_nombres,usua_apellidos,usua_login,usua_password,usua_estado,usua_contador,usua_caducapass,usua_fechacaduca,usua_cambiarpass,usua_estadologin,usua_terminallogin,usua_avatarlogin,usua_fechacreacion,usua_usuariocreacion,usua_terminalcreacion) ";
    $xSQL .= "VALUES(1,-1,1,'Super','Administrador','superadmin@prestasalud.com','827ccb0eea8a706c4c34a16891f84e7b','A',0,'NO','$xFechaActual','NO','NO','','','$xFechaActual',1,'Sistemas' ) ";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_usuarios` (perf_id,pais_id,empr_id,usua_nombres,usua_apellidos,usua_login,usua_password,usua_estado,usua_contador,usua_caducapass,usua_fechacaduca,usua_cambiarpass,usua_estadologin,usua_terminallogin,usua_avatarlogin,usua_fechacreacion,usua_usuariocreacion,usua_terminalcreacion) ";
    $xSQL .= "VALUES(2,1,1,'Administrador','Sistema','admin@prestasalud.com','827ccb0eea8a706c4c34a16891f84e7b','A',0,'NO','$xFechaActual','NO','NO','','','$xFechaActual',1,'Sistemas' ) ";
    mysqli_query($con, $xSQL);

    $xSQL = "DELETE FROM `expert_parametro_paginas` ";    
    mysqli_query($con, $xSQL);
    $xSQL = "ALTER TABLE `expert_parametro_paginas` AUTO_INCREMENT = 1";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_parametro_paginas` (empr_id,pais_id,usua_id,index_menu,index_content,estado ) ";
    $xSQL .= "VALUES(1,-1,1,'dark','light','A' ) ";
    mysqli_query($con, $xSQL);

    $xSQL = "INSERT INTO `expert_parametro_paginas` (empr_id,pais_id,usua_id,index_menu,index_content,estado ) ";
    $xSQL .= "VALUES(1,1,2,'dark','dark','A' ) ";
    mysqli_query($con, $xSQL);

    
    echo 'PROCESO FINALIZADO..!';



?>