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
	<div class="detailsHeading well" >
		<?php if ($event->Image != "") { ?>
			<img class="image" src="<?php echo $GLOBALS["beans"]->fileHelper->getUploadedFileURL('event', $event->Image) ?>"/>
		<?php } else { ?>
			<img class="imageTag" src="<?php echo $GLOBALS["beans"]->siteHelper->getTagImage($event->TagID) ?>"/>
		<?php } ?>
		<div class="title"><?php echo $event->Name ?></div>
	</div>
	
	<div class="col-sm-6">
		<div class="form-horizontal eventDetails">
			<div class="form-group">
				<label class="col-sm-1 control-label">Description</label>
				<div class="col-sm-5">
					<p class="form-control-static"><?php echo $event->FormattedDate ?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-1 control-label">Date/Time</label>
				<div class="col-sm-5">
					<p class="form-control-static"><?php echo $event->FormattedDate ?> <?php echo $event->FormattedTime ?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-1 control-label">Location</label>
				<div class="col-sm-5">
					<p class="form-control-static"><?php echo $event->Address ?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-1 control-label">Capacity</label>
				<div class="col-sm-5">
					<p class="form-control-static"><?php echo $event->Capacity ?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-1 control-label">Type</label>
				<div class="col-sm-5">
					<p class="form-control-static"><?php echo $event->TagName ?></p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-1 control-label">Host</label>
				<div class="col-sm-5">
					<p class="form-control-static"><?php echo $event->HostFirstName." ".$event->HostLastName ?></p>
				</div>
			</div>
			<div>
				<div>
					<?php if (strtotime($event->Time) > time()) {
						if ($userID == $event->HostID) { ?>
							<button type="button" id="edit" class="btn btn-default">Edit</button>
							<button type="button" id="delete" class="btn btn-default">Delete</button>
						<?php } else if (count($userParticipation) > 0) { ?>
							<button type="button" id="leave" class="btn btn-default">Leave</button>
						<?php } else if ($joinAllowed && count($userParticipation) == 0) { ?>
							<button type="button" id="join" class="btn btn-default">Join</button>
						<?php } else { ?>
							This event has reached the maximum capacity.
						<?php }
					} else if ($userID == $event->HostID) { ?>
						<button type="button" id="recreate" class="btn btn-default">Recreate</button>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	
	<div class="form-horizontal attendees">	
		<div class="participants">
			<h3 class="page-header">Participants</h3>
			<div class="form-group profilePhotos">
				<?php foreach ($participants as $person) { ?>
					<div class="profile">
						<a href="<?php echo URL_WITH_INDEX_FILE . "user/viewParticipantProfile/" . $person->UserID; ?>">
							<?php if ($person->Picture == "") { ?>
								<img class="image" src="<?php echo URL; ?>public/img/profile.png">
							<?php } else { ?>
								<img class="image" src="<?php echo $GLOBALS["beans"]->fileHelper->getUploadedFileURL('profile', $person->Picture) ?>"/>
							<?php } ?>
							<div class="name"><?php echo $person->FirstName ?></div>
						</a>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>	
	<br><br>

		<!-- Media -->
		<div class="media">
			<h3 class="page-header">Media</h3>
			<?php echo $GLOBALS["beans"]->siteHelper->getAlertsHTML(); ?>
			<div class="row well">
				<div class="col-md-12">
					<div class="carousel carousel-showmanymoveone slide" id="carousel123">
						<div class="carousel-inner">
							<?php $cnt = 0;
							$act = "active";
							foreach ($media as $image) { 
								if ($image->Image != "") { 
									$ext = explode(".", strtolower($image->Image));
									if ($ext[1] == "mp4") { ?>
										<div class="item <?php echo "$act"; ?> ">
											<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
												<a class="image-frame-car" href="" data-toggle="modal" data-target="#showModal" data-id="<?php echo $image->MediaID;?>" data-owner="<?php echo $image->UserID;?>" data-whatever="<?php echo $GLOBALS["beans"]->fileHelper->getUploadedFileURL('media', $image->Image) ?>" data-type="video">
												<video class="image-thumb-car">
													<source src="<?php echo $GLOBALS["beans"]->fileHelper->getUploadedFileURL('media', $image->Image) ?>" type="video/mp4">
													<source src="<?php echo $GLOBALS["beans"]->fileHelper->getUploadedFileURL('media', $image->Image) ?>" type="video/ogg">
													Your browser does not support HTML5 video.
												</video>
												</a>
											</div>
										</div>
									<?php } else { ?>
										<div class="item <?php echo "$act"; ?> ">
											<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
												<a class="image-frame-car" href="" data-toggle="modal" data-target="#showModal" data-id="<?php echo $image->MediaID;?>" data-owner="<?php echo $image->UserID;?>" data-whatever="<?php echo $GLOBALS["beans"]->fileHelper->getUploadedFileURL('media', $image->Image) ?>" data-type="image">
													<div class="image-thumb-car" style="background-image: url('<?php echo $GLOBALS["beans"]->fileHelper->getUploadedFileURL('media', $image->Image) ?>');">
													</div>
												</a>
											</div>
										</div>
									<?php }
								}
								if ($cnt == "active" && count($media) >= 6) {
									$act = "";
								}
							} ?>
						</div>
						<?php if (count($media) >= 6) { ?>
							<a class="left carousel-control" href="#carousel123" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
							<a class="right carousel-control" href="#carousel123" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
						<?php } ?>
					</div>
				</div>
			</div>
			<br>
			<div class="row text-center">
				<?php $showUpload = false;
				foreach ($participants as $participant) {
					if ($participant->UserID == $userID) {
						$showUpload = true;
						break;
					}
				}
				if ($showUpload) { ?>
					<button type="submit" class="btn btn-default" data-toggle="modal" data-target="#uploadModal">Upload New Media</button>
				<?php } ?>
			</div>
		</div>

		<!-- Modal Upload Media -->
		<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<form class="form-group" id="formUpload" method="post" action="<?php echo URL_WITH_INDEX_FILE; ?>events/upload" enctype="multipart/form-data" >
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h3 class="modal-title" id="myModalLabel"><img class="icon" src="<?php echo URL; ?>public/img/icon.png"> Upload Media</h3>
						</div>
	
						<div class="modal-body">
							<br>
							<input type="hidden" id="eventID" name="eventID" value="<?php echo $event->EventID ?>" />
							<label for="image" class="sr-only">Image</label>
							<input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
							<input type="file" id="image" name="image[]" accept="image/jpg,image/jpeg,image/png,image/bmp,video/mp4" multiple class="form-control" />
							<p class="help-block">Max file size: 2 MB. Accepted file types: .jpg, .jpeg, .png, .bmp</p>
							<br>
						</div>
	
						<div class="modal-footer">
							<!-- Buttons -->
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary">Upload</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<!-- Modal Show Media -->
		<div class="modal fade" id="showModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					</div>
					<div class="modal-body text-center">
						<img id="showImage" name="showImage" class="showImage" src="">
						<video width="100%" id="showVideo" autoplay style="display:none" controls>
							<source src="" type="video/mp4"></source>
							Your browser does not support HTML5 video.
						</video>
					</div>
					<div class="modal-footer">
						<div class="right">
							<button id="deleteMedia" style="display:none; right;" type="button" class="btn btn-primary" onclick="">Delete</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Comments -->	
		<div class="comments">
			<h3 class="page-header">Comments</h3>
			<table class="table table-striped">
				<tbody>
					<?php $parentID = "";
					foreach ($comments as $comment) { 
						if ($parentID != "" && $parentID != $comment->ParentID) { ?>
							<tr>
								<td>
									<a onclick="reply(this, <?php echo $parentID ?>)" style="cursor:pointer">reply</a>
								</td>
								<td colspan="3"></td>
							</tr>
						<?php }
						if ($comment->ParentID == $comment->CommentID) { ?>
							<tr>
								<td class="col-md-1"><?php echo $comment->FirstName ?></td>
								<td colspan="2"><?php echo $comment->Text ?></td>
								<td width="1%">
									<?php if ($comment->UserID == $userID) {?>
										<span class="glyphicon glyphicon-remove" aria-hidden="true" style="cursor:pointer" onclick="deleteComment(<?php echo $comment->CommentID ?>)"></span>
									<?php } ?>
								</td>
							</tr>
						<?php } else { ?>
							<tr>
								<td></td>	
								<td class="col-md-1"><?php echo $comment->FirstName ?></td>
								<td><?php echo $comment->Text ?></td>
								<td width="1%">
									<?php if ($comment->UserID == $userID) {?>
										<span class="glyphicon glyphicon-remove" aria-hidden="true" style="cursor:pointer" onclick="deleteComment(<?php echo $comment->CommentID ?>)"></span>
									<?php } ?>
								</td>
							</tr>
						<?php }
						$parentID = $comment->ParentID;
					}
					if ($parentID != "") { ?>
						<tr>
							<td>
								<a onclick="reply(this, <?php echo $parentID ?>)" style="cursor:pointer">reply</a>
							</td>
							<td colspan="3"></td>
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


<script>
	$(document).ready(function() {
		<?php if (strtotime($event->Time) > time()) {
			if ($userID == $event->HostID) { ?>
				$('#edit').click(function() {
					window.location.href = '<?php echo URL_WITH_INDEX_FILE . "events/edit/" . $event->EventID; ?>';
				});
	
				$('#delete').click(function() {
					if (confirm('Are you sure you want to delete this event?')) {
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
		<?php }
		} else if ($userID == $event->HostID) { ?>
			$('#recreate').click(function() {
				window.location.href = '<?php echo URL_WITH_INDEX_FILE . "events/recreate/" . $event->EventID; ?>';
			});
		<?php } ?>

		$('#form').validate({});

		reply = function(replyLink, parentID) {
			var td = $(replyLink).parent().next();

			if (td.children().length == 0) {
				var form = $('<form method="post" action="<?php echo URL_WITH_INDEX_FILE; ?>events/reply" class="form-horizontal"></form>');
				form.append('<input type="hidden" name="eventID" value="<?php echo $event->EventID ?>" />');
				form.append('<input type="hidden" name="parentID" value="' + parentID + '" />');
				form.append('<input type="text" name="text" required aria-required="true" />');
				form.append('<button type="submit" class="btn btn-default" style="margin-left:5px">Save</button>');
				form.append('<button type="button" class="btn btn-default" style="margin-left:5px" onclick="cancelReply(this)">Cancel</button>');

				td.append(form);
			}
		}

		cancelReply = function(cancelButton) {
			var td = $(cancelButton).closest('td');
			td.empty();
		}

		deleteComment = function(commentID) {
			if (confirm('Are you sure you want to delete this comment?')) {
				window.location.href = '<?php echo URL_WITH_INDEX_FILE . "events/deleteComment/" . $event->EventID . "/"; ?>' + commentID;
			}
		}

		deleteMedia = function(mediaID) {
			if (confirm('Are you sure you want to delete this media?')) {
				window.location.href = '<?php echo URL_WITH_INDEX_FILE . "events/deleteMedia/" . $event->EventID . "/"; ?>' + mediaID;
			}
		}

		$('.carousel-showmanymoveone .item').each(function() {
			var itemToClone = $(this);

			<?php if (count($media) >= 6) { ?>
				for (var i=1;i<6;i++) {
					itemToClone = itemToClone.next();
					// wrap around if at end of item collection
					if (!itemToClone.length) {
						itemToClone = $(this).siblings(':first');
					}

					// grab item, clone, add marker class, add to collection
					itemToClone.children(':first-child').clone().addClass("cloneditem-"+(i)).appendTo($(this));
				}
			<?php } ?>
		});

		$('#showModal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget) // Button that triggered the modal
			var imgSrc = button.data('whatever') // Extract info from data-* attributes
			var mediaId = button.data('id') // Extract info from data-* attributes
			var owner = button.data('owner') // Extract info from data-* attributes
			var type = button.data('type') // Extract info from data-* attributes
			var modal = $(this);
			var userId = <?php echo $userID; ?>;

			$("#showImage").attr('src',imgSrc);
			if (userId == owner) {
				$("#deleteMedia").attr('onclick',"deleteMedia("+mediaId+")"); 
				$("#deleteMedia").attr('style',"display:block"); 
			}
			if (type == "video") {
				$("#showImage").attr('style',"display:none");
				$("#showVideo").attr("src", imgSrc);
				$("#showVideo").attr('style',"display:block");
			}
			else {
				$("#showImage").attr('style',"display:block");
				$("#showVideo").attr('style',"display:none");
				$("#showVideo")[0].pause();
			}
		});
		$('#showModal').on('hide.bs.modal', function (event) {
			$("#showVideo")[0].pause();
		});
	});
</script>
