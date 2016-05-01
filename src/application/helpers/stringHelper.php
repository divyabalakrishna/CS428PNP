<?php

class StringHelper {

	/**
	 * get n-character substring from left  
	 * @param string $string
	 * @param string $length
	 */                                
	public function left($string, $length) {
		$result = trim($string);
		if (strlen($result) > $length) {
			$result = substr($result, 0, $length);
		}

		return $result;
	}

	/**
	 * get n-character substring from right  
	 * @param string $string
	 * @param string $length
	 */                                
	public function right($string, $length) {
		$result = trim($string);
		if (strlen($result) > $length) {
			$result = substr($result, strlen($result) - $length, $length);
		}

		return $result;
	}

	/**
	 * generate random string for activation code and passcode for reset password
	 * @param integer $length
	 */                                    
	public function genString($length = 16) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';

		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}

		return $randomString;
	}    

	/**
	 * Format birthday into age  
	 * @param date $birthday
	 */                                    
	public function convertAge($birthday) {
    
        //explode the date to get month, day and year
        $birthDate = explode("/", $birthday);
        //get age from date or birthdate
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
        ? ((date("Y") - $birthDate[2]) - 1)
        : (date("Y") - $birthDate[2]));
        
        return $age;
    }
    

}