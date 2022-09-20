<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-sm-4 col-3">
				<h4 class="page-title"><?= $title ?></h4>
			</div>
			<!--  <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="#" class="btn btn-primary btn-rounded pull-right" data-toggle="modal" data-target="#add_banner" onClick="javascript:$('form#add-banner')[0].reset();var validator = $( 'form#add-banner' ).validate();validator.resetForm();$('form#add-banner select').val('').trigger('change');"><i class="fa fa-plus"></i> Add Banner</a>
                        <div class="view-icons">
                            <a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
                            <a href="clients-list.html" class="list-view btn btn-link active"><i class="fa fa-bars"></i></a>
                        </div>
                    </div>-->
		</div>
		<?php
		$form_attribute = array(
			'name' => 'search-banner',
			'class' => '',
			'method' => "get",
			'autocomplete' => "off",
			'id' => 'search-banner',
			'novalidate' => 'novalidate',
		);
		$hidden = array('action' => 'search-banner');
		// Form Open
		echo form_open('admin/banners_list', $form_attribute, $hidden);
		?>
		<div class="row filter-row">

			<div class="col-sm-6 col-md-3">
				<div class="form-group form-focus">
					<label class="focus-label">Banner Name</label>
					<input type="text" class="form-control floating" name="serach-query" id="serach-query" value="<?= (isset($searchbannerKeyword) && !empty($searchbannerKeyword)) ? $searchbannerKeyword : ''; ?>">
				</div>
			</div>
			<div class="col-sm-6 col-md-3">
				<div class="form-group form-focus select-focus">
					<label class="focus-label">Status</label>
					<select class="select floating" name="status" id="status">
						<option value="">--Select--</option>
						<option value="1" <?= (isset($statusKeyword) && !empty($statusKeyword) && $statusKeyword == 1) ? 'selected' : ''; ?>>Active</option>
						<option value="0" <?= (isset($statusKeyword) && $statusKeyword != '' && $statusKeyword == 0) ? 'selected' : ''; ?>>Inactive</option>
					</select>
				</div>
			</div>
			<div class="col-md-3">
				<button type="submit" class="btn btn-success"> Search </button>
				<button type="button" class="btn btn-danger" onclick="javascript:window.location.href='<?= base_url('admin/banners_list') ?>';"> Clear </button>
			</div>
		</div>
		<?php
		// Form Close
		echo form_close(); ?>
		<?php if ($this->session->flashdata('banner_success')) { ?>
			<div class="alert alert-success">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>Success!</strong> <?php echo $this->session->flashdata('banner_success'); ?>
			</div>

		<?php } else if ($this->session->flashdata('banner_error')) {  ?>
			<div class="alert alert-danger">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>Error!</strong> <?php echo $this->session->flashdata('banner_error'); ?>
			</div>
		<?php } ?>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped custom-table datatable">
						<thead>
							<tr>
								<th>Sr.No.</th>
								<th>Page</th>
								<th>Banner Name</th>
								<th>Banner Image</th>
								<th>Status</th>
								<th class="text-right">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$srno = 1;
							$count = 0;
							foreach ($bannersList as $bannersList) {
								$count++;
								$class = ($count % 2 == 1) ? " odd" : " even";
							?>
								<tr role="row" class="<?= $class ?>">
									<td><?= $srno ?></td>
									<td><?php $page_detail = $this->admin_model->getPageById($bannersList->page_id);
										if (!empty($page_detail)) {
											echo $page_detail[0]->page_title;
										} else {
											echo 'N/A';
										}

										?></td>
									<td><?= $bannersList->banner_title ?></td>
									<td class="lightgallery">
										<?php
										$bannerfilename = 'uploads/banner_images/' . $bannersList->banner_image;
										$banner_file = '../uploads/no-image100x100.jpg';
										$banner_original_file = '../uploads/no-image400x400.jpg';
										if (file_exists($bannerfilename) && !empty($bannersList->banner_image)) {
											$banner_file = '../uploads/banner_images/small/' . $bannersList->banner_image;
											$banner_original_file = '../uploads/banner_images/' . $bannersList->banner_image;
										}
										?>
										<a href="<?= $banner_original_file ?>">
											<img src="<?= $banner_file ?>" class="img-thumbnail" />
										</a>
									</td>
									<td>
										<div class="dropdown action-label">
											<a class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">

												<?= (isset($bannersList->status) && $bannersList->status == 1) ? '<i class="fa fa-dot-circle-o text-success"></i> Active' : '<i class="fa fa-dot-circle-o text-danger"></i> Inactive'; ?>
											</a>
											<?php
											if (getUserCan('sliders_module', 'access_write')) {
											?>
												<div class="dropdown-menu">
													<a class="dropdown-item" href="<?= base_url('admin/banner_status?do=active&banner_id=' . $bannersList->id) ?>"><i class="fa fa-dot-circle-o text-success"></i> Active</a>
													<a class="dropdown-item" href="<?= base_url('admin/banner_status?do=inactive&banner_id=' . $bannersList->id) ?>"><i class="fa fa-dot-circle-o text-danger"></i> Inactive</a>
												</div>
											<?php } ?>
										</div>
									</td>
									<td class="text-right">
										<?php
										if (getUserCan('sliders_module', 'access_write')) {
										?>
											<div class="dropdown dropdown-action">
												<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
												<div class="dropdown-menu dropdown-menu-right">
													<?php
													if (getUserCan('sliders_module', 'access_write')) {
													?>
														<a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#edit_banner" onClick="getEditData(<?= $bannersList->id ?>);"><i class="fa fa-pencil m-r-5"></i> Edit</a>
													<?php } ?>
													<!--<a class="dropdown-item delete-banner" href="javascript:void(0);" id="<? //=$bannersList->id
													?>" data-toggle="modal" data-target="#delete_banner"><i class="fa fa-trash-o m-r-5"></i> Delete</a>-->
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
<div id="add_banner" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<div class="modal-content modal-lg">
			<div class="modal-header">
				<h4 class="modal-title">Add Banner</h4>
			</div>
			<div class="modal-body">
				<div class="m-b-30">
					<?php
					$form_attribute = array(
						'name' => 'add-banner',
						'class' => 'form-horizontal',
						'method' => "post",
						'id' => 'add-banner',
						'novalidate' => 'novalidate',
					);
					$hidden = array('action' => 'addBanner');
					// Form Open
					echo form_open_multipart('admin/add_banner', $form_attribute, $hidden);
					?>
					<div class="col-sm-7">
						<div class="form-group">
							<label>Banner Name <span class="text-danger">*</span></label>
							<input class="form-control required" type="text" name="banner_title" id="banner_title">
						</div>
					</div>
					<div class="col-sm-7">
								 <div class="form-group">
									<label class="control-label">Meta Tag</label>
									<input class="form-control" type="text" name="meta_tag" id="meta_tag">
								</div>
					</div>
					 <div class="col-sm-7">
							  <div class="form-group">
									<label class="control-label">Meta Description</label>
									<textarea class="form-control" name="meta_description" id="meta_description"></textarea>
								</div>
				  </div>
					<div class="col-sm-7">
						<div class="form-group">
							<label>Banner Image <span class="text-danger">*</span></label>
							<input class="form-control required" type="file" name="banner_image" id="banner_image">
							<small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png.</small>
						</div>
					</div>
					<div class="col-sm-7">
						<div class="form-group">
							<label>Banner Content <span class="text-danger"></span></label>
							<textarea class="form-control requireds" name="banner_description" id="banner_description"></textarea>
						</div>
					</div>
					<div class="col-sm-7">
						<div class="form-group">
							<label>Banner Url <span class="text-danger"></span></label>
							<input class="form-control requireds" type="text" name="banner_url" id="banner_url">
						</div>
					</div>
					<div class="col-sm-7">
						<div class="form-group">
							<label>Status <span class="text-danger">*</span></label>
							<select class="select floating required" name="status" id="status">
								<option value="">Select Status</option>
								<option value="1">Active</option>
								<option value="0">Inactive</option>
							</select>
						</div>
					</div>
					<div class="col-sm-7">
						<div class="m-t-20">
							<button class="btn btn-primary btn-lg" type="submit">Create Banner</button>
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
<div id="edit_banner" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<div class="modal-content modal-lg">
			<div class="modal-header">
				<h4 class="modal-title">Edit Banner</h4>
			</div>
			<div class="modal-body">
				<div class="m-b-30">
					<?php
					$form_attribute = array(
						'name' => 'edit-banner',
						'class' => 'form-horizontal',
						'method' => "post",
						'id' => 'edit-banner',
						'novalidate' => 'novalidate',
					);
					$hidden = array('action' => 'editBanner', 'id' => '');
					// Form Open
					echo form_open_multipart('admin/add_banner', $form_attribute, $hidden);
					?>
					<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label>Banner Name <span class="text-danger">*</span></label>
							<input class="form-control required" type="text" name="banner_title" id="banner_title">
						</div>
					</div>
					<div class="col-sm-6">
								 <div class="form-group">
									<label class="control-label">Meta Tag</label>
									<input class="form-control" type="text" name="meta_tag" id="meta_tag">
								</div>
					</div>
					 <div class="col-sm-6">
							  <div class="form-group">
									<label class="control-label">Meta Description</label>
									<textarea class="form-control" name="meta_description" id="meta_description"></textarea>
								</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label>Banner Image <span class="text-danger">*</span></label>
							<input class="form-control" type="file" name="banner_image" id="banner_image">
							<small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png.</small>
						</div>
						<div class="form-group">
							<label></label>
							<img id="banner_image_file" />
						</div>
					</div>
					<div class="col-sm-12">
						<div class="form-group">
							<label>Banner Content <span class="text-danger"></span></label>
							<textarea class="form-control requireds text-editor" name="banner_description" id="edit_banner_description"></textarea>
							<label for="description" generated="true" id="ckeditor-msg"></label>
						</div>
					</div>
					<div class="col-sm-12">
						<div class="form-group">
							<label>Banner Sub Content <span class="text-danger"></span></label>
							<textarea class="form-control requireds text-editor" name="banner_sub_description" id="edit_sub_banner_description"></textarea>
							<label for="sub_description" generated="true" id="ckeditor-msg1"></label>
						</div>
					</div>
					<div class="col-sm-7">
						<div class="form-group">
							<label>Banner Url <span class="text-danger"></span></label>
							<input class="form-control requireds" type="text" name="banner_url" id="banner_url">
						</div>
					</div>
					<div class="col-sm-7">
						<div class="form-group">
							<label>Status <span class="text-danger">*</span></label>
							<select class="select required" name="status" id="status">
								<option value="">Select Status</option>
								<option value="1">Active</option>
								<option value="0">Inactive</option>
							</select>
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
<div id="delete_banner" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content modal-md">
			<div class="modal-header">
				<h4 class="modal-title">Delete Banner</h4>
			</div>
			<div class="modal-body card-box">
				<?php
				$form_attribute = array(
					'name' => 'delete-banner',
					'class' => 'form-horizontal',
					'method' => "post",
					'id' => 'delete-banner',
					'novalidate' => 'novalidate',
				);
				$hidden = array('action' => 'deleteBanner', 'banner_id' => '');
				//Form Open
				echo form_open_multipart('admin/delete_banner', $form_attribute, $hidden);
				?>
				<p>Do you want to delete the banner now with his related table data? This cannot be undone.</p>
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
<script src="https://cdn.ckeditor.com/4.8.0/standard-all/ckeditor.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>assets/plugins/ckfinder/ckfinder.js"></script>
<script>
	$.validator.addMethod("extension", function(value, element, param) {
		param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
		return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
	}, jQuery.format("Please enter a value with a valid extensions."));

	$.validator.addMethod("checkEditBannerNameAvailable",
		function(value, element) {
			var result = false;
			banner_id = $("form[name=edit-banner] input[name='id']").val();
			$.ajax({
				type: "POST",
				async: false,
				dataType: "json",
				url: BASE_URL + "ajax/ajaxProcess", // script to validate in server side
				data: "banner_name=" + value + "&request=check-banner-name&action=edit-banner&banner_id=" + banner_id,
				success: function(data) {
					console.log(data);
					//return false;
					result = (data.dataContent == "0") ? true : false;
				}
			});
			// return true if SHOW NAME is exist in database
			return result;
		},
		"This Banner Name is already taken! Try another."
	);

	$.validator.addMethod("checkBannerNameAvailable",
		function(value, element) {
			var result = false;
			$.ajax({
				type: "POST",
				async: false,
				dataType: "json",
				url: BASE_URL + "ajax/ajaxProcess", // script to validate in server side
				data: "banner_name=" + value + "&request=check-banner-name&action=add-banner",
				success: function(data) {
					console.log(data);
					//return false;
					result = (data.dataContent == "0") ? true : false;
				}
			});
			// return true if SHOW NAME is exist in database
			return result;
		},
		"This Banner Name is already taken! Try another."
	);
	/*----------- BEGIN validate CODE -------------------------*/
	$('#add-banner').validate({
		rules: {
			"banner_title": {
				required: true,
				checkBannerNameAvailable: false
			},
			"banner_image": {
				required: true,
				extension: "gif|jpg|png"
			}
		}
	});
	$('#edit-banner').validate({
		rules: {
			"banner_title": {
				required: true,
				checkEditBannerNameAvailable: false
			},
			"banner_image": {
				required: false,
				extension: "gif|jpg|png"
			}
		}
	});

	function getEditData(banner_id) {
		var validator = $("form#edit-banner").validate();
		validator.resetForm();
		var dataString = "request=edit_banner_data&banner_id=" + banner_id;
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
						$("form[name=edit-banner] input[name='id']").val(res.dataContent.id);
						$("form[name=edit-banner] #banner_title").val(res.dataContent.banner_title);
						$("form[name=edit-banner] #meta_tag").val(res.dataContent.meta_tag);
						$("form[name=edit-banner] #meta_description").val(res.dataContent.meta_description);
				
						//$("form[name=edit-banner] #banner_description").val(res.dataContent.banner_description);
						CKEDITOR.instances['edit_banner_description'].setData(res.dataContent.banner_description);
						CKEDITOR.instances['edit_sub_banner_description'].setData(res.dataContent.banner_sub_description);
						//$("form[name=edit-banner] #banner_sub_description").val(res.dataContent.banner_sub_description);
						$("form[name=edit-banner] #banner_url").val(res.dataContent.banner_url);
						$('form[name=edit-banner] #status').val(res.dataContent.status).trigger('change');
						img_src = '../uploads/no-image100x100.jpg';
						if (res.dataContent.banner_image != '' && res.dataContent.banner_image) {
							img_src = '../uploads/banner_images/small/' + res.dataContent.banner_image;
						}
						$('form[name=edit-banner] img#banner_image_file').prop('src', img_src);
						console.log(res.dataContent);
					} else if (res.dataContent == '') {
						console.log(res);
					}
				}
			}
		});
	}
	//delete banner
	$("body").on('click', '.delete-banner', function(event) {
		event.preventDefault();
		var stringArrayId = $(this).prop("id");
		if (stringArrayId > 0) {
			$("form[name=delete-banner] input[name='banner_id']").val(stringArrayId);
		}
		//alert(stringArrayId);	
	});
	var editor = CKEDITOR.replaceClass="text-editor";
	CKFinder.setupCKEditor( CKEDITOR.instances['text-editor'], '<?=base_url(); ?>assets/plugins/ckfinder/;?>' );
</script>