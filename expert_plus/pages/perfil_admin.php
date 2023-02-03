<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');

    //$xServidor = $_SERVER['HTTP_HOST'];
    $page = isset($_GET['page']) ? $_GET['page'] : "index";
    $xFecha = strftime("%Y-%m-%d %H:%M:%S", time());    

    @session_start();

    //$yEmprid = $_SESSION["i_empre_id"];
    $yEmprid = 1;
    $xDisabledEdit = "";
    
    /*if(isset($_SESSION["s_usuario"])){
        if($_SESSION["s_login"] != "loged"){
            header("Location: ./logout.php");
            exit();
        }
    } else{
        header("Location: ./logout.php");
        exit();
    }*/

    $xSql = "SELECT per.perf_id AS Id,per.perf_descripcion AS Perfil,per.perf_observacion AS Descripcion,CASE per.perf_estado WHEN 'A' THEN 'Activo' ELSE 'Inactivo' END AS Estado ";
    $xSql .= "FROM `expert_perfil` per WHERE per.empr_id=" . $yEmprid;

    $all_perfiles = mysqli_query($con, $xSql);
    foreach ($all_perfiles as $perfil){
        $xName = $perfil["Perfil"];
    }
	
?>				
					
		<div id="kt_content_container" class="container-xxl">
			<div class="card card-flush">
				<div class="card-toolbar">
					<a href="?page=addperfil" class="btn btn-sm btn-light-primary">
						<span class="svg-icon svg-icon-2">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
								<rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
							</svg>
						</span>
					Nuevo Perfil</a>
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
						<!-- <input class="form-control form-control-solid w-100 mw-250px" placeholder="Pick date range" id="kt_ecommerce_report_shipping_daterangepicker" /> -->
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
                                    <th>Perfil</th>
                                    <th>Descipción</th>                                    
                                    <th style="text-align:center;">Opciones</th>
									<th>Estado</th>
                                    <th>Status</th>
							</tr>
						</thead>
						<tbody class="fw-bold text-gray-600">
                            <?php
                                if($xName == "Administrador"){
                                    $chkEstado = "disabled";
                                }
                                foreach ($all_perfiles as $perfil){    
                            ?>
                            <?php
                            
                                if ($perfil['Perfil'] != 'Administrador' && $perfil['Estado'] == 'Inactivo') {
                                    $xDisabledEdit = 'disabled';
                                }
								if($perfil['Estado'] == 'Activo'){
									$xTextColor = "badge badge-light-success";
								}else{
									$xTextColor = "badge badge-light-danger";
								}
                            ?>
							<tr>
                                <td><?php echo $perfil['Perfil']; ?></td>
                                <td><?php echo $perfil['Descripcion']; ?></td>
								<td>
									<div class="text-center">
										<div class="btn-group">
											<a href="?page=revisolpn&id=2222" <?php echo $xDisabledEdit ?> id="btnEditar<?php echo $perfil['perf_id']; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title='Revisar Solicitud'>
												<i class='fa fa-edit'></i>
											</a>																														
										</div>
									</div>
								</td>
								<td>
									<div class="<?php  echo $xTextColor; ?>"><?php echo $perfil['Estado']; ?></div>
								</td>								
                                <td style="text-align:center">
									<div class="form-check form-check-sm form-check-custom form-check-solid">
										<input class="form-check-input" type="checkbox" <?php echo $chkEstado; ?> id="chk<?php echo $perfil['Id']; ?>" <?php if ($perfil['Estado'] == 'Activo') {
											echo "checked";} else {'';} ?> value="<?php echo $perfil['Id']; ?>" />
									</div>
                                </td>                                                           
							</tr>
                            <?php }
                                ?>                            
						</tbody>
					</table>
				</div>
			</div>
		</div>

		
							