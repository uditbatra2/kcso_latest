<div class="page-wrapper">
            <div class="content container-fluid">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title"><?=$title?></h4>
                    </div>
                    <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="#" class="btn btn-primary btn-rounded pull-right" data-toggle="modal" data-target="#add_location" onClick="javascript:$('form#add-location')[0].reset();var validator = $( 'form#add-location' ).validate();validator.resetForm();$('form#add-location select').val('').trigger('change');"><i class="fa fa-plus"></i> Add Location</a>
                        <div class="view-icons">
                            <!---<a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
                            <a href="clients-list.html" class="list-view btn btn-link active"><i class="fa fa-bars"></i></a>-->
                        </div>
                    </div>
                </div>
				<?php
			   $form_attribute=array(
						'name' => 'search-location',
						'class' => '',
						'method' =>"get",
						'autocomplete'=>"off",
						'id' => 'search-location',
						'novalidate' => 'novalidate',
						);
				$hidden = array('action' => 'search-location');
				// Form Open
				echo form_open('admin/locations_list',$form_attribute,$hidden);
				?>						
                <div class="row filter-row">
				
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <label class="focus-label">Location Name</label>
                            <input type="text" class="form-control floating" name="serach-query" id="serach-query" value="<?=(isset($searchlocationKeyword) && !empty($searchlocationKeyword))?$searchlocationKeyword:'';?>">
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
                    <div class="col-sm-6 col-md-1">
                        <button type="submit" class="btn btn-success">Search</button>
                    </div>
                    <div class="col-sm-6 col-md-1">
						<button type="button" class="btn btn-danger" onclick="javascript:window.location.href='<?=base_url('admin/locations_list')?>';">Clear</button>
                    </div>					
                </div>
				<?php
					// Form Close
					echo form_close(); ?>
				<?php if($this->session->flashdata('loc_success')){ ?>
					<div class="alert alert-success">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Success!</strong> <?php echo $this->session->flashdata('loc_success'); ?>
					</div>

				<?php }else if($this->session->flashdata('loc_error')){  ?>
					<div class="alert alert-danger">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Error!</strong> <?php echo $this->session->flashdata('loc_error'); ?>
					</div>
				<?php }?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table datatable">
                                <thead>
                                    <tr>
									    <th>Sr.No.</th>
                                        <th>Location Name</th>
										<th>Location Pin Code</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								    <?php
									$srno=1;
                                    $count = 0;
                                    foreach($locationList as $locationList){								
									$count++;
								    $class=($count % 2 == 1) ? " odd" : " even";
									?>
                                    <tr role="row" class="<?=$class?>">
									    <td><?=$srno?></td>
                                        <td><?=$locationList->location_name?></td>
										<td><?=$locationList->location_pin_code?></td>
                                        <td>
                                            <div class="dropdown action-label">
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												
												<?=(isset($locationList->status) && $locationList->status==1)?'<i class="fa fa-dot-circle-o text-success"></i> Active':'<i class="fa fa-dot-circle-o text-danger"></i> Inactive';?>
												</a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="<?=base_url('admin/location_status?do=active&loc_id='.$locationList->id)?>"><i class="fa fa-dot-circle-o text-success"></i> Active</a>
                                                    <a class="dropdown-item" href="<?=base_url('admin/location_status?do=inactive&loc_id='.$locationList->id)?>"><i class="fa fa-dot-circle-o text-danger"></i> Inactive</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#edit_location" onClick="getEditData(<?=$locationList->id?>);"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <a class="dropdown-item delete-location" href="javascript:void(0);" id="<?=$locationList->id?>" data-toggle="modal" data-target="#delete_location"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
        <div id="add_location" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Location</h4>
                    </div>
                    <div class="modal-body">
						<div class="m-b-30">
                             <?php
									$form_attribute=array(
											'name' => 'add-location',
											'class' => 'form-horizontal',
											'method'=>"post",
											'id' => 'add-location',
											'novalidate' => 'novalidate',
											);
									$hidden = array('action' => 'addLocation');
									// Form Open
									echo form_open_multipart('admin/add_location',$form_attribute,$hidden);
								?>
							    <div class="col-sm-7">
									<div class="form-group">
										<label>Location Name <span class="text-danger">*</span></label>
										<input class="form-control required" type="text" name="location_name" id="location_name">
									</div>
								</div>
								<div class="col-sm-7">
									<div class="form-group">
										<label>Location Pin Code <span class="text-danger">*</span></label>
										<input class="form-control required digits" type="text" name="location_pin_code" id="location_pin_code" minlength="6" maxlength="6">
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
										<button class="btn btn-primary btn-lg" type="submit">Create Location</button>
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
        <div id="edit_location" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Location</h4>
                    </div>
                    <div class="modal-body">
                        <div class="m-b-30">
                            <?php
								$form_attribute=array(
										'name' => 'edit-location',
										'class' => 'form-horizontal',
										'method'=>"post",
										'id' => 'edit-location',
										'novalidate' => 'novalidate',
										);
								$hidden = array('action' => 'editLocation','id'=>'');
								// Form Open
								echo form_open_multipart('admin/add_location',$form_attribute,$hidden);
								?>
							    <div class="col-sm-7">
									<div class="form-group">
										<label>Location Name <span class="text-danger">*</span></label>
										<input class="form-control required" type="text" name="location_name" id="location_name">
									</div>
								</div>
								<div class="col-sm-7">
									<div class="form-group">
										<label>Location Pin Code <span class="text-danger">*</span></label>
										<input class="form-control required digits" type="text" name="location_pin_code" id="location_pin_code" minlength="6" maxlength="6">
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
        <div id="delete_location" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content modal-md">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete Location</h4>
                    </div>
                    <div class="modal-body card-box">
					 <?php
						$form_attribute=array(
								'name' => 'delete-location',
								'class' => 'form-horizontal',
								'method'=>"post",
								'id' => 'delete-location',
								'novalidate' => 'novalidate',
								);
						$hidden = array('action' => 'deleteLocation','loc_id'=>'');
						//Form Open
						echo form_open_multipart('admin/delete_location',$form_attribute,$hidden);
						?>
                        <p>Do you want to delete the location now with his related table data? This cannot be undone.</p>
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
$.validator.addMethod("checkEditLocationAvailable", 
	 function(value, element) {
			var result = false;
			loc_id=$("form[name=edit-location] input[name='id']").val();
			$.ajax({
				type:"POST",
				async: false,
				dataType:"json",
				url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
				data : "location_name="+value+"&request=check-location-name&action=edit-location&loc_id="+loc_id,
				success: function(data) {
					console.log(data);
					//return false;
					result = (data.dataContent== "0") ? true : false;
				}
			});
			// return true if SHOW NAME is exist in database
			return result; 
		}, 
		"This Location Name is already taken! Try another."
);

$.validator.addMethod("checkLocationNameAvailable", 
	function(value, element) {
		var result = false;
		$.ajax({
			type:"POST",
			async: false,
			dataType:"json",
			url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
			data : "location_name="+value+"&request=check-location-name&action=add-location",
			success: function(data) {
				console.log(data);
				//return false;
				result = (data.dataContent== "0") ? true : false;
			}
		});
		// return true if SHOW NAME is exist in database
		return result; 
	}, 
	"This Location Name is already taken! Try another."
);
/*----------- BEGIN validate CODE -------------------------*/
$('#add-location').validate({
	ignore: [],
	rules: {
		"location_name": {
			required: true,
			checkLocationNameAvailable: true
		}
	}
});
$('#edit-location').validate({
	ignore: [],
	rules: {
		"location_name": {
			required: true,
			checkEditLocationAvailable: true
		}
	}
});

function getEditData(loc_id){
	var validator = $( "form#edit-location" ).validate();
	validator.resetForm();
	var dataString="request=edit_location_data&loc_id="+loc_id;
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
					$("form[name=edit-location] input[name='id']").val(res.dataContent.id);
					$("form[name=edit-location] #location_name").val(res.dataContent.location_name);
					$("form[name=edit-location] #location_pin_code").val(res.dataContent.location_pin_code);
					$('form[name=edit-location] #status').val(res.dataContent.status).trigger('change');			
					console.log(res.dataContent);
				}else if (res.dataContent == ''){
					console.log(res.dataContent);
				}
			}
		}
	});
}
//delete category
$("body").on('click','.delete-location',function(event) {
	event.preventDefault();
	var stringArrayId=$(this).prop("id");
	if(stringArrayId > 0){
		$("form[name=delete-location] input[name='loc_id']").val(stringArrayId);
	}
	//alert(stringArrayId);	
});
</script>