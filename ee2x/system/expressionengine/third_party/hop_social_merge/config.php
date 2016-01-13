<?php if ( ! defined('EXT')) exit('No direct script access allowed');

/**
 * Hop Social Merge - Config
 *
 * NSM Addon Updater config file.
 *
 * @package		Hop Studios:Hop Social Merge
 * @author		Hop Studios, Inc.
 * @copyright	Copyright (c) 2015, Hop Studios, Inc.
 * @link		http://www.hopstudios.com/software
 * @version		1.0
 * @filesource	hop_social_merge/config.php
 */

$config['name']='Hop Social Merge';
$config['version']='1.0';
$config['nsm_addon_updater']['versions_xml']='http://www.hopstudios.com/software/versions/hop_404_reporter';

// Version constant
if (!defined("HOP_SOCIAL_MERGE_VERSION")) {
	define('HOP_SOCIAL_MERGE_VERSION', $config['version']);
}
