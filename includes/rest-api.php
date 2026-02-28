<?php
/**
 * Agency REST API Endpoints
 *
 * Registers custom REST API endpoints for progetti and servizi
 * custom post types with full ACF field support.
 *
 * @package Agency
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register all custom REST API routes.
 *
 * Hooked into rest_api_init to ensure the REST API
 * infrastructure is available before registration.
 *
 * @return void
 */
function agency_register_rest_routes() {
	$namespace = 'agency/v1';

	// -------------------------------------------------------------------------
	// PROGETTI endpoints
	// -------------------------------------------------------------------------

	/**
	 * GET /agency/v1/progetti
	 * Returns a paginated list of all published progetto posts.
	 */
	register_rest_route(
		$namespace,
		'/progetti',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'agency_get_progetti',
			'permission_callback' => '__return_true',
			'args'                => agency_get_collection_args(
				array(
					'categoria' => array(
						'description'       => __( 'Filter results by categoria ACF field value.', 'agency' ),
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
					),
				)
			),
		)
	);

	/**
	 * GET /agency/v1/progetti/{id}
	 * Returns a single progetto by its post ID.
	 */
	register_rest_route(
		$namespace,
		'/progetti/(?P<id>\d+)',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'agency_get_single_progetto',
			'permission_callback' => '__return_true',
			'args'                => array(
				'id' => array(
					'description'       => __( 'Unique identifier for the progetto.', 'agency' ),
					'type'              => 'integer',
					'required'          => true,
					'sanitize_callback' => 'absint',
				),
			),
		)
	);

	// -------------------------------------------------------------------------
	// SERVIZI endpoints
	// -------------------------------------------------------------------------

	/**
	 * GET /agency/v1/servizi
	 * Returns a paginated list of all published servizio posts.
	 */
	register_rest_route(
		$namespace,
		'/servizi',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'agency_get_servizi',
			'permission_callback' => '__return_true',
			'args'                => agency_get_collection_args(
				array(
					'is_featured' => array(
						'description'       => __( 'Filter results to only featured (1) or non-featured (0) servizi.', 'agency' ),
						'type'              => 'integer',
						'enum'              => array( 0, 1 ),
						'sanitize_callback' => 'absint',
					),
				)
			),
		)
	);

	/**
	 * GET /agency/v1/servizi/{id}
	 * Returns a single servizio by its post ID.
	 */
	register_rest_route(
		$namespace,
		'/servizi/(?P<id>\d+)',
		array(
			'methods'             => WP_REST_Server::READABLE,
			'callback'            => 'agency_get_single_servizio',
			'permission_callback' => '__return_true',
			'args'                => array(
				'id' => array(
					'description'       => __( 'Unique identifier for the servizio.', 'agency' ),
					'type'              => 'integer',
					'required'          => true,
					'sanitize_callback' => 'absint',
				),
			),
		)
	);
}
add_action( 'rest_api_init', 'agency_register_rest_routes' );

// =============================================================================
// HELPER FUNCTIONS
// =============================================================================

/**
 * Returns the shared collection argument definitions merged with endpoint-specific ones.
 *
 * @param array $extra_args Additional endpoint-specific argument definitions.
 * @return array Merged argument definitions.
 */
function agency_get_collection_args( array $extra_args = array() ) {
	$base_args = array(
		'per_page' => array(
			'description'       => __( 'Maximum number of items to return per page.', 'agency' ),
			'type'              => 'integer',
			'default'           => 10,
			'minimum'           => 1,
			'maximum'           => 100,
			'sanitize_callback' => 'absint',
		),
		'page'     => array(
			'description'       => __( 'Current page of the collection.', 'agency' ),
			'type'              => 'integer',
			'default'           => 1,
			'minimum'           => 1,
			'sanitize_callback' => 'absint',
		),
		'orderby'  => array(
			'description'       => __( 'Sort collection by post attribute.', 'agency' ),
			'type'              => 'string',
			'default'           => 'date',
			'enum'              => array( 'date', 'title', 'menu_order', 'modified', 'id' ),
			'sanitize_callback' => 'sanitize_key',
		),
		'order'    => array(
			'description'       => __( 'Order sort direction: ASC or DESC.', 'agency' ),
			'type'              => 'string',
			'default'           => 'DESC',
			'enum'              => array( 'ASC', 'DESC' ),
			'sanitize_callback' => 'sanitize_text_field',
		),
	);

	return array_merge( $base_args, $extra_args );
}

/**
 * Builds a WP_Query arguments array common to collection endpoints.
 *
 * @param WP_REST_Request $request   The current REST request object.
 * @param string          $post_type The post type slug to query.
 * @return array WP_Query arguments.
 */
function agency_build_query_args( WP_REST_Request $request, $post_type ) {
	$per_page = (int) $request->get_param( 'per_page' );
	$page     = (int) $request->get_param( 'page' );
	$orderby  = $request->get_param( 'orderby' );
	$order    = strtoupper( $request->get_param( 'order' ) );

	// Map 'id' alias to the WP_Query 'ID' orderby value.
	if ( 'id' === $orderby ) {
		$orderby = 'ID';
	}

	return array(
		'post_type'      => sanitize_key( $post_type ),
		'post_status'    => 'publish',
		'posts_per_page' => $per_page,
		'paged'          => $page,
		'orderby'        => $orderby,
		'order'          => in_array( $order, array( 'ASC', 'DESC' ), true ) ? $order : 'DESC',
		'no_found_rows'  => false, // We need found_posts for pagination headers.
	);
}

/**
 * Formats an ACF image field value into a standardised array.
 *
 * ACF image fields can return an array (when set to "Image Array") or an ID.
 * This helper normalises both cases into a consistent structure.
 *
 * @param mixed $image The ACF image field value (array or attachment ID).
 * @return array|null Normalised image data or null if no image.
 */
function agency_format_image_field( $image ) {
	if ( empty( $image ) ) {
		return null;
	}

	// If ACF returned an array, extract values directly.
	if ( is_array( $image ) ) {
		return array(
			'id'    => isset( $image['ID'] ) ? (int) $image['ID'] : null,
			'url'   => isset( $image['url'] ) ? esc_url_raw( $image['url'] ) : '',
			'alt'   => isset( $image['alt'] ) ? sanitize_text_field( $image['alt'] ) : '',
			'sizes' => isset( $image['sizes'] ) ? $image['sizes'] : array(),
		);
	}

	// If ACF returned an attachment ID, build the data manually.
	if ( is_numeric( $image ) ) {
		$attachment_id = (int) $image;
		$url           = wp_get_attachment_url( $attachment_id );

		if ( ! $url ) {
			return null;
		}

		$alt         = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
		$sizes_data  = array();
		$size_names  = get_intermediate_image_sizes();
		$size_names[] = 'full';

		foreach ( $size_names as $size ) {
			$src = wp_get_attachment_image_src( $attachment_id, $size );
			if ( $src ) {
				$sizes_data[ $size ]            = esc_url_raw( $src[0] );
				$sizes_data[ $size . '-width' ]  = (int) $src[1];
				$sizes_data[ $size . '-height' ] = (int) $src[2];
			}
		}

		return array(
			'id'    => $attachment_id,
			'url'   => esc_url_raw( $url ),
			'alt'   => sanitize_text_field( $alt ),
			'sizes' => $sizes_data,
		);
	}

	return null;
}

/**
 * Adds pagination headers to a WP_REST_Response.
 *
 * @param WP_REST_Response $response   The response object to modify.
 * @param WP_Query         $query      The executed WP_Query instance.
 * @param int              $per_page   Number of items per page.
 * @return WP_REST_Response The modified response with pagination headers.
 */
function agency_add_pagination_headers( WP_REST_Response $response, WP_Query $query, $per_page ) {
	$total      = (int) $query->found_posts;
	$total_pages = ( $per_page > 0 ) ? (int) ceil( $total / $per_page ) : 1;

	$response->header( 'X-WP-Total', $total );
	$response->header( 'X-WP-TotalPages', $total_pages );

	return $response;
}

// =============================================================================
// PROGETTI CALLBACKS
// =============================================================================

/**
 * Callback for GET /agency/v1/progetti
 *
 * Queries and returns all published progetto posts with their ACF fields.
 *
 * @param WP_REST_Request $request Full details about the request.
 * @return WP_REST_Response|WP_Error Response object or WP_Error on failure.
 */
function agency_get_progetti( WP_REST_Request $request ) {
	$query_args = agency_build_query_args( $request, 'progetto' );

	// Apply optional categoria filter via meta query.
	$categoria = $request->get_param( 'categoria' );
	if ( ! empty( $categoria ) ) {
		$query_args['meta_query'] = array(
			array(
				'key'     => 'categoria',
				'value'   => sanitize_text_field( $categoria ),
				'compare' => '=',
			),
		);
	}

	$query = new WP_Query( $query_args );
	$items = array();

	if ( $query->have_posts() ) {
		foreach ( $query->posts as $post ) {
			$items[] = agency_format_progetto( $post );
		}
	}

	$response = new WP_REST_Response( $items, 200 );
	$response = agency_add_pagination_headers( $response, $query, $query_args['posts_per_page'] );

	return $response;
}

/**
 * Callback for GET /agency/v1/progetti/{id}
 *
 * Returns a single progetto post by its ID.
 *
 * @param WP_REST_Request $request Full details about the request.
 * @return WP_REST_Response|WP_Error Response object or 404 WP_Error.
 */
function agency_get_single_progetto( WP_REST_Request $request ) {
	$post_id = (int) $request->get_param( 'id' );
	$post    = get_post( $post_id );

	if ( ! $post || 'progetto' !== $post->post_type || 'publish' !== $post->post_status ) {
		return new WP_Error(
			'agency_progetto_not_found',
			__( 'Progetto not found.', 'agency' ),
			array( 'status' => 404 )
		);
	}

	$data     = agency_format_progetto( $post );
	$response = new WP_REST_Response( $data, 200 );

	return $response;
}

/**
 * Formats a single progetto WP_Post object into the API response shape.
 *
 * @param WP_Post $post The post object to format.
 * @return array Formatted progetto data.
 */
function agency_format_progetto( WP_Post $post ) {
	$post_id = $post->ID;

	// -------------------------------------------------------------------------
	// Galleria: ACF repeater field returning rows with image + caption.
	// -------------------------------------------------------------------------
	$galleria_raw = get_field( 'galleria', $post_id );
	$galleria     = array();

	if ( ! empty( $galleria_raw ) && is_array( $galleria_raw ) ) {
		foreach ( $galleria_raw as $row ) {
			$galleria[] = array(
				'immagine'   => agency_format_image_field( isset( $row['immagine'] ) ? $row['immagine'] : null ),
				'didascalia' => isset( $row['didascalia'] ) ? sanitize_text_field( $row['didascalia'] ) : '',
			);
		}
	}

	// -------------------------------------------------------------------------
	// Tag Tecnologie: ACF repeater field returning rows with technology name.
	// -------------------------------------------------------------------------
	$tag_raw  = get_field( 'tag_tecnologie', $post_id );
	$tag_list = array();

	if ( ! empty( $tag_raw ) && is_array( $tag_raw ) ) {
		foreach ( $tag_raw as $row ) {
			$tag_list[] = array(
				'nome_tecnologia' => isset( $row['nome_tecnologia'] ) ? sanitize_text_field( $row['nome_tecnologia'] ) : '',
			);
		}
	}

	return array(
		'id'                   => $post_id,
		'title'                => get_the_title( $post ),
		'slug'                 => $post->post_name,
		'date'                 => get_the_date( 'c', $post ),

		// ACF fields.
		'titolo_progetto'      => sanitize_text_field( (string) get_field( 'titolo_progetto', $post_id ) ),
		'cliente'              => sanitize_text_field( (string) get_field( 'cliente', $post_id ) ),
		'categoria'            => sanitize_text_field( (string) get_field( 'categoria', $post_id ) ),
		'anno'                 => sanitize_text_field( (string) get_field( 'anno', $post_id ) ),
		'descrizione_breve'    => wp_kses_post( (string) get_field( 'descrizione_breve', $post_id ) ),
		'descrizione_completa' => wp_kses_post( (string) get_field( 'descrizione_completa', $post_id ) ),
		'immagine_hero'        => agency_format_image_field( get_field( 'immagine_hero', $post_id ) ),
		'galleria'             => $galleria,
		'link_live'            => esc_url_raw( (string) get_field( 'link_live', $post_id ) ),
		'tag_tecnologie'       => $tag_list,
	);
}

// =============================================================================
// SERVIZI CALLBACKS
// =============================================================================

/**
 * Callback for GET /agency/v1/servizi
 *
 * Queries and returns all published servizio posts with their ACF fields.
 *
 * @param WP_REST_Request $request Full details about the request.
 * @return WP_REST_Response|WP_Error Response object or WP_Error on failure.
 */
function agency_get_servizi( WP_REST_Request $request ) {
	$query_args = agency_build_query_args( $request, 'servizio' );

	// Apply optional is_featured filter via meta query.
	$is_featured = $request->get_param( 'is_featured' );
	if ( null !== $is_featured && '' !== $is_featured ) {
		$query_args['meta_query'] = array(
			array(
				'key'     => 'is_featured',
				// ACF boolean fields are stored as '1' or '0' in the database.
				'value'   => (int) $is_featured ? '1' : '0',
				'compare' => '=',
			),
		);
	}

	$query = new WP_Query( $query_args );
	$items = array();

	if ( $query->have_posts() ) {
		foreach ( $query->posts as $post ) {
			$items[] = agency_format_servizio( $post );
		}
	}

	$response = new WP_REST_Response( $items, 200 );
	$response = agency_add_pagination_headers( $response, $query, $query_args['posts_per_page'] );

	return $response;
}

/**
 * Callback for GET /agency/v1/servizi/{id}
 *
 * Returns a single servizio post by its ID.
 *
 * @param WP_REST_Request $request Full details about the request.
 * @return WP_REST_Response|WP_Error Response object or 404 WP_Error.
 */
function agency_get_single_servizio( WP_REST_Request $request ) {
	$post_id = (int) $request->get_param( 'id' );
	$post    = get_post( $post_id );

	if ( ! $post || 'servizio' !== $post->post_type || 'publish' !== $post->post_status ) {
		return new WP_Error(
			'agency_servizio_not_found',
			__( 'Servizio not found.', 'agency' ),
			array( 'status' => 404 )
		);
	}

	$data     = agency_format_servizio( $post );
	$response = new WP_REST_Response( $data, 200 );

	return $response;
}

/**
 * Formats a single servizio WP_Post object into the API response shape.
 *
 * @param WP_Post $post The post object to format.
 * @return array Formatted servizio data.
 */
function agency_format_servizio( WP_Post $post ) {
	$post_id = $post->ID;

	// -------------------------------------------------------------------------
	// Lista Features: ACF repeater field returning rows with a feature string.
	// -------------------------------------------------------------------------
	$features_raw = get_field( 'lista_features', $post_id );
	$features     = array();

	if ( ! empty( $features_raw ) && is_array( $features_raw ) ) {
		foreach ( $features_raw as $row ) {
			$features[] = array(
				'feature' => isset( $row['feature'] ) ? sanitize_text_field( $row['feature'] ) : '',
			);
		}
	}

	// Cast is_featured to a strict boolean for JSON output.
	$is_featured_raw = get_field( 'is_featured', $post_id );
	$is_featured     = (bool) $is_featured_raw;

	return array(
		'id'               => $post_id,
		'title'            => get_the_title( $post ),
		'slug'             => $post->post_name,
		'date'             => get_the_date( 'c', $post ),

		// ACF fields.
		'titolo_servizio'  => sanitize_text_field( (string) get_field( 'titolo_servizio', $post_id ) ),
		'sottotitolo'      => sanitize_text_field( (string) get_field( 'sottotitolo', $post_id ) ),
		'icona_svg'        => wp_kses(
								(string) get_field( 'icona_svg', $post_id ),
								array(
									'svg'  => array(
										'xmlns'   => true,
										'width'   => true,
										'height'  => true,
										'viewbox' => true,
										'fill'    => true,
										'class'   => true,
										'aria-hidden' => true,
									),
									'path' => array(
										'd'    => true,
										'fill' => true,
									),
									'circle' => array(
										'cx'   => true,
										'cy'   => true,
										'r'    => true,
										'fill' => true,
									),
									'rect' => array(
										'x'      => true,
										'y'      => true,
										'width'  => true,
										'height' => true,
										'fill'   => true,
									),
									'polyline' => array(
										'points' => true,
										'fill'   => true,
										'stroke' => true,
									),
									'line' => array(
										'x1'     => true,
										'y1'     => true,
										'x2'     => true,
										'y2'     => true,
										'stroke' => true,
									),
								)
							),
		'descrizione'      => wp_kses_post( (string) get_field( 'descrizione', $post_id ) ),
		'lista_features'   => $features,
		'prezzo_da'        => sanitize_text_field( (string) get_field( 'prezzo_da', $post_id ) ),
		'cta_label'        => sanitize_text_field( (string) get_field( 'cta_label', $post_id ) ),
		'cta_link'         => esc_url_raw( (string) get_field( 'cta_link', $post_id ) ),
		'is_featured'      => $is_featured,
	);
}