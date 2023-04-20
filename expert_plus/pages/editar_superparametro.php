<?php
    //error_reporting(E_ALL);
    ini_set('display_errors', 0);

    putenv("TZ=America/Guayaquil");
    date_default_timezone_set('America/Guayaquil');	    	

    require_once("dbcon/config.php");
    require_once("dbcon/functions.php");

    mysqli_query($con,'SET NAMES utf8');  
    mysqli_set_charset($con,'utf8');	

    $xServidor = $_SERVER['HTTP_HOST'];
    $page = isset($_GET['page']) ? $_GET['page'] : "index";

    $idpaca = $_POST['idparam'];
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

 

    $xSQL = "SELECT paca_nombre AS Nombre, paca_descripcion AS Descri, paca_estado AS Estado FROM `expert_superparametro_cabecera` WHERE paca_id = $idpaca ";
    $all_paca = mysqli_query($con, $xSQL);
    foreach($all_paca as $paca){

        $xNomPaca = $paca['Nombre'];
        $xDescPaca = $paca['Descri'];

    }

    $xSQL = "SELECT pade_id AS Idpade,pade_orden AS Orden, pade_nombre AS Detalle, pade_valorV AS ValorV, pade_valorI AS ValorI,";
    $xSQL .= "pade_estado AS Estado FROM `expert_superparametro_detalle` WHERE paca_id = $idpaca ";
    $all_pade = mysqli_query($con, $xSQL);

    $xSQL = "SELECT  pade_orden AS Orden FROM `expert_superparametro_detalle` WHERE paca_id = $idpaca ORDER BY pade_orden DESC LIMIT 1 ";
    $orden = mysqli_query($con, $xSQL);
    foreach($orden as $ord){
        $xOrdenDet = $ord['Orden'];
    }
    

?>

<div id="kt_content_container" class="container-xxl">
	<div class="card card-flush">
		<div class="card-body pt-0">
            <br/>
            <div class="card-header">
                <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8">
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_settings_general">											
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M16.0173 9H15.3945C14.2833 9 13.263 9.61425 12.7431 10.5963L12.154 11.7091C12.0645 11.8781 12.1072 12.0868 12.2559 12.2071L12.6402 12.5183C13.2631 13.0225 13.7556 13.6691 14.0764 14.4035L14.2321 14.7601C14.2957 14.9058 14.4396 15 14.5987 15H18.6747C19.7297 15 20.4057 13.8774 19.912 12.945L18.6686 10.5963C18.1487 9.61425 17.1285 9 16.0173 9Z" fill="currentColor" />
                                <rect opacity="0.3" x="14" y="4" width="4" height="4" rx="2" fill="currentColor" />
                                <path d="M4.65486 14.8559C5.40389 13.1224 7.11161 12 9 12C10.8884 12 12.5961 13.1224 13.3451 14.8559L14.793 18.2067C15.3636 19.5271 14.3955 21 12.9571 21H5.04292C3.60453 21 2.63644 19.5271 3.20698 18.2067L4.65486 14.8559Z" fill="currentColor" />
                                <rect opacity="0.3" x="6" y="5" width="6" height="6" rx="3" fill="currentColor" />
                            </svg>
                        Parametro</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_settings_store">
                            <span class="svg-icon svg-icon-2 me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M18 10V20C18 20.6 18.4 21 19 21C19.6 21 20 20.6 20 20V10H18Z" fill="currentColor" />
                                    <path opacity="0.3" d="M11 10V17H6V10H4V20C4 20.6 4.4 21 5 21H12C12.6 21 13 20.6 13 20V10H11Z" fill="currentColor" />
                                    <path opacity="0.3" d="M10 10C10 11.1 9.1 12 8 12C6.9 12 6 11.1 6 10H10Z" fill="currentColor" />
                                    <path opacity="0.3" d="M18 10C18 11.1 17.1 12 16 12C14.9 12 14 11.1 14 10H18Z" fill="currentColor" />
                                    <path opacity="0.3" d="M14 4H10V10H14V4Z" fill="currentColor" />
                                    <path opacity="0.3" d="M17 4H20L22 10H18L17 4Z" fill="currentColor" />
                                    <path opacity="0.3" d="M7 4H4L2 10H6L7 4Z" fill="currentColor" />
                                    <path d="M6 10C6 11.1 5.1 12 4 12C2.9 12 2 11.1 2 10H6ZM10 10C10 11.1 10.9 12 12 12C13.1 12 14 11.1 14 10H10ZM18 10C18 11.1 18.9 12 20 12C21.1 12 22 11.1 22 10H18ZM19 2H5C4.4 2 4 2.4 4 3V4H20V3C20 2.4 19.6 2 19 2ZM12 17C12 16.4 11.6 16 11 16H6C5.4 16 5 16.4 5 17C5 17.6 5.4 18 6 18H11C11.6 18 12 17.6 12 17Z" fill="currentColor" />
                                </svg>
                            </span>
                        Detalle</a>
                    </li>
                </ul>
                <a href="?page=param_sistema&menuid=<?php echo $menuid;?>" class="btn btn-icon btn-light-primary btn-sm ms-auto me-lg-n7">
                    <span class="svg-icon svg-icon-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M11.2657 11.4343L15.45 7.25C15.8642 6.83579 15.8642 6.16421 15.45 5.75C15.0358 5.33579 14.3642 5.33579 13.95 5.75L8.40712 11.2929C8.01659 11.6834 8.01659 12.3166 8.40712 12.7071L13.95 18.25C14.3642 18.6642 15.0358 18.6642 15.45 18.25C15.8642 17.8358 15.8642 17.1642 15.45 16.75L11.2657 12.5657C10.9533 12.2533 10.9533 11.7467 11.2657 11.4343Z" fill="currentColor" />
                        </svg>
                    </span>
                </a>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="kt_ecommerce_settings_general" role="tabpanel"> 
                    <div class="card-body pt-0">
                        <div class="row g-9 mb-7">
                            <div class="col-md-12 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">Parametro</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Nombre del parametro"></i>
                                </label>
                                <input type="text" class="form-control form-control-solid" id="txtParaEdit" name="txtParaEdit" minlength="5" maxlength="100" value="<?php echo $xNomPaca; ?>" />
                            </div>
                        </div>
                        <div class="row g-9 mb-7">
                            <div class="col-md-12 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">Descripcion</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Descripcion del parametro"></i>
                                </label>
                                <textarea class="form-control form-control-solid" name="txtDescEdit" id="txtDescEdit" maxlength="150" onkeydown="return (event.keyCode!=13);"><?php echo $xDescPaca; ?></textarea>
                            </div>
                        </div>
                        <div class="card-toolbar d-flex align-self-end">
                           <button type="button" id="btnGuardarEdit" onclick="f_Guardar(<?php echo $idpaca; ?>)" class="btn btn-primary">Guardar</button>
                        </div>	
                    </div>
                </div>
                <div class="tab-pane fade" id="kt_ecommerce_settings_store" role="tabpanel">
                    <br/>
                    <div class="card-body pt-0">
                        <div class="row g-9 mb-7">
                            <div class="col-md-4 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">Detalle</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Especifique el nombre del detalle"></i>
                                </label>
                                <input type="text" class="form-control form-control-solid" id="txtDetalle" name="txtDetalle" minlength="2" maxlength="100" placeholder="nombre del detalle" value="" />                       
                            </div>
                                <div class="col-md-3 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">Valor Texto</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="solo valor en texto"></i>
                                </label>
                                <input type="text" class="form-control form-control-solid" id="txtValorV" name="txtValorV" minlength="1" maxlength="50" placeholder="valor texto" value="" />                       
                            </div>
                                <div class="col-md-3 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                <span class="required">Valor Entero</span>
                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="solo valores enteros"></i>
                                </label>
                                <input type="text" class="form-control form-control-solid" id="txtValorI" name="txtValorI" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" minlength="1" maxlength="10" placeholder="valor entero" value="" />                       
                            </div>
                            <div class="col-md-2 fv-row">
                                <button class="btn btn-sm btn-light-primary" id="btnAgregar">
                                        <span class="svg-icon svg-icon-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                                <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
                                            </svg>
                                        </span>
                                    Agregar    
                                </button>
                            </div>
                        </div>
                        <br/>
                        <div class="d-flex align-items-center position-relative my-1">
                            <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
                                    <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
                                </svg>
                            </span>
                            <input type="text" data-kt-ecommerce-order-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Buscar Dato" />
                        </div>
                        <hr class="bg-primary border-2 border-top border-primary">
                        <div class="mh-375px scroll-y me-n7 pe-7">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_report_shipping_table" style="width: 100%;">
                                <thead>
                                    <tr class="text-start text-gray-800 fw-bolder fs-7 gs-0">
                                        <th style="display:none;">Id</th>
                                        <th>Detalle</th>
                                        <th>Valor Texto</th>
                                        <th>Valor entero</th>
                                        <th>Estado</th>
                                        <th style="text-align: center;">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-bold text-gray-600">
                                    <?php 
                                    foreach($all_pade as $pade){

                                        $xPadeId = $pade['Idpade'];
                                        $xPadeNom = $pade['Detalle'];
                                        $xPadeValorV = $pade['ValorV'];
                                        $xPadeValorI = $pade['ValorI'];
                                        $xPadeEstado = $pade['Estado'];
                                    ?>
                                    <?php 
                                        $xCheking = '';
                                        $xDisabledEdit = '';

                                        if($xPadeEstado == 'A'){
                                            $xCheking = 'checked="checked"';
                                        
                                        }else{
                                            $xDisabledEdit = 'disabled';
                                        }

                                        if($xPadeValorI == 0){
                                            $xPadeValorI = '';
                                        
                                        }
                                        
                                        ?>
                                    <tr id="row_<?php echo $xPadeId; ?>">
                                        <td style="display: none;">
                                            <?php echo $xPadeId; ?>
                                        </td>               
                                        <td><?php echo $xPadeNom; ?></td>
                                        <td><?php echo $xPadeValorV; ?></td>
                                        <td><?php echo $xPadeValorI; ?></td>
                                        <td style="text-align:center">
                                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                <input <?php echo $xCheking; ?>  class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk<?php echo $xPadeId; ?>" 
                                                   onchange="f_UpdateEstado(<?php echo $xPadeId;?>)" value="<?php echo $xPadeId;?>" />
                                            </div>
                                        </td> 
                                        <td>
                                            <div class="text-center">
                                                <div class="btn-group">	
                                                    <button type="button" id="btnEditar_<?php echo $xPadeId;?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" <?php echo $xDisabledEdit;?> title='Editar Detalle'>
                                                        <i class="fa fa-edit"></i>
                                                    </button> 
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>     
                </div>
            </div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal_detalle" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered mw-550px">
    <div class="modal-content">
        <div class="modal-header" id="parametro_header">
            <h5 class="modal-title" id="exampleModalLabel">Editar Detalle</h5>
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
            <div class="d-flex flex-column mb-7 fv-row">
                <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                    <span class="required">Detalle</span>
                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Nombre del detalle"></i>
                </label>
                <input type="text" class="form-control form-control-solid" id="txtDetalleEdit" name="txtDetalleEdit"  minlength="2" maxlength="100" />
            </div>
            <div class="d-flex flex-column mb-7 fv-row">
                <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                    <span class="required">Valor Texto</span>
                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="solo valor en texto"></i>
                </label>
                <input type="text" class="form-control form-control-solid" id="txtValorVedit" name="txtValorVedit" minlength="1" maxlength="50" />
            </div>
            <div class="d-flex flex-column mb-7 fv-row">
                <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                    <span class="required">Valor Entero</span>
                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="solo valores enteros"></i>
                </label>
                <input type="text" class="form-control form-control-solid" id="txtValorIedit" name="txtValorIedit" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" minlength="1" maxlength="10" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" id="btnGuardar" class="btn btn-primary">Grabar</button>
            </div>
            <input type="hidden" id="txtDetalleold" name="txtDetalleold" />
            <input type="hidden" id="txtValortexto" name="txtDetalleold" />
            <input type="hidden" id="txtValorentero" name="txtValorentero" />
        </div>
    </div>
  </div>
</div>


<script>
    var _idpaca,_idpade,_paisid;

    $(document).ready(function(){
        _parametroold = $('#txtParaEdit').val();

    });

    $('#btnAgregar').click(function(){

        var _estado = 'A';
        var _pacaid = '<?php echo $idpaca; ?>';
        _paisid = '<?php echo  $xPaisid; ?>';
        var _ordendet = '<?php echo  $xOrdenDet; ?>';
       
        if($.trim($('#txtDetalle').val()).length == 0)
        {           
            mensajesweetalert("center","warning","Ingrese Detalle",false,1900);
            return false;          
        }

        if($.trim($('#txtValorV').val()).length == 0 && $.trim($('#txtValorI').val()).length == 0 )
        {    
            mensajesweetalert("center","warning","Ingrese Valor Texto o Valor Entero..!",false,1900);        
            return false;
            
        }

        if($.trim($('#txtValorV').val()).length > 0 && $.trim($('#txtValorI').val()).length > 0 )
        {    
            mensajesweetalert("center","warning","Ingrese Solo Valor Texto o Valor Entero..!",false,1900);         
            return false;
       
        }

        var _detalle = $.trim($('#txtDetalle').val());
        var _valorV =  $.trim($('#txtValorV').val());

        _ordendet++;

        if($.trim($('#txtValorI').val()).length == 0){
            var _valorI = 0;
        }else{
            _valorI = $.trim($('#txtValorI').val());
        }

                 $datosDetalle ={
                    xxDetalle: _detalle,
                    xxValorV: _valorV,
                    xxValorI: _valorI
                }

                var xrespuesta = $.post("codephp/consultar_supdetalle.php", $datosDetalle);
                xrespuesta.done(function(response){
                    if(response == 0){

                       // debugger;

                        $parametros ={
                            xxPacaId: _pacaid,
                            xxDetalle: _detalle,
                            xxValorV: _valorV,
                            xxValorI: _valorI,
                            xxEstado: _estado,
                            xxOrden: _ordendet
                         
                        }

                        $.ajax({
							url: "codephp/grabar_superdetalle.php",
							type: "POST",
							dataType: "json",
							data: $parametros,          
							success: function(response){ 
								if(response != 0){

								           
                                    _padeid = response;
                                    _padenom = _detalle;
                                    _padev = _valorV;
                                    _padei = _valorI;
                                    _checked = "checked='checked'";

                                    var _btnChk = '<td style="text-align:center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
                                                   '<input ' + _checked + ' class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk' + _padeid + '"' +
                                                   '</div></td>';
                                    
                                    var _btnGrup = '<td><div class="text-center"><div class="btn-group">' +
                                                   '<button type="button" id="btnEditar" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" id="">' +
                                                   '<i class="fa fa-edit"></i></button></div></div></td>';
                                    

                                    TableData = $('#kt_ecommerce_report_shipping_table').DataTable();  
                                    TableData.column(0).visible(0);

                                    TableData.row.add([_padeid, _padenom, _padev, _padei, _btnChk,_btnGrup]).draw();

                                    $("#txtDetalle").val("");
                                    $("#txtValorV").val("");
                                    $("#txtValorI").val("");

                                    $.redirect('?page=editsuperparametro&menuid=<?php echo $menuid; ?>', {idparam: <?php echo $idpaca; ?>}); //POR METODO POST

								}                                                                         
							},
							error: function (error){
								console.log(error);
							}                            
						});

                    }else{

                        mensajesweetalert("center","warning","Nombre Detalle ya Existe y/o Valor Texto u Valor Entero..!",false,1800);
                    }

                });
        
    });

    //Editar Detalle Modal

    $(document).on("click",".btnEditar",function(){
        $("#modal_detalle input").val("");
        $("#txtValorVedit").prop("disabled",false);
        $("#txtValorIedit").prop("disabled",false);      

        _idpaca = '<?php echo $idpaca; ?>';
        _fila = $(this).closest("tr");
        var _data = $('#kt_ecommerce_report_shipping_table').dataTable().fnGetData(_fila);
         _idpade = _data[0];

                $parametros = {
					xxPadeid: _idpade,
					xxPacaid: _idpaca
				}

                $.ajax({
					url: "codephp/editar_supdetalles.php",
					type: "POST",
					dataType: "json",
					data: $parametros,          
					success: function(data){ 

                     
                     //console.log(data);
                        var _nombre = data[0]['Nombre'];
                        var _valorv = data[0]['ValorT'];
                        var _valori = data[0]['ValorI'];


                        $("#txtDetalleEdit").val(_nombre);
                        $("#txtValorVedit").val(_valorv);
                        $("#txtValorIedit").val(_valori);

                        $("#txtDetalleold").val(_nombre);
                        $("#txtValortexto").val(_valorv);
                        $("#txtValorentero").val(_valori);

                   
						                                   
                        if(_valorv == ''){
                            $("#txtValorVedit").prop("disabled",true);   
                        }
                        if(_valori == ''){
                            $("#txtValorIedit").prop("disabled",true);   
                        }

					},
					error: function (error){
						console.log(error);
					}                            
				}); 

              $("#modal_detalle").modal("show");

    });

    //Guardar Editar Detalle

    $('#btnGuardar').click(function(e){
     
       var _padeid = _idpade;
       var _pacaid =   _idpaca
       var _consultar = 'NO';

       var _nombre = $.trim($("#txtDetalleEdit").val());
       var _valorV = $.trim($("#txtValorVedit").val());
       var _valorI = $.trim($('#txtValorI').val());

       var _nombreold = $.trim($("#txtDetalleold").val());
       var _valovold = $.trim($("#txtValortexto").val());
       var _valoriold = $.trim($("#txtValorentero").val());

       if($.trim($('#txtValorIedit').val()).length == 0){
            var _valorI = 0;
        }else{
            _valorI = $.trim($('#txtValorIedit').val());
        }
        
        if($.trim($('#txtValorentero').val()).length == 0){
            var _valoriold = 0;
        }else{
            _valoriold = $.trim($('#txtValorentero').val());
        }        

        if(_nombreold != _nombre){
            _consultar = 'SI';
        }

        if(_valorV != ''){
            if(_valorV != _valovold){
                _consultar = 'SI';
            }
        }

        if(_valorI != '0'){
            if(_valorI != _valoriold){
                _consultar = 'SI'; 
            }
        }        

       if($.trim($('#txtDetalleEdit').val()).length == 0)
        {           
            mensajesweetalert("center","warning","Ingrese Detalle",false,1800);
            return false;
        }

        if($.trim($('#txtValorVedit').val()).length == 0 && $.trim($('#txtValorIedit').val()).length == 0 )
        {    
            mensajesweetalert("center","warning","Ingrese Valor Texto o Valor Entero..!",false,1800);        
            return false;
        }

        if($.trim($('#txtValorVedit').val()).length > 0 && $.trim($('#txtValorIedit').val()).length > 0 )
        {    
            mensajesweetalert("center","warning","Ingrese Solo Valor Texto o Valor Entero..!",false,1800);         
            return false;
        }

        $datosDetalle ={
            xxDetalle: _nombre,
            xxValorV: _valorV,
            xxValorI: _valorI,
            xxDetalleold: _nombreold,
            xxValorVold: _valovold,
            xxValorIold: _valoriold               
        }

        if(_consultar == 'SI'){
            var xrespuesta = $.post("codephp/consultar_supdetalledit.php", $datosDetalle);
            xrespuesta.done(function(response){
                if(response.trim() == 0){

                    $parametros ={
                        xxPacaId: _pacaid,
                        xxPadeId: _padeid,
                        xxDetalle: _nombre,
                        xxValorV: _valorV,
                        xxValorI: _valorI                    
                    }
                    
                    var xresponse = $.post("codephp/update_supdetalle.php", $parametros);
                    xresponse.done(function(response){    

                        if(response.trim() == 'OK'){

                            _padeid = _padeid
                            _padenom = _nombre;
                            _padev = _valorV;
                            _padei = _valorI;

                            if(_padei == 0){
                                _padei = '';
                            }
                            _checked = "checked='checked'";

                            var _btnChk = '<td style="text-align:center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
                                            '<input ' + _checked + ' class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk' + _padeid + '"' +
                                            '</div></td>';
                            
                            var _btnGrup = '<td><div class="text-center"><div class="btn-group">' +
                                            '<button type="button" id="btnEditar" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" id="">' +
                                            '<i class="fa fa-edit"></i></button></div></div></td>';

                            TableData = $('#kt_ecommerce_report_shipping_table').DataTable();  
                            TableData.column(0).visible(0);

                            TableData.row(_fila).data([_padeid, _padenom, _padev, _padei, _btnChk, _btnGrup ]).draw();

                            $("#modal_detalle").modal("hide");
                        }
                    }); 
            
                }else{

                    mensajesweetalert("center","warning","Nombre Detalle ya Existe y/o Valor Texto u Valor Entero..!",false,2800);
                }

            });
        }else{
            $parametros ={
                xxPacaId: _pacaid,
                xxPadeId: _padeid,
                xxDetalle: _nombre,
                xxValorV: _valorV,
                xxValorI: _valorI,
            }
            
            var xresponse = $.post("codephp/update_supdetalle.php", $parametros);
            xresponse.done(function(response){    

                if(response.trim() == 'OK'){

                    _padeid = _padeid
                    _padenom = _nombre;
                    _padev = _valorV;
                    _padei = _valorI;
                    _checked = "checked='checked'";

                    var _btnChk = '<td style="text-align:center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
                                    '<input ' + _checked + ' class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk' + _padeid + '"' +
                                    '</div></td>';
                    
                    var _btnGrup = '<td><div class="text-center"><div class="btn-group">' +
                                    '<button type="button" id="btnEditar" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" id="">' +
                                    '<i class="fa fa-edit"></i></button></div></div></td>';

                    TableData = $('#kt_ecommerce_report_shipping_table').DataTable();  
                    TableData.column(0).visible(0);

                    TableData.row(_fila).data([_padeid, _padenom, _padev, _padei, _btnChk, _btnGrup ]).draw();

                    $("#modal_detalle").modal("hide");
                }
            });             
        }
    });

    //Guardar Editar Paramentro
    function f_Guardar(_idpaca){

        var _parametro = $.trim($("#txtParaEdit").val());
        var _descripcion = $.trim($("#txtDescEdit").val());

        if(_parametro == ''){                        
            mensajesweetalert("center","warning","Ingrese Nombre del Parametro..!!",false,1800);
            return;
        }

        $datosParam ={
            xxParametro: _parametro
        }
        
        if(_parametroold != _parametro){
            var xrespuesta = $.post("codephp/consultar_superparametro.php", $datosParam);
            xrespuesta.done(function(response){
                if(response.trim() == 0){

                    $parametros ={
                        xxPacaId: _idpaca,
                        xxParametro: _parametro,
                        xxDescripcion: _descripcion 
                    }
                    
                    var xresponse = $.post("codephp/update_superparametro.php", $parametros);
                    xresponse.done(function(response){            

                        if(response.trim() == 'OK'){
                            $.redirect('?page=param_sistema&menuid=<?php echo $menuid; ?>', {'mensaje': 'Actualizado con Exito'}); //POR METODO POST            
                        }

                    }); 
                }else{
                    mensajesweetalert("center","warning","Nombre del Parametro ya Existe..!",false,1800);
                }

            });
        }else{
            $parametros ={
                xxPacaId: _idpaca,
                xxParametro: _parametro,
                xxDescripcion: _descripcion
                
            }
            
            var xresponse = $.post("codephp/update_superparametro.php", $parametros);
            xresponse.done(function(response){            

                if(response.trim() == 'OK'){
                    $.redirect('?page=param_sistema&menuid=<?php echo $menuid; ?>', {'mensaje': 'Actualizado con Exito'}); //POR METODO POST            
                }

            });
        }
    }

    
     //cambiar estado y desactivar botones en linea
    function f_UpdateEstado(_padeid){

        let _check = $("#chk" + _padeid).is(":checked");
        let _checked = "";
        let _disabled = "";
        let _btnedit = "btnEditar_" + _padeid;

        if(_check){
            _estado = "Activo";
            _disabled = "";
            _checked = "checked='checked'";
            $('#'+_btnedit).prop("disabled",false);                    
        }else{                    
            _estado = "Inactivo";
            _disabled = "disabled";
            $('#'+_btnedit).prop("disabled",true);
        }


            $parametros = {
                xxPadeid: _padeid,
                xxEstado: _estado
            }

            var xrespuesta = $.post("codephp/delnew_supdetalle.php", $parametros);
            xrespuesta.done(function(response){
            });	     

    }

      //Desplazar-modal

   $("#modal_detalle").draggable({
        handle: ".modal-header"
    });
    
 

  



</script> 	