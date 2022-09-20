<?php
  $q='';
  $cat_id=trim($this->input->get('cat_id', TRUE));
  $sub_cat_id=trim($this->input->get('sub_cat_id', TRUE));
  $price=trim($this->input->get('price', TRUE));
  $q=trim($this->input->get('q', TRUE));
  $product_list_order=trim($this->input->get('product_list_order', TRUE));
  $limiter=trim($this->input->get('limiter', TRUE));
  $url_query='sub_cat_id='.$sub_cat_id;
  $subCategories=array();
  //if(!empty($catDetails->parent_id) && $catDetails->parent_id > 0){
     $subCategories=getAllCategories($parent_id=0,$limit='');
  //}
  if(isset($cat_id) && !empty($cat_id)){
  	$url_query='cat_id='.$cat_id;
  	$q=trim($this->input->get('q', TRUE));
  	if(!empty($catDetails->id) && $catDetails->id > 0){
  		$subCategories=getAllCategories($parent_id=$catDetails->id,$limit='');
  	}
  }
  //echo $url_query;
  //echo "<pre>";print_r($subCategories);
  //echo "<pre>";print_r($productList);
  ?>
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
        <a href="<?=base_url()?>" title="Go to Home Page">Home</a>
      </li>
	  <?php if(!empty($catDetails) && count($catDetails) > 0){?>
      <li class="item category21">
        <strong><?=$catDetails->name?></strong>
      </li>
	  <?php if(isset($q) && !empty($q)){?>
	  <li class="item search">
			<strong>Search results for: '<?=$q?>'</strong>
	   </li>
	  <?php } ?>
	  <?php }else if(isset($q) && !empty($q)){ ?>
		 <li class="item search">
			<strong>Search results for: '<?=$q?>'</strong>
		</li>
	<?php }else{ ?>
	 <li class="item category21">
        <strong>Showing All Products</strong>
      </li>
	<?php } ?>
    </ul>
  </div>
</div>
<div id="maincontent" class="page-main container" style="display:block;">
<a id="contentarea" tabindex="-1"></a>
<div class="page messages">
  <div data-placeholder="messages"></div>
</div>
<div class="main-columns layout layout-2-col row">
  <div class="col-main col-xs-12 col-sm-8 col-md-9 col-lg-9 col-sm-push-4 col-md-push-3 col-lg-push-3">
  <?php if(!empty($productList) && count($productList) > 0){?>
    <div class="category-image">
      <?php	
        if(!empty($catDetails) && count($catDetails)>0){  
        $cat_file= '/uploads/no-image100x100.jpg';
			$cat_original_file= '/uploads/no-image400x400.jpg';
			if(count($catDetails->image) > 0 && !empty($catDetails->image)){
				$catfilename = 'uploads/category_images/'.$catDetails->image;
				if (file_exists($catfilename) && !empty($catDetails->image))
				{
					$cat_file='/uploads/category_images/large/'.$catDetails->image;
					$cat_original_file='/uploads/category_images/'.$catDetails->image;														
				}
			}				
			?>
			<img src="<?=base_url()?><?=$cat_original_file?>" alt="<?=$catDetails->name?>" title="<?=$catDetails->name?>" class="image" style="width:870px;height:180px;"/>
		<?php }else{?>
		   <img src="<?=base_url()?>assets/pub/media/catalog/category/inner-banner.png" alt="Smartphone" title="Smartphone" class="image" />			
		<?php } ?>
    </div>
    <input name="form_key" type="hidden" value="MqMCYOcohEpzie68" />
    <div class="categories-page">
	<?php if(!empty($catDetails) && count($catDetails) > 0){?>  
      <h1 class="category-title"><?=$catDetails->name?></h1>
	<?php } else if(isset($q) && !empty($q)){?>
	 <h1 class="category-title">Search results for: '<?=$q?>'</h1>
	<?php }else{ ?>
	 <h1 class="category-title">Showing All Products</h1>
	<?php } ?>
      <div class="top-toolbar">
        <div class="toolbar toolbar-products">
		<?php if((isset($q) && !empty($q)) || (isset($price) && !empty($price)) || (isset($limiter) && !empty($limiter)) || (isset($product_list_order) && !empty($product_list_order))){?>		
          <div class="modes">
		    <div class="block-actions filter-actions">
			<?php 
			    $url_q ='';
				$url_q .=(isset($cat_id) && $cat_id != '')?'cat_id='.$cat_id:'sub_cat_id='.$sub_cat_id;
				$url_ca=base_url('product/products_list/?'.$url_q);
			?>
             <a href="<?=$url_ca?>" class="action clear filter-clear"><span class="fa fa-times"> Clear All Filters</span></a>
			 </div>
           </div>
		<?php } ?>
          <div class="field limiter">
            <div class="control">
              <select id="limiter" data-role="limiter" class="limiter-options">
                <option value="12" <?=(isset($limiterkeyword) && !empty($limiterkeyword) && $limiterkeyword=='')?'selected':'selected="selected" ';?>>12 per page</option>
                <option value="16" <?=(isset($limiterkeyword) && !empty($limiterkeyword) && $limiterkeyword=='16')?'selected':'';?>>16 per page</option>
                <option value="302" <?=(isset($limiterkeyword) && !empty($limiterkeyword) && $limiterkeyword=='302')?'selected':'';?>>302 per page</option>
                <option value="all" <?=(isset($limiterkeyword) && !empty($limiterkeyword) && $limiterkeyword=='all')?'selected':'';?>>All per page</option>
              </select>
            </div>
          </div>
          <div class="toolbar-sorter sorter">
            <select id="sorter" data-role="sorter" class="sorter-options">
              <option value="position" <?=(isset($productlistorderkeyword) && !empty($productlistorderkeyword) && $productlistorderkeyword=='')?'selected':'selected="selected" ';?>>Sort by Position</option>
              <option value="product-name" <?=(isset($productlistorderkeyword) && !empty($productlistorderkeyword) && $productlistorderkeyword=='product-name')? 'selected':'';?>>Sort by Product Name</option>
              <option value="price-asc" <?=(isset($productlistorderkeyword) && !empty($productlistorderkeyword) && $productlistorderkeyword=='price-asc')? 'selected':'';?>>Sort by Price: Lowest to Highest</option>
              <option value="price-desc" <?=(isset($productlistorderkeyword) && !empty($productlistorderkeyword) && $productlistorderkeyword=='price-desc')? 'selected':'';?>>Sort by Price: Highest to Lowest</option>
            </select>
            <!--<a title="Set Descending Direction" href="#" class="action sorter-action sort-asc">
              <span>Set Descending Direction</span>
              </a>-->
          </div>
        </div>
      </div>
      <div class="grid product-grid">
        <ol class="row" id="resultData">
          <?php 	  
            foreach($productList as $key=>$productLists){
            $catD=getCategory($productLists->category_id);
            $subCatD=getCategory($productLists->sub_category_id);
            $productImage=getProductImage($productLists->id,$limit=1);											
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
                    <a href="<?=base_url('product/product_details/?pro_id='.$productLists->id); ?>" class="product photo product-item-photo" tabindex="-1">
                    <span class="image0 image-switch">
                    <span class="product-image-container"style="width:528px;">
                    <span class="product-image-wrapper"style="padding-bottom: 79.545454545455%;">
                    <img class="product-image-photo" src="<?=base_url()?><?=$pro_file?>"width="528"height="420"alt="<?=$productLists->product_name?>"/></span>
                    </span>
                    </span>
                    <span class="image1 image-switch">                                    
                    <span class="product-image-container"
                      style="width:528px;">
                    <span class="product-image-wrapper"
                      style="padding-bottom: 79.545454545455%;">
                    <img class="product-image-photo" src="<?=base_url()?><?=$pro_file?>"width="528"height="420"alt="<?=$productLists->product_name?>"/></span></span>
                    </span>
                    </a>
                    <?php if(isset($productLists->is_new) && $productLists->is_new == 1){?>
                    <div class="new-sale-label">
                      <span class="label-product label-new">New</span>
                    </div>
                    <?php } ?>
                  </div>
                  <div class="product-info  clearfix">
                    <div class="cate_name">
                      <a href="<?=base_url('product/products_list/?sub_cat_id='.$productLists->sub_category_id); ?>" title='<?=$subCatD->name?>'><?=$subCatD->name?></a>
                    </div>
                    <h3 class="product-name">
                      <a href="<?=base_url('product/product_details/?pro_id='.$productLists->id); ?>">
                      <?=$productLists->product_name?>                          
                      </a>
                    </h3>
                    <p><?=truncate($productLists->description, $length=60, $stopanywhere=false)?></p>
                    <div class="price-box price-final_price" data-role="priceBox" data-product-id="<?=$productLists->id?>">
                      <span class="price-container price-final_price tax weee">
                      <span data-price-amount="<?=$productLists->id?>" data-price-type="finalPrice" class="price-wrapper">
                      <span class="price"><i class="fa fa-inr" aria-hidden="true"></i>&nbsp;<?=$productLists->price?></span> </span>
                      </span>
                    </div>
                  </div>
                  <div class="actions clearfix">
				  <?php if($productLists->stock_availability == 1){?>
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
                      <input type="hidden" name="product" value="<?=$productLists->id?>">
                      <input type="hidden" name="qty" value="1">
                      <input name="form_key" type="hidden" value="MqMCYOcohEpzie68" /> 
                      <button class="btn btn-add-to-cart" type="submit" data-toggle="tooltip" title="Add to Cart">                                               
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
                    <ul class="add-to-links clearfix">
                      <li class="wishlist">
                        <a href="<?=base_url("user/wishlist/?do=add-wishlist&wishlist_id=".$productLists->id); ?>" data-toggle="tooltip" title="Add to Wishlist" aria-label="Add to Wishlist" data-action="add-to-wishlist" role="button">
                        Add to Wishlist                                            
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
          </li>
          <?php } ?>		   
        </ol>
        </div>
        <!-- paging section -->
		<?php if(count($productList) >= 12){?>
			  <div class="toolbar toolbar-products">
			  </div>
			<div class="clearfix"> </div>
			<div class="col-md-12 text-center load-more">
			  <input type="hidden" id="result_no" value="12">
			  <input type="button" class="btn btn-add-to-cart" id="load" value="Load More Results">
			</div>
			<div class="clearfix"> </div>
		<?php } ?>
        <!-- end paging section -->
      </div>
	  <?php } else{?>
	  <?php if(isset($q) && !empty($q)){?>
		<div class="message notice">
			<div>Your search returned no results.</div>
		</div>
	  <?php }else{ ?>
        <div class="message notice">
			<div>No Product Found</div>
		</div>
	  <?php } } ?>
    </div>
    <!--left menu -->
    <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 col-sm-pull-8 col-md-pull-9 col-lg-pull-9">
      <div class="sidebar sidebar-main-1">
        <div class="block filter">
          <div class="block-title filter-title">
            <strong>Shop By</strong>			
          </div>
          <div class="block-content filter-content">
            <dl class="filter-options" id="narrow-by-list">
              <dt role="heading" aria-level="3" class="filter-options-title">Category
                <?php if(isset($cat_idkeyword) && !empty($cat_idkeyword) && $cat_idkeyword != 'all'){?>			  
				<div class="block-actions filter-actions text-right" style="margin-top: -10px;font-size: 14px;">
                    <?php 
						$url_q ='';
						$url_q .='cat_id=all';
						$url_q .=(isset($product_list_order) && $product_list_order != '')?'&product_list_order='.$product_list_order:"";
						$url_q .=(isset($price) && $price != '')?'&price='.$price:"";
						$url_q .=(isset($q) && $q != '')?'&q='.$q:"";
						$url_q .=(isset($limiter) && $limiter != '')?'&limiter='.$limiter:"";
						$url_c=base_url('product/products_list/?'.$url_q);
					?>
                <a href="<?=$url_c?>" class="action clear filter-clear"><span class="fa fa-times"> Clear Category Filter</span></a>
				</div>
                <?php } ?>				
			  </dt>
              <?php if(!empty($subCategories) && count($subCategories) > 0){?>
              <dd class="filter-options-content">
                <ol class="items">
                  <?php $url_q=''; foreach($subCategories as $subCategories){
                    $q=trim($this->input->get('q', TRUE));
                    $url_querys=(isset($subCategories->parent_id) && empty($subCategories->parent_id))?'cat_id='.$subCategories->id:'sub_cat_id='.$subCategories->id;
                    $price=(isset($price) && $price != '')?$price:"";
                    $url_q =(isset($subCategories->parent_id) && empty($subCategories->parent_id))?'cat_id='.$subCategories->id:'sub_cat_id='.$subCategories->id;
                    $url_q .=(isset($price) && $price != '')?'&price='.$price:"";
                    $url_q .=(isset($q) && $q != '')?'&q='.$q:"";
                    $url_q .=(isset($product_list_order) && $product_list_order != '')?'&product_list_order='.$product_list_order:"";
					$url_q .=(isset($limiter) && $limiter != '')?'&limiter='.$limiter:"";
                    $url=base_url('product/products_list/?'.$url_q);
                    ?> 
                  <li class="item">
                    <a href="<?=$url?>"><?=$subCategories->name?><span class="count"><?=getProductCountPrice($url_querys,$price,$q);?>
                    <span class="filter-count-label">items</span></span>
                    </a>
                  </li>
                  <?php } ?>
                </ol>
              </dd>
              <?php } ?>
              </dt>
              <dt role="heading" aria-level="3" class="filter-options-title">Price
			  <?php if(isset($pricekeyword) && !empty($pricekeyword)){?>
			  <div class="block-actions filter-actions text-right" style="margin-top: -10px;font-size: 14px;">
			   <?php 
					$url_q ='';
					$url_q .=(isset($product_list_order) && $product_list_order != '')?'&product_list_order='.$product_list_order:"";
					$url_q .=(isset($q) && $q != '')?'&q='.$q:"";
					$url_q .=(isset($limiter) && $limiter != '')?'&limiter='.$limiter:"";
					$url_p=base_url('product/products_list/?'.$url_query.$url_q);
				?>
                <a href="<?=$url_p?>" class="action clear filter-clear"><span class="fa fa-times"> Clear Price Filter</span></a>
			  </div>
              <?php } ?>			  
			  </dt>	
              <?php
                $url_q ='';
                $url_q .=(isset($product_list_order) && $product_list_order != '')?'&product_list_order='.$product_list_order:"";
                $url_q .=(isset($q) && $q != '')?'&q='.$q:"";
				$url_q .=(isset($limiter) && $limiter != '')?'&limiter='.$limiter:"";
                ?>
              <dd class="filter-options-content">
                <ol class="items">
                  <li class="item">
                    <a href="<?=base_url('product/products_list/?'.$url_query.'&price=1-250'.$url_q)?>">
                    <span class="price"><i class="fa fa-inr" aria-hidden="true" style="margin-top: 6px;"></i> 1.00</span> - <span class="price"><i class="fa fa-inr" aria-hidden="true" style="margin-top: 6px;"></i> 250.00</span><span class="count"><?=getProductCountPrice($url_query,'1-250',$q);?><span class="filter-count-label">items</span></span>
                    </a>
                  </li>
                  <li class="item">
                    <a href="<?=base_url('product/products_list/?'.$url_query.'&price=251-500'.$url_q)?>">
                    <span class="price"><i class="fa fa-inr" aria-hidden="true" style="margin-top: 6px;"></i> 251.00</span> - <span class="price"><i class="fa fa-inr" aria-hidden="true" style="margin-top: 6px;"></i> 500.00</span><span class="count"><?=getProductCountPrice($url_query,'251-500',$q);?><span class="filter-count-label">items</span></span>
                    </a>
                  </li>
                  <li class="item">
                    <a href="<?=base_url('product/products_list/?'.$url_query.'&price=501-750'.$url_q)?>">
                    <span class="price"><i class="fa fa-inr" aria-hidden="true" style="margin-top: 6px;"></i> 501.00</span> - <span class="price"><i class="fa fa-inr" aria-hidden="true" style="margin-top: 6px;"></i> 750.00</span><span class="count"><?=getProductCountPrice($url_query,'501-750',$q);?><span class="filter-count-label">item</span></span>
                    </a>
                  </li>
                  <li class="item">
                    <a href="<?=base_url('product/products_list/?'.$url_query.'&price=751-1000'.$url_q)?>">
                    <span class="price"><i class="fa fa-inr" aria-hidden="true" style="margin-top: 6px;"></i> 751.00</span> - <span class="price"><i class="fa fa-inr" aria-hidden="true" style="margin-top: 6px;"></i> 1000.00</span><span class="count"><?=getProductCountPrice($url_query,'751-1000',$q);?><span class="filter-count-label">item</span></span>
                    </a>
                  </li>
                  <li class="item">
                 
                  <li class="item">
                    <a href="<?=base_url('product/products_list/?'.$url_query.'&price=1000-'.$url_q)?>">
                    <span class="price"><i class="fa fa-inr" aria-hidden="true" style="margin-top: 6px;"></i> 1000.00</span> and above<span class="count"><?=getProductCountPrice($url_query,'1000-',$q);?><span class="filter-count-label">item</span></span>
                    </a>
                  </li>
                </ol>
              </dd>
              </dt>
            </dl>
          </div>
        </div>
      </div>
    </div>
    <!-- end left menu -->
  </div>
</div>
<script type="text/javascript">
  require([
  'jquery',
  ], function ($) {
  'use strict';
   $('#sorter').on('change',function(event){
  event.preventDefault();
  var input_ = $(this).val();
  if(input_ == 'position'){
     //alert(input_);
     location.href = "<?=base_url('product/products_list/?'.$url_query)?>";
  }else{
  	<?php
    $url_q ='';
    $url_q .=(isset($price) && $price != '')?'&price='.$price:"";
    $url_q .=(isset($q) && $q != '')?'&q='.$q:"";
	$url_q .=(isset($limiter) && $limiter != '')?'&limiter='.$limiter:"";
    $url=base_url('product/products_list/?'.$url_q);
    ?>
     location.href = "<?=base_url('product/products_list/?'.$url_query.'&product_list_order=')?>"+input_+"<?=$url_q?>";
  }
  });
  
  $('#limiter').on('change',function(event){
  event.preventDefault();
  var input_ = $(this).val();
   <?php  if(!empty($productList) && count($productList) > 0){?>
  var val = document.getElementById("result_no").value;
  document.getElementById("result_no").value = Number(val)+ Number(input_);
   <?php } ?>
  if(input_ == '12'){
     //alert(input_);
     location.href = "<?=base_url('product/products_list/?'.$url_query)?>";
  }else{
  	<?php
    $url_q ='';
    $url_q .=(isset($price) && $price != '')?'&price='.$price:"";
    $url_q .=(isset($q) && $q != '')?'&q='.$q:"";
	$url_q .=(isset($product_list_order) && $product_list_order != '')?'&product_list_order='.$product_list_order:"";
    //$url=base_url('product/products_list/?'.$url_q);
    ?>
     location.href = "<?=base_url('product/products_list/?'.$url_query.'&limiter=')?>"+input_+"<?=$url_q?>";
  }
 
  });
  <?php
  $limiter=(isset($limiter) && $limiter != '')?$limiter:0;
  ?>
   <?php  if(!empty($productList) && count($productList) > 0){?>
   if($('#result_no').length > 0){
	   var val = document.getElementById("result_no").value;
	   document.getElementById("result_no").value = Number(val)+ Number(<?=$limiter?>);
   }
   <?php } ?>
  
    $("#load").click(function(){
	   $(".no_records").html('');
	   loadmore();
	  });
  function loadmore()
  {
	var val = document.getElementById("result_no").value;
	<?php 
	$url_q ='';
	$url_q .=(isset($price) && $price != '')?'&price='.$price:"";
	$url_q .=(isset($q) && $q != '')?'&q='.$q:"";
	$url_q .=(isset($product_list_order) && $product_list_order != '')?'&product_list_order='.$product_list_order:"";
	$url_q .=(isset($limiter) && $limiter != '')?'&limiter='.$limiter:"";
	$url_q .='&request=pagination-product-search';
	?>
  var dataString = '<?=$url_query.$url_q?>'+'&getresult=' + val;
  /* var dialog = bootbox.dialog({
  message: '<p class="text-center"><img src="../img/logo-animation2.gif"></p><p class="text-center">Please wait while we do something...</p>',
  closeButton: false
  }); */
   $.ajax({
    type: "GET",
    url: BASE_URL+"ajax/products_list",
	data: dataString,
	cache: false,
	async: true,
	beforeSend: function(){	   
		$(".loading-mask").removeClass("hide");
		$("#load").val("Loading...");
	},
	success: function (response) {
	$(".no_records").html('');
	var content = document.getElementById("resultData");
	content.innerHTML = content.innerHTML+response;
	$("#load").val("Load More Results");
	// do something in the background
	//dialog.modal('hide');
	// We increase the value by 12 because we limit the results by 12
	document.getElementById("result_no").value = Number(val)+12;
   },
   complete: function(){
		$('.loading-mask').addClass("hide");
	}
   });
  }
  });
</script>