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
      <span class="base" data-ui-id="page-title-wrapper" >Reset Password</span>
    </h1>
  </div>
  <div class="main-columns layout layout-2-col row">
    <div class="col-main col-xs-12 col-sm-8 col-md-9 col-lg-9 col-sm-push-4 col-md-push-3 col-lg-push-3">
	  <div id="msg"></div>
      <?php if($this->session->flashdata('reset_pass_success')){ ?>
      <div class="message success empty">
        <div><?php echo $this->session->flashdata('reset_pass_success'); ?></div>
      </div>
      <?php }else if($this->session->flashdata('reset_pass_error')){  ?>
      <div class="message error empty">
        <div><?php echo $this->session->flashdata('reset_pass_error'); ?></div>
      </div>
      <?php }?>
      <?php if(isset($display_form) && !empty($display_form)){?>	
      <?php
        $form_attribute=array(
        		'name' => 'resetPassForm',
        		'class' => 'form-horizontal cust_validate',
        		'method'=>"post",
        		'id' => 'form-validate',
        		'novalidate' => 'novalidate',
        		'autocomplete'=>"off",
        		'data-mage-init'=>'{"validation":{}}',
        		);
        $hidden = array('action' => 'resetPassForm','token'=> $token,'type'=> $type);
        // Form Open
        echo form_open('',$form_attribute,$hidden);
        ?>
      <input name="form_key" type="hidden" value="C6GTLfbIPK75zXud" />
      <fieldset class="fieldset password fieldset3 fieldset5">
        <legend class="legend legend3"><span>Reset Password</span></legend>
        <br>
        <div class="field new password required">
          <label class="label" for="password"><span>New Password</span></label>
          <div class="control">
            <input type="password" class="input-text" name="new_password" id="password" data-validate="{required:true, 'validate-password':true}" autocomplete="off">
          </div>
        </div>
        <div class="field confirm password required">
          <label class="label" for="password-confirmation"><span>Confirm New Password</span></label>
          <div class="control">
            <input type="password" class="input-text" name="confirm_password" id="password-confirmation" data-validate="{required:true, equalTo:'#password'}" autocomplete="off">
          </div>
        </div>
      </fieldset>
      <div class="actions-toolbar">
        <div class="primary"><button type="submit" title="Save" class="action save primary submit"><span>Save</span></button></div>
        <div class="secondary"><a class="action back" href="<?=base_url("user/login")?>"><span>Back</span></a></div>
      </div>
      <?php 
        // Form Close
        echo form_close(); ?>
      <?php } ?>
      <script>
        require([
        		'jquery',
        		'mage/mage',
        		'quickview/cloudzoom'
        	], function ($) {
        		
        		 var dataForm = $('#form-validate');
        		 var ignore = null;
        		 
        		 dataForm.mage('validation', {
				    ignore: ignore ? ':hidden:not(' + ignore + ')' : ':hidden',
        			submitHandler: function(form) {
						//alert("asdasdsad");
						//return false;
        				var form=$("#form-validate");
        					$(".submit").addClass("disabled");
        					jQuery.ajax({
        						type: "POST",
        						url: "<?php echo base_url(); ?>" + "ajax/reset_password",
        						dataType: 'json',
        						async: true,
        						cache: false,
        						data: form.serialize(),
        						success: function(res) {
        							allowDismiss='';
        							if (res)
        							{
        								if(res.status==1){
											$("input[name='token']").val("");
											$('#form-validate')[0].reset();
											$(".submit").removeClass("disabled");
											$("#msg").html('<div class="message success empty"><div>'+res.message+'</div></div>');
											setTimeout(function(){ $(location).attr('href',res.redirect_url); }, 3000);
        								//setTimeout(function(){ $(location).attr('href',res.redirect_url); }, 5000);
        								//console.log(res);
        								}else if (res.status==0){
											$(".submit").removeClass("disabled");
											$("#msg").html('<div class="message error empty"><div>'+res.message+'</div></div>');
        								//console.log(res);
        								}
        							}
        						}
        				});
        			}       				  
			  });	
        	});
      </script>
    </div>
  </div>
</div>
</div>