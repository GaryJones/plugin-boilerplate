<?php
/**
 * Rector configuration
 *
 * @package      Gamajo\PluginSlug
 * @author       Gary Jones
 * @copyright    2024-2026 Gary Jones
 * @license      GPL-2.0-or-later
 */

declare( strict_types = 1 );

use Rector\Config\RectorConfig;

return RectorConfig::configure()
	->withPaths(
		[
			__DIR__ . '/src',
			__DIR__ . '/tests',
			__DIR__ . '/views',
			__DIR__ . '/plugin-slug.php',
		]
	)
	->withPhpSets( php84: true )
	->withPreparedSets( deadCode: true, codeQuality: true, instanceOf: true, codingStyle: true, typeDeclarations: true );
