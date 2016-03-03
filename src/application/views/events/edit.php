<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); }

if (is_numeric($event->EventID)) {
	$title = "Edit Event";
	$cancelURL = URL_WITH_INDEX_FILE . "events/view/" . $eventID;
}
else {
	$title = "Create Event";
	$cancelURL = URL_WITH_INDEX_FILE . "events/listHosted";
}
?>

<div class="container">
	<h2 class="page-header"><?php echo $title; ?></h2>

	<!-- Photo -->

	<form id="form" method="post" action="<?php echo URL_WITH_INDEX_FILE; ?>events/save" class="form-horizontal">
		<input type="hidden" id="eventID" name="eventID" value="<?php echo $event->EventID ?>" />

		<!-- Name -->
		<div class="form-group">
			<label for="name" class="col-sm-2 control-label">Name</label>
			<div class="col-sm-10">
				<input type="text" id="name" name="name" value="<?php echo $event->Name ?>" class="form-control" required aria-required="true" placeholder="Event Name">
			</div>
		</div>

		<!-- Description -->
		<div class="form-group">
			<label for="description" class="col-sm-2 control-label">Description</label>
			<div class="col-sm-10">
				<textarea id="description" name="description" class="form-control" placeholder="Event Description"><?php echo $event->Description ?></textarea>
			</div>
		</div>

		<!-- Date -->
		<div class="form-group">
			<label for="date" class="col-sm-2 control-label">Date</label>
			<div class="col-sm-10">
				<div class="input-group date col-sm-2">
					<input type="text" id="date" name="date" value="<?php echo $event->FormattedDate ?>" class="form-control" required aria-required="true" />
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

		<!-- Location --> 
		<div class="form-group">
			<label for="address" class="col-sm-2 control-label">Location</label>
			<div class="col-sm-10">
				<input type="text" id="address" name="address" value="<?php echo $event->Address ?>" class="form-control" required aria-required="true" placeholder="Event Location">
			</div>
		</div>

		<!-- Capacity -->  
		<div class="form-group">
			<label for="capacity" class="col-sm-2 control-label">Capacity</label>
			<div class="col-sm-10">
				<input type="number" id="capacity" name="capacity" value="<?php echo $event->Capacity ?>" min="1" step="1" class="form-control" placeholder="Event Capacity">
			</div>
		</div>

		<!-- Type -->
		<div class="form-group">
			<label for="tagID" class="col-sm-2 control-label">Type</label>
			<div class="col-sm-10">
				<select id="tagID" name="tagID" class="form-control">
					<option value="">- Event Type -</option>
					<?php foreach ($tags as $tag) { ?>
						<option value="<?php echo $tag->TagID; ?>" <?php if ($event->TagID == $tag->TagID) { ?>selected<?php } ?>><?php echo $tag->TagID; ?></option>
					<?php } ?>
				</select>
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
			window.location.href = '<?php echo $cancelURL; ?>';
		});

		$('.input-group.date').datepicker({
			todayBtn: 'linked',
			clearBtn: true
		});

		$('.input-group.timepicker').find('input[type="text"]').each(function() {
			$(this).timepicker({
				defaultTime: false
			});
		});

		$('#form').validate({
			rules: {
				date: {
					date: true
				}
			}
		});
	});
</script>