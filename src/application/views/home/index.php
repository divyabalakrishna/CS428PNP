<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>
<div class="splitter">
	<?php echo $GLOBALS["beans"]->siteHelper->getAlertsHTML(); ?>
	<div class="leftpaneExpanded" id="leftpane">
    	<div class="content" id="content">
		    <button class="buttons" id="hosted">Hosted</button>
		    <button class="buttons" id="past">Past</button>
		    <button class="buttons" id="joined">Joined</button>
		    <button class="buttons" id="feed">Feed</button>
		    <button class="buttons" id="create">create</button>
		    <div id="tagsList" class="tagList"></div>
	    </div>
    </div>
    <div class="rightpane rightpaneExpanded" id="rightpane">
    	
    </div>
</div>
<script>
	var that = this;
	function tileClick(){
		var eventID = $(this).children()[0].value;
		self.location='<?php echo URL_WITH_INDEX_FILE; ?>events/view/' + eventID;
	}
	function tagFilter(){
		var tag = $(this)[0].text.substring(1);
		load(tag);
	}
	function loadTags(data){
		$('#tagsList').empty();
		var tagsList = [];
		for(var i = 0; i < data.length; i++){
			var tag = data[i].TagName;
			var present = 0;
			for(j = 0; j < tagsList.length; j++){
				if(tag === tagsList[j]){
					present = 1;
				}
			}
			if(!present){
				tagsList[tagsList.length] = data[i].TagName;
				var tag = '<a class="tag">#' + data[i].TagName + '</a><br/>';
				$('#tagsList').append(tag);
				$('.tag').click(tagFilter);
			}
		}
	}
	function load(filter=""){
		$('#rightpane').empty();
		
		for(i = 0; i < that.data.length; i++){
			if(that.data[i].TagName != filter){
				var tile =  '<div class = "tiles">' +
			   		'<input type="hidden" id="eventIDTile" name="eventID" value="'+ that.data[i].EventID+'" />'+
				    '<div class="icon">'+
				    	'<img style="width: 100%;height: 100%;" src="<?php echo URL; ?>public/img/sports/' + that.data[i].TagName + '.png">'+
					'</div>'+
					'<div class="content">'+
						'<div class="title">'+ that.data[i].Name +'</div>';
				if(that.data[i].Description != null){
					tile = tile + '<div class="desc">'+ that.data[i].Description + '</div>';
				}
				tile = tile + '<div class="desc">'+ that.data[i].Address + '</div>'+
						'<div class="desc">'+ that.data[i].FormattedDate + '</div>'+
						'<div class="desc">'+ that.data[i].FormattedTime +'</div>'+
					'</div>'+
			    '</div>';
			    
			    $('#rightpane').append(tile);
			    $('.tiles').click(tileClick);
			}	
		}
		loadTags(data);
	}
	function getFeed(){
		<?php $Events = $joinableEvents;
		$js_array = json_encode($Events);
		echo "that.data = ". $js_array . ";\n";
		?>
	    load();
	}
    $(function() {
    	$(document).ready(function(){
    		getFeed();
    		$('.tiles').click(tileClick);
    		$('#create').click(function(){
    			self.location='<?php echo URL_WITH_INDEX_FILE; ?>events/edit';
        	});
    		$('#feed').click(getFeed);
    		$('#joined').click(function(){
    			<?php $Events = $joinedEvents;
    			$js_array = json_encode($Events);
    			echo "that.data = ". $js_array . ";\n";
    			?>
			    load();
    		});
    		$('#past').click(function(){
    			<?php $Events = $pastEvents;
    			$js_array = json_encode($Events);
    			echo "that.data = ". $js_array . ";\n";
    			?>
			    load();
    		});
    		$('#hosted').click(function(){
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
