<?php
/**
 * Field view fixture
 *
 * Echoes the $label_for and $option_name that render_field() extracts from
 * its args, so the test can assert both that the view is required and that
 * those values reach it.
 *
 * @package      Gamajo\PluginSlug\Tests
 * @author       Gary Jones
 * @copyright    2026 Gary Jones
 * @license      GPL-2.0-or-later
 *
 * @var string $label_for   Field id, passed through from render_field().
 * @var string $option_name Option name, passed through from render_field().
 */

declare( strict_types = 1 );

// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Test fixture marker.
echo 'FIELD_FIXTURE:' . $label_for . ':' . $option_name;
