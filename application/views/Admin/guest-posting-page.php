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
                    <div class="col-sm-10"><h2>Youtube Video</h2></div>
					<?php
					if (getUserCan('page_module', 'access_write')) {
					?>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-primary btn-rounded save-video" id="save-video"> Save</button>
                    </div>
					<?php } ?>
                </div>
            </div>
            <table id="video-table" class="table table-bordered">
               <thead>
                    <tr>
                        <th>Title</th>
						<th>Link</th>
                        
                    </tr>
                </thead>
                <tbody>
				  <tr>
                        <td><input class="form-control" type="text" name="title" value="" id="title"></td>
						<td><input class="form-control" type="url" name="link" value="" id="link"></td>
                        
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
	
	<div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-10"><h2>Heading</h2></div>
					<?php
					if (getUserCan('page_module', 'access_write')) {
					?>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-primary btn-rounded save-heading" id="save-heading"> Save</button>
                    </div>
					<?php } ?>
                </div>
            </div>
            <table id="quality-table" class="table table-bordered">
             
                <tbody>
				  <tr>
                        <td><input class="form-control" type="text" name="heading" value="" id="heading"></td>
				       
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
	
	
	<div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-10"><h2>Quality Control</h2></div>
					<?php
					if (getUserCan('page_module', 'access_write')) {
					?>
                    <div class="col-sm-2">
                        <button type="button" class="btn btn-primary btn-rounded save-quality" id="save-quality"> Save</button>
                    </div>
					<?php } ?>
                </div>
            </div>
            <table id="qualityc-table" class="table table-bordered">
               <thead>
                    <tr>
                        <th>Title</th>
						<th>Content</th>
                        
                    </tr>
                </thead>
                <tbody>
				  <tr>
                        <td><input class="form-control" type="text" name="title" value="" id="qtitle"></td>
						<td><textarea class="form-control" name="content" value="" id="content"></textarea></td>
                        
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
	
	
	<div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-10"><h2>Numbers</h2></div>
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
						<th>Content</th>
						
                        <th>Number 1</th>
						<th>Number 2</th>
                        <th>Number 3</th>
						<th>Number 4</th>
						<th>Number 5</th>
				   </tr>
                </thead>
                <tbody>
				  <tr>
				  <td><textarea class="form-control" name="ncontent" value="" id="ncontent"></textarea></td>
					<td><input class="form-control" type="text" name="number1" value="" id="number1" placeholder="Number">
					<input style="margin-top:20px" class="form-control" type="text" name="ntitle1" value="" id="ntitle1" placeholder="Title">
					</td>
					
					<td><input class="form-control" type="text" name="number2" value="" id="number2" placeholder="Number">
					<input style="margin-top:20px" class="form-control" type="text" name="ntitle2" value="" id="ntitle2" placeholder="Title">
					</td>
					
					<td><input class="form-control" type="text" name="number3" value="" id="number3" placeholder="Number">
					<input style="margin-top:20px" class="form-control" type="text" name="ntitle3" value="" id="ntitle3" placeholder="Title">
					</td>
					
					<td><input class="form-control" type="text" name="number4" value="" id="number4" placeholder="Number">
					<input style="margin-top:20px" class="form-control" type="text" name="ntitle4" value="" id="ntitle4" placeholder="Title">
					</td>
					
					<td><input class="form-control" type="text" name="number5" value="" id="number5" placeholder="Number">
					<input style="margin-top:20px" class="form-control" type="text" name="ntitle5" value="" id="ntitle5" placeholder="Title">
					</td>
                        
                    </tr>
                </tbody>
            </table>
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

function load_number_data(){
	var dataString = "requestType=Number&pid="+<?php echo GUEST_POSTING_PAGE_ID; ?>;
	$('#number-table').block({
				// BlockUI code for element blocking
				message: "<h5>Fetching...</h5>",
				css: { color: 'white', backgroundColor: 'none',borderColor: 'none',border: 'none'}
			});
	return jQuery.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>" + "ajax/load_inner_table_data",
		dataType: 'json',
		data: dataString,
		success: function(res) {
			var trHTML = '<tr><td colspan="3" style="text-align:center;">No data Found</td></tr>';	
			if (res.dataContent === 1)
			{
				$('[data-toggle="tooltip"]').tooltip('dispose');
					if(res.timelineData.ncontent){
                    $("#ncontent").val(res.timelineData.ncontent);
			      }
				  if(res.timelineData.number1){
                    $("#number1").val(res.timelineData.number1);
			      }
				  if(res.timelineData.ntitle1){
                    $("#ntitle1").val(res.timelineData.ntitle1);
			      }
				  
				  if(res.timelineData.number2){
                    $("#number2").val(res.timelineData.number2);
			      }
				  if(res.timelineData.ntitle2){
                    $("#ntitle2").val(res.timelineData.ntitle2);
			      }
				  
				  if(res.timelineData.number3){
                    $("#number3").val(res.timelineData.number3);
			      }
				  if(res.timelineData.ntitle3){
                    $("#ntitle3").val(res.timelineData.ntitle3);
			      }
				  
				  if(res.timelineData.number4){
                    $("#number4").val(res.timelineData.number4);
			      }
				  if(res.timelineData.ntitle4){
                    $("#ntitle4").val(res.timelineData.ntitle4);
			      }
				  
				  if(res.timelineData.number5){
                    $("#number5").val(res.timelineData.number5);
			      }
				  if(res.timelineData.ntitle5){
                    $("#ntitle5").val(res.timelineData.ntitle5);
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


function load_video_data(){
	var dataString = "requestType=Video&pid="+<?php echo GUEST_POSTING_PAGE_ID; ?>;
	$('#video-table').block({
				// BlockUI code for element blocking
				message: "<h5>Fetching...</h5>",
				css: { color: 'white', backgroundColor: 'none',borderColor: 'none',border: 'none'}
			});
	return jQuery.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>" + "ajax/load_inner_table_data",
		dataType: 'json',
		data: dataString,
		success: function(res) {
			var trHTML = '<tr><td colspan="3" style="text-align:center;">No data Found</td></tr>';	
			if (res.dataContent === 1)
			{
				$('[data-toggle="tooltip"]').tooltip('dispose');
					if(res.timelineData.title){
                    $("#title").val(res.timelineData.title);
			      }
				  if(res.timelineData.link){
                    $("#link").val(res.timelineData.link);
			      }
				 
					$('[data-toggle="tooltip"]').tooltip();
			}
			$('#video-table').unblock();
			return false;
		},
		error: function (error) {
			alert('error; ' +error);
			$('#video-table').unblock();
		} 
	});
}


function load_quality_data(){
	var dataString = "requestType=Quality&pid="+<?php echo GUEST_POSTING_PAGE_ID; ?>;
	$('#qualityc-table').block({
				// BlockUI code for element blocking
				message: "<h5>Fetching...</h5>",
				css: { color: 'white', backgroundColor: 'none',borderColor: 'none',border: 'none'}
			});
	return jQuery.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>" + "ajax/load_inner_table_data",
		dataType: 'json',
		data: dataString,
		success: function(res) {
			var trHTML = '<tr><td colspan="3" style="text-align:center;">No data Found</td></tr>';	
			if (res.dataContent === 1)
			{
				$('[data-toggle="tooltip"]').tooltip('dispose');
					if(res.timelineData.title){
                    $("#qtitle").val(res.timelineData.title);
			      }
				  if(res.timelineData.content){
                    $("#content").val(res.timelineData.content);
			      }
				 
					$('[data-toggle="tooltip"]').tooltip();
			}
			$('#qualityc-table').unblock();
			return false;
		},
		error: function (error) {
			alert('error; ' +error);
			$('#qualityc-table').unblock();
		} 
	});
}


function load_heading_data(){
	var dataString = "requestType=Heading&pid="+<?php echo GUEST_POSTING_PAGE_ID; ?>;
	$('#quality-table').block({
				// BlockUI code for element blocking
				message: "<h5>Fetching...</h5>",
				css: { color: 'white', backgroundColor: 'none',borderColor: 'none',border: 'none'}
			});
	return jQuery.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>" + "ajax/load_inner_table_data",
		dataType: 'json',
		data: dataString,
		success: function(res) {
			var trHTML = '<tr><td colspan="3" style="text-align:center;">No data Found</td></tr>';	
			if (res.dataContent === 1)
			{
				$('[data-toggle="tooltip"]').tooltip('dispose');
					if(res.timelineData.heading){
                    $("#heading").val(res.timelineData.heading);
			      }
				  
				 
					$('[data-toggle="tooltip"]').tooltip();
			}
			$('#quality-table').unblock();
			return false;
		},
		error: function (error) {
			alert('error; ' +error);
			$('#quality-table').unblock();
		} 
	});
}

$(document).ready(function(){
	load_trusted_data();
	load_number_data();
	load_video_data();
	load_quality_data();
	load_heading_data();
    $('[data-toggle="tooltip"]').tooltip();
	var actions = $("table td:last-child").html();
	 $("#save-number").click(function(){
	var ncontent = $("#ncontent").val();
	var number1 = $("#number1").val();
	var ntitle1 = $("#ntitle1").val();
	
	var number2 = $("#number2").val();
	var ntitle2 = $("#ntitle2").val();
	
	var number3 = $("#number3").val();
	var ntitle3 = $("#ntitle3").val();
	
	var number4 = $("#number4").val();
	var ntitle4 = $("#ntitle4").val();
	
	var number5 = $("#number5").val();
	var ntitle5 = $("#ntitle5").val();
	//console.log(year, description, isExist, );
	var dataString = "ncontent="+ncontent+"&number1="+number1+"&number2="+number2+"&number3="+number3+"&number4="+number4+"&number5="+number5+"&ntitle1="+ntitle1+"&ntitle2="+ntitle2+"&ntitle3="+ntitle3+"&ntitle4="+ntitle4+"&ntitle5="+ntitle5;
	return jQuery.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>" + "ajax/saveGuestNumberData",
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
   
	
	 $("#save-video").click(function(){
	var title = $("#title").val();
	var link = $("#link").val();
		var dataString = "title="+title+"&link="+link;
	return jQuery.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>" + "ajax/saveVideoData",
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
   
   
    $("#save-quality").click(function(){
	var title = $("#qtitle").val();
	var content = $("#content").val();
		var dataString = "title="+title+"&content="+content;
	return jQuery.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>" + "ajax/saveQualityData",
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
   
    $("#save-heading").click(function(){
	var heading = $("#heading").val();
		var dataString = "heading="+heading;
	return jQuery.ajax({
		type: "POST",
		url: "<?php echo base_url(); ?>" + "ajax/saveHeadingGuestData",
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
					 $(".alert").delay(4000).slideUp(200, function() {
						$('.alert').alert('close');
					});
					return true;
			   }else{
				    $('#tMsg').html('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong>  '+data.message+'</div>');
					 $('html, body').animate({
                        scrollTop: $('#tMsg').offset().top - 60
                }, 1000);
				//$('.add').parents("tr").removeClass('tr-overlay');
				$('#custom-table').unblock();
				 $(".alert").delay(4000).slideUp(200, function() {
						$('.alert').alert('close');
					});
				 return false;
			   }
			}).catch(error => alert(error.message));
			//$('.add').parents("tr").removeClass('tr-overlay');
		}		
    });
	});
$(document).ready(function() {
    document.getElementById('pro-image').addEventListener('change', readImage, false);
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
							 var dataString = "requestType=Trusted&pid="+<?php echo GUEST_POSTING_PAGE_ID; ?>+"&deleteId="+deleteImage;
							$.ajax({
								type: "POST",
								url: "<?php echo base_url(); ?>" + "ajax/delete_inner_trusted_data",
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
	
	
});

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
			formdata.append("pid", <?php echo GUEST_POSTING_PAGE_ID; ?>);
			//$('.preview-images-zone').attr('id', 'overlay').html('<div style="text-align:center;color: black;font-size: 25px;margin-top: 60px;">Processing...</div>');
			$.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>" + "ajax/uploadInnerTrustedFile",
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
			$(".alert").delay(4000).slideUp(200, function() {
						$(this).alert('close');
					});
		  }
        $("#pro-image").val('');
    } else {
        console.log('Browser not support');
    }
}

function load_trusted_data(){
	var dataString = "requestType=Trusted&pid="+<?php echo GUEST_POSTING_PAGE_ID; ?>;
	var output = $(".preview-images-zone");
	var trHTML = '';
	$('.preview-images-zone').block({
				// BlockUI code for element blocking
				message: "<h5>Fetching...</h5>",
				css: { color: 'white', backgroundColor: 'none',borderColor: 'none',border: 'none'}
			});	
	$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>" + "ajax/getInnerTrustedImageList",
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
                         trHTML +=    '<div class="image-cancel" data-no="' + num + '" data-image="'+item.image+'">x</div>';
							<?php } ?>
							trHTML +=  '<div class="image-zone lightgallery"><a href="../uploads/trusted_images/' + item.image + '"><img id="pro-img-' + num + '" src="../uploads/trusted_images/' + item.image + '" class="img-thumbnail"></a></div>' +
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

</script>