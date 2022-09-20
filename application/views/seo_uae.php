
    	<section class="banner inner-banner  position-relative z-0 d-flex align-items-center">
        	<div class="container-xl position-relative z-1">
            <div class="row align-items-center">
            	<div class="col-md-7 col-xl-7 mb-4 mb-md-0">
				<h1 class="font-graphik-b"><?php echo $bannerDetails->banner_description; ?></h1>
                <p class="font-graphik"><?php echo $bannerDetails->banner_sub_description; ?></p>
                <div class="d-flex pt-4 justify-content-start">
                	<a href="" class="btn btn-outline-primary me-2">Talk to our Expert</a>
                	<a href="" class="btn btn-primary mx-2" data-bs-toggle="modal" data-bs-target="#demo-modal">Free Website Audit</a>
                </div>
                </div>
            	<div class="col-md-5 col-xl-4 ms-auto">
                	<div class="shadow bg-white rounded-3 p-4 ">
                    	<h3 class="theme-color text-center h2">Tell us a few things</h3>
                        <p class="text-center text-muted mb-4">We’ll help you work through the contact details </p>
                <?php include('include/header_form.php'); ?>     </div>
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
					?>
                </div>
            </div>
        </section>
<section class="pb-5">
	<div style="background:#F9FAFB" class="pt-5">
    	<div class="container-xl text-center">
        	<div class="row">
            	<div class="col-xl-9 mx-auto">
              <h2>Our Process</h2>
              <p class="mb-0">Our 5 step SEO process ensures that your business gets the most out of our services.Learn about the client's business, website, 
and competitors in order to provide you clear documentation to be used throughout all other stages of the project.</p>
				 <div class="nav position-relative justify-content-between nav-pills process-tab z-0" id="v-pills-tab" role="tablist" aria-orientation="vertical">
    <button class="nav-link rounded-circle shadow-sm active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true"><img src="<?=base_url(); ?>assets/images/Group 2170.svg" alt="" class="img-fluid"></button>
    <button class="nav-link rounded-circle shadow-sm" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false"><img src="<?=base_url(); ?>assets/images/Group 2175.svg" alt="" class="img-fluid"></button>
    <button class="nav-link rounded-circle shadow-sm" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false"><img src="<?=base_url(); ?>assets/images/Group 2174.svg" alt="" class="img-fluid"></button>
    <button class="nav-link rounded-circle shadow-sm" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false"><img src="<?=base_url(); ?>assets/images/Path 5410.svg" alt="" class="img-fluid"></button>
    <button class="nav-link rounded-circle shadow-sm" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings1" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false"><img src="<?=base_url(); ?>assets/images/Group 2169.svg" alt="" class="img-fluid"></button>
  </div>
                
                </div>
            </div>
        </div>
    </div>
    	<div class="tab-content container-xl mt-5 pt-5" id="v-pills-tabContent">
    <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
    	<div class="row align-items-center px-xl-5">
    	<div class="col-md-7 mb-4 mb-md-0">
        	<h3>Custom Strategy Building</h3>
            <p>When we do in-depth research, we develop strategy as per your current standing in the market & competition’s fierceness</p>
            <ul class="list-unstyled lh-lg">
            	<li><i class="far fa-check me-2 theme-color"></i> <strong>Project brief:</strong> Define client information & business goals</li>
<li><i class="far fa-check me-2 theme-color"></i><strong>Keyword research:</strong> Define potential target keywords.</li>
<li><i class="far fa-check me-2 theme-color"></i><strong>SEO audit:</strong> Create current baseline for SEO.</li>
<li><i class="far fa-check me-2 theme-color"></i><strong>Competitive analysis:</strong> Define SEO competitors.</li>
            </ul>
            <a href="" class="btn btn-primary mt-4 ">Get Started Now</a>
        </div>
        <div class="col-md-5 ms-auto col-xl-4">
        	<img src="<?=base_url(); ?>assets/images/1.svg" alt="" class="img-fluid">
        </div>
        </div>
    </div>
    <div class="tab-pane fade " id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-home-tab">
    	<div class="row align-items-center px-xl-5">
    	<div class="col-md-7 mb-4 mb-md-0">
        	<h3>Diverse Idea Generation</h3>
            <p>Our principle is to have more hands on deck while we strategize for your brands. People from diverse backgrounds and experiences are a part of our idea-generation process. 
 </p>
            <ul class="list-unstyled lh-lg">
            	<li><i class="far fa-check me-2 theme-color"></i> <strong>Project brief:</strong> Define client information & business goals</li>
<li><i class="far fa-check me-2 theme-color"></i><strong>Keyword research:</strong> Define potential target keywords.</li>
<li><i class="far fa-check me-2 theme-color"></i><strong>SEO audit:</strong> Create current baseline for SEO.</li>
<li><i class="far fa-check me-2 theme-color"></i><strong>Competitive analysis:</strong> Define SEO competitors.</li>
            </ul>
            <a href="" class="btn btn-primary mt-4 ">Get Started Now</a>
        </div>
        <div class="col-md-5 ms-auto col-xl-4">
        	<img src="<?=base_url(); ?>assets/images/1.svg" alt="" class="img-fluid">
        </div>
        </div>
    </div>
	
	<div class="tab-pane fade " id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-home-tab">
    	<div class="row align-items-center px-xl-5">
    	<div class="col-md-7 mb-4 mb-md-0">
        	<h3>Setting up quarterly targets</h3>
            <p>To make your brand go & grow, we set up SMART quarterly goals for our SEO team and keep a keen eye on the performance.

 </p>
            <ul class="list-unstyled lh-lg">
            	<li><i class="far fa-check me-2 theme-color"></i> <strong>Project brief:</strong> Define client information & business goals</li>
<li><i class="far fa-check me-2 theme-color"></i><strong>Keyword research:</strong> Define potential target keywords.</li>
<li><i class="far fa-check me-2 theme-color"></i><strong>SEO audit:</strong> Create current baseline for SEO.</li>
<li><i class="far fa-check me-2 theme-color"></i><strong>Competitive analysis:</strong> Define SEO competitors.</li>
            </ul>
            <a href="" class="btn btn-primary mt-4 ">Get Started Now</a>
        </div>
        <div class="col-md-5 ms-auto col-xl-4">
        	<img src="<?=base_url(); ?>assets/images/1.svg" alt="" class="img-fluid">
        </div>
        </div>
    </div>
	
	
	<div class="tab-pane fade " id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-home-tab">
    	<div class="row align-items-center px-xl-5">
    	<div class="col-md-7 mb-4 mb-md-0">
        	<h3>Weekly call & Monthly Meetings</h3>
            <p>Our relationship with clients doesn’t end with a transaction. We keep you involved with us through weekly and monthly reporting. 

 </p>
            <ul class="list-unstyled lh-lg">
            	<li><i class="far fa-check me-2 theme-color"></i> <strong>Project brief:</strong> Define client information & business goals</li>
<li><i class="far fa-check me-2 theme-color"></i><strong>Keyword research:</strong> Define potential target keywords.</li>
<li><i class="far fa-check me-2 theme-color"></i><strong>SEO audit:</strong> Create current baseline for SEO.</li>
<li><i class="far fa-check me-2 theme-color"></i><strong>Competitive analysis:</strong> Define SEO competitors.</li>
            </ul>
            <a href="" class="btn btn-primary mt-4 ">Get Started Now</a>
        </div>
        <div class="col-md-5 ms-auto col-xl-4">
        	<img src="<?=base_url(); ?>assets/images/1.svg" alt="" class="img-fluid">
        </div>
        </div>
    </div>
	
	<div class="tab-pane fade " id="v-pills-settings1" role="tabpanel" aria-labelledby="v-pills-home-tab">
    	<div class="row align-items-center px-xl-5">
    	<div class="col-md-7 mb-4 mb-md-0">
        	<h3>Regular roadmap analysis</h3>
            <p>We don’t dive in and develop unrealistic roadmap for your brand. We conduct regular evaluation of the goals & based on the results, adjustments are made to overwhelm your performance

 </p>
            <ul class="list-unstyled lh-lg">
            	<li><i class="far fa-check me-2 theme-color"></i> <strong>Project brief:</strong> Define client information & business goals</li>
<li><i class="far fa-check me-2 theme-color"></i><strong>Keyword research:</strong> Define potential target keywords.</li>
<li><i class="far fa-check me-2 theme-color"></i><strong>SEO audit:</strong> Create current baseline for SEO.</li>
<li><i class="far fa-check me-2 theme-color"></i><strong>Competitive analysis:</strong> Define SEO competitors.</li>
            </ul>
            <a href="" class="btn btn-primary mt-4 ">Get Started Now</a>
        </div>
        <div class="col-md-5 ms-auto col-xl-4">
        	<img src="<?=base_url(); ?>assets/images/1.svg" alt="" class="img-fluid">
        </div>
        </div>
    </div>
    </div>

        </section>
        <section class="py-5 position-relative factors">
        <div class="wave position-absolute end-0 theme-bg top-0 w-100  overflow-hidden ">
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
        	<div class="container-xl position-relative ">
            	<div class="row align-items-center">
                	<div class="col-md-5 pe-xl-5">
                    	<h3 class="text-white">Services We Offer
</h3>
                        <h2 class="text-white">Do you want to generate more traffic to your website? We know the solution</h2>
                    </div>
                    <div class="col-md-7">
                    	<p class="text-white">Give a boost that your brand needs with a top-notch SEO marketing partner. We are a full-service business that is being refined with time. We are working with the leading global brands across the world. Our services are tailored as per your business’s specific needs. ESEO’s holistic approach comprises collaboration among various teams and experts to bring in new and innovative ideas to develop our best-in-class services to give your brand a competitive edge in digital marketing. 
</p>
                    </div>
                </div>
                <div class="row mt-5 text-center">
                	<div class="col-md-4 col-lg-3 mb-4">
                    	<div class="shadow bg-white rounded-3 p-4 h-100">
                        	<img src="<?=base_url(); ?>assets/images/Group 36852.svg" alt="" class="img-fluid mb-4">
                        	<h3 class="theme-color">Global SEO</h3>
                            <p>Upscale the business’s reach, website traffic, and conversions to give your business international heights with us. 
</p>
                        </div>
                    </div>

                	<div class="col-md-4 col-lg-3 mb-4">
                    	<div class="shadow bg-white rounded-3 p-4 h-100">
                        	<img src="<?=base_url(); ?>assets/images/Group 3686.svg" alt="" class="img-fluid mb-4">
                        	<h3 class="theme-color">Local SEO</h3>
                            <p>Get away with black-hat SEO providers & be a leader in the localized market with us
</p>
                        </div>
                    </div>

                	<div class="col-md-4 col-lg-3 mb-4">
                    	<div class="shadow bg-white rounded-3 p-4 h-100">
                        	<img src="<?=base_url(); ?>assets/images/Group 3687.svg" alt="" class="img-fluid mb-4">
                        	<h3 class="theme-color">National SEO</h3>
                            <p> Our highly proficient SEO team has in-depth knowledge of the ongoing market trends and can make you a national leader in no time. 
</p>
                        </div>
                    </div>
                	<div class="col-md-4 col-lg-3 mb-4">
                    	<div class="shadow bg-white rounded-3 p-4 h-100">
                        	<img src="<?=base_url(); ?>assets/images/Group 36861.svg" alt="" class="img-fluid mb-4">
                        	<h3 class="theme-color">E-Commerce Marketing</h3>
                            <p>Driving traffics to your web-store in no time with out of the box SEO strategy
</p>
                        </div>
                    </div>
                	<div class="col-md-4 col-lg-3 mb-4">
                    	<div class="shadow bg-white rounded-3 p-4 h-100">
                        	<img src="<?=base_url(); ?>assets/images/Group 2211.svg" alt="" class="img-fluid mb-4">
                        	<h3 class="theme-color">Guest Posting</h3>
                            <p>Boost up the organic traffic and get listed among the popular brands in the industry with niche relevant guest posts.</p>
                        </div>
                    </div>

                	<div class="col-md-4 col-lg-3 mb-4">
                    	<div class="shadow bg-white rounded-3 p-4 h-100">
                        	<img src="<?=base_url(); ?>assets/images/Group 2212.svg" alt="" class="img-fluid mb-4">
                        	<h3 class="theme-color">Content Marketing</h3>
                            <p>Get your business displayed with impeccable content and engage the audience with the right product information. 
</p>
                        </div>
                    </div>
                	<div class="col-md-4 col-lg-3 mb-4">
                    	<div class="shadow bg-white rounded-3 p-4 h-100">
                        	<img src="<?=base_url(); ?>assets/images/Group 2213.svg" alt="" class="img-fluid mb-4">
                        	<h3 class="theme-color">App Store Optimisation</h3>
                            <p>Boost the download of your apps on the playstore with our unique mobile app marketing services
</p>
                        </div>
                    </div>
                	<div class="col-md-4 col-lg-3 mb-4">
                    	<div class="shadow bg-white rounded-3 p-4 h-100">
                        	<img src="<?=base_url(); ?>assets/images/Group 2214.svg" alt="" class="img-fluid mb-4">
                        	<h3 class="theme-color">Mobile App-marketing</h3>
                            <p>Boost the download of your apps on the playstore with our unique mobile app marketing services
</p>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center pt-4">
                	<a href="" class="btn btn-outline-primary mx-2">Request a Free Quote</a>
                	<a href="" class="btn btn-primary mx-2">Talk to our Expert</a>
                </div>
            </div>
        </section>
        <section class="py-5 theme-bg analysis">
        	<div class="container-xl text-center text-white">
            	<h3>SEO ANALYSIS</h3>
                <h2>Website’s SEO Analysis For Free!</h2>
                <p>Let us help you get your business online and grow it with passion. Our team of professional SEO experts is the perfect partner </p>
                <div class="row pt-4">

               <div class="col-md-11 col-xl-8 mx-auto">
                <form action="">
                	<div class="row g-3">
  <div class="col-12 col-md">
    <input type="text" class="form-control text-white" placeholder="Enter Your Website URL.." aria-label="First name">
  </div>
  <div class="col-12 col-md">
    <input type="email" class="form-control text-white" placeholder="Enter Your Email ID" aria-label="Last name">
  </div>
  <div class="col-12 col-md-auto">
    <button type="submit" class="btn btn-white" data-bs-toggle="modal" data-bs-target="#demo-modal">Free Website Audit </button>
  </div>
</div>
                </form>
                </div>
                </div>
            </div>
        </section>
<section class="py-5">
        	<div class="container-xl">
           	  <div class="row align-items-center">
              <div class="col-md-5 pe-xl-5 mb-4 mb-md-0">
              	<img src="<?=base_url(); ?>assets/images/Group 36y85.svg" alt="" class="img-fluid">
                <a href="" class="btn btn-primary mt-4 py-3 px-4">Free Website Audit</a>
              </div>
           	    <div class="col-md-7">
                		<span class="d-block sub-heading">How to Effectively Implement an SEO?</span>
                    	<h2>Do you want to generate more traffic to your website? We know the solution</h2>
                  <p>If you are going to use a passage of Lorem Ipsum, you need to be suthere isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator. If you are going to use a passage of Lorem you need to be suthere isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator. </p>
                  
                  <div class="row text-center pt-3">
                  	<div class="col-md-6 col-lg-4  pt-lg-5 mb-3 mb-lg-0">
                    	<div class="rounded-3 px-4 pt-4 pb-3 h-100" style="background:#E5F9F9">
                        <img src="<?=base_url(); ?>assets/images/Group 2223.svg" alt="" class="img-fluid mb-4">
                        	<h3 style="color:#009e9e">Global SEO</h3>
                            <p>Our Salesforce experts will recommend effective solutions to meet </p>	
                        </div>
                    </div>

                  	<div class="col-md-6 col-lg-4 mb-3 mb-lg-0">
                    	<div class="rounded-3 px-4 pt-4 pb-3" style="background:#FFE8EF">
                        <img src="<?=base_url(); ?>assets/images/Group 2224.svg" alt="" class="img-fluid mb-4">
                        	<h3 style="color:#e20e4f">Local SEO</h3>
                            <p>Our Salesforce experts will recommend effective solutions to meet your business objectives. </p>	
                        </div>
                    </div>

                  	<div class="col-md-6 col-lg-4 pt-lg-5 mb-3 mb-lg-0">
                    	<div class="rounded-3 px-4 pt-4 pb-3 h-100" style="background:#F8F1E9">
                        <img src="<?=base_url(); ?>assets/images/Group 2225.svg" alt="" class="img-fluid mb-4">
                        	<h3 style="color:#ed8003">National SEO</h3>
                            <p>Our Salesforce experts will recommend effective solutions to meet </p>	
                        </div>
                    </div>
                  </div>

                    </div>
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
        	<div class="container-xl text-center">
            	<span class="text-center d-block sub-heading text-white">Alliance</span>
            	<h2>Associate Partner</h2>
                 <div class="owl-carousel owl-theme partner-slider mt-4 position-relative px-xl-5">
                	<div class="item with-ease px-3 py-1  h-100 d-flex aign-items-center justify-content-center mx-2 shadow"><img src="<?=base_url(); ?>assets/images/google-partner.svg" alt="" class="img-fluid w-auto"></div>
                	<div class="item with-ease px-3 py-1  h-100 d-flex aign-items-center justify-content-center mx-2 shadow"><img src="<?=base_url(); ?>assets/images/Bing-Ads-Accredited-Professional.svg" alt="" class="img-fluid w-auto"></div>
                	<div class="item with-ease px-3 py-1  h-100 d-flex aign-items-center justify-content-center mx-2 shadow"><img src="<?=base_url(); ?>assets/images/google_analytics-ar21.svg" alt="" class="img-fluid w-auto"></div>
                	<div class="item with-ease px-3 py-1  h-100 d-flex aign-items-center justify-content-center mx-2 shadow"><img src="<?=base_url(); ?>assets/images/google-partner.svg" alt="" class="img-fluid w-auto"></div>
                	<div class="item with-ease px-3 py-1  h-100 d-flex aign-items-center justify-content-center mx-2 shadow"><img src="<?=base_url(); ?>assets/images/HubSpot-sm.svg" alt="" class="img-fluid w-auto"></div>
                	<div class="item with-ease px-3 py-1  h-100 d-flex aign-items-center justify-content-center mx-2 shadow"><img src="<?=base_url(); ?>assets/images/facebookblueprint.svg" alt="" class="img-fluid w-auto"></div>
                </div>
            </div>	
        </section>
        
        <section class="py-5" style="background:#F1F8FF">
        	<div class="container-xl text-center">
            	<h2>Industries We Serve</h2>
                <p>Right from the very beginning of our journey, we are building our reign with innovation and creative ideas. Our diverse clientele is exemplary of the same.
</p>
                <div class="row text-start">
                	<div class="col-md-6 col-lg-4 my-4">
                    	<div class="d-flex align-items-center">
  <div class="flex-shrink-0">
    <img src="<?=base_url(); ?>assets/images/Group h2269.svg" alt="..." class="img-fluid">
  </div>
  <div class="flex-grow-1 ms-3">
  <h3>Healthcare</h3>
    <p class="mb-0">Guest tiled he quick by so these trees am. It announcing alteration at surrounded</p>
  </div>
</div>
                    </div>
                    <div class="col-md-6 col-lg-4 my-4">
                    	<div class="d-flex align-items-center">
  <div class="flex-shrink-0">
    <img src="<?=base_url(); ?>assets/images/Group 2269.svg" alt="..." class="img-fluid">
  </div>
  <div class="flex-grow-1 ms-3">
  <h3>Finance</h3>
    <p class="mb-0">Guest tiled he quick by so these trees am. It announcing alteration at surrounded</p>
  </div>
</div>
                    </div>

                    <div class="col-md-6 col-lg-4 my-4">
                    	<div class="d-flex align-items-center">
  <div class="flex-shrink-0">
    <img src="<?=base_url(); ?>assets/images/Group 2273.svg" alt="..." class="img-fluid">
  </div>
  <div class="flex-grow-1 ms-3">
  <h3>Infrastructure</h3>
    <p class="mb-0">Guest tiled he quick by so these trees am. It announcing alteration at surrounded</p>
  </div>
</div>
                    </div>
                    <div class="col-md-6 col-lg-4 my-4">
                    	<div class="d-flex align-items-center">
  <div class="flex-shrink-0">
    <img src="<?=base_url(); ?>assets/images/Group 2268.svg" alt="..." class="img-fluid">
  </div>
  <div class="flex-grow-1 ms-3">
  <h3>Government</h3>
    <p class="mb-0">Guest tiled he quick by so these trees am. It announcing alteration at surrounded</p>
  </div>
</div>
                    </div>
                    <div class="col-md-6 col-lg-4 my-4">
                    	<div class="d-flex align-items-center">
  <div class="flex-shrink-0">
    <img src="<?=base_url(); ?>assets/images/Group 2271.svg" alt="..." class="img-fluid">
  </div>
  <div class="flex-grow-1 ms-3">
  <h3>Entertainment</h3>
    <p class="mb-0">Guest tiled he quick by so these trees am. It announcing alteration at surrounded</p>
  </div>
</div>
                    </div>
                    <div class="col-md-6 col-lg-4 my-4">
                    	<div class="d-flex align-items-center">
  <div class="flex-shrink-0">
    <img src="<?=base_url(); ?>assets/images/Group 2267.svg" alt="..." class="img-fluid">
  </div>
  <div class="flex-grow-1 ms-3">
  <h3>Automotive</h3>
    <p class="mb-0">Guest tiled he quick by so these trees am. It announcing alteration at surrounded</p>
  </div>
</div>
                    </div>

                </div>	
            </div>
        </section>
        <section class="py-5  text-center">
        	<div class="container-xl px-xl-5">
              <span class="d-block sub-heading">We work together across the globe</span>
              <h2>Client Result & Case Study
</h2>
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
        
        <section class="py-5 position-relative faqs overflow-hidden">
        	<div class="container-xl position-relative">
            <div class="row align-items-center">
            	<div class="col-xl-5 col-md-6 pe-xl-5 mb-4 mb-md-0">
                	<span class="d-block sub-heading">FAQ's</span>
                    <h2>To the innumerable questions & queries, we have curated the right answers for you. 
</h2>
                    
                    <div class="accordion my-5" id="accordionExample">
  <div class="accordion-item mb-3 shadow-sm">
    <h2 class="accordion-header" id="headingOne">
      <button class="accordion-button shadow-none text-muted py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
         Why Should I Go for SEO Services?
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
      <div class="accordion-body small lh-lg">
     
Living in a world where more than 80% users search for a product or service online, having a web presence has become indispensable. If your business gets first page rankings, this means your future customers bestow more trust and are likely to buy from you. At ESEO, our aim is to help you fetch top spots on search engines for your target keywords.

      </div>
    </div>
  </div>
  <div class="accordion-item mb-3 shadow-sm">
    <h2 class="accordion-header" id="headingTwo">
      <button class="accordion-button shadow-none text-muted py-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
       What’s the point of doing SEO when I am selling offline?
      </button>
    </h2>
    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
      <div class="accordion-body small lh-lg">
        
As mentioned before, it’s important to build trust with your audience before they become customers. Even if you have an offline presence, it’s essential to have a running website with all your features listed; if you are to attract attention. That’s where SEO comes into play. With our SEO services, we help you connect the road to more leads and recurring sales.

      </div>
    </div>
  </div>
  <div class="accordion-item mb-3 shadow-sm">
    <h2 class="accordion-header" id="headingThree">
      <button class="accordion-button shadow-none text-muted py-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree7" aria-expanded="false" aria-controls="collapseThree">
      What Services are offered in SEO Package?
      </button>
    </h2>
    <div id="collapseThree7" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
      <div class="accordion-body small lh-lg">
       
That completely depends on the current status of the website, the keywords you are trying to rank for among major factors. We will create a customized package depending on your requirements and the level of SEO required to propel your website to the top of search engine rankings. We are not a money minting SEO business, so you can expect complete transparency from our side.

      </div>
    </div>
  </div>

  <div class="accordion-item mb-3 shadow-sm">
    <h2 class="accordion-header" id="headingThree">
      <button class="accordion-button shadow-none text-muted py-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
       Have You Delivered Results for My Industry in the Past?
      </button>
    </h2>
    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
      <div class="accordion-body small lh-lg">
      
Apart from the usual industries such as IT, Manufacturing, Retail and Pharma; our SEO experts have worked with challenging industries and delivered impeccable results that other SEO agencies deem impossible. We would be more than happy in discussing your requirements, no matter how difficult that might sound. We always encourage our clients to give a shot, and then we commit to deliver.

      </div>
    </div>
  </div>

  <div class="accordion-item mb-3 shadow-sm">
    <h2 class="accordion-header" id="headingThree">
      <button class="accordion-button shadow-none text-muted py-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree6" aria-expanded="false" aria-controls="collapseThree">
         How Does Your Reporting Mechanism Looks Like?
      </button>
    </h2>
    <div id="collapseThree6" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
      <div class="accordion-body small lh-lg">
      
After having a discussion, we will be agreeing on certain goals based on which we will send you monthly reports to track the progress. Our reporting is quite simple and transparent and will give you a 360-degree view of how your target keywords are performing and how the entire SEO efforts are shaping up.

      </div>
    </div>
  </div>
  
  <div class="accordion-item mb-3 shadow-sm">
    <h2 class="accordion-header" id="headingThree">
      <button class="accordion-button shadow-none text-muted py-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree5" aria-expanded="false" aria-controls="collapseThree">
        Can You Help Me with Local SEO?
      </button>
    </h2>
    <div id="collapseThree5" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
      <div class="accordion-body small lh-lg">
      

Definitely. Local SEO is our forte and we have helped multiple local businesses acquire top positions on local search engines. We encourage you to discuss your local SEO needs in detail and we are more than happy to help.


      </div>
    </div>
  </div>
  
  
    <div class="accordion-item mb-3 shadow-sm">
    <h2 class="accordion-header" id="headingThree">
      <button class="accordion-button shadow-none text-muted py-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree4" aria-expanded="false" aria-controls="collapseThree">
  How Soon Can We Expect Traffic and Rankings to Pour In?
      </button>
    </h2>
    <div id="collapseThree4" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
      <div class="accordion-body small lh-lg">
     
It’s difficult to comment without understanding where your site stands at the moment and the industry you are operating in. SEO is a marathon and not a sprint. Moreover, certain industries and keywords take more time to rank than others. But, with consistent efforts, every mountain can be scaled. Typically, a duration of 6 months to 1 year is recommended to see visible results.


      </div>
    </div>
  </div>
  
  
    <div class="accordion-item mb-3 shadow-sm">
    <h2 class="accordion-header" id="headingThree">
      <button class="accordion-button shadow-none text-muted py-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree3" aria-expanded="false" aria-controls="collapseThree">
    Why Should I Continue SEO After hitting the top spot on Google?
      </button>
    </h2>
    <div id="collapseThree3" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
      <div class="accordion-body small lh-lg">
  
This is an area where even pro businesses falter. Even when you are on top of Google search results, you should continue your SEO efforts. There are always changes and fluctuations in search engine algorithms that means your rankings might slip. Therefore, we strongly recommend you to not discontinue your SEO efforts.


      </div>
    </div>
  </div>
  
    <div class="accordion-item mb-3 shadow-sm">
    <h2 class="accordion-header" id="headingThree">
      <button class="accordion-button shadow-none text-muted py-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree2" aria-expanded="false" aria-controls="collapseThree">
        Do Your SEO Techniques Pose a Threat to My Website?
      </button>
    </h2>
    <div id="collapseThree2" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
      <div class="accordion-body small lh-lg">
     
Not at all. Every SEO technique we deploy lies in the ambit of google and we neither do black hat nor grey hat SEO. We never encourage link spamming or building shady links to your site that do more harm than good to our SEO efforts. We believe in mutual win-win, so always steer clear of disapproved techniques.


      </div>
    </div>
  </div>
  
    <div class="accordion-item mb-3 shadow-sm">
    <h2 class="accordion-header" id="headingThree">
      <button class="accordion-button shadow-none text-muted py-2 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree1" aria-expanded="false" aria-controls="collapseThree">
       Will you Sign an NDA?
      </button>
    </h2>
    <div id="collapseThree1" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
      <div class="accordion-body small lh-lg">
      
Definitely. We are highly critical about the security of your data and once we enter an agreement; we willy duly sign a NDA to safekeep your data like we do with all our clients. There will be no compromise to your data security, whatsoever.


      </div>
    </div>
  </div>
</div>	
<a href="" class="btn btn-primary ">Ask to Expert</a>
                </div>
            	<div class="col-md-6 ms-auto">
                	<div class="p-4 p-xl-5  text-white position-relative faq-content mCustomScrollbar">
                    	<h3>Get Traffic Ticking <br>With Our Premium</h3>
                        <p>Bar flustered impressive manifest far crud opened inside owing punitively around forewent and after wasteful telling sprang coldly and spoke less clients.Bar flustered impressive manifest far crud opened inside owing punitively around forewent. Having a strong desire to be his own boss, Sumit proved the adage that age is just a number. Sharing a common</p>
                        <p> ESEO solutions as a go-to marketplace for digital marketing services. After successfully working with hundreds of small and medium businesses, Sumit aims to take ESEO solutions global; one step at a time. Deepak at a tender age of 19 and jointly scaled ESEO solutions as a go-to marketplace for digital marketing services. After successfully working. After successfully working with hundreds of small and medium businesses, Sumit aims to take ESEO solutions global; one step at a time.</p>
                        <p>Bar flustered impressive manifest far crud opened inside owing punitively around forewent and after wasteful telling sprang coldly and spoke less clients.Bar flustered impressive manifest far crud opened inside owing punitively around forewent. Having a strong desire to be his own boss, Sumit proved the adage that age is just a number. Sharing a common</p>
                        <p>Bar flustered impressive manifest far crud opened inside owing punitively around forewent and after wasteful telling sprang coldly and spoke less clients.Bar flustered impressive manifest far crud opened inside owing punitively around forewent. Having a strong desire to be his own boss, Sumit proved the adage that age is just a number. Sharing a common</p>
                        <p> ESEO solutions as a go-to marketplace for digital marketing services. After successfully working with hundreds of small and medium businesses, Sumit aims to take ESEO solutions global; one step at a time. Deepak at a tender age of 19 and jointly scaled ESEO solutions as a go-to marketplace for digital marketing services. After successfully working. After successfully working with hundreds of small and medium businesses, Sumit aims to take ESEO solutions global; one step at a time.</p>
                        <p>Bar flustered impressive manifest far crud opened inside owing punitively around forewent and after wasteful telling sprang coldly and spoke less clients.Bar flustered impressive manifest far crud opened inside owing punitively around forewent. Having a strong desire to be his own boss, Sumit proved the adage that age is just a number. Sharing a common</p>
                        <p>Bar flustered impressive manifest far crud opened inside owing punitively around forewent and after wasteful telling sprang coldly and spoke less clients.Bar flustered impressive manifest far crud opened inside owing punitively around forewent. Having a strong desire to be his own boss, Sumit proved the adage that age is just a number. Sharing a common</p>
                        <p> ESEO solutions as a go-to marketplace for digital marketing services. After successfully working with hundreds of small and medium businesses, Sumit aims to take ESEO solutions global; one step at a time. Deepak at a tender age of 19 and jointly scaled ESEO solutions as a go-to marketplace for digital marketing services. After successfully working. After successfully working with hundreds of small and medium businesses, Sumit aims to take ESEO solutions global; one step at a time.</p>
                        <p>Bar flustered impressive manifest far crud opened inside owing punitively around forewent and after wasteful telling sprang coldly and spoke less clients.Bar flustered impressive manifest far crud opened inside owing punitively around forewent. Having a strong desire to be his own boss, Sumit proved the adage that age is just a number. Sharing a common</p>
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
         <section   class=" py-5 overflow-hidden">
        		<div class="container-fluid">
                	<div class="row">
                    	<div class="col-md-4 review-tr">
                    	<span class="d-block sub-heading">Client feedback </span>
                    	<h2>Reviews From Our Customers</h2>
                        <p>Aliquam a augue suscipit, luctus neque purus ipsum neque dolor primis libero tempus, blandit posuere and ligula varius magna a porta elementum massa risus</p>
                        <div class="mt-4  d-flex text-secondary review-nav" style="opacity: 0.4;">
                        	<button class="btn prv me-2 shadow-none p-0 text-secondary">
                            	<i class="fal fa-chevron-circle-left"></i>	
                            </button>
                        	<button class="btn nxt shadow-none p-0 text-secondary">
                            	<i class="fal fa-chevron-circle-right"></i>	
                            </button>
                        </div>
                        </div>
                    	<div class="col-md-8">
                 <div class="owl-carousel owl-theme review-slider ">
				 <?php $j=0;foreach($page_testimonial as $pt){ $j++;?>
                	<div class="item p-3 p-xl-4  h-100 shadow <?php if($j==1){echo 'active';} ?>">
                    <img src="<?=base_url(); ?><?php echo ADMIN_SLUG; ?>/uploads/testimonial_images/<?php echo $pt->organization_logo; ?>" alt="" class="img-fluid w-auto d-block mb-4">
                    <p><?php echo $pt->description;?></p>
                    <div class="d-flex align-items-center pt-4 pt-xl-5 ">
  <div class="flex-shrink-0">
    <img src="<?=base_url(); ?><?php echo ADMIN_SLUG; ?>/uploads/testimonial_images/<?php echo $pt->picture; ?>" alt="..." class="img-fluid" style="max-width:40px">
  </div>
  <div class="flex-grow-1 ms-3">
  	<span class="d-block fw-400 theme-color text-uppercase"><?php echo $pt->author; ?></span>
<small class="lh-sm d-block"><?php echo $pt->role;?>  I  <?php echo $pt->organization;?></small>
  	
  </div>
</div>
                    
                    </div>
				 <?php } ?>
              </div>
                        </div>	
                    </div>
                <div class="d-flex pt-4 justify-content-center">
                	<a href="<?php echo base_url(); ?>testimonial" class="btn btn-outline-primary mx-2">See All Testimonials</a>
                	<a href="" class="btn btn-primary mx-2">Review on Google</a>
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
   