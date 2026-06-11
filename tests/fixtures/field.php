<?php
/**
 * Field view fixture
 *
 * Echoes a marker together with the $args passed to the field render closure,
 * so the test can assert both that the closure requires this view and that the
 * label_for and option_name arguments reach it intact. esc_html() is stubbed
 * by Brain Monkey in the test, mirroring the real field view.
 *
 * @package      Gamajo\PluginSlug\Tests
 * @author       Gary Jones
 * @copyright    2026 Gary Jones
 * @license      GPL-2.0-or-later
 *
 * @var array $args Field arguments passed by add_settings_field().
 */

declare( strict_types = 1 );

echo esc_html( 'FIELD_FIXTURE_OUTPUT:' . $args['label_for'] . ':' . $args['option_name'] );
