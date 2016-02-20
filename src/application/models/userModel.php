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

}