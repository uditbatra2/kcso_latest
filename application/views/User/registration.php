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
        <h1 class="page-title" style="text-align: center;">
          <span class="base" data-ui-id="page-title-wrapper" >Create New Customer Account</span>    
        </h1>
      </div>
      <div class="main-columns layout layout-1-col">
        <div class="col-main">
          <input name="form_key" type="hidden" value="tZgWm1UKW50WodYf" />
          <div id="authenticationPopup" data-bind="scope:'authenticationPopup'" style="display: none;">            
          </div>
           <?php
				$form_attribute=array(
						'name' => 'register-form',
						'class' => 'form create account form-create-account',
						'method'=>"post",
						'id' => 'form-validate',
						'novalidate' => 'novalidate',
						'autocomplete'=>"off"
						);
				$hidden = array('action' => 'registerForm',"do"=>$do);
				//Form Open
				echo form_open_multipart('user/registration',$form_attribute,$hidden);
			?>
            <fieldset class="fieldset create info">
              <legend class="legend"><span>Personal Information</span></legend>
              <br>
			   <div class="page messages" id="reg-message"></div>
              <input type="hidden" name="success_url" value="users/accounts">
              <input type="hidden" name="error_url" value="users/registrations">
              <div class="field field-name-firstname required">
                <label class="label" for="firstname">
                <span>Name</span>
                </label>
                <div class="control">
                  <input type="text" id="firstname"
                    name="firstname"
                    value=""
                    title="First Name"
                    class="input-text required-entry"   data-validate="{required:true}">
                </div>
              </div>
              <div class="field required">
                <label for="email_address" class="label"><span>Email</span></label>
                <div class="control">
                  <input  type="email" name="email" id="email_address" value="" title="Email" class="input-text" data-validate="{required:true, 'validate-email':true}">
				  <div class="page messages" id="email-message"></div>
                </div>
              </div>
              <div class="field field-name-lastname required">
                <label class="label"  for="mobile_no">
                <span>Mobile No</span>
                </label>
                <div class="control">
                  <input type="text" id="mobile_no"
                    name="mobile_no"
                    value=""
                    title="Mobile No"
                    class="input-text required-entry digits" data-validate="{required:true}">
					<div class="page messages" id="phone-message"></div>
                </div>
              </div>
              <div class="field field-name-lastname required">
                <label class="label"  for="address">
                <span>Address</span>
                </label>
                <div class="control">
                  <textarea type="text" id="address"
                    name="address"
                    value=""
                    title="Address"
                    class="input-text required-entry" data-validate="{required:true}"></textarea>
                </div>
              </div>
			  <div class="field field-name-lastname required">
			  <label class="label"  for="lastname">
			  <span>City</span>
			  </label>
			  <div class="control">
				<select class="select required" data-bind="
				  attr: {
				  name: inputName,
				  id: uid,
				  disabled: disabled,
				  'aria-describedby': noticeId,
				  placeholder: placeholder
				  },
				  hasFocus: focused,
				  optgroup: options,
				  value: value,
				  optionsCaption: caption,
				  optionsValue: 'value',
				  optionsText: 'label',
				  optionsAfterRender: function(option, item) {
				  if (item &amp;&amp; item.disabled) {
				  ko.applyBindingsToNode(option, {attr: {disabled: true}}, item);
				  }
				  }" name="city" id="X3ILJSA" aria-describedby="notice-X3ILJSA" placeholder="">
				  <option value="">Please select a city.</option>
				  <?php foreach($cityList as $cityList){?>
				  <option data-title="<?=$cityList->city_name?>" value="<?=$cityList->id?>"><?=$cityList->city_name?></option>
				  <?php } ?>
				</select>
			  </div>
			</div>
			<div class="field field-name-lastname required">
			  <label class="label"  for="lastname">
			  <span>State</span>
			  </label>
			  <div class="control">
				<select class="select required" data-bind="
				  attr: {
				  name: inputName,
				  id: uid,
				  disabled: disabled,
				  'aria-describedby': noticeId,
				  placeholder: placeholder
				  },
				  hasFocus: focused,
				  optgroup: options,
				  value: value,
				  optionsCaption: caption,
				  optionsValue: 'value',
				  optionsText: 'label',
				  optionsAfterRender: function(option, item) {
				  if (item &amp;&amp; item.disabled) {
				  ko.applyBindingsToNode(option, {attr: {disabled: true}}, item);
				  }
				  }" name="region_id" id="H96X1OG" aria-describedby="notice-H96X1OG" placeholder="">
				  <option value="">Please select a region, state or province.</option>
				  <?php foreach($statList as $statList){?>
				  <option data-title="<?=$statList->state_name?>" value="<?=$statList->id?>"><?=$statList->state_name?></option>
				  <?php } ?>
				</select>
			  </div>
			</div>
			<div class="field field-name-lastname required">
			  <label class="label"  for="lastname">
			  <span>Pin Code</span>
			  </label>
			  <div class="control">
				<input class="input-text required digits" type="text" data-bind="
				  value: value,
				  valueUpdate: 'keyup',
				  hasFocus: focused,
				  attr: {
				  name: inputName,
				  placeholder: placeholder,
				  'aria-describedby': noticeId,
				  id: uid,
				  disabled: disabled
				  }" name="postcode" value="" placeholder="" aria-describedby="notice-KWTPJTN" id="KWTPJTN" minlength="6" maxlength="6">
			  </div>
			</div>
              <div class="field choice newsletter">
                <input type="checkbox" name="is_subscribed" title="Sign Up for Newsletter" value="1" id="is_subscribed" class="checkbox">
                <label for="is_subscribed" class="label"><span>Sign Up for Newsletter</span></label>
              </div>
            </fieldset>
            <fieldset class="fieldset create account" data-hasrequired="* Required Fields">
              <legend class="legend"><span>Sign-in Information</span></legend>
              <br>
              <div class="field required">
                <label for="password" class="label"><span>Password</span></label>
                <div class="control">
                  <input type="password" name="password" id="password" title="Password" class="input-text" data-validate="{required:true, 'validate-password':true}" autocomplete="off">
                </div>
              </div>
              <div class="field confirmation required">
                <label for="password-confirmation" class="label"><span>Confirm Password</span></label>
                <div class="control">
                  <input type="password" name="password_confirmation" title="Confirm Password" id="password-confirmation" class="input-text" data-validate="{required:true, equalTo:'#password'}" autocomplete="off">
                </div>
              </div>
            </fieldset>
            <div class="actions-toolbar">
              <div class="primary">
                <button type="submit" class="action submit primary" title="Create an Account"><span>Create an Account</span></button>
              </div>
              <div class="secondary">
                <a class="action back" href="<?=base_url("user/login"); ?>"><span>Back</span></a>
              </div>
            </div>
           <?php 
			// Form Close
			echo form_close(); ?>
          <script>		
            require([
                'jquery',
                'mage/mage'
            ], function($){	
				var dataForm = $('#form-validate');
				var ignore = null;				
				dataForm.mage('validation', {
						ignore: ignore ? ':hidden:not(' + ignore + ')' : ':hidden',						
						submitHandler: function (form) {					
							var form=$("#form-validate");
							$("#reg-message").empty();
							$("#email-message").empty();
							$("#phone-message").empty();
							$(".submit").addClass("disabled");
							$.ajax({
								type: "POST",
								url: BASE_URL + "ajax/registration",
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
											$("#reg-message").html(msg);
											$('html,body').animate({
													scrollTop: $('#maincontent').offset().top
													},1000);
											setTimeout(function(){ $(location).attr('href',res.redirect_url); }, 3000);															
											return false;
											//console.log(res);
										}else if (res.status==0){
											$(".submit").removeClass("disabled");
											if(res.status_msg == 1){												
												var divId=$("#reg-message");
											}else if(res.status_msg == 2){
												var divId=$("#email-message");
											}else if(res.status_msg == 3){
												var divId=$("#phone-message");
											}											
											var msg='<div class="message error empty "><a href="#" class="close" data-dismiss="alert">&times;</a>'+res.message+'</div>';
											$(divId).html(msg);
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
					}).find('input:text').attr('autocomplete', 'off');
            });
          </script>
        </div>
      </div>
</div>