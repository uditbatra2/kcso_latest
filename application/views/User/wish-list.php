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
    <span class="base" data-ui-id="page-title-wrapper">My Wish List</span>    
  </h1>
</div>
<div class="main-columns layout layout-2-col row">
<div class="col-main col-xs-12 col-sm-8 col-md-9 col-lg-9 col-sm-push-4 col-md-push-3 col-lg-push-3">
  <?php if($this->session->flashdata('product_cart_success')){ ?>
  <div class="message success empty close_alert">
    <div><?php echo $this->session->flashdata('product_cart_success'); ?></div>
  </div>
  <?php }else if($this->session->flashdata('product_cart_error')){  ?>
  <div class="message error empty close_alert">
    <div><?php echo $this->session->flashdata('product_cart_error'); ?></div>
  </div>
  <?php }?>
  <input name="form_key" type="hidden" value="C6GTLfbIPK75zXud">
  <div class="form-wishlist-items" id="wishlist-view-form">
    <?php if(!empty($wishListData) && count($wishListData) > 0){ ?>
    <input name="form_key" type="hidden" value="C6GTLfbIPK75zXud">                        
    <div class="products-grid wishlist">
      <ol class="product-items row">
        <?php foreach($wishListData as $wishListData){
          $productImage=getProductImage($wishListData->product_id,$limit=1);											
          $pro_file= './uploads/no-image100x100.jpg';
          $pro_original_file= './uploads/no-image400x400.jpg';
          if(count($productImage) > 0 && !empty($productImage)){
          	$profilename = 'uploads/product_images/'.$productImage[0]->images;
          	if (file_exists($profilename) && !empty($productImage[0]->images))
          	{
          		$pro_file='./uploads/product_images/medium/'.$productImage[0]->images;
          		$pro_original_file='./uploads/product_images/'.$productImage[0]->images;														
          	}
          }
           ?>
        <li data-row="product-item" class="products-item col-xs-6 col-sm-4" id="item_15">
          <div class="item-info">
            <a class="product-item-photo" href="<?=base_url('product/product_details/?pro_id='.$wishListData->product_id); ?>" title="<?=$wishListData->product_name?>">
            <span class="product-image-container" style="width:300px;">
            <span class="product-image-wrapper" style="padding-bottom: 100%;">
            <img class="product-image-photo" src="<?=base_url()?><?=$pro_file?>" width="300" height="300" alt="<?=$wishListData->product_name?>"></span>
            </span>
            </a>
            <strong class="product-item-name">
            <a href="<?=base_url('product/product_details/?pro_id='.$wishListData->product_id); ?>" title="<?=$wishListData->product_name?>" class="product-item-link">
            <?=$wishListData->product_name?>   </a>
            </strong>
            <div class="price-box price-final_price" data-role="priceBox" data-product-id="<?=$wishListData->product_id?>">
              <p class="price-as-configured">
                <span class="price-container price-final_price tax weee">
                <span data-price-amount="<?=$wishListData->price?>" data-price-type="finalPrice" class="price-wrapper ">
                <span class="price"><i class="fa fa-inr" aria-hidden="true"></i><?=$wishListData->price?></span></span>
                </span>
              </p>
            </div>
            <div class="product-item-inner">
              <?php if(isset($wishListData->stock_availability) && $wishListData->stock_availability == 1){?>
              <div class="box-tocart">
                <?php
                  $form_attribute=array(
                  		'name' => 'product_addtocart_form',
                  		'class' => 'form-horizontal',
                  		'method'=>"post",
                  		'id' => 'product_addtocart_form',
                  		'novalidate' => 'novalidate',
                  		);
                  $hidden = array('action' => 'productAddToCartFromWishlist','wishlist_id'=>$wishListData->id);
                  //Form Open
                  echo form_open('cart/add_cart_item',$form_attribute,$hidden);
                  ?>
                <input type="hidden" name="product" value="<?=$wishListData->product_id?>" />
                <fieldset class="fieldset">
                  <div class="field qty">
                    <label class="label" for="qty[15]"><span>Qty</span></label>
                    <div class="control">
                      <input type="number" data-role="qty" id="qty[<?=$wishListData->product_id?>]" class="input-text qty" data-validate="{'required-number':true,'validate-greater-than-zero':true}" name="qty" value="1">
                    </div>
                  </div>
                  <div class="product-item-actions">
                    <div class="actions-primary">
                      <button type="submit" title="Add to Cart" data-item-id="<?=$wishListData->id?>" class="action tocart primary">
                      <span>Add to Cart</span>
                      </button>
                    </div>
                  </div>
                </fieldset>
                <?php 
                  // Form Close
                  echo form_close(); ?>
              </div>
              <?php }else{ ?>
              <div class="message error empty">
                <div>Out Of Stock</div>
              </div>
              <?php } ?>
              <div class="product-item-actions">
                <a href="javascript:void(0);" data-role="remove" title="Remove Item" class="btn-remove action delete" data-item-id="<?=$wishListData->id?>">
                <span>Remove item</span>
                </a>
              </div>
            </div>
          </div>
        </li>
        <?php } ?>
      </ol>
    </div>
    <?php }else{ ?>
    <div class="message info empty"><span>You have no items in your wish list.</span></div>
    <div class="actions-toolbar">
      <div class="primary">
      </div>
      <div class="secondary">
        <a href="<?=base_url("user/account"); ?>" class="action back">
        <span>Back</span>
        </a>
      </div>
    </div>
    <?php } ?>
    <script>
      require([
      		'jquery',
      		'mage/mage',
      		'quickview/cloudzoom',
      		'Magento_Catalog/product/view/validation',
      	], function ($) {					
      		$('#product_addtocart_form').mage('validation', {
      		radioCheckboxClosest: '.nested',
      		submitHandler: function (form) {
      		   $(form).submit();
      			return false;
      		}
      		});
      
                       $(".delete").click(function(){
      			var wishlistId = $(this).attr("data-item-id");
      			//alert(wishlistId);
      			window.location.href="<?=base_url("user/delete_wishlist_item/?wishlist_id=")?>"+wishlistId;
      		});					
      		
      	});
       
    </script>
  </div>
</div>