<?php
/**
 * Plugin Name:  Mortgage Calculators WP
 * Plugin URI:   https://mortgagecalculatorsplugin.com
 * Description:  A contemporary set of mortgage calculators from Lenderd.com
 * Version:      1.63
 * Author:       Lenderd
 * Author URI:   https://lenderd.com
 * License:      GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  mortgage-calculators-wp
 * Domain Path:  /languages
 *
 * @package mortgage_calculator
 */

// Blocking direct access to your plugin PHP files.
// phpcs:ignore Squiz.Operators.ValidLogicalOperators.NotAllowed
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
define( 'MCWP_VERSION', '1.63' );
define( 'MC_PATH', plugin_dir_path( __FILE__ ) );
define( 'MC_URL', plugin_dir_url( __FILE__ ) );
// Load common functions.
require __DIR__ . '/includes/functions/functions.php';
// Load template functions.
require_once __DIR__ . '/includes/templates/templates.php';
// Load options functions.
require_once __DIR__ . '/includes/options/options.php';
require_once __DIR__ . '/includes/shortcodes/mcwp.php';
// Load update network option functions.
require_once __DIR__ . '/includes/options/update_network_options.php';
// Runs when plugin is activated.
register_activation_hook( __FILE__, 'mortgage_calculator_install' );
// Runs on plugin deactivation.
register_deactivation_hook( __FILE__, 'mortgage_calculator_remove' );

/**
 * Load plugin textdomain.
 */
function mcwp_load_textdomain() {
	load_plugin_textdomain( 'mortgage-calculators-wp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'init', 'mcwp_load_textdomain' );

/**
 * Activation hook.
 */
function mortgage_calculator_install() {
	// do something when plugin is activated or installed.
}

/**
 * Deactivation hook.
 */
function mortgage_calculator_remove() {
	// do something when plugin is deactivated or removed.
}

/**
 * Load CSS & JS Files.
 */
function mcwp_enqueue() {
	wp_register_script( 'wpmc_slider', plugin_dir_url( __FILE__ ) . 'assets/bootstrap-slider/bootstrap-slider.js', array( 'jquery' ), MCWP_VERSION, true );
	wp_register_script( 'wpmc_calculator', plugin_dir_url( __FILE__ ) . 'assets/js/wpmc.js', array( 'jquery', 'wpmc_slider' ), MCWP_VERSION, true );
	wp_register_style( 'wpmc_slider_css', plugin_dir_url( __FILE__ ) . 'assets/bootstrap-slider/bootstrap-slider.css', array(), MCWP_VERSION );
	wp_register_style( 'wpmc_slider', plugin_dir_url( __FILE__ ) . 'assets/css/wpmc.css', array( 'wpmc_slider_css' ), MCWP_VERSION );
	wp_localize_script(
		'wpmc_calculator',
		'mcwp_ajax',
		array(
			'ajaxurl'  => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'mcwp_sendmail_nonce' ),
			'calc_res' => __( 'Your calculations are on the way to your inbox!', 'mortgage-calculators-wp' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'mcwp_enqueue', 11 );

/**
 * Enqueue admin scripts only on the plugin settings page.
 *
 * @param string $hook_suffix The current admin page hook suffix.
 */
function mcwp_admin_scripts( $hook_suffix ) {
	if ( 'toplevel_page_wpmc' !== $hook_suffix ) {
		return;
	}
	wp_enqueue_style( 'mcwp-css', plugin_dir_url( __FILE__ ) . 'admin/admin.css', array(), MCWP_VERSION );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wpmc-script-handle', plugin_dir_url( __FILE__ ) . 'admin/admin.js', array( 'wp-color-picker', 'jquery' ), MCWP_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'mcwp_admin_scripts' );

/**
 * Output inline calculator color styles.
 */
function mcwp_inline_color_style() {
	if ( ! wp_style_is( 'wpmc_slider', 'enqueued' ) ) {
		return;
	}
	$option_func = ( mcwp_use_network_settings( 'wpmc_mail_use_network_settings' ) === 'yes' ) ? 'get_site_option' : 'get_option';
	$mcwp_color  = $option_func( 'mcwp_color' );
	$css         = '.mcalc-color,.mcalc .slider-handle.round,.mcalc .slider.slider-horizontal .slider-selection{background:' . esc_attr( $mcwp_color ) . ' !important;}';
	wp_add_inline_style( 'wpmc_slider', $css );
}
add_action( 'wp_enqueue_scripts', 'mcwp_inline_color_style', 20 );

/**
 * Network admin menu.
 */
function wpmc_network_admin_menu() {
	add_menu_page(
		__( 'Mortgage Calculator', 'mortgage-calculators-wp' ),
		__( 'Calculator', 'mortgage-calculators-wp' ),
		'manage_options',
		'wpmc',
		'mortgage_calculator_html_page',
		plugin_dir_url( __FILE__ ) . 'assets/images/calculator.png',
		20
	);
}
add_action( 'network_admin_menu', 'wpmc_network_admin_menu' );

/**
 * Admin menu.
 */
function mortgage_calculator_admin_menu() {
	add_menu_page(
		__( 'Mortgage Calculator', 'mortgage-calculators-wp' ),
		__( 'Calculator', 'mortgage-calculators-wp' ),
		'manage_options',
		'wpmc',
		'mortgage_calculator_html_page',
		plugin_dir_url( __FILE__ ) . 'assets/images/calculator.png',
		20
	);
}
add_action( 'admin_menu', 'mortgage_calculator_admin_menu' );

/**
 * Create Tabs Template.
 */
function mortgage_calculator_html_page() {
	wpmc_main_template(); // Load the Main template html.
}
add_action( 'admin_init', 'wpmc_admin_init' );
