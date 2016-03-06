<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>

<div class="container">
	<h2 class="page-header">Events Created</h2>

	<div class="clearfix table-action">
		<button type="button" id="add" class="btn btn-default pull-left">Create Event</button>

		<form method="post" class="form-inline table-filter pull-right">
			<div class="form-group">
				<label class="sr-only" for="timeType">Time Type</label>
				<select id="timeType" name="timeType" class="form-control">
					<option value="">- Time Type -</option>
					<option value="past" <?php if (strcasecmp("past", $timeType) == 0) { ?>selected<?php } ?>>Past</option>
					<option value="future" <?php if (strcasecmp("future", $timeType) == 0) { ?>selected<?php } ?>>Future</option>
				</select>
			</div>
			<button type="submit" class="btn btn-default btn-sm">Go</button>
			<button type="button" id="clear" class="btn btn-default btn-sm">Clear</button>
		</form>
	</div>

	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<th>Date/Time</th>
					<th>Location</th>
					<th>Participants</th>
					<th>Type</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($events as $event) { ?>
					<tr>
						<td>
							<a href="<?php echo URL_WITH_INDEX_FILE . "events/view/" . $event->EventID; ?>">
								<?php echo $event->Name ?>
							</a>
						</td>
						<td class="truncate"><?php echo $event->Description ?></td>
						<td><?php echo $event->FormattedDateTime ?></td>
						<td><?php echo $event->Address ?></td>
						<td>
							<?php echo $event->ParticipantCount;
							if (is_numeric($event->Capacity)) {
								echo " out of " . $event->Capacity;
							} ?>
						</td>
						<td><?php echo $event->TagName ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>

<script>
	$(document).ready(function(){
		$('#add').click(function(){
			window.location.href = '<?php echo URL_WITH_INDEX_FILE; ?>events/edit';
		});

		$('#clear').click(function(){
			window.location.href = '<?php echo URL_WITH_INDEX_FILE; ?>events/listHosted';
		});
	});
</script>