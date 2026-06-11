<?php
/**
 * Stub build asset file
 *
 * Stands in for the admin-settings.asset.php that @wordpress/scripts generates
 * during a real build, so enqueue_admin_assets() has dependencies and a version
 * to read in the unit tests.
 *
 * @package      Gamajo\PluginSlug\Tests
 * @author       Gary Jones
 * @copyright    2026 Gary Jones
 * @license      GPL-2.0-or-later
 */

declare( strict_types = 1 );

return [
	'dependencies' => [ 'wp-dom-ready', 'wp-i18n' ],
	'version'      => 'testhash123',
];
