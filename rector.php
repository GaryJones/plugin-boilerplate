<?php
/**
 * Rector configuration
 *
 * @package      Gamajo\PluginSlug
 * @author       Gary Jones
 * @copyright    2024 Gary Jones
 * @license      GPL-2.0-or-later
 */

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Php84\Rector\Param\ExplicitNullableParamTypeRector;

return RectorConfig::configure()
	->withPaths(
		[
			__DIR__ . '/src',
			__DIR__ . '/tests',
			__DIR__ . '/views',
			__DIR__ . '/plugin-slug.php',
		]
	)
	->withPhpSets( php82: true )
	// Changes from later PHP Sets that are backwards compatible.
	->withRules(
		[
			// 8.3.
			AddOverrideAttributeToOverriddenMethodsRector::class,

			// 8.4.
			ExplicitNullableParamTypeRector::class,
		]
	)
	->withPreparedSets( deadCode: true, codeQuality: true, instanceOf: true, codingStyle: true, typeDeclarations: true );
