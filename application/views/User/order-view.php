<?php //echo "<pre>";print_r($orderItems);
	$where_s1 = array("id"=> $usersBilingAddress->state_id, "status"=> 1);
	$stateData1 = $this->base_model->getOneRecordWithWhere("brij_states",$where_s1 ,"*");
	$where_cty1 = array("id"=> $usersBilingAddress->city_id, "status"=> 1);
	$cityData1 = $this->base_model->getOneRecordWithWhere("brij_cities",$where_cty1 ,"*");
	$where_country1 = array("id"=> $usersBilingAddress->country_id, "status"=> 1);
	$countryData1 = $this->base_model->getOneRecordWithWhere("brij_countries",$where_country1 ,"*");
?>
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
<a id="contentarea" tabindex="-1"></a>
<div class="page-title-wrapper">
  <h1 class="page-title">
	<span class="base" data-ui-id="page-title-wrapper" >Order # <?=$orderDetails->order_number?></span>    
  </h1>
  <span class="order-status"><?=$orderDetails->order_status?></span>
  <div class="order-date">
	<span class="label">Order Date:</span> 
	<date><?=dateFormat("F d, Y",$orderDetails->order_date)?></date>
  </div>
  <?php if(!empty($orderDetails->order_remark)){?>
  <div class="actions-toolbar order-actions-toolbar">
	<div class="actions">
	  <!--<a class="action print"
		href="http://techno.themevast.com/index.php/en/sales/order/print/order_id/25/"
		onclick="this.target='_blank';">
	  <span>Print Order</span>
	  </a>-->
	  <span class="label">Order Remark:</span>
      <span class="color"><font color="red"><?=$orderDetails->order_remark?></font></span>
	</div>
  </div>
  <?php } ?>
</div>
<div class="page messages">
  <div data-placeholder="messages"></div>
  <div data-bind="scope: 'messages'">
	<div data-bind="foreach: { data: cookieMessages, as: 'message' }" class="messages">
	  <div data-bind="attr: {
		class: 'message-' + message.type + ' ' + message.type + ' message',
		'data-ui-id': 'message-' + message.type
		}">
		<div data-bind="html: message.text"></div>
	  </div>
	</div>
	<div data-bind="foreach: { data: messages().messages, as: 'message' }" class="messages">
	  <div data-bind="attr: {
		class: 'message-' + message.type + ' ' + message.type + ' message',
		'data-ui-id': 'message-' + message.type
		}">
		<div data-bind="html: message.text"></div>
	  </div>
	</div>
  </div>
</div>
<div class="main-columns layout layout-2-col row">
  <div class="col-main col-xs-12 col-sm-8 col-md-9 col-lg-9 col-sm-push-4 col-md-push-3 col-lg-push-3">
	<ul class="items order-links">
	  <li class="nav item current"><strong>Items Ordered</strong></li>
	</ul>
	<div class="order-details-items ordered">
	  <div class="order-title">
		<strong>Items Ordered</strong>
	  </div>
	  <div class="table-wrapper order-items">
		<table class="data table table-order-items" id="my-orders-table" summary="Items Ordered">
		  <caption class="table-caption">Items Ordered</caption>
		  <thead>
			<tr>
			  <th class="col name">Product Name</th>
			  <th class="col image">Product Image</th>
			  <th class="col sku">SKU</th>
			  <th class="col price">Price</th>
			  <th class="col qty">Qty</th>
			  <th class="col subtotal">Subtotal</th>
			</tr>
		  </thead>
		  <?php foreach($orderItems as $orderItems){?>
		  <tbody>
			<tr id="order-item-row-34">
			  <td class="col name" data-th="Product Name">
				<strong class="product name product-item-name"><?=$orderItems->product_name?></strong>
				<dl class="item-options">
				  <dt><strong>Category:</strong></dt>
				  <dd>
					<?=$orderItems->cat_name?>                                    
				  </dd>
				</dl>
			  </td>
			  <td class="col name" data-th="Product Image">
			    <?php
				$profilename = 'uploads/product_images/'.$orderItems->images;
				$pro_file= '/uploads/no-image100x100.jpg';
				$pro_original_file= '/uploads/no-image400x400.jpg';
				if (file_exists($profilename) && !empty($orderItems->images))
				{
					$pro_file='/uploads/product_images/small/'.$orderItems->images;
					$pro_original_file='/uploads/product_images/'.$orderItems->images;													
				}
				?>
				<img src="<?=base_url()?><?=$pro_file?>" class="img-thumbnail" width="70px" height="70px"/>
			  </td>
			  <td class="col sku" data-th="SKU"><?=$orderItems->product_code?></td>
			  <td class="col price" data-th="Price">
				<span class="price-excluding-tax" data-label="Excl. Tax">
				<span class="cart-price">
				<span class="price"><i class="fa fa-inr" aria-hidden="true"></i><?=$orderItems->price?></span>            </span>
				</span>
			  </td>
			  <td class="col qty" data-th="Qty">
				<ul class="items-qty">
				  <li class="item">
					<span class="title">Ordered</span>
					<span class="content"><?=$orderItems->product_quantity?></span>
				  </li>
				</ul>
			  </td>
			  <td class="col subtotal" data-th="Subtotal">
				<span class="price-excluding-tax" data-label="Excl. Tax">
				<span class="cart-price">
				<span class="price"><i class="fa fa-inr" aria-hidden="true"></i><?=$orderItems->product_price?></span> </span>
				</span>
			  </td>
			</tr>
		  </tbody>
		  <?php } ?>
		  <tfoot>
			<tr class="subtotal">
			  <th colspan="5" class="mark" scope="row">
				Subtotal                    
			  </th>
			  <td class="amount" data-th="Subtotal">
				<span class="price"><i class="fa fa-inr" aria-hidden="true"></i><?=number_format($orderDetails->order_sub_total)?></span>                    
			  </td>
			</tr>
			<tr class="shipping">
			  <th colspan="5" class="mark" scope="row">
				Tax                  
			  </th>
			  <td class="amount" data-th="Shipping &amp; Handling">
				<span class="price"><i class="fa fa-inr" aria-hidden="true"></i><?=number_format($orderDetails->tax_amount)?></span>                    
			  </td>
			</tr>
			<tr class="grand_total">
			  <th colspan="5" class="mark" scope="row">
				<strong>Grand Total</strong>
			  </th>
			  <td class="amount" data-th="Grand Total">
				<strong><span class="price"><i class="fa fa-inr" aria-hidden="true"></i><?=number_format($orderDetails->order_total)?></span></strong>
			  </td>
			</tr>
		  </tfoot>
		</table>
	  </div>
	  <div class="actions-toolbar">
		<div class="secondary">
		  <a class="action back" href="<?=base_url("user/my_orders"); ?>">
		  <span>Back to My Orders</span>
		  </a>
		</div>
	  </div>
	</div>
	<div class="block block-order-details-view">
	  <div class="block-title">
		<strong>Order Information</strong>
	  </div>
	  <div class="block-content">
		<div class="box box-order-shipping-address">
		  <strong class="box-title"><span>Shipping Address</span></strong>
		  <div class="box-content">
			<address><?=$orderDetails->shipping_name?><br/>
			<?php if(!empty($orderDetails->shipping_company_name)){?>
			  <?=$orderDetails->shipping_company_name?><br />
			<?php } ?>
			  <?=$orderDetails->shipping_address?><br />
			  surya.com<br />
			  <?=$orderDetails->shipping_city?>,  <?=$orderDetails->shipping_state?>, <?=$orderDetails->shipping_country?><br/>
			  <?=$orderDetails->shipping_country?><br/>
			  T: <?=$orderDetails->shipping_mobile_no?>
			</address>
		  </div>
		</div>
		<div class="box box-order-billing-address">
		  <strong class="box-title">
		  <span>Billing Address</span>
		  </strong>
		  <div class="box-content">
			<address><?=$usersBilingAddress->name?><br/>
			  <?=$usersBilingAddress->address?><br />
			  <?php if(!empty($cityData1->city_name)){?>
			  <?=$cityData1->city_name?>,  <?=$stateData1->state_name?>, <?=$usersBilingAddress->pin_code?><br/>
			  <?=$countryData1->country_name?><br/>
			  <?php } ?>
			  T: <?=$usersBilingAddress->phone_no?>
			</address>
		  </div>
		</div>
		<div class="box box-order-billing-method">
		  <strong class="box-title">
		  <span>Payment Method</span>
		  </strong>
		  <div class="box-content">
			<dl class="payment-method checkmemo">
			  <dt class="title"><?=$orderDetails->payment_method?></dt>
			</dl>
		  </div>
		</div>
		<div class="box box-order-billing-method">
		  <strong class="box-title">
		  <span>Payment Status</span>
		  </strong>
		  <div class="box-content">
			<dl class="payment-method checkmemo">
			  <dt class="title"><?=$orderDetails->payment_status?></dt>
			</dl>
		  </div>
		</div>
	  </div>
	</div>
	<input name="form_key" type="hidden" value="ryHnfCvhTpzYHYnf" />
	<script>
	  require([
			'jquery',
			'mage/mage',
			'quickview/cloudzoom'
		], function ($) {
		});
	</script>
  </div>