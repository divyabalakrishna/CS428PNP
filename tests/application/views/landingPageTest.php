<?php

include_once (__DIR__ . '/viewTestCase.php');

class LandingPageTest extends ViewTestCase {

	public function testSignInInvalid() {
		$this->url($this->applicationURL);

		$signInLink = $this->byId('signInLink');
		$signInLink->click();
		usleep(500000);

		$emailField = $this->byCssSelector('#signinForm #email');
		$emailField->clear();
		$this->keys('jdoe@email.com');

		$passwordField = $this->byCssSelector('#signinForm #password');
		$passwordField->clear();
		$this->keys('123');

		$form = $this->byId('signinForm');
		$form->submit();
		usleep(500000);

		$errorMessage = $this->byCssSelector('#signinForm div.alert');

		$this->assertEquals('Invalid email or password.', $errorMessage->text());
	}

	public function testSignInSuccessful() {
		$this->url($this->applicationURL);

		$signInLink = $this->byId('signInLink');
		$signInLink->click();
		usleep(500000);

		$emailField = $this->byCssSelector('#signinForm #email');
		$emailField->clear();
		$this->keys('jdoe@email.com');

		$passwordField = $this->byCssSelector('#signinForm #password');
		$passwordField->clear();
		$this->keys('12345');

		$form = $this->byId('signinForm');
		$form->submit();
		usleep(500000);

		$logoutLink = $this->byLinkText('Logout');

		$this->assertEquals('Logout', $logoutLink->text());
	}

	public function testSignUp() {
		$this->url($this->applicationURL);

		$signUpLink = $this->byId('signUpLink');
		$signUpLink->click();
		usleep(500000);

		$emailField = $this->byCssSelector('#signupForm #email');
		$emailField->clear();
		$this->keys('email@email.com');

		$passwordField = $this->byCssSelector('#signupForm #password1');
		$passwordField->clear();
		$this->keys('abc123');

		$confirmPasswordField = $this->byCssSelector('#signupForm #password2');
		$confirmPasswordField->clear();
		$this->keys('abc123');

		$form = $this->byId('signupForm');
		$form->submit();
		usleep(500000);

		$activateButton = $this->byCssSelector('#activationForm button');

		$this->assertEquals('ACTIVATE', $activateButton->text());
	}

	public function testForgetPassword() {
		$this->url($this->applicationURL);

		$signInLink = $this->byId('signInLink');
		$signInLink->click();
		usleep(500000);

		$signInForm = $this->byId('signinForm');
		$forgetLink = $signInForm->byLinkText('Forget Password');
		$forgetLink->click();
		usleep(800000);

		$emailField = $this->byCssSelector('#forgetForm #email');
		$emailField->clear();
		$this->keys('jdoe@email.com');

		$form = $this->byId('forgetForm');
		$form->submit();
		usleep(500000);

		$successMessage = $this->byCssSelector('#forgetForm div.alert');

		$this->assertEquals('Reset password notification sent successfully. Please check your email.', $successMessage->text());
	}
}