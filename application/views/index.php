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
<div class="wrapper_slider slider_techno1-slider">
  <div class="container">
    <div class="owl-carousel">
      <?php if(!empty($sliderData) && count($sliderData) > 0){ 
	  foreach($sliderData as $sliderData){
        if(count($sliderData->slider_image) > 0 && !empty($sliderData->slider_image)){
        $sliderfilename = 'uploads/slider_images/'.$sliderData->slider_image;
        if (file_exists($sliderfilename) && !empty($sliderData->slider_image))
        {
        $slider_img='./uploads/slider_images/'.$sliderData->slider_image;														
        }
        }	
        ?>
      <div class="banner_item">
        <a href="<?=$sliderData->slider_url?>"><img src="<?=$slider_img?>" alt="<?=$sliderData->slider_name?>" /></a>			
      </div>
      <?php } } ?>
    </div>
  </div>
</div>
<script>
  require([
  	'jquery',
  	'mage/mage',
  	'themevast/owl'
  ], function ($) {
  	'use strict';
  
  	jQuery(".slider_techno1-slider .owl-carousel").owlCarousel(
  		{
  		items:1,
  		autoplay : 	true,
  		nav:false,
  		autoplayHoverPause:true,
  		dots:true,
  		dotsSpeed:500,
  		smartSpeed:500,
  		animateIn: 'fadeIn',
             animateOut: 'fadeOut',
             loop: true,
  		responsive:{
  			0:{
  				items:1,
  				/* nav:true */
  			},
  			1199:{
  				items:1,
  			},
  			980:{
  				items:1,
  				/* nav:true,
  				loop:false */
  			},
  			768:{
  				items:1,
  				/* nav:true,
  				loop:false */
  			}
  		}
  		
  		}
  	);
  });
</script>
<div class="top-content-home">
<?php if(!empty($bannerData) && count($bannerData) > 0){?>
  <div class="flat-row flat-banner-box">
    <div class="container">
      <div class="row">
        <div class="col-md-8">
          <div class="banner-box one-half">		    
            <div class="inner-box"><a title="<?=$bannerData[3]->banner_title?>" href="<?=$bannerData[3]->banner_url?>"> <img src="<?='./uploads/banner_images/'.$bannerData[3]->banner_image?>" alt="<?=$bannerData[3]->banner_title?>" /> </a></div>
            <div class="inner-box"><a title="<?=$bannerData[2]->banner_title?>" href="<?=$bannerData[2]->banner_url?>"><img src="<?='./uploads/banner_images/'.$bannerData[2]->banner_image?>" alt="<?=$bannerData[2]->banner_title?>" /> </a></div>
            <div class="clearfix"></div>
          </div>
          <div class="banner-box">
            <div class="inner-box"><a title="<?=$bannerData[1]->banner_title?>" href="<?=$bannerData[1]->banner_url?>"><img src="<?='./uploads/banner_images/'.$bannerData[1]->banner_image?>" alt="<?=$bannerData[1]->banner_title?>" /> </a></div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="banner-box">
            <div class="inner-box"><a title="<?=$bannerData[0]->banner_title?>" href="<?=$bannerData[0]->banner_url?>"><img src="<?='./uploads/banner_images/'.$bannerData[0]->banner_image?>" alt="<?=$bannerData[0]->banner_title?>" /> </a></div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php } ?>
  <!-- new arivalls-->
  <div class="row-content">
    <div class="container">
      <div class="newproduct">
        <div class="title-product-heading title-new-heading">
          <h2>New Arrivals</h2>
        </div>
        <div class="owl-carousel">
		<?php if(!empty($newArrivalsData) && count($newArrivalsData) > 0){   
		$count = 0; foreach($newArrivalsData as $newArrivalsData){
				$catD=getCategory($newArrivalsData->category_id);
				$productImage=getProductImage($newArrivalsData->id,$limit=1);											
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
				$count++;
			?>
			  <?php if($count % 2 == 0){?>
				<div class="item-inner clearfix">
			  <?php }else{ ?>
					<div class='item product-item newproductslider-item clearfix'>
					<div class="item-inner clearfix">
			  <?php } ?>
              <div class="product-photo">
                <a href="<?=base_url('product/product_details/?pro_id='.$newArrivalsData->id); ?>" class="product photo product-item-photo" tabindex="-1">
                <span class="image0 image-switch">
                <span class="product-image-container"
                  style="width:528px;">
                <span class="product-image-wrapper"
                  style="padding-bottom: 79.545454545455%;">
                <img class="product-image-photo"
                  src="<?=$pro_file?>"
                  width="528"
                  height="420"
                  alt="<?=$newArrivalsData->product_name?>"/></span>
                </span>
                </span>
                <span class="image1 image-switch">
                <span class="product-image-container"
                  style="width:528px;">
                <span class="product-image-wrapper"
                  style="padding-bottom: 79.545454545455%;">
                <img class="product-image-photo"
                  src="<?=$pro_file?>"
                  width="528"
                  height="420"
                  alt="<?=$newArrivalsData->product_name?>"/></span>
                </span>
                </span>
                </a>
              </div>
              <div class="product-info">
                <div class="cate_name"><a href="<?=base_url('product/products_list/?cat_id='.$newArrivalsData->category_id); ?>" title='<?=$catD->name?>'><?=$catD->name?></a></div>
                <h3 class="product-name">
                  <a href="<?=base_url('product/product_details/?pro_id='.$newArrivalsData->id); ?>">
                  <?=$newArrivalsData->product_name?>									
				  </a>
                </h3>
				<p><?=truncate($newArrivalsData->description, $length=70, $stopanywhere=false)?></p>
                <div class="price-box price-final_price" data-role="priceBox" data-product-id="<?=$newArrivalsData->id?>">
                  <span class="special-price">
                  <span class="price-container price-final_price tax weee"
                    >
                  <span class="price-label"><?=$newArrivalsData->price?></span>
                  <span 
                    data-price-amount="<?=$newArrivalsData->price?>"
                    data-price-type="finalPrice"
                    class="price-wrapper "
                    >
                  <span class="price"><i class="fa fa-inr" aria-hidden="true"></i> <?=$newArrivalsData->price?></span>    </span>
                  </span>
                  </span>
                  <span class="old-price">
                  <span class="price-container price-final_price tax weee"
                    >
                  <span class="price-label"><?=$newArrivalsData->price?></span>
                 <span 
                    data-price-amount="<?=$newArrivalsData->price?>"
                    data-price-type="oldPrice"
                    class="price-wrapper "
                    >
                  <span class="price"><i class="fa fa-inr" aria-hidden="true"></i><?=$newArrivalsData->price?></span>    </span>
                  </span>
                  </span>
                  <!--<span class="product-sale">
                  -11%    </span>-->
                </div>
              </div>
              <div class="actions clearfix">
                <ul class="add-to-links">
                  <li class="wishlist">
                    <a href="<?=base_url("user/wishlist/?do=add-wishlist&wishlist_id=".$newArrivalsData->id); ?>"
                      class="action towishlist"
                      title="Add to Wish List"
                      aria-label="Add to Wish List"
                      role="button">
                    Wishlist														
					</a>
                  </li>
                </ul>
				<?php if($newArrivalsData->stock_availability == 1){?>
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
                    <input type="hidden" name="product" value="<?=$newArrivalsData->id?>">
                    <input type="hidden" name="qty" value="1">
                    <input name="form_key" type="hidden" value="bm69ui3ezBoX7K9C" />													
					<button type="submit"
                      title="Add to Cart"
                      class="btn btn-add-to-cart">
                    <span>Add to Cart</span>
                    </button>
                 <?php 
					// Form Close
					echo form_close(); ?>
                </div>
				<?php }else{ ?>
				<div class="actions-primary">
				 <span  class="btn btn-add-to-cart">Out Of Stock</span>
				 </div>
				<?php } ?>
              </div>            
			<?php if($count % 2 == 0){?>
				</div>
				</div>
			  <?php }else{ ?>
					</div>
			  <?php } ?>
		<?php } } ?>
        </div>
        <script type="text/x-magento-init">
          {
          	"[data-role=tocart-form], .form.map.checkout": {
          		"catalogAddToCart": {}
          	}
          }
        </script>
        <script type="text/javascript">
          require([
          	'jquery',
          	'mage/mage',
          	'themevast/owl'
          ], function ($) {
          	'use strict';
          
          	jQuery(".newproduct .owl-carousel").owlCarousel({
          		autoplay :false,
          		items : 6,
          		smartSpeed : 500,
          		dotsSpeed : 500,
          		rewindSpeed : 500,
          		nav : false,
          		autoplayHoverPause : true,
          		dots : true,
          		scrollPerPage:true,
          		margin: 0,
          		responsive: {
          		0: {
          			items: 1,
          		},
          		480: {
          			items:1,
          		},
          		768: {
          			items:2,
          		},
          		991: {
          			items:2,
          		},						
          		1200: {
          			items:3,
          		}
          	 }
          	});
          });
        </script>
      </div>
    </div>
  </div>
  <!-- End of New Arrival -->
  <!-- second bottom -->
  <div class="block-products">
    <div class="container">
      <div class="row">
	  <?php if(!empty($bestSellerData) && count($bestSellerData) > 0){?>
        <div class="col-md-4 col-sm-4">
          <div class="bestsellerproduct product-hover">
            <div class="title-product-heading title-bestseller-heading">
              <h2>BestSeller</h2>
            </div>
            <div class="bestseller-inner">
              <div class="owl-carousel">
                <div class='item bestseller-item clearfix'>
				<?php if(!empty($bestSellerData) && count($bestSellerData) > 0){			
			     foreach($bestSellerData as $bestSellerData){
					$productImage=getProductImage($bestSellerData->product_id,$limit=1);											
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
                  <div class="product-item">
                    <div class="item-inner">
                      <div class="product-photo">									         
                        <a href="<?=base_url('product/product_details/?pro_id='.$bestSellerData->product_id); ?>" class="product photo product-item-photo" tabindex="-1">
                        <span class="image0 image-switch">											
                        <span class="product-image-container"style="width:528px;"> 
                        <span class="product-image-wrapper"style="padding-bottom: 79.545454545455%;">
                        <img class="product-image-photo"src="<?=$pro_file?>"			  
                          width="528"
                          height="420"
                          alt="<?=$bestSellerData->product_name?>"/>
                        </span>
                        </span>
                        </span>
                        <span class="image1 image-switch">
                        <span class="product-image-container"style="width:528px;">  
                        <span class="product-image-wrapper"
                          style="padding-bottom: 79.545454545455%;">
                        <img class="product-image-photo"
                          src="<?=$pro_file?>"width="528" height="420" alt="<?=$bestSellerData->product_name?>"/>
                        </span>		 		
                        </span>
                        </span>
                        </a>
                      </div>
                      <div class="product-info clearfix">
                        <h3 class="product-name"><a href="<?=base_url('product/product_details/?pro_id='.$bestSellerData->product_id); ?>" style="font-size: 16px;"><?=$bestSellerData->product_name?></a></h3>
						<p><?=truncate($bestSellerData->description, $length=70, $stopanywhere=false)?></p>
                        <div class="price-box price-final_price">
                          <span class="special-price">
                          <span class="price-container price-final_price tax weee">
                          <span class="price-label"></span>
                          <span data-price-amount="<?=$bestSellerData->price?>"class="price-wrapper ">
                          <span class="price"><i class="fa fa-inr" aria-hidden="true" style="margin-top: 0px;"></i><?=$bestSellerData->price?></span></span>
                          </span>
                          </span>
                          <span class="old-price">
                          <span class="price-container price-final_price tax weee">
                          <span class="price-label"></span>
                          <span data-price-amount="<?=$bestSellerData->price?>"class="price-wrapper"><span class="price"><i class="fa fa-inr" aria-hidden="true" style="margin-top: 4px;padding: 0px 3px;"></i><?=$bestSellerData->price?></span></span>
                          </span>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
				<?php } } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
	  <?php } ?>
	   <?php if(!empty($featuredData) && count($featuredData) > 0){?>
        <div class="col-md-4 col-sm-4">
          <div class="featuredproducts product-hover">
            <div class="title-product-heading title-featured-heading">
              <h2>Featured</h2>
            </div>
            <div class="bestseller-inner">
              <div class="owl-carousel">
                <div class='item bestseller-item clearfix'>
				<?php if(!empty($featuredData) && count($featuredData) > 0){			
			     foreach($featuredData as $featuredData){
					$productImage=getProductImage($featuredData->id,$limit=1);											
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
                  <div class="product-item">
                    <div class="item-inner">
                      <div class="product-photo">
                        <a href="<?=base_url('product/product_details/?pro_id='.$featuredData->id); ?>" class="product photo product-item-photo" tabindex="-1">
                        <span class="image0 image-switch">											
                        <span class="product-image-container"style="width:528px;"> 
                        <span class="product-image-wrapper"style="padding-bottom: 79.545454545455%;">
                        <img class="product-image-photo"src="<?=$pro_file?>"			  
                          width="528"
                          height="420"
                          alt="<?=$featuredData->product_name?>"/>
                        </span>
                        </span>
                        </span>
                        <span class="image1 image-switch">
                        <span class="product-image-container"style="width:528px;">  
                        <span class="product-image-wrapper"
                          style="padding-bottom: 79.545454545455%;">
                        <img class="product-image-photo"
                          src="<?=$pro_file?>"width="528" height="420" alt="<?=$featuredData->product_name?>"/>
                        </span>		 		
                        </span>
                        </span>
                        </a>
                      </div>
                      <div class="product-info clearfix">
                        <h3 class="product-name"><a href="<?=base_url('product/product_details/?pro_id='.$featuredData->id); ?>" style="font-size: 16px;"><?=$featuredData->product_name?></a></h3>
                        <p><?=truncate($featuredData->description, $length=70, $stopanywhere=false)?></p>
                        <div class="price-box price-final_price">
                          <span class="special-price">
                          <span class="price-container price-final_price tax weee">
                          <span class="price-label"></span>
                          <span data-price-amount="<?=$featuredData->price?>"class="price-wrapper ">
                          <span class="price"><i class="fa fa-inr" aria-hidden="true" style="margin-top: 0px;;"></i> <?=$featuredData->price?></span></span>
                          </span>
                          </span>
                          <span class="old-price">
                          <span class="price-container price-final_price tax weee">
                          <span class="price-label"></span>
                          <span data-price-amount="<?=$featuredData->price?>"class="price-wrapper"><span class="price"><i class="fa fa-inr" aria-hidden="true" style="margin-top: 4px;padding: 0px 3px;"></i><?=$featuredData->price?></span></span>
                          </span>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
				<?php } } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
	   <?php } ?>
	   <?php if(!empty($latestProductData) && count($latestProductData) > 0){?>
        <div class="col-md-4 col-sm-4">
          <div class="tvrated">
            <div class="title-product-heading title-rated-heading">
              <h2>Latest Product</h2>
            </div>
            <div class="bestseller-inner">
              <div class="owl-carousel">
                <div class='item rated-item clearfix'>
				<?php if(!empty($latestProductData) && count($latestProductData) > 0){			
			     foreach($latestProductData as $latestProductData){
					$productImage=getProductImage($latestProductData->id,$limit=1);											
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
                  <div class="product-item">
                    <div class="item-inner">
                      <div class="product-photo">   
                        <a href="<?=base_url('product/product_details/?pro_id='.$latestProductData->id); ?>" class="product photo product-item-photo" tabindex="-1">
                        <span class="image0 image-switch">											
                        <span class="product-image-container"style="width:528px;"> 
                        <span class="product-image-wrapper"style="padding-bottom: 79.545454545455%;">
                        <img class="product-image-photo"src="<?=$pro_file?>"			  
                          width="528"
                          height="420"
                          alt="<?=$latestProductData->product_name?>"/>
                        </span>
                        </span>
                        </span>
                        <span class="image1 image-switch">
                        <span class="product-image-container"style="width:528px;">  
                        <span class="product-image-wrapper"
                          style="padding-bottom: 79.545454545455%;">
                        <img class="product-image-photo"
                          src="<?=$pro_file?>"width="528" height="420" alt="<?=$latestProductData->product_name?>"/>
                        </span>		 		
                        </span>
                        </span>
                        </a>
                      </div>
                      <div class="product-info clearfix">
                        <h3 class="product-name"><a href="<?=base_url('product/product_details/?pro_id='.$latestProductData->id); ?>" style="font-size: 16px;"><?=$latestProductData->product_name?></a></h3>
                       <p><?=truncate($latestProductData->description, $length=70, $stopanywhere=false)?></p>
                        <div class="price-box price-final_price">
                          <span class="special-price">
                          <span class="price-container price-final_price tax weee">
                          <span class="price-label"></span>
                          <span data-price-amount="<?=$latestProductData->price?>"class="price-wrapper ">
                          <span class="price"><i class="fa fa-inr" aria-hidden="true" style="margin-top: 0px;"></i> <?=$latestProductData->price?></span></span>
                          </span>
                          </span>
                          <span class="old-price">
                          <span class="price-container price-final_price tax weee">
                          <span class="price-label"></span>
                          <span data-price-amount="<?//=$latestProductData->price?>"class="price-wrapper"><span class="price"><i class="fa fa-inr" aria-hidden="true" style="margin-top: 4px;padding: 0px 3px;"></i><?=$latestProductData->price?></span></span>
                          </span>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
				<?php } } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
	   <?php } ?>
      </div>
    </div>
  </div>
  <!-- end secondv bottom-->
  <!-- bottom slider -->
  <?php //if(!empty($mostViewedProductData) && count($mostViewedProductData) > 0){?>
  <div class="most-view">
    <div class="container">
      <div class="mostviewed">
        <div class="title-product-heading title-mostviewed-heading">
          <h2>Recently Viewed</h2>
        </div>
        <div class="owl-carousel">
		 <?php
		// print_r($mostViewedProductData);
            if(!empty($mostViewedProductData) && count($mostViewedProductData) > 0){		 
			 foreach($mostViewedProductData as $mostViewedProductData){
			     
				$catD=getCategory($mostViewedProductData->category_id);
				$productImage=getProductImage($mostViewedProductData->id,$limit=1);											
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
          <div class='item mostviewed-item clearfix'>
            <div class="product-item ">
              <div class="item-inner">
                <div class="product-photo">
                  <a href="<?=base_url('product/product_details/?pro_id='.$mostViewedProductData->id); ?>" class="product photo product-item-photo" tabindex="-1">
                  <span class="image0 image-switch">										
                  <span class="product-image-container"style="width:528px;">
                  <span class="product-image-wrapper"style="padding-bottom: 79.545454545455%;">
                  <img class="product-image-photo"src="<?=$pro_file?>"width="528"height="420"alt="<?=$mostViewedProductData->product_name?>"/></span></span></span>
                  <span class="image1 image-switch">
                  <span class="product-image-container"style="width:528px;">
                  <span class="product-image-wrapper"style="padding-bottom: 79.545454545455%;"><img class="product-image-photo"src="<?=$pro_file?>"width="528"height="420"alt="<?=$mostViewedProductData->product_name?>"/></span></span></span></a>
                  <div class="actions clearfix">
                    <ul class="add-to-links">
                      <li>
                        <div class="actions-primary">
                          <form data-role="tocart-form" action="<?=base_url('cart/add_cart_item'); ?>" method="post">
                            <input type="hidden" name="product" value="36">
                            <input type="hidden" name="uenc" value="1">
                            <input name="form_key" type="hidden" value="bm69ui3ezBoX7K9C" />															
							<button type="submit" class="btn btn-add-to-cart tooltip-toggle" title="Add to Cart" >
                            <span class="tooltip-content">Add to Cart</span>
                            </button>
                          </form>
                        </div>
                      </li>
                      <li>
                        <a href="#"
                          class="action wishlist tooltip-toggle"
                          title="Add to Wish List"
                          aria-label="Add to Wish List"
                          role="button">
                        <span class="tooltip-content">Add Wishlist</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="product-info">
                  <div class="cate_name">
                    <a href="<?=base_url('product/products_list/?cat_id='.$mostViewedProductData->category_id); ?>" title='<?=$catD->name?>'><?=$catD->name?></a>
                  </div>
                  <h3 class="product-name">
                    <a href="<?=base_url('product/product_details/?pro_id='.$mostViewedProductData->id); ?>"><?=$mostViewedProductData->product_name?></a>
                  </h3>
                 <p><?=truncate($mostViewedProductData->description, $length=70, $stopanywhere=false)?></p>
                  <div class="price-box price-final_price" data-role="priceBox" data-product-id="<?=$mostViewedProductData->id?>">
                    <span class="special-price">
                    <span class="price-container price-final_price tax weee">
                    <span class="price-label"><?=$mostViewedProductData->product_name?></span>
                    <span data-price-amount="<?=$mostViewedProductData->price?>"data-price-type="finalPrice"class="price-wrapper">
                    <span class="price"><i class="fa fa-inr" aria-hidden="true"></i> <?=$mostViewedProductData->price?></span></span>
                    </span>
                    </span><span class="old-price">
                    <span class="price-container price-final_price tax weee">
                    <span class="price-label"><?=$mostViewedProductData->product_name?></span>
                    <!--<span data-price-amount="299"data-price-type="oldPrice"class="price-wrapper">
                    <span class="price"><i class="fa fa-inr" aria-hidden="true"></i> <?//=$mostViewedProductData->price?></span></span>--></span></span>
                    <!--<span class="product-sale">-11%</span>-->
                  </div>
                </div>
              </div>
            </div>
          </div>
		<?php } } ?>
          </div>
        </div>
        <script type="text/x-magento-init">
          {
          	"[data-role=tocart-form], .form.map.checkout": {
          		"catalogAddToCart": {}
          	}
          }
        </script>
        <script type="text/javascript">
          require([
          	'jquery',
          	'mage/mage',
          	'themevast/owl'
          ], function ($) {
          	'use strict';
          
          	jQuery(".mostviewed .owl-carousel").owlCarousel({
          		autoplay :true,
          		items : 5,
          		smartSpeed : 1500,
          		dotsSpeed : 1500,
          		rewindSpeed : 1500,
          		nav : true,
          		autoplayHoverPause : true,
          		dots : true,
          		scrollPerPage:true,
          		margin: 30,
          		responsive: {
          		0: {
          			items:1
          		},
          		480: {
          			items:2
          		},
          		768: {
          			items:3
          		},
          		991: {
          			items:3
          		},						
          		1200: {
          			items:5
          		}
          	 }
          	});
          });
        </script>
      </div>
    </div>
	<?php //} ?>
  </div>
  <!-- end bottom view -->
  <div class="flat-iconbox">
    <div class="container">
      <div class="row">
        <div class="col-md-3 col-sm-6">
          <div class="iconbox">
            <div class="box-header">
              <div class="image">
                <img src="assets/images/extra-img/car.png" alt="">
              </div>
              <div class="box-title">
                <h3>Shipping in Noida Only</h3>
              </div>
            </div>
            <div class="box-content">
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="iconbox">
            <div class="box-header">
              <div class="image">
                <img src="assets/images/extra-img/order.png" alt="">
              </div>
              <div class="box-title">
                <h3>Order Online Service</h3>
              </div>
            </div>
            <div class="box-content">
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="iconbox">
            <div class="box-header">
              <div class="image">
                <img src="assets/images/extra-img/payment.png" alt="">
              </div>
              <div class="box-title">
                <h3>Secure Online Payment</h3>
              </div>
            </div>
            <div class="box-content">
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="iconbox">
            <div class="box-header">
              <div class="image">
                <img src="assets/images/extra-img/return.png" alt="">
              </div>
              <div class="box-title">
                <h3>Cash on Delivery</h3>
              </div>
            </div>
            <div class="box-content">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>