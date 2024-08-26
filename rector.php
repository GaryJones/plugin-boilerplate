<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php82\Rector\Encapsed\VariableInStringInterpolationFixerRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
use Rector\Php84\Rector\Param\ExplicitNullableParamTypeRector;

return RectorConfig::configure()
	->withPaths(
		array(
			__DIR__ . '/src',
			__DIR__ . '/tests',
			__DIR__ . '/views',
			__DIR__ . '/init.php',
			__DIR__ . '/plugin-slug.php',
		)
	)
	->withPhpSets( php82: true )
	// Changes from later PHP Sets that are backwards compatible:
	->withRules(
		array(
			// 8.3
			AddOverrideAttributeToOverriddenMethodsRector::class,

			// 8.4
			ExplicitNullableParamTypeRector::class,
		)
	);
// ->withPreparedSets( deadCode: true, codeQuality: true, instanceOf: true, codingStyle: true )
// ->withTypeCoverageLevel( 1 )
