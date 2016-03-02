<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>
<p>You have successfully logged in! YAY!</p>

<a href="<?php echo URL_WITH_INDEX_FILE ?>event/create_event">Create Event</a>
<br>

<a href="<?php echo URL_WITH_INDEX_FILE ?>user/logout">Logout</a>

