<?php

include_once(__DIR__ . '/../../utils/dbTestCase.php');
include_once(__DIR__ . '/../../../src/application/helpers/queryHelper.php');
include_once(__DIR__ . '/../../../src/application/helpers/stringHelper.php');
include_once(__DIR__ . '/../../../src/application/core/model.php');

abstract class ModelTestCase extends DBTestCase
{

	public static function setGlobalVariables() {
		$GLOBALS['beans'] = new stdClass();
		$GLOBALS['beans']->queryHelper = new QueryHelper();
		$GLOBALS['beans']->stringHelper = new StringHelper();
	}

}