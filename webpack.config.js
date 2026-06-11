/**
 * webpack configuration.
 *
 * Extends the default @wordpress/scripts config rather than replacing it, so
 * all the WordPress-optimised defaults (Babel, asset file generation, CSS
 * extraction) are inherited. Only the entry points are overridden to give the
 * build a named bundle instead of the default src/index.js.
 */

const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

module.exports = {
	...defaultConfig,
	entry: {
		'admin-settings': './src/js/admin-settings.js',
	},
};
