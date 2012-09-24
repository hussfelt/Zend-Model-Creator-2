<?php
/**
* Code generator for Entity class
*
* @author HHenrik Hussfelt
* @copyright	Copyright (C) 2012 Hussfelt Consulting AB. All rights reserved.
* @license		SEE LICENCE
*
**/

class EntityCreatorService {

    private $_data = '';

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
		$this->_generateClassHeader($className);
		$this->_generateClassDeclarations($parameterArray['fields']);
		//$this->_generateClassGettersSetters($parameterArray['fields']);
		$this->_generateClassFooter();
		return $this->_data;
	}

	/**
	 * Generates header for entity class
	 *
	 */
	private function _generateClassHeader($className) {
		$this->_data .= "<?php
/**
* file: $className.php
* $className entity
*
* @author ".ZendModelCreator2::getGenerator()."
* @version ".ZendModelCreator2::getVersion()."
* @package ".ZendModelCreator2::getNamespace()."
* @since " . date("Y-m-d") . "
* @package ".ZendModelCreator2::getNamespace()."
*/

namespace ".ZendModelCreator2::getNamespace()."\Entity;

/**
* $className
*
*
* @author ".ZendModelCreator2::getGenerator()."
* @version ".ZendModelCreator2::getVersion()."
* @package ".ZendModelCreator2::getNamespace()."
* @since " . date("Y-m-d") . "
*
**/
class $className {
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
						$this->_data .= "\tprotected \$" . $name . " = " . ($data[1] != "" ? $data[1] : 0) . ";\n";
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
	 * Generates a footer
	 *
	 */
	private function _generateClassFooter() {
		$this->_data .= "\n}";
	}
}