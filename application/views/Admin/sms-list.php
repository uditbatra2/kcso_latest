<?php
$download_url_query='';
if(isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])){
	$download_url_query='&'.$_SERVER['QUERY_STRING'];
}
?>
<div class="page-wrapper">
		<div class="content container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="page-title"><?=$title?></h4>
                    </div>
                </div>
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
                    <div class="col-sm-12">
                        <div class="card-box">
                            <?php
								$form_attribute=array(
										'name' => 'sms-user',
										'class' => 'form-horizontal',
										'method'=>"post",
										'id' => 'sms-user',
										'novalidate' => 'novalidate',
										);
								$hidden = array('action' => 'smsUser');
								//Form Open
								echo form_open_multipart('admin/sms_list',$form_attribute,$hidden);
								?>
                                <div class="form-group">
									<label>Mobile Number <span class="text-danger">*</span></label>
									<select class="select required" name="to[]" id="to" multiple>
										<?php foreach($userListData as $userListData){?>
										<option value="<?=$userListData->phone_no?>"><?=$userListData->phone_no?> (<?=$userListData->name?>)</option>
										<?php } ?>
									</select>
								</div>
                                <div class="form-group">
								    <label>Message <span class="text-danger">*</span></label>
                                    <textarea rows="4" cols="5" class="form-control summernote required" placeholder="Enter your message here" name="message" id="message"></textarea>
                                </div>
                                <div class="form-group m-b-0">
                                    <div class="text-center">
                                        <button class="btn btn-primary" type="submit"><span>Send</span> <i class="fa fa-send m-l-5"></i></button>
                                        <!--<button class="btn btn-success m-l-5" type="button"><span>Draft</span> <i class="fa fa-floppy-o m-l-5"></i></button>
                                        <button class="btn btn-success m-l-5" type="button"><span>Delete</span> <i class="fa fa-trash-o m-l-5"></i></button>-->
                                    </div>
                                </div>
								<?php 
							// Form Close
							echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
	<script>
	/*----------- BEGIN validate CODE -------------------------*/
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

	$('#sms-user').validate({
		ignore: [],
		rules: {
			"message": {
				required: true,
				checkMessageisSmsEmpty: true
			},
		}
		
	});
	</script>