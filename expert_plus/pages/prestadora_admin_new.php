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

    $xPaisid = $_SESSION["i_paisid"];
    $xEmprid = $_SESSION["i_emprid"];
    $xUsuaid = $_SESSION["i_usuaid"];

    $xSQL = "SELECT (SELECT prv.ciudad FROM `provincia_ciudad` prv WHERE prv.prov_id=pre.prov_id) AS Ciudad,pre.pres_nombre AS Prestadora,";
    $xSQL .= "(SELECT pde.pade_nombre FROM `expert_parametro_detalle` pde, `expert_parametro_cabecera` pca WHERE pde.paca_id=pca.paca_id AND pca.paca_nombre='Tipo Sector' AND pde.pade_valorv=pre.pres_sector) AS Sector,(SELECT pde.pade_nombre FROM `expert_parametro_detalle` pde, `expert_parametro_cabecera` pca WHERE pde.paca_id=pca.paca_id AND pca.paca_nombre='Tipo Prestador' AND pde.pade_valorv=pre.pres_tipoprestador) AS TipoPrestador,pre.pres_logo AS Logo,pre.pres_estado AS Estado,pre.pres_ubicacion AS Ubicacion,pre.pres_url AS Url,pre.pres_id AS Id FROM `expert_prestadora` pre ";
    $all_prestador = mysqli_query($con, $xSQL);

    $xSQL = "SELECT COUNT(*) as Contar  FROM `expert_prestadora` pre WHERE pre.pais_id=$xPaisid AND pre.empr_id=$xEmprid";
	$all_contar = mysqli_query($con, $xSQL);
    foreach($all_contar as $contar){ 
        $xTotalPrestadores = $contar['Contar'];
    }





?>

<div id="kt_content_container" class="container-xxl">
    <div class="card">
        <div class="card-header border-0 pt-6">
			<div class="card-title">
				<div class="d-flex align-items-center position-relative my-1">
					<span class="svg-icon svg-icon-1 position-absolute ms-6">
					    <i class="fa fa-search fa-1x" style="color:#3B8CEC;" aria-hidden="true"></i> 
					</span>
					<input type="text" data-kt-user-table-filter="search" class="form-control w-250px ps-14" placeholder="Buscar Usuario" />
				</div>
			</div>	
			
            <div class="card-toolbar">
				<button type="button" data-repeater-create="" class="btn btn-light-primary btn-sm" id="btnNuevo"><i class="fa fa-plus-circle" aria-hidden="true"></i>
						Nuevo Prestador
				</button>
		    </div>			
		</div>
        <div class="card-body py-4" >
            <table class="table align-middle table-row-dashed table-hover fs-6 gy-5" id="kt_table_users" style="width: 100%;">
                <thead>
					<tr class="text-start text-gray-800 fw-bolder fs-7 text-uppercase gs-0">
						<th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
					</tr>
				</thead>
                <tbody class="text-gray-600 fw-bold">
                    <?php 

                        $xCantidad = 0;          
                        foreach($all_prestador as $presta){
                            $xId = $presta['Id'];
                            $xCiudad = trim($presta['Ciudad']);
                            $xPrestador = trim($presta['Prestadora']);
                            $xSector = trim($presta['Sector']);
                            $xTipoPresta = trim($presta['TipoPrestador']);
                            $xLogo = trim($presta['Logo']);
                            $xEstado = trim($presta['Estado']);
                            $xUbicacion = trim($presta['Ubicacion']);
                            $xUrl = trim($presta['Url']);

                            if($xLogo == ''){
                                $xLogo = 'logo.png';
                            }

                            $xCantidad = $xCantidad + 1; 
                        ?>
                            <?php 

                                $chkEstado = '';
                                $xDisabledEdit = '';
                                $xTarget = '';
                                $xTargeturl = '';

                                if($xEstado == 'A'){
                                    $xEstado = 'ACTIVO';
                                    $chkEstado = 'checked="checked"';
                                    $xTextColor = "badge badge-light-primary";
                                }else{
                                    $xEstado = 'INACTIVO';
                                    $xTextColor = "badge badge-light-danger";
                                    $xDisabledEdit = 'disabled';
                                }

                                if($xUrl != ''){
                                    $xTargeturl = 'target="_blank"'.' '. 'rel="noopener noreferrer"';
                                }else{
                                    $xUrl="#";   
                                }
                                
                                if($xUbicacion != ''){
                                    $xTarget =  'target="_blank"' .' '. 'rel="noopener noreferrer"';
                                }else{
                                    $xUbicacion ="#";    
                                }   

                            ?>
                            <?php if($xCantidad == 1 ){ ?>
                               <tr> 
                            <?php } ?>
                                    <td style="width: 1%;"></td>
                                    <td style="width: 32%;">
                                        <div class="card card-flush h-md-100">
                                            <div class="card-body d-flex flex-center flex-column pt-12 p-9">
                                                <div class="symbol symbol-65px symbol-circle mb-5">
                                                    <img src="assets/images/users/<?php echo $xLogo; ?>" class="w-150" />
                                                    <div class="bg-success position-absolute border border-4 border-white h-15px w-15px rounded-circle translate-middle start-100 top-100 ms-n3 mt-n3"></div>
                                                </div>   
                                                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic"><?php echo $xPrestador; ?></h2>
                                                <div id="divEstado_"  class="<?php echo $xTextColor; ?>"><?php echo $xEstado; ?></div>
                                                <div class="text-center">
                                                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                        <input class="form-check-input h-20px w-20px border-primary" type="checkbox" id="" 
                                                            onchange="" value=""/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer flex-wrap pt-0">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="d-grid gap-2">
                                                            <button type="button" class="btn btn-light-primary border border-primary btn-sm" onclick=""><i class="las la-pencil-alt me-1" aria-hidden="true"></i>Editar Prestador</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                            <?php if($xCantidad == 3) { $xTotalPrestadores = $xTotalPrestadores - $xCantidad; $xCantidad = 0; ?>
                                    <td style="width: 1%;"></td>
                                </tr>
                            <?php } ?>
                            
                    <?php } ?>
                    <?php
						if($xTotalPrestadores == 1) { ?>
							<td style="width: 1%;"></td>
							<td style="width: 32%;"></td>
							<td style="width: 1%;"></td>
							<td style="width: 32%;"></td>
							<td style="width: 1%;"></td>
							</tr>
					<?php  }elseif($xTotalPrestadores == 2) { ?>
							<td style="width: 1%;"></td>
							<td style="width: 32%;"></td>
							<td style="width: 1%;"></td>
							</tr>
					<?php } ?>
                   

                </tbody>
                
            </table>

        </div>
        
    </div>


</div>