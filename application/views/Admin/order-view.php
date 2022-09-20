<div class="page-wrapper">
            <div class="content container-fluid">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title">Order Update</h4>
                    </div>
                    <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="<?=base_url('admin/orders_list')?>" class="btn btn-info btn-rounded pull-right"><i class="fa fa-arrow-left"></i> Back To List</a>
                        <div class="view-icons">
                            <!---<a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
                            <a href="clients-list.html" class="list-view btn btn-link active"><i class="fa fa-bars"></i></a>-->
                        </div>
                    </div>
                </div>
				<?php if($this->session->flashdata('order_success')){ ?>
					<div class="alert alert-success">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Success!</strong> <?php echo $this->session->flashdata('order_success'); ?>
					</div>

				<?php }else if($this->session->flashdata('order_error')){  ?>
					<div class="alert alert-danger">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Error!</strong> <?php echo $this->session->flashdata('order_error'); ?>
					</div>
				<?php }?>
				<?php
			   $form_attribute=array(
						'name' => 'update-order',
						'class' => '',
						'method' =>"post",
						'autocomplete'=>"off",
						'id' => 'update-order',
						'novalidate' => 'novalidate',
						);
				$hidden = array('action' => 'update-order','order_id'=>$order_id);
				// Form Open
				echo form_open('admin/order_view',$form_attribute,$hidden);
				?>						
                <div class="row filter-row">				
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group">
							<textarea cols="5" class="form-control required" placeholder="Order Remark" name="order_remark" id="order_remark"><?=(isset($orderData->order_remark) && !empty($orderData->order_remark) && $orderData->order_remark != '')? $orderData->order_remark:'';?></textarea>
                        </div>
                    </div>
					<?php if(isset($orderData->payment_method) && !empty($orderData->payment_method) && $orderData->payment_method == 'COD'){?>
					 <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Payment Status</label>
                            <select class="select floating required" name="payment_status" id="payment_status">
							    <option value="NOT PAID" <?=(isset($orderData->payment_status) && !empty($orderData->payment_status) && $orderData->payment_status=="NOT PAID")? 'selected':'';?>>Not Paid</option>
                                <option value="PAID" <?=(isset($orderData->payment_status) && !empty($orderData->payment_status) && $orderData->payment_status=="PAID")? 'selected':'';?>>Paid</option>
                            </select>
                        </div>
                    </div>
					<?php } ?>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Order Status</label>
                            <select class="select floating required" name="order_status" id="order_status">
                                <!--<option value="">Select Status</option>-->
                                <?php foreach($orderStatus as $key=>$orderStatus){?>
								<option value="<?=$key?>" <?=(isset($orderData->order_status) && !empty($orderData->order_status) && $orderData->order_status==$orderStatus)? 'selected':'';?>><?=$orderStatus?></option>
								<?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-2">
                        <button type="submit" class="btn btn-success btn-block"> Update </button>
                    </div>
                    <!--<div class="col-sm-6 col-md-3">
						<button type="button" class="btn btn-danger btn-block" onclick="javascript:window.location.href='<?//=base_url('admin/order_view?do=order_view&order_id='.$order_id)?>';"> Cancel </button>
                    </div>-->					
                </div>
				<?php
					// Form Close
					echo form_close(); ?>
				    <div class="row">
						<div class="col-sm-5 col-4">
							<h4 class="page-title"><?=$title?></h4>
						</div>
						<div class="col-sm-7 col-8 text-right m-b-30">
							<div class="btn-group btn-group-sm">
								<!--<button class="btn btn-white" id="pdf">PDF</button>-->
								<button class="btn btn-white" id="print"><i class="fa fa-print fa-lg"></i> Print</button>
							</div>
						</div>
					</div>
                   <div class="row" id="printarea">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6 m-b-20">
                                        <h5>Billing information:</h5>
                                        <ul class="list-unstyled">
                                            <li><?=$orderUsersData->name?></li>
                                            <li><?=$orderUsersData->address?>,</li>
                                            <li><?=$orderUsersData->city_name?>, <?=$orderUsersData->state_name?>, <?=$orderUsersData->pin_code?></li>
                                            <li><?=$orderUsersData->country_name?></li>
											<li><?=$orderUsersData->phone_no?></li>
                                            <li><a href="mailto:<?=$orderUsersData->email_id?>"><?=$orderUsersData->email_id?></a></li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-6 m-b-20">
                                        <div class="invoice-details">
                                            <h3 class="text-uppercase">Order ID #<?=$orderData->order_number?></h3>
                                            <ul class="list-unstyled">
                                                <li>Order Date: <span><?=dateFormat("F d, Y",$orderData->order_date)?></span></li>
                                                <li>Order Status: <span><?=$orderData->order_status?></span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 col-lg-9 m-b-20">
                                        <h5>Shipping information:</h5>
                                        <ul class="list-unstyled">
                                            <li>
                                                <h5><strong><?=$orderData->shipping_name?></strong></h5></li>
                                            <li><?=$orderData->shipping_address?></li>
                                            <li><?=$orderData->shipping_city?>, <?=$orderData->shipping_state?>, <?=$orderData->shipping_post_code?></li>
                                            <li><?=$orderData->shipping_country?></li>
                                            <li><?=$orderData->shipping_mobile_no?></li>
                                            <li><a href="mailto:<?=$orderUsersData->email_id?>"><?=$orderUsersData->email_id?></a></li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-6 col-lg-3 m-b-20">
                                        <span class="text-muted">Payment Details:</span>
                                        <ul class="list-unstyled invoice-payment-details">
                                            <li>
                                                <h5>Total Amount: <span class="text-right"><i class="fa fa-inr"></i><?=number_format($orderData->order_total)?></span></h5></li>
                                            <li>Payment Method: <span><?=$orderData->payment_method;?></span></li>
                                            <li>Payment Status: <span><?=$orderData->payment_status;?></span></li>
											<?php if(isset($orderData->payment_method) && !empty($orderData->payment_method) && $orderData->payment_method != 'COD'){?>
                                            <li>Transaction ID: <span><?=$orderData->transaction_id;?></span></li>
											<?php } ?>
											<li>Payment date: <span><?=dateFormat("F d, Y",$orderData->payment_date)?></span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>ITEM</th>
                                                <th class="d-none d-sm-block">Image</th>
                                                <th>UNIT COST</th>
                                                <th>QUANTITY</th>
                                                <th>TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
										<?php $srno=1; foreach($orderItemsData as $orderItemsData){?>
                                            <tr>
                                                <td><?=$srno?></td>
                                                <td><?=$orderItemsData->product_name?></td>
                                                <td class="d-none d-sm-block lightgallery">
												<?php
												$profilename = 'uploads/product_images/'.$orderItemsData->images;
												$pro_file= '../uploads/no-image100x100.jpg';
												$pro_original_file= '../uploads/no-image400x400.jpg';
												if (file_exists($profilename) && !empty($orderItemsData->images))
												{
													$pro_file='../uploads/product_images/small/'.$orderItemsData->images;
                                                    $pro_original_file='../uploads/product_images/'.$orderItemsData->images;													
												}
												?>
												<a href="<?=$pro_original_file?>">
												<img src="<?=$pro_file?>" class="img-thumbnail" width="70px" height="70px"/>
												</a>											
												</td>
                                                <td><i class="fa fa-inr"></i> <?=$orderItemsData->price?></td>
                                                <td><?=$orderItemsData->product_quantity?></td>
                                                <td><i class="fa fa-inr"></i> <?=number_format($orderItemsData->price*$orderItemsData->product_quantity)?></td>
                                            </tr>
										<?php $srno++;} ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                <div class="row invoice-payment">
                                        <div class="col-sm-7">
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="m-b-20">
                                                <h6>Total due</h6>
                                                <div class="table-responsive no-border">
                                                    <table class="table m-b-0">
                                                        <tbody>
                                                            <tr>
                                                                <th>Subtotal:</th>
                                                                <td class="text-right"><i class="fa fa-inr"></i> <?=number_format($orderData->order_sub_total)?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Tax: <span class="text-regular">(0%)</span></th>
                                                                <td class="text-right"><i class="fa fa-inr"></i> <?=number_format($orderData->tax_amount)?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Total:</th>
                                                                <td class="text-right text-primary">
                                                                    <h5><i class="fa fa-inr"></i> <?=number_format($orderData->order_total)?></h5>
																</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invoice-info">
                                        <h5>Delivery Instructions:</h5>
                                        <p class="text-muted"><?=(isset($orderData->user_order_remark) && !empty($orderData->user_order_remark) && $orderData->user_order_remark != '')? $orderData->user_order_remark:'Not Given';?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
 <!-- printThis -->
<script type="text/javascript" src="<?=base_url(); ?>/assets/js/printThis.js"></script>
<script>
/*----------- BEGIN validate CODE -------------------------*/
$('#update-order').validate({
	ignore: []
});
function getEditData(slider_id){
	var validator = $( "form#edit-slider" ).validate();
	validator.resetForm();
	var dataString="request=edit_slider_data&slider_id="+slider_id;
	//alert(dataString);
	//return false;
	jQuery.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>" + "ajax/ajaxProcess",
		dataType: 'json',
		data: dataString,
		success: function(res) {
			//console.log(res.dataContent);
			//return false;
			if (res.dataContent)
			{
				if(res.dataContent != ''){
					$("form[name=edit-slider] input[name='id']").val(res.dataContent.id);
					$("form[name=edit-slider] #slider_name").val(res.dataContent.slider_name);
					$("form[name=edit-slider] #slider_content").val(res.dataContent.slider_content);
					$("form[name=edit-slider] #slider_url").val(res.dataContent.slider_url);
					$('form[name=edit-slider] #status').val(res.dataContent.status).trigger('change');
					img_src= 'uploads/no-image100x100.jpg';
					if (res.dataContent.slider_image != '')
					{
						img_src='../uploads/slider_images/small/'+res.dataContent.slider_image;				
					}									
                    $('form[name=edit-slider] img#slider_image_file').prop('src', img_src);					
					console.log(res.dataContent);
				}else if (res.dataContent == ''){
					console.log(res);
				}
			}
		}
	});
}
$('#print').on('click',function(){
	 $('#printarea').printThis({
        importCSS: true,
		debug: false,
        header: "<h1>Order Details</h1>",
        base: "<?=base_url(); ?>/Brijwasi/"
      });
})
</script>