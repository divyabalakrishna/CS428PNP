<?php

include_once (__DIR__ . '/viewTestCase.php');

class ProfileEditTest extends ViewTestCase {

	public function testChangePassword() {
		parent::loginToSite('jdoe@email.com', '12345');

		$navbar = $this->byId('navbar');
		$searchLink = $navbar->byLinkText('Profile');
		$searchLink->click();

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