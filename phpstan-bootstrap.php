<?php
/**
 * PHPStan bootstrap
 *
 * Declares the plugin constants for static analysis. They are defined at
 * runtime in plugin-slug.php, but PHPStan does not evaluate that define(),
 * so it is told about them here via the config's bootstrapFiles.
 *
 * @package      Gamajo\PluginSlug
 * @author       Gary Jones
 * @copyright    2024-2026 Gary Jones
 * @license      GPL-2.0-or-later
 */

declare( strict_types = 1 );

define( 'PLUGIN_SLUG_FILE', __FILE__ );
