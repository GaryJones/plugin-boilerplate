<?php
/**
 * Initialise the plugin
 *
 * This file can use syntax from the required level of PHP or later.
 *
 * @package      Gamajo\PluginSlug
 * @author       Gary Jones
 * @copyright    2017 Gamajo
 * @license      GPL-2.0+
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
if ( file_exists( plugin_dir_path( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';
}

// Initialize the plugin.
$plugin_slug_config = ConfigFactory::create( __DIR__ . '/config/defaults.php' );
Plugin::get_instance( $plugin_slug_config->getSubConfig( 'Gamajo\PluginSlug' ) )->run();
