<?php

/**
 * This class handles database interaction for user module.
 */
class UserModel extends Model {

	/**
	 * Retrieve login information for a given email address.
	 * @param string $email Email address.
	 * @return stdClass Query result.
	 */
	public function getLoginInfo($email) {
		$sql = "SELECT UserID, Email, Password, Active
				FROM User
				WHERE Email = :email";

		$parameters = array(":email" => $email);

		return $GLOBALS["beans"]->queryHelper->getSingleRowObject($this->db, $sql, $parameters);
	}

	/**
	 * Insert a user record.
	 * @param string $email Email address.
	 * @param string $password Password.
	 * @param string $activation Activation code.
	 * @return integer User ID.
	 */
	public function insertUser($email, $password, $activation) {
		$sql = "INSERT INTO User (Email, Password, Active)
				VALUES (:email, :password, :active)";

		$parameters = array(
				":email" => $email,
				":password" => password_hash($password, PASSWORD_DEFAULT),
				":active" => $activation
		);

		return $GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	/**
	 * Retrieve user details for a given user ID.
	 * @param integer $userID User ID.
	 * @return stdClass Query result.
	 */
	public function getProfile($userID) {
		$sql = "SELECT FirstName, LastName, Email, Phone, Picture, Radius, Reminder, NickName, 
					DATE_FORMAT(BirthDate, '%m/%d/%Y') AS FormattedDate, 
					Gender, Active
				FROM User
				WHERE UserID = :userID";
	
		$parameters = array(":userID" => $userID);
	
		return $GLOBALS["beans"]->queryHelper->getSingleRowObject($this->db, $sql, $parameters);
	}

	/**
	 * Retrieve user tag records for a given user ID.
	 * @param integer $userID User ID.
	 * @return array Query result.
	 */
	public function getUserTags($userID) {
		$sql = "SELECT UserTag.*,
					Tag.Name AS TagName
				FROM UserTag
				INNER JOIN Tag ON Tag.TagID = UserTag.TagID
				WHERE UserTag.UserID = :userID";

		$parameters = array(":userID" => $userID);

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}

	/**
	 * Updates a user record.
	 * @param integer $userID User ID.
	 * @param string $firstName First name.
	 * @param string $lastName Last name.
	 * @param string $email Email address.
	 * @param string $password password.
	 * @param string $phone Phone number.
	 * @param string $nickName Nick name.
	 * @param string $gender Gender.
	 * @param string $birthDate Birthdate.
	 */
	public function updateUser($userID, $firstName, $lastName, $email, $password, $phone, $nickName, $gender, $birthDate) {
		$sql = "UPDATE User
				SET FirstName = :firstName,
					LastName = :lastName,
					Email = :email,";

		if ($password != "") {
			$sql .= "Password = :password,";
		}

		$sql .= "	Phone = :phone,
					NickName = :nickName,
					Gender = :gender,
					BirthDate = STR_TO_DATE(:birthDate, '%m/%d/%Y')
				WHERE User.UserID = :userID";

		$parameters = array(
				":userID" => $userID,
				":firstName" => $firstName,
				":lastName" => $lastName,
				":email" => $email,
				":phone" => $phone,
				":nickName" => $nickName,
				":gender" => $gender,
				":birthDate" => $birthDate
		);

		if ($password != "") {
			$parameters["password"] = password_hash($password, PASSWORD_DEFAULT);
		}

		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	/**
	 * Delete user tag records for a given user ID.
	 * @param integer $userID User ID.
	 */
	public function deleteUserTags($userID) {
		$sql = "DELETE
				FROM UserTag
				WHERE UserTag.UserID = :userID";

		$parameters = array(":userID" => $userID);

		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	/**
	 * Insert a user tag record.
	 * @param integer $userID User ID.
	 * @param integer $tagID Tag ID.
	 */
	public function insertUserTag($userID, $tagID) {
		$sql = "INSERT INTO UserTag (UserID, TagID)
				VALUES (:userID, :tagID)";

		$parameters = array(
				":userID" => $userID,
				":tagID" => $tagID
		);

		return $GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	/**
	 * Update profile picture file name for a given user ID.
	 * @param integer $userID User ID.
	 * @param string $picture Profile picture file name.
	 */
	public function updatePicture($userID, $picture) {
		$sql = "UPDATE User
				SET Picture = :picture
				WHERE UserID = :userID";
	
		$parameters = array(
				":userID" => $userID,
				":picture" => $picture
		);
	
		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	/**
	 * Retrieve all user IDs.
	 * @return array Query result.
	 */
	public function getAllUserIDs() {
		$sql = "SELECT UserID
				FROM User
				ORDER BY UserID";

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql);
	}

	/**
	 * Retrieve activation code for a given user ID.
	 * @param integer $userID User ID.
	 * @return stdClass Query result.
	 */
	public function isActive($userID) {
		$sql = "SELECT Active
				FROM User
				WHERE UserID = :userID";

		$parameters = array(":userID" => $userID);

		return $GLOBALS["beans"]->queryHelper->getSingleRowObject($this->db, $sql, $parameters);
	}

	/**
	 * Update activation code for a given user ID.
	 * @param integer $userID User ID.
	 * @param string $active Activation code.
	 */
	public function setActive($userID, $active) {
		$sql = "UPDATE User
				SET Active = :active
				WHERE UserID = :userID";

		$parameters = array(
				":userID" => $userID,
				":active" => $active
		);

		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	/**
	 * Update password for a given user ID.
	 * @param integer $userID User ID.
	 * @param string $password Password.
	 * @param string $encrypt Use yes to enable encryption, otherwise encryption will be disabled.
	 */
	public function setPassword($userID, $password, $encrypt = 'yes') {
		$sql = "UPDATE User
				SET Password = :password
				WHERE UserID = :userID";
	
		$parameters = array(
				":userID" => $userID
		);

		if ($encrypt == "yes") {
			// Perform encryption
			$parameters["password"] = password_hash($password, PASSWORD_DEFAULT);
		}
		else {
			$parameters["password"] = $password;
		}

		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

}