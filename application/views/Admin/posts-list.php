<div class="page-wrapper">
            <div class="content container-fluid">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title"><?=$title?></h4>
                    </div>
					<?php
					if (getUserCan('posts_module', 'access_create')) {
					?>
                    <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="#" class="btn btn-primary btn-rounded pull-right" data-toggle="modal" data-target="#add_post" onClick="javascript:$('form#add-post')[0].reset();var validator = $( 'form#add-post' ).validate();validator.resetForm();$('form#add-post select').val('').trigger('change');$('#ckeditor-msg').html('');$('#ckeditor-msg2').html('');getAllCategoriesData('');CKEDITOR.instances['post_short_content'].setData('');CKEDITOR.instances['post_long_content'].setData('');"><i class="fa fa-plus"></i> Add Post</a>
                        <div class="view-icons">
                            <!---<a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
                            <a href="clients-list.html" class="list-view btn btn-link active"><i class="fa fa-bars"></i></a>-->
                        </div>
                    </div>
					<?php } ?>
                </div>
				<?php
			   $form_attribute=array(
						'name' => 'search-post',
						'class' => '',
						'method' =>"get",
						'autocomplete'=>"off",
						'id' => 'search-post',
						'novalidate' => 'novalidate',
						);
				$hidden = array('action' => 'search-posts');
				// Form Open
				echo form_open('admin/posts_list',$form_attribute,$hidden);
				?>						
                <div class="row filter-row">
                    <div class="col-sm-4 col-md-2">
                        <div class="form-group form-focus">
                            <label class="focus-label">Title</label>
                            <input type="text" class="form-control floating" name="serach-query" id="serach-query" value="<?=(isset($searchpagesKeyword) && !empty($searchpagesKeyword))?$searchpagesKeyword:'';?>">
                        </div>
                    </div>
					<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                        <div class="form-group form-focus">
                            <label class="focus-label">From</label>
                            <div class="cal-icon">
                                <input class="form-control floating datetimepicker" type="text" name="date_from" id="date_from" value="<?=(isset($searchuserFromKeyword) && !empty($searchuserFromKeyword))?dateFormat("d-m-Y",$searchuserFromKeyword):'';?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                        <div class="form-group form-focus">
                            <label class="focus-label">To</label>
                            <div class="cal-icon">
                                <input class="form-control floating datetimepicker" type="text" name="date_to" id="date_to" value="<?=(isset($searchuserToKeyword) && !empty($searchuserToKeyword))?dateFormat("d-m-Y",$searchuserToKeyword):'';?>">
                            </div>
                        </div>
                    </div>
					<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Category</label>
                            <select class="select floating" name="cat_id" id="cat_id">
                                <option value="">--Select--</option>
								<?php
								foreach($AllCatDetails as $AllCatDetails){?>
                                <option value="<?=$AllCatDetails->id?>" <?=(isset($catIdKeyword) && !empty($catIdKeyword) && $catIdKeyword==$AllCatDetails->id)? 'selected':'';?>><?=$AllCatDetails->name?></option>
								<?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Status</label>
                            <select class="select floating" name="status" id="status">
                                <option value="">--Select--</option>
                                <option value="1" <?=(isset($statusKeyword) && !empty($statusKeyword) && $statusKeyword==1)? 'selected':'';?>>Pending</option>
                                <option value="2" <?=(isset($statusKeyword) && $statusKeyword != '' && $statusKeyword==2)? 'selected':'';?>>Publish</option>
								<option value="3" <?=(isset($statusKeyword) && $statusKeyword != '' && $statusKeyword==3)? 'selected':'';?>>Draft</option>
                            </select>
                        </div>
                    </div>
                    <div class="ml-3">
                        <button type="submit" class="btn btn-success"> Search </button>
						<button type="button" class="btn btn-danger" onclick="javascript:window.location.href='<?=base_url('admin/posts_list')?>';"> Clear</button>
                    </div>					
                </div>
				<?php
					// Form Close
					echo form_close(); ?>
				<?php if($this->session->flashdata('post_success')){ ?>
					<div class="alert alert-success">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Success!</strong> <?php echo $this->session->flashdata('post_success'); ?>
					</div>

				<?php }else if($this->session->flashdata('post_error')){  ?>
					<div class="alert alert-danger">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Error!</strong> <?php echo $this->session->flashdata('post_error'); ?>
					</div>
				<?php }?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table datatable">
                                <thead>
                                    <tr>
									    <th>Sr.No.</th>
                                        <th>Title</th>
                                        <th>Image</th>
										<th>Slug</th>
										<th>Author</th>
										<th>Categories</th>
										<!--<th>Type</th>-->
                                        <th>Status</th>
										<th width="15%">Date Published</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								    <?php
									$srno=1;
                                    $count = 0;
									$statusArray = [1=>'Pending',2=>'Publish',3=>'Draft'];
                                    foreach($postsList as $postsList){								
									$count++;
								    $class=($count % 2 == 1) ? " odd" : " even";
									?>
                                    <tr role="row" class="<?=$class?>">
									    <td><?=$srno?></td>
                                        <td><?=$postsList->post_title?></td>
										 <td class="lightgallery">
											<?php
											$pagefilename = 'uploads/post_images/'.$postsList->post_image;
											$page_file= '../uploads/no-image100x100.jpg';
											$page_original_file= '../uploads/no-image400x400.jpg';
											if (file_exists($pagefilename) && !empty($postsList->post_image))
											{
												$page_file='../uploads/post_images/small/'.$postsList->post_image;
                                                $page_original_file = '../uploads/post_images/'.$postsList->post_image;												
											}
											?>
											<a href="<?=$page_original_file?>">
												<img src="<?=$page_file?>" class="img-thumbnail" width="60%" height="60%"/>
											</a>
										</td>
										<td><?=$postsList->post_slug?></td>
                                        <td><?=$postsList->author?></td>
                                        <td><?=($postsList->cat_name)?$postsList->cat_name:'-'?></td>										
										<!--<td>
										  <div class="dropdown action-label">
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												  <?php 
													switch($postsList->post_type){
														case 0:
														echo '<i class="fa fa-dot-circle-o text-danger"></i> None Featured';
														break;
														case 1:
														echo '<i class="fa fa-dot-circle-o text-success"></i> Featured';
														break;
													}
													?>
												</a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="<?=base_url('admin/post_type?do=none-featured&post_id='.$postsList->id)?>"><i class="fa fa-dot-circle-o text-danger"></i> None Featured</a>
                                                    <a class="dropdown-item" href="<?=base_url('admin/post_type?do=featured&post_id='.$postsList->id)?>"><i class="fa fa-dot-circle-o text-success"></i> Featured</a>
                                                </div>
                                            </div>
											</td>-->
                                            <td>
                                            <div class="dropdown action-label">
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												<?php 
												switch($postsList->status){
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
												if (getUserCan('posts_module', 'access_write')) {
												?>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="<?=base_url('admin/post_status?do=pending&post_id='.$postsList->id)?>"><i class="fa fa-dot-circle-o text-warning"></i> Pending</a>
                                                    <a class="dropdown-item" href="<?=base_url('admin/post_status?do=publish&post_id='.$postsList->id)?>"><i class="fa fa-dot-circle-o text-success"></i> Publish</a>
													<a class="dropdown-item" href="<?=base_url('admin/post_status?do=draft&post_id='.$postsList->id)?>"><i class="fa fa-dot-circle-o text-danger"></i> Draft</a>
                                                </div>
												<?php } ?>
                                            </div>
                                        </td>
			                            <td><?=$postsList->date_added?></td>
                                        <td class="text-right">
										<?php
										if (getUserCan('posts_module', 'access_write') || getUserCan('posts_module', 'access_delete')) {
										?>
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
												   <?php
													if (getUserCan('posts_module', 'access_write')) {
													?>
                                                    <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#edit_post" onClick="getEditData(<?=$postsList->id?>);getAllCategoriesData(<?=$postsList->id?>);"><i class="fa fa-pencil m-r-5"></i> Edit</a>
													<?php } 
													if (getUserCan('posts_module', 'access_delete')) {
													?>
                                                    <a class="dropdown-item delete-post" href="javascript:void(0);" id="<?=$postsList->id?>" data-toggle="modal" data-target="#delete_post"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
        <div id="add_post" class="modal custom-modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-content modal-lg" style="width:100%">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Post</h4>
                    </div>
                    <div class="modal-body">
						<div class="m-b-30">
                             <?php
									$form_attribute=array(
											'name' => 'add-post',
											'class' => 'form-horizontal',
											'method'=>"post",
											'id' => 'add-post',
											'novalidate' => 'novalidate',
											);
									$hidden = array('action' => 'addPost');
									// Form Open
									echo form_open_multipart('admin/add_post',$form_attribute,$hidden);
								?>
							<div class="row">
							  <div class="col-sm-6">
								<div class="form-group">
									<label class="control-label">Post Title <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="post_title" id="post_title">
								</div>
							 </div>
							  <div class="col-sm-6">
								 <div class="form-group">
									<label class="control-label">Meta Tag <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="meta_tag" id="meta_tag">
								</div>
								</div>
							
							  <div class="col-sm-6">
							    <div class="form-group">
									<label class="control-label">Post Slug <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="post_slug" id="post_slug" readonly>
								</div>							
							  </div>
							  <div class="col-sm-6">
							   <div class="form-group">
									<label class="control-label">Meta Keyword <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="meta_keyword" id="meta_keyword">
								</div>
							 </div>
							
							  <div class="col-sm-6">
								<div class="form-group">
									<label class="control-label">Post Image <span class="text-danger">*</span></label>
									<div>
										<input class="form-control required" type="file" name="post_image" id="post_image">
										<small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png, svg.</small>
									</div>
								</div>
							 </div>
							  <div class="col-sm-6">
							  <div class="form-group">
									<label class="control-label">Meta Description <span class="text-danger">*</span></label>
									<textarea class="form-control required" name="meta_description" id="meta_description"></textarea>
								</div>
							  </div>
							  <div class="col-md-6">
									<div class="form-group">
										<label>Post Type <span class="text-danger">*</span></label>
										<select class="select required" name="post_type[]" id="post_type" multiple>
										</select>
									</div>									
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Post Order <span class="text-danger">*</span></label>
										<select class="order_display required" name="post_display_order[]" id="post_display_order" multiple>
										</select>
									</div>
 								</div>
							  <div class="col-sm-6">
							     <div class="form-group">
									<label class="display-block control-label">Post Status <span class="text-danger">*</span></label>
									<div class="form-check form-check-inline">
										<input class="form-check-input required" type="radio" name="status" id="post_pending" value="1" checked>
										<label class="form-check-label" for="post_pending">Pending</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input required" type="radio" name="status" id="post_publish" value="2">
										<label class="form-check-label" for="post_publish">Publish</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input required" type="radio" name="status" id="post_draft" value="3">
										<label class="form-check-label" for="post_draft">Draft</label>
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
							   
								<div class="form-group">
									<label class="control-label">Post Short Content <span class="text-danger">*</span></label>
									<textarea class="form-control required text-editor" name="post_short_content" id="post_short_content"></textarea>
									<label for="post_short_content" generated="true" id="ckeditor-msg"></label>
								</div>
								<div class="form-group">
									<label class="control-label">Post Long Content <span class="text-danger">*</span></label>
									<textarea class="form-control required text-editor" name="post_long_content" id="post_long_content"></textarea>
									<label for="post_long_content" generated="true" id="ckeditor-msg2"></label>
								</div>
                                <div class="col-sm-7">								
									<div class="m-t-20">
										<button class="btn btn-primary btn-lg" type="submit">Create Post</button>
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
        <div id="edit_post" class="modal custom-modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-content modal-lg" style="width:100%">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Post</h4>
                    </div>
                    <div class="modal-body">
                        <div class="m-b-30">
                            <?php
								$form_attribute=array(
										'name' => 'edit-post',
										'class' => 'form-horizontal',
										'method'=>"post",
										'id' => 'edit-post',
										'novalidate' => 'novalidate',
										);
								$hidden = array('action' => 'editPost','id'=>'');
								// Form Open
								echo form_open_multipart('admin/add_post',$form_attribute,$hidden);
								?>
							<div class="row">
							  <div class="col-sm-6">
							    <div class="form-group">
									<label>Post Title <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="post_title" id="post_title">
								</div>
								</div>
								<div class="col-sm-6">
								<div class="form-group">
									<label>Meta Tag <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="meta_tag" id="meta_tag">
								</div>
								</div>
								
								<div class="col-sm-6">
								<div class="form-group">
									<label>Post Slug <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="post_slug" id="post_slug" readonly>
								</div>
								</div>
								
								<div class="col-sm-6">
								<div class="form-group">
									<label>Meta Keyword <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="meta_keyword" id="meta_keyword">
								</div>
								</div>
								<div class="col-sm-6">
								<div class="form-group">
									<label>Post Image <span class="text-danger"></span></label>
									<div>
										<input class="form-control requireds" type="file" name="post_image" id="post_image">
										<small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png, svg.</small>
									</div>
								</div>
								</div>
								<div class="col-sm-6">
								<div class="form-group">
									<label>Meta Description <span class="text-danger">*</span></label>
									<textarea class="form-control required" name="meta_description" id="meta_description"></textarea>
								</div>
								</div>
								<div class="col-sm-7">								
									<div class="form-group">
										<label></label>
										<img id="post_image_file"/>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Post Type <span class="text-danger">*</span></label>
										<select class="select required" name="post_type[]" id="post_type" multiple>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Post Order <span class="text-danger">*</span></label>
										<select class="order_display required" name="post_display_order[]" id="post_display_order" multiple>
										</select>
									</div>
 								</div>
								<div class="col-md-6">
								<div class="form-group">
									<label class="display-block">Post Status <span class="text-danger">*</span></label>
									<div class="form-check form-check-inline">
										<input class="form-check-input required" type="radio" name="status" id="epost_pending" value="1" checked>
										<label class="form-check-label" for="epost_pending">Pending</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input required" type="radio" name="status" id="epost_publish" value="2">
										<label class="form-check-label" for="epost_publish">Publish</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input required" type="radio" name="status" id="epost_draft" value="3">
										<label class="form-check-label" for="epost_draft">Draft</label>
									</div>
								</div>
								</div>
								<div class="col-sm-12">
							     <div class="form-group">
								<div class="table-responsive m-t-15" id="all-categories" style="max-height:200px;border:solid 1px #ced4da;overflow-y:auto;">
									
                                </div>
								<span id="catID-errorMsg"></span>
								</div>
								</div>
								</div>
								<div class="form-group">
									<label>Post Short Content <span class="text-danger">*</span></label>
									<textarea class="form-control required text-editor" name="post_short_content" id="edit_post_short_content"></textarea>
									<label for="post_short_content" generated="true" id="ckeditor-msg"></label>
								</div>
								<div class="form-group">
									<label>Post Long Content <span class="text-danger">*</span></label>
									<textarea class="form-control required text-editor" name="post_long_content" id="edit_post_long_content"></textarea>
									<label for="post_long_content" generated="true" id="ckeditor-msg2"></label>
								</div>
								
								<div class="form-group">
									<div class="form-check form-check-inline">
										<input class="form-check-input" type="checkbox" name="feature" id="feature" value="1">
										<label class="form-check-label" for="epost_draft">Is Featured</label>
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
        <div id="delete_post" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content modal-md">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete Post</h4>
                    </div>
                    <div class="modal-body card-box">
					 <?php
						$form_attribute=array(
								'name' => 'delete-post',
								'class' => 'form-horizontal',
								'method'=>"post",
								'id' => 'delete-post',
								'novalidate' => 'novalidate',
								);
						$hidden = array('action' => 'deletePost','post_id'=>'');
						//Form Open
						echo form_open('admin/delete_post',$form_attribute,$hidden);
						?>
                        <p>Do you want to delete the post now with his related table data? This cannot be undone.</p>
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
	$("body").on('click','#selectAll', function () {
        var check = $('#chkFileds').is(':checked') ? false:true;
        $("INPUT[id^='chkFileds']").prop('checked', check);
    });
});
$.validator.addMethod("extension", function (value, element, param) {
	param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif|svg";
	return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
}, jQuery.format("Please enter a value with a valid extensions."));	
$.validator.addMethod("checkPostTypeLength", function(value, element, param) {
	var len1 = $('form#'+param+' #post_display_order').val().length;
	var len2 = $('form#'+param+' #post_type').val().length;
	//alert(len2 +'==='+ len1+'======'+param);
	return len2 === len1;
}, "Length should be same as Post Type!");

$.validator.addMethod("checkEditPostNameAvailable", 
	 function(value, element) {
			var result = false;
			post_id=$("form[name=edit-post] input[name='id']").val();
			$.ajax({
				type:"POST",
				async: false,
				dataType:"json",
				url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
				data : "post_name="+value+"&request=check-post-name&action=edit-post&post_id="+post_id,
				success: function(data) {
					//console.log(data);
					$("form#edit-post #post_slug").val(data.slug);
					//return false;
					result = (data.dataContent== "0") ? true : false;
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
			type:"POST",
			async: false,
			dataType:"json",
			url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
			data : "post_name="+value+"&request=check-post-name&action=add-post",
			success: function(data) {
				console.log(data);
				$("form#add-post #post_slug").val(data.slug);
				//return false;
				result = (data.dataContent== "0") ? true : false;
			}
		});
		// return true if SHOW NAME is exist in database
		return result; 
	}, 
	"This Post Title is already taken! Try another."
);
/*----------- BEGIN validate CODE -------------------------*/
$('#add-post').validate({
	rules: {
	"post_title": {
		required: true,
		checkPostNameAvailable: true
	},
	"post_image": {
		  required: true,
		  extension: "gif|jpe?g|png|svg"
		},
	"post_display_order[]": {
		required: true,
		digits: true,
		checkPostTypeLength: "add-post"
	 }
	},
	messages: {
		'post_display_order[]': {digits:"Please enter numbers Only"}
            },
	errorPlacement: function (error, element) {
		if (element.attr("name") == "category_id[]"){
			$("span[id^=catID-errorMsg]").html(error);
		}else {
        error.insertAfter(element);
      }
	}
});
$('#edit-post').validate({
	rules: {
	"post_title": {
		required: true,
		checkEditPostNameAvailable: true
	},
	"post_image": {
		  required: false,
		  extension: "gif|jpe?g|png|svg"
	},
	"post_display_order[]": {
		required: true,
		digits: true,
		checkPostTypeLength: "edit-post"
	 }
	},
	messages: {
		'post_display_order[]': {digits:"Please enter numbers Only"}
            },
	errorPlacement: function (error, element) {
		if (element.attr("name") == "category_id[]"){
			$("span[id^=catID-errorMsg]").html(error);
		}else {
        error.insertAfter(element);
      }
	}
});

function getEditData(post_id){
	var validator = $( "form#edit-post" ).validate();
	validator.resetForm();
	$("#ckeditor-msg").html("");
	$("#ckeditor-msg2").html("");
	var dataString="request=edit_post_data&post_id="+post_id;
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
					$("form[name=edit-post] input[name='id']").val(res.dataContent.id);
					$("form[name=edit-post] #post_title").val(res.dataContent.post_title);
					$("form[name=edit-post] #meta_tag").val(res.dataContent.meta_tag);
					$("form[name=edit-post] #meta_keyword").val(res.dataContent.meta_keyword);
					$("form[name=edit-post] #meta_description").val(res.dataContent.meta_description);
					$("form[name=edit-post] #post_slug").val(res.dataContent.post_slug);
					//$("form[name=edit-page] #page_long_content").val(res.dataContent.page_long_content);
					CKEDITOR.instances['edit_post_short_content'].setData(res.dataContent.post_short_content);
					CKEDITOR.instances['edit_post_long_content'].setData(res.dataContent.post_long_content);
					//$("form[name=edit-post] #post_type").val([1,2,3]).trigger('change');
					//$('form[name=edit-post] #post_type').select2('val', ['1','2','3']).trigger('change');
					//$('form[name=edit-post] #post_type').val('['+res.dataContent.post_type+']').trigger('change');
					//$('form[name=edit-post] input[name=status]').val(res.dataContent.status).trigger('change');
					$("form[name=edit-post] input[name=status][value='"+res.dataContent.status+"']").prop("checked",true).trigger('change');
					if(res.dataContent.feature==1){
						$("form[name=edit-post] input[name=feature]").prop("checked",true);
					}else{
						$("form[name=edit-post] input[name=feature]").prop("checked",false);
					}
					img_src= 'uploads/no-image100x100.jpg';
					if (res.dataContent.post_image != '')
					{
						img_src='../uploads/post_images/small/'+res.dataContent.post_image;				
					}									
                    $('form[name=edit-post] img#post_image_file').prop('src', img_src);					
					console.log(res.dataContent);
				}else if (res.dataContent == ''){
					console.log(res);
				}
			}
		}
	});
}
//delete slider
$("body").on('click','.delete-post',function(event) {
	event.preventDefault();
	var stringArrayId=$(this).prop("id");
	if(stringArrayId > 0){
		$("form[name=delete-post] input[name='post_id']").val(stringArrayId);
	}
	//alert(stringArrayId);	
});

function getAllPagesData(post_id){
	var dataString="request=get-all-pages&type=P&id="+post_id;
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
					$("select[id^=post_type]").html('').html(res.dataContent);
					$("select[id^=post_display_order]").html('').html(res.dataContent1);				
					console.log(res.dataContent);
				}else if (res.dataContent == ''){
					console.log(res);
				}
			}
		}
	});
}

function getAllCategoriesData(post_id){
	var dataString="request=get-all-categories&type=P&id="+post_id;
	getAllPagesData(post_id);
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
					$("div[id^=all-categories]").html('').html(res.dataContent);				
					//console.log(res.dataContent);
				}else if (res.dataContent == ''){
					console.log(res);
				}
			}
		}
	});
}
</script>
<script src="https://cdn.ckeditor.com/4.8.0/standard-all/ckeditor.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>assets/plugins/ckfinder/ckfinder.js"></script>
<script>
var editor = CKEDITOR.replaceClass="text-editor";
CKFinder.setupCKEditor( CKEDITOR.instances['text-editor'], '<?=base_url(); ?>assets/plugins/ckfinder/;?>' );
$("form[id=add-post]").submit( function(e) {
	var messageLength = CKEDITOR.instances['post_short_content'].getData().replace(/<[^>]*>/gi, '').length;
	var messageLength2 = CKEDITOR.instances['post_long_content'].getData().replace(/<[^>]*>/gi, '').length;
	$("#ckeditor-msg").html("");
	$("#ckeditor-msg2").html("");
	if( !messageLength ) {
		//alert('Please enter a Service Description.');
		$("#ckeditor-msg").html("<font color=red>This field is required .</font>");
		CKEDITOR.instances.post_short_content.focus();
		e.preventDefault();
	}
	if( !messageLength2 ) {
		//alert('Please enter a Service Description.');
		$("#ckeditor-msg2").html("<font color=red>This field is required.</font>");
		CKEDITOR.instances.post_long_content.focus();
		e.preventDefault();
	}
});
$("form[id=edit-post]").submit( function(e) {
	var messageLength = CKEDITOR.instances['edit_post_short_content'].getData().replace(/<[^>]*>/gi, '').length;
	var messageLength2 = CKEDITOR.instances['edit_post_long_content'].getData().replace(/<[^>]*>/gi, '').length;
	$("#ckeditor-msg").html("");
	$("#ckeditor-msg2").html("");
	if( !messageLength ) {
		//alert('Please enter a Service Description.');
		$("#ckeditor-msg").html("<font color=red>This field is required .</font>");
		CKEDITOR.instances.edit_post_short_content.focus();
		e.preventDefault();
	}
	if( !messageLength2 ) {
		//alert('Please enter a Service Description.');
		$("#ckeditor-msg2").html("<font color=red>This field is required.</font>");
		CKEDITOR.instances.edit_post_long_content.focus();
		e.preventDefault();
	}
});
</script>