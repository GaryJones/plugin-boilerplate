<?php
/**
 * Plugin Name
 *
 * The GitHub Plugin URI header below is read by Git Updater (formerly
 * GitHub Updater) to serve plugin updates directly from GitHub.
 *
 * @package      Gamajo\PluginSlug
 * @author       Gary Jones
 * @copyright    2024-2026 Gary Jones
 * @license      GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Plugin Boilerplate
 * Plugin URI:        https://github.com/GaryJones/plugin-slug
 * Description:       ...
 * Version:           0.1.0
 * Author:            Gary Jones
 * Author URI:        https://garyjones.io
 * Text Domain:       plugin-slug
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * GitHub Plugin URI: https://github.com/GaryJones/plugin-slug
 * Requires PHP:      8.4
 * Requires at least: 6.9
 */

declare( strict_types = 1 );

namespace Gamajo\PluginSlug;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Load Composer autoloader.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

// Initialize the plugin on a hook, rather than at file include time.
add_action(
	'plugins_loaded',
	static function (): void {
		plugin_slug()->run();
	}
);

/**
 * Get the plugin instance.
 *
 * Builds and caches the Plugin object on first call, so no work happens
 * when this file is merely included.
 *
 * @since 0.1.0
 *
 * @return Plugin Plugin instance.
 */
function plugin_slug(): Plugin {
	static $plugin = null;

	if ( ! $plugin instanceof Plugin ) {
		$plugin = new Plugin( __FILE__ );
	}

	return $plugin;
}
