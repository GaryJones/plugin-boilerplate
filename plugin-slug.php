<?php
/**
 * Plugin Name
 *
 * @package      Gamajo\PluginSlug
 * @author       Gary Jones
 * @copyright    2024 Gary Jones
 * @license      GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Plugin Boilerplate
 * Plugin URI:        https://github.com/garyjones/...
 * Description:       ...
 * Version:           0.1.0
 * Author:            Gary Jones
 * Author URI:        https://garyjones.io
 * Text Domain:       plugin-slug
 * License:           GPL-2.0-or-later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/garyjones/...
 * Requires PHP:      8.2
 * Requires WP:       6.6
 */

declare( strict_types = 1 );

namespace Gamajo\PluginSlug;

use BrightNucleus\Config\ConfigFactory;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'PLUGIN_SLUG_DIR' ) ) {
	define( 'PLUGIN_SLUG_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'PLUGIN_SLUG_URL' ) ) {
	define( 'PLUGIN_SLUG_URL', plugin_dir_url( __FILE__ ) );
}

// Load Composer autoloader.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

// Initialize the plugin.
$GLOBALS['plugin_slug'] = new Plugin( ConfigFactory::create( __DIR__ . '/config/defaults.php' )->getSubConfig( 'Gamajo\PluginSlug' ) );
$GLOBALS['plugin_slug']->run();
