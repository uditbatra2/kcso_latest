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
									'name' => 'theme-settings',
									'class' => 'form-horizontal',
									'method'=>"post",
									'id' => 'theme-settings',
									'novalidate' => 'novalidate',
									);
							$hidden = array('action' => 'themeSettings');
							//Form Open
							echo form_open_multipart('admin/theme_settings',$form_attribute,$hidden);
							?>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Site Name<span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="website_name" id="website_name" class="form-control required" value="<?=getSiteSettingValue(1)?>" type="text">
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-lg-3 col-form-label">Site URL<span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="site_url" id="site_url" class="form-control required url" value="<?=getSiteSettingValue(2)?>" type="text">
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-lg-3 col-form-label">Site Meta Description<span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <textarea name="site_description" id="site_description" class="form-control required"><?=getSiteSettingValue(3)?></textarea>
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-lg-3 col-form-label">Site Meta Keywords<span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="site_keywords" id="site_keywords" class="form-control required" value="<?=getSiteSettingValue(4)?>" type="text">
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-lg-3 col-form-label">Site Owner Name<span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input name="site_owner_name" id="site_owner_name" class="form-control required" value="<?=getSiteSettingValue(5)?>" type="text">
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-lg-3 col-form-label">Site Address<span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <textarea name="site_address" id="site_address" class="form-control required"><?=getSiteSettingValue(7)?></textarea>
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-lg-3 col-form-label">Site Phone Number<span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                   <input name="site_phone_no" id="site_phone_no" class="form-control required" value="<?=getSiteSettingValue(13)?>" type="text">
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-lg-3 col-form-label">Site Email Address<span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                   <input name="site_email_address" id="site_email_address" class="form-control required email" value="<?=getSiteSettingValue(28)?>" type="text">
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-lg-3 col-form-label">Facebook Url<span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                   <input name="facebook_url" id="facebook_url" class="form-control required" value="<?=getSiteSettingValue(46)?>" type="text">
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-lg-3 col-form-label">Twitter Url<span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                   <input name="twitter_url" id="twitter_url" class="form-control required" value="<?=getSiteSettingValue(47)?>" type="text">
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-lg-3 col-form-label">Instagram Url<span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                   <input name="instagram_url" id="instagram_url" class="form-control required" value="<?=getSiteSettingValue(48)?>" type="text">
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-lg-3 col-form-label">Pinterest Url<span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                   <input name="linkedin_url" id="linkedin_url" class="form-control required" value="<?=getSiteSettingValue(49)?>" type="text">
                                </div>
                            </div>
							<div class="form-group row">
                                <label class="col-lg-3 col-form-label">Google Analytics Code</label>
                                <div class="col-lg-9">
                                    <textarea rows="10" name="google_analytics_code" id="google_analytics_code" class="form-control requireds"><?=getSiteSettingValue(8)?></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Light Logo<span class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                    <input class="form-control requireds" type="file" name="site_logo" id="site_logo">
                                    <span class="form-text text-muted">Recommended image size is 201px x 30px</span>
                                </div>
								<label class="col-lg-3 col-form-label"></label>
                                <div class="col-lg-6">								  
								    <?php
									$imageValue=getSiteSettingValue(15);
									$sitel_file= '../uploads/no-image100x100.jpg';
									if(isset($imageValue) && !empty($imageValue)){
										$sitelfilename = 'uploads/site_images/'.$imageValue;
										if (file_exists($sitelfilename) && !empty($imageValue))
										{
											$sitel_file='../uploads/site_images/medium/'.$imageValue;														
										}
									}									
									?>
                                    <div class="img-thumbnail pull-rights"><img src="<?=$sitel_file?>" alt="" width="201px" height="40px"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Favicon<span class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                    <input class="form-control required" type="file" name="favicon" id="favicon">
                                    <span class="form-text text-muted">Recommended image size is 32px x 32px</span>
                                </div>
                                <div class="col-lg-2">
								     <?php
										$imageValue=getSiteSettingValue(27);
										$favicon_file= '../uploads/no-image100x100.jpg';
										if(isset($imageValue) && !empty($imageValue)){
											$faviconfilename = 'uploads/site_images/'.$imageValue;
											if (file_exists($faviconfilename) && !empty($imageValue))
											{
												$favicon_file='../uploads/site_images/'.$imageValue;
											}
										}
									?>
                                    <div class="settings-image img-thumbnail pull-right"><img src="<?=$favicon_file?>" class="img-fluid" width="16" height="16" alt=""></div>
                                </div>
                            </div>
                            <?php  
                                if (getUserCan('general_module', 'access_write')) {
                                ?>
                            <div class="m-t-20 text-center">
                                <button class="btn btn-primary btn-lg">Save Changes</button>
                            </div>
                            <?php } ?>
							<?php 
						// Form Close
						echo form_close(); ?>
                    </div>
                </div>
            </div>
	<script>
	$.validator.addMethod("extension", function (value, element, param) {
	param = typeof param === "string" ? param.replace(/,/g, '|') : "svg|png|jpe?g|gif|ico";
	return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
}, jQuery.format("Please enter a value with a valid extensions."));
	/*----------- BEGIN validate CODE -------------------------*/
	$('#theme-settings').validate({
		ignore: [],
		rules: {
        "site_logo": {
          required: false,
		  extension: "gif|svg|jpg|png"
        },
        "favicon": {
           required: false,
		   extension: "gif|svg|jpg|png|ico"
        }
    }		
	});
	</script>