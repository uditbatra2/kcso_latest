<?php 
if(!empty($productList) && count($productList)){	  
	foreach($productList as $key=>$productList){
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
  <li class="item col-sm-6 col-md-6 col-lg-4 col-xs-6<?=($key == 0)?' first':'';?>">
	<div class="product-info-grid product-item-info">
	  <div class="product-list-item">
		<div class="item-inner">
		  <div class="product-photo">
			<a href="<?=base_url('product/product_details/?pro_id='.$productList->id); ?>" class="product photo product-item-photo" tabindex="-1">
			<span class="image0 image-switch">
			<span class="product-image-container"style="width:528px;">
			<span class="product-image-wrapper"style="padding-bottom: 79.545454545455%;">
			<img class="product-image-photo" src="<?=base_url()?><?=$pro_file?>"width="528"height="420"alt="<?=$productList->product_name?>"/></span>
			</span>
			</span>
			<span class="image1 image-switch">                                    
			<span class="product-image-container"
			  style="width:528px;">
			<span class="product-image-wrapper"
			  style="padding-bottom: 79.545454545455%;">
			<img class="product-image-photo" src="<?=base_url()?><?=$pro_file?>"width="528"height="420"alt="<?=$productList->product_name?>"/></span></span>
			</span>
			</a>
			<?php if(isset($productList->is_new) && $productList->is_new == 1){?>
			<div class="new-sale-label">
			  <span class="label-product label-new">New</span>
			</div>
			<?php } ?>
		  </div>
		  <div class="product-info  clearfix">
			<div class="cate_name">
			  <a href="<?=base_url('product/products_list/?sub_cat_id='.$productList->sub_category_id); ?>" title='<?=$subCatD->name?>'><?=$subCatD->name?></a>
			</div>
			<h3 class="product-name">
			  <a href="<?=base_url('product/product_details/?pro_id='.$productList->id); ?>">
			  <?=$productList->product_name?>                          
			  </a>
			</h3>
			<p><?=truncate($productList->description, $length=60, $stopanywhere=false)?></p>
			<div class="price-box price-final_price" data-role="priceBox" data-product-id="<?=$productList->id?>">
			  <span class="price-container price-final_price tax weee">
			  <span data-price-amount="<?=$productList->id?>" data-price-type="finalPrice" class="price-wrapper">
			  <span class="price"><i class="fa fa-inr" aria-hidden="true"></i>&nbsp;<?=$productList->price?></span> </span>
			  </span>
			</div>
		  </div>
		  <div class="actions clearfix">
			<div class="actions-primary">
			  <?php
				$form_attribute=array(
						'method'=>"post",
						'novalidate' => 'novalidate',
						'data-role' => "tocart-form",
						);
				$hidden = array('action' => 'cartItemForm');
				//Form Open
				echo form_open('cart/add_cart_item',$form_attribute,$hidden);
				?>
			  <input type="hidden" name="product" value="<?=$productList->id?>">
			  <input type="hidden" name="qty" value="1">
			  <input name="form_key" type="hidden" value="MqMCYOcohEpzie68" /> 
			  <button class="btn btn-add-to-cart" type="submit" data-toggle="tooltip" title="Add to Cart">                                               
			  <span>Add to Cart</span>
			  </button>
			  <?php 
				// Form Close
				echo form_close(); ?>
			</div>
			<ul class="add-to-links clearfix">
			  <li class="wishlist">
				<a href="<?=base_url("user/wishlist/?do=add-wishlist&wishlist_id=".$productList->id); ?>" data-toggle="tooltip" title="Add to Wishlist" aria-label="Add to Wishlist" data-action="add-to-wishlist" role="button">
				Add to Wishlist                                            
				</a>
			  </li>
			</ul>
		  </div>
		</div>
	  </div>
  </li>
<?php }}else{ ?>
	<div class="clearfix"> </div>
	<div class="col-md-12 text-center">
	<div class="no_records">No Products Found</div>
	</div><div class="clearfix"> </div>
<?php } ?>