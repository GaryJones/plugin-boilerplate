<?php
/**
 * PHPUnit bootstrap
 *
 * @package      Gamajo\PluginSlug\Tests
 * @author       Gary Jones
 * @copyright    2017 Gamajo
 * @license      GPL-2.0+
 */

$argv = $GLOBALS['argv'];
$key = array_search( '--testsuite', $argv );

if ( $key && 'Integration' === $argv[ $key + 1 ] ) {
	$plugin_slug_tests_dir = getenv( 'WP_TESTS_DIR' );

	if ( ! $plugin_slug_tests_dir ) {
		$plugin_slug_tests_dir = '/tmp/wordpress-tests-lib';
	}

	// Give access to tests_add_filter() function.
	require_once $plugin_slug_tests_dir . '/includes/functions.php';

	/**
	 * Manually load the plugin being tested.
	 */
	tests_add_filter( 'muplugins_loaded', function () {
		require dirname( __DIR__ ) . '/plugin-slug.php';
	} );

	// Start up the WP testing environment.
	require $plugin_slug_tests_dir . '/includes/bootstrap.php';
}
