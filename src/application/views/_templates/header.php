<?php

// This here blocks direct access to this file (so an attacker can't look into application/views/_templates/header.php).
// "$this" only exists if header.php is loaded from within the app, but not if THIS file here is called directly.
// If someone called header.php directly we completely stop everything via exit() and send a 403 server status code.
// Also make sure there are NO spaces etc. before "<!DOCTYPE" as this might break page rendering.
if (!$this) {
	exit(header('HTTP/1.0 403 Forbidden'));
}

if (!isset($userID)) {
	$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
}
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="<?php echo URL; ?>public/img/icon.png">

	<title>PLAN & PLAY</title>

	<!-- Custom Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic" rel="stylesheet" type="text/css">

	<!-- CSS -->
	<link href="<?php echo URL; ?>public/css/bootstrap.css" rel="stylesheet" type="text/css">
	<?php if (is_numeric($userID) || (isset($cheat) && $cheat!=0)) { ?>
		<link href="<?php echo URL; ?>public/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
	<?php } ?>
	<link href="<?php echo URL; ?>public/css/bootstrap-datepicker3.css" rel="stylesheet">
	<link href="<?php echo URL; ?>public/css/bootstrap-timepicker.css" rel="stylesheet">
	<link href="<?php echo URL; ?>public/css/font-awesome.css" rel="stylesheet" type="text/css">
	<link href="<?php echo URL; ?>public/css/animate.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo URL; ?>public/css/creative.css" rel="stylesheet" type="text/css">
	<link href="<?php echo URL; ?>public/css/notification.css" rel="stylesheet" type="text/css">
	<link href="<?php echo URL; ?>public/css/home.css" rel="stylesheet" type="text/css">

	<!-- JS -->
	<script src="<?php echo URL; ?>public/js/jquery.js"></script>
	<script src="<?php echo URL; ?>public/js/jquery.validate.js"></script>
	<script src="<?php echo URL; ?>public/js/additional-methods.js"></script>
	<script src="<?php echo URL; ?>application/views/home/temp.js"></script>

	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<script>
		$.validator.setDefaults({
			errorElement: 'span',
			errorClass: 'help-block error-help-block',
			errorPlacement: function (error, element) {
				if (element.parent().parent().hasClass('checkbox') || element.parent().parent().hasClass('radio')) {
					element.parent().parent().parent().append(error);
				}
				else if (element.parent('.input-group').length || element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
					error.insertAfter(element.parent());
				}
				else {
					error.insertAfter(element);
				}
			},
			highlight: function(element) {
				$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			},
			unhighlight: function(element) {
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success');
			}
		});

		$(document).ready(function(){
			// Add asterisk to required fields
			$('input,textarea,select').filter('[required]').each(function(index, element) {
				$(element).closest('.form-group').find('label:first').append('<span class="asterisk-required">*</span>');
			});

			// Add asterisk to required groups of checkboxes or radio buttons
			$('.form-group.required').each(function(index, element) {
				$(element).find('label:first').append('<span class="asterisk-required">*</span>');
			});
		});
	</script>
</head>
<body>
	<!-- top bar -->
    
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="containerHeader">
			<div class="navbar-header">
				<a id="menuHome" class="menuHome">
					Menu
				</a>
				<a class="navbar-brand" href="<?php echo URL_WITH_INDEX_FILE; ?>">
					<img alt="PLAN n PLAY" src="<?php echo URL; ?>public/img/pnp.png" height="35" />
				</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
                    <?php if (is_numeric($userID)) { ?>
                        <li>
                            <a href="<?php echo URL_WITH_INDEX_FILE; ?>">Home</a>
                        </li>                    
                        <?php if ($GLOBALS["beans"]->userModel->isActive($userID)->Active == 'Yes') { ?>
                        <li id="notification_li">
                            <a href="#" id="notificationLink">Notification</a>
                            <div id="notificationContainer">
                                <div id="notificationTitle">Notifications</div>
                                <?php 
                                    $userID = $GLOBALS["beans"]->siteHelper->getSession("userID");           
                                    $notifs = $GLOBALS["beans"]->notifModel->getNotifications($userID,5);

                                    $row = 0;
                                    $count = 0;
                                    foreach ($notifs as $notif) {
                                ?>
                                <div id="notificationsBody" class="notifications">
                                    <div class="row">
                                        <div class="col-sm-2 col-md-2">
                                            <div class="image-frame"> 
                                                <div class="image-thumb" style="background-image: url('<?php echo URL; ?><?php echo $notif->ImgLink?>');"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-10 col-md-10">
                                            <a href="<?php echo URL_WITH_INDEX_FILE; ?><?php echo $notif->UrlLink ?>"><?php echo $notif->Message ?></a>
                                        </div>
                                    </div>
                                    <div style="font-size: 10px;font-style: italic;" class="    text-right"><?php echo $GLOBALS["beans"]->siteHelper->notifMsg($notif->Time); ?></div>

                                </div>
                                <?php
                                        //$GLOBALS["beans"]->notifModel->updateFlag($notif->NotificationID);
                                        if($notif->Flag == 0) $count++;
                                        $row++;
                                    } 
                                ?>
                            <?php if($row == 0) {?>
                            <div id="notificationsBody" class="notifications text-center">You don't have notifications</div>
                            <?php } ?>

                                <div id="notificationFooter"><a href="<?php echo URL_WITH_INDEX_FILE; ?>notifs/index">See All</a></div>
                            </div>
                            <?php if($count > 0) {?>
                            <span id="notification_count"><?php echo $count; ?></span>
                            <?php } ?>
                        </li>
                        <li>
                            <a href="<?php echo URL_WITH_INDEX_FILE; ?>events/listSearch">Search</a>
                        </li>
                        <li>
                            <a href="<?php echo URL_WITH_INDEX_FILE; ?>user/viewProfile">Profile</a>
                        </li>
                        <?php } ?>
                        <li>
                            <a href="<?php echo URL_WITH_INDEX_FILE; ?>user/logout">Logout</a>
                        </li>
                    <?php } else { ?>
                        <?php if((isset($cheat) && $cheat!=0) || !isset($cheat)) {?>
                        <li>
                            <a href="<?php echo URL_WITH_INDEX_FILE; ?>">Home</a>
                        </li>                    
                        <?php }?>
                        <?php if(isset($cheat) && $cheat==0) {?>
                            <li>
                                <a id="signInLink" href="" class="btn" data-toggle="modal" data-target="#myModal">Sign in</a>
                            </li>
                            <li>
                                <a id="signUpLink" href="" class="btn" data-toggle="modal" data-target="#myModal2">Sign Up</a>
                            </li>
                        <?php }?>
                    <?php }?>
                    
				</ul>
			</div>
		</div>
	</nav>
	
</body>