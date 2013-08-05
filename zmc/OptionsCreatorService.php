<?php
/**
* Code generator for Options class
*
* @author 		Henrik Hussfelt
* @copyright	Copyright (C) 2012 Hussfelt Consulting AB. All rights reserved.
* @license		SEE LICENCE
*
**/

class OptionsCreatorService {

    private $_data = '';

	/**
	 * Generates an Module class
	 *
	 * @return string formatted code ready to use
	 *
	 */
	public function createOptions($className) {
		$this->_generateClassHeader($className);
		$this->_generateGetHydration();
		$this->_generateSetHydration();
		$this->_generateClassFooter();
		return $this->_data;
	}

	/**
	 * Generates header for Options class
	 *
	 */
	private function _generateClassHeader($className) {
		$this->_data .= "<?php
/**
* file: ModuleOptions.php
* Module options
*
* @author ".ZendModelCreator::getGenerator()."
* @version ".ZendModelCreator::getVersion()."
* @package ".ZendModelCreator::getNamespace()."
* @package ".ZendModelCreator::getNamespace()."
* @since " . date("Y-m-d") . "
*/

namespace ".ZendModelCreator::getNamespace()."\Options;

use Zend\Stdlib\AbstractOptions;

/**
* Module
*
* @author ".ZendModelCreator::getGenerator()."
* @version ".ZendModelCreator::getVersion()."
* @package ".ZendModelCreator::getNamespace()."
* @since " . date("Y-m-d") . "
*
**/
class ModuleOptions extends AbstractOptions
{
    protected \$__strictMode__ = false;
    protected \$hydrateResultsByDefault = true;
";
	}

	/**
	* Generate getHydration
	*/
	private function _generateGetHydration() {
		$this->_data.="\t/**\n";
		$this->_data.="\t* getHydration\n";
		$this->_data.="\t*\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function getHydration()\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\treturn \$this->hydrateResultsByDefault;\n";
		$this->_data.="\t}\n\n";
	}

	/**
	* Generate setHydration
	*/
	private function _generateSetHydration() {
		$this->_data.="\t/**\n";
		$this->_data.="\t* setHydration\n";
		$this->_data.="\t* @param bool \$hydrate\n";
		$this->_data.="\t*\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function setHydration(\$hydrate = true)\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t\$this->hydrateResultsByDefault = \$hydrate;\n";
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