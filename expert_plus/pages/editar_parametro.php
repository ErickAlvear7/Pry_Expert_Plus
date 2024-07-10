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

    $xPaisid = $_SESSION["i_paisid"];
    $xEmprid = $_SESSION["i_emprid"];
 

    $xSQL = "SELECT paca_nombre AS Nombre, paca_descripcion AS Descri, paca_estado AS Estado FROM `expert_parametro_cabecera` WHERE paca_id = $idpaca ";
    $xSQL .= "AND empr_id = $xEmprid ";
    $all_paca = mysqli_query($con, $xSQL);
    foreach($all_paca as $paca){

        $xNomPaca = $paca['Nombre'];
        $xDescPaca = $paca['Descri'];

    }

    $xSQL = "SELECT pade_id AS Idpade,pade_orden AS Orden, pade_nombre AS Detalle, pade_valorV AS ValorV, pade_valorI AS ValorI,";
    $xSQL .= "pade_estado AS Estado FROM `expert_parametro_detalle` WHERE paca_id = $idpaca ";
    $all_pade = mysqli_query($con, $xSQL);

    $xSQL = "SELECT  pade_orden AS Orden FROM `expert_parametro_detalle`WHERE paca_id = $idpaca ORDER BY pade_orden DESC LIMIT 1 ";
    $orden = mysqli_query($con, $xSQL);
    foreach($orden as $ord){
        $xOrdenDet = $ord['Orden'];
    }
    

?>

<div id="kt_content_container" class="container-xxl">
	<div class="card card-flush">
		<div class="card-body pt-0">
            <br/>
            <div class="card-header mb-4">
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
                <a href="?page=param_generales&menuid=<?php echo $menuid;?>" class="btn btn-icon btn-light-primary btn-sm ms-auto me-lg-n7" title="Regresar" data-bs-toggle="tooltip" data-bs-placement="left">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
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
                                <input type="text" class="form-control" id="txtParaEdit" name="txtParaEdit" minlength="5" maxlength="100" value="<?php echo $xNomPaca; ?>" />
                            </div>
                        </div>
                        <div class="row g-9 mb-10">
                            <div class="col-md-12 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">Descripcion</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Descripcion del parametro"></i>
                                </label>
                                <textarea class="form-control" name="txtDescEdit" id="txtDescEdit" maxlength="150" onkeydown="return (event.keyCode!=13);"><?php echo $xDescPaca; ?></textarea>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                           <button class="btn btn-sm btn-primary" type="button" id="btnGuardarEdit" onclick="f_Guardar(<?php echo $xPaisid; ?>,<?php echo $xEmprid; ?>,<?php echo $idpaca; ?>)"><i class="fa fa-hdd me-1"></i>Grabar</button>
                        </div>	
                    </div>
                </div>
                <div class="tab-pane fade" id="kt_ecommerce_settings_store" role="tabpanel">
                    <div class="card">
                        <div class="card-header border-0 pt-6">
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
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="solo valor en texto"></i>
                                    </label>
                                    <input type="text" class="form-control" id="txtValorV" name="txtValorV" minlength="1" maxlength="50" placeholder="valor texto" value="" />                       
                                </div>
                                    <div class="col-md-3 fv-row">
                                    <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">Valor Entero</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="solo valores enteros"></i>
                                    </label>
                                    <input type="text" class="form-control form-control-solid" id="txtValorI" name="txtValorI" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" minlength="1" maxlength="10" placeholder="valor entero" value="" />                       
                                </div>
                                <div class="col-md-2 fv-row">
                                <button type="button" data-repeater-create="" class="btn btn-light-primary btn-sm mb-2 border border-primary" id="btnAgregar"><i class="fa fa-plus me-1" aria-hidden="true"></i>
                                    Agregar
                                </button>
                                </div>
                            </div>
                        </div>
                        <hr class="bg-primary border-2 border-top border-primary">
                        <div class="card-body pt-0">
                            <div class="d-flex align-items-center position-relative my-4">
                                <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                    <i class="fa fa-search fa-1x" style="color:#3B8CEC;" aria-hidden="true"></i>
                                </span>
                                <input type="text" data-kt-ecommerce-order-filter="search" class="form-control w-250px ps-14" placeholder="Buscar Dato" />
                            </div>
                            <div class="mh-375px scroll-y me-n7 pe-7">
                                <table class="table align-middle table-row-dashed table-hover fs-6 gy-5" id="kt_ecommerce_report_shipping_table" style="width: 100%;">
                                    <thead>
                                        <tr class="text-start text-gray-800 fw-bolder fs-7 text-uppercase gs-0">
                                            <th style="display:none;">Id</th>
                                            <th class="min-w-125px">Detalle</th>
                                            <th class="min-w-125px">Valor Texto</th>
                                            <th class="min-w-125px">Valor entero</th>
                                            <th class="min-w-125px">Estado</th>
                                            <th>Status</th>
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
                                                $xEstado = 'ACTIVO';
                                                $xTextColor = "badge badge-light-primary";
                                                $xCheking = 'checked="checked"';
                                            
                                            }else{
                                                $xEstado = 'INACTIVO';
                                                $xTextColor = "badge badge-light-danger";
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
                                            <td id="td_<?php echo $xPadeId; ?>">
                                                <div class="<?php echo $xTextColor; ?>"><?php echo $xEstado; ?></div>
                                            </td>
                                            <td class="text-center">
                                                <div class="form-check form-check-sm form-check-custom form-check-solid">
                                                    <input <?php echo $xCheking; ?>  class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk<?php echo $xPadeId; ?>" 
                                                    onchange="f_UpdateEstado(<?php echo $xPadeId;?>)" value="<?php echo $xPadeId;?>" />
                                                </div>
                                            </td> 
                                            <td>
                                                <div class="text-center">
                                                    <div class="btn-group">	
                                                        <button type="button" id="btnEditar_<?php echo $xPadeId;?>" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" <?php echo $xDisabledEdit;?> title='Editar Detalle' data-bs-toggle="tooltip" data-bs-placement="left">
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
</div>

<!--Modal detalle -->
<div class="modal fade" id="modal_detalle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable mw-700px">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="badge badge-light-primary fw-light fs-2 fst-italic">Editar Detalle</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body py-lg-5 px-lg-10">
                <div class="card card-flush py-2">
                    <div class="card-body pt-0">
                        <div class="row mb-7">
                            <div class="col-md-3">
                                <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                    <span>Parametro:</span>
                                </label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="txtParametroEdit" name="txtParametroEdit" disabled />
                            </div>
                        </div>
                        <div class="row mb-7">
                            <div class="col-md-3">
                                <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                    <span class="required">Detalle</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Ingrese Detalle"></i>
                                </label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="txtDetalleEdit" name="txtDetalleEdit" placeholder="Ingrese Detalle" minlength="2" maxlength="100" />
                            </div>
                        </div>
                        <div class="row mb-7">
                            <div class="col-md-3">
                                <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                    <span class="required">Valor Texto</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="solo valor en texto"></i>
                                </label>  
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="txtValorVedit" name="txtValorVedit" placeholder="valor Texto" minlength="1" maxlength="50" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                                    <span class="required">Valor Entero</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="solo valores enteros"></i>
                                </label>  
                            </div>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="txtValorIedit" name="txtValorIedit" placeholder="valor Entero" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" minlength="1" maxlength="10" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-light-danger border border-danger" data-bs-dismiss="modal"><i class="fa fa-times me-1" aria-hidden="true"></i>Cerrar</button>
                <button type="button" id="btnGuardar" class="btn btn-sm btn-light-primary border border-primary"><i class="las la-pencil-alt me-1"></i>Modificar</button>
            </div>
            <input type="hidden" id="txtDetalleold" name="txtDetalleold" />
            <input type="hidden" id="txtValortexto" name="txtDetalleold" />
            <input type="hidden" id="txtValorentero" name="txtValorentero" />
        </div>
    </div>
</div>   


<script>
    var _idpaca,_idpade,_paisid;

    $(document).ready(function(){
        _parametroold = $('#txtParaEdit').val();

    });

    //Agregar Detalle directo a la BDD 

    $('#btnAgregar').click(function(){

        var _estado = 'A';
        var _pacaid = '<?php echo $idpaca; ?>';
        var _paisid = '<?php echo  $xPaisid; ?>';
        var _emprid = '<?php echo  $xEmprid; ?>';
        var _parametro = $.trim($("#txtParaEdit").val());
        var _ordendet = '<?php echo  $xOrdenDet; ?>';
       
        if($.trim($('#txtDetalle').val()).length == 0)
        {           
            toastSweetAlert("top-end",3000,"warning","Ingrese Detalle..!!");
            return false;          
        }

        if($.trim($('#txtValorV').val()).length == 0 && $.trim($('#txtValorI').val()).length == 0 )
        {    
            toastSweetAlert("top-end",3000,"warning","Ingrese valor texto o entero..!!");
            return false;
        }

        if($.trim($('#txtValorV').val()).length > 0 && $.trim($('#txtValorI').val()).length > 0 )
        {    
            toastSweetAlert("top-end",3000,"warning","Ingrese solo valor texto o entero..!!");
            return false;       
        }

        //debugger

        var _detalle = $.trim($('#txtDetalle').val());
        var _valorV =  $.trim($('#txtValorV').val());
        var _valorI =  $.trim($('#txtValorI').val());

        _ordendet++;

        if(_valorI == ''){
            _valorI = 0;
        }

        var _datosDetalle ={
            "xxPaisId" : _paisid,
            "xxEmprId" : _emprid,
            "xxParemtro" : _parametro,
            "xxDetalle" : _detalle,
            "xxValorV" : _valorV,
            "xxValorI" : _valorI
        }

        var xrespuesta = $.post("codephp/consultar_detalle.php", _datosDetalle);
        xrespuesta.done(function(response){
            if(response == 0){

                var _parametros ={
                    "xxPacaId" : _pacaid,
                    "xxDetalle" : _detalle,
                    "xxValorV" : _valorV,
                    "xxValorI" : _valorI,
                    "xxEstado" : _estado,
                    "xxOrden" : _ordendet                         
                }

                $.ajax({
                    url: "codephp/grabar_detalle.php",
                    type: "POST",
                    dataType: "json",
                    data: _parametros,          
                    success: function(response){ 
                        if(response != 0){
                            _padeid = response;
                            _padenom = _detalle;
                            _padev = _valorV;
                            if(_valorI == 0){
                                _valorI = '';
                            }
                            _padei = _valorI;
                            _estado = 'ACTIVO';
                            _class = "badge badge-light-primary";
                            _status = '<div class="' + _class + '">' + _estado + ' </div>';
                            _checked = "checked='checked'";

                            var _btnChk = '<td class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
                                            '<input ' + _checked + ' class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk' + _padeid + '" onchange="f_UpdateEstado(' + _padeid + ')" />' +
                                            '</div></td>';
                            
                            var _btnGrup = '<td><div class="text-center"><div class="btn-group">' +
                                            '<button type="button" id="btnEditar_'+_padeid+'" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" id="">' +
                                            '<i class="fa fa-edit"></i></button></div></div></td>';
                            

                            TableData = $('#kt_ecommerce_report_shipping_table').DataTable();  
                            TableData.column(0).visible(0);

                            TableData.row.add([_padeid, _padenom, _padev, _padei, _status,_btnChk,_btnGrup]).draw();

                            $("#txtDetalle").val("");
                            $("#txtValorV").val("");
                            $("#txtValorI").val("");

                            toastSweetAlert("top-end",3000,"success","Detalle Agregado");
                            $.redirect('?page=editparametro&menuid=<?php echo $menuid; ?>', {idparam: <?php echo $idpaca; ?>}); //POR METODO POST

                        }                                                                         
                    },
                    error: function (error){
                        console.log(error);
                    }                            
                });

            }else{
                toastSweetAlert("top-end",3000,"warning","Detalle, valor texto o entero ya existe..!!");

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

                var _parametros = {
					"xxPadeid" : _idpade,
					"xxPacaid" : _idpaca
				}

                $.ajax({
					url: "codephp/editar_detalles.php",
					type: "POST",
					dataType: "json",
					data: _parametros,          
					success: function(data){ 

                     //console.log(data);
                        //debugger;
                        var _nombre = data[0]['Nombre'];
                        var _valorv = data[0]['ValorV'];
                        var _valori = data[0]['ValorI'];
                        var _parametro = data[0]['Parametro'];

                        if(_valori == 0){
                            _valori = '';
                        }
                        
                        $("#txtParametroEdit").val(_parametro);
                        $("#txtDetalleEdit").val(_nombre);
                        $("#txtValorVedit").val(_valorv);
                        $("#txtValorIedit").val(_valori);

                        $("#txtDetalleold").val(_nombre);
                        $("#txtValortexto").val(_valorv);
                        $("#txtValorentero").val(_valori);
 
                        if(_valorv == ''){
                            $("#txtValorVedit").prop("disabled",true);   
                        }
                        if(_valori == 0){
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
     
        debugger;
       var _padeid = _idpade;
       var _pacaid =   _idpaca
       _paisid = '<?php echo  $xPaisid; ?>';
       var _consultar = 'NO';
       
       var _nombre = $.trim($("#txtDetalleEdit").val());
       var _valorV = $.trim($("#txtValorVedit").val());
       var _valorI = $.trim($('#txtValorIedit').val());

       var _nombreold = $.trim($("#txtDetalleold").val());
       var _valovold = $.trim($("#txtValortexto").val());
       var _valoriold = $.trim($("#txtValorentero").val());
      

        if(_nombreold != _nombre){
            _consultar = 'SI';
        }

        if(_valorV != ''){
            if(_valorV != _valovold){
                _consultar = 'SI';
            }
        }

        if(_valorI != ''){
            if(_valorI != _valoriold){
                _consultar = 'SI'; 
            }
        }        

       if($.trim($('#txtDetalleEdit').val()).length == 0)
        {           
            toastSweetAlert("top-end",3000,"warning","Ingrese Detalle..!!");
            return false;
        }

        if($.trim($('#txtValorVedit').val()).length == 0 && $.trim($('#txtValorIedit').val()).length == 0 )
        {    
            toastSweetAlert("top-end",3000,"warning","Ingrese valor texto o entero..!!");        
            return false;
        }

        if($.trim($('#txtValorVedit').val()).length > 0 && $.trim($('#txtValorIedit').val()).length > 0 )
        {    
            toastSweetAlert("top-end",3000,"warning","Ingrese solo valor texto o entero..!!");  
            return false;
        }

        var _parametros ={
            "xxPacaId" : _pacaid,
            "xxDetalle" : _nombre,
            "xxValorV" : _valorV,
            "xxValorI" : _valorI,
            "xxDetalleold" : _nombreold,
            "xxValorVold" : _valovold,
            "xxValorIold" : _valoriold               
        }

        if(_consultar == 'SI'){
            var xrespuesta = $.post("codephp/consultar_detalledit.php", _parametros);
            xrespuesta.done(function(response){
                if(response.trim() == 0){

                    var _parametros ={
                        "xxPacaId" : _pacaid,
                        "xxPadeId" : _padeid,
                        "xxDetalle" : _nombre,
                        "xxValorV" : _valorV,
                        "xxValorI" : _valorI                    
                    }
                    
                    var xresponse = $.post("codephp/update_detalle.php", _parametros);
                    xresponse.done(function(response){    

                        if(response.trim() == 'OK'){

                            _padeid = _padeid
                            _padenom = _nombre;
                            _padev = _valorV;
                            _padei = _valorI;
                            _estado = 'ACTIVO';
                            _class = "badge badge-light-primary";
                            _status = '<div class="' + _class + '">' + _estado + ' </div>';


                            if(_padei == 0){
                                _padei = '';
                            }
                            _checked = "checked='checked'";

                            var _btnChk = '<td class="text-center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
                                            '<input ' + _checked + ' class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk' + _padeid + '" onchange="f_UpdateEstado(' + _padeid + ')" />' +
                                            '</div></td>';
                            
                            var _btnGrup = '<td><div class="text-center"><div class="btn-group">' +
                                            '<button type="button" id="btnEditar_'+_padeid+'" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" id="">' +
                                            '<i class="fa fa-edit"></i></button></div></div></td>';

                            TableData = $('#kt_ecommerce_report_shipping_table').DataTable();  
                            TableData.column(0).visible(0);

                            TableData.row(_fila).data([_padeid, _padenom, _padev, _padei,_status, _btnChk, _btnGrup ]).draw();
            

                            $("#modal_detalle").modal("hide");
                        }
                    }); 
            
                }else{
                    toastSweetAlert("top-end",3000,"warning","Detalle valor texto o entero ya existe..!!");
                }

            });
        }else{
            var _parametros = {
                xxPacaId: _pacaid,
                xxPadeId: _padeid,
                xxDetalle: _nombre,
                xxValorV: _valorV,
                xxValorI: _valorI,
            }
            
            var xresponse = $.post("codephp/update_detalle.php", _parametros);
            xresponse.done(function(response){    

                if(response.trim() == 'OK'){

                    _padeid = _padeid
                    _padenom = _nombre;
                    _padev = _valorV;
                    _padei = _valorI;
                    _estado = 'ACTIVO'   
                    _checked = "checked='checked'";
                    _class = "badge badge-light-primary";
                    _status = '<div class="' + _class + '">' + _estado + ' </div>';

                    var _btnChk = '<td style="text-align:center"><div class="form-check form-check-sm form-check-custom form-check-solid">' +
                                    '<input ' + _checked + ' class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk' + _padeid + '"' +
                                    '</div></td>';
                    
                    var _btnGrup = '<td><div class="text-center"><div class="btn-group">' +
                                    '<button type="button" id="btnEditar" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" id="" title="Editar Detalle" data-bs-toggle="tooltip" data-bs-placement="right">' +
                                    '<i class="fa fa-edit"></i></button></div></div></td>';

                    TableData = $('#kt_ecommerce_report_shipping_table').DataTable();  
                    TableData.column(0).visible(0);

                    TableData.row(_fila).data([_padeid, _padenom, _padev, _padei,_status, _btnChk, _btnGrup ]).draw();

                    $("#modal_detalle").modal("hide");
                }
            });             
        }
    });

    //Guardar Editar Paramentro
    function f_Guardar(_idpais,_idempr,_idpaca){

        var _parametro = $.trim($("#txtParaEdit").val());
        var _descripcion = $.trim($("#txtDescEdit").val());

        if(_parametro == ''){                        
            toastSweetAlert("top-end",3000,"warning","Ingrese Parametro..!!");
            return;
        }

        var _parametros = {
            "xxPaisId" : _idpais,
            "xxEmprId" : _idempr,
            "xxParametro" : _parametro
        }
        
        if(_parametroold != _parametro){
            var xrespuesta = $.post("codephp/consultar_parametro.php", _parametros);
            xrespuesta.done(function(response){
                if(response.trim() == '0'){

                    _parametros = {
                        "xxPacaId" : _idpaca,
                        "xxEmprId" : _idempr,
                        "xxPaisId" : _idpais,
                        "xxParametro" : _parametro,
                        "xxDescripcion" : _descripcion 
                    }
                    
                    var xresponse = $.post("codephp/update_parametro.php", _parametros);
                    xresponse.done(function(response){            
                        if(response.trim() == 'OK'){
                            $.redirect('?page=param_generales&menuid=<?php echo $menuid; ?>', {'mensaje': 'Actualizado con Exito'}); //POR METODO POST            
                        }
                    }); 
                }else{
                    toastSweetAlert("top-end",3000,"warning","Parametro ya Existe..!!");
                }
            });
        }else{
            _parametros ={
                "xxPacaId" : _idpaca,
                "xxEmprId" : _idempr,
                "xxPaisId" : _idpais,
                "xxParametro" : _parametro,
                "xxDescripcion" : _descripcion                
            }
            
            var xresponse = $.post("codephp/update_parametro.php", _parametros);
            xresponse.done(function(response){            

                if(response.trim() == 'OK'){
                    $.redirect('?page=param_generales&menuid=<?php echo $menuid; ?>', {'mensaje': 'Actualizado con Exito'}); //POR METODO POST            
                }
            });
        }
    }

    
     //cambiar estado y desactivar botones en linea
    function f_UpdateEstado(_padeid){

        let _check = $("#chk" + _padeid).is(":checked");
        let _checked = "";
        let _disabled = "";
        let _td = "td_" + _padeid;
        let _class = "badge badge-light-primary";
        let _btnedit = "btnEditar_" + _padeid;

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

        var _changetd = document.getElementById(_td);
		_changetd.innerHTML = '<td><div class="' + _class + '">' + _estado + ' </div>';

        var _parametros = {
            "xxPadeid" : _padeid,
            "xxEstado" : _estado
        }

        var xrespuesta = $.post("codephp/delnew_detalle.php", _parametros);
        xrespuesta.done(function(response){
        });	
    }

   $("#modal_detalle").draggable({
        handle: ".modal-header"
    });
    
</script> 	