<?php

include_once(__DIR__ . '/../../../src/application/helpers/stringHelper.php');

/**
 * This class provides unit tests for StringHelper.
 */
class StringHelperTest extends PHPUnit_Framework_TestCase {

	private static $string;
	private static $stringHelper;

	/**
	 * Initialize required variables once for all tests.
	 */
	public static function setUpBeforeClass() {
		static::$string = 'PLAN & PLAY';
		static::$stringHelper = new StringHelper();
	}

	/**
	 * Test the normal usage for left function.
	 */
	public function testLeft() {
		$this->assertEquals('PLAN', static::$stringHelper->left(static::$string, 4));
	}

	/**
	 * Test left function when the original string is empty.
	 */
	public function testLeftEmpty() {
		$this->assertEquals('', static::$stringHelper->left('', 4));
	}

	/**
	 * Test left function when the number of characters is greater than the original string length.
	 */
	public function testLeftOverLength() {
		$this->assertEquals('PLAN & PLAY', static::$stringHelper->left(static::$string, 20));
	}

	/**
	 * Test the normal usage for right function.
	 */
	public function testRight() {
		$this->assertEquals('PLAY', static::$stringHelper->right(static::$string, 4));
	}

	/**
	 * Test right function when the original string is empty.
	 */
	public function testRightEmpty() {
		$this->assertEquals('', static::$stringHelper->right('', 4));
	}

	/**
	 * Test right function when the number of characters is greater than the original string length.
	 */
	public function testRightOverLength() {
		$this->assertEquals('PLAN & PLAY', static::$stringHelper->right(static::$string, 20));
	}

	/**
	 * Test genString function with the default number of characters.
	 */
	public function testGenStringDefaultLength() {
		$randomString = static::$stringHelper->genString();
		$this->assertEquals(16, strlen($randomString));
	}

	/**
	 * Test the normal usage for genString function.
	 */
	public function testGenString() {
		$randomString = static::$stringHelper->genString(10);
		$this->assertEquals(10, strlen($randomString));
	}

	/**
	 * Test genString function when the number of characters is a negative number.
	 */
	public function testGenStringNegativeLength() {
		$randomString = static::$stringHelper->genString(-1);
		$this->assertEquals(0, strlen($randomString));
	}

}