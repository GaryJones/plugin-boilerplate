<?php
/**
 * Plugin Name
 *
 * @package   Gamajo\PluginSlug
 * @author    Gary Jones
 * @copyright 2016 Gary Jones, Gamajo Tech
 * @license   GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Plugin Boilerplate
 * Plugin URI:  https://github.com/garyjones/...
 * Description: ...
 * Version:     0.1.0
 * Author:      Gary Jones
 * Author URI:  https://gamajo.com
 * Text Domain: plugin-slug
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace Gamajo\PluginSlug;

use BrightNucleus\Config\ConfigFactory;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'GAMAJO_PLUGINSLUG_DIR' ) ) {
	define( 'GAMAJO_PLUGINSLUG_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'GAMAJO_PLUGINSLUG_URL' ) ) {
	define( 'GAMAJO_PLUGINSLUG_URL', plugin_dir_url( __FILE__ ) );
}

// Load Composer autoloader.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

// Initialize the plugin.
$config = ConfigFactory::create( __DIR__ . '/config/defaults.php' );
Plugin::get_instance( $config->getSubConfig( 'Gamajo\PluginSlug' ) )->run();
