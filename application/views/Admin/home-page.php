<style>
.table-wrapper {
    width: 100%;
    /*margin: 30px auto;*/
    background: #fff;
    padding: 20px;	
    box-shadow: 0 1px 1px rgba(0,0,0,.05);
}
.table-title {
    padding-bottom: 10px;
    margin: 0 0 10px;
}
.table-title h2 {
    margin: 6px 0 0;
    font-size: 22px;
}
.table-title .add-new {
    float: right;
    height: 30px;
    font-weight: bold;
    font-size: 12px;
    text-shadow: none;
    min-width: 100px;
    border-radius: 50px;
    line-height: 13px;
}
.table-title .add-new i {
    margin-right: 4px;
}
.table-title .add-new-trust {
    float: right;
    height: 30px;
    font-weight: bold;
    font-size: 12px;
    text-shadow: none;
    min-width: 100px;
    border-radius: 50px;
    line-height: 13px;
}
.table-title .add-new-trust i {
    margin-right: 4px;
}

#custom-table.table {
    table-layout: fixed;
}
#custom-table.table tr th, table.table tr td {
    border-color: #e9e9e9;
}
#custom-table.table th i {
    font-size: 13px;
    margin: 0 5px;
    cursor: pointer;
}
#custom-table.table th:last-child {
    width: 100px;
}
#custom-table.table td a {
    cursor: pointer;
    display: inline-block;
    margin: 0 5px;
    min-width: 24px;
}    
#custom-table.table td a.add {
    color: #27C46B;
}
#custom-table.table td a.edit {
    color: #FFC107;
}
#custom-table.table td a.delete {
    color: #E34724;
}
#custom-table.table td i {
    font-size: 19px;
}
#custom-table.table td a.add i {
    font-size: 24px;
    margin-right: -1px;
    position: relative;
    top: 3px;
}    
#custom-table.table .form-control {
    height: 32px;
    line-height: 32px;
    box-shadow: none;
    border-radius: 2px;
}
#custom-table.table .form-control.error {
    border-color: #f50000;
}
#custom-table.table td .add {
    display: none;
}
.preview-images-zone,.preview-mimages-zone {
    width: 100%;
    border: 1px solid #ddd;
    min-height: 180px;
    /* display: flex; */
    padding: 5px 5px 0px 5px;
    position: relative;
    overflow:auto;
}
/*.preview-images-zone > .preview-image:first-child {
    height: 185px;
    width: 185px;
    position: relative;
    margin-right: 5px;
}*/
.preview-images-zone > .preview-image,.preview-mimages-zone > .preview-image {
    height: 90px;
    width: 90px;
    position: relative;
    margin-right: 5px;
    float: left;
    margin-bottom: 5px;
}
.preview-images-zone > .preview-image > .image-zone , .preview-mimages-zone > .preview-image > .image-zone{
    width: 100%;
    height: 100%;
}
.preview-images-zone > .preview-image > .image-zone > img,.preview-mimages-zone > .preview-image > .image-zone > img {
    width: 100%;
    height: 100%;
}
.preview-images-zone > .preview-image > .tools-edit-image , .preview-mimages-zone > .preview-image > .tools-edit-image{
    position: absolute;
    z-index: 100;
    color: #fff;
    bottom: 0;
    width: 100%;
    text-align: center;
    margin-bottom: 10px;
    display: none;
}
.preview-images-zone > .preview-image > .image-cancel,.preview-mimages-zone > .preview-image > .mimage-cancel {
    font-size: 18px;
    position: absolute;
    top: 0;
    right: 0;
    font-weight: bold;
    margin-right: 10px;
    cursor: pointer;
    display: none;
    z-index: 100;
}
.preview-image:hover > .image-zone,.preview-mimage:hover > .image-zone {
    cursor: pointer;
    opacity: .5;
}
.preview-image:hover > .tools-edit-image,
.preview-image:hover > .image-cancel, .preview-mimage:hover > .tools-edit-image,
.preview-image:hover > .mimage-cancel{
    display: block;
}
.ui-sortable-helper {
    width: 90px !important;
    height: 90px !important;
}
</style>
<div class="page-wrapper">
	<div class="content container-fluid">
	 <div class="row">
		<div class="col-sm-4 col-3">
			<h4 class="page-title"><?=isset($title)?$title:''?></h4>
		</div>
	</div>
	<div id="tMsg"></div>
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8"><h2>A <b>Timeline</b> of our journey Details</h2></div>
					<?php
					if (getUserCan('page_module', 'access_create')) {
					?>
                    <div class="col-sm-4">
                        <button type="button" class="btn btn-primary btn-rounded add-new"><i class="fa fa-plus"></i> Add New</button>
                    </div>
					<?php } ?>
                </div>
            </div>
            <table id="custom-table"class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10%;">Year</th>
                        <th>Description</th>
						<th>Action</th>
                    </tr>
                </thead>
                <tbody>
				 
                </tbody>
            </table>
        </div>
    </div>
	
	<div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-10"><h2>Let's talk Numbers</h2></div>
					<?php
					if (getUserCan('page_module', 'access_write')) {
					?>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-primary btn-rounded save-number" id="save-number"> Save</button>
                    </div>
					<?php } ?>
                </div>
            </div>
            <table id="number-table" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Keyword Rank</th>
                        <th>Project Successful Completed</th>
						<th>Organic Traffic Generate</th>
						<th>Happy Customers</th>
                    </tr>
                </thead>
                <tbody>
				  <tr>
                        <td><input class="form-control" type="text" name="keyword_num" value="" id="keyword_num"></td>
                        <td><input class="form-control" type="text" name="project_num" value="" id="project_num"></td>
						<td><input class="form-control" type="text" name="traffic_num" value="" id="traffic_num"></td>
						<td><input class="form-control" type="text" name="customer_num" value="" id="customer_num"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
	 <div class="row">
		 <div class="row">
			<div class="col-sm-4 col-3">
				<h4 class="page-title"></h4>
			</div>
	   </div>
	</div>
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8"><h2>Trusted By Over <b>500+</b> Companies Worldwide</h2></div>
                </div>
            </div>
			<div id="tMsg-2"></div>
			<?php
			if (getUserCan('page_module', 'access_create')) {
			?>
			<fieldset class="form-group">
				<a href="javascript:void(0)" class="btn btn-primary btn-rounded add-new-trust" onclick="$('#pro-image').click()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upload" viewBox="0 0 16 16"><path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/><path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/></svg> Choose Files</a>
				<input type="file" id="pro-image" name="pro-image" style="display: none;" class="form-control" multiple>
			</fieldset>
			<?php } ?>
			<div class="preview-images-zone">
				<!---content load-------------->
			</div>
			
        </div>
    </div>
	
	  <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8"><h2>Media Coverage</h2></div>
                </div>
            </div>
			<div id="tMsg-3"></div>
			<?php
			if (getUserCan('page_module', 'access_create')) {
			?>
			<fieldset class="form-group">
				<a href="javascript:void(0)" class="btn btn-primary btn-rounded add-new-media" onclick="$('#media-image').click()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-upload" viewBox="0 0 16 16"><path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/><path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/></svg> Choose Files</a>
				<input type="file" id="media-image" name="media-image" style="display: none;" class="form-control" multiple>
			</fieldset>
			<?php } ?>
			<div class="preview-mimages-zone">
				<!---content load-------------->
			</div>
			
        </div>
    </div>

</div>
</div>
<script> 
$.validator.addMethod("extension", function (value, element, param) {
	param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
	return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
}, jQuery.format("Please enter a value with a valid extensions."));
/*----------- BEGIN validate CODE -------------------------*/
function saveData(){
	var year = $("#dateyearpicker").val();
	var description = $("#description").val();
	var isExist = $("#isExist").val();
	var requestType = $("#request-type").val();
	//console.log(year, description, isExist, );
	var dataString = "year="+year+"&description="+description+"&isExist="+isExist+"&requestType="+requestType;
	return jQuery.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>" + "ajax/saveData",
		dataType: 'json',
		data: dataString,
		/*success: function(res) {
			console.log(res.dataContent);
			return false;
			if (res.dataContent)
			{
				if(res.dataContent != ''){				
					console.log(res.dataContent);
				}else if (res.dataContent == ''){
					console.log(res);
				}
			}
		}*/
	}).then(function(resp){ 
     return resp;
  }).catch(error => alert(error.message));
}

function load_table_data(){
	var dataString = "requestType=Timeline";
	$('#custom-table').block({
				// BlockUI code for element blocking
				message: "<h5>Fetching...</h5>",
				css: { color: 'white', backgroundColor: 'none',borderColor: 'none',border: 'none'}
			});
	return jQuery.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>" + "ajax/load_table_data",
		dataType: 'json',
		data: dataString,
		success: function(res) {
			var trHTML = '<tr><td colspan="3" style="text-align:center;">No data Found</td></tr>';	
			if (res.dataContent === 1)
			{
				$('[data-toggle="tooltip"]').tooltip('dispose');
               if(res.timelineData){
                    var trHTML = '';				   
					$.each(res.timelineData, function (i, item) {
							trHTML += '<tr><td>' + item.year + '</td><td title="'+item.description+'">' + item.description + '</td><td>';
							<?php
							if (getUserCan('page_module', 'access_write')) {
							?>
							trHTML+= '<a class="add" title="Add" data-toggle="tooltip" id="saveData"><i class="material-icons">&#xE03B;</i></a><a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a>';
							<?php
							}
							if (getUserCan('page_module', 'access_delete')) {
							?>
							trHTML+= '<a class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a>';
							<?php } ?>
							trHTML+= '</td></tr>';
							});
			      }
					$('[data-toggle="tooltip"]').tooltip();
			}
			$('#custom-table').unblock();
			$('#custom-table tbody').empty().append(trHTML);
			return false;
		},
		error: function (error) {
			alert('error; ' +error);
		} 
	});
}

function load_number_data(){
	var dataString = "requestType=Number";
	$('#number-table').block({
				// BlockUI code for element blocking
				message: "<h5>Fetching...</h5>",
				css: { color: 'white', backgroundColor: 'none',borderColor: 'none',border: 'none'}
			});
	return jQuery.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>" + "ajax/load_table_data",
		dataType: 'json',
		data: dataString,
		success: function(res) {
			var trHTML = '<tr><td colspan="3" style="text-align:center;">No data Found</td></tr>';	
			if (res.dataContent === 1)
			{
				$('[data-toggle="tooltip"]').tooltip('dispose');
					if(res.timelineData.keyword_num){
                    $("#keyword_num").val(res.timelineData.keyword_num);
			      }
				  if(res.timelineData.project_num){
                    $("#project_num").val(res.timelineData.project_num);
			      }
				  if(res.timelineData.traffic_num){
                    $("#traffic_num").val(res.timelineData.traffic_num);
			      }
				  if(res.timelineData.customer_num){
                    $("#customer_num").val(res.timelineData.customer_num);
			      }
					$('[data-toggle="tooltip"]').tooltip();
			}
			$('#number-table').unblock();
			return false;
		},
		error: function (error) {
			alert('error; ' +error);
			$('#number-table').unblock();
		} 
	});
}
$(document).ready(function(){
	load_table_data();
	load_number_data();
	load_trusted_data();
	load_media_data();
	$('[data-toggle="tooltip"]').tooltip();
	var actions = $("table td:last-child").html();
	// Append table with add row form on add new button click
	 $("#save-number").click(function(){
	var keyword_num = $("#keyword_num").val();
	var project_num = $("#project_num").val();
	var traffic_num = $("#traffic_num").val();
	var customer_num = $("#customer_num").val();
	//console.log(year, description, isExist, );
	var dataString = "keyword_num="+keyword_num+"&project_num="+project_num+"&traffic_num="+traffic_num+"&customer_num="+customer_num;
	return jQuery.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>" + "ajax/saveNumberData",
		dataType: 'json',
		data: dataString,
		success: function(res) {
			if (res.dataContent)
			{
					$('#tMsg').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success! '+res.message+'</div>');
					$('html, body').animate({
                        scrollTop: $('#tMsg').offset().top - 60
                }, 1000);
					 $(".alert").delay(4000).fadeOut(200, function() {
						$('.alert').alert('close');
					});
					return true;
			   }
			
		}
	}).then(function(resp){ 
     return resp;
  }).catch(error => alert(error.message));
	 });
    $(".add-new").click(function(){
		$(this).attr("disabled", "disabled");
		var index = $("table tbody tr:last-child").index();
        var row = '<tr>' +
            '<td><input type="text" name="dateyearpicker" id="dateyearpicker" class="form-control"/><input type="hidden" class="form-control" value="no" id="isExist"><input type="hidden" class="form-control" value="add" id="request-type"></td>' +
            '<td><textarea rows="4" cols="5" class="form-control resize-ta" name="description" id="description" style="margin-top: 0px; margin-bottom: 0px; height: 59px;"></textarea></td>' +
			'<td><a class="add" title="Add" data-toggle="tooltip" id="saveData"><i class="material-icons">&#xE03B;</i></a><a class="edit" title="Edit" data-toggle="tooltip"><i class="material-icons">&#xE254;</i></a><a class="delete" title="Delete" data-toggle="tooltip"><i class="material-icons">&#xE872;</i></a></td>' +
        '</tr>';
    	$("table#custom-table").append(row);		
		$("table#custom-table tbody tr").eq(index + 1).find(".add, .edit").toggle();
        $('[data-toggle="tooltip"]').tooltip();
		if ($("#dateyearpicker").length > 0) {
			$('#dateyearpicker').datetimepicker({
				format: 'YYYY',
				viewMode: "years",
			});

			$("#dateyearpicker").on("dp.hide", function (e) {
				$('#dateyearpicker').datetimepicker('destroy');
				$('#dateyearpicker').datetimepicker({
					format: 'YYYY',
					viewMode: "years",
				});
			});
			
			 $('html, body').animate({
				scrollTop: $("table#custom-table tbody tr input").offset().top-70
			}, 2000);
		}
    });
	// Add row on add button click
	$(document).on("click", ".add", function(){
		var empty = false;
		var input = $(this).parents("tr").find('input[type="text"], textarea');
        input.each(function(){
			if(!$(this).val()){
				$(this).addClass("error");
				empty = true;
			} else{
                $(this).removeClass("error");
            }
		});
		$(this).parents("tr").find(".error").first().focus();
		if(!empty){
			$('#custom-table').block({
				// BlockUI code for element blocking
				message: "<h5>Loading...</h5>",
				css: { color: 'white', backgroundColor: 'none',borderColor: 'none',border: 'none'}
			});
			//$(this).parents("tr").addClass('tr-overlay');
			 rtnVal = saveData().then(function(data){
			  if (data.dataContent === 1){ 
                    /*input.each(function(){
						$(this).parent("td").html($(this).val());
					});			
					$(this).parents("tr").find(".add, .edit").toggle();*/
					$(".add-new").removeAttr("disabled");			  
					$('#tMsg').html('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success! '+data.message+'</div>');
					$('html, body').animate({
                        scrollTop: $('#tMsg').offset().top - 60
                }, 1000);
					load_table_data();
					//$('.add').parents("tr").removeClass('tr-overlay');
					$('#custom-table').unblock();
					hideAlertBox();
					return true;
			   }else{
				    $('#tMsg').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong>  '+data.message+'</div>');
					 $('html, body').animate({
                        scrollTop: $('#tMsg').offset().top - 60
                }, 1000);
				//$('.add').parents("tr").removeClass('tr-overlay');
				$('#custom-table').unblock();
				hideAlertBox();
				 return false;
			   }
			}).catch(error => alert(error.message));
			//$('.add').parents("tr").removeClass('tr-overlay');
		}		
    });
	// Edit row on edit button click
	$(document).on("click", ".edit", function(){
		//$(this).parents("tr").addClass('tr-overlay');
        $('#custom-table').block({
				// BlockUI code for element blocking
				message: "<h5>Fetching...</h5>",
				css: { color: 'white', backgroundColor: 'none',borderColor: 'none',border: 'none'}
			});		
        $(this).parents("tr").find("td:not(:last-child)").each(function(i){
			if (i === 1){
				$(this).html('<textarea rows="4" cols="5" class="form-control resize-ta" style="margin-top: 0px; margin-bottom: 0px; height: 59px;" id="description" name="description">' + $(this).text() + '</textarea>');
			}else{
				$(this).html('<input type="text" class="form-control" value="' + $(this).text() + '" name="dateyearpicker" id="dateyearpicker"><input type="hidden" class="form-control" value="' + $(this).text() + '" id="isExist"><input type="hidden" class="form-control" value="edit" id="request-type">');
			}
		});		
		$(this).parents("tr").find(".add, .edit").toggle();
		$(".add-new").attr("disabled", "disabled");
		if ($("#dateyearpicker").length > 0) {
			$('#dateyearpicker').datetimepicker({
				format: 'YYYY',
				viewMode: "years",
			});

			$("#dateyearpicker").on("dp.hide", function (e) {
				$('#dateyearpicker').datetimepicker('destroy');
				$('#dateyearpicker').datetimepicker({
					format: 'YYYY',
					viewMode: "years",
				});
			});
		}
		//$(this).parents("tr").removeClass('tr-overlay');
		$('#custom-table').unblock();
    });
	// Delete row on delete button click
	$(document).on("click", ".delete", function(event){
		//event.preventDefault();
        //event.stopPropagation();
		//event.stopImmediatePropagation();
		deleteId = $(this).closest('tr').children('td:first').text();
		if(deleteId != ''){
			$this = $(this);
		    //$($this).parents("tr").addClass('tr-overlay');
			   $.confirm({
					title: 'Confirm!',
					content: 'Are you sure you want to delete this?',
					buttons: {
						confirm: function () {
							$('#custom-table').block({
									// BlockUI code for element blocking
									message: "<h5>Deleting...</h5>",
									css: { color: 'white', backgroundColor: 'none',borderColor: 'none',border: 'none'}
								});
							 var dataString = "requestType=Timeline&deleteId="+deleteId;
							$.ajax({
								type: "POST",
								url: "<?php echo base_url(); ?>" + "ajax/delete_table_data",
								dataType: 'json',
								data: dataString,
								success: function(res) {
									if (res.dataContent === 1)
									{
										$.alert(res.message);
										load_table_data();
										$($this).tooltip('dispose');
										$($this).parents("tr").remove();
										$(".add-new").removeAttr("disabled");
									}
									$('#custom-table').unblock();
									//$($this).parents("tr").removeClass('tr-overlay');
									return false;
								},
								error: function (error) {
									alert('error; ' +error);
									//$($this).parents("tr").removeClass('tr-overlay');
									$('#custom-table').unblock();
								} 
							});
						},
						cancel: function () {
							//$.alert('Canceled!');
							//return false;
							//$($this).parents("tr").removeClass('tr-overlay');
							$('#custom-table').unblock();
						}
					}
				});
		}else{
		   $(this).tooltip('dispose');
			$(this).parents("tr").remove();
			$('.add-new').removeAttr("disabled");	
		}
    });
});
$(document).ready(function() {
    document.getElementById('pro-image').addEventListener('change', readImage, false);
	document.getElementById('media-image').addEventListener('change', readMediaImage, false);
    //$( ".preview-images-zone" ).sortable();
    $(document).on('click', '.image-cancel', function() {
        let no = $(this).data('no');
		let deleteImage = $(this).data('image');
		$.confirm({
					title: 'Confirm!',
					content: 'Are you sure you want to delete this?',
					buttons: {
						confirm: function () {
							$('.preview-images-zone').block({
									// BlockUI code for element blocking
									message: "<h5>Deleting...</h5>",
									css: { color: 'white', backgroundColor: 'none',borderColor: 'none',border: 'none'}
								});
							//$('.preview-images-zone').attr('id', 'overlay').html('<div style="text-align:center;color: black;font-size: 25px;margin-top: 60px;">Deleting...</div>');
							 var dataString = "requestType=Trusted&deleteId="+deleteImage;
							$.ajax({
								type: "POST",
								url: "<?php echo base_url(); ?>" + "ajax/delete_trusted_data",
								dataType: 'json',
								data: dataString,
								success: function(res) {
									if (res.dataContent === 1)
									{
										$.alert(res.message);
										load_trusted_data();
										 $(".preview-image.preview-show-"+no).remove();
									}
									//$('.preview-images-zone').removeAttr('id');
									$('.preview-images-zone').unblock();
									return false;
									
								},
								error: function (error) {
									alert('error; ' +error);
									//$('.preview-images-zone').removeAttr('id');
									$('.preview-images-zone').unblock();
								} 
							});
						},
						cancel: function () {
							//$.alert('Canceled!');
							//return false;
							//$('.preview-images-zone').removeAttr('id');
							$('.preview-images-zone').unblock();
						}
					}
				});
    });
	
	$(document).on('click', '.mimage-cancel', function() {
        let no = $(this).data('no');
		let deleteImage = $(this).data('image');
		$.confirm({
					title: 'Confirm!',
					content: 'Are you sure you want to delete this?',
					buttons: {
						confirm: function () {
							$('.media-images-zone').block({
									// BlockUI code for element blocking
									message: "<h5>Deleting...</h5>",
									css: { color: 'white', backgroundColor: 'none',borderColor: 'none',border: 'none'}
								});
							//$('.preview-images-zone').attr('id', 'overlay').html('<div style="text-align:center;color: black;font-size: 25px;margin-top: 60px;">Deleting...</div>');
							 var dataString = "requestType=Media&deleteId="+deleteImage;
							$.ajax({
								type: "POST",
								url: "<?php echo base_url(); ?>" + "ajax/delete_media_data",
								dataType: 'json',
								data: dataString,
								success: function(res) {
									if (res.dataContent === 1)
									{
										$.alert(res.message);
										load_media_data();
										 $(".media-images-zone .preview-image.preview-show-"+no).remove();
									}
									//$('.preview-images-zone').removeAttr('id');
									$('.media-images-zone').unblock();
									return false;
									
								},
								error: function (error) {
									alert('error; ' +error);
									//$('.preview-images-zone').removeAttr('id');
									$('.media-images-zone').unblock();
								} 
							});
						},
						cancel: function () {
							//$.alert('Canceled!');
							//return false;
							//$('.preview-images-zone').removeAttr('id');
							$('.media-images-zone').unblock();
						}
					}
				});
    });

});
function readMediaImage() {
    if (window.File && window.FileList && window.FileReader) {
        var files = event.target.files; //FileList object
        formdata = new FormData();
		isValidExt = false;
        for (let i = 0; i < files.length; i++) {
            var file = files[i];
			//alert(file.type);
            if (file.type.match('image')){
				isValidExt = true;
				if (formdata) {
				 formdata.append("images[]", file);
			   }
			 }
            }
		 if(isValidExt){
			 $('.preview-mimages-zone').block({
				// BlockUI code for element blocking
				message: "<h5>Uploading...</h5>",
				css: { color: 'white', backgroundColor: 'none',borderColor: 'none',border: 'none'}
			});
			//$('.preview-images-zone').attr('id', 'overlay').html('<div style="text-align:center;color: black;font-size: 25px;margin-top: 60px;">Processing...</div>');
			$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>" + "ajax/uploadMediaFile",
				dataType: 'json',
				data: formdata,
				processData: false,
				contentType: false,
				success: function(res) {
					if (res.dataContent === 1)
					{
						$.alert(res.message);
						load_media_data();
					}
					//$('.preview-images-zone').removeAttr('id');
					$('.preview-mimages-zone').unblock();
					return false;
				},
				error: function (error) {
					alert('error; ' +error);
					$('.preview-mimages-zone').unblock();
				} 
			});
	      }else{
			 $('#tMsg-3').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Please enter a value with a valid extensions. (Type:- image format)</div>');
					 $('html, body').animate({
                        scrollTop: $('#tMsg-3').offset().top - 60
                }, 1000); 
				hideAlertBox();
		  }
        $("#media-image").val('');
    } else {
        console.log('Browser not support');
    }
}

function readImage() {
    if (window.File && window.FileList && window.FileReader) {
        var files = event.target.files; //FileList object
        formdata = new FormData();
		isValidExt = false;
        for (let i = 0; i < files.length; i++) {
            var file = files[i];
			//alert(file.type);
            if (file.type.match('image')){
				isValidExt = true;
				if (formdata) {
				 formdata.append("images[]", file);
			   }
			 }
            }
		 if(isValidExt){
			 $('.preview-images-zone').block({
				// BlockUI code for element blocking
				message: "<h5>Uploading...</h5>",
				css: { color: 'white', backgroundColor: 'none',borderColor: 'none',border: 'none'}
			});
			//$('.preview-images-zone').attr('id', 'overlay').html('<div style="text-align:center;color: black;font-size: 25px;margin-top: 60px;">Processing...</div>');
			$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>" + "ajax/uploadTrustedFile",
				dataType: 'json',
				data: formdata,
				processData: false,
				contentType: false,
				success: function(res) {
					if (res.dataContent === 1)
					{
						$.alert(res.message);
						load_trusted_data();
					}
					//$('.preview-images-zone').removeAttr('id');
					$('.preview-images-zone').unblock();
					return false;
				},
				error: function (error) {
					alert('error; ' +error);
					$('.preview-images-zone').unblock();
				} 
			});
	      }else{
			 $('#tMsg-2').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Please enter a value with a valid extensions. (Type:- image format)</div>');
					 $('html, body').animate({
                        scrollTop: $('#tMsg-2').offset().top - 60
                }, 1000); 
				hideAlertBox();
		  }
        $("#pro-image").val('');
    } else {
        console.log('Browser not support');
    }
}

function load_trusted_data(){
	var dataString = "requestType=Trusted";
	var output = $(".preview-images-zone");
	var trHTML = '';
	$('.preview-images-zone').block({
				// BlockUI code for element blocking
				message: "<h5>Fetching...</h5>",
				css: { color: 'white', backgroundColor: 'none',borderColor: 'none',border: 'none'}
			});	
	$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>" + "ajax/getTrustedImageList",
			dataType: 'json',
			data: dataString,
			success: function(res) {
				if (res.dataContent === 1)
				{	var num = 1;		   
					$.each(res.trustedData, function (i, item) {
					trHTML +=  '<div class="preview-image preview-show-' + num + '">';
					       <?php
							if (getUserCan('page_module', 'access_delete')) {
							?>
                          trHTML +=  '<div class="image-cancel" data-no="' + num + '" data-image="'+item.image+'">x</div>';
                            <?php } ?>
							trHTML +=   '<div class="image-zone lightgallery"><a href="../uploads/trusted_images/' + item.image + '"><img id="pro-img-' + num + '" src="../uploads/trusted_images/' + item.image + '" class="img-thumbnail"></a></div>' +
                            '</div>';
					num = num + 1;
				    });
					$('.preview-images-zone').unblock();
                output.empty().append(trHTML);
				$(".lightgallery").lightGallery();
				}else{
				   trHTML =  '<div style="text-align: center;margin-top: 75px;">No Data Found</div>';
				   $('.preview-images-zone').unblock();
				   output.empty().append(trHTML);	
				}
				return false;
			},
			error: function (error) {
				alert('error; ' +error);
				$('.preview-images-zone').unblock();
			} 
		});
}

function load_media_data(){
	var dataString = "requestType=Media";
	var output = $(".preview-mimages-zone");
	var trHTML = '';
	$('.preview-mimages-zone').block({
				// BlockUI code for element blocking
				message: "<h5>Fetching...</h5>",
				css: { color: 'white', backgroundColor: 'none',borderColor: 'none',border: 'none'}
			});	
	$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>" + "ajax/getTrustedImageList",
			dataType: 'json',
			data: dataString,
			success: function(res) {
				if (res.dataContent === 1)
				{	var num = 1;		   
					$.each(res.trustedData, function (i, item) {
					trHTML += '<div class="preview-image preview-show-' + num + '">';
						<?php
						if (getUserCan('page_module', 'access_delete')) {
						?>
                         trHTML += '<div class="mimage-cancel" data-no="' + num + '" data-image="'+item.image+'">x</div>' ;
							<?php } ?>
							trHTML += '<div class="image-zone lightgallery"><a href="../uploads/media_images/' + item.image + '"><img id="pro-img-' + num + '" src="../uploads/media_images/' + item.image + '" class="img-thumbnail"></a></div>' +
                            '</div>';
					num = num + 1;
				    });
					$('.preview-mimages-zone').unblock();
                output.empty().append(trHTML);
				$(".lightgallery").lightGallery();
				}else{
				   trHTML =  '<div style="text-align: center;margin-top: 75px;">No Data Found</div>';
				   $('.preview-mimages-zone').unblock();
				   output.empty().append(trHTML);	
				}
				return false;
			},
			error: function (error) {
				alert('error; ' +error);
				$('.preview-mimages-zone').unblock();
			} 
		});
}

function hideAlertBox(){
	$(".alert").delay(4000).fadeOut(200, function() {
		$(this).alert('close');
	});
}

/*$(document).on('click','#saveData',function(event) {
	event.preventDefault();
	var year = $("#dateyearpicker").attr('id');
	var description = $("#description").attr('id');
	console.log(year);
	//alert(stringArrayId);	
});*/
</script>