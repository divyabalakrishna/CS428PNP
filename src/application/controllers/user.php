<?php

class User
{

	public function login()
	{
		$errorMessage = "Invalid email or password.";
		$loginInfo = $GLOBALS["beans"]->userModel->getLoginInfo($_POST["email"]);

		if (strcasecmp($_POST["email"],$loginInfo->Email) == 0)
		{
			if (password_verify($_POST["password"],$loginInfo->Password))
			{
				$_SESSION["userID"] = $loginInfo->UserID;
				$errorMessage = "";
			}
		}

		if ($errorMessage != "")
		{
			$GLOBALS["beans"]->siteHelper->setAlert("danger", $errorMessage);
		}

		header('location: ' . URL_WITH_INDEX_FILE);
	}

	public function logout()
	{
		// Unset all of the session variables.
		$_SESSION = array();

		// If it's desired to kill the session, also delete the session cookie.
		// Note: This will destroy the session, and not just the session data!
		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
					$params["path"], $params["domain"],
					$params["secure"], $params["httponly"]
			);
		}

		// Finally, destroy the session.
		session_destroy();

		header('location: ' . URL_WITH_INDEX_FILE);
	}

	public function createAccount()
	{
		$GLOBALS["beans"]->userModel->insertUser($_POST["email"], $_POST["password1"]);
		//$this->login();
        
        $loginInfo = $GLOBALS["beans"]->userModel->getLoginInfo($_POST["email"]);

        $_SESSION["userID"] = $loginInfo->UserID;
		header('location: ' . URL_WITH_INDEX_FILE);

	}

	public function checkUniqueEmail()
	{
		$unique = false;
		$loginInfo = $GLOBALS["beans"]->userModel->getLoginInfo($_POST["email"]);

		if (!is_numeric($loginInfo->UserID))
		{
			$unique = true;
		}

		echo json_encode($unique);
	}

	public function viewProfile()
	{
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$profileInfo = $GLOBALS["beans"]->userModel->getProfile($userID);
		
		require APP . 'views/_templates/header.php';
		require APP . 'views/user/user_profile.php';
		require APP . 'views/_templates/footer.php';
	}

	
	public function saveProfile(){
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$GLOBALS["beans"]->userModel->updateProfile(
				$userID,
				$_POST["firstname"],
				$_POST["lastname"],
				$_POST["email"],
				$_POST["phone"],
				$_POST["picture"],
				$_POST["radius"],
				$_POST["reminder"],
				$_POST["gender"],
				$_POST["birthdate"],
				$_POST["nickname"]
		);
		header('location: ' . URL_WITH_INDEX_FILE . 'user/viewProfile');
	}
}