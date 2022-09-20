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
    <span class="base" data-ui-id="page-title-wrapper" >Address Book</span>    
  </h1>
</div>
<div class="page messages">
  <div data-placeholder="messages"></div>
  <div data-bind="scope: 'messages'">
    <div data-bind="foreach: { data: cookieMessages, as: 'message' }" class="messages">
      <div data-bind="attr: {
        class: 'message-' + message.type + ' ' + message.type + ' message',
        'data-ui-id': 'message-' + message.type
        }">
        <div data-bind="html: message.text"></div>
      </div>
    </div>
    <div data-bind="foreach: { data: messages().messages, as: 'message' }" class="messages">
      <div data-bind="attr: {
        class: 'message-' + message.type + ' ' + message.type + ' message',
        'data-ui-id': 'message-' + message.type
        }">
        <div data-bind="html: message.text"></div>
      </div>
    </div>
  </div>
  <script type="text/x-magento-init">
    {
    	"*": {
    		"Magento_Ui/js/core/app": {
    			"components": {
    					"messages": {
    						"component": "Magento_Theme/js/view/messages"
    					}
    				}
    			}
    		}
    }
     
  </script>
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
  <?php }?>
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
    		  
    $(".delete").click(function(){
    	var addressId = $(this).attr("data-address");
    	//alert(addressId);
    	//return false;
    	window.location.href="<?=base_url("user/delete_shipping_address/?address_id=")?>"+addressId;
    });
    });
  </script>
  <?php if(isset($do) && empty($do)){?>
  <input name="form_key" type="hidden" value="wvtlA9Fxfzxvj9eq" />
  <div class="block block-addresses-default">
    <div class="block-title"><strong>Default Addresses</strong></div>
    <div class="block-content">
      <div class="box box-address-shipping">
        <strong class="box-title">
        <span>Default Shipping Address</span>
        </strong>
		<?php if(!empty($userDefaultShippingAddressDetails) && count($userDefaultShippingAddressDetails) > 0){?>
        <div class="box-content">
          <address>
            <?=$userDefaultShippingAddressDetails->full_name?><br>
            <?php
              if(isset($userDefaultShippingAddressDetails->company_name) && !empty($userDefaultShippingAddressDetails->company_name)){?>
            <?=$userDefaultShippingAddressDetails->company_name?><br>
            <?php } ?>
            <?=$userDefaultShippingAddressDetails->a_address_one?><br>
            <?=$userDefaultShippingAddressDetails->city_name?>,  <?=$userDefaultShippingAddressDetails->state_name?>, <?=$userDefaultShippingAddressDetails->a_post_code?><br>
            <?=$userDefaultShippingAddressDetails->country_name?><br>
            T: <?=$userDefaultShippingAddressDetails->a_mobile_no?>
          </address>
        </div>
        <div class="box-actions">
          <a class="action edit" href="<?=base_url("user/address?do=edit&address_id=".$userDefaultShippingAddressDetails->id)?>">
          <span>Change Shipping Address</span>
          </a>
        </div>
		<?php }else{ ?>
		 <div class="box-content">
          <address>
           You have not set a default shipping address.
          </address>
        </div>
        <div class="box-actions">
		  <a class="action edit" href="<?=base_url("user/address?do=add")?>" data-ui-id="default-shipping-edit-link"><span>Add New Address</span></a>
        </div>
		<?php } ?>
      </div>
    </div>
  </div>
  <div class="block block-addresses-list">
    <div class="block-title"><strong>Additional Address Entries</strong></div>
    <div class="block-content">
      <ol class="items addresses">
	    <?php if(!empty($allShippingAddressList) && count($allShippingAddressList) > 0){?>
        <?php foreach($allShippingAddressList as $allShippingAddressList){?>
        <li class="item">
          <address>
            <?=$allShippingAddressList->full_name?><br>
            <?php
              if(isset($allShippingAddressList->company_name) && !empty($allShippingAddressList->company_name)){?>
            <?=$allShippingAddressList->company_name?><br>
            <?php } ?>
            <?=$allShippingAddressList->a_address_one?><br>
            <?=$allShippingAddressList->city_name?>,  <?=$allShippingAddressList->state_name?>, <?=$allShippingAddressList->a_post_code?><br>
            <?=$allShippingAddressList->country_name?><br>
            T: <?=$allShippingAddressList->a_mobile_no?>
            <br />
          </address>
          <div class="item actions">
            <a class="action edit" href="<?=base_url("user/address?do=edit&address_id=".$allShippingAddressList->id)?>"><span>Edit Address</span></a>
            <a class="action delete" href="#" role="delete-address" data-address="<?=$allShippingAddressList->id?>"><span>Delete Address</span></a>
          </div>
        </li>
        <?php } ?>
		 <?php }else{ ?>
		  You have not add a shipping address.
		 <?php } ?>
      </ol>
    </div>
  </div>
  <div class="actions-toolbar">
    <div class="primary">
      <button type="button" role="add-address" title="Add New Address" class="action primary add" onclick="javascript:window.location.href='<?=base_url("user/address?do=add");?>'"><span>Add New Address</span></button>
    </div>
    <div class="secondary">
      <a class="action back" href="<?=base_url("user/account");?>"><span>Back</span></a>
    </div>
  </div>
  <?php }else if(isset($do) && !empty($do) && $do == 'edit'){		
    //echo "<pre>";print_r($shippingAddressDetails);
    ?>
  <?php
    $form_attribute=array(
    		'name' => 'address-form',
    		'class' => 'form form-address-edit',
    		'method'=>"post",
    		'id' => 'form-validate',
    		'novalidate' => 'novalidate',
    		'autocomplete'=>"off",
    		'data-hasrequired'=> "* Required Fields"
    		);
    $hidden = array('action' => 'addressForm',"do"=>$do,'id'=>$shippingAddressDetails->id);
    //Form Open
    echo form_open_multipart('user/add_shipping_address',$form_attribute,$hidden);
    ?>
  <fieldset class="fieldset fieldset3 fieldset5">
    <legend class="legend legend3"><span>Contact Information</span></legend>
    <br>
    <input name="form_key" type="hidden" value="ZDG5BfMvixUbJUP9" />        
    <input type="hidden" name="success_url" value="">
    <input type="hidden" name="error_url" value="">
    <div class="field field-name-firstname required">
      <label class="label" for="firstname">
      <span>First Name</span>
      </label>
      <div class="control">
        <input type="text" id="firstname"
          name="firstname"
          value="<?=$shippingAddressDetails->a_fname?>"
          title="First Name"
          class="input-text required-entry"  data-validate="{required:true}">
      </div>
    </div>
    <div class="field field-name-lastname required">
      <label class="label"  for="lastname">
      <span>Last Name</span>
      </label>
      <div class="control">
        <input type="text" id="lastname"
          name="lastname"
          value="<?=$shippingAddressDetails->a_lname?>"
          title="Last Name"
          class="input-text required-entry" data-validate="{required:true}">
      </div>
    </div>
    <div class="field company">
      <label class="label" for="company"><span>Company</span></label>
      <div class="control">
        <input type="text" name="company" id="company" title="Company" value="<?=$shippingAddressDetails->company_name?>" class="input-text ">
      </div>
    </div>
    <div class="field telephone required">
      <label class="label" for="telephone"><span>Phone Number</span></label>
      <div class="control">
        <input type="text" name="phonenumber" value="<?=$shippingAddressDetails->a_mobile_no?>" title="Phone Number" class="input-text required-entry digits" id="phonenumber" minlength="10" maxlength="11">
      </div>
    </div>
  </fieldset>
  <fieldset class="fieldset fieldset3 fieldset5">
    <legend class="legend legend3"><span>Address</span></legend>
    <br>
    <div class="field street required">
      <label for="street_1" class="label"><span>Street Address</span></label>
      <div class="control">
        <input type="text" name="street[0]" value="<?=$shippingAddressDetails->a_address_one?>" title="Street Address" id="street_1" class="input-text required-entry"  />
      </div>
    </div>
    <div class="field city required">
      <label class="label" for="city"><span>City</span></label>
      <div class="control">
        <select id="city" name="city" title="City" class="validate-select">
          <option value="">Please select city.</option>
          <?php foreach($cityList as $cityLists){?>
          <option data-title="<?=$cityLists->city_name?>" value="<?=$cityLists->id?>" <?=(isset($shippingAddressDetails->a_city_id) && !empty($shippingAddressDetails->a_city_id) && $shippingAddressDetails->a_city_id==$cityLists->id)? 'selected':'';?>><?=$cityLists->city_name?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="field region required">
      <label class="label" for="region_id"><span>State/Province</span></label>
      <div class="control">
        <select class="validate-select" name="region_id" id="H96X1OG" aria-describedby="notice-H96X1OG" placeholder="">
          <option value="">Please select a region, state or province.</option>
          <?php foreach($statList as $statList){?>
          <option data-title="<?=$statList->state_name?>" value="<?=$statList->id?>" <?=(isset($shippingAddressDetails->a_state_id) && !empty($shippingAddressDetails->a_state_id) && $shippingAddressDetails->a_state_id==$statList->id)? 'selected':'';?>><?=$statList->state_name?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="field zip required">
      <label class="label" for="zip"><span>Zip/Postal Code</span></label>
      <div class="control">
        <input type="text" name="postcode" value="<?=$shippingAddressDetails->a_post_code?>" title="Zip/Postal Code" id="zip" class="input-text required-entry digits" minlength="6" maxlength="6">
      </div>
    </div>
    <!--<div class="field choice set billing">
      <input type="checkbox" id="primary_billing" name="default_billing" value="1" class="checkbox">
      <label class="label" for="primary_billing"><span>Use as my default billing address</span></label>
      </div>-->
    <?php if(isset($shippingAddressDetails->set_default) && !empty($shippingAddressDetails->set_default) && $shippingAddressDetails->set_default == 1){?>
    <div class="message info">It's a default shipping address.</div>
    <?php }else{ ?>
    <div class="field choice set shipping">
      <input type="checkbox" id="primary_shipping" name="is_default" value="1" class="checkbox">
      <label class="label" for="primary_shipping"><span>Use as my default shipping address</span></label>
    </div>
    <?php } ?>
  </fieldset>
  <div class="actions-toolbar">
    <div class="primary">
      <button type="submit" class="action save primary" data-action="save-address" title="Save Address">
      <span>Save Address</span>
      </button>
    </div>
    <div class="secondary">
      <a class="action back" href="<?=base_url("user/address")?>"><span>Go back</span></a>
    </div>
  </div>
  <?php 
    // Form Close
    echo form_close(); ?>
  <?php }else if(isset($do) && !empty($do) && $do == 'add'){?>
  <?php
    $form_attribute=array(
    		'name' => 'address-form',
    		'class' => 'form form-address-edit',
    		'method'=>"post",
    		'id' => 'form-validate',
    		'novalidate' => 'novalidate',
    		'autocomplete'=>"off",
    		'data-hasrequired'=> "* Required Fields"
    		);
    $hidden = array('action' => 'addressForm',"do"=>$do,'id'=>'');
    //Form Open
    echo form_open_multipart('user/add_shipping_address',$form_attribute,$hidden);
    ?>
  <fieldset class="fieldset fieldset3 fieldset5">
    <legend class="legend legend3"><span>Contact Information</span></legend>
    <br>
    <input name="form_key" type="hidden" value="ZDG5BfMvixUbJUP9" />        
    <input type="hidden" name="success_url" value="">
    <input type="hidden" name="error_url" value="">
    <div class="field field-name-firstname required">
      <label class="label" for="firstname">
      <span>First Name</span>
      </label>
      <div class="control">
        <input type="text" id="firstname"
          name="firstname"
          value=""
          title="First Name"
          class="input-text required-entry"   data-validate="{required:true}">
      </div>
    </div>
    <div class="field field-name-lastname required">
      <label class="label"  for="lastname">
      <span>Last Name</span>
      </label>
      <div class="control">
        <input type="text" id="lastname"
          name="lastname"
          value=""
          title="Last Name"
          class="input-text required-entry"   data-validate="{required:true}">
      </div>
    </div>
    <div class="field company">
      <label class="label" for="company"><span>Company</span></label>
      <div class="control">
        <input type="text" name="company" id="company" title="Company" value="" class="input-text ">
      </div>
    </div>
    <div class="field telephone required">
      <label class="label" for="telephone"><span>Phone Number</span></label>
      <div class="control">
        <input type="text" name="phonenumber" value="" title="Phone Number" class="input-text required-entry digits" id="phonenumber" minlength="10" maxlength="11">
      </div>
    </div>
  </fieldset>
  <fieldset class="fieldset fieldset3 fieldset5">
    <legend class="legend legend3"><span>Address</span></legend>
    <br>
    <div class="field street required">
      <label for="street_1" class="label"><span>Street Address</span></label>
      <div class="control">
        <input type="text" name="street[0]" value="" title="Street Address" id="street_1" class="input-text required-entry"  />
      </div>
    </div>
    <div class="field city required">
      <label class="label" for="city"><span>City</span></label>
      <div class="control">
        <select id="city" name="city" title="City" class="validate-select">
          <option value="">Please select city.</option>
          <?php foreach($cityList as $cityList){?>
          <option data-title="<?=$cityList->city_name?>" value="<?=$cityList->id?>"><?=$cityList->city_name?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="field region required">
      <label class="label" for="region_id"><span>State/Province</span></label>
      <div class="control">
        <select class="validate-select" name="region_id" id="H96X1OG" aria-describedby="notice-H96X1OG" placeholder="">
          <option value="">Please select a region, state or province.</option>
          <?php foreach($statList as $statList){?>
          <option data-title="<?=$statList->state_name?>" value="<?=$statList->id?>"><?=$statList->state_name?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="field zip required">
      <label class="label" for="zip"><span>Zip/Postal Code</span></label>
      <div class="control">
        <input type="text" name="postcode" value="" title="Zip/Postal Code" id="zip" class="input-text required-entry digits" minlength="6" maxlength="6">
      </div>
    </div>
    <!--<div class="field choice set billing">
      <input type="checkbox" id="primary_billing" name="default_billing" value="1" class="checkbox">
      <label class="label" for="primary_billing"><span>Use as my default billing address</span></label>
      </div>-->
    <div class="field choice set shipping">
      <input type="checkbox" id="primary_shipping" name="is_default" value="1" class="checkbox">
      <label class="label" for="primary_shipping"><span>Use as my default shipping address</span></label>
    </div>
  </fieldset>
  <div class="actions-toolbar">
    <div class="primary">
      <button type="submit" class="action save primary" data-action="save-address" title="Save Address">
      <span>Save Address</span>
      </button>
    </div>
    <div class="secondary">
      <a class="action back" href="<?=base_url("user/address")?>"><span>Go back</span></a>
    </div>
  </div>
  <?php 
    // Form Close
    echo form_close(); ?>
  <?php } ?>
</div>