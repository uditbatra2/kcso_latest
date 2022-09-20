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
	<span class="base" data-ui-id="page-title-wrapper" >My Dashboard</span>    
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
</div>
<div class="main-columns layout layout-2-col row">
  <div class="col-main col-xs-12 col-sm-8 col-md-9 col-lg-9 col-sm-push-4 col-md-push-3 col-lg-push-3">
	<input name="form_key" type="hidden" value="cG6M3bCivEUBbSaW" />
	<div class="block block-dashboard-info clearfix">
	  <div class="block-title"><strong>Account Information</strong></div>
	  <div class="block-content">
		<div class="box box-information">
		  <strong class="box-title">
		  <span>Contact Information</span>
		  </strong>
		  <div class="box-content">
			<p>
			  <?=$userDetails->name;?><br>
			  <?=$userDetails->email_id;?><br>
			</p>
		  </div>
		  <div class="box-actions">
			<a class="action edit" href="<?=base_url("user/account_edit")?>">
			<span>Edit</span>
		    </a>
			<a href="<?=base_url("user/account_edit?changepass=1")?>" class="action change-password">
			Change Password </a>
		  </div>
		</div>
		<div class="box box-newsletter">
		  <strong class="box-title">
		  <span>Newsletters</span>
		  </strong>
		  <div class="box-content">
			<p>
			 <?php if(isset($userDetails->is_subscribe_newletters) && $userDetails->is_subscribe_newletters == 1){?>
			  You subscribe to "General Subscription".
			 <?php }else{?>			  
              You don't subscribe to our newsletter.
			 <?php } ?>			  
			</p>
		  </div>
		  <div class="box-actions">
			<a class="action edit" href="<?=base_url("user/newsletter_manage")?>"><span>Edit</span></a>
		  </div>
		</div>
	  </div>
	</div>
	<div class="block block-dashboard-addresses clearfix">
	  <div class="block-title">
		<strong>Address Book</strong>
		<a class="action edit" href="<?=base_url("user/address")?>"><span>Manage Addresses</span></a>
	  </div>
	  <div class="block-content">
		<!--<div class="box box-billing-address">
		  <strong class="box-title">
		  <span>Default Billing Address</span>
		  </strong>
		  <div class="box-content">
			<address>
			  You have not set a default billing address.                
			</address>
		  </div>
		  <div class="box-actions">
			<a class="action edit" href="<?//=base_url("user/address_edit/?address_id=1")?>" data-ui-id="default-billing-edit-link"><span>Edit Address</span></a>
		  </div>
		</div>-->
		<div class="box box-shipping-address" style="float: left;">
		  <strong class="box-title">
		  <span>Default Shipping Address</span>
		  </strong>
		  <?php //echo "<pre>";print_r($userDefaultShippingAddressDetails); 
		  if(!empty($userDefaultShippingAddressDetails) && count($userDefaultShippingAddressDetails) > 0){?>
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
			<a class="action edit" href="<?=base_url("user/address?do=edit&address_id=".$userDefaultShippingAddressDetails->id)?>" data-ui-id="default-shipping-edit-link"><span>Edit Address</span></a>
		  </div>
		  <?php }else{?>
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
  </div>