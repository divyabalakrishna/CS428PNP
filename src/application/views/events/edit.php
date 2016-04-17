<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); }

if (is_numeric($event->EventID)) {
	$title = "Edit Event";
	$cancelURL = URL_WITH_INDEX_FILE . "events/view/" . $eventID;
}
else {
	$title = "Create Event";
	$cancelURL = URL_WITH_INDEX_FILE . "events/listHosted";
}
?>

<!-- JS -->
<script type="text/javascript" src='http://maps.google.com/maps/api/js?sensor=false&libraries=places'></script>
<script src="<?php echo URL; ?>public/js/locationpicker.jquery.js"></script>
<style>
	/* style overrides for bootstrap/google map conflicts */
	.gm-style img {max-width: none;}
	.gm-style label {width: auto; display:inline;} 
	.pac-container {z-index:2000 !important;}
</style>

<div class="container">
	<?php echo $GLOBALS["beans"]->siteHelper->getAlertsHTML(); ?>

	<h2 class="page-header"><?php echo $title; ?></h2>

	<form id="form" method="post" action="<?php echo URL_WITH_INDEX_FILE; ?>events/save" enctype="multipart/form-data" class="form-horizontal">
		<input type="hidden" id="eventID" name="eventID" value="<?php echo $event->EventID ?>" />

		<!-- Name -->
		<div class="form-group">
			<label for="name" class="col-sm-2 control-label">Name</label>
			<div class="col-sm-10">
				<input type="text" id="name" name="name" value="<?php echo $event->Name ?>" class="form-control" required aria-required="true" placeholder="Event Name">
			</div>
		</div>

		<!-- Description -->
		<div class="form-group">
			<label for="description" class="col-sm-2 control-label">Description</label>
			<div class="col-sm-10">
				<textarea id="description" name="description" class="form-control" required aria-required="true" placeholder="Event Description"><?php echo $event->Description ?></textarea>
			</div>
		</div>

		<!-- Date -->
		<div class="form-group">
			<label for="date" class="col-sm-2 control-label">Date</label>
			<div class="col-sm-10">
				<div class="input-group date col-sm-2">
					<input type="text" id="date" name="date" value="<?php echo $event->FormattedDate ?>" class="form-control" required aria-required="true" />
					<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
				</div>
			</div>
		</div>

		<!-- Time -->
		<div class="form-group">
			<label for="time" class="col-sm-2 control-label">Time</label>
			<div class="col-sm-10">
				<div class="input-group bootstrap-timepicker timepicker col-sm-2">
					<input type="text" id="time" name="time" value="<?php echo $event->FormattedTime ?>" class="form-control" required aria-required="true" />
					<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
				</div>
			</div>
		</div>

		<!-- Location --> 
		<div class="form-group">
			<label for="address" class="col-sm-2 control-label">Location</label>
			<div class="col-sm-10">
				<div class="input-group col-sm-12">
					<input type="text" id="address" name="address" value="<?php echo $event->Address ?>" class="form-control" required aria-required="true" placeholder="Event Location">
					<span class="input-group-addon"><a id="location" href="" data-target="#gmap-dialog" data-toggle="modal"><i class="glyphicon glyphicon-map-marker"></i></a></span>
				</div>
			</div>
		</div>

		<div id="gmap-dialog" class="modal fade">
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
								<div class="col-sm-10"><input type="text" class="form-control" id="gmap-address" value="<?php echo $event->Address ?>"/></div>
							</div>
							<div id="gmap" style="width: 100%; height: 400px;"></div>
							<div class="clearfix">&nbsp;</div>
							<div class="m-t-small">
								<label class="p-r-small col-sm-1 control-label">Lat.:</label>
								<div class="col-sm-3"><input type="text" class="form-control" style="width: 110px" id="gmap-lat" name="gmap-lat"/></div>
								<label class="p-r-small col-sm-2 control-label">Long.:</label>
								<div class="col-sm-3"><input type="text" class="form-control" style="width: 110px" id="gmap-lon" name="gmap-lon"/></div>
							</div>
							<div class="clearfix"></div>
                            <script>
                                var lat,long;
                                function getLocation() {
                                    if (navigator.geolocation) {
                                        navigator.geolocation.getCurrentPosition(getPosition, noPosition);
                                    } else { 
                                        alert("Geolocation is not supported by this browser.");
                                    }
                                }
                                function noPosition(){
                                    setMap(40.1138767,-88.2242376);
                                }
                                function getPosition(position) {
                                    setMap(position.coords.latitude,position.coords.longitude);
                                }

                                function setMap(lat,lon){
                                    $('#gmap-lat').val(lat);
                                    $('#gmap-lon').val(lon);
                                    $('#gmap').locationpicker({
                                        <?php if (is_numeric($event->Lat) && is_numeric($event->Lon)) { ?>
                                        location: {latitude: <?php echo $event->Lat ?>, longitude: <?php echo $event->Lon ?>},
                                        <?php } else { ?>
                                        location: {latitude: lat, longitude: lon},
                                        <?php } ?>
                                        radius: 0,
                                        inputBinding: {
                                            latitudeInput: $('#gmap-lat'),
                                            longitudeInput: $('#gmap-lon'),
                                            locationNameInput: $('#gmap-address')
                                        },
                                        enableAutocomplete: true
                                    });
                                }
                                
                                getLocation();
                                $('#gmap-dialog').on('shown.bs.modal', function() {
                                    $('#gmap').locationpicker('autosize');
                                });
                                $(function(){
                                    $(document).on("click", "#save-event", function(event){
                                        $('#address').val($('#gmap-address').val());        
                                    }); 
                                });                        
                                $(function(){
                                    $(document).on("focus", "#address", function(event){
                                        $("#gmap-dialog").modal('show');
                                    }); 
                                });     
                                $(function(){
                                    $(document).on("keydown", "#address", function(event){
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

		<!-- Capacity -->  
		<div class="form-group">
			<label for="capacity" class="col-sm-2 control-label">Capacity</label>
			<div class="col-sm-10">
				<input type="number" id="capacity" name="capacity" value="<?php echo $event->Capacity ?>" min="1" step="1" class="form-control" placeholder="Event Capacity">
			</div>
		</div>

		<!-- Type -->
		<div class="form-group">
			<label for="tagID" class="col-sm-2 control-label">Type</label>
			<div class="col-sm-10">
				<select id="tagID" name="tagID" class="form-control" required aria-required="true">
					<option value="">- Event Type -</option>
					<?php foreach ($tags as $tag) { ?>
						<option value="<?php echo $tag->TagID; ?>" <?php if ($event->TagID == $tag->TagID) { ?>selected<?php } ?>><?php echo $tag->Name; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>

		<!-- Image -->
		<div class="form-group">
			<label for="image" class="col-sm-2 control-label">Image</label>
			<div class="col-sm-10">
				<input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
				<input type="file" id="image" name="image" accept="image/jpg,image/jpeg,image/png,image/bmp" class="form-control" />
				<p class="help-block">Max file size: 2 MB. Accepted file types: .jpg, .jpeg, .png, .bmp</p>
				<?php if ($event->Image != "") { ?>
					<img src="<?php echo $GLOBALS["beans"]->fileHelper->getUploadedFileURL('event', $event->Image) ?>" height="100" />
				<?php } ?>
			</div>
		</div>

		<!-- Buttons -->
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-default">Save</button>
				<button type="button" id="cancel" class="btn btn-default">Cancel</button>
			</div>
		</div>
	</form>
</div>

<script>
	$(document).ready(function(){
		$('#cancel').click(function(){
			window.location.href = '<?php echo $cancelURL; ?>';
		});

		$('.input-group.date').datepicker({
			todayBtn: 'linked',
			clearBtn: true
		});

		$('.input-group.timepicker').find('input[type="text"]').each(function() {
			$(this).timepicker({
				defaultTime: false
			});
		});

		$.validator.addMethod("todayOrFutureDate", function(value, element) {
			if (this.optional(element)) {
				return true;
			}

			if (!/Invalid|NaN/.test(new Date(value))) {
				var today = new Date();
				today.setHours(0, 0, 0, 0);

				return new Date(value) >= today;
			}

			return false;
		}, "Please enter a future date.");

		$.validator.addMethod("futureTime", function(value, element) {
			/* If date is invalid, there is no need to check the time */
			var dateString = $('#date').val();
			var today = new Date();
			today.setHours(0, 0, 0, 0);
			if (/Invalid|NaN/.test(new Date(dateString)) || (new Date(dateString) < today)) {
				return true;
			}

			var dateTimeString = dateString + ' ' + value;
			if (!/Invalid|NaN/.test(new Date(dateTimeString))) {
				return new Date(dateTimeString) > new Date();
			}

			return false;
		}, "Please enter a future time.");

		$('#form').validate({
			rules: {
				date: {
					date: true,
					todayOrFutureDate: true
				},
				time: {
					time12h: true,
					futureTime: true
				}
			}
		});
//		$('#location').click(function(){
//			alert('loca');
//		});
	});
</script>
