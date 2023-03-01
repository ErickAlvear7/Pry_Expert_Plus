
<?php
	$page = isset($_GET['page']) ? $_GET['page'] : "index";

	require_once("dbcon/config.php");
	require_once("dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

	$xServidor = $_SERVER['HTTP_HOST'];
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());

	$yEmprid = 1;

	$xSQL = "SELECT menu_id AS Idmenu, empr_id AS Empid, menu_descripcion AS Menu, menu_observacion AS Observacion, CASE menu_estado WHEN 'A' THEN 'Activo' ";
	$xSQL .= "ELSE 'Inactivo' END AS Estado FROM `expert_menu`";
	$expertmenu = mysqli_query($con, $xSQL);
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
									<a class="nav-link text-active-primary ms-0 me-10 py-5 active" href="?page=suptarea&menuid=0">Tareas</a>
								</li>
								<li class="nav-item mt-2">
									<a class="nav-link text-active-primary ms-0 me-10 py-5" href="?page=supmenu&menuid=0">Menu</a>
								</li>
								<li class="nav-item mt-2">
									<a class="nav-link text-active-primary ms-0 me-10 py-5" href="?page=supperfil&menuid=0">Perfil</a>
								</li>
								<li class="nav-item mt-2">
									<a class="nav-link text-active-primary ms-0 me-10 py-5" href="?page=supusuarios&menuid=0">Usuarios</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="row g-5 g-xxl-8">
						<input type="hidden" id="mensaje" value="<?php echo $mensaje ?>">
						<div class="card card-flush">
							<div class="card-toolbar">
								<a href="?page=addmenu" class="btn btn-sm btn-light-primary">
									<span class="svg-icon svg-icon-2">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
											<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
											<rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
										</svg>
									</span>
								Nuevo Menu</a>
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
											<th style="display:none;">Idmenu</th>
											<th style="width: 30px;">Menu</th>
											<th style="width: 30px;">Descripcion</th>
											<th style="width: 30px;">Estado</th>
											<th style="width: 30px; text-align:center;">Opciones</th>
											<th style="width: 10px;">Status</th>
										</tr>
									</thead>
									<tbody class="fw-bold text-gray-600">
										<?php 
										
										foreach($expertmenu as $menu){
										$xMenu = $menu['Menu'];
										?>
										<?php 

										$chkEstado = '';
										$xDisabledEdit = '';

										if($xMenu == 'Seguridad'){
											$chkEstado = 'disabled';
											$xDisabledEdit = 'disabled';
										}elseif($menu['Estado'] == 'Inactivo'){
											$xDisabledEdit = 'disabled';
										}

										if($menu['Estado'] == 'Activo'){
											$xTextColor = "badge badge-light-primary";
										}else{
											$xTextColor = "badge badge-light-danger";
										}
										
										?>
										<tr>
											<td style="display:none;"><?php echo $menu['Idmenu']; ?></td>
											<td><?php echo $menu['Menu']; ?></td>
											<td><?php echo $menu['Observacion']; ?></td>
											<td>
											<div class="<?php  echo $xTextColor; ?>"><?php echo $menu['Estado']; ?></div>
											</td>
											<td>
												<div class="text-center">
													<div class="btn-group">
														<button <?php echo $xDisabledEdit ?> onclick="f_Editar(<?php echo $menu['Idmenu']; ?>)" id="btnEditar" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title='Editar Perfil'>
															<i class='fa fa-edit'></i>
														</button>																															 
													</div>
												</div>
											</td>
											<td>
												<div class="text-center">
													<div class="form-check form-check-sm form-check-custom form-check-solid">
															<input class="form-check-input btnEstado" type="checkbox" <?php echo $chkEstado; ?> id="chk<?php echo $menu['Idmenu']; ?>" <?php if ($menu['Estado'] == 'Activo') {
																	echo "checked";} else {'';} ?> value="<?php echo $menu['Idmenu']; ?>"  onchange="f_Check(<?php echo $yEmprid; ?>,<?php echo $menu['Idmenu']; ?>)" />
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
					<!--end::Row-->
				</div>
				<!--end::Container-->

		<!--end::Modal - Create App-->
		<!--begin::Modal - New Address-->
		<div class="modal fade" id="modal-tarea" tabindex="-1" aria-hidden="true">
			<!--begin::Modal dialog-->
			<div class="modal-dialog modal-dialog-centered mw-650px">
				<div class="modal-content">
					<form class="form" id="frm_datos">
						<div class="modal-header" id="kt_modal_new_address_header">
							<h2 class="modal-title" id="modalLabel"></h2>
							<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
								<span class="svg-icon svg-icon-1">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
										<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
										<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
									</svg>
								</span>
							</div>
						</div>
												
						<div class="modal-body py-10 px-lg-17">
							<div class="scroll-y me-n7 pe-7" id="kt_modal_new_address_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_new_address_header" data-kt-scroll-wrappers="#kt_modal_new_address_scroll" data-kt-scroll-offset="300px">
								<!-- <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed mb-9 p-6">
									<span class="svg-icon svg-icon-2tx svg-icon-warning me-4">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
											<rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor" />
											<rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)" fill="currentColor" />
											<rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)" fill="currentColor" />
										</svg>
									</span>
									
									<div class="d-flex flex-stack flex-grow-1">
										<div class="fw-bold">
											<h4 class="text-gray-900 fw-bolder">Warning</h4>
											<div class="fs-6 text-gray-700">Updating address may affter to your
											<a href="#">Tax Location</a></div>
										</div>
									</div>
								</div> -->
								
								<!-- <div class="row mb-5">
									<div class="col-md-6 fv-row">
										<label class="required fs-5 fw-bold mb-2">First name</label>
										<input type="text" class="form-control form-control-solid" placeholder="" name="first-name" />
									</div>
									
									<div class="col-md-6 fv-row">
										<label class="required fs-5 fw-bold mb-2">Last name</label>
										<input type="text" class="form-control form-control-solid" placeholder="" name="last-name" />
									</div>
								</div> -->
								
								<!-- <div class="d-flex flex-column mb-5 fv-row">
									<label class="d-flex align-items-center fs-5 fw-bold mb-2">
										<span class="required">Country</span>
										<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Your payment statements may very based on selected country"></i>
									</label>
									
									<select name="country" data-control="select2" data-dropdown-parent="#modal-tarea" data-placeholder="Select a Country..." class="form-select form-select-solid">
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
										<option value="US">United States</option>
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
									
								</div> -->

								<div class="d-flex flex-column mb-5 fv-row">
									<label class="required fs-5 fw-bold mb-2">Tarea</label>
									<input class="form-control form-control-solid" id="txtTarea" name="txtTarea" maxlength="100" placeholder="Ingrese Tarea"  />
								</div>
								
								<div class="d-flex flex-column mb-5 fv-row">
									<label class="required fs-5 fw-bold mb-2">Accion/Ruta</label>
									<input class="form-control form-control-solid" id="txtRuta" name="txtRuta" maxlength="150" placeholder="Ingrese Ruta" />
								</div>
								
								<!-- <div class="fv-row mb-5">
									<div class="d-flex flex-stack">
										<div class="me-5">
											<label class="fs-5 fw-bold">Use as a billing adderess?</label>
											<div class="fs-7 fw-bold text-muted">If you need more info, please check budget planning</div>
										</div>
										<label class="form-check form-switch form-check-custom form-check-solid">
											<input class="form-check-input" name="billing" type="checkbox" value="1" checked="checked" />
											<span class="form-check-label fw-bold text-muted">Yes</span>
										</label>
									</div>
								</div> -->
							</div>
						</div>

						<div class="modal-footer flex-center">
							<button type="button" id="btnSave" class="btn btn-primary">
								<span class="indicator-label">Grabar</span>
								<span class="indicator-progress">Please wait...
								<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!--end::Modal - New Address-->
		<!--begin::Modal - Users Search-->		
		
		<script>
			$(document).ready(function(){
				_mensaje = $('input#mensaje').val();

				if(_mensaje != ''){
					//mensajesweetalert("center","success",_mensaje+"..!",false,1800);
					mensajesalertify(_mensaje+"..!","S","top-center",5);
				}

				$("#btnTarea").click(function(){
					$("#modal-tarea").modal("show");
					$(".modal-title").text("Nueva Tarea");
					$("#frm_datos").trigger("reset");
					_addmod = 'add';
					_idtarea = 0;
				});

				$(document).on("click",".btnEditar",function(){
                
					_fila = $(this).closest("tr");
					_data = $('#kt_ecommerce_report_shipping_table').dataTable().fnGetData(_fila);
					_idtarea = _data[0];					
					_tareaold = _data[1];
					_addmod = 'mod';

					$(".modal-title").text("Editar Tarea");				
					$("#btnSave").text("Modificar");
					$("#frm_datos").trigger("reset");
					$("#modal-tarea").modal("show");
					$("#txtTarea").val(_data[1]);
					$("#txtRuta").val(_data[2]);					
				
				});				

				$('#btnSave').click(function(e){

					var _paisid = "<?php echo $yPaisid; ?>"
					var _emprid = "<?php echo $yEmprid; ?>"
					var _usuaid = "<?php echo $yUsuaid; ?>"
					var _tarea = $.trim($("#txtTarea").val());
					var _ruta = $.trim($("#txtRuta").val());
					var _buscar = 'SI';

					if(_tarea == ''){                        
						mensajesweetalert("center","warning","Ingrese Tarea",false,1800);
						return;
					}

					if(_ruta == ''){                        
						mensajesweetalert("center","warning","Ingrese Accion/Ruta",false,1800);
						return;
					}

					if(_addmod == 'mod'){
						if(_tareaold != _tarea){
							_buscar = 'SI';
						}else{
							_buscar = 'NO';
						}
					}
					
					if(_buscar == 'SI'){
						var xrespuesta = $.post("codephp/consultar_tarea.php", { xxTarea: _tarea, xxEmprid: _emprid });
						xrespuesta.done(function(response){							
							if(response == '0'){
								funGrabar(_paisid,_emprid,_usuaid,_tarea,_ruta);
							}else{								
								mensajesweetalert("center","warning","Tarea ya Existe..!",false,1800);
							}
						});						
					}else{
						funGrabar(_paisid,_emprid,_usuaid,_tarea,_ruta);
					}
				});

				$(document).on("click",".btnEstado",function(e){
					_fila = $(this).closest("tr");
					_tarea = $(this).closest("tr").find('td:eq(1)').text();  
					_ruta = $(this).closest("tr").find('td:eq(2)').text(); 					
					//console.log(_fila);
				});
				
			});

			function f_UpdateEstado(_tareaid, _emprid){

				var _paisid = "<?php echo $yPaisid; ?>"
				var _emprid = "<?php echo $yEmprid; ?>"
				var _usuaid = "<?php echo $yUsuaid; ?>"
				
				let _check = $("#chk" + _tareaid).is(":checked");
				let _checked = "";
				let _disabled = "";
				let _class = "badge badge-light-primary";
				let _estado = "";

				if(_check){
					_estado = "Activo";
					_disabled = "";
					_checked = "checked='checked'";
					_class = "badge badge-light-primary";
				}else{
					_estado = "Inactivo";
					_disabled = "disabled";
					_class = "badge badge-light-danger";
				}

				var _lblEstado = '<td><div class="' + _class + '">' + _estado + ' </div>';

				var _btnedit = '<td><div class="text-center"><div class="btn-group"><button ' + _disabled + 
							'id="btnEditar"' +_tareaid + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title="Editar Tarea">' +
							'<i class="fa fa-edit"></i></button></div></div></td>';

				var _btnchk = '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
							'<input class="form-check-input btnEstado" type="checkbox" ' + ' id="chk' + _tareaid + '"' +
							' ' + _checked + ' value="' + _tareaid + '" onchange="f_UpdateEstado(' +_tareaid  + ',' + _emprid + ')"/>' +
							'</div></div></td>';

				TableData = $('#kt_ecommerce_report_shipping_table').DataTable();

				TableData.row(_fila).data([_tareaid, _tarea, _ruta, _lblEstado, _btnedit, _btnchk]).draw();

				$parametros = {
					xxEmprid: _emprid,
					xxTareaId: _tareaid,
					xxEstado: _estado
				}

				$.post("codephp/update_estado_tarea.php", $parametros , function(data){

					$parametros = {
						xxPaisid: _paisid,
						xxEmprid: _emprid,
						xxUsuaid: _usuaid,
						xxDetalle: 'Cambio Estado Tarea',
					}					

					var xrespuesta = $.post("codephp/new_log.php", $parametros);						
						xrespuesta.done(function(response) {
							//console.log(response);
					});	

				});
			}			

			function funGrabar(_paisid,_emprid,_usuaid,_tarea,_ruta){

				if(_addmod == 'add'){
					_ulr = "codephp/new_tarea.php";
				}else{
					_ulr = "codephp/update_tarea.php";
				}
				
				$parametros = {
					xxEmprid: _emprid,
					xxUsuaid: _usuaid,
					xxTareaId: _idtarea,
					xxTarea: _tarea,
					xxRuta: _ruta
				}				

				$.post(_ulr, $parametros , function(data){

					var _tareaid = data;

					if(_tareaid != 0){
						var _estado = '<td><div class="badge badge-light-primary">Activo</div>' ;

						var _btnedit = '<td><div class="text-center"><div class="btn-group"><button id="btnEditar' + _tareaid + '"' + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title="Editar Tarea" >' + 
							'<i class="fa fa-edit"></i></button></div></div></td>';

						var _btnchk = '<td style="text-align:center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
									'<input class="form-check-input btnEstado" type="checkbox" id="chk' + _tareaid + '" checked onchange="f_Check(' +
									_emprid + ',' + _tareaid + ')"' + ' value="' + _tareaid + '" onclick="f_UpdateEstado(' + _tareaid + ',' + _emprid + ')"' + '/></div></td>';	
									
						TableData = $('#kt_ecommerce_report_shipping_table').DataTable();

						TableData.column(0).visible(0);
							
						if(_addmod == 'add'){
							TableData.row.add([_tareaid, _tarea, _ruta, _estado, _btnedit, _btnchk]).draw();
							_detalle = 'Crear Nueva Tarea';
						}
						else{
							TableData.row(_fila).data([_tareaid, _tarea, _ruta, _estado, _btnedit, _btnchk]).draw();
							_detalle = 'Modificar Tarea';
						} 
					}else{
						//console.log('Error encontrado en sentecia SQL');
						_detalle = 'Error encontrado en sentecia SQL';
					}

					$("#modal-tarea").modal("hide");

					$parametros = {
						xxPaisid: _paisid,
						xxEmprid: _emprid,
						xxUsuaid: _usuaid,
						xxDetalle: _detalle,
					}					

					var xrespuesta = $.post("codephp/new_log.php", $parametros);						
					xrespuesta.done(function(response) {
						//console.log(response);
						if(response == 'OK'){
							mensajesweetalert("center","success","Grabado con Exito..!",false,1800);
						}
					});
				});	

				// $.ajax({
				// 	url: _ulr,
				// 	type: "POST",
				// 	dataType: "json",
				// 	data: $parametros,          
				// 	success: function(data){ 
				// 		if(data != 0){

				// 			_tareaid = data;										

				// 			var _estado = '<td><div class="badge badge-light-primary">Activo</div>' ;

				// 			var _btnedit = '<td><div class="text-center"><div class="btn-group"><button id="btnEditar' + _tareaid + '"' + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" title="Editar Tarea" >' + 
				// 				'<i class="fa fa-edit"></i></button></div></div></td>';

				// 			var _btnchk = '<td style="text-align:center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
				// 						'<input class="form-check-input btnEstado" type="checkbox" id="chk' + _tareaid + '" checked onchange="f_Check(' +
				// 						_emprid + ',' + _tareaid + ')"' + ' value="' + _tareaid + '"' + '/></div></td>';	
										
				// 			TableData = $('#kt_ecommerce_report_shipping_table').DataTable();

				// 			TableData.column(0).visible(0);
								
				// 			if(_addmod == 'add'){
				// 				TableData.row.add([_tareaid, _tarea, _ruta, _estado, _btnedit, _btnchk]).draw();
				// 			}
				// 			else{
				// 				TableData.row(_fila).data([_tareaid, _tarea, _ruta, _estado, _btnedit, _btnchk]).draw();
				// 			} 

				// 			$("#modal-tarea").modal("hide");									

				// 		}                                                                         
				// 	},
				// 	error: function (error){
				// 		console.log(error);
				// 	}                            
				// });				
			}

			$("#modal-tarea").draggable({
					handle: ".modal-header"
			}); 			

		</script> 			