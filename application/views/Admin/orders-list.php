<?php
$download_url_query='';
if(isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])){
	$download_url_query='&'.$_SERVER['QUERY_STRING'];
}
?>
<div class="page-wrapper">
            <div class="content container-fluid">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title"><?=$title?></h4>
                    </div>
                    <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="<?=base_url('admin/orders_list?do=download-excel'.$download_url_query)?>" class="btn btn-dark pull-right"><i class="fa fa-download"></i> Download orders data in excel</a>
                    </div>
                </div>
				<?php
			   $form_attribute=array(
						'name' => 'search-order',
						'class' => '',
						'method' =>"get",
						'autocomplete'=>"off",
						'id' => 'search-order',
						'novalidate' => 'novalidate',
						);
				$hidden = array('action' => 'search-order');
				// Form Open
				echo form_open('admin/orders_list',$form_attribute,$hidden);
				?>
                			
                <div class="row filter-row">
				    <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                        <div class="form-group form-focus">
                            <label class="focus-label">Order Number</label>
                            <input type="text" class="form-control floating" name="serach-query" id="serach-query" value="<?=(isset($searchordernumberKeyword) && !empty($searchordernumberKeyword))?$searchordernumberKeyword:'';?>">
                        </div>
                    </div>
					<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                        <div class="form-group form-focus">
                            <label class="focus-label">From</label>
                            <div class="cal-icon">
                                <input class="form-control floating datetimepicker" type="text" name="order_date_from" id="order_date_from" value="<?=(isset($searchorderFromKeyword) && !empty($searchorderFromKeyword))?dateFormat("d-m-Y",$searchorderFromKeyword):'';?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                        <div class="form-group form-focus">
                            <label class="focus-label">To</label>
                            <div class="cal-icon">
                                <input class="form-control floating datetimepicker" type="text" name="order_date_to" id="order_date_to" value="<?=(isset($searchorderToKeyword) && !empty($searchorderToKeyword))?dateFormat("d-m-Y",$searchorderToKeyword):'';?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Users</label>
                            <select class="select floating" name="user_id" id="user_id">
                                <option value=""> -- Select -- </option>
                                <?php foreach($usersData as $key=>$usersData){?>
								<option value="<?=$usersData->id?>" <?=(isset($userIDKeyword) && !empty($userIDKeyword) && $userIDKeyword==$usersData->id)? 'selected':'';?>><?=$usersData->name?></option>
								<?php } ?>
                            </select>
                        </div>
                    </div>
					<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Status</label>
                            <select class="select floating" name="order_status" id="order_status">
                                <option value=""> -- Select -- </option>
								<?php foreach($orderStatus as $key=>$orderStatus){?>
								<option value="<?=$key?>" <?=(isset($statusKeyword) && !empty($statusKeyword) && $statusKeyword==$orderStatus)? 'selected':'';?>><?=$orderStatus?></option>
								<?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-1 col-md-1">
                        <button type="submit" class="btn btn-success">Search</button>
                    </div>
                    <div class="col-sm-1 col-md-1">
						<button type="button" class="btn btn-danger" onclick="javascript:window.location.href='<?=base_url('admin/orders_list')?>';">Clear</button>
                    </div>					
                </div>
				<?php
					// Form Close
					echo form_close(); ?>
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table datatable">
                                <thead>
                                    <tr>
									    <th>Sr.No.</th>
                                        <th>Order Id</th>
                                        <th>Order Date</th>
                                        <th>Customer Name</th>
                                        <th>No. of Items</th>
                                        <th>Amount</th>
										<th>Payment Mode</th>
                                        <th>Order Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								    <?php
									//echo "<pre>";print_r($ordersList);
									$srno=1;
                                    $count = 0;
                                    foreach($ordersList as $ordersList){								
									$count++;
								    $class=($count % 2 == 1) ? " odd" : " even";
									//$productImage=getProductImage($ordersList->id,$limit=1);
									//echo "<pre>";print_r($productImage);
									?>
                                    <tr role="row" class="<?=$class?>">
									    <td><?=$srno?></td>
										<td><a href="#">#<?=$ordersList->order_number?></a></td>
										<td><?=dateFormat('d-m-Y',$ordersList->order_date)?></td>
										<td><?=$ordersList->username?></td>
										<td><?=$ordersList->number_of_items?></td>
										<td><p class="price-sup"><i class="fa fa-inr"></i> <?=$ordersList->order_total?></p></td>
										<td><span class="badge badge-info-border"><?=$ordersList->payment_method?></span></td>
										<td><span class="badge badge-<?=$orderStatusClass[$ordersList->order_status];?>-border"><?=$ordersList->order_status?></span></td>
                                        <!--<td>
                                            <div class="dropdown action-label">
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												
												<?//=(isset($ordersList->status) && $ordersList->status==1)?'<i class="fa fa-dot-circle-o text-success"></i> Active':'<i class="fa fa-dot-circle-o text-danger"></i> Inactive';?>
												</a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="<?//=base_url('admin/product_status?do=active&pro_id='.$ordersList->id)?>"><i class="fa fa-dot-circle-o text-success"></i> Active</a>
                                                    <a class="dropdown-item" href="<?//=base_url('admin/product_status?do=inactive&pro_id='.$ordersList->id)?>"><i class="fa fa-dot-circle-o text-danger"></i> Inactive</a>
                                                </div>
                                            </div>
                                        </td>-->
                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="<?=base_url('admin/order_view?do=order_view&order_id='.$ordersList->id)?>"><i class="fa fa-eye m-r-5"></i> View Order</a>
                                                    <a class="dropdown-item delete-order" href="javascript:void(0);" id="<?=$ordersList->id?>" data-toggle="modal" data-target="#delete_order"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
								    <?php $srno++; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="delete_order" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content modal-md">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete Order</h4>
                    </div>
                    <div class="modal-body card-box">
					 <?php
						$form_attribute=array(
								'name' => 'delete-order',
								'class' => 'form-horizontal',
								'method'=>"post",
								'id' => 'delete-order',
								'novalidate' => 'novalidate',
								);
						$hidden = array('action' => 'deleteOrder','order_id'=>'');
						//Form Open
						echo form_open_multipart('admin/delete_order',$form_attribute,$hidden);
						?>
                        <p>Do you want to delete the Order now with his related table data? This cannot be undone.</p>
                        <div class="m-t-20"> <a href="javascript:void(0);" class="btn btn-white" data-dismiss="modal">Close</a>						   
                            <button type="submit" class="btn btn-danger">Delete</button>							
                        </div>
                    </div>
					<?php
					// Form Close
					echo form_close(); ?>
                </div>
            </div>
        </div>
<script>
//delete order
$("body").on('click','.delete-order',function(event) {
	event.preventDefault();
	var stringArrayId=$(this).prop("id");
	if(stringArrayId > 0){
		$("form[name=delete-order] input[name='order_id']").val(stringArrayId);
	}
	//alert(stringArrayId);	
});
</script>