<?php
/**
 * Main plugin file, hooking into WordPress.
 *
 * @package   Gamajo\PluginSlug
 * @author    Gary Jones
 * @link      http://gamajo.com
 * @copyright 2015 Gary Jones, Gamajo Tech
 * @license   GPL-2.0+
 */

namespace Gamajo\PluginSlug;

/**
 *
 */
class Plugin {
	protected $foo;

	function __construct( $foo = null ) {
		$this->foo = $foo;
	}

	public function setup_hooks() {
		// add hooks
		add_action( 'genesis_before', function() {
			echo 'Woohoo!';
		});

		add_action( 'wp_footer', array( $this->foo, 'bar' ) );
	}
}
