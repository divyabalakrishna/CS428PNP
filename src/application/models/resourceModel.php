<?php

class ResourceModel extends Model {

	public function getTags() {
		$sql = "SELECT *
				FROM Tag
				ORDER BY Name";

		return $GLOBALS["beans"]->queryHelper->getAllRows($this->db, $sql);
	}

}