<?php
/**
 * Global functions.
 *
 * @package mortgage_calculator
 *
 * phpcs:disable WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
 */

/**
 * Sendmail.
 */
function mcwp_sendmail() {
	global $shortcode_tags;
	$to          = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$uns         = get_option( 'wpmc_mail_use_network_settings' );
	$option_func = ( ( false === $uns ) ? 'get_site_option' : ( ( 1 === $uns ) ? 'get_site_option' : 'get_option' ) );
	if ( use_network_setting_email() === 'yes' ) {
		$wpmc_mail_message = do_shortcode( get_site_option( 'wpmc_mail_message' ) );
	} else {
		$wpmc_mail_message = do_shortcode( get_option( 'wpmc_mail_message' ) );
	}
	$option_func   = ( use_network_settings( 'wpmc_mail_use_network_settings' ) === 'yes' ) ? 'get_site_option' : 'get_option';
	$mcwp_currency = $option_func( 'mcwp_currency' );
	$curr_symbol   = $mcwp_currency;
	$body          = '';
	$request_type  = isset( $_REQUEST['type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['type'] ) ) : '';
	if ( 'cv' === $request_type ) {
		require_once 'emails/cv.php';
	} elseif ( 'fha' === $request_type ) {
		require_once 'emails/fha.php';
	} elseif ( 'va' === $request_type ) {
		require_once 'emails/va.php';
	} elseif ( 'mha' === $request_type ) {
		require_once 'emails/mha.php';
	} elseif ( 'rc' === $request_type ) {
		require_once 'emails/rc.php';
	}
	wp_mail( $to, $subject, $body, email_headers() );
	if ( use_network_setting_email() === 'yes' ) {
		$to_form = get_site_option( 'wpmc_one_email' );
	} else {
		$to_form = get_option( 'wpmc_one_email' );
	}
	if ( preg_match( '/[\[\]\'^£$%&*()@#~?><>,|=_+¬-]/', $to_form ) ) {
		$to_form = do_shortcode( $to_form );
	}
	wp_mail( $to_form, $cc_subject, $cc_body, email_headers() );
	wp_die();
}
add_action( 'wp_ajax_mcwp_sendmail', 'mcwp_sendmail' );
add_action( 'wp_ajax_nopriv_mcwp_sendmail', 'mcwp_sendmail' );

/**
 * Email dynamic body.
 *
 * @param string $msg_body Body content.
 * @param array  $current_post Current post array.
 */
function body_dynamic( $msg_body, $current_post ) {
	$msg_body_arr      = preg_split( '/\r\n|[\r\n]/', $msg_body );
	$current_post_data = array();
	foreach ( $current_post as $key => $value ) {
		$current_post_data[ $key ] = sanitize_text_field( $value );
	}
	$newpost = $current_post_data;
	if ( is_array( $newpost ) && isset( $newpost['action'] ) ) {
		unset( $newpost['action'] );
	}
	if ( is_array( $newpost ) && isset( $newpost['type'] ) ) {
		unset( $newpost['type'] );
	}
	if ( is_array( $newpost ) && isset( $newpost['email'] ) ) {
		unset( $newpost['email'] );
	}
	$newpost_replace = array();
	foreach ( $newpost as $key => $value ) {
		$newpost_replace[ str_replace( '_', '-', $key ) ] = $value;
	}
	$emailmessage = $msg_body_arr;
	foreach ( $newpost_replace as $shortkey => $val ) {
		$emailmessage = str_replace( '[' . $shortkey . ']', $val, $emailmessage );
	}

	$body_part_dynamic = '';
	foreach ( $emailmessage as $key => $val ) {
		if ( '' !== $val && ! empty( $val ) ) {
			$body_part_dynamic .= '<p>' . $val . '</p>';
		}
	}
	return $body_part_dynamic;
}

/**
 * Network setting email.
 */
function use_network_setting_email() {
	$uns = get_option( 'wpmc_mail_use_network_settings' );
	return 0 === (int) $uns ? 'yes' : 'no';
}

/**
 * Check settings.
 *
 * @param string $val Option name.
 */
function checksettings( $val ) {
	$uns = get_option( 'wpmc_mail_use_network_settings' );
	return 0 === (int) $uns ? get_site_option( $val ) : get_option( $val );
}

/**
 * Network settings.
 */
function wpmc_one_use_network_settings() {
	// use conventional network settings.
	$uns = get_option( 'wpmc_one_use_network_settings' );
	return 0 === (int) $uns ? 'yes' : 'no';
}

/**
 * Use network settings.
 *
 * @param string $val Option name.
 */
function use_network_settings( $val ) {
	// use conventional network settings.
	$uns = get_option( $val );
	return 0 === (int) $uns ? 'yes' : 'no';
}

/**
 * Calculator fields.
 *
 * @param string $network Network name.
 * @param string $field Option name.
 * @param string $re Dynamic text.
 */
function calc_fields( $network, $field, $re ) {
	if ( 'cv' === $network ) {
		$set = get_option( 'wpmc_one_use_network_settings' );
	} elseif ( 'fha' === $network ) {
		$set = get_option( 'wpmc_two_use_network_settings' );
	} elseif ( 'va' === $network ) {
		$set = get_option( 'wpmc_three_use_network_settings' );
	} elseif ( 'mha' === $network ) {
		$set = get_option( 'wpmc_five_use_network_settings' );
	} elseif ( 'rc' === $network ) {
		$set = get_option( 'wpmc_six_use_network_settings' );
	}
	if ( 0 === (int) $set ) {
		$option = get_site_option( $field );
	} else {
		$option = get_option( $field );
	}
	$option = empty( $option ) ? $re : $option;
	return $option;
}

/**
 * Email heanders.
 */
function email_headers() {
	$from       = checksettings( 'wpmc_mail_from' );
	$from       = ( preg_match( '/[\[\]\'^£$%&*()@#~?><>,|=_+¬-]/', $from ) ) ? $from = do_shortcode( $from ) : $from;
	$from_name  = checksettings( 'wpmc_mail_from_name' );
	$from_name  = ( preg_match( '/[\[\]\'^£$%&*()@#~?><>,|=_+¬-]/', $from_name ) ) ? $from_name = do_shortcode( $from_name ) : $from_name;
	$reply      = checksettings( 'wpmc_mail_reply_to' );
	$reply      = ( preg_match( '/[\[\]\'^£$%&*()@#~?><>,|=_+¬-]/', $reply ) ) ? $reply = do_shortcode( $reply ) : $reply;
	$reply_name = checksettings( 'wpmc_mail_reply_to_name' );
	$reply_name = ( preg_match( '/[\[\]\'^£$%&*()@#~?><>,|=_+¬-]/', $reply_name ) ) ? $reply_name = do_shortcode( $reply_name ) : $reply_name;
	$headers    = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: ' . $from_name . ' <' . $from . '>',
		'Reply-To: ' . $reply_name . ' <' . $reply . '>',
	);
	return $headers;
}

/**
 * Get option name.
 *
 * @param string $option_name Option name.
 */
function get_wpmc_option( $option_name ) {
	if ( is_network_admin() ) {
		return get_site_option( $option_name );
	} else {
		return get_option( $option_name );
	}
}

/**
 * Update option.
 *
 * @param string $option_name Option name.
 * @param string $option_value Option value.
 */
function update_wpmc_option( $option_name, $option_value ) {
	$option_value = sanitize_text_field( $option_value );
	if ( is_network_admin() ) {
		return update_site_option( $option_name, $option_value );
	} else {
		return update_option( $option_name, $option_value );
	}
}

/**
 * Update option.
 *
 * @param string $option_name Option name.
 */
function delete_wpmc_option( $option_name ) {
	if ( is_network_admin() ) {
		return delete_site_option( $option_name );
	} else {
		return delete_option( $option_name );
	}
}
