<?php
/**
 * Register shortcode.
 *
 * @package mortgage_calculator
 */

/**
 * MCWP shortcode.
 *
 * @param array       $atts Shortcode atts.
 * @param string|null $content Content.
 * @param strin       $tag Tags.
 */
function mcwp_shortcode( $atts = array(), $content = null, $tag = '' ) {
	wp_enqueue_script( 'wpmc_calculator' );
	wp_enqueue_style( 'wpmc_slider' );

	// normalize attribute keys, lowercase.
	$atts = array_change_key_case( (array) $atts, CASE_LOWER );
	// override default attributes with user attributes.
	$atts = shortcode_atts( array( 'type' => 'cv' ), $atts, $tag );
	$type = sanitize_text_field( $atts['type'] );
	if ( ! in_array( $type, array( 'cv', 'fha', 'va', 'mha', 'rc' ), true ) ) {
		$type = 'cv';
	}

	// phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
	$calTemplate2  = '';
	$option_func   = ( use_network_settings( 'wpmc_mail_use_network_settings' ) === 'yes' ) ? 'get_site_option' : 'get_option';
	$mcwp_currency = $option_func( 'mcwp_currency' );
	$curr_symbol   = $mcwp_currency;

	$wrap_class = '';
	if ( 'cv' === $type ) {
		require_once 'views/conventional.php';
		$wrap_class = 'mcalc-conventional';
	} elseif ( 'fha' === $type ) {
		require_once 'views/fha.php';
		$wrap_class = 'mcalc-fha';
	} elseif ( 'va' === $type ) {
		require_once 'views/va.php';
		$wrap_class = 'mcalc-va';
	} elseif ( 'mha' === $type ) {
		require_once 'views/mha.php';
		$wrap_class = 'mcalc-ha';
	} elseif ( 'rc' === $type ) {
		require_once 'views/rc.php';
		$wrap_class = 'mcalc-refi';
	}
	$cal_form = '<form class="mcalc ' . $wrap_class . ' mcalc-color" name="' . $type . '" id="id_' . $type . '">
    ' . $calculator_layout . '
      <input type="hidden" name="action" value="mcwp_sendmail"/>
    </form>';

	return $cal_form;
}

/**
 * Shortcodes init.
 */
function wporg_shortcodes_init() {
	add_shortcode( 'mcwp', 'mcwp_shortcode' );
}
add_action( 'init', 'wporg_shortcodes_init' );
