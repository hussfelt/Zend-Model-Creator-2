Zend Model Creator 2
=======
Version 0.5 Created by Henrik Hussfelt

Introduction
------------

Zend Model Creator is a generator for quick prototyping of your Zend Project.
If you are not keen on using pure ORM's like Doctrine, this might be something for you.

Out of the box, Zend Model Creator works with Zend\Db and generates Entities, Mappers
and Services based on your database structure.

This project is manly used to avoid the time spent on creating models for
your Zend Framework projects.

Requirements
------------

* [PHP5](https://php.net/)

Features / Goals
----------------

* Generate Entities [INCOMPLETE]
* Generate Mappers [INCOMPLETE]
* Generate Event Aware Services [INCOMPLETE]
* Generate EventServices [INCOMPLETE]
* Generate Module.php [INCOMPLETE]

Installation
------------

Clone this repo
`git clone git@github.com:hussfelt/Zend-Model-Creator-2.git`

cd into your directory
`cd Zend-Model-Creator-2`

Run with php:
`php zmc.php --db=[DATABASENAME] --user=[USERNAME] --password=[PASSWORD]`

Options
-------

`--without-entity`
Will not generate entities

`--without-mapper`
Will not generate mapper

`--without-service`
Will not generate the services

`--namespace=[NAMESPACE]`
Will generate your data into a given Namespace and not use ZMC2