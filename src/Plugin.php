<?php
/**
 * Main plugin file
 *
 * @package      Gamajo\PluginSlug
 * @author       Gary Jones
 * @copyright    2017 Gamajo
 * @license      GPL-2.0+
 */

declare( strict_types = 1 );

namespace Gamajo\PluginSlug;

use BrightNucleus\Config\ConfigInterface;
use BrightNucleus\Config\ConfigTrait;
use BrightNucleus\Config\Exception\FailedToProcessConfigException;
use BrightNucleus\Settings\Settings;

/**
 * Main plugin class.
 *
 * @since   0.1.0
 *
 * @package Gamajo\PluginSlug
 * @author  Gary Jones
 */
class Plugin {

	use ConfigTrait;

	/**
	 * Static instance of the plugin.
	 *
	 * @since 0.1.0
	 *
	 * @var self
	 */
	protected static Plugin $instance;

	/**
	 * Instantiate a Plugin object.
	 *
	 * Don't call the constructor directly, use the `Plugin::get_instance()`
	 * static method instead.
	 *
	 * @since 0.1.0
	 *
	 * @throws FailedToProcessConfigException If the Config could not be parsed correctly.
	 *
	 * @param ConfigInterface $config Config to parametrize the object.
	 */
	public function __construct( ConfigInterface $config ) {
		$this->processConfig( $config );
	}

	/**
	 * Launch the initialization process.
	 *
	 * @since 0.1.0
	 */
	public function run(): void {
		\add_action( 'plugins_loaded', [ $this, 'load_textdomain' ] );
	}

	/**
	 * Load the plugin text domain.
	 *
	 * @since 0.1.0
	 */
	public function load_textdomain(): void {
		/**
		 * Plugin text domain.
		 *
		 * @var string
		 */
		$text_domain   = $this->config->getKey( 'Plugin.textdomain' );
		$languages_dir = 'languages';
		if ( $this->config->hasKey( 'Plugin/languages_dir' ) ) {
			/**
			 * Directory path.
			 *
			 * @var string
			 */
			$languages_dir = $this->config->getKey( 'Plugin.languages_dir' );
		}

		\load_plugin_textdomain( $text_domain, false, $text_domain . '/' . $languages_dir );
	}
}
