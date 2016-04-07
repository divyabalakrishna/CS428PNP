<?php

include_once (__DIR__ . '/viewTestCase.php');

class LandingPageTest extends ViewTestCase {

	public function testLoginInvalid() {
		$this->url($this->applicationURL);

		$loginLink = $this->byId('signInLink');
		$loginLink->click();
		sleep(1);

		$emailField = $this->byCssSelector('#signinForm #email');
		$emailField->clear();
		$this->keys('jdoe@email.com');

		$emailField = $this->byCssSelector('#signinForm #password');
		$emailField->clear();
		$this->keys('123');

		$form = $this->byId('signinForm');
		$form->submit();
		sleep(1);

		$errorMessage = $this->byCssSelector('#signinForm div.alert');

		$this->assertEquals('Invalid email or password.', $errorMessage->text());
	}

}