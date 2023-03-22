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

    $xSQL = "SELECT  pade_orden AS Orden FROM `expert_parametro_detalle` ORDER BY pade_orden DESC LIMIT 1 ";
    $orden = mysqli_query($con, $xSQL);
    foreach($orden as $ord){
        $xOrdenDet = $ord['Orden'];
    }
    

 

?>

<div id="kt_content_container" class="container-xxl">
	<div class="card card-flush">
        <div class="card-toolbar d-flex align-self-end">
            <a href="?page=param_generales" class="btn btn-light-primary"><i class="las la-arrow-left"></i>Regresar</a>
        </div>	
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
                                <input type="text" class="form-control form-control-solid" id="txtNombrePara" name="txtNombrePara" minlength="5" maxlength="100" value="<?php echo $xNomPaca; ?>" />
                            </div>
                        </div>
                        <div class="row g-9 mb-7">
                            <div class="col-md-12 fv-row">
                                <label class="d-flex align-items-center fs-6 fw-bold mb-2">
                                    <span class="required">Descripcion</span>
                                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="especifique una descripcion"></i>
                                </label>
                                <textarea class="form-control form-control-solid" name="txtDesc" id="txtDesc" maxlength="150" onkeydown="return (event.keyCode!=13);"><?php echo $xDescPaca; ?></textarea>
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
                                    <th class="min-w-125px">Estado</th>
                                    <th class="min-w-125px" style="text-align: center;">Opciones</th>
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

                                    if($xPadeEstado == 'A'){
                                        $xCheking = 'checked="checked"';
                                       
                                    }
                                    
                                    ?>
                                <tr id="row_<?php echo  $xPadeId; ?>">
                                    <td style="display: none;">
                                        <?php echo  $xPadeId; ?>
                                    </td>               
                                    <td><?php echo $xPadeNom; ?></td>
                                    <td><?php echo $xPadeValorV; ?></td>
                                    <td><?php echo $xPadeValorI; ?></td>
                                    <td style="text-align:center">
                                        <div class="form-check form-check-sm form-check-custom form-check-solid">
                                            <input <?php echo $xCheking; ?>  class="form-check-input h-20px w-20px border-primary btnEstado" type="checkbox" id="chk<?php echo $xPadeId; ?>" 
                                                     onchange="f_Pade(<?php echo $xPadeId; ?>, <?php echo $idpaca; ?>)" />
                                        </div>
                                    </td> 
                                    <td>
                                        <div class="text-center">
                                            <div class="btn-group">
                                                <button type="button" id="btnDelete" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1 btnDelete" id="">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                <button type="button" id="btnEditar" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" id="">
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
<div class="modal fade" id="kt_modal_new_card" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Card</h2>
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <span class="svg-icon svg-icon-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                            <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <div class="d-flex flex-column mb-7 fv-row">
                    <label class="d-flex align-items-center fs-6 fw-bold form-label mb-2">
                        <span class="required">Name On Card</span>
                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Specify a card holder's name"></i>
                    </label>
                    <input type="text" class="form-control form-control-solid" placeholder="" name="card_name" value="Max Doe" />
                </div>
                <div class="d-flex flex-column mb-7 fv-row">
                    <label class="required fs-6 fw-bold form-label mb-2">Card Number</label>
                    <div class="position-relative">
                    <input type="text" class="form-control form-control-solid" placeholder="" name="card_name" value="Max Doe" />
                    </div>
                </div>
                <div class="text-center pt-15">
                    <button type="reset" id="kt_modal_new_card_cancel" class="btn btn-light me-3">Discard</button>
                    <button type="submit" id="kt_modal_new_card_submit" class="btn btn-primary"></button>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    var _idpaca,_idpade;

    $(document).ready(function(){

   


    });

    $('#btnAgregar').click(function(){

        var _estado = 'A';
        var _pacaid = '<?php echo $idpaca; ?>';
        var _paisid = '<?php echo  $xPaisid; ?>';
        var _ordendet = '<?php echo  $xOrdenDet; ?>';
       

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

        var _detalle = $.trim($('#txtDetalle').val());
        var _valorV =  $.trim($('#txtValorV').val());
        _ordendet++;

        if($.trim($('#txtValorI').val()).length == 0){
            var _valorI = 0;
        }else{
            _valorI = $.trim($('#txtValorI').val());
        }

                 $datosDetalle ={
                    xxPacaId: _pacaid,
                    xxPaisId: _paisid,
                    xxDetalle: _detalle,
                    xxValorV: _valorV,
                    xxValorI: _valorI
                }

                var xrespuesta = $.post("codephp/consultar_detalle.php", $datosDetalle);
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
							url: "codephp/grabar_detalle.php",
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
                                    
                                    var _btnGrup = '<td><div class="text-center"><div class="btn-group"><button type="button" id="btnDelete" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1 btnDelete" id="">' +
                                                   '<i class="fa fa-trash"></i></button><button type="button" id="btnEditar" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 btnEditar" id="">' +
                                                   '<i class="fa fa-edit"></i></button></div></div></td>';
                                    

                                    TableData = $('#tblDetalle').DataTable();  
                                    TableData.column(0).visible(0);

                                    TableData.row.add([_padeid, _padenom, _padev, _padei, _btnChk,_btnGrup]).draw();

                                    $("#txtDetalle").val("");
                                    $("#txtValorV").val("");
                                    $("#txtValorI").val("");

									
							

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



    function f_Pade(_idpaca,_idpade){
        alert('aki');

    }

    $(document).on("click",".btnEditar",function(){

       

        $("#kt_modal_new_card").modal("show");

    });
    
 

  



</script> 	