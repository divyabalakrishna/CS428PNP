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
		$sql = "SELECT FirstName, LastName, Email, Phone, Picture, Radius, Reminder
				FROM User
				WHERE UserID = :userID";
	
		$parameters = array(":userID" => $userID);
	
		return $GLOBALS["beans"]->queryHelper->getSingleRowObject($this->db, $sql, $parameters);
	}
	
	public function updateProfile($userID, $firstname, $lastname, $email, $phone, $picture, $radius, $reminder){
		$sql = "UPDATE User
				SET FirstName = :firstname,
					LastName = :lastname,
					Email = :email, 
					Phone = :phone, 
					Picture = :picture,
					Radius = :radius, 
					Reminder = :reminder
				WHERE UserID = :userID";

		$parameters = array(
				":userID" => $userID,
				":firstname" => $firstname,
				":lastname" => $lastname,
				":email" => $email,
				":phone" => $phone,
				":picture" => $picture,
				":radius" => $radius,
				":reminder" => $reminder
		);
		$GLOBALS["beans"]->queryHelper->executeWriteQuery($this->db, $sql, $parameters);
	
	}

}