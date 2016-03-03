<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>

<div class="container">
	<form class="form-horizontal">
		<div class="form-group">
	  		<label for="firstName" class="col-sm-2 control-label">First Name</label>
	    	<div class="col-sm-10">
	    		<input type="firstname" class="form-control" id="firstname" placeholder="First Name">
	    	</div>
		</div>
		<div class="form-group">
			<label for="lastname" class="col-sm-2 control-label">Last Name</label>
			<div class="col-sm-10">
				<input type="lastname" class="form-control" id="lastname" placeholder="Last Name">
	    	</div>
	    </div>
	    <div class="form-group">
			<label for="nickname" class="col-sm-2 control-label">Nick Name</label>
			<div class="col-sm-10">
				<input type="nickname" class="form-control" id="nickname" placeholder="Nick Name">
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
	    <div class="col-sm-offset-2 col-sm-10">
	      <div class="checkbox">
	        <label>
	          <input type="checkbox"> Remember me
	        </label>
	      </div>
	    </div>
	  </div>
	  <div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10">
	      <button type="submit" class="btn btn-default">Sign in</button>
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