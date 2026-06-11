<?php
/**
 * Foo class, does foo
 *
 * @package      Gamajo\PluginSlug
 * @author       Gary Jones
 * @copyright    2024-2026 Gary Jones
 * @license      GPL-2.0-or-later
 */

declare( strict_types = 1 );

namespace Gamajo\PluginSlug;

/**
 * Foo class.
 *
 * @since 0.1.0
 */
class Foo {
	/**
	 * Bar.
	 *
	 * @since 0.1.0
	 *
	 * @return string Identification of the method being called.
	 */
	public function bar(): string {
		return 'Foo::bar()';
	}

	/**
	 * Returns true, always.
	 *
	 * @since 0.1.0
	 *
	 * @return true
	 */
	public function is_true(): bool {
		return true;
	}
}
