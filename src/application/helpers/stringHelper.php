<?php

/**
 * This class provides utility functions for string.
 */
class StringHelper {

	/**
	 * Get a number of characters from the left.
	 * @param string $string A string.
	 * @param string $length Number of characters.
	 * @return string Substring result.
	 */
	public function left($string, $length) {
		$result = trim($string);
		if (strlen($result) > $length) {
			$result = substr($result, 0, $length);
		}

		return $result;
	}

	/**
	 * Get a number of characters from the right.
	 * @param string $string A string.
	 * @param string $length Number of characters.
	 * @return string Substring result.
	 */
	public function right($string, $length) {
		$result = trim($string);
		if (strlen($result) > $length) {
			$result = substr($result, strlen($result) - $length, $length);
		}

		return $result;
	}

	/**
	 * Generate a random string.
	 * @param integer $length Number of characters.
	 * @return Random string.
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
	 * Calculate age from birthdate.
	 * @param date $birthday Birthdate.
	 * @return integer Age.
	 */
	public function convertAge($birthday) {
		// Get birth month, day and year
		$birthDate = explode("/", $birthday);

		// Calculate age from date or birthdate
		$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
		? ((date("Y") - $birthDate[2]) - 1)
		: (date("Y") - $birthDate[2]));

		return $age;
	}

}