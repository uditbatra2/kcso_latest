<div class="col-xs-12 col-sm-4 col-md-3 col-lg-3 col-sm-pull-8 col-md-pull-9 col-lg-pull-9" style="display:block;">
		   <div class="sidebar sidebar-main-1">
			  <div class="block account-nav">
				 <div class="title">
					<strong>Dashboard</strong>
				 </div>
				 <div class="content">
					<nav class="account-nav">
					   <ul class="nav items">
						  <li class="nav item account"><a href="<?=base_url("user/account"); ?>">Account Dashboard</a></li>
						  <li class="nav item account_edit"><a href="<?=base_url("user/account_edit"); ?>">Account Information</a></li>
						  <li class="nav item address"><a href="<?=base_url("user/address"); ?>">Address Book</a></li>
						  <li class="nav item my_orders order_view"><a href="<?=base_url("user/my_orders"); ?>">My Orders</a></li>
						  <!--<li class="nav item"><a href="products.php">My Downloadable Products</a></li>-->
						  <li class="nav item newsletter_manage"><a href="<?=base_url("user/newsletter_manage"); ?>">Newsletter Subscriptions</a></li>
						  <!--<li class="nav item"><a href="customer-review.php">My Product Reviews</a></li>-->
						  <li class="nav item wishlist"><a href="<?=base_url("user/wishlist"); ?>">Wishlist</a></li>
					   </ul>
					</nav>
				 </div>
			  </div>
		   </div>
		   <div class="sidebar sidebar-additional1">
			  <div class="block block-wishlist" data-bind="scope: 'wishlist'">
				 <div class="block-title">
					<strong>Account Settings</strong>
				 </div>
				 <div class="block-content">
					 </div>
				 <div class="content">
					<nav class="account-nav">
					   <ul class="nav items">
						  <li class="nav item change_password"><a href="<?=base_url("user/account_edit?changepass=1"); ?>">Change Password</a></li>
						  <li class="nav item"><a href="<?=base_url("user/logout"); ?>">Logout</a></li>
					   </ul>
					</nav>
				 </div>
				 </div>
			  </div>
		   </div>
		</div>
 </div>
</div>