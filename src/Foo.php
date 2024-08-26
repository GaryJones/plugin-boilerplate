<?php
/**
 * Foo class, does foo
 *
 * @package      Gamajo\PluginSlug
 * @author       Gary Jones
 * @copyright    2024 Gary Jones
 * @license      GPL-2.0-or-later
 */

declare( strict_types = 1 );

namespace Gamajo\PluginSlug;

/**
 * Foo class.
 */
class Foo {
	/**
	 * Bar.
	 */
	public function bar(): string {
		return 'Foo::bar()';
	}

	/**
	 * Returns true, always.
	 *
	 * @return true
	 */
	public function is_true(): bool {
		return true;
	}
}
