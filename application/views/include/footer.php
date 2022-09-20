<footer class="page-footer">
  <div class="footer-container">
    <div class="container">
      <div class="footer-info-container">
        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-6">
            <div class="widget-ft widget-about">
              <div class="logo logo-ft">
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
                <a title="Brijwasi" href="<?=base_url()?>"><img src="<?=base_url()?><?=$sitel_file_logo?>" alt="Brijwasi" style="width: 50%;"/> </a>
              </div>
              <div class="widget-content">
                <div class="icon"><img src="<?=base_url(); ?>assets/pub/media/wysiwyg/call.png" alt="" /></div>
                <div class="info">
                 <!-- <p class="questions">Got Questions ? Call us 24/7!</p>-->
                  <p class="phone">Call Us: <?=getSiteSettingValue(13)?></p>
                  <p class="address"><?=getSiteSettingValue(7)?></p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
		  <?php $footerCategories=getAllCategories();?>
            <div class="widget-ft widget-categories-ft">
              <div class="widget-title">
                <h3>Find By Categories</h3>
              </div>
              <ul class="cat-list-ft">
			  <?php if(!empty($footerCategories) && count($footerCategories) > 0){ 
			  foreach($footerCategories as $footerCategories){?>
                <li><a title="<?=$footerCategories->name?>" href="<?=base_url('product/products_list/?cat_id='.$footerCategories->id); ?>"><?=$footerCategories->name?></a></li>
			  <?php } } ?>
              </ul>
               <ul class="pay-list">
              <h3>Secure Online Payments</h3>
              <li><a title="" href="https://play.google.com/store/apps/details?id=com.clorent.brijwasimobileapp" target="_blank"> <img src="<?=base_url(); ?>assets/pub/media/wysiwyg/secure-payment.png" alt="" /> </a></li>
            </ul>
            </div>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
            <div class="widget-ft widget-menu">
              <div class="widget-title">
                <h3>Customer Care</h3>
              </div>
              <ul>
               <!-- <li><a title="" href="#"> Contact us </a></li>
                <li><a title="" href="#"> Site Map </a></li>-->
                <li><a title="My Account" href="<?=base_url("user/account"); ?>"> My Account </a></li>
                <li><a title="Wish List" href="<?=base_url("user/wishlist"); ?>"> Wish List </a></li>
               <!-- <li><a title="" href="#"> Delivery Information </a></li>-->
                <li><a title="" href="#"> Privacy Policy </a></li>
                <li><a title="" href="#"> Terms &amp; Conditions </a></li>
              </ul>
            </div>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6">
            <div class="widget-ft widget-menu">
              <div class="widget-title">
                <h3>Follow US</h3>
              </div>
            </div>
            <div class="widget-ft widget-about">
              <ul class="social-list">
                <li class="social-icons-facebook"><a title="" href="<?=getSiteSettingValue(46)?>" target="_blank"> <i class="fa fa-facebook"></i> </a></li>
                <li class="social-icons-twitter"><a title="" href="<?=getSiteSettingValue(47)?>" target="_blank"> <i class="fa fa-twitter"></i> </a></li>
                <li class="social-icons-instagram"><a title="" href="<?=getSiteSettingValue(48)?>" target="_blank"> <i class="fa fa-instagram"></i> </a></li>
                <li class="social-icons-linkedin"><a href="<?=getSiteSettingValue(49)?>" target="_blank"><i class="fa fa-pinterest-p"></i></a></li>
              </ul>
            </div>
           
             <ul class="pay-list" sTYLE="margin-top: 40px !important;">
              <h3 style="margin-bottom: 20px;">Download App</h3>
              <li><a title="" href="https://play.google.com/store/apps/details?id=com.clorent.brijwasimobileapp" target="_blank"> <img src="<?=base_url(); ?>assets/pub/media/wysiwyg/play-store.png" alt="" /> </a></li>
            </ul>
          </div>
        </div>
      </div>
      <div class="appstore">
        <div class="row">
        </div>
      </div>
    </div>
    <div class="footer">
      <div class="container">
        <div class="footer-copyright">
          <small class="copyright">
          <span>© 2018 Kharya's Brijwasi SMB 75 LLP All Rights Reserved. || Developed by <a href="http://clorent.com/" target="_blank"><span style="color:#2d2d2d;font-weight: 600;">Clorent Technologies</span></a> </span>
          </small>
        </div>
        <div id="back-top"></div>
      </div>
    </div>
  </div>
</footer>
</div>
<script type="text/javascript">
require([
	'jquery',
	'mage/mage',
	'themevast/owl'
], function ($) {
	'use strict';
	setTimeout(function(){
	  $('.close_alert').remove();
	}, 5000);
	
	var path = location.pathname.split('?')[0];
	//alert(path);
	var start = path.lastIndexOf('/') + 1;
	//alert(start);
	var activeLink = path.substr(start);
	$("ul.items li").removeClass('current');
	if(activeLink){
		var parent = $('ul.items li.' + activeLink);
		parent.addClass('current');
	}
	//alert(activeLink);
});
</script>
</body>
</html>