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

}