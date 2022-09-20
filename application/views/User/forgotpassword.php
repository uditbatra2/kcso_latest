<noscript>
  <div class="message global noscript">
    <div class="content">
      <p>
        <strong>JavaScript seems to be disabled in your browser.</strong>
        <span>For the best experience on our site, be sure to turn on Javascript in your browser.</span>
      </p>
    </div>
  </div>
</noscript>
<div id="maincontent" class="page-main container" style="display:block;">
 <a id="contentarea" tabindex="-1"></a>
 <div class="page-title-wrapper">
	<h1 class="page-title">
	   <span class="base" data-ui-id="page-title-wrapper" >Forgot Your Password</span>
	</h1>
 </div>
 <div class="main-columns layout layout-2-col row">
	<div class="col-main col-xs-12">
	  <?php
		$form_attribute=array(
				'name' => 'forgotpassword-form',
				'class' => 'form password forget',
				'method'=>"post",
				'id' => 'form-validate',
				'novalidate' => 'novalidate',
				'autocomplete'=>"off",
				'data-mage-init'=>'{"validation":{}}',
				);
		$hidden = array('action' => 'forgotpasswordForm');
		//Form Open
		echo form_open_multipart('user/forgotpassword',$form_attribute,$hidden);
	?>
	<?php if($this->session->flashdata('user_success')){ ?>
	  <div class="message success empty close_alert">
		<div><?php echo $this->session->flashdata('user_success'); ?></div>
	  </div>
	  <?php }else if($this->session->flashdata('user_error')){  ?>
	  <div class="message error empty close_alert">
		<div><?php echo $this->session->flashdata('user_error'); ?></div>
	  </div>
	  <?php }?>
    <fieldset class="fieldset" data-hasrequired="* Required Fields">
        <div class="field note">Please enter your email address below to receive a password reset link.</div>
        <div class="field email required">
            <div class="control">
                <input placeholder="Email" type="email" name="email" alt="email" id="email_address" class="input-text" value="" data-validate="{required:true, 'validate-email':true}">
            </div>
        </div>
            </fieldset>
    <div class="actions-toolbar">
        <div class="primary">
            <button type="submit" class="action submit primary"><span>Submit</span></button>
        </div>
        <div class="secondary">
            <a class="action back" href="<?=base_url("user/login");?>"><span>Go back</span></a>
        </div>
    </div>
<?php 
// Form Close
echo form_close(); 

?>		
<script>
require([
		'jquery',
		'mage/mage',
		'quickview/cloudzoom'
	], function ($) {
		
		var dataForm = $('#form-validate');
      var ignore = null;
      
      dataForm.mage('validation', {
    			  ignore: ignore ? ':hidden:not(' + ignore + ')' : ':hidden'
    		  });
	});
</script>
	
</div>
</div>
</div>