<?php

include_once(__DIR__ . '/modelTestCase.php');
include_once(__DIR__ . '/../../../src/application/models/resourceModel.php');

/**
 * This class provides unit tests for ResourceModel.
 */
class ResourceModelTest extends ModelTestCase {

	private static $resourceModel;

	/**
	 * Initialize required variables once for all tests.
	 */
	public static function setUpBeforeClass() {
		static::$resourceModel = new ResourceModel(parent::getPDO());
		parent::setGlobalVariables();
	}

	/**
	 * Test the normal usage for getTags function.
	 */
	public function testGetTags() {
		$expectedTable = (new PHPUnit_ArrayDataSet(static::$resourceModel->getTags(), true))->getTable('Table');

		$actualTable = $this->getConnection()->createQueryTable('Table', 'SELECT * FROM Tag ORDER BY Name');

		$this->assertTablesEqual($expectedTable, $actualTable);
	}

}