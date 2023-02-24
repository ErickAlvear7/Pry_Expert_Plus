<?php
	

	require_once("dbcon/config.php");
	require_once("dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
	mysqli_set_charset($con,'utf8');	

	$xServidor = $_SERVER['HTTP_HOST'];
	$xFecha = strftime("%Y-%m-%d %H:%M:%S", time());

	$yEmprid = 1;

    $xSQL = "SELECT usua_id AS Idusuario, CONCAT(usua_nombres,' ',usua_apellidos) AS Nombres, usua_login AS Log, CASE usua_estado WHEN 'A' THEN 'Activo' ";
	$xSQL .= "ELSE 'Inactivo' END AS Estado, usua_caducapass AS CaducaPass FROM `expert_usuarios`";
	$expertusuario = mysqli_query($con, $xSQL);

	$xSQL = "SELECT p.perf_descripcion AS Descripcion,p.perf_id AS Codigo FROM `expert_perfil` p ";
	$xSQL .= " WHERE empr_id= $yEmprid AND perf_estado = 'A' ";
	$xSQL .= " UNION SELECT '  --Seleccione Perfil--  ',0";
	$xSQL .= " ORDER BY Codigo ";
    $expertperfil = mysqli_query($con, $xSQL);

?>

<div id="kt_content_container" class="container-xxl">
    <input type="hidden" id="idempr" value="<?php echo $yEmprid; ?>"  />
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
						<th class="min-w-125px">Departamento</th>
						<th class="min-w-125px" style="text-align: center;">Reset Password</th>
						<th class="min-w-125px" style="text-align: center;">Opciones</th>
						<th class="min-w-125px">Estado</th>
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

						if($usuario == 'Administrador Principal'){
							$chkEstado = 'disabled';
							$xDisabledEdit = 'disabled';
							$xDisabledReset = 'disabled';
						}elseif($estado == 'Inactivo'){
							  $xDisabledEdit = 'disabled';
							  $xDisabledReset = 'disabled';
						}
					?>
					<tr>
					    <td style="display:none;"><?php echo $usu['Idusuario'] ?></td>
						<td><?php echo $usu['Nombres']; ?></td>
						<td><?php echo $usu['Log']; ?></td>
						<td id="<?php echo $usu['Idusuario']; ?>">
							<div class="<?php echo $xTextColor; ?>"><?php echo $usu['Estado']; ?></div>
						</td>
						<td>Administracion</td>
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

<div class="modal fade" id="user_modal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-750px">
		<div class="modal-content rounded">
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
			<div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
				<input class="form-control form-control-solid" type="hidden" id="txtid" name="txtid" />
				<form id="frm_user" class="form">
					<div class="mb-13 text-center">
					    <h3 class="modal-title" id="modalLabel"></h3>
					</div>
					
					<div class="row g-9 mb-8">
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
					<div class="row g-9 mb-8">
					   <div class="col-md-6 fv-row">
						    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
								<span class="required">Login</span>
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
					<div class="row g-9 mb-8">
						<div class="col-md-6 fv-row">
							<label class="required fs-6 fw-bold mb-2">Perfil</label>
							<select name="cboperfil" id="cboperfil" aria-label="Seleccione Perfil..." data-control="select2" data-placeholder="Seleccione Perfil..." class="form-select form-select-solid form-select-lg fw-bold">
							    <?php foreach ($expertperfil as $per) : ?>
								  <option value="<?= $per['Codigo'] ?>"><?= $per['Descripcion'] ?></option>
								<?php endforeach ?>
							</select>
						</div>
					</div>				
					<!-- <div class="d-flex flex-column mb-8 fv-row">
						<label class="fs-6 fw-bold mb-2">Email</label>
						<input type="email" class="form-control form-control-solid" id="txtEmail" name="txtEmail" placeholder="ejemplo@email.com" />
					</div> -->
					<div class="col-md-3 fv-row">
																<label>Genero</label>
                                                                <select name="gender" id="gender" aria-label="Seleccione Sexo..." data-control="select2" data-placeholder="Seleccione Sexo..." class="form-select form-select-solid form-select-lg fw-bold" required>
                                                                    <option value="">Seleccione Sexo...</option>
                                                                    <option value="HOMBRE">HOMBRE</option>
															        <option value="MUJER">MUJER</option>
															    </select>
															    <div class="fv-plugins-message-container" style="color: red;" id="errorSexo"></div>																
															</div>					
					<br/>
					<br/>
					<div class="row g-9 mb-8 text-center">
						<div class="col-md-6 fv-row">
							<label class="fs-6 fw-bold mb-2">Passward Caduca</label>
						</div>
						<div class="col-md-6 fv-row">
						    <label class="fs-6 fw-bold mb-2">Cambiar Password</label>   
						</div>
					</div>
					<div class="row g-9 mb-4 text-center">
						<div class="col-md-6 fv-row">
						    <input class="form-check-input h-20px w-20px border-primary" type="checkbox" id="chkCaducaPass" name="chkCaducaPass" value=""  />
							<label class="form-check-label" id="lblCaducaPass">NO</label>
						</div>
						<div class="col-md-6 fv-row">
						    <input class="form-check-input h-20px w-20px border border-primary" type="checkbox" id="chkCamPass" name="chkCamPass" value="email" />
							<label class="form-check-label" id="lblCamPass">NO</label>
						</div>
					</div>
					<div class="row g-9 mb-4 text-center">
						<div class="col-md-4 fv-row" id="content" style="display: none;">
							<input type="date" class="form-control" id="txtFechacaduca" name="fechacaduca">
						</div>
					</div>
					<br/>
					<br/>
					<div class="text-center">
						<button type="button" id="btnSave" class="btn btn-primary">
							<span class="indicator-label">Guardar</span>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

								<!--begin::Modals-->
								<!--begin::Modal - Customers - Add-->
								<div class="modal fade" id="kt_modal_add_customer" tabindex="-1" aria-hidden="true">
									<!--begin::Modal dialog-->
									<div class="modal-dialog modal-dialog-centered mw-650px">
										<!--begin::Modal content-->
										<div class="modal-content">
											<!--begin::Form-->
											<form class="form" action="#" id="kt_modal_add_customer_form" data-kt-redirect="../../demo1/dist/apps/customers/list.html">
												<!--begin::Modal header-->
												<div class="modal-header" id="kt_modal_add_customer_header">
													<!--begin::Modal title-->
													<h2 class="fw-bolder">Add a Customer</h2>
													<!--end::Modal title-->
													<!--begin::Close-->
													<div id="kt_modal_add_customer_close" class="btn btn-icon btn-sm btn-active-icon-primary">
														<!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
														<span class="svg-icon svg-icon-1">
															<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
																<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
															</svg>
														</span>
														<!--end::Svg Icon-->
													</div>
													<!--end::Close-->
												</div>
												<!--end::Modal header-->
												<!--begin::Modal body-->
												<div class="modal-body py-10 px-lg-17">
													<!--begin::Scroll-->
													<div class="scroll-y me-n7 pe-7" id="kt_modal_add_customer_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_customer_header" data-kt-scroll-wrappers="#kt_modal_add_customer_scroll" data-kt-scroll-offset="300px">
														<!--begin::Input group-->
														<div class="fv-row mb-7">
															<!--begin::Label-->
															<label class="required fs-6 fw-bold mb-2">Name</label>
															<!--end::Label-->
															<!--begin::Input-->
															<input type="text" class="form-control form-control-solid" placeholder="" name="name" value="Sean Bean" />
															<!--end::Input-->
														</div>
														<!--end::Input group-->
														<!--begin::Input group-->
														<div class="fv-row mb-7">
															<!--begin::Label-->
															<label class="fs-6 fw-bold mb-2">
																<span class="required">Email</span>
																<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Email address must be active"></i>
															</label>
															<!--end::Label-->
															<!--begin::Input-->
															<input type="email" class="form-control form-control-solid" placeholder="" name="email" value="sean@dellito.com" />
															<!--end::Input-->
														</div>
														<!--end::Input group-->
														<!--begin::Input group-->
														<div class="fv-row mb-15">
															<!--begin::Label-->
															<label class="fs-6 fw-bold mb-2">Description</label>
															<!--end::Label-->
															<!--begin::Input-->
															<input type="text" class="form-control form-control-solid" placeholder="" name="description" />
															<!--end::Input-->
														</div>
														<!--end::Input group-->
														<!--begin::Billing toggle-->
														<div class="fw-bolder fs-3 rotate collapsible mb-7" data-bs-toggle="collapse" href="#kt_modal_add_customer_billing_info" role="button" aria-expanded="false" aria-controls="kt_customer_view_details">Shipping Information
														<span class="ms-2 rotate-180">
															<!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
															<span class="svg-icon svg-icon-3">
																<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
																	<path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
																</svg>
															</span>
															<!--end::Svg Icon-->
														</span></div>
														<!--end::Billing toggle-->
														<!--begin::Billing form-->
														<div id="kt_modal_add_customer_billing_info" class="collapse show">
															<!--begin::Input group-->
															<div class="d-flex flex-column mb-7 fv-row">
																<!--begin::Label-->
																<label class="required fs-6 fw-bold mb-2">Address Line 1</label>
																<!--end::Label-->
																<!--begin::Input-->
																<input class="form-control form-control-solid" placeholder="" name="address1" value="101, Collins Street" />
																<!--end::Input-->
															</div>
															<!--end::Input group-->
															<!--begin::Input group-->
															<div class="d-flex flex-column mb-7 fv-row">
																<!--begin::Label-->
																<label class="fs-6 fw-bold mb-2">Address Line 2</label>
																<!--end::Label-->
																<!--begin::Input-->
																<input class="form-control form-control-solid" placeholder="" name="address2" value="" />
																<!--end::Input-->
															</div>
															<!--end::Input group-->
															<!--begin::Input group-->
															<div class="d-flex flex-column mb-7 fv-row">
																<!--begin::Label-->
																<label class="required fs-6 fw-bold mb-2">Town</label>
																<!--end::Label-->
																<!--begin::Input-->
																<input class="form-control form-control-solid" placeholder="" name="city" value="Melbourne" />
																<!--end::Input-->
															</div>
															<!--end::Input group-->
															<!--begin::Input group-->
															<div class="row g-9 mb-7">
																<!--begin::Col-->
																<div class="col-md-6 fv-row">
																	<!--begin::Label-->
																	<label class="required fs-6 fw-bold mb-2">State / Province</label>
																	<!--end::Label-->
																	<!--begin::Input-->
																	<input class="form-control form-control-solid" placeholder="" name="state" value="Victoria" />
																	<!--end::Input-->
																</div>
																<!--end::Col-->
																<!--begin::Col-->
																<div class="col-md-6 fv-row">
																	<!--begin::Label-->
																	<label class="required fs-6 fw-bold mb-2">Post Code</label>
																	<!--end::Label-->
																	<!--begin::Input-->
																	<input class="form-control form-control-solid" placeholder="" name="postcode" value="3000" />
																	<!--end::Input-->
																</div>
																<!--end::Col-->
															</div>
															<!--end::Input group-->
															<!--begin::Input group-->
															<div class="d-flex flex-column mb-7 fv-row">
																<!--begin::Label-->
																<label class="fs-6 fw-bold mb-2">
																	<span class="required">Country</span>
																	<i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Country of origination"></i>
																</label>
																<!--end::Label-->
																<!--begin::Input-->
																<select name="country" aria-label="Select a Country" data-control="select2" data-placeholder="Select a Country..." data-dropdown-parent="#kt_modal_add_customer" class="form-select form-select-solid fw-bolder">
																	<option value="">Select a Country...</option>
																	<option value="AF">Afghanistan</option>
																	<option value="AX">Aland Islands</option>
																	<option value="AL">Albania</option>
																	<option value="DZ">Algeria</option>
																	<option value="AS">American Samoa</option>
																	<option value="AD">Andorra</option>
																	<option value="AO">Angola</option>
																	<option value="AI">Anguilla</option>
																	<option value="AG">Antigua and Barbuda</option>
																	<option value="AR">Argentina</option>
																	<option value="AM">Armenia</option>
																	<option value="AW">Aruba</option>
																	<option value="AU">Australia</option>
																	<option value="AT">Austria</option>
																	<option value="AZ">Azerbaijan</option>
																	<option value="BS">Bahamas</option>
																	<option value="BH">Bahrain</option>
																	<option value="BD">Bangladesh</option>
																	<option value="BB">Barbados</option>
																	<option value="BY">Belarus</option>
																	<option value="BE">Belgium</option>
																	<option value="BZ">Belize</option>
																	<option value="BJ">Benin</option>
																	<option value="BM">Bermuda</option>
																	<option value="BT">Bhutan</option>
																	<option value="BO">Bolivia, Plurinational State of</option>
																	<option value="BQ">Bonaire, Sint Eustatius and Saba</option>
																	<option value="BA">Bosnia and Herzegovina</option>
																	<option value="BW">Botswana</option>
																	<option value="BR">Brazil</option>
																	<option value="IO">British Indian Ocean Territory</option>
																	<option value="BN">Brunei Darussalam</option>
																	<option value="BG">Bulgaria</option>
																	<option value="BF">Burkina Faso</option>
																	<option value="BI">Burundi</option>
																	<option value="KH">Cambodia</option>
																	<option value="CM">Cameroon</option>
																	<option value="CA">Canada</option>
																	<option value="CV">Cape Verde</option>
																	<option value="KY">Cayman Islands</option>
																	<option value="CF">Central African Republic</option>
																	<option value="TD">Chad</option>
																	<option value="CL">Chile</option>
																	<option value="CN">China</option>
																	<option value="CX">Christmas Island</option>
																	<option value="CC">Cocos (Keeling) Islands</option>
																	<option value="CO">Colombia</option>
																	<option value="KM">Comoros</option>
																	<option value="CK">Cook Islands</option>
																	<option value="CR">Costa Rica</option>
																	<option value="CI">Côte d'Ivoire</option>
																	<option value="HR">Croatia</option>
																	<option value="CU">Cuba</option>
																	<option value="CW">Curaçao</option>
																	<option value="CZ">Czech Republic</option>
																	<option value="DK">Denmark</option>
																	<option value="DJ">Djibouti</option>
																	<option value="DM">Dominica</option>
																	<option value="DO">Dominican Republic</option>
																	<option value="EC">Ecuador</option>
																	<option value="EG">Egypt</option>
																	<option value="SV">El Salvador</option>
																	<option value="GQ">Equatorial Guinea</option>
																	<option value="ER">Eritrea</option>
																	<option value="EE">Estonia</option>
																	<option value="ET">Ethiopia</option>
																	<option value="FK">Falkland Islands (Malvinas)</option>
																	<option value="FJ">Fiji</option>
																	<option value="FI">Finland</option>
																	<option value="FR">France</option>
																	<option value="PF">French Polynesia</option>
																	<option value="GA">Gabon</option>
																	<option value="GM">Gambia</option>
																	<option value="GE">Georgia</option>
																	<option value="DE">Germany</option>
																	<option value="GH">Ghana</option>
																	<option value="GI">Gibraltar</option>
																	<option value="GR">Greece</option>
																	<option value="GL">Greenland</option>
																	<option value="GD">Grenada</option>
																	<option value="GU">Guam</option>
																	<option value="GT">Guatemala</option>
																	<option value="GG">Guernsey</option>
																	<option value="GN">Guinea</option>
																	<option value="GW">Guinea-Bissau</option>
																	<option value="HT">Haiti</option>
																	<option value="VA">Holy See (Vatican City State)</option>
																	<option value="HN">Honduras</option>
																	<option value="HK">Hong Kong</option>
																	<option value="HU">Hungary</option>
																	<option value="IS">Iceland</option>
																	<option value="IN">India</option>
																	<option value="ID">Indonesia</option>
																	<option value="IR">Iran, Islamic Republic of</option>
																	<option value="IQ">Iraq</option>
																	<option value="IE">Ireland</option>
																	<option value="IM">Isle of Man</option>
																	<option value="IL">Israel</option>
																	<option value="IT">Italy</option>
																	<option value="JM">Jamaica</option>
																	<option value="JP">Japan</option>
																	<option value="JE">Jersey</option>
																	<option value="JO">Jordan</option>
																	<option value="KZ">Kazakhstan</option>
																	<option value="KE">Kenya</option>
																	<option value="KI">Kiribati</option>
																	<option value="KP">Korea, Democratic People's Republic of</option>
																	<option value="KW">Kuwait</option>
																	<option value="KG">Kyrgyzstan</option>
																	<option value="LA">Lao People's Democratic Republic</option>
																	<option value="LV">Latvia</option>
																	<option value="LB">Lebanon</option>
																	<option value="LS">Lesotho</option>
																	<option value="LR">Liberia</option>
																	<option value="LY">Libya</option>
																	<option value="LI">Liechtenstein</option>
																	<option value="LT">Lithuania</option>
																	<option value="LU">Luxembourg</option>
																	<option value="MO">Macao</option>
																	<option value="MG">Madagascar</option>
																	<option value="MW">Malawi</option>
																	<option value="MY">Malaysia</option>
																	<option value="MV">Maldives</option>
																	<option value="ML">Mali</option>
																	<option value="MT">Malta</option>
																	<option value="MH">Marshall Islands</option>
																	<option value="MQ">Martinique</option>
																	<option value="MR">Mauritania</option>
																	<option value="MU">Mauritius</option>
																	<option value="MX">Mexico</option>
																	<option value="FM">Micronesia, Federated States of</option>
																	<option value="MD">Moldova, Republic of</option>
																	<option value="MC">Monaco</option>
																	<option value="MN">Mongolia</option>
																	<option value="ME">Montenegro</option>
																	<option value="MS">Montserrat</option>
																	<option value="MA">Morocco</option>
																	<option value="MZ">Mozambique</option>
																	<option value="MM">Myanmar</option>
																	<option value="NA">Namibia</option>
																	<option value="NR">Nauru</option>
																	<option value="NP">Nepal</option>
																	<option value="NL">Netherlands</option>
																	<option value="NZ">New Zealand</option>
																	<option value="NI">Nicaragua</option>
																	<option value="NE">Niger</option>
																	<option value="NG">Nigeria</option>
																	<option value="NU">Niue</option>
																	<option value="NF">Norfolk Island</option>
																	<option value="MP">Northern Mariana Islands</option>
																	<option value="NO">Norway</option>
																	<option value="OM">Oman</option>
																	<option value="PK">Pakistan</option>
																	<option value="PW">Palau</option>
																	<option value="PS">Palestinian Territory, Occupied</option>
																	<option value="PA">Panama</option>
																	<option value="PG">Papua New Guinea</option>
																	<option value="PY">Paraguay</option>
																	<option value="PE">Peru</option>
																	<option value="PH">Philippines</option>
																	<option value="PL">Poland</option>
																	<option value="PT">Portugal</option>
																	<option value="PR">Puerto Rico</option>
																	<option value="QA">Qatar</option>
																	<option value="RO">Romania</option>
																	<option value="RU">Russian Federation</option>
																	<option value="RW">Rwanda</option>
																	<option value="BL">Saint Barthélemy</option>
																	<option value="KN">Saint Kitts and Nevis</option>
																	<option value="LC">Saint Lucia</option>
																	<option value="MF">Saint Martin (French part)</option>
																	<option value="VC">Saint Vincent and the Grenadines</option>
																	<option value="WS">Samoa</option>
																	<option value="SM">San Marino</option>
																	<option value="ST">Sao Tome and Principe</option>
																	<option value="SA">Saudi Arabia</option>
																	<option value="SN">Senegal</option>
																	<option value="RS">Serbia</option>
																	<option value="SC">Seychelles</option>
																	<option value="SL">Sierra Leone</option>
																	<option value="SG">Singapore</option>
																	<option value="SX">Sint Maarten (Dutch part)</option>
																	<option value="SK">Slovakia</option>
																	<option value="SI">Slovenia</option>
																	<option value="SB">Solomon Islands</option>
																	<option value="SO">Somalia</option>
																	<option value="ZA">South Africa</option>
																	<option value="KR">South Korea</option>
																	<option value="SS">South Sudan</option>
																	<option value="ES">Spain</option>
																	<option value="LK">Sri Lanka</option>
																	<option value="SD">Sudan</option>
																	<option value="SR">Suriname</option>
																	<option value="SZ">Swaziland</option>
																	<option value="SE">Sweden</option>
																	<option value="CH">Switzerland</option>
																	<option value="SY">Syrian Arab Republic</option>
																	<option value="TW">Taiwan, Province of China</option>
																	<option value="TJ">Tajikistan</option>
																	<option value="TZ">Tanzania, United Republic of</option>
																	<option value="TH">Thailand</option>
																	<option value="TG">Togo</option>
																	<option value="TK">Tokelau</option>
																	<option value="TO">Tonga</option>
																	<option value="TT">Trinidad and Tobago</option>
																	<option value="TN">Tunisia</option>
																	<option value="TR">Turkey</option>
																	<option value="TM">Turkmenistan</option>
																	<option value="TC">Turks and Caicos Islands</option>
																	<option value="TV">Tuvalu</option>
																	<option value="UG">Uganda</option>
																	<option value="UA">Ukraine</option>
																	<option value="AE">United Arab Emirates</option>
																	<option value="GB">United Kingdom</option>
																	<option value="US" selected="selected">United States</option>
																	<option value="UY">Uruguay</option>
																	<option value="UZ">Uzbekistan</option>
																	<option value="VU">Vanuatu</option>
																	<option value="VE">Venezuela, Bolivarian Republic of</option>
																	<option value="VN">Vietnam</option>
																	<option value="VI">Virgin Islands</option>
																	<option value="YE">Yemen</option>
																	<option value="ZM">Zambia</option>
																	<option value="ZW">Zimbabwe</option>
																</select>
																<!--end::Input-->
															</div>
															<!--end::Input group-->
															<!--begin::Input group-->
															<div class="fv-row mb-7">
																<!--begin::Wrapper-->
																<div class="d-flex flex-stack">
																	<!--begin::Label-->
																	<div class="me-5">
																		<!--begin::Label-->
																		<label class="fs-6 fw-bold">Use as a billing adderess?</label>
																		<!--end::Label-->
																		<!--begin::Input-->
																		<div class="fs-7 fw-bold text-muted">If you need more info, please check budget planning</div>
																		<!--end::Input-->
																	</div>
																	<!--end::Label-->
																	<!--begin::Switch-->
																	<label class="form-check form-switch form-check-custom form-check-solid">
																		<!--begin::Input-->
																		<input class="form-check-input" name="billing" type="checkbox" value="1" id="kt_modal_add_customer_billing" checked="checked" />
																		<!--end::Input-->
																		<!--begin::Label-->
																		<span class="form-check-label fw-bold text-muted" for="kt_modal_add_customer_billing">Yes</span>
																		<!--end::Label-->
																	</label>
																	<!--end::Switch-->
																</div>
																<!--begin::Wrapper-->
															</div>
															<!--end::Input group-->
														</div>
														<!--end::Billing form-->
													</div>
													<!--end::Scroll-->
												</div>
												<!--end::Modal body-->
												<!--begin::Modal footer-->
												<div class="modal-footer flex-center">
													<!--begin::Button-->
													<button type="reset" id="kt_modal_add_customer_cancel" class="btn btn-light me-3">Discard</button>
													<!--end::Button-->
													<!--begin::Button-->
													<button type="submit" id="kt_modal_add_customer_submit" class="btn btn-primary">
														<span class="indicator-label">Submit</span>
														<span class="indicator-progress">Please wait...
														<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
													</button>
													<!--end::Button-->
												</div>
												<!--end::Modal footer-->
											</form>
											<!--end::Form-->
										</div>
									</div>
								</div>
								<!--end::Modal - Customers - Add-->
								<!--end::Modals-->

	<script>
		$(document).ready(function(){

			var _emprid,cambiarpass, _estado,caduca,_campass,_nombre,_apellido,_login,_password,_perfil,estado,_caduca,
			_fechacaduca,_cambiarpass,_log,_usu,_dep,_fila,_tipo;

			//abrir-modal-nuevo-usuario
			$("#nuevoUsuario").click(function(){

				$("#frm_user").trigger("reset");
				$("#kt_modal_add_customer").modal("show");
				$(".modal-title").text("Nuevo Usuario");
				$("#btnSave").text("Guardar");
				$("#chkCaducaPass").prop("checked", false);
				$("#lblCaducaPass").text("NO");
				$("#chkCamPass").prop("checked", false);
				$("#lblCamPass").text("NO");  

				$('#txtPassword').prop('readonly', false);
				$('#content').css('display','none'); 
				$('#cboPerfil').val(null).trigger('change');  

				estado = 'A';
				_addmod = 'add';

			});

			//cambiar label -SI-NO

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

				_emprid = $('#idempr').val();
				_estado = estado
				_nombre = $.trim($("#txtNombre").val());
				_apellido = $.trim($("#txtApellido").val());
				_login = $.trim($("#txtLogin").val());
				_password = $.trim($("#txtPassword").val());
				_perfil = $('#cboPerfil').val();
				_perfilname = $("#cboPerfil option:selected").text();				
				_caduca = caduca;
				_cambiarpass = cambiarpass;
				_fechacaduca = $.trim($("#txtFechacaduca").val());
				_buscar = 'SI';
				_continuar = 'SI';

				//alert(_emprid);
				//debugger;

				if(_nombre == ''){                        
					mensajesweetalert("center","warning","ingrese un nombre",false,1800);
					return;
				}

				if(_apellido == ''){                        
					mensajesweetalert("center","warning","ingrese un apellido",false,1800);
					return;
				}

				if(_login == ''){                        
					mensajesweetalert("center","warning","ingrese un login",false,1800);
					return;
				}

				if(_password == ''){                        
					mensajesweetalert("center","warning","ingrese una contraseña",false,1800);
					return;
				}

				
				if(_perfil == 0){                        
					mensajesweetalert("center","warning","ingrese un perfil",false,1800);
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
							xxEstado: _estado,
							xxNombre: _nombre,
							xxApellido:_apellido,
							xxLogin: _login,
							xxPassword: _password,
							xxPerfil: _perfil,
							xxCaducaPass: _caduca,
							xxCambiarPass: _cambiarpass,
							xxFecha: _fechacaduca
						}
						_ulr = "codephp/grabar_usuarios.php";	
					}else{
						_userid = $('#txtid').val();

						$datosUser = {
							xxEmprid: _emprid,
							xxUserid: _userid,
							xxEstado: _estado,
							xxNombre: _nombre,
							xxApellido:_apellido,
							xxLogin: _login,
							xxPerfil: _perfil,
							xxCaducaPass: _caduca,
							xxCambiarPass: _cambiarpass,
							xxFecha: _fechacaduca
						}	
						_ulr = "codephp/editar_usuarios.php";
					}
					
					$.ajax({
							url: _ulr,
							type: "POST",
							dataType: "json",
							data: $datosUser,          
							success: function(data){ 
								if(data != 0){

									_userid = data;										
									_usuario = _nombre + ' ' + _apellido;

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
										TableData.row.add([_userid, _usuario, _login, _estado, _perfilname, _btnreset, _btnedit, _btnchk]).draw();
									}
									else{
										TableData.row(_fila).data([_userid, _usuario, _login, _estado, _perfilname, _btnreset, _btnedit, _btnchk]).draw();
									} 

									$("#user_modal").modal("hide");									

								}                                                                         
							},
							error: function (error){
								console.log(error);
							}                            
						}); 					

				}

				// var xrespuesta = $.post("codephp/consultar_usuarios.php", $parametros);
				// xrespuesta.done(function(response){
				// 	//console.log(response);
				// 	if(response == 0){

				// 		$datosUser = {
				// 			xxEmprid: _emprid,
				// 			xxEstado: _estado,
				// 			xxNombre: _nombre,
				// 			xxApellido:_apellido,
				// 			xxLogin: _login,
				// 			xxPassword: _password,
				// 			xxPerfil: _perfil,
				// 			xxCaducaPass: _caduca,
				// 			xxCambiarPass: _cambiarpass,
				// 			xxFecha: _fechacaduca
				// 		}

				// 		if(_addmod == 'add'){
				// 			_ulr = "codephp/grabar_usuarios.php";							
				// 		}else{
				// 			_ulr = "codephp/editar_usuarios.php";							
				// 		}

				// 		$.ajax({
				// 			url: _ulr,
				// 			type: "POST",
				// 			dataType: "json",
				// 			data: $datosUser,          
				// 			success: function(data){ 
				// 				if(data != 0){

				// 					if(_addmod == 'add'){
				// 						_userid = data;
				// 					}else{
				// 						_userid = $('#txtid').val();
				// 					}
									
				// 					_usuario = _nombre + ' ' + _apellido;

				// 					var _estado = '<td><div class="badge badge-light-primary">Activo</div>' ;

				// 					var _btnreset = '<td><div class="text-center"><div class="btn-group"><button id="btnReset' + _userid + '"' + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Resetear Perfil" >' + 
				//  						'<i class="fa fa-key"></i></button></div></div></td>';

				// 					var _btnedit = '<td><div class="text-center"><div class="btn-group"><button id="btnEditar' + _userid + '"' + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title="Editar Usuario" >' + 
				//  						'<i class="fa fa-edit"></i></button></div></div></td>';

				// 					var _btnchk = '<td style="text-align:center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
				// 								'<input class="form-check-input btnEstado" type="checkbox" id="chk' + _userid + '" checked onchange="f_Check(' +
				// 								_emprid + ',' + _userid + ')"' + ' value="' + _userid + '"' + '/></div></td>';	
												
				// 					TableData = $('#kt_ecommerce_report_shipping_table').DataTable();

				// 					TableData.column(0).visible(0);
										

				// 					if(_addmod == 'add'){

				// 						TableData.row.add([_userid, _usuario, _login, _estado, _perfilname, _btnreset, _btnedit, _btnchk]).draw();
				// 					}
				// 					else{
				// 						TableData.row(_fila).data([_userid, _usuario, _login, _estado, _perfilname, _btnreset, _btnedit, _btnchk]).draw();
				// 					} 

				// 					$("#user_modal").modal("hide");									
				// 				}                                                                         
				// 			},
				// 			error: function (error){
				// 				console.log(error);
				// 			}                            
				// 		}); 
				// 	}else{
				// 		mensajesweetalert("center","warning","Nombre del Usuario ya Existe..!",false,1800);
				// 	}
				// });

			});

			//editar modal usuario

			$(document).on("click",".btnEditar",function(){
                
				_fila = $(this).closest("tr");
				_data = $('#kt_ecommerce_report_shipping_table').dataTable().fnGetData(_fila);
				_idusu = _data[0];
				_loginold = _data[2];
				_addmod = 'mod';

				$('#txtPassword').prop('readonly', true);

				$parametros = {
					xxIdUsuario: _idusu,
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
				$("#txtid").val(_idusu);
				$("#frm_user").trigger("reset");
			    $("#user_modal").modal("show");
				

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
			$("#user_modal").draggable({
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