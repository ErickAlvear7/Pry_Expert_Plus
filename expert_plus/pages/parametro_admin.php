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

    $menuid = $_GET['menuid'];
    $mensaje = (isset($_POST['mensaje'])) ? $_POST['mensaje'] : '';
    
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
    $xSQL .= "CASE paca_estado WHEN 'A' THEN 'ACTIVO' ELSE 'INACTIVO' END AS Estado ";
    $xSQL .= "FROM `expert_parametro_cabecera` WHERE empr_id=$xEmprid AND pais_id=$xPaisid";
    $all_param = mysqli_query($con, $xSQL);

?>

<div id="kt_content_container" class="container-xxl">
    <input type="hidden" id="mensaje" value="<?php echo $mensaje ?>">
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1">
                    <span class="svg-icon svg-icon-1 position-absolute ms-4">
                        <i class="fa fa-search fa-1x" style="color:#3B8CEC;" aria-hidden="true"></i> 
                    </span>
                    <input type="text" data-kt-ecommerce-order-filter="search" class="form-control w-250px ps-14" placeholder="Buscar Dato" />
                </div>
            </div> 
            <div class="card-toolbar">
                <button type="button" data-repeater-create="" class="btn btn-light-primary btn-sm" id="nuevoParametro"><i class="fa fa-plus-circle" aria-hidden="true"></i>
                      Nuevo Parametro
                </button>
		    </div>                       
        </div>
		<div class="card-body pt-0">
			<table class="table align-middle table-row-dashed table-hover fs-6 gy-5" id="kt_ecommerce_report_shipping_table" style="width: 100%;">
				<thead>
					<tr class="text-start text-gray-800 fw-bolder fs-7 text-uppercase gs-0">
					    <th style="display:none;">Id</th>
						<th class="">Parametro</th>
						<th class="">Descripcion</th>
						<th class="min-w-125px">Estado</th>
						<th>Status</th>
                        <th style="text-align: center;">Opciones</th>
					</tr>
				</thead>
				<tbody class="fw-bold text-gray-600">
					<?php 
                       foreach($all_param as $paca){

                        $xPacaId = $paca['Idpaca'];
                        $xPacaNombre = $paca['Parametro'];
                        $xPacaDesc = $paca['Descrip'];
                        $xPacaEstado = $paca['Estado'];
                    ?>
                    <?php 
                       $xCheking = '';
                       $xDisabledEdit = '';

                       if($xPacaEstado == 'ACTIVO'){
                            $xCheking = 'checked="checked"';
                            $xTextColor = "badge badge-light-primary";
                        }else{
                            $xTextColor = "badge badge-light-danger";
                            $xDisabledEdit = 'disabled';
                        }
                    
                    ?>
					<tr>
					    <td style="display:none;"><?php echo $xPacaId ?></td>
						<td><?php echo $xPacaNombre; ?></td>
						<td><?php echo $xPacaDesc; ?></td>
						<td id="td_<?php echo $xPacaId;?>">
                           <div class="<?php echo $xTextColor; ?>"><?php echo $xPacaEstado ?></div>
                        </td>
                        <td>
                            <div class="text-center">
								<div class="form-check form-check-sm form-check-custom form-check-solid">
									<input <?php echo $xCheking; ?> class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk<?php echo $xPacaId; ?>" 
                                       onchange="f_UpdateEstado(<?php echo $xPacaId;?>,<?php echo $xPaisid;?>,<?php echo $xEmprid;?>)" value="<?php echo $xPacaId;?>"/>
								</div>
							</div>
						</td>
						<td>
                            <div class="text-center">
								<div class="btn-group">
									<button id="btnEditar_<?php echo $xPacaId;?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" onclick="f_Editar(<?php echo $xPacaId;?>)" <?php echo $xDisabledEdit;?>  title='Editar Parametro' data-bs-toggle="tooltip" data-bs-placement="left">
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


<!--Modal Parametro-->
<div class="modal fade" id="modal_parametro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Nuevo Parametro</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body py-lg-7 px-lg-10">
                <div class="card card-flush py-4">
                    <div class="card-header mb-7">
                        <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-bold mb-8">
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab" href="#kt_ecommerce_settings_general">											
                                    <i class="fa fa-tasks fa-1x me-2" aria-hidden="true"></i>
                                    Parametro
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab" href="#kt_ecommerce_settings_store">
                                    <i class="fa fa-sitemap fa-1x me-2" aria-hidden="true"></i>
                                    Detalle
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body pt-0">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="kt_ecommerce_settings_general" role="tabpanel"> 
                                <div class="card-body pt-0">
                                    <div class="row g-9 mb-10">
                                        <div class="col-md-12 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-bold mb-3">
                                                <span class="required">Parametro</span>
                                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Especifique el nombre del parametro"></i>
                                            </label>
                                            <input type="text" class="form-control" id="txtNombrePara" name="txtNombrePara" minlength="5" maxlength="100" placeholder="Ingrese Parametro" value="" />
                                        </div>
                                    </div>
                                    <div class="row g-9 mb-2">
                                        <div class="col-md-12 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-bold mb-3">
                                                <span class="required">Descripcion</span>
                                                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Especifique una descripcion del parametro"></i>
                                            </label>
                                            <textarea class="form-control" name="txtDesc" id="txtDesc" maxlength="150" onkeydown="return (event.keyCode!=13);"></textarea>
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
                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Especifique el nombre del detalle"></i>
                                            </label>
                                            <input type="text" class="form-control" id="txtDetalle" name="txtDetalle" minlength="2" maxlength="100" placeholder="Ingrese Detalle" value="" />                       
                                        </div>
                                        <div class="col-md-3 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                            <span class="required">Valor Texto</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Solo valores en texto"></i>
                                            </label>
                                            <input type="text" class="form-control" id="txtValorV" name="txtValorV" minlength="1" maxlength="50" placeholder="valor texto" value="" />                       
                                        </div>
                                        <div class="col-md-3 fv-row">
                                            <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                            <span class="required">Valor Entero</span>
                                            <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Solo valores enteros"></i>
                                            </label>
                                            <input type="text" class="form-control form-control-solid" id="txtValorI" name="txtValorI" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" minlength="1" maxlength="10" placeholder="valor entero" value="" />                       
                                        </div>
                                        <div class="col-md-2 fv-row">
                                            <button class="btn btn-sm btn-light-primary" id="btnAgregar">
                                                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                                Agregar    
                                            </button>
                                        </div>
                                    </div>
                                    <br/>
                                    <hr class="bg-primary border-2 border-top border-primary">
                                    <table class="table align-middle table-row-dashed table-hover fs-6 gy-5" id="tblDetalle" style="width: 100%;">
                                        <thead>
                                            <tr class="text-start text-gray-800 fw-bolder fs-7 text-uppercase gs-0">
                                                <th style="display:none;">Id</th>
                                                <th class="min-w-125px">Detalle</th>
                                                <th>Valor Texto</th>
                                                <th>Valor entero</th>
                                                <th style="text-align: center;">Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fw-bold text-gray-600"></tbody>
                                    </table>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger" data-bs-dismiss="modal"><i class="fa fa-window-close" aria-hidden="true"></i>Cerrar</button>
                <button type="button" id="btnGuardar" class="btn btn-sm btn-light-primary" onclick="f_Guardar(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $xUsuaid; ?>)"><i class="las la-save"></i>Grabar</button>
            </div>
        </div>
    </div>
</div>   


<script>

   var _estado, _detalle,_valorI,_result = [],_count =0,_idpais,_idempr,_idusua;

    $(document).ready(function(){

        //_mensaje = $('input#mensaje').val();
        _mensaje = "<?php echo $mensaje; ?>";

        if(_mensaje != ''){
            toastSweetAlert("top-end",3000,"success",_mensaje);
        }
			
       //abrir-modal-nuevo-parametro
        $("#nuevoParametro").click(function(){

            $("#modal_parametro").find("input,textarea").val("");

            _result.splice(0,_result.length);

            // while(a.length > 0) {
            //         a.pop();
            //     }
            _estado = 'A';

            $('[href="#kt_ecommerce_settings_general"]').tab('show');
            $("#modal_parametro").modal("show");
            $('#modal_parametro').modal('handleUpdate')

        });  
    });

    //Agregar detalle del parametro modal

    $('#btnAgregar').click(function(){
      //debugger;
      
       var _continuar = true;
       var _output;

       var _idpais = '<?php echo $xPaisid; ?>';
       var _idempr = '<?php echo $xEmprid; ?>';
       var _parametro = $.trim($("#txtNombrePara").val());

        if($.trim($('#txtDetalle').val()).length == 0)
        {           
            toastSweetAlert("top-end",3000,"warning","Ingrese Detalle..!!");
            return false;
        }

        if($.trim($('#txtValorV').val()).length == 0 && $.trim($('#txtValorI').val()).length == 0 )
        {    
            toastSweetAlert("top-end",3000,"warning","Ingrese valor texto o entero..!");
            return false;
        }

        if($.trim($('#txtValorV').val()).length > 0 && $.trim($('#txtValorI').val()).length > 0 )
        {    
            toastSweetAlert("top-end",3000,"warning","Solo valor texto o entero..!!");
            return false;
        }

        _detalle = $.trim($('#txtDetalle').val());
        _valorV =  $.trim($('#txtValorV').val());
        _valorI = $.trim($('#txtValorI').val());

        if(_valorI == ''){
            _valorI = 0;
            
        }
            
                 
        var _datosDet ={
            "xxPaisId" : _idpais,
            "xxEmprId" : _idempr,
            "xxParemtro" : _parametro,
            "xxDetalle" : _detalle,
            "xxValorV" : _valorV,
            "xxValorI" : _valorI
        }

        var xrespuesta = $.post("codephp/consultar_detalle.php", _datosDet);
        xrespuesta.done(function(response){

            if(response.trim() == 0){

                $.each(_result,function(i,item){
                    if(item.arrydetalle.toUpperCase() == _detalle.toUpperCase())
                    {                  
                        toastSweetAlert("top-end",3000,"warning","Detalle ya existe..!!");
                        _continuar = false;
                        return false;
                    }else{
                        $.each(_result,function(i,item){
                            if(_valorI == 0)
                            {
                                if(item.arryvalorv.toUpperCase() == _valorV.toUpperCase())
                                {                               
                                    toastSweetAlert("top-end",3000,"warning","Valor texto ya existe..!!");
                                    _continuar = false;
                                    return false;
                                }else{
                                    _continuar = true;
                                }
                            }else
                            {
                                if(item.arryvalori == _valorI)
                                {                               
                                    toastSweetAlert("top-end",3000,"warning","Valor entero ya existe..!!");
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

                    _objeto = {
                        arrydetalle: _detalle,
                        arryvalorv: _valorV,
                        arryvalori: _valorI,
                        arryorden: _count
                    }

                    _result.push(_objeto);

                    $("#txtDetalle").val("");
                    $("#txtValorV").val("");
                    $("#txtValorI").val("");
                }
            }else{
                toastSweetAlert("top-end",3000,"warning","Detalle, valor texto o entero ya existe..!!");
            }
        });
        
    });

  
   
   //Guardar parametro-detalle

    function f_Guardar(_idpais,_idempr,_idusua){

      var _parametro = $.trim($("#txtNombrePara").val());
      var _descripcion = $.trim($("#txtDesc").val());

      if(_parametro == ''){                        
        toastSweetAlert("top-end",3000,"warning","Ingrese Parametro..!!");
        return;
      }

      if(_count == 0){
        toastSweetAlert("top-end",3000,"warning","Ingrese Detalle del Parametro..!!");
        return;
      }

        var _datosParam = {
            "xxPaisId" : _idpais,
            "xxEmprId" : _idempr,
            "xxParametro" : _parametro
        }

        var xrespuesta = $.post("codephp/consultar_parametro.php", _datosParam);
        xrespuesta.done(function(response){
            if(response == 0){
                        
                var _parametros = {
                    "xxPaisId" : _idpais,
                    "xxUsuaId" : _idusua,
                    "xxEmprId" : _idempr,
                    "xxParametro" : _parametro,
                    "xxResultado" : _result,
                    "xxEstado" : _estado,
                    "xxDescripcion" : _descripcion
                }

                $.ajax({
                    url: "codephp/grabar_parametro.php",
                    type: "POST",
                    dataType: "json",
                    data: _parametros,
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
                                _mensaje = 'Grabado con Exito';
                                    
                            $("#modal_parametro").modal("hide");
                            
                            $.redirect('?page=param_generales&menuid=<?php echo $menuid; ?>', {'mensaje': _mensaje}); //POR METODO POST

                        }
                    },
                    error: function (error){
                        console.log(error);
                    }
                }); 
            }else{
                toastSweetAlert("top-end",3000,"warning","Parametro ya existe..!!");
            }
        });
    }

    //Eliminar Detalle en linea

    $(document).on("click",".btnDelete",function(){
        row_id = $(this).attr("id");
        _detalle = $('#txtDetalle' + row_id + '').val();

        $.each(_result,function(i,item){
            if(item.arrydetalle == _detalle)
            {
                _result.splice(i, 1);
                return false;
            }else{
                continuar = true;
            }
        }); 

        $('#row_' + row_id + '').remove();
        _count--;

    });

    function f_Editar(_paraid){
        $.redirect('?page=editparametro&menuid=<?php echo $menuid; ?>', {'idparam': _paraid}); //POR METODO POST
    }

    //cambiar estado y desactivar botones en linea

    function f_UpdateEstado(_pacaid, _paisid,_emprid){


        let _check = $("#chk" + _pacaid).is(":checked");
        let _checked = "";
		let _disabled = "";
        let _class = "badge badge-light-primary";
        let _td = "td_" + _pacaid;
        let _btnedit = "btnEditar_" + _pacaid;

        if(_check){
            _estado = "ACTIVO";
            _disabled = "";
            _checked = "checked='checked'";
            $('#'+_btnedit).prop("disabled",false);                    
        }else{                    
            _estado = "INACTIVO";
            _disabled = "disabled";
            _class = "badge badge-light-danger";
            $('#'+_btnedit).prop("disabled",true);
        }

            var cambiar = document.getElementById(_td);
              cambiar.innerHTML = '<div class="' + _class + '">' + _estado + ' </div>';

            var _parametros = {
				"xxPacaid" : _pacaid,
                "xxPaisid" : _paisid,
				"xxEmprid" : _emprid,
				"xxEstado" : _estado
			}

            var xrespuesta = $.post("codephp/delnew_parametro.php", _parametros);
			xrespuesta.done(function(response){
			});	     

    }

   //Desplazar-modal

   $("#modal_parametro").draggable({
        handle: ".modal-header"
    });    

</script> 	