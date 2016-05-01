<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>

<div class="container">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form class="form-group" id="resetForm" method="post" action="<?php echo URL_WITH_INDEX_FILE; ?>user/resetPassword">
				<fieldset>
					<div class="modal-header">
						<h3 class="modal-title" id="myModalLabel"><img class="icon" src="<?php echo URL; ?>public/img/icon.png"> Reset Password</h3>
					</div>
					<div class="modal-body">
						<label for="email" class="sr-only">Email address</label>
						<input type="hidden" id="existingEmail" name="existingEmail" value="<?php echo $email ?>" />
						<input type="email" id="email" name="email" class="form-control" placeholder="Email address" value="<?php echo $email ?>" readonly required>
						<br>
						<label for="password1" class="sr-only">Password</label>
						<input type="password" id="password1" name="password1" class="form-control" placeholder="New Password" required>
						<br>
						<label for="password2" class="sr-only">Re-type Password</label>
						<input type="password" id="password2" name="password2" class="form-control" placeholder="Re-type New Password" required>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Reset</button>
					</div>
				</fieldset>
			</form>
		</div>
	</div>

</div>
<script>
	$(document).ready(function() {

        /**
         * Reset Form validation
         */
		$("#resetForm").validate({
			rules: {
				email: {
					required: true,
					email: true
				},
				password1: {
					required: true,
					minlength: 6
				},
				password2: {
					required: true,
					minlength: 6,
					equalTo: "#password1"
				}
			},
			messages: {
				email: {
					required: "Please enter your email",
					email: "Please enter correct email format"
				},
				password1: {
					required: "Please provide a password",
					minlength: "Your password must be at least 6 characters long"
				},
				password2: {
					required: "Please provide a password",
					minlength: "Your password must be at least 6 characters long",
					equalTo: "Please enter the same password as above"
				}
			}
		});
	});
</script>
