<?php
$download_url_query='';
if(isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])){
	$download_url_query='&'.$_SERVER['QUERY_STRING'];
}
?>
<div class="page-wrapper">
            <div class="content container-fluid">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title"><?=$title?></h4>
                    </div>
                    <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="#" class="btn btn-primary btn-rounded pull-right" data-toggle="modal" data-target="#add_news_letter" onClick="javascript:$('form#add-new-letter')[0].reset();var validator = $( 'form#add-new-letter' ).validate();validator.resetForm();$('form#add-new-letter select').val('').trigger('change');"><i class="fa fa-plus"></i> Send Newsletter</a>						
						<a href="#" class="btn btn-primary btn-rounded pull-right" data-toggle="modal" data-target="#add_news_letter_temp" onClick="javascript:$('form#add-new-letter-temp')[0].reset();var validator = $( 'form#add-new-letter-temp' ).validate();validator.resetForm();$('form#add-new-letter-temp select').val('').trigger('change');" style="margin-right: 10px;"><i class="fa fa-plus"></i> Add Newsletter Template</a>						
						<a href="<?=base_url('admin/news_letter_subscribers?do=download-excel'.$download_url_query)?>" class="btn btn-dark pull-left" style="margin-left: -30px;"><i class="fa fa-download"></i> Download subscribers data in excel</a>
                        <div class="view-icons">
                            <!---<a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
                            <a href="clients-list.html" class="list-view btn btn-link active"><i class="fa fa-bars"></i></a>-->
                        </div>
                    </div>
                </div>
				<?php
			   $form_attribute=array(
						'name' => 'search-user',
						'class' => '',
						'method' =>"get",
						'autocomplete'=>"off",
						'id' => 'search-user',
						'novalidate' => 'novalidate',
						);
				$hidden = array('action' => 'search-user');
				// Form Open
				echo form_open('admin/news_letter_subscribers',$form_attribute,$hidden);
				?>						
                <div class="row filter-row">
				
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <label class="focus-label">User Name</label>
                            <input type="text" class="form-control floating" name="serach-query" id="serach-query" value="<?=(isset($searchuserKeyword) && !empty($searchuserKeyword))?$searchuserKeyword:'';?>">
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
                    <div class="col-sm-1 col-md-2">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Newletter Subscribe</label>
                            <select class="select floating" name="status" id="status">
                                <option value="">--Select--</option>
                                <option value="1" <?=(isset($statusKeyword) && !empty($statusKeyword) && $statusKeyword==1)? 'selected':'';?>>Yes</option>
                                <option value="0" <?=(isset($statusKeyword) && $statusKeyword != '' && $statusKeyword==0)? 'selected':'';?>>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-1 col-md-1">
                        <button type="submit" class="btn btn-success">Search</button>
                    </div>
                    <div class="col-sm-2 col-md-2">
						<button type="button" class="btn btn-danger" onclick="javascript:window.location.href='<?=base_url('admin/news_letter_subscribers')?>';">Clear</button>
                    </div>					
                </div>
				<?php
					// Form Close
					echo form_close(); ?>
				<?php if($this->session->flashdata('s_user_success')){ ?>
					<div class="alert alert-success">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Success!</strong> <?php echo $this->session->flashdata('s_user_success'); ?>
					</div>

				<?php }else if($this->session->flashdata('s_user_error')){  ?>
					<div class="alert alert-danger">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Error!</strong> <?php echo $this->session->flashdata('s_user_error'); ?>
					</div>
				<?php }?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table datatable">
                                <thead>
                                    <tr>
									    <th>Sr.No.</th>
                                        <th>User Name</th>
                                        <th>Email Id</th>
                                        <th>Mobile No</th>
										<th>Registered Date</th>
                                        <th>Newletter</th>
                                        <!--<th class="text-right">Action</th>-->
                                    </tr>
                                </thead>
                                <tbody>
								    <?php
									//echo "<pre>";print_r($subscriberListData);
									$srno=1;
                                    $count = 0;
                                    foreach($subscriberListData as $usersList){								
									$count++;
								    $class=($count % 2 == 1) ? " odd" : " even";
									//$productImage=getProductImage($usersList->id,$limit=1);
									//echo "<pre>";print_r($productImage);
									?>
                                    <tr role="row" class="<?=$class?>">
									    <td><?=$srno?></td>
										<td><a href="#"><?=$usersList->name?></a></td>
										<td><?=$usersList->email_id?></td>
										<td><?=$usersList->phone_no?></td>
										<td><?=dateFormat('d-m-Y',$usersList->date_added)?></td>
                                        <td>
                                            <div class="dropdown action-label">
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												
												<?=(isset($usersList->is_subscribe_newletters) && $usersList->is_subscribe_newletters==1)?'<i class="fa fa-dot-circle-o text-success"></i> Subscribe':'<i class="fa fa-dot-circle-o text-danger"></i> Unsubscribe';?>
												</a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="<?=base_url('admin/subscriber_status?do=subscribe&user_id='.$usersList->id)?>"><i class="fa fa-dot-circle-o text-success"></i> Subscribe</a>
                                                    <a class="dropdown-item" href="<?=base_url('admin/subscriber_status?do=unsubscribe&user_id='.$usersList->id)?>"><i class="fa fa-dot-circle-o text-danger"></i> Unsubscribe</a>
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
        <div id="delete_user" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content modal-md">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete User</h4>
                    </div>
                    <div class="modal-body card-box">
					 <?php
						$form_attribute=array(
								'name' => 'delete-user',
								'class' => 'form-horizontal',
								'method'=>"post",
								'id' => 'delete-user',
								'novalidate' => 'novalidate',
								);
						$hidden = array('action' => 'deleteUser','user_id'=>'');
						//Form Open
						echo form_open_multipart('admin/delete_user',$form_attribute,$hidden);
						?>
                        <p>Do you want to delete the user now with his related table data? This cannot be undone.</p>
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
		<div id="add_news_letter" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <h4 class="modal-title">Send Newsletter</h4>
                    </div>
                    <div class="modal-body">
						<div class="m-b-30">
                             <?php
								$form_attribute=array(
										'name' => 'add-new-letter',
										'class' => 'form-horizontal',
										'method'=>"post",
										'id' => 'add-new-letter',
										'novalidate' => 'novalidate',
										);
								$hidden = array('action' => 'sendAddNewletter','user_id'=>'');
								// Form Open
								echo form_open_multipart('admin/send_news_letter',$form_attribute,$hidden);
							?>
                            <div class="form-group">
                                <label class="display-block">To</label>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="status" id="newsletter_subscribers" value="Subscribers" checked="">
									<label class="form-check-label" for="newsletter_subscribers">
									Subscribers
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="status" id="newsletter_unsubscribers" value="Unsubscribers">
									<label class="form-check-label" for="newsletter_unsubscribers">
									Unsubscribers
									</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="status" id="newsletter_all" value="All">
									<label class="form-check-label" for="newsletter_all">
									All
									</label>
								</div>
                            </div>
							<div class="form-group">
                                <label>Template Images <span class="text-danger">*</span></label>
								 <div id="lightgallery" class="row">
								 <?php if(!empty($templateListData)){ foreach($templateListData as $templateListData){?>
									 <div class="col-md-3 col-sm-3 col-4 col-lg-3 col-xl-2" id="template-remove-<?=$templateListData->id?>">
										<div class="product-thumbnail">
										    <?php
												$tempfilename = 'uploads/newsletter_template_images/'.$templateListData->template_image;
												$temp_file= '../uploads/no-image100x100.jpg';
												$temp_original_file = '../uploads/no-image400x400.jpg';
												if (file_exists($tempfilename) && !empty($templateListData->template_image))
												{
													$temp_file='../uploads/newsletter_template_images/small/'.$templateListData->template_image;
													$temp_original_file='../uploads/newsletter_template_images/'.$templateListData->template_image;													
												}
											?>
											<a href="<?=$temp_original_file?>">
											  <img src="<?=$temp_file?>" class="img-thumbnail img-fluid" alt="">
											</a>
											<input class="form-control required" type="radio" name="template_id" id="template_id_<?=$templateListData->id?>" value="<?=$templateListData->id?>">
											<span class="product-remove template-remove" title="remove" id="<?=$templateListData->id?>"><i class="fa fa-close"></i></span>
										</div>
									</div>
								 <?php } }else{ ?>
								 <a href="#" class="btn btn-primary btn-rounded pull-right" onclick="javascript:window.location.href='<?=base_url('admin/news_letter_subscribers')?>';" style="margin-right: 10px;"><i class="fa fa-plus"></i> Add Newsletter Template</a>
								 <?php } ?>
								</div>
							</div>
                            <div class="form-group">
                                <label>Subject <span class="text-danger">*</span></label>
                                <input class="form-control required" type="text" name="subject" id="subject">
                            </div>
							<div class="form-group">
                                <label>Content <span class="text-danger">*</span></label>
                                <textarea class="form-control required editor1" name="message" id="message"></textarea>
								<p id="ckeditor-msg"></p>
                            </div>
                            <div class="m-t-20 text-center">
                                <button class="btn btn-primary btn-lg" type="submit">Send Newsletter</button>
                            </div>
                            <?php
							// Form Close
							echo form_close(); ?>
							</div>
                        </div>
                    </div>
                </div>
        </div>
		<div id="add_news_letter_temp" class="modal custom-modal fade" role="dialog">
		<div class="modal-dialog">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<div class="modal-content modal-lg">
				<div class="modal-header">
					<h4 class="modal-title">Add Template</h4>
				</div>
				<div class="modal-body">
					<div class="m-b-30">
						 <?php
								$form_attribute=array(
										'name' => 'add-new-letter-temp',
										'class' => 'form-horizontal',
										'method'=>"post",
										'id' => 'add-new-letter-temp',
										'novalidate' => 'novalidate',
										);
								$hidden = array('action' => 'addTemplate');
								// Form Open
								echo form_open_multipart('admin/add_newsletter_template',$form_attribute,$hidden);
							?>
							<div class="col-sm-7">								
								<div class="form-group">
									<label>Template Image <span class="text-danger">*</span></label>
									<input class="form-control required" type="file" name="template_image" id="template_image">
									<small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png.</small>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group">
									<label>Template Content <span class="text-danger">*</span></label>
									<textarea class="form-control required editor2" name="template_content" id="template_content"></textarea>
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
									<button class="btn btn-primary btn-lg" type="submit">Create Template</button>
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
    <script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>
	<script>

	ClassicEditor
    .create( document.querySelector( '.editor2' ), {
        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
    } )
    .catch( error => {
        console.log( error );
    } );
	
	
	var Myeditor;
    ClassicEditor
    .create( document.querySelector( '.editor1' ), {
        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
    } )
	.then( editor => {
		Myeditor = editor;
		console.log( editor );
	} )
    .catch( error => {
        console.log( error );
    } );
	
	
	
	/*----------- BEGIN validate CODE -------------------------*/
	
	$("select[name=state_id]").change(function(event,param1,param2)
	{
		var id = $(this).val();
		if(id > 0){
		var dataString = 'state_id=' + id +'&request=get_city_category';
		//alert(dataString);
		//return false;
		$.ajax({
				type: "POST",
				url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
				data: dataString,
				cache: false,
				async: false,
				dataType:"json",
				success: function (html)
				{
					//alert('sujeet');
					$("form#edit-user select[name='city_id'],form#add-user select[name='city_id']").html('').html(html.cityList);
					//$("select[name='category_id']").find("option").eq(0).remove();
					if(param1!="")
					{
					 $("form#edit-user select[name='city_id']").val(param2);
					}
				}
			});
		}
	});
	function getEditData(user_id){
		var validator = $( "form#edit-user" ).validate();
		validator.resetForm();
		var dataString="request=edit_user_data&user_id="+user_id;
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
						$("form[name=edit-user] input[name='id']").val(res.dataContent.id);
						$("form[name=edit-user] #name").val(res.dataContent.name);
						$("form[name=edit-user] #email_id").val(res.dataContent.email_id);
						$("form[name=edit-user] #address").val(res.dataContent.address);
						$("form[name=edit-user] #phone_no").val(res.dataContent.phone_no);
						$("form[name=edit-user] #pin_code").val(res.dataContent.pin_code);
						$("form[name=edit-user] #state_id").val(res.dataContent.state_id).trigger('change');
						$("form[name=edit-user] #city_id").val(res.dataContent.city_id).trigger('change');
						$("form[name=edit-user] #status").val(res.dataContent.status).trigger('change');					
						console.log(res.dataContent);
					}else if (res.dataContent == ''){
						console.log(res);
					}
				}
			}
		});
	}
	$.validator.addMethod("checkMessageisSmsEmpty", 
		 function(value, element) {
				var result = false;	
                //alert(value);				
				var messageLength = value.replace('<p>&nbsp;</p>', '').length;
				//alert(messageLength);
				//return false;
				if( !messageLength ) {
					return false;
				}else{
                 return true;				 
				}
			}, 
			"This field is required."
	);
	
	
	$.validator.addMethod("extension", function (value, element, param) {
	param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
	return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
}, jQuery.format("Please enter a value with a valid extensions."));
	
	$.validator.addMethod("checkTemplatecontentisEmpty", 
		 function(value, element) {
				var result = false;	
                //alert(value);				
				var messageLength = value.replace('<p>&nbsp;</p>', '').length;
				//alert(messageLength);
				//return false;
				if( !messageLength ) {
					return false;
				}else{
                 return true;				 
				}
			}, 
			"This field is required."
	);

	$('#add-new-letter').validate({
		ignore: [],
		rules: {
			"message": {
				required: true,
				checkMessageisSmsEmpty: true
			},
		}
		
	});
	
	
	$('#add-new-letter-temp').validate({
		ignore: [],
		rules: {
			"template_content": {
				required: true,
				checkTemplatecontentisEmpty: true
			},
			"template_image": {
				  required: true,
				  extension: "gif|jpg|png"
				}
		}
		
	});
	
	//delete user
	$("body").on('click','.delete-user',function(event) {
		event.preventDefault();
		var stringArrayId=$(this).prop("id");
		if(stringArrayId > 0){
			$("form[name=delete-user] input[name='user_id']").val(stringArrayId);
		}
		//alert(stringArrayId);	
	});
	
	//delete news letter templates image
	$("body").on('click','.template-remove',function(event) {
		event.preventDefault();
		var stringArrayId=$(this).prop("id");
		if(stringArrayId > 0){
			var dataString="request=delete_template_image_data&temp_image_id="+stringArrayId;
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
						if(res.dataContent == 1){					
							$('#template-remove-'+res.id).remove();					
							console.log(res.dataContent);
						}else if (res.dataContent == 0){
							console.log(res.dataContent);
						}
					}
				}
			});
		}
		//alert(stringArrayId);	
	});
	
	$("body").on('click','input:radio[name=template_id]',function() {
		//event.preventDefault();
		var stringArrayId = $("input:radio[name=template_id]:checked").val()//$(this).prop("id");
		if(stringArrayId > 0){
			var dataString="request=get_template_image_data&temp_image_id="+stringArrayId;
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
							//$('form#add-new-letter #message').html(res.dataContent.template_content);
							
							Myeditor.setData(res.dataContent.template_content );
							
                            //alert(res.dataContent.template_content);							
							console.log(res.dataContent);
						}else if (res.dataContent == ''){
							console.log(res.dataContent);
						}
					}
				}
			});
		}
		//alert(stringArrayId);	
	});
	</script>