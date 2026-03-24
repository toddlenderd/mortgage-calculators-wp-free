<?php
/**
 * Plugin Name:  Mortgage Calculators WP
 * Plugin URI:   https://mortgagecalculatorsplugin.com
 * Description:  A contemporary set of mortgage calculators from Lenderd.com
 * Version:      1.61
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
define( 'MC_PATH', plugin_dir_path( __FILE__ ) );
define( 'MC_URL', plugin_dir_url( __FILE__ ) );
// Load common  functions.
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
 * Custom theme setup.
 */
function custom_theme_setup() {
	load_plugin_textdomain( 'mortgage-calculators-wp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'after_setup_theme', 'custom_theme_setup' );

/**
 * Activation hook.
 */
function mortgage_calculator_install() {
	// do something when plugin is activated or installed.
}

/**
 * Deactivatio hook.
 */
function mortgage_calculator_remove() {
	// do something when plugin is deactivated or removed.
}

/**
 * Load CSS & JS Files.
 */
function mcwp_enqueue() {
	wp_register_script( 'wpmc_slider', plugin_dir_url( __FILE__ ) . 'assets/bootstrap-slider/bootstrap-slider.js', array( 'jquery' ), true, true );
	wp_register_script( 'wpmc_calculator', plugin_dir_url( __FILE__ ) . 'assets/js/wpmc.js', array( 'jquery', 'wpmc_slider' ), true, true );
	wp_register_style( 'wpmc_slider_css', plugin_dir_url( __FILE__ ) . 'assets/bootstrap-slider/bootstrap-slider.css', array(), true );
	wp_register_style( 'wpmc_slider', plugin_dir_url( __FILE__ ) . 'assets/css/wpmc.css', array( 'wpmc_slider_css' ), true );
	wp_localize_script(
		'wpmc_calculator',
		'mcwp_ajax',
		array(
			'ajaxurl'  => admin_url( 'admin-ajax.php' ),
			'calc_res' => __( 'Your calculations are on the way to your inbox!', 'mortgage-calculators-wp' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', 'mcwp_enqueue', 11 );

/**
 * Enqueue admin scripts.
 */
function softlights_admin_scripts() {
	wp_enqueue_style( 'mcwp-css', plugin_dir_url( __FILE__ ) . 'admin/admin.css', array(), true );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wpmc-script-handle', plugin_dir_url( __FILE__ ) . 'admin/admin.js', array( 'wp-color-picker', 'jquery' ), true, true );
}
add_action( 'admin_enqueue_scripts', 'softlights_admin_scripts' );

add_action(
	'wp_head',
	function () {
		$option_func = ( use_network_settings( 'wpmc_mail_use_network_settings' ) === 'yes' ) ? 'get_site_option' : 'get_option';
		$mcwp_color  = $option_func( 'mcwp_color' );
		?>
	<style type="text/css">.mcalc-color,.mcalc .slider-handle.round,.mcalc .slider.slider-horizontal .slider-selection{background:<?php echo esc_attr( $mcwp_color ); ?> !important;}</style>
			<?php
	}
);

if ( is_network_admin() ) {
	/**
	 * Network admin menu.
	 */
	function wpmc_network_admin_menu() {
		add_menu_page(
			__( 'Mortage Calculator', 'mortgage-calculators-wp' ),
			__( 'Calculator', 'mortgage-calculators-wp' ),
			'manage_options',
			'wpmc',
			'mortgage_calculator_html_page',
			plugin_dir_url( __FILE__ ) . 'assets/images/calculator.png',
			20
		);
	}
	add_filter( 'network_admin_menu', 'wpmc_network_admin_menu' );
}

// Create Top Level Menu & Sub Menu.
if ( is_admin() ) {

	/**
	 * Admin menu.
	 */
	function mortgage_calculator_admin_menu() {
		add_menu_page(
			__( 'Mortage Calculator', 'mortgage-calculators-wp' ),
			__( 'Calculator', 'mortgage-calculators-wp' ),
			'manage_options',
			'wpmc',
			'mortgage_calculator_html_page',
			plugin_dir_url( __FILE__ ) . 'assets/images/calculator.png',
			20
		);
	}
	add_action( 'admin_menu', 'mortgage_calculator_admin_menu' );
}
/**
 * Create Tabs Template.
 */
function mortgage_calculator_html_page() {
	wpmc_main_template(); // Load the Main template html.
}
add_action( 'admin_init', 'wpmc_admin_init' );

// Remove error:: JQMIGRATE: Migrate is installed, version 1.4.1.
add_action(
	'wp_default_scripts',
	function ( $scripts ) {
		if ( ! empty( $scripts->registered['jquery'] ) ) {
			$scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, array( 'jquery-migrate' ) );
		}
	}
);

