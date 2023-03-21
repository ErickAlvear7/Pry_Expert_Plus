<?php

	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');

	require_once("./dbcon/config.php");
	require_once("./dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');		

    //$xServidor = $_SERVER['HTTP_HOST'];
    $page = isset($_GET['page']) ? $_GET['page'] : "index";
    $menuid = $_GET['menuid'] ;
    
    @session_start();

    if(isset($_SESSION["s_usuario"])){
        if($_SESSION["s_loged"] != "loged"){
            header("Location: ./logout.php");
            exit();
        }
    } else{
        header("Location: ./logout.php");
        exit();
    }

    $xPaisid = $_SESSION["i_paisid"];
    $xEmprid = $_SESSION["i_emprid"];	
	$xUsuaid = $_SESSION["i_usuaid"];
    
	$mensaje = (isset($_POST['mensaje'])) ? $_POST['mensaje'] : '';
    
    $xSQL = "SELECT per.perf_id AS Id,per.perf_descripcion AS Perfil,per.perf_observacion AS Descripcion,CASE per.perf_estado WHEN 'A' THEN 'Activo' ELSE 'Inactivo' END AS Estado ";
    $xSQL .= "FROM `expert_perfil` per WHERE per.pais_id=$xPaisid AND per.empr_id=$xEmprid";

    $all_perfiles = mysqli_query($con, $xSQL);
    foreach ($all_perfiles as $perfil){
        $xName = $perfil["Perfil"];
    }
	
?>				
					
		<div id="kt_content_container" class="container-xxl">
			<input type="hidden" id="mensaje" value="<?php echo $mensaje ?>">
			<div class="card card-flush">
				<div class="card-toolbar">
					<a href="?page=addperfil&menuid=<?php echo $menuid; ?>" class="btn btn-sm btn-light-primary">
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
					
					<!--<div class="card-toolbar flex-row-fluid justify-content-end gap-5">
						<div class="w-150px">
							<select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Estado" data-kt-ecommerce-order-filter="status">
								<option></option>
								<option value="all">Todos</option>
								<option value="Activo">Activo</option>
								<option value="Inactivo">Inactivo</option>
							</select>
						</div>
					</div>-->
					
				</div>
				<div class="card-body pt-0">
					<table class="table align-middle table-row-dashed fs-6 gy-5 table-hover" id="kt_ecommerce_report_shipping_table" style="width: 100%;">
						<thead>
							<tr class="text-start text-gray-800 fw-bolder fs-7 gs-0">
                                    <th>Perfil</th>
                                    <th>Descipcion</th>                                                                        									
									<th>Estado</th>
                                    <th>Status</th>
                                    <th style="text-align:center;">Opciones</th>
							</tr>
						</thead>
						<tbody class="fw-bold text-gray-600">
                            <?php

                                foreach ($all_perfiles as $perfil){    
                            ?>
                            <?php
                            
								$xDisabledEdit = '';
								$xDisabledChk = '';

                                if ($perfil['Estado'] == 'Inactivo') {
                                    $xDisabledEdit = 'disabled';
                                }

								if($perfil['Perfil'] == 'Administrador'){
									$xDisabledChk = 'disabled';
								}

								if($perfil['Estado'] == 'Activo'){
									$xTextColor = 'badge badge-light-primary';
								}else{
									$xTextColor = 'badge badge-light-danger';
								}
                            ?>
							<tr>
                                <td><?php echo $perfil['Perfil']; ?></td>
                                <td><?php echo $perfil['Descripcion']; ?></td>								
								<td id="td<?php echo $perfil['Id']; ?>">
									<div class="<?php echo $xTextColor; ?>"><?php echo $perfil['Estado']; ?></div>
								</td>								
                                <td style="text-align:center">
									<div class="form-check form-check-sm form-check-custom form-check-solid">
										<input class="form-check-input btnEstado" type="checkbox" <?php echo $xDisabledChk; ?> id="chk<?php echo $perfil['Id']; ?>" <?php if ($perfil['Estado'] == 'Activo') {
											echo "checked='checked'";} else {'';} ?> onchange="f_Check(<?php echo $xEmprid; ?>,<?php echo $perfil['Id']; ?>)" value="<?php echo $perfil['Id']; ?>" />
									</div>
                                </td>
								<td>
									<div class="text-center">
										<div class="btn-group">
											<button <?php echo $xDisabledEdit ?> onclick="f_Editar(<?php echo $perfil['Id']; ?>)" id="btnEdit<?php echo $perfil['Id']; ?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title='Editar Perfil'>
												<i class='fa fa-edit'></i>
											</button>
										</div>
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

		
		<script>
			$(document).ready(function(){
				_mensaje = $('input#mensaje').val();

				if(_mensaje != ''){
					//mensajesalertify(_mensaje+"..!","S","top-center",5);
					mensajesweetalert("center","warning",_mensaje,false,1800);  
				}

				$(document).on("click",".btnEstado",function(e){
					_fila = $(this).closest("tr");
					_perfil = $(this).closest("tr").find('td:eq(0)').text(); 
					_descripcion = $(this).closest("tr").find('td:eq(1)').text(); 
        			console.log(_fila);
				});


			});

			function f_Editar(_perfid){
				$.redirect('?page=editperfil&menuid=<?php echo $menuid; ?>', {'idperfil': _perfid}); //POR METODO POST
			}

			function f_Check(_emprid, _perfid){
				//let _div = "div_" + _perfid;              
				let _check = $("#chk" + _perfid).is(":checked");
				let _btn = "btnEdit" + _perfid;
				let _td = "td" + _perfid;
				let _checked = "";
				let _disabled = "";
				let _classes = "badge badge-light-primary";

				if(_check){
					//$("#"+_div).removeClass("badge badge-light-danger");
					//$("#"+_div).addClass("badge badge-light-primary");
					//document.getElementById(_btn).disabled = false;
					//document.getElementById(_td).innerHTML  = "<div class='badge badge-light-primary'>Activo</div>";
					_tipo = "Activo";
					_checked = "checked='checked'";
				}else{
					//$("#"+_div).removeClass("badge badge-light-primary");
					//$("#"+_div).addClass("badge badge-light-danger");
					//document.getElementById(_btn).disabled = true;
					//document.getElementById(_td).innerHTML  = "<div class='badge badge-light-danger'>Inactivo</div>";
					_tipo = "Inactivo";
					_disabled = "disabled";
					_classes = "badge badge-light-danger";
				}

				 var _estado = '<td><div class="' + _classes + '">' + _tipo + ' </div>' ;

				 var _btnchk = '<td style="text-align:center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
				 			'<input class="form-check-input btnEstado" type="checkbox" id="chk' + _perfid + '" ' + _checked + ' onchange="f_Check(' +
				 			_emprid + ',' + _perfid + ')"' + ' value="' + _perfid + '"' + '/></div></td>';
				 			
				 var _boton = '<td><div class="text-center"><div class="btn-group"><button ' + _disabled + ' onclick="f_Editar(' + _perfid + ')" ' +
				 			'id="btnEdit' + _perfid + '"' + ' class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Editar Perfil" >' + 
				 			'<i class="fa fa-edit"></i></button></div></div></td>';

				TableData = $('#kt_ecommerce_report_shipping_table').DataTable();

				TableData.row(_fila).data([_perfil , _descripcion, _estado, _btnchk, _boton ]).draw();
				
				$parametros = {
                        xxIdPerfil: _perfid,
                        xxIdMeta: 0,
                        xxEmprid: _emprid,
                        xxTipo: _tipo
                    }				
				
				var xrespuesta = $.post("codephp/delnew_perfil.php", $parametros);
				xrespuesta.done(function(response){
					//console.log(response);
				});				

			}

		</script> 