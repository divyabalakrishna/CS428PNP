<?php

/**
 * This class provides utility functions for database queries.
 */
class QueryHelper {

	/**
	 * Execute a read query and get a single result.
	 * @param PDO $db A PDO database connection. 
	 * @param string $sql SQL statement.
	 * @param array $parameters Parameter values.
	 * @return stdClass Query result. If the query does not return anything, this function will return an object where the properties are empty string.
	 */
	public function getSingleRowObject($db, $sql, $parameters) {
		$query = $db->prepare($sql);
		$query->execute($parameters);
		$result = $query->fetch();

		if (!$result) {
			// Convert result to an object
			$result = new stdClass();
			for ($i = 0; $i < $query->columnCount(); $i++) {
				$columnMeta = $query->getColumnMeta($i);
				$result->{$columnMeta["name"]} = "";
			}
		}

		return $result;
	}

	/**
	 * Execute a write query.
	 * @param PDO $db A PDO database connection.
	 * @param string $sql SQL statement.
	 * @param array $parameters Parameter values.
	 * @return integer|void Record ID if the query is an insert query, no return value otherwise.
	 */
	public function executeWriteQuery($db, $sql, $parameters) {
		foreach ($parameters as $parameterKey => $parameterValue) {
			if (!is_numeric($parameterValue) && $parameterValue == "") {
				$parameters[$parameterKey] = null;
			}
		}

		$query = $db->prepare($sql);
		$query->execute($parameters);

		if (strcasecmp($GLOBALS["beans"]->stringHelper->left($sql, 6), "INSERT") == 0) {
			return $db->lastInsertId();
		}
	}

	/**
	 * Execute a read query and get all results.
	 * @param PDO $db A PDO database connection.
	 * @param string $sql SQL statement.
	 * @param array $parameters Parameter values.
	 * @return array Query result.
	 */
	public function getAllRows($db, $sql, $parameters = "") {
		$query = $db->prepare($sql);

		if ($parameters == "") {
			$query->execute();
		}
		else {
			$query->execute($parameters);
		}

		return $query->fetchAll();
	}

}