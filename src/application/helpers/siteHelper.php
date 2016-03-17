<?php

class SiteHelper
{

	public function getSession($variableName)
	{
		$value = "";

		if (array_key_exists($variableName, $_SESSION))
		{
			$value = $_SESSION[$variableName];
		}

		return $value;
	}

	/* Options for $type:
	 *		success (light green background)
	 *		info (light blue background)
	 *		warning (light yellow background)
	 *		danger (pink background)
	 */
	public function setAlert($type, $message)
	{
		$_SESSION["alert"] = new stdClass();
		$_SESSION["alert"]->type = $type;
		$_SESSION["alert"]->message = $message;
	}

	public function getAlertHTML()
	{
		$html = "";
		$alert = $this->getSession("alert");

		if (is_object($alert))
		{
			$html = "<div class='alert alert-" . $alert->type . "' role='alert'>" . $alert->message . "</div>";
		}

		$_SESSION["alert"] = "";

		return $html;
	}
	public function setPopUp($modalID)
	{
		$_SESSION["popup"] = new stdClass();
		$_SESSION["popup"]->modalID = $modalID;
	}

	public function getPopUp()
	{
		$html = "";
		$popup = $this->getSession("popup");

		if (is_object($popup))
		{
            $html = "$(window).load(function(){ $('". $popup->modalID . "').modal('show');  })";
		}

		$_SESSION["popup"] = "";

		return $html;
	}

    function notifMsg($datetime) 
    {
        date_default_timezone_set('America/Chicago');
        $timeline = time()-strtotime($datetime);
        $time = strtotime($datetime);
        $time = date('H:i', $time);
        $periods = array('day' => 86400, 'hour' => 3600, 'minute' => 60, 'second' => 1);
        $diff = array('day' => 0, 'hour' => 0, 'minute' => 0, 'second' => 0);

        $result = ""; $ret="";
        foreach($periods AS $name => $seconds){
            $num = floor($timeline / $seconds);
            $timeline -= ($num * $seconds);
            $ret .= $num.' '.$name.(($num > 1) ? 's' : '').' ';
            $diff[$name] = $num;
        }
        
        if($diff['day'] >= 2) {
            $result = $diff['day'] . " days ago";
        }
        else if($diff['day'] == 1) {
            $result = "yesterday at " . $time;
        }
        else 
        {
            if($diff['hour'] > 0)
                $result .= $diff['hour'] . " hours ";
            if($diff['minute'] > 0)
                $result .= $diff['minute'] . " minutes";
            else
                $result .= "few seconds";
            $result = $result . " ago";
        }
        
        return trim($result); 
    }    


    
}