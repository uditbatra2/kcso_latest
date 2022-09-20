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
    <span class="base" data-ui-id="page-title-wrapper" >Edit Account Information</span>    
  </h1>
</div>
<div class="main-columns layout layout-2-col row">
<div class="col-main col-xs-12 col-sm-8 col-md-9 col-lg-9 col-sm-push-4 col-md-push-3 col-lg-push-3">
  <?php if($this->session->flashdata('user_success')){ ?>
  <div class="message success empty close_alert">
    <div><?php echo $this->session->flashdata('user_success'); ?></div>
  </div>
  <?php }else if($this->session->flashdata('user_error')){  ?>
  <div class="message error empty close_alert">
    <div><?php echo $this->session->flashdata('user_error'); ?></div>
  </div>
  <?php } ?>
  <?php
    $form_attribute=array(
    'name' => 'form form-edit-account',
    'class' => 'form form-edit-account',
    'method'=>"post",
    'id' => 'form-validate',
    'novalidate' => 'novalidate',
    'data-hasrequired' => "* Required Fields",
    'autocomplete'=> "off"
    );
    $hidden = array('action' => 'AccountEdit',"do"=> 'AccountEdit','id'=>$userDetails->id);
    //Form Open
    echo form_open('user/account_edit',$form_attribute,$hidden);
    ?>
  <input name="form_key" type="hidden" value="C6GTLfbIPK75zXud" />
  <fieldset class="fieldset info fieldset3 fieldset5">
    <input name="form_key" type="hidden" value="C6GTLfbIPK75zXud" />        
    <legend class="legend legend3"><span>Account Information</span></legend>
    <br>
    <div class="field field-name-firstname required">
      <label class="label" for="firstname">
      <span>Name</span>
      </label>
      <div class="control">
        <input type="text" id="firstname"
          name="firstname"
          value="<?=$userDetails->name;?>"
          title="First Name"
          class="input-text required-entry"   data-validate="{required:true}">
      </div>
    </div>
    <div class="field email required">
      <label class="label" for="email"><span>Email</span></label>
      <div class="control">
        <input type="email" name="email" id="email" value="<?=$userDetails->email_id;?>" title="Email" class="input-text" data-validate="{required:true, 'validate-email':true}">
      </div>
    </div>
    <div class="field field-name-lastname required">
      <label class="label"  for="lastname">
      <span>Phone Number</span>
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
          }" name="phonenumber" value="<?=$userDetails->phone_no;?>" placeholder="" aria-describedby="notice-KWTPJTNT" id="KWTPJTNT" minlength="10" maxlength="11">
      </div>
    </div>
    <div class="field field-name-lastname required">
      <label class="label"  for="lastname">
      <span>Address</span>
      </label>
      <div class="control">
        <input type="text" id="address"
          name="address"
          value="<?=$userDetails->address;?>"
          title="Address"
          class="input-text required-entry"   data-validate="{required:true}">
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
          <option data-title="<?=$cityList->city_name?>" value="<?=$cityList->id?>" <?=(isset($userDetails->city_id) && !empty($userDetails->city_id) && $userDetails->city_id==$cityList->id)? 'selected':'';?>><?=$cityList->city_name?></option>
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
          <option data-title="<?=$statList->state_name?>" value="<?=$statList->id?>" <?=(isset($userDetails->state_id) && !empty($userDetails->state_id) && $userDetails->state_id==$statList->id)? 'selected':'';?>><?=$statList->state_name?></option>
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
          }" name="postcode" value="<?=$userDetails->pin_code;?>" placeholder="" aria-describedby="notice-KWTPJTN" id="KWTPJTN" minlength="6" maxlength="6">
      </div>
    </div>
    <div class="field choice">
      <input type="checkbox" name="change_password" id="change-password" value="1" title="Change Password" class="checkbox" <?=(isset($changepass) && !empty($changepass) && $changepass=='1')?'checked':'';?>/>
      <label class="label" for="change-password"><span>Change Password</span></label>
    </div>
  </fieldset>
  <fieldset class="fieldset password fieldset3 fieldset5">
    <legend class="legend legend3"><span>Change Password</span></legend>
    <br>
    <div class="field password current required">
      <label class="label" for="current-password"><span>Current Password</span></label>
      <div class="control">
        <input type="password" class="input-text" name="current_password" id="current-password" <?php if(isset($changepass) && !empty($changepass) && $changepass=='1'){?>data-validate="{required:true}"<?php } ?> autocomplete="off">
      </div>
    </div>
    <div class="field new password required">
      <label class="label" for="password"><span>New Password</span></label>
      <div class="control">
        <input type="password" class="input-text" name="password" id="password" <?php if(isset($changepass) && !empty($changepass) && $changepass=='1'){?>data-validate="{required:true, 'validate-password':true}" <?php } ?> autocomplete="off">
      </div>
    </div>
    <div class="field confirm password required">
      <label class="label" for="password-confirmation"><span>Confirm New Password</span></label>
      <div class="control">
        <input type="password" class="input-text" name="password_confirmation" id="password-confirmation" <?php if(isset($changepass) && !empty($changepass) && $changepass=='1'){ ?>data-validate="{required:true, equalTo:'#password'}" <?php } ?> autocomplete="off">
      </div>
    </div>
  </fieldset>
  <div class="actions-toolbar">
    <div class="primary">
      <button type="submit" class="action save primary" title="Save"><span>Save</span></button>
    </div>
    <div class="secondary">
      <a class="action back" href="<?=base_url("user/account")?>"><span>Go back</span></a>
    </div>
  </div>
  <?php 
    // Form Close
    echo form_close(); ?>
  <script>
    require([
     "jquery",
     "mage/mage"
    ], function($){
     var dataForm = $('#form-validate');
     var ignore = null;
     
     dataForm.mage('validation', {
    		  ignore: ignore ? ':hidden:not(' + ignore + ')' : ':hidden'
    	  });					  
    $('#change-password').click(function() {
    	//alert('radio selected');
      if ($(this).attr("checked") == "checked") {
    	    $("#current-password").attr("data-validate","{required:true}");
    		$("#password").attr("data-validate","{required:true, 'validate-password':true}");
    	    $("#password-confirmation").attr("data-validate","{required:true, equalTo:'#password'}");
    		//alert("checked");
      }else{
    	 //alert("Unchecked");
         $("#current-password").removeAttr("data-validate");
    	 $("#password").removeAttr("data-validate");
    	 $("#password-confirmation").removeAttr("data-validate");					 
      }
    });
    });
  </script>
</div>