<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>
<div class="splitter">
	<?php echo $GLOBALS["beans"]->siteHelper->getAlertsHTML(); ?>
	<?php $Events = $hostedEvents ?>
    <div class="leftpaneCollapsed" id="leftpane">
	    <button class="buttons" id="hosted">Hosted</button>
	    <button class="buttons" id="past">Past</button>
	    <button class="buttons" id="joined">Joined</button>
	    <button class="buttons" id="feed">Feed</button>
	    <button class="buttons" id="create">create</button>
    </div>
    <div class="rightpane rightpaneExpanded" id="rightpane">
    	<?php foreach ($Events as $event) { ?>
		    <div class = "tiles">
		    	<input type="hidden" id="eventIDTile" name="eventID" value="<?php echo $event->EventID ?>" />
			    <div class="icon">
			    	<img style="width: 100%;height: 100%;" src="<?php echo URL; ?>public/img/sports/<?php echo $event->TagName ?>.png">
				</div>
				<div class="content">
					<div class="title"><?php echo $event->Name ?></div>
					<div class="desc"><?php echo $event->Description ?></div>
					<div class="desc"><?php echo $event->Address ?></div>
					<div class="desc"><?php echo $event->FormattedDate ?></div>
					<div class="desc"><?php echo $event->FormattedTime ?></div>
				</div>
		    </div>
	    <?php } ?>
    </div>
</div>
<script>
	$('#menuHome').show();
	function tileClick(){
		var eventID = $(this).children()[0].value;
		self.location='<?php echo URL_WITH_INDEX_FILE; ?>events/view/' + eventID;
	}
    $(function() {
    	$(document).ready(function(){
    		$('#menuHome').click(function(){
				if(this.toggle){
            		this.toggle = 0;
            		$('#rightpane').css('width','100%');
            		$('#leftpane').removeClass('leftpaneExpanded');
        			$('#leftpane').addClass('leftpaneCollapsed');
				}
				else{
					this.toggle = 1;
					$('#rightpane').css('width','85%');
					$('#leftpane').removeClass('leftpaneCollapsed');
        			$('#leftpane').addClass('leftpaneExpanded');
				}
    		});
    		$('.tiles').click(tileClick);
    		$('#create').click(function(){
    			self.location='<?php echo URL_WITH_INDEX_FILE; ?>events/edit';
        	});
    		$('#feed').click(function(){
    			
    		});
    		$('#joined').click(function(){
    			<?php $Events = $joinedEvents ?>
    			$('#rightpane').empty();
    			<?php foreach ($Events as $event) { ?>
				   var tile =  '<div class = "tiles">' +
				   		'<input type="hidden" id="eventIDTile" name="eventID" value="<?php echo $event->EventID ?>" />'+
					    '<div class="icon">'+
					    	'<img style="width: 100%;height: 100%;" src="<?php echo URL; ?>public/img/sports/<?php echo $event->TagName ?>.png">'+
						'</div>'+
						'<div class="content">'+
							'<div class="title"><?php echo $event->Name ?></div>'+
							'<div class="desc"><?php echo $event->Description ?></div>'+
							'<div class="desc"><?php echo $event->Address ?></div>'+
							'<div class="desc"><?php echo $event->FormattedDate ?></div>'+
							'<div class="desc"><?php echo $event->FormattedTime ?></div>'+
						'</div>'+
				    '</div>';
				    
				    $('#rightpane').append(tile);
				    $('.tiles').click(tileClick);
			    <?php } ?>
    		});
    		$('#past').click(function(){
    			<?php $Events = $pastEvents ?>
    			$('#rightpane').empty();
    			debugger;
    			<?php foreach ($Events as $event) { ?>
				   var tile =  '<div class = "tiles">' +
				   		'<input type="hidden" id="eventIDTile" name="eventID" value="<?php echo $event->EventID ?>" />'+
					    '<div class="icon">'+
					    	'<img style="width: 100%;height: 100%;" src="<?php echo URL; ?>public/img/sports/<?php echo $event->TagName ?>.png">'+
						'</div>'+
						'<div class="content">'+
							'<div class="title"><?php echo $event->Name ?></div>'+
							'<div class="desc"><?php echo $event->Description ?></div>'+
							'<div class="desc"><?php echo $event->Address ?></div>'+
							'<div class="desc"><?php echo $event->FormattedDate ?></div>'+
							'<div class="desc"><?php echo $event->FormattedTime ?></div>'+
						'</div>'+
				    '</div>';
				    
				    $('#rightpane').append(tile);
				    $('.tiles').click(tileClick);
			    <?php } ?>
    		});
    		$('#hosted').click(function(){
    			<?php $Events = $hostedEvents ?>
    			$('#rightpane').empty();
    			<?php foreach ($Events as $event) { ?>
				   var tile =  '<div class = "tiles">' +
				   		'<input type="hidden" id="eventIDTile" name="eventID" value="<?php echo $event->EventID ?>" />'+
					    '<div class="icon">'+
					    	'<img style="width: 100%;height: 100%;" src="<?php echo URL; ?>public/img/sports/<?php echo $event->TagName ?>.png">'+
						'</div>'+
						'<div class="content">'+
							'<div class="title"><?php echo $event->Name ?></div>'+
							'<div class="desc"><?php echo $event->Description ?></div>'+
							'<div class="desc"><?php echo $event->Address ?></div>'+
							'<div class="desc"><?php echo $event->FormattedDate ?></div>'+
							'<div class="desc"><?php echo $event->FormattedTime ?></div>'+
						'</div>'+
				    '</div>';
				    
				    $('#rightpane').append(tile);
				    $('.tiles').click(tileClick);
			    <?php } ?>
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
