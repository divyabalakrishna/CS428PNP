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

}