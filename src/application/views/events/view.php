<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>

<div class="container">
	<h2 class="page-header">Event Details</h2>

	<!-- TODO: Photo -->

	<div class="section form-horizontal">
		<div class="form-group">
			<label class="col-sm-2 control-label">Name</label>
			<div class="col-sm-10">
				<p class="form-control-static"><?php echo $event->Name ?></p>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Description</label>
			<div class="col-sm-10">
				<p class="form-control-static"><?php echo $event->Description ?></p>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Date/Time</label>
			<div class="col-sm-10">
				<p class="form-control-static"><?php echo $event->FormattedDate ?> <?php echo $event->FormattedTime ?></p>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Location</label>
			<div class="col-sm-10">
				<p class="form-control-static"><?php echo $event->Address ?></p>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Capacity</label>
			<div class="col-sm-10">
				<p class="form-control-static"><?php echo $event->Capacity ?></p>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Type</label>
			<div class="col-sm-10">
				<p class="form-control-static"><?php echo $event->TagName ?></p>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<?php if ($userID == $event->HostID) { ?>
					<button type="button" id="edit" class="btn btn-default">Edit</button>
					<button type="button" id="delete" class="btn btn-default">Delete</button>
				<?php } else { ?>
					<!-- TODO: Join/Leave Event -->
				<?php } ?>
			</div>
		</div>
	</div>

	<!-- TODO: Participant List -->
</div>

<script>
	$(document).ready(function(){
		<?php if ($userID == $event->HostID) { ?>
			$('#edit').click(function(){
				window.location.href = '<?php echo URL_WITH_INDEX_FILE . "events/edit/" . $event->EventID; ?>';
			});

			$('#delete').click(function(){
				if (confirm('Are you sure you want to delete this travel?'))
				{
					window.location.href = '<?php echo URL_WITH_INDEX_FILE . "events/delete/" . $event->EventID; ?>';
				}
			});
		<?php } else { ?>
			<!-- TODO: Join/Leave Event -->
		<?php } ?>
	});
</script>