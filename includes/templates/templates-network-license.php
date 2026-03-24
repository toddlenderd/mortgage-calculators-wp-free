<?php
/**
 * Network license template.
 *
 * @package mortgage_calculator
 *
 * phpcs:disable WordPress.Security.NonceVerification.Recommended
 */

/**
 * Main template.
 */
function wpmc_main_template() {
	$cal_one_screen   = ( isset( $_GET['action'] ) && 'cal-one' === $_GET['action'] ) ? true : false;
	$cal_two_screen   = ( isset( $_GET['action'] ) && 'cal-two' === $_GET['action'] ) ? true : false;
	$cal_three_screen = ( isset( $_GET['action'] ) && 'cal-three' === $_GET['action'] ) ? true : false;
	$cal_four_screen  = ( isset( $_GET['action'] ) && 'cal-four' === $_GET['action'] ) ? true : false;
	$admin_url        = ( is_network_admin() ? 'network/admin.php?page=wpmc' : 'admin.php?page=wpmc' );
	// show error/update messages.
	settings_errors( 'wporg_messages' );


	<div class="wrap">
		<?php
		$header_tag = '';
		if ( $cal_one_screen ) {
			$header_tag = 'Conventional Mortgage Calculator';
		} elseif ( $cal_two_screen ) {
			$header_tag = 'FHA Mortgage Calculator';
		} elseif ( $cal_three_screen ) {
			$header_tag = 'VA Mortgage Calculator';
		} else {
			$header_tag = 'General Settings';
		}
		?>
		<h1 id="header_tag"><?php echo esc_attr( $header_tag ); ?></h1>
		<h2 class="nav-tab-wrapper">

			<?php

			$active_screen1 = '';
			if ( ! isset( $_GET['action'] ) || isset( $_GET['action'] ) && 'cal-one' !== $_GET['action'] && 'cal-two' !== $_GET['action'] && 'cal-three' !== $_GET['action'] && 'cal-four' !== $_GET['action'] ) {
				$active_screen1 = esc_attr( 'nav-tab-active' );
			}
			?>

			<a href="<?php echo esc_url( admin_url( $admin_url ) ); ?>" class="nav-tab <?php echo esc_attr( $active_screen1 ); ?>"><?php esc_html_e( 'Mail' ); ?></a>

			<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'cal-one' ), admin_url( $admin_url ) ) ); ?>" class="nav-tab  <?php echo( $cal_one_screen ) ? esc_attr( 'nav-tab-active' ) : ''; ?>"><?php esc_html_e( 'Conventional Calc' ); ?></a>

			<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'cal-two' ), admin_url( $admin_url ) ) ); ?>" class="nav-tab  <?php echo( $cal_two_screen ) ? esc_attr( 'nav-tab-active' ) : ''; ?>"><?php esc_html_e( 'FHA Calc' ); ?></a>

			<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'cal-three' ), admin_url( $admin_url ) ) ); ?>" class="nav-tab <?php echo( $cal_three_screen ) ? esc_attr( 'nav-tab-active' ) : ''; ?>"><?php esc_html_e( 'VA Calc' ); ?></a>

		</h2>
		<div class="wrap">
			<p id="settings_errors"><?php settings_errors(); ?></p>

			<form method="post" action="<?php echo( is_network_admin() ? 'edit.php?action=wpmc_update_network_options' : 'options.php' ); ?>">
				<?php
				if ( $cal_one_screen ) {
					settings_fields( 'wpmc_one' );
					do_settings_sections( 'wpmc-settings-one' );
					submit_button();
				} elseif ( $cal_two_screen ) {
					echo '<p style="color: red; border: 2px solid red; padding: 40px; margin: 100px auto; text-align: center; width: 50%;"><strong style="color: red;">Premium License Required to Use this Calculator</strong></p>';
				} elseif ( $cal_three_screen ) {
					echo '<p style="color: red; border: 2px solid red; padding: 40px; margin: 100px auto; text-align: center; width: 50%;"><strong style="color: red;">Premium License Required to Use this Calculator</strong></p>';
				} elseif ( $cal_four_screen ) {
					echo '<p style="color: red; border: 2px solid red; padding: 40px; margin: 100px auto; text-align: center; width: 50%;"><strong style="color: red;">Premium License Required to Use this Calculator</strong></p>';
				} else {
					settings_fields( 'wpmc_mail' );
					do_settings_sections( 'wpmc-settings-mail' );
					submit_button();
				}
				?>
			</form>
			<script>
				var is_multisite = '<?php echo is_multisite() ? true : false; ?>';
				var is_network_admin = '<?php echo is_network_admin() ? true : false; ?>';
				<?php if ( empty( $_GET['action'] ) ) { ?>

					<?php
					$options = get_wpmc_option( 'wpmc_mail_use_network_settings' );
					$val     = ( 0 === (int) $options ) ? '0' : '1';
					?>
					var wpmc_mail_use_network_settings = '<?php echo esc_attr( $val ); ?>';
					<?php
						$wpmc_mail_use_network_settings = get_wpmc_option( 'wpmc_mail_use_network_settings' );
					if ( false !== $wpmc_mail_use_network_settings ) {
						?>
					if (is_multisite && !is_network_admin && wpmc_mail_use_network_settings == '0') {
						jQuery('.wpmc_mail').not(':first').parents('tr').hide();
					}
						<?php
					} else {
						?>
						if (is_multisite && !is_network_admin) {
							jQuery('.wpmc_mail').not(':first').parents('tr').hide();
						}
					<?php } ?>
					jQuery('input[name="wpmc_mail_use_network_settings"]').click(function() {
						if (jQuery(this).is(':checked')) {
							jQuery(this).val('0');
						} else {
							jQuery(this).val('1');
						}
						jQuery('.wpmc_mail').not(':first').parents('tr').toggle();
					});
				<?php } ?>
				<?php if ( $cal_one_screen ) { ?>
					jQuery('input[name="wpmc_one_email"]').parents('tr').wrap( "<div class='mail_one_heading'></div>" );

					jQuery('.mail_one_heading').prepend('<tr><td colspan="2"><p class="wpmc_one_label" style="border-left: 2px solid #008ec2; padding-left: 7px; background: #fff; margin-left: -10px;">Edit your calculator\'s email address & message body</p></td></tr>');

					jQuery('.mail_one_heading').prepend('<h2 class="wpmc_one_label">Email Settings</h2>');

					jQuery('input[name="wpmc_one_email"]').parents('tr').unwrap();



					jQuery('input[name="wpmc_one_pp"]').parents('tr').wrap( "<div class='label_one_heading'></div>" );

					jQuery('.label_one_heading').prepend('<p class="wpmc_one_label" style="border-left: 2px solid #008ec2; padding-left: 7px; background: #fff;">Edit your calculator\'s labels</strong></p>');

					jQuery('.label_one_heading').prepend('<h2 class="wpmc_one_label">Label Settings</h2>');

					jQuery('input[name="wpmc_one_pp"]').parents('tr').unwrap();



					jQuery('input[name="wpmc_one_pp_initial"]').parents('tr').wrap( "<div class='value_one_heading'></div>" );

					jQuery('.value_one_heading').prepend('<p class="wpmc_one_label" style="border-left: 2px solid #008ec2; padding-left: 7px; background: #fff;">Edit your calculator\'s initial values</p>');

					jQuery('.value_one_heading').prepend('<h2 class="wpmc_one_label">Initial Value Settings</h2>');

					jQuery('input[name="wpmc_one_pp_initial"]').parents('tr').unwrap();



					jQuery('textarea[name="wpmc_one_msg_bdy"]').parents('tr').wrap( "<div class='msg_bdy_one_heading'></div>" );

					jQuery('.msg_bdy_one_heading').prepend('<tr><td colspan="2"><p class="wpmc_one_label" style="border-left: 2px solid #46b450; padding-left: 7px; background: #fff; margin-left: -10px;">Edit your mail body\'s labels or reorganize them by the following label-tags:<br /><strong> [principal-and-interest], [monthly-taxes], [monthly-insurance], [monthly-mortgage-insurance], [monthly-hoa] .</strong></p></td></tr>');

					jQuery('textarea[name="wpmc_one_msg_bdy"]').parents('tr').unwrap();

					<?php
					$options = get_wpmc_option( 'wpmc_one_use_network_settings' );
					$val     = ( 0 === (int) $options ) ? '0' : '1';
					?>
					var wpmc_one_use_network_settings = '<?php echo esc_attr( $val ); ?>';
					<?php
					$wpmc_one_use_network_settings = get_wpmc_option( 'wpmc_one_use_network_settings' );
					if ( false !== $wpmc_one_use_network_settings ) {
						?>
					if (is_multisite && !is_network_admin && wpmc_one_use_network_settings == '0') {
						jQuery('.wpmc_one').not(':first').parents('tr').hide();
						jQuery('.wpmc_one_label').hide();
					}

						<?php
					} else {
						?>

					if (is_multisite && !is_network_admin) {

						jQuery('.wpmc_one').not(':first').parents('tr').hide();

						jQuery('.wpmc_one_label').hide();

					}

					<?php } ?>

					jQuery('input[name="wpmc_one_use_network_settings"]').click(function() {

						if (jQuery(this).is(':checked')) {

							jQuery(this).val('0');

						} else {

							jQuery(this).val('1');

						}

						jQuery('.wpmc_one').not(':first').parents('tr').toggle();

						jQuery('.wpmc_one_label').toggle();

					});

				<?php } ?>

				<?php if ( $cal_two_screen ) { ?>

					jQuery('input[name="wpmc_two_email"]').parents('tr').wrap( "<div class='mail_two_heading'></div>" );

					jQuery('.mail_two_heading').prepend('<tr><td colspan="2"><p class="wpmc_two_label" style="border-left: 2px solid #008ec2; padding-left: 7px; background: #fff; margin-left: -10px;">Edit your calculator\'s email address & message body</p></td></tr>');

					jQuery('.mail_two_heading').prepend('<h2 class="wpmc_two_label">Email Settings</h2>');

					jQuery('input[name="wpmc_two_email"]').parents('tr').unwrap();
					jQuery('input[name="wpmc_two_pp"]').parents('tr').wrap( "<div class='label_two_heading'></div>" );

					jQuery('.label_two_heading').prepend('<p class="wpmc_two_label" style="border-left: 2px solid #008ec2; padding-left: 7px; background: #fff;">Edit your calculator\'s labels</p>');

					jQuery('.label_two_heading').prepend('<h2 class="wpmc_two_label">Label Settings</h2>');

					jQuery('input[name="wpmc_two_pp"]').parents('tr').unwrap();

					jQuery('input[name="wpmc_two_pp_initial"]').parents('tr').wrap( "<div class='value_two_heading'></div>" );

					jQuery('.value_two_heading').prepend('<p class="wpmc_two_label" style="border-left: 2px solid #008ec2; padding-left: 7px; background: #fff;">Edit your calculator\'s initial values</p>');

					jQuery('.value_two_heading').prepend('<h2 class="wpmc_two_label">Initial Value Settings</h2>');

					jQuery('input[name="wpmc_two_pp_initial"]').parents('tr').unwrap();

					jQuery('textarea[name="wpmc_two_msg_bdy"]').parents('tr').wrap( "<div class='msg_bdy_two_heading'></div>" );

					jQuery('.msg_bdy_two_heading').prepend('<tr><td colspan="2"><p class="wpmc_two_label" style="border-left: 2px solid #46b450; padding-left: 7px; background: #fff; margin-left: -10px;">Edit your mail body\'s labels or reorganize them by the following label-tags:<br /><strong>[principal-and-interest], [monthly-taxes], [monthly-insurance], [monthly-mortgage-insurance], [monthly-hoa] .</strong></p></td></tr>');

					jQuery('textarea[name="wpmc_two_msg_bdy"]').parents('tr').unwrap();
					<?php
					$options = get_wpmc_option( 'wpmc_two_use_network_settings' );
					$val     = ( 0 === (int) $options ) ? '0' : '1';
					?>
					var wpmc_two_use_network_settings = '<?php echo esc_attr( $val ); ?>';

					<?php
						$wpmc_two_use_network_settings = get_wpmc_option( 'wpmc_two_use_network_settings' );
					if ( false !== $wpmc_two_use_network_settings ) {
						?>

					if (is_multisite && !is_network_admin && wpmc_two_use_network_settings == '0') {
						jQuery('.wpmc_two').not(':first').parents('tr').hide();
						jQuery('.wpmc_two_label').hide();
					}
						<?php
					} else {
						?>

					if (is_multisite && !is_network_admin) {

						jQuery('.wpmc_two').not(':first').parents('tr').hide();

						jQuery('.wpmc_two_label').hide();

					}

					<?php } ?>

					jQuery('input[name="wpmc_two_use_network_settings"]').click(function() {

						if (jQuery(this).is(':checked')) {

							jQuery(this).val('0');

						} else {

							jQuery(this).val('1');

						}

						jQuery('.wpmc_two').not(':first').parents('tr').toggle();

						jQuery('.wpmc_two_label').toggle();

					});

				<?php } ?>



				<?php if ( $cal_three_screen ) { ?>

					console.log('cal screen 3')

					jQuery('input[name="wpmc_three_email"]').parents('tr').wrap( "<div class='mail_three_heading'></div>" );

					jQuery('.mail_three_heading').prepend('<tr><td colspan="2"><p class="wpmc_three_label" style="border-left: 2px solid #008ec2; padding-left: 7px; background: #fff; margin-left: -10px;">Edit your calculator\'s email address & message body</p></td></tr>');

					jQuery('.mail_three_heading').prepend('<h2 class="wpmc_three_label">Email Settings</h2>');

					jQuery('input[name="wpmc_three_email"]').parents('tr').unwrap();



					jQuery('input[name="wpmc_three_ftvl"]').parents('tr').wrap( "<div class='label_three_heading'></div>" );

					jQuery('.label_three_heading').prepend('<p class="wpmc_three_label" style="border-left: 2px solid #008ec2; padding-left: 7px; background: #fff;">Edit your calculator\'s labels</p>');

					jQuery('.label_three_heading').prepend('<h2 class="wpmc_three_label">Label Settings</h2>');

					jQuery('input[name="wpmc_three_ftvl"]').parents('tr').unwrap();



					jQuery('input[name="wpmc_three_pp_initial"]').parents('tr').wrap( "<div class='value_three_heading'></div>" );

					jQuery('.value_three_heading').prepend('<p class="wpmc_three_label" style="border-left: 2px solid #008ec2; padding-left: 7px; background: #fff;">Edit your calculator\'s initial values</p>');

					jQuery('.value_three_heading').prepend('<h2 class="wpmc_three_label">Initial Value Settings</h2>');

					jQuery('input[name="wpmc_three_pp_initial"]').parents('tr').unwrap();



					jQuery('textarea[name="wpmc_three_msg_bdy"]').parents('tr').wrap( "<div class='msg_bdy_three_heading'></div>" );

					jQuery('.msg_bdy_three_heading').prepend('<tr><td colspan="2"><p class="wpmc_three_label" style="border-left: 2px solid #46b450; padding-left: 7px; background: #fff; margin-left: -10px;">Edit your mail body\'s labels or reorganize them by the following label-tags:<br /><strong>[principal-and-interest], [monthly-taxes], [monthly-insurance], [monthly-hoa], [va-funding-fee] .</strong></p></td></tr>');

					jQuery('textarea[name="wpmc_three_msg_bdy"]').parents('tr').unwrap();



					<?php

					$options = get_wpmc_option( 'wpmc_three_use_network_settings' );

					$val = ( 0 === (int) $options ) ? '0' : '1';

					?>

					var wpmc_three_use_network_settings = '<?php echo esc_attr( $val ); ?>';

					<?php

						$wpmc_three_use_network_settings = get_wpmc_option( 'wpmc_three_use_network_settings' );

					if ( false !== $wpmc_three_use_network_settings ) {
						?>

					if (is_multisite && !is_network_admin && wpmc_three_use_network_settings == '0') {

						jQuery('.wpmc_three').not(':first').parents('tr').hide();

						jQuery('.wpmc_three_label').hide();

					}

						<?php
					} else {
						?>

					if (is_multisite && !is_network_admin) {

						jQuery('.wpmc_three').not(':first').parents('tr').hide();

						jQuery('.wpmc_three_label').hide();

					}

					<?php } ?>

					jQuery('input[name="wpmc_three_use_network_settings"]').click(function() {

						if (jQuery(this).is(':checked')) {

							jQuery(this).val('0');

						} else {

							jQuery(this).val('1');

						}

						jQuery('.wpmc_three').not(':first').parents('tr').toggle();

						jQuery('.wpmc_three_label').toggle();

					});

				<?php } ?>

			</script>

		</div>

	</div> 
	<?php
}

/**
 * Calculator template.
 */
function wpmc_calculator_template1() {
	$uns                   = get_option( 'wpmc_one_use_network_settings' );
	$option_func           = ( ( false === $uns ) ? 'get_site_option' : ( ( 1 === $uns ) ? 'get_site_option' : 'get_option' ) );
	$wpmc_one_pp           = $option_func( 'wpmc_one_pp' );
	$wpmc_one_dp           = $option_func( 'wpmc_one_dp' );
	$wpmc_one_ir           = $option_func( 'wpmc_one_ir' );
	$wpmc_one_mt           = $option_func( 'wpmc_one_mt' );
	$wpmc_one_at           = $option_func( 'wpmc_one_at' );
	$wpmc_one_ai           = $option_func( 'wpmc_one_ai' );
	$wpmc_one_mhoa         = $option_func( 'wpmc_one_mhoa' );
	$wpmc_one_pp_initial   = $option_func( 'wpmc_one_pp_initial' );
	$wpmc_one_dp_max       = $option_func( 'wpmc_one_dp_max' );
	$wpmc_one_dp_min       = $option_func( 'wpmc_one_dp_min' );
	$wpmc_one_dp_initial   = $option_func( 'wpmc_one_dp_initial' );
	$wpmc_one_ir_max       = $option_func( 'wpmc_one_ir_max' );
	$wpmc_one_ir_min       = $option_func( 'wpmc_one_ir_min' );
	$wpmc_one_ir_initial   = $option_func( 'wpmc_one_ir_initial' );
	$wpmc_one_at_max       = $option_func( 'wpmc_one_at_max' );
	$wpmc_one_at_min       = $option_func( 'wpmc_one_at_min' );
	$wpmc_one_at_initial   = $option_func( 'wpmc_one_at_initial' );
	$wpmc_one_ai_initial   = $option_func( 'wpmc_one_ai_initial' );
	$wpmc_one_mhoa_initial = $option_func( 'wpmc_one_mhoa_initial' );
	$wpmc_email            = $option_func( 'wpmc_one_email' );
	$admin_email           = $option_func( 'admin_email' );

	$wpmc_one_email = ( ! empty( $wpmc_email ) && '[email]' === $wpmc_email ) ? $admin_email : ( ( ! empty( $wpmc_email ) && '[email]' !== $wpmc_email ) ? $wpmc_email : $admin_email );

	$cal_template_1 = '
				<div class="mcalc mcalc-conventional">
					<div class="col-md-6 col-lg-7">
						<div class="row">
							<div class="col-sm-12">
								<label>' . ( ( ! empty( $wpmc_one_pp ) ) ? $wpmc_one_pp : 'Purchase Price' ) . '($)</label>
								<input type="text" id="inp_purchase_price" value="' . ( ( ! empty( $wpmc_one_pp_initial ) ) ? $wpmc_one_pp_initial : '250,000' ) . '">
							</div>
							<div class="col-sm-6">
								<label>' . ( ( ! empty( $wpmc_one_dp ) ) ? $wpmc_one_dp : 'Down Payment' ) . ' (%)</label>
								<input id="ex1" class="ex1 down_payment_scrl" data-slider-id="ex1Slider" type="text" data-slider-min="' . ( ( ! empty( $wpmc_one_dp_min ) ) ? $wpmc_one_dp_min : 0 ) . '" data-slider-max="' . ( ( ! empty( $wpmc_one_dp_max ) ) ? $wpmc_one_dp_max : 100 ) . '" data-slider-step="1" data-slider-value="10"/>
								<p>10%</p>
							</div>
							<div class="col-sm-6">
								<label>' . ( ( ! empty( $wpmc_one_dp ) ) ? $wpmc_one_dp : 'Down Payment' ) . ' ($)</label>
								<input type="text" id="down_payment_inp" value="' . ( ( ! empty( $wpmc_one_dp_initial ) ) ? $wpmc_one_dp_initial : 600 ) . '">
							</div>
						</div>
						<div class="row">

							<div class="col-sm-6">

								<label>' . ( ( ! empty( $wpmc_one_ir ) ) ? $wpmc_one_ir : 'Interest Rate' ) . ' (%)</label>

								<input id="ex1" class="ex1 interest_rate_scrl" data-slider-id="ex1Slider" type="text" data-slider-min="' . ( ( ! empty( $wpmc_one_ir_min ) ) ? $wpmc_one_ir_min : 1 ) . '" data-slider-max="' . ( ( ! empty( $wpmc_one_ir_max ) ) ? $wpmc_one_ir_max : 10 ) . '" data-slider-step=".125" data-slider-value="' . ( ( ! empty( $wpmc_one_ir_initial ) ) ? $wpmc_one_ir_initial : 4 ) . '"/>

								<p>4%</p>

							</div>

							<div class="col-sm-6">

								<label class="">' . ( ( ! empty( $wpmc_one_mt ) ) ? $wpmc_one_mt : 'Mortgage Term (Year)' ) . '</label>



								<select id="mortgage_term_yr">

								  <option value="15">15</option>

								  <option value="30">30</option>

  								</select>



							</div>

						</div>



						<div class="row">

							<div class="col-sm-6">

								<label>' . ( ( ! empty( $wpmc_one_at ) ) ? $wpmc_one_at : 'Annual Taxes' ) . ' (%)</label>

								<input id="ex1" class="ex1 annual_tax_scrl" data-slider-id="ex1Slider" type="text" data-slider-min="' . ( ( ! empty( $wpmc_one_at_min ) ) ? $wpmc_one_at_min : 0 ) . '" data-slider-max="' . ( ( ! empty( $wpmc_one_at_max ) ) ? $wpmc_one_at_max : 5 ) . '" data-slider-step="0.1" data-slider-value="1"/>

								<p>1%</p>

							</div>

							<div class="col-sm-6">

								<label >' . ( ( ! empty( $wpmc_one_at ) ) ? $wpmc_one_at : 'Annual Taxes' ) . ' ($)</label>

								<input type="text" id="annual_tax_inp" value="' . ( ( ! empty( $wpmc_one_at_initial ) ) ? $wpmc_one_at_initial : 20 ) . '">

							</div>

						</div>



						<div class="row">

							<div class="col-sm-6">

								<label>' . ( ( ! empty( $wpmc_one_ai ) ) ? $wpmc_one_ai : 'Annual Insurance' ) . ' ($)</label>

								<input type="text" id="annual_insurance_inp" value="' . ( ( ! empty( $wpmc_one_ai_initial ) ) ? $wpmc_one_ai_initial : 600 ) . '">

							</div>

							<div class="col-sm-6">

								<label >' . ( ( ! empty( $wpmc_one_mhoa ) ) ? $wpmc_one_mhoa : 'Monthly HOA' ) . '</label>

								<input type="text" id="monthly_hoa_inp" value="' . ( ( ! empty( $wpmc_one_mhoa_initial ) ) ? $wpmc_one_mhoa_initial : 50 ) . '">

							</div>

						</div>

					</div>

					<div class="col-md-6 col-lg-5">

						<div class="mcalc-results">

							<h2 class="color">$<span id="emmp_div_span">1421</span></h2>

							<p>Est. Monthly Payment</p>

							<h4 class="color2">Payment Breakdown</h4>

							<p>Principal & Interest: $<span id="pi_div_span" class="color">1421</span></p>

							<p>Monthly Taxes: $<span id="mtax_div_span" class="color">1421</span></p>

							<p>Monthly Insurance: $<span id="minsure_div_span" class="color">1421</span></p>

							<!--<p>Monthly Mortgage Insurance: $<span id="mmi_div_span" class="color">0</span></p>-->

							<p>Monthly HOA: $<span id="hoa_div_span" class="color">1421</span></p>

							<h5>Want a Copy of the Results?</h5>

							<input type="email" id="cal1_email" placeholder="Enter your email address" value="" />

							<input type="button" id="wpmc1_send_mail" class="wpmc-submit bg" value="Send Results!">

						</div>

					</div>

				</div>';

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $cal_template_1;
}

