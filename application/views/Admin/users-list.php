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
                        <a href="#" class="btn btn-primary btn-rounded pull-right" data-toggle="modal" data-target="#add_user" onClick="javascript:$('form#add-user')[0].reset();var validator = $( 'form#add-user' ).validate();validator.resetForm();$('form#add-user select').val('').trigger('change');"><i class="fa fa-plus"></i> Add User</a>
						
						<a href="<?=base_url('admin/users_list?do=download-excel'.$download_url_query)?>" class="btn btn-dark pull-left" style="margin-left: 307px;"><i class="fa fa-download"></i> Download users data in excel</a>
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
				echo form_open('admin/users_list',$form_attribute,$hidden);
				?>						
                <div class="row filter-row">
				
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <label class="focus-label">User Name</label>
                            <input type="text" class="form-control floating" name="serach-query" id="serach-query" value="<?=(isset($searchuserKeyword) && !empty($searchuserKeyword))?$searchuserKeyword:'';?>">
                        </div>
                    </div>
					<div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 col-12">
                        <div class="form-group form-focus">
                            <label class="focus-label">From</label>
                            <div class="cal-icon">
                                <input class="form-control floating datetimepicker" type="text" name="date_from" id="date_from" value="<?=(isset($searchuserFromKeyword) && !empty($searchuserFromKeyword))?dateFormat("d-m-Y",$searchuserFromKeyword):'';?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-2 col-12">
                        <div class="form-group form-focus">
                            <label class="focus-label">To</label>
                            <div class="cal-icon">
                                <input class="form-control floating datetimepicker" type="text" name="date_to" id="date_to" value="<?=(isset($searchuserToKeyword) && !empty($searchuserToKeyword))?dateFormat("d-m-Y",$searchuserToKeyword):'';?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1 col-md-2">
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
						<button type="button" class="btn btn-danger" onclick="javascript:window.location.href='<?=base_url('admin/users_list')?>';">Clear</button>
                    </div>					
                </div>
				<?php
					// Form Close
					echo form_close(); ?>
				<?php if($this->session->flashdata('user_success')){ ?>
					<div class="alert alert-success">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Success!</strong> <?php echo $this->session->flashdata('user_success'); ?>
					</div>

				<?php }else if($this->session->flashdata('user_error')){  ?>
					<div class="alert alert-danger">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Error!</strong> <?php echo $this->session->flashdata('user_error'); ?>
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
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								    <?php
									//echo "<pre>";print_r($usersList);
									$srno=1;
                                    $count = 0;
                                    foreach($usersList as $usersList){								
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
												
												<?=(isset($usersList->status) && $usersList->status==1)?'<i class="fa fa-dot-circle-o text-success"></i> Active':'<i class="fa fa-dot-circle-o text-danger"></i> Inactive';?>
												</a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="<?=base_url('admin/user_status?do=active&user_id='.$usersList->id)?>"><i class="fa fa-dot-circle-o text-success"></i> Active</a>
                                                    <a class="dropdown-item" href="<?=base_url('admin/user_status?do=inactive&user_id='.$usersList->id)?>"><i class="fa fa-dot-circle-o text-danger"></i> Inactive</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
													<a class="dropdown-item" href="<?=base_url('admin/orders_list?do=user_order&user_id='.$usersList->id)?>"><i class="fa fa-shopping-bag m-r-5"></i> View Orders</a>
													 <a class="dropdown-item" href="<?=base_url('admin/product_reviews?do=user_product_review&user_id='.$usersList->id)?>"><i class="fa fa-star m-r-5"></i> View Product Reviews</a>
													<a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#send_sms" onClick="getSendSmsData(<?=$usersList->id?>);"><i class="fa fa-commenting-o m-r-5"></i> Send SMS</a>
													<a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#send_email" onClick="getSendEmailData(<?=$usersList->id?>);"><i class="fa fa-envelope m-r-5"></i> Send Email</a>
													<a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#edit_user" onClick="getEditData(<?=$usersList->id?>);"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <a class="dropdown-item delete-user" href="javascript:void(0);" id="<?=$usersList->id?>" data-toggle="modal" data-target="#delete_user"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
        <div id="add_user" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <h4 class="modal-title">Add User</h4>
                    </div>
                    <div class="modal-body">
						<div class="m-b-30">
                             <?php
								$form_attribute=array(
										'name' => 'add-user',
										'class' => 'form-horizontal',
										'method'=>"post",
										'id' => 'add-user',
										'novalidate' => 'novalidate',
										);
								$hidden = array('action' => 'addUser');
								// Form Open
								echo form_open_multipart('admin/add_user',$form_attribute,$hidden);
							?>
                            <div class="form-group">
                                <label>Name <span class="text-danger">*</span></label>
                                <input class="form-control required" type="text" name="name" id="name">
                            </div>
                            <div class="form-group">
                                <label>Email ID <span class="text-danger">*</span></label>
                                <input class="form-control required email" type="text" name="email_id" id="email_id">
                            </div>
							 <div class="form-group">
                                <label>Password <span class="text-danger">*</span></label>
                                <input class="form-control required" type="password" name="password" id="password" minlength="6">
								<small class="form-text text-muted">Note: Please copy this password for remember when login.</small>
                            </div>
                            <div class="form-group">
                                <label>Mobile Number <span class="text-danger">*</span></label>
                                <div>
                                    <input class="form-control required digits" type="text" name="phone_no" id="phone_no" minlength="10" maxlength="11">
                                </div>
                            </div>
							<div class="form-group">
                                <label>Address <span class="text-danger">*</span></label>
                                <textarea class="form-control required" name="address" id="address"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>State <span class="text-danger">*</span></label>
                                        <select class="select required" name="state_id" id="state_id">
                                            <option value="">Select State</option>
                                            <?php 
											if (!empty($stateData)){
												foreach($stateData as $stateDataW){?>
													<option value="<?=$stateDataW->id?>"><?=$stateDataW->state_name?></option>
											<?php } 
											} ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City <span class="text-danger">*</span></label>
                                        <select class="select required" name="city_id" id="city_id">
										 <option value="">Select City</option>
                                          <!--option load ajax-->
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pin Code <span class="text-danger">*</span></label>
                                        <input class="form-control required digits" type="text" name="pin_code" id="pin_code" minlength="6">
                                    </div>
                                </div>
								 <div class="col-md-6">
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
                            <div class="m-t-20 text-center">
                                <button class="btn btn-primary btn-lg" type="submit">Create User</button>
                            </div>
                            <?php
							// Form Close
							echo form_close(); ?>
							</div>
                        </div>
                    </div>
                </div>
        </div>
        <div id="edit_user" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit User</h4>
                    </div>
                    <div class="modal-body">
                        <div class="m-b-30">
                            <?php
								$form_attribute=array(
										'name' => 'edit-user',
										'class' => 'form-horizontal',
										'method'=>"post",
										'id' => 'edit-user',
										'novalidate' => 'novalidate',
										);
								$hidden = array('action' => 'editUser','id'=>'');
								// Form Open
								echo form_open_multipart('admin/add_user',$form_attribute,$hidden);
								?>
                           <div class="form-group">
                                <label>Name <span class="text-danger">*</span></label>
                                <input class="form-control required" type="text" name="name" id="name">
                            </div>
                            <div class="form-group">
                                <label>Email ID <span class="text-danger">*</span></label>
                                <input class="form-control required email" type="text" name="email_id" id="email_id">
                            </div>
							 <div class="form-group">
                                <label>Password <span class="text-danger"></span></label>
                                <input class="form-control" type="password" name="password" id="password" minlength="6">
								<small class="form-text text-muted">Note: Please copy this password for remember when login.</small>
                            </div>
                            <div class="form-group">
                                <label>Mobile Number <span class="text-danger">*</span></label>
                                <div>
                                    <input class="form-control required digits" type="text" name="phone_no" id="phone_no" minlength="10" maxlength="11">
                                </div>
                            </div>
							<div class="form-group">
                                <label>Address <span class="text-danger">*</span></label>
                                <textarea class="form-control required" name="address" id="address"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>State <span class="text-danger">*</span></label>
                                        <select class="select required" name="state_id" id="state_id">
                                            <option value="">Select State</option>
                                            <?php 
											if (!empty($stateData)){
												foreach($stateData as $stateDataQ){?>
													<option value="<?=$stateDataQ->id?>"><?=$stateDataQ->state_name?></option>
											<?php } 
											} ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>City <span class="text-danger">*</span></label>
                                        <select class="select required" name="city_id" id="city_id">
										 <option value="">Select City</option>
                                          <!--option load ajax-->
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pin Code <span class="text-danger">*</span></label>
                                        <input class="form-control required digits" type="text" name="pin_code" id="pin_code" minlength="6">
                                    </div>
                                </div>
								 <div class="col-md-6">
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
                            <div class="m-t-20 text-center">
                                <button class="btn btn-primary btn-lg">Save Changes</button>
                            </div>
                            <?php
							// Form Close
							echo form_close(); ?>
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
		<div id="send_sms" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <h4 class="modal-title">Send SMS</h4>
                    </div>
                    <div class="modal-body">
						<div class="m-b-30">
                             <?php
								$form_attribute=array(
										'name' => 'send-sms',
										'class' => 'form-horizontal',
										'method'=>"post",
										'id' => 'send-sms',
										'novalidate' => 'novalidate',
										);
								$hidden = array('action' => 'sendSms','user_id'=>'');
								// Form Open
								echo form_open_multipart('admin/send_sms',$form_attribute,$hidden);
							?>
                            <div class="form-group">
                                <label>Mobile No.<span class="text-danger">*</span></label>
                                <input class="form-control required" type="text" name="mobile_no" id="mobile_no" readonly>
                            </div>
							<div class="form-group">
                                <label>Message <span class="text-danger">*</span></label>
                                <textarea class="form-control required editor1" name="message" id="message"></textarea>
								<p id="ckeditor-msg"></p>
                            </div>
                            <div class="m-t-20 text-center">
                                <button class="btn btn-primary btn-lg" type="submit">Send SMS</button>
                            </div>
                            <?php
							// Form Close
							echo form_close(); ?>
							</div>
                        </div>
                    </div>
                </div>
        </div>
		<div id="send_email" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <h4 class="modal-title">Send Email</h4>
                    </div>
                    <div class="modal-body">
						<div class="m-b-30">
                             <?php
								$form_attribute=array(
										'name' => 'send-email',
										'class' => 'form-horizontal',
										'method'=>"post",
										'id' => 'send-email',
										'novalidate' => 'novalidate',
										);
								$hidden = array('action' => 'sendEmail','user_id'=>'');
								// Form Open
								echo form_open_multipart('admin/send_email',$form_attribute,$hidden);
							?>
                            <div class="form-group">
                                <label>To <span class="text-danger">*</span></label>
                                <input class="form-control required" type="text" name="to" id="to" readonly>
                            </div>
                            <div class="form-group">
                                <label>Subject<span class="text-danger">*</span></label>
                                <input class="form-control required" type="text" name="subject" id="subject">
                            </div>
							<div class="form-group">
                                <label>Message <span class="text-danger">*</span></label>
                                <textarea class="form-control required editor2" name="message" id="message"></textarea>
								<p id="ckeditor-msg"></p>
                            </div>
                            <div class="m-t-20 text-center">
                                <button class="btn btn-primary btn-lg" type="submit">Send Email</button>
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
    .create( document.querySelector( '.editor1' ), {
        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
    } )
    .catch( error => {
        console.log( error );
    } );
	
	ClassicEditor
    .create( document.querySelector( '.editor2' ), {
        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
    } )
    .catch( error => {
        console.log( error );
    } );
	$.validator.addMethod("checkEditEmailIdAvailable", 
		 function(value, element) {
				var result = false;
				user_id=$("form[name=edit-user] input[name='id']").val();
				$.ajax({
					type:"POST",
					async: false,
					dataType:"json",
					url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
					data : "emailId="+value+"&request=check-email-name&action=edit-user&user_id="+user_id,
					success: function(data) {
						console.log(data);
						//return false;
						result = (data.dataContent== "0") ? true : false;
					}
				});
				// return true if SHOW NAME is exist in database
				return result; 
			}, 
			"This Email ID is already taken! Try another."
	);

	$.validator.addMethod("checkEmailIdAvailable", 
		function(value, element) {
			var result = false;
			$.ajax({
				type:"POST",
				async: false,
				dataType:"json",
				url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
				data : "emailId="+value+"&request=check-email-name&action=add-user",
				success: function(data) {
					console.log(data);
					//return false;
					result = (data.dataContent== "0") ? true : false;
				}
			});
			// return true if SHOW NAME is exist in database
			return result; 
		}, 
		"This Email ID is already taken! Try another."
	);

	$.validator.addMethod("checkPhoneAvailable", 
		function(value, element) {
			var result = false;
			$.ajax({
				type:"POST",
				async: false,
				dataType:"json",
				url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
				data : "phone_no="+value+"&request=check-phone-no-name&action=add-user",
				success: function(data) {
					console.log(data);
					//return false;
					result = (data.dataContent== "0") ? true : false;
				}
			});
			// return true if SHOW NAME is exist in database
			return result; 
		}, 
		"This Mobile Number is already taken! Try another."
	);

	$.validator.addMethod("checkEditPhoneAvailable", 
		 function(value, element) {
				var result = false;
				user_id=$("form[name=edit-user] input[name='id']").val();
				$.ajax({
					type:"POST",
					async: false,
					dataType:"json",
					url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
					data : "phone_no="+value+"&request=check-phone-no-name&action=edit-user&user_id="+user_id,
					success: function(data) {
						console.log(data);
						//return false;
						result = (data.dataContent== "0") ? true : false;
					}
				});
				// return true if SHOW NAME is exist in database
				return result; 
			}, 
			"This Mobile Number is already taken! Try another."
	);
	/*----------- BEGIN validate CODE -------------------------*/
	$('#add-user').validate({
		ignore: [],
		rules: {
			"email_id": {
				required: true,
				checkEmailIdAvailable: true,
			},
			"phone_no": {
				required: true,
				checkPhoneAvailable: true,
			}
		}
	});
	$('#edit-user').validate({
		ignore: [],
		rules: {
			"email_id": {
				required: true,
				checkEditEmailIdAvailable: true
			},
			"phone_no": {
				required: true,
				checkEditPhoneAvailable: true,
			}
		}
	});

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
					//alert('Please enter a Service Description.');
					 //$("#ckeditor-msg").html("<font color=red>Please enter a Service Description.</font>");
					//ClassicEditor.instances.message.focus();
					return false;
				}else{
					
				// $("#ckeditor-msg").html("");
                 return true;				 
				}
			}, 
			"This field is required."
	);

	$('#send-sms').validate({
		ignore: [],
		rules: {
			"message": {
				required: true,
				checkMessageisSmsEmpty: true
			},
		}
		
	});
	
	//get sms data
	function getSendSmsData(user_id){	
	var validator = $( "form#send-sms" ).validate();
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
						$("form[name=send-sms] input[name='user_id']").val(res.dataContent.id);
						$("form[name=send-sms] #mobile_no").val(res.dataContent.phone_no);				
						console.log(res.dataContent);
					}else if (res.dataContent == ''){
						console.log(res);
					}
				}
			}
		});	
		
	}
	
	$.validator.addMethod("checkMessageisEmailEmpty", 
		 function(value, element) {
				var result = false;	
                //alert(value);				
				var messageLength = value.replace('<p>&nbsp;</p>', '').length;
				//alert(messageLength);
				//return false;
				if( !messageLength ) {
					//alert('Please enter a Service Description.');
					 //$("#ckeditor-msg").html("<font color=red>Please enter a Service Description.</font>");
					//ClassicEditor.instances.message.focus();
					return false;
				}else{
					
				// $("#ckeditor-msg").html("");
                 return true;				 
				}
			}, 
			"This field is required."
	);

	$('#send-email').validate({
		ignore: [],
		rules: {
			"message": {
				required: true,
				checkMessageisEmailEmpty: true
			},
		}
	});
	//get email data
	function getSendEmailData(user_id){	
	var validator = $( "form#send-email" ).validate();
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
						$("form[name=send-email] input[name='user_id']").val(res.dataContent.id);
						$("form[name=send-email] #to").val(res.dataContent.email_id);					
						console.log(res.dataContent);
					}else if (res.dataContent == ''){
						console.log(res);
					}
				}
			}
		});	
		
	}

	//delete user
	$("body").on('click','.delete-user',function(event) {
		event.preventDefault();
		var stringArrayId=$(this).prop("id");
		if(stringArrayId > 0){
			$("form[name=delete-user] input[name='user_id']").val(stringArrayId);
		}
		//alert(stringArrayId);	
	});
	</script>