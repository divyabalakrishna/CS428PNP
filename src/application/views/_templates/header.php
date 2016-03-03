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

	<title>PLAN n PLAY</title>

	<!-- Custom Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic,900,900italic" rel="stylesheet" type="text/css">

	<!-- CSS -->
	<link href="<?php echo URL; ?>public/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="<?php echo URL; ?>public/css/bootstrap-theme.css" rel="stylesheet" type="text/css">
	<link href="<?php echo URL; ?>public/css/font-awesome.css" rel="stylesheet" type="text/css">
	<link href="<?php echo URL; ?>public/css/animate.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo URL; ?>public/css/creative.css" rel="stylesheet" type="text/css">

	<!-- JS -->
	<script src="<?php echo URL; ?>public/js/jquery.js"></script>

	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>
<body>
<?php if (is_numeric($userID)) { ?>
	<!-- top bar -->
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button class="navbar-toggle collapsed" aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" type="button">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php echo URL_WITH_INDEX_FILE; ?>">
					<img alt="PLAN n PLAY" src="<?php echo URL; ?>public/img/pnp.png" height="30" />
				</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					<li>
						<a href="<?php echo URL_WITH_INDEX_FILE; ?>">Home</a>
					</li>
					<li>
						<a href="<?php echo URL_WITH_INDEX_FILE; ?>user/viewProfile">Profile</a>
					</li>
					<li>
						<a href="<?php echo URL_WITH_INDEX_FILE; ?>user/logout">Logout</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
<?php } ?>