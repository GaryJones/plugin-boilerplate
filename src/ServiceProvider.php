<?php
/**
 * DI service provider.
 *
 * @package   Gamajo\PluginSlug
 * @author    Gary Jones
 * @link      http://gamajo.com
 * @copyright 2015 Gary Jones, Gamajo Tech
 * @license   GPL-2.0+
 */

namespace Gamajo\PluginSlug;

use \Pimple\ServiceProviderInterface;
use \Pimple\Container;

/**
 *
 */
class ServiceProvider implements ServiceProviderInterface {
 	public function register( Container $pimple ) {
 		$pimple['foo'] = function() {
 			return new Foo();
 		};
 	}
}
