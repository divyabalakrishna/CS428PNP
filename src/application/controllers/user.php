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
			$GLOBALS["beans"]->siteHelper->addAlert("danger", $errorMessage);
			$GLOBALS["beans"]->siteHelper->setPopUp("#myModal");
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
		$userID = $GLOBALS["beans"]->userModel->insertUser($_POST["email"], $_POST["password1"]);

		$_SESSION["userID"] = $userID;

		header('location: ' . URL_WITH_INDEX_FILE . 'user/viewProfile');
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
		$tagInfo = $GLOBALS["beans"]->userModel->getUserTags($userID);
		$tags = $GLOBALS["beans"]->resourceModel->getTags();
		
		require APP . 'views/_templates/header.php';
		require APP . 'views/user/user_profile.php';
		require APP . 'views/_templates/footer.php';
	}

	public function saveProfile(){
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$user = $GLOBALS["beans"]->userModel->getProfile($userID);
		$oldImage = $user->Picture;

		$GLOBALS["beans"]->userModel->updateUser(
				$userID,
				$_POST["firstname"],
				$_POST["lastname"],
				$_POST["email"],
				$_POST["newPassword"],
				$_POST["phone"],
				$_POST["nickname"],
				$_POST["gender"],
				$_POST["birthdate"]
		);

		if ($_POST["newPassword"] != "") {
			$GLOBALS["beans"]->siteHelper->addAlert("success", "Password has been successfully changed.");
		}

		// Delete existing interests
		$GLOBALS["beans"]->userModel->deleteUserTags($userID);

		// Insert new interests
		$tagIDs = array_filter(explode(",", $_POST["user_tags"]), "strlen");
		foreach ($tagIDs as $tagID) {
			$GLOBALS["beans"]->userModel->insertUserTag($userID, $tagID);
		}

		// Profile picture
		$result = $GLOBALS["beans"]->fileHelper->uploadFile("picture", "profile", "jpg,jpeg,png,bmp", "profile image", 2097152, $userID);

		if ($result->fileUploaded) {
			$GLOBALS["beans"]->userModel->updatePicture($userID, $result->fileName);

			if ($oldImage != "" && $oldImage != $result->fileName) {
				$GLOBALS["beans"]->fileHelper->deleteUploadedFile("picture", $oldImage);
			}
		}
		else if ($result->errorMessage != "") {
			$GLOBALS["beans"]->siteHelper->addAlert("danger", $result->errorMessage);
		}

		header('location: ' . URL_WITH_INDEX_FILE . 'user/viewProfile');
	}

}