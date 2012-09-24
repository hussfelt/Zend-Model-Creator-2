<?php
/**
* @package		ZendModelCreator 2
* @copyright	Copyright (C) 2012 Hussfelt Consulting AB. All rights reserved.
* @license		SEE LICENCE
*
**/

// Parameters used
$parameters = array(
	'host:',
	'db:',
	'user:',
	'password:',
	'without-entity::',
	'without-mapper::',
	'without-service::',
	'namespace::',
);

// Get the parameters
$options = getopt('', $parameters);

// MySQL settings
$SETTINGS['mysql_host'] = $options['host'];
$SETTINGS['mysql_user'] = $options['user'];
$SETTINGS['mysql_password'] = $options['password'];
$SETTINGS['mysql_db'] = $options['db'];

// Setup the model creator service with our specified settings
require_once 'zmc/zmc2.php';
$zmc = new ZendModelCreator2($SETTINGS);