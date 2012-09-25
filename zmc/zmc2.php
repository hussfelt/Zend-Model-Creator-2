<?php
/**
* @package		ZendModelCreator 2
* @copyright	Copyright (C) 2012 Hussfelt Consulting AB. All rights reserved.
* @license		SEE LICENCE
*
**/

require_once 'EntityCreatorService.php';
require_once 'MapperCreatorService.php';
require_once 'ServiceCreatorService.php';
require_once 'EventCreatorService.php';

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
	// Namespace
	private static $_namespace = 'Zmc';

	// Directory separator
	public static $_DS = '/';
	// Tables
	public static $tables = array();

	// MySQL Types
    public static $STRING = 'string';
    public static $INTEGER = 'integer';
    public static $DATETIME = 'datetime';
    public static $DATE = 'date';
	public static $ARRAY = 'array';
	public static $DOUBLE = 'double';

	/**
	*	Zend Model Creator 2
	*/
	public function __construct($settings){
		// Set settings
		$this->_settings = $settings;

		// Set the namespace, if it should change
		if ($settings['namespace'] != '') {
			self::$_namespace = $settings['namespace'];
		}

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
	}

	/**
	* Translates a camel case string into a string with underscores (e.g. firstName -&gt; first_name)
	* @param 	string $str String in camel case format
	* @return 	string $str Translated into underscore format
	*/
	function from_camel_case($str) {
		$str[0] = strtolower($str[0]);
		$func = create_function('$c', 'return "_" . strtolower($c[1]);');
		return preg_replace_callback('/([A-Z])/', $func, $str);
	}

	/**
	* Translates a string with underscores into camel case (e.g. first_name -&gt; firstName)
	* @param    string 	$str String in underscore format
	* @param    bool 	$capitalise_first_char If true, capitalise the first char in $str
	* @return   string 	$str translated into camel caps
	*/
	public static function toCamelCase($str, $capitalise_first_char = true) {
		if($capitalise_first_char) {
			$str[0] = strtoupper($str[0]);
		}
		$func = create_function('$c', 'return strtoupper($c[1]);');
		return preg_replace_callback('/_([a-z])/', $func, $str);
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
	* Get generator string
	*/
	public static function getNamespace() {
		return self::$_namespace;
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

	/**
	* Get generated data from our services
	*/
	public function getDataFromServices() {
		foreach (self::$tables as $table => $data) {
			// clean interface array
			foreach ($this->getSetting('types') as $type => $get_data) {
				if($get_data) {
					// Quit if no primary key is set.
					if (isset($data['primary_key'])) {
						// set object names to Ucfirst then lowercase.
						$table = ucfirst(strtolower($table));
						switch ($type) {
							case "create_entity":
								$EntityService = new EntityCreatorService();
								$this->_data[$table]['entity'] = $EntityService->createEntity($table, $data);
								break;
							case "create_mapper":
								$MapperService = new MapperCreatorService();
								$this->_data[$table]['mapper'] = $MapperService->createMapper($table, $data);
								break;
							case "create_service":
								$serviceCreator = new ServiceCreatorService();
								$this->_data[$table]['service'] = $serviceCreator->createService($table, $data);
								$EventService = new EventCreatorService();
								$this->_data[$table]['service_event'] = $EventService->createEventService($table, $data);
								break;
							default:
								die("Settings not set correctly. [types]");
								break;
						}
					}
				}
			}
		}
	}
}
?>
