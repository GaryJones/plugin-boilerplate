<?php
/**
 * PHPUnit bootstrap
 *
 * Loads the WordPress core test framework only when PHPUnit is invoked with
 * the integration test suite, i.e. with `--testsuite integration` or
 * `--testsuite=integration` on the command line. Unit tests run without
 * WordPress loaded, so Brain Monkey can stub WordPress functions instead.
 *
 * @package      Gamajo\PluginSlug\Tests
 * @author       Gary Jones
 * @copyright    2024-2026 Gary Jones
 * @license      GPL-2.0-or-later
 */

declare( strict_types = 1 );

namespace Gamajo\PluginSlug\Tests;

// Check for a `--testsuite integration` or `--testsuite=integration` arg when
// calling phpunit, and use it to conditionally load up WordPress.
$plugin_slug_argv           = $GLOBALS['argv'] ?? [];
$plugin_slug_is_integration = false;

foreach ( $plugin_slug_argv as $plugin_slug_index => $plugin_slug_arg ) {
	if ( '--testsuite=integration' === $plugin_slug_arg ) {
		$plugin_slug_is_integration = true;
		break;
	}

	if ( '--testsuite' === $plugin_slug_arg && 'integration' === ( $plugin_slug_argv[ $plugin_slug_index + 1 ] ?? '' ) ) {
		$plugin_slug_is_integration = true;
		break;
	}
}

if ( $plugin_slug_is_integration ) {
	$plugin_slug_tests_dir = getenv( 'WP_TESTS_DIR' );

	if ( ! $plugin_slug_tests_dir ) {
		// wp-env exposes the WP test suite at this path inside the tests-cli container.
		$plugin_slug_tests_dir = '/wordpress-phpunit';
	}

	// Give access to tests_add_filter() function.
	require_once $plugin_slug_tests_dir . '/includes/functions.php';

	/**
	 * Manually load the plugin being tested.
	 */
	\tests_add_filter(
		'muplugins_loaded',
		function (): void {
			require dirname( __DIR__ ) . '/plugin-slug.php';
		}
	);

	// Start up the WP testing environment.
	require $plugin_slug_tests_dir . '/includes/bootstrap.php';
} else {
	// Eagerly load the plugin classes for the unit suite.
	//
	// Brain Monkey loads Patchwork on the first Monkey\setUp() call, and
	// Patchwork's stream wrapper permanently replaces Infection's include
	// interceptor, so classes autoloaded after that point would receive the
	// original code instead of the mutated code, and every mutant would
	// escape. Loading the classes here, before any test runs, means
	// Infection's interceptor is still in place to serve the mutants.
	$plugin_slug_src_files = new \RecursiveIteratorIterator(
		new \RecursiveDirectoryIterator( dirname( __DIR__ ) . '/src', \FilesystemIterator::SKIP_DOTS )
	);

	foreach ( $plugin_slug_src_files as $plugin_slug_src_file ) {
		if ( 'php' === $plugin_slug_src_file->getExtension() ) {
			require_once $plugin_slug_src_file->getPathname();
		}
	}
}
