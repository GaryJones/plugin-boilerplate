<?php
/**
 * Main plugin file
 *
 * @package      Gamajo\PluginSlug
 * @author       Gary Jones
 * @copyright    2024-2026 Gary Jones
 * @license      GPL-2.0-or-later
 */

declare( strict_types = 1 );

namespace Gamajo\PluginSlug;

/**
 * Main plugin class.
 *
 * Registers an example settings page directly against the WordPress API.
 * Swap these registrations for your own; the boilerplate keeps them here,
 * fully typed, rather than behind a configuration abstraction.
 *
 * @since   0.1.0
 *
 * @package Gamajo\PluginSlug
 * @author  Gary Jones
 */
class Plugin {

	/**
	 * Settings page slug, and the page the settings are shown on.
	 *
	 * @since 0.2.0
	 */
	private const string MENU_SLUG = 'plugin-slug';

	/**
	 * Option group the settings are registered against.
	 *
	 * @since 0.2.0
	 */
	private const string OPTION_GROUP = 'plugin_slug';

	/**
	 * Name of the single option the settings are stored in.
	 *
	 * @since 0.2.0
	 */
	private const string OPTION_NAME = 'plugin_slug_settings';

	/**
	 * Section identifier on the settings page.
	 *
	 * @since 0.2.0
	 */
	private const string SECTION_ID = 'plugin_slug_section_general';

	/**
	 * Handle shared by the admin settings script and style.
	 *
	 * @since 0.2.0
	 */
	private const string ASSET_HANDLE = 'plugin-slug-admin-settings';

	/**
	 * Absolute path to the plugin directory, with a trailing slash.
	 *
	 * @since 0.2.0
	 */
	private readonly string $dir;

	/**
	 * URL to the plugin directory, with a trailing slash.
	 *
	 * @since 0.2.0
	 */
	private readonly string $url;

	/**
	 * Hook suffix of the settings page, set once it is registered.
	 *
	 * @since 0.2.0
	 */
	private string $hook_suffix = '';

	/**
	 * Instantiate a Plugin object.
	 *
	 * @since 0.2.0
	 *
	 * @param string $file Absolute path to the main plugin file.
	 */
	public function __construct( string $file ) {
		$this->dir = plugin_dir_path( $file );
		$this->url = plugin_dir_url( $file );
	}

	/**
	 * Launch the initialization process.
	 *
	 * @since 0.1.0
	 */
	public function run(): void {
		add_action( 'admin_menu', $this->register_settings_page( ... ) );
		add_action( 'admin_init', $this->register_settings( ... ) );
		add_action( 'admin_enqueue_scripts', $this->enqueue_admin_assets( ... ) );
	}

	/**
	 * Add the settings page under the Settings admin menu.
	 *
	 * Runs on admin_menu, after init, so the translated titles resolve here.
	 *
	 * @since 0.2.0
	 */
	public function register_settings_page(): void {
		$hook_suffix = add_options_page(
			__( 'Plugin Slug Settings', 'plugin-slug' ),
			__( 'Plugin Slug', 'plugin-slug' ),
			'manage_options',
			self::MENU_SLUG,
			$this->render_settings_page( ... )
		);

		if ( $hook_suffix ) {
			$this->hook_suffix = $hook_suffix;
		}
	}

	/**
	 * Render the settings page wrapper.
	 *
	 * @since 0.2.0
	 */
	private function render_settings_page(): void {
		require $this->dir . 'views/admin-page.php';
	}

	/**
	 * Register the setting, its section and its field.
	 *
	 * The field passes a label_for argument matching the id of the input in
	 * its view, so the rendered field title labels that control.
	 *
	 * @since 0.1.0
	 */
	public function register_settings(): void {
		register_setting(
			self::OPTION_GROUP,
			self::OPTION_NAME,
			[
				'type'              => 'array',
				'sanitize_callback' => $this->sanitize_settings( ... ),
				'default'           => [],
			]
		);

		add_settings_section(
			self::SECTION_ID,
			__( 'My Section Title', 'plugin-slug' ),
			$this->render_section( ... ),
			self::MENU_SLUG
		);

		add_settings_field(
			'field1',
			__( 'My Field Title', 'plugin-slug' ),
			$this->render_field( ... ),
			self::MENU_SLUG,
			self::SECTION_ID,
			[
				'label_for'   => 'field1',
				'option_name' => self::OPTION_NAME,
			]
		);
	}

	/**
	 * Sanitize the submitted settings.
	 *
	 * @since 0.2.0
	 *
	 * @param mixed $value Raw value submitted for the option.
	 * @return array<array-key, string> Sanitized settings.
	 */
	public function sanitize_settings( mixed $value ): array {
		if ( ! is_array( $value ) ) {
			return [];
		}

		$sanitized = [];

		foreach ( $value as $key => $field ) {
			$sanitized[ $key ] = sanitize_text_field( is_scalar( $field ) ? (string) $field : '' );
		}

		return $sanitized;
	}

	/**
	 * Render the settings section description.
	 *
	 * @since 0.2.0
	 */
	private function render_section(): void {
		require $this->dir . 'views/section1.php';
	}

	/**
	 * Render the example settings field.
	 *
	 * @since 0.2.0
	 *
	 * @param array{label_for: string, option_name: string} $args Field arguments.
	 */
	private function render_field( array $args ): void {
		$label_for   = $args['label_for'];
		$option_name = $args['option_name'];

		require $this->dir . 'views/field1.php';
	}

	/**
	 * Enqueue the admin settings script and style.
	 *
	 * Loads only on the plugin's own settings screen. The build/*.asset.php
	 * file is generated during the npm build and supplies the script's
	 * WordPress package dependencies and a content-hash version for cache
	 * busting, so neither has to be maintained by hand.
	 *
	 * @since 0.2.0
	 *
	 * @param string $hook_suffix Admin page identifier passed by WordPress.
	 */
	public function enqueue_admin_assets( string $hook_suffix ): void {
		if ( $hook_suffix !== $this->hook_suffix ) {
			return;
		}

		$asset_file = $this->dir . 'build/admin-settings.asset.php';

		// Built assets are absent until `npm run build` has run.
		if ( ! is_file( $asset_file ) ) {
			return;
		}

		/**
		 * Script dependencies and version, in the shape @wordpress/scripts writes.
		 *
		 * @var array{dependencies: list<string>, version: string} $asset
		 */
		$asset = require $asset_file;

		wp_enqueue_script(
			self::ASSET_HANDLE,
			$this->url . 'build/admin-settings.js',
			$asset['dependencies'],
			$asset['version'],
			[ 'in_footer' => true ]
		);

		// Lets the script's __() calls pick up translations from a .json file.
		wp_set_script_translations( self::ASSET_HANDLE, 'plugin-slug' );

		wp_enqueue_style(
			self::ASSET_HANDLE,
			$this->url . 'build/admin-settings.css',
			[],
			$asset['version']
		);

		// @wordpress/scripts emits a matching -rtl.css that WordPress swaps in
		// for right-to-left locales.
		wp_style_add_data( self::ASSET_HANDLE, 'rtl', 'replace' );
	}
}
