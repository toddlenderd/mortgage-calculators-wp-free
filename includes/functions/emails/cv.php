<?php
/**
 * CV template.
 *
 * @package mortgage_calculator
 *
 * phpcs:disable WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
 */

$calculation_result         = isset( $_POST['calculation_result'] ) ? sanitize_text_field( wp_unslash( $_POST['calculation_result'] ) ) : '';
$principal_and_interest     = isset( $_POST['principal_and_interest'] ) ? sanitize_text_field( wp_unslash( $_POST['principal_and_interest'] ) ) : '';
$price                      = isset( $_POST['purchase_price'] ) ? sanitize_text_field( wp_unslash( $_POST['purchase_price'] ) ) : '';
$_term                      = isset( $_POST['mortgage_term'] ) ? sanitize_text_field( wp_unslash( $_POST['mortgage_term'] ) ) : '';
$interest_rate              = isset( $_POST['interest_rate'] ) ? sanitize_text_field( wp_unslash( $_POST['interest_rate'] ) ) : '';
$down_payment               = isset( $_POST['down_payment'] ) ? sanitize_text_field( wp_unslash( $_POST['down_payment'] ) ) : '';
$monthly_taxes              = isset( $_POST['monthly_taxes'] ) ? sanitize_text_field( wp_unslash( $_POST['monthly_taxes'] ) ) : '';
$monthly_insurance          = isset( $_POST['monthly_insurance'] ) ? round( sanitize_text_field( wp_unslash( $_POST['monthly_insurance'] ) ), 2 ) : '';
$monthly_mortgage_insurance = isset( $_POST['monthly_mortgage_insurance'] ) ? sanitize_text_field( wp_unslash( $_POST['monthly_mortgage_insurance'] ) ) : '';
$monthly_hoa                = isset( $_POST['monthly_hoa'] ) ? sanitize_text_field( wp_unslash( $_POST['monthly_hoa'] ) ) : '';
$option_func                = ( use_network_settings( 'wpmc_one_use_network_settings' ) === 'yes' ) ? 'get_site_option' : 'get_option';
$wpmc_admin                 = $option_func( 'wpmc_one_email' );
$site_admin                 = checksettings( 'admin_email' );

// Dynamically Create the Body.
$msg_body          = $option_func( 'wpmc_one_msg_bdy' );
$current_post      = map_deep( $_REQUEST, 'wp_kses_post' );
$body_part_dynamic = body_dynamic( $msg_body, $_REQUEST );
$subject           = __( 'Your Conventional Mortgage Calculation', 'mortgage-calculators-wp' );

$body_part_static = __( 'Based on a purchase price of', 'mortgage-calculators-wp' ) . " <strong>$curr_symbol$price</strong>, " . __( 'and a down payment of', 'mortgage-calculators-wp' ) . " <strong>$curr_symbol$down_payment</strong>, " . __( 'your new', 'mortgage-calculators-wp' ) . " <strong>$_term " . __( 'year', 'mortgage-calculators-wp' ) . '</strong> ' . __( 'loan with an interest rate of', 'mortgage-calculators-wp' ) . " <strong>$interest_rate%</strong> " . __( 'will have a payment of', 'mortgage-calculators-wp' ) . " <strong>$curr_symbol$calculation_result</strong>. " . __( 'This includes monthly taxes of', 'mortgage-calculators-wp' ) . " <strong>$curr_symbol$monthly_taxes</strong>, " . __( 'monthly insurance of', 'mortgage-calculators-wp' ) . " <strong>$curr_symbol$monthly_insurance</strong>, " . __( 'and monthly hoa of', 'mortgage-calculators-wp' ) . " <strong>$curr_symbol$monthly_hoa</strong>.";

$body      .= "<div style='font-family:Arial;font-size: 13px;padding:0 10px;'>
    <p style='line-height: 20px; max-width: 500px'>$wpmc_mail_message</p>
    " . ( ! empty( $body_part_dynamic ) ? $body_part_dynamic : $body_part_static ) . '
  </div>';
$cc_subject = __( 'New Conventional Calculation by ', 'mortgage-calculators-wp' ) . $to;
$href       = esc_attr( 'mailto:' . $to );
$cc_body    = "<div style='font-family:Arial;font-size: 13px;padding:0 10px;'><p><a href='$href'>" . __( 'Click Here', 'mortgage-calculators-wp' ) . '</a> ' . __( 'to follow up with', 'mortgage-calculators-wp' ) . " $to. " . __( 'They requested a calculation and a copy of the email they received is below for reference', 'mortgage-calculators-wp' ) . ':</p><em>' . ( ! empty( $body_part_dynamic ) ? $body_part_dynamic : $body_part_static ) . '</em></div>';
