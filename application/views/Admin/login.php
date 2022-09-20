<!DOCTYPE html>
<html>

<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - KarCharger</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600"
        rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?=base_url(); ?>assets/vendors/css/vendors.min.css">
    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="<?=base_url(); ?>assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?=base_url(); ?>assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="<?=base_url(); ?>assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="<?=base_url(); ?>assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="<?=base_url(); ?>assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="<?=base_url(); ?>assets/css/themes/bordered-layout.css">
    <link rel="stylesheet" type="text/css" href="<?=base_url(); ?>assets/css/themes/semi-dark-layout.css">
    <link rel="stylesheet" type="text/css" href="<?=base_url(); ?>assets/css/custom.css">
</head>

<body>
    <section id="login" class="p-1 p-md-2 d-md-flex align-items-center justify-content-center h-100">
        <div class="card m-0">
            <div class="card-header d-block text-center">
                <img src="<?=base_url(); ?>assets/images/logo.png" alt="" style="max-width: 150px;">
                <h4 class="h3 fw-bolder my-2">Welcome to KarCharger 👋</h4>
                <p class="card-text m-0">Please sign-in to your account and start the adventure</p>
            </div>
            <div class="card-body">
                <form class="form form-vertical" id="sign_in" name="sign_in">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-1">
                                <label class="form-label fw-bolder" for="email-id-vertical">Email</label>
                                <input type="email" id="email" class="form-control" name="email"
                                    placeholder="Email" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-1">
                                <div class="d-flex align-items-center justify-content-between flex-wrap form-label">
                                    <label class="fw-bolder" for="password-vertical ">Password</label>
                                    <a href="forgot-password.html" class="text-decoration-underline">Forgot Password?</a>
                                </div>
                                <div class="input-group input-group-merge form-password-toggle mb-2">
                                    <input type="password" class="form-control" id="password"
                                        placeholder="Your Password" name="password" aria-describedby="basic-default-password1" />
                                    <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-1">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="customCheck3">
                                    <label class="form-check-label" for="customCheck3">Remember me</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
						<div id="login-message"></div>
                            <button type="submit"
                                class="btn btn-primary w-100 waves-effect waves-float waves-light submit">Login</button>
                        </div>
                    </div>
                </form>
                <p class="text-center mt-2"><span>New on our platform?</span><a class="text-decoration-underline" href="signup.html"><span>&nbsp;Create an
                            account</span></a></p>
                <div class="divider my-2">
                    <div class="divider-text">or</div>
                </div>
                <div class="auth-footer-btn d-flex justify-content-center">
                    <a class="btn btn-facebook" href="#">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 320 512" height="25" width="25" xmlns="http://www.w3.org/2000/svg"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"></path></svg>
                    </a>
                    <a class="btn btn-google" href="#">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 1024 1024" height="25" width="25" xmlns="http://www.w3.org/2000/svg"><path d="M881 442.4H519.7v148.5h206.4c-8.9 48-35.9 88.6-76.6 115.8-34.4 23-78.3 36.6-129.9 36.6-99.9 0-184.4-67.5-214.6-158.2-7.6-23-12-47.6-12-72.9s4.4-49.9 12-72.9c30.3-90.6 114.8-158.1 214.7-158.1 56.3 0 106.8 19.4 146.6 57.4l110-110.1c-66.5-62-153.2-100-256.6-100-149.9 0-279.6 86-342.7 211.4-26 51.8-40.8 110.4-40.8 172.4S151 632.8 177 684.6C240.1 810 369.8 896 519.7 896c103.6 0 190.4-34.4 253.8-93 72.5-66.8 114.4-165.2 114.4-282.1 0-27.2-2.4-53.3-6.9-78.5z"></path></svg>
                    </a>
                </div>
            </div>
    </section>
    <script src="<?=base_url(); ?>assets/vendors/js/vendors.min.js"></script>
    <script src="<?=base_url(); ?>assets/js/core/app-menu.js"></script>
    <script src="<?=base_url(); ?>assets/js/core/app.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
	
    <script>
        $(window).on('load', function () {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
		
		
    </script>
	<script type="text/javascript">
// Ajax post
$(document).ready(function() {
	$('#sign_in').validate({

		rules:{
			email:"required",

			password:"required",

		},
		messages: {

    email:"Please enter your email",

    password:"Please enter your password",

		},
		submitHandler: function(event) {
			    	
		var form=$("#sign_in");
		$("#login-message").empty();
		$(".submit").addClass("disabled");
		    
		$.ajax({
			type: "POST",
			url: "<?php echo base_url(); ?>" + "ajax/login",
			dataType: 'json',
			async: true,
			cache: false,
			data: form.serialize(),
			success: function(res) {
				if (res)
				{
					if(res.status==1){
						msg='<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a>'+res.message+'</div>';
                        $("#login-message").html(msg);
                        //setTimeout(function(){ $(location).attr('href',res.redirect_url); }, 1000);
                        $(location).attr('href',res.redirect_url);
						return false;
						//console.log(res);
					}else if (res.status==0){
						$(".submit").removeClass("disabled");
						msg='<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a>'+res.message+'</div>';
						$("#login-message").html(msg);
						return false;
						//console.log(res);
					}
				}
			}
		});
		return false;
            }
			
	});

	
});
</script>

</body>

</html>