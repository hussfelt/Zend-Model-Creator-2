<?php
/**
* Code generator for Event class
*
* @author 		Henrik Hussfelt
* @copyright	Copyright (C) 2012 Hussfelt Consulting AB. All rights reserved.
* @license		SEE LICENCE
*
**/

class EventCreatorService {

    private $_data = '';

	/**
	 * Generates an Event class
	 *
	 * @return string formatted code ready to use
	 *
	 */
	public function createEventService($className, $parameterArray) {
		$this->_generateClassHeader($className);
		$this->_generateConstants($className);
		$this->_generateGet($className);
		$this->_generateSet($className);
		$this->_generateClassFooter();
		return $this->_data;
	}

	/**
	 * Generates header for Mapper class
	 *
	 */
	private function _generateClassHeader($className) {
		$this->_data .= "<?php
/**
* file: " . $className . "Event.php
* " . $className . " Event
*
* @author ".ZendModelCreator::getGenerator()."
* @version ".ZendModelCreator::getVersion()."
* @package ".ZendModelCreator::getNamespace()."
* @since " . date("Y-m-d") . "
*/

namespace ".ZendModelCreator::getNamespace()."\Service;

use ArrayObject;

use ".ZendModelCreator::getNamespace()."\Entity\\" . $className . " as " . $className . "Entity;

use Zend\EventManager\Event;

/**
* " . $className . "Event
*
* @author ".ZendModelCreator::getGenerator()."
* @version ".ZendModelCreator::getVersion()."
* @package ".ZendModelCreator::getNamespace()."
* @since " . date("Y-m-d") . "
*
**/
class " . $className . "Event extends Event
{
";
	}

	/**
	* Generate constants
	*/
	private function _generateConstants($className) {
		$this->_data.="\tconst EVENT_ADD_" . strtoupper($className) . "_POST = 'add" . $className . ".post';\n";
		$this->_data.="\tconst EVENT_UPDATE_" . strtoupper($className) . "_POST = 'update" . $className . ".post';\n";
		$this->_data.="\tconst EVENT_DELETE_" . strtoupper($className) . "_POST = 'delete" . $className . ".post';\n";
		$this->_data.="\n";
	}

	/**
	* Generate get
	*/
	private function _generateGet($className) {
		$this->_data.="\t/**\n";
		$this->_data.="\t* Get record from event\n";
		$this->_data.="\t*\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function get" . $className . "()\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t// Return param\n";
		$this->_data.="\t\treturn \$this->getParam('" . ZendModelCreator::fromCamelCase($className) . "');\n";
		$this->_data.="\t}\n\n";
	}

	/**
	* Generate set
	*/
	private function _generateSet($className) {
		$this->_data.="\t/**\n";
		$this->_data.="\t* Set record to event\n";
		$this->_data.="\t*\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function set" . $className . "(" . $className . "Entity \$" . ZendModelCreator::fromCamelCase($className) . ")\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t// Set param\n";
		$this->_data.="\t\t\$this->setParam('" . ZendModelCreator::fromCamelCase($className) . "', \$" . ZendModelCreator::fromCamelCase($className) . ");\n";
		$this->_data.="\t\treturn \$this;\n";
		$this->_data.="\t}";
	}

	/**
	 * Generates a footer
	 *
	 */
	private function _generateClassFooter() {
		$this->_data .= "\n}";
	}
}