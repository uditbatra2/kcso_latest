<div class="sidebar" id="sidebar">
	<div class="sidebar-inner slimscroll">
		<div id="sidebar-menu" class="sidebar-menu">
			<ul id="menu">
				<!-- <li class="menu-title">Main</li>-->
				<li class="dashboard">
					<a href="<?= base_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Dashboard</a>
				</li>
				<!--<li class="submenu">
					<a href="#" class="sub_categories_list categories_list"><i class="fa fa-sitemap" aria-hidden="true"></i><span> Category Management</span><span class="menu-arrow"></span></a>
					<ul class="list-unstyled" style="display: none;">
						<li><a href="<?= base_url('admin/categories_list'); ?>" class="categories_list">Category List</a></li>
						<li><a href="<?= base_url('admin/sub_categories_list'); ?>" class="sub_categories_list">Sub category List</a></li>
						
					</ul>
				</li>-->
				<!--<li class="submenu">
					<a href="#" class="products_list product_reviews"><i class="fa fa-product-hunt" aria-hidden="true"></i><span>Product Management</span><span class="menu-arrow"></span></a>
					<ul class="list-unstyled" style="display: none;">
						<li><a href="<?= base_url('admin/products_list'); ?>" class="products_list">Manage Products</a></li>
                        <li><a href="<?= base_url('admin/product_reviews'); ?>" class="product_reviews">Manage Product Reviews</a></li>						
					</ul>
				</li>-->
				<!--<li class="submenu">
					<a href="#" class="orders_list order_view"><i class="fa fa-shopping-bag" aria-hidden="true"></i><span>Order Management</span><span class="menu-arrow"></span></a>
					<ul class="list-unstyled" style="display: none;">
						<li><a href="<?= base_url('admin/orders_list'); ?>" class="orders_list order_view">Manage Orders</a></li>       
					</ul>
				</li>-->
				<!--<li class="submenu">
					<a href="#" class="users_list"><i class="fa fa-users" aria-hidden="true"></i><span>User Management</span><span class="menu-arrow"></span></a>
					<ul class="list-unstyled" style="display: none;">
						<li><a href="<?= base_url('admin/users_list'); ?>" class="users_list">Manage Users</a></li>       
					</ul>
				</li>-->
				<!--<li class="submenu">
					<a href="#" class="locations_list"><i class="fa fa-location-arrow" aria-hidden="true"></i><span> Location Management</span><span class="menu-arrow"></span></a>
					<ul class="list-unstyled" style="display: none;">
						<li><a href="<? //=base_url('admin/locations_list'); 
										?>" class="locations_list">Manage Locations</a></li>       
					</ul>
				</li>-->
				<?php
				if (getUserCan('page_module', 'access_read')) {
				?>
					<li class="submenu">
						<a href="#" class="pages_list"><i class="fa fa-table" aria-hidden="true"></i><span>Page Management</span><span class="menu-arrow"></span></a>
						<ul class="list-unstyled" style="display: none;">
							<!---	<li><a href="<?= base_url('admin/pages_list'); ?>" class="pages_list">Manage Pages</a></li>-->
							<li><a href="<?= base_url('admin/home_page'); ?>" class="home_page">Manage Home Page</a></li>
							<li><a href="<?= base_url('admin/about_page'); ?>" class="about_page">Manage About Page</a></li>
							<li><a href="<?= base_url('admin/seo_page'); ?>" class="seo_page">Manage SEO Page</a></li>
							<li><a href="<?= base_url('admin/career_page'); ?>" class="career_page">Manage Career Page</a></li>
							<li><a href="<?= base_url('admin/sem_page'); ?>" class="sem_page">Manage SEM Page</a></li>
							<li><a href="<?= base_url('admin/guest_posting_page'); ?>" class="guest_posting_page">Manage Guest Posting Page</a></li>
							<li><a href="<?= base_url('admin/free_seo_audit_page'); ?>" class="free_seo_audit_page">Manage Free Seo Audit Page</a></li>
							<li><a href="<?= base_url('admin/content_writing_page'); ?>" class="content_writing_page">Manage Content Writing Page</a></li>
							<li><a href="<?= base_url('admin/pricing_page'); ?>" class="pricing_page">Manage Pricing Page</a></li>
							<li><a href="<?= base_url('admin/smo_page'); ?>" class="smo_page">Manage SMO Page</a></li>
						</ul>
					</li>
				<?php } 
				if (getUserCan('categories_module', 'access_read')) {
				?>
				<li class="submenu">
					<a href="#" class="categories_list"><i class="fa fa-tasks" aria-hidden="true"></i><span> Categories Management</span><span class="menu-arrow"></span></a>
					<ul class="list-unstyled" style="display: none;">
						<li><a href="<?= base_url('admin/categories_list'); ?>" class="categories_list">Manage Categories</a></li>
					</ul>
				</li>
				<?php } 
				if (getUserCan('posts_module', 'access_read')) {
				?>
				<li class="submenu">
					<a href="#" class="pages_list"><i class="fa fa-newspaper-o" aria-hidden="true"></i><span> Posts Management</span><span class="menu-arrow"></span></a>
					<ul class="list-unstyled" style="display: none;">
						<li><a href="<?= base_url('admin/posts_list'); ?>" class="posts_list">Manage Posts</a></li>
					</ul>
				</li>
				<?php } 
				if (getUserCan('case_studies_module', 'access_read')) {
				?>
				<li class="submenu">
					<a href="#" class="case_studies_list"><i class="fa fa-graduation-cap" aria-hidden="true"></i><span> Case Studies Management</span><span class="menu-arrow"></span></a>
					<ul class="list-unstyled" style="display: none;">
						<li><a href="<?= base_url('admin/case_studies_list'); ?>" class="case_studies_list">Manage Case Studies</a></li>
					</ul>
				</li>
				<?php } 
				if (getUserCan('testimonials_module', 'access_read')) {
				?>
				<li class="submenu">
					<a href="#" class="testimonials_list"><i class="fa fa-quote-left" aria-hidden="true"></i><span> Testimonials Management</span><span class="menu-arrow"></span></a>
					<ul class="list-unstyled" style="display: none;">
						<li><a href="<?= base_url('admin/testimonials_list'); ?>" class="testimonials_list">Manage Testimonials</a></li>
						<!--<li><a href="<?= base_url('admin/categories_list'); ?>" class="categories_list">Manage Categories</a></li>-->
					</ul>
				</li>
				<?php } 
				if (getUserCan('team_module', 'access_read')) {
				?>
				<li class="submenu">
					<a href="#" class="team_list"><i class="fa fa-users" aria-hidden="true"></i><span> Team Management</span><span class="menu-arrow"></span></a>
					<ul class="list-unstyled" style="display: none;">
						<li><a href="<?= base_url('admin/team_list'); ?>" class="team_list">Manage Team</a></li>
						<!--<li><a href="<?= base_url('admin/categories_list'); ?>" class="categories_list">Manage Categories</a></li>-->
					</ul>
				</li>
				<?php } 
				if (getUserCan('team_module', 'access_read')) {
				?>
				<li class="submenu">
					<a href="#" class="client_list"><i class="fa fa-users" aria-hidden="true"></i><span> Client Management</span><span class="menu-arrow"></span></a>
					<ul class="list-unstyled" style="display: none;">
						<li><a href="<?= base_url('admin/client_list'); ?>" class="client_list">Manage Client</a></li>
						<!--<li><a href="<?= base_url('admin/categories_list'); ?>" class="categories_list">Manage Categories</a></li>-->
					</ul>
				</li>
				<?php } 
				if (getUserCan('career_module', 'access_read')) {
				?>
				<li class="submenu">
					<a href="#" class="career_list"><i class="fa fa-book" aria-hidden="true"></i><span> Career Management</span><span class="menu-arrow"></span></a>
					<ul class="list-unstyled" style="display: none;">
						<li><a href="<?= base_url('admin/career_list'); ?>" class="career_list">Manage Career</a></li>
						<!--<li><a href="<?= base_url('admin/categories_list'); ?>" class="categories_list">Manage Categories</a></li>-->
					</ul>
				</li>
				<?php } 
				if (getUserCan('sliders_module', 'access_read')) {
				?>
				<li class="submenu">
					<a href="#" class="sliders_list banners_list"><i class="fa fa-picture-o" aria-hidden="true"></i><span> Slider Management</span><span class="menu-arrow"></span></a>
					<ul class="list-unstyled" style="display: none;">
						<li><a href="<?= base_url('admin/sliders_list'); ?>" class="sliders_list">Manage Slider Image</a></li>
						<li><a href="<?= base_url('admin/banners_list'); ?>" class="banners_list">Manage Banner Image</a></li>
					</ul>
				</li>
				<?php } 
				if (getUserCan('repeater_module', 'access_read')) {
				?>
				
				<li class="submenu">
					<a href="#" class="repeater_list"><i class="fa fa-repeat" aria-hidden="true"></i><span> Repeater Management</span><span class="menu-arrow"></span></a>
					<ul class="list-unstyled" style="display: none;">
						<li><a href="<?=base_url('admin/guest_post_repeater'); ?>" class="guest_post_repeater">Manage Guest Post</a></li>
											
					</ul>
					
				</li>
				<?php } 
				if (getUserCan('admin_users_module', 'access_read')) {
				?>
				<li class="submenu">
					<a href="#" class="admin_users_list"><i class="fa fa-laptop" aria-hidden="true"></i><span> Admin Management</span><span class="menu-arrow"></span></a>
					<ul class="list-unstyled" style="display: none;">
						<li><a href="<?= base_url('admin/admin_users_list'); ?>" class="admin_users_list">Manage Admin Users</a></li>
					</ul>
				</li>
				<!--<li class="submenu">
					<a href="#" class="emails_list sms_list"><i class="fa fa-envelope" aria-hidden="true"></i><span>Email & SMS Management</span><span class="menu-arrow"></span></a>
					<ul class="list-unstyled" style="display: none;">
						<li><a href="<?= base_url('admin/emails_list'); ?>" class="emails_list">Manage Email</a></li>
                        <li><a href="<?= base_url('admin/sms_list'); ?>" class="sms_list">Manage SMS</a></li>						
					</ul>
				</li>-->
				<?php } 
				if (getUserCan('general_module', 'access_read')) {
				?>
				<li class="submenu">
					<a href="#" class="theme_settings email_settings sms_settings payment_settings notifications"><i class="fa fa-cog" aria-hidden="true"></i><span>General Settings</span><span class="menu-arrow"></span></a>
					<ul class="list-unstyled" style="display: none;">
						<li><a href="<?= base_url('admin/theme_settings'); ?>" class="theme_settings">Theme Settings</a></li>
						<li><a href="<?= base_url('admin/email_settings'); ?>" class="email_settings">Email Settings</a></li>
						<!--<li><a href="<?= base_url('admin/sms_settings'); ?>" class="sms_settings">SMS Settings</a></li>
                        <li><a href="<?= base_url('admin/payment_settings'); ?>" class="payment_settings">Payment Settings</a></li>-->
						<!---   <li><a href="<?= base_url('admin/notifications'); ?>" class="notifications">Notifications</a></li> -->
					</ul>
				</li>
				<?php } ?>
				<!--<li class="news_letter_subscribers">
					<a href="<?= base_url('admin/news_letter_subscribers'); ?>"><i class="fa fa-newspaper-o" aria-hidden="true"></i> Newsletter Subscribers</a>
				</li>-->
			</ul>
		</div>
	</div>
</div>