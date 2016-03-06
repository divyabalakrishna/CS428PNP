<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>
<!-- JS -->
<script type="text/javascript" src='http://maps.google.com/maps/api/js?sensor=false&libraries=places'></script>
<script src="<?php echo URL; ?>public/js/locationpicker.jquery.js"></script>

<style>
    /* style overrides for bootstrap/google map conflicts */
    .gm-style img {max-width: none;}
    .gm-style label {width: auto; display:inline;} 
    .pac-container {z-index:2000 !important;}
    
gmap{
    width:100%;
    height:100%;
    margin:0;
    padding:0;
    position:absolute;
}
</style>

<div class="container">
	<h2 class="page-header">Search Events</h2>

    <button type="button" class="btn btn-primary" id="show-map" data-target="#gmap-dialog" data-toggle="modal">Show Map</button>

	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<th>Date/Time</th>
					<th>Location</th>
					<th>Type</th>
					<th>Distance</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($events as $event) { ?>
					<tr>
						<td>
							<a href="<?php echo URL_WITH_INDEX_FILE . "events/view/" . $event->EventID; ?>">
								<?php echo $event->Name ?>
							</a>
						</td>
						<td class="truncate"><?php echo $event->Description ?></td>
						<td><?php echo $event->FormattedDateTime ?></td>
						<td><?php echo $event->Address ?></td>
						<td><?php echo $event->TagName ?></td>
						<td><?php echo floor($event->distance * 1000) / 1000 ?> miles</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<div id="gmap-dialog" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Event Search Map</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal" style="width: 100%">
                    <div id="gmap" style="width: 100%; height: 500px;"></div>
                    <div class="clearfix"></div>
                    <script>
                        var lat = 40.11374573,lon=-88.224828;
                        function getLocation() {
                            if (navigator.geolocation) {
                                navigator.geolocation.getCurrentPosition(getPosition,errorFunction);
                            } else { 
                                alert("Geolocation is not supported by this browser.");
                            }
                        }
                        function getPosition(position) {
                            $('#gmap-lat').val(position.coords.latitude); 
                            $('#gmap-lon').val(position.coords.longitude);
                            
                            lat= position.coords.latitude;
                            lon= position.coords.longitude;
                        }
                        function errorFunction() {
                            alert("enable your location !!!");
                        }

                        getLocation();
                        $('#gmap-dialog').on('shown.bs.modal', function() {
                            $('#gmap').locationpicker({
                                location: {latitude: lat, longitude: lon},
                                radius: 0,
                                icon: '<?php echo URL; ?>public/img/user.png',
                                enableAutocomplete: true,
                                draggableIcon: false,
                                titleIcon: "You are here."
                            });
                            $('#gmap').locationpicker('load');
                            $('#gmap').locationpicker('autosize');
                        });
                    </script>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




<script>
	$(document).ready(function(){
		$('#clear').click(function(){
			window.location.href = '<?php echo URL_WITH_INDEX_FILE; ?>events/listJoined';
		});
	});
</script>