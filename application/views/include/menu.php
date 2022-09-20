<style>
   @media (min-width: 768px){
   .tv-horizontal-menu .groupmenu li.level0 a.menu-link1:after {
   content: "";
   width: 1px;
   height: 14px;
   background: none !important;
   position: absolute;
   right: -1px;
   top: 11px;
   }
   }
</style>
<!--MENU-->
<div class="top-menu-wrapper">
   <div class="container">
      <div class="top-menu-inner">
         <div class="menu-vertical">
            <div class="box-menu">
               <div class="vertical-menu-title">
                  <div class="btn-mega">
                     <span></span>
                     All Categories							
                  </div>
               </div>
               <div class="tv-menu tv-vertical-menu tv-normal">
			   <?php $menuCategories=getAllCategories();?>
                  <ul class="groupmenu">
				  <?php if(!empty($menuCategories) && count($menuCategories) > 0){
					  $r=0; foreach($menuCategories as $menuCategories){
					      $menuSubCategories=getAllCategories($menuCategories->id);
						  $is_parent=(!empty($menuSubCategories) && count($menuSubCategories) > 0)?' parent':'';
					  ?>
                     <li class="item level0 level-top<?=$is_parent?>" >
                        <a class="menu-link" href="<?=base_url('product/products_list/?cat_id='.$menuCategories->id); ?>">
                        <span><span><?=$menuCategories->name?></span></span>
                        </a>
						<?php if(!empty($menuSubCategories) && count($menuSubCategories) > 0){?>
                        <ul class="groupmenu-drop">
                           <li class="item level1 text-content" >
                              <div class="groupmenu-drop-content groupmenu-width-6" style="">
                                 <div class="clearfix">
                                    <div class="col-sm-6 col-xs-12">
                                       <p class="groupdrop-title"><?=$menuCategories->name?></p>
										   <ul class="groupdrop-link">
										      <?php $c=0; foreach($menuSubCategories as $menuSubCategories){?>
											  <li class="item"><a href="<?=base_url('product/products_list/?sub_cat_id='.$menuSubCategories->id); ?>"><?=$menuSubCategories->name?></a></li>
											  <?php $c++; } ?>
										   </ul>
                                    </div>
                                 </div>
                              </div>
                           </li>
                        </ul>
						<?php } ?>
                     </li>
				  <?php $r++;} } ?>
                  </ul>
               </div>
            </div>
         </div>
         <div class="menu-horizontal">
            <div class="megamenu-top">
               <div class="menu-overlay"></div>
               <div class="megamenu-inner">
                  <h3 class="title-mobile-menu">Menu<span class="close-menu"><i class="pe-7s-close"></i></span></h3>
                  <div class="tv-menu tv-horizontal-menu   tv-translate" id="menu-5-5b4aedc27baea">
                     <ul class="groupmenu">
                        <li class="item level0  level-top parent" >
                           <a class="menu-link1 menu-link" href="<?=base_url(); ?>"> <span><span>Contact Us</span></span></a>
                        </li>
                        <li class="item level0  level-top" >
                           <a class="menu-link" href="<?=base_url(); ?>"> <span><span>Our Services</span></span></a>
                        </li>
						 <li class="item level0  level-top" >
                           <a class="menu-link" href="<?=base_url(); ?>"> <span><span>About Us</span></span></a>
                        </li>
                        <li class="item level0  level-top">
                           <a class="menu-link" href="<?=base_url(); ?>"><span><span>Home</span></span></a>
                        </li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!--END MENU SECTION -->
</div>