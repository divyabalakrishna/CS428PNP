<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>

<div class="container">
	<?php echo $GLOBALS["beans"]->siteHelper->getAlertsHTML(); ?>

	<h2 class="page-header">
		View Profile
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	</h2>
	
	<div class="form-group">
		<label for="picture" class="col-sm-2 control-label">Picture</label>
		<div class="col-sm-10">
			<?php if ($profileInfo->Picture != "") { ?>
				<img src="<?php echo $GLOBALS["beans"]->fileHelper->getUploadedFileURL('profile', $profileInfo->Picture) ?>" height="200" />
			<?php } ?>
		</div>
	</div>
		
	<div class="form-group">
  		<label for="firstName" class="col-sm-2 control-label">First Name</label>
  		<label for="firstName" class="control-label"><?php echo $profileInfo->FirstName ?></label>
	</div>
	<div class="form-group">
		<label for="lastname" class="col-sm-2 control-label">Last Name</label>
		<label for="lastname" class="control-label"><?php echo $profileInfo->LastName ?></label>
    </div>
    <div class="form-group">
		<label for="nickname" class="col-sm-2 control-label">Nick Name</label>
		<label for="nickname" class="control-label"><?php echo $profileInfo->NickName ?></label>
    </div>

    <!-- Date -->
	<div class="form-group">
		<label for="birthdate" class="col-sm-2 control-label">Birth Date</label>
		<label for="birthdate" class="control-label"><?php echo $profileInfo->FormattedDate ?></label>
	</div>
	
	<div class="form-group">
		<label for="gender" class="col-sm-2 control-label">Gender</label>
		<label for="gender" class="control-label">
			<?php if ($profileInfo->Gender == "M") { ?>Male <?php } ?>
			<?php if ($profileInfo->Gender == "F") { ?>Female <?php } ?>
		</label>
		
		
    </div>
    
    <div class="form-group">
		<label for="phone" class="col-sm-2 control-label">Phone</label>
		<label for="phone" class="control-label"><?php echo $profileInfo->Phone ?></label>
    </div>
    
    <div class="form-group">
		<label for="email" class="col-sm-2 control-label">Email</label>
		<label for="email" class="control-label"><?php echo $profileInfo->Email ?></label>
    </div>
    
    <div class="form-group">
    	<input type="hidden" id="user_tags" name="user_tags" />
    	<label for="interests" class="col-sm-2 control-label">Interests</label>
		<div class="col-sm-10">
			<?php foreach ($tags as $tag) { ?>
				<label class="checkbox-inline">
					<input type="checkbox" class="tag-checkbox" id="inlineCheckbox1" value="<?php echo $tag->TagID; ?>" <?php foreach ($tagInfo as $usertag) { if ($usertag->TagID == $tag->TagID) { ?>checked<?php }} ?> disabled readonly> <?php echo $tag->Name; ?>
				</label>
			<?php } ?>
    	</div>
    </div>
</div>
