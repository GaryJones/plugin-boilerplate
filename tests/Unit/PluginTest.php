<?php
/**
 * Unit tests for Plugin
 *
 * @package      Gamajo\PluginSlug\Tests\Unit
 * @author       Gary Jones
 * @copyright    2026 Gary Jones
 * @license      GPL-2.0-or-later
 */

declare( strict_types = 1 );

namespace Gamajo\PluginSlug\Tests\Unit;

use Brain\Monkey\Functions;
use BrightNucleus\Config\Config;
use Closure;
use Gamajo\PluginSlug\Plugin as Testee;
use Gamajo\PluginSlug\Tests\TestCase;
use Mockery;
use ReflectionFunction;

/**
 * Plugin test case.
 *
 * Drives the registration of admin pages, settings, sections and fields from a
 * BrightNucleus Config, asserting both the exact arguments passed to the
 * WordPress registration functions and the behaviour of the closures captured
 * by those functions. The closures require view files, so the test Config
 * points the 'view' values at fixtures that echo a known marker.
 *
 * @covers \Gamajo\PluginSlug\Plugin
 */
class PluginTest extends TestCase {

	/**
	 * Directory holding the view fixtures required by the render closures.
	 */
	private string $fixtures;

	/**
	 * Prepares the test environment before each test.
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->fixtures = dirname( __DIR__ ) . '/fixtures/';

		// The field fixture mirrors the real view and escapes its output.
		Functions\when( 'esc_html' )->returnArg();
	}

	/**
	 * Build the test Config consumed by the Plugin.
	 *
	 * The shape mirrors config/defaults.php but stays minimal: one submenu page
	 * and one setting that contains one section with one field. Translatable
	 * labels are closures and 'view' values point at the fixtures.
	 *
	 * @return Config Config to parametrize the Plugin.
	 */
	private function make_config(): Config {
		return new Config(
			[
				'Settings' => [
					'submenu_pages' => [
						[
							'parent_slug' => 'options-general.php',
							'page_title'  => static fn(): string => 'Page Title',
							'menu_title'  => static fn(): string => 'Menu Title',
							'capability'  => 'manage_options',
							'menu_slug'   => 'plugin-slug',
							'view'        => $this->fixtures . 'page.php',
						],
					],
					'settings'      => [
						'option1' => [
							'option_group'      => 'pluginslug',
							'sanitize_callback' => static fn( $value ): string => 'sanitized:' . $value,
							'sections'          => [
								'section1' => [
									'title'  => static fn(): string => 'Section Title',
									'view'   => $this->fixtures . 'section.php',
									'fields' => [
										'field1' => [
											'title' => static fn(): string => 'Field Title',
											'view'  => $this->fixtures . 'field.php',
										],
									],
								],
							],
						],
					],
				],
				'Assets'   => [
					'admin' => [
						'asset_file' => $this->fixtures . 'build/admin-settings.asset.php',
						'script'     => 'https://example.test/build/admin-settings.js',
						'style'      => 'https://example.test/build/admin-settings.css',
					],
				],
			]
		);
	}

	/**
	 * Capture the output produced by invoking a render closure.
	 *
	 * @param Closure $closure Render closure captured from a registration call.
	 * @param array   ...$args Arguments to pass to the closure.
	 *
	 * @return string Buffered output.
	 */
	private function capture( Closure $closure, array ...$args ): string {
		ob_start();
		$closure( ...$args );

		return (string) ob_get_clean();
	}

	/**
	 * The run() method wires register_admin_pages() to the admin_menu hook.
	 */
	public function test_run_hooks_register_admin_pages_onto_admin_menu(): void {
		$plugin = new Testee( $this->make_config() );

		Functions\expect( 'add_action' )
			->once()
			->with(
				'admin_menu',
				Mockery::on(
					static function ( $candidate ) use ( $plugin ): bool {
						if ( ! $candidate instanceof Closure ) {
							return false;
						}

						$reflection = new ReflectionFunction( $candidate );

						return 'register_admin_pages' === $reflection->getName()
							&& $plugin === $reflection->getClosureThis();
					}
				)
			);

		// The other hooks are added too, but are asserted by other tests.
		Functions\expect( 'add_action' )->with( 'admin_init', Mockery::any() );
		Functions\expect( 'add_action' )->with( 'admin_enqueue_scripts', Mockery::any() );

		$plugin->run();
	}

	/**
	 * The run() method wires register_settings() to the admin_init hook.
	 */
	public function test_run_hooks_register_settings_onto_admin_init(): void {
		$plugin = new Testee( $this->make_config() );

		Functions\expect( 'add_action' )->with( 'admin_menu', Mockery::any() );
		Functions\expect( 'add_action' )->with( 'admin_enqueue_scripts', Mockery::any() );

		Functions\expect( 'add_action' )
			->once()
			->with(
				'admin_init',
				Mockery::on(
					static function ( $candidate ) use ( $plugin ): bool {
						if ( ! $candidate instanceof Closure ) {
							return false;
						}

						$reflection = new ReflectionFunction( $candidate );

						return 'register_settings' === $reflection->getName()
							&& $plugin === $reflection->getClosureThis();
					}
				)
			);

		$plugin->run();
	}

	/**
	 * The run() method wires enqueue_admin_assets() to admin_enqueue_scripts.
	 */
	public function test_run_hooks_enqueue_admin_assets_onto_admin_enqueue_scripts(): void {
		$plugin = new Testee( $this->make_config() );

		Functions\expect( 'add_action' )->with( 'admin_menu', Mockery::any() );
		Functions\expect( 'add_action' )->with( 'admin_init', Mockery::any() );

		Functions\expect( 'add_action' )
			->once()
			->with(
				'admin_enqueue_scripts',
				Mockery::on(
					static function ( $candidate ) use ( $plugin ): bool {
						if ( ! $candidate instanceof Closure ) {
							return false;
						}

						$reflection = new ReflectionFunction( $candidate );

						return 'enqueue_admin_assets' === $reflection->getName()
							&& $plugin === $reflection->getClosureThis();
					}
				)
			);

		$plugin->run();
	}

	/**
	 * Each submenu page is registered with exact arguments.
	 */
	public function test_register_admin_pages_passes_exact_arguments(): void {
		$plugin = new Testee( $this->make_config() );

		Functions\expect( 'add_submenu_page' )
			->once()
			->with(
				'options-general.php',
				'Page Title',
				'Menu Title',
				'manage_options',
				'plugin-slug',
				Mockery::type( Closure::class )
			);

		$plugin->register_admin_pages();
	}

	/**
	 * The page render closure requires the configured view file.
	 */
	public function test_register_admin_pages_render_closure_requires_view(): void {
		$plugin = new Testee( $this->make_config() );

		$captured = null;

		Functions\expect( 'add_submenu_page' )
			->once()
			->with(
				Mockery::any(),
				Mockery::any(),
				Mockery::any(),
				Mockery::any(),
				Mockery::any(),
				Mockery::on(
					static function ( $candidate ) use ( &$captured ): bool {
						$captured = $candidate;

						return $candidate instanceof Closure;
					}
				)
			);

		$plugin->register_admin_pages();

		static::assertInstanceOf( Closure::class, $captured );
		static::assertSame( 'PAGE_FIXTURE_OUTPUT', $this->capture( $captured ) );
	}

	/**
	 * The setting is registered with exact arguments.
	 */
	public function test_register_settings_registers_setting_with_exact_arguments(): void {
		$config = $this->make_config();
		$plugin = new Testee( $config );

		$sanitize = $config->getKey( [ 'Settings', 'settings', 'option1', 'sanitize_callback' ] );

		Functions\expect( 'register_setting' )
			->once()
			->with(
				'pluginslug',
				'option1',
				Mockery::on(
					static fn( $args ): bool => is_array( $args )
						&& array_keys( $args ) === [ 'sanitize_callback' ]
						&& $args['sanitize_callback'] === $sanitize
				)
			);

		Functions\expect( 'add_settings_section' )->andReturnNull();
		Functions\expect( 'add_settings_field' )->andReturnNull();

		$plugin->register_settings();
	}

	/**
	 * The sanitize callback wired into the setting is the one from the Config.
	 */
	public function test_register_settings_uses_config_sanitize_callback(): void {
		$config = $this->make_config();
		$plugin = new Testee( $config );

		$captured = null;

		Functions\expect( 'register_setting' )
			->once()
			->with(
				Mockery::any(),
				Mockery::any(),
				Mockery::on(
					static function ( array $args ) use ( &$captured ): bool {
						$captured = $args['sanitize_callback'] ?? null;

						return true;
					}
				)
			);

		Functions\expect( 'add_settings_section' )->andReturnNull();
		Functions\expect( 'add_settings_field' )->andReturnNull();

		$plugin->register_settings();

		static::assertInstanceOf( Closure::class, $captured );
		static::assertSame( 'sanitized:raw', $captured( 'raw' ) );
	}

	/**
	 * Each section is registered with exact arguments.
	 */
	public function test_register_settings_registers_section_with_exact_arguments(): void {
		$plugin = new Testee( $this->make_config() );

		Functions\expect( 'register_setting' )->andReturnNull();

		Functions\expect( 'add_settings_section' )
			->once()
			->with(
				'section1',
				'Section Title',
				Mockery::type( Closure::class ),
				'pluginslug'
			);

		Functions\expect( 'add_settings_field' )->andReturnNull();

		$plugin->register_settings();
	}

	/**
	 * The section render closure requires the configured view file.
	 */
	public function test_register_settings_section_closure_requires_view(): void {
		$plugin = new Testee( $this->make_config() );

		$captured = null;

		Functions\expect( 'register_setting' )->andReturnNull();

		Functions\expect( 'add_settings_section' )
			->once()
			->with(
				Mockery::any(),
				Mockery::any(),
				Mockery::on(
					static function ( $candidate ) use ( &$captured ): bool {
						$captured = $candidate;

						return $candidate instanceof Closure;
					}
				),
				Mockery::any()
			);

		Functions\expect( 'add_settings_field' )->andReturnNull();

		$plugin->register_settings();

		static::assertInstanceOf( Closure::class, $captured );
		static::assertSame( 'SECTION_FIXTURE_OUTPUT', $this->capture( $captured ) );
	}

	/**
	 * Each field is registered with exact arguments.
	 */
	public function test_register_settings_registers_field_with_exact_arguments(): void {
		$plugin = new Testee( $this->make_config() );

		Functions\expect( 'register_setting' )->andReturnNull();
		Functions\expect( 'add_settings_section' )->andReturnNull();

		Functions\expect( 'add_settings_field' )
			->once()
			->with(
				'field1',
				'Field Title',
				Mockery::type( Closure::class ),
				'pluginslug',
				'section1',
				[
					'label_for'   => 'field1',
					'option_name' => 'option1',
				]
			);

		$plugin->register_settings();
	}

	/**
	 * The field render closure requires the view and receives the $args array.
	 */
	public function test_register_settings_field_closure_requires_view_with_args(): void {
		$plugin = new Testee( $this->make_config() );

		$captured = null;

		Functions\expect( 'register_setting' )->andReturnNull();
		Functions\expect( 'add_settings_section' )->andReturnNull();

		Functions\expect( 'add_settings_field' )
			->once()
			->with(
				Mockery::any(),
				Mockery::any(),
				Mockery::on(
					static function ( $candidate ) use ( &$captured ): bool {
						$captured = $candidate;

						return $candidate instanceof Closure;
					}
				),
				Mockery::any(),
				Mockery::any(),
				Mockery::any()
			);

		$plugin->register_settings();

		static::assertInstanceOf( Closure::class, $captured );

		$output = $this->capture(
			$captured,
			[
				'label_for'   => 'field1',
				'option_name' => 'option1',
			]
		);

		static::assertSame( 'FIELD_FIXTURE_OUTPUT:field1:option1', $output );
	}

	/**
	 * Register the admin pages with a stubbed hook suffix.
	 *
	 * Captures the suffix into the plugin's page_hooks so the enqueue tests
	 * can drive the on-screen check, mirroring how WordPress returns a hook
	 * suffix from add_submenu_page().
	 *
	 * @param Testee $plugin      Plugin under test.
	 * @param string $hook_suffix Hook suffix add_submenu_page() should return.
	 */
	private function register_pages_returning( Testee $plugin, string $hook_suffix ): void {
		Functions\expect( 'add_submenu_page' )->once()->andReturn( $hook_suffix );

		$plugin->register_admin_pages();
	}

	/**
	 * Assets are not enqueued on admin pages other than the plugin's own.
	 */
	public function test_enqueue_admin_assets_skips_pages_outside_the_plugin(): void {
		$plugin = new Testee( $this->make_config() );
		$this->register_pages_returning( $plugin, 'settings_page_plugin-slug' );

		Functions\expect( 'wp_enqueue_script' )->never();
		Functions\expect( 'wp_enqueue_style' )->never();

		$plugin->enqueue_admin_assets( 'index.php' );
	}

	/**
	 * The built script and style are enqueued on the plugin's own page.
	 */
	public function test_enqueue_admin_assets_enqueues_the_built_script_and_style(): void {
		$plugin = new Testee( $this->make_config() );
		$this->register_pages_returning( $plugin, 'settings_page_plugin-slug' );

		Functions\expect( 'wp_enqueue_script' )
			->once()
			->with(
				'plugin-slug-admin-settings',
				'https://example.test/build/admin-settings.js',
				[ 'wp-dom-ready', 'wp-i18n' ],
				'testhash123',
				[ 'in_footer' => true ]
			);

		Functions\expect( 'wp_set_script_translations' )
			->once()
			->with( 'plugin-slug-admin-settings', 'plugin-slug' );

		Functions\expect( 'wp_enqueue_style' )
			->once()
			->with(
				'plugin-slug-admin-settings',
				'https://example.test/build/admin-settings.css',
				[],
				'testhash123'
			);

		Functions\expect( 'wp_style_add_data' )
			->once()
			->with( 'plugin-slug-admin-settings', 'rtl', 'replace' );

		$plugin->enqueue_admin_assets( 'settings_page_plugin-slug' );
	}
}
