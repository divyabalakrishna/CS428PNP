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
	public function addAlert($type, $message, $id="")
	{
		$alerts = $this->getSession("alerts");

		if (!is_array($alerts)) {
			$_SESSION["alerts"] = array();
		}

		$alert = new stdClass();
		$alert->type = $type;
		$alert->message = $message;
        $alert->id = $id;

		$_SESSION["alerts"][] = $alert;
	}

	public function getAlertsHTML($id="")
	{
		$html = "";
		$alerts = $this->getSession("alerts");

        if (is_array($alerts))
        {
            foreach($alerts as $alert) {
                if(!isset($id) || $alert->id == $id) {
                    $html .= "<div class='alert alert-" . $alert->type . "' role='alert'>" . $alert->message . "</div>";
                    $_SESSION["alerts"] = "";
                }
            }
        }

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

	public function getTagImage($tagID)
	{
        $imgDir = "/public/img/sports/";
    
		$tagName = $GLOBALS["beans"]->eventModel->getTagName($tagID);

		return $imgDir . $tagName->Name . ".png";
	}

	public function sendActivationMail($email, $active)
    {
        $headers = "From: no-reply@plannplay.web.engr.illinois.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $message = "Hello, <p>Welcome to <b>Plan & Play</b> and thank you for signing up.<br>";
        $message .= "Please take a moment to verify the email address associated with your <b>Plan & Play</b> account";
        $message .= " by inputing the activation code or clicking the link below:<p>";
        $message .= "Email Address: ".$email."<br>";
        $message .= "Activation Code: ".$active."<p>";
        $message .="<a href='".URL_WITH_INDEX_FILE."user/active/".$email."/".$active."'>CONFIRM EMAIL</a><p>";
        $message .= "If you have not signed up for a <b>Plan & Play</b> account, please ignore this email.<p>";
        $message .= "Thanks,<br>The <b>Plan & Play</b> Team";

        $subject = "Plan & Play - Account Activation";
        $to = $email; 

        mail($to,$subject,$message,$headers);        
    }

	public function sendForgotMail($email, $code)
    {
        $headers = "From: no-reply@plannplay.web.engr.illinois.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $message = "Hello, <p>You are requesting password reset.<br>";
        $message .= "Here is your <b>Plan & Play</b> Account information:<p>";
        $message .= "Email Address: ".$email."<br>";
        $message .= "Reset Code: ".$code."<p>";
        $message .="<a href='".URL_WITH_INDEX_FILE."user/reset/".$email."/".$code."'>RESET PASSWORD</a><p>";
        $message .= "If you have not signed up for a <b>Plan & Play</b> account, please ignore this email.<p>";
        $message .= "Thanks,<br>The <b>Plan & Play</b> Team";

        $subject = "Plan & Play - Reset Password Request";
        $to = $email; 

        mail($to,$subject,$message,$headers);        
    }
    
    
}