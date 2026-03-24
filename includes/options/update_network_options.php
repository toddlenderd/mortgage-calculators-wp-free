<?php
defined( 'ABSPATH' ) || exit; // phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Update network options.
 *
 * @package mortgage_calculator
 */

/**
 * Update network options.
 */
function wpmc_update_network_options() {
	// Check if current user is a site administrator.
	if ( ! current_user_can( 'manage_network_options' ) ) {
		wp_die( 'You don\'t have the privileges to do this operation (should be: site administrator).' );
	}

	// $_POST[ 'option_page' ] below comes from a hidden input that WordPress automatically generates for admin forms. The value equals to the admin page slug.
	$page_slug = isset( $_POST['option_page'] ) ? sanitize_text_field( wp_unslash( $_POST['option_page'] ) ) : '';
	// Check that the request is coming from the administration area.
	check_admin_referer( $page_slug . '-options' );
	// Cycle through the settings we're submitting. If there are any changes, update them.
	global $new_allowed_options;
	$options = isset( $new_allowed_options[ $page_slug ] ) ? $new_allowed_options[ $page_slug ] : array();

	foreach ( $options as $option ) {
		if ( isset( $_POST[ $option ] ) ) {
			if ( 'wpmc_one_msg_bdy' === $option || 'wpmc_mail_message' === $option || 'wpmc_two_msg_bdy' === $option || 'wpmc_three_msg_bdy' === $option || 'wpmc_five_msg_bdy' === $option || 'wpmc_six_msg_bdy' === $option ) {
				if ( isset( $_POST[ $option ] ) ) {
					update_site_option( $option, wp_kses_post( wp_unslash( $_POST[ $option ] ) ) );
				}
			} elseif ( isset( $_POST[ $option ] ) ) {
				update_site_option( $option, sanitize_text_field( wp_unslash( $_POST[ $option ] ) ) );
			}
		}
	}

	// Finally, after saving the settings, redirect to the settings page.
	$query_args = array( 'page' => 'wpmc' );
	if ( 'wpmc_one' === $page_slug ) {
		$query_args['action'] = 'cal-one';
	} elseif ( 'wpmc_two' === $page_slug ) {
		$query_args['action'] = 'cal-two';
	} elseif ( 'wpmc_three' === $page_slug ) {
		$query_args['action'] = 'cal-three';
	} elseif ( 'wpmc_four' === $page_slug ) {
		$query_args['action'] = 'cal-four';
	} elseif ( 'wpmc_five' === $page_slug ) {
		$query_args['action'] = 'cal-five';
	} elseif ( 'wpmc_six' === $page_slug ) {
		$query_args['action'] = 'cal-six';
	}
	$query_args['settings-updated'] = 'true';
	wp_safe_redirect( add_query_arg( $query_args, network_admin_url( 'admin.php' ) ) );
	exit();
}
add_action( 'network_admin_edit_wpmc_update_network_options', 'wpmc_update_network_options' );
