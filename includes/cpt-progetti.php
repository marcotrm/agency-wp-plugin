<?php
/**
 * Custom Post Type: Progetto
 *
 * Registers the 'progetto' Custom Post Type with Italian labels,
 * full Gutenberg (block editor) support via REST API, and archive pages.
 *
 * @package    CustomPostTypes
 * @author     Your Name
 * @license    GPL-2.0-or-later
 * @version    1.0.0
 *
 * Plugin Name: Custom Post Type Progetto
 * Plugin URI:  https://example.com
 * Description: Registers a Custom Post Type 'progetto' (progetti) with Italian labels.
 * Version:     1.0.0
 * Author:      Your Name
 * Author URI:  https://example.com
 * License:     GPL-2.0-or-later
 * Text Domain: cpt-progetto
 */

// Prevent direct access to this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the 'progetto' Custom Post Type.
 *
 * Hooked into the 'init' action to ensure WordPress is fully loaded
 * before the post type is registered.
 *
 * @since 1.0.0
 * @return void
 */
function cpt_register_progetto() {

	/**
	 * Labels for the 'progetto' Custom Post Type.
	 *
	 * All labels are defined in Italian as required.
	 *
	 * @var array $labels
	 */
	$labels = array(
		'name'                     => _x( 'Progetti', 'post type general name', 'cpt-progetto' ),
		'singular_name'            => _x( 'Progetto', 'post type singular name', 'cpt-progetto' ),
		'add_new'                  => __( 'Aggiungi Nuovo', 'cpt-progetto' ),
		'add_new_item'             => __( 'Aggiungi Nuovo Progetto', 'cpt-progetto' ),
		'edit_item'                => __( 'Modifica Progetto', 'cpt-progetto' ),
		'new_item'                 => __( 'Nuovo Progetto', 'cpt-progetto' ),
		'view_item'                => __( 'Visualizza Progetto', 'cpt-progetto' ),
		'view_items'               => __( 'Visualizza Progetti', 'cpt-progetto' ),
		'search_items'             => __( 'Cerca Progetti', 'cpt-progetto' ),
		'not_found'                => __( 'Nessun progetto trovato.', 'cpt-progetto' ),
		'not_found_in_trash'       => __( 'Nessun progetto trovato nel cestino.', 'cpt-progetto' ),
		'parent_item_colon'        => __( 'Progetto Padre:', 'cpt-progetto' ),
		'all_items'                => __( 'Tutti i Progetti', 'cpt-progetto' ),
		'archives'                 => __( 'Archivio Progetti', 'cpt-progetto' ),
		'attributes'               => __( 'Attributi Progetto', 'cpt-progetto' ),
		'insert_into_item'         => __( 'Inserisci nel progetto', 'cpt-progetto' ),
		'uploaded_to_this_item'    => __( 'Caricato in questo progetto', 'cpt-progetto' ),
		'featured_image'           => __( 'Immagine in Evidenza', 'cpt-progetto' ),
		'set_featured_image'       => __( 'Imposta immagine in evidenza', 'cpt-progetto' ),
		'remove_featured_image'    => __( 'Rimuovi immagine in evidenza', 'cpt-progetto' ),
		'use_featured_image'       => __( 'Usa come immagine in evidenza', 'cpt-progetto' ),
		'menu_name'                => _x( 'Progetti', 'admin menu', 'cpt-progetto' ),
		'filter_items_list'        => __( 'Filtra lista progetti', 'cpt-progetto' ),
		'filter_by_date'           => __( 'Filtra per data', 'cpt-progetto' ),
		'items_list_navigation'    => __( 'Navigazione lista progetti', 'cpt-progetto' ),
		'items_list'               => __( 'Lista progetti', 'cpt-progetto' ),
		'item_published'           => __( 'Progetto pubblicato.', 'cpt-progetto' ),
		'item_published_privately' => __( 'Progetto pubblicato privatamente.', 'cpt-progetto' ),
		'item_reverted_to_draft'   => __( 'Progetto riportato a bozza.', 'cpt-progetto' ),
		'item_scheduled'           => __( 'Progetto programmato.', 'cpt-progetto' ),
		'item_updated'             => __( 'Progetto aggiornato.', 'cpt-progetto' ),
		'item_link'                => _x( 'Link Progetto', 'navigation link block title', 'cpt-progetto' ),
		'item_link_description'    => _x( 'Un link a un singolo progetto.', 'navigation link block description', 'cpt-progetto' ),
	);

	/**
	 * Arguments for the 'progetto' Custom Post Type registration.
	 *
	 * @var array $args
	 */
	$args = array(

		// Labels defined above.
		'labels'            => $labels,

		// A short descriptive summary of what the post type is.
		'description'       => __( 'Archivio dei progetti realizzati.', 'cpt-progetto' ),

		// Whether the post type is intended for use publicly, either via
		// the admin UI or by front-end users.
		'public'            => true,

		// Whether to generate and allow a UI for managing this post type in the admin.
		'show_ui'           => true,

		// Whether to show the post type in the admin menu.
		'show_in_menu'      => true,

		// Whether to make this post type available in the WordPress admin bar.
		'show_in_admin_bar' => true,

		// Whether to show the post type in the nav-menus admin panel.
		'show_in_nav_menus' => true,

		// Makes the post type available via the REST API.
		// Required for Gutenberg (block editor) support.
		'show_in_rest'      => true,

		// The base URL that will be used when accessing the REST API endpoint.
		'rest_base'         => 'progetti',

		// Whether to enable or disable the Gutenberg editor for this post type.
		// Requires 'show_in_rest' => true to take effect.
		'show_in_rest'      => true,

		// Position in the admin menu. 'null' defaults below Comments.
		'menu_position'     => 5,

		// The Dashicon used for the admin menu icon.
		'menu_icon'         => 'dashicons-portfolio',

		// The string to use to build the read, edit, and delete capabilities.
		'capability_type'   => 'post',

		// Whether the post type is hierarchical (like pages). False = flat (like posts).
		'hierarchical'      => false,

		// Enables WordPress post features for this post type.
		'supports'          => array(
			'title',       // Post title field.
			'editor',      // Post content / block editor.
			'thumbnail',   // Featured image.
			'excerpt',     // Post excerpt field.
			'revisions',   // Stores post revisions.
		),

		// Whether there should be post type archives accessible at the rewrite slug.
		'has_archive'       => true,

		// Whether to exclude posts of this type from front-end searches.
		'exclude_from_search' => false,

		// Whether queries can be performed on the front end for this post type.
		'publicly_queryable' => true,

		// Custom permalink structure for this post type.
		'rewrite'           => array(
			// The slug used in the URL.
			'slug'       => 'progetti',

			// Whether to prepend the front base (e.g. /blog/) to URLs.
			'with_front' => false,

			// Whether to add rewrite rules for this post type pages.
			'pages'      => true,

			// Whether to allow feeds for this post type.
			'feeds'      => true,
		),

		// Whether to allow the post type to be exported via the WordPress export tool.
		'can_export'        => true,

		// Whether to delete posts of this type when deleting a user.
		// true  = delete posts when user is deleted.
		// false = do not delete posts.
		'delete_with_user'  => false,

		// Whether to register a default taxonomy for this post type.
		// Optional; can be extended later.
		'taxonomies'        => array(),
	);

	// Register the Custom Post Type.
	register_post_type( 'progetto', $args );
}

/**
 * Hook the registration function into the 'init' action.
 *
 * Using priority 0 ensures the CPT is registered early,
 * making it available for other plugins/themes that may depend on it.
 */
add_action( 'init', 'cpt_register_progetto', 0 );
