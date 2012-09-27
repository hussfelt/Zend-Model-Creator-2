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

		$this->_generateGetMapper($className);
		$this->_generateSetMapper($className);
		$this->_generateGetOptions($className);
		$this->_generateSetOptions($className);
		$this->_generateGetEventManager($className);
		$this->_generateSetEventManager($className);
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
	 * Generates header for Mapper class
	 *
	 */
	private function _generateClassHeader($className) {
		$this->_data .= "<?php
/**
* file: " . ucfirst(strtolower($className)) . ".php
* " . ucfirst(strtolower($className)) . " Service
*
* @author ".ZendModelCreator::getGenerator()."
* @version ".ZendModelCreator::getVersion()."
* @package ".ZendModelCreator::getNamespace()."
* @package ".ZendModelCreator::getNamespace()."
* @since " . date("Y-m-d") . "
*/

namespace ".ZendModelCreator::getNamespace()."\Service;

use ArrayObject;

use ".ZendModelCreator::getNamespace()."\Entity\\" . $className . " as " . $className . "Entity;
use ".ZendModelCreator::getNamespace()."\Service\\" . $className . "Event;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
* " . ucfirst(strtolower($className)) . "
*
* @author ".ZendModelCreator::getGenerator()."
* @version ".ZendModelCreator::getVersion()."
* @package ".ZendModelCreator::getNamespace()."
* @since " . date("Y-m-d") . "
*
**/
class " . ucfirst(strtolower($className)) . " implements EventManagerAwareInterface
{
    protected \$mapper;
    protected \$options;
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
		$this->_data.="\t\t// Create event manager instance\n";
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
		$this->_data.="\t* @return object ".ZendModelCreator::getNamespace()."\Entity\\" . ucfirst(strtolower($className)) . "\n";
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
		$this->_data.="\t* @return array objects ".ZendModelCreator::getNamespace()."\Entity\\" . ucfirst(strtolower($className)) . "\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function findAll()\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t// Call Mapper and return all records\n";
		$this->_data.="\t\treturn \$this->mapper->findAll();\n";
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
		$this->_data.="\t* @return object ".ZendModelCreator::getNamespace()."\Entity\\" . ucfirst(strtolower($className)) . "\n";
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
		$this->_data.="\t* @return object ".ZendModelCreator::getNamespace()."\Entity\\" . ucfirst(strtolower($className)) . "\n";
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
		$this->_data.="\t\t\t\$" . strtolower($className) . " = \$" . strtolower($className) . "->get" . ZendModelCreator::toCamelCase($this->_primary_key) . "();\n";
		$this->_data.="\t\t}\n";
		$this->_data.="\n";
		$this->_data.="\t\t// Ge the event manager\n";
		$this->_data.="\t\t\$events = \$this->getEventManager();\n";
		$this->_data.="\n";
		$this->_data.="\t\t// Trigger event for deleting a record\n";
		$this->_data.="\t\t\$events->trigger(" . ucfirst(strtolower($className)) . "Event::EVENT_DELETE_" . strtoupper($className) . "_POST, \$this, array('" . strtolower($className) . "' => \$" . strtolower($className) . "));\n";
		$this->_data.="\n";
		$this->_data.="\t\t// Return the result of deleting the record\n";
		$this->_data.="\t\treturn \$this->mapper->delete(\$" . strtolower($className) . ");\n";
		$this->_data.="\t}\n\n";
	}

	/**
	 * Generates getMapper
	 *
	 */
	private function _generateGetMapper($className) {
		// Set fetch function headers
		$this->_data.="\t/**\n";
		$this->_data.="\t* Get the mapper\n";
		$this->_data.="\t*\n";
		$this->_data.="\t* @return objects object ".ZendModelCreator::getNamespace()."\Mapper\\" . ucfirst(strtolower($className)) . "Mapper\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function getMapper()\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t// Return mapper\n";
		$this->_data.="\t\treturn \$this->mapper;\n";
		$this->_data.="\t}\n\n";
	}

	/**
	 * Generates setMapper
	 *
	 */
	private function _generateSetMapper($className) {
		// Set fetch function headers
		$this->_data.="\t/**\n";
		$this->_data.="\t* Set the mapper\n";
		$this->_data.="\t*\n";
		$this->_data.="\t* @param objects object ".ZendModelCreator::getNamespace()."\Mapper\\" . ucfirst(strtolower($className)) . "Mapper\n";
		$this->_data.="\t* @return object \$this\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function setMapper(\$mapper)\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t\$this->mapper = \$mapper;\n";
		$this->_data.="\t\treturn \$this;\n";
		$this->_data.="\t}\n\n";
	}

	/**
	 * Generates getOptions
	 *
	 */
	private function _generateGetOptions($className) {
		// Set fetch function headers
		$this->_data.="\t/**\n";
		$this->_data.="\t* Get the mapper\n";
		$this->_data.="\t*\n";
		$this->_data.="\t* @return array \$options\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function getOptions()\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t// Return options\n";
		$this->_data.="\t\treturn \$this->options;\n";
		$this->_data.="\t}\n\n";
	}

	/**
	 * Generates setOptions
	 *
	 */
	private function _generateSetOptions($className) {
		// Set fetch function headers
		$this->_data.="\t/**\n";
		$this->_data.="\t* Set the mapper\n";
		$this->_data.="\t*\n";
		$this->_data.="\t* @param array \$options\n";
		$this->_data.="\t* @return object \$this\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function setOptions(\$options)\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t\$this->options = \$options;\n";
		$this->_data.="\t\treturn \$this;\n";
		$this->_data.="\t}\n\n";
	}

	/**
	 * Generates getEventManager
	 *
	 */
	private function _generateGetEventManager($className) {
		// Set fetch function headers
		$this->_data.="\t/**\n";
		$this->_data.="\t* Get the Event Manager\n";
		$this->_data.="\t*\n";
		$this->_data.="\t* @return object EventManager\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function getEventManager()\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t// Return eventManager\n";
		$this->_data.="\t\treturn \$this->eventManager;\n";
		$this->_data.="\t}\n\n";
	}

	/**
	 * Generates setEventManager
	 *
	 */
	private function _generateSetEventManager($className) {
		// Set fetch function headers
		$this->_data.="\t/**\n";
		$this->_data.="\t* Set the Event Manager\n";
		$this->_data.="\t*\n";
		$this->_data.="\t* @param object Zend\EventManager\EventManagerInterface\n";
		$this->_data.="\t* @return object \$this\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function setEventManager(EventManagerInterface \$eventManager)\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t\$eventManager->setIdentifiers(\n";
		$this->_data.="\t\t\t__CLASS__,\n";
		$this->_data.="\t\t\tget_called_class(),\n";
		$this->_data.="\t\t\t'" . strtolower($className) . "'\n";
		$this->_data.="\t\t);\n";
		$this->_data.="\n";
		$this->_data.="\t\t\$eventManager->setEventClass('" . ZendModelCreator::getNamespace() . "\Service\\" . ucfirst(strtolower($className)) . "Event');\n";
		$this->_data.="\n";
		$this->_data.="\t\t\$this->eventManager = \$eventManager;\n";
		$this->_data.="\t\treturn \$this;\n";
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