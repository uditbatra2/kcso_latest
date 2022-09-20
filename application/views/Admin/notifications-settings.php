<?php
$download_url_query='';
if(isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])){
	$download_url_query='&'.$_SERVER['QUERY_STRING'];
}
?>
<div class="page-wrapper">
	<div class="content container-fluid">
			<div class="row">
				<div class="col-md-8 offset-md-2">
					<h4 class="page-title">Notifications Settings</h4>
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
					 <?php
						$form_attribute=array(
								'name' => 'notifications-settings',
								'class' => 'form-horizontal',
								'method'=>"post",
								'id' => 'notifications-settings',
								'novalidate' => 'novalidate',
								);
						$hidden = array('action' => 'notificationsSettings');
						// Form Open
						echo form_open_multipart('admin/notifications',$form_attribute,$hidden);
					?>
					<div>
						<ul class="list-group notification-list">
							<li class="list-group-item">
								Email Notifications
								<div class="material-switch pull-right">
								    <?php $siteEmailN=getSiteSettingValue(25);
									   $checkedE= (isset($siteEmailN) && $siteEmailN==1)?'checked="checked"':'';
									?>
									<input id="email_module" type="checkbox" <?=$checkedE?> value="1">
									<label for="email_module" class="badge-primary"></label>
								</div>
							</li>
							<!--<li class="list-group-item">
								SMS Notifications
								<div class="material-switch pull-right">
								    <?php $siteSmsN=getSiteSettingValue(26);
									$checkedN= (isset($siteSmsN) && $siteSmsN==1)?'checked="checked"':'';
									?>
									<input id="sms_module" type="checkbox" <?=$checkedN?> value="1">
									<label for="sms_module" class="badge-primary"></label>
								</div>
							</li>-->
							<input id="SITE_EMAIL_NOTIFICATION" name="SITE_EMAIL_NOTIFICATION" type="hidden" value="<?=$siteEmailN?>">
							<input id="SITE_SMS_NOTIFICATION" name="SITE_SMS_NOTIFICATION" type="hidden" value="<?=$siteSmsN?>">
						</ul>
					</div>
					 <div class="m-t-20 text-center">
						<button class="btn btn-primary btn-lg" type="submit">Save Changes</button>
					</div>
					 <?php
					// Form Close
					echo form_close(); ?>
				</div>
			</div>
		</div>
	<script>
	/*----------- BEGIN validate CODE -------------------------*/
	$('#change-password').validate({
		ignore: [],
		rules: {
        "new_password": {
            required: true,
            minlength: 6
        },
        "confirm_password": {
            required: true,
            minlength: 6,
            equalTo: "#new_password"
        }
    }		
	});
	
	$('#email_module').on('change', function() {
		checkedValue=this.checked ? this.value : 0;
        $('#SITE_EMAIL_NOTIFICATION').val(checkedValue);		
		//alert(checkedValue);
	});
	
	$('#sms_module').on('change', function() {
		checkedValue=this.checked ? this.value : 0;		
		//alert(checkedValue);
		$('#SITE_SMS_NOTIFICATION').val(checkedValue);
	});
	</script>