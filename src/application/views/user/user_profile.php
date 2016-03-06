<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>

<div class="container">
	<!-- Photo -->

	<form id="form" method="post" action="<?php echo URL_WITH_INDEX_FILE; ?>user/saveProfile" class="form-horizontal" enctype="multipart/form-data">
		
		<div class="form-group">
	  		<label for="firstName" class="col-sm-2 control-label">First Name</label>
	    	<div class="col-sm-10">
	    		<input type="firstname" class="form-control" id="firstname" name="firstname" value="<?php echo $profileInfo->FirstName ?>" placeholder="First Name" required aria-required="true">
	    	</div>
		</div>
		<div class="form-group">
			<label for="lastname" class="col-sm-2 control-label">Last Name</label>
			<div class="col-sm-10">
				<input type="lastname" class="form-control" id="lastname" name="lastname" value="<?php echo $profileInfo->LastName ?>" placeholder="Last Name" required aria-required="true">
	    	</div>
	    </div>
	    <div class="form-group">
			<label for="nickname" class="col-sm-2 control-label">Nick Name</label>
			<div class="col-sm-10">
				<input type="nickname" class="form-control" id="nickname" name="nickname" value="<?php echo $profileInfo->NickName ?>" placeholder="Nick Name">
	    	</div>
	    </div>
	    
	    <!-- Date -->
		<div class="form-group">
			<label for="birthdate" class="col-sm-2 control-label">Birth Date</label>
			<div class="col-sm-10">
				<div class="input-group date col-sm-2">
					<input type="text" id="birthdate" name="birthdate" value="<?php echo $profileInfo->FormattedDate ?>" class="form-control" required aria-required="true" />
					<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
				</div>
			</div>
		</div>
		
		<div class="form-group">
			<label for="gender" class="col-sm-2 control-label">Gender</label>
			<div class="col-sm-10">
				<select id="gender" name="gender" class="form-control">
					<option value="">- Gender -</option>
					<option value="M" <?php if ($profileInfo->Gender == "M") { ?>selected<?php } ?>>Male</option>
					<option value="F" <?php if ($profileInfo->Gender == "F") { ?>selected<?php } ?>>Female</option>
				</select>
	    	</div>
	    </div>
	    
	    <div class="form-group">
			<label for="phone" class="col-sm-2 control-label">Phone</label>
			<div class="col-sm-10">
				<input type="phone" class="form-control" id="phone" name="phone" value="<?php echo $profileInfo->Phone ?>" placeholder="Phone">
	    	</div>
	    </div>
	    
	    <div class="form-group">
			<label for="email" class="col-sm-2 control-label">Email</label>
			<div class="col-sm-10">
				<input type="email" class="form-control" id="email" name="email" value="<?php echo $profileInfo->Email ?>" placeholder="test@test.com" required aria-required="true">
	    	</div>
	    </div>
	    
	    <!-- Image -->
		<div class="form-group">
			<label for="picture" class="col-sm-2 control-label">Profile</label>
			<div class="col-sm-10">
				<input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
				<input type="file" id="picture" name="picture" accept="image/jpg,image/jpeg,image/png,image/bmp" class="form-control" />
				<p class="help-block">Max file size: 2 MB. Accepted file types: .jpg, .jpeg, .png, .bmp</p>
				<?php if ($profileInfo->Picture != "") { ?>
					<img src="<?php echo $GLOBALS["beans"]->fileHelper->getUploadedFileURL('profile', $profileInfo->Picture) ?>" height="100" />
				<?php } ?>
			</div>
		</div>
	    
	    <div class="form-group">
	    	<input type="hidden" id="user_tags" name="user_tags" />
	    	<label for="interests" class="col-sm-2 control-label">Interests</label>
			<div class="col-sm-10">
				<?php foreach ($tags as $tag) { ?>
					<label class="checkbox-inline">
						<input type="checkbox" class="tag-checkbox" id="inlineCheckbox1" value="<?php echo $tag->TagID; ?>" <?php foreach ($tagInfo as $usertag) { if ($usertag->TagID == $tag->TagID) { ?>checked<?php }} ?>> <?php echo $tag->Name; ?>
					</label>
				<?php } ?>
	    	</div>
	    </div>
	    
	  
	  	<div class="form-group">
	    	<div class="col-sm-offset-2 col-sm-10">
	      		<button type="submit" class="btn btn-default">Save</button>
	    	</div>
	  	</div>
	</form>
</div>

<script>
	var currentdate =  new Date();
	
	$(document).ready(function(){
		
		$('.input-group.date').datepicker({
			todayBtn: 'linked',
			clearBtn: true
		});

		$.validator.addMethod('atLeastOne', function() {
			  return $('input.tag-checkbox:checked').length > 0 ? true : false;
		}, 'Please select at least one interest.');

		$('#form').validate({
			rules: {
				birthdate: {
					date: true,
					
				},
				user_tags: {
					atLeastOne: true
				}
			}
		});
		

		$('#form').submit(function() {
			var user_tags = '';
			$('input.tag-checkbox:checked').each(function() {
				user_tags = user_tags + ',' + $(this).val();
			});
			if (user_tags.substr(0,1) == ',') {
				user_tags = user_tags.substr(1);
			}
			$('#user_tags').val(user_tags);
		});
	});
</script>