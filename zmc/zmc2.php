<?php
/**
* @package		ZendModelCreator 2
* @copyright	Copyright (C) 2012 Hussfelt Consulting AB. All rights reserved.
* @license		SEE LICENCE
*
**/

//require_once 'DTOCreatorService.php';

class ZendModelCreator2 {

	// Settings
	private $_settings = array();
	// Db connection
	private $_dbcon = null;
	// data
	private $_data = array();

	// Version of ZMC2
	private static $_version = '0.5';
	// Generator string
	private static $_generator = 'Zend Model Creator 2, [https://github.com/hussfelt/Zend-Model-Creator-2]';

	// Directory separator
	public static $_DS = '/';
	// Tables
	public static $tables = array();

	// MySQL Types
    public static $STRING = 'string';
    public static $INTEGER = 'integer';
    public static $DATETIME = 'datetime';
	public static $ARRAY = 'array';
	public static $DOUBLE = 'double';

	/**
	*	Zend Model Creator 2
	*/
	public function __construct($settings){
		// Set settings
		$this->_settings = $settings;

		// Open db connection
		if (!$this->_dbcon = mysql_connect($this->getSetting('mysql_host'),$this->getSetting('mysql_user'),$this->getSetting('mysql_password'))) {
			die("Can't connect to database");
		}
		if (!mysql_select_db($this->getSetting('mysql_db'), $this->_dbcon)) {
			die("Can't select database");
		}

		// set tables
		$this->_setTables();
		$this->_setTableData();

		// close connection
		mysql_close($this->_dbcon);
		echo "OK";
		print_r(self::$tables);
		exit;
	}

	/**
	* Get a setting
	* @param string $key of setting to return
	*/
	public function getSetting($key) {
		// if setting is not set, die
		if(!isset($this->_settings[$key])) {
			die("Settings not set correctly. [global]");
		}
		return $this->_settings[$key];
	}

	/**
	* Get the current version string
	*/
	public static function getVersion() {
		return self::$_version;
	}

	/**
	* Get generator string
	*/
	public static function getGenerator() {
		return self::$_generator;
	}

	/**
	* Set tables to work with
	*/
	private function _setTables(){
		// show all tables in selected database
		$result = mysql_query("SHOW tables", $this->_dbcon);
		while($row = mysql_fetch_row($result)) {
			self::$tables[$row[0]] = array();
		}
	}

	/**
	* Set table data to work with
	*/
	private function _setTableData(){
		foreach (self::$tables as $tbl => $junk) {
			$this->devnull = $junk;
			$result = mysql_query("DESCRIBE ".$tbl, $this->_dbcon);

			while($row = mysql_fetch_row($result)) {
				$name = $row[0];
				$type = $row[1];
				$default_value = $row[4];

				// if the fourth description is PRI, this is a primary key and is
				// pushed to self::$table[$tbl]['primary_key']
				if($row[3] == "PRI") {
					self::$tables[$tbl]['primary_key'] = $name;
				}

				// get the datetype of the column and set a proper DTO type
				if(stristr($type, "int")) {
					$type = ZendModelCreator2::$INTEGER;
				} elseif(stristr($type, "date")) {
					$type = ZendModelCreator2::$DATETIME;
				} elseif(stristr($type, "double")) {
					$type = ZendModelCreator2::$DOUBLE;
				} else {
					// everything else
					$type = ZendModelCreator2::$STRING;
				}

				// set to global table
				$final = array($name => array($type, $default_value));
				self::$tables[$tbl]['fields'][] = $final;
			}
		}
	}
}
?>
