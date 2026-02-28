<?php
/**
 * Agency Experience Assets
 *
 * Enqueues Three.js, OrbitControls, custom experience scripts and styles
 * for the front-end of the WordPress site. Passes localized PHP data
 * to JavaScript via wp_localize_script.
 *
 * Usage: require_once this file from your plugin's main file or functions.php.
 *
 * @package Agency
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue Agency Experience scripts and styles on the front-end only.
 *
 * Hooks into wp_enqueue_scripts to ensure assets are never loaded
 * in the WordPress admin area.
 *
 * @since 1.0.0
 * @return void
 */
function agency_enqueue_experience_assets() {

	/**
	 * Define the base path to this plugin's assets directory.
	 * Adjust AGENCY_PLUGIN_DIR and AGENCY_PLUGIN_URL to match
	 * the constants defined in your plugin's main file.
	 *
	 * Example in main plugin file:
	 *   define( 'AGENCY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	 *   define( 'AGENCY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	 */
	$plugin_dir = defined( 'AGENCY_PLUGIN_DIR' ) ? AGENCY_PLUGIN_DIR : trailingslashit( get_stylesheet_directory() );
	$plugin_url = defined( 'AGENCY_PLUGIN_URL' ) ? AGENCY_PLUGIN_URL : trailingslashit( get_stylesheet_directory_uri() );

	// Absolute paths to local asset files (used for filemtime cache busting).
	$js_file  = $plugin_dir . 'assets/js/experience.js';
	$css_file = $plugin_dir . 'assets/css/experience.css';

	// Generate cache-busting version strings from file modification times.
	// Falls back to the current timestamp if the file does not exist.
	$js_version  = file_exists( $js_file )  ? filemtime( $js_file )  : time();
	$css_version = file_exists( $css_file ) ? filemtime( $css_file ) : time();

	// -------------------------------------------------------------------------
	// 1. Enqueue Three.js from CDN.
	// -------------------------------------------------------------------------
	wp_enqueue_script(
		'three-js',
		'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js',
		array(),        // No dependencies.
		'r128',         // Library version as the cache-busting string.
		true            // Load in the footer.
	);

	// -------------------------------------------------------------------------
	// 2. Enqueue OrbitControls from jsDelivr CDN (depends on Three.js).
	// -------------------------------------------------------------------------
	wp_enqueue_script(
		'orbit-controls',
		'https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js',
		array( 'three-js' ), // Must load after Three.js.
		'0.128.0',           // Library version as the cache-busting string.
		true                 // Load in the footer.
	);

	// -------------------------------------------------------------------------
	// 3. Enqueue the custom experience script from the plugin directory.
	//    Depends on both Three.js and OrbitControls.
	//    Uses filemtime for automatic cache busting on every file save.
	// -------------------------------------------------------------------------
	wp_enqueue_script(
		'agency-experience-js',
		$plugin_url . 'assets/js/experience.js',
		array( 'three-js', 'orbit-controls' ), // Load after Three.js and OrbitControls.
		$js_version,                           // filemtime-based version string.
		true                                   // Load in the footer.
	);

	// -------------------------------------------------------------------------
	// 4. Enqueue the custom experience stylesheet.
	//    Uses filemtime for automatic cache busting on every file save.
	// -------------------------------------------------------------------------
	wp_enqueue_style(
		'agency-experience-css',
		$plugin_url . 'assets/css/experience.css',
		array(),       // No style dependencies.
		$css_version   // filemtime-based version string.
	);

	// -------------------------------------------------------------------------
	// 5. Pass PHP data to JavaScript via wp_localize_script.
	//    The 'agencyData' object will be available globally in experience.js.
	// -------------------------------------------------------------------------
	wp_localize_script(
		'agency-experience-js', // Script handle to attach the data to.
		'agencyData',           // JavaScript global variable name.
		array(
			/**
			 * WordPress AJAX URL for admin-ajax.php requests.
			 * Use this as the endpoint for wp_ajax_* / wp_ajax_nopriv_* handlers.
			 */
			'ajax_url'  => admin_url( 'admin-ajax.php' ),

			/**
			 * REST API base URL for the custom 'agency/v1/' namespace.
			 * Example: https://example.com/wp-json/agency/v1/
			 */
			'rest_url'  => rest_url( 'agency/v1/' ),

			/**
			 * Security nonce for REST API and AJAX requests.
			 * Verify server-side with check_ajax_referer() or verify_nonce().
			 */
			'nonce'     => wp_create_nonce( 'wp_rest' ),

			/**
			 * URL of the active theme directory.
			 * Useful for referencing theme-based assets from JavaScript.
			 */
			'theme_url' => get_stylesheet_directory_uri(),
		)
	);
}

/**
 * Register the asset enqueue function on the wp_enqueue_scripts hook.
 *
 * The is_admin() check is implicit because wp_enqueue_scripts only fires
 * on the front-end, but we add an explicit guard for absolute safety.
 */
if ( ! is_admin() ) {
	add_action( 'wp_enqueue_scripts', 'agency_enqueue_experience_assets' );
}
