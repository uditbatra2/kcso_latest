<?php
$screen_name=$this->session->userdata('logged_in_brijwasi_data')['screen_name'];
$user_mail=$this->session->userdata('logged_in_brijwasi_data')['user_mail'];
$user_role=$this->session->userdata('logged_in_brijwasi_data')['user_role'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="<?=base_url(); ?>/uploads/site_images/favicon.png">
    <title><?php echo isset($title) ? 'Eseo Administrator | '.$title : 'Eseo Administrator' ; ?></title>
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans:400,500,600,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?=base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?=base_url(); ?>assets/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>assets/plugins/light-gallery/css/lightgallery.min.css">
	<link rel="stylesheet" type="text/css" href="<?=base_url(); ?>assets/plugins/summernote/dist/summernote-bs4.css">
    <link rel="stylesheet" type="text/css" href="<?=base_url(); ?>assets/css/fullcalendar.min.css">
    <link rel="stylesheet" type="text/css" href="<?=base_url(); ?>assets/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="<?=base_url(); ?>assets/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="<?=base_url(); ?>assets/css/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="<?=base_url(); ?>assets/plugins/morris/morris.css">
    <link rel="stylesheet" type="text/css" href="<?=base_url(); ?>assets/css/style.css">
	<script type="text/javascript" src="<?=base_url(); ?>assets/js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="<?=base_url(); ?>assets/js/jquery.validate.js"></script>
	<link rel="stylesheet" href="<?=base_url(); ?>assets/css/jquery-confirm.min.css">
    <link rel="stylesheet" href="<?=base_url(); ?>assets/css/jquery-ui.css">
	<script>
		var BASE_URL='<?=base_url()?>';
	</script>
</head>
<body>
    <div class="main-wrapper">
        <div class="header">
            <div class="header-left">
                <a href="<?=base_url('admin/dashboard'); ?>" class="logo">
                    <img src="<?=base_url(); ?>/assets/img/logo.svg" width="" height="55px" alt="">
                </a>
            </div>
            <a id="mobile_btn" class="mobile_btn pull-left" href="#sidebar"><i class="fa fa-bars" aria-hidden="true"></i></a>
            <ul class="nav user-menu pull-right">
                <li class="nav-item dropdown d-none d-sm-block">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown"><i class="fa fa-bell-o"></i> <span class="badge badge-pill bg-primary pull-right">3</span></a>
                    <div class="dropdown-menu notifications">
                        <div class="topnav-dropdown-header">
                            <span>Notifications</span>
                        </div>
                        <div class="drop-scroll">
                            <ul class="notification-list">
                                <li class="notification-message">
                                    <a href="#">
                                        <div class="media">
                                            <span class="avatar">
                                                    <img alt="John Doe" src="<?=base_url(); ?>/assets/img/user.jpg" class="img-fluid">
                                            </span>
                                            <div class="media-body">
                                                    <p class="noti-details"><span class="noti-title">John Doe</span> added new task <span class="noti-title">Patient appointment booking</span></p>
                                                    <p class="noti-time"><span class="notification-time">4 mins ago</span></p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="#">
                                        <div class="media">
                                            <span class="avatar">V</span>
                                            <div class="media-body">
                                                <p class="noti-details"><span class="noti-title">Tarah Shropshire</span> changed the task name <span class="noti-title">Appointment booking with payment gateway</span></p>
                                                <p class="noti-time"><span class="notification-time">6 mins ago</span></p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="#">
                                        <div class="media">
                                            <span class="avatar">L</span>
                                            <div class="media-body">
                                                    <p class="noti-details"><span class="noti-title">Misty Tison</span> added <span class="noti-title">Domenic Houston</span> and <span class="noti-title">Claire Mapes</span> to project <span class="noti-title">Doctor available module</span></p>
                                                    <p class="noti-time"><span class="notification-time">8 mins ago</span></p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="#">
                                        <div class="media">
                                            <span class="avatar">G</span>
                                            <div class="media-body">
                                                    <p class="noti-details"><span class="noti-title">Rolland Webber</span> completed task <span class="noti-title">Patient and Doctor video conferencing</span></p>
                                                    <p class="noti-time"><span class="notification-time">12 mins ago</span></p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="notification-message">
                                    <a href="#">
                                        <div class="media">
                                            <span class="avatar">V</span>
                                            <div class="media-body">
                                                    <p class="noti-details"><span class="noti-title">Bernardo Galaviz</span> added new task <span class="noti-title">Private chat module</span></p>
                                                    <p class="noti-time"><span class="notification-time">2 days ago</span></p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="topnav-dropdown-footer">
                            <a href="#">View all Notifications</a>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown has-arrow">
                    <a href="#" class="dropdown-toggle nav-link user-link" data-toggle="dropdown">
                        <span class="user-img">
                            <img class="rounded-circle" src="<?=base_url(); ?>/assets/img/user.jpg" width="40" alt="Admin">
                            <span class="status online"></span>
                        </span>
                        <span><?=$screen_name?></span>
                    </a>
                        <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?=base_url('admin/change_password'); ?>">Change Password</a>
                                <?php 
                                if (getUserCan('general_module', 'access_read')) {
                                ?>
                                <a class="dropdown-item" href="<?=base_url('admin/theme_settings'); ?>">Settings</a>
                                <?php } ?>
                                <a class="dropdown-item" href="<?=base_url('admin/logout'); ?>">Logout</a>
                        </div>
                </li>
            </ul>
            <div class="dropdown mobile-user-menu pull-right">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                     <a class="dropdown-item" href="<?=base_url('admin/change_password'); ?>">Change Password</a>
                    <a class="dropdown-item" href="<?=base_url('admin/theme_settings'); ?>">Settings</a>
                    <a class="dropdown-item" href="<?=base_url('admin/logout'); ?>">Logout</a>
                </div>
            </div>
        </div>