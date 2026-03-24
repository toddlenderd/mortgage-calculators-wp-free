<?php
/**
 * Main license template.
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
	$cal_five_screen  = ( isset( $_GET['action'] ) && 'cal-five' === $_GET['action'] ) ? true : false;
	$cal_six_screen   = ( isset( $_GET['action'] ) && 'cal-six' === $_GET['action'] ) ? true : false;
	$admin_url        = ( is_network_admin() ? 'network/admin.php?page=wpmc' : 'admin.php?page=wpmc' );
	// show error/update messages.
	settings_errors( 'wporg_messages' ); ?>

	<div class="wrap">

		<h1 id="header_tag">
			<?php
			if ( $cal_one_screen ) {
				esc_html_e( 'Conventional Mortgage Calculator', 'mortgage-calculators-wp' );
			} elseif ( $cal_two_screen ) {
				esc_html_e( 'FHA Mortgage Calculator', 'mortgage-calculators-wp' );
			} elseif ( $cal_three_screen ) {
				esc_html_e( 'VA Mortgage Calculator', 'mortgage-calculators-wp' );
			} elseif ( $cal_five_screen ) {
				esc_html_e( 'Affordability Calculator', 'mortgage-calculators-wp' );
			} elseif ( $cal_six_screen ) {
				esc_html_e( 'Refinance Calculator', 'mortgage-calculators-wp' );
			} else {
				esc_html_e( 'General Settings', 'mortgage-calculators-wp' );
			}
			?>
		</h1>

		<h2 class="nav-tab-wrapper">

			<a href="<?php echo esc_url( admin_url( $admin_url ) ); ?>" class="nav-tab
				<?php
				if ( ! isset( $_GET['action'] ) || isset( $_GET['action'] ) && 'cal-one' !== $_GET['action'] && 'cal-two' !== $_GET['action'] && 'cal-three' !== $_GET['action'] && 'cal-four' !== $_GET['action'] && 'cal-five' !== $_GET['action'] && 'cal-six' !== $_GET['action'] ) {
						echo esc_attr( ' nav-tab-active' );
				}
				?>
			"><?php esc_html_e( 'General Settings', 'mortgage-calculators-wp' ); ?></a>

			<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'cal-one' ), admin_url( $admin_url ) ) ); ?>" class="nav-tab
				<?php
				if ( $cal_one_screen ) {
						echo esc_attr( ' nav-tab-active' );
				}
				?>
			"><?php esc_html_e( 'Conventional', 'mortgage-calculators-wp' ); ?></a>

			<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'cal-two' ), admin_url( $admin_url ) ) ); ?>" class="nav-tab
				<?php
				if ( $cal_two_screen ) {
						echo esc_attr( ' nav-tab-active' );
				}
				?>
			"><?php esc_html_e( 'FHA', 'mortgage-calculators-wp' ); ?></a>


			<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'cal-three' ), admin_url( $admin_url ) ) ); ?>" class="nav-tab
				<?php
				if ( $cal_three_screen ) {
						echo esc_attr( ' nav-tab-active' );
				}
				?>
			"><?php esc_html_e( 'VA', 'mortgage-calculators-wp' ); ?></a>



			<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'cal-five' ), admin_url( $admin_url ) ) ); ?>" class="nav-tab
				<?php
				if ( $cal_five_screen ) {
					echo ' nav-tab-active';
				}
				?>
			"><?php esc_html_e( 'Affordability', 'mortgage-calculators-wp' ); ?></a>

			<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'cal-six' ), admin_url( $admin_url ) ) ); ?>" class="nav-tab
				<?php
				if ( $cal_six_screen ) {
						echo esc_attr( ' nav-tab-active' );
				}
				?>
			"><?php esc_html_e( 'Refinance', 'mortgage-calculators-wp' ); ?></a>
		</h2>
		<div class="wrap">
			<p id="settings_errors"><?php settings_errors(); ?></p>

			<form method="post" action="<?php echo( is_network_admin() ? 'edit.php?action=wpmc_update_network_options' : 'options.php' ); ?>">
				<?php
				$upgrade_text     = __( 'To upgrade or get plugin support please visit', 'mortgage-calculators-wp' );
				$mortage_url      = 'https://mortgagecalculatorsplugin.com';
				$mortage_url_text = 'MortgageCalculatorsPlugin.com';
				if ( $cal_one_screen ) {
					settings_fields( 'wpmc_one' );
					do_settings_sections( 'wpmc-settings-one' );
					submit_button();
				} elseif ( $cal_two_screen ) {
						echo '<p style="background: #3e50b4; padding:10px 15px; border-radius: 3px; font-size: 16px; color:#fff">' . esc_html( $upgrade_text ) . ' <a href="' . esc_url( $mortage_url ) . '" target="_blank" style="color:#fff;">' . esc_html( $mortage_url_text ) . '</a></p>';
				} elseif ( $cal_three_screen ) {
						echo '<p style="background: #3e50b4; padding:10px 15px; border-radius: 3px; font-size: 16px; color:#fff">' . esc_html( $upgrade_text ) . ' <a href="' . esc_url( $mortage_url ) . '" target="_blank" style="color:#fff;">' . esc_html( $mortage_url_text ) . '</a></p>';
				} elseif ( $cal_four_screen ) {
						echo '<p style="background: #3e50b4; padding:10px 15px; border-radius: 3px; font-size: 16px; color:#fff">' . esc_html( $upgrade_text ) . ' <a href="https://mortgagecalculatorsplugin.com" target="_blank" style="color:#fff;">' . esc_html( $mortage_url_text ) . '</a></p>';
				} elseif ( $cal_five_screen ) {
						echo '<p style="background: #3e50b4; padding:10px 15px; border-radius: 3px; font-size: 16px; color:#fff">' . esc_html( $upgrade_text ) . ' <a href="' . esc_url( $mortage_url ) . '" target="_blank" style="color:#fff;">' . esc_html( $mortage_url_text ) . '</a></p>';
				} elseif ( $cal_six_screen ) {
						echo '<p style="background: #3e50b4; padding:10px 15px; border-radius: 3px; font-size: 16px; color:#fff">' . esc_html( $upgrade_text ) . ' <a href="' . esc_url( $mortage_url ) . '" target="_blank" style="color:#fff;">' . esc_html( $mortage_url_text ) . '</a></p>';
				} else {
					echo '<p style="background: #3e50b4; padding:10px 15px; border-radius: 3px; font-size: 16px; color:#fff">' . esc_html( $upgrade_text ) . ' <a href="' . esc_url( $mortage_url ) . '" target="_blank" style="color:#fff;">' . esc_html( $mortage_url_text ) . '</a></p>';
					settings_fields( 'wpmc_mail' );
					do_settings_sections( 'wpmc-settings-mail' );
					submit_button();
				}
				?>
			</form>
			<script>
				var $mcwp = jQuery.noConflict();
				$mcwp(function($){
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
					jQuery('input[name="wpmc_one_pp"]').parents('tr').wrap( "<div class='label_one_heading'></div>" );
					jQuery('.label_one_heading').prepend('<h2 class="wpmc_one_label">Customize Field Labels</h2>');
					jQuery('input[name="wpmc_one_pp"]').parents('tr').unwrap();
					jQuery('input[name="wpmc_one_pp_initial"]').parents('tr').wrap( "<div class='value_one_heading'></div>" );
					jQuery('.value_one_heading').prepend('<h2 class="wpmc_one_label" style="padding-top:30px">Customize Values</h2>');
					jQuery('input[name="wpmc_one_pp_initial"]').parents('tr').unwrap();
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
					jQuery('input[name="wpmc_two_pp"]').parents('tr').wrap( "<div class='label_two_heading'></div>" );
					jQuery('.label_two_heading').prepend('<h2 class="wpmc_two_label">Customize Field Labels</h2>');
					jQuery('input[name="wpmc_two_pp"]').parents('tr').unwrap();
					jQuery('input[name="wpmc_two_pp_initial"]').parents('tr').wrap( "<div class='value_two_heading'></div>" );
					jQuery('.value_two_heading').prepend('<h2 class="wpmc_two_label" style="padding-top:30px">Customize Values</h2>');
					jQuery('input[name="wpmc_two_pp_initial"]').parents('tr').unwrap();
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
					jQuery('input[name="wpmc_three_ftvl"]').parents('tr').wrap( "<div class='label_three_heading'></div>" );
					jQuery('.label_three_heading').prepend('<h2 class="wpmc_three_label">Customize Field Labels</h2>');
					jQuery('input[name="wpmc_three_ftvl"]').parents('tr').unwrap();
					jQuery('input[name="wpmc_three_pp_initial"]').parents('tr').wrap( "<div class='value_three_heading'></div>" );
					jQuery('.value_three_heading').prepend('<h2 class="wpmc_three_label" style="padding-top: 30px;">Customize Values</h2>');
					jQuery('input[name="wpmc_three_pp_initial"]').parents('tr').unwrap();
					<?php
					$options = get_wpmc_option( 'wpmc_three_use_network_settings' );
					$val     = ( 0 === (int) $options ) ? '0' : '1';
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



				<?php if ( $cal_five_screen ) { ?>
					jQuery('input[name="wpmc_five_ai"]').parents('tr').wrap( "<div class='label_five_heading'></div>" );
					jQuery('.label_five_heading').prepend('<h2 class="wpmc_five_label">Customize Field Labels</h2>');
					jQuery('input[name="wpmc_five_ai"]').parents('tr').unwrap();
					jQuery('input[name="wpmc_five_mhaai_initial"]').parents('tr').wrap( "<div class='value_five_heading'></div>" );
					jQuery('.value_five_heading').prepend('<h2 class="wpmc_five_label" style="padding-top: 30px;">Customize Values</h2>');
					jQuery('input[name="wpmc_five_mhaai_initial"]').parents('tr').unwrap();
					<?php
					$options = get_wpmc_option( 'wpmc_five_use_network_settings' );
					$val     = ( 0 === (int) $options ) ? '0' : '1';
					?>
					var wpmc_five_use_network_settings = '<?php echo esc_attr( $val ); ?>';
					<?php
						$wpmc_five_use_network_settings = get_wpmc_option( 'wpmc_five_use_network_settings' );
					if ( false !== $wpmc_five_use_network_settings ) {
						?>
					if (is_multisite && !is_network_admin && wpmc_five_use_network_settings == '0') {
						jQuery('.wpmc_five').not(':first').parents('tr').hide();
						jQuery('.wpmc_five_label').hide();
					}
						<?php
					} else {
						?>
					if (is_multisite && !is_network_admin) {
						jQuery('.wpmc_five').not(':first').parents('tr').hide();
						jQuery('.wpmc_five_label').hide();
					}
					<?php } ?>
					jQuery('input[name="wpmc_five_use_network_settings"]').click(function() {
						if (jQuery(this).is(':checked')) {
							jQuery(this).val('0');
						} else {
							jQuery(this).val('1');
						}
						jQuery('.wpmc_five').not(':first').parents('tr').toggle();
						jQuery('.wpmc_five_label').toggle();
					});

				<?php } ?>

				<?php if ( $cal_six_screen ) { ?>
					jQuery('input[name="wpmc_six_first_heading"]').parents('tr').wrap( "<div class='label_six_heading'></div>" );
					jQuery('.label_six_heading').prepend('<h2 class="wpmc_six_label">Customize Field Labels</h2>');
					jQuery('input[name="wpmc_six_first_heading"]').parents('tr').unwrap();
					jQuery('input[name="wpmc_six_la_initial"]').parents('tr').wrap( "<div class='value_six_heading'></div>" );
					jQuery('.value_six_heading').prepend('<h2 class="wpmc_six_label" style="padding-top: 30px;">Customize Values</h2>');
					jQuery('input[name="wpmc_six_la_initial"]').parents('tr').unwrap();
					<?php
					$options = get_wpmc_option( 'wpmc_six_use_network_settings' );
					$val     = ( 0 === (int) $options ) ? '0' : '1';
					?>
					var wpmc_six_use_network_settings = '<?php echo esc_attr( $val ); ?>';
					<?php
						$wpmc_six_use_network_settings = get_wpmc_option( 'wpmc_six_use_network_settings' );
					if ( false !== $wpmc_six_use_network_settings ) {
						?>
					if (is_multisite && !is_network_admin && wpmc_six_use_network_settings == '0') {
						jQuery('.wpmc_six').not(':first').parents('tr').hide();
						jQuery('.wpmc_six_label').hide();
					}
						<?php
					} else {
						?>
					if (is_multisite && !is_network_admin) {
						jQuery('.wpmc_six').not(':first').parents('tr').hide();
						jQuery('.wpmc_six_label').hide();
					}
					<?php } ?>
					jQuery('input[name="wpmc_six_use_network_settings"]').click(function() {
						if (jQuery(this).is(':checked')) {
							jQuery(this).val('0');
						} else {
							jQuery(this).val('1');
						}
						jQuery('.wpmc_six').not(':first').parents('tr').toggle();
						jQuery('.wpmc_six_label').toggle();
					});
				<?php } ?>
		});

			</script>
		</div>
	</div> 
	<?php
}
