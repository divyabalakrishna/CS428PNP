<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>

<div class="container">
	<?php if ($event->Image != "") { ?>
		<img src="<?php echo $GLOBALS["beans"]->fileHelper->getUploadedFileURL('event', $event->Image) ?>" height="100" class="center-block" />
	<?php } ?>

	<h2 class="page-header">Event Details</h2>

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
				<?php } else if ($userID == $event->HostID) { ?>
					<!-- TODO: Join/Leave Event -->
					
				<?php } else { ?>
					<button type="button" id="join" class="btn btn-default">Join</button>
				<?php } ?>
			</div>
		</div>
	</div>

	<!-- TODO: Participant List -->
	
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Comment ID</th>
					<th>User ID</th>
					<th>Comment</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($comments as $comment) { ?>
					<tr>
						<!-- 
						<td>
							<a href="<?php echo URL_WITH_INDEX_FILE . "events/view/" . $event->EventID; ?>">
								<?php echo $event->Name ?>
							</a>
						</td>
						<td class="truncate"><?php echo $event->Description ?></td> -->
						<td><?php echo $comment->CommentID ?></td>
						<td><?php echo $comment->FirstName ?></td>
						<td><?php echo $comment->Text ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	
	<form id="form" method="post" action="<?php echo URL_WITH_INDEX_FILE; ?>events/reply" enctype="multipart/form-data" class="form-horizontal">
		<input type="hidden" id="eventID" name="eventID" value="<?php echo $event->EventID ?>" />
		<input type="hidden" id="userID" name="userID" value="<?php echo $userID ?>" />
		<input type="hidden" id="parentID" name="parentID" value="" />

		<!-- Name -->
		<div class="form-group">
			<label for="name" class="col-sm-2 control-label">Comment</label>
			<div class="col-sm-10">
				<input type="textview" id="text" name="text" class="form-control" required aria-required="true">
			</div>
		</div>
		
		<!-- Buttons -->
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-default">Save</button>
				<!-- 
				<button type="button" id="cancel" class="btn btn-default">Cancel</button>  -->
			</div>
		</div>
	</form>
</div>
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