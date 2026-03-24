<?php
/**
 * Conventional.
 *
 * @package mortgage_calculator
 */

$option_func             = ( use_network_settings( 'wpmc_one_use_network_settings' ) === 'yes' ) ? 'get_site_option' : 'get_option';
$mcwp_hide_insurance_one = $option_func( 'mcwp_hide_insurance_one' );
$mcwp_hide_hoa_one       = $option_func( 'mcwp_hide_hoa_one' );

$wpmc_one_dp_initial = calc_fields( 'cv', 'wpmc_one_dp_initial', '5' );
$wpmc_one_ir_initial = calc_fields( 'cv', 'wpmc_one_ir_min', '5' );
$wpmc_one_at_initial = calc_fields( 'cv', 'wpmc_one_at_initial', '1' ); // 1

$show_hoa = ( 'yes' === $mcwp_hide_hoa_one ) ? '' : '<p>' . calc_fields( 'cv', 'wpmc_one_mhoa', __( 'Monthly HOA', 'mortgage-calculators-wp' ) ) . ' <strong class="mcalc-value">' . $curr_symbol . '<span id="hoa_div_span">1421</span></strong></p>';

$show_in = ( 'yes' === $mcwp_hide_insurance_one ) ? '' : '<p>' . calc_fields( 'cv', 'wpmc_one_ai', __( 'Monthly Insurance', 'mortgage-calculators-wp' ) ) . ' <strong class="mcalc-value">' . $curr_symbol . '<span id="minsure_div_span">1421</span></strong></p>';

$wpmc_email     = $option_func( 'wpmc_one_email' );
$admin_email    = $option_func( 'admin_email' );
$wpmc_one_email = ( ! empty( $wpmc_email ) && '[email]' !== $wpmc_email ) ? $wpmc_email : $admin_email;

$calculator_layout = '
<div>
  <div class="mcalc-main">
    <div class="mcalc-half mcwp-purchase">
      <label for="inp_purchase_price">' . calc_fields( 'cv', 'wpmc_one_pp', __( 'Purchase Price', 'mortgage-calculators-wp' ) ) . '</label>
      <i>' . $curr_symbol . '</i>
      <input type="text" name="purchase_price" id="inp_purchase_price" value="' . calc_fields( 'cv', 'wpmc_one_pp_initial', '250,000' ) . '" class="mcalc-dollar">
    </div>
    <div class="mcalc-half mcwp-term">
      <label for="mortgage_term_yr">' . calc_fields( 'cv', 'wpmc_one_mt', __( 'Mortgage Term', 'mortgage-calculators-wp' ) ) . '</label>
      <select name="mortgage_term" id="mortgage_term_yr">
        <option value="30">30 ' . __( 'Years', 'mortgage-calculators-wp' ) . '</option>
        <option value="25">25 ' . __( 'Years', 'mortgage-calculators-wp' ) . '</option>
        <option value="20">20 ' . __( 'Years', 'mortgage-calculators-wp' ) . '</option>
        <option value="15">15 ' . __( 'Years', 'mortgage-calculators-wp' ) . '</option>
        <option value="10">10 ' . __( 'Years', 'mortgage-calculators-wp' ) . '</option>
        <option value="5">5 ' . __( 'Years', 'mortgage-calculators-wp' ) . '</option>
      </select>
    </div>
    <div class="mcalc-half mcwp-down-payment">
      <label class="mcalc-half" for="down_payment_inp">' . calc_fields( 'cv', 'wpmc_one_dp', __( 'Down Payment', 'mortgage-calculators-wp' ) ) . ' (' . $curr_symbol . ')</label>

      <input type="text" name="down_payment" id="down_payment_inp" value=""  class="mcalc-half">

      <input id="ex1 e1" class="ex1 down_payment_scrl" data-slider-id="ex1Slider" type="text" data-slider-min="0" data-slider-max="80" data-slider-step="1" data-slider-value="' . $wpmc_one_dp_initial . '" data-slider-arialabel="DP Slider" />
      <p class="mcalc-percent">' . $wpmc_one_dp_initial . '%</p>
    </div>

    <div class="mcalc-half mcwp-taxes">
      <label class="mcalc-half" for="annual_tax_inp">' . calc_fields( 'cv', 'wpmc_one_at', __( 'Annual Taxes', 'mortgage-calculators-wp' ) ) . ' (' . $curr_symbol . ')</label>
      <input type="text" name="annual_taxes" id="annual_tax_inp" value="" class="mcalc-half">
      <input id="ex1 e2" class="ex1 annual_tax_scrl" data-slider-id="ex1Slider" type="text" data-slider-min="0" data-slider-max="20" data-slider-step="0.1" data-slider-value="' . $wpmc_one_at_initial . '" title="Tax Slider" />
      <p class="mcalc-percent">' . $wpmc_one_at_initial . '%</p>
    </div>

    <div class="mcalc-full mcwp-interest-rate">
      <label for="ex1">' . calc_fields( 'cv', 'wpmc_one_ir', 'Interest Rate' ) . ' (%)</label>

      <input id="ex1 e3" name="interest_rate" class="ex1 interest_rate_scrl" data-slider-id="ex1Slider" type="text" data-slider-min="1" data-slider-max="30" data-slider-step=".125" data-slider-value="' . $wpmc_one_ir_initial . '"/>

      <p class="mcalc-percent">' . $wpmc_one_ir_initial . '%</p>
    </div>';

if ( 'yes' === $mcwp_hide_insurance_one ) {
	$calculator_layout .= '
      <input type="hidden" name="annual_insurance" id="annual_insurance_inp" value="0" class="mcalc-dollar">
    ';
} else {
	$calculator_layout .= '<div class="mcalc-half mcwp-insurance">
      <label for="annual_insurance_inp">' . calc_fields( 'cv', 'wpmc_one_ai', __( 'Annual Insurance', 'mortgage-calculators-wp' ) ) . '</label>
      <i>' . $curr_symbol . '</i>
      <input type="text" name="annual_insurance" id="annual_insurance_inp" value="' . calc_fields( 'cv', 'wpmc_one_ai_initial', '600' ) . '" class="mcalc-dollar">
    </div>';
}

if ( 'yes' === $mcwp_hide_hoa_one ) {
	$calculator_layout .= '
      <input type="hidden" name="monthly_hoa_form" id="monthly_hoa_inp" value="0" class="mcalc-dollar">
    ';
} else {
	$calculator_layout .= '<div class="mcalc-half mcwp-hoa">
      <label for="monthly_hoa_inp">' . calc_fields( 'cv', 'wpmc_one_mhoa', __( 'Monthly HOA', 'mortgage-calculators-wp' ) ) . '</label>
      <i>' . $curr_symbol . '</i>

      <input type="text" name="monthly_hoa_form" id="monthly_hoa_inp" value="' . calc_fields( 'cv', 'wpmc_one_mhoa_initial', '50' ) . '" class="mcalc-dollar">

    </div>';
}

$calculator_layout .= '<div class="mcalc-full mcwp-results">
      <label for="cal1_email">' . __( 'Want a Copy of the Results?', 'mortgage-calculators-wp' ) . '</label>
      <input type="email" id="cal1_email" placeholder="' . __( 'Enter your email address', 'mortgage-calculators-wp' ) . '" value="" name="email" />
      <input type="button" id="wpmc1_send_mail" class="mcwp-submit bg cv_submit mcalc-color" value="' . __( 'Send Results!', 'mortgage-calculators-wp' ) . '">
    </div>
  </div>
  <div class="mcalc-values">
    <div class="mcalc-results">
      <h2 class="mcalc-value mcalc-payment">' . $curr_symbol . '<span id="emmp_div_span">1421</span>
      </h2>
      <h3>' . __( 'Monthly Payment', 'mortgage-calculators-wp' ) . '</h3>
      <p class="mcwp-pi">' . __( 'Principal & Interest', 'mortgage-calculators-wp' ) . ' <strong class="mcalc-value">' . $curr_symbol . '<span id="pi_div_span">1421</span></strong></p>
      <p class="mcwp-mt">' . calc_fields( 'cv', 'wpmc_one_at', __( 'Monthly Taxes', 'mortgage-calculators-wp' ) ) . ' <strong class="mcalc-value">' . $curr_symbol . '<span id="mtax_div_span">1421</span></strong></p>
      ' . $show_hoa . '
      ' . $show_in . '
      <small>' . calc_fields( 'cv', 'wpmc_one_disclaimer', '' ) . '</small>
    </div>
  </div>
</div>';
$calculator_layout .= '
<input type="hidden" name="principal_and_interest" class="pi_div_span" />
<input type="hidden" name="calculation_result" class="emmp_div_span" />
<input type="hidden" name="monthly_taxes" class="mtax_div_span" />
<input type="hidden" name="monthly_insurance" class="minsure_div_span" value=""/>
<input type="hidden" name="monthly_mortgage_insurance" class="mmi_div_span" />
<input type="hidden" name="monthly_hoa" class="hoa_div_span" value=""/>
<input type="hidden" name="type" value="cv"/>
';
