<div class="page-wrapper">
            <div class="content container-fluid">
                <div class="row">
                    <div class="col-sm-4 col-3">
                        <h4 class="page-title"><?=$title?></h4>
                    </div>
					<?php
					if (getUserCan('case_studies_module', 'access_create')) {
					?>
                    <div class="col-sm-8 col-9 text-right m-b-20">
                        <a href="#" class="btn btn-primary btn-rounded pull-right" data-toggle="modal" data-target="#add_cs" onClick="javascript:$('form#add-cs')[0].reset();var validator = $( 'form#add-cs' ).validate();validator.resetForm();$('form#add-cs select').val('').trigger('change');$('#ckeditor-msg1').html('');$('#ckeditor-msg2').html('');$('#ckeditor-msg3').html('');getAllCategoriesData('');CKEDITOR.instances['backstory_content'].setData('');CKEDITOR.instances['problem_statement_content'].setData('');CKEDITOR.instances['the_challenge_content'].setData('');"><i class="fa fa-plus"></i> Add Case Study</a>
                        <div class="view-icons">
                            <!---<a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
                            <a href="clients-list.html" class="list-view btn btn-link active"><i class="fa fa-bars"></i></a>-->
                        </div>
                    </div>
					<?php  } ?>
                </div>
				<?php
			   $form_attribute=array(
						'name' => 'search-cs',
						'class' => '',
						'method' =>"get",
						'autocomplete'=>"off",
						'id' => 'search-cs',
						'novalidate' => 'novalidate',
						);
				$hidden = array('action' => 'search-cs');
				// Form Open
				echo form_open('admin/case_studies_list',$form_attribute,$hidden);
				?>						
                <div class="row filter-row">
                    <div class="col-sm-4 col-md-2">
                        <div class="form-group form-focus">
                            <label class="focus-label">Title</label>
                            <input type="text" class="form-control floating" name="serach-query" id="serach-query" value="<?=(isset($searchpagesKeyword) && !empty($searchpagesKeyword))?$searchpagesKeyword:'';?>">
                        </div>
                    </div>
					<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                        <div class="form-group form-focus">
                            <label class="focus-label">From</label>
                            <div class="cal-icon">
                                <input class="form-control floating datetimepicker" type="text" name="date_from" id="date_from" value="<?=(isset($searchuserFromKeyword) && !empty($searchuserFromKeyword))?dateFormat("d-m-Y",$searchuserFromKeyword):'';?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                        <div class="form-group form-focus">
                            <label class="focus-label">To</label>
                            <div class="cal-icon">
                                <input class="form-control floating datetimepicker" type="text" name="date_to" id="date_to" value="<?=(isset($searchuserToKeyword) && !empty($searchuserToKeyword))?dateFormat("d-m-Y",$searchuserToKeyword):'';?>">
                            </div>
                        </div>
                    </div>
					<div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Category</label>
                            <select class="select floating" name="cat_id" id="cat_id">
                                <option value="">--Select--</option>
								<?php
								foreach($AllCatDetails as $AllCatDetails){?>
                                <option value="<?=$AllCatDetails->id?>" <?=(isset($catIdKeyword) && !empty($catIdKeyword) && $catIdKeyword==$AllCatDetails->id)? 'selected':'';?>><?=$AllCatDetails->name?></option>
								<?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 col-lg-3 col-xl-2 col-12">
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Status</label>
                            <select class="select floating" name="status" id="status">
                                <option value="">--Select--</option>
                                <option value="1" <?=(isset($statusKeyword) && !empty($statusKeyword) && $statusKeyword==1)? 'selected':'';?>>Pending</option>
                                <option value="2" <?=(isset($statusKeyword) && $statusKeyword != '' && $statusKeyword==2)? 'selected':'';?>>Publish</option>
								<option value="3" <?=(isset($statusKeyword) && $statusKeyword != '' && $statusKeyword==3)? 'selected':'';?>>Draft</option>
                            </select>
                        </div>
                    </div>
                    <div class="ml-3">
                        <button type="submit" class="btn btn-success"> Search </button>
						<button type="button" class="btn btn-danger" onclick="javascript:window.location.href='<?=base_url('admin/case_studies_list')?>';"> Clear</button>
                    </div>					
                </div>
				<?php
					// Form Close
					echo form_close(); ?>
				<?php if($this->session->flashdata('cs_success')){ ?>
					<div class="alert alert-success">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Success!</strong> <?php echo $this->session->flashdata('cs_success'); ?>
					</div>

				<?php }else if($this->session->flashdata('cs_error')){  ?>
					<div class="alert alert-danger">
						<a href="#" class="close" data-dismiss="alert">&times;</a>
						<strong>Error!</strong> <?php echo $this->session->flashdata('cs_error'); ?>
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
                                        <th>Image</th>
										<th>Slug</th>
										<th>Categories</th>
                                        <th>Status</th>
										<th width="15%">Date Published</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
								    <?php
									$srno=1;
                                    $count = 0;
									$statusArray = [1=>'Pending',2=>'Publish',3=>'Draft'];
                                    foreach($caseStudiesList as $caseStudiesList){								
									$count++;
								    $class=($count % 2 == 1) ? " odd" : " even";
									?>
                                    <tr role="row" class="<?=$class?>">
									    <td><?=$srno?></td>
                                        <td><?=$caseStudiesList->case_study_title?></td>
										 <td class="lightgallery">
											<?php
											$pagefilename = 'uploads/case_study_images/'.$caseStudiesList->case_study_image;
											$page_file= '../uploads/no-image100x100.jpg';
											$page_original_file= '../uploads/no-image400x400.jpg';
											if (file_exists($pagefilename) && !empty($caseStudiesList->case_study_image))
											{
												$page_file='../uploads/case_study_images/small/'.$caseStudiesList->case_study_image;
                                                $page_original_file = '../uploads/case_study_images/'.$caseStudiesList->case_study_image;												
											}
											?>
											<a href="<?=$page_original_file?>">
												<img src="<?=$page_file?>" class="img-thumbnail" width="60%" height="60%"/>
											</a>
										</td>
										<td><?=$caseStudiesList->case_study_slug?></td>
                                        <td><?=($caseStudiesList->cat_name)?$caseStudiesList->cat_name:'-'?></td>
										<td>
                                            <div class="dropdown action-label">
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												<?php 
												switch($caseStudiesList->status){
													case 1:
													echo '<i class="fa fa-dot-circle-o text-warning"></i> Pending';
													break;
													case 2:
													echo '<i class="fa fa-dot-circle-o text-success"></i> Publish';
													break;
													case 3:
													echo '<i class="fa fa-dot-circle-o text-danger"></i> Draft';
													break;
												}
												?>
												</a>
												<?php
												if (getUserCan('case_studies_module', 'access_write')) {
												?>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="<?=base_url('admin/case_study_status?do=pending&case_study_id='.$caseStudiesList->id)?>"><i class="fa fa-dot-circle-o text-warning"></i> Pending</a>
                                                    <a class="dropdown-item" href="<?=base_url('admin/case_study_status?do=publish&case_study_id='.$caseStudiesList->id)?>"><i class="fa fa-dot-circle-o text-success"></i> Publish</a>
													<a class="dropdown-item" href="<?=base_url('admin/case_study_status?do=draft&case_study_id='.$caseStudiesList->id)?>"><i class="fa fa-dot-circle-o text-danger"></i> Draft</a>
                                                </div>
												<?php } ?>
                                            </div>
                                        </td>
			                            <td><?=$caseStudiesList->date_added?></td>
                                        <td class="text-right">
										<?php
										if (getUserCan('case_studies_module', 'access_write') || getUserCan('case_studies_module', 'access_delete')) {
										?>
                                            <div class="dropdown dropdown-action">
                                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
												<?php
												if (getUserCan('case_studies_module', 'access_write')) {
												?>
                                                    <a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#edit_cs" onClick="getEditData(<?=$caseStudiesList->id?>);getAllCategoriesData(<?=$caseStudiesList->id?>);"><i class="fa fa-pencil m-r-5"></i> Edit</a>
													<?php } 
													if (getUserCan('case_studies_module', 'access_delete')) {
													?>
                                                    <a class="dropdown-item delete-cs" href="javascript:void(0);" id="<?=$caseStudiesList->id?>" data-toggle="modal" data-target="#delete_cs"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
													<?php } ?>
                                                </div>
                                            </div>
										<?php } ?>
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
        <div id="add_cs" class="modal custom-modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-content modal-lg" style="width:100%">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Case Study</h4>
                    </div>
                    <div class="modal-body">
						<div class="m-b-30">
                             <?php
									$form_attribute=array(
											'name' => 'add-cs',
											'class' => 'form-horizontal',
											'method'=>"post",
											'id' => 'add-cs',
											'novalidate' => 'novalidate',
											);
									$hidden = array('action' => 'addCs');
									// Form Open
									echo form_open_multipart('admin/add_case_study',$form_attribute,$hidden);
								?>
							<div class="row">
							  <div class="col-sm-6">
								<div class="form-group">
									<label class="control-label">Case Study Title <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="case_study_title" id="case_study_title">
								</div>
							 </div>
							  <div class="col-sm-6">
								 <div class="form-group">
									<label class="control-label">Meta Tag <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="meta_tag" id="meta_tag">
								</div>
								</div>
							
							  <div class="col-sm-6">
							    <div class="form-group">
									<label class="control-label">Case Study Slug <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="case_study_slug" id="case_study_slug" readonly>
								</div>							
							  </div>
							  <div class="col-sm-6">
							   <div class="form-group">
									<label class="control-label">Meta Keyword <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="meta_keyword" id="meta_keyword">
								</div>
							 </div>
							 <div class="col-sm-6">
								<div class="form-group">
									<label class="control-label">Case Study Sub Title <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="case_study_sub_title" id="case_study_sub_title">
								</div>
							 </div>
							  <div class="col-sm-6">
							  <div class="form-group">
									<label class="control-label">Meta Description <span class="text-danger">*</span></label>
									<textarea class="form-control required" name="meta_description" id="meta_description"></textarea>
								</div>
							  </div>
							  <div class="col-sm-6">
								<div class="form-group">
									<label class="control-label">Case Study Logo <span class="text-danger">*</span></label>
									<div>
										<input class="form-control required" type="file" name="case_study_logo" id="case_study_logo">
										<small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png, svg.</small>
									</div>
								</div>
							 </div>
							 <div class="col-sm-6">
								<div class="form-group">
									<label class="control-label">Case Study Image <span class="text-danger">*</span></label>
									<div>
										<input class="form-control required" type="file" name="case_study_image" id="case_study_image">
										<small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png, svg.</small>
									</div>
								</div>
							 </div>
							 
								<div class="col-md-12">
									<div class="form-group">
										<label>Case Study Content <span class="text-danger">*</span></label>
										<textarea class="form-control required" name="case_study_long_content" id="case_study_long_content"></textarea>
									</div>
 								</div>
								 <div class="col-md-4">
									<div class="form-group">
										<label>Increase in website visitor <span class="text-danger">*</span></label>
										<input class="form-control required" type="text" name="website_visitor" id="website_visitor">
									</div>									
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Increase in organic Search traffic <span class="text-danger">*</span></label>
										<input class="form-control required" type="text" name="organic_search_traffic" id="organic_search_traffic">
									</div>
 								</div>
								 <div class="col-md-4">
									<div class="form-group">
										<label>Increase in the Conversation rate<span class="text-danger">*</span></label>
										<input class="form-control required" type="text" name="conversation_rate" id="conversation_rate">
									</div>									
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label>Backstory <span class="text-danger">*</span></label>
										<textarea class="form-control required text-editor" name="backstory_content" id="backstory_content"></textarea>
										<label for="backstory_content" generated="true" id="ckeditor-msg1"></label>
									</div>
 								</div>
								 <div class="col-md-12">
									<div class="form-group">
										<label>Problem Statement <span class="text-danger">*</span></label>
										<textarea class="form-control required text-editor" name="problem_statement_content" id="problem_statement_content"></textarea>
										<label for="problem_statement_content" generated="true" id="ckeditor-msg2"></label>
									</div>									
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label>The Challenge <span class="text-danger">*</span></label>
										<textarea class="form-control required text-editor" name="the_challenge_content" id="the_challenge_content"></textarea>
										<label for="the_challenge_content" generated="true" id="ckeditor-msg3"></label>
									</div>
 								</div>
								 <div class="col-md-12">
									<div class="form-group">
										<label>Case Study Bottom Title <span class="text-danger">*</span></label>
										<textarea class="form-control required" name="case_study_title_1" id="case_study_title_1"></textarea>
									</div>									
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Case Study Bottom Sub Title <span class="text-danger">*</span></label>
										<input class="form-control required" type="text" name="case_study_sub_title_1" id="case_study_sub_title_1">
									</div>
 								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Client Name <span class="text-danger">*</span></label>
										<input class="form-control required" type="text" name="client_name" id="client_name">
									</div>
 								</div>
								 <div class="col-md-6">
									<div class="form-group">
										<label>Clicks impressions SEO overview Image<span class="text-danger">*</span></label>
										<div>
											<input class="form-control required" type="file" name="clicks_impressions_seo_overview_image" id="clicks_impressions_seo_overview_image">
											<small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png, svg.</small>
										</div>
									</div>									
								</div>
								 <div class="col-md-6">
									<div class="form-group">
										<label>Client Profile Picture<span class="text-danger">*</span></label>
										<div>
											<input class="form-control required" type="file" name="client_image" id="client_image">
											<small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png, svg.</small>
										</div>
									</div>									
								</div>
								
								<div class="col-md-6">
									<div class="form-group">
										<label>Client Designation <span class="text-danger">*</span></label>
										<input class="form-control required" type="text" name="client_designation" id="client_designation">
									</div>
 								</div>
								 <div class="col-md-6">
									<div class="form-group">
										<label>Client Company Name <span class="text-danger">*</span></label>
										<input class="form-control required" type="text" name="client_company" id="client_company">
									</div>									
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label>Client Content <span class="text-danger">*</span></label>
										<textarea class="form-control required" name="client_content" id="client_content"></textarea>
									</div>
 								</div>
							  <div class="col-md-6">
									<div class="form-group">
										<label>Case Study Type <span class="text-danger">*</span></label>
										<select class="select required" name="case_study_type[]" id="case_study_type" multiple>
										</select>
									</div>									
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Case Study Order <span class="text-danger">*</span></label>
										<select class="order_display required" name="case_study_display_order[]" id="case_study_display_order" multiple>
										</select>
									</div>
 								</div>
							  <div class="col-sm-6">
							     <div class="form-group">
									<label class="display-block control-label">Case Study Status <span class="text-danger">*</span></label>
									<div class="form-check form-check-inline">
										<input class="form-check-input required" type="radio" name="status" id="post_pending" value="1" checked>
										<label class="form-check-label" for="post_pending">Pending</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input required" type="radio" name="status" id="post_publish" value="2">
										<label class="form-check-label" for="post_publish">Publish</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input required" type="radio" name="status" id="post_draft" value="3">
										<label class="form-check-label" for="post_draft">Draft</label>
									</div>
								</div>
							  </div>
							  <div class="col-sm-12">
							     <div class="form-group">
							      <div class="table-responsive" id="all-categories" style="max-height:200px;border:solid 1px #ced4da;overflow-y:auto;">
                                </div>
								<span id="catID-errorMsg"></span>
								</div>
								</div>
								
							</div>
                                <div class="col-sm-7">								
									<div class="m-t-20">
										<button class="btn btn-primary btn-lg" type="submit">Create Case Study</button>
									</div>
								</div>
                            <?php
							// Form Close
							echo form_close(); ?>
							</div>
                        </div>
                    </div>
                </div>
        </div>
        <div id="edit_cs" class="modal custom-modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="modal-content modal-lg" style="width:100%">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Case Study</h4>
                    </div>
                    <div class="modal-body">
                        <div class="m-b-30">
                            <?php
								$form_attribute=array(
										'name' => 'edit-cs',
										'class' => 'form-horizontal',
										'method'=>"post",
										'id' => 'edit-cs',
										'novalidate' => 'novalidate',
										);
								$hidden = array('action' => 'editCs','id'=>'');
								// Form Open
								echo form_open_multipart('admin/add_case_study',$form_attribute,$hidden);
								?>
							<div class="row">
							  <div class="col-sm-6">
								<div class="form-group">
									<label class="control-label">Case Study Title <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="case_study_title" id="case_study_title">
								</div>
							 </div>
							  <div class="col-sm-6">
								 <div class="form-group">
									<label class="control-label">Meta Tag <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="meta_tag" id="meta_tag">
								</div>
								</div>
							
							  <div class="col-sm-6">
							    <div class="form-group">
									<label class="control-label">Case Study Slug <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="case_study_slug" id="case_study_slug" readonly>
								</div>							
							  </div>
							  <div class="col-sm-6">
							   <div class="form-group">
									<label class="control-label">Meta Keyword <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="meta_keyword" id="meta_keyword">
								</div>
							 </div>
							 <div class="col-sm-6">
								<div class="form-group">
									<label class="control-label">Case Study Sub Title <span class="text-danger">*</span></label>
									<input class="form-control required" type="text" name="case_study_sub_title" id="case_study_sub_title">
								</div>
							 </div>
							  <div class="col-sm-6">
							  <div class="form-group">
									<label class="control-label">Meta Description <span class="text-danger">*</span></label>
									<textarea class="form-control required" name="meta_description" id="meta_description"></textarea>
								</div>
							  </div>
							  <div class="col-sm-6">
								<div class="form-group">
									<label class="control-label">Case Study Logo <span class="text-danger">*</span></label>
									<div>
										<input class="form-control required" type="file" name="case_study_logo" id="case_study_logo">
										<small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png, svg.</small>
									</div>
									<div class="col-sm-7">								
									<div class="form-group">
										<label></label>
										<img id="case_study_logo_file"/>
									</div>
								</div>
								</div>
							 </div>
							 <div class="col-sm-6">
								<div class="form-group">
									<label class="control-label">Case Study Image <span class="text-danger">*</span></label>
									<div>
										<input class="form-control required" type="file" name="case_study_image" id="case_study_image">
										<small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png, svg.</small>
									</div>
									<div class="col-sm-7">								
									<div class="form-group">
										<label></label>
										<img id="case_study_image_file"/>
									</div>
								</div>
								</div>
							 </div>
								<div class="col-md-12">
									<div class="form-group">
										<label>Case Study Content <span class="text-danger">*</span></label>
										<textarea class="form-control required" name="case_study_long_content" id="case_study_long_content"></textarea>
									</div>
 								</div>
								 <div class="col-md-4">
									<div class="form-group">
										<label>Increase in website visitor <span class="text-danger">*</span></label>
										<input class="form-control required" type="text" name="website_visitor" id="website_visitor">
									</div>									
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Increase in organic Search traffic <span class="text-danger">*</span></label>
										<input class="form-control required" type="text" name="organic_search_traffic" id="organic_search_traffic">
									</div>
 								</div>
								 <div class="col-md-4">
									<div class="form-group">
										<label>Increase in the Conversation rate<span class="text-danger">*</span></label>
										<input class="form-control required" type="text" name="conversation_rate" id="conversation_rate">
									</div>									
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label>Backstory <span class="text-danger">*</span></label>
										<textarea class="form-control required text-editor" name="backstory_content" id="edit_backstory_content"></textarea>
										<label for="backstory_content" generated="true" id="ckeditor-msg1"></label>
									</div>
 								</div>
								 <div class="col-md-12">
									<div class="form-group">
										<label>Problem Statement <span class="text-danger">*</span></label>
										<textarea class="form-control required text-editor" name="problem_statement_content" id="edit_problem_statement_content"></textarea>
										<label for="problem_statement_content" generated="true" id="ckeditor-msg2"></label>
									</div>									
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label>The Challenge <span class="text-danger">*</span></label>
										<textarea class="form-control required text-editor" name="the_challenge_content" id="edit_the_challenge_content"></textarea>
										<label for="the_challenge_content" generated="true" id="ckeditor-msg3"></label>
									</div>
 								</div>
								 <div class="col-md-12">
									<div class="form-group">
										<label>Case Study Bottom Title <span class="text-danger">*</span></label>
										<textarea class="form-control required" name="case_study_title_1" id="case_study_title_1"></textarea>
									</div>									
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Case Study Bottom Sub Title <span class="text-danger">*</span></label>
										<input class="form-control required" type="text" name="case_study_sub_title_1" id="case_study_sub_title_1">
									</div>
 								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Client Name <span class="text-danger">*</span></label>
										<input class="form-control required" type="text" name="client_name" id="client_name">
									</div>
 								</div>
								 <div class="col-md-6">
									<div class="form-group">
										<label>Clicks impressions SEO overview Image<span class="text-danger">*</span></label>
										<div>
											<input class="form-control required" type="file" name="clicks_impressions_seo_overview_image" id="clicks_impressions_seo_overview_image">
											<small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png, svg.</small>
										</div>
										<div class="col-sm-7">								
											<div class="form-group">
												<label></label>
												<img id="clicks_impressions_seo_overview_image_file"/>
											</div>
										</div>		
									</div>							
								</div>
								 <div class="col-md-6">
									<div class="form-group">
										<label>Client Profile Picture<span class="text-danger">*</span></label>
										<div>
											<input class="form-control required" type="file" name="client_image" id="client_image">
											<small class="form-text text-muted">Max. file size: 50 MB. Allowed images: jpg, gif, png, svg.</small>
										</div>
										<div class="col-sm-7">								
										<div class="form-group">
											<label></label>
											<img id="client_image_file"/>
										</div>
									</div>		
									</div>							
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Client Designation <span class="text-danger">*</span></label>
										<input class="form-control required" type="text" name="client_designation" id="client_designation">
									</div>
 								</div>
								 <div class="col-md-6">
									<div class="form-group">
										<label>Client Company Name <span class="text-danger">*</span></label>
										<input class="form-control required" type="text" name="client_company" id="client_company">
									</div>									
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label>Client Content <span class="text-danger">*</span></label>
										<textarea class="form-control required" name="client_content" id="client_content"></textarea>
									</div>
 								</div>
							  <div class="col-md-6">
									<div class="form-group">
										<label>Case Study Type <span class="text-danger">*</span></label>
										<select class="select required" name="case_study_type[]" id="case_study_type" multiple>
										</select>
									</div>									
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Case Study Order <span class="text-danger">*</span></label>
										<select class="order_display required" name="case_study_display_order[]" id="case_study_display_order" multiple>
										</select>
									</div>
 								</div>
							  <div class="col-sm-6">
							     <div class="form-group">
									<label class="display-block control-label">Case Study Status <span class="text-danger">*</span></label>
									<div class="form-check form-check-inline">
										<input class="form-check-input required" type="radio" name="status" id="epost_pending" value="1" checked>
										<label class="form-check-label" for="epost_pending">Pending</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input required" type="radio" name="status" id="epost_publish" value="2">
										<label class="form-check-label" for="epost_publish">Publish</label>
									</div>
									<div class="form-check form-check-inline">
										<input class="form-check-input required" type="radio" name="status" id="epost_draft" value="3">
										<label class="form-check-label" for="epost_draft">Draft</label>
									</div>
								</div>
							  </div>
							  <div class="col-sm-12">
							     <div class="form-group">
							      <div class="table-responsive" id="all-categories" style="max-height:200px;border:solid 1px #ced4da;overflow-y:auto;">
                                </div>
								<span id="catID-errorMsg"></span>
								</div>
								</div>
								
							</div>
								
                                <div class="col-sm-7">								
									<div class="m-t-20">
										<button class="btn btn-primary btn-lg" type="submit">Save Changes</button>
									</div>
								</div>
                            <?php
							// Form Close
							echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="delete_cs" class="modal custom-modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content modal-md">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete Case Study</h4>
                    </div>
                    <div class="modal-body card-box">
					 <?php
						$form_attribute=array(
								'name' => 'delete-cs',
								'class' => 'form-horizontal',
								'method'=>"post",
								'id' => 'delete-cs',
								'novalidate' => 'novalidate',
								);
						$hidden = array('action' => 'deleteCs','cs_id'=>'');
						//Form Open
						echo form_open('admin/delete_case_study',$form_attribute,$hidden);
						?>
                        <p>Do you want to delete the case study now with his related table data? This cannot be undone.</p>
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
$(document).ready(function() {
	$("body").on('click','#selectAll', function () {
        var check = $('#chkFileds').is(':checked') ? false:true;
        $("INPUT[id^='chkFileds']").prop('checked', check);
    });
});
$.validator.addMethod("extension", function (value, element, param) {
	param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif|svg";
	return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
}, jQuery.format("Please enter a value with a valid extensions."));	
$.validator.addMethod("checkCsTypeLength", function(value, element, param) {
	var len1 = $('form#'+param+' #case_study_display_order').val().length;
	var len2 = $('form#'+param+' #case_study_type').val().length;
	//alert(len2 +'==='+ len1+'======'+param);
	return len2 === len1;
}, "Length should be same as Case Study Type!");

$.validator.addMethod("checkEditCsNameAvailable", 
	 function(value, element) {
			var result = false;
			cs_id=$("form[name=edit-cs] input[name='id']").val();
			$.ajax({
				type:"POST",
				async: false,
				dataType:"json",
				url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
				data : "cs_name="+value+"&request=check-cs-name&action=edit-cs&cs_id="+cs_id,
				success: function(data) {
					//console.log(data);
					$("form#edit-cs #case_study_slug").val(data.slug);
					//return false;
					result = (data.dataContent== "0") ? true : false;
				}
			});
			// return true if SHOW NAME is exist in database
			return result; 
		}, 
		"This Case Study Title is already taken! Try another."
);

$.validator.addMethod("checkCsNameAvailable", 
	function(value, element) {
		var result = false;
		$.ajax({
			type:"POST",
			async: false,
			dataType:"json",
			url: BASE_URL+"ajax/ajaxProcess", // script to validate in server side
			data : "cs_name="+value+"&request=check-cs-name&action=add-cs",
			success: function(data) {
				console.log(data);
				$("form#add-cs #case_study_slug").val(data.slug);
				//return false;
				result = (data.dataContent== "0") ? true : false;
			}
		});
		// return true if SHOW NAME is exist in database
		return result; 
	}, 
	"This Case Study Title is already taken! Try another."
);
/*----------- BEGIN validate CODE -------------------------*/
$('#add-cs').validate({
	rules: {
	"case_study_title": {
		required: true,
		checkCsNameAvailable: true
	},
	"case_study_image": {
		  required: true,
		  extension: "gif|jpe?g|png|svg"
	},
	"case_study_logo": {
		  required: true,
		  extension: "gif|jpe?g|png|svg"
	},
	"clicks_impressions_seo_overview_image": {
		  required: true,
		  extension: "gif|jpe?g|png|svg"
	},
	"client_image": {
		  required: true,
		  extension: "gif|jpe?g|png|svg"
	},
	"case_study_display_order[]": {
		required: true,
		digits: true,
		checkCsTypeLength: "add-cs"
	 },
	"website_visitor": {
		required: true,
		digits: true,
	 },
	"organic_search_traffic": {
		required: true,
		digits: true,
	 },
	"conversation_rate": {
		required: true,
		digits: true,
	 }
	},
	messages: {
		'case_study_display_order[]': {digits:"Please enter numbers Only"},
		'website_visitor': {digits:"Please enter numbers Only"},
		'organic_search_traffic': {digits:"Please enter numbers Only"},
		'conversation_rate': {digits:"Please enter numbers Only"},
        },
	errorPlacement: function (error, element) {
		if (element.attr("name") == "category_id[]"){
			$("span[id^=catID-errorMsg]").html(error);
		}else {
        error.insertAfter(element);
      }
	}
});
$('#edit-cs').validate({
	rules: {
	"case_study_title": {
		required: true,
		checkEditCsNameAvailable: true
	},
	"case_study_image": {
		  required: false,
		  extension: "gif|jpe?g|png|svg"
	},
	"case_study_logo": {
		  required: false,
		  extension: "gif|jpe?g|png|svg"
	},
	"clicks_impressions_seo_overview_image": {
		  required: false,
		  extension: "gif|jpe?g|png|svg"
	},
	"client_image": {
		  required: false,
		  extension: "gif|jpe?g|png|svg"
	},
	"case_study_display_order[]": {
		required: true,
		digits: true,
		checkCsTypeLength: "edit-cs"
	 },
	"website_visitor": {
		required: true,
		digits: true,
	 },
	"organic_search_traffic": {
		required: true,
		digits: true,
	 },
	"conversation_rate": {
		required: true,
		digits: true,
	 }
	},
	messages: {
		'case_study_display_order[]': {digits:"Please enter numbers Only"},
		'website_visitor': {digits:"Please enter numbers Only"},
		'organic_search_traffic': {digits:"Please enter numbers Only"},
		'conversation_rate': {digits:"Please enter numbers Only"},
        },
	errorPlacement: function (error, element) {
		if (element.attr("name") == "category_id[]"){
			$("span[id^=catID-errorMsg]").html(error);
		}else {
        error.insertAfter(element);
      }
	}
});

function getEditData(cs_id){
	var validator = $( "form#edit-cs" ).validate();
	validator.resetForm();
	$("#ckeditor-msg1").html("");
	$("#ckeditor-msg2").html("");
	$("#ckeditor-msg3").html("");
	var dataString="request=edit_cs_data&cs_id="+cs_id;
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
					$("form[name=edit-cs] input[name='id']").val(res.dataContent.id);
					$("form[name=edit-cs] #case_study_title").val(res.dataContent.case_study_title);
					$("form[name=edit-cs] #meta_tag").val(res.dataContent.meta_tag);
					$("form[name=edit-cs] #meta_keyword").val(res.dataContent.meta_keyword);
					$("form[name=edit-cs] #meta_description").val(res.dataContent.meta_description);
					$("form[name=edit-cs] #case_study_slug").val(res.dataContent.case_study_slug);

					$("form[name=edit-cs] #case_study_long_content").val(res.dataContent.case_study_long_content);
					$("form[name=edit-cs] #case_study_sub_title").val(res.dataContent.case_study_sub_title);
					$("form[name=edit-cs] #case_study_sub_title_1").val(res.dataContent.case_study_sub_title_1);
					$("form[name=edit-cs] #case_study_title_1").val(res.dataContent.case_study_title_1);
					$("form[name=edit-cs] #client_company").val(res.dataContent.client_company);
					$("form[name=edit-cs] #client_content").val(res.dataContent.client_content);
					$("form[name=edit-cs] #client_designation").val(res.dataContent.client_designation);
					$("form[name=edit-cs] #client_name").val(res.dataContent.client_name);
					$("form[name=edit-cs] #conversation_rate").val(res.dataContent.conversation_rate);
					$("form[name=edit-cs] #organic_search_traffic").val(res.dataContent.organic_search_traffic);
					$("form[name=edit-cs] #website_visitor").val(res.dataContent.website_visitor);
					//$("form[name=edit-cs] #case_study_slug").val(res.dataContent.case_study_slug);
					//$("form[name=edit-cs] #case_study_slug").val(res.dataContent.case_study_slug);
					//$("form[name=edit-cs] #case_study_slug").val(res.dataContent.case_study_slug);
					//$("form[name=edit-cs] #case_study_slug").val(res.dataContent.case_study_slug);
					//$("form[name=edit-page] #page_long_content").val(res.dataContent.page_long_content);
					CKEDITOR.instances['edit_backstory_content'].setData(res.dataContent.backstory_content);
					CKEDITOR.instances['edit_problem_statement_content'].setData(res.dataContent.problem_statement_content);
					CKEDITOR.instances['edit_the_challenge_content'].setData(res.dataContent.the_challenge_content);
					//$("form[name=edit-post] #post_type").val([1,2,3]).trigger('change');
					//$('form[name=edit-post] #post_type').select2('val', ['1','2','3']).trigger('change');
					//$('form[name=edit-post] #post_type').val('['+res.dataContent.post_type+']').trigger('change');
					//$('form[name=edit-post] input[name=status]').val(res.dataContent.status).trigger('change');
					$("form[name=edit-cs] input[name=status][value='"+res.dataContent.status+"']").prop("checked",true).trigger('change');
					img_src1=img_src2=img_src3=img_src4= 'uploads/no-image100x100.jpg';
					if (res.dataContent.case_study_image != '')
					{
						img_src1='../uploads/case_study_images/small/'+res.dataContent.case_study_image;				
					}
					if (res.dataContent.case_study_logo != '')
					{
						img_src2='../uploads/case_study_images/small/'+res.dataContent.case_study_logo;				
					}
					if (res.dataContent.clicks_impressions_seo_overview_image != '')
					{
						img_src3='../uploads/case_study_images/small/'+res.dataContent.clicks_impressions_seo_overview_image;				
					}
					if (res.dataContent.client_image != '')
					{
						img_src4='../uploads/case_study_images/small/'+res.dataContent.client_image;				
					}									
                    $('form[name=edit-cs] img#case_study_image_file').prop('src', img_src1);
					$('form[name=edit-cs] img#case_study_logo_file').prop('src', img_src2);
					$('form[name=edit-cs] img#clicks_impressions_seo_overview_image_file').prop('src', img_src3);
					$('form[name=edit-cs] img#client_image_file').prop('src', img_src4);					
					console.log(res.dataContent);
				}else if (res.dataContent == ''){
					console.log(res);
				}
			}
		}
	});
}
//delete slider
$("body").on('click','.delete-cs',function(event) {
	event.preventDefault();
	var stringArrayId=$(this).prop("id");
	if(stringArrayId > 0){
		$("form[name=delete-cs] input[name='cs_id']").val(stringArrayId);
	}
	//alert(stringArrayId);	
});

function getAllPagesData(cs_id){
	var dataString="request=get-all-pages&type=CS&id="+cs_id;
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
					$("select[id^=case_study_type]").html('').html(res.dataContent);
					$("select[id^=case_study_display_order]").html('').html(res.dataContent1);				
					console.log(res.dataContent);
				}else if (res.dataContent == ''){
					console.log(res);
				}
			}
		}
	});
}

function getAllCategoriesData(cs_id){
	var dataString="request=get-all-categories&type=CS&id="+cs_id;
	getAllPagesData(cs_id);
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
					$("div[id^=all-categories]").html('').html(res.dataContent);				
					//console.log(res.dataContent);
				}else if (res.dataContent == ''){
					console.log(res);
				}
			}
		}
	});
}
</script>
<script src="https://cdn.ckeditor.com/4.8.0/standard-all/ckeditor.js"></script>
<script type="text/javascript" src="<?=base_url(); ?>assets/plugins/ckfinder/ckfinder.js"></script>
<script>
var editor = CKEDITOR.replaceClass="text-editor";
CKFinder.setupCKEditor( CKEDITOR.instances['text-editor'], '<?=base_url(); ?>assets/plugins/ckfinder/;?>' );
$("form[id=add-cs]").submit( function(e) {
	var messageLength = CKEDITOR.instances['backstory_content'].getData().replace(/<[^>]*>/gi, '').length;
	var messageLength2 = CKEDITOR.instances['problem_statement_content'].getData().replace(/<[^>]*>/gi, '').length;
	var messageLength3 = CKEDITOR.instances['the_challenge_content'].getData().replace(/<[^>]*>/gi, '').length;
	$("#ckeditor-msg1").html("");
	$("#ckeditor-msg2").html("");
	$("#ckeditor-msg3").html("");
	if( !messageLength ) {
		//alert('Please enter a Service Description.');
		$("#ckeditor-msg1").html("<font color=red>This field is required .</font>");
		CKEDITOR.instances.backstory_content.focus();
		e.preventDefault();
	}
	if( !messageLength2 ) {
		//alert('Please enter a Service Description.');
		$("#ckeditor-msg2").html("<font color=red>This field is required.</font>");
		CKEDITOR.instances.problem_statement_content.focus();
		e.preventDefault();
	}
	if( !messageLength3 ) {
		//alert('Please enter a Service Description.');
		$("#ckeditor-msg3").html("<font color=red>This field is required.</font>");
		CKEDITOR.instances.the_challenge_content.focus();
		e.preventDefault();
	}
});
$("form[id=edit-cs]").submit( function(e) {
	var messageLength = CKEDITOR.instances['edit_backstory_content'].getData().replace(/<[^>]*>/gi, '').length;
	var messageLength2 = CKEDITOR.instances['edit_problem_statement_content'].getData().replace(/<[^>]*>/gi, '').length;
	var messageLength3 = CKEDITOR.instances['edit_the_challenge_content'].getData().replace(/<[^>]*>/gi, '').length;
	$("#ckeditor-msg1").html("");
	$("#ckeditor-msg2").html("");
	$("#ckeditor-msg3").html("");
	if( !messageLength ) {
		//alert('Please enter a Service Description.');
		$("#ckeditor-msg1").html("<font color=red>This field is required .</font>");
		CKEDITOR.instances.edit_backstory_content.focus();
		e.preventDefault();
	}
	if( !messageLength2 ) {
		//alert('Please enter a Service Description.');
		$("#ckeditor-msg2").html("<font color=red>This field is required.</font>");
		CKEDITOR.instances.edit_problem_statement_content.focus();
		e.preventDefault();
	}
	if( !messageLength3 ) {
		//alert('Please enter a Service Description.');
		$("#ckeditor-msg3").html("<font color=red>This field is required.</font>");
		CKEDITOR.instances.edit_the_challenge_content.focus();
		e.preventDefault();
	}
});
</script>