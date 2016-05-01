<?php

/**
 * This is as a base class for all model classes.
 */
class Model {

	/**
	 * Construct a new Model object.
	 * @param object $db A PDO database connection.
	 */
	function __construct($db) {
		$this->db = $db;
	}

}