<?php 
/**
 * Hopsuite - Config
 *
 * NSM Addon Updater config file.
 *
 * @package		Hop Studios:Hopsuite
 * @author		Hop Studios, Inc.
 * @copyright	Copyright (c) 2015, Hop Studios, Inc.
 * @link		http://www.hopstudios.com/software
 * @version		1.0
 * @filesource	hopsuite/config.php
 */

$config['name']='Hopsuite';
$config['version']='1.0';
$config['nsm_addon_updater']['versions_xml']='http://www.hopstudios.com/software/versions/hopsuite';

// Version constant
if (!defined("HOPSUITE_VERSION")) {
	define('HOPSUITE_VERSION', $config['version']);
}

//Clean name constant
if (!defined("HOPSUITE_NAME")) {
	define('HOPSUITE_NAME', 'hopsuite');
}

//Full addon name
if (!defined("HOPSUITE_FULL_NAME")) {
	define('HOPSUITE_FULL_NAME', $config['name']);
}
