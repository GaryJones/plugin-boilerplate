<?php
/**
 * Integration tests for Foo
 *
 * @package      Gamajo\PluginSlug\Tests\Integration
 * @author       Gary Jones
 * @copyright    2024 Gary Jones
 * @license      GPL-2.0-or-later
 */

declare( strict_types = 1 );

namespace Gamajo\PluginSlug\Tests\Integration;

use Gamajo\PluginSlug\Foo as Testee;
use WP_UnitTestCase;

/**
 * Foo test case.
 */
class FooTest extends WP_UnitTestCase {
	/**
	 * A single example test.
	 */
	public function test_foo(): void {
		// Replace this with some actual integration testing code.
		static::assertTrue( ( new Testee() )->is_true() );
	}
}
