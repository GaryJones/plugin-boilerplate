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

// The one constant the plugin needs: every path is derived from it at runtime.
define( 'PLUGIN_SLUG_FILE', __FILE__ );

// Load Composer autoloader.
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

// Wire the plugin's features to WordPress on a hook, rather than at include time.
add_action(
	'plugins_loaded',
	static function (): void {
		new Bootstrapper()->init();
	}
);
