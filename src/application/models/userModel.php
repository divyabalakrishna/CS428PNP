<?php

class UserModel extends Model {

	/**
	 * retrieves login info for given email
	 * @param string $email
	 */
	public function getLoginInfo($email) {
		$sql = "SELECT UserID, Email, Password, Active
				FROM User
				WHERE Email = :email";

		$parameters = array(":email" => $email);

		return $GLOBALS["beans"]->queryHelper->getSingleRowObject($this->db, $sql, $parameters);
	}

	/**
	 * insert user into database 
	 * @param string $email
	 * @param string $password
	 * @param string $activation
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
	 * Retrieves profile with user id
	 * @param integer $userID
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
	 * Retrieves user tags using user id
	 * @param integer $userID
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
	 * updates the user using given values like user id, first name, last name, etc.
	 * @param integer $userID
	 * @param string $firstName
	 * @param string $lastName
	 * @param string $email
	 * @param string $password
	 * @param string $phone
	 * @param string $nickName
	 * @param string $gender
	 * @param string $birthDate
	 */
	public function updateUser($userID, $firstName, $lastName, $email, $password, $phone, $nickName, $gender, $birthDate) {
		$sql = "UPDATE User
				SET FirstName = :firstName,
					LastName = :lastName,
					Email = :email,";
		//make sure password is not blank
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

		//make sure password is not blank
		if ($password != "") {
			$parameters["password"] = password_hash($password, PASSWORD_DEFAULT);
		}

		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	/**
	 * Delete user tages using user id
	 * @param integer $userID
	 */
	public function deleteUserTags($userID) {
		$sql = "DELETE
				FROM UserTag
				WHERE UserTag.UserID = :userID";

		$parameters = array(":userID" => $userID);

		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	/**
	 * insert user's tag (interest) by giving user id and tag id
	 * @param integer $userID
	 * @param string $tagID
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
	 * update picture using user id and picture
	 * @param integer $userID
	 * @param string $picture
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
	 * Retrieves all user ids
	 */
	public function getAllUserIDs() {
		$sql = "SELECT UserID
				FROM User
				ORDER BY UserID";

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql);
	}

	/**
	 * Checks if user id is active or not
	 * @param integer $userID
	 */
	public function isActive($userID) {
		$sql = "SELECT Active
				FROM User
				WHERE UserID = :userID";

		$parameters = array(":userID" => $userID);

		return $GLOBALS["beans"]->queryHelper->getSingleRowObject($this->db, $sql, $parameters);
	}

	/**
	 * Set user id to become active
	 * @param integer $userID
	 * @param string $active
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
	 * sets password for user
	 * @param integer $userID
	 * @param string $password
	 * @param string $encrypt
	 */
	public function setPassword($userID, $password, $encrypt = 'yes') {
		$sql = "UPDATE User
				SET Password = :password
				WHERE UserID = :userID";
	
		$parameters = array(
				":userID" => $userID
		);

		//if encryption is enabled
		if ($encrypt == "yes") {
			$parameters["password"] = password_hash($password, PASSWORD_DEFAULT);
		}
		else {
			$parameters["password"] = $password;
		}

		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

}