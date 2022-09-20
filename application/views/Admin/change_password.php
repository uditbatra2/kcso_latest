<?php
$download_url_query='';
if(isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])){
	$download_url_query='&'.$_SERVER['QUERY_STRING'];
}
?>
<div class="page-wrapper">
		<div class="content container-fluid">
                <div class="row">
                    <div class="col-md-6 offset-md-3">
                        <h4 class="page-title"><?=$title?></h4>
						<?php if($this->session->flashdata('change_pass_success')){ ?>
							<div class="alert alert-success">
								<a href="#" class="close" data-dismiss="alert">&times;</a>
								<strong>Success!</strong> <?php echo $this->session->flashdata('change_pass_success'); ?>
							</div>

						<?php }else if($this->session->flashdata('change_pass_error')){  ?>
							<div class="alert alert-danger">
								<a href="#" class="close" data-dismiss="alert">&times;</a>
								<strong>Error!</strong> <?php echo $this->session->flashdata('change_pass_error'); ?>
							</div>
						<?php }?>
							<?php
							$form_attribute=array(
									'name' => 'change-password',
									'class' => 'form-horizontal',
									'method'=>"post",
									'id' => 'change-password',
									'novalidate' => 'novalidate',
									);
							$hidden = array('action' => 'changePassword');
							//Form Open
							echo form_open_multipart('admin/change_password',$form_attribute,$hidden);
							?>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Old password</label>
                                        <input type="password" class="form-control required" name="current_password" id="current_password">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>New password</label>
                                        <input type="password" class="form-control required" name="new_password" id="new_password" minlength="6">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Confirm password</label>
                                        <input type="password" class="form-control required" name="confirm_password" id="confirm_password" minlength="6">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-center m-t-20">
                                    <button type="submit" class="btn btn-primary btn-lg">Update Password</button>
                                </div>
                            </div>
							<?php 
						// Form Close
						echo form_close(); ?>
                    </div>
                </div>
            </div>
	<script>
	/*----------- BEGIN validate CODE -------------------------*/
	$('#change-password').validate({
		ignore: [],
		rules: {
        "new_password": {
            required: true,
            minlength: 6
        },
        "confirm_password": {
            required: true,
            minlength: 6,
            equalTo: "#new_password"
        }
    }		
	});
	</script>