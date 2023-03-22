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

    $xSQL = "SELECT paca_id AS Idpaca,paca_nombre AS Parametro,paca_descripcion AS Descrip, ";
    $xSQL .= "CASE paca_estado WHEN 'A' THEN 'Activo' ELSE 'Inactivo' END AS Estado ";
    $xSQL .= "FROM `expert_parametro_cabecera` WHERE empr_id=$xEmprid AND pais_id=$xPaisid";
    $all_param = mysqli_query($con, $xSQL);

?>

<div id="kt_content_container" class="container-xxl">

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
            <div class="card-toolbar">
                <button class="btn btn-primary" id="nuevoParametro">
                    <span class="svg-icon svg-icon-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2" rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                            <rect x="4.36396" y="11.364" width="16" height="2" rx="1" fill="currentColor" />
                        </svg>
                    </span>
                    Nuevo Parametro
                </button>
		    </div>                       
        </div>
		<div class="card-body pt-0">
			<table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_report_shipping_table" style="width: 100%;">
				<thead>
					<tr class="text-start text-gray-800 fw-bolder fs-7 gs-0">
					    <th style="display:none;">Id</th>
						<th class="min-w-125px">Parametro</th>
						<th class="min-w-125px">Descripcion</th>
						<th class="min-w-125px">Estado</th>
						<th class="min-w-125px">Status</th>
                        <th class="min-w-125px" style="text-align: center;">Opciones</th>
					</tr>
				</thead>
				<tbody class="fw-bold text-gray-600">
					<?php 
                       foreach($all_param as $paca){

                        $xParaid = $paca['Idpaca'];
                        $xParam = $paca['Parametro'];
                        $xDesc = $paca['Descrip'];
                        $xEstado = $paca['Estado'];
                    ?>
                    <?php 
                       $xCheking = '';

                       if($xEstado == 'Activo'){
                            $xCheking = 'checked="checked"';
                            $xTextColor = "badge badge-light-primary";
                        }else{
                            $xTextColor = "badge badge-light-danger";
                        }
                    
                    ?>
					<tr>
					    <td style="display:none;"><?php echo $xParaid ?></td>
						<td><?php echo $xParam ?></td>
						<td><?php echo $xDesc ?></td>
						<td>
                           <div class="<?php echo $xTextColor; ?>"><?php echo $xEstado ?></div>
                        </td>
                        <td>
                            <div class="text-center">
								<div class="form-check form-check-sm form-check-custom form-check-solid">
									<input <?php echo $xCheking; ?> class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk<?php echo $xParaid; ?>" value=""/>
								</div>
							</div>
						</td>
						<td>
                            <div class="text-center">
								<div class="btn-group">
									<button id="btnEditar" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" onclick="f_Editar(<?php echo $xParaid;?>)"  title='Editar Parametro'>
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

<div class="modal fade" id="modal_parametro" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header" id="parametro_header">
        <h5 class="modal-title" id="exampleModalLabel">Nuevo Parametro</h5>
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
        <div class="scroll-y me-n7 pe-7" id="parametro_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#parametro_header" data-kt-scroll-wrappers="#parametro_scroll" data-kt-scroll-offset="300px">
                <div class="card card-flush">
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
                                    <!--begin::Svg Icon | path: icons/duotune/ecommerce/ecm004.svg-->
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
                    </div>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="kt_ecommerce_settings_general" role="tabpanel"> 
                            <div class="card-body pt-0">
                                <div class="row g-9 mb-7">
                                    <div class="col-md-12 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                            <span class="required">Parametro</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="especifique el nombre del usuario"></i>
                                        </label>
                                        <input type="text" class="form-control form-control-solid" id="txtNombrePara" name="txtNombrePara" minlength="5" maxlength="100" placeholder="Ingrese Nombre" value="" />
                                    </div>
                                </div>
                                <div class="row g-9 mb-7">
                                    <div class="col-md-12 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                            <span class="required">Descripcion</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="especifique una descripcion"></i>
                                        </label>
                                        <textarea class="form-control form-control-solid" name="txtDesc" id="txtDesc" maxlength="150" onkeydown="return (event.keyCode!=13);"></textarea>
                                    </div>
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
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="especifique el nombre del detalle"></i>
                                        </label>
                                        <input type="text" class="form-control form-control-solid" id="txtDetalle" name="txtDetalle" minlength="2" maxlength="100" placeholder="nombre del detalle" value="" />                       
                                   </div>
                                      <div class="col-md-3 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Valor Texto</span>
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="solo valor en texto"></i>
                                        </label>
                                        <input type="text" class="form-control form-control-solid" id="txtValorV" name="txtValorV" minlength="1" maxlength="100" placeholder="valor texto" value="" />                       
                                   </div>
                                     <div class="col-md-3 fv-row">
                                        <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                        <span class="required">Valor Entero</span>
                                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="solo valores enteros"></i>
                                        </label>
                                        <input type="text" class="form-control form-control-solid" id="txtValorI" name="txtValorI" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" minlength="5" maxlength="100" placeholder="valor entero" value="" />                       
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
                                <hr class="bg-primary border-2 border-top border-primary">
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="tblDetalle" style="width: 100%;">
                                    <thead>
                                        <tr class="text-start text-gray-800 fw-bolder fs-7 gs-0">
                                            <th style="display:none;">Id</th>
                                            <th class="min-w-125px">Detalle</th>
                                            <th class="min-w-125px">Valor Texto</th>
                                            <th class="min-w-125px">Valor entero</th>
                                            <th class="min-w-125px" style="text-align: center;">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bold text-gray-600"></tbody>
			                    </table>
                            </div>
                            
                        </div>
                    </div>
                </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" id="btnGuardar" onclick="f_Guardar(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xUsuaid; ?>)" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </div>
  </div>
</div>


<script>

   var _estado, _detalle,_valorI,_result = [],_count =0,_idpais,_idempr,_idusua;

    $(document).ready(function(){
       //abrir-modal-nuevo-parametro
       $("#nuevoParametro").click(function(){

          $("#modal_parametro").find("input,textarea").val("");
           _result = [];
           _estado = 'A';


        

            $("#modal_parametro").modal("show");
            $('#modal_parametro').modal('handleUpdate')


        });  
    });

  //Agregar detalle

    $('#btnAgregar').click(function(){
      //debugger;
       var _agregarDet = 'add';
       var _continuar = true;
       var _output;

        if($.trim($('#txtDetalle').val()).length == 0)
        {           
            mensajesweetalert("center","warning","Ingrese Detalle",false,1800);
            return false;
        }

        if($.trim($('#txtValorV').val()).length == 0 && $.trim($('#txtValorI').val()).length == 0 )
        {    
            mensajesweetalert("center","warning","Ingrese Valor Texto o Valor Entero..!",false,1800);        
            return false;
        }

        if($.trim($('#txtValorV').val()).length > 0 && $.trim($('#txtValorI').val()).length > 0 )
        {    
            mensajesweetalert("center","warning","Ingrese Solo Valor Texto o Valor Entero..!",false,1800);         
            return false;
        }

        _detalle = $.trim($('#txtDetalle').val());
        _valorV =  $.trim($('#txtValorV').val());

        if($.trim($('#txtValorI').val()).length == 0){
            _valorI = 0;
        }else{
            _valorI = $.trim($('#txtValorI').val());
        }

        if(_agregarDet == 'add'){

            $.each(_result,function(i,item){
                if(item.arrydetalle.toUpperCase() == _detalle.toUpperCase())
                {                  
                    mensajesweetalert("center","warning","Nombre del Detalle ya Existe..!",false,1800);                    
                    _continuar = false;
                    return false;
                }else{
                    $.each(_result,function(i,item){
                        if(_valorI == 0)
                        {
                            if(item.arryvalorv.toUpperCase() == _valorV.toUpperCase())
                            {                               
                                mensajesweetalert("center","warning","Valor Texto de Parámetro ya Existe..!","W","top-right",false,1800);    
                                _continuar = false;
                                return false;
                            }else{
                                _continuar = true;
                            }
                        }else
                        {
                            if(item.arryvalori == _valorI)
                            {                               
                                mensajesweetalert("center","warning","Valor Entero de Parámetro ya Existe..!","W","top-right",false,1800); 
                                _continuar = false;
                                return false;
                            }else{
                                _continuar = true;
                            }                            
                        }
                    });
                }
            });


            if(_continuar){
                _count = _count + 1;

                _output = '<tr id="row_' + _count + '">';
                _output += '<td style="display: none;">' + _count + ' <input type="hidden" name="hidden_orden[]" id="orden' + _count + '" value="' + _count + '" /></td>';                
                _output += '<td>' + _detalle + ' <input type="hidden" name="hidden_detalle[]" id="txtDetalle' + _count + '" value="' + _detalle + '" /></td>';
                _output += '<td>' + _valorV + ' <input type="hidden" name="hidden_valorv[]" id="txtValorV' +_count + '" value="' + _valorV + '" /></td>';
                _output += '<td>' + _valorI + ' <input type="hidden" name="hidden_valori[]" id="txtValorI' + _count + '" value="' + _valorI + '" /></td>';
                _output += '<td><div class="text-center"><div class="btn-group">';
                _output += '<button type="button" name="btnDelete" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1 btnDelete" id="' + _count + '"><i class="fa fa-trash"></i></button></div></div></td>';
                _output += '</tr>';


                $('#tblDetalle').append(_output);

                //console.log(_output);

                  _objeto = {
                    arryid : _count,
                    arrydetalle : _detalle,
                    arryvalorv : _valorV,
                    arryvalori : _valorI,
                }

                _result.push(_objeto);

                $("#txtDetalle").val("");
                $("#txtValorV").val("");
                $("#txtValorI").val("");

            }

        }
    });

   
   //Guardar parametro-detalle

    function f_Guardar(_idpais,_idempr,_idusua){

      var _parametro = $.trim($("#txtNombrePara").val());
      var _descripcion = $.trim($("#txtDesc").val());

      //console.log(_idempr);

      if(_parametro == '')
      {                        
        mensajesweetalert("center","warning","Ingrese Nombre del Parametro..!!",false,1800);
        return;
      }

      if(_count == 0){
        mensajesweetalert("center","warning","Ingrese al menos un Detalle..!!",false,1800);
        return;
      }

      //debugger;

                $datosParam ={
                    xxPaisId: _idpais,
					xxEmprId: _idempr,
                    xxParametro: _parametro
                }


                var xrespuesta = $.post("codephp/consultar_parametro.php", $datosParam);
                xrespuesta.done(function(response){
                    if(response == 0){

                        //debugger;

                        $parametros ={
                            xxPaisId: _idpais,
                            xxUsuaId: _idusua,
                            xxEmprId: _idempr,
                            xxParametro: _parametro,
                            xxResultado: _result,
                            xxEstado: _estado,
                            xxDescripcion: _descripcion
                         
                        }

                        $.ajax({
							url: "codephp/grabar_parametro.php",
							type: "POST",
							dataType: "json",
							data: $parametros,          
							success: function(response){ 
								if(response != 0){

									_pacaid = response;										
									_paramom = _parametro;
                                    _paradesc = _descripcion;
                                    _checked = "checked='checked'";

									var _estado = '<td><div class="badge badge-light-primary">Activo</div></td>';


									var _btnChk = '<td><div class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
                                                   '<input ' + _checked + ' class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk' + _pacaid + '" value=""/>' +
                                                   '</div></div></td>';
												

                                    var _btnEdit = '<td><div class="text-center"><div class="btn-group"><button id="btnEditar" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" onclick="f_Editar(' + _pacaid + ')" title="Editar Parametro" >' + 
				 						            '<i class="fa fa-edit"></i></button></div></div></td>';            
												
									TableData = $('#kt_ecommerce_report_shipping_table').DataTable();

									TableData.column(0).visible(0);
										
									
										TableData.row.add([_pacaid, _paramom, _paradesc, _estado, _btnChk, _btnEdit]).draw();
									
									

									$("#modal_parametro").modal("hide");									

								}                                                                         
							},
							error: function (error){
								console.log(error);
							}                            
						});

                       



                    }else{

                        mensajesweetalert("center","warning","Nombre del Parametro ya Existe..!",false,1800);
                    }

                });
    }

    //Eliminar Detalle

    $(document).on("click",".btnDelete",function(){
        row_id = $(this).attr("id");
        _detalle = $('#txtDetalle' + row_id + '').val();

        FunRemoveItemFromArr(_result, _detalle);
        $('#row_' + row_id + '').remove();
        _count--;

    });
    function FunRemoveItemFromArr(arr, deta)
    {
        $.each(arr,function(i,item){
            if(item.arrydetalle == deta)
            {
                arr.splice(i, 1);
                return false;
            }else{
                continuar = true;
            }
        });        
    };

    
    //debugger;

    function f_Editar(_paraid){
        $.redirect('?page=editparametro', {'idparam': _paraid}); //POR METODO POST
    }



   //Desplazar-modal

   $("#modal_parametro").draggable({
        handle: ".modal-header"
    });
    
 

  



</script> 	