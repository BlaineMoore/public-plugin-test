<?php

namespace RYSE\GitHubUpdaterDemo;

/**
 * Plugin Name:        Plugin Test
 * Version:            0.0.3
 * Description:        This is a plugin tester to test automatic updates.
 * Author:             Blaine Moore
 * Author URI:         https://blainemoore.com/
 * Requires at least:  6.5
 * Requires PHP:       8.2
 * Tested Up To:       6.6.1
 * Plugin URI:         https://github.com/blainemoore/plugin-test
 * Update URI:         https://github.com/blainemoore/plugin-test
 * Update Method:	   default
 * Branch Name:		   main
 * Icon URI:           icon-128x128.jpg
 * License:            GPLv2
 * License URI:        https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) exit;

require_once('GitHubUpdater.php');
$updater = new GitHubUpdater(__FILE__);
//$updater->setAccessToken('');
$updater->enableDebugger(true);
$updater->add();