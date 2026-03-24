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

/**
 * Get the calculator accent color from settings.
 *
 * @return string Sanitized CSS color value.
 */
function mcwp_get_color() {
	$option_func = ( use_network_settings( 'wpmc_mail_use_network_settings' ) === 'yes' ) ? 'get_site_option' : 'get_option';
	$color       = $option_func( 'mcwp_color' );

	if ( empty( $color ) ) {
		return '#1a56db';
	}

	if ( strpos( $color, '[' ) !== false ) {
		$color = do_shortcode( $color );
	}

	$color = trim( $color );

	if ( preg_match( '/^#[0-9a-fA-F]{3,8}$/', $color ) ) {
		return $color;
	}
	if ( preg_match( '/^[a-zA-Z]+$/', $color ) ) {
		return $color;
	}
	if ( preg_match( '/^(rgba?|hsla?)\(\s*[\d\s,.\/%]+\)$/', $color ) ) {
		return $color;
	}

	return '#1a56db';
}

/**
 * Build a styled email using the "Clean Card" template.
 *
 * @param array $args {
 *     @type string $calc_type    Display name, e.g. "Conventional Loan".
 *     @type string $subtitle     Subtitle line, e.g. "30 Year Fixed".
 *     @type string $total        Formatted total payment, e.g. "$1,687".
 *     @type string $total_label  Optional label above total. Default "Estimated Monthly Payment".
 *     @type string $message      Custom message from admin settings.
 *     @type array  $rows         Array of ['label' => ..., 'value' => ...] for the breakdown table.
 *     @type string $curr_symbol  Currency symbol.
 *     @type string $disclaimer   Optional disclaimer text.
 * }
 * @return string Full HTML email body.
 */
function mcwp_email_template( $args ) {
	$color = mcwp_get_color();

	$calc_type   = esc_html( $args['calc_type'] );
	$subtitle    = esc_html( $args['subtitle'] );
	$total       = esc_html( $args['total'] );
	$total_label = isset( $args['total_label'] ) ? esc_html( $args['total_label'] ) : __( 'Estimated Monthly Payment', 'mortgage-calculators-wp' );
	$message     = $args['message'];
	$rows        = $args['rows'];
	$disclaimer  = isset( $args['disclaimer'] ) ? $args['disclaimer'] : '';

	$rows_html = '';
	foreach ( $rows as $row ) {
		$rows_html .= '
				<tr>
				  <td style="padding:12px 0; border-bottom:1px solid #f3f4f6; color:#6b7280;">' . esc_html( $row['label'] ) . '</td>
				  <td style="padding:12px 0; border-bottom:1px solid #f3f4f6; text-align:right; font-weight:600; color:#111827;">' . esc_html( $row['value'] ) . '</td>
				</tr>';
	}

	$rows_html .= '
				<tr>
				  <td style="padding:14px 0 0; font-weight:700; color:#111827; font-size:15px;">' . __( 'Total Monthly Payment', 'mortgage-calculators-wp' ) . '</td>
				  <td class="color" style="padding:14px 0 0; text-align:right; font-weight:700; color:' . esc_attr( $color ) . '; font-size:15px;">' . $total . '</td>
				</tr>';

	$disclaimer_html = '';
	if ( ! empty( $disclaimer ) ) {
		$disclaimer_html = '
		  <tr>
			<td style="padding:0 40px 32px;">
			  <p style="margin:0; font-size:12px; line-height:18px; color:#9ca3af;">' . $disclaimer . '</p>
			</td>
		  </tr>';
	}

	$message_html = '';
	if ( ! empty( $message ) ) {
		$message_html = '
		  <tr>
			<td style="padding:24px 40px 8px;">
			  <p style="margin:0; font-size:15px; line-height:24px; color:#4b5563;">' . $message . '</p>
			</td>
		  </tr>';
	}

	$html = '
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" class="bg" style="background-color:#f4f5f7; padding:40px 20px; font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif; color:#2d3748;">
  <tr>
	<td align="center">
	  <table role="presentation" width="600" cellpadding="0" cellspacing="0" class="bg" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 6px rgba(0,0,0,0.07);">

		<tr>
		  <td class="bg color" style="background-color:' . esc_attr( $color ) . '; padding:32px 40px; text-align:center;">
			<h1 style="margin:0; font-size:24px; font-weight:600; color:#ffffff; letter-spacing:-0.3px;">' . __( 'Your Calculations', 'mortgage-calculators-wp' ) . '</h1>
			<p style="margin:8px 0 0; font-size:14px; color:rgba(255,255,255,0.75);">' . $calc_type . ' &bull; ' . $subtitle . '</p>
		  </td>
		</tr>

		<tr>
		  <td style="padding:36px 40px 20px; text-align:center; border-bottom:1px solid #e5e7eb;">
			<p style="margin:0 0 4px; font-size:13px; text-transform:uppercase; letter-spacing:1px; color:#6b7280;">' . $total_label . '</p>
			<p class="color" style="margin:0; font-size:48px; font-weight:700; color:' . esc_attr( $color ) . '; letter-spacing:-1px;">' . $total . '</p>
		  </td>
		</tr>

		' . $message_html . '

		<tr>
		  <td style="padding:16px 40px 32px;">
			<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;">
			  ' . $rows_html . '
			</table>
		  </td>
		</tr>

		' . $disclaimer_html . '

	  </table>
	</td>
  </tr>
</table>';

	return $html;
}

/**
 * Build a styled admin notification (CC) email.
 *
 * @param string $to           The lead's email address.
 * @param string $calc_type    Display name of calculator type.
 * @param string $body_content The lead's email body content to include as reference.
 * @return string Full HTML email body for admin.
 */
function mcwp_cc_email_template( $to, $calc_type, $body_content ) {
	$color = mcwp_get_color();

	$html = '
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" class="bg" style="background-color:#f4f5f7; padding:40px 20px; font-family:\'Helvetica Neue\', Helvetica, Arial, sans-serif; color:#2d3748;">
  <tr>
	<td align="center">
	  <table role="presentation" width="600" cellpadding="0" cellspacing="0" class="bg" style="background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 6px rgba(0,0,0,0.07);">

		<tr>
		  <td class="bg color" style="background-color:' . esc_attr( $color ) . '; padding:24px 40px; text-align:center;">
			<h1 style="margin:0; font-size:20px; font-weight:600; color:#ffffff;">' . sprintf( __( 'New %s Lead', 'mortgage-calculators-wp' ), esc_html( $calc_type ) ) . '</h1>
		  </td>
		</tr>

		<tr>
		  <td style="padding:28px 40px;">
			<p style="margin:0 0 16px; font-size:15px; line-height:24px; color:#4b5563;">
			  <a href="mailto:' . esc_attr( $to ) . '" class="color" style="color:' . esc_attr( $color ) . '; font-weight:600; text-decoration:none;">' . esc_html( $to ) . '</a>
			  ' . __( 'requested a calculation. A copy of the email they received is below for reference.', 'mortgage-calculators-wp' ) . '
			</p>
			<div class="bg" style="background-color:#f9fafb; border-radius:8px; padding:20px; border:1px solid #e5e7eb;">
			  ' . $body_content . '
			</div>
		  </td>
		</tr>

	  </table>
	</td>
  </tr>
</table>';

	return $html;
}
