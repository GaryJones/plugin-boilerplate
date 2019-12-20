<?php
/**
 * Unit tests for Plugin
 *
 * @package      Gamajo\PluginSlug\Tests\Unit
 * @author       Gary Jones
 * @copyright    2017 Gamajo
 * @license      GPL-2.0+
 */

declare( strict_types = 1 );

namespace Gamajo\PluginSlug\Tests\Unit;

use Brain\Monkey\Functions;
use BrightNucleus\Config\ConfigFactory;
use BrightNucleus\Config\ConfigInterface;
use Gamajo\PluginSlug\Plugin as Testee;
use Gamajo\PluginSlug\Tests\TestCase;

/**
 * Foo test case.
 */
class PluginTest extends TestCase {
	/**
	 * The method inside the Plugin class which calls `load_plugin_textdomain()`.
	 *
	 * @var string
	 */
	private string $load_textdomain_callback;

	/**
	 * Plugin config for these unit tests.
	 *
	 * @var ConfigInterface
	 */
	private ConfigInterface $mock_config;

	/**
	 * Prepares the test environment before each test.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	protected function setUp(): void {
		$this->load_textdomain_callback = 'load_textdomain';

		$mock_config = [
			'Settings' => [],
			'Plugin'   => [
				'textdomain'    => 'apple',
				'languages_dir' => 'banana',
			],
		];

		$this->mock_config = ConfigFactory::createFromArray( $mock_config );

		parent::setUp();
	}

	/**
	 * Test that method that calls load_plugin_textdomain is hooked in to to the correct hook.
	 */
	public function test_load_plugin_textdomain_method_is_hooked_in_correctly(): void {
		// Create an instance of the class under test.
		$plugin = new Testee( $this->mock_config );
		$plugin->run();

		// Check the plugin method that loads the text domain is hooked into the right filter.
		static::assertNotFalse( has_action( 'plugins_loaded', [ $plugin, $this->load_textdomain_callback ] ), 'Loading textdomain is not hooked in correctly.' );
	}

	/**
	 * Test that load_plugin_textdomain() is called with the correct configurable arguments.
	 */
	public function test_load_plugin_textdomain_called_with_correct_args(): void {
		Functions\expect( 'load_plugin_textdomain' )
			->once()
			->with( 'apple', false, 'apple/banana' );

		// Create an instance of the class under test.
		$plugin = new Testee( $this->mock_config );
		$plugin->{$this->load_textdomain_callback}();
	}
}
