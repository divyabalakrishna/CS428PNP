<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); }

$joinAllowed = false;
if (!is_numeric($event->Capacity) || $event->Capacity == 0) {
	$joinAllowed = true;
}
else if ($event->Capacity > count($participants)) {
	$joinAllowed = true;
}
?>

<div class="container">
	<div class="detailsHeading" >
		<?php if ($event->Image != "") { ?>
			<img class="image" src="<?php echo $GLOBALS["beans"]->fileHelper->getUploadedFileURL('event', $event->Image) ?>"/>
		<?php } else { ?>
			<img class="imageTag" src="<?php echo $GLOBALS["beans"]->siteHelper->getTagImage($event->TagID) ?>"/>
		<?php } ?>
		<div class="title"><?php echo $event->Name ?></div>
	</div>
	
	<div>
		<div class="eventDetails">
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
			<div>
				<div>
					<?php if ($userID == $event->HostID) { ?>
						<button type="button" id="edit" class="btn btn-default">Edit</button>
						<button type="button" id="delete" class="btn btn-default">Delete</button>
					<?php } else if (count($userParticipation) > 0) { ?>
						<button type="button" id="leave" class="btn btn-default">Leave</button>
					<?php } else if ($joinAllowed && count($userParticipation) == 0) { ?>
						<button type="button" id="join" class="btn btn-default">Join</button>
					<?php } else { ?>
						This event has reached the maximum capacity.
					<?php } ?>
				</div>
			</div>
		</div>
		
		<div class="participants">
			
			<h3 class="page-header">Participants</h3>
			<?php foreach ($participants as $person) { ?>
				<div class="profile">
					<?php if ($person->Picture == "") { ?>
						<img class="image" src="<?php echo URL; ?>public/img/profile.png">
					<?php } else { ?>
						<img class="image" src="<?php echo $GLOBALS["beans"]->fileHelper->getUploadedFileURL('profile', $person->Picture) ?>"/>
					<?php } ?>
					<div class="name"><?php echo $person->FirstName ?></div>
				</div>
			<?php } ?>
		</div>
	
		<br/><br/><br/>
		
		<!-- Media -->
		<div class="media">
			<h3 class="page-header">Media</h3>
			<div class = "photos">
			<?php foreach ($media as $image) { ?>
				<div class="profile">
					<?php if ($image->Image != "") { ?>
						<img class="image" src="<?php echo $GLOBALS["beans"]->fileHelper->getUploadedFileURL('media', $image->Image) ?>"/>
					<?php } ?>
				</div>
			<?php } ?>
			</div>
			
			<form id="formUpload" method="post" action="<?php echo URL_WITH_INDEX_FILE; ?>events/upload" enctype="multipart/form-data" >
				<input type="hidden" id="eventID" name="eventID" value="<?php echo $event->EventID ?>" />
				<label class="col-sm-2 control-label">Image</label>
				<div class="col-sm-10">
					<input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
					<input type="file" id="image" name="image" accept="image/jpg,image/jpeg,image/png,image/bmp" class="form-control" />
					<p class="help-block">Max file size: 2 MB. Accepted file types: .jpg, .jpeg, .png, .bmp</p>
					
				</div>
				<!-- Buttons -->
				<button type="submit" class="btn btn-default">Upload</button>
			</form>
		</div>
		
		<div class="comments">
			<h3 class="page-header">Comments</h3>
			<table class="table table-striped">
				<tbody>
					<?php $parentID = "";
					foreach ($comments as $comment) { 
						if ($parentID != "" && $parentID != $comment->ParentID) { ?>
							<tr>
								<td><a onclick="reply(this, <?php echo $parentID ?>)">
									reply
								</a></td>
								<td colspan="2"></td>
							</tr>
						<?php }
						if ($comment->ParentID == $comment->CommentID) { ?>
							<tr>
								<td class="col-md-1"><?php echo $comment->FirstName ?></td>
								<td colspan="2"><?php echo $comment->Text ?></td>
							</tr>
							
						<?php } else { ?>
							<tr>
								<td></td>	
								<td class="col-md-1"><?php echo $comment->FirstName ?></td>
								<td><?php echo $comment->Text ?></td>
							</tr>
						<?php } ?>
					<?php $parentID = $comment->ParentID; 
					} 
					if ($parentID != "") { ?>
						<tr>
							<td><a onclick="reply(this, <?php echo $parentID ?>)">
								reply
							</a></td>
							<td colspan="2"></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			
			<form id="form" method="post" action="<?php echo URL_WITH_INDEX_FILE; ?>events/reply" enctype="multipart/form-data" class="form-horizontal">
				<input type="hidden" id="eventID" name="eventID" value="<?php echo $event->EventID ?>" />
				<input type="hidden" id="userID" name="userID" value="<?php echo $userID ?>" />
				<input type="hidden" id="parentID" name="parentID" value="" />
		
				<!-- Name -->
				<div class="form-group">
					<div class="col-sm-10">
						<textarea class="form-control" id="text" name="text" required aria-required="true"></textarea>
					</div>
				</div>
				
				<!-- Buttons -->
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-default">Save</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	
</div>


<script>
	$(document).ready(function(){
		<?php if ($userID == $event->HostID) { ?>
			$('#edit').click(function(){
				window.location.href = '<?php echo URL_WITH_INDEX_FILE . "events/edit/" . $event->EventID; ?>';
			});

			$('#delete').click(function(){
				if (confirm('Are you sure you want to delete this event?'))
				{
					window.location.href = '<?php echo URL_WITH_INDEX_FILE . "events/delete/" . $event->EventID; ?>';
				}
			});
		<?php } else if (count($userParticipation) > 0) { ?>
			$('#leave').click(function() {
				window.location.href = '<?php echo URL_WITH_INDEX_FILE . "events/leave/" . $event->EventID; ?>';
			});
		<?php } else if ($joinAllowed && count($userParticipation) == 0) { ?>
			$('#join').click(function() {
				window.location.href = '<?php echo URL_WITH_INDEX_FILE . "events/join/" . $event->EventID; ?>';
			});
		<?php } ?>

		$('#form').validate({});
	});

	function reply(replyLink, parentID) {
		var td = $(replyLink).parent().next();
		var form = $('<form method="post" action="<?php echo URL_WITH_INDEX_FILE; ?>events/reply" class="form-horizontal"></form>');
		form.append('<input type="hidden" name="eventID" value="<?php echo $event->EventID ?>" />');
		form.append('<input type="hidden" name="parentID" value="' + parentID + '" />');
		form.append('<input type="text" name="text" required aria-required="true" />');
		form.append('<button type="submit" class="btn btn-default" style="margin-left:5px">Save</button>');
		td.append(form);
		return false;
	};
</script>