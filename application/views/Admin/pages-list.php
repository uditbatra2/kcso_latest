<div class="page-wrapper">
            <div class="content container-fluid">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title"><?=$title?></h4>
                    </div>
                    <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="#" class="btn btn-primary btn-rounded pull-right" data-toggle="modal" data-target="#add_page" onClick="javascript:$('form#add-page')[0].reset();var validator = $( 'form#add-page' ).validate();validator.resetForm();$('form#add-page select').val('').trigger('change');"><i class="fa fa-plus"></i> Add Page</a>
                        <div class="view-icons">
                            <!---<a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
                            <a href="clients-list.html" class="list-view btn btn-link active"><i class="fa fa-bars"></i></a>-->
                        </div>
                    </div>
                </div>
				<?php
			   $form_attribute=array(
						'name' => 'search-page',
						'class' => '',
						'method' =>"get",
						'autocomplete'=>"off",
						'id' => 'search-page',
						'novalidate' => 'novalidate',
						);
				$hidden = array('action' => 'search-page');
				// Form Open
				echo form_open('admin/pages_list',$form_attribute,$hidden);
				?>						
                <div class="row filter-row">
				
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <label class="focus-label">Page Name</label>
                            <input type="text" class="form-control floating" name="serach-query" id="serach-query" value="<?=(isset($searchpagesKeyword) && !empty($searchpagesKeyword))?$searchpagesKeyword:'';?>">
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Status</label>
                            <select class="select floating" name="status" id="status">
                                <option value="">--Select--</option>
                                <option value="1" <?=(isset($statusKeyword) && !empty($statusKeyword) && $statusKeyword==1)? 'selected':'';?>>Active</option>
                                <option value="0" <?=(isset($statusKeyword) && $statusKeyword != '' && $statusKeyword==0)? 'selected':'';?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-1">
                        <button type="submit" class="btn btn-success"> Search </button>
                    </div>
                    <div class="col-sm-6 col-md-1">
						<button type="button" class="btn btn-danger" onclick="javascript:window.location.href='<?=base_url('admin/pages_list')?>';"> Clear</button>
                    </div>					
                </div>
				<?php
					// Form Close
					echo form_close(); ?>
				<?php if($this->session->flashdata('page_success')){ ?>
					<div class="alert alert-success">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Success!</strong> <?php echo $this->session->flashdata('page_success'); ?>
					</div>

				<?php }else if($this->session->flashdata('page_error')){  ?>
					<div class="alert alert-danger">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Error!</strong> <?php echo $this->session->flashdata('page_error'); ?>
					</div>
				<?php }?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table datatable">
                                <thead>
                                    <tr>
									    <th>Sr.No.</th>
                                        <th>Page Name</th>
										<th>Page Slug</th>
                                        <th>Page Image</th>
										<th>Page Type</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								    <?php
									$srno=1;
                                    $count = 0;
                                    foreach($pagesList as $pagesList){								
									$count++;
								    $class=($count % 2 == 1) ? " odd" : " even";
									?>
                                    <tr role="row" class="<?=$class?>">
									    <td><?=$srno?></td>
                                        <td><?=$pagesList->page_title?></td>
										<td><?=$pagesList->page_slug?></td>
                                        <td class="lightgallery">
											<?php
											$pagefilename = 'uploads/page_images/'.$pagesList->page_image;
											$page_file= '../uploads/no-image100x100.jpg';
											$page_original_file= '../uploads/no-image400x400.jpg';
											if (file_exists($pagefilename) && !empty($pagesList->page_image))
											{
												$page_file='../uploads/page_images/small/'.$pagesList->page_image;
                                                $page_original_file = '../uploads/page_images/'.$pagesList->page_image;												
											}
											?>
											<a href="<?=$page_original_file?>">
												<img src="<?=$page_file?>" class="img-thumbnail"/>
											</a>
										</td>
										<td><?=$pagesList->page_type.' Menu'?></td>
                                        <td>
                                            <div class="dropdown action-label">
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												
												<?=(isset($pagesList->status) && $pagesList->status==1)?'<i class="fa fa-dot-circle-o text-success"></i> Active':'<i class="fa fa-dot-circle-o text-danger"></i> Inactive';?>
												</a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="<?=base_url('admin/page_status?do=active&page_id='.$pagesList->id)?>"><i class="fa fa-dot-circle-o text-success"></i> Active</a>
                                                    <a class="dropdown-item" href="<?=base_url('admin/page_status?do=inactive&page_id='.$pagesList->id)?>"><i class="fa fa-dot-circle-o text-danger"></i> Inactive</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#edit_page" onClick="getEditData(<?=$pagesList->id?>);"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <a class="dropdown-item delete-page" href="javascript:void(0);" id="<?=$pagesList->id?>" data-toggle="modal" data-target="#delete_page"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
								    <?php $srno++; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="add_page" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Page</h4>
                    </div>
                    <div class="modal-body">
						<div class="m-b-30">
                             <?php
									$form_attribute=array(
											'name' => 'add-page',
											'class' => 'form-horizontal',
											'method'=>"post",
											'id' => 'add-page',
											'novalidate' => 'novalidate',
											);
									$hidden = array('action' => 'addPage');
									// Form Open
									echo form_open_multipart('admin/add_page',$form_attribute,$hidden);
								?>
								<div class="form-group">
									<label>Page Title <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="page_title" id="page_title">
								</div>
								<div class="form-group">
									<label>Page Slug <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="page_slug" id="page_slug" readonly>
								</div>
								<div class="form-group">
									<label>Meta Tag <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="meta_tag" id="meta_tag">
								</div>
								<div class="form-group">
									<label>Meta Keyword <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="meta_keyword" id="meta_keyword">
								</div>
								<div class="form-group">
									<label>Meta Description <span class="text-danger">*</span></label>
									<textarea class="form-control required" name="meta_description" id="meta_description"></textarea>
								</div>
								<div class="form-group">
									<label>Page Image <span class="text-danger">*</span></label>
									<div>
										<input class="form-control required" type="file" name="page_image" id="page_image">
										<small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png.</small>
									</div>
								</div>
								<div class="form-group">
									<label>Page Content <span class="text-danger">*</span></label>
									<textarea class="form-control required text-editor" name="page_long_content" id="page_long_content"></textarea>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Page Type <span class="text-danger">*</span></label>
											<select class="select required" name="page_type" id="page_type">
												<option value="">Select Page Type</option>
												<option value="Top">Top Menu</option>
												<option value="Footer">Footer Menu</option>
												<option value="Both">Both Menu</option>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="display-block">Page Status <span class="text-danger">*</span></label>
									<div class="form-check form-check-inline">
										<input class="form-check-input required" type="radio" name="status" id="page_active" value="1" checked>
										<label class="form-check-label" for="page_active">Active</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input required" type="radio" name="status" id="page_inactive" value="0">
										<label class="form-check-label" for="page_inactive">Inactive</label>
									</div>
								</div>
                                <div class="col-sm-7">								
									<div class="m-t-20">
										<button class="btn btn-primary btn-lg" type="submit">Create Page</button>
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
        <div id="edit_page" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Page</h4>
                    </div>
                    <div class="modal-body">
                        <div class="m-b-30">
                            <?php
								$form_attribute=array(
										'name' => 'edit-page',
										'class' => 'form-horizontal',
										'method'=>"post",
										'id' => 'edit-page',
										'novalidate' => 'novalidate',
										);
								$hidden = array('action' => 'editPage','id'=>'');
								// Form Open
								echo form_open_multipart('admin/add_page',$form_attribute,$hidden);
								?>
							    <div class="form-group">
									<label>Page Title <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="page_title" id="page_title">
								</div>
								<div class="form-group">
									<label>Page Slug <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="page_slug" id="page_slug" readonly>
								</div>
								<div class="form-group">
									<label>Meta Tag <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="meta_tag" id="meta_tag">
								</div>
								<div class="form-group">
									<label>Meta Keyword <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="meta_keyword" id="meta_keyword">
								</div>
								<div class="form-group">
									<label>Meta Description <span class="text-danger">*</span></label>
									<textarea class="form-control required" name="meta_description" id="meta_description"></textarea>
								</div>
								<div class="form-group">
									<label>Page Image <span class="text-danger"></span></label>
									<div>
										<input class="form-control requireds" type="file" name="page_image" id="page_image">
										<small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png.</small>
									</div>
								</div>
								<div class="col-sm-7">								
									<div class="form-group">
										<label></label>
										<img id="page_image_file"/>
									</div>
								</div>
								<div class="form-group">
									<label>Page Content <span class="text-danger">*</span></label>
									<textarea class="form-control required text-editor" name="page_long_content" id="page_long_content"></textarea>
								</div>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Page Type <span class="text-danger">*</span></label>
											<select class="select required" name="page_type" id="page_type">
												<option value="">Select Page Type</option>
												<option value="Top">Top Menu</option>
												<option value="Footer">Footer Menu</option>
												<option value="Both">Both Menu</option>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="display-block">Page Status <span class="text-danger">*</span></label>
									<div class="form-check form-check-inline">
										<input class="form-check-input required" type="radio" name="status" id="page_active" value="1" checked>
										<label class="form-check-label" for="page_active">Active</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input required" type="radio" name="status" id="page_inactive" value="0">
										<label class="form-check-label" for="page_inactive">Inactive</label>
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
        <div id="delete_page" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content modal-md">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete Page</h4>
                    </div>
                    <div class="modal-body card-box">
					 <?php
						$form_attribute=array(
								'name' => 'delete-page',
								'class' => 'form-horizontal',
								'method'=>"post",
								'id' => 'delete-page',
								'novalidate' => 'novalidate',
								);
						$hidden = array('action' => 'deletePage','page_id'=>'');
						//Form Open
						echo form_open_multipart('admin/delete_page',$form_attribute,$hidden);
						?>
                        <p>Do you want to delete the page now with his related table data? This cannot be undone.</p>
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
$.validator.addMethod("extension", function (value, element, param) {
	param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
	return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
}, jQuery.format("Please enter a value with a valid extensions."));
		
$.validator.addMethod("checkEditPageNameAvailable", 
	 function(value, element) {
			var result = false;
			page_id=$("form[name=edit-page] input[name='id']").val();
			$.ajax({
				type:"POST",
				async: false,
				dataType:"json",
				url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
				data : "page_name="+value+"&request=check-page-name&action=edit-page&page_id="+page_id,
				success: function(data) {
					console.log(data);
					$("form#edit-page #page_slug").val(data.slug);
					//return false;
					result = (data.dataContent== "0") ? true : false;
				}
			});
			// return true if SHOW NAME is exist in database
			return result; 
		}, 
		"This Page Name is already taken! Try another."
);

$.validator.addMethod("checkPageNameAvailable", 
	function(value, element) {
		var result = false;
		$.ajax({
			type:"POST",
			async: false,
			dataType:"json",
			url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
			data : "page_name="+value+"&request=check-page-name&action=add-page",
			success: function(data) {
				console.log(data);
				$("form#add-page #page_slug").val(data.slug);
				//return false;
				result = (data.dataContent== "0") ? true : false;
			}
		});
		// return true if SHOW NAME is exist in database
		return result; 
	}, 
	"This Page Name is already taken! Try another."
);
/*----------- BEGIN validate CODE -------------------------*/
$('#add-page').validate({
	rules: {
	"page_title": {
		required: true,
		checkPageNameAvailable: true
	},
	"page_image": {
		  required: true,
		  extension: "gif|jpg|png"
		}
	}
});
$('#edit-page').validate({
	rules: {
	"page_title": {
		required: true,
		checkEditPageNameAvailable: true
	},
	"page_image": {
		  required: false,
		  extension: "gif|jpg|png"
		}
	}
});

function getEditData(page_id){
	var validator = $( "form#edit-page" ).validate();
	validator.resetForm();
	var dataString="request=edit_page_data&page_id="+page_id;
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
			if (res.dataContent)
			{
				if(res.dataContent != ''){
					$("form[name=edit-page] input[name='id']").val(res.dataContent.id);
					$("form[name=edit-page] #page_title").val(res.dataContent.page_title);
					$("form[name=edit-page] #meta_tag").val(res.dataContent.meta_tag);
					$("form[name=edit-page] #meta_keyword").val(res.dataContent.meta_keyword);
					$("form[name=edit-page] #meta_description").val(res.dataContent.meta_description);
					$("form[name=edit-page] #page_slug").val(res.dataContent.page_slug);
					//$("form[name=edit-page] #page_long_content").val(res.dataContent.page_long_content);
					CKEDITOR.instances['page_long_content'].setData(res.dataContent.page_long_content);
					$("form[name=edit-page] #page_type").val(res.dataContent.page_type).trigger('change');
					$('form[name=edit-page] #status').val(res.dataContent.status).trigger('change');
					img_src= 'uploads/no-image100x100.jpg';
					if (res.dataContent.page_image != '')
					{
						img_src='../uploads/page_images/small/'+res.dataContent.page_image;				
					}									
                    $('form[name=edit-page] img#page_image_file').prop('src', img_src);					
					console.log(res.dataContent);
				}else if (res.dataContent == ''){
					console.log(res);
				}
			}
		}
	});
}
//delete slider
$("body").on('click','.delete-page',function(event) {
	event.preventDefault();
	var stringArrayId=$(this).prop("id");
	if(stringArrayId > 0){
		$("form[name=delete-page] input[name='page_id']").val(stringArrayId);
	}
	//alert(stringArrayId);	
});
</script>
<script src="https://cdn.ckeditor.com/4.8.0/standard-all/ckeditor.js"></script>
<script>
CKEDITOR.replaceClass="text-editor";
$("form[action=edit_service_description]").submit( function(e) {
	var messageLength = CKEDITOR.instances['service_description'].getData().replace(/<[^>]*>/gi, '').length;
	if( !messageLength ) {
		//alert('Please enter a Service Description.');
		$("#ckeditor-msg").html("<font color=red>Please enter a Page Content.</font>");
		CKEDITOR.instances.service_description.focus();
		e.preventDefault();
	}else{

		$("#ckeditor-msg").html("");
	}
});
</script>