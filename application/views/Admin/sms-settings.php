<?php
$download_url_query='';
if(isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])){
	$download_url_query='&'.$_SERVER['QUERY_STRING'];
}
?>
<div class="page-wrapper">
		<div class="content container-fluid">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <h4 class="page-title"><?=$title?></h4>
						<?php if($this->session->flashdata('site_success')){ ?>
							<div class="alert alert-success">
								<a href="#" class="close" data-dismiss="alert">&times;</a>
								<strong>Success!</strong> <?php echo $this->session->flashdata('site_success'); ?>
							</div>

						<?php }else if($this->session->flashdata('site_error')){  ?>
							<div class="alert alert-danger">
								<a href="#" class="close" data-dismiss="alert">&times;</a>
								<strong>Error!</strong> <?php echo $this->session->flashdata('site_error'); ?>
							</div>
						<?php } ?>
							<?php
							$form_attribute=array(
									'name' => 'sms-settings',
									'class' => 'form-horizontal',
									'method'=>"post",
									'id' => 'sms-settings',
									'novalidate' => 'novalidate',
									);
							$hidden = array('action' => 'smsSettings');
							//Form Open
							echo form_open_multipart('admin/sms_settings',$form_attribute,$hidden);
							?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control required" name="sms_users" id="sms_users" value="<?=getSiteSettingValue(29)?>">
										<small class="form-text text-muted">SMS Username here as provided by SMS API</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control required" name="sms_users_password" id="sms_users_password" value="<?=getSiteSettingValue(30)?>">
										<small class="form-text text-muted">SMS Password here as provided by SMS API</small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Sender ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control required" name="sms_sender_id" id="sms_sender_id" value="<?=getSiteSettingValue(31)?>">
										<small class="form-text text-muted">SMS Sender ID here as provided by SMS API</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-center m-t-20">
                                    <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                                </div>
                            </div>
							<?php 
						// Form Close
						echo form_close(); ?>
                    </div>
                </div>
            </div>
	<script>
	/*----------- BEGIN validate CODE -------------------------*/
	$('#sms-settings').validate({
		ignore: [],	
	});
	</script>