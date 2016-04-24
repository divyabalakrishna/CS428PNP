<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>

<!-- JS -->
<script type="text/javascript" src='http://maps.google.com/maps/api/js?sensor=false&libraries=places'></script>
<script src="<?php echo URL; ?>public/js/locationpicker.jquery.js"></script>

<style>
	/* style overrides for bootstrap/google map conflicts */
	.gm-style img {max-width: none;}
	.gm-style label {width: auto; display:inline;} 
	.pac-container {z-index:2000 !important;}
	.col-sm-10 {float: right;}

	gmap {
		width:100%;
		height:100%;
		margin:0;
		padding:0;
		position:absolute;
	}
</style>

<div class="container">
	<h2 class="page-header">Search Events</h2>

	<form action="<?php echo URL_WITH_INDEX_FILE; ?>events/ListSearch" method="post">
		<input type="submit" class="btn btn-default" name="search" value="Go" style="float: right" />
		<div style="overflow:hidden; padding-right:0.5em;">
			<div class="form-group">
				<div class="col-sm-10">
					<div class="input-group col-sm-12">
						<input type="text" id="address" name="address" class="form-control" placeholder="Search A Location">
						<span class="input-group-addon"><a id="location" href="" data-target="#gmap-dialog2" data-toggle="modal"><i class="glyphicon glyphicon-map-marker"></i></a></span>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-10">
					<div id="check">
						<input type="checkbox" name="tag">Filter by Tags
						<input type="checkbox" name="old">Show Past Events
					</div>
				</div>
			</div>
		</div>

		<div id="gmap-dialog2" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Choose Location</h4>
					</div>
					<div class="modal-body">
						<div class="form-horizontal" style="width: 100%">
							<div class="form-group">
								<label class="col-sm-2 control-label">Location:</label>
								<div class="col-sm-10"><input type="text" class="form-control" id="gmap-address"/></div>
							</div>
							<div id="gmap2" style="width: 100%; height: 400px;"></div>
							<div class="clearfix">&nbsp;</div>
							<div class="m-t-small">
								<label class="p-r-small col-sm-1 control-label">Lat.:</label>
								<div class="col-sm-3"><input type="text" class="form-control" style="width: 110px" id="gmap-lat2" name="gmap-lat2"/></div>
								<label class="p-r-small col-sm-2 control-label">Long.:</label>
								<div class="col-sm-3"><input type="text" class="form-control" style="width: 110px" id="gmap-lon2" name="gmap-lon2"/></div>
							</div>
							<div class="clearfix"></div>
							<script>
								var lat,long;

								function getLocation() {
									if (navigator.geolocation) {
										navigator.geolocation.getCurrentPosition(getPosition, noPosition);
									}
									else {
										alert("Geolocation is not supported by this browser.");
									}
								}

								function noPosition() {
									setMap(40.1138767,-88.2242376);
								}

								function getPosition(position) {
									setMap(position.coords.latitude,position.coords.longitude);
								}

								function setMap(lat, lon) {
									$('#gmap-lat2').val(lat);
									$('#gmap-lon2').val(lon);
									$('#gmap2').locationpicker({
										location: {latitude: lat, longitude: lon},
										radius: 0,
										inputBinding: {
											latitudeInput: $('#gmap-lat2'),
											longitudeInput: $('#gmap-lon2'),
											locationNameInput: $('#gmap-address')
										},
										enableAutocomplete: true
									});
								}

								getLocation();

								$('#gmap-dialog2').on('shown.bs.modal', function() {
									$('#gmap2').locationpicker('autosize');
								});

								$(function() {
									$(document).on("click", "#save-event", function(event) {
										$('#address').val($('#gmap-address').val());
									});
								});

								$(function() {
									$(document).on("focus", "#address", function(event) {
										$("#gmap-dialog2").modal('show');
									});
								});

								$(function() {
									$(document).on("keydown", "#address", function(event) {
										event.preventDefault();
									});
								});
							</script>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" data-dismiss="modal" id="save-event">Select</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
	</form>

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
						<td class='text-center'>
							<a href="<?php echo URL_WITH_INDEX_FILE . "events/view/" . $event->EventID; ?>">
								<div class="image-frame"> 
									<div class="image-thumb" style="background-image: url('<?php echo URL; ?>public/img/sports/<?php echo $event->TagName ?>.png');"></div>
								</div><br>
								<?php echo $event->Name ?>
							</a>
						</td>
						<td class="truncate"><?php echo $event->Description ?></td>
						<td><?php echo $event->FormattedDateTime ?></td>
						<td><?php echo $event->Address ?></td>
						<td><?php echo $event->TagName ?></td>
						<td><?php echo floor($event->Distance * 1000) / 1000 ?> miles</td>
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
					<div id="gmap-error" class='alert alert-danger text-center hidden' role='alert'>No internet connection !!!</div>
					<div id="gmap" style="width: 100%; height: 500px;"></div>
					<div class="clearfix"></div>
					<script>
						var lat = 40.11374573,lon=-88.224828;
						function getLocation() {
							if (navigator.geolocation) {
								navigator.geolocation.getCurrentPosition(getPosition,errorFunction);
							}
							else { 
								alert("Geolocation is not supported by this browser.");
							}
						}

						function getPosition(position) {
							$('#gmap-lat').val(position.coords.latitude); 
							$('#gmap-lon').val(position.coords.longitude);

							lat = position.coords.latitude;
							lon = position.coords.longitude;
						}

						function errorFunction() {
							$('#gmap-error').removeClass("hidden");
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
	$(document).ready(function() {
		$('#clear').click(function() {
			window.location.href = '<?php echo URL_WITH_INDEX_FILE; ?>events/listJoined';
		});
	});
</script>