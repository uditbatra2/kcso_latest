<div class="page-wrapper">
            <div class="content container-fluid">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title"><?=$title?></h4>
                    </div>
                    <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="#" class="btn btn-primary btn-rounded pull-right" data-toggle="modal" data-target="#add_sub_category" onClick="javascript:$('form#add-sub-category')[0].reset();var validator = $( 'form#add-sub-category' ).validate();validator.resetForm();$('form#add-sub-category select').val('').trigger('change');"><i class="fa fa-plus"></i> Add Sub Category</a>
                        <div class="view-icons">
                            <!---<a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
                            <a href="clients-list.html" class="list-view btn btn-link active"><i class="fa fa-bars"></i></a>-->
                        </div>
                    </div>
                </div>
				<?php
			   $form_attribute=array(
						'name' => 'search-subcategory',
						'class' => '',
						'method' =>"get",
						'autocomplete'=>"off",
						'id' => 'search-subcategory',
						'novalidate' => 'novalidate',
						);
				$hidden = array('action' => 'search-subcategory');
				// Form Open
				echo form_open('admin/sub_categories_list',$form_attribute,$hidden);
				?>						
                <div class="row filter-row">
				
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <label class="focus-label">Sub Category Name</label>
                            <input type="text" class="form-control floating" name="serach-query" id="serach-query" value="<?=(isset($searchcategoryKeyword) && !empty($searchcategoryKeyword))?$searchcategoryKeyword:'';?>">
                        </div>
                    </div>
					<div class="col-sm-6 col-md-3">
                        <div class="fform-group form-focus select-focus">
                            <label class="focus-label">Category</label>
                            <select class="select floating" name="parent_id" id="parent_id">
                                <option value="">--Select--</option>
								<?php if (!empty($catData)){foreach($catData as $catDataV){?>
                                <option value="<?=$catDataV->id?>" <?=(isset($searchsubcategoryKeyword) && !empty($searchsubcategoryKeyword) && $searchsubcategoryKeyword==$catDataV->id)? 'selected':'';?>><?=$catDataV->name?></option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-2 col-md-3">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Status</label>
                            <select class="select floating" name="status" id="status">
                                <option value="">--Select--</option>
                                <option value="1" <?=(isset($statusKeyword) && !empty($statusKeyword) && $statusKeyword==1)? 'selected':'';?>>Active</option>
                                <option value="0" <?=(isset($statusKeyword) && $statusKeyword != '' && $statusKeyword==0)? 'selected':'';?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-1 col-md-1">
                        <button type="submit" class="btn btn-success">Search</button>
                    </div>
                    <div class="col-sm-1 col-md-1">
						<button type="button" class="btn btn-danger" onclick="javascript:window.location.href='<?=base_url('admin/sub_categories_list')?>';">Clear</button>
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
									    <th>Sr.No.</th>
                                        <th>Category Name</th>
										<th>Sub Category Name</th>
                                        <th>Sub Category Image</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								    <?php
									$srno=1;
                                    $count = 0;
                                    foreach($subCategoryList as $categoryList){								
									$count++;
								    $class=($count % 2 == 1) ? " odd" : " even";
									?>
                                    <tr role="row" class="<?=$class?>">
									    <td><?=$srno?></td>
                                        <td><?=$categoryList->cat_name?></td>
										<td><?=$categoryList->subcat_name?></td>
                                        <td class="lightgallery">
											<?php
											$catfilename = 'uploads/category_images/'.$categoryList->image;
											$cat_file= '../uploads/no-image100x100.jpg';
											$cat_original_file= '../uploads/no-image400x400.jpg';
											if (file_exists($catfilename) && !empty($categoryList->image))
											{
												$cat_file='../uploads/category_images/small/'.$categoryList->image;
                                                $cat_original_file='../uploads/category_images/'.$categoryList->image;													
											}
											?>
											<a href="<?=$cat_original_file?>">
												<img src="<?=$cat_file?>" class="img-thumbnail"/>
											</a>
										</td>
                                        <td>
                                            <div class="dropdown action-label">
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												
												<?=(isset($categoryList->status) && $categoryList->status==1)?'<i class="fa fa-dot-circle-o text-success"></i> Active':'<i class="fa fa-dot-circle-o text-danger"></i> Inactive';?>
												</a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="<?=base_url('admin/sub_category_status?do=active&cat_id='.$categoryList->id)?>"><i class="fa fa-dot-circle-o text-success"></i> Active</a>
                                                    <a class="dropdown-item" href="<?=base_url('admin/sub_category_status?do=inactive&cat_id='.$categoryList->id)?>"><i class="fa fa-dot-circle-o text-danger"></i> Inactive</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#edit_sub_category" onClick="getEditData(<?=$categoryList->id?>);"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <a class="dropdown-item delete-sub-category" href="javascript:void(0);" id="<?=$categoryList->id?>" data-toggle="modal" data-target="#delete_sub_category"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
        <div id="add_sub_category" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Sub Category</h4>
                    </div>
                    <div class="modal-body">
						<div class="m-b-30">
                             <?php
									$form_attribute=array(
											'name' => 'add-sub-category',
											'class' => 'form-horizontal',
											'method'=>"post",
											'id' => 'add-sub-category',
											'novalidate' => 'novalidate',
											);
									$hidden = array('action' => 'addSubCategory');
									// Form Open
									echo form_open_multipart('admin/add_sub_category',$form_attribute,$hidden);
								?>
								<div class="col-sm-7">
									<div class="form-group">
									<label>Category<span class="text-danger">*</span></label>
										<select class="select floating required" name="parent_id" id="parent_id">
											<option value="">Select Category</option>
											<?php if (!empty($catData)){foreach($catData as $catDatas){?>
											<option value="<?=$catDatas->id?>"><?=$catDatas->name?></option>
											<?php } } ?>
										</select>
									</div>
								</div>
							    <div class="col-sm-7">
									<div class="form-group">
										<label>Sub Category Name <span class="text-danger">*</span></label>
										<input class="form-control required" type="text" name="name" id="name">
									</div>
								</div>
                                <div class="col-sm-7">								
									<div class="form-group">
										<label>Sub Category Image <span class="text-danger">*</span></label>
										<input class="form-control required" type="file" name="cat_image" id="cat_image">
										<small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png.</small>
									</div>
								</div>
								<div class="col-sm-7">
									<div class="form-group">
										<label>Sub Category Description <span class="text-danger">*</span></label>
										<textarea class="form-control required" name="description" id="description"></textarea>
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
										<button class="btn btn-primary btn-lg" type="submit">Create Sub Category</button>
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
        <div id="edit_sub_category" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Sub Category</h4>
                    </div>
                    <div class="modal-body">
                        <div class="m-b-30">
                            <?php
								$form_attribute=array(
										'name' => 'edit-sub-category',
										'class' => 'form-horizontal',
										'method'=>"post",
										'id' => 'edit-sub-category',
										'novalidate' => 'novalidate',
										);
								$hidden = array('action' => 'editSubCategory','id'=>'');
								// Form Open
								echo form_open_multipart('admin/add_sub_category',$form_attribute,$hidden);
								?>
								<div class="col-sm-7">
									<div class="form-group">
									<label>Category<span class="text-danger">*</span></label>
										<select class="select floating required" name="parent_id" id="parent_id">
											<option value="">Select Category</option>
											<?php if (!empty($catData)){foreach($catData as $catDatas){?>
											<option value="<?=$catDatas->id?>"><?=$catDatas->name?></option>
											<?php } } ?>
										</select>
									</div>
								</div>
							    <div class="col-sm-7">
									<div class="form-group">
										<label>Sub Category Name <span class="text-danger">*</span></label>
										<input class="form-control required" type="text" name="name" id="name">
									</div>
								</div>
                                <div class="col-sm-7">								
									<div class="form-group">
										<label>Sub Category Image <span class="text-danger">*</span></label>
										<input class="form-control" type="file" name="cat_image" id="cat_image">
										<small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png.</small>
									</div>
								</div>
								<div class="col-sm-7">								
									<div class="form-group">
										<label></label>
										<img id="cat_image_file"/>
									</div>
								</div>
								<div class="col-sm-7">
									<div class="form-group">
										<label>Sub Category Description <span class="text-danger">*</span></label>
										<textarea class="form-control required" name="description" id="description"></textarea>
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
        <div id="delete_sub_category" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content modal-md">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete Sub Category</h4>
                    </div>
                    <div class="modal-body card-box">
					 <?php
						$form_attribute=array(
								'name' => 'delete-sub-category',
								'class' => 'form-horizontal',
								'method'=>"post",
								'id' => 'delete-sub-category',
								'novalidate' => 'novalidate',
								);
						$hidden = array('action' => 'deleteSubCategory','cat_id'=>'');
						//Form Open
						echo form_open_multipart('admin/delete_sub_category',$form_attribute,$hidden);
						?>
                        <p>Do you want to delete the Sub category now with his related table data? This cannot be undone.</p>
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
		
$.validator.addMethod("checkEditCategoryNameAvailable", 
	 function(value, element) {
			var result = false;
			cat_id=$("form[name=edit-sub-category] input[name='id']").val();
			$.ajax({
				type:"POST",
				async: false,
				dataType:"json",
				url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
				data : "cat_name="+value+"&request=check-category-name&action=edit-category&cat_id="+cat_id,
				success: function(data) {
					console.log(data);
					//return false;
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
		var result = false;
		$.ajax({
			type:"POST",
			async: false,
			dataType:"json",
			url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
			data : "cat_name="+value+"&request=check-category-name&action=add-category",
			success: function(data) {
				console.log(data);
				//return false;
				result = (data.dataContent== "0") ? true : false;
			}
		});
		// return true if SHOW NAME is exist in database
		return result; 
	}, 
	"This Category Name is already taken! Try another."
);
/*----------- BEGIN validate CODE -------------------------*/
$('#add-sub-category').validate({
	ignore: [],
	rules: {
	"name": {
		required: true,
		checkCategoryNameAvailable: true
	},
	"cat_image": {
		  required: true,
		  extension: "gif|jpg|png"
		}
	}
});
$('#edit-sub-category').validate({
	ignore: [],
	rules: {
	"name": {
		required: true,
		checkEditCategoryNameAvailable: true
	},
	"cat_image": {
		  required: false,
		  extension: "gif|jpg|png"
		}
	}
});

function getEditData(cat_id){
	var validator = $( "form#edit-sub-category" ).validate();
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
					$("form[name=edit-sub-category] input[name='id']").val(res.dataContent.id);
					$("form[name=edit-sub-category] #name").val(res.dataContent.name);
					$("form[name=edit-sub-category] #description").val(res.dataContent.description);
					$('form[name=edit-sub-category] #parent_id').val(res.dataContent.parent_id).trigger('change');
					$('form[name=edit-sub-category] #status').val(res.dataContent.status).trigger('change');
					img_src= 'uploads/no-image100x100.jpg';
					if (res.dataContent.image != '')
					{
						img_src='../uploads/category_images/small/'+res.dataContent.image;				
					}									
                    $('form[name=edit-sub-category] img#cat_image_file').prop('src', img_src);					
					console.log(res.dataContent);
				}else if (res.dataContent == ''){
					console.log(res);
				}
			}
		}
	});
}
//delete category
$("body").on('click','.delete-sub-category',function(event) {
	event.preventDefault();
	var stringArrayId=$(this).prop("id");
	if(stringArrayId > 0){
		$("form[name=delete-sub-category] input[name='cat_id']").val(stringArrayId);
	}
	//alert(stringArrayId);	
});
</script>