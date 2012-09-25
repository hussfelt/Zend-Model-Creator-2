<?php
/**
* Code generator for Mapper class
*
* @author 		Henrik Hussfelt
* @copyright	Copyright (C) 2012 Hussfelt Consulting AB. All rights reserved.
* @license		SEE LICENCE
*
**/

class MapperCreatorService {

    private $_data = '';
	private $_primary_key = '';

	/**
	 * Generates an Mapper class
	 *
	 * @return string formatted code ready to use
	 *
	 */
	public function createMapper($className, $parameterArray) {
		$this->_setPrimaryKey($parameterArray['primary_key']);
		$this->_generateClassHeader($className);
		$this->_generateConstructor($className);
		$this->_generateFindById($className);
		$this->_generateFindAll($className);
		$this->_generatePersist($className);
		$this->_generateDelete($className);
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
* file: ".$className."Mapper.php
* " . ucfirst(strtolower($className)) . " mapper
*
* @author ".ZendModelCreator::getGenerator()."
* @version ".ZendModelCreator::getVersion()."
* @package ".ZendModelCreator::getNamespace()."
* @package ".ZendModelCreator::getNamespace()."
* @since " . date("Y-m-d") . "
*/

namespace ".ZendModelCreator::getNamespace()."\Mapper;

use ArrayObject;

use ".ZendModelCreator::getNamespace()."\Entity\\" . $className . ";

use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Stdlib\Hydrator\ArraySerializable;
use Zend\Stdlib\Hydrator\ClassMethods;

use ZfcBase\Mapper\AbstractDbMapper;

/**
* " . ucfirst(strtolower($className)) . "Mapper
*
* @author ".ZendModelCreator::getGenerator()."
* @version ".ZendModelCreator::getVersion()."
* @package ".ZendModelCreator::getNamespace()."
* @since " . date("Y-m-d") . "
*
**/
class " . ucfirst(strtolower($className)) . "Mapper extends AbstractDbMapper
{
";
	}

	/**
	* Generate a constructor
	*
	*/
	private function _generateConstructor($className) {
		$this->_data.="\t/**\n";
		$this->_data.="\t* " . $className . "Mapper constructor\n";
		$this->_data.="\t*\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function __construct()\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t\$this->setEntityPrototype(new " . $className . ");\n";
		$this->_data.="\t\t\$this->setHydrator(new ClassMethods);\n";
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
		$this->_data.="\t\t\$select = new Select;\n";
		$this->_data.="\t\t\$select->from('" . strtolower($className) . "');\n";
		$this->_data.="\t\t\$where = new Where;\n";
		$this->_data.="\t\t\$where->equalTo('" . $this->_primary_key . "', \$id);\n";
		$this->_data.="\t\t\$result = \$this->select(\$select->where(\$where))->current();\n";
		$this->_data.="\t\treturn \$result;\n";
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
		$this->_data.="\t\t\$select = new Select;\n";
		$this->_data.="\t\t\$select->from('" . strtolower($className) . "');\n";
		$this->_data.="\t\t\$result = \$this->select(\$select);\n";
		$this->_data.="\t\treturn \$result;\n";
		$this->_data.="\t}\n\n";
	}

	/**
	 * Generate persist method
	 *
	 */
	private function _generatePersist($className) {
		// Set fetch function headers
		$this->_data.="\t/**\n";
		$this->_data.="\t* Save a record\n";
		$this->_data.="\t*\n";
		$this->_data.="\t* @param object ".ZendModelCreator::getNamespace()."\Entity\\" . ucfirst(strtolower($className)) . "\n";
		$this->_data.="\t* @return object ".ZendModelCreator::getNamespace()."\Entity\\" . ucfirst(strtolower($className)) . "\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function persist(" . ucfirst(strtolower($className)) . " \$" . strtolower($className) . ")\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\tif (\$" . strtolower($className) . "->get" . ZendModelCreator::toCamelCase($this->_primary_key) . "() > 0) {\n";
		$this->_data.="\t\t\t\$where = new Where;\n";
		$this->_data.="\t\t\t\$where->equalTo('" . $this->_primary_key . "', \$" . strtolower($className) . "->get" . ZendModelCreator::toCamelCase($this->_primary_key) . "());\n";
		$this->_data.="\t\t\t\$this->update(\$" . strtolower($className) . ", \$where, '" . strtolower($className) . "');\n";
		$this->_data.="\t\t} else {\n";
		$this->_data.="\t\t\t\$result = \$this->insert(\$" . strtolower($className) . ", '" . strtolower($className) . "');\n";
		$this->_data.="\t\t\t\$" . strtolower($className) . "->set" . ZendModelCreator::toCamelCase($this->_primary_key) . "(\$result->getGeneratedValue());\n";
		$this->_data.="\t\t}\n";
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
		$this->_data.="\t* @param int \$" . $this->_primary_key . "\n";
		$this->_data.="\t* @return bool\n";
		$this->_data.="\t**/\n";
		$this->_data.="\tpublic function delete(\$" . $this->_primary_key . ")\n";
		$this->_data.="\t{\n";
		$this->_data.="\t\t\$adapter = \$this->getDbAdapter();\n";
		$this->_data.="\t\t\$statement = \$adapter->createStatement();\n";
		$this->_data.="\t\t\$where = new Where;\n";
		$this->_data.="\t\t\$where->equalTo('" . $this->_primary_key . "', \$" . $this->_primary_key . ");\n";
		$this->_data.="\t\t\$sql = new Delete;\n";
		$this->_data.="\t\t\$sql->from('" . strtolower($className) . "')->where(\$where);\n";
		$this->_data.="\t\t\$sql->prepareStatement(\$adapter, \$statement);\n";
		$this->_data.="\t\t\$result = \$statement->execute();\n";
		$this->_data.="\t\treturn \$result;\n";
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