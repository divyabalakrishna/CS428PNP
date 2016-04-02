<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>

<div class="container">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form class="form-group" id="activationForm" method="post" action="<?php echo URL_WITH_INDEX_FILE; ?>user/activation">
				<div class="modal-header">
					<h3 class="modal-title" id="myModalLabel"><img class="icon" src="<?php echo URL; ?>public/img/icon.png"> Activation</h3>
				</div>
				<div class="modal-body">
					<label for="email" class="sr-only">Email address</label>
					<input type="email" id="email" name="email" class="form-control" placeholder="Email address" value="<?php echo $user->Email;?>" readonly required>
					<br>
					<label for="active" class="sr-only">Activation</label>
					<input type="text" id="active" name="active" class="form-control" placeholder="Activation code" value="<?php echo $active;?>" required>
					<br>
                    <?php if(isset($cheat)) {?>
					<label for="password" class="sr-only">Password</label>
					<input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                    <br>
                    <?php } ?>
                    
                    <?php echo $GLOBALS["beans"]->siteHelper->getAlertsHTML(); ?>                    
				</div>
				<div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6 text-left">
                            <a class="text-left" href="<?php echo URL_WITH_INDEX_FILE; ?>user/resendActivation">Resend Activation Code</a>
                        </div>
                        <div class="col-md-6 ">
                            <button type="submit" class="btn btn-primary">Activate</button>
                        </div>
                    </div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
    $(document).ready(function(){

        $("#activationForm").validate({
            
            rules: {
                email: {
                    required: true,
                    email: true
                },
                <?php if(isset($cheat)) {?>
                password: {
					required: true,
					minlength: 6,
				},
                <?php } ?>
                active: {
                    required: true,
                    minlength: 16
                }
            },
            messages: {
				email: {
                    required: "Please enter your email",
                    email: "Please enter correct email format"
                },
                <?php if(isset($cheat)) {?>                
				password: {
					required: "Please provide a password",
					minlength: "Your password must be at least 6 characters long",
					equalTo: "Please enter the same password as above"
				},
                <?php } ?>                
				active: {
					required: "Please provide a activation code",
					minlength: "Your activation code must be at least 16 characters long"
				}
			}
		});

        
	});
</script>
