<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>

<div class="container well">
	<?php echo $GLOBALS["beans"]->siteHelper->getAlertsHTML(); ?>

	<h2 class="page-header">Edit Profile</h2>

	<form id="form" method="post" action="<?php echo URL_WITH_INDEX_FILE; ?>user/saveProfile" class="form-horizontal" enctype="multipart/form-data">
		<div class="row">
			<div class="col-sm-6 col-md-6">

				<div class="form-group">
					<label for="firstName" class="col-sm-4 control-label">First Name</label>
					<div class="col-sm-8">
					<input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $profileInfo->FirstName ?>" placeholder="First Name" required aria-required="true">
					</div>
				</div>
				<div class="form-group">
					<label for="lastname" class="col-sm-4 control-label">Last Name</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $profileInfo->LastName ?>" placeholder="Last Name" required aria-required="true">
					</div>
				</div>
				<div class="form-group">
					<label for="nickname" class="col-sm-4 control-label">Nick Name</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="nickname" name="nickname" value="<?php echo $profileInfo->NickName ?>" placeholder="Nick Name">
					</div>
				</div>

				<!-- Date -->
				<div class="form-group">
					<label for="birthdate" class="col-sm-4 control-label">Birth Date</label>
					<div class="col-sm-8">
						<div class="input-group date col-sm-6">
							<input type="text" id="birthdate" name="birthdate" value="<?php echo $profileInfo->FormattedDate ?>" class="form-control" />
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="gender" class="col-sm-4 control-label">Gender</label>
					<div class="col-sm-8">
						<select id="gender" name="gender" class="form-control">
							<option value="">- Gender -</option>
							<option value="M" <?php if ($profileInfo->Gender == "M") { ?>selected<?php } ?>>Male</option>
							<option value="F" <?php if ($profileInfo->Gender == "F") { ?>selected<?php } ?>>Female</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label for="phone" class="col-sm-4 control-label">Phone</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="phone" name="phone" value="<?php echo $profileInfo->Phone ?>" placeholder="Phone">
					</div>
				</div>

				<div class="form-group">
					<label for="email" class="col-sm-4 control-label">Email</label>
					<div class="col-sm-8">
						<input type="email" class="form-control" id="email" name="email" value="<?php echo $profileInfo->Email ?>" placeholder="Email" required aria-required="true">
					</div>
				</div>

				<div class="form-group">
					<label for="newPassword" class="col-sm-4 control-label">New Password</label>
					<div class="col-sm-8 form-field">
						<input type="password" id="newPassword" name="newPassword" class="form-control" placeholder="New Password" />
					</div>
				</div>
				<div class="form-group">
					<label for="confirmNewPassword" class="col-sm-4 control-label">Confirm New Password</label>
					<div class="col-sm-8 form-field">
						<input type="password" id="confirmNewPassword" name="confirmNewPassword" class="form-control" placeholder="Confirm New Password" />
					</div>
				</div>

			</div>
			<div class="col-sm-6 col-md-6">

				<!-- Image -->
				<div class="text-center">
					<?php 
						if ($profileInfo->Picture != "") {
							$picture = $GLOBALS["beans"]->fileHelper->getUploadedFileURL('profile', $profileInfo->Picture);
						} else {
							$picture = URL. "public/img/profile.png";
						}
					?>
					<a class="image-frame-profile" href="" data-toggle="modal" data-target="#showModal" >
						<div class="image-thumb-profile" style="background-image: url('<?php echo $picture ?>');"></div>
					</a>

				</div>
				<div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h3 class="modal-title" id="myModalLabel"><img class="icon" src="<?php echo URL; ?>public/img/icon.png"> Picture Profile</h3>
								
							</div>
							<div class="modal-body text-center">

								<div class="form-group">
									<?php 
										if ($profileInfo->Picture != "") {
											$picture = $GLOBALS["beans"]->fileHelper->getUploadedFileURL('profile', $profileInfo->Picture);
										} else {
											$picture = URL. "public/img/profile.png";
										}
									?>
									<div class="image-frame-profile">
										<div class="image-thumb-profile" style="background-image: url('<?php echo $picture ?>');"></div>
									</div>
									<br>
									<div class="col-sm-12">
										<input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
										<input type="file" id="picture" name="picture[]" accept="image/jpg,image/jpeg,image/png,image/bmp" class="form-control" />
										<p class="help-block">Max file size: 2 MB. Accepted file types: .jpg, .jpeg, .png, .bmp</p>
									</div>
								</div>
							</div>

							<div class="modal-footer">
								<div class="right">
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
									<button id="uploadPicture" type="submit" class="btn btn-primary" onclick="">Upload</button>
								</div>
							</div>

						</div>
					</div>
				</div>

				<p></p>
				<h3 class="page-header">Interests</h3>
				<div class="form-group">
					<input type="hidden" id="user_tags" name="user_tags" />
					<div class="col-sm-12">
						<?php foreach ($tags as $tag) { ?>
							<label class="checkbox-inline">
								<input type="checkbox" class="tag-checkbox" id="inlineCheckbox1" value="<?php echo $tag->TagID; ?>" <?php foreach ($tagInfo as $usertag) { if ($usertag->TagID == $tag->TagID) { ?>checked<?php }} ?>> <?php echo $tag->Name; ?>
							</label>
						<?php } ?>
					</div>
				</div>
			</div>

		</div>

		<h2 class="page-header"></h2>

		<div class="form-group">
			<div class="col-sm-12 col-sm-12 text-center">
				<button type="submit" class="btn btn-default">Save</button>
			</div>
		</div>
	</form>
</div>

<script>
	var currentdate = new Date();

	$(document).ready(function(){

		// Datepicker event handler
		$('.input-group.date').datepicker({
			todayBtn: 'linked',
			clearBtn: true
		});

		// Form validation
		$('#form').validate({
			rules: {
				birthdate: {
					date: true,
					pastDate: true
				},
				confirmNewPassword: {
					equalTo: '#newPassword'
				}
			},
			messages: {
				confirmNewPassword: {
					equalTo: 'Confirm new password should match new password.'
				}
			}
		});

		// Form submit event handler
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