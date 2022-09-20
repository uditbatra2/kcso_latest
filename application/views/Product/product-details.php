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
<div class="breadcrumbs">
  <div class="container">
	<ul class="items">
	  <li class="item home">
		<a href="<?=base_url()?>" title="Go to Home Page">
		Home					
		</a>
	  </li>
	  <li class="item product">
		<strong><?=$productDetails->product_name?></strong>
	  </li>
	</ul>
  </div>
</div>
<div id="maincontent" class="page-main container" style="display:block;">
  <div class="main-columns layout layout-1-col">
	<div class="col-main">
	  <div class="product-view">
		<div class="view-product">
		  <div class="row">
			<div class="col-md-6 col-sm-6 col-xs-12">
			  <div class="product-media">
			    <?php
				$productImage=getProductImage($productDetails->id,$limit=1);											
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
				<img class="fotorama__img" src="<?=base_url()?><?=$pro_original_file?>" width="528"height="420"alt="<?=$productDetails->product_name?>"/>
			  </div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
			<?php if($this->session->flashdata('product_cart_success')){ ?>
				<div class="message success empty close_alert"><div><?php echo $this->session->flashdata('product_cart_success'); ?></div></div>
			<?php }else if($this->session->flashdata('product_cart_error')){  ?>
				<div class="message error empty close_alert"><div><?php echo $this->session->flashdata('product_cart_error'); ?></div></div>
			<?php }?>
			  <div class="product-info product-info-main">
				<div class="box-inner1">
				  <div class="page-title-wrapper product">
					<h1 class="page-title">
					  <span class="base" data-ui-id="page-title-wrapper" itemprop="name"><?=$productDetails->product_name?></span>    
					</h1>
				  </div>
				  <!--<div class="box-poin-review clearfix">
					<div class="product-reviews-summary" >
					  <div class="rating-summary">
						<span class="label"><span>Rating:</span></span>
						<div class="rating-result" title="90%">
						  <span style="width:90%"><span><span>90</span>% of <span>100</span></span></span>
						</div>
					  </div>
					  <div class="reviews-actions">
						<a class="action view" href="movado-bolblack-watch.html#reviews"><span>2</span>&nbsp;<span>Reviews</span></a>
						<a class="action add" href="movado-bolblack-watch.html#review-form">Add Your Review</a>
					  </div>
					</div>
				  </div>-->
				  <div class="product-info-price">
					<div class="price-box price-final_price" data-role="priceBox" data-product-id="<?=$productDetails->id?>">
					  <span class="special-price">
						<span class="price-container price-final_price tax weee"1>
						  <span class="price-label"><?=$productDetails->product_name?></span>
						  <span data-price-amount="<?=$productDetails->price?>"data-price-type="finalPrice"class="price-wrapper "itemprop="price">
						  <span class="price"><i class="fa fa-inr" aria-hidden="true"></i> <?=$productDetails->price?> / <?=$priceType[$productDetails->price_type]?></span>
						  </span>
						  <meta itemprop="priceCurrency" content="INR" />
						</span>
					  </span>
					  <span class="old-price">
					  <span class="price-container price-final_price tax weee">
					  <span class="price-label"><?=$productDetails->product_name?></span>
					  <!--<span data-price-amount="<?//=$productDetails->product_name?>"data-price-type="oldPrice"class="price-wrapper">
					  <span class="price">
					  <i class="fa fa-inr" aria-hidden="true"></i> <?//=$productDetails->price?></span>
					  </span>-->
					  </span>
					  </span>
					  <!--<span class="product-sale">-11%</span>-->
					</div>
				  </div>
				  <div class="product-info-stock-sku">
					<div class="product attribute sku">
					  <strong class="type">Item Code: </strong>
					  <div class="value" itemprop="Sku"><?=$productDetails->product_code?></div>
					</div>
					<div class="stock available" title="Availability">
					  Availability: <?=(isset($productDetails->stock_availability) && $productDetails->stock_availability == 1)?'<span>In stock</span>':'<span>Out stock</span>';?>
					</div>
				  </div>
				  <div class="product attribute overview">
					<div class="value" itemprop="description">
					  <p><?=strip_tags($productDetails->description);?></p>
					</div>
				  </div>
				  <?php if(isset($productDetails->stock_availability) && $productDetails->stock_availability == 1){?>
				  <div class="product-add-form">
				    <?php
                    $form_attribute=array(
                    		'name' => 'product_addtocart_form',
                    		'class' => 'form-horizontal',
                    		'method'=>"post",
                    		'id' => 'product_addtocart_form',
                    		'novalidate' => 'novalidate',
                    		);
                    $hidden = array('action' => 'productAddToCart');
                    //Form Open
                    echo form_open('cart/add_cart_item',$form_attribute,$hidden);
                    ?>
					  <input type="hidden" name="product" value="<?=$productDetails->id?>" />
					  <input type="hidden" name="selected_configurable_option" value="" />
					  <input type="hidden" name="related_product" id="related-products-field" value="" />
					  <input name="form_key" type="hidden" value="FQMAevpDnhnwqwwG" />                                    
					  <div class="product-options-bottom">
						<div class="box-add-to-link clearfix">
						  <div class="box-tocart">
							<div class="fieldset">
							  <div class="field qty">
								<div class="control">
								  <label class="qty-title">Qty:</label>
								  <div class="info-qty">
									<a class="qty-down" href="#"><i class="fa fa-sort-desc"></i></a>
									<input type="number"
									  name="qty"
									  id="qty"
									  maxlength="12"
									  value="1"
									  title="Quantity" class="input-text qty"
										data-validate="{&quot;required-number&quot;:true,&quot;validate-item-quantity&quot;:{&quot;minAllowed&quot;:1}}"
									  />
									<a class="qty-up" href="#"><i class="fa fa-sort-asc"></i></a>
								  </div>
								</div>
							  </div>
							  <div class="actions">
								<button type="submit" title="Add to Cart"class="action primary tocart"id="product-addtocart-button">
								Add to Cart           
								</button>
							  </div>
							</div>
						  </div>
						</div>
					  </div>
					<?php 
                    // Form Close
                    echo form_close(); ?>
				  </div>
				  <?php } ?>
				  <div class="box-add-to-links clearfix">
					<div class="product-social-links">
					  <div class="product-addto-links" data-role="add-to-links">
						<a href="<?=base_url("user/wishlist/?do=add-wishlist&wishlist_id=".$productDetails->id); ?>" class="wishlist" style="margin-bottom: 10px; display: inline-block;">Add to Wishlist</a>
						<?php
						$form_attribute=array(
								'name' => 'product_checkpin_form',
								'class' => 'form-horizontal cust_validate',
								'method'=>"post",
								'id' => 'product_checkpin_form',
								'novalidate' => 'novalidate',
								'autocomplete'=>"off",
								'data-mage-init'=>'{"validation":{}}',
								'style'=> "display: inline-block;"
								);
						$hidden = array('action' => 'productCheckpinForm','product_id' =>$productDetails->id);
						// Form Open
						echo form_open('product/set_product_pin_code',$form_attribute,$hidden);
						?>
						<input type="text" name="product_pin_code" id="product_pin_code" placeholder="Enter Delivery Pincode" style="width: 122px;display: inline-block;height: 30px;margin-right: 5px;" class="required digits" value="<?php if(isset($this->session->userdata('brijwasi_user_session_data')['PRODUCT_PIN_CODE']) && !empty($this->session->userdata('brijwasi_user_session_data')['PRODUCT_PIN_CODE'])){
                           echo $this->session->userdata('brijwasi_user_session_data')['PRODUCT_PIN_CODE'];							
						}?>" minlength="6" maxlength="6">
						<input type="submit" name="submit" value="Check delivery" class="action primary tocart" style="font-size: 12px; height: auto; line-height: normal;padding: 7px 15px;">
						<p style="margin: 5px 0; color:red;">
						<?php if($this->session->flashdata('product_pin_success')){ ?>
								<div class="message success empty close_alerts"><div><?php echo $this->session->flashdata('product_pin_success'); ?></div></div>
							<?php }else if($this->session->flashdata('product_pin_error')){  ?>
								<div class="message error empty close_alerts"><div><?php echo $this->session->flashdata('product_pin_error'); ?></div></div>
							<?php }?>
						</p>
						<?php 
						// Form Close
						echo form_close(); ?>
					  </div>
					  <div class="addthis_inline_share_toolbox_pzhd"></div>
					</div>
				  </div>
				</div>
			  </div>
			</div>
		  </div>
		  <div class="product info detailed">
		     
		  </div>
		</div>
		<section class="upsell" data-mage-init='{"relatedProducts":{"relatedCheckbox":".related.checkbox"}}' data-limit="0" data-shuffle="0">
		  <div class="title-product-heading upsell-heading">
			<h2><span>You might also like</span></h2>
		  </div>
		  <div class="upsel-content" aria-labelledby="block-upsell-heading">
			<div id="upsell_product_slider" class="owl-carousel">
			 <?php 			
				foreach($productList as $productList){
				$catD=getCategory($productList->category_id);
				$subCatD=getCategory($productList->sub_category_id);
				$productImage=getProductImage($productList->id,$limit=1);											
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
					  <a href="<?=base_url('product/product_details/?pro_id='.$productList->id); ?>" class="product photo product-item-photo" tabindex="-1">
					  <span class="image0 image-switch">
					  <span class="product-image-container"
						style="width:500px;">
					  <span class="product-image-wrapper"
						style="padding-bottom: 100%;">
					  <img class="product-image-photo"
						src="<?=base_url()?><?=$pro_file?>"
						width="500"
						height="500"
						alt="<?=$productList->product_name?>"/></span>
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
						alt="<?=$productList->product_name?>"/></span>
					  </span>
					  </span>
					  </a>
					</div>
					<div class="product-info  clearfix">
					  <div class="cate_name"> <a href="<?=base_url('product/products_list/?cat_id='.$productList->category_id); ?>" title='<?=$catD->name?>'><?=$catD->name?></a></div>
					  <h3 class="product-name">
						<a class="product-item-link" title="<?=$productList->product_name?>" href="<?=base_url('product/product_details/?pro_id='.$productList->id); ?>">
						 <?=$productList->product_name?></a>
					  </h3>
					  <p><?=truncate($productList->description, $length=70, $stopanywhere=false)?></p>
					  <div class="price-box price-final_price" data-role="priceBox" data-product-id="<?=$productList->id?>">
						<span class="price-container price-final_price tax weee"
						  >
						<span 
						  data-price-amount="<?=$productList->price?>"
						  data-price-type="finalPrice"
						  class="price-wrapper "
						  >
						<span class="price"><i class="fa fa-inr" aria-hidden="true"></i> <?=$productList->price?></span>    </span>
						</span>
					  </div>
					</div>
				  </div>
				</div>
			  </div>
			 <?php } ?>
			</div>
		  </div>
		</section>
		<script type="text/javascript">
		  require([
			'jquery',
			'mage/mage',
			'themevast/owl',
			'Magento_Catalog/product/view/validation',
			'priceBox'
		  ], function ($) {
			'use strict';
		  
			jQuery("#upsell_product_slider").owlCarousel({
				autoplay :true,
				items : 3,
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
					items:2,
				},
				991: {
					items: 4,
				},						
				1200: {
					items: 4,
				}
			 }
			});
			$("#upsell_product_slider .owl-stage-outer").hover(function(){
				  $(this).css("padding", "10px 10px 200px").css("margin", "-10px -10px -200px");
				  }, function(){
				  $(this).css("padding", "0").css("margin", "0");
			  });
			  
			  $('.qty-up').on('click',function(event){
				event.preventDefault();
				var input_ = $(this).closest('.info-qty').find('#qty');
				var qtyval = parseInt(input_.val(),10);
				qtyval=qtyval+1;
				input_.val(qtyval);
			});
			$('.qty-down').on('click',function(event){
				event.preventDefault();
				var input_ = $(this).closest('.info-qty').find('#qty');
				var qtyval = parseInt(input_.val(),10);
				qtyval=qtyval-1;
				if(qtyval>1){
					input_.val(qtyval);
				}else{
					qtyval=1;
					input_.val(qtyval);
				}
			});
			
			$('#product_addtocart_form').mage('validation', {
				radioCheckboxClosest: '.nested',
				submitHandler: function (form) {
				   $(form).submit();
					return false;
				}
			});
			
			$('#product_checkpin_form').mage('validation', {
				radioCheckboxClosest: '.nested',
				submitHandler: function (form) {
				   $(form).submit();
					return false;
				}
			});

             var dataPriceBoxSelector = '[data-role=priceBox]',
            dataProductIdSelector = '[data-product-id=<?=$productList->id?>]',
            priceBoxes = $(dataPriceBoxSelector + dataProductIdSelector);
			
			priceBoxes = priceBoxes.filter(function(index, elem){
				return !$(elem).find('.price-from').length;
			});

			//priceBoxes.priceBox({'priceConfig': {"productId":"<?=$productList->id?>","priceFormat":{"pattern":"$%s","precision":2,"requiredPrecision":2,"decimalSymbol":".","groupSymbol":",","groupLength":3,"integerRequired":1},"prices":{"oldPrice":{"amount":<?=$productList->price?>,"adjustments":[]},"basePrice":{"amount":<?=$productList->price?>,"adjustments":[]},"finalPrice":{"amount":<?=$productList->price?>,"adjustments":[]}},"idSuffix":"_clone","tierPrices":[],"calculationAlgorithm":"TOTAL_BASE_CALCULATION"}});			
		  });
		</script>
	  </div>
	  <input name="form_key" type="hidden" value="FQMAevpDnhnwqwwG" />
	  <div id="authenticationPopup" data-bind="scope:'authenticationPopup'" style="display: none;">
		
	  </div>
	 
	</div>
  </div>
</div>