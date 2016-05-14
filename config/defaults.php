<?php
/**
 * Plugin configuration file
 *
 * @package   Gamajo\PluginSlug
 * @author    Gary Jones
 * @copyright 2016 Gamajo Tech
 * @license   GPL-2.0+
 */

namespace Gamajo\PluginSlug;

$plugin = [
	'textdomain' => 'plugin-slug',
];

$settings = [
	'submenu_pages' => [
		[
			'parent_slug' => 'options-general.php',
			'page_title'  => __( 'Plugin Slug Settings', 'plugin-slug' ),
			'menu_title'  => __( 'Plugin Slug', 'plugin-slug' ),
			'capability'  => 'manage_options',
			'menu_slug'   => 'pluginslug',
			'view'        => GAMAJO_PLUGINSLUG_DIR . 'views/admin-page.php',
			'dependencies' => [
				'styles'   => [],
				'scripts'  => [
					[
						'handle'    => 'pluginslug-js',
						'src'       => GAMAJO_PLUGINSLUG_URL . 'js/admin-page.js',
						'deps'      => [ 'jquery' ],
						'ver'       => '1.2.3',
						'in_footer' => true,
						'is_needed' => function ( $context ) { return true; },
						'localize'  => [
							'name' => 'pluginSlugI18n',
							'data' => function ( $context ) {
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
	'settings'    => [
		'setting1' => [
			'option_group'      => 'pluginslug',
			'sanitize_callback' => null,
			'sections'          => [
				'section1' => [
					'title'  => __( 'My Section Title', 'pluginslug' ),
					'view'   => GAMAJO_PLUGINSLUG_DIR . 'views/section1.php',
					'fields' => [
						'field1' => [
							'title' => __( 'My Field Title', 'pluginslug' ),
							'view'  => GAMAJO_PLUGINSLUG_DIR . 'views/field1.php',
						],
					],
				],
			],
		],
	],
];

$pluginslug = [
	'Plugin'   => $plugin,
	'Settings' => $settings,
];

return [
	'Gamajo' => [
		'PluginSlug' => $pluginslug,
	],
];
