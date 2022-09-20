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
						<?php }?>
							<?php
							$form_attribute=array(
									'name' => 'email-settings',
									'class' => 'form-horizontal',
									'method'=>"post",
									'id' => 'email-settings',
									'novalidate' => 'novalidate',
									);
							$hidden = array('action' => 'emailSettings');
							//Form Open
							echo form_open_multipart('admin/email_settings',$form_attribute,$hidden);
							?>
                            <div class="form-group">
							     <?php $sitePhpMailN=getSiteSettingValue(36);
									?>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="mailoption" id="phpmail" value="PHPMAIL" <?=(isset($sitePhpMailN) && $sitePhpMailN=='PHPMAIL')?'checked="checked"':'';?>>
									<label class="form-check-label" for="phpmail">PHP Mail</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="mailoption" id="smtpmail" value="SMTPMAIL" <?=(isset($sitePhpMailN) && $sitePhpMailN=='SMTPMAIL')?'checked="checked"':'';?>>
									<label class="form-check-label" for="smtpmail">SMTP</label>
								</div>
								<small class="form-text text-muted">Choose Mailing System</small>
							</div>
							<h4 class="page-title">PHP Email Settings</h4>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Email From Address <span class="text-danger">*</span></label>
                                        <input class="form-control required" type="email" name="Email_From_Address" id="Email_From_Address" value="<?=getSiteSettingValue(37)?>">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Emails From Name <span class="text-danger">*</span></label>
                                        <input class="form-control required" type="text" name="Emails_From_Name" id="Emails_From_Name" value="<?=getSiteSettingValue(38)?>">
                                    </div>
                                </div>
                            </div>
							<div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Order Email Address <span class="text-danger">*</span></label>
                                        <input class="form-control required" type="text" name="Order_Email_Address" id="Order_Email_Address" value="<?=getSiteSettingValue(39)?>">
										<small class="form-text text-muted"> To send to multiple e-mail addresses, separate email addresses with a comma </small>
                                    </div>
                                </div>
                            </div>
							<div id="SMTP_SETTING_DIV">
							<h4 class="page-title m-t-30">SMTP Email Settings</h4>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>SMTP HOST <span class="text-danger">*</span></label>
                                        <input class="form-control required" type="text" name="SMTP_HOST" id="SMTP_HOST" value="<?=getSiteSettingValue(40)?>">
										<small class="form-text text-muted">SMTP HOST as provided by CPANEL</small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>SMTP USER <span class="text-danger">*</span></label>
                                        <input class="form-control required" type="text" name="SMTP_USER" id="SMTP_USER" value="<?=getSiteSettingValue(41)?>">
										<small class="form-text text-muted">SMTP USER as provided by CPANEL</small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>SMTP PASSWORD <span class="text-danger">*</span></label>
                                        <input class="form-control required" type="password" name="SMTP_PASSWORD" id="SMTP_PASSWORD" value="<?=getSiteSettingValue(42)?>">
										<small class="form-text text-muted">SMTP PASSWORD as provided by CPANEL</small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>SMTP PORT <span class="text-danger">*</span></label>
                                        <input class="form-control required" type="text" name="SMTP_PORT" id="SMTP_PORT" value="<?=getSiteSettingValue(43)?>">
										<small class="form-text text-muted">SMTP PORT as provided by CPANEL</small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
								   <?php $siteSMTPN=getSiteSettingValue(44);
									?>
                                    <div class="form-group">
                                        <label>SMTP Security <span class="text-danger">*</span></label>
                                        <select class="select required" name="SMTP_Security" id="SMTP_Security">
										    <option value="">Select SMTP Security</option>
                                            <option value="None" <?=(isset($siteSMTPN) && $siteSMTPN=='None')?'selected':'';?>>None</option>
                                            <option value="SSL" <?=(isset($siteSMTPN) && $siteSMTPN=='SSL')?'selected':'';?>>SSL</option>
                                            <option value="TLS" <?=(isset($siteSMTPN) && $siteSMTPN=='TLS')?'selected':'';?>>TLS</option>
                                        </select>
										<small class="form-text text-muted">SMTP Security as provided by CPANEL</small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>SMTP Authentication Domain <span class="text-danger">*</span></label>
                                        <input class="form-control required" type="text" name="SMTP_Authentication" id="SMTP Authentication" value="<?=getSiteSettingValue(45)?>">
										<small class="form-text text-muted">SMTP Authentication Domain as provided by CPANEL</small>
                                    </div>
                                </div>
                            </div>
							</div>
                            <?php  
                                if (getUserCan('general_module', 'access_write')) {
                                ?>
                            <div class="col-sm-12 m-t-20 text-center">
                                <button type="submit" class="btn btn-primary btn-lg">Save &amp; update</button>
                            </div>
                            <?php } ?>
							<?php 
						// Form Close
						echo form_close(); ?>
                    </div>
                </div>
            </div>
	<script>
	/*----------- BEGIN validate CODE -------------------------*/
	jQuery.validator.addMethod("multiemail", function (value, element) {
    if (this.optional(element)) {
        return true;
    }

    var emails = value.split(','),
        valid = true;

    for (var i = 0, limit = emails.length; i < limit; i++) {
        value = jQuery.trim(emails[i]);
        valid = valid && jQuery.validator.methods.email.call(this, value, element);
    }

    return valid;
}, "Invalid email format: please use a comma to separate multiple email addresses.");
	$('#email-settings').validate({
		ignore: [],
		rules: {
        "Order_Email_Address": {
            required: true,
			multiemail: true
        }
    }
	});
	$("#SMTP_SETTING_DIV").hide("fast");
	$('input[name=mailoption]').on('change', function() {
		checkedValue=this.checked ? this.value : 0;
        //$('#SITE_EMAIL_NOTIFICATION').val(checkedValue);		
		//alert(checkedValue);
		if(checkedValue == 'PHPMAIL'){			
			$("#SMTP_SETTING_DIV").hide("fast");
			$("#SMTP_SETTING_DIV input,#SMTP_SETTING_DIV select").removeClass("required");
		}else if(checkedValue == 'SMTPMAIL'){
            $("#SMTP_SETTING_DIV").show("fast");			
			$("#SMTP_SETTING_DIV input,#SMTP_SETTING_DIV select").addClass("required");
		}
	});
	
	$('input[name=mailoption]').trigger("change");
	</script>