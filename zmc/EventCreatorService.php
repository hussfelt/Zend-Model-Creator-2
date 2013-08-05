<?php
/**
* Code generator for Event class
*
* @author 		Henrik Hussfelt
* @copyright	Copyright (C) 2012 Hussfelt Consulting AB. All rights reserved.
* @license		SEE LICENCE
*
**/
require_once 'ZendModelCreator.php';

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
		$sCamelClassName = ZendModelCreator::toCamelCase($className);
		$this->_data .= "<?php
/**
* file: " . $sCamelClassName . "Event.php
* " . $sCamelClassName . " Event
*
* @author ".ZendModelCreator::getGenerator()."
* @version ".ZendModelCreator::getVersion()."
* @package ".ZendModelCreator::getNamespace()."
* @since " . date("Y-m-d") . "
*/

namespace ".ZendModelCreator::getNamespace()."\Service;

use ArrayObject;

use ".ZendModelCreator::getNamespace()."\Entity\\" . $sCamelClassName . " as " . $sCamelClassName . "Entity;

use Zend\EventManager\Event;

/**
* " . $sCamelClassName . "Event
*
* @author ".ZendModelCreator::getGenerator()."
* @version ".ZendModelCreator::getVersion()."
* @package ".ZendModelCreator::getNamespace()."
* @since " . date("Y-m-d") . "
*
**/
class " . $sCamelClassName . " extends Event
{
";
	}

	/**
	* Generate constants
	*/
	private function _generateConstants($className) {
		$sCamelClassName = ZendModelCreator::toCamelCase($className);
		$this->_data.="\tconst EVENT_ADD_" . strtoupper($sCamelClassName) . "_POST = 'add" . $sCamelClassName . ".post';\n";
		$this->_data.="\tconst EVENT_UPDATE_" . strtoupper($sCamelClassName) . "_POST = 'update" . $sCamelClassName . ".post';\n";
		$this->_data.="\tconst EVENT_DELETE_" . strtoupper($sCamelClassName) . "_POST = 'delete" . $sCamelClassName . ".post';\n";
		$this->_data.="\n";
	}

	/**
	* Generate get
	*/
	private function _generateGet($className) {
		$sCamelClassName = ZendModelCreator::toCamelCase($className);
		$this->_data.="\t/**\n";
		$this->_data.="\t* Get record from event\n";
		$this->_data.="\t*\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function get" . $sCamelClassName . "()\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t// Return param\n";
		$this->_data.="\t\treturn \$this->getParam('" . strtolower($className) . "');\n";
		$this->_data.="\t}\n\n";
	}

	/**
	* Generate set
	*/
	private function _generateSet($className) {
		$sCamelClassName = ZendModelCreator::toCamelCase($className);

		$this->_data.="\t/**\n";
		$this->_data.="\t* Set record to event\n";
		$this->_data.="\t*\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function set" . $sCamelClassName . "(" . $sCamelClassName . "Entity \$" . strtolower($className) . ")\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t// Set param\n";
		$this->_data.="\t\t\$this->setParam('" . strtolower($className) . "', \$" . strtolower($className) . ");\n";
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