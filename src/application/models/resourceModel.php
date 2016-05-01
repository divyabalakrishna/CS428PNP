<?php

/**
 * This class handles database interaction for resources.
 */
class ResourceModel extends Model {

	/**
	 * Retrieve all tag records.
	 * @return array Query result.
	 */
	public function getTags() {
		$sql = "SELECT *
				FROM Tag
				ORDER BY Name";

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql);
	}

}