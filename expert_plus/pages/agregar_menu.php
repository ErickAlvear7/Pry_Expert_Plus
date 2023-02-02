<?php 

require_once("dbcon/config.php");
require_once("dbcon/functions.php");

mysqli_query($con,'SET NAMES utf8');  
mysqli_set_charset($con,'utf8');	

$xServidor = $_SERVER['HTTP_HOST'];
$xFecha = strftime("%Y-%m-%d %H:%M:%S", time());

$xSQL = "SELECT tare_id AS Idtarea, empr_id AS Empid, tare_nombre as SubMenu, tare_ruta AS Ruta, CASE tare_estado WHEN 'A' THEN 'Activo' ";
$xSQL .= "ELSE 'Inactivo' END AS Estado FROM expert_tarea";
$expertsubmenu = mysqli_query($con, $xSQL);

?>
<div id="kt_content_container" class="container-xxl">
   <div class="card card-flush">
            <div class="card-toolbar d-flex align-self-end">
                <a href="?page=seg_menuadmin" class="btn btn-light-primary"><i class="las la-arrow-left"></i>Regresar</a>
            </div>	
        <div class="card-body">
            <div class="form-group">
                <div class="d-flex align-items-center mb-3">
                    <span class="font-weight-bold mr-2">Informacion Menu:</span> &nbsp; &nbsp;
                </div>
            </div>	
        </div>
        <div class="card-header">
                <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_1">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_2">Sub-Menu</a>
                    </li>
                </ul>	
        </div>
        <form action="m-0" class="form mb-15" method="post" id="kt_careers_form"> 
            <div class="tab-content" id="myTabContent">
                    <div class="card-header"> 
                        <div class="card-toolbar">
                            <button type="submit" class="btn btn-light-success"><i class="las la-save"></i>Guardar</button>
                        </div>
                    </div> 
                <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Menu</label>
                            <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="">
                        </div>
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Descripcion</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                            </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
                   <div class="card">
                        <div class="card-header border-0 pt-6">                
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <span class="svg-icon svg-icon-1 position-absolute ms-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <input type="text" data-kt-ecommerce-order-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Buscar Dato" />
                                </div>
                            </div>
                            <div class="card-toolbar">
                                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                                    <div class="w-150px me-3">
                                        <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Estado" data-kt-ecommerce-order-filter="status">
                                            <option></option>
                                            <option value="all">Todos</option>
                                            <option value="Activo">Activo</option>
                                            <option value="Inactivo">Inactivo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected">
                                    <div class="fw-bolder me-5">
                                    <span class="me-2" data-kt-customer-table-select="selected_count"></span>Selected</div>
                                    <button type="button" class="btn btn-danger" data-kt-customer-table-select="delete_selected">Delete Selected</button>
                                </div>
                            </div>
                                                
                        </div>
                        <div class="row d-flex justify-content-center">
                        <div class="card-body pt-0 ">
                            <table class="table align-middle table-row-dashed fs-6 gy-5 table-hover" id="kt_ecommerce_report_shipping_table" style="width: 100%;">
                               <thead>
                                    <tr class="text-start text-gray-400 fw-bolder fs-7 gs-0">
                                        <th>Seleccionar</th>
                                        <th style="display:none;">IdTarea</th>
                                        <th>SubMenu</th>
                                        <th>Estado</th>
                                        <th>Ruta</th>
                                    </tr>
                                </thead>
                               <tbody class="fw-bold text-gray-600">
                                    <?php foreach($expertsubmenu as $submenu){
                                        $xIdSub =  $menu['Idtarea'];
                                        ?>
                                        <?php 
                                            if($submenu['Estado'] == 'Activo'){
                                                $xTextColor = "badge badge-light-success";
                                            }else{
                                                $xTextColor = "badge badge-light-danger";
                                            }
                                        ?>
                                    <tr>
                                        <td style="text-align: center;" >
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input class="form-check-input"  id="chk<?php echo $submenu['Idtarea']; ?>" type="checkbox" value="1" />
                                            </div>
                                        </td>
                                        <td style="display:none;"><?php echo $submenu['Idtarea']; ?></td>
                                        <td><?php echo $submenu['SubMenu']; ?></td>
                                        <td>
                                        <div class="<?php echo $xTextColor; ?>"><?php echo $submenu['Estado']; ?></div>
                                        </td>
                                        <td><?php echo $submenu['Ruta']; ?></td>
                                    </tr>
                                    <?php } ?>  
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form> 
   </div>

</div>

