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
                        <a href="#" class="btn btn-primary btn-rounded pull-right" data-toggle="modal" data-target="#add_product" onClick="javascript:$('form#add-product')[0].reset();var validator = $( 'form#add-product' ).validate();validator.resetForm();$('form#add-product select').val('').trigger('change');"><i class="fa fa-plus"></i> Add Product</a>
						
						<a href="<?=base_url('admin/products_list?do=download-excel'.$download_url_query)?>" class="btn btn-dark pull-right" style="margin-right: 20px;"><i class="fa fa-download"></i> Download Product data in excel</a>
                        <div class="view-icons">
                            <!---<a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
                            <a href="clients-list.html" class="list-view btn btn-link active"><i class="fa fa-bars"></i></a>-->
                        </div>
                    </div>
                </div>
				<?php
			   $form_attribute=array(
						'name' => 'search-product',
						'class' => '',
						'method' =>"get",
						'autocomplete'=>"off",
						'id' => 'search-product',
						'novalidate' => 'novalidate',
						);
				$hidden = array('action' => 'search-product');
				// Form Open
				echo form_open('admin/products_list',$form_attribute,$hidden);
				?>						
                <div class="row filter-row">
				
                    <div class="col-sm-3 col-md-3">
                        <div class="form-group form-focus">
                            <label class="focus-label">Product Name or ID</label>
                            <input type="text" class="form-control floating" name="serach-query" id="serach-query" value="<?=(isset($searchproKeyword) && !empty($searchproKeyword))?$searchproKeyword:'';?>">
                        </div>
                    </div>
					<div class="col-sm-1 col-md-2">
                        <div class="fform-group form-focus select-focus">
                            <label class="focus-label">Category</label>
                            <select class="select floating" name="category_id" id="search_category_id">
                                <option value="">--Select--</option>
								<?php if (!empty($catData)){foreach($catData as $catDataV){?>
                                <option value="<?=$catDataV->id?>" <?=(isset($searchcategoryKeyword) && !empty($searchcategoryKeyword) && $searchcategoryKeyword==$catDataV->id)? 'selected':'';?>><?=$catDataV->name?></option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>
					<div class="col-sm-3 col-md-3">
                        <div class="fform-group form-focus select-focus">
                            <label class="focus-label">Sub Category</label>
                            <select class="select floating" name="sub_category_id" id="sub_category_id">
                                <option value="">--Select--</option>
									<!--option load ajax-->
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-1 col-md-2">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Status</label>
                            <select class="select floating" name="status" id="status">
                                <option value="">--Select--</option>
                                <option value="1" <?=(isset($statusKeyword) && !empty($statusKeyword) && $statusKeyword==1)? 'selected':'';?>>Active</option>
                                <option value="0" <?=(isset($statusKeyword) && $statusKeyword != '' && $statusKeyword==0)? 'selected':'';?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-1 col-md-1">
                        <button type="submit" class="btn btn-success">Search</button>
                    </div>
                    <div class="col-sm-1 col-md-1">
						<button type="button" class="btn btn-danger" onclick="javascript:window.location.href='<?=base_url('admin/products_list')?>';">Clear</button>
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
                                        <th>Product</th>
                                        <th>Product Id</th>
                                        <th>Created Date</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Popular</th>
                                        <th>Stock Status</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								    <?php
									//echo "<pre>";print_r($productList);
									$srno=1;
                                    $count = 0;
                                    foreach($productList as $productList){								
									$count++;
								    $class=($count % 2 == 1) ? " odd" : " even";
									$productImage=getProductImage($productList->id,$limit=1);
									//echo "<pre>";print_r($productImage);
									?>
                                    <tr role="row" class="<?=$class?>">
									    <td><?=$srno?></td>
										<td>
                                            <div class="product-det">
											<div class="lightgallery" style="float: left;">
											    <?php												
												$pro_file= '../uploads/no-image100x100.jpg';
												$pro_original_file= '../uploads/no-image400x400.jpg';
												if(count($productImage) > 0 && !empty($productImage)){
													$profilename = 'uploads/product_images/'.$productImage[0]->images;
													if (file_exists($profilename) && !empty($productImage[0]->images))
													{
														$pro_file='../uploads/product_images/small/'.$productImage[0]->images;
                                                        $pro_original_file='../uploads/product_images/'.$productImage[0]->images;														
													}
												}
												?>
												<a href="<?=$pro_original_file?>">
												<img src="<?=$pro_file?>" class="img-thumbnail" width='70px'/>
												</a>
												</div>
                                                <div class="product-desc" style="padding: 0 0 0 77px;">
                                                    <h2><a href="#"><?=$productList->product_name?></a> <span><?=truncate($productList->description, $length=70, $stopanywhere=false)?></span></h2>
												</div>
                                            </div>
                                        </td>
										<td><a href="#">#<?=$productList->product_code?></a></td>
										<td><?=dateFormat('d-m-Y',$productList->date_added)?></td>
										<td><?=$productList->quantity?></td>
										<td><p class="price-sup"><i class="fa fa-inr"></i> <?=$productList->price?></p></td>
										<td><span class="badge badge-<?=(isset($productList->is_popular) && $productList->is_popular == 1)?'success':'danger';?>-border"><?=(isset($productList->is_popular) && $productList->is_popular == 1)?'Yes':'No';?></span></td>
										<td><span class="badge badge-<?=(isset($productList->stock_availability) && $productList->stock_availability == 1)?'success':'danger';?>-border"><?=(isset($productList->stock_availability) && $productList->stock_availability == 1)?'In Stock':'Out of Stock';?></span></td>
                                        <!--<td><?//=$productList->cat_name?></td>-->
										<!--<td><?//=$productList->subcat_name?></td>-->
                                        <td>
                                            <div class="dropdown action-label">
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												
												<?=(isset($productList->status) && $productList->status==1)?'<i class="fa fa-dot-circle-o text-success"></i> Active':'<i class="fa fa-dot-circle-o text-danger"></i> Inactive';?>
												</a>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="<?=base_url('admin/product_status?do=active&pro_id='.$productList->id)?>"><i class="fa fa-dot-circle-o text-success"></i> Active</a>
                                                    <a class="dropdown-item" href="<?=base_url('admin/product_status?do=inactive&pro_id='.$productList->id)?>"><i class="fa fa-dot-circle-o text-danger"></i> Inactive</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
												    <a class="dropdown-item" href="<?=base_url('admin/product_reviews?do=product_review&pro_id='.$productList->id)?>"><i class="fa fa-star m-r-5"></i> View User Review</a>
                                                    <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#edit_product" onClick="getEditData(<?=$productList->id?>);"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                    <a class="dropdown-item delete-product" href="javascript:void(0);" id="<?=$productList->id?>" data-toggle="modal" data-target="#delete_product"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
        <div id="add_product" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Product</h4>
                    </div>
                    <div class="modal-body">
						<div class="m-b-30">
                             <?php
								$form_attribute=array(
										'name' => 'add-product',
										'class' => 'form-horizontal',
										'method'=>"post",
										'id' => 'add-product',
										'novalidate' => 'novalidate',
										);
								$hidden = array('action' => 'addProduct');
								// Form Open
								echo form_open_multipart('admin/add_product',$form_attribute,$hidden);
							?>
                            <div class="form-group">
                                <label>Product Code <span class="text-danger">*</span></label>
                                <input class="form-control required" type="text" name="product_code" id="product_code">
                            </div>
                            <div class="form-group">
                                <label>Product Name <span class="text-danger">*</span></label>
                                <input class="form-control required" type="text" name="product_name" id="product_name">
                            </div>
                            <div class="form-group">
                                <label>Product Images <span class="text-danger">*</span></label>
                                <div>
                                    <input class="form-control required" type="file" name="product_images[]" id="product_images" multiple>
                                    <small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png. Maximum 10 images only.</small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Product Category <span class="text-danger">*</span></label>
                                        <select class="select required" name="category_id" id="category_id">
                                            <option value="">Select Category</option>
                                            <?php 
											if (!empty($catData)){
												foreach($catData as $catDataQ){?>
													<option value="<?=$catDataQ->id?>"><?=$catDataQ->name?></option>
											<?php } 
											} ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Product Sub Category <span class="text-danger"></span></label>
                                        <select class="select required" name="sub_category_id" id="sub_category_id">
										 <option value="">Select Sub Category</option>
                                          <!--option load ajax-->
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Quantity <span class="text-danger">*</span></label>
                                        <input class="form-control required digits" type="text" name="quantity" id="quantity">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Price <span class="text-danger">*</span></label>										
                                         <div class="clearfix"></div>
										  <div style="width:49%; float:left; margin-right:2%;"><input class="form-control required number" type="text" name="price" id="price"> </div>
										  <div style="width:49%; float:left;">	
										  <select class="select required" name="price_type" id="price_type">
										  <option value="">Type</option>
										  <?php foreach($priceType as $key=>$priceTypeF){?>
											 <option value="<?=$key?>"><?=$priceTypeF?></option>
										  <?php } ?>
									     </select>
										 </div>
                                    </div>
                                </div>
                            </div>
							<div class="row">
								 <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Product Stock Availability <span class="text-danger">*</span></label>
                                        <select class="select required" name="stock_availability" id="stock_availability">
                                            <option value="">Select Stock</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
								 <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Delivery Time (in days) <span class="text-danger">*</span></label>
                                        <input class="form-control required" type="text" name="delivered_in_days" id="delivered_in_days">
                                    </div>
                                </div>
                            </div>
							<div class="form-group">
                                <label>Product Pin Code <span class="text-danger">*</span></label>
                                <input class="form-control required digits" type="text" name="product_pincode" id="product_pincode" minlength="6" maxlength="6">
                            </div>
                            <div class="form-group">
                                <label>Product Description <span class="text-danger">*</span></label>
                               <!-- <textarea cols="30" rows="6" class="form-control summernotes required" name="description" id="description"></textarea>-->
                                	<textarea class="form-control text-editor" name="description" id="description"></textarea>
                            </div>
							<!--<div class="form-group">
                                <label>Product Additional Information<span class="text-danger"></span></label>
                                <textarea cols="30" rows="6" class="form-control summernotes requireds" name="additional_info" id="additional_info"></textarea>
                            </div>-->
							<div class="row">
                                <div class="col-md-6">
								<div class="form-group">
									<label class="display-block">Popular/Featured <span class="text-danger">*</span></label>
									<div class="form-check form-check-inline">
										<input class="form-check-input required" type="radio" name="is_popular" id="is_yes" value="1" checked>
										<label class="form-check-label" for="is_yes">Yes</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input required" type="radio" name="is_popular" id="is_no" value="0">
										<label class="form-check-label" for="is_no">No</label>
									</div>
								</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="display-block">New Product<span class="text-danger">*</span></label>
										<div class="form-check form-check-inline">
											<input class="form-check-input required" type="radio" name="is_new" id="is_yes" value="1" checked>
											<label class="form-check-label" for="is_yes">Yes</label>
										</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input required" type="radio" name="is_new" id="is_no" value="0">
											<label class="form-check-label" for="is_no">No</label>
										</div>
									</div>
								</div>
							</div>
                            <div class="form-group">
                                <label class="display-block">Product Status <span class="text-danger">*</span></label>
								<div class="form-check form-check-inline">
									<input class="form-check-input required" type="radio" name="status" id="product_active" value="1" checked>
									<label class="form-check-label" for="product_active">Active</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input required" type="radio" name="status" id="product_inactive" value="0">
									<label class="form-check-label" for="product_inactive">Inactive</label>
								</div>
                            </div>
                            <div class="m-t-20 text-center">
                                <button class="btn btn-primary btn-lg" type="submit">Publish Product</button>
                            </div>
                            <?php
							// Form Close
							echo form_close(); ?>
							</div>
                        </div>
                    </div>
                </div>
        </div>
        <div id="edit_product" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Product</h4>
                    </div>
                    <div class="modal-body">
                        <div class="m-b-30">
                            <?php
								$form_attribute=array(
										'name' => 'edit-product',
										'class' => 'form-horizontal',
										'method'=>"post",
										'id' => 'edit-product',
										'novalidate' => 'novalidate',
										);
								$hidden = array('action' => 'editProduct','id'=>'');
								// Form Open
								echo form_open_multipart('admin/add_product',$form_attribute,$hidden);
								?>
                            <div class="form-group">
                                <label>Product Code <span class="text-danger">*</span></label>
                                <input class="form-control required" type="text" name="product_code" id="product_code" readonly>
                            </div>
                            <div class="form-group">
                                <label>Product Name <span class="text-danger">*</span></label>
                                <input class="form-control required" type="text" name="product_name" id="product_name">
                            </div>
                            <div class="form-group">
                                <label>Product Images </label>
                                <div>
                                    <input class="form-control" type="file" name="product_images[]" id="product_images" multiple>
                                    <small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png. Maximum 10 images only.</small>
                                </div>
								<div class="row" id="product-image">
                                 <!--load via ajax---------->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Product Category <span class="text-danger">*</span></label>
                                        <select class="select required" name="category_id" id="category_id">
                                            <option value="">Select Category</option>
                                            <?php 
											if (!empty($catData)){
												foreach($catData as $catDataF){?>
													<option value="<?=$catDataF->id?>"><?=$catDataF->name?></option>
											<?php } 
											} ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Product Sub Category <span class="text-danger"></span></label>
                                        <select class="select required" name="sub_category_id" id="sub_category_id">
										 <option value="">Select Sub Category</option>
                                          <!--option load ajax-->
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Quantity <span class="text-danger">*</span></label>
                                        <input class="form-control required digits" type="text" name="quantity" id="quantity">
                                    </div>
                                </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Price <span class="text-danger">*</span></label>										
                                         <div class="clearfix"></div>
										  <div style="width:49%; float:left; margin-right:2%;"><input class="form-control required number" type="text" name="price" id="price"> </div>
										  <div style="width:49%; float:left;">	
										  <select class="select required" name="price_type" id="price_type">
										  <option value="">Type</option>
										  <?php foreach($priceType as $key=>$priceTypeE){?>
											 <option value="<?=$key?>"><?=$priceTypeE?></option>
										  <?php } ?>
									     </select>
										 </div>
                                    </div>
                                </div>
                            </div>
							<div class="row">
								 <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Product Stock Availability <span class="text-danger">*</span></label>
                                        <select class="select required" name="stock_availability" id="stock_availability">
                                            <option value="">Select Stock</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
								 <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Delivery Time (in days) <span class="text-danger">*</span></label>
                                        <input class="form-control required" type="text" name="delivered_in_days" id="delivered_in_days">
                                    </div>
                                </div>
                            </div>
							<div class="form-group">
                                <label>Product Pin Code <span class="text-danger">*</span></label>
                                <input class="form-control required digits" type="text" name="product_pincode" id="product_pincode" minlength="6" maxlength="6">
                            </div>
                            <div class="form-group">
                                <label>Product Description <span class="text-danger">*</span></label>
                                <!--<textarea cols="30" rows="6" class="form-control summernotes required" name="description" id="description"></textarea>-->
                                <textarea class="form-control text-editor" name="description" id="description"></textarea>
                            </div>
						<!--	<div class="form-group">
                                <label>Product Additional Information<span class="text-danger"></span></label>
                                <textarea cols="30" rows="6" class="form-control summernotes requireds" name="additional_info" id="additional_info"></textarea>
                            </div>-->
							<div class="row">
                                <div class="col-md-6">
								<div class="form-group">
									<label class="display-block">Popular/Featured <span class="text-danger">*</span></label>
									<div class="form-check form-check-inline">
										<input class="form-check-input required" type="radio" name="is_popular" id="is_yes" value="1">
										<label class="form-check-label" for="is_yes">Yes</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input required" type="radio" name="is_popular" id="is_no" value="0" checked>
										<label class="form-check-label" for="is_no">No</label>
									</div>
								</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="display-block">New Product<span class="text-danger">*</span></label>
										<div class="form-check form-check-inline">
											<input class="form-check-input required" type="radio" name="is_new" id="is_n_yes" value="1">
											<label class="form-check-label" for="is_n_yes">Yes</label>
										</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input required" type="radio" name="is_new" id="is_n_no" value="0" checked>
											<label class="form-check-label" for="is_n_no">No</label>
										</div>
									</div>
								</div>
							</div>
                            <div class="form-group">
                                <label class="display-block">Product Status <span class="text-danger">*</span></label>
								<div class="form-check form-check-inline">
									<input class="form-check-input required" type="radio" name="status" id="product_active" value="1" checked>
									<label class="form-check-label" for="product_active">Active</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input required" type="radio" name="status" id="product_inactive" value="0">
									<label class="form-check-label" for="product_inactive">Inactive</label>
								</div>
                            </div>
                            <div class="m-t-20 text-center">
                                <button class="btn btn-primary btn-lg">Save Changes</button>
                            </div>
                            <?php
							// Form Close
							echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="delete_product" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content modal-md">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete Product</h4>
                    </div>
                    <div class="modal-body card-box">
					 <?php
						$form_attribute=array(
								'name' => 'delete-product',
								'class' => 'form-horizontal',
								'method'=>"post",
								'id' => 'delete-product',
								'novalidate' => 'novalidate',
								);
						$hidden = array('action' => 'deleteProduct','pro_id'=>'');
						//Form Open
						echo form_open_multipart('admin/delete_product',$form_attribute,$hidden);
						?>
                        <p>Do you want to delete the product now with his related table data? This cannot be undone.</p>
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
$.validator.addMethod("extension", function (value, element, param) {
	param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
	return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
}, jQuery.format("Please enter a value with a valid extensions."));
		
$.validator.addMethod("checkEditProductNameAvailable", 
	 function(value, element) {
			var result = false;
			product_id=$("form[name=edit-product] input[name='id']").val();
			$.ajax({
				type:"POST",
				async: false,
				dataType:"json",
				url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
				data : "product_name="+value+"&request=check-product-name&action=edit-product&product_id="+product_id,
				success: function(data) {
					console.log(data);
					//return false;
					result = (data.dataContent== "0") ? true : false;
				}
			});
			// return true if SHOW NAME is exist in database
			return result; 
		}, 
		"This Product Name is already taken! Try another."
);

$.validator.addMethod("checkProductNameAvailable", 
	function(value, element) {
		var result = false;
		$.ajax({
			type:"POST",
			async: false,
			dataType:"json",
			url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
			data : "product_name="+value+"&request=check-product-name&action=add-product",
			success: function(data) {
				console.log(data);
				//return false;
				result = (data.dataContent== "0") ? true : false;
			}
		});
		// return true if SHOW NAME is exist in database
		return result; 
	}, 
	"This Product Name is already taken! Try another."
);

$.validator.addMethod("checkProductCodeAvailable", 
	function(value, element) {
		var result = false;
		$.ajax({
			type:"POST",
			async: false,
			dataType:"json",
			url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
			data : "product_code="+value+"&request=check-product-name&action=add-product-code",
			success: function(data) {
				console.log(data);
				//return false;
				result = (data.dataContent== "0") ? true : false;
			}
		});
		// return true if SHOW NAME is exist in database
		return result; 
	}, 
	"This Product Code is already taken! Try another."
);
/*----------- BEGIN validate CODE -------------------------*/
$('#add-product').validate({
	ignore: [],
	rules: {
	"product_code": {
		required: true,
		checkProductCodeAvailable: true,
	},
	"product_name": {
		required: true,
		checkProductNameAvailable: true,
	},
	"product_images[]": {
		  required: true,
		  extension: "gif|jpg|png"
		}
	}
});
$('#edit-product').validate({
	ignore: [],
	rules: {
	"product_name": {
		required: true,
		checkEditProductNameAvailable: true
	},
	"product_images[]": {
		  required: false,
		  extension: "gif|jpg|png"
		}
	}
});
function getEditData(pro_id){
	var validator = $( "form#edit-product" ).validate();
	validator.resetForm();
	var dataString="request=edit_product_data&pro_id="+pro_id;
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
					$("form[name=edit-product] input[name='id']").val(res.dataContent.id);
					$("form[name=edit-product] #product_code").val(res.dataContent.product_code);
					$("form[name=edit-product] #product_name").val(res.dataContent.product_name);
					$("form[name=edit-product] #category_id").val(res.dataContent.category_id).trigger('change');
					$("form[name=edit-product] #sub_category_id").val(res.dataContent.sub_category_id).trigger('change');
					$("form[name=edit-product] #price_type").val(res.dataContent.price_type).trigger('change');
					$("form[name=edit-product] #quantity").val(res.dataContent.quantity);
					$("form[name=edit-product] #price").val(res.dataContent.price);
					$("form[name=edit-product] #delivered_in_days").val(res.dataContent.delivered_in_days);
					$("form[name=edit-product] #product_pincode").val(res.dataContent.product_pincode);
					//$("form[name=edit-product] #description").val(res.dataContent.description);
					CKEDITOR.instances['description'].setData(res.dataContent.description);
					$("form[name=edit-product] #additional_info").val(res.dataContent.additional_info);
					//$("form[name=edit-product] #description").summernote("code", res.dataContent.description);
					//$("form[name=edit-product] #additional_info").summernote("code", res.dataContent.additional_info);
					$('form[name=edit-product] #stock_availability').val(res.dataContent.stock_availability).trigger('change');
					$('form[name=edit-product] input[name=status][value='+res.dataContent.status+']').prop("checked",true);
					$('form[name=edit-product] input[name=is_new][value='+res.dataContent.is_new+']').prop("checked",true);	
                    $('form[name=edit-product] input[name=is_popular][value='+res.dataContent.is_popular+']').prop("checked",true);				
                    $('form[name=edit-product] div#product-image').html(res.productImage);					
					console.log(res.dataContent);
				}else if (res.dataContent == ''){
					console.log(res);
				}
			}
		}
	});
}
//delete product
$("body").on('click','.delete-product',function(event) {
	event.preventDefault();
	var stringArrayId=$(this).prop("id");
	if(stringArrayId > 0){
		$("form[name=delete-product] input[name='pro_id']").val(stringArrayId);
	}
	//alert(stringArrayId);	
});
//delete product image
$("body").on('click','.product-image-remove',function(event) {
	event.preventDefault();
	var stringArrayId=$(this).prop("id");
	if(stringArrayId > 0){
		var dataString="request=delete_product_image_data&pro_image_id="+stringArrayId;
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
					if(res.dataContent == 1){					
						$('form[name=edit-product] #product-image-'+res.id).remove();					
						console.log(res.dataContent);
					}else if (res.dataContent == 0){
						console.log(res.dataContent);
					}
				}
			}
		});
	}
	//alert(stringArrayId);	
});
// Ajax post
$(document).ready(function() {
	$("select[name=category_id]").change(function(event,param1,param2)
	{
		var id = $(this).val();
		if(id > 0){
		var dataString = 'cat_id=' + id +'&request=get_sub_category';
		//alert(dataString);
		//return false;
		$.ajax({
				type: "POST",
				url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
				data: dataString,
				cache: false,
				async: false,
				dataType:"json",
				success: function (html)
				{
					//alert('sujeet');
					$("form#edit-product select[name='sub_category_id'],form#add-product select[name='sub_category_id']").html('').html(html.subCatList);
					//$("select[name='category_id']").find("option").eq(0).remove();
					if(param1!="")
					{
					 $("form#edit-product select[name='sub_category_id']").val(param2);
					}
				}
			});
		}
	});
	
	$("select[id=search_category_id]").change(function(event,param1,param2)
	{
		var id = $(this).val();
		if(id > 0){
		var dataString = 'cat_id=' + id +'&request=get_sub_category';
		//alert(dataString);
		//return false;
		$.ajax({
				type: "POST",
				url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
				data: dataString,
				cache: false,
				async: false,
				dataType:"json",
				success: function (html)
				{
					//alert('sujeet');
					$("form#search-product select[name='sub_category_id']").html('').html(html.subCatList);
					//$("select[name='category_id']").find("option").eq(0).remove();
					if(param1!="")
					{
					 $("form#search-product select[name='sub_category_id']").val(param2);
					}
				}
			});
		}
	});	
	<?php
	if(isset($searchcategoryKeyword) && !empty($searchcategoryKeyword)){?>
	 var cat_id='<?=$searchcategoryKeyword?>';
	 var sub_cat_id='<?=$searchsubcategoryKeyword?>'; 
	 $("select[id=search_category_id]").trigger('change', [cat_id,sub_cat_id]);
	<?php } ?>
});
</script>
<script src="https://cdn.ckeditor.com/4.8.0/standard-all/ckeditor.js"></script>
<script>
CKEDITOR.replaceClass="text-editor";
$("form[action=edit_service_description]").submit( function(e) {
	var messageLength = CKEDITOR.instances['service_description'].getData().replace(/<[^>]*>/gi, '').length;
	if( !messageLength ) {
		//alert('Please enter a Service Description.');
		$("#ckeditor-msg").html("<font color=red>Please enter a Page Content.</font>");
		CKEDITOR.instances.service_description.focus();
		e.preventDefault();
	}else{

		$("#ckeditor-msg").html("");
	}
});
</script>