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
  <div class="main-columns layout layout-1-col">
    <div class="col-main">
      <input name="form_key" type="hidden" value="Qdd3WcI5pSWK3wWj" />
      <div id="checkout" data-bind="scope:'checkout'" class="checkout-container">
        <ul class="opc-progress-bar">
          <!-- ko foreach: { data: steps().sort(sortItems), as: 'item' } -->
          <li class="opc-progress-bar-item _active" data-bind="css: item.isVisible() ? '_active' : ($parent.isProcessed(item) ? '_complete' : '')">
            <span data-bind="i18n: item.title, click: $parent.navigateTo">Shipping</span>
          </li>
          <li class="opc-progress-bar-item" data-bind="css: item.isVisible() ? '_active' : ($parent.isProcessed(item) ? '_complete' : '')">
            <span data-bind="i18n: item.title, click: $parent.navigateTo">Review &amp; Payments</span>
          </li>
          <!-- /ko -->
        </ul>
        <div class="opc-wrapper">
          <ol class="opc" id="checkoutSteps">
            <li id="shipping" class="checkout-shipping-address" data-bind="fadeVisible: visible()">
              <div class="step-title" data-bind="i18n: 'Shipping Address'" data-role="title">Shipping Address</div>
              <?php if($this->session->flashdata('user_success')){ ?>
              <div class="message success empty close_alert">
                <div><?php echo $this->session->flashdata('user_success'); ?></div>
              </div>
              <?php }else if($this->session->flashdata('user_error')){  ?>
              <div class="message error empty close_alert">
                <div><?php echo $this->session->flashdata('user_error'); ?></div>
              </div>
              <?php }?>
              <div id="checkout-step-shipping" class="step-content" data-role="content">
                <?php if(!isset($this->session->userdata('logged_in_brijwasi_user_data')['ID']) && empty($this->session->userdata('logged_in_brijwasi_user_data')['ID'])){?>
                <?php
                  $form_attribute=array(
                  		'name' => 'login-form',
                  		'class' => 'form form-login',
                  		'method'=>"post",
                  		'id' => 'login-form',
                  		'novalidate' => 'novalidate',
                  		);
                  $hidden = array('action' => 'loginForm',"do"=> 'checkout-page');
                  //Form Open
                  echo form_open('',$form_attribute,$hidden);
                  ?>
                <div class="page messages" id="login-message"></div>
                <fieldset id="customer-email-fieldset" class="fieldset" data-bind="blockLoader: isLoading">
                  <div class="field required">
                    <label class="label" for="customer-email">
                    <span data-bind="i18n: 'Email Address'">Email Address</span>
                    </label>
                    <div class="control _with-tooltip">
                      <input class="input-text valid" type="email" data-bind="
                        textInput: email,
                        hasFocus: emailFocused" name="username" data-validate="{required:true, 'validate-email':true}" id="customer-email" aria-required="true" placeholder="Email">
                    </div>
                  </div>
                  <!--Hidden fields -->
                  <fieldset class="fieldset hidden-fields" data-bind="fadeVisible: isPasswordVisible" style="display: block;">
                    <div class="field required">
                      <label class="label" for="customer-password">
                      <span data-bind="i18n: 'Password'">Password</span>
                      </label>
                      <div class="control">
                        <input class="input-text mage-error" placeholder="password" type="password" name="password" id="customer-password" data-validate="{required:true}" autocomplete="off" aria-invalid="true" aria-describedby="customer-password-error">
                        <span class="note" data-bind="i18n: 'You already have an account with us. Sign in or Sign up.'">You already have an account with us. Sign in or <a href="<?=base_url("user/registration/?page=checkout");?>">Sign up.</a></span>
                      </div>
                    </div>
                    <!-- ko foreach: getRegion('additional-login-form-fields') -->
                    <!-- ko template: getTemplate() -->
                    <!-- ko foreach: {data: elems, as: 'element'} -->
                    <!-- ko if: hasTemplate() --><!-- ko template: getTemplate() -->
                    <!-- ko if: (isRequired() && getIsVisible())--><!-- /ko -->
                    <!-- /ko --><!-- /ko -->
                    <!-- /ko -->
                    <!-- /ko -->
                    <!-- /ko -->
                    <div class="actions-toolbar">
                      <input name="context" type="hidden" value="checkout">
                      <div class="primary">
                        <button type="submit" class="action login primary" data-action="checkout-method-login"><span data-bind="i18n: 'Login'">Login</span></button>
                      </div>
                      <div class="secondary">
                        <a class="action remind" data-bind="attr: { href: forgotPasswordUrl }" href="<?=base_url("user/forgotpassword/?page=checkout");?>">
                        <span data-bind="i18n: 'Forgot Your Password?'">Forgot Your Password?</span>
                        </a>
                      </div>
                    </div>
                  </fieldset>
                  <!--Hidden fields -->
                </fieldset>
                <?php 
                  // Form Close
                  echo form_close(); ?>
                <?php }else{ ?>
                <div id="shipping-new-address-form-new-show" style="display:none">
                  <?php
                    $form_attribute=array(
                    'name' => 'form-shipping-address-new',
                    'class' => 'form form-shipping-address',
                    'method'=>"post",
                    'id' => 'co-shipping-form-new',
                    'novalidate' => 'novalidate',
                    'data-hasrequired' => "* Required Fields"
                    );
                    $hidden = array('action' => 'shippingAddressFormNew',"do"=> 'shipping-address');
                    //Form Open
                    echo form_open('user/add_shipping_address',$form_attribute,$hidden);
                    ?>
                  <div id="shipping-new-address-form-new" class="fieldset address">
                    <div class="field _required" data-bind="visible: visible, attr: {'name': element.dataScope}, css: additionalClasses" name="shippingAddress.firstname">
                      <label class="label" data-bind="attr: { for: element.uid }" for="S1AQ6OD">
                      <span data-bind="text: element.label">First Name</span>
                      </label>
                      <div class="control" data-bind="css: {'_with-tooltip': element.tooltip}">
                        <input class="input-text required" type="text" data-bind="
                          value: value,
                          valueUpdate: 'keyup',
                          hasFocus: focused,
                          attr: {
                          name: inputName,
                          placeholder: placeholder,
                          'aria-describedby': noticeId,
                          id: uid,
                          disabled: disabled
                          }" name="firstname" placeholder="" aria-describedby="notice-S1AQ6OD" id="S1AQ6OD">
                      </div>
                    </div>
                    <div class="field _required" data-bind="visible: visible, attr: {'name': element.dataScope}, css: additionalClasses" name="shippingAddress.lastname">
                      <label class="label" data-bind="attr: { for: element.uid }" for="JYAJ4GA">
                      <span data-bind="text: element.label">Last Name</span>
                      </label>
                      <div class="control" data-bind="css: {'_with-tooltip': element.tooltip}">
                        <input class="input-text required" type="text" data-bind="
                          value: value,
                          valueUpdate: 'keyup',
                          hasFocus: focused,
                          attr: {
                          name: inputName,
                          placeholder: placeholder,
                          'aria-describedby': noticeId,
                          id: uid,
                          disabled: disabled
                          }" name="lastname" placeholder="" aria-describedby="notice-JYAJ4GA" id="JYAJ4GA">
                      </div>
                    </div>
                    <div class="field" data-bind="visible: visible, attr: {'name': element.dataScope}, css: additionalClasses" name="shippingAddress.company">
                      <label class="label" data-bind="attr: { for: element.uid }" for="N7W8C87">
                      <span data-bind="text: element.label">Company</span>
                      </label>
                      <div class="control" data-bind="css: {'_with-tooltip': element.tooltip}">
                        <input class="input-text" type="text" data-bind="
                          value: value,
                          valueUpdate: 'keyup',
                          hasFocus: focused,
                          attr: {
                          name: inputName,
                          placeholder: placeholder,
                          'aria-describedby': noticeId,
                          id: uid,
                          disabled: disabled
                          }" name="company" placeholder="" aria-describedby="notice-N7W8C87" id="N7W8C87">
                      </div>
                    </div>
                    <fieldset class="field street admin__control-fields required" data-bind="css: additionalClasses">
                      <legend class="label">
                        <span data-bind="text: element.label">Street Address</span>
                      </legend>
                      <div class="control">
                        <div class="field _required" data-bind="visible: visible, attr: {'name': element.dataScope}, css: additionalClasses" name="shippingAddress.street.0">
                          <label class="label" data-bind="attr: { for: element.uid }" for="INOH0CB">
                          </label>
                          <div class="control" data-bind="css: {'_with-tooltip': element.tooltip}">
                            <input class="input-text required" type="text" data-bind="
                              value: value,
                              valueUpdate: 'keyup',
                              hasFocus: focused,
                              attr: {
                              name: inputName,
                              placeholder: placeholder,
                              'aria-describedby': noticeId,
                              id: uid,
                              disabled: disabled
                              }" name="street[0]" placeholder="" aria-describedby="notice-INOH0CB" id="INOH0CB">
                          </div>
                        </div>
                        <div class="field additional" data-bind="visible: visible, attr: {'name': element.dataScope}, css: additionalClasses" name="shippingAddress.street.1">
                          <label class="label" data-bind="attr: { for: element.uid }" for="SB3VN0U">
                          </label>
                          <div class="control" data-bind="css: {'_with-tooltip': element.tooltip}">
                            <input class="input-text required" type="text" data-bind="
                              value: value,
                              valueUpdate: 'keyup',
                              hasFocus: focused,
                              attr: {
                              name: inputName,
                              placeholder: placeholder,
                              'aria-describedby': noticeId,
                              id: uid,
                              disabled: disabled
                              }" name="street[1]" placeholder="" aria-describedby="notice-SB3VN0U" id="SB3VN0U">
                          </div>
                        </div>
                      </div>
                    </fieldset>
                    <div class="field _required" data-bind="visible: visible, attr: {'name': element.dataScope}, css: additionalClasses" name="shippingAddress.city">
                      <label class="label" data-bind="attr: { for: element.uid }" for="X3ILJSA">
                      <span data-bind="text: element.label">City</span>
                      </label>
                      <div class="control" data-bind="css: {'_with-tooltip': element.tooltip}">
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
                          <?php foreach($cityList as $cityLists){?>
                          <option data-title="<?=$cityLists->city_name?>" value="<?=$cityLists->id?>"><?=$cityLists->city_name?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="field _required" data-bind="visible: visible, attr: {'name': element.dataScope}, css: additionalClasses" name="shippingAddress.region_id">
                      <label class="label" data-bind="attr: { for: element.uid }" for="H96X1OG">
                      <span data-bind="text: element.label">State/Province</span>
                      </label>
                      <div class="control" data-bind="css: {'_with-tooltip': element.tooltip}">
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
                          <?php foreach($statList as $statLists){?>
                          <option data-title="<?=$statLists->state_name?>" value="<?=$statLists->id?>"><?=$statLists->state_name?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="field _required" data-bind="visible: visible, attr: {'name': element.dataScope}, css: additionalClasses" name="shippingAddress.postcode">
                      <label class="label" data-bind="attr: { for: element.uid }" for="KWTPJTN">
                      <span data-bind="text: element.label">Zip/Postal Code</span>
                      </label>
                      <div class="control" data-bind="css: {'_with-tooltip': element.tooltip}">
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
                          }" name="postcode" placeholder="" aria-describedby="notice-KWTPJTN" id="KWTPJTN" minlength="6" maxlength="6">
                      </div>
                    </div>
                    <div class="field _required" data-bind="visible: visible, attr: {'name': element.dataScope}, css: additionalClasses" name="shippingAddress.telephone">
                      <label class="label" data-bind="attr: { for: element.uid }" for="SM5REIR">
                      <span data-bind="text: element.label">Phone Number</span>
                      </label>
                      <div class="control" data-bind="css: {'_with-tooltip': element.tooltip}">
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
                          }" name="phonenumber" placeholder="" aria-describedby="notice-KWTPJTNT" id="KWTPJTNT" minlength="10" maxlength="11">
                      </div>
                    </div>
                    <div class="field choice address_default">
                      <input type="checkbox" name="is_default" title="Save address default" value="1" id="is_default" class="checkbox">
                      <label for="is_default" class="label"><span>Check For Default Address</span></label>
                    </div>
                  </div>
                  <div id="onepage-checkout-shipping-method-additional-load"></div>
                  <div class="actions-toolbar" id="shipping-method-buttons-container">
                    <div class="primary">
                      <button data-role="opc-continue" type="submit" class="button action continue primary">
                      <span data-bind="i18n: 'Next'">Save Address</span>
                      </button>
                    </div>
                    <a href="javascript:void(0);" id="shipping-address-popup-hide" style="display: inline-block; margin: 15px;color: #f28b01;text-decoration: underline;">Cancel</a>
                  </div>
                  <?php 
                    // Form Close
                    echo form_close(); ?>
                </div>
                <?php if(empty($usersShippingAddress) && count($usersShippingAddress) <= 0){?>
                <?php
                  $form_attribute=array(
                  		'name' => 'form-shipping-address',
                  		'class' => 'form form-shipping-address',
                  		'method'=>"post",
                  		'id' => 'co-shipping-form',
                  		'novalidate' => 'novalidate',
                  		'data-hasrequired' => "* Required Fields"
                  		);
                  $hidden = array('action' => 'shippingAddressFormNew',"do"=> 'shipping-address');
                  //Form Open
                  echo form_open('user/add_shipping_address',$form_attribute,$hidden);
                  ?>
                <div id="shipping-new-address-form" class="fieldset address">
                  <div class="field _required" data-bind="visible: visible, attr: {'name': element.dataScope}, css: additionalClasses" name="shippingAddress.firstname">
                    <label class="label" data-bind="attr: { for: element.uid }" for="S1AQ6OD">
                    <span data-bind="text: element.label">First Name</span>
                    </label>
                    <div class="control" data-bind="css: {'_with-tooltip': element.tooltip}">
                      <input class="input-text required" type="text" data-bind="
                        value: value,
                        valueUpdate: 'keyup',
                        hasFocus: focused,
                        attr: {
                        name: inputName,
                        placeholder: placeholder,
                        'aria-describedby': noticeId,
                        id: uid,
                        disabled: disabled
                        }" name="firstname" placeholder="" aria-describedby="notice-S1AQ6OD" id="S1AQ6OD">
                    </div>
                  </div>
                  <div class="field _required" data-bind="visible: visible, attr: {'name': element.dataScope}, css: additionalClasses" name="shippingAddress.lastname">
                    <label class="label" data-bind="attr: { for: element.uid }" for="JYAJ4GA">
                    <span data-bind="text: element.label">Last Name</span>
                    </label>
                    <div class="control" data-bind="css: {'_with-tooltip': element.tooltip}">
                      <input class="input-text required" type="text" data-bind="
                        value: value,
                        valueUpdate: 'keyup',
                        hasFocus: focused,
                        attr: {
                        name: inputName,
                        placeholder: placeholder,
                        'aria-describedby': noticeId,
                        id: uid,
                        disabled: disabled
                        }" name="lastname" placeholder="" aria-describedby="notice-JYAJ4GA" id="JYAJ4GA">
                    </div>
                  </div>
                  <div class="field" data-bind="visible: visible, attr: {'name': element.dataScope}, css: additionalClasses" name="shippingAddress.company">
                    <label class="label" data-bind="attr: { for: element.uid }" for="N7W8C87">
                    <span data-bind="text: element.label">Company</span>
                    </label>
                    <div class="control" data-bind="css: {'_with-tooltip': element.tooltip}">
                      <input class="input-text" type="text" data-bind="
                        value: value,
                        valueUpdate: 'keyup',
                        hasFocus: focused,
                        attr: {
                        name: inputName,
                        placeholder: placeholder,
                        'aria-describedby': noticeId,
                        id: uid,
                        disabled: disabled
                        }" name="company" placeholder="" aria-describedby="notice-N7W8C87" id="N7W8C87">
                    </div>
                  </div>
                  <fieldset class="field street admin__control-fields required" data-bind="css: additionalClasses">
                    <legend class="label">
                      <span data-bind="text: element.label">Street Address</span>
                    </legend>
                    <div class="control">
                      <div class="field _required" data-bind="visible: visible, attr: {'name': element.dataScope}, css: additionalClasses" name="shippingAddress.street.0">
                        <label class="label" data-bind="attr: { for: element.uid }" for="INOH0CB">
                        </label>
                        <div class="control" data-bind="css: {'_with-tooltip': element.tooltip}">
                          <input class="input-text required" type="text" data-bind="
                            value: value,
                            valueUpdate: 'keyup',
                            hasFocus: focused,
                            attr: {
                            name: inputName,
                            placeholder: placeholder,
                            'aria-describedby': noticeId,
                            id: uid,
                            disabled: disabled
                            }" name="street[0]" placeholder="" aria-describedby="notice-INOH0CB" id="INOH0CB">
                        </div>
                      </div>
                      <div class="field additional" data-bind="visible: visible, attr: {'name': element.dataScope}, css: additionalClasses" name="shippingAddress.street.1">
                        <label class="label" data-bind="attr: { for: element.uid }" for="SB3VN0U">
                        </label>
                        <div class="control" data-bind="css: {'_with-tooltip': element.tooltip}">
                          <input class="input-text required" type="text" data-bind="
                            value: value,
                            valueUpdate: 'keyup',
                            hasFocus: focused,
                            attr: {
                            name: inputName,
                            placeholder: placeholder,
                            'aria-describedby': noticeId,
                            id: uid,
                            disabled: disabled
                            }" name="street[1]" placeholder="" aria-describedby="notice-SB3VN0U" id="SB3VN0U">
                        </div>
                      </div>
                    </div>
                  </fieldset>
                  <div class="field _required" data-bind="visible: visible, attr: {'name': element.dataScope}, css: additionalClasses" name="shippingAddress.city">
                    <label class="label" data-bind="attr: { for: element.uid }" for="X3ILJSA">
                    <span data-bind="text: element.label">City</span>
                    </label>
                    <div class="control" data-bind="css: {'_with-tooltip': element.tooltip}">
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
                  <div class="field _required" data-bind="visible: visible, attr: {'name': element.dataScope}, css: additionalClasses" name="shippingAddress.region_id">
                    <label class="label" data-bind="attr: { for: element.uid }" for="H96X1OG">
                    <span data-bind="text: element.label">State/Province</span>
                    </label>
                    <div class="control" data-bind="css: {'_with-tooltip': element.tooltip}">
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
                  <div class="field _required" data-bind="visible: visible, attr: {'name': element.dataScope}, css: additionalClasses" name="shippingAddress.postcode">
                    <label class="label" data-bind="attr: { for: element.uid }" for="KWTPJTN">
                    <span data-bind="text: element.label">Zip/Postal Code</span>
                    </label>
                    <div class="control" data-bind="css: {'_with-tooltip': element.tooltip}">
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
                        }" name="postcode" placeholder="" aria-describedby="notice-KWTPJTN" id="KWTPJTN" minlength="6" maxlength="6">
                    </div>
                  </div>
                  <div class="field _required" data-bind="visible: visible, attr: {'name': element.dataScope}, css: additionalClasses" name="shippingAddress.telephone">
                    <label class="label" data-bind="attr: { for: element.uid }" for="SM5REIR">
                    <span data-bind="text: element.label">Phone Number</span>
                    </label>
                    <div class="control" data-bind="css: {'_with-tooltip': element.tooltip}">
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
                        }" name="phonenumber" placeholder="" aria-describedby="notice-KWTPJTNT" id="KWTPJTNT" minlength="10" maxlength="11">
                    </div>
                  </div>
                  <div class="field choice address_default">
                    <input type="checkbox" name="is_default" title="Save address default" value="1" id="is_default" class="checkbox">
                    <label for="is_default" class="label"><span>Check For Default Address</span></label>
                  </div>
                </div>
                <div id="onepage-checkout-shipping-method-additional-load"></div>
                <div class="actions-toolbar" id="shipping-method-buttons-container">
                  <div class="primary">
                    <button data-role="opc-continue" type="submit" class="button action continue primary">
                    <span data-bind="i18n: 'Next'">Next</span>
                    </button>
                  </div>
                </div>
                <?php 
                  // Form Close
                  echo form_close(); ?>
                <?php }else  if(!empty($usersShippingAddress) && count($usersShippingAddress) > 0){ ?>
                <div class="field addresses">
                  <div class="control">
                    <div class="shipping-address-items">
					
                      <?php  $SHIPPING_ADDRESS=''; foreach($usersShippingAddress as $usersShippingAddress){
                        $where_s = array("id"=> $usersShippingAddress->a_state_id, "status"=> 1);
                        $stateData = $this->base_model->getOneRecordWithWhere("brij_states",$where_s ,"*");
                        $where_cty = array("id"=> $usersShippingAddress->a_city_id, "status"=> 1);
                        $cityData = $this->base_model->getOneRecordWithWhere("brij_cities",$where_cty ,"*");
                        $where_country = array("id"=> $usersShippingAddress->a_country_id, "status"=> 1);
                        $countryData = $this->base_model->getOneRecordWithWhere("brij_countries",$where_country ,"*");
                       
                        $class_add=$class_add_default=' not-selected-item';
                        if(isset($usersShippingAddress->set_default) && $usersShippingAddress->set_default==1 && !isset($this->session->userdata('brijwasi_user_session_data')['SHIPPING_ADDRESS'])){
                        $SHIPPING_ADDRESS = trim($usersShippingAddress->id);
                        $class_add=' selected-item';
                        }
                        if(isset($this->session->userdata('brijwasi_user_session_data')['SHIPPING_ADDRESS']) && $this->session->userdata('brijwasi_user_session_data')['SHIPPING_ADDRESS'] == $usersShippingAddress->id){
                        $class_add=' selected-item';
                        }
                        
                        /* if(isset($usersShippingAddress->set_default) && !empty($usersShippingAddress->set_default) && $usersShippingAddress->set_default==1 || (isset($this->session->userdata('brijwasi_user_session_data')['SHIPPING_ADDRESS']) && $this->session->userdata('brijwasi_user_session_data')['SHIPPING_ADDRESS'] == $usersShippingAddress->id))? 'selected-item':'not-selected-item'; */
                        
                        ?>
                      <div class="shipping-address-item<?=$class_add?>" data-bind="css: isSelected() ? 'selected-item' : 'not-selected-item'" id="<?=$usersShippingAddress->id?>">
                        <?=$usersShippingAddress->a_fname?>
                        <?=$usersShippingAddress->a_lname?><br>
                        <?=$usersShippingAddress->a_address_one?><br>
                        <?=$cityData->city_name?><?=$stateData->state_name?>
                        <?=$usersShippingAddress->a_post_code?><br>
                        <?=$countryData->country_name?><br>
                        <?=$usersShippingAddress->a_mobile_no?><br>                      
                        <button type="button" data-bind="click: selectAddress" class="action action-select-shipping-item">
                        <span data-bind="i18n: 'Ship Here'">Ship Here</span>
                        </button>
                      </div>
                      <?php } ?>
                      <!-- /ko -->
                      <!-- /ko -->
                    </div>
                  </div>
                </div>
                <button type="button" id="shipping-address-popup" class="action action-show-popup">
                <span data-bind="i18n: 'New Address'">New Address</span>
                </button>
                <div class="actions-toolbar" id="shipping-method-buttons-container">
                  <div class="primary">
                    <?php
                      $form_attribute=array(
                      'name' => 'form-shipping-address',
                      'class' => 'form form-shipping-address',
                      'method'=>"post",
                      'id' => 'co-shipping-form1',
                      'novalidate' => 'novalidate',
                      'data-hasrequired' => "* Required Fields"
                      );
                      $hidden = array('action' => 'shippingAddressForm',"do"=> 'shipping-address');
                      //Form Open
                      echo form_open('user/set_shipping_address',$form_attribute,$hidden);
                      ?>
                    <input type="hidden" class="required" name="shipping_address_id" id="shipping_address_id" value="<?=(isset($this->session->userdata('brijwasi_user_session_data')['SHIPPING_ADDRESS']) && !empty($this->session->userdata('brijwasi_user_session_data')['SHIPPING_ADDRESS']))? $this->session->userdata('brijwasi_user_session_data')['SHIPPING_ADDRESS']:$SHIPPING_ADDRESS;?>"/>
                    <button data-role="opc-continue" type="submit" class="button action continue primary">
                    <span data-bind="i18n: 'Next'">Next</span>
                    </button>
                    <?php 
                      // Form Close
                      echo form_close(); ?>
                  </div>
                </div>
                <?php } ?>
              </div>
            </li>
            <?php } ?>
          </ol>
        </div>
        <!-- side info -->
        <aside class="modal-custom opc-sidebar opc-summary-wrappercustom-slide"data-role="modal" data-type="custom" tabindex="0">
          <?php $cart_details=getTotalCartItems($session_id=$this->session->session_id); //echo "<pre>";print_r($cart_details);?>
          <div data-role="focusable-start" tabindex="0"></div>
          <div class="modal-inner-wrap" data-role="focusable-scope">
            <header class="modal-header">  
              <button class="action-close" data-role="closeBtn" type="button">
              <span>Close</span>
              </button>
            </header>
            <div class="modal-content" data-role="content">
              <div id="opc-sidebar" data-bind="">
                <div class="opc-block-summary" data-bind="blockLoader: isLoading">
                  <span data-bind="i18n: 'Order Summary'" class="title">Order Summary</span>
                  <table class="data table table-totals">
                    <caption class="table-caption" data-bind="i18n: 'Order Summary'">Order Summary</caption>
                    <tbody>
                      <tr class="totals sub">
                        <th data-bind="" class="mark" scope="row">Cart Subtotal</th>
                        <td class="amount">
                          <span class="price" data-bind="" data-th=""><i class="fa fa-inr" aria-hidden="true"></i> <?=number_format($cart_details['cart_total'])?></span>
                        </td>
                      </tr>
                      <tr class="totals shipping excl">
                        <th class="mark" scope="row">
                          <span class="label" data-bind="i18n: title">Tax</span>
                          <span class="value" data-bind="text: getShippingMethodTitle()"></span>
                        </th>
                        <td class="amount">
                          <span class="not-calculated" data-bind="" data-th=""><i class="fa fa-inr" aria-hidden="true"></i> <?=number_format($cart_details['cart_items_tax'])?></span>
                        </td>
                      </tr>
                      <tr class="grand totals">
                        <th class="mark" scope="row">
                          <strong data-bind="i18n: title">Order Total</strong>
                        </th>
                        <td data-bind="" class="amount" data-th="Order Total">
                          <strong><span class="price" data-bind="text: getValue()"><i class="fa fa-inr" aria-hidden="true"></i>   <?=number_format($cart_details['grand_total_with_tax'])?></span></strong>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="block items-in-cart active" data-bind="" data-collapsible="true" role="tablist">
                    <div class="title" data-role="title" role="tab" aria-selected="false" aria-expanded="true" tabindex="0">
                      <strong role="heading">
                      <span data-bind="text: getItemsQty()"><?=$cart_details['cart_quantity']?></span>
                      <span>Items in Cart</span>
                      </strong>
                    </div>
                    <div class="content minicart-items" data-role="content" role="tabpanel" aria-hidden="false">
                      <div class="minicart-items-wrapper overflowed">
                        <ol class="minicart-items">
                          <?php 
                            //echo "<pre>";print_r($cartItemList);			  
                            foreach($cartItemList as $cartItemList){
                            //$catD=getCategory($cartItemList->category_id);
                            //$subCatD=getCategory($cartItemList->sub_category_id);
                            $productImage=getProductImage($cartItemList->product_id,$limit=1);											
                            $pro_file= '/uploads/no-image100x100.jpg';
                            $pro_original_file= '/uploads/no-image400x400.jpg';
                            if(count($productImage) > 0 && !empty($productImage)){
                            	$profilename = 'uploads/product_images/'.$productImage[0]->images;
                            	if (file_exists($profilename) && !empty($productImage[0]->images))
                            	{
                            		$pro_file='/uploads/product_images/medium/'.$productImage[0]->images;
                            		$pro_original_file='/uploads/product_images/'.$productImage[0]->images;														
                            	}
                            }
                            ?>
                          <li class="product-item">
                            <div class="product">
                              <span class="product-image-container" data-bind="" style="height: 100px; width: 100px;">
                              <span class="product-image-wrapper">
                              <img data-bind="" src="<?=base_url()?><?=$pro_file?>" width="100" height="100" alt="<?=$cartItemList->product_name?>">
                              </span>
                              </span>
                              <div class="product-item-details">
                                <div class="product-item-inner">
                                  <div class="product-item-name-block">
                                    <strong class="product-item-name" data-bind="text: $parent.name"><?=$cartItemList->product_name?>
                                    </strong>
                                    <div class="details-qty">
                                      <span class="label"><span>Qty</span></span>
                                      <span class="value" data-bind="text: $parent.qty"><?=$cartItemList->product_quantity?></span>
                                    </div>
                                  </div>
                                  <div class="subtotal">
                                    <span class="price-excluding-tax" data-bind="" data-label="">
                                    <span class="cart-price">
                                    <span class="price" data-bind="text: getFormattedPrice(getRowDisplayPriceExclTax($parents[2]))"><i class="fa fa-inr" aria-hidden="true"></i><?=number_format($cartItemList->price)?></span>
                                    </span>
                                    </span>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </li>
                          <?php } ?>
                        </ol>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </aside>
        <script type="text/javascript">
          require([
          'jquery',
          'mage/mage',
          'Magento_Catalog/product/view/validation'
          ], function ($) {
          'use strict';
          $('#login-form').mage('validation', {
          submitHandler: function (form) {					
          	var form=$("#login-form");
          	$("#login-message").empty();
          	$(".login").addClass("disabled");
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
          					setTimeout(function(){ $(location).attr('href',''); }, 3000);															
          					return false;
          					//console.log(res);
          				}else if (res.status==0){
          					$(".login").removeClass("disabled");
          					var msg='<div class="message error empty "><a href="#" class="close" data-dismiss="alert">&times;</a>'+res.message+'</div>';
          					$("#login-message").html(msg);
          					return false;
          					//console.log(res);
          				}
          				
          				$('html,body').animate({
          					scrollTop: $('#maincontent').offset().top
          				},1000);
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
          
          $('#co-shipping-form').mage('validation', {
          radioCheckboxClosest: '.nested',
          submitHandler: function (form) {
             $(form).submit();
          	return false;
          }
          }); 
          
          $('#co-shipping-form1').mage('validation', {
          radioCheckboxClosest: '.nested',
          ignore: [],
          messages:{
          shipping_address_id: "Please Select Shipping Address",
          },
          submitHandler: function (form) {
          $(form).submit();
          return false;
          }
          });
          
          $('#co-shipping-form-new').mage('validation', {
          radioCheckboxClosest: '.nested',
          submitHandler: function (form) {
          $(form).submit();
          return false;
          }
          });
          $('#shipping-new-address-form-new-show').hide('fast');
          $('#shipping-address-popup').click(function() {
          $('html,body').animate({
          scrollTop: $('#maincontent').offset().top
          },1000);
          $('#shipping-new-address-form-new-show').show('fast');
          });
          
          $('#shipping-address-popup-hide').click(function() {
          $('html,body').animate({
          scrollTop: $('#maincontent').offset().top
          },1000);
          $('#shipping-new-address-form-new-show').hide('fast');
          });
          
          $(".shipping-address-item").click(function(){
          $(".shipping-address-item").removeClass("selected-item").addClass("not-selected-item");
          $(this).removeClass("not-selected-item").addClass("selected-item"); 
          $("#shipping_address_id").val("").val($(this).attr("id")); 
          });
          });		  
        </script>
        <!-- end side info -->
      </div>
    </div>
  </div>
</div>
<!-- Modal -->