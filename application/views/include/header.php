<!doctype html>
<html lang="en-US">
  <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
  <head>
    <script>
      var require = {
      	"baseUrl": "http://techno.themevast.com/pub/static/frontend/tv_themevast_package/techno1/en_US"
      };
    </script>
    <script>
      var BASE_URL='<?=base_url()?>';
    </script>
 <!-- <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-8835482891991659",
    enable_page_level_ads: true
  });
</script>-->
    <meta charset="utf-8"/>
    <meta name="description" content="<?php echo isset($site_description) ? strip_tags($site_description) : 'Eseo' ; ?>"/>
    <meta name="keywords" content="<?php echo isset($site_keyword) ? strip_tags($site_keyword) : 'Eseo' ; ?>"/>
    <meta name="robots" content="INDEX,FOLLOW"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
    <title><?php echo isset($title) ? strip_tags($title) : 'Eseo' ; ?></title>
    <link  rel="stylesheet" type="text/css"  media="all" href="<?=base_url(); ?>assets/css/custom-css.css" />
    <link  rel="stylesheet" type="text/css"  media="all" href="<?=base_url(); ?>assets/css/calendar.css" />
    <link  rel="stylesheet" type="text/css"  media="all" href="<?=base_url(); ?>assets/css/styles-m.css" />
    <link  rel="stylesheet" type="text/css"  media="all" href="<?=base_url(); ?>assets/css/styles-l.css" />
    <link  rel="stylesheet" type="text/css"  media="all" href="<?=base_url(); ?>assets/css/bootstrap.css" />
    <link  rel="stylesheet" type="text/css"  media="all" href="<?=base_url(); ?>assets/css/font-awesome.min.css" />
    <link  rel="stylesheet" type="text/css"  media="all" href="<?=base_url(); ?>assets/css/font.css" />
    <link  rel="stylesheet" type="text/css"  media="all" href="<?=base_url(); ?>assets/css/pe-icon-7-stroke.css" />
    <link  rel="stylesheet" type="text/css"  media="all" href="<?=base_url(); ?>assets/css/helper.css" />
    <link  rel="stylesheet" type="text/css"  media="all" href="<?=base_url(); ?>assets/css/themes.css" />
    <link  rel="stylesheet" type="text/css"  media="all" href="<?=base_url(); ?>assets/css/swatches.css" />
	<!--<link  rel="stylesheet" type="text/css"  media="all" href="<?//=base_url(); ?>assets/css/gallery.css" />-->
    <link  rel="stylesheet" type="text/css"  media="print" href="<?=base_url(); ?>assets/css/print.css" />
    <link  rel="icon" type="image/x-icon" href="<?=base_url(); ?>assets/images/favicon.ico" />
    <link  rel="shortcut icon" type="image/x-icon" href="<?=base_url(); ?>assets/images/favicon.ico" />
    <script  type="text/javascript"  src="<?=base_url(); ?>assets/js/require.js"></script>
    <script  type="text/javascript"  src="<?=base_url(); ?>assets/js/mixins.js"></script>
    <script  type="text/javascript"  src="<?=base_url(); ?>assets/js/requirejs-config.js"></script>
    <script  type="text/javascript"  src="<?=base_url(); ?>assets/js/timer.js"></script>
    <script  type="text/javascript"  src="<?=base_url(); ?>assets/js/jquery.bpopup.min.js"></script>
    <link  rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800" />
    <?=getSiteSettingValue(8)?>
  </head>
  <body data-container="body" data-mage-init='{"loaderAjax": {}, "loader": { "icon": "assets/pub/static/frontend/tv_themevast_package/techno1/en_US/images/loader-2.gif"}}' class="cms-fastshop cms-index-index page-layout-1column">
    <div data-role="loader" class="loading-mask hide">
        <div class="loader">
            <img src="<?=base_url(); ?>assets/pub/static/frontend/tv_themevast_package/techno1/en_US/images/loader-1.gif"
                 alt="Loading...">
        </div>
    </div>
    <div class="page-wrapper">
    <div class="header-container">
        
    <!--HEADER-->
    <div class="header">
      <div class="container">
        <div class="header-inner">
          <div class="row">
            <div class="col-sm-3 col-md-3 col-lg-3 box-logo">
              <div class="logo-menu">						
                <strong class="logo">
                <a href="<?=base_url()?>" title="Brijwasi">
                <?php
                  $imageValue=getSiteSettingValue(15);
                  $sitel_file_logo= '/uploads/no-image100x100.jpg';
                  if(count($imageValue) > 0 && !empty($imageValue)){
                  	$sitelfilename = 'uploads/site_images/'.$imageValue;
                  	if (file_exists($sitelfilename) && !empty($imageValue))
                  	{
                  		$sitel_file_logo='uploads/site_images/medium/'.$imageValue;														
                  	}
                  }									
                  ?>
                <img src="<?=base_url()?><?=$sitel_file_logo; ?>" alt="Brijwasi" width="201" height="30"/>
                </a>
                </strong>
              </div>
            </div>
            <div class="col-sm-5 col-md-6 col-lg-6 box-searchbar">
              <?php $searchCategories=getAllCategories();?>
              <div class="block-search">
                <div class="block-content">
                  <?php
                    $form_attribute=array(
                    		'name' => 'product-search',
                    		'class' => 'form-horizontal',
                    		'method'=>"get",
                    		'id' => 'product-search',
                    		'novalidate' => 'novalidate',
                    		);
                    $hidden = array('action' => 'productSearch');
                    //Form Open
                    echo form_open('product/products_list',$form_attribute,$hidden);
                    ?>
                  <div class="field search">
                    <div class="control">
                      <div class="seclect-cat">
                        <select name="cat_id" id="choose_category">
                          <option value="all">All Categories</option>
                          <?php if(!empty($searchCategories) && count($searchCategories) > 0){
                            foreach($searchCategories as $searchCategories){?>
                          <option value="<?=$searchCategories->id?>" <?=(isset($cat_idkeyword) && !empty($cat_idkeyword) && $cat_idkeyword==$searchCategories->id)? 'selected':'';?>><?=$searchCategories->name?></option>
                          <?php } } ?>
                        </select>
                      </div>
                      <div class="auto-search">
                        <input id="search"
                          data-mage-init='{"quickSearch":{
                          "formSelector":"#search_mini_form",
                          "url":"page/en/search/ajax/suggest/",
                          "destinationSelector":"#search_autocomplete"}
                          }'
                          type="text"
                          name="q"
                          value="<?=(isset($querykeyword) && !empty($querykeyword))? $querykeyword:'';?>"
                          placeholder="Search what you looking for ?"
                          class="input-text"
                          maxlength="128"
                          role="combobox"
                          aria-haspopup="false"
                          aria-autocomplete="both"
                          autocomplete="off"/>
                        <div id="search_autocomplete" class="search-autocomplete"></div>
                      </div>
                    </div>
                  </div>
                  <div class="actions">
                    <button type="submit"
                      title="Search"
                      class="btn-search">
                    <i class="flaticon-search"></i>
                    </button>
                  </div>
                  <?php 
                    // Form Close
                    echo form_close(); ?>
                </div>
              </div>
              <script type="text/javascript">
                require([
                	'jquery',
                	'themevast/choose'
                ], function ($) {
                	$("#choose_category").chosen();
                });
              </script>					
            </div>
            <div class="col-sm-4 col-md-3 col-lg-3 box-quick-access">
			<?php $cart_details=getTotalCartItems($session_id=$this->session->session_id);
				//echo "<pre>";print_r($cart_details);
			?>
              <div class="quick-access">
                <div class="link-wishlist">
                  <a href="<?=base_url("user/wishlist"); ?>"><span class="flaticon-like"></span></a>
                </div>
                <div data-block="minicart" class="minicart-wrapper">
                  <a class="action showcart" href="<?=base_url('cart'); ?>"
                    data-bind="scope: 'minicart_content'">
                    <i class="flaticon-shopping-cart"></i>
                    <span class="counter qty empty"
                      data-bind="css: { empty: !!getCartParam('summary_count') == false }, blockLoader: isLoading">
                      <!-- ko if: getCartParam('summary_count') -->
                      <span class="counter-number">
                        <!-- ko text: getCartParam('summary_count') --><!-- /ko -->
                      </span>
                      <!-- /ko -->
                      <!-- ko ifnot: getCartParam('summary_count') -->
                      <span class="counter-empty"><?=$cart_details['cart_quantity']?></span>
                      <!-- /ko -->
                    </span>
                   <span class="totals" data-bind="html: getCartParam('subtotal')"><!--<span class="price"><i class="fa fa-inr" aria-hidden="true"></i><?//=$cart_details['cart_total']?></span>--></span>
                  </a>
                  <div class="block block-minicart empty"
                    data-role="dropdownDialog"
                    data-mage-init='{"dropdownDialog":{
                    "appendTo":"[data-block=minicart]",
                    "triggerTarget":".showcart",
                    "timeout": "2000",
                    "closeOnMouseLeave": true,
                    "closeOnEscape": true,
                    "triggerClass":"active",
                    "parentClass":"active",
                    "buttons":[]}}'>
                    <div id="minicart-content-wrapper" data-bind="scope: 'minicart_content'"></div>
                  </div>
                </div>
                <a class="menu-bar mobile-navigation" href="javascript:void(0);"><i class="fa fa-bars" aria-hidden="true"></i></a>
               
               
                <div class="box-setting" style="display: inline-block;">
              <div class="links-account clearfix">
			  <?php if(isset($this->session->userdata('logged_in_brijwasi_user_data')['ID']) && !empty($this->session->userdata('logged_in_brijwasi_user_data')['ID'])){?>
                <div class="links-my-account flat-unstyled">
                  <a href="javascript:void(0);">Hello, <?=$this->session->userdata('logged_in_brijwasi_user_data')['USERNAME']?></a>
                  <ul class="sub-links unstyled">
				    <li class="level-1"><a href="<?=base_url("user/account"); ?>">My Account</a></li>
                    <li class="level-1"><a href="<?=base_url("user/wishlist"); ?>">Wishlist</a></li>
                    <li class="level-1"><a href="<?=base_url("cart"); ?>">My Cart</a></li>
                    <li class="level-1"><a href="<?=base_url("checkout"); ?>">Checkout</a></li>
					<li class="level-1"><a href="<?=base_url("user/logout"); ?>">Logout</a></li>
                  </ul>
                </div>
			  <?php }else{ ?>
			  <div class="links-my-account flat-unstyled">
                  <a href="<?=base_url("user/login"); ?>">Login & Register</a>
                  <ul class="sub-links unstyled">
                    <li class="level-1"><a href="<?=base_url("cart"); ?>">My Cart</a></li>
                  </ul>				  
                </div>			  
			  <?php } ?>
              </div>
            </div>
              </div>
        
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END HEADE-->