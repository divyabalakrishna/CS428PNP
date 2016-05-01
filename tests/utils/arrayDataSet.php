<?php

/**
 * This class provides a PHPUnit dataset implementation from an array.
 */
class PHPUnit_ArrayDataSet extends PHPUnit_Extensions_Database_DataSet_AbstractDataSet {

	/**
	 * @var array An array of tables.
	 */
	protected $tables = array();

	/**
	 * Construct a new PHPUnit_ArrayDataSet object.
	 * @param array $data An array of data.
	 * @param True if the data array is a result of a PDO query, false otherwise.
	 */
	public function __construct(array $data, $pdo = false) {
		if ($pdo) {
			$tableName = 'Table';
			$columns = array();
			if (isset($data[0])) {
				$columns = array_keys(get_object_vars($data[0]));
			}

			$metaData = new PHPUnit_Extensions_Database_DataSet_DefaultTableMetaData($tableName, $columns);
			$table = new PHPUnit_Extensions_Database_DataSet_DefaultTable($metaData);

			foreach ($data as $row) {
				$table->addRow(get_object_vars($row));
			}
			$this->tables[$tableName] = $table;
		}
		else {
			foreach ($data as $tableName => $rows) {
				$columns = array();
				if (isset($rows[0])) {
					$columns = array_keys($rows[0]);
				}

				$metaData = new PHPUnit_Extensions_Database_DataSet_DefaultTableMetaData($tableName, $columns);
				$table = new PHPUnit_Extensions_Database_DataSet_DefaultTable($metaData);

				foreach ($rows as $row) {
					$table->addRow($row);
				}
				$this->tables[$tableName] = $table;
			}
		}
	}

	/**
	 * Create a table iterator object.
	 * @param string $reverse True to reverse the iterator order, false otherwise.
	 * @return PHPUnit_Extensions_Database_DataSet_DefaultTableIterator The newly created table iterator object.
	 */
	protected function createIterator($reverse = false) {
		return new PHPUnit_Extensions_Database_DataSet_DefaultTableIterator($this->tables, $reverse);
	}

	/**
	 * Get data for a particular table.
	 * @param string $tableName Table name.
	 * @return PHPUnit_Extensions_Database_DataSet_DefaultTable An object with the table data.
	 */
	public function getTable($tableName) {
		if (!isset($this->tables[$tableName])) {
			throw new InvalidArgumentException($tableName . ' is not a table in the current database.');
		}

		return $this->tables[$tableName];
	}

}