<div class="page-wrapper">
	<div class="content container-fluid">
		<div class="row">
			<div class="col-sm-4 col-3">
				<h4 class="page-title"><?= $title ?></h4>
			</div>
			<?php
			if (getUserCan('sliders_module', 'access_create')) {
			?>
				<div class="col-sm-8 col-9 text-right m-b-20">
					<a href="#" class="btn btn-primary btn-rounded pull-right" data-toggle="modal" data-target="#add_slider" onClick="javascript:$('form#add-slider')[0].reset();var validator = $( 'form#add-slider' ).validate();validator.resetForm();$('form#add-slider select').val('').trigger('change');"><i class="fa fa-plus"></i> Add Slider</a>
					<div class="view-icons">
						<!---<a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
                            <a href="clients-list.html" class="list-view btn btn-link active"><i class="fa fa-bars"></i></a>-->
					</div>
				</div>
			<?php } ?>
		</div>
		<?php
		$form_attribute = array(
			'name' => 'search-slider',
			'class' => '',
			'method' => "get",
			'autocomplete' => "off",
			'id' => 'search-slider',
			'novalidate' => 'novalidate',
		);
		$hidden = array('action' => 'search-slider');
		// Form Open
		echo form_open('admin/sliders_list', $form_attribute, $hidden);
		?>
		<div class="row filter-row">

			<div class="col-sm-6 col-md-3">
				<div class="form-group form-focus">
					<label class="focus-label">Slider Name</label>
					<input type="text" class="form-control floating" name="serach-query" id="serach-query" value="<?= (isset($searchsliderKeyword) && !empty($searchsliderKeyword)) ? $searchsliderKeyword : ''; ?>">
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
				<button type="button" class="btn btn-danger" onclick="javascript:window.location.href='<?= base_url('admin/sliders_list') ?>';"> Clear</button>
			</div>
		</div>
		<?php
		// Form Close
		echo form_close(); ?>
		<?php if ($this->session->flashdata('slider_success')) { ?>
			<div class="alert alert-success">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>Success!</strong> <?php echo $this->session->flashdata('slider_success'); ?>
			</div>

		<?php } else if ($this->session->flashdata('slider_error')) {  ?>
			<div class="alert alert-danger">
				<a href="#" class="close" data-dismiss="alert">&times;</a>
				<strong>Error!</strong> <?php echo $this->session->flashdata('slider_error'); ?>
			</div>
		<?php } ?>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped custom-table datatable">
						<thead>
							<tr>
								<th>Sr.No.</th>
								<th>Slider Name</th>
								<th>Slider Image</th>
								<th>Status</th>
								<th class="text-right">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$srno = 1;
							$count = 0;
							foreach ($sliderList as $sliderList) {
								$count++;
								$class = ($count % 2 == 1) ? " odd" : " even";
							?>
								<tr role="row" class="<?= $class ?>">
									<td><?= $srno ?></td>
									<td><?= $sliderList->slider_name ?></td>
									<td class="lightgallery">
										<?php
										$sliderfilename = 'uploads/slider_images/' . $sliderList->slider_image;
										$slider_file = '../uploads/no-image100x100.jpg';
										$slider_original_file = '../uploads/no-image400x400.jpg';
										if (file_exists($sliderfilename) && !empty($sliderList->slider_image)) {
											$slider_file = '../uploads/slider_images/small/' . $sliderList->slider_image;
											$slider_original_file = '../uploads/slider_images/' . $sliderList->slider_image;
										}
										?>
										<a href="<?= $slider_original_file ?>">
											<img src="<?= $slider_file ?>" class="img-thumbnail" />
										</a>
									</td>
									<td>
										<div class="dropdown action-label">
											<a class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">

												<?= (isset($sliderList->status) && $sliderList->status == 1) ? '<i class="fa fa-dot-circle-o text-success"></i> Active' : '<i class="fa fa-dot-circle-o text-danger"></i> Inactive'; ?>
											</a>
											<?php
											if (getUserCan('sliders_module', 'access_write')) {
											?>
												<div class="dropdown-menu">
													<a class="dropdown-item" href="<?= base_url('admin/slider_status?do=active&slider_id=' . $sliderList->id) ?>"><i class="fa fa-dot-circle-o text-success"></i> Active</a>
													<a class="dropdown-item" href="<?= base_url('admin/slider_status?do=inactive&slider_id=' . $sliderList->id) ?>"><i class="fa fa-dot-circle-o text-danger"></i> Inactive</a>
												</div>
											<?php } ?>
										</div>
									</td>
									<td class="text-right">
										<?php
										if (getUserCan('sliders_module', 'access_write') || getUserCan('sliders_module', 'access_delete')) {
										?>
											<div class="dropdown dropdown-action">
												<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
												<div class="dropdown-menu dropdown-menu-right">
													<?php
													if (getUserCan('sliders_module', 'access_write')) {
													?>
														<a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#edit_slider" onClick="getEditData(<?= $sliderList->id ?>);"><i class="fa fa-pencil m-r-5"></i> Edit</a>
													<?php }
													if (getUserCan('sliders_module', 'access_delete')) {
													?>
														<a class="dropdown-item delete-slider" href="javascript:void(0);" id="<?= $sliderList->id ?>" data-toggle="modal" data-target="#delete_slider"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
<div id="add_slider" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<div class="modal-content modal-lg">
			<div class="modal-header">
				<h4 class="modal-title">Add Slider</h4>
			</div>
			<div class="modal-body">
				<div class="m-b-30">
					<?php
					$form_attribute = array(
						'name' => 'add-slider',
						'class' => 'form-horizontal',
						'method' => "post",
						'id' => 'add-slider',
						'novalidate' => 'novalidate',
					);
					$hidden = array('action' => 'addSlider');
					// Form Open
					echo form_open_multipart('admin/add_slider', $form_attribute, $hidden);
					?>
					<div class="col-sm-7">
						<div class="form-group">
							<label>Slider Name <span class="text-danger">*</span></label>
							<input class="form-control required" type="text" name="slider_name" id="slider_name">
						</div>
					</div>
					<div class="col-sm-7">
						<div class="form-group">
							<label>Slider Image <span class="text-danger">*</span></label>
							<input class="form-control required" type="file" name="slider_image" id="slider_image">
							<small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png.</small>
						</div>
					</div>
					<div class="col-sm-7">
						<div class="form-group">
							<label>Slider Content <span class="text-danger"></span></label>
							<textarea class="form-control requireds" name="slider_content" id="slider_content"></textarea>
						</div>
					</div>
					<div class="col-sm-7">
						<div class="form-group">
							<label>Slider Url <span class="text-danger"></span></label>
							<input class="form-control requireds" type="text" name="slider_url" id="slider_url">
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
							<button class="btn btn-primary btn-lg" type="submit">Create Slider</button>
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
<div id="edit_slider" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<div class="modal-content modal-lg">
			<div class="modal-header">
				<h4 class="modal-title">Edit Slider</h4>
			</div>
			<div class="modal-body">
				<div class="m-b-30">
					<?php
					$form_attribute = array(
						'name' => 'edit-slider',
						'class' => 'form-horizontal',
						'method' => "post",
						'id' => 'edit-slider',
						'novalidate' => 'novalidate',
					);
					$hidden = array('action' => 'editSlider', 'id' => '');
					// Form Open
					echo form_open_multipart('admin/add_slider', $form_attribute, $hidden);
					?>
					<div class="col-sm-7">
						<div class="form-group">
							<label>Slider Name <span class="text-danger">*</span></label>
							<input class="form-control required" type="text" name="slider_name" id="slider_name">
						</div>
					</div>
					<div class="col-sm-7">
						<div class="form-group">
							<label>Slider Image <span class="text-danger">*</span></label>
							<input class="form-control" type="file" name="slider_image" id="slider_image">
							<small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png.</small>
						</div>
					</div>
					<div class="col-sm-7">
						<div class="form-group">
							<label></label>
							<img id="slider_image_file" />
						</div>
					</div>
					<div class="col-sm-7">
						<div class="form-group">
							<label>Slider Content <span class="text-danger"></span></label>
							<textarea class="form-control requireds" name="slider_content" id="slider_content"></textarea>
						</div>
					</div>
					<div class="col-sm-7">
						<div class="form-group">
							<label>Slider Url <span class="text-danger"></span></label>
							<input class="form-control requireds" type="text" name="slider_url" id="slider_url">
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
<div id="delete_slider" class="modal custom-modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content modal-md">
			<div class="modal-header">
				<h4 class="modal-title">Delete Slider</h4>
			</div>
			<div class="modal-body card-box">
				<?php
				$form_attribute = array(
					'name' => 'delete-slider',
					'class' => 'form-horizontal',
					'method' => "post",
					'id' => 'delete-slider',
					'novalidate' => 'novalidate',
				);
				$hidden = array('action' => 'deleteSlider', 'slider_id' => '');
				//Form Open
				echo form_open_multipart('admin/delete_slider', $form_attribute, $hidden);
				?>
				<p>Do you want to delete the slider now with his related table data? This cannot be undone.</p>
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
	$.validator.addMethod("extension", function(value, element, param) {
		param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
		return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
	}, jQuery.format("Please enter a value with a valid extensions."));

	$.validator.addMethod("checkEditSliderNameAvailable",
		function(value, element) {
			var result = false;
			slider_id = $("form[name=edit-slider] input[name='id']").val();
			$.ajax({
				type: "POST",
				async: false,
				dataType: "json",
				url: BASE_URL + "ajax/ajaxProcess", // script to validate in server side
				data: "slider_name=" + value + "&request=check-slider-name&action=edit-slider&slider_id=" + slider_id,
				success: function(data) {
					console.log(data);
					//return false;
					result = (data.dataContent == "0") ? true : false;
				}
			});
			// return true if SHOW NAME is exist in database
			return result;
		},
		"This Slider Name is already taken! Try another."
	);

	$.validator.addMethod("checkSliderNameAvailable",
		function(value, element) {
			var result = false;
			$.ajax({
				type: "POST",
				async: false,
				dataType: "json",
				url: BASE_URL + "ajax/ajaxProcess", // script to validate in server side
				data: "slider_name=" + value + "&request=check-slider-name&action=add-slider",
				success: function(data) {
					console.log(data);
					//return false;
					result = (data.dataContent == "0") ? true : false;
				}
			});
			// return true if SHOW NAME is exist in database
			return result;
		},
		"This Slider Name is already taken! Try another."
	);
	/*----------- BEGIN validate CODE -------------------------*/
	$('#add-slider').validate({
		rules: {
			"slider_name": {
				required: true,
				checkSliderNameAvailable: false
			},
			"slider_image": {
				required: true,
				extension: "gif|jpg|png"
			}
		}
	});
	$('#edit-slider').validate({
		rules: {
			"slider_name": {
				required: true,
				checkEditSliderNameAvailable: false
			},
			"slider_image": {
				required: false,
				extension: "gif|jpg|png"
			}
		}
	});

	function getEditData(slider_id) {
		var validator = $("form#edit-slider").validate();
		validator.resetForm();
		var dataString = "request=edit_slider_data&slider_id=" + slider_id;
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
						$("form[name=edit-slider] input[name='id']").val(res.dataContent.id);
						$("form[name=edit-slider] #slider_name").val(res.dataContent.slider_name);
						$("form[name=edit-slider] #slider_content").val(res.dataContent.slider_content);
						$("form[name=edit-slider] #slider_url").val(res.dataContent.slider_url);
						$('form[name=edit-slider] #status').val(res.dataContent.status).trigger('change');
						img_src = 'uploads/no-image100x100.jpg';
						if (res.dataContent.slider_image != '') {
							img_src = '../uploads/slider_images/small/' + res.dataContent.slider_image;
						}
						$('form[name=edit-slider] img#slider_image_file').prop('src', img_src);
						console.log(res.dataContent);
					} else if (res.dataContent == '') {
						console.log(res);
					}
				}
			}
		});
	}
	//delete slider
	$("body").on('click', '.delete-slider', function(event) {
		event.preventDefault();
		var stringArrayId = $(this).prop("id");
		if (stringArrayId > 0) {
			$("form[name=delete-slider] input[name='slider_id']").val(stringArrayId);
		}
		//alert(stringArrayId);	
	});
</script>