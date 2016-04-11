<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>

<div class="container">
	<h2 class="page-header">Recreate Event</h2>

	<form id="form" method="post" action="<?php echo URL_WITH_INDEX_FILE; ?>events/recreateSave" enctype="multipart/form-data" class="form-horizontal">
		<input type="hidden" id="eventID" name="eventID" value="<?php echo $eventID ?>" />

		<!-- Date -->
		<div class="form-group">
			<label for="date" class="col-sm-2 control-label">Date</label>
			<div class="col-sm-10">
				<div class="input-group date col-sm-2">
					<input type="text" id="date" name="date" class="form-control" required aria-required="true" />
					<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
				</div>
			</div>
		</div>

		<!-- Time -->
		<div class="form-group">
			<label for="time" class="col-sm-2 control-label">Time</label>
			<div class="col-sm-10">
				<div class="input-group bootstrap-timepicker timepicker col-sm-2">
					<input type="text" id="time" name="time" value="<?php echo $event->FormattedTime ?>" class="form-control" required aria-required="true" />
					<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
				</div>
			</div>
		</div>

		<!-- Buttons -->
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-default">Save</button>
				<button type="button" id="cancel" class="btn btn-default">Cancel</button>
			</div>
		</div>
	</form>
</div>

<script>
	$(document).ready(function(){
		$('#cancel').click(function(){
			window.location.href = '<?php echo URL_WITH_INDEX_FILE . "events/view/". $eventID; ?>';
		});

		$('.input-group.date').datepicker({
			todayBtn: 'linked',
			clearBtn: true
		});
		/*Input time*/
		$('.input-group.timepicker').find('input[type="text"]').each(function() {
			$(this).timepicker({
				defaultTime: false
			});
		});

		$.validator.addMethod("todayOrFutureDate", function(value, element) {
			if (this.optional(element)) {
				return true;
			}

			if (!/Invalid|NaN/.test(new Date(value))) {
				var today = new Date();
				today.setHours(0, 0, 0, 0);

				return new Date(value) >= today;
			}
			/*In case date added is in the past*/
			return false;
		}, "Please enter a future date.");

		$.validator.addMethod("futureTime", function(value, element) {
			/* If date is invalid, there is no need to check the time */
			var dateString = $('#date').val();
			var today = new Date();
			today.setHours(0, 0, 0, 0);
			if (/Invalid|NaN/.test(new Date(dateString)) || (new Date(dateString) < today)) {
				return true;
			}

			var dateTimeString = dateString + ' ' + value;
			if (!/Invalid|NaN/.test(new Date(dateTimeString))) {
				return new Date(dateTimeString) > new Date();
			}
			/*In case the time added is in the past*/
			return false;
		}, "Please enter a future time.");
		
		/*Check if the form is validated*/
		$('#form').validate({
			rules: {
				date: {
					date: true,
					todayOrFutureDate: true
				},
				time: {
					time12h: true,
					futureTime: true
				}
			}
		});
	});
</script>
