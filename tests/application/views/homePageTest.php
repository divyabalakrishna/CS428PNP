<?php

include_once (__DIR__ . '/viewTestCase.php');

/**
 * This class provides Selenium tests for home page.
 */
class HomePageTest extends ViewTestCase {

	/**
	 * Test activating an account with an invalid activation code.
	 */
	public function testActivationInvalid() {
		parent::loginToSite('firstlast@email.com', '98765');

		$activationCodeField = $this->byCssSelector('#activationForm #active');
		$activationCodeField->clear();
		$this->keys('28sjd391kmcwk2j3');

		$form = $this->byId('activationForm');
		$form->submit();
		usleep(500000);

		$errorMessage = $this->byCssSelector('#activationForm div.alert span');

		$this->assertEquals('Activation failed, invalid code !!!.', $errorMessage->text());
	}

	/**
	 * Test activating an account with a valid activation code.
	 */
	public function testActivationSuccessful() {
		parent::loginToSite('firstlast@email.com', '98765');

		$activationCodeField = $this->byCssSelector('#activationForm #active');
		$activationCodeField->clear();
		$this->keys('abcde12345abcde1');

		$form = $this->byId('activationForm');
		$form->submit();
		usleep(500000);

		$successMessage = $this->byCssSelector('div.alert span');

		$this->assertEquals('Congratulation. Your Account is now active.', $successMessage->text());
	}

	/**
	 * Test clicking on the Hosted filter.
	 */
	public function testViewHosted() {
		parent::loginToSite('jdoe@email.com', '12345');

		$hostedButton = $this->byId('hosted');
		$hostedButton->click();
		usleep(500000);

		$eventTitle = $this->byCssSelector('div.tiles div.title');
		$this->assertEquals('Badminton Game', $eventTitle->text());
	}

	/**
	 * Test clicking on the Past filter.
	 */
	public function testViewPast() {
		parent::loginToSite('jdoe@email.com', '12345');

		$pastButton = $this->byId('past');
		$pastButton->click();
		usleep(500000);

		$eventTitle = $this->byCssSelector('div.tiles div.title');
		$this->assertEquals('Casual jogging', $eventTitle->text());
	}

	/**
	 * Test clicking on the Joined filter.
	 */
	public function testViewJoined() {
		parent::loginToSite('jsmith@email.com', 'abcde');

		$joinedButton = $this->byId('joined');
		$joinedButton->click();
		usleep(500000);

		$eventTitle = $this->byCssSelector('div.tiles div.title');
		$this->assertEquals('Badminton Game', $eventTitle->text());
	}

	/**
	 * Test clicking on the Feed filter.
	 */
	public function testViewFeed() {
		parent::loginToSite('joe@email.com', 'password');

		$feedButton = $this->byId('feed');
		$feedButton->click();
		usleep(500000);

		$eventTitle = $this->byCssSelector('div.tiles div.title');
		$this->assertEquals('Badminton Game', $eventTitle->text());
	}

}