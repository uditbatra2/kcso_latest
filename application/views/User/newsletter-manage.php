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
<div id="maincontent" class="page-main container" style="display:block;">
 <a id="contentarea" tabindex="-1"></a>
 <div class="page-title-wrapper">
	<h1 class="page-title">
	   <span class="base" data-ui-id="page-title-wrapper" >Newsletter Subscription</span>
	</h1>
 </div>
 <div class="main-columns layout layout-2-col row">
	<div class="col-main col-xs-12 col-sm-8 col-md-9 col-lg-9 col-sm-push-4 col-md-push-3 col-lg-push-3">
		  <?php if($this->session->flashdata('user_success')){ ?>
	  <div class="message success empty close_alert">
		<div><?php echo $this->session->flashdata('user_success'); ?></div>
	  </div>
	  <?php }else if($this->session->flashdata('user_error')){  ?>
	  <div class="message error empty close_alert">
		<div><?php echo $this->session->flashdata('user_error'); ?></div>
	  </div>
	  <?php }?>
	  <?php
		$form_attribute=array(
				'name' => 'newsletter-manage-form',
				'class' => 'form form-newsletter-manage',
				'method'=>"post",
				'id' => 'form-validate',
				'novalidate' => 'novalidate',
				'autocomplete'=>"off"
				);
		$hidden = array('action' => 'newsletterManageForm');
		//Form Open
		echo form_open_multipart('user/newsletter_manage',$form_attribute,$hidden);
	?>
	   <input name="form_key" type="hidden" value="C6GTLfbIPK75zXud" />
		  <fieldset class="fieldset fieldset3 fieldset5">
			 <input name="form_key" type="hidden" value="C6GTLfbIPK75zXud" />            
			 <legend class="legend legend3"><span>Subscription option</span></legend>
			 <br>
			 <div class="field choice">
				<input type="checkbox" name="is_subscribed" id="subscription" value="1" title="General Subscription" class="checkbox" <?=(isset($userDetails->is_subscribe_newletters) && !empty($userDetails->is_subscribe_newletters) && $userDetails->is_subscribe_newletters==1)? 'checked':'';?>>
				<label for="subscription" class="label"><span>General Subscription</span></label>
			 </div>
		  </fieldset>
		  <div class="actions-toolbar">
			 <div class="primary"><button type="submit" title="Save" class="action save primary"><span>Save</span></button></div>
			 <div class="secondary"><a class="action back" href="<?=base_url("user/account")?>"><span>Back</span></a></div>
		  </div>
	    <?php 
			// Form Close
			echo form_close(); ?>
	</div>