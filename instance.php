<?php
/**
 * Instantiates the Flint plugin
 *
 * @package Flint
 */

namespace Flint;

global $flint_plugin;

require_once __DIR__ . '/php/class-plugin-base.php';
require_once __DIR__ . '/php/class-plugin.php';

$flint_plugin = new Plugin();

/**
 * Flint Plugin Instance
 *
 * @return Plugin
 */
function get_plugin_instance() {
	global $flint_plugin;
	return $flint_plugin;
}
