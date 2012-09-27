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
require_once 'ModuleCreatorService.php';
require_once 'OptionsCreatorService.php';

class ZendModelCreator {

	// Settings
	private $_settings = array();
	// Db connection
	private $_dbcon = null;
	// data
	private $_data = array();
	private $_files = array();

	// Version of ZMC2
	private static $_version = '0.0.1';
	// Generator string
	private static $_generator = 'Zend Model Creator 2, [https://github.com/hussfelt/Zend-Model-Creator-2]';
	// Namespace
	private static $_namespace = 'ZmcBase';

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
		if ($settings['namespace'] != null) {
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
	function fromCamelCase($str) {
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
					$type = ZendModelCreator::$INTEGER;
				} elseif(stristr($type, "date")) {
					$type = ZendModelCreator::$DATETIME;
				} elseif(stristr($type, "double")) {
					$type = ZendModelCreator::$DOUBLE;
				} else {
					// everything else
					$type = ZendModelCreator::$STRING;
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
		$createModule = false;
		$createAutoloaders = false;
		$createConfig = false;
		$createOptions = false;
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
							case "create_module":
								$createModule = true;
								break;
							case "create_autoloaders":
								$createAutoloaders = true;
								break;
							case "create_config":
								$createConfig = true;
								break;
							case "create_options":
								$createOptions = true;
								break;
							default:
								die("Settings not set correctly. [types]");
								break;
						}
					}
				}
			}
		}

		// Check if we want to create the module
		if ($createModule) {
			$moduleCreator = new ModuleCreatorService();
			$this->_files['module'] = $moduleCreator->createModule($table, self::$tables);
		}

		// Check if we want to create the options file
		if ($createOptions) {
			$optionsCreator = new OptionsCreatorService();
			$this->_files['options'] = $optionsCreator->createOptions($table);
		}

		// Check if we want to create the config
		if ($createConfig) {
			// Build config file
			$this->_files['config'] = "<?php\n";
			$this->_files['config'] .= "return array(\n";
			$this->_files['config'] .= "\t'service_manager' => array(\n";
			$this->_files['config'] .= "\t\t'aliases' => array(\n";
			$this->_files['config'] .= "\t\t\t'" . strtolower(self::getNamespace()) . "_zend_db_adapter' => 'Zend\Db\Adapter\Adapter',\n";
			$this->_files['config'] .= "\t\t),\n";
			$this->_files['config'] .= "\t),\n";
			$this->_files['config'] .= ");";
		}

		// Check if we want to create the options file
		if ($createConfig) {
			// Build config file
			$this->_files['config'] = "<?php\n";
			$this->_files['config'] .= "return array(\n";
			$this->_files['config'] .= "\t'service_manager' => array(\n";
			$this->_files['config'] .= "\t\t'aliases' => array(\n";
			$this->_files['config'] .= "\t\t\t'" . strtolower(self::getNamespace()) . "_zend_db_adapter' => 'Zend\Db\Adapter\Adapter',\n";
			$this->_files['config'] .= "\t\t),\n";
			$this->_files['config'] .= "\t),\n";
			$this->_files['config'] .= ");";
		}

		// Check if we want to create autoloaders
		if ($createAutoloaders) {
			// Build classmap file
			$this->_files['classmap'] = "<?php\n";
			$this->_files['classmap'] .= "return array(\n";
			$this->_files['classmap'] .= "\t'" . ZendModelCreator::getNamespace() . "\Module' => __DIR__ . '/Module.php',\n";
			$this->_files['classmap'] .= ");";

			// Build function file
			$this->_files['function'] = "<?php\n";
			$this->_files['function'] .= "return function (\$class) {\n";
			$this->_files['function'] .= "\tstatic \$map;\n";
			$this->_files['function'] .= "\tif (!\$map) {\n";
			$this->_files['function'] .= "\t\t\$map = include __DIR__ . '/autoload_classmap.php';\n";
			$this->_files['function'] .= "\t}\n";
			$this->_files['function'] .= "\tif (!isset(\$map[\$class])) {\n";
			$this->_files['function'] .= "\t\treturn false;\n";
			$this->_files['function'] .= "\t}\n";
			$this->_files['function'] .= "\treturn include \$map[\$class];\n";
			$this->_files['function'] .= "};\n";

			// Build register file
			$this->_files['register'] = "<?php\n";
			$this->_files['register'] .= "spl_autoload_register(include __DIR__ . '/autoload_function.php');";
		}
	}

	/**
	* Write the data to files
	*/
	public function writePHPCreatedModelData() {
		// Set DS to local variable for simpler use
		$DS = self::$_DS;

		// Check if the container directory exists, else create it
		if(!is_dir(self::$_namespace) && !mkdir(self::$_namespace)) {
			die("Can't create dir: " . self::$_namespace);
		}

		// Check if the src directory exists, else create it
		if(!is_dir(self::$_namespace . $DS . 'src') && !mkdir(self::$_namespace . $DS . 'src')) {
			die("Can't create dir: " . self::$_namespace . $DS . 'src');
		}

		// Check if the config directory exists, else create it
		if(!is_dir(self::$_namespace . $DS . 'config') && !mkdir(self::$_namespace . $DS . 'config')) {
			die("Can't create dir: " . self::$_namespace . $DS . 'config');
		}

		// Check if the namespace directory exists, else create it
		if(!is_dir(self::$_namespace . $DS . 'src' . $DS . self::$_namespace) && !mkdir(self::$_namespace . $DS . 'src' . $DS . self::$_namespace)) {
			die("Can't create dir: " . self::$_namespace . $DS . 'src' . $DS . self::$_namespace);
		}

		// Get the types to create
		$types = $this->getSetting('types');
		$createModule = false;
		$createAutoloaders = false;
		$createConfig = false;
		$createOptions = false;

		// Loop through the table data and create files	
		foreach ($this->_data as $table => $data) {
			// If user specifies that entetie files should be created, do it
			if($types['create_entity']) {
				$entityFileName = self::toCamelCase(ucfirst(strtolower($table)));
				// Check if the entity directory exists, else create it
				if(!is_dir(self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Entity') && !mkdir(self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Entity')) {
					die("Can't create dir: " . self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Entity');
				}

				// Check if we can create the entity file
				if (!$handle = fopen(self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Entity' . $DS . $entityFileName . '.php', 'w+')) {
					die("Cannot open/create file: " . self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Entity' . $DS . $entityFileName . '.php');
				}

				// Write contents to the Entity file
				if (fwrite($handle, $data['entity']) === FALSE) {
					die("Cannot write to file: " . self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Entity' . $DS . $entityFileName . '.php');
				}

				// Close this handle
				fclose($handle);
			}

			// If user specifies that mapper files should be created, do it
			if($types['create_mapper']) {
				$mapperFileName = self::toCamelCase(ucfirst(strtolower($table))) . "Mapper";
				// Check if the mapper directory exists, else create it
				if(!is_dir(self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Mapper') && !mkdir(self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Mapper')) {
					die("Can't create dir: " . self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Mapper');
				}

				// Check if we can create the mapper file
				if (!$handle = fopen(self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Mapper' . $DS . $mapperFileName . '.php', 'w+')) {
					die("Cannot open/create file: " . self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Mapper' . $DS . $mapperFileName . '.php');
				}

				// Write contents to the Mapper file
				if (fwrite($handle, $data['mapper']) === FALSE) {
					die("Cannot write to file: " . self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Mapper' . $DS . $mapperFileName . '.php');
				}

				// Close this handle
				fclose($handle);
			}

			// If user specifies that service files should be created, do it
			if($types['create_service']) {
				$mapperFileName = self::toCamelCase(ucfirst(strtolower($table)));
				// Check if the service directory exists, else create it
				if(!is_dir(self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Service') && !mkdir(self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Service')) {
					die("Can't create dir: " . self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Service');
				}

				// Check if we can create the service file
				if (!$handle = fopen(self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Service' . $DS . $mapperFileName . '.php', 'w+')) {
					die("Cannot open/create file: " . self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Service' . $DS . $mapperFileName . '.php');
				}

				// Write contents to the Service file
				if (fwrite($handle, $data['service']) === FALSE) {
					die("Cannot write to file: " . self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Service' . $DS . $mapperFileName . '.php');
				}

				// Close this handle
				fclose($handle);

				// Check if we can create the event file
				if (!$handle = fopen(self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Service' . $DS . $mapperFileName . 'Event.php', 'w+')) {
					die("Cannot open/create file: " . self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Service' . $DS . $mapperFileName . 'Event.php');
				}

				// Write contents to the Service file
				if (fwrite($handle, $data['service_event']) === FALSE) {
					die("Cannot write to file: " . self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Service' . $DS . $mapperFileName . 'Event.php');
				}

				// Close this handle
				fclose($handle);
			}

			// Add true to create module if we want to do this
			if($types['create_module']) {
				$createModule = true;
			}

			// Add true to create config if we want to do this
			if($types['create_options']) {
				$createOptions = true;
			}

			// Add true to create autoloaders if we want to do this
			if($types['create_autoloaders']) {
				$createAutoloaders = true;
			}

			// Add true to create config if we want to do this
			if($types['create_config']) {
				$createConfig = true;
			}
		}

		// If user specifies that Module file should be created, do it
		if($createModule) {
			// Check if we can create the module file
			if (!$handle = fopen(self::$_namespace . $DS . 'Module.php', 'w+')) {
				die("Cannot open/create file: " . self::$_namespace . $DS . 'Module.php');
			}

			// Write contents to the Module file
			if (fwrite($handle, $this->_files['module']) === FALSE) {
				die("Cannot write to file: " . self::$_namespace . $DS . 'Module.php');
			}

			// Close this handle
			fclose($handle);
		}

		// If user specifies that autloader files should be created, do it
		if($createAutoloaders) {
			// Check if we can create the autoload_classmap file
			if (!$handle = fopen(self::$_namespace . $DS . 'autoload_classmap.php', 'w+')) {
				die("Cannot open/create file: " . self::$_namespace . $DS . 'autoload_classmap.php');
			}

			// Write contents to the autoload_classmap file
			if (fwrite($handle, $this->_files['classmap']) === FALSE) {
				die("Cannot write to file: " . self::$_namespace . $DS . 'autoload_classmap.php');
			}

			// Close this handle
			fclose($handle);

			// Check if we can create the autoload_function file
			if (!$handle = fopen(self::$_namespace . $DS . 'autoload_function.php', 'w+')) {
				die("Cannot open/create file: " . self::$_namespace . $DS . 'autoload_function.php');
			}

			// Write contents to the autoload_function file
			if (fwrite($handle, $this->_files['function']) === FALSE) {
				die("Cannot write to file: " . self::$_namespace . $DS . 'autoload_function.php');
			}

			// Check if we can create the autoload_register file
			if (!$handle = fopen(self::$_namespace . $DS . 'autoload_register.php', 'w+')) {
				die("Cannot open/create file: " . self::$_namespace . $DS . 'autoload_register.php');
			}

			// Write contents to the autoload_register file
			if (fwrite($handle, $this->_files['register']) === FALSE) {
				die("Cannot write to file: " . self::$_namespace . $DS . 'autoload_register.php');
			}

			// Close this handle
			fclose($handle);
		}

		// If user specifies that config file should be created, do it
		if($createConfig) {
			// Check if we can create the config file
			if (!$handle = fopen(self::$_namespace . $DS . 'config' . $DS . 'module.config.php', 'w+')) {
				die("Cannot open/create file: " . self::$_namespace . $DS . 'config' . $DS . 'module.config.php');
			}

			// Write contents to the config file
			if (fwrite($handle, $this->_files['config']) === FALSE) {
				die("Cannot write to file: " . self::$_namespace . $DS . 'config' . $DS . 'module.config.php');
			}

			// Close this handle
			fclose($handle);
		}



		// If user specifies that options file should be created, do it
		if($createOptions) {
			// Check if the options directory exists, else create it
			if(!is_dir(self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Options') && !mkdir(self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Options')) {
				die("Can't create dir: " . self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Options');
			}

			// Check if we can create the options file
			if (!$handle = fopen(self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Options' . $DS . 'ModuleOptions.php', 'w+')) {
				die("Cannot open/create file: " . self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Options' . $DS . 'ModuleOptions.php');
			}

			// Write contents to the options file
			if (fwrite($handle, $this->_files['options']) === FALSE) {
				die("Cannot write to file: " . self::$_namespace . $DS . 'src' . $DS . self::$_namespace . $DS . 'Options' . $DS . 'ModuleOptions.php');
			}

			// Close this handle
			fclose($handle);
		}

		// Check if the namespace directory exists, else create it
		if(!is_dir(self::$_namespace . $DS . 'src' . $DS . self::$_namespace) && !mkdir(self::$_namespace . $DS . 'src' . $DS . self::$_namespace)) {
			die("Can't create dir: " . self::$_namespace . $DS . 'src' . $DS . self::$_namespace);
		}

		return "Files created successfully";
	}
}
?>
