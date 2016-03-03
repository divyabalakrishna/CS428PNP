<?php if (!$this) { exit(header('HTTP/1.0 403 Forbidden')); } ?>
<br>
<br>
<br>
<p>CREATE EVENT PAGE</p>


<!-- Event Photo -->


<div class="container">
	<form class="form-horizontal">
	
	<!-- Event Name -->
	  <div class="form-group">
	    <label for="inputEventName" class="col-sm-2 control-label">Event Name</label>
	    <div class="col-sm-10">
	      <input type="eventName" class="form-control" id="eventName" placeholder="Event Name">
	    </div>
	  </div>
	
	<!-- Event Capacity -->  
	  <div class="form-group">
	    <label for="inputCapacity" class="col-sm-2 control-label">Event Capacity</label>
	    <div class="col-sm-10">
	      <input type="eventCapacity" class="form-control bfh-number" id="eventCapacity" placeholder="Number of people attending">
	    </div>
	  </div>
	
	<!-- Event Location --> 
	  <div class="form-group">
	    <label for="inputLocation" class="col-sm-2 control-label">Location</label>
	    <div class="col-sm-10">
	      <input type="location" class="form-control" id="location" placeholder="Location of the event">
	    </div>
	  </div>
	
	<!-- Event Time -->
	  <div class="form-group">
	    <label for="inputTime" class="col-sm-2 control-label">Event Time</label>
	    <div class="col-sm-10">
	      <input type="time" class="bfh-timepicker" id="time" placeholder="Time">
	    </div>
	  </div>
	
	
	<!-- Event Type -->
	<label for="inputLocation" class="col-sm-2 control-label">Event Type</label>
	<div class="dropdown">
	  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
	    Choose Event Type
	    <span class="caret"></span>
	  </button>
	  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
	    <li><a href="#">Board Games</a></li>
	    <li><a href="#">Card Games</a></li>
	    <li><a href="#">Chess</a></li>
	    <li><a href="#">Other Indoor Games</a></li>
	    <li role="separator" class="divider"></li>
	    <li><a href="#">American Football</a></li>
	    <li><a href="#">Athletics</a></li>
	    <li><a href="#">Badminton</a></li>
	    <li><a href="#">Baseball</a></li>
	    <li><a href="#">Basketball</a></li>
	    <li><a href="#">Bowling</a></li>
	    <li><a href="#">Boxing</a></li>
	    <li><a href="#">Cricket</a></li>
	    <li><a href="#">Cycling</a></li>
	    <li><a href="#">Dance</a></li>
	    <li><a href="#">Diving</a></li>
	    <li><a href="#">Dragon Boat Racing</a></li>
	    <li><a href="#">Endurance Sports</a></li>
	    <li><a href="#">Field Hockey</a></li>
	    <li><a href="#">Fishing</a></li>
	    <li><a href="#">Golf</a></li>
	    <li><a href="#">Gymnastics</a></li>
	    <li><a href="#">Ice Hockey</a></li>
	    <li><a href="#">Kayaking</a></li>
	    <li><a href="#">Kick Boxing</a></li>
	    <li><a href="#">Karate</a></li>
	    <li><a href="#">Lacrosse</a></li>
	    <li><a href="#">Polo</a></li>
	    <li><a href="#">Racing</a></li>
	    <li><a href="#">Rafting</a></li>
	    <li><a href="#">Rock Climbing</a></li>
	    <li><a href="#">Rowing</a></li>
	    <li><a href="#">Rugby League</a></li>
	    <li><a href="#">Rugby Union</a></li>
	    <li><a href="#">Skating Sports</a></li>
	    <li><a href="#">Skiing</a></li>
	    <li><a href="#">Sled Sports</a></li>
	    <li><a href="#">Soccer</a></li>
	    <li><a href="#">Squash</a></li>
	    <li><a href="#">Swimming</a></li>
	    <li><a href="#">Table Sports</a></li>
	    <li><a href="#">Table Tennis</a></li>
	    <li><a href="#">Team Handball</a></li>
	    <li><a href="#">Tennis</a></li>
	    <li><a href="#">Volleyball</a></li>
	    <li><a href="#">Waterpolo</a></li>
	    <li><a href="#">Weightlifting</a></li>
	    <li><a href="#">Wind Sports</a></li>
	    <li><a href="#">Other</a></li>
	  </ul>
	</div>
	  
	<br>
	  
	<!-- Event Description -->  
	<div class="form-group">
	    <label for="inputDescription" class="col-sm-2 control-label">Event Description</label>
	    <div class="col-sm-10">
	      <input type="description" class="form-control" id="description" placeholder="Add a description..">
	    </div>
	  </div>

	
	<!-- SAVE and CANCEL button -->
	 <br>
	  <div class="form-group">
	    <div class="col-sm-offset-2 col-sm-10">
	      <button type="submit" class="btn btn-default">Save</button>
	      <button type="button" value="Cancel" class="btn btn-default">Cancel</button>
	    </div>
	  </div>
	  
	
	</form>
</div>