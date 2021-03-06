<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>

<div class="container">
	<h2 class="page-header">Notifications</h2>
	<?php foreach ($events as $notif) { ?>
		<div id="notificationsBody" class="notifications">
			<div class="row">
				<div class="col-xs-3 col-md-1">
					<div class="image-frame"> 
						<div class="image-thumb" style="background-image: url('<?php echo URL; ?><?php echo $notif->ImgLink?>');">
						</div>
					</div>
				</div>
				<div class="col-xs-9 col-md-11">
					<a href="<?php echo URL_WITH_INDEX_FILE; ?><?php echo $notif->UrlLink ?>"><?php echo $notif->Message ?></a>
				</div>
			</div>
			<div style="font-size: 10px;font-style: italic;" class=" text-right"><?php echo $GLOBALS["beans"]->siteHelper->notifMsg($notif->Time); ?>
			</div>
		</div>
	<?php } ?>
</div>