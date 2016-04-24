<?php

include_once(__DIR__ . '/../../../src/application/helpers/stringHelper.php');

class StringHelperTest extends PHPUnit_Framework_TestCase {

	private static $string;
	private static $stringHelper;

	public static function setUpBeforeClass() {
		static::$string = 'PLAN & PLAY';
		static::$stringHelper = new StringHelper();
	}

	public function testLeft() {
		$this->assertEquals('PLAN', static::$stringHelper->left(static::$string, 4));
	}

	public function testLeftEmpty() {
		$this->assertEquals('', static::$stringHelper->left('', 4));
	}

	public function testLeftOverLength() {
		$this->assertEquals('PLAN & PLAY', static::$stringHelper->left(static::$string, 20));
	}

	public function testRight() {
		$this->assertEquals('PLAY', static::$stringHelper->right(static::$string, 4));
	}

	public function testRightEmpty() {
		$this->assertEquals('', static::$stringHelper->right('', 4));
	}

	public function testRightOverLength() {
		$this->assertEquals('PLAN & PLAY', static::$stringHelper->right(static::$string, 20));
	}

	public function testGenStringDefaultLength() {
		$randomString = static::$stringHelper->genString();
		$this->assertEquals(16, strlen($randomString));
	}

	public function testGenString() {
		$randomString = static::$stringHelper->genString(10);
		$this->assertEquals(10, strlen($randomString));
	}

	public function testGenStringNegativeLength() {
		$randomString = static::$stringHelper->genString(-1);
		$this->assertEquals(0, strlen($randomString));
	}

}