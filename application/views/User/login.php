<div class="customer-account-login page-layout-1column" style="background: #e5e5e5;">
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
        <h1 class="page-title"
          >
          <span class="base" data-ui-id="page-title-wrapper" >Customer Login</span>    
        </h1>
      </div>
      <div class="main-columns layout layout-1-col">
        <div class="col-main">
		 <div class="page messages" id="login-message"></div>
          <div class="login-container">
            <div class="block block-customer-login">
              <div class="block-title">
                <strong id="block-customer-login-heading" role="heading" aria-level="2">Login</strong>
              </div>
              <div class="block-content" aria-labelledby="block-customer-login-heading">
			   <?php
					$form_attribute=array(
							'name' => 'login-form',
							'class' => 'form form-login',
							'method'=>"post",
							'id' => 'login-form',
							'novalidate' => 'novalidate',
							'data-mage-init' => '{"validation":{}}',
							);
					$hidden = array('action' => 'loginForm');
					//Form Open
					echo form_open('',$form_attribute,$hidden);
				?>
				  <input name="form_key" type="hidden" value="tZgWm1UKW50WodYf" />
                  <input name="form_key" type="hidden" value="tZgWm1UKW50WodYf" />            
                  <fieldset class="fieldset login" data-hasrequired="* Required Fields">
                    <div class="field note">If you have an account, sign in with your email address.</div>
                    <div class="field email required">
                      <label class="label" for="email"><span>Email</span></label>
                      <div class="control">
                        <input placeholder="Email" name="username" value=""  autocomplete="off" id="email" type="email" class="input-text" title="Email" data-validate="{required:true, 'validate-email':true}">
                      </div>
                    </div>
                    <div class="field password required">
                      <label for="pass" class="label"><span>Password</span></label>
                      <div class="control">
                        <input placeholder="Password" name="password" type="password"  autocomplete="off" class="input-text" id="pass" title="Password" data-validate="{required:true, 'validate-password':true}">
                      </div>
                    </div>
                    <div class="actions-toolbar">
                      <div class="primary"><button type="submit" class="action login primary" name="send" id="send2"><span>Login</span></button></div>
                      <div class="secondary"><a class="action remind" href="<?=base_url("user/forgotpassword"); ?>"><span>Forgot Your Password?</span></a></div>
                    </div>
                  </fieldset>
                <?php 
                    // Form Close
                    echo form_close(); ?>
              </div>
            </div>
            <div class="block block-new-customer">
              <div class="block-title">
                <strong id="block-new-customer-heading" role="heading" aria-level="2">New Customers</strong>
              </div>
              <div class="block-content" aria-labelledby="block-new-customer-heading">
                <p>Registering for this site allows you to access your order status and history. Just fill in the fields below, and we’ll get a new account set up for you in no time. We will only ask you for information necessary to make the purchase process faster and easier.</p>
                <div class="actions-toolbar">
                  <div class="primary">
                    <a href="<?=base_url("user/registration"); ?>" class="action create primary"><span>Register</span></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
         <script type="text/javascript">
		  require([
			'jquery',
			'mage/mage',
			'Magento_Catalog/product/view/validation',
		  ], function ($) {
			'use strict';
			$('#login-form').mage('validation', {
				submitHandler: function (form) {					
					var form=$("#login-form");
					$("#login-message").empty();
					$("#send2").addClass("disabled");
					$.ajax({
						type: "POST",
						url: "<?php echo base_url(); ?>" + "ajax/userlogin",
						dataType: 'json',
						async: true,
						cache: false,
						data: form.serialize(),
						beforeSend: function(){	   
							$(".loading-mask").removeClass("hide");
						},
						success: function(res) {
							if (res)
							{
								if(res.status==1){
									var msg='<div class="message success empty "><a href="#" class="close" data-dismiss="alert">&times;</a>'+res.message+'</div>';
									$("#login-message").html(msg);
									$('html,body').animate({
									scrollTop: $('#maincontent').offset().top
								},1000);
									setTimeout(function(){ $(location).attr('href',res.redirect_url); }, 3000);															
									return false;
									//console.log(res);
								}else if (res.status==0){
									$("#send2").removeClass("disabled");
									var msg='<div class="message error empty "><a href="#" class="close" data-dismiss="alert">&times;</a>'+res.message+'</div>';
									$("#login-message").html(msg);
									$('html,body').animate({
									scrollTop: $('#maincontent').offset().top
								},1000);
									return false;
									//console.log(res);
								}
							}
						},
					   complete: function(){
							$('.loading-mask').addClass("hide");
						},
					  error: function(xhr, textStatus, error){
						  console.log(xhr.statusText);
						  console.log(textStatus);
						  console.log(error);
					  }
					});
				   //$(form).submit();
				   //return false;
				}
			}); 		
		  });
		</script>
        </div>
      </div>
    </div>
</div>