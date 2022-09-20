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
  <div class="page messages">
    <div data-placeholder="messages"></div>
    <div data-bind="scope: 'messages'">
      <div data-bind="foreach: { data: cookieMessages, as: 'message' }" class="messages"></div>
      <div data-bind="foreach: { data: messages().messages, as: 'message' }" class="messages"></div>
    </div>
  </div>
  <div class="main-columns layout layout-1-col">
    <div class="col-main">
      <input name="form_key" type="hidden" value="0cZO8Ul8dGbRNLLo">
      <div class="checkout-success">
	    <?php if($this->session->flashdata('payment_success')){ ?>
		  <div class="message success empty">
			<div><?php echo $this->session->flashdata('payment_success'); ?></div>
		  </div>
		  <?php }else if($this->session->flashdata('payment_error')){  ?>
		  <div class="message error empty">
			<div><?php echo $this->session->flashdata('payment_error'); ?></div>
		  </div>
		  <?php } ?>
	    <?php //echo $msg; ?>
        <p>Your order number is: <a href="<?=base_url("user/order_view?order_id=".$orderDetails->id); ?>" class="order-number"><strong><?=$orderDetails->order_number?></strong></a>.</p>
        <p>We'll email you an order confirmation with details and tracking info.</p>
        <div class="actions-toolbar">
          <div class="primary">
            <a class="action primary continue" href="<?=base_url(); ?>"><span>Continue Shopping</span></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>