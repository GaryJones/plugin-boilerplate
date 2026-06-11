<?php
/**
 * Plugin uninstall handler
 *
 * Removes the options created by the plugin when it is deleted via the
 * WordPress admin.
 *
 * @package      Gamajo\PluginSlug
 * @author       Gary Jones
 * @copyright    2026 Gary Jones
 * @license      GPL-2.0-or-later
 */

declare( strict_types = 1 );

// Exit if this file is not reached as part of an actual plugin uninstall.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'setting1' );
