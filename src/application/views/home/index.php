<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>

<div class="container">
	<p>You have successfully logged in! YAY!</p>
	
	<a href="events/listHosted">Events Created</a><br/>
	<a href="events/edit">Create Event</a><br/>
	<br/>
	<a href="events/listJoined">Events Joined</a><br/>
</div>