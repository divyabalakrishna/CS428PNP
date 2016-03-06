<?php
        // Start XML file, create parent node
        $dom = new DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);

        header("Content-type: text/xml");

        // Iterate through the rows, adding XML nodes for each
        foreach ($events as $event) { 

          // ADD TO XML DOCUMENT NODE
          $node = $dom->createElement("marker");
          $newnode = $parnode->appendChild($node);
          $newnode->setAttribute("id", $event->EventID);
          $newnode->setAttribute("name", $event->Name);
          $newnode->setAttribute("address", $event->Address);
          $newnode->setAttribute("lat", $event->Lat);
          $newnode->setAttribute("lon", $event->Lon);
          $newnode->setAttribute("tag", $event->TagName);
          $newnode->setAttribute("time", $event->FormattedDateTime);
        }

        echo $dom->saveXML();	
?>