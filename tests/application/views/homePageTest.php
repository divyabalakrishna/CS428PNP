<?php

include_once (__DIR__ . '/viewTestCase.php');

class HomePageTest extends ViewTestCase {

	public function testActivationInvalid() {
		parent::loginToSite('firstlast@email.com', '98765');

		$activationCodeField = $this->byCssSelector('#activationForm #active');
		$activationCodeField->clear();
		$this->keys('28sjd391kmcwk2j3');

		$form = $this->byId('activationForm');
		$form->submit();
		usleep(500000);

		$errorMessage = $this->byCssSelector('#activationForm div.alert');

		$this->assertEquals('Activation failed, invalid code !!!.', $errorMessage->text());
	}

	public function testActivationSuccessful() {
		parent::loginToSite('firstlast@email.com', '98765');

		$activationCodeField = $this->byCssSelector('#activationForm #active');
		$activationCodeField->clear();
		$this->keys('abcde12345abcde1');

		$form = $this->byId('activationForm');
		$form->submit();
		usleep(500000);

		try {
			$this->byId('activationForm');
			$this->fail('Activation form exists.');
		}
		catch(PHPUnit_Extensions_Selenium2TestCase_WebDriverException $exception) {
			$this->assertEquals(PHPUnit_Extensions_Selenium2TestCase_WebDriverException::NoSuchElement, $exception ->getCode());
		}
	}

}