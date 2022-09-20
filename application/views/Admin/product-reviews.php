<?php
$download_url_query=$hideShow='';
if(isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])){
	$download_url_query='&'.$_SERVER['QUERY_STRING'];
}
$hideShow= (isset($doKeyword) && !empty($doKeyword))? ' style="display:block"':' style="display:none"';
?>
<div class="page-wrapper">
            <div class="content container-fluid">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title"><?=$title?></h4>
                    </div>
                    <div class="col-sm-8 col-9 text-right m-b-20"<?=$hideShow?>>
                        <a href="javascript:window.history.back();" class="btn btn-info btn-rounded pull-right"><i class="fa fa-arrow-left"></i> Back To List</a>
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
					if(isset($doKeyword) && !empty($doKeyword)){
					 $hidden["do"] = $doKeyword;	
					}
				// Form Open
				echo form_open('admin/product_reviews',$form_attribute,$hidden);
				?>               			
                <div class="row filter-row">
					<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                        <div class="form-group form-focus">
                            <label class="focus-label">From</label>
                            <div class="cal-icon">
                                <input class="form-control floating datetimepicker" type="text" name="review_date_from" id="review_date_from" value="<?=(isset($searchreviewFromKeyword) && !empty($searchreviewFromKeyword))?dateFormat("d-m-Y",$searchreviewFromKeyword):'';?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                        <div class="form-group form-focus">
                            <label class="focus-label">To</label>
                            <div class="cal-icon">
                                <input class="form-control floating datetimepicker" type="text" name="review_date_to" id="review_date_to" value="<?=(isset($searchreviewToKeyword) && !empty($searchreviewToKeyword))?dateFormat("d-m-Y",$searchreviewToKeyword):'';?>">
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
                            <label class="focus-label">Products</label>
                            <select class="select floating" name="pro_id" id="pro_id">
                                <option value=""> -- Select -- </option>
                                <?php foreach($productsData as $key=>$productsData){?>
								<option value="<?=$productsData->id?>" <?=(isset($productIDKeyword) && !empty($productIDKeyword) && $productIDKeyword==$productsData->id)? 'selected':'';?>><?=$productsData->product_name?></option>
								<?php } ?>
                            </select>
                        </div>
                    </div>
					<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Status</label>
                            <select class="select floating" name="review_status" id="review_status">
                                <option value=""> -- Select -- </option>
                                <option value="1" <?=(isset($statusKeyword) && !empty($statusKeyword) && $statusKeyword==1)? 'selected':'';?>>Approved</option>
                                <option value="0" <?=(isset($statusKeyword) && $statusKeyword != '' && $statusKeyword==0)? 'selected':'';?>>Pending</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-1 col-md-1">
                        <button type="submit" class="btn btn-success">Search</button>
                    </div>
                    <div class="col-sm-1 col-md-1">
						<button type="button" class="btn btn-danger" onclick="javascript:window.location.href='<?=base_url('admin/product_reviews')?>';">Clear</button>
                    </div>					
                </div>
				<?php
					// Form Close
					echo form_close(); ?>
				<?php if($this->session->flashdata('pro_success')){ ?>
					<div class="alert alert-success">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Success!</strong> <?php echo $this->session->flashdata('pro_success'); ?>
					</div>

				<?php }else if($this->session->flashdata('pro_error')){  ?>
					<div class="alert alert-danger">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Error!</strong> <?php echo $this->session->flashdata('pro_error'); ?>
					</div>
				<?php }?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table datatable">
                                <thead>
                                    <tr>
									    <th>Sr.No.</th>
										<th>Title</th>
                                        <th>Description</th>
                                        <th>Username</th>
                                        <th>Product Name</th>                                       
										<th>Review Date</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								    <?php
									//echo "<pre>";print_r($productReviewsList);
									$srno=1;
                                    $count = 0;
                                    foreach($productReviewsList as $productReviewsList){								
									$count++;
								    $class=($count % 2 == 1) ? " odd" : " even";
									//$productImage=getProductImage($ordersList->id,$limit=1);
									//echo "<pre>";print_r($productImage);
									?>
                                    <tr role="row" class="<?=$class?>">
									    <td><?=$srno?></td>
										<td><?=$productReviewsList->title?></td>
										<td><?=$productReviewsList->description?></td>
										<td><?=$productReviewsList->username?></td>
										<td><?=$productReviewsList->product_name?></td>
										<td><?=dateFormat('d-m-Y',$productReviewsList->review_date)?></td>
                                        <td>
                                            <div class="dropdown action-label">
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												
												<?=(isset($productReviewsList->review_status) && $productReviewsList->review_status==1)?'<i class="fa fa-dot-circle-o text-success"></i> Approved':'<i class="fa fa-dot-circle-o text-danger"></i> Pending';?>
												</a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="<?=base_url('admin/product_review_status?do=active&pro_r_id='.$productReviewsList->id)?>"><i class="fa fa-dot-circle-o text-success"></i> Approved</a>
                                                    <a class="dropdown-item" href="<?=base_url('admin/product_review_status?do=inactive&pro_r_id='.$productReviewsList->id)?>"><i class="fa fa-dot-circle-o text-danger"></i> Pending</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item delete-product-review" href="javascript:void(0);" id="<?=$productReviewsList->id?>" data-toggle="modal" data-target="#delete_product_review"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
        <div id="delete_product_review" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content modal-md">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete Order</h4>
                    </div>
                    <div class="modal-body card-box">
					 <?php
						$form_attribute=array(
								'name' => 'delete-product-review',
								'class' => 'form-horizontal',
								'method'=>"post",
								'id' => 'delete-product-review',
								'novalidate' => 'novalidate',
								);
						$hidden = array('action' => 'deleteProductReview','pro_r_id'=>'');
						//Form Open
						echo form_open_multipart('admin/delete_product_review',$form_attribute,$hidden);
						?>
                        <p>Do you want to delete the product reviews now with his related table data? This cannot be undone.</p>
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
$("body").on('click','.delete-product-review',function(event) {
	event.preventDefault();
	var stringArrayId=$(this).prop("id");
	if(stringArrayId > 0){
		$("form[name=delete-product-review] input[name='pro_r_id']").val(stringArrayId);
	}
	//alert(stringArrayId);	
});
</script>