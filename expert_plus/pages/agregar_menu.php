<?php 


?>
<div id="kt_content_container" class="container-xxl">
   <div class="card card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-toolbar">
                <a href="?page=seg_menuadmin" class="btn btn-light-primary"><i class="las la-arrow-left"></i>Regresar</a>
            </div>	
        </div>
        <div class="card-body">
            <div class="form-group">
                <div class="d-flex align-items-center mb-3">
                    <span class="font-weight-bold mr-2">Informacion General:</span> &nbsp; &nbsp;
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
                                <label for="exampleFormControlTextarea1" class="form-label">Icono Menu</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                            </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel">
                    <div class="card-body">
                       <div class="table-responsive">							
                            <table class="table table-row-bordered table-row-gray-600 table-hover align-middle gs-0 gy-4" style="width: 100%;">
                                <thead>
                                    <tr class="text-start text-gray-800 fw-bolder fs-7 gs-0">
                                        <th class="ps-4 min-w-10%">Fecha Carga</th>
                                        <th class="min-w-20%">Tipo de Archivo</th>
                                        <th class="min-w-20%">Formato del Archivo</th>
                                        <th class="min-w-10%">Status</th>
                                        <th class="min-w-30%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-bold text-gray-600">
                                    <tr>
                                        <td>2022-04-18 19:27:33</td>
                                        <td>DOCUMENTO DE IDENTIDAD ANVERSO</td>
                                        <td>jpg</td>
                                        <td>
                                            <span class="badge badge-light-primary fs-7 fw-bold">APROBADO</span>
                                        </td>
                                        <td class="text-end">
                                            <button class='btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1' title='Ver Documento' onclick="f_changeStatus($f_id,'\VISUALIZADO\'')" >
                                                <a href='$f_path' target='_blank'><i class='fas fa-eye'></i>
                                            </button>																			
                                            <button class='btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1' title='Download' >
                                                <a href='$f_path' target='_blank'><i class='fas fa-download'></i>
                                            </button>	
                                            <button class='btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1' title='Ver Solicitud' onclick="f_changeStatus($f_id,'\VISUALIZADO\'')" >
                                                <a href='$f_path' target='_blank'><i class='fas fa-clock'></i>
                                            </button>																																							
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2022-04-18 19:27:33</td>
                                        <td>DOCUMENTO DE IDENTIDAD REVERSO</td>
                                        <td>jpg</td>
                                        <td>
                                            <span class="badge badge-light-danger fs-7 fw-bold">ACT. SOLICITADA</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                <span class="svg-icon svg-icon-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z" fill="currentColor" />
                                                        <path opacity="0.3" d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                            </a>
                                            <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                <span class="svg-icon svg-icon-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="currentColor" />
                                                        <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                            </a>
                                            <a href="#" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm">
                                                <span class="svg-icon svg-icon-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z" fill="currentColor" />
                                                        <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z" fill="currentColor" />
                                                        <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>					
                </div>
            </div>
        </form> 
   </div>

</div>

<script>
    $(document).ready(function () {

    });
</script>