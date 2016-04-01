<?php

class StringHelper
{

	public function left($string, $length)
	{
		$result = trim($string);
		if (strlen($result) > $length)
		{
			$result = substr($result, 0, $length);
		}

		return $result;
	}

	public function right($string, $length)
	{
		$result = trim($string);
		if (strlen($result) > $length)
		{
			$result = substr($result, strlen($result) - $length, $length);
		}

		return $result;
	}

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