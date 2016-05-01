<?php

class SiteHelper {

	/**
	 * retrieve session information
	 * @param string $variableName
	 */
	public function getSession($variableName) {
		$value = "";

		if (array_key_exists($variableName, $_SESSION)) {
			$value = $_SESSION[$variableName];
		}

		return $value;
	}

	/**
	 * add alert message notification info into session
	 * @param string $type
	 * @param string $message
	 * @param string $id
	 *
	 *	Options for $type:
	 *		success (light green background)
	 *		info	(light blue background)
	 *		warning (light yellow background)
	 *		danger  (pink background)
	 */
	public function addAlert($type, $message, $id = "") {
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

	/**
	 * retrieve alert info session and show it in html page
	 * @param string $id
	 */
	public function getAlertsHTML($id="") {
		$html = "";
		$alerts = $this->getSession("alerts");

		if (is_array($alerts)) {
			foreach ($alerts as $alert) {
				if (!isset($id) || $alert->id == $id) {
					$html .= "<div class='alert alert-" . $alert->type . "' role='alert'>";
					$html .= "<span>" . $alert->message . "</span>";
					$html .= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>";
					$html .= "</div>";
					$_SESSION["alerts"] = "";
				}
			}
		}

		return $html;
	}

	/**
	 * set popup modalID into session
	 * @param string $modalID
	 */
	public function setPopUp($modalID) {
		$_SESSION["popup"] = new stdClass();
		$_SESSION["popup"]->modalID = $modalID;
	}

	/**
	 * get popup modalID from session and perform javascript command
	 * @param string $modalID
	 */
	public function getPopUp() {
		$html = "";
		$popup = $this->getSession("popup");

		if (is_object($popup)) {
			$html = "$(window).load(function() { $('" . $popup->modalID . "').modal('show'); })";
		}

		$_SESSION["popup"] = "";

		return $html;
	}

	/**
	 * date formatter from datetime to "sometime ago" 
	 * @param string $datetime
	 */
	function notifMsg($datetime) {
		date_default_timezone_set('America/Chicago');
		$timeline = time()-strtotime($datetime);
		$time = strtotime($datetime);
		$time = date('H:i', $time);
		$periods = array('day' => 86400, 'hour' => 3600, 'minute' => 60, 'second' => 1);
		$diff = array('day' => 0, 'hour' => 0, 'minute' => 0, 'second' => 0);

		$result = ""; $ret="";
		foreach ($periods AS $name => $seconds) {
			$num = floor($timeline / $seconds);
			$timeline -= ($num * $seconds);
			$ret .= $num.' '.$name.(($num > 1) ? 's' : '').' ';
			$diff[$name] = $num;
		}

		if ($diff['day'] >= 2) {
			$result = $diff['day'] . " days ago";
		}
		else if ($diff['day'] == 1) {
			$result = "yesterday at " . $time;
		}
		else {
			if ($diff['hour'] > 0) {
				$result .= $diff['hour'] . " hours ";
			}

			if ($diff['minute'] > 0) {
				$result .= $diff['minute'] . " minutes";
			}
			else {
				$result .= "few seconds";
			}

			$result = $result . " ago";
		}

		return trim($result); 
	}

	/**
	 * send email template for activation code notification
	 * @param string $email
	 * @param string $active
	 */
	public function sendActivationMail($email, $active) {
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

	/**
	 * send email template for reset password
	 * @param string $email
	 * @param string $code
	 */
	public function sendForgotMail($email, $code) {
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

	/**
	 * default latitude user location
	 */
	public function getDefaultLat() {
		return 40.11374573;
	}

	/**
	 * default longitude user location
	 */
	public function getDefaultLon() {
		return -88.224828;
	}

}