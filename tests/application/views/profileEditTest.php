<?php

include_once (__DIR__ . '/viewTestCase.php');

/**
 * This class provides Selenium tests for Edit Profile page.
 */
class ProfileEditTest extends ViewTestCase {

	/**
	 * Test changing password.
	 */
	public function testChangePassword() {
		parent::loginToSite('jdoe@email.com', '12345');

		$searchLink = $this->byId('profileLink');
		$searchLink->click();
		usleep(500000);

		$newPasswordField = $this->byId('newPassword');
		$newPasswordField->clear();
		$this->keys('a1b2c3d4e5');

		$confirmNewPasswordField = $this->byId('confirmNewPassword');
		$confirmNewPasswordField->clear();
		$this->keys('a1b2c3d4e5');

		$form = $this->byId('form');
		$form->submit();
		usleep(500000);

		$successMessage = $this->byCssSelector('div.alert span');

		$this->assertEquals('Password has been successfully changed.', $successMessage->text());
	}

}