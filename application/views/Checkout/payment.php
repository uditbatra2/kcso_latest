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
<div id="maincontent" class="page-main container" style="display:block">
  <div class="main-columns layout layout-1-col">
    <div class="col-main">
      <div id="checkout" data-bind="scope:'checkout'" class="checkout-container">
        <ul class="opc-progress-bar">
          <!-- ko foreach: { data: steps().sort(sortItems), as: 'item' } -->
          <li class="opc-progress-bar-item _complete" data-bind="css: item.isVisible() ? '_active' : ($parent.isProcessed(item) ? '_complete' : '')">
            <span data-bind="i18n: item.title, click: $parent.navigateTo">Shipping</span>
          </li>
          <li class="opc-progress-bar-item _active" data-bind="css: item.isVisible() ? '_active' : ($parent.isProcessed(item) ? '_complete' : '')">
            <span data-bind="i18n: item.title, click: $parent.navigateTo">Review &amp; Payments</span>
          </li>
          <!-- /ko -->
        </ul>
        <div class="opc-wrapper">
		<?php if($this->session->flashdata('payment_success')){ ?>
		  <div class="message success empty">
			<div><?php echo $this->session->flashdata('payment_success'); ?></div>
		  </div>
		  <?php }else if($this->session->flashdata('payment_error')){  ?>
		  <div class="message error empty">
			<div><?php echo $this->session->flashdata('payment_error'); ?></div>
		  </div>
		  <?php }?>
          <ol class="opc" id="checkoutSteps">
		    <li id="payment" role="presentation" class="checkout-payment-method" data-bind="fadeVisible: isVisible" style="display: list-item;">
              <div id="checkout-step-payment" class="step-content" data-role="content" role="tabpanel" aria-hidden="false">
                <!-- ko if: (quoteIsVirtual) --><!--/ko-->
                <form id="co-payment-form" class="form payments col-md-6" novalidate="novalidate">
                  <input data-bind="attr: {value: getFormKey()}" type="hidden" name="form_key" value="DyQnWSyJj1T8dksl">
                  <fieldset class="fieldset">
                    <legend class="legend">
                      <span data-bind="i18n: 'Payment Information'">Payment Information</span>
                    </legend>
                    <br>
                    <!-- ko foreach: getRegion('beforeMethods') -->
                    <!-- ko template: getTemplate() -->
                    <!-- ko foreach: {data: elems, as: 'element'} --><!-- /ko -->
                    <!-- /ko -->
                    <!-- /ko -->
                    <div id="checkout-payment-method-load" class="opc-payment" data-bind="visible: isPaymentMethodsAvailable" style="">
                      <!-- ko foreach: getRegion('payment-methods-list') -->
                      <!-- ko template: getTemplate() -->
                      <!-- ko if: isPaymentMethodsAvailable() -->
                      <div class="items payment-methods">
                        <!-- ko repeat: {foreach: paymentGroupsList, item: '$group'} -->
                        <div class="payment-group" data-repeat-index="0">
                          <!-- ko if: getRegion($group().displayArea)().length -->
                          <div class="step-title" data-role="title" data-bind="i18n: getGroupTitle($group)">Billing Address:</div>
						  <?php //print_r($usersShippingAddress);
							$where_s1 = array("id"=> $usersShippingAddress->a_state_id, "status"=> 1);
							$stateData1 = $this->base_model->getOneRecordWithWhere("brij_states",$where_s1 ,"*");
							$where_cty1 = array("id"=> $usersShippingAddress->a_city_id, "status"=> 1);
							$cityData1 = $this->base_model->getOneRecordWithWhere("brij_cities",$where_cty1 ,"*");
							$where_country1 = array("id"=> $usersShippingAddress->a_country_id, "status"=> 1);
							$countryData1 = $this->base_model->getOneRecordWithWhere("brij_countries",$where_country1 ,"*");
						?>
                          <!-- /ko -->
                          <!-- ko foreach: {data: getRegion($group().displayArea), as: 'method'} --><!-- ko template: getTemplate() -->
                          <div class="payment-method _active" data-bind="css: {'_active': (getCode() == isChecked())}">
                           
                            <div class="payment-method-content">
                              <div class="payment-method-billing-address">
                                <!-- ko foreach: $parent.getRegion(getBillingAddressFormName()) -->
                                <!-- ko template: getTemplate() -->
                                <div class="checkout-billing-address">
                                  
                                  <!-- ko template: 'Magento_Checkout/billing-address/details' -->
                                  <div class="billing-address-details" data-bind="if: isAddressDetailsVisible() &amp;&amp; currentBillingAddress()">
                                    <!-- ko text: currentBillingAddress().prefix --><!-- /ko --> <!-- ko text: currentBillingAddress().firstname --><?=$usersBilingAddress->name?><!-- /ko -->
                                   <br>
                                    <!-- ko text: currentBillingAddress().street --><?=$usersBilingAddress->address?><!-- /ko --><br>
                                    <!-- ko text: currentBillingAddress().city --><?=$cityData1->city_name?><!-- /ko -->, <!-- ko text: currentBillingAddress().region --><?=$stateData1->state_name?><!-- /ko --> <!-- ko text: currentBillingAddress().postcode --><?=$usersBilingAddress->pin_code?><!-- /ko --><br>
                                    <!-- ko text: getCountryName(currentBillingAddress().countryId) --><?=$countryData1->country_name?><!-- /ko --><br>
                                    <!-- ko text: currentBillingAddress().telephone --><?=$usersBilingAddress->phone_no?><!-- /ko --><br>
                                    <!-- ko foreach: { data: currentBillingAddress().customAttributes, as: 'element' } --><!-- /ko -->
                                    <button type="button" class="action action-edit-address" data-bind="visible: !isAddressSameAsShipping(), click: editAddress" style="display: none;">
                                    <span data-bind="i18n: 'Edit'">Edit</span>
                                    </button>
                                  </div>
                                  <!-- /ko -->			   
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </fieldset>              
				</form>
				<form id="co-payment-form" class="form payments col-md-6" novalidate="novalidate">
                  <input data-bind="attr: {value: getFormKey()}" type="hidden" name="form_key" value="DyQnWSyJj1T8dksl">
                  <fieldset class="fieldset">
                    <legend class="legend">
                      <span data-bind="i18n: 'Payment Information'">Payment Information</span>
                    </legend>
                    <br>
                    <!-- ko foreach: getRegion('beforeMethods') -->
                    <!-- ko template: getTemplate() -->
                    <!-- ko foreach: {data: elems, as: 'element'} --><!-- /ko -->
                    <!-- /ko -->
                    <!-- /ko -->
                    <div id="checkout-payment-method-load" class="opc-payment" data-bind="visible: isPaymentMethodsAvailable" style="">
                      <!-- ko foreach: getRegion('payment-methods-list') -->
                      <!-- ko template: getTemplate() -->
                      <!-- ko if: isPaymentMethodsAvailable() -->
                      <div class="items payment-methods">
                        <!-- ko repeat: {foreach: paymentGroupsList, item: '$group'} -->
                        <div class="payment-group" data-repeat-index="0">
                          <!-- ko if: getRegion($group().displayArea)().length -->
                          <div class="step-title" data-role="title" data-bind="i18n: getGroupTitle($group)">Shipping Address:</div>
						  <?php //print_r($usersShippingAddress);
							$where_s = array("id"=> $usersShippingAddress->a_state_id, "status"=> 1);
							$stateData = $this->base_model->getOneRecordWithWhere("brij_states",$where_s ,"*");
							$where_cty = array("id"=> $usersShippingAddress->a_city_id, "status"=> 1);
							$cityData = $this->base_model->getOneRecordWithWhere("brij_cities",$where_cty ,"*");
							$where_country = array("id"=> $usersShippingAddress->a_country_id, "status"=> 1);
							$countryData = $this->base_model->getOneRecordWithWhere("brij_countries",$where_country ,"*");
						?>
                          <!-- /ko -->
                          <!-- ko foreach: {data: getRegion($group().displayArea), as: 'method'} --><!-- ko template: getTemplate() -->
                          <div class="payment-method _active" data-bind="css: {'_active': (getCode() == isChecked())}">
                            <div class="payment-method-content">
                              <div class="payment-method-billing-address">
                                <!-- ko foreach: $parent.getRegion(getBillingAddressFormName()) -->
                                <!-- ko template: getTemplate() -->
                                <div class="checkout-billing-address">
                                  <!-- ko template: 'Magento_Checkout/billing-address/details' -->
                                  <div class="billing-address-details" data-bind="if: isAddressDetailsVisible() &amp;&amp; currentBillingAddress()">
                                    <!-- ko text: currentBillingAddress().prefix --><!-- /ko --> <!-- ko text: currentBillingAddress().firstname --><?=$usersShippingAddress->a_fname?><!-- /ko -->
                                    <!-- ko text: currentBillingAddress().lastname --><?=$usersShippingAddress->a_lname?><!-- /ko --> <!-- ko text: currentBillingAddress().suffix --><!-- /ko --><br>
                                    <!-- ko text: currentBillingAddress().street --><?=$usersShippingAddress->a_address_one?><!-- /ko --><br>
                                    <!-- ko text: currentBillingAddress().city --><?=$cityData->city_name?><!-- /ko -->, <!-- ko text: currentBillingAddress().region --><?=$stateData->state_name?><!-- /ko --> <!-- ko text: currentBillingAddress().postcode --><?=$usersShippingAddress->a_post_code?><!-- /ko --><br>
                                    <!-- ko text: getCountryName(currentBillingAddress().countryId) --><?=$countryData->country_name?><!-- /ko --><br>
                                    <!-- ko text: currentBillingAddress().telephone --><?=$usersShippingAddress->a_mobile_no?><!-- /ko --><br>
                                    <!-- ko foreach: { data: currentBillingAddress().customAttributes, as: 'element' } --><!-- /ko -->
                                    <button type="button" class="action action-edit-address" data-bind="visible: !isAddressSameAsShipping(), click: editAddress" style="display: block;" onClick="javascript:window.location.href='<?=base_url("checkout");?>';">
                                    <span data-bind="i18n: 'Edit'">Change Address</span>
                                    </button>
                                  </div>
                                  <!-- /ko -->			   
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </fieldset>               
				</form>
				<div style="clear:both"></div>         				
              </div>			  
            </li>
            <li id="payment" role="presentation" class="checkout-payment-method" data-bind="fadeVisible: isVisible" style="display: list-item; clear: both;">
              <div id="checkout-step-payment" class="step-content" data-role="content" role="tabpanel" aria-hidden="false">
                <!-- ko if: (quoteIsVirtual) --><!--/ko-->
				<?php
                  $form_attribute=array(
                  		'name' => 'co-payment-form',
                  		'class' => 'form payments',
                  		'method'=>"post",
                  		'id' => 'co-payment-form',
                  		'novalidate' => 'novalidate',
                  		);
                  $hidden = array('action' => 'paymentForm');
                  //Form Open
                  echo form_open('checkout/place_order',$form_attribute,$hidden);
                  ?>
                  <input data-bind="attr: {value: getFormKey()}" type="hidden" name="form_key" value="DyQnWSyJj1T8dksl">
                  <fieldset class="fieldset">
                    <legend class="legend">
                      <span data-bind="i18n: 'Payment Information'">Payment Information</span>
                    </legend>
                    <br>
                    <!-- ko foreach: getRegion('beforeMethods') -->
                    <!-- ko template: getTemplate() -->
                    <!-- ko foreach: {data: elems, as: 'element'} --><!-- /ko -->
                    <!-- /ko -->
                    <!-- /ko -->
                    <div id="checkout-payment-method-load" class="opc-payment" data-bind="visible: isPaymentMethodsAvailable" style="">
                      <!-- ko foreach: getRegion('payment-methods-list') -->
                      <!-- ko template: getTemplate() -->
                      <!-- ko if: isPaymentMethodsAvailable() -->
                      <div class="items payment-methods">
                        <!-- ko repeat: {foreach: paymentGroupsList, item: '$group'} -->
                        <div class="payment-group" data-repeat-index="0">
                          <!-- ko if: getRegion($group().displayArea)().length -->
                          <div class="step-title" data-role="title" data-bind="i18n: getGroupTitle($group)">Payment Method:</div>
                          <!-- /ko -->
                          <!-- ko foreach: {data: getRegion($group().displayArea), as: 'method'} --><!-- ko template: getTemplate() -->
                          <div class="payment-method _active" data-bind="css: {'_active': (getCode() == isChecked())}">
                            <div class="payment-method-title field choice">
                              <input type="radio" name="payment[method]" class="radio" data-bind="attr: {'id': getCode()}, value: getCode(), checked: isChecked, click: selectPaymentMethod, visible: isRadioButtonVisible()" id="checkmo" value="checkmo" style="display: none;">
                              <label data-bind="attr: {'for': getCode()}" class="label" for="checkmo"><span data-bind="text: getTitle()">Check / Money order</span></label>
                            </div>
                            <div class="payment-method-content">
                              <!-- ko foreach: getRegion('messages') -->
                              <!-- ko template: getTemplate() -->
                              <div data-role="checkout-messages" class="messages" data-bind="visible: isVisible(), click: removeAll">
                                <!-- ko foreach: messageContainer.getErrorMessages() --><!--/ko-->
                                <!-- ko foreach: messageContainer.getSuccessMessages() --><!--/ko-->
                              </div>
                              <!-- /ko -->
                              <!--/ko-->
                              <div class="payment-method-billing-address">
                                <!-- ko foreach: $parent.getRegion(getBillingAddressFormName()) -->
                                <!-- ko template: getTemplate() -->
                                <div class="checkout-billing-address">
                                  <div class="billing-address-same-as-shipping-block field choice" data-bind="visible: canUseShippingAddress()">
                                    <input type="radio" name="payment_method" data-bind="checked: isAddressSameAsShipping, click: useShippingAddress, attr: {id: 'billing-address-same-as-shipping-' + getCode($parent)}" id="billing-address-same-as-shipping-checkmo" value="COD" checked>
                                    <label data-bind="attr: {for: 'billing-address-same-as-shipping-' + getCode($parent)}" for="billing-address-same-as-shipping-checkmo"><span data-bind="i18n: 'My billing and shipping address are the same'">Cash On Delivery</span></label>									
                                  </div>
								  <div class="billing-address-same-as-shipping-block field choice" data-bind="visible: canUseShippingAddress()">
								   <input type="radio" name="payment_method" data-bind="checked: isAddressSameAsShipping, click: useShippingAddress, attr: {id: 'billing-address-same-as-shipping-' + getCode($parent)}" id="billing-address-same-as-shipping-checkmo" value="PayUmoney">
                                    <label data-bind="attr: {for: 'billing-address-same-as-shipping-' + getCode($parent)}" for="billing-address-same-as-shipping-checkmo"><span data-bind="i18n: 'My billing and shipping address are the same'">PayUmoney</span></label>
									</div>
                                  <!-- ko template: 'Magento_Checkout/billing-address/details' -->
                                  <!-- /ko -->			   
                                </div>
                              </div>
							 <label class="label"><strong>Delivery Instructions:</strong></label> <textarea name="user_order_remark" id="user_order_remark"></textarea>
                              <div class="actions-toolbar">
                                <div class="primary">
                                  <button class="action primary checkout" type="submit" data-bind="
                                    click: placeOrder,
                                    attr: {title: $t('Place Order')},
                                    css: {disabled: !isPlaceOrderActionAllowed()},
                                    enable: (getCode() == isChecked())
                                    " title="Place Order">
                                  <span data-bind="i18n: 'Place Order'">Place Order</span>
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </fieldset>
                <?php 
                  // Form Close
                  echo form_close(); ?>
              </div>
            </li>
          </ol>
        </div>
        <aside class="modal-custom opc-sidebar opc-summary-wrapper custom-slides" data-role="modals" data-type="custom" tabindex="0">
		 <?php $cart_details=getTotalCartItems($session_id=$this->session->session_id); //echo "<pre>";print_r($cart_details);?>
          <div data-role="focusable-start" tabindex="0"></div>
          <div class="modal-inner-wrap" data-role="focusable-scope">
            <div class="modal-content" data-role="content">
              <div id="opc-sidebar">
                <div class="opc-block-summary" data-bind="blockLoader: isLoading">
                  <span data-bind="i18n: 'Order Summary'" class="title">Order Summary</span>
                  <table class="data table table-totals">
                    <caption class="table-caption" data-bind="i18n: 'Order Summary'">Order Summary</caption>
                    <tbody>
                      <tr class="totals sub">
                        <th data-bind="i18n: title" class="mark" scope="row">Cart Subtotal</th>
                        <td class="amount">
                          <span class="price" data-bind="text: getValue(), attr: {'data-th': title}" data-th="Cart Subtotal"><i class="fa fa-inr" aria-hidden="true"></i> <?=number_format($cart_details['cart_total'])?></span>
                        </td>
                      </tr>
                      <tr class="totals shipping excl">
                        <th class="mark" scope="row">
                          <span class="label" data-bind="i18n: title">Tax</span>
                          <span class="value" data-bind="text: getShippingMethodTitle()">Flat Rate - Fixed</span>
                        </th>
                        <td class="amount">
                          <span class="price" data-bind="text: getValue(), attr: {'data-th': title}" data-th="Shipping"><i class="fa fa-inr" aria-hidden="true"></i><?=number_format($cart_details['cart_items_tax'])?></span>
                        </td>
                      </tr>
                      <tr class="grand totals">
                        <th class="mark" scope="row">
                          <strong data-bind="i18n: title">Order Total</strong>
                        </th>
                        <td data-bind="attr: {'data-th': title}" class="amount" data-th="Order Total">
                          <strong><span class="price" data-bind="text: getValue()"><i class="fa fa-inr" aria-hidden="true"></i><?=number_format($cart_details['grand_total_with_tax'])?></span></strong>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="block items-in-cart" data-bind="mageInit: {'collapsible':{'openedState': 'active', 'active': isItemsBlockExpanded()}}" data-collapsible="true" role="tablist">
                    <div class="title" data-role="title" role="tab" aria-selected="false" aria-expanded="false" tabindex="0">
                      <strong role="heading">
                      <span data-bind="text: getItemsQty()"><?=$cart_details['cart_quantity']?></span>
                      <span>Item in Cart</span>
                      </strong>
                    </div>
                    <div class="content minicart-items" data-role="content" role="tabpanel" aria-hidden="true" style="display: none;">
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
                <div class="opc-block-shipping-information">
				<?php //print_r($usersShippingAddress);
					$where_s = array("id"=> $usersShippingAddress->a_state_id, "status"=> 1);
					$stateData = $this->base_model->getOneRecordWithWhere("brij_states",$where_s ,"*");
					$where_cty = array("id"=> $usersShippingAddress->a_city_id, "status"=> 1);
					$cityData = $this->base_model->getOneRecordWithWhere("brij_cities",$where_cty ,"*");
					$where_country = array("id"=> $usersShippingAddress->a_country_id, "status"=> 1);
					$countryData = $this->base_model->getOneRecordWithWhere("brij_countries",$where_country ,"*");
				?>
                  <div class="shipping-information">
                    <div class="ship-to">
                      <div class="shipping-information-title">
                        <span data-bind="i18n: 'Ship To:'">Ship To:</span>
                        <button class="action action-edit" data-bind="click: back">
                        <span data-bind="i18n: 'edit'">edit</span>
                        </button>
                      </div>
                      <div class="shipping-information-content">
                        <!-- ko text: address().prefix --><!-- /ko --> <!-- ko text: address().firstname --><?=$usersShippingAddress->a_fname?><!-- /ko -->
                        <!-- ko text: address().lastname --><?=$usersShippingAddress->a_lname?><!-- /ko --> <!-- ko text: address().suffix --><!-- /ko --><br>
                        <!-- ko text: address().street --><?=$usersShippingAddress->a_address_one?><!-- /ko --><br>
                        <!-- ko text: address().city --> <?=$cityData->city_name?><!-- /ko -->, <!-- ko text: address().region --><?=$stateData->state_name?><!-- /ko --> <!-- ko text: address().postcode --><?=$usersShippingAddress->a_post_code?><!-- /ko --><br>
                        <!-- ko text: getCountryName(address().countryId) --><?=$countryData->country_name?><!-- /ko --><br>
                        <!-- ko text: address().telephone --><?=$usersShippingAddress->a_mobile_no?><!-- /ko --><br>
                        <!-- ko foreach: { data: address().customAttributes, as: 'element' } --><!-- /ko -->
                        <!-- /ko -->
                        <!-- /ko -->
                        <!-- /ko -->
                        <!-- /ko -->
                        <!--/ko-->
                      </div>
                    </div>
                  </div>
                  <!--/ko-->
                  <!-- /ko -->
                  <!--/ko-->
                </div>
              </div>
            </div>
          </div>
          <div data-role="focusable-end" tabindex="0"></div>
        </aside>
      </div>
    </div>
  </div>
</div>