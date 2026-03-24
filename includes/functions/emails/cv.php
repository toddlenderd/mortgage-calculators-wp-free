<?php
defined( 'ABSPATH' ) || exit;
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
$option_func                = ( mcwp_use_network_settings( 'wpmc_one_use_network_settings' ) === 'yes' ) ? 'get_site_option' : 'get_option';
$subject = __( 'Your Conventional Mortgage Calculation', 'mortgage-calculators-wp' );

// Build the breakdown rows for the email template.
$rows = array(
	array(
		'label' => __( 'Purchase Price', 'mortgage-calculators-wp' ),
		'value' => $curr_symbol . $price,
	),
	array(
		'label' => __( 'Down Payment', 'mortgage-calculators-wp' ),
		'value' => $curr_symbol . $down_payment,
	),
	array(
		'label' => __( 'Interest Rate', 'mortgage-calculators-wp' ),
		'value' => $interest_rate . '%',
	),
	array(
		'label' => __( 'Principal & Interest', 'mortgage-calculators-wp' ),
		'value' => $curr_symbol . $principal_and_interest,
	),
	array(
		'label' => __( 'Monthly Taxes', 'mortgage-calculators-wp' ),
		'value' => $curr_symbol . $monthly_taxes,
	),
	array(
		'label' => __( 'Monthly Insurance', 'mortgage-calculators-wp' ),
		'value' => $curr_symbol . $monthly_insurance,
	),
	array(
		'label' => __( 'Monthly HOA', 'mortgage-calculators-wp' ),
		'value' => $curr_symbol . $monthly_hoa,
	),
);

$body = mcwp_email_template(
	array(
		'calc_type'   => __( 'Conventional Loan', 'mortgage-calculators-wp' ),
		'subtitle'    => $_term . ' ' . __( 'Year Fixed', 'mortgage-calculators-wp' ),
		'total'       => $curr_symbol . $calculation_result,
		'message'     => $wpmc_mail_message,
		'rows'        => $rows,
		'curr_symbol' => $curr_symbol,
		'disclaimer'  => $option_func( 'wpmc_one_disclaimer' ),
	)
);

$cc_subject = __( 'New Conventional Calculation by ', 'mortgage-calculators-wp' ) . $to;
$cc_body    = mcwp_cc_email_template( $to, __( 'Conventional', 'mortgage-calculators-wp' ), $body );
