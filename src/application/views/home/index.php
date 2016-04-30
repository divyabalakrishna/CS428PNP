<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>
<div class="splitter">
	<?php echo $GLOBALS["beans"]->siteHelper->getAlertsHTML(); ?>
	<div class="leftpaneExpanded" id="leftpane">
		<div class="content" id="content">
			<button class="buttons" id="hosted">Hosted</button>
			<button class="buttons" id="past">Past</button>
			<button class="buttons" id="joined">Joined</button>
			<button class="buttons" id="feed">Feed</button>
			<button class="buttons" id="create">Create</button>
			<div id="tagsList" class="tagList"></div>
		</div>
	</div>
	<div class="rightpane rightpaneExpanded" id="rightpane">
		<div id="rightpaneTitle" class="rightpaneTitle">Other Events</div>
		<div id="rightpaneContent"></div>
	</div>
</div>

<script>
	var that = this;

	/*
	* event listener for tile click on home screen
	*/
	function tileClick() {
		var eventID = $(this).children()[0].value;
		self.location='<?php echo URL_WITH_INDEX_FILE; ?>events/view/' + eventID;
	}

	/*
	* event listener for tag click
	*/
	function tagFilter(event) {
		var tag = $(this)[0].text.substring(1);
		if (that.selected) {
			$("#"+that.selected).removeClass('tagSelected');
			$("#"+that.selected).addClass('tag');
		}
		that.selected = event.target.id;
		$("#"+that.selected).removeClass('tag');
		$("#"+that.selected).addClass('tagSelected');
		load(tag);
	}

	/*
	* get all the tags in the right pane data and create them on left panel of home screen
	* params list of data to be loaded 
	*/
	function loadTags(data) {
		$('#tagsList').empty();
		var tagsList = [];
		for (var i = 0; i < data.length; i++) {
			var tag = data[i].TagName;
			var present = 0;
			for (j = 0; j < tagsList.length; j++) {
				if (tag === tagsList[j]) {
					present = 1;
				}
			}
			if (!present) {
				tagsList[tagsList.length] = data[i].TagName;
				var tag = '<a class="tag" ' + 'id="tag' + i +'">#' + data[i].TagName + '</a><br/>';
				$('#tagsList').append(tag);
				$('.tag').click(tagFilter);
			}
		}
	}

	/*
	* load tiles representing the data
	* params list of data to be loaded 
	*/
	function loadData(data) {
		var tile = '<div class = "tiles">' +
	 		'<input type="hidden" id="eventIDTile" name="eventID" value="'+ data.EventID+'" />'+
			'<div class="icon">'+
				'<img style="width:100%; height:100%;" src="<?php echo URL; ?>public/img/sports/' + data.TagName + '.png">'+
			'</div>'+
			'<div class="content">'+
				'<div class="title">'+ data.Name +'</div>';
		if (data.Description != null) {
			tile = tile + '<div class="desc">'+ data.Description + '</div>';
		}
		tile = tile + '<div class="address">'+ data.Address + '</div>'+
				'<div class="timedate">'+ data.FormattedDate + '</div>'+
				'<div class="timedate">'+ data.FormattedTime +'</div>'+
			'</div>'+
		'</div>';

		$('#rightpaneContent').append(tile);
		$('.tiles').click(tileClick);
	}

	/*
	* load tiles representing the data
	*/
	function load(filter = "") {
		$('#rightpaneContent').empty();
		if(that.data.length == 0){
			$('#tagsList').empty();
		}
		for (i = 0; i < that.data.length; i++) {
			if (filter == "") {
				loadData(that.data[i]);
				loadTags(data);
			}
			else {
				if (that.data[i].TagName == filter) {
					loadData(that.data[i]);
				}
			}
		}
		
	}

	/*
	* event handler for click of feed button on home screen
	*/
	function getFeed() {
		$('#rightpaneTitle').text("Other Events");
		<?php $Events = $joinableEvents;
		$js_array = json_encode($Events);
		echo "that.data = ". $js_array . ";\n"; ?>
		load();
	}

	$(function() {
		$(document).ready(function() {
			getFeed();
			$('.tiles').click(tileClick);

			//navigate to create screen on click of create
			$('#create').click(function() {
				self.location='<?php echo URL_WITH_INDEX_FILE; ?>events/edit';
			});

			//register event handler for feed button
			$('#feed').click(getFeed);

			//event handler for click of Joined button
			$('#joined').click(function() {
				$('#rightpaneTitle').text("Joined Events");
				<?php $Events = $joinedEvents;
				$js_array = json_encode($Events);
				echo "that.data = ". $js_array . ";\n";
				?>
				load();
			});

			//event handler for click of Past button
			$('#past').click(function() {
				$('#rightpaneTitle').text("Past Events");
				<?php $Events = $pastEvents;
				$js_array = json_encode($Events);
				echo "that.data = ". $js_array . ";\n";
				?>
				load();
			});

			//event handler for click of Hosted button
			$('#hosted').click(function() {
				$('#rightpaneTitle').text("Hosted Events");
				<?php $Events = $hostedEvents;
				$js_array = json_encode($Events);
				echo "that.data = ". $js_array . ";\n";
				?>
				load();
			});
		});

		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(successFunction, errorFunction);
		}
		else {
			alert('not supported');
		}

		function errorFunction() {
			alert('something went wrong');
		}

		function successFunction(position) {
			var latitude = position.coords.latitude;
			var longitude = position.coords.longitude;

			document.cookie = "latitude=" + latitude;
			document.cookie = "longitude=" + longitude;
		}
	});
</script>