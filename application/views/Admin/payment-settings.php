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
									'name' => 'payment-settings',
									'class' => 'form-horizontal',
									'method'=>"post",
									'id' => 'payment-settings',
									'novalidate' => 'novalidate',
									);
							$hidden = array('action' => 'paymentSettings');
							//Form Open
							echo form_open_multipart('admin/payment_settings',$form_attribute,$hidden);
							?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Merchant ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control required digits" name="merchant_id" id="merchant_id" value="<?=getSiteSettingValue(32)?>">
										<small class="form-text text-muted">Merchant ID as provided by Payu</small>
                                    </div>
                                </div>
								<div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Merchant Key <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control required" name="merchant_key" id="merchant_key" value="<?=getSiteSettingValue(33)?>">
										<small class="form-text text-muted">Merchant key here as provided by Payu</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Merchant Salt <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control required" name="merchant_salt" id="merchant_salt" value="<?=getSiteSettingValue(34)?>">
										<small class="form-text text-muted">Merchant Salt as provided by Payu</small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                        <label>Mode <span class="text-danger">*</span></label>
                                         <div class="form-group">
											<select class="select required" name="payu_base_url" id="payu_base_url">
											    <?php
												 $PbaseUrl=getSiteSettingValue(35);
												?>
												<option value="">Select Mode</option>
												<option value="https://test.payu.in" <?=(isset($PbaseUrl) && !empty($PbaseUrl) && $PbaseUrl=='https://test.payu.in')? 'selected':'';?>>Test</option>
												<option value="https://secure.payu.in" <?=(isset($PbaseUrl) && $PbaseUrl != '' && $PbaseUrl=='https://secure.payu.in')? 'selected':'';?>>Live</option>
											</select>
										</div>										
										<small class="form-text text-muted">End point - change to https://secure.payu.in for LIVE mode</small>                                   
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
	$('#payment-settings').validate({
		ignore: [],		
	});
	</script>