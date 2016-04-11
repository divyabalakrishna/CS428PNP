<?php

include_once (__DIR__ . '/viewTestCase.php');

class EventViewTest extends ViewTestCase {

	public function testViewHostedEvent() {
		parent::loginToSite('jdoe@email.com', '12345');

		$createdDiv = $this->byCssSelector('.created');
		$viewDetailsLink = $createdDiv->byLinkText('View Details');
		$viewDetailsLink->click();

		$editButton = $this->byId('edit');
		$this->assertEquals('EDIT', $editButton->text());

		$deleteButton = $this->byId('delete');
		$this->assertEquals('DELETE', $deleteButton->text());

		$recreateButton = $this->byId('recreate');
		$this->assertEquals('RECREATE', $recreateButton->text());

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
		parent::loginToSite('jsmith@email.com', 'abcde');

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
			$this->byId('recreate');
			$this->fail('Recreate button exists.');
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
		parent::loginToSite('joe@email.com', 'password');

		$navbar = $this->byId('navbar');
		$searchLink = $navbar->byLinkText('Search');
		$searchLink->click();
		usleep(500000);

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
			$this->byId('recreate');
			$this->fail('Recreate button exists.');
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

	public function testInsertComment() {
		parent::loginToSite('jdoe@email.com', '12345');

		$createdDiv = $this->byCssSelector('.created');
		$viewDetailsLink = $createdDiv->byLinkText('View Details');
		$viewDetailsLink->click();

		$commentTextArea = $this->byCssSelector('div.comments #text');
		$commentTextArea->clear();
		$this->keys('Anyone has extra racket?');

		$form = $this->byCssSelector('div.comments #form');
		$form->submit();
		usleep(500000);

		$row = $this->byCssSelector('div.comments table tr');
		$nameColumn = $row->byCssSelector('td');
		$commentColumn = $row->byCssSelector('td + td');

		$this->assertEquals('Jane', $nameColumn->text());
		$this->assertEquals('Anyone has extra racket?', $commentColumn->text());
	}

	public function testRecreateForm() {
		parent::loginToSite('jdoe@email.com', '12345');

		$createdDiv = $this->byCssSelector('.created');
		$viewDetailsLink = $createdDiv->byLinkText('View Details');
		$viewDetailsLink->click();

		$originalLink = $this->getBrowserUrl();

		$recreateButton = $this->byId('recreate');
		$recreateButton->click();
		usleep(500000);

		$pageTitleDiv = $this->byCssSelector('h2.page-header');
		$this->assertEquals('Recreate Event', $pageTitleDiv->text());

		$expectedLink = str_replace('view', 'recreate', $originalLink);
		$this->assertEquals($expectedLink, $this->getBrowserUrl());
	}

	public function testRecreateCancel() {
		parent::loginToSite('jdoe@email.com', '12345');

		$createdDiv = $this->byCssSelector('.created');
		$viewDetailsLink = $createdDiv->byLinkText('View Details');
		$viewDetailsLink->click();

		$originalLink = $this->getBrowserUrl();

		$recreateButton = $this->byId('recreate');
		$recreateButton->click();
		usleep(500000);

		$cancelButton = $this->byId('cancel');
		$cancelButton->click();
		usleep(500000);

		$recreateButton = $this->byId('recreate');
		$this->assertEquals('RECREATE', $recreateButton->text());

		$this->assertEquals($originalLink, $this->getBrowserUrl());
	}

}