<?php

class UserModel extends Model {

	public function getLoginInfo($email) {
		$sql = "SELECT UserID, Email, Password, Active
				FROM User
				WHERE Email = :email";

		$parameters = array(":email" => $email);

		return $GLOBALS["beans"]->queryHelper->getSingleRowObject($this->db, $sql, $parameters);
	}

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
	
	public function getProfile($userID) {
		$sql = "SELECT FirstName, LastName, Email, Phone, Picture, Radius, Reminder, NickName, 
					DATE_FORMAT(BirthDate, '%m/%d/%Y') AS FormattedDate, 
					Gender, Active
				FROM User
				WHERE UserID = :userID";
	
		$parameters = array(":userID" => $userID);
	
		return $GLOBALS["beans"]->queryHelper->getSingleRowObject($this->db, $sql, $parameters);
	}

	public function getUserTags($userID) {
		$sql = "SELECT UserTag.*
				FROM UserTag
				WHERE UserTag.UserID = :userID";

		$parameters = array(":userID" => $userID);

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}

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

	public function deleteUserTags($userID) {
		$sql = "DELETE
				FROM UserTag
				WHERE UserTag.UserID = :userID";

		$parameters = array(":userID" => $userID);

		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

	public function insertUserTag($userID, $tagID) {
		$sql = "INSERT INTO UserTag (UserID, TagID)
				VALUES (:userID, :tagID)";

		$parameters = array(
				":userID" => $userID,
				":tagID" => $tagID
		);

		return $GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

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

	public function getAllUserIDs() {
		$sql = "SELECT UserID
				FROM User";

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql);
	}

	public function isActive($userID) {
		$sql = "SELECT Active
				FROM User
				WHERE UserID = :userID";

		$parameters = array(":userID" => $userID);

		return $GLOBALS["beans"]->queryHelper->getSingleRowObject($this->db, $sql, $parameters);
	}

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

	public function setPassword($userID, $password, $encrypt = 'yes') {
		$sql = "UPDATE User
				SET Password = :password
				WHERE UserID = :userID";
	
		$parameters = array(
				":userID" => $userID
		);

		if ($encrypt == "yes") {
			$parameters["password"] = password_hash($password, PASSWORD_DEFAULT);
		}
		else {
			$parameters["password"] = $password;
		}

		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}

}