Zend Model Creator 2
=======
Version 0.0.1 Created by Henrik Hussfelt

Introduction
------------

Zend Model Creator is a generator for quick prototyping of your Zend Project.
If you are not keen on using pure ORM's like Doctrine, this might be something for you.

Out of the box, Zend Model Creator works with Zend\Db and generates Entities, Mappers
and event aware Services based on your database structure.

This project is manly used to avoid the time spent on creating models for
your Zend Framework projects.

Requirements
------------

* [PHP5](https://php.net/)
* [Zend Framework 2](https://github.com/zendframework/zf2) - Not needed to generate your models
* [ZfcBase](https://github.com/ZF-Commons/ZfcBase) - Not needed to generate your models

Features / Goals
----------------

* Generate Entities [COMPLETE]
* Generate Mappers [COMPLETE]
* Generate Event Aware Services [COMPLETE]
* Generate EventServices [COMPLETE]
* Generate Module.php [COMPLETE]
* Generate autoloader files [COMPLETE]
* Generate Add Forms [INCOMPLETE]
* Generate Edit Forms [INCOMPLETE]
* Generate FormFilters [INCOMPLETE]

Installation
------------

**Install via git**

Clone this repo
`git clone git@github.com:hussfelt/Zend-Model-Creator-2.git`

cd into your directory
`cd Zend-Model-Creator-2`

**Install via Composer**

Add this to your composer.json under "require":
`"hussfelt/zend-model-creator-2": "dev-master"`

Run command:
``php composer.phar update``

Usage
-----

Cd into the directory (installed with composer):
``cd vendor/hussfelt/zend-model-creator-2``

Run with php:
`php zmc.php --host=[DB_HOST] --db=[DATABASE_NAME] --user=[USERNAME] --password=[PASSWORD]`

1. Move your [NAMESPACE] directory into the /vendor/ directory
2. Add [NAMESPACE] to your application.config.php:

```php
return array(
    'modules' => array(
        'ZfcBase',
        '[NAMESPACE_HERE]',
        'Application',
    ),
);
```

Options
-------

`--without-entity=1`
Will not generate entities

`--without-mapper=1`
Will not generate mapper

`--without-service=1`
Will not generate the services

`--without-module=1`
Will not generate the Module.php file

`--without-autoloaders=1`
Will not generate the autoloader files

`--without-config=1`
Will not generate the config file

`--without-options=1`
Will not generate the options file

`--namespace=[NAMESPACE]`
Will generate your data into a given Namespace and not use ZMC2

Example usage in Controller
---------------------------
This will replace the IndexController.php in the [Zend Framework Skeleton application](https://github.com/zendframework/ZendSkeletonApplication).
Given you have a database-table "album" in your database, this will output all records "Name" from that table.

***module/Application/src/Application/Controller/IndexController.php:***

```php
<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
	protected $albumService;
	protected $options;

    public function indexAction()
    {
        // Get all albums from service
        $albums = $this->getAlbumService()->findAll();
        foreach ($albums as $album) {
            echo $album->getName() . "<br />";
        }
        exit;
    }

    public function getAlbumService()
    {
        if (!isset($this->albumService)) {
            $this->albumService = $this->getServiceLocator()->get('ZmcBase\Service\Album');
        }

        return $this->albumService;
    }
}
```

Also, you need to fix your global.php and local.php config files if you have not done this yet:

***config/autoload/global.php:***

```php
<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overridding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'db' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=[YOUR_DATABASE_NAME];host=[YOUR_DB_HOST]',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter'
                    => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
);
```

***config/autoload/local.php:***

```php
<?php
/**
 * Local Configuration Override
 *
 * This configuration override file is for overriding environment-specific and
 * security-sensitive configuration information. Copy this file without the
 * .dist extension at the end and populate values as needed.
 *
 * @NOTE: This file is ignored from Git by default with the .gitignore included
 * in ZendSkeletonApplication. This is a good practice, as it prevents sensitive
 * credentials from accidentally being comitted into version control.
 */

return array(
    'db' => array(
        'username' => '[DB_USERNAME]',
        'password' => '[DB_PASSWORD]',
    ),
);
```
