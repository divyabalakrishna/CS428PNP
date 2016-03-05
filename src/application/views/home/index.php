<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>

<div class="container" style="font-size: 120%;">
	<a style="color:#EA7617;" href="<?php echo URL_WITH_INDEX_FILE; ?>events/listHosted">Events Created</a><br/>
	<create>
		<created class="created">
			<div class="icon">
			<img style="width: 100%;height: 100%;" src="../public/img/tennis.png">
			</div>
			<div class="content">
				<div style="font-size: 20px;">Tennis</div>
				<div style="font-size: 10px;font-style: italic;">Sunday</div>
				<div style="font-size: 10px;">3:00 pm</div>
				<div>ARC</div>
			</div>
			<div class="details">View Details</div>
		</created>
	</create>
	<button class="create" onclick="self.location='<?php echo URL_WITH_INDEX_FILE; ?>events/edit'">+</button><br/>
	<br/>
</div>
<div class="container" style="font-size: 120%;">
	<a style="color: #E49721;" href="<?php echo URL_WITH_INDEX_FILE; ?>events/listJoined">Events Joined</a><br/>
	<join>
		<joined class="joined">
			<div class="icon">
			<img style="width: 100%;height: 100%;" src="../public/img/tennis.png">
			</div>
			<div class="content">
				<div style="font-size: 20px;">Tennis</div>
				<div style="font-size: 10px;font-style: italic;">Sunday</div>
				<div style="font-size: 10px;">3:00 pm</div>
				<div>ARC</div>
			</div>
			<div class="details">View Details</div>
		</joined>
	</join>
</div>
<br/>
<div class="container" style="font-size: 120%;">
	<a style="color: #E4B40F">Other Events</a><br/>
	<other>
		<otherEvents class="other">
			<div class="icon">
			<img style="width: 100%;height: 100%;" src="../public/img/tennis.png">
			</div>
			<div class="content">
				<div style="font-size: 20px;">Tennis</div>
				<div style="font-size: 10px;font-style: italic;">Sunday</div>
				<div style="font-size: 10px;">3:00 pm</div>
				<div>ARC</div>
			</div>
			<div class="details">View Details</div>
		</otherEvents>
	</other>
</div>
<button onclick="create()">add</button><br/>