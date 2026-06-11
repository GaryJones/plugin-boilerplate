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

use BrightNucleus\Config\ConfigInterface;
use BrightNucleus\Config\ConfigTrait;
use BrightNucleus\Config\Exception\FailedToProcessConfigException;

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
	 * Instantiate a Plugin object.
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
		add_action( 'admin_menu', [ $this, 'register_admin_pages' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
	}

	/**
	 * Register the plugin admin pages from the config.
	 *
	 * Runs on the admin_menu hook, which fires after init, so the page and
	 * menu title closures can be safely resolved to translated strings here.
	 *
	 * @since 0.1.0
	 */
	public function register_admin_pages(): void {
		foreach ( $this->getConfigKey( 'Settings', 'submenu_pages' ) as $page ) {
			add_submenu_page(
				$page['parent_slug'],
				$page['page_title'](),
				$page['menu_title'](),
				$page['capability'],
				$page['menu_slug'],
				static function () use ( $page ): void {
					require $page['view'];
				}
			);
		}
	}

	/**
	 * Register settings, sections and fields from the config.
	 *
	 * Each field passes a label_for argument that matches the id of the
	 * input in the field view, so the rendered field title becomes a label
	 * for the form control.
	 *
	 * @since 0.1.0
	 */
	public function register_settings(): void {
		foreach ( $this->getConfigKey( 'Settings', 'settings' ) as $option_name => $setting ) {
			register_setting(
				$setting['option_group'],
				$option_name,
				[
					'sanitize_callback' => $setting['sanitize_callback'],
				]
			);

			foreach ( $setting['sections'] as $section_id => $section ) {
				add_settings_section(
					$section_id,
					$section['title'](),
					static function () use ( $section ): void {
						require $section['view'];
					},
					$setting['option_group']
				);

				foreach ( $section['fields'] as $field_id => $field ) {
					add_settings_field(
						$field_id,
						$field['title'](),
						// $args is used by the view file pulled in via require.
						// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.Found
						static function ( array $args ) use ( $field ): void {
							require $field['view'];
						},
						$setting['option_group'],
						$section_id,
						[
							'label_for'   => $field_id,
							'option_name' => $option_name,
						]
					);
				}
			}
		}
	}
}
