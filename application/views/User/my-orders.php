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
<div id="maincontent" class="page-main container" style="display:block">
  <div class="page-title-wrapper">
	<h1 class="page-title">
	  <span class="base" data-ui-id="page-title-wrapper">My Orders</span>    
	</h1>
  </div>
  <div class="main-columns layout layout-2-col row">
	<div class="col-main col-xs-12 col-sm-8 col-md-9 col-lg-9 col-sm-push-4 col-md-push-3 col-lg-push-3">
	  <input name="form_key" type="hidden" value="CutcyVYoaE8GFffy" />
	  <?php if(!empty($orderList) && count($orderList) > 0){?>
	  <div class="table-wrapper orders-history">
		<table class="data table table-order-items history" id="my-orders-table">
		  <caption class="table-caption">Orders</caption>
		  <thead>
			<tr>
			  <th scope="col" class="col id">Order #</th>
			  <th scope="col" class="col date">Date</th>
			  <th scope="col" class="col shipping">Ship To</th>
			  <th scope="col" class="col total">Order Total</th>
			  <th scope="col" class="col status">Payment Status</th>
			  <th scope="col" class="col status">Order Status</th>
			  <th scope="col" class="col actions">&nbsp;</th>
			</tr>
		  </thead>
		  <tbody>
		  <?php //echo "<pre>"; print_r($orderList);		  
		  foreach($orderList as $orderList){?>
			<tr>
			  <td data-th="Order #" class="col id"><?=$orderList->order_number?></td>
			  <td data-th="Date" class="col date"><?=dateFormat("m/d/Y",$orderList->order_date)?></td>
			  <td data-th="Ship To" class="col shipping"><?=$orderList->shipping_name?></td>
			  <td data-th="Order Total" class="col total"><span class="price"><i class="fa fa-inr" aria-hidden="true"></i><?=number_format($orderList->order_total)?></span></td>
			  <td data-th="Payment Status" class="col status"><?=$orderList->payment_status?></td>
			  <td data-th="Status" class="col status"><?=$orderList->order_status?></td>
			  <td data-th="Actions" class="col actions">
				<a href="<?=base_url("user/order_view?order_id=".$orderList->id)?>" class="action view">
				<span>View Order</span>
				</a>
			  </td>
			</tr>
		  <?php } ?>
		  </tbody>
		</table>
	  </div>
	 <?php }else{ ?>
	    <div class="message info empty"><span>You have no orders in the Order list.</span></div>
	   <?php }  ?>
	  <div class="order-products-toolbar toolbar bottom">
		<div class="pager">
		</div>
	  </div>
	  <div class="actions-toolbar">
		<div class="secondary"><a class="action back" href="<?=base_url("/")?>"><span>Back</span></a></div>
	  </div>
	</div>