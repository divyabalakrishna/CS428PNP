<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>

<div class="container">
	<?php if (!isset($userID)) { ?>
		<br><br><br><br>
	<?php } ?>
	<div class="alert alert-danger" role="alert">
		<b>ERROR:</b>
		The page you are trying to reach does not exist.
	</div>
</div>