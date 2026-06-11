<?php
/**
 * Admin page section field view
 *
 * Receives the $args array passed to add_settings_field(), containing
 * label_for and option_name keys. The input id matches the label_for
 * value, so the field title rendered by WordPress labels this control.
 *
 * @package      Gamajo\PluginSlug
 * @author       Gary Jones
 * @copyright    2024-2026 Gary Jones
 * @license      GPL-2.0-or-later
 */

declare( strict_types = 1 );

$plugin_slug_options = (array) get_option( $args['option_name'], [] );
$plugin_slug_value   = (string) ( $plugin_slug_options[ $args['label_for'] ] ?? '' );
?>
<input
	type="text"
	id="<?php echo esc_attr( $args['label_for'] ); ?>"
	name="<?php echo esc_attr( $args['option_name'] . '[' . $args['label_for'] . ']' ); ?>"
	value="<?php echo esc_attr( $plugin_slug_value ); ?>"
	class="regular-text"
>
<p class="description"><?php esc_html_e( 'This is the field 1 view.', 'plugin-slug' ); ?></p>
