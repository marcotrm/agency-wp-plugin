<?php
/**
 * Plugin Name: Agency Custom Plugin
 * Plugin URI:  https://example.com/agency-custom-plugin
 * Description: Custom Post Types, ACF Fields, REST API endpoints and Three.js enqueue for a creative agency website.
 * Version:     1.0.0
 * Author:      Creative Agency
 * Author URI:  https://example.com
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: agency-plugin
 * Domain Path: /languages
 *
 * @package AgencyCustomPlugin
 */

// Exit immediately if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define plugin constants.
 */
define( 'AGENCY_PLUGIN_VERSION', '1.0.0' );
define( 'AGENCY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'AGENCY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'AGENCY_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Load plugin text domain for translations.
 *
 * @return void
 */
function agency_plugin_load_textdomain() {
	load_plugin_textdomain(
		'agency-plugin',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
}
add_action( 'plugins_loaded', 'agency_plugin_load_textdomain' );

/**
 * Include Custom Post Type: Progetti.
 *
 * Registers the 'Progetti' (Projects) custom post type.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/cpt-progetti.php';

/**
 * Include Custom Post Type: Servizi.
 *
 * Registers the 'Servizi' (Services) custom post type.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/cpt-servizi.php';

/**
 * Include ACF Fields.
 *
 * Registers Advanced Custom Fields field groups for the plugin's post types.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/acf-fields.php';

/**
 * Include REST API Endpoints.
 *
 * Registers custom REST API routes and endpoints for the agency website.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/rest-api.php';

/**
 * Include Three.js Enqueue.
 *
 * Handles the enqueueing of the Three.js library and related scripts.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/enqueue-threejs.php';

/**
 * Activation hook callback.
 *
 * Runs on plugin activation. Flushes rewrite rules to ensure
 * custom post type permalinks work correctly.
 *
 * @return void
 */
function agency_plugin_activate() {
	// Trigger CPT registration before flushing rules.
	if ( function_exists( 'agency_register_cpt_progetti' ) ) {
		agency_register_cpt_progetti();
	}
	if ( function_exists( 'agency_register_cpt_servizi' ) ) {
		agency_register_cpt_servizi();
	}
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'agency_plugin_activate' );

/**
 * Deactivation hook callback.
 *
 * Runs on plugin deactivation. Flushes rewrite rules to clean up
 * any custom post type permalink structures.
 *
 * @return void
 */
function agency_plugin_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'agency_plugin_deactivate' );