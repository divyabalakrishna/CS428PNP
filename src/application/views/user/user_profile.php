<!--<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>-->

<div class="container">
	<!-- Photo -->

	<form id="form" method="post" action="<?php echo URL_WITH_INDEX_FILE; ?>user/saveProfile" class="form-horizontal">
		<div class="form-group">
	  		<label for="firstName" class="col-sm-2 control-label">First Name</label>
	    	<div class="col-sm-10">
	    		<input type="firstname" class="form-control" id="firstname" value="<?php echo $profileInfo->FirstName ?>" placeholder="First Name">
	    	</div>
		</div>
		<div class="form-group">
			<label for="lastname" class="col-sm-2 control-label">Last Name</label>
			<div class="col-sm-10">
				<input type="lastname" class="form-control" id="lastname" value="<?php echo $profileInfo->LastName ?>" placeholder="Last Name">
	    	</div>
	    </div>
	    <div class="form-group">
			<label for="nickname" class="col-sm-2 control-label">Nick Name</label>
			<div class="col-sm-10">
				<input type="nickname" class="form-control" id="nickname" value="" placeholder="Nick Name">
	    	</div>
	    </div>
	    
	    <!-- Date -->
		<div class="form-group">
			<label for="birthdate" class="col-sm-2 control-label">Birth Date</label>
			<div class="col-sm-10">
				<div class="input-group date col-sm-2">
					<!-- <input type="text" id="birthdate" name="birthdate" value="<?php echo $event->FormattedDate ?>" class="form-control" required aria-required="true" /> -->
					<input type="text" id="birthdate" name="birthdate" value="03/03/1999" class="form-control" required aria-required="true" />
					<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
				</div>
			</div>
		</div>
		
		<div class="form-group">
			<label for="gender" class="col-sm-2 control-label">Gender</label>
			<div class="col-sm-10">
				<input type="gender" class="form-control" id="gender" placeholder="Gender">
	    	</div>
	    </div>
	    
	    <div class="form-group">
			<label for="phone" class="col-sm-2 control-label">Phone</label>
			<div class="col-sm-10">
				<input type="phone" class="form-control" id="phone" value="<?php echo $profileInfo->Phone ?>" placeholder="Phone">
	    	</div>
	    </div>
	    
	    <div class="form-group">
			<label for="email" class="col-sm-2 control-label">Email</label>
			<div class="col-sm-10">
				<input type="email" class="form-control" id="email" value="<?php echo $profileInfo->Email ?>" placeholder="test@test.com">
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
	$(document).ready(function(){
		$('.input-group.date').datepicker({
			todayBtn: 'linked',
			clearBtn: true
		});

		$('#form').validate({
			rules: {
				birthdate: {
					date: true
				}
			}
		});
	});
</script>