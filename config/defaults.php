<?php
/**
 * Plugin configuration file
 *
 * Translatable labels are closures, so the translation functions only run
 * when the labels are resolved during hook callbacks (after init). Calling
 * translation functions while this file loads would trigger the
 * _load_textdomain_just_in_time notice in WordPress 6.7+.
 *
 * @package      Gamajo\PluginSlug
 * @author       Gary Jones
 * @copyright    2024-2026 Gary Jones
 * @license      GPL-2.0-or-later
 */

declare( strict_types = 1 );

namespace Gamajo\PluginSlug;

$plugin_slug_settings = [
	'submenu_pages' => [
		[
			'parent_slug' => 'options-general.php',
			'page_title'  => static fn(): string => __( 'Plugin Slug Settings', 'plugin-slug' ),
			'menu_title'  => static fn(): string => __( 'Plugin Slug', 'plugin-slug' ),
			'capability'  => 'manage_options',
			'menu_slug'   => 'plugin-slug',
			'view'        => PLUGIN_SLUG_DIR . 'views/admin-page.php',
		],
	],
	'settings'      => [
		'setting1' => [
			'option_group'      => 'pluginslug',
			'sanitize_callback' => static function ( mixed $value ): array {
				if ( ! is_array( $value ) ) {
					return [];
				}

				return array_map( 'sanitize_text_field', $value );
			},
			'sections'          => [
				'section1' => [
					'title'  => static fn(): string => __( 'My Section Title', 'plugin-slug' ),
					'view'   => PLUGIN_SLUG_DIR . 'views/section1.php',
					'fields' => [
						'field1' => [
							'title' => static fn(): string => __( 'My Field Title', 'plugin-slug' ),
							'view'  => PLUGIN_SLUG_DIR . 'views/field1.php',
						],
					],
				],
			],
		],
	],
];

return [
	'Gamajo' => [
		'PluginSlug' => [
			'Settings' => $plugin_slug_settings,
		],
	],
];
