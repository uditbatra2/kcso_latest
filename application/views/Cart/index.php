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
  <div class="page-title-wrapper">
	<h1 class="page-title">
	  <span class="base" data-ui-id="page-title-wrapper" >Shopping Cart</span>    
	</h1>
  </div>
  <div class="main-columns layout layout-1-col">
	<div class="col-main">
		<?php if($this->session->flashdata('product_cart_success')){ ?>
			<div class="message success empty close_alert"><div><?php echo $this->session->flashdata('product_cart_success'); ?></div></div>
		<?php }else if($this->session->flashdata('product_cart_error')){  ?>
			<div class="message error empty close_alert"><div><?php echo $this->session->flashdata('product_cart_error'); ?></div></div>
		<?php }?>
	  <?php if(!empty($cartItemList) && count($cartItemList) > 0){
		      $cart_details=getTotalCartItems($session_id=$this->session->session_id);
		  ?>
	  <div class="cart-container">
		<!-- TOTAL AMOUNT -->
		<div class="cart-summary">
		  <strong class="summary title">Summary</strong>
		  <div id="cart-totals" class="cart-totals" data-bind="scope:'block-totals'">
			<div class="table-wrapper" data-bind="blockLoader: isLoading">
			  <table class="data table totals">
				<caption class="table-caption" data-bind="text: $t('Total')">Total</caption>
				<tbody>
				  <tr class="totals sub">
					<th class="mark" colspan="1" scope="row" data-bind="i18n: title">Subtotal</th>
					<td class="amount" data-th="Subtotal">
					  <span class="price" data-bind="text: getValue()"><i class="fa fa-inr" aria-hidden="true" style="margin-top:4px;"></i> <?=number_format($cart_details['cart_total'])?></span>
					</td>
				  </tr>
				  <tr class="totals shipping excl">
					<th class="mark" colspan="1" scope="row" data-bind="text: title + ' (' + getShippingMethodTitle() + ')'">Tax (Flat Rate - Fixed)</th>
					<td class="amount">
					  <span class="price" data-bind="text: getValue()"><i class="fa fa-inr" aria-hidden="true" style="margin-top:4px;"></i> <?=number_format($cart_details['cart_items_tax'])?></span>
					</td>
				  </tr>
				  <tr class="grand totals">
					<th class="mark" colspan="1" scope="row">
					  <strong data-bind="i18n: title">Order Total</strong>
					</th>
					<td class="amount" data-th="Order Total">
					  <strong><span class="price" data-bind="text: getValue()"><i class="fa fa-inr" aria-hidden="true" style="margin-top:4px;"></i> <?=number_format($cart_details['grand_total_with_tax'])?></span></strong>
					</td>
				  </tr>
				</tbody>
			  </table>
			</div>
		  </div>
		  <ul class="checkout methods items checkout-methods-items">
			<li class="item">    
			<button type="button"
			  data-role="proceed-to-checkout"
			  title="Proceed to Checkout"
			  data-mage-init=''
			  class="action primary checkout"
			  >
			  <span><a href="<?=base_url('checkout'); ?>">Proceed to Checkout</a></span>
			  </button>
			</li>
		  </ul>
		</div>
		<!--END total amout -->
		 <?php
			$form_attribute=array(
					'name' => 'form-cart',
					'class' => 'form form-cart',
					'method'=>"post",
					'id' => 'form-validate',
					'novalidate' => 'novalidate',
					);
			$hidden = array('action' => 'cartItemForm');
			//Form Open
			echo form_open('cart/cart_update',$form_attribute,$hidden);
		  ?>
		  <input name="form_key" type="hidden" value="BQJuG0FIHuPD9cK3" />    
		  <div class="cart table-wrapper">
			<table id="shopping-cart-table"class="cart items data table"data-mage-init=''>
			  <caption role="heading" aria-level="2" class="table-caption">Shopping Cart Items</caption>
			  <thead>
				<tr>
				  <th class="col item" scope="col"><span>Item</span></th>
				  <th class="col price" scope="col"><span>Price</span></th>
				  <th class="col qty" scope="col"><span>Qty</span></th>
				  <th class="col subtotal" scope="col"><span>Subtotal</span></th>
				</tr>
			  </thead>
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
			  <tbody class="cart item">
				<tr class="item-info">
				  <td data-th="Item" class="col item">
					<a href="<?=base_url('product/product_details/?pro_id='.$cartItemList->product_id); ?>"title=""tabindex="-1"class="product-item-photo">                        
					<span class="product-image-container"style="width:100px;">
					<span class="product-image-wrapper"style="padding-bottom: 100%;">
					<img class="product-image-photo"
					  src="<?=base_url()?><?=$pro_file?>"
					  width="100"
					  height="100"
					  alt="<?=$cartItemList->product_name?>"/></span>
					</span>
					</a>
					<div class="product-item-details">
					  <strong class="product-item-name">
					  <a href="<?=base_url('product/product_details/?pro_id='.$cartItemList->product_id); ?>"><?=$cartItemList->product_name?></a>
					  </strong>
					  <dl class="item-options">
						<dt>Category</dt>
						<dd>
						  <?=$cartItemList->name?>                                                           
						</dd>
					  </dl>
					</div>
					<div class="actions-toolbar">
					  <a href="javascript:void(0);"
					  title="Remove item"
					  class="action action-delete" data-delete-ID="<?=$cartItemList->id?>" id="<?=$cartItemList->id?>">
					  <span>
					  Remove item </span>
					  </a>
					</div>
				  </td>
				  <td class="col price" data-th="Price">
					<span class="price-excluding-tax" data-label="Excl. Tax">
					<span class="cart-price">
					<span class="price"><i class="fa fa-inr" aria-hidden="true"></i> <?=$cartItemList->price?>  </span></span>
					</span>
				  </td>
				  <td class="col qty" data-th="Qty">
					<div class="field qty">
					  <label class="label" for="cart-<?=$cartItemList->id?>-qty">
					  <span>Qty</span>
					  </label>
					  <div class="control qty">
						<input id="cart-<?=$cartItemList->id?>-qty"
						  name="cart[<?=$cartItemList->id?>][qty]"
						  data-cart-item-id="<?=$cartItemList->product_code?>"
						  value="<?=$cartItemList->product_quantity?>"
						  type="number"
						  size="4"
						  title="Qty"
						  class="input-text qty required"
						  maxlength="12"
						  data-validate="{required:true,'validate-greater-than-zero':true}"
                          data-role="cart-item-qty"/>
					  </div>
					</div>
				  </td>
				  <td class="col subtotal" data-th="Subtotal">
					<span class="price-excluding-tax" data-label="Excl. Tax">
					<span class="cart-price">
					<span class="price"><i class="fa fa-inr" aria-hidden="true"></i> <?=number_format($cartItemList->product_price)?></span></span>
					</span>
				  </td>
				</tr>
			  </tbody>
			  <?php } ?>
			</table>
		  </div>
		  <div class="cart main actions">
			<a class="action continue"
			  href="<?=base_url();?>"
			  title="Continue Shopping">
			<span>Continue Shopping</span>
			</a>
			<button type="submit"
			  name="update_cart_action"
			  data-cart-empty=""
			  value="empty_cart"
			  title="Clear Shopping Cart"
			  class="action clear" id="empty_cart_button">
			<span>Clear Shopping Cart</span>
			</button>
			<button type="submit"
			  name="update_cart_action"
			  data-cart-item-update=""
			  value="update_qty"
			  title="Update Shopping Cart"
			  class="action update">
			<span>Update Shopping Cart</span>
			</button>
			<input type="hidden" value="" id="update_cart_action_container" data-cart-item-update=""/>
		  </div>
		<?php 
		// Form Close
		echo form_close(); ?>
	  </div>
	  <?php }else{ ?>
	  <div class="cart-empty">
        <p>You have no items in your shopping cart.</p>
		<p>Click <a href="<?=base_url()?>">here</a> to continue shopping.</p>
	   </div>
	  <?php } ?>
	  <!-- Related Product -->
	  <?php 
	  if(!empty($relatedProductList) && count($relatedProductList) > 0){ ?>
	  <div class="crosssell-products">
		<div class="title-product-heading crosssell-heading">
		  <h2>Related Product</h2>
		</div>
		<div class="block-content content" aria-labelledby="block-crosssell-heading">
		  <div class="product-grid">
			<div id="crosssell_product_slider" class="owl-carousel">
			<?php //echo "<pre>";print_r($relatedProductList);
			foreach($relatedProductList as $relatedProductList){
				$catD=getCategory($relatedProductList->category_id);
				$subCatD=getCategory($relatedProductList->sub_category_id);
				$productImage=getProductImage($relatedProductList->id,$limit=1);											
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
		  <div class="products-item">
			<div class="product-item">
			  <div class="item-inner">
				<div class="product-photo">
				  <a href="<?=base_url('product/product_details/?pro_id='.$relatedProductList->id); ?>" class="product photo product-item-photo" tabindex="-1">
				  <span class="image0 image-switch">
				  <span class="product-image-container"
					style="width:300px;">
				  <span class="product-image-wrapper"
					style="padding-bottom: 100%;">
				  <img class="product-image-photo"
					src="<?=base_url()?><?=$pro_file?>"
					width="300"
					height="300"
					alt="<?=$relatedProductList->product_name?>"/></span>
				  </span>
				  </span>
				  <span class="image1 image-switch">
				  <span class="product-image-container"
					style="width:528px;">
				  <span class="product-image-wrapper"
					style="padding-bottom: 79.545454545455%;">
				  <img class="product-image-photo"
					src="<?=base_url()?><?=$pro_file?>"
					width="528"
					height="420"
					alt="<?=$relatedProductList->product_name?>"/></span>
				  </span>
				  </span>
				  </a>
				</div>
				<div class="product-info clearfix">
				  <div class="cate_name"> <a href="<?=base_url('product/products_list/?cat_id='.$relatedProductList->category_id); ?>" title='<?=$catD->name?>'><?=$catD->name?></a></div>
				  <h3 class="product-name">
					 <a href="<?=base_url('product/product_details/?pro_id='.$relatedProductList->id); ?>" class="product-item-link" title="<?=$relatedProductList->product_name?>">
                      <?=$relatedProductList->product_name?>                          
                      </a>
				  </h3>
				  <p><?=truncate($relatedProductList->description, $length=70, $stopanywhere=false)?></p>
				  <div class="price-box price-final_price" data-role="priceBox" data-product-id="<?=$relatedProductList->id?> ">
					<span class="special-price">
					<span class="price-container price-final_price tax weee"
					  >
					<span 
					  data-price-amount="<?=$relatedProductList->price?>"
					  data-price-type="finalPrice"
					  class="price-wrapper "
					  >
					<span class="price"><i class="fa fa-inr" aria-hidden="true"></i> <?=$relatedProductList->price?></span>    </span>
					</span>
					</span>
					<span class="old-price">
					<span class="price-container price-final_price tax weee">					
					</span>
					</span>
					<!--<span class="product-sale">
					-63%    </span>-->
				  </div>
				</div>
			  </div>
			</div>
		  </div>
		<?php } ?>
		</div>
	  </div>
	</div>
  </div>
<?php } ?>
  <!-- End of Related Product -->
  <script type="text/javascript">
	require([
		'jquery',
		'mage/mage',
		'themevast/owl',
		'Magento_Catalog/product/view/validation',
	], function ($) {
		'use strict';
	
		jQuery("#crosssell_product_slider").owlCarousel({
			autoplay :true,
			items : 4,
			smartSpeed : 500,
			dotsSpeed : 500,
			rewindSpeed : 500,
			nav : true,
			autoplayHoverPause : true,
			dots : false,
			scrollPerPage:true,
			margin: 30,
			responsive: {
			0: {
				items: 1,
			},
			480: {
				items:2,
			},
			768: {
				items:3,
			},
			991: {
				items:3,
			},                      
			1200: {
				items:4,
			}
		 }
		});
		
		//delete cart items
		jQuery("body").on('click','.action-delete',function(event) {
			event.preventDefault();
			var stringArrayId=$(this).prop("id");
			if(stringArrayId > 0){
				// Sets the new href (URL) for the current window.
				window.location.href = "<?=base_url('cart/delete_cart_items/?cart_id='); ?>"+stringArrayId;				
			}
			//alert(stringArrayId);	
		});
		
		$('#form-validate').mage('validation', {
				radioCheckboxClosest: '.nested',
				submitHandler: function (form) {
				   $(form).submit();
					return false;
				}
			}); 
	});
  </script>
</div>
</div>
</div>