<?php
/**
 * Custom Post Type: Servizio
 *
 * Registers the 'servizio' Custom Post Type for managing services.
 *
 * @package    CustomPostTypes
 * @subpackage Servizio
 * @version    1.0.0
 * @author     Your Name
 * @license    GPL-2.0-or-later
 *
 * @wordpress-plugin
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the 'servizio' Custom Post Type.
 *
 * This function defines all labels in Italian and registers the CPT
 * with the appropriate settings for public visibility, REST API support,
 * and archive pages.
 *
 * @since 1.0.0
 * @return void
 */
function cpt_register_servizio() {

	/**
	 * Labels for the 'servizio' Custom Post Type.
	 *
	 * All labels are defined in Italian as required.
	 *
	 * @var array $labels
	 */
	$labels = array(
		'name'                  => _x( 'Servizi', 'Post type general name', 'textdomain' ),
		'singular_name'         => _x( 'Servizio', 'Post type singular name', 'textdomain' ),
		'menu_name'             => _x( 'Servizi', 'Admin Menu text', 'textdomain' ),
		'name_admin_bar'        => _x( 'Servizio', 'Add New on Toolbar', 'textdomain' ),
		'add_new'               => __( 'Aggiungi Nuovo', 'textdomain' ),
		'add_new_item'          => __( 'Aggiungi Nuovo Servizio', 'textdomain' ),
		'new_item'              => __( 'Nuovo Servizio', 'textdomain' ),
		'edit_item'             => __( 'Modifica Servizio', 'textdomain' ),
		'view_item'             => __( 'Visualizza Servizio', 'textdomain' ),
		'view_items'            => __( 'Visualizza Servizi', 'textdomain' ),
		'all_items'             => __( 'Tutti i Servizi', 'textdomain' ),
		'search_items'          => __( 'Cerca Servizi', 'textdomain' ),
		'parent_item_colon'     => __( 'Servizio Genitore:', 'textdomain' ),
		'not_found'             => __( 'Nessun servizio trovato.', 'textdomain' ),
		'not_found_in_trash'    => __( 'Nessun servizio trovato nel cestino.', 'textdomain' ),
		'featured_image'        => _x( 'Immagine in Evidenza', 'Overrides the \'Featured Image\' phrase for this post type.', 'textdomain' ),
		'set_featured_image'    => _x( 'Imposta immagine in evidenza', 'Overrides the \'Set featured image\' phrase for this post type.', 'textdomain' ),
		'remove_featured_image' => _x( 'Rimuovi immagine in evidenza', 'Overrides the \'Remove featured image\' phrase for this post type.', 'textdomain' ),
		'use_featured_image'    => _x( 'Usa come immagine in evidenza', 'Overrides the \'Use as featured image\' phrase for this post type.', 'textdomain' ),
		'archives'              => _x( 'Archivio Servizi', 'The post type archive label used in nav menus.', 'textdomain' ),
		'insert_into_item'      => _x( 'Inserisci nel servizio', 'Overrides the \'Insert into post\' phrase for this post type.', 'textdomain' ),
		'uploaded_to_this_item' => _x( 'Caricato in questo servizio', 'Overrides the \'Uploaded to this post\' phrase for this post type.', 'textdomain' ),
		'filter_items_list'     => _x( 'Filtra lista servizi', 'Screen reader text for the filter links heading on the post type listing screen.', 'textdomain' ),
		'items_list_navigation' => _x( 'Navigazione lista servizi', 'Screen reader text for the pagination heading on the post type listing screen.', 'textdomain' ),
		'items_list'            => _x( 'Lista servizi', 'Screen reader text for the items list heading on the post type listing screen.', 'textdomain' ),
		'item_published'        => __( 'Servizio pubblicato.', 'textdomain' ),
		'item_published_privately' => __( 'Servizio pubblicato privatamente.', 'textdomain' ),
		'item_reverted_to_draft'   => __( 'Servizio riportato in bozza.', 'textdomain' ),
		'item_scheduled'           => __( 'Servizio pianificato.', 'textdomain' ),
		'item_updated'             => __( 'Servizio aggiornato.', 'textdomain' ),
	);

	/**
	 * Arguments for the 'servizio' Custom Post Type registration.
	 *
	 * @var array $args
	 */
	$args = array(
		// Assign the labels defined above.
		'labels'            => $labels,

		// A short descriptive summary of what the post type is.
		'description'       => __( 'Gestione dei servizi offerti.', 'textdomain' ),

		// Make the post type public and visible in the admin UI and on the front end.
		'public'            => true,

		// Show the post type in the admin menu.
		'show_ui'           => true,

		// Make the post type available for selection in navigation menus.
		'show_in_nav_menus' => true,

		// Show the post type in the admin bar "New" dropdown.
		'show_in_admin_bar' => true,

		// Expose this post type in the WordPress REST API.
		'show_in_rest'      => true,

		// The base URL slug used for the REST API endpoint.
		'rest_base'         => 'servizi',

		// Position in the admin menu (below 'Posts' and 'Media' by default).
		'menu_position'     => 5,

		// Dashicon for the admin menu icon.
		'menu_icon'         => 'dashicons-hammer',

		// Use the same capabilities as the built-in 'post' type.
		'capability_type'   => 'post',

		// Enable hierarchical (page-like) structure. False means flat like posts.
		'hierarchical'      => false,

		// Enable the archive page at the rewrite slug.
		'has_archive'       => true,

		// Core features this post type supports.
		'supports'          => array(
			'title',
			'editor',
			'thumbnail',
			'excerpt',
			'revisions',
		),

		// Custom rewrite rules for pretty permalinks.
		'rewrite'           => array(
			// The URL slug for this post type.
			'slug'       => 'servizi',

			// Prepend the front base (e.g. /blog/) to the slug.
			'with_front' => true,

			// Enable archive rewrite rules.
			'feeds'      => true,

			// Add paging rewrite rules.
			'pages'      => true,
		),

		// Triggers the handling of rewrites when the post type is created.
		'query_var'         => true,
	);

	// Register the Custom Post Type with WordPress.
	register_post_type( 'servizio', $args );
}

/**
 * Hook the CPT registration function into the 'init' action.
 *
 * Using priority 0 ensures the CPT is registered early,
 * before other hooks that might depend on it.
 */
add_action( 'init', 'cpt_register_servizio', 0 );