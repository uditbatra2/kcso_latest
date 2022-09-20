
<!-- Footer Start -->
<!--<section id="corporate-footer"> 
<div class="container"> 
<div class="row"> 
<div class="col-sm-6 corporate-footer-left"> 
<p>&#169;2018 Brijwasi. All Rights Reserved.</p>
</div>
<div class="col-sm-6 corporate-footer-right"> 
<p class="designBy">Designed by <a href="http://clorent.com/" target="_blank">Clorent Technologies</a></p>
</div>
</div>
</div>
</section> -->
<!--/ End Footer -->

</div>
    <div class="sidebar-overlay" data-reff=""></div>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/popper.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?=base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="<?=base_url(); ?>assets/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/jquery.slimscroll.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/select2.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/moment.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/plugins/morris/morris.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/plugins/raphael/raphael-min.js"></script>
	<script type="text/javascript" src="<?=base_url(); ?>assets/plugins/light-gallery/js/lightgallery-all.min.js"></script>
	<script type="text/javascript" src="<?=base_url(); ?>assets/plugins/summernote/dist/summernote-bs4.min.js"></script>
	<script type="text/javascript" src="<?=base_url(); ?>assets/js/jquery-confirm.min.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/app.js"></script>
    <script type="text/javascript" src="<?=base_url(); ?>assets/js/jquery.blockUI.js"></script>
	<script>
	$(".alert").delay(4000).fadeOut(200, function() {
		$(this).alert('close');
	});
	$("ul#menu li").click(function(){
		//$("ul#menu li").addClass("active").siblings().removeClass("active");
        //$("ul#menu li.submenu a").addClass("active subdrop").siblings().removeClass("active subdrop");
        //$("ul#menu li.submenu ul.list-unstyled li a").addClass("active").siblings().removeClass("active");		
	});
	
	var path = location.pathname.split('?')[0];
	//alert(path);
	var start = path.lastIndexOf('/') + 1;
	//alert(start);
	var activeLink = path.substr(start);
	$("ul#menu li").removeClass('active');
	$("ul#menu li.submenu a").removeClass('active subdrop');
	$("ul#menu li.submenu ul.list-unstyled li a").removeClass('active subdrop');
	if(activeLink){
		var parent = $('ul#menu li.' + activeLink);
		var parent2 = $('ul.list-unstyled li a.' + activeLink);
		parent.addClass('active subdrop');
		parent2.addClass('active subdrop');
		//parent2.addClass('active');
	}
	//alert(activeLink);
	$('select').change(function(){
    if ($(this).val()!="")
		{
			$(this).valid();
		}
	});
	$(".lightgallery").lightGallery();
	</script>
</body>
</html>