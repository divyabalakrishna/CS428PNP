<?php

include_once (__DIR__ . '/viewTestCase.php');

/**
 * This class provides Selenium tests for View Event page.
 */
class EventViewTest extends ViewTestCase {

	/**
	 * Test action buttons display when the user is the event host.
	 */
	public function testViewHostedEvent() {
		parent::loginToSite('jdoe@email.com', '12345');

		$hostedButton = $this->byId('hosted');
		$hostedButton->click();
		usleep(500000);

		$eventDiv = $this->byCssSelector('div.tiles');
		$eventDiv->click();

		$editButton = $this->byId('edit');
		$this->assertEquals('EDIT', $editButton->text());

		$deleteButton = $this->byId('delete');
		$this->assertEquals('DELETE', $deleteButton->text());

		$this->assertNotExists('recreate', 'Recreate button');
		$this->assertNotExists('join', 'Join button');
		$this->assertNotExists('leave', 'Leave button');
	}

	/**
	 * Test action buttons display when the user is a participant.
	 */
	public function testViewJoinedEvent() {
		parent::loginToSite('jsmith@email.com', 'abcde');

		$joinedButton = $this->byId('joined');
		$joinedButton->click();
		usleep(500000);

		$eventDiv = $this->byCssSelector('div.tiles');
		$eventDiv->click();

		$editButton = $this->byId('leave');
		$this->assertEquals('LEAVE', $editButton->text());

		$this->assertNotExists('edit', 'Edit button');
		$this->assertNotExists('delete', 'Delete button');
		$this->assertNotExists('recreate', 'Recreate button');
		$this->assertNotExists('join', 'Join button');
	}

	/**
	 * Test action buttons display when the user is neither the event host nor a participant.
	 */
	public function testViewOtherEvent() {
		parent::loginToSite('joe@email.com', 'password');

		$eventDiv = $this->byCssSelector('div.tiles');
		$eventDiv->click();

		$editButton = $this->byId('join');
		$this->assertEquals('JOIN', $editButton->text());

		$this->assertNotExists('edit', 'Edit button');
		$this->assertNotExists('delete', 'Delete button');
		$this->assertNotExists('recreate', 'Recreate button');
		$this->assertNotExists('leave', 'Leave button');
	}

	/**
	 * Test adding a comment.
	 */
	public function testInsertComment() {
		parent::loginToSite('jdoe@email.com', '12345');

		$hostedButton = $this->byId('hosted');
		$hostedButton->click();
		usleep(500000);

		$eventDiv = $this->byCssSelector('div.tiles');
		$eventDiv->click();

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

	/**
	 * Test clicking on the recreate button.
	 */
	public function testRecreateForm() {
		parent::loginToSite('jdoe@email.com', '12345');

		$pastButton = $this->byId('past');
		$pastButton->click();
		usleep(500000);

		$eventDiv = $this->byCssSelector('div.tiles');
		$eventDiv->click();

		$originalLink = $this->getBrowserUrl();

		$recreateButton = $this->byId('recreate');
		$recreateButton->click();
		usleep(500000);

		$pageTitleDiv = $this->byCssSelector('h2.page-header');
		$this->assertEquals('Recreate Event', $pageTitleDiv->text());

		$expectedLink = str_replace('view', 'recreate', $originalLink);
		$this->assertEquals($expectedLink, $this->getBrowserUrl());
	}

	/**
	 * Test clicking on the cancel button on the recreate event page.
	 */
	public function testRecreateCancel() {
		parent::loginToSite('jdoe@email.com', '12345');

		$pastButton = $this->byId('past');
		$pastButton->click();
		usleep(500000);

		$eventDiv = $this->byCssSelector('div.tiles');
		$eventDiv->click();

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