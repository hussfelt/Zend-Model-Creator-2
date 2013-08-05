<?php
/**
* Code generator for Event class
*
* @author 		Henrik Hussfelt
* @copyright	Copyright (C) 2012 Hussfelt Consulting AB. All rights reserved.
* @license		SEE LICENCE
*
**/
require_once 'ZendModelCreator.php';//ycheukf

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
		$sNewClassName = ZendModelCreator::toCamelCase(ucfirst(strtolower($className)));//@ycheukf
		$this->_data .= "<?php
/**
* file: " . $sNewClassName . "Event.php
* " . $sNewClassName . " Event
*
* @author ".ZendModelCreator::getGenerator()."
* @version ".ZendModelCreator::getVersion()."
* @package ".ZendModelCreator::getNamespace()."
* @since " . date("Y-m-d") . "
*/

namespace ".ZendModelCreator::getNamespace()."\Service;

use ArrayObject;

use ".ZendModelCreator::getNamespace()."\Entity\\" . $sNewClassName . " as " . $sNewClassName . "Entity;

use Zend\EventManager\Event;

/**
* " . $sNewClassName . "Event
*
* @author ".ZendModelCreator::getGenerator()."
* @version ".ZendModelCreator::getVersion()."
* @package ".ZendModelCreator::getNamespace()."
* @since " . date("Y-m-d") . "
*
**/
class " . $sNewClassName . " extends Event
{
";
	}

	/**
	* Generate constants
	*/
	private function _generateConstants($className) {
		$sNewClassName = ZendModelCreator::toCamelCase(ucfirst(strtolower($className)));//@ycheukf
		$this->_data.="\tconst EVENT_ADD_" . strtoupper($sNewClassName) . "_POST = 'add" . $sNewClassName . ".post';\n";
		$this->_data.="\tconst EVENT_UPDATE_" . strtoupper($sNewClassName) . "_POST = 'update" . $sNewClassName . ".post';\n";
		$this->_data.="\tconst EVENT_DELETE_" . strtoupper($sNewClassName) . "_POST = 'delete" . $sNewClassName . ".post';\n";
		$this->_data.="\n";
	}

	/**
	* Generate get
	*/
	private function _generateGet($className) {
		$sNewClassName = ZendModelCreator::toCamelCase(ucfirst(strtolower($className)));//@ycheukf
		$this->_data.="\t/**\n";
		$this->_data.="\t* Get record from event\n";
		$this->_data.="\t*\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function get" . $sNewClassName . "()\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t// Return param\n";
		$this->_data.="\t\treturn \$this->getParam('" . strtolower($className) . "');\n";
		$this->_data.="\t}\n\n";
	}

	/**
	* Generate set
	*/
	private function _generateSet($className) {
		$sNewClassName = ZendModelCreator::toCamelCase(ucfirst(strtolower($className)));//@ycheukf

		$this->_data.="\t/**\n";
		$this->_data.="\t* Set record to event\n";
		$this->_data.="\t*\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function set" . $sNewClassName . "(" . $sNewClassName . "Entity \$" . strtolower($className) . ")\n";
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