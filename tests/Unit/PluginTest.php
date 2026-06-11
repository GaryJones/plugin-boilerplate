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
use Closure;
use Gamajo\PluginSlug\Plugin as Testee;
use Gamajo\PluginSlug\Tests\TestCase;
use Mockery;
use ReflectionFunction;

/**
 * Plugin test case.
 *
 * Asserts the exact arguments passed to the WordPress registration functions
 * and the behaviour of the render callbacks captured by them. The callbacks
 * require view files, so the plugin directory is pointed at fixtures that echo
 * a known marker.
 *
 * @covers \Gamajo\PluginSlug\Plugin
 */
class PluginTest extends TestCase {

	/**
	 * Directory holding the view and build fixtures.
	 */
	private string $fixtures;

	/**
	 * Prepares the test environment before each test.
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->fixtures = dirname( __DIR__ ) . '/fixtures/';

		// Titles are passed through unchanged so exact-argument assertions read clearly.
		Functions\when( '__' )->returnArg();
	}

	/**
	 * Build a Plugin whose directory and URL are stubbed.
	 *
	 * @param string|null $dir Directory the plugin should resolve to. Defaults
	 *                         to the fixtures directory.
	 * @return Testee Plugin under test.
	 */
	private function make_plugin( ?string $dir = null ): Testee {
		Functions\when( 'plugin_dir_path' )->justReturn( $dir ?? $this->fixtures );
		Functions\when( 'plugin_dir_url' )->justReturn( 'https://example.test/' );

		return new Testee( '/plugin/plugin-slug.php' );
	}

	/**
	 * Register the settings page so the captured hook suffix is available.
	 *
	 * @param Testee      $plugin      Plugin under test.
	 * @param string|bool $hook_suffix Value add_options_page() should return.
	 */
	private function register_page_returning( Testee $plugin, string|bool $hook_suffix ): void {
		Functions\expect( 'add_options_page' )->once()->andReturn( $hook_suffix );

		$plugin->register_settings_page();
	}

	/**
	 * Capture the output produced by invoking a render callback.
	 *
	 * @param Closure               $callback Callback captured from a registration call.
	 * @param array<string, string> ...$args  Arguments to pass to the callback.
	 *
	 * @return string Buffered output.
	 */
	private function capture( Closure $callback, array ...$args ): string {
		ob_start();
		$callback( ...$args );

		return (string) ob_get_clean();
	}

	/**
	 * Build a Mockery matcher asserting a first-class callable wraps a method.
	 *
	 * @param Testee $plugin Plugin the callable should be bound to.
	 * @param string $method Method name the callable should wrap.
	 *
	 * @return object Mockery matcher.
	 */
	private function bound_to( Testee $plugin, string $method ): object {
		return Mockery::on(
			fn( $candidate ): bool => $this->is_bound_to( $candidate, $plugin, $method )
		);
	}

	/**
	 * The run() method wires register_settings_page() onto admin_menu.
	 */
	public function test_run_registers_settings_page_on_admin_menu(): void {
		$plugin = $this->make_plugin();

		Functions\expect( 'add_action' )->once()->with( 'admin_menu', $this->bound_to( $plugin, 'register_settings_page' ) );
		Functions\expect( 'add_action' )->with( 'admin_init', Mockery::any() );
		Functions\expect( 'add_action' )->with( 'admin_enqueue_scripts', Mockery::any() );

		$plugin->run();
	}

	/**
	 * The run() method wires register_settings() onto admin_init.
	 */
	public function test_run_registers_settings_on_admin_init(): void {
		$plugin = $this->make_plugin();

		Functions\expect( 'add_action' )->with( 'admin_menu', Mockery::any() );
		Functions\expect( 'add_action' )->once()->with( 'admin_init', $this->bound_to( $plugin, 'register_settings' ) );
		Functions\expect( 'add_action' )->with( 'admin_enqueue_scripts', Mockery::any() );

		$plugin->run();
	}

	/**
	 * The run() method wires enqueue_admin_assets() onto admin_enqueue_scripts.
	 */
	public function test_run_enqueues_assets_on_admin_enqueue_scripts(): void {
		$plugin = $this->make_plugin();

		Functions\expect( 'add_action' )->with( 'admin_menu', Mockery::any() );
		Functions\expect( 'add_action' )->with( 'admin_init', Mockery::any() );
		Functions\expect( 'add_action' )->once()->with( 'admin_enqueue_scripts', $this->bound_to( $plugin, 'enqueue_admin_assets' ) );

		$plugin->run();
	}

	/**
	 * The settings page is added under the Settings menu with exact arguments.
	 */
	public function test_register_settings_page_adds_options_page_with_exact_arguments(): void {
		$plugin = $this->make_plugin();

		Functions\expect( 'add_options_page' )
			->once()
			->with(
				'Plugin Slug Settings',
				'Plugin Slug',
				'manage_options',
				'plugin-slug',
				Mockery::type( Closure::class )
			)
			->andReturn( 'settings_page_plugin-slug' );

		$plugin->register_settings_page();
	}

	/**
	 * The settings page callback renders the page view.
	 */
	public function test_settings_page_callback_renders_the_view(): void {
		$plugin   = $this->make_plugin();
		$captured = null;

		Functions\expect( 'add_options_page' )
			->once()
			->with(
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
			)
			->andReturn( 'settings_page_plugin-slug' );

		$plugin->register_settings_page();

		static::assertInstanceOf( Closure::class, $captured );
		static::assertSame( 'ADMIN_PAGE_FIXTURE', $this->capture( $captured ) );
	}

	/**
	 * The setting is registered with exact arguments.
	 */
	public function test_register_settings_registers_setting_with_exact_arguments(): void {
		$plugin = $this->make_plugin();

		Functions\expect( 'register_setting' )
			->once()
			->with(
				'plugin_slug',
				'plugin_slug_settings',
				Mockery::on(
					fn( $args ): bool => is_array( $args )
						&& 'array' === $args['type']
						&& [] === $args['default']
						&& $this->is_bound_to( $args['sanitize_callback'], $plugin, 'sanitize_settings' )
				)
			);

		Functions\expect( 'add_settings_section' )->andReturnNull();
		Functions\expect( 'add_settings_field' )->andReturnNull();

		$plugin->register_settings();
	}

	/**
	 * The section is registered with exact arguments.
	 */
	public function test_register_settings_adds_section_with_exact_arguments(): void {
		$plugin = $this->make_plugin();

		Functions\expect( 'register_setting' )->andReturnNull();
		Functions\expect( 'add_settings_section' )
			->once()
			->with(
				'plugin_slug_section_general',
				'My Section Title',
				Mockery::type( Closure::class ),
				'plugin-slug'
			);
		Functions\expect( 'add_settings_field' )->andReturnNull();

		$plugin->register_settings();
	}

	/**
	 * The section callback renders the section view.
	 */
	public function test_section_callback_renders_the_view(): void {
		$plugin   = $this->make_plugin();
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
		static::assertSame( 'SECTION_FIXTURE', $this->capture( $captured ) );
	}

	/**
	 * The field is registered with exact arguments.
	 */
	public function test_register_settings_adds_field_with_exact_arguments(): void {
		$plugin = $this->make_plugin();

		Functions\expect( 'register_setting' )->andReturnNull();
		Functions\expect( 'add_settings_section' )->andReturnNull();
		Functions\expect( 'add_settings_field' )
			->once()
			->with(
				'field1',
				'My Field Title',
				Mockery::type( Closure::class ),
				'plugin-slug',
				'plugin_slug_section_general',
				[
					'label_for'   => 'field1',
					'option_name' => 'plugin_slug_settings',
				]
			);

		$plugin->register_settings();
	}

	/**
	 * The field callback renders the field view with the args passed through.
	 */
	public function test_field_callback_renders_the_view_with_args(): void {
		$plugin   = $this->make_plugin();
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
				'option_name' => 'plugin_slug_settings',
			]
		);

		static::assertSame( 'FIELD_FIXTURE:field1:plugin_slug_settings', $output );
	}

	/**
	 * Non-array input sanitizes to an empty array.
	 */
	public function test_sanitize_settings_returns_empty_array_for_non_array(): void {
		static::assertSame( [], $this->make_plugin()->sanitize_settings( 'not-an-array' ) );
	}

	/**
	 * Each scalar field is passed through sanitize_text_field.
	 */
	public function test_sanitize_settings_sanitizes_each_scalar_field(): void {
		// Mockery::type( 'string' ) asserts each field is cast to a string before
		// it reaches sanitize_text_field, which under strict_types it must be.
		Functions\expect( 'sanitize_text_field' )
			->twice()
			->with( Mockery::type( 'string' ) )
			->andReturnUsing( static fn( string $value ): string => 'clean:' . $value );

		static::assertSame(
			[
				'field1' => 'clean:hello',
				'field2' => 'clean:42',
			],
			$this->make_plugin()->sanitize_settings(
				[
					'field1' => 'hello',
					'field2' => 42,
				]
			)
		);
	}

	/**
	 * Non-scalar fields are replaced with an empty string before sanitizing.
	 */
	public function test_sanitize_settings_replaces_non_scalar_fields(): void {
		Functions\expect( 'sanitize_text_field' )->andReturnArg( 0 );

		static::assertSame(
			[ 'field1' => '' ],
			$this->make_plugin()->sanitize_settings( [ 'field1' => [ 'nested' ] ] )
		);
	}

	/**
	 * Assets are not enqueued on admin pages other than the plugin's own.
	 */
	public function test_enqueue_skips_other_pages(): void {
		$plugin = $this->make_plugin();
		$this->register_page_returning( $plugin, 'settings_page_plugin-slug' );

		Functions\expect( 'wp_enqueue_script' )->never();
		Functions\expect( 'wp_enqueue_style' )->never();

		$plugin->enqueue_admin_assets( 'index.php' );
	}

	/**
	 * Nothing is enqueued when the settings page failed to register.
	 */
	public function test_enqueue_skips_when_page_registration_failed(): void {
		$plugin = $this->make_plugin();
		$this->register_page_returning( $plugin, false );

		Functions\expect( 'wp_enqueue_script' )->never();
		Functions\expect( 'wp_enqueue_style' )->never();

		$plugin->enqueue_admin_assets( 'settings_page_plugin-slug' );
	}

	/**
	 * Nothing is enqueued before the assets have been built.
	 */
	public function test_enqueue_skips_when_build_is_absent(): void {
		$plugin = $this->make_plugin( '/no/such/directory/' );
		$this->register_page_returning( $plugin, 'settings_page_plugin-slug' );

		Functions\expect( 'wp_enqueue_script' )->never();
		Functions\expect( 'wp_enqueue_style' )->never();

		$plugin->enqueue_admin_assets( 'settings_page_plugin-slug' );
	}

	/**
	 * The built script and style are enqueued on the plugin's own page.
	 */
	public function test_enqueue_enqueues_the_built_script_and_style(): void {
		$plugin = $this->make_plugin();
		$this->register_page_returning( $plugin, 'settings_page_plugin-slug' );

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

	/**
	 * Whether a candidate is a first-class callable wrapping a bound method.
	 *
	 * @param mixed  $candidate Value to inspect.
	 * @param Testee $plugin    Plugin the callable should be bound to.
	 * @param string $method    Method name the callable should wrap.
	 */
	private function is_bound_to( mixed $candidate, Testee $plugin, string $method ): bool {
		if ( ! $candidate instanceof Closure ) {
			return false;
		}

		$reflection = new ReflectionFunction( $candidate );

		return $method === $reflection->getName() && $plugin === $reflection->getClosureThis();
	}
}
