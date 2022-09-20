<div class="page-wrapper">
            <div class="content container-fluid">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title"><?=$title?></h4>
                    </div>
					<?php
					if (getUserCan('categories_module', 'access_create')) {
					?>
                    <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="#" class="btn btn-primary btn-rounded pull-right" data-toggle="modal" data-target="#add_category" onClick="javascript:$('form#add-category')[0].reset();var validator = $( 'form#add-category' ).validate();validator.resetForm();$('form#add-category select').val('').trigger('change');"><i class="fa fa-plus"></i> Add Category</a>
                        <div class="view-icons">
                            <!---<a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
                            <a href="clients-list.html" class="list-view btn btn-link active"><i class="fa fa-bars"></i></a>-->
                        </div>
                    </div>
					<?php } ?>
                </div>
				<?php
			   $form_attribute=array(
						'name' => 'search-category',
						'class' => '',
						'method' =>"get",
						'autocomplete'=>"off",
						'id' => 'search-category',
						'novalidate' => 'novalidate',
						);
				$hidden = array('action' => 'search-category');
				// Form Open
				echo form_open('admin/categories_list',$form_attribute,$hidden);
				?>						
                <div class="row filter-row">
				
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <label class="focus-label">Category Name</label>
                            <input type="text" class="form-control floating" name="serach-query" id="serach-query" value="<?=(isset($searchcategoryKeyword) && !empty($searchcategoryKeyword))?$searchcategoryKeyword:'';?>">
                        </div>
                    </div>
					<div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Category Type</label>
                            <select class="select floating" name="type" id="type">
                                <option value="">Select Type</option>
								<option value="P" <?=(isset($typeKeyword) && !empty($typeKeyword) && $typeKeyword=='P')? 'selected':'';?>>Post</option>
								<option value="T" <?=(isset($typeKeyword) && !empty($typeKeyword) && $typeKeyword=='T')? 'selected':'';?>>Testimonial</option>
								<option value="CS" <?=(isset($typeKeyword) && !empty($typeKeyword) && $typeKeyword=='CS')? 'selected':'';?>>Case Study</option>
								<option value="CA" <?=(isset($typeKeyword) && !empty($typeKeyword) && $typeKeyword=='CA')? 'selected':'';?>>Career</option>
								<option value="TM" <?=(isset($typeKeyword) && !empty($typeKeyword) && $typeKeyword=='TM')? 'selected':'';?>>Team</option>
								<option value="CL" <?=(isset($typeKeyword) && !empty($typeKeyword) && $typeKeyword=='CL')? 'selected':'';?>>Client</option>
                            </select>
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
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-success"> Search </button>
						<button type="button" class="btn btn-danger" onclick="javascript:window.location.href='<?=base_url('admin/categories_list')?>';">Clear</button>
                    </div>					
                </div>
				<?php
					// Form Close
					echo form_close(); ?>
				<?php if($this->session->flashdata('cat_success')){ ?>
					<div class="alert alert-success">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Success!</strong> <?php echo $this->session->flashdata('cat_success'); ?>
					</div>

				<?php }else if($this->session->flashdata('cat_error')){  ?>
					<div class="alert alert-danger">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Error!</strong> <?php echo $this->session->flashdata('cat_error'); ?>
					</div>
				<?php }?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table datatable">
                                <thead>
                                    <tr>
									    <th width="1%">Sr.No.</th>
                                        <th width="12%">Category Name</th>
										<th width="12%">Category Slug</th>
										<th width="12%">Category Description</th>
										<th width="12%">Category Type</th>
                                        <th width="12%">Status</th>
                                        <th class="text-right" width="1%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								    <?php
									$srno=1;
                                    $count = 0;
                                    foreach($categoryList as $categoryList){								
									$count++;
								    $class=($count % 2 == 1) ? " odd" : " even";
									?>
                                    <tr role="row" class="<?=$class?>">
									    <td><?=$srno?></td>
                                        <td><?=$categoryList->name?></td>
										<td><?=($categoryList->slug)?$categoryList->slug:'-'?></td>
										<td><?=($categoryList->description)?$categoryList->description:'-'?></td>
										<td>
										<?php
										switch($categoryList->type){
											case 'P':
											$type = 'Post';
											break;
							
											case 'T':
												$type = 'Testimonial';
											break;
							
											case 'CS':
												$type = 'Case Study';
											break;
											case 'CA':
												$type = 'Career';
											break;
											case 'TM':
												$type = 'Team';
											break;
											case 'CL':
												$type = 'Client';
											break;
										}
										echo $type;
										?>
										</td>
                                        <td>
                                            <div class="dropdown action-label">
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												
												<?=(isset($categoryList->status) && $categoryList->status==1)?'<i class="fa fa-dot-circle-o text-success"></i> Active':'<i class="fa fa-dot-circle-o text-danger"></i> Inactive';?>
												</a>
												<?php
												if (getUserCan('categories_module', 'access_write')) {
												?>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="<?=base_url('admin/category_status?do=active&cat_id='.$categoryList->id)?>"><i class="fa fa-dot-circle-o text-success"></i> Active</a>
                                                    <a class="dropdown-item" href="<?=base_url('admin/category_status?do=inactive&cat_id='.$categoryList->id)?>"><i class="fa fa-dot-circle-o text-danger"></i> Inactive</a>
                                                </div>
												<?php } ?>
                                            </div>
                                        </td>
                                        <td class="text-right">
										<?php
										if (getUserCan('categories_module', 'access_write') || getUserCan('categories_module', 'access_delete')) {
										?>
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
													<?php
													if (getUserCan('categories_module', 'access_write')) {
													?>
                                                    <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#edit_category" onClick="getEditData(<?=$categoryList->id?>);"><i class="fa fa-pencil m-r-5"></i> Edit</a>
													<?php } 
													if (getUserCan('categories_module', 'access_delete')) {
													?>
                                                    <a class="dropdown-item delete-category" href="javascript:void(0);" id="<?=$categoryList->id?>" Dtype="<?=$categoryList->type?>" data-toggle="modal" data-target="#delete_category"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
													<?php } ?>
                                                </div>
                                            </div>
											<?php } ?>
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
        <div id="add_category" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Category</h4>
                    </div>
                    <div class="modal-body">
						<div class="m-b-30">
                             <?php
									$form_attribute=array(
											'name' => 'add-category',
											'class' => 'form-horizontal',
											'method'=>"post",
											'id' => 'add-category',
											'novalidate' => 'novalidate',
											);
									$hidden = array('action' => 'addCategory');
									// Form Open
									echo form_open_multipart('admin/add_category',$form_attribute,$hidden);
								?>
							    <div class="col-sm-7">
									<div class="form-group">
										<label>Category Name <span class="text-danger">*</span></label>
										<input class="form-control required" type="text" name="name" id="name">
									</div>
								</div>
								<div class="col-sm-7">
								<div class="form-group">
									<label>Category Slug <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="slug" id="slug" readonly>
								</div>
								</div>
								<div class="col-sm-7">
									<div class="form-group">
										<label>Category Description <span class="text-danger">*</span></label>
										<textarea class="form-control required" name="description" id="description"></textarea>
									</div>
								</div>
								<div class="col-sm-7">
									<div class="form-group">
										<label>Type <span class="text-danger">*</span></label>
										 <select class="select floating required" name="type" id="type">
											<option value="">Select Type</option>
											<option value="P">Post</option>
											<option value="T">Testimonial</option>
											<option value="CS">Case Study</option>
											<option value="CA">Career</option>
											<option value="TM">Team</option>
											<option value="CL">Client</option>
										</select>
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
									<div class="form-group">
										<label>Display Order <span class="text-danger">*</span></label>
										 <input class="form-control required" type="number" name="reorder" id="reorder">
									</div>
								</div>
                                <div class="col-sm-7">								
									<div class="m-t-20">
										<button class="btn btn-primary btn-lg" type="submit">Create Category</button>
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
        <div id="edit_category" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Category</h4>
                    </div>
                    <div class="modal-body">
                        <div class="m-b-30">
                            <?php
								$form_attribute=array(
										'name' => 'edit-category',
										'class' => 'form-horizontal',
										'method'=>"post",
										'id' => 'edit-category',
										'novalidate' => 'novalidate',
										);
								$hidden = array('action' => 'editCategory','id'=>'');
								// Form Open
								echo form_open_multipart('admin/add_category',$form_attribute,$hidden);
								?>
							    <div class="col-sm-7">
									<div class="form-group">
										<label>Category Name <span class="text-danger">*</span></label>
										<input class="form-control required" type="text" name="name" id="name">
									</div>
								</div>
								<div class="col-sm-7">
								<div class="form-group">
									<label>Category Slug <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="slug" id="slug" readonly>
								</div>
								</div>
								<div class="col-sm-7">
									<div class="form-group">
										<label>Category Description <span class="text-danger">*</span></label>
										<textarea class="form-control required" name="description" id="description"></textarea>
									</div>
								</div>
								<div class="col-sm-7">
									<div class="form-group">
										<label>Type <span class="text-danger">*</span></label>
										 <select class="select floating required" name="type" id="type">
											<option value="">Select Type</option>
											<option value="P">Post</option>
											<option value="T">Testimonial</option>
											<option value="CS">Case Study</option>
											<option value="CA">Career</option>
											<option value="TM">Team</option>
											<option value="CL">Client</option>
										</select>
										<span class="text-info" id="countInfo"></span>
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
									<div class="form-group">
										<label>Display Order <span class="text-danger">*</span></label>
										 <input class="form-control required" type="number" name="reorder" id="reorder">
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
        <div id="delete_category" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content modal-md">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete Category</h4>
                    </div>
                    <div class="modal-body card-box">
					 <?php
						$form_attribute=array(
								'name' => 'delete-category',
								'class' => 'form-horizontal',
								'method'=>"post",
								'id' => 'delete-category',
								'novalidate' => 'novalidate',
								);
						$hidden = array('action' => 'deleteCategory','cat_id'=>'','type'=>'');
						//Form Open
						echo form_open_multipart('admin/delete_category',$form_attribute,$hidden);
						?>
                        <p>Do you want to delete the Category now with his related table data? This cannot be undone.</p>
						<div id="all-categories-post"></div>
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
		
$.validator.addMethod("checkEditCategoryNameAvailable", 
	 function(value, element) {
			var type = $('form[name=edit-category] #type').val();
			if(type == ''){
				return true;
			}
			var result = false;
			cat_id=$("form[name=edit-category] input[name='id']").val();
			$.ajax({
				type:"POST",
				async: false,
				dataType:"json",
				url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
				data : "cat_name="+value+"&request=check-category-name&type="+type+"&action=edit-category&cat_id="+cat_id,
				success: function(data) {
					console.log(data);
					//return false;
					$("form#edit-category #slug").val(data.slug);
					result = (data.dataContent== "0") ? true : false;
				}
			});
			// return true if SHOW NAME is exist in database
			return result; 
		}, 
		"This Category Name is already taken! Try another."
);

$.validator.addMethod("checkCategoryNameAvailable", 
	function(value, element) {
	    var type = $('form[name=add-category] #type').val();
	    if(type == ''){
            return true;
		}
		var result = false;
		$.ajax({
			type:"POST",
			async: false,
			dataType:"json",
			url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
			data : "cat_name="+value+"&request=check-category-name&type="+type+"&action=add-category",
			success: function(data) {
				console.log(data);
				//return false;
				$("form#add-category #slug").val(data.slug);
				result = (data.dataContent== "0") ? true : false;
			}
		});
		// return true if SHOW NAME is exist in database
		return result; 
	}, 
	"This Category Name is already taken! Try another."
);
/*----------- BEGIN validate CODE -------------------------*/
$('#add-category').validate({
	rules: {
	"name": {
		required: true,
		checkCategoryNameAvailable: true
	},
	"cat_image": {
		  required: true,
		  extension: "gif|jpg|png"
		},
	"reorder": {
		  required: true,
		  digits: true
		}
	}
});
$('#edit-category').validate({
	rules: {
	"name": {
		required: true,
		checkEditCategoryNameAvailable: true
	},
	"cat_image": {
		  required: false,
		  extension: "gif|jpg|png"
		},
	"reorder": {
		  required: true,
		  digits: true
		}
	}
});

function getEditData(cat_id){
	var validator = $( "form#edit-category" ).validate();
	validator.resetForm();
	var dataString="request=edit_cat_data&cat_id="+cat_id;
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
					$("form[name=edit-category] input[name='id']").val(res.dataContent.id);
					$("form[name=edit-category] #name").val(res.dataContent.name);
					$("form[name=edit-category] #description").val(res.dataContent.description);
					$("form[name=edit-category] #slug").val(res.dataContent.slug);
					$('form[name=edit-category] #type').val(res.dataContent.type).trigger('change');
					$('form[name=edit-category] #status').val(res.dataContent.status).trigger('change');
					$('form[name=edit-category] #reorder').val(res.dataContent.reorder).trigger('change');
					img_src= 'uploads/no-image100x100.jpg';
					if (res.dataContent.image != '')
					{
						img_src='../uploads/category_images/small/'+res.dataContent.image;				
					}									
                    $('form[name=edit-category] img#cat_image_file').prop('src', img_src);
                    $("form[name=edit-category] #type").prop("disabled", false);
					$("form[name=edit-category] #countInfo").html('');
					switch(res.dataContent.type){
						case 'P':
						 title = 'Posts';
						break;
						case 'T':
							title = 'Testimonials'
						break;
						case 'CS':
							title = 'Case Studies';
						break;
						case 'CA':
							title = 'Career';
						break;
						case 'TM':
							title = 'Team';
						break;
						case 'CL':
							title = 'Client';
						break;
					}
					if(res.isDisabled == 'yes'){
					   $("form[name=edit-category] #type").prop("disabled", true);
					   $("form[name=edit-category] #countInfo").html(`Note: <span class="badge badge-danger">${res.dataCount}</span> ${title} has been linked type does not update.`);
					}

					console.log(res.dataContent);
				}else if (res.dataContent == ''){
					console.log(res);
				}
			}
		}
	});
}
//delete category
$("body").on('click','.delete-category',function(event) {
	event.preventDefault();
	var stringArrayId=$(this).prop("id");
	var type=$(this).attr("Dtype");
	if(stringArrayId > 0){
		$("form[name=delete-category] input[name='cat_id']").val(stringArrayId);
		$("form[name=delete-category] input[name='type']").val(type);
		var dataString="request=get-all-categories-post-status&type="+type+"&cat_id="+stringArrayId;
		//alert(type);
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
						$("div[id^=all-categories-post").html('').html(res.dataContent);				
						console.log(res.dataContent);
					}else if (res.dataContent == ''){
						console.log(res);
					}
				}
			}
		});
	}
	//alert(stringArrayId);	
});

$("body").on('change','input:radio',function() {
	$("#confirm").removeAttr('disabled');
});

</script>
