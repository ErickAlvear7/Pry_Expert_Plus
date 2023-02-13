<?php







?>

<div id="kt_content_container" class="container-xxl">
	<div class="card card-flush">
		<div class="card-header border-0 pt-6">
			<div class="card-title">
				<div class="d-flex align-items-center position-relative my-1">
					<span class="svg-icon svg-icon-1 position-absolute ms-6">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
							<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor" />
							<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor" />
						</svg>
					</span>
					<input type="text" data-kt-customer-table-filter="search" class="form-control form-control-solid w-250px ps-15" placeholder="Search Customers" />
				</div>
			</div>
			<div class="card-toolbar">
				<div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
					<div class="w-150px me-3">
						<select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Status" data-kt-ecommerce-order-filter="status">
							<option></option>
							<option value="all">All</option>
							<option value="active">Active</option>
							<option value="locked">Locked</option>
						</select>
					</div>
					<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_new_ticket">Add Customer</button>
				</div>
				<div class="d-flex justify-content-end align-items-center d-none" data-kt-customer-table-toolbar="selected">
					<div class="fw-bolder me-5">
					<span class="me-2" data-kt-customer-table-select="selected_count"></span>Selected</div>
					<button type="button" class="btn btn-danger" data-kt-customer-table-select="delete_selected">Delete Selected</button>
				</div>
			</div>
		</div>
		<div class="card-body pt-0">
		  <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
			<thead>
				<tr class="text-start fw-bolder fs-7 text-uppercase gs-0">
					<th class="min-w-125px">Usuario</th>
					<th class="min-w-125px">Login</th>
					<th class="min-w-125px">Departamento</th>
					<th class="min-w-125px">Estado</th>
					<th class="min-w-125px">Reset Password</th>
					<th class="min-w-125px">Editar</th>
				</tr>
			</thead>
			<tbody class="fw-bold text-gray-600">
			   <tr>
			      <td>
				     <a href="#" class="text-gray-600 text-hover-primary mb-1">smith@kpmg.com</a>
				  </td>
			   </tr>
			</tbody>
		  </table>
		</div>
	</div>
</div>
<div class="modal fade" id="kt_modal_new_ticket" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered mw-750px">
		<div class="modal-content rounded">
			<div class="modal-header pb-0 border-0 justify-content-end">
				<div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
					<span class="svg-icon svg-icon-1">
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
							<rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
							<rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
						</svg>
					</span>
				</div>
			</div>
			<div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
				<form id="kt_modal_new_ticket_form" class="form" action="#">
					<div class="mb-13 text-center">
						<h1 class="mb-3">Create Ticket</h1>
						<div class="text-gray-400 fw-bold fs-5">If you need more info, please check
						<a href="" class="fw-bolder link-primary">Support Guidelines</a>.</div>
					</div>
					<div class="d-flex flex-column mb-8 fv-row">
						<label class="d-flex align-items-center fs-6 fw-bold mb-2">
							<span class="required">Subject</span>
							<i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Specify a subject for your issue"></i>
						</label>
						<input type="text" class="form-control form-control-solid" placeholder="Enter your ticket subject" name="subject" />
					</div>
					<div class="row g-9 mb-8">
						<div class="col-md-6 fv-row">
							<label class="required fs-6 fw-bold mb-2">Product</label>
							<select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Select a product" name="product">
								<option value="">Select a product...</option>
								<option value="1">HTML Theme</option>
								<option value="1">Angular App</option>
								<option value="1">Vue App</option>
								<option value="1">React Theme</option>
								<option value="1">Figma UI Kit</option>
								<option value="3">Laravel App</option>
								<option value="4">Blazor App</option>
								<option value="5">Django App</option>
							</select>
						</div>
						<div class="col-md-6 fv-row">
							<label class="required fs-6 fw-bold mb-2">Assign</label>
							<select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Select a Team Member" name="user">
								<option value="">Select a user...</option>
								<option value="1">Karina Clark</option>
								<option value="2">Robert Doe</option>
								<option value="3">Niel Owen</option>
								<option value="4">Olivia Wild</option>
								<option value="5">Sean Bean</option>
							</select>
						</div>
					</div>
					<div class="row g-9 mb-8">
						<div class="col-md-6 fv-row">
							<label class="required fs-6 fw-bold mb-2">Status</label>
							<select class="form-select form-select-solid" data-control="select2" data-placeholder="Open" data-hide-search="true">
								<option value=""></option>
								<option value="1" selected="selected">Open</option>
								<option value="2">Pending</option>
								<option value="3">Resolved</option>
								<option value="3">Closed</option>
							</select>
						</div>
						<div class="col-md-6 fv-row">
							<label class="required fs-6 fw-bold mb-2">Due Date</label>
							<div class="position-relative d-flex align-items-center">
								<div class="symbol symbol-20px me-4 position-absolute ms-4">
									<span class="symbol-label bg-secondary">
										<span class="svg-icon">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
												<rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor" />
												<rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="currentColor" />
												<rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="currentColor" />
												<rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="currentColor" />
											</svg>
										</span>
									</span>
								</div>
								<input class="form-control form-control-solid ps-12" placeholder="Select a date" name="due_date" />
							</div>
						</div>
					</div>
					<div class="d-flex flex-column mb-8 fv-row">
						<label class="fs-6 fw-bold mb-2">Description</label>
						<textarea class="form-control form-control-solid" rows="4" name="description" placeholder="Type your ticket description"></textarea>
					</div>
					<div class="fv-row mb-8">
						<label class="fs-6 fw-bold mb-2">Attachments</label>
						<div class="dropzone" id="kt_modal_create_ticket_attachments">
							<div class="dz-message needsclick align-items-center">
								<span class="svg-icon svg-icon-3hx svg-icon-primary">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
										<path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM14.5 12L12.7 9.3C12.3 8.9 11.7 8.9 11.3 9.3L10 12H11.5V17C11.5 17.6 11.4 18 12 18C12.6 18 12.5 17.6 12.5 17V12H14.5Z" fill="currentColor" />
										<path d="M13 11.5V17.9355C13 18.2742 12.6 19 12 19C11.4 19 11 18.2742 11 17.9355V11.5H13Z" fill="currentColor" />
										<path d="M8.2575 11.4411C7.82942 11.8015 8.08434 12.5 8.64398 12.5H15.356C15.9157 12.5 16.1706 11.8015 15.7425 11.4411L12.4375 8.65789C12.1875 8.44737 11.8125 8.44737 11.5625 8.65789L8.2575 11.4411Z" fill="currentColor" />
										<path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor" />
									</svg>
								</span>
								<div class="ms-4">
									<h3 class="fs-5 fw-bolder text-gray-900 mb-1">Drop files here or click to upload.</h3>
									<span class="fw-bold fs-7 text-gray-400">Upload up to 10 files</span>
								</div>
							</div>
						</div>
					</div>
					<div class="mb-15 fv-row">
						<div class="d-flex flex-stack">
							<div class="fw-bold me-5">
								<label class="fs-6">Notifications</label>
								<div class="fs-7 text-gray-400">Allow Notifications by Phone or Email</div>
							</div>
							<div class="d-flex align-items-center">
								<label class="form-check form-check-custom form-check-solid me-10">
									<input class="form-check-input h-20px w-20px" type="checkbox" name="notifications[]" value="email" checked="checked" />
									<span class="form-check-label fw-bold">Email</span>
								</label>
								<label class="form-check form-check-custom form-check-solid">
									<input class="form-check-input h-20px w-20px" type="checkbox" name="notifications[]" value="phone" />
									<span class="form-check-label fw-bold">Phone</span>
								</label>
							</div>
						</div>
					</div>
					<div class="text-center">
						<button type="reset" id="kt_modal_new_ticket_cancel" class="btn btn-light me-3">Cancel</button>
						<button type="submit" id="kt_modal_new_ticket_submit" class="btn btn-primary">
							<span class="indicator-label">Submit</span>
							<span class="indicator-progress">Please wait...
							<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>