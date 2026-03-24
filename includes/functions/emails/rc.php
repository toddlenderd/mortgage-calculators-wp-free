<?php
/**
 * RC template.
 *
 * @package mortgage_calculator
 *
 * phpcs:disable WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
 */

$cal_result_home_afford = isset( $_POST['cal_result_home_afford'] ) ? sanitize_text_field( wp_unslash( $_POST['cal_result_home_afford'] ) ) : '';
$rc_lifetime_value      = isset( $_POST['lifetime_value'] ) ? sanitize_text_field( wp_unslash( $_POST['lifetime_value'] ) ) : '';
$rc_refinance_fees      = isset( $_POST['refinance_fees'] ) ? sanitize_text_field( wp_unslash( $_POST['refinance_fees'] ) ) : '';
$rc_monthly_payment     = isset( $_POST['new_monthly_payment'] ) ? sanitize_text_field( wp_unslash( $_POST['new_monthly_payment'] ) ) : '';
$rc_new_loan_amount     = isset( $_POST['new_loan_amount'] ) ? sanitize_text_field( wp_unslash( $_POST['new_loan_amount'] ) ) : '';
$rc_new_interest_rate   = isset( $_POST['new_interest_rate'] ) ? sanitize_text_field( wp_unslash( $_POST['new_interest_rate'] ) ) : '';
$rc_new_loan_term       = isset( $_POST['new_loan_term'] ) ? sanitize_text_field( wp_unslash( $_POST['new_loan_term'] ) ) : '';

$option_func = ( use_network_settings( 'wpmc_six_use_network_settings' ) === 'yes' ) ? 'get_site_option' : 'get_option';
$wpmc_admin  = $option_func( 'wpmc_six_email' );
$site_admin  = checksettings( 'admin_email' );
$subject     = __( 'Your Refinance Calculation', 'mortgage-calculators-wp' );

// Dynamically Create the Body.
$msg_body = $option_func( 'wpmc_six_msg_bdy' );

$current_post      = map_deep( $_REQUEST, 'wp_kses_post' );
$body_part_dynamic = body_dynamic( $msg_body, $_REQUEST );

// phpcs:ignore WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
$forPara = __( 'Principal & Interest', 'mortgage-calculators-wp' );

$body_part_static = __( 'Refinancing could save you', 'mortgage-calculators-wp' ) . " <strong>$curr_symbol$cal_result_home_afford</strong> " . __( 'per month and', 'mortgage-calculators-wp' ) . " <strong>$curr_symbol$rc_lifetime_value</strong> " . __( 'over the life of the loan. This is based on a new loan amount of', 'mortgage-calculators-wp' ) . " <strong>$curr_symbol$rc_new_loan_amount</strong> " . __( 'at', 'mortgage-calculators-wp' ) . " <strong>$rc_new_interest_rate%</strong> " . __( 'for', 'mortgage-calculators-wp' ) . " <strong>$rc_new_loan_term " . __( 'months', 'mortgage-calculators-wp' ) . '</strong>.';

$body      .= "<div style='font-family:Arial;font-size: 13px;padding:0 10px;'>
    <p style='line-height: 20px; max-width: 500px'>$wpmc_mail_message</p>
    " . ( ! empty( $body_part_dynamic ) ? $body_part_dynamic : $body_part_static ) . '
  </div>';
$cc_subject = __( 'New Refinance Calculation by ', 'mortgage-calculators-wp' ) . $to;
$href       = esc_attr( 'mailto:' . $to );
$cc_body    = "<div style='font-family:Arial;font-size: 13px;padding:0 10px;'><p><a href='$href'>" . __( 'Click Here', 'mortgage-calculators-wp' ) . '</a> ' . __( 'to follow up with', 'mortgage-calculators-wp' ) . " $to. " . __( 'They requested a calculation and a copy of the email they received is below for reference', 'mortgage-calculators-wp' ) . ':</p><em>' . ( ! empty( $body_part_dynamic ) ? $body_part_dynamic : $body_part_static ) . '</em></div>';
