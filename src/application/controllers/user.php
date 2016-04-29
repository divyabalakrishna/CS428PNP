<?php

class User {

	/**
	 * check user login credential info in database
	 */
	public function login() {
		$errorMessage = "Invalid email or password.";
		$loginInfo = $GLOBALS["beans"]->userModel->getLoginInfo($_POST["email"]);

		if (strcasecmp($_POST["email"],$loginInfo->Email) == 0) {
			if (password_verify($_POST["password"],$loginInfo->Password)) {
				$_SESSION["userID"] = $loginInfo->UserID;
				$errorMessage = "";
			}
		}

		if ($errorMessage != "") {
			$GLOBALS["beans"]->siteHelper->addAlert("danger", $errorMessage);
			$GLOBALS["beans"]->siteHelper->setPopUp("#myModal");
		}

		header('location: ' . URL_WITH_INDEX_FILE);
	}

	/**
	 * perform user logout, clear session
	 */    
	public function logout() {
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

	/**
	 * account creation, insert user into database
	 * @param string $_POST["email"]     
	 * @param string $_POST["password1"]     
	 */    
	public function createAccount() {
		$activation = $GLOBALS["beans"]->stringHelper->genString();

		$userID = $GLOBALS["beans"]->userModel->insertUser($_POST["email"], $_POST["password1"], $activation);
		$GLOBALS["beans"]->siteHelper->sendActivationMail($_POST["email"], $activation);

		$_SESSION["userID"] = $userID;

		header('location: ' . URL_WITH_INDEX_FILE . 'user/viewProfile');
	}

	/**
	 * forgot password, send email to reset password
	 * @param string $_POST["email"]
	 */    
	public function forgotPassword() {
		$code = $GLOBALS["beans"]->stringHelper->genString();

		$loginInfo = $GLOBALS["beans"]->userModel->getLoginInfo($_POST["email"]);
		$GLOBALS["beans"]->userModel->setPassword($loginInfo->UserID, $code, "no");
		$GLOBALS["beans"]->siteHelper->sendForgotMail($_POST["email"], $code);

		$errorMessage = "Reset password notification sent successfully. Please check your email.";

		$GLOBALS["beans"]->siteHelper->addAlert("info", $errorMessage,"#myModal3");
		$GLOBALS["beans"]->siteHelper->setPopUp("#myModal3");

		header('location: ' . URL_WITH_INDEX_FILE);
	}

	/**
	 * check user email address in user table 
	 * @param string $_POST["email"]
	 */        
	public function emailExist() {
		$unique = false;
		$loginInfo = $GLOBALS["beans"]->userModel->getLoginInfo($_POST["email"]);

		if (!is_numeric($loginInfo->UserID)) {
			$unique = true;
		}

		return $unique;
	}

	/**
	 * check whether email address is not registered
	 */            
	public function checkUniqueEmail() {
		echo json_encode($this->emailExist());
	}

	/**
	 * check whether email address is registered
	 */            
    public function checkExistEmail() {
		echo json_encode(!$this->emailExist());
	}

	/**
	 * view user profile main page
	 */                
	public function viewProfile() {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$profileInfo = $GLOBALS["beans"]->userModel->getProfile($userID);
		$tagInfo = $GLOBALS["beans"]->userModel->getUserTags($userID);
		$tags = $GLOBALS["beans"]->resourceModel->getTags();
		
		require APP . 'views/_templates/header.php';
		require APP . 'views/user/user_profile.php';
		require APP . 'views/_templates/footer.php';
	}

	/**
	 * view other user profile main page
	 * @param integer $userID
	 */                    
	public function viewParticipantProfile($userID) {
		$profileInfo = $GLOBALS["beans"]->userModel->getProfile($userID);
		$userTags = $GLOBALS["beans"]->userModel->getUserTags($userID);

        $countHosteds = $GLOBALS["beans"]->eventModel->countHostedEvents($userID);
        if($countHosteds)
            $countHosted = $countHosteds[0]->cnt;
        else
            $countHosted = 0;
        
        $countJoineds = $GLOBALS["beans"]->eventModel->countJoinedEvents($userID);
        if($countJoineds)
            $countJoined = $countJoineds[0]->cnt;
        else
            $countJoined = 0;

		require APP . 'views/_templates/header.php';
		require APP . 'views/user/user_profileview.php';
		require APP . 'views/_templates/footer.php';
	}

	/**
	 * update user profile into database
	 * @param integer $userID
	 */                        
	public function saveProfile() {
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

	/**
	 * account activation process, set activation flag in user table
	 */                        
	public function activation() {
		$errorMessage = "Activation failed, invalid code !!!.";

		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		if (is_numeric($userID) && $_POST["password"] == "") {
			$loginInfo = $GLOBALS["beans"]->userModel->getProfile($userID);

			if (strcasecmp($_POST["email"],$loginInfo->Email) == 0) {
				if (strcasecmp($_POST["active"],$loginInfo->Active) == 0) {
					$GLOBALS["beans"]->userModel->setActive($userID, "Yes");

					$errorMessage = "";
					$GLOBALS["beans"]->siteHelper->addAlert("info", "Congratulation. Your Account is now active.");
				}
			}
		}
		else {
			$loginInfo = $GLOBALS["beans"]->userModel->getLoginInfo($_POST["email"]);

			if (strcasecmp($_POST["email"],$loginInfo->Email) == 0) {
				if (password_verify($_POST["password"],$loginInfo->Password)) {
					if (strcasecmp($_POST["active"],$loginInfo->Active) == 0) {
						$_SESSION["userID"] = $loginInfo->UserID;
						$GLOBALS["beans"]->userModel->setActive($loginInfo->UserID, "Yes");

						$errorMessage = "";
						$GLOBALS["beans"]->siteHelper->addAlert("info", "Congratulation. Your Account is now active.");
					}
				}
				else {
					$errorMessage = "Invalid password.";
					$GLOBALS["beans"]->siteHelper->addAlert("danger", $errorMessage);

					header('location: ' . URL_WITH_INDEX_FILE . "user/active/" . $_POST["email"] . "/" . $_POST["active"]);
					exit();
				}
			}
		}

		if ($errorMessage != "") {
			$GLOBALS["beans"]->siteHelper->addAlert("danger", $errorMessage);
		}

		header('location: ' . URL_WITH_INDEX_FILE);
	}

	/**
	 * resend email activation code 
	 */                        
	public function resendActivation() {
		$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");
		$loginInfo = $GLOBALS["beans"]->userModel->getProfile($userID);
		$activation = $GLOBALS["beans"]->stringHelper->genString();

		$GLOBALS["beans"]->userModel->setActive($userID, $activation);
		$GLOBALS["beans"]->siteHelper->sendActivationMail($loginInfo->Email, $activation);
		$errorMessage = "Activation code sent successfully.";
		$GLOBALS["beans"]->siteHelper->addAlert("success", $errorMessage);

		header('location: ' . URL_WITH_INDEX_FILE);
	}

	/**
	 * user activation main page
	 * @param string $email
	 * @param string $active
	 */                        
	public function active($email = "", $active = "") {
		$user = $GLOBALS["beans"]->userModel->getLoginInfo($email);

		if (strcasecmp($active,$user->Active) == 0 && $user->Active != "" && $user->Active != "Yes") {
			$userID = $GLOBALS["beans"]->siteHelper->getSession("userID");

			if (!is_numeric($userID)) {
				$cheat = 1;
			}

			require APP . 'views/_templates/header.php';
			require APP . 'views/user/activation.php';
			require APP . 'views/_templates/footer.php';
		}
		else {
			header('location: ' . URL_WITH_INDEX_FILE);
		}
	}

	/**
	 * reset password main page
	 * @param string $email
	 * @param string $passcode
	 */                        
	public function reset($email = "", $passcode = "") {
		$loginInfo = $GLOBALS["beans"]->userModel->getLoginInfo($email);

		if (strcasecmp($passcode,$loginInfo->Password) == 0) {
			$cheat = 1;

			require APP . 'views/_templates/header.php';
			require APP . 'views/user/reset.php';
			require APP . 'views/_templates/footer.php';
		}
		else {
			header('location: ' . URL_WITH_INDEX_FILE);
		}
	}

	/**
	 * reset password process, update new password into user table 
	 * @param string $_POST["email"]
	 * @param string $_POST["password1"]
	 */                            
	public function resetPassword() {
		$activation = $GLOBALS["beans"]->stringHelper->genString();
		$loginInfo = $GLOBALS["beans"]->userModel->getLoginInfo($_POST["email"]);

		$GLOBALS["beans"]->userModel->setPassword($loginInfo->UserID, $_POST["password1"]);

		$errorMessage = "Password changed successfully.";
		$GLOBALS["beans"]->siteHelper->addAlert("info", $errorMessage);
		$GLOBALS["beans"]->siteHelper->setPopUp("#myModal");

		header('location: ' . URL_WITH_INDEX_FILE);
	}

}