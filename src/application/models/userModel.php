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
	
	public function getTags($userID){
		$sql = "SELECT TagID
				FROM UserTag
				WHERE UserTag.UserID = :userID";
		
		$parameters = array(":userID" => $userID);
		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql, $parameters);
	}
	
	public function updateProfile($userID, $firstname, $lastname, $email, $phone, $gender, $birthdate, $nickname, $user_tags){
		//delete old tags
		$sql = "DELETE
				FROM UserTag
				WHERE UserTag.UserID = :userID";
		
		$parameters = array(
				":userID" => $userID
		);
		
		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
		
		//update user profile information
		$sql = "UPDATE User
				SET FirstName = :firstname,
					LastName = :lastname,
					Email = :email, 
					Phone = :phone, 
					Gender = :gender,
					BirthDate = STR_TO_DATE(:birthdate, '%m/%d/%Y'),
					NickName = :nickname
				WHERE UserID = :userID";

		$parameters = array(
				":userID" => $userID,
				":firstname" => $firstname,
				":lastname" => $lastname,
				":email" => $email,
				":phone" => $phone,
				":gender" => $gender,
				":birthdate" => $birthdate,
				":nickname" => $nickname
		);
		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
		
		//loop through each tag and add to usertag table
		if(!is_null($user_tags)){
			$tags = explode(",", $user_tags);
			foreach ($tags as $tagID) {
				$sql = "INSERT INTO UserTag (UserID, TagID)
				VALUES (:userID, :tagID)";
					
				$parameters = array(
						":userID" => $userID,
						":tagID" => $tagID
				);
					
				$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
			}
		}
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

}