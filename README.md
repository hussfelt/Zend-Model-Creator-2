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
* Zend Framework 2 - Not needed to generate your models
* [ZfcBase](https://github.com/ZF-Commons/ZfcBase) - Not needed to generate your models

Features / Goals
----------------

* Generate Entities [COMPLETE]
* Generate Mappers [COMPLETE]
* Generate Event Aware Services [COMPLETE]
* Generate EventServices [COMPLETE]
* Generate Module.php [COMPLETE]
* Generate autoloader files [COMPLETE]

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