<?php
/**
* Code generator for Entity class
*
* @author Henrik Hussfelt
* @copyright	Copyright (C) 2012 Hussfelt Consulting AB. All rights reserved.
* @license		SEE LICENCE
*
**/

class EntityCreatorService {

    private $_data = '';
    private $_primary_key = '';

    public static $STRING = 'string';
    public static $INTEGER = 'integer';
    public static $DATETIME = 'datetime';
    public static $DATE = 'date';
	public static $ARRAY = 'array';
	public static $DOUBLE = 'double';

	/**
	 * Generates an Entity class
	 *
	 * @return string formatted code ready to use
	 *
	 */
	public function createEntity($className, $parameterArray) {
		$this->_setPrimaryKey($parameterArray['primary_key']);
		$this->_generateClassHeader($className);
		$this->_generateClassDeclarations($parameterArray['fields']);
		$this->_generateClassGettersSetters($parameterArray['fields']);
		$this->_generateClassFooter();
		return $this->_data;
	}

	/**
	* Set primary key
	*/
	private function _setPrimaryKey($primary_key) {
		$this->_primary_key = $primary_key;
	}

	/**
	 * Generates header for entity class
	 *
	 */
	private function _generateClassHeader($className) {
		$this->_data .= "<?php
/**
* file: " . ucfirst(strtolower($className)) . ".php
* " . ucfirst(strtolower($className)) . " entity
*
* @author ".ZendModelCreator::getGenerator()."
* @version ".ZendModelCreator::getVersion()."
* @package ".ZendModelCreator::getNamespace()."
* @since " . date("Y-m-d") . "
* @package ".ZendModelCreator::getNamespace()."
*/

namespace ".ZendModelCreator::getNamespace()."\Entity;

/**
* " . ucfirst(strtolower($className)) . "
*
* @author ".ZendModelCreator::getGenerator()."
* @version ".ZendModelCreator::getVersion()."
* @package ".ZendModelCreator::getNamespace()."
* @since " . date("Y-m-d") . "
*
**/
class " . ucfirst(strtolower($className)) . "
{
";
	}

	/**
	 * Creates variables used in entity class
	 *
	 */
	private function _generateClassDeclarations($params) {
		foreach ($params as $param) {
			foreach ($param as $name => $data) {
				switch ($data[0]) {
					case self::$STRING:
						$this->_data .= "\t/**\n";
						$this->_data .= "\t* @var string". "\n";
						$this->_data .= "\t**/\n";
						$this->_data .= "\tprotected \$" . $name . " = '" . $data[1] . "';\n";
						break;
					case self::$INTEGER:
						$this->_data .= "\t/**\n";
						$this->_data .= "\t* @var int". "\n";
						$this->_data .= "\t**/\n";
						$intData = ($name == $this->_primary_key ? '' : " = " . ($data[1] != "" ? $data[1] : 0) );
						$this->_data .= "\tprotected \$" . $name . $intData . ";\n";
						break;
					case self::$DATETIME:
						$this->_data .= "\t/**\n";
						$this->_data .= "\t* @var string". "\n";
						$this->_data .= "\t**/\n";
						$this->_data .= "\tprotected \$" . $name . " = '" . ($data[1] != "" ? $data[1] : '0000-00-00 00:00:00') . "';\n";
						break;
					case self::$DATE:
						$this->_data .= "\t/**\n";
						$this->_data .= "\t* @var string". "\n";
						$this->_data .= "\t**/\n";
						$this->_data .= "\tprotected \$" . $name . " = '" . ($data[1] != "" ? $data[1] : '0000-00-00') . "';\n";
						break;
					case self::$DOUBLE:
						$this->_data .= "\t/**\n";
						$this->_data .= "\t* @var float". "\n";
						$this->_data .= "\t**/\n";
						$this->_data .= "\tprotected \$" . $name . " = " . ($data[1] != "" ? $data[1] : 0) . ";\n";
						break;
					case self::$ARRAY:
						$this->_data .= "\t/**\n";
						$this->_data .= "\t* @var array". "\n";
						$this->_data .= "\t**/\n";
						$this->_data .= "\tprotected \$" . $name . " = array();\n";
						break;
					default:
						break;
				}
			}
		}
		$this->_data .= "\n";
	}

	/**
	* Generate comment for get method
	*
	*/
	private function _getGetComment($type, $name) {
		$data = "\t/**\n";
		$data .= "\t* Gets the $name property\n";
		$data .= "\t* @return $type the $name\n";
		$data .= "\t*/\n";
		return $data;
	}

	/**
	* Generate comment for set method
	*
	*/
	private function _getSetComment($type, $name) {
		$data = "\t/**\n";
		$data .= "\t* Sets the $name property\n";
		$data .= "\t* @param $type the $name to set\n";
		$data .= "\t* @return void\n";
		$data .= "\t*/\n";
		return $data;
	}

	/**
	* Generate getters and setters used in the entity class
	*
	*/
	private function _generateClassGettersSetters($params) {
		foreach ($params as $param) {
			foreach ($param as $name => $type) {
				switch ($type[0]) {
					case self::$STRING:
						$this->_data .= $this->_getGetComment($type[0], $name);
						$this->_data .= "\tpublic function get".ZendModelCreator::toCamelCase($name)."()\n";
						$this->_data .= "\t{\n";
						$this->_data .= "\t\treturn \$this->$name;";
						$this->_data .= "\n\t}\n\n";

						$this->_data .= $this->_getSetComment($type[0], $name);
						$this->_data .= "\tpublic function set".ZendModelCreator::toCamelCase($name)."(\$$name)\n";
						$this->_data .= "\t{\n";
						$this->_data .= "\t\t\$this->$name = \$$name;";
						$this->_data .= "\n\t}\n\n";
						break;

					case self::$INTEGER:
						$this->_data .= $this->_getGetComment($type[0], $name);
						$this->_data .= "\tpublic function get".ZendModelCreator::toCamelCase($name)."()\n";
						$this->_data .= "\t{\n";
						$this->_data .= "\t\treturn \$this->$name;";
						$this->_data .= "\n\t}\n\n";

						$this->_data .= $this->_getSetComment($type[0], $name);
						$this->_data .= "\tpublic function set".ZendModelCreator::toCamelCase($name)."(\$$name)\n";
						$this->_data .= "\t{\n";
						$this->_data .= "\t\t\$this->$name = \$$name;";
						$this->_data .= "\n\t}\n\n";
						break;

					case self::$DATETIME:
						$this->_data .= $this->_getGetComment($type[0], $name);
						$this->_data .= "\tpublic function get".ZendModelCreator::toCamelCase($name)."()\n";
						$this->_data .= "\t{\n";
						$this->_data .= "\t\treturn \$this->$name;";
						$this->_data .= "\n\t}\n\n";

						$this->_data .= $this->_getSetComment($type[0], $name);
						$this->_data .= "\tpublic function set".ZendModelCreator::toCamelCase($name)."(\$$name)\n";
						$this->_data .= "\t{\n";
						$this->_data .= "\t\t\$this->$name = \$$name;";
						$this->_data .= "\n\t}\n\n";
						break;

					case self::$DOUBLE:
						$this->_data .= $this->_getGetComment($type[0], $name);
						$this->_data .= "\tpublic function get".ZendModelCreator::toCamelCase($name)."()\n";
						$this->_data .= "\t{\n";
						$this->_data .= "\t\treturn \$this->$name;";
						$this->_data .= "\n\t}\n\n";

						$this->_data .= $this->_getSetComment($type[0], $name);
						$this->_data .= "\tpublic function set".ZendModelCreator::toCamelCase($name)."(\$$name)\n";
						$this->_data .= "\t{\n";
						$this->_data .= "\t\t\$this->$name = \$$name;";
						$this->_data .= "\n\t}\n\n";
						break;

					case self::$ARRAY:
						$this->_data .= $this->_getGetComment($type[0], $name);
						$this->_data .= "\tpublic function get".ZendModelCreator::toCamelCase($name)."()\n";
						$this->_data .= "\t{\n";
						$this->_data .= "\t\treturn \$this->$name;";
						$this->_data .= "\n\t}\n\n";

						$this->_data .= $this->_getSetComment($type[0], $name);
						$this->_data .= "\tpublic function set".ZendModelCreator::toCamelCase($name)."(array \$$name)\n";
						$this->_data .= "\t{\n";
						$this->_data .= "\t\t\$this->$name = \$$name;";
						$this->_data .= "\n\t}\n\n";
						break;

				}
			}
		}
	}

	/**
	 * Generates a footer
	 *
	 */
	private function _generateClassFooter() {
		$this->_data .= "\n}";
	}
}