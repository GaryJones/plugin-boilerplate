/**
 * Admin settings page enhancement.
 *
 * A small showcase of the @wordpress/scripts build. Importing WordPress
 * packages turns them into wp-* script dependencies in the generated
 * admin-settings.asset.php file; @wordpress/i18n provides translatable
 * strings on the JavaScript side; and the imported stylesheet is extracted
 * to admin-settings.css.
 */

import domReady from '@wordpress/dom-ready';
import { __, sprintf } from '@wordpress/i18n';

import './admin-settings.css';

domReady( () => {
	const field = document.getElementById( 'field1' );

	if ( ! field || field.tagName !== 'INPUT' ) {
		return;
	}

	const counter = document.createElement( 'p' );
	counter.className = 'plugin-slug-counter';

	const update = () => {
		counter.textContent = sprintf(
			/* translators: %d: number of characters entered. */
			__( '%d characters', 'plugin-slug' ),
			field.value.length
		);
	};

	field.after( counter );
	field.addEventListener( 'input', update );
	update();
} );
