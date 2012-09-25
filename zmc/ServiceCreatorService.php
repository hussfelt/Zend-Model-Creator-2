<?php
/**
* Code generator for Service class
*
* @author 		Henrik Hussfelt
* @copyright	Copyright (C) 2012 Hussfelt Consulting AB. All rights reserved.
* @license		SEE LICENCE
*
**/

class ServiceCreatorService {

    private $_data = '';

    public static $STRING = 'string';
    public static $INTEGER = 'integer';
    public static $DATETIME = 'datetime';
    public static $DATE = 'date';
	public static $ARRAY = 'array';
	public static $DOUBLE = 'double';
	private $_primary_key = '';

	/**
	 * Generates an Service class
	 *
	 * @return string formatted code ready to use
	 *
	 */
	public function createService($className, $parameterArray) {
		$this->_setPrimaryKey($parameterArray['primary_key']);
		$this->_generateClassHeader($className);
		$this->_generateConstructor($className);
		$this->_generateFindById($className);
		$this->_generateFindAll($className);
		$this->_generateCreate($className);
		$this->_generateUpdate($className);
		$this->_generateDelete($className);
		$this->_generateClassFooter();
		print_r($this->_data);exit;
		return $this->_data;
	}

	/**
	* Set primary key
	*/
	private function _setPrimaryKey($primary_key) {
		$this->_primary_key = $primary_key;
	}

	/**
	 * Generates header for Mapper class
	 *
	 */
	private function _generateClassHeader($className) {
		$this->_data .= "<?php
/**
* file: $className.php
* $className Service
*
* @author ".ZendModelCreator2::getGenerator()."
* @version ".ZendModelCreator2::getVersion()."
* @package ".ZendModelCreator2::getNamespace()."
* @since " . date("Y-m-d") . "
* @package ".ZendModelCreator2::getNamespace()."
*/

namespace ".ZendModelCreator2::getNamespace()."\Mapper;

use ArrayObject;

use ".ZendModelCreator2::getNamespace()."\Entity\\" . $className . ";

namespace ".ZendModelCreator2::getNamespace()."\Service;

use ".ZendModelCreator2::getNamespace()."\Entity\\" . $className . " as " . $className . "Entity;
use ".ZendModelCreator2::getNamespace()."\Service\\" . $className . "Event;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
* " . $className . "
*
* @author ".ZendModelCreator2::getGenerator()."
* @version ".ZendModelCreator2::getVersion()."
* @package ".ZendModelCreator2::getNamespace()."
* @since " . date("Y-m-d") . "
*
**/
class " . $className . " implements EventManagerAwareInterface
{
    protected $mapper;
    protected $options;
";
	}

	/**
	* Generate a constructor
	*
	*/
	private function _generateConstructor($className) {
		$this->_data.="\t/**\n";
		$this->_data.="\t* " . $className . "Service constructor\n";
		$this->_data.="\t*\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function __construct()\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t\// Create event manager instance\n";
		$this->_data.="\t\t\$this->setEventManager(new EventManager());\n";
		$this->_data.="\t}\n\n";        
	}

	/**
	 * Generates findById
	 *
	 */
	private function _generateFindById($className) {
		// Set fetch function headers
		$this->_data.="\t/**\n";
		$this->_data.="\t* Find a record by id\n";
		$this->_data.="\t*\n";
		$this->_data.="\t* @param int \$id\n";
		$this->_data.="\t* @return object ".ZendModelCreator2::getNamespace()."\Entity\\" . ucfirst(strtolower($className)) . "\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function findById(\$id)\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t// Call Mapper and return a record by id\n";
		$this->_data.="\t\treturn \$this->mapper->findById(\$id);\n";
		$this->_data.="\t}\n\n";

        
	}


	/**
	 * Generates findAll
	 *
	 */
	private function _generateFindAll($className) {
		// Set fetch function headers
		$this->_data.="\t/**\n";
		$this->_data.="\t* Find all records\n";
		$this->_data.="\t*\n";
		$this->_data.="\t* @return array objects ".ZendModelCreator2::getNamespace()."\Entity\\" . ucfirst(strtolower($className)) . "\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function findAll()\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t// Call Mapper and return all records\n";
		$this->_data.="\t\treturn \$this->mapper->findAll(\$id);\n";
		$this->_data.="\t}\n\n";
	}

	/**
	 * Generate persist method
	 *
	 */
	private function _generateCreate($className) {
		// Set fetch function headers
		$this->_data.="\t/**\n";
		$this->_data.="\t* Create a record\n";
		$this->_data.="\t*\n";
		$this->_data.="\t* @param array/object \$" . strtolower($className) . "\n";
		$this->_data.="\t* @return object ".ZendModelCreator2::getNamespace()."\Entity\\" . ucfirst(strtolower($className)) . "\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function create(\$" . strtolower($className) . ")\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t// If we're getting an array, convert to a UserEntity\n";
		$this->_data.="\t\tif (is_array(\$" . strtolower($className) . ")) {\n";
		$this->_data.="\t\t\t\$hydrator = new ClassMethods;\n";
		$this->_data.="\t\t\t\$" . strtolower($className) . " = \$hydrator->hydrate(\$" . strtolower($className) . ", new " . ucfirst(strtolower($className)) . "Entity);\n";
		$this->_data.="\t\t}\n";
		$this->_data.="\n";
		$this->_data.="\t\t// Save the record\n";
		$this->_data.="\t\t\$" . strtolower($className) . " = \$this->mapper->persist(\$" . strtolower($className) . ");\n";
		$this->_data.="\n";
		$this->_data.="\t\t// Ge the event manager\n";
		$this->_data.="\t\t\$events = \$this->getEventManager();\n";
		$this->_data.="\n";
		$this->_data.="\t\t// Trigger event for adding a record\n";
		$this->_data.="\t\t\$events->trigger(" . ucfirst(strtolower($className)) . "Event::EVENT_ADD_" . strtoupper($className) . "_POST, \$this, array('" . strtolower($className) . "' => \$" . strtolower($className) . "));\n";
		$this->_data.="\n";
		$this->_data.="\t\treturn \$" . strtolower($className) . ";\n";
		$this->_data.="\t}\n\n";
	}


	/**
	 * Generate persist method
	 *
	 */
	private function _generateUpdate($className) {
		// Set fetch function headers
		$this->_data.="\t/**\n";
		$this->_data.="\t* Update a record\n";
		$this->_data.="\t*\n";
		$this->_data.="\t* @param array/object \$" . strtolower($className) . "\n";
		$this->_data.="\t* @return object ".ZendModelCreator2::getNamespace()."\Entity\\" . ucfirst(strtolower($className)) . "\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function update(\$" . strtolower($className) . ")\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t// If we're getting an array, convert to a UserEntity\n";
		$this->_data.="\t\tif (is_array(\$" . strtolower($className) . ")) {\n";
		$this->_data.="\t\t\t\$hydrator = new ClassMethods;\n";
		$this->_data.="\t\t\t\$" . strtolower($className) . " = \$hydrator->hydrate(\$" . strtolower($className) . ", new " . ucfirst(strtolower($className)) . "Entity);\n";
		$this->_data.="\t\t}\n";
		$this->_data.="\n";
		$this->_data.="\t\t// Save the record\n";
		$this->_data.="\t\t\$" . strtolower($className) . " = \$this->mapper->persist(\$" . strtolower($className) . ");\n";
		$this->_data.="\n";
		$this->_data.="\t\t// Ge the event manager\n";
		$this->_data.="\t\t\$events = \$this->getEventManager();\n";
		$this->_data.="\n";
		$this->_data.="\t\t// Trigger event for updating a record\n";
		$this->_data.="\t\t\$events->trigger(" . ucfirst(strtolower($className)) . "Event::EVENT_UPDATE_" . strtoupper($className) . "_POST, \$this, array('" . strtolower($className) . "' => \$" . strtolower($className) . "));\n";
		$this->_data.="\n";
		$this->_data.="\t\treturn \$" . strtolower($className) . ";\n";
		$this->_data.="\t}\n\n";
	}

	/**
	 * Generate delete method
	 *
	 */
	private function _generateDelete($className) {
		// Set fetch function headers
		$this->_data.="\t/**\n";
		$this->_data.="\t* Delete a record\n";
		$this->_data.="\t*\n";
		$this->_data.="\t* @param int/object \$" . strtolower($className) . "\n";
		$this->_data.="\t* @return bool\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function delete(\$" . strtolower($className) . ")\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t// If the variable \$" . strtolower($className) . " is an instance of " . ucfirst(strtolower($className)) . "Entity, get the " . $this->_primary_key . " out of the object\n";
		$this->_data.="\t\tif (\$" . strtolower($className) . " instanceof " . ucfirst(strtolower($className)) . "Entity) {\n";
		$this->_data.="\t\t\t\$" . strtolower($className) . " = \$" . strtolower($className) . "->get" . ZendModelCreator2::toCamelCase($this->_primary_key) . "();\n";
		$this->_data.="\t\t}\n";
		$this->_data.="\n";
		$this->_data.="\t\t// Return the result of deleting the record\n";
		$this->_data.="\t\treturn \$this->mapper->delete(\$" . strtolower($className) . ");\n";
		$this->_data.="\t}\n";
	}

	/**
	 * Generates a footer
	 *
	 */
	private function _generateClassFooter() {
		$this->_data .= "\n}";
	}
}