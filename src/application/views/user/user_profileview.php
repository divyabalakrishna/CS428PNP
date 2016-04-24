<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); }

$tagList = "";
$index = 1;
foreach ($userTags as $userTag) {
	if ($index > 1) {
		$tagList = $tagList . ", " ;
	}

	$tagList = $tagList . $userTag->TagName;

	$index = $index + 1;
}
?>

<div class="container">
	<h2 class="page-header"><?php echo $profileInfo->FirstName ?> <?php echo $profileInfo->LastName ?>'s Profile</h2>

	<div class="form-horizontal">
		<?php if ($profileInfo->Picture != "") { ?>
			<div class="form-group">
				<label class="col-sm-2 control-label">Picture</label>
				<div class="col-sm-10">
					<img src="<?php echo $GLOBALS["beans"]->fileHelper->getUploadedFileURL('profile', $profileInfo->Picture) ?>" height="200" />
				</div>
			</div>
		<?php } ?>

		<div class="form-group">
			<label class="col-sm-2 control-label">Nick Name</label>
			<div class="col-sm-10">
				<p class="form-control-static"><?php echo $profileInfo->NickName ?></p>
			</div>
		</div>

		<!-- Date -->
		<div class="form-group">
			<label class="col-sm-2 control-label">Birth Date</label>
			<div class="col-sm-10">
				<p class="form-control-static"><?php echo $profileInfo->FormattedDate ?></p>
			</div>
		</div>
	
		<div class="form-group">
			<label class="col-sm-2 control-label">Gender</label>
			<div class="col-sm-10">
				<p class="form-control-static">
					<?php if ($profileInfo->Gender == "M") { ?>Male <?php } ?>
					<?php if ($profileInfo->Gender == "F") { ?>Female <?php } ?>
				</p>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label">Email</label>
			<div class="col-sm-10">
				<p class="form-control-static"><?php echo $profileInfo->Email ?></p>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label">Interests</label>
			<div class="col-sm-10">
				<p class="form-control-static"><?php echo $tagList; ?></p>
			</div>
		</div>

		<!-- Buttons -->
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="button" class="btn btn-default" onClick="javascript: history.back()">Back</button>
			</div>
		</div>
	</div>
</div>