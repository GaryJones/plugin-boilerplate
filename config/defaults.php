<?php
/**
 * Plugin configuration file
 *
 * @package      Gamajo\PluginSlug
 * @author       Gary Jones
 * @copyright    2017 Gamajo
 * @license      GPL-2.0+
 */

declare( strict_types = 1 );

namespace Gamajo\PluginSlug;

$plugin_slug_plugin = [
	'textdomain'    => 'plugin-slug',
	'languages_dir' => 'languages',
];

$plugin_slug_settings = [
	'submenu_pages' => [
		[
			'parent_slug'  => 'options-general.php',
			'page_title'   => __( 'Plugin Slug Settings', 'plugin-slug' ),
			'menu_title'   => __( 'Plugin Slug', 'plugin-slug' ),
			'capability'   => 'manage_options',
			'menu_slug'    => 'plugin-slug',
			'view'         => PLUGIN_SLUG_DIR . 'views/admin-page.php',
			'dependencies' => [
				'styles'   => [],
				'scripts'  => [
					[
						'handle'    => 'plugin-slug-js',
						'src'       => PLUGIN_SLUG_URL . 'js/admin-page.js',
						'deps'      => [ 'jquery' ],
						'ver'       => '1.2.3',
						'in_footer' => true,
						// phpcs:ignore NeutronStandard.Functions.TypeHint.NoArgumentType -- Mixed type
						'is_needed' => function ( $context ): bool {
							if ( $context ) {
								return false;
							}

							return true;
						},
						'localize'  => [
							'name' => 'pluginSlugI18n',
							// phpcs:ignore NeutronStandard.Functions.TypeHint.NoArgumentType -- Mixed type
							'data' => function ( $context ): array {
								return [
									'test_localize_data' => 'test_localize_value',
									'context'            => $context,
								];
							},
						],
					],
				],
				'handlers' => [
					'scripts' => 'BrightNucleus\Dependency\ScriptHandler',
					'styles'  => 'BrightNucleus\Dependency\StyleHandler',
				],
			],
		],
	],
	'settings'      => [
		'setting1' => [
			'option_group'      => 'pluginslug',
			'sanitize_callback' => null,
			'sections'          => [
				'section1' => [
					'title'  => __( 'My Section Title', 'plugin-slug' ),
					'view'   => PLUGIN_SLUG_DIR . 'views/section1.php',
					'fields' => [
						'field1' => [
							'title' => __( 'My Field Title', 'plugin-slug' ),
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
			'Plugin'   => $plugin_slug_plugin,
			'Settings' => $plugin_slug_settings,
		],
	],
];
