<?php
/**
 * VA template.
 *
 * @package mortgage_calculator
 *
 * phpcs:disable WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
 */

$calculation_result     = isset( $_POST['calculation_result'] ) ? sanitize_text_field( wp_unslash( $_POST['calculation_result'] ) ) : '';
$principal_and_interest = isset( $_POST['principal_and_interest'] ) ? sanitize_text_field( wp_unslash( $_POST['principal_and_interest'] ) ) : '';
$monthly_taxes          = isset( $_POST['monthly_taxes'] ) ? round( sanitize_text_field( wp_unslash( $_POST['monthly_taxes'] ) ), 2 ) : '';
$monthly_insurance      = isset( $_POST['monthly_insurance'] ) ? round( sanitize_text_field( wp_unslash( $_POST['monthly_insurance'] ) ), 2 ) : '';
$_term                  = isset( $_POST['mortgage_term'] ) ? sanitize_text_field( wp_unslash( $_POST['mortgage_term'] ) ) : '';
$funding_fee            = isset( $_POST['funding_fee'] ) ? sanitize_text_field( wp_unslash( $_POST['funding_fee'] ) ) : '';
$rate                   = isset( $_POST['interest_rate'] ) ? sanitize_text_field( wp_unslash( $_POST['interest_rate'] ) ) : '';
$monthly_hoa            = isset( $_POST['monthly_hoa'] ) ? sanitize_text_field( wp_unslash( $_POST['monthly_hoa'] ) ) : '';
$purchase_price         = isset( $_POST['purchase_price'] ) ? sanitize_text_field( wp_unslash( $_POST['purchase_price'] ) ) : '';
$va_funding_fee_2       = isset( $_POST['va_funding_fee_2'] ) ? sanitize_text_field( wp_unslash( $_POST['va_funding_fee_2'] ) ) : '';
$amount_financed        = isset( $_POST['amount_financed'] ) ? sanitize_text_field( wp_unslash( $_POST['amount_financed'] ) ) : '';
$option_func            = ( use_network_settings( 'wpmc_three_use_network_settings' ) === 'yes' ) ? 'get_site_option' : 'get_option';
$wpmc_admin             = $option_func( 'wpmc_three_email' );
$site_admin             = checksettings( 'admin_email' );
$subject                = __( 'Your VA Mortgage Calculation', 'mortgage-calculators-wp' );

// Dynamically Create the Body.
$msg_body          = $option_func( 'wpmc_three_msg_bdy' );
$current_post      = map_deep( $_REQUEST, 'wp_kses_post' );
$body_part_dynamic = body_dynamic( $msg_body, $_REQUEST );


$body_part_static = __( 'Based on a purchase price of', 'mortgage-calculators-wp' ) . " <strong>$curr_symbol$purchase_price</strong>, " . __( 'your new', 'mortgage-calculators-wp' ) . " <strong>$_term " . __( 'year', 'mortgage-calculators-wp' ) . '</strong> ' . __( 'VA loan in the amount of', 'mortgage-calculators-wp' ) . " <strong>$curr_symbol$amount_financed</strong>, " . __( 'which includes a funding fee of', 'mortgage-calculators-wp' ) . " <strong>$curr_symbol$funding_fee</strong>, " . __( ' with an interest rate of', 'mortgage-calculators-wp' ) . " <strong>$rate%</strong> " . __( 'will have a payment of', 'mortgage-calculators-wp' ) . " <strong>$curr_symbol$calculation_result</strong>. " . __( 'This includes monthly taxes of', 'mortgage-calculators-wp' ) . " <strong>$curr_symbol$monthly_taxes</strong>, " . __( 'monthly insurance of', 'mortgage-calculators-wp' ) . " <strong>$curr_symbol$monthly_insurance</strong>, " . __( 'and monthly hoa of', 'mortgage-calculators-wp' ) . " <strong>$curr_symbol$monthly_hoa</strong>.";

$body      .= "<div style='font-family:Arial;font-size: 13px;padding:0 10px;'>
    <p style='line-height: 20px; max-width: 500px'>$wpmc_mail_message</p>
    " . ( ! empty( $body_part_dynamic ) ? $body_part_dynamic : $body_part_static ) . '
  </div>';
$cc_subject = __( 'New VA Calculation by ', 'mortgage-calculators-wp' ) . $to;
$href       = esc_attr( 'mailto:' . $to );
$cc_body    = "<div style='font-family:Arial;font-size: 13px;padding:0 10px;'><p><a href='$href'>" . __( 'Click Here', 'mortgage-calculators-wp' ) . '</a> ' . __( 'to follow up with', 'mortgage-calculators-wp' ) . " $to. " . __( 'They requested a calculation and a copy of the email they received is below for reference', 'mortgage-calculators-wp' ) . ':</p><em>' . ( ! empty( $body_part_dynamic ) ? $body_part_dynamic : $body_part_static ) . '</em></div>';
