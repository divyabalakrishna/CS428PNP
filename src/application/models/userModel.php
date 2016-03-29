<?php

class UserModel extends Model
{

	public function getLoginInfo($email)
	{
		$sql = "SELECT UserID, Email, Password
				FROM User
				WHERE Email = :email";

		$parameters = array(":email" => $email);

		return $GLOBALS["beans"]->queryHelper->getSingleRowObject($this->db, $sql, $parameters);
	}

	public function insertUser($email, $password) {
		$sql = "INSERT INTO User (Email, Password)
				VALUES (:email, :password)";

		$parameters = array(
				":email" => $email,
				":password" => password_hash($password, PASSWORD_DEFAULT)
		);

		return $GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	}
	
	public function getProfile($userID){
		$sql = "SELECT FirstName, LastName, Email, Phone, Picture, Radius, Reminder, NickName, 
					DATE_FORMAT(BirthDate, '%m/%d/%Y') AS FormattedDate, 
					Gender
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

	public function updateUser($userID, $firstName, $lastName, $email, $phone, $nickName, $gender, $birthDate) {
		$sql = "UPDATE User
				SET FirstName = :firstName,
					LastName = :lastName,
					Email = :email,
					Phone = :phone,
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

}