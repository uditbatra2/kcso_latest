<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-sm-4 col-3">
				<h4 class="page-title"><?= $title ?></h4>
			</div>
			<?php
			if (getUserCan('client_module', 'access_create')) {
			?>
			<div class="col-sm-8 col-9 text-right m-b-20">
				<a href="#" class="btn btn-primary btn-rounded pull-right" data-toggle="modal" data-target="#add_testimonial" onClick="javascript:$('form#add-testimonial')[0].reset();var validator = $( 'form#add-testimonial' ).validate();validator.resetForm();$('form#add-testimonial select').val('').trigger('change');$('#ckeditor-msg').html('');getAllCategoriesData('');CKEDITOR.instances['description'].setData('');"><i class="fa fa-plus"></i> Add Client</a>
				<div class="view-icons">
					<!---<a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
                            <a href="clients-list.html" class="list-view btn btn-link active"><i class="fa fa-bars"></i></a>-->
				</div>
			</div>
			<?php } ?>
		</div>
		<?php
		$form_attribute = array(
			'name' => 'search-testimonials',
			'class' => '',
			'method' => "get",
			'autocomplete' => "off",
			'id' => 'search-testimonials',
			'novalidate' => 'novalidate',
		);
		$hidden = array('action' => 'search-testimonials');
		// Form Open
		echo form_open('admin/client_list', $form_attribute, $hidden);
		?>
		<div class="row filter-row">

			<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
				<div class="form-group form-focus">
					<label class="focus-label">From</label>
					<div class="cal-icon">
						<input class="form-control floating datetimepicker" type="text" name="date_from" id="date_from" value="<?= (isset($searchuserFromKeyword) && !empty($searchuserFromKeyword)) ? dateFormat("d-m-Y", $searchuserFromKeyword) : ''; ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
				<div class="form-group form-focus">
					<label class="focus-label">To</label>
					<div class="cal-icon">
						<input class="form-control floating datetimepicker" type="text" name="date_to" id="date_to" value="<?= (isset($searchuserToKeyword) && !empty($searchuserToKeyword)) ? dateFormat("d-m-Y", $searchuserToKeyword) : ''; ?>">
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
				<div class="form-group form-focus select-focus">
					<label class="focus-label">Category</label>
					<select class="select floating" name="cat_id" id="cat_id">
						<option value="">--Select--</option>
						<?php
						foreach ($AllCatDetails as $AllCatDetails) { ?>
							<option value="<?= $AllCatDetails->id ?>" <?= (isset($catIdKeyword) && !empty($catIdKeyword) && $catIdKeyword == $AllCatDetails->id) ? 'selected' : ''; ?>><?= $AllCatDetails->name ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
				<div class="form-group form-focus select-focus">
					<label class="focus-label">Status</label>
					<select class="select floating" name="status" id="status">
						<option value="">--Select--</option>
						<option value="1" <?= (isset($statusKeyword) && !empty($statusKeyword) && $statusKeyword == 1) ? 'selected' : ''; ?>>Pending</option>
						<option value="2" <?= (isset($statusKeyword) && $statusKeyword != '' && $statusKeyword == 2) ? 'selected' : ''; ?>>Publish</option>
						<option value="3" <?= (isset($statusKeyword) && $statusKeyword != '' && $statusKeyword == 3) ? 'selected' : ''; ?>>Draft</option>
					</select>
				</div>
			</div>
			<div class="ml-3">
				<button type="submit" class="btn btn-success"> Search </button>
				<button type="button" class="btn btn-danger" onclick="javascript:window.location.href='<?= base_url('admin/client_list') ?>';"> Clear</button>
			</div>
		</div>
		<?php
		// Form Close
		echo form_close(); ?>
		<?php if ($this->session->flashdata('team_success')) { ?>
			<div class="alert alert-success">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>Success!</strong> <?php echo $this->session->flashdata('team_success'); ?>
			</div>

		<?php } else if ($this->session->flashdata('team_error')) {  ?>
			<div class="alert alert-danger">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>Error!</strong> <?php echo $this->session->flashdata('team_error'); ?>
			</div>
		<?php } ?>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped custom-table datatable">
						<thead>
							<tr>
								<th>Sr.No.</th>
								<th width="15%">Profile Picture</th>
								<th>Categories</th>
								<th>Status</th>
								<th>Date Published</th>
								<th class="text-right">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$srno = 1;
							$count = 0;
							foreach ($teamList as $testimonialList) {
								$count++;
								$class = ($count % 2 == 1) ? " odd" : " even";
							?>
								<tr role="row" class="<?= $class ?>">
									<td><?= $srno ?></td>
									<td class="lightgallery">
										<?php
										$pagefilename = 'uploads/client_images/' . $testimonialList->picture;
										$page_file = '../uploads/no-image100x100.jpg';
										$page_original_file = '../uploads/no-image400x400.jpg';
										if (file_exists($pagefilename) && !empty($testimonialList->picture)) {
											$page_file = '../uploads/client_images/small/' . $testimonialList->picture;
											$page_original_file = '../uploads/client_images/large/' . $testimonialList->picture;
										}
										?>
										<a href="<?= $page_original_file ?>">
											<img src="<?= $page_file ?>" class="img-thumbnail" width="60%" height="15%" />
										</a>
									</td>
									<td><?= ($testimonialList->cat_name) ? $testimonialList->cat_name : '-' ?></td>
									<td>
										<div class="dropdown action-label">
											<a class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												<?php
												switch ($testimonialList->status) {
													case 1:
														echo '<i class="fa fa-dot-circle-o text-warning"></i> Pending';
														break;
													case 2:
														echo '<i class="fa fa-dot-circle-o text-success"></i> Publish';
														break;
													case 3:
														echo '<i class="fa fa-dot-circle-o text-danger"></i> Draft';
														break;
												}
												?>
											</a>
											<?php
												if (getUserCan('client_module', 'access_write')) {
												?>
											<div class="dropdown-menu">
												<a class="dropdown-item" href="<?= base_url('admin/client_status?do=pending&team_id=' . $testimonialList->id) ?>"><i class="fa fa-dot-circle-o text-warning"></i> Pending</a>
												<a class="dropdown-item" href="<?= base_url('admin/client_status?do=publish&team_id=' . $testimonialList->id) ?>"><i class="fa fa-dot-circle-o text-success"></i> Publish</a>
												<a class="dropdown-item" href="<?= base_url('admin/client_status?do=draft&team_id=' . $testimonialList->id) ?>"><i class="fa fa-dot-circle-o text-danger"></i> Draft</a>
											</div>
											<?php } ?>
										</div>

									</td>
									<td><?= $testimonialList->date_added ?></td>
									<td class="text-right">
									<?php
										if (getUserCan('client_module', 'access_write') || getUserCan('client_module', 'access_delete')) {
										?>
										<div class="dropdown dropdown-action">
											<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
											<div class="dropdown-menu dropdown-menu-right">
											<?php
												if (getUserCan('client_module', 'access_write')) {
												?>
												<a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#edit_testimonial" onClick="getEditData(<?= $testimonialList->id ?>);getAllCategoriesData(<?= $testimonialList->id ?>);"><i class="fa fa-pencil m-r-5"></i> Edit</a>
												<?php } 
													if (getUserCan('client_module', 'access_delete')) {
													?>
												<a class="dropdown-item delete-testimonial" href="javascript:void(0);" id="<?= $testimonialList->id ?>" data-toggle="modal" data-target="#delete_testimonial"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
												<?php } ?>
											</div>
										</div>
										<?php } ?>
									</td>
								</tr>
							<?php $srno++;
							} ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="add_testimonial" class="modal custom-modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<div class="modal-content modal-lg" style="width:100%">
			<div class="modal-header">
				<h4 class="modal-title">Add Client</h4>
			</div>
			<div class="modal-body">
				<div class="m-b-30">
					<?php
					$form_attribute = array(
						'name' => 'add-testimonial',
						'class' => 'form-horizontal',
						'method' => "post",
						'id' => 'add-testimonial',
						'novalidate' => 'novalidate',
					);
					$hidden = array('action' => 'addTestimonial');
					// Form Open
					echo form_open_multipart('admin/add_client', $form_attribute, $hidden);
					?>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="display-block">Status <span class="text-danger">*</span></label>
								<div class="form-check form-check-inline">
									<input class="form-check-input required" type="radio" name="status" id="testi_pending" value="1" checked>
									<label class="form-check-label" for="testi_pending">Pending</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input required" type="radio" name="status" id="testi_publish" value="2">
									<label class="form-check-label" for="testi_publish">Publish</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input required" type="radio" name="status" id="testi_draft" value="3">
									<label class="form-check-label" for="testi_draft">Draft</label>
								</div>
							</div>
						</div>
					
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Profile Picture <span class="text-danger">*</span></label>
								<div>
									<input class="form-control required" type="file" name="picture" id="picture">
									<small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png, svg.</small>
								</div>
							</div>
						</div>

						<div class="col-sm-12">
							<div class="form-group">
								<div class="table-responsive" id="all-categories" style="max-height:200px;border:solid 1px #ced4da;overflow-y:auto;">
								</div>
								<span id="catID-errorMsg"></span>
							</div>
						</div>

					</div>

					<div class="col-sm-7">
						<div class="m-t-20">
							<button class="btn btn-primary btn-lg" type="submit">Create Client</button>
						</div>
					</div>
					<?php
					// Form Close
					echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="edit_testimonial" class="modal custom-modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<div class="modal-content modal-lg" style="width:100%">
			<div class="modal-header">
				<h4 class="modal-title">Edit Client</h4>
			</div>
			<div class="modal-body">
				<div class="m-b-30">
					<?php
					$form_attribute = array(
						'name' => 'edit-testimonial',
						'class' => 'form-horizontal',
						'method' => "post",
						'id' => 'edit-testimonial',
						'novalidate' => 'novalidate',
					);
					$hidden = array('action' => 'editTestimonial', 'id' => '');
					// Form Open
					echo form_open_multipart('admin/add_client', $form_attribute, $hidden);
					?>
					<div class="row">
						

						<div class="col-sm-6">
							<div class="form-group">
								<label class="display-block">Status <span class="text-danger">*</span></label>
								<div class="form-check form-check-inline">
									<input class="form-check-input required" type="radio" name="status" id="etesti_pending" value="1" checked>
									<label class="form-check-label" for="etesti_pending">Pending</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input required" type="radio" name="status" id="etesti_publish" value="2">
									<label class="form-check-label" for="etesti_publish">Publish</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input required" type="radio" name="status" id="etesti_draft" value="3">
									<label class="form-check-label" for="etesti_draft">Draft</label>
								</div>
							</div>
						</div>
					
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label">Profile Picture <span class="text-danger">*</span></label>
								<div>
									<input class="form-control" type="file" name="picture" id="picture">
									<small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png, svg.</small>
								</div>
								<div class="form-group">
									<label></label>
									<img id="testimonial_image_file" width="60%" height="15%"/>
								</div>
							</div>
						</div>

						<div class="col-sm-12">
							<div class="form-group">
								<div class="table-responsive" id="all-categories" style="max-height:200px;border:solid 1px #ced4da;overflow-y:auto;">
								</div>
								<span id="catID-errorMsg"></span>
							</div>
						</div>

					</div>


					<div class="col-sm-7">
						<div class="m-t-20">
							<button class="btn btn-primary btn-lg" type="submit">Save Changes</button>
						</div>
					</div>
					<?php
					// Form Close
					echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="delete_testimonial" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content modal-md">
			<div class="modal-header">
				<h4 class="modal-title">Delete Client</h4>
			</div>
			<div class="modal-body card-box">
				<?php
				$form_attribute = array(
					'name' => 'delete-testimonial',
					'class' => 'form-horizontal',
					'method' => "post",
					'id' => 'delete-testimonial',
					'novalidate' => 'novalidate',
				);
				$hidden = array('action' => 'deleteTestimonial', 'team_id' => '');
				//Form Open
				echo form_open('admin/delete_client', $form_attribute, $hidden);
				?>
				<p>Do you want to delete the client now with his related table data? This cannot be undone.</p>
				<div class="m-t-20"> <a href="javascript:void(0);" class="btn btn-white" data-dismiss="modal">Close</a>
					<button type="submit" class="btn btn-danger">Delete</button>
				</div>
			</div>
			<?php
			// Form Close
			echo form_close(); ?>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$("body").on('click', '#selectAll', function() {
			var check = $('#chkFileds').is(':checked') ? false : true;
			$("INPUT[id^='chkFileds']").prop('checked', check);
		});
	});
	$.validator.addMethod("extension", function(value, element, param) {
		param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif|svg";
		return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
	}, jQuery.format("Please enter a value with a valid extensions."));
	$.validator.addMethod("checkTypeLength", function(value, element, param) {
		var len1 = $('form#' + param + ' #ordering').val().length;
		var len2 = $('form#' + param + ' #type').val().length;
		//alert(len2 +'==='+ len1+'======'+param);
		return len2 === len1;
	}, "Length should be same as Type!");
	$.validator.addMethod("checkEditPostNameAvailable",
		function(value, element) {
			var result = false;
			post_id = $("form[name=edit-post] input[name='id']").val();
			$.ajax({
				type: "POST",
				async: false,
				dataType: "json",
				url: BASE_URL + "ajax/ajaxProcess", // script to validate in server side
				data: "post_name=" + value + "&request=check-post-name&action=edit-post&post_id=" + post_id,
				success: function(data) {
					//console.log(data);
					$("form#edit-post #post_slug").val(data.slug);
					//return false;
					result = (data.dataContent == "0") ? true : false;
				}
			});
			// return true if SHOW NAME is exist in database
			return result;
		},
		"This Post Title is already taken! Try another."
	);

	$.validator.addMethod("checkPostNameAvailable",
		function(value, element) {
			var result = false;
			$.ajax({
				type: "POST",
				async: false,
				dataType: "json",
				url: BASE_URL + "ajax/ajaxProcess", // script to validate in server side
				data: "post_name=" + value + "&request=check-post-name&action=add-post",
				success: function(data) {
					console.log(data);
					$("form#add-post #post_slug").val(data.slug);
					//return false;
					result = (data.dataContent == "0") ? true : false;
				}
			});
			// return true if SHOW NAME is exist in database
			return result;
		},
		"This Post Title is already taken! Try another."
	);
	/*----------- BEGIN validate CODE -------------------------*/
	$('#add-testimonial').validate({
		rules: {
			
			"picture": {
				required: true,
				extension: "gif|jpe?g|png|svg"
			},
		},
		errorPlacement: function(error, element) {
			if (element.attr("name") == "category_id[]") {
				$("span[id^=catID-errorMsg]").html(error);
			} else {
				error.insertAfter(element);
			}
		}
	});
	$('#edit-testimonial').validate({
		rules: {
			
			"picture": {
				required: false,
				extension: "gif|jpe?g|png|svg"
			},
		},
		errorPlacement: function(error, element) {
			if (element.attr("name") == "category_id[]") {
				$("span[id^=catID-errorMsg]").html(error);
			} else {
				error.insertAfter(element);
			}
		}
	});

	function getEditData(testimonial_id) {
		var validator = $("form#edit-testimonial").validate();
		validator.resetForm();
		$("#ckeditor-msg").html("");
		var dataString = "request=edit_client_data&team_id=" + testimonial_id;
		//alert(dataString);
		//return false;
		jQuery.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>" + "ajax/ajaxProcess",
			dataType: 'json',
			data: dataString,
			success: function(res) {
				//console.log(res.dataContent);
				//return false;
				if (res.dataContent) {
					if (res.dataContent != '') {
						$("form[name=edit-testimonial] input[name='id']").val(res.dataContent.id);
						//$("form[name=edit-page] #page_long_content").val(res.dataContent.page_long_content);
						//$("form[name=edit-post] #post_type").val([1,2,3]).trigger('change');
						//$('form[name=edit-post] #post_type').select2('val', ['1','2','3']).trigger('change');
						//$('form[name=edit-post] #post_type').val('['+res.dataContent.post_type+']').trigger('change');
						//$('form[name=edit-post] input[name=status]').val(res.dataContent.status).trigger('change');
						$("form[name=edit-testimonial] input[name=status][value='" + res.dataContent.status + "']").prop("checked", true).trigger('change');
						img_src = 'uploads/no-image100x100.jpg';
						if (res.dataContent.picture != '') {
							img_src = '../uploads/client_images/medium/' + res.dataContent.picture;
						}
						$('form[name=edit-testimonial] img#testimonial_image_file').prop('src', img_src);
						img_src = 'uploads/no-image100x100.jpg';
						console.log(res.dataContent);
					} else if (res.dataContent == '') {
						console.log(res);
					}
				}
			}
		});
	}
	//delete slider
	$("body").on('click', '.delete-testimonial', function(event) {
		event.preventDefault();
		var stringArrayId = $(this).prop("id");
		if (stringArrayId > 0) {
			$("form[name=delete-testimonial] input[name='team_id']").val(stringArrayId);
		}
		//alert(stringArrayId);	
	});

	function getAllPagesData(testimonial_id) {
		var dataString = "request=get-all-pages&type=CL&id=" + testimonial_id;
		//alert(dataString);
		//return false;
		jQuery.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>" + "ajax/ajaxProcess",
			dataType: 'json',
			data: dataString,
			success: function(res) {
				//console.log(res.dataContent);
				//return false;
				if (res.dataContent) {
					if (res.dataContent != '') {
						console.log(res.dataContent);
					} else if (res.dataContent == '') {
						console.log(res);
					}
				}
			}
		});
	}

	function getAllCategoriesData(testimonial_id) {
		var dataString = "request=get-all-categories&type=CL&id=" + testimonial_id;
		//getAllPagesData(testimonial_id);
		//alert(dataString);
		//return false;
		jQuery.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>" + "ajax/ajaxProcess",
			dataType: 'json',
			data: dataString,
			success: function(res) {
				//console.log(res.dataContent);
				//return false;
				if (res.dataContent) {
					if (res.dataContent != '') {
						$("div[id^=all-categories]").html('').html(res.dataContent);
						//console.log(res.dataContent);
					} else if (res.dataContent == '') {
						console.log(res);
					}
				}
			}
		});
	}
</script>
<script src="https://cdn.ckeditor.com/4.8.0/standard-all/ckeditor.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/plugins/ckfinder/ckfinder.js"></script>
<script>
	var editor = CKEDITOR.replaceClass = "text-editor";
	CKFinder.setupCKEditor(CKEDITOR.instances['text-editor'], '<?= base_url(); ?>assets/plugins/ckfinder/;?>');
	$("form[id=add-testimonialS]").submit(function(e) {
		var messageLength = CKEDITOR.instances['description'].getData().replace(/<[^>]*>/gi, '').length;
		$("#ckeditor-msg").html("");
		if (!messageLength) {
			//alert('Please enter a Service Description.');
			$("#ckeditor-msg").html("<font color=red>This field is required .</font>");
			CKEDITOR.instances.description.focus();
			e.preventDefault();
		}
	});
	$("form[id=edit-testimonial]").submit(function(e) {
		var messageLength = CKEDITOR.instances['edit_description'].getData().replace(/<[^>]*>/gi, '').length;
		$("#ckeditor-msg").html("");
		if (!messageLength) {
			//alert('Please enter a Service Description.');
			$("#ckeditor-msg").html("<font color=red>This field is required .</font>");
			CKEDITOR.instances.edit_description.focus();
			e.preventDefault();
		}
	});
</script>