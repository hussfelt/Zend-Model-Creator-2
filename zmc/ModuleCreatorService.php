<?php
/**
* Code generator for Module.php class
*
* @author 		Henrik Hussfelt
* @copyright	Copyright (C) 2012 Hussfelt Consulting AB. All rights reserved.
* @license		SEE LICENCE
*
**/

class ModuleCreatorService {

    private $_data = '';

	/**
	 * Generates an Module class
	 *
	 * @return string formatted code ready to use
	 *
	 */
	public function createModule($className, $parameterArray) {
		$this->_generateClassHeader($className);
		$this->_generateGetServiceConfig($className, $parameterArray);
		$this->_generateAutoloaderConfig();
		$this->_generateGetConfig();
		$this->_generateOnBootstrap();
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
* file: Module.php
* ".ZendModelCreator::getNamespace()." Module
*
* @author ".ZendModelCreator::getGenerator()."
* @version ".ZendModelCreator::getVersion()."
* @package ".ZendModelCreator::getNamespace()."
* @package ".ZendModelCreator::getNamespace()."
* @since " . date("Y-m-d") . "
*/

namespace ".ZendModelCreator::getNamespace().";

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;

/**
* Module
*
* @author ".ZendModelCreator::getGenerator()."
* @version ".ZendModelCreator::getVersion()."
* @package ".ZendModelCreator::getNamespace()."
* @since " . date("Y-m-d") . "
*
**/
class Module implements AutoloaderProviderInterface
{
";
	}

	/**
	* Generate a constructor
	*/
	private function _generateGetServiceConfig($className, $tables) {
		$this->_data.="\t/**\n";
		$this->_data.="\t* getServiceConfig\n";
		$this->_data.="\t*\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function getServiceConfig()\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\treturn array(\n";
		$this->_data.="\t\t\t'shared' => array(\n";
		$this->_data.="\t\t\t),\n";
		$this->_data.="\t\t\t'factories' => array(\n";
		// First add mappers
		$this->_data.="\t\t\t\t// Adding all mappers for namespace\n";
		foreach ($tables as $table => $data) {
            $this->_data.="\t\t\t\t'".ZendModelCreator::getNamespace()."\Mapper\\" . ZendModelCreator::toCamelCase(ucfirst(strtolower($table))) . "Mapper' => function(\$sm) {\n";
            $this->_data.="\t\t\t\t\t\$mapper = new Mapper\\" . ZendModelCreator::toCamelCase(ucfirst(strtolower($table))) . "Mapper;\n";
			$this->_data.="\t\t\t\t\t\$mapper->setDbAdapter(\$sm->get('".strtolower(ZendModelCreator::getNamespace())."_zend_db_adapter'));\n";
            $this->_data.="\t\t\t\t\treturn \$mapper;\n";
            $this->_data.="\t\t\t\t},\n";
		}
		$this->_data.="\n";
		$this->_data.="\t\t\t\t// Adding all services for namespace\n";
		// Then add Services
		foreach ($tables as $table => $data) {
            $this->_data.="\t\t\t\t'".ZendModelCreator::getNamespace()."\Service\\" . ZendModelCreator::toCamelCase(ucfirst(strtolower($table))) . "' => function(\$sm) {\n";
            $this->_data.="\t\t\t\t\t\$service = new Service\\" . ZendModelCreator::toCamelCase(ucfirst(strtolower($table))) . ";\n";
			$this->_data.="\t\t\t\t\t\$service->setMapper(\$sm->get('".ZendModelCreator::getNamespace()."\Mapper\\" . ZendModelCreator::toCamelCase(ucfirst(strtolower($table))) . "Mapper'));\n";
			$this->_data.="\t\t\t\t\t\$service->setOptions(\$sm->get('".ZendModelCreator::getNamespace()."\Options\ModuleOptions'));\n";
            $this->_data.="\t\t\t\t\treturn \$service;\n";
            $this->_data.="\t\t\t\t},\n";
		}

		// Add module options
		$this->_data.="\n";
		$this->_data.="\t\t\t\t// Adding module options\n";
        $this->_data.="\t\t\t\t'".ZendModelCreator::getNamespace()."\Options\ModuleOptions' => function(\$sm) {\n";
        $this->_data.="\t\t\t\t\t\$config = \$sm->get('Configuration');\n";
		$this->_data.="\t\t\t\t\t\$moduleConfig = isset(\$config['" . strtolower(ZendModelCreator::getNamespace()) . "']) ? \$config['" . strtolower(ZendModelCreator::getNamespace()) . "'] : array();\n";
        $this->_data.="\t\t\t\t\treturn new Options\ModuleOptions(\$moduleConfig);\n";
        $this->_data.="\t\t\t\t},\n";
		$this->_data.="\t\t\t),\n";
		$this->_data.="\t\t);\n";
		$this->_data.="\t}\n\n";
	}

	/**
	* Generate Autoloader Config
	*/
	private function _generateAutoloaderConfig() {
		$this->_data.="\t/**\n";
		$this->_data.="\t* getAutoLoaderConfig\n";
		$this->_data.="\t*\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function getAutoloaderConfig()\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\treturn array(\n";
		$this->_data.="\t\t\t'Zend\Loader\ClassMapAutoloader' => array(\n";
		$this->_data.="\t\t\t\t__DIR__ . '/autoload_classmap.php',\n";
		$this->_data.="\t\t\t),\n";
		$this->_data.="\t\t\t'Zend\Loader\StandardAutoloader' => array(\n";
		$this->_data.="\t\t\t\t'namespaces' => array(\n";
		$this->_data.="\t\t\t\t\t// if we're in a namespace deeper than one level we need to fix the \ in the path\n";
		$this->_data.="\t\t\t\t\t__NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\\\', '/' , __NAMESPACE__),\n";
		$this->_data.="\t\t\t\t),\n";
		$this->_data.="\t\t\t),\n";
		$this->_data.="\t\t);\n";
		$this->_data.="\t}\n\n";
	}

	/**
	* Generate getConfig
	*/
	private function _generateGetConfig() {
		$this->_data.="\t/**\n";
		$this->_data.="\t* getConfig\n";
		$this->_data.="\t*\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function getConfig()\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\treturn include __DIR__ . '/config/module.config.php';\n";
		$this->_data.="\t}\n\n";
	}

	/**
	* Generate onBootstrap
	*/
	private function _generateOnBootstrap() {
		$this->_data.="\t/**\n";
		$this->_data.="\t* onBootstrap\n";
		$this->_data.="\t*\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function onBootstrap(\$e)\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t// You may not need to do this if you're doing it elsewhere in your application;\n";
		$this->_data.="\t\t\$eventManager = \$e->getApplication()->getEventManager();\n";
		$this->_data.="\t\t\$moduleRouteListener = new ModuleRouteListener();\n";
		$this->_data.="\t\t\$moduleRouteListener->attach(\$eventManager);\n";
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