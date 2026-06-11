<?php
/**
 * Admin page view
 *
 * @package      Gamajo\PluginSlug
 * @author       Gary Jones
 * @copyright    2024-2026 Gary Jones
 * @license      GPL-2.0-or-later
 */

declare( strict_types = 1 );
?>
<div class="wrap">
	<form method="post" action="options.php">

		<?php settings_fields( 'plugin_slug' ); // Matches the registered option group. ?>

		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

		<?php
		do_settings_sections( 'plugin-slug' ); // Matches the settings page slug.
		?>

		<div class="bottom-buttons">
			<?php
			submit_button( __( 'Save Changes', 'plugin-slug' ), 'primary', 'submit', false );
			?>
		</div>
	</form>
</div>
