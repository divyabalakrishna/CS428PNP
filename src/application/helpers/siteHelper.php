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

}