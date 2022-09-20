
    	<section class="banner inner-banner  position-relative z-0 d-flex align-items-center">
        	<div class="container-xl position-relative z-1">
            <div class="row align-items-center">
            	<div class="col-md-7 col-xl-7 mb-4 mb-md-0">
            	<h1 class="font-graphik-b"><?php echo $bannerDetails->banner_description; ?></h1>
                <p class="font-graphik">Get Awesome, In-Content White Hat Links Through Manual Blogger Outreach<br>
 – Done For You!</p>
 <div class="row">
 	<div class="col-md-6 col-xl-5">
    	<ul class="list-unstyled lh-lg mb-0">
            	<li><i class="far fa-check me-2 theme-color"></i> Approve domains before placement</li>
<li><i class="far fa-check me-2 theme-color"></i>U.S. and Canadian writers</li>
<li><i class="far fa-check me-2 theme-color"></i>Predictable turnaround</li>
            </ul>
    </div>
 	<div class="col-md-6">
    <ul class="list-unstyled lh-lg">
            	<li><i class="far fa-check me-2 theme-color"></i> Approve domains before placement</li>
<li><i class="far fa-check me-2 theme-color"></i>U.S. and Canadian writers</li>
<li><i class="far fa-check me-2 theme-color"></i>Predictable turnaround</li>
            </ul>
    </div>
 </div>
 <a href="" class="theme-color  fw-400 d-inline-flex align-items-center "><i class="fas fa-play-circle me-2 rounded-circle  p-2" style="color:#FA407E; box-shadow:0 0 0 9px #1473E6 inset; font-size:20px"></i>See how we work</a>
                </div>
            	<div class="col-md-5 col-xl-4 ms-auto">
                	<div class="shadow bg-white rounded-3 p-4 ">
                    	<h3 class="theme-color text-center h2">Tell us a few things</h3>
                        <p class="text-center text-muted mb-4">We’ll help you work through the contact details </p>
                      <?php include('include/header_form.php'); ?>  
                    </div>
                </div>
            </div>
            </div>
        </section>
        <section class="py-5">
        	<div class="container-xl">
            	<h2 class="text-center fw-500 opacity-75" style="font-size:1.5rem">Trusted By Over 500+ Companies Worldwide</h2>
                <div class="owl-carousel owl-theme logo-slider mt-4 position-relative px-xl-5">
                	<?PHP $logos = json_decode($trustedData->json_content); ?>
					<?php foreach($logos as $logo){
						?>
						<div class="item px-4"><img src="<?=base_url(); ?><?php echo ADMIN_SLUG; ?>/uploads/trusted_images/<?php echo $logo->image; ?>" alt="" class="img-fluid w-auto"></div>
						<?php
					}
					?>     </div>
            </div>
        </section>
<section class="py-5" style="background:url(<?=base_url(); ?>assets/images/circle.svg) no-repeat right -40% top / auto 80%">
	<div class="pt-5">
    	<div class="container-xl px-xl-5">
        	<div class="row px-xl-4">
            	<div class="col-xl-5">
              <h2>If you only read one section other than pricing, <span class="theme-color">make it this one:</span></h2>
                
                </div>
                <div class="col-xl-9">
                	<p class="lead">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley.</p>
                </div>
            </div>
            <div class="row mt-lg-5 px-xl-4">
            	<div class="col-md-6">
                	<div class="shadow rounded-3 p-4 bg-white px-xl-5 pt-xl-5 mb-4 gp">
                    	<h3 class="theme-color mb-4 d-inline-block border-white border-bottom">What this is</h3>
                        <p>We earn you links the ‘hard way’ but make it easy for you, with done-for-you prospecting, quality control, content creation, placement, and reporting.</p>
                        <ul class="list-unstyled lh-lg">
            	<li><i class="far fa-check me-2 theme-color"></i> Share your URLs and desired anchor text</li>
<li><i class="far fa-check me-2 theme-color"></i>See a curated list of vetted domains to approve or replace</li>
<li><i class="far fa-check me-2 theme-color"></i>Relax while we create content, conduct outreach, and report when it’s live</li>
            </ul>
                    </div>
                    <div class="shadow rounded-3 p-4  bg-white px-xl-5 pt-xl-5 mb-4 gp">
                    	<h3 class="theme-color mb-4 d-inline-block border-white border-bottom">Who it's best for</h3>
                        <p>Sites in ‘Basic’ and ‘Premium’ are safe, but still growing. They’re usually multi-topic. These links work best to diversify your profile without putting you at risk.</p>
                        <ul class="list-unstyled lh-lg">
            	<li><i class="far fa-check me-2 theme-color"></i> Share your URLs and desired anchor text</li>
<li><i class="far fa-check me-2 theme-color"></i>See a curated list of vetted domains to approve or replace</li>
<li><i class="far fa-check me-2 theme-color"></i>Relax while we create content, conduct outreach, and report when it’s live</li>
            </ul>
                    </div>
                </div>

            	<div class="col-md-6 pt-md-4">
                	<div class="shadow rounded-3 p-4  bg-white px-xl-5 pt-xl-5 mb-4 gp">
                    	<h3 class="theme-color mb-4 d-inline-block border-white border-bottom">What we promise</h3>
                        <p>We earn you links the ‘hard way’ but make it easy for you, with done-for-you prospecting, quality control, content creation, placement, and reporting.</p>
                        <ul class="list-unstyled lh-lg">
            	<li><i class="far fa-check me-2 theme-color"></i> Topically relevant, well-written articles</li>
<li><i class="far fa-check me-2 theme-color"></i>1-month turnaround</li>
<li><i class="far fa-check me-2 theme-color"></i>Guaranteed placement or replaced with an equal or better link</li>
            </ul>
                    </div>
                	<div class="shadow rounded-3 p-4  bg-white px-xl-5 pt-xl-5 mb-4 gp">
                    	<h3 class="theme-color mb-4 d-inline-block border-white border-bottom">You can expect</h3>
                        <p>We earn you links the ‘hard way’ but make it easy for you, with done-for-you prospecting, quality control, content creation, placement, and reporting.</p>
                        <ul class="list-unstyled lh-lg">
            	<li><i class="far fa-check me-2 theme-color"></i> Great communication</li>
<li><i class="far fa-check me-2 theme-color"></i>Well-written content  (sample)</li>
<li><i class="far fa-check me-2 theme-color"></i>Client-ready reporting  (sample)</li>
            </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

        </section>

<section class="py-5">
    	<div class="container-xl">
        	<div class="row">
            	<div class="col-12 col-xl-7 text-center mx-auto">
              <h2>What about <span class="theme-color">Quality Control?</span></h2>
              <p>Leading brands and agencies trust our thorough vetting process. We go beyond metrics alone to find sites with stable histories and quality content. Imagine being able to reverse engineer your competitors’ SEO, content marketing, and social media marketing strategy.</p>
                
                </div>
            </div>
            <div class="row align-items-center py-4 py-xl-5 border-bottom">
            	<div class="col-md-6 pe-xl-5">
                	<h3 class="theme-color">Domain & Hosting Details</h3>	
                    <p>As a first check, dangerous sites are eliminated by analyzing critical factors such as dangerous sites are eliminated: </p>
                     <ul class="list-unstyled lh-lg">
            	<li><i class="far fa-check me-2 theme-color"></i> DNS and A records</li>
<li><i class="far fa-check me-2 theme-color"></i>Use of SSL / https</li>
<li><i class="far fa-check me-2 theme-color"></i>Hosting country</li>
<li><i class="far fa-check me-2 theme-color"></i>Hyphen spam in domain name</li>
<li><i class="far fa-check me-2 theme-color"></i>English TLD & language</li>
<li><i class="far fa-check me-2 theme-color"></i>No poker, pills, adult sites</li>
            </ul>
                </div>
            	<div class="col-md-6">
                <img src="<?=base_url(); ?>assets/images/dns.png" alt="" class="img-fluid shadow">
                </div>
            </div>

            <div class="row align-items-center py-4 py-xl-5 border-bottom">
            	<div class="col-md-6 ps-xl-5 order-md-2">
                	<h3 class="theme-color">Core Metrics</h3>	
                    <p>Domains must then meet, at a bare minimum, the following observable metrics (and several others): </p>
                     <ul class="list-unstyled lh-lg">
            	<li><i class="far fa-check me-2 theme-color"></i>Indexed pages: 10+</li>
<li><i class="far fa-check me-2 theme-color"></i>Organic Traffic: 100/mo+ (minimum)</li>
<li><i class="far fa-check me-2 theme-color"></i>Ref. Domains: 50+</li>
<li><i class="far fa-check me-2 theme-color"></i>Average DR: 30</li>
<li><i class="far fa-check me-2 theme-color"></i>Average DA: 30</li>
<li><i class="far fa-check me-2 theme-color"></i>CF/TF: 10+</li>
<li><i class="far fa-check me-2 theme-color"></i>Traffic origin (50%+ from country of origin)</li>
            </ul>
                </div>
            	<div class="col-md-6 order-md-1">
                <img src="<?=base_url(); ?>assets/images/core.png" alt="" class="img-fluid shadow">
                </div>
            </div>
            <div class="row align-items-center py-4 py-xl-5 ">
            	<div class="col-md-6 pe-xl-5">
                	<h3 class="theme-color">Site History and Trends</h3>	
                    <p>To weed out gamed metrics, false authority, and sneaky spam, we also evaluate historical factors such as: </p>
                     <ul class="list-unstyled lh-lg">
            	<li><i class="far fa-check me-2 theme-color"></i> No penalizations or expirations</li>
<li><i class="far fa-check me-2 theme-color"></i>No wild swings in traffic or links</li>
<li><i class="far fa-check me-2 theme-color"></i>Traffic trending up</li>
<li><i class="far fa-check me-2 theme-color"></i>Ref.domains trending up</li>
<li><i class="far fa-check me-2 theme-color"></i>Ref. domain anchors</li>
<li><i class="far fa-check me-2 theme-color"></i>Ref. domain types (real, quality sites)</li>
            </ul>
                </div>
            	<div class="col-md-6">
                <img src="<?=base_url(); ?>assets/images/site.png" alt="" class="img-fluid shadow">
                </div>
            </div>
            <div class="d-flex justify-content-center pt-4">
                	<a href="" class="btn btn-outline-primary mx-2">Request a Free Quote</a>
                	<a href="" class="btn btn-primary mx-2">Talk to our Expert</a>
                </div>
        </div>

        </section>
        <section class="py-5" style="background:#F1F8FF">
        	<div class="container-xl text-center">
            	<h2 class="h1">How it Works</h2>
                <span class="display-6">Order, Approve, Report</span>
                <div class="row">
                	<div class="col-xl-9 mx-auto">
                <div class="row align-items-center text-start mt-5 works">
                	<div class="col-md-5 pe-xl-5 position-relative">
                    	<span class="vl position-absolute"></span>
                    	<img src="<?=base_url(); ?>assets/images/Updated-bg-banner..-3-768x644.png" alt="" class="img-fluid">
                    </div>
                    <div class="col-md-7 ps-xl-5 d-flex ">
                    	<span class="fw-700 h1 display-2 me-2 ts lh-1">1</span>
                        <div class="mt-3"><h3>Place Your Order</h3>
                        	<p>Share your desired URLs and the anchor text you’d like placed within each post, and we’ll get to work Share your desired URLs and the anchor text you’d like placed within each post, and we’ll get to work!!</p>
                        </div>
                    </div>
                </div>

                <div class="row align-items-center text-start  works">
                	<div class="col-md-5 ps-xl-5 order-md-2 position-relative">
                    	<span class="vl position-absolute"></span>
                    	<img src="<?=base_url(); ?>assets/images/Taining-768x768.png" alt="" class="img-fluid">
                    </div>
                    <div class="col-md-7 pe-xl-5 d-flex order-md-1">
                    	<span class="fw-700 h1 display-2 me-2 ms-md-2 ts lh-1 order-md-2">2</span>
                        <div class="order-md-1 text-md-end mt-3"><h3>Quality Control</h3>
                        	<p>We’ll send you a list of sites that fit your criteria to approve or veto. Then, our writers create content that fits your targets and each site’s specific publishing guidelines</p>
                        </div>
                    </div>
                </div>

                <div class="row align-items-center text-start  works">
                	<div class="col-md-5 pe-xl-5 position-relative">
                    	<span class="vl position-absolute"></span>
                    	<img src="<?=base_url(); ?>assets/images/resume-preparation-2-1-768x768.png" alt="" class="img-fluid">
                    </div>
                    <div class="col-md-7 ps-xl-5 d-flex">
                    	<span class="fw-700 h1 display-2 me-2 ts lh-1">3</span>
                        <div class="mt-3"><h3>Publish, Review, & Report</h3>
                        	<p>Our team will manually verify that your link is placed as-ordered, naturally within the content. Then, we’ll send you a white-label report (sample) with links to the guest posts you purchased.</p>
                        </div>
                    </div>
                </div>
                </div>
                </div>
<div class="d-flex justify-content-center pt-5 mt-xl-4">
                	<a href="" class="btn btn-outline-primary mx-2">Request a Free Quote</a>
                	<a href="" class="btn btn-primary mx-2">Talk to our Expert</a>
                </div>
                 </div>
        </section>
        <section class="py-5  position-relative ">
        	<div class="container-xl text-center position-relative">
            	<h2>Who Can Avail This Service?</h2>
                <p class="lead fw-400">We, at Outreach Monks, make sure that our Guest Post Service fits diverse requirements. </p>
                <div class="row text-start  py-5">
                	<div class="col-md-4 col-6 mb-4 ">
                    	<div class="shadow p-4 rounded-3 h-100">
                        <div class="d-flex align-items-center pb-3 mb-3 border-bottom">
                    	<img src="<?=base_url(); ?>assets/images/Government.png" alt="" class="img-fluid me-3">
                        <h3 class="">Agency Owners</h3>
                        </div>
                        <p class="fw-400">Scale your business while we fulfill your outreach orders and send you white labeled
reports</p>
</div>
                    </div>
                	<div class="col-md-4 col-6 mb-4 ">
                    	<div class="shadow p-4 rounded-3 h-100">
                        <div class="d-flex align-items-center pb-3 mb-3 border-bottom">
                    	<img src="<?=base_url(); ?>assets/images/Group 635x5.png" alt="" class="img-fluid me-3">
                        <h3 class="">Website Owners</h3>
                        </div>
                       <p class="fw-400">Ranking on Google is easier with our guest post services that deliver better rankings and ROI. </p>
                       </div>
                    </div>
                	<div class="col-md-4 col-6 mb-4">
                    	<div class="shadow p-4 rounded-3 h-100">
                        <div class="d-flex align-items-center pb-3 mb-3 border-bottom">
                    	<img src="<?=base_url(); ?>assets/images/Technical SEO.png" alt="" class="img-fluid me-3">
                        <h3 class="">Blogger</h3>
                        </div>
                        <p class="fw-400">Boost the authority and improve the positions of traffic driving keywords with our guest post service. </p>
                        </div>
                    </div>
                	<div class="col-md-4 col-6 mb-4 ">
                    	<div class="shadow p-4 rounded-3 h-100">
                        <div class="d-flex align-items-center pb-3 mb-3 border-bottom">
                    	<img src="<?=base_url(); ?>assets/images/Link Building.png" alt="" class="img-fluid me-3">
                        <h3 class="">Affiliate Marketer</h3>
                        </div>
                        <p class="fw-400">Get quick, evergreen organic traction through result-based blogger outreach services that can boost your revenue exponentially.</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-6 mb-4 ">
                    	<div class="shadow p-4 rounded-3 h-100">
                        <div class="d-flex align-items-center pb-3 mb-3 border-bottom">
                    	<img src="<?=base_url(); ?>assets/images/CPA Marketing.png" alt="" class="img-fluid me-3">
                        <h3 class="">Freelance SEO Consultant</h3>
                        </div>
                        <p class="fw-400">Why struggle with link building orders when we can help you execute it with our white label guest post services. </p>
                        </div>
                    </div>

                    <div class="col-md-4 col-6 mb-4 ">
                    	<div class="shadow p-4 rounded-3 h-100">
                        <div class="d-flex align-items-center pb-3 mb-3 border-bottom">
                    	<img src="<?=base_url(); ?>assets/images/Organic SEO.png" alt="" class="img-fluid me-3">
                        <h3 class="">SEO Manager</h3>
                        </div>
                       <p class="fw-400">In-house talent crunch shouldn’t deter you from taking up more link building orders. We can help you meet deadlines fast and easy!</p>
                       </div>
                    </div>
                </div>
<div class="d-flex justify-content-center ">
                	<a href="" class="btn btn-primary mx-2">Request a Free Quote</a>
                	<a href="" class="btn btn-outline-primary mx-2">Talk to our Expert</a>
                </div>
            </div>	
        </section>
        
        <section class="py-5 gpost-benefits" style="background:#1B78FE url(<?=base_url(); ?>assets/images/man.jpg) no-repeat top right / auto 100%">
        	<div class="container-xl text-white">
            	<div class="row">
                	<div class="col-xl-8">
            	<h2>Benefits of Guest Post Services</h2>
                <p>Considering hiring a professional Guest Posting Services/Blogger Outreach Services? Check out the benefits below and make the final decision:</p>
                <div class="row text-start">
                	<div class="col-md-6  my-4">
                    	<div class="d-flex align-items-center">
  <div class="flex-shrink-0">
    <img src="<?=base_url(); ?>assets/images/Group 3693.png" alt="..." class="img-fluid">
  </div>
  <div class="flex-grow-1 ms-3">
  <h3>Improves Your Ranking</h3>
    <p class="mb-0">Guest posting services will help you secure links from various popular blogs </p>
  </div>
</div>
                    </div>
                    <div class="col-md-6  my-4">
                    	<div class="d-flex align-items-center">
  <div class="flex-shrink-0">
    <img src="<?=base_url(); ?>assets/images/Group 3689.png" alt="..." class="img-fluid">
  </div>
  <div class="flex-grow-1 ms-3">
  <h3>Domain & Search Engine Authority</h3>
    <p class="mb-0">Blogger outreach services will help you build your domain name and search engine </p>
  </div>
</div>
                    </div>

                    <div class="col-md-6  my-4">
                    	<div class="d-flex align-items-center">
  <div class="flex-shrink-0">
    <img src="<?=base_url(); ?>assets/images/Group 3691.png" alt="..." class="img-fluid">
  </div>
  <div class="flex-grow-1 ms-3">
  <h3>Brand Awareness</h3>
    <p class="mb-0">Guest blog posting services will provide wide exposure to your brand by having it mentioned </p>
  </div>
</div>
                    </div>
                    <div class="col-md-6  my-4">
                    	<div class="d-flex align-items-center">
  <div class="flex-shrink-0">
    <img src="<?=base_url(); ?>assets/images/Group 3692.png" alt="..." class="img-fluid">
  </div>
  <div class="flex-grow-1 ms-3">
  <h3>Quality Traffic</h3>
    <p class="mb-0">With guest blogging services, you can get relevant traffic on your website </p>
  </div>
</div>
                    </div>
                    <div class="col-md-6  my-4">
                    	<div class="d-flex align-items-center">
  <div class="flex-shrink-0">
    <img src="<?=base_url(); ?>assets/images/Group 3694.png" alt="..." class="img-fluid">
  </div>
  <div class="flex-grow-1 ms-3">
  <h3>Link Building</h3>
    <p class="mb-0">Our Guest posting services help companies/agencies acquire backlinks through high quality link building</p>
  </div>
</div>
                    </div>
                    <div class="col-md-6  my-4">
                    	<div class="d-flex align-items-center">
  <div class="flex-shrink-0">
    <img src="<?=base_url(); ?>assets/images/Group 3695.png" alt="..." class="img-fluid">
  </div>
  <div class="flex-grow-1 ms-3">
  <h3>Credibility</h3>
    <p class="mb-0">Consumers like to check the online presence and portrayal of your brand. Blogger outreach services help</p>
  </div>
</div>
                    </div>

                </div>	
                    </div>
                </div>
            </div>
        </section>
        <section class="py-5" style="background:#F1F8FF url('<?=base_url(); ?>assets/images/Group 216.png') no-repeat left -100px bottom -90px / auto 100% ">
        	<div class="container py-xl-4">
            	<div class="row">
                	<div class="col-xl-9 ms-auto">
                    	<h2>Guest Posting Services in India</h2>
                        <p>No black-hat techniques or link spam. We strictly adhere to Google posting guidelines</p>
                        <div class="table-responsive-xl">
                        <table class="table table-light table-striped border text-center small gp-table">
                        	 <thead class="fw-400 theme-color">
    <tr>
      <th scope="col">DA/PA</th>
      <th scope="col">DR</th>
      <th scope="col">CF/TF</th>
      <th scope="col">Organic Traffic</th>
      <th scope="col">C Class IP</th>
      <th scope="col">Google Indexing</th>
      <th scope="col">Links</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td scope="row">170 +	</td>
      <td>60+</td>
      <td>50+</td>
      <td>5000+</td>
      <td>Verified</td>
      <td>Yes</td>
      <td>Dofollow / Nofollow</td>
    </tr>
    <tr>
      <td scope="row">170 +	</td>
      <td>60+</td>
      <td>50+</td>
      <td>5000+</td>
      <td>Verified</td>
      <td>Yes</td>
      <td>Dofollow / Nofollow</td>
    </tr>
    <tr>
      <td scope="row">170 +	</td>
      <td>60+</td>
      <td>50+</td>
      <td>5000+</td>
      <td>Verified</td>
      <td>Yes</td>
      <td>Dofollow / Nofollow</td>
    </tr>
  </tbody>
                        </table>
                        </div>
                        <p class="theme-color lead fw-400">If you have come this far, you should give Blogger Outreach’s Guest Posting Services a try</p>
                        <div class="d-flex justify-content-start mt-xl-4">
                	<a href="" class="btn btn-outline-primary me-2">Request a Free Quote</a>
                	<a href="" class="btn btn-primary mx-2">Talk to our Expert</a>
                </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="theme-bg py-5" style="background-image:url(<?=base_url(); ?>assets/images/curve-alt.png); background-repeat:no-repeat; background-size:100% auto; background-position:center top">
        	<div class="container-xl pt-5">
            	<div class="row pt-xl-5 justify-content-center">
                	<div class="col-lg-3">
                    	<h3 class="text-white lh-lg">100% expert created content.<br>
100% brand safety, guaranteed.<br>
100% organic traffic. </h3>
                    </div>
                    <div class="col-lg-8">
                    	<img src="<?=base_url(); ?>assets/images/stats-circle.png" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </section>
        <section class="py-5  text-center">
        	<div class="container-xl px-xl-5">
              <span class="d-block sub-heading">We work together across the globe</span>
              <h2>Case studies & stories</h2>
                <div class="owl-carousel owl-theme case-slider mt-4 position-relative ">
                	<div class="item px-xl-5 py-xl-4">
                <?php foreach($page_case as $postList){ ?>
                    	<div class="row g-0">
                       			<div class="col-md-6 p-3 p-xl-5">
                            <div class="d-flex align-items-center justify-content-between mb-4 mb-xl-4">
                            	<img src="<?=base_url(); ?><?php echo ADMIN_SLUG; ?>/uploads/case_study_images/<?php echo $postList->case_study_logo; ?>" alt="" class="w-auto img-fluid">
                                <a href="<?=base_url(); ?>case-studies/<?php echo $postList->case_study_slug; ?>" class="btn btn-primary">Read Case Study</a>
                            </div>
                            <p class="text-start"><?php echo $postList->case_study_long_content; ?> </p>
                            <div class="c-r shadow p-3 position-relative z-1 bg-white mt-4 text-center">
                            	<span class="text-center d-block text-secondary fw-500 opacity-75">CLIENT RESULTS</span>	
                                <div class="row mt-3">
                                	<div class="col-4 px-xl-5">
                                    	<span class="h2 d-block">+<span class="counter"><?php echo $postList->website_visitor; ?></span>%</span>
                                        <small class="text-secondary d-block opacity-75">Increase in website visitor</small>
                                    </div>
                                	<div class="col-4 px-xl-5 border-start" style="border-left-color:#dedfea !important">
                                    	<span class="h2 d-block ">+<span class="counter"><?php echo $postList->organic_search_traffic; ?></span>%</span>
                                        <small class="text-secondary d-block opacity-75">Increase in organic Search traficc</small>
                                    </div>
                                	<div class="col-4 px-xl-5 border-start" style="border-left-color:#dedfea !important">
                                    	<span class="h2 d-block">+<span class="counter"><?php echo $postList->conversation_rate; ?></span>%</span>
                                        <small class="text-secondary d-block opacity-75">Increase in the Conversation rate</small>
                                    </div>
                                </div>
                            </div>
                            	
                            </div>
                       		<div class="col-md-6  ps-xl-5">
							<img src="<?=base_url(); ?><?php echo ADMIN_SLUG; ?>/uploads/case_study_images/<?php echo $postList->clicks_impressions_seo_overview_image; ?>" alt="" class="img-fluid rounded-3 shadow">
                            </div> 
                        </div>
					<?php } ?>
                    </div>
                </div>
                <div class="d-flex justify-content-center pt-4">
                	<a href="<?php echo base_url(); ?>case-studies" class="btn btn-outline-primary mx-2">See All Case Study</a>
             	<a href="" class="btn btn-primary mx-2">Talk to our Expert</a>
                </div>
            </div>
        </section>
        
        <section class="py-5" style="background:#F1F8FF">
        	<div class="container-xl position-relative">
            <div class="row align-items-center">
            	<div class="col-12 col-xl-9 mx-auto">
                    <h2 class="text-center">Learn More About Guest Blogging</h2>
                    
                    <div class="accordion my-5" id="accordionExample">
  <div class="accordion-item mb-3 shadow-sm">
    <h2 class="accordion-header" id="headingOne">
      <button class="accordion-button shadow-none theme-color py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
         Why Should I opt for Guest Blogging?
 
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
      <div class="accordion-body small lh-lg">
       <p>Most guest posting providers build spammy backlinks from irrelevant websites, trying to fetch higher search engine rankings for their clients; which invites penalties. At ESEO, we only help you post high quality guest posts on niche websites in an organic fashion, which is under the ambit of Google guidelines. This way, you don’t have to fear about any penalties. To ease your concerns, we follow a definite checklist before any website can qualify for your guest blogging initiatives including filters for IP, domain age, page authority, minimum organic traffic parameters, google indexing status and more.

</p>
      </div>
    </div>
  </div>
  <div class="accordion-item mb-3 shadow-sm">
    <h2 class="accordion-header" id="headingTwo">
      <button class="accordion-button shadow-none theme-color py-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
       Is Guest Blogging a Safe Alternative?

      </button>
    </h2>
    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
      <div class="accordion-body small lh-lg">
         <p>Most guest posting providers build spammy backlinks from irrelevant websites, trying to fetch higher search engine rankings for their clients; which invites penalties. At ESEO, we only help you post high quality guest posts on niche websites in an organic fashion, which is under the ambit of Google guidelines. This way, you don’t have to fear about any penalties. To ease your concerns, we follow a definite checklist before any website can qualify for your guest blogging initiatives including filters for IP, domain age, page authority, minimum organic traffic parameters, google indexing status and more. </p>

      </div>
    </div>
  </div>
  <div class="accordion-item mb-3 shadow-sm">
    <h2 class="accordion-header" id="headingThree">
      <button class="accordion-button shadow-none theme-color py-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
        Is Guest Blogging the Only Way to Secure Backlinks?

      </button>
    </h2>
    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
      <div class="accordion-body small lh-lg">
         <p>No, there are multiple ways to secure quality backlinks but if you are to rank for your target keywords; Google prefers organic and white hat SEO methods. Of course you can go for other methods like influencer outreach, infographics and all; however these initiatives would take time to deliver SEO results. Given that your business shouldn't be on the backburner, you can approach webmasters with quality content that can add value to their audience, provide exposure to your business while fetching a backlink in return. </p>
      </div>
    </div>
  </div>

  <div class="accordion-item mb-3 shadow-sm">
    <h2 class="accordion-header" id="headingThree">
      <button class="accordion-button shadow-none theme-color py-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree1" aria-expanded="false" aria-controls="collapseThree">
        How Many Backlinks Per Month Should I Build with Guest Blogging?
      </button>
    </h2>
    <div id="collapseThree1" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
      <div class="accordion-body small lh-lg">
         <p>Every online business wants first page rankings for their business keywords that can help the leadbase grow. At ESEO, we prepare a separate plan for informational and transactional keywords and begin the outreach process with a fixed target in mind. Most search engines including Google promote diversity in the link farm and therefore, we carefully assess the number of backlinks that can prove to be a needle mover for your rankings. </p>

      </div>
    </div>
  </div>
  
  
   <div class="accordion-item mb-3 shadow-sm">
    <h2 class="accordion-header" id="headingThree">
      <button class="accordion-button shadow-none theme-color py-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree12" aria-expanded="false" aria-controls="collapseThree">
        Does ESEO Sticks to Google Guidelines?

      </button>
    </h2>
    <div id="collapseThree12" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
      <div class="accordion-body small lh-lg">
       <p> Yes. That’s why we run a manual outreach process with high quality content and try to convince webmasters about how a particular piece of content can impact their audience and they are more than happy to accept our submission, giving credit in the form of a backlink in return. And the best part, we always focus on building backlinks from the content body over the ones buried in the author bio. Although links in the author bio are fruitful to get you introduced to a new audience, text-based backlinks are always better when it comes to fetch keyword rankings. </p>

      </div>
    </div>
  </div>
  
   <div class="accordion-item mb-3 shadow-sm">
    <h2 class="accordion-header" id="headingThree13">
      <button class="accordion-button shadow-none theme-color py-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree13" aria-expanded="false" aria-controls="collapseThree">
        How Do You Find Out Niche Websites for My Business?

      </button>
    </h2>
    <div id="collapseThree13" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
      <div class="accordion-body small lh-lg">
        <p>We have a dedicated team of experts that does the job. Depending on your business and the type of customers it targets, our team fetches the websites where your customers are spending the most time on; which also happen to be the sites catering to a similar niche as yours. All in all, we use both fixed and customized templates to begin the outreach process. Even if a webmaster won’t respond in the first shot, we follow up with a set of follow up emails to gather their interest, add value in the form of data-driven content and garner backlinks. </p>

      </div>
    </div>
  </div>

</div>	
<h3 class="text-center">Have Questions for Our Team?</h3>
<div class="text-center">
	<a href="" class="btn btn-primary mt-4 ">Talk to Our Expert</a>
</div>
                </div>
                </div>
            </div>
        </section>
        
         <section   class="py-5 bg-light">
        		<div class="container-xl">
                	<div class="row align-items-center">
                    	<div class="col-md-5">
                    	<span class="d-block sub-heading">Our Blog</span>
                    	<h2>Our Latest Updates<br>Blog Post & News</h2>
                        </div>
                    	<div class="col-md-7">
                        	<p>Welcome to India's best and most reliable SEO blog. At eSEO blog, we share top blogs, long posts, and step-by-step guides on the varied facets of digital marketing domain such as SEO, Link Building, PPC, ORM, and SMO. So, keep reading our blogs to stay tuned. </p>
                        </div>	
                    </div>
                    <div class="row mt-5">
					<?php $k=0;foreach($page_blog as $postList){
							$content = $postList->post_long_content;
							$short_content=strip_tags($postList->post_long_content);
							if($k==0){
						?>
                    	<div class="col-md-6">
                        	<div class="img-box position-relative mb-5">
                        	<a href="<?=base_url(); ?>blog/<?php echo $postList->post_slug; ?>"><img src="<?=base_url(); ?><?php echo ADMIN_SLUG; ?>/uploads/post_images/large/<?php echo $postList->post_image; ?>" alt="" class="img-fluid"></a>
                            <small class="d-inline-flex align-items-center justify-content-center position-absolute shadow bg-white start-0 blog-date px-3 py-2 fw-600 ms-3"><?php $date=$postList->date_added; 
								$newDate = date("F d, Y", strtotime($date));
								?>
								
								<?php echo $newDate; ?></small>
                         </div>
                            <h3><?=$postList->post_title?></h3>
                            <p><?php echo substr($short_content, 0, 200); ?></p>
                        </div>
							<?php }else  { if($k==1){?>
                        <div class="col-md-6">
							<?php } ?>
                        	<div class="d-lg-flex align-items-center <?php if($k==1){echo 'mb-4 pb-3'; } ?>">
  <div class="flex-shrink-0 mb-3 mb-lg-0">
<a href="<?=base_url(); ?>blog/<?php echo $postList->post_slug; ?>"><img src="<?=base_url(); ?><?php echo ADMIN_SLUG; ?>/uploads/post_images/<?php echo $postList->post_image; ?>" alt="" class="img-fluid" style="max-width:260px"></a>
  </div>
  <div class="flex-grow-1 ms-lg-3">
  	<small class="d-block fw-600 mb-1"><?php $date=$postList->date_added; 
								$newDate = date("F d, Y", strtotime($date));
								?>
								
								<?php echo $newDate; ?></small>
  
	 <h4><?=$postList->post_title?></h4>
                           <?php echo substr($short_content, 0, 200); ?>

  </div>
</div>
                        
<?php if($k == 2){ ?>
                        </div>
<?php  } } $k++; } ?>
                    </div>
                <div class="d-flex pt-4">
                	<a href="<?php echo base_url(); ?>blog" class="btn btn-outline-primary mx-2">See All Blogs</a>
             	<a href="" class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#demo-modal">Free Audit Website</a>
                </div>
                </div>
        </section>
        <section class="py-5 theme-bg text-white position-relative">
        <div class="wave position-absolute end-0 top-0 w-100 h-100 overflow-hidden d-none d-xl-block">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
        	<div class="container-xl position-relative py-xl-4">
            	<div class="row align-items-center" >
                	<div class="col-md-8">
                      <h2>Improve your search ranking now!</h2>
                      <p class="fw-600">Donec vel sapien augue integer urna vel turpis cursus porta, mauris sed augue luctus dolor velna auctor congue tempus magna integer</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                    	<a href="" class="btn btn-white px-4 shadow">Get Started Now </a>
                    </div>
                </div>
            </div>	
        </section>
        <section class="position-relative">
        	        	<div class="map position-absolute top-0 start-0 w-100 h-100" style="background:url(<?=base_url(); ?>assets/images/map.jpg) no-repeat top left / cover">
            </div>

        	<div class="container-xl position-relative">
            	<div class="row">
                	<div class="col-md-6 col-xl-4 ms-auto">
                    	<div class="bg-white p-4 py-xl-5 form-box my-5">
                        	<h3 class="theme-color mb-4">Get in Touch</h3>
                       <?php include('include/footer_form.php'); ?>
                        </div>	
                    </div>
                </div>
            </div>
        </section>
   