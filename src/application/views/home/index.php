<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>

<div class="container">
	<p>You have successfully logged in! YAY!</p>
	
	<a href="<?php echo URL_WITH_INDEX_FILE; ?>events/listHosted">Events Created</a><br/>
	<a href="<?php echo URL_WITH_INDEX_FILE; ?>events/edit">Create Event</a><br/>
	<br/>
	<a href="<?php echo URL_WITH_INDEX_FILE; ?>events/listJoined">Events Joined</a><br/>
</div>