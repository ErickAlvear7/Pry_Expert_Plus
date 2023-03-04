
<?php
	$page = isset($_GET['page']) ? $_GET['page'] : "index";

	require_once("dbcon/config.php");
	require_once("dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

	$xServidor = $_SERVER['HTTP_HOST'];
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());

	//$yUsuaid = $_SESSION["i_usuaid"];	
    //$yEmprid = $_SESSION["i_empre_id"];
    
	$yEmprid = 1;	
	$yUsuaid = 1;

    $xSQL = "SELECT usua_id AS Idusuario, CONCAT(usua_nombres,' ',usua_apellidos) AS Nombres, usua_login AS Log, CASE usua_estado WHEN 'A' THEN 'Activo' ";
	$xSQL .= "ELSE 'Inactivo' END AS Estado, usua_caducapass AS CaducaPass FROM `expert_usuarios` AND perf_id>0";
	$expertusuario = mysqli_query($con, $xSQL);

    $xSQL = "SELECT pais_id AS IdPais, pais_nombre AS Pais, pais_flag AS Bandera FROM `expert_pais` ";
	$xSQL .= " ORDER BY IdPais ";
    $all_pais = mysqli_query($con, $xSQL);

	$xSQL = "SELECT p.perf_descripcion AS Descripcion,p.perf_id AS Codigo FROM `expert_perfil` p ";
	$xSQL .= " WHERE empr_id=$yEmprid AND perf_estado='A' AND pais_id=0 ";
	//$xSQL .= " UNION SELECT '--Seleccione Perfil--',0";
	$xSQL .= " ORDER BY Codigo ";
    $expertperfil = mysqli_query($con, $xSQL);
?>	


		<!--begin::Container-->
		<div id="kt_content_container" class="container-xxl">
			<div class="card mb-5 mb-xxl-8">
				<div class="card-body pt-9 pb-0">
					<div class="d-flex flex-wrap flex-sm-nowrap">
						<div class="flex-grow-1">
							<div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
								<div class="d-flex flex-column">
								</div>
								<div class="d-flex my-0">
									<div class="me-0">
										<button class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
											<i class="bi bi-three-dots fs-3"></i>
										</button>
										<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-kt-menu="true">
											<div class="menu-item px-3">
												<div class="menu-content text-muted pb-2 px-3 fs-7 text-uppercase">Otras Acciones</div>
											</div>
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Accion 1</a>
											</div>
											<div class="menu-item px-3">
												<a href="#" class="menu-link flex-stack px-3">Accion 2
												<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Specify a target name for future usage and reference"></i></a>
											</div>
											<div class="menu-item px-3">
												<a href="#" class="menu-link px-3">Accion 3</a>
											</div>
											<div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-end">
												<a href="#" class="menu-link px-3">
													<span class="menu-title">Configuraciones</span>
													<span class="menu-arrow"></span>
												</a>
												<div class="menu-sub menu-sub-dropdown w-175px py-4">
													<div class="menu-item px-3">
														<a href="#" class="menu-link px-3">Configuracion 1</a>
													</div>
													<div class="menu-item px-3">
														<a href="#" class="menu-link px-3">Configuracion 2</a>
													</div>
													<div class="menu-item px-3">
														<a href="#" class="menu-link px-3">Configuracion 3</a>
													</div>
													<div class="separator my-2"></div>
													<div class="menu-item px-3">
														<div class="menu-content px-3">
															<label class="form-check form-switch form-check-custom form-check-solid">
																<input class="form-check-input w-30px h-20px" type="checkbox" value="1" checked="checked" name="notifications" />
																<span class="form-check-label text-muted fs-6">Accion Cheched</span>
															</label>
														</div>
													</div>
												</div>
											</div>
											<div class="menu-item px-3 my-1">
												<a href="#" class="menu-link px-3">Seteos</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bolder">
						<li class="nav-item mt-2">
							<a class="nav-link text-active-primary ms-0 me-10 py-5" href="?page=suptarea&menuid=0">Tareas</a>
						</li>
						<li class="nav-item mt-2">
							<a class="nav-link text-active-primary ms-0 me-10 py-5 " href="?page=supmenu&menuid=0">Menu</a>
						</li>
						<li class="nav-item mt-2">
							<a class="nav-link text-active-primary ms-0 me-10 py-5" href="?page=supperfil&menuid=0">Perfil</a>
						</li>
						<li class="nav-item mt-2">
							<a class="nav-link text-active-primary ms-0 me-10 py-5 active" href="?page=supusuarios&menuid=0">Usuarios</a>
						</li>
					</ul>
				</div>
			</div>
			<!--begin::hasta aqui la cabecera-->
			<div class="row g-5 g-xxl-8">
                <div class="card card-flush">
                    <div class="card-toolbar">
                        <button class="btn btn-sm btn-light-primary" id="nuevoUsuario">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
                            </svg>
                        </span>
                        Nuevo Usuario</button>
                    </div>
                    <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                        <div class="card-title">
                            <div class="d-flex align-items-center position-relative my-1">
                                <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                        <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                    </svg>
                                </span>
                                <input type="text" data-kt-ecommerce-order-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Buscar Dato" />
                            </div>
                            <div id="kt_ecommerce_report_shipping_export" class="d-none"></div>
                        </div>
                        <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                            <div class="w-150px">
                                <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Estado" data-kt-ecommerce-order-filter="status">
                                    <option></option>
                                    <option value="all">Todos</option>
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_report_shipping_table" style="width: 100%;">
                            <thead>
                                <tr class="text-start text-gray-800 fw-bolder fs-7 gs-0">
                                    <th style="display:none;">Id</th>
                                    <th class="min-w-125px">Usuario</th>
                                    <th class="min-w-125px">Login</th>
                                    <th class="min-w-125px">Estado</th>
                                    <th class="min-w-125px" style="text-align: center;">Reset Password</th>
                                    <th class="min-w-125px" style="text-align: center;">Opciones</th>
                                    <th class="min-w-125px">Status</th>
                                </tr>
                            </thead>
                            <tbody class="fw-bold text-gray-600">
                                <?php 
                                
                                foreach($expertusuario as $usu){
                                    $idusuario = $usu['Idusuario'];
                                    $estado = $usu['Estado'];
                                    $usuario = $usu['Nombres'];
                                ?>
                                <?php 

                                    $cheking = '';
                                    $chkEstado = '';
                                    $xDisabledEdit = '';
                                    $xDisabledReset = '';

                                    if($estado == 'Activo'){
                                        $cheking = 'checked="checked"';
                                        $xTextColor = "badge badge-light-primary";
                                    }else{
                                        $xTextColor = "badge badge-light-danger";
                                    }

                                ?>
                                <tr>
                                    <td style="display:none;"><?php echo $usu['Idusuario'] ?></td>
                                    <td><?php echo $usu['Nombres']; ?></td>
                                    <td><?php echo $usu['Log']; ?></td>
                                    <td id="<?php echo $usu['Idusuario']; ?>">
                                        <div class="<?php echo $xTextColor; ?>"><?php echo $usu['Estado']; ?></div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <div class="btn-group">
                                                <button id="btnReset" onclick="f_ResetPass(<?php echo $idusuario; ?>,<?php echo $yEmprid; ?>)" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" <?php echo $xDisabledReset;?> title='Resetear Password'>
                                                    <i class='fa fa-key'></i>
                                                </button>																															 
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <div class="btn-group">
                                                <button id="btnEditar" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" <?php echo $xDisabledEdit; ?> title='Editar Usuario'>
                                                    <i class='fa fa-edit'></i>
                                                </button>												 
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input <?php echo $cheking; ?> class="form-check-input h-20px w-20px border-primary btnEstado" <?php echo $chkEstado; ?> type="checkbox" id="chk<?php echo $idusuario; ?>" 
                                                    onchange="f_Check(<?php echo $yEmprid; ?>,<?php echo $usu['Idusuario']; ?>)" value="<?php echo $idusuario; ?>"/>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>  
                            </tbody>
                        </table>
                    </div>
                </div>
			</div>
		</div>
		<!--end::Container-->

        <div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <form class="form" id="frm_datos">
                        <div class="modal-header" id="kt_modal_add_customer_header">
                            <h3 class="modal-title" id="modalLabel"></h3>
                            <div class="modal-header pb-0 border-0 justify-content-end">
                                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                                    <span class="svg-icon svg-icon-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                                        </svg>
                                    </span>
                                </div>
                        </div>
                        </div>
                        <div class="modal-body py-10 px-lg-17">
                            <div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_customer_header" data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
                                <div class="fw-bolder fs-3 rotate collapsible mb-7" data-bs-toggle="collapse" href="#kt_modal_add_customer_billing_info" role="button" aria-expanded="false" aria-controls="kt_customer_view_details">Informacion Usuario
                                    <span class="ms-2 rotate-180">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                            </svg>
                                        </span>
                                    </span>
                                </div>
                                <div id="kt_modal_add_customer_billing_info" class="collapse show">
                                    <input class="form-control form-control-solid" type="hidden" id="txtUserid" name="txtUserid" value="0" />
                                    <div class="row g-9 mb-7">
                                        <div class="col-md-6 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                                <span class="required">Nombre</span>
                                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="especifique el nombre del usuario"></i>
                                            </label>
                                            <input type="text" class="form-control form-control-solid" id="txtNombre" name="txtNombre" minlength="5" maxlength="100" placeholder="Ingrese Nombre" value="" />
                                        </div>
                                        <div class="col-md-6 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                                <span class="required">Apellido</span>
                                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="especifique el apellido del usuario"></i>
                                        </label>
                                            <input type="text" class="form-control form-control-solid" id="txtApellido" name="txtApellido" minlength="5" maxlength="100" placeholder="Ingrese Apellido" />
                                        </div>
                                    </div>

                                    <div class="row g-9 mb-7">
                                        <div class="col-md-6 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                                <span class="required">Email</span>
                                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="especifique el nick del usuario"></i>
                                            </label>
                                            <input type="text" class="form-control form-control-solid" id="txtLogin" name="txtLogin" minlength="1" maxlength="100"  placeholder=""  />
                                        </div>
                                        <div class="col-md-6 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                                <span class="required">Password</span>
                                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="especifique la contraseña del usuario"></i>
                                            </label>
                                            <input type="password" class="form-control form-control-solid" id="txtPassword" name="subject" minlength="1" maxlength="100" />
                                        </div>                                        

                                    </div>

                                    <div class="row g-9 mb-7">
                                        <div class="col-md-6 fv-row">
                                            <label class="required fs-6 fw-bold mb-2">Pais</label>
                                            <select id="cboPais" name="cboPais" data-placeholder="Seleccione Pais" class="form-select form-select-solid fw-bolder">
                                                <option value="0">--Seleccione Pais--</option>
                                                <?php foreach ($all_pais as $pais) : 
                                                    
                                                    $flag = ' data-kt-select2-country=' . '"assets/media/flags/' . $pais['Bandera'] . '"';
                                                ?>
                                                    <option value="<?php echo $pais['IdPais']; ?>"<?php echo $flag; ?>><?php echo $pais['Pais']; ?></option>
                                                <?php endforeach ?>						
                                            </select>
                                        </div>

                                        <div class="col-md-6 fv-row">
                                            <label class="required fs-6 fw-bold mb-2">Perfil</label>
                                            <select id="cboPerfil" name="cboPerfil" aria-label="Seleccione Perfil..." data-control="select2" data-placeholder="Seleccione Perfil..." data-dropdown-parent="#kt_modal_add_customer" class="form-select form-select-solid fw-bolder">
                                                <option value="0" selected="selected">--Seleccione Perfil--</option>
                                                <?php foreach ($expertperfil as $per) : ?>
                                                    <option value="<?= $per['Codigo'] ?>"><?= $per['Descripcion'] ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>                                    

                                    <div class="row g-9 mb-7">
                                        <div class="col-md-6 fv-row text-center">
                                            <label class="fs-6 fw-bold mb-2">Passward Caduca</label>
                                        </div>
                                        <div class="col-md-6 fv-row text-center">
                                            <label class="fs-6 fw-bold mb-2">Cambiar Password</label>  
                                        </div>
                                    </div>
                                    <div class="row g-9 mb-7">
                                        <div class="col-md-6 fv-row text-center">
                                            <input class="form-check-input h-20px w-20px border-primary" type="checkbox" id="chkCaducaPass" name="chkCaducaPass" value=""  />
                                            <label class="form-check-label" id="lblCaducaPass">NO</label>
                                        </div>
                                        <div class="col-md-6 fv-row text-center">
                                            <input class="form-check-input h-20px w-20px border border-primary" type="checkbox" id="chkCamPass" name="chkCamPass" value="" />
                                            <label class="form-check-label" id="lblCamPass">NO</label>
                                        </div>
                                    </div>
                                    <div class="row g-9 mb-7">
                                        <div class="col-md-4 fv-row flex-center" id="content" style="display: none;">
                                            <input type="date" class="form-control" id="txtFechacaduca" name="fechacaduca">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer flex-center">
                            <button type="button" id="btnSave" class="btn btn-primary">
                                <span class="indicator-label">Guardar</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>        

        <script>

            $(document).ready(function(){

                var optionFormat = function(item) {
                    if ( !item.id ) {
                        return item.text;
                    }

                    var span = document.createElement('span');
                    var imgUrl = item.element.getAttribute('data-kt-select2-country');
                    var template = '';

                    if(item.id != 0){

                        template += '<img src="' + imgUrl + '" class="rounded-circle h-20px me-2" alt="image"/>';
                    }

                    template += item.text;

                    span.innerHTML = template;			

                    return $(span);
                }

                $('#cboPais').select2({
                    templateSelection: optionFormat,
                    templateResult: optionFormat
                }); 
               
                $("#cboPerfil").val('0').change();
                

                var _emprid,cambiarpass, _estado,caduca,_campass,_nombre,_apellido,_login,_password,_perfil,estado,_caduca,
                _fechacaduca,_cambiarpass,_log,_usu,_dep,_fila,_tipo;

                //abrir-modal-nuevo-usuario
                $("#nuevoUsuario").click(function(){

                    $("#kt_modal_add_customer").modal("show");
                    $(".modal-title").text("Nuevo Usuario");
                    $("#btnSave").text("Guardar");
                    $("#chkCaducaPass").prop("checked", false);
                    $("#lblCaducaPass").text("NO");
                    $("#chkCamPass").prop("checked", false);
                    $("#lblCamPass").text("NO");
                                        
                    $('#txtPassword').prop('readonly', false);
                    $('#content').css('display','none'); 
                    $("#frm_datos").trigger("reset");
                    //$('#cboPerfil').val(null).trigger('change');  

                    _addmod = 'add';

                });

                caduca = 'NO';
                cambiarpass = 'NO';

                $(document).on("click","#chkCaducaPass",function(){
                    
                    element = document.getElementById("content");
                    if($("#chkCaducaPass").is(":checked")){
                        element.style.display='block';
                        $("#lblCaducaPass").text("SI");
                        caduca = 'SI';
                        var now = new Date();
                        var day = ("0" + now.getDate()).slice(-2);
                        var month = ("0" + (now.getMonth() + 1)).slice(-2);
                        var today = now.getFullYear()+"-"+(month)+"-"+(day) ;
                        $('#txtFechacaduca').val(today);
                    }else{
                        element.style.display='none';
                        $("#lblCaducaPass").text("NO");
                        caduca = 'NO';					
                    }

                });

                $(document).on("click","#chkCamPass",function(){

                    if($("#chkCamPass").is(":checked")){
                        $("#lblCamPass").text("SI");
                        cambiarpass = 'SI';
                    }else{
                        $("#lblCamPass").text("NO");
                        cambiarpass = 'NO';
                    }

                });

                //Guardar usuario

                $('#btnSave').click(function(e){
                    e.preventDefault();

                    debugger;
					
                    var _emprid = "<?php echo $yEmprid; ?>";
                    var _usuaid = "<?php echo $yUsuaid; ?>";
                    var _nombre = $.trim($("#txtNombre").val());
                    var _apellido = $.trim($("#txtApellido").val());
                    var _login = $.trim($("#txtLogin").val());
                    var _password = $.trim($("#txtPassword").val());
                    var _paisid = $('#cboPais').val();                    
                    var _perfilid = $('#cboPerfil').val();                    
                    //var _perfilname = $("#cboPerfil option:selected").text();				
                    var _caduca = caduca;
                    var _cambiarpass = cambiarpass;
                    var _fechacaduca = $.trim($("#txtFechacaduca").val());
                    var _buscar = 'SI';
                    var _continuar = 'SI';
                    var _respuesta = 'OK';

                    if(_nombre == ''){                        
                        mensajesweetalert("center","warning","Ingrese Nombre de Usuario",false,1800);
                        return;
                    }

                    if(_login == ''){                        
                        mensajesweetalert("center","warning","Ingrese Login/Email",false,1800);
                        return;
                    }

                    if(_password == ''){                        
                        mensajesweetalert("center","warning","Ingrese Password",false,1800);
                        return;
                    }

                    if(_paisid == '0'){                        
                        mensajesweetalert("center","warning","Seleccione Pais",false,1800);
                        return;
                    }
                    

                    if(_perfilid == '0'){                        
                        mensajesweetalert("center","warning","Seleccione Perfil",false,1800);
                        return;
                    }

                    $parametros = {
                        xxEmprid: _emprid,
                        xxLogin: _login
                    }
                    
                    if(_addmod == 'mod'){
                        if(_loginold != _login){
                            _buscar = 'SI';
                        }else{
                            _buscar = 'NO';
                        }
                    }

                    if(_buscar == 'SI'){
                        var xrespuesta = $.post("codephp/consultar_usuarios.php", $parametros);
                        xrespuesta.done(function(response){
                            if(response == 0){
                                _continuar = 'SI'
                            }else{
                                _continuar = 'NO'
                                mensajesweetalert("center","warning","Nombre del Usuario ya Existe..!",false,1800);
                                return;
                            }
                        });
                    }

                    if(_continuar == 'SI'){
                        
                        if(_addmod == 'add'){
                            $datosUser = {
                                xxEmprid: _emprid,
                                xxNombre: _nombre,
                                xxApellido:_apellido,
                                xxLogin: _login,
                                xxPassword: _password,
                                xxPais: _paisid,
                                xxPerfil: _perfilid,
                                xxCaducaPass: _caduca,
                                xxCambiarPass: _cambiarpass,
                                xxFecha: _fechacaduca
                            }
                            _ulr = "codephp/grabar_usuarios.php";	
                        }else{
                            _userid = $('#txtUserid').val();

                            $datosUser = {
                                xxUserid: _userid,
                                xxNombre: _nombre,
                                xxApellido:_apellido,
                                xxLogin: _login,
                                xxPais: _paisid,
                                xxPerfil: _perfilid,
                                xxCaducaPass: _caduca,
                                xxCambiarPass: _cambiarpass,
                                xxFecha: _fechacaduca
                            }	
                            _ulr = "codephp/actualizar_usuario.php";
                        }

                        $.post(_ulr, $datosUser , function(response){

                            _userid = response;	
                            _usuario = _nombre + ' ' + _apellido;

                            if(_userid != 0){
                                var _estado = '<td><div class="badge badge-light-primary">Activo</div>' ;

                                var _btnreset = '<td><div class="text-center"><div class="btn-group"><button id="btnReset' + _userid + '"' + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Resetear Perfil" >' + 
                                    '<i class="fa fa-key"></i></button></div></div></td>';

                                var _btnedit = '<td><div class="text-center"><div class="btn-group"><button id="btnEditar' + _userid + '"' + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title="Editar Usuario" >' + 
                                    '<i class="fa fa-edit"></i></button></div></div></td>';

                                var _btnchk = '<td style="text-align:center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
                                            '<input class="form-check-input btnEstado" type="checkbox" id="chk' + _userid + '" checked onchange="f_Check(' +
                                            _emprid + ',' + _userid + ')"' + ' value="' + _userid + '"' + '/></div></td>';	
                                            
                                TableData = $('#kt_ecommerce_report_shipping_table').DataTable();

                                TableData.column(0).visible(0);
                                    
                                if(_addmod == 'add'){
                                    TableData.row.add([_userid, _usuario, _login, _estado, _btnreset, _btnedit, _btnchk]).draw();
                                    _detalle = 'Crear Nuevo Usuario';
                                }
                                else{
                                    TableData.row(_fila).data([_userid, _usuario, _login, _estado, _btnreset, _btnedit, _btnchk]).draw();
                                    _detalle = 'Modificar Usuario';
                                } 
                            }else{
                                _detalle = 'Error encontrado en sentecia SQL';
						        _respuesta = 'ERR';                                
                            }

                            $("#kt_modal_add_customer").modal("hide");	
                            
                            if(_respuesta == 'OK'){
                                mensajesweetalert("center","success","Grabado con Exito..!",false,1800);
                            } 
                            
                            /**PARA CREAR REGISTRO DE LOGS */
                            $parametros = {
                                xxPaisid: _paisid,
                                xxEmprid: _emprid,
                                xxUsuaid: _usuaid,
                                xxDetalle: _detalle,
                            }					

                            $.post("codephp/new_log.php", $parametros, function(response){
                                //console.log(response);
                            }); 
                                    

                        });                        
                        
                        // $.ajax({
                        //     url: _ulr,
                        //     type: "POST",
                        //     dataType: "json",
                        //     data: $datosUser,          
                        //     success: function(data){ 
                        //         if(data != 0){

                        //             _userid = data;										
                        //             _usuario = _nombre + ' ' + _apellido;

                        //             var _estado = '<td><div class="badge badge-light-primary">Activo</div>' ;

                        //             var _btnreset = '<td><div class="text-center"><div class="btn-group"><button id="btnReset' + _userid + '"' + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Resetear Perfil" >' + 
                        //                 '<i class="fa fa-key"></i></button></div></div></td>';

                        //             var _btnedit = '<td><div class="text-center"><div class="btn-group"><button id="btnEditar' + _userid + '"' + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title="Editar Usuario" >' + 
                        //                 '<i class="fa fa-edit"></i></button></div></div></td>';

                        //             var _btnchk = '<td style="text-align:center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
                        //                         '<input class="form-check-input btnEstado" type="checkbox" id="chk' + _userid + '" checked onchange="f_Check(' +
                        //                         _emprid + ',' + _userid + ')"' + ' value="' + _userid + '"' + '/></div></td>';	
                                                
                        //             TableData = $('#kt_ecommerce_report_shipping_table').DataTable();

                        //             TableData.column(0).visible(0);
                                        
                        //             if(_addmod == 'add'){
                        //                 TableData.row.add([_userid, _usuario, _login, _estado, _btnreset, _btnedit, _btnchk]).draw();
                        //             }
                        //             else{
                        //                 TableData.row(_fila).data([_userid, _usuario, _login, _estado, _btnreset, _btnedit, _btnchk]).draw();
                        //             } 

                        //             $("#kt_modal_add_customer").modal("hide");									

                        //         }                                                                         
                        //     },
                        //     error: function (error){
                        //         console.log(error);
                        //     }                            
                        // });
                    }

                });

                //editar modal usuario

                $(document).on("click",".btnEditar",function(){
                    
                    var _emprid = "<?php echo $yEmprid; ?>"
                    var _fila = $(this).closest("tr");
                    var _data = $('#kt_ecommerce_report_shipping_table').dataTable().fnGetData(_fila);
                    var _idusu = _data[0];
                    var _loginold = _data[2];
                    var _addmod = 'mod';

                    $('#txtPassword').prop('readonly', true);

                    $parametros = {
                        xxEmprid: _emprid,
                        xxIdUsuario: _idusu
                    }

                    $.ajax({
                        url: "codephp/editar_usuarios.php",
                        type: "POST",
                        dataType: "json",
                        data: $parametros,          
                        success: function(data){ 
                            //console.log(data);
                            //debugger;
                            var _nombres = data[0]['Nombres'];
                            var _apellidos = data[0]['Apellidos'];
                            var _login = data[0]['Login'];
                            var _password = data[0]['Password'];
                            var _cboPerfil = data[0]['CodigoPerfil'];
                            var _passCaduca = data[0]['CaducaPass'];
                            var _fechaCaduca = data[0]['FechaCaduca'];
                            var _cambiarPass = data[0]['CambiarPass'];

                            $("#txtNombre").val(_nombres);
                            $("#txtApellido").val(_apellidos);
                            $("#txtLogin").val(_login);
                            $("#txtPassword").val(_password);
                            $("#cboPerfil").val(_cboPerfil).change();
                            $("#txtFechacaduca").val(_fechaCaduca);

                            if(_passCaduca == 'SI'){
                                $("#chkCaducaPass").prop("checked", true);
                                $("#lblCaducaPass").text("SI");  
                                $('#content').css('display','block');       
                            }else if(_passCaduca == 'NO'){
                                $("#chkCaducaPass").prop("checked", false);
                                $("#lblCaducaPass").text("NO");  
                                $('#content').css('display','none');   

                            }

                            if(_cambiarPass == 'SI'){
                                $("#chkCamPass").prop("checked", true);
                                $("#lblCamPass").text('SI');
                            }else if(_cambiarPass == 'NO'){
                                $("#chkCamPass").prop("checked", false);
                                $("#lblCamPass").text('NO');
                            }
                                                                                                
                        },
                        error: function (error){
                            console.log(error);
                        }                            
                    }); 
                    
                    $(".modal-title").text("Editar Usuario");				
                    $("#btnSave").text("Modificar");
                    $("#txtUserid").val(_idusu);
                    $("#frm_user").trigger("reset");
                    $("#kt_modal_add_customer").modal("show");                    

                });

            });	

            //cambiar estado y desactivar botones en linea

            $(document).on("click",".btnEstado",function(e){
                    _fila = $(this).closest("tr");
                    _usu = $(this).closest("tr").find('td:eq(1)').text();
                    _log = $(this).closest("tr").find('td:eq(2)').text();  
                    _dep = $(this).closest("tr").find('td:eq(4)').text(); 
            });

            //cambiar estado y desactivar botones en linea

            function f_Check(_emprid, _userid){

                let _check = $("#chk" + _userid).is(":checked");
                let _btn = "btnEditar" + _userid;
                let _td = "td" + _userid;
                let _checked = "";
                let _disabled = "";
                let _class = "badge badge-light-primary";
    
    
                if(_check){
                    _tipo = "Activo";
                    _disabled = "";
                    _checked = "checked='checked'";
                }else{
                        _tipo = "Inactivo";
                        _disabled = "disabled";
                        _class = "badge badge-light-danger";
                }
    
                var _lblEstado = '<td><div class="' + _class + '">' + _tipo + ' </div>';
    
                var _btnEdit = '<td><div class="text-center"><div class="btn-group"><button ' +  _disabled +  ' id="btnEditar' + _userid + '"' +
                                'class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title="Editar Usuario">' +
                                '<i class="fa fa-edit"></i></button></div></div></td>';		  
                
                var _btnReset = '<td><div class="text-center"><div class="btn-group"><button ' +  _disabled +  ' id="btnReset"' +
                                ' onclick="f_ResetPass(' +_userid + ',' +_emprid + ')"' + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Resetear Password">' +
                                '<i class="fa fa-key"></i></button></div></div></td>';			

    
                var _btnchk = '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
                            '<input ' + _checked + 'class="form-check-input h-20px w-20px border-primary" type="checkbox" id="chk' + _userid + '"' +
                            'onchange="f_Check(' +_emprid  + ',' + _userid + ')"' + 'value="' + _userid + '"' + '/></div></div></td>';
    
    
    
                TableData = $('#kt_ecommerce_report_shipping_table').DataTable();
    
                TableData.row(_fila).data([_userid,_usu,_log,_lblEstado,_dep,_btnReset,_btnEdit,_btnchk ]).draw();
    
                        $parametros = {
                        xxUsuId: _userid,
                        xxEmpr: _emprid,
                        xxTipo: _tipo
                        }	
    
                var xrespuesta = $.post("codephp/delnew_usuario.php", $parametros);
                xrespuesta.done(function(response){
                    //console.log(response);
                });	
                                    
            }

            //desplazar ventana modal
            $("#kt_modal_add_customer").draggable({
                handle: ".modal-header"
            }); 

            //resetaer password

            function f_ResetPass(idusu,idempr){

                    _idusu= idusu;
                    _idempr= idempr;

                $parametros ={
                    xxUsuId: _idusu,
                    xxEmprId: _idempr
                }

                $.ajax({
                    url: "codephp/reset_password.php",
                    type: "POST",
                    dataType: "json",
                    data: $parametros,          
                    success: function(data){ 
                        //console.log(data);
                        if(data == 'OK'){
                            mensajesweetalert("center","success","password actualizado con exito..!",false,1800);
                        }                                                                         
                    },
                    error: function (error){
                        console.log(error);
                    }                            
                }); 			 
            }
	    </script> 	        