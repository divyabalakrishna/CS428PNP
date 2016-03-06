<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>

<div class="container" style="font-size: 120%;">
	<a style="color:#EA7617;" href="<?php echo URL_WITH_INDEX_FILE; ?>events/listHosted">Events Created</a><br/>
	<?php foreach ($hostedEvents as $event) { ?>
		<created class="created">
			<div class="icon">
			<img style="width: 100%;height: 100%;" src="<?php echo URL; ?>public/img/sports/<?php echo $event->TagName ?>.png">
			</div>
			<div class="content">
				<div style="font-size: 20px;"><?php echo $event->Name ?></div>
				<div style="font-size: 10px;font-style: italic;"><?php echo $event->FormattedDate ?></div>
				<div style="font-size: 10px;"><?php echo $event->FormattedTime ?></div>
			</div>
			<div class="details"><a href="<?php echo URL_WITH_INDEX_FILE; ?>events/view/<?php echo $event->EventID ?>"> View Details</a></div>
		</created>
	<?php } ?>
	<button class="create" onclick="self.location='<?php echo URL_WITH_INDEX_FILE; ?>events/edit'">+</button><br/>
	<br/>
</div>
<div class="container" style="font-size: 120%;">
	<a style="color: #E49721;" href="<?php echo URL_WITH_INDEX_FILE; ?>events/listJoined">Events Joined</a><br/>
	<?php foreach ($hostedEvents as $event) { ?>
		<joined class="joined">
			<div class="icon">
			<img style="width: 100%;height: 100%;" src="<?php echo URL; ?>public/img/sports/<?php echo $event->TagName ?>.png">
			</div>
			<div class="content">
				<div style="font-size: 20px;"><?php echo $event->Name ?></div>
				<div style="font-size: 10px;font-style: italic;"><?php echo $event->FormattedDate ?></div>
				<div style="font-size: 10px;"><?php echo $event->FormattedTime ?></div>
			</div>
			<div class="details">View Details</div>
		</joined>
	<?php } ?>
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
			</div>
			<div class="details">View Details</div>
		</otherEvents>
	</other>
</div>
<script>
    $(function() {

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(successFunction, errorFunction);
        }
        else {
            alert('not supported');
        }

        function errorFunction(){
            alert('something went wrong');
        }

        function successFunction(position) {
           var latitude = position.coords.latitude;
           var longitude = position.coords.longitude;

            document.cookie="latitude=" + latitude;            
            document.cookie="longitude=" + longitude;            
            
        }
    });
</script>
