<?php
/**
 * MHA template.
 *
 * @package mortgage_calculator
 *
 * phpcs:disable WordPress.Security.NonceVerification.Missing, WordPress.Security.NonceVerification.Recommended
 */

$cal_result_home_afford = isset( $_POST['cal_result_home_afford'] ) ? sanitize_text_field( wp_unslash( $_POST['cal_result_home_afford'] ) ) : '';
$mha_monthly_payment    = isset( $_POST['monthly_payment'] ) ? sanitize_text_field( wp_unslash( $_POST['monthly_payment'] ) ) : '';
$mha_principal_interest = isset( $_POST['principal_interest'] ) ? sanitize_text_field( wp_unslash( $_POST['principal_interest'] ) ) : '';
$mha_tax_value          = isset( $_POST['tax_value'] ) ? sanitize_text_field( wp_unslash( $_POST['tax_value'] ) ) : '';
$mha_insurance_value    = isset( $_POST['insurance_value'] ) ? sanitize_text_field( wp_unslash( $_POST['insurance_value'] ) ) : '';
$mha_term               = isset( $_POST['mortgage_term'] ) ? sanitize_text_field( wp_unslash( $_POST['mortgage_term'] ) ) : '';
$mha_rate               = isset( $_POST['interest_rate'] ) ? sanitize_text_field( wp_unslash( $_POST['interest_rate'] ) ) : '';
$mha_income             = isset( $_POST['annual_income'] ) ? sanitize_text_field( wp_unslash( $_POST['annual_income'] ) ) : '';
$mha_debts              = isset( $_POST['monthly_debts'] ) ? sanitize_text_field( wp_unslash( $_POST['monthly_debts'] ) ) : '';
$option_func            = ( use_network_settings( 'wpmc_five_use_network_settings' ) === 'yes' ) ? 'get_site_option' : 'get_option';
$wpmc_admin             = $option_func( 'wpmc_five_email' );
$site_admin             = mcwp_checksettings( 'admin_email' );
$subject                = __( 'Your Affordability Calculation', 'mortgage-calculators-wp' );

// Dynamically Create the Body.
$msg_body          = $option_func( 'wpmc_five_msg_bdy' );
$current_post      = map_deep( $_REQUEST, 'wp_kses_post' );
$body_part_dynamic = mcwp_body_dynamic( $msg_body, $_REQUEST );

$body_part_static = __( 'You may be able to afford a loan with a', 'mortgage-calculators-wp' ) . " <strong>$mha_term " . __( 'year term', 'mortgage-calculators-wp' ) . '</strong> ' . __( 'in the amount of', 'mortgage-calculators-wp' ) . " <strong>$curr_symbol$cal_result_home_afford</strong> " . __( 'at', 'mortgage-calculators-wp' ) . " <strong>$mha_rate%</strong> " . __( 'that has a total monthly payment of', 'mortgage-calculators-wp' ) . " <strong>$curr_symbol$mha_monthly_payment</strong>" . __( '. This is based on your annual income of', 'mortgage-calculators-wp' ) . " <strong>$curr_symbol$mha_income</strong> " . __( 'and monthly debts of', 'mortgage-calculators-wp' ) . " <strong>$curr_symbol$mha_debts</strong>.";

$body      .= "<div style='font-family:Arial;font-size: 13px;padding:0 10px;'>
    <p style='line-height: 20px; max-width: 500px'>$wpmc_mail_message</p>
    " . ( ! empty( $body_part_dynamic ) ? $body_part_dynamic : $body_part_static ) . '
  </div>';
$cc_subject = __( 'New Affordability Calculation by ', 'mortgage-calculators-wp' ) . $to;
$href       = esc_attr( 'mailto:' . $to );
$cc_body    = "<div style='font-family:Arial;font-size: 13px;padding:0 10px;'><p><a href='$href'>" . __( 'Click Here', 'mortgage-calculators-wp' ) . '</a> ' . __( 'to follow up with', 'mortgage-calculators-wp' ) . " $to. " . __( 'They requested a calculation and a copy of the email they received is below for reference', 'mortgage-calculators-wp' ) . ':</p><em>' . ( ! empty( $body_part_dynamic ) ? $body_part_dynamic : $body_part_static ) . '</em></div>';
