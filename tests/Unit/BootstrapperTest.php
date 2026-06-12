<?php
/**
 * Unit tests for Bootstrapper
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
use Gamajo\PluginSlug\Bootstrapper as Testee;
use Gamajo\PluginSlug\Tests\TestCase;
use Mockery;

/**
 * Bootstrapper test case.
 *
 * @covers \Gamajo\PluginSlug\Bootstrapper
 * @uses   \Gamajo\PluginSlug\SettingsPage
 */
class BootstrapperTest extends TestCase {

	/**
	 * Prepares the test environment before each test.
	 */
	protected function setUp(): void {
		parent::setUp();

		if ( ! defined( 'PLUGIN_SLUG_FILE' ) ) {
			define( 'PLUGIN_SLUG_FILE', '/plugin/plugin-slug.php' );
		}

		// SettingsPage derives its paths from these in its constructor.
		Functions\when( 'plugin_dir_path' )->justReturn( '/plugin/' );
		Functions\when( 'plugin_dir_url' )->justReturn( 'https://example.test/' );
	}

	/**
	 * The init() method registers the settings page feature with WordPress.
	 */
	public function test_init_registers_the_settings_page(): void {
		// SettingsPage::register() adds exactly these three hooks.
		Functions\expect( 'add_action' )->once()->with( 'admin_menu', Mockery::type( Closure::class ) );
		Functions\expect( 'add_action' )->once()->with( 'admin_init', Mockery::type( Closure::class ) );
		Functions\expect( 'add_action' )->once()->with( 'admin_enqueue_scripts', Mockery::type( Closure::class ) );

		new Testee()->init();
	}
}
