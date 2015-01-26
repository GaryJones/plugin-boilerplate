<?php
/**
 * Plugin Name
 *
 * @package   Gamajo\PluginSlug
 * @author    Gary Jones
 * @link      http://gamajo.com/
 * @copyright 2015 Gary Jones, Gamajo Tech
 * @license   GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Plugin Boilerplate
 * Plugin URI:  http://gamajo.com/
 * Description: TODO
 * Version:     1.0.0
 * Author:      Gary Jones
 * Author URI:  http://gamajo.com/
 * Text Domain: plugin-slug
 * Domain Path: /lang
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace Gamajo\PluginSlug;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
// register_activation_hook( __FILE__, array( 'PluginSlug', 'activate' ) );
// register_deactivation_hook( __FILE__, array( 'PluginSlug', 'deactivate' ) );

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
}

$plugin_slug = new Plugin();
$plugin_slug->register( new ServiceProvider() );
$plugin_slug->setup_hooks();
