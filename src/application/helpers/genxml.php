<?php
define('ROOT', __DIR__ . DIRECTORY_SEPARATOR);
require("../config/config.php");

// Start XML file, create parent node

$dom = new DOMDocument("1.0");
$node = $dom->createElement("markers");
$parnode = $dom->appendChild($node);

// Opens a connection to a MySQL server

//$connection=mysql_connect (DB_HOST, DB_USER, DB_PASS);
$connection = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);

if (!$connection) {  die('Not connected : ' . mysql_error());}

// Select all the rows in the markers table
$Address = urlencode("911 W.Springfield Ave, Urbana, IL");
$request_url = "http://maps.googleapis.com/maps/api/geocode/xml?address=".$Address."&sensor=true";
$xml = simplexml_load_file($request_url) or die("url not loading");
$status = $xml->status;
if ($status=="OK") {
  $Lat = $xml->result->geometry->location->lat;
  $Lon = $xml->result->geometry->location->lng;
}

$sql = "SELECT Event.*,DATE_FORMAT(Event.Time, '%m/%d/%Y %h:%i %p') AS FormattedTime, 
                Tag.Name AS TagName,
                Tag.Icon AS TagIcon,
        ( 3959 * acos( cos( radians(".$Lat.") ) * cos( radians( lat ) ) 
        * cos( radians( Lon ) - radians(".$Lon.") ) + sin( radians(".$Lat.") ) * sin(radians(lat)) ) ) AS distance 
        FROM event 
        LEFT JOIN Tag ON Tag.TagID = Event.TagID                
        HAVING distance < 2
        ORDER BY distance";

//$sql = "SELECT * FROM event WHERE 1";
$query = $connection->prepare($sql);
$query->execute();


header("Content-type: text/xml");

// Iterate through the rows, adding XML nodes for each

$rows = $query->fetchAll();
foreach ($rows as $row) { 
    
  // ADD TO XML DOCUMENT NODE
  $node = $dom->createElement("marker");
  $newnode = $parnode->appendChild($node);
  $newnode->setAttribute("id",$row['EventID']);
  $newnode->setAttribute("name",$row['Name']);
  $newnode->setAttribute("address", $row['Address']);
  $newnode->setAttribute("lat", $row['Lat']);
  $newnode->setAttribute("lon", $row['Lon']);
  $newnode->setAttribute("tag", $row['TagName']);
}

echo $dom->saveXML();

?>