<?php

include_once (__DIR__ . '/viewTestCase.php');

class EventViewTest extends ViewTestCase {

	public function testViewHostedEvent() {
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

		$createdDiv = $this->byCssSelector('.created');
		$viewDetailsLink = $createdDiv->byLinkText('View Details');
		$viewDetailsLink->click();

		$editButton = $this->byId('edit');
		$this->assertEquals('EDIT', $editButton->text());

		$deleteButton = $this->byId('delete');
		$this->assertEquals('DELETE', $deleteButton->text());

		try {
			$this->byId('join');
			$this->fail('Join button exists.');
		}
		catch(PHPUnit_Extensions_Selenium2TestCase_WebDriverException $exception) {
			$this->assertEquals(PHPUnit_Extensions_Selenium2TestCase_WebDriverException::NoSuchElement, $exception ->getCode());
		}

		try {
			$this->byId('leave');
			$this->fail('Leave button exists.');
		}
		catch(PHPUnit_Extensions_Selenium2TestCase_WebDriverException $exception) {
			$this->assertEquals(PHPUnit_Extensions_Selenium2TestCase_WebDriverException::NoSuchElement, $exception ->getCode());
		}
	}

	public function testViewJoinedEvent() {
		$this->url($this->applicationURL);

		$signInLink = $this->byId('signInLink');
		$signInLink->click();
		usleep(500000);

		$emailField = $this->byCssSelector('#signinForm #email');
		$emailField->clear();
		$this->keys('jsmith@email.com');

		$passwordField = $this->byCssSelector('#signinForm #password');
		$passwordField->clear();
		$this->keys('abcde');

		$form = $this->byId('signinForm');
		$form->submit();
		usleep(500000);

		$createdDiv = $this->byCssSelector('.joined');
		$viewDetailsLink = $createdDiv->byLinkText('View Details');
		$viewDetailsLink->click();

		$editButton = $this->byId('leave');
		$this->assertEquals('LEAVE', $editButton->text());

		try {
			$this->byId('edit');
			$this->fail('Edit button exists.');
		}
		catch(PHPUnit_Extensions_Selenium2TestCase_WebDriverException $exception) {
			$this->assertEquals(PHPUnit_Extensions_Selenium2TestCase_WebDriverException::NoSuchElement, $exception ->getCode());
		}

		try {
			$this->byId('delete');
			$this->fail('Delete button exists.');
		}
		catch(PHPUnit_Extensions_Selenium2TestCase_WebDriverException $exception) {
			$this->assertEquals(PHPUnit_Extensions_Selenium2TestCase_WebDriverException::NoSuchElement, $exception ->getCode());
		}

		try {
			$this->byId('join');
			$this->fail('Join button exists.');
		}
		catch(PHPUnit_Extensions_Selenium2TestCase_WebDriverException $exception) {
			$this->assertEquals(PHPUnit_Extensions_Selenium2TestCase_WebDriverException::NoSuchElement, $exception ->getCode());
		}
	}

	public function testViewOtherEvent() {
		$this->url($this->applicationURL);

		$signInLink = $this->byId('signInLink');
		$signInLink->click();
		usleep(500000);

		$emailField = $this->byCssSelector('#signinForm #email');
		$emailField->clear();
		$this->keys('joe@email.com');

		$passwordField = $this->byCssSelector('#signinForm #password');
		$passwordField->clear();
		$this->keys('password');

		$form = $this->byId('signinForm');
		$form->submit();
		usleep(500000);

		$navbar = $this->byId('navbar');
		$searchLink = $navbar->byLinkText('Search');
		$searchLink->click();

		$viewDetailsLink = $this->byCssSelector('.table-striped tr a');
		$viewDetailsLink->click();

		$editButton = $this->byId('join');
		$this->assertEquals('JOIN', $editButton->text());

		try {
			$this->byId('edit');
			$this->fail('Edit button exists.');
		}
		catch(PHPUnit_Extensions_Selenium2TestCase_WebDriverException $exception) {
			$this->assertEquals(PHPUnit_Extensions_Selenium2TestCase_WebDriverException::NoSuchElement, $exception ->getCode());
		}

		try {
			$this->byId('delete');
			$this->fail('Delete button exists.');
		}
		catch(PHPUnit_Extensions_Selenium2TestCase_WebDriverException $exception) {
			$this->assertEquals(PHPUnit_Extensions_Selenium2TestCase_WebDriverException::NoSuchElement, $exception ->getCode());
		}

		try {
			$this->byId('leave');
			$this->fail('Leave button exists.');
		}
		catch(PHPUnit_Extensions_Selenium2TestCase_WebDriverException $exception) {
			$this->assertEquals(PHPUnit_Extensions_Selenium2TestCase_WebDriverException::NoSuchElement, $exception ->getCode());
		}
	}

}