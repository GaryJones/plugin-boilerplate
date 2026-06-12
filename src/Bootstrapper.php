<?php
/**
 * Plugin bootstrapper
 *
 * @package      Gamajo\PluginSlug
 * @author       Gary Jones
 * @copyright    2024-2026 Gary Jones
 * @license      GPL-2.0-or-later
 */

declare( strict_types = 1 );

namespace Gamajo\PluginSlug;

/**
 * The composition root.
 *
 * Instantiates the plugin's features and registers them with WordPress. This
 * is the single place where features are wired up; add new ones to init().
 *
 * @since   0.2.0
 *
 * @package Gamajo\PluginSlug
 * @author  Gary Jones
 */
final class Bootstrapper {

	/**
	 * Initialise the plugin.
	 *
	 * @since 0.2.0
	 */
	public function init(): void {
		new SettingsPage()->register();
	}
}
