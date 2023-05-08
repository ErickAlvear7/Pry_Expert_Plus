<?php
	
	//error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	    	

	require_once("dbcon/config.php");
	require_once("dbcon/functions.php");

	mysqli_query($con,'SET NAMES utf8');  
	mysqli_set_charset($con,'utf8');	

	//$xServidor = $_SERVER['HTTP_HOST'];
	$page = isset($_GET['page']) ? $_GET['page'] : "index";
    $mensaje = (isset($_POST['mensaje'])) ? $_POST['mensaje'] : '';
    
	$menuid = $_GET['menuid'];
	
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

    $xUsuaid = $_SESSION["i_usuaid"];

    $xSQL = "SELECT clie_id AS IdCliente, clie_nombre AS Cliente, clie_descripcion AS Descrip, CASE clie_estado WHEN 'A' THEN 'Activo' ELSE 'Inactivo' END AS Estado ";
    $xSQL .="FROM `expert_cliente` ";
    $all_clie = mysqli_query($con, $xSQL);

    

?>

<div id="kt_content_container" class="container-xxl">
    <input type="hidden" id="mensaje" value="<?php echo $mensaje ?>">
    <div class="card">
        <div class="card-header border-0 pt-6">
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
            </div> 
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                <a href="?page=addclienteprod&menuid=<?php echo $menuid; ?>" class="btn btn-primary">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
                            </svg>
                        </span>
                    Nuevo CLiente
                </a>
		    </div>                       
        </div>
		<div class="card-body pt-0">
			<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_report_shipping_table" style="width: 100%;">
				<thead>
					<tr class="text-start text-gray-800 fw-bolder fs-7 gs-0">
					    <th style="display:none;">Id</th>
						<th class="min-w-125px">Cliente</th>
						<th class="min-w-125px">Descripcion</th>
						<th class="min-w-125px">Estado</th>
                        <th class="min-w-125px">Logo</th>
						<th class="min-w-125px">Status</th>
                        <th class="min-w-125px" style="text-align: center;">Opciones</th>
					</tr>
				</thead>
				<tbody class="fw-bold text-gray-600">
                    <?php foreach($all_clie as $clie){ 
                        
                        $xClieid = $clie['IdCliente'];
                        $xCliente = $clie['Cliente'];
                        $xDescrip = $clie['Descrip'];
                        $xEstado = $clie['Estado'];
                        
                    ?>
                     <?php 
                       $xCheking = '';
                       $xDisabledEdit = '';

                       if($xEstado == 'Activo'){
                            $xCheking = 'checked="checked"';
                            $xTextColor = "badge badge-light-primary";
                        }else{
                            $xTextColor = "badge badge-light-danger";
                            $xDisabledEdit = 'disabled';
                        }
                    
                    ?>
			
					<tr>
					    <td style="display:none;"><?php echo $xClieid; ?></td>
						<td><?php echo $xCliente; ?></td>
						<td><?php echo $xDescrip; ?></td>
						<td id="td_<?php echo $xClieid; ?>">
                           <div class="<?php echo $xTextColor; ?>"><?php echo $xEstado; ?></div>
                        </td>
                        <td class="d-flex align-items-center">
                            <a href="?page=modprestadora&menuid=" class="symbol symbol-50px">
                                <span class="symbol-label" style="background-image:url(Cliente/1683307394_ford-gt-atras_3840x2160_xtrafondos.com.jpg);"></span>
                            </a>
                            <span class="fw-bolder"></span>
                        </td>
                        <td>
                            <div class="text-center">
								<div class="form-check form-check-sm form-check-custom form-check-solid">
									<input <?php echo $xCheking; ?> class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk" 
                                       onchange="f_UpdateEstado()" value=""/>
								</div>
							</div>
						</td>
						<td>
                            <div class="text-center">
								<div class="btn-group">
									<button id="btnEditar_" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" onclick="" title='Editar Producto'>
										<i class='fa fa-edit'></i>
									</button>												 
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

    <script>

        $(document).ready(function(){

            _mensaje = $('input#mensaje').val();

            if(_mensaje != ''){
                mensajesweetalert("center","success",_mensaje,false,1900);  
            }

        });	

      

    </script>

   