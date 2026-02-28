<?php
/**
 * ACF Local Field Groups: Dettagli Progetto & Dettagli Servizio
 *
 * Registers two Advanced Custom Fields field groups programmatically
 * using acf_add_local_field_group(), eliminating any dependency on
 * the ACF UI or exported JSON/PHP files.
 *
 * @package    MyTheme
 * @subpackage ACF
 * @author     Developer
 * @version    1.0.0
 *
 * Usage: require or include this file from functions.php, or drop it
 * into a mu-plugins directory so it loads automatically.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevent direct access.
}

/**
 * Register all local ACF field groups.
 *
 * Hooked into 'acf/init' so that ACF is fully bootstrapped before we
 * attempt to register anything.
 */
add_action( 'acf/init', 'mytheme_register_acf_field_groups' );

/**
 * Callback: define and register every local field group.
 *
 * @return void
 */
function mytheme_register_acf_field_groups() {

	// Bail early if ACF is not active / the function does not exist.
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	// =========================================================================
	// FIELD GROUP 1 â Dettagli Progetto
	// Post type: progetto
	// =========================================================================
	acf_add_local_field_group(
		array(
			// -----------------------------------------------------------------
			// Group identity
			// -----------------------------------------------------------------
			'key'                   => 'group_progetti_details',
			'title'                 => 'Dettagli Progetto',

			// -----------------------------------------------------------------
			// Location: show only on the 'progetto' custom post type.
			// -----------------------------------------------------------------
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'progetto',
					),
				),
			),

			// -----------------------------------------------------------------
			// Display settings
			// -----------------------------------------------------------------
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => array(),
			'active'                => true,
			'description'           => 'Campi personalizzati per i progetti del portfolio.',

			// -----------------------------------------------------------------
			// Fields
			// -----------------------------------------------------------------
			'fields'                => array(

				// 1. Titolo del Progetto ----------------------------------------
				array(
					'key'               => 'field_proj_titolo_progetto',
					'label'             => 'Titolo del Progetto',
					'name'              => 'titolo_progetto',
					'type'              => 'text',
					'instructions'      => 'Inserisci il titolo completo del progetto.',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => '',
					'placeholder'       => '',
					'prepend'           => '',
					'append'            => '',
					'maxlength'         => '',
				),

				// 2. Cliente ----------------------------------------------------
				array(
					'key'               => 'field_proj_cliente',
					'label'             => 'Cliente',
					'name'              => 'cliente',
					'type'              => 'text',
					'instructions'      => 'Nome del cliente o committente.',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => '',
					'placeholder'       => '',
					'prepend'           => '',
					'append'            => '',
					'maxlength'         => '',
				),

				// 3. Categoria (select) -----------------------------------------
				array(
					'key'               => 'field_proj_categoria',
					'label'             => 'Categoria',
					'name'              => 'categoria',
					'type'              => 'select',
					'instructions'      => 'Seleziona la categoria principale del progetto.',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					// Choices: key => label pairs.
					'choices'           => array(
						'branding' => 'Branding',
						'web'      => 'Web',
						'motion'   => 'Motion',
						'3d'       => '3D',
					),
					'default_value'     => array(),
					'allow_null'        => 1,
					'multiple'          => 0,
					'ui'                => 0,
					'return_format'     => 'value',
					'ajax'              => 0,
					'placeholder'       => '',
				),

				// 4. Anno (number) ----------------------------------------------
				array(
					'key'               => 'field_proj_anno',
					'label'             => 'Anno',
					'name'              => 'anno',
					'type'              => 'number',
					'instructions'      => 'Anno di realizzazione del progetto (es. 2024).',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => '',
					'placeholder'       => '',
					'prepend'           => '',
					'append'            => '',
					'min'               => 1900,
					'max'               => 2100,
					'step'              => 1,
				),

				// 5. Descrizione Breve (textarea) --------------------------------
				array(
					'key'               => 'field_proj_descrizione_breve',
					'label'             => 'Descrizione Breve',
					'name'              => 'descrizione_breve',
					'type'              => 'textarea',
					'instructions'      => 'Breve sintesi del progetto (usata in listing e anteprime).',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => '',
					'placeholder'       => '',
					'maxlength'         => '',
					'rows'              => 3,
					'new_lines'         => 'wpautop', // Converts line-breaks to <p> tags.
				),

				// 6. Descrizione Completa (wysiwyg) ------------------------------
				array(
					'key'               => 'field_proj_descrizione_completa',
					'label'             => 'Descrizione Completa',
					'name'              => 'descrizione_completa',
					'type'              => 'wysiwyg',
					'instructions'      => 'Contenuto editoriale completo del progetto.',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => '',
					'tabs'              => 'all',      // Show both Visual and Text tabs.
					'toolbar'           => 'full',     // Use the full TinyMCE toolbar.
					'media_upload'      => 1,
					'delay'             => 0,
				),

				// 7. Immagine Hero (image) ---------------------------------------
				array(
					'key'               => 'field_proj_immagine_hero',
					'label'             => 'Immagine Hero',
					'name'              => 'immagine_hero',
					'type'              => 'image',
					'instructions'      => 'Immagine principale visualizzata in cima alla pagina progetto.',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'return_format'     => 'array',   // Returns full image data array.
					'preview_size'      => 'medium',
					'library'           => 'all',
					'min_width'         => '',
					'min_height'        => '',
					'min_size'          => '',
					'max_width'         => '',
					'max_height'        => '',
					'max_size'          => '',
					'mime_types'        => '',
				),

				// 8. Galleria (repeater) ----------------------------------------
				array(
					'key'               => 'field_proj_galleria',
					'label'             => 'Galleria',
					'name'              => 'galleria',
					'type'              => 'repeater',
					'instructions'      => 'Aggiungi le immagini della galleria del progetto.',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'collapsed'         => '',
					'min'               => 0,
					'max'               => 0,
					'layout'            => 'block',   // 'table', 'block', or 'row'.
					'button_label'      => 'Aggiungi Immagine',
					// Sub-fields ------------------------------------------------
					'sub_fields'        => array(

						// 8a. Immagine
						array(
							'key'               => 'field_proj_galleria_immagine',
							'label'             => 'Immagine',
							'name'              => 'immagine',
							'type'              => 'image',
							'instructions'      => '',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'return_format'     => 'array',
							'preview_size'      => 'thumbnail',
							'library'           => 'all',
							'min_width'         => '',
							'min_height'        => '',
							'min_size'          => '',
							'max_width'         => '',
							'max_height'        => '',
							'max_size'          => '',
							'mime_types'        => '',
						),

						// 8b. Didascalia
						array(
							'key'               => 'field_proj_galleria_didascalia',
							'label'             => 'Didascalia',
							'name'              => 'didascalia',
							'type'              => 'text',
							'instructions'      => 'Testo alternativo o didascalia per questa immagine.',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'maxlength'         => '',
						),
					),
				),

				// 9. Link Live (url) --------------------------------------------
				array(
					'key'               => 'field_proj_link_live',
					'label'             => 'Link Live',
					'name'              => 'link_live',
					'type'              => 'url',
					'instructions'      => 'URL pubblico del progetto online (opzionale).',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => '',
					'placeholder'       => 'https://',
				),

				// 10. Tag Tecnologie (repeater) ----------------------------------
				array(
					'key'               => 'field_proj_tag_tecnologie',
					'label'             => 'Tag Tecnologie',
					'name'              => 'tag_tecnologie',
					'type'              => 'repeater',
					'instructions'      => 'Elenca le tecnologie o gli strumenti usati nel progetto.',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'collapsed'         => '',
					'min'               => 0,
					'max'               => 0,
					'layout'            => 'table',
					'button_label'      => 'Aggiungi Tecnologia',
					// Sub-fields ------------------------------------------------
					'sub_fields'        => array(

						// 10a. Nome Tecnologia
						array(
							'key'               => 'field_proj_tag_tecnologie_nome',
							'label'             => 'Nome Tecnologia',
							'name'              => 'nome_tecnologia',
							'type'              => 'text',
							'instructions'      => 'Es. WordPress, React, Figmaâ¦',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'maxlength'         => '',
						),
					),
				),

			), // end fields â group_progetti_details
		)
	);

	// =========================================================================
	// FIELD GROUP 2 â Dettagli Servizio
	// Post type: servizio
	// =========================================================================
	acf_add_local_field_group(
		array(
			// -----------------------------------------------------------------
			// Group identity
			// -----------------------------------------------------------------
			'key'                   => 'group_servizi_details',
			'title'                 => 'Dettagli Servizio',

			// -----------------------------------------------------------------
			// Location: show only on the 'servizio' custom post type.
			// -----------------------------------------------------------------
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'servizio',
					),
				),
			),

			// -----------------------------------------------------------------
			// Display settings
			// -----------------------------------------------------------------
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => array(),
			'active'                => true,
			'description'           => 'Campi personalizzati per i servizi offerti.',

			// -----------------------------------------------------------------
			// Fields
			// -----------------------------------------------------------------
			'fields'                => array(

				// 1. Titolo del Servizio (text) ----------------------------------
				array(
					'key'               => 'field_serv_titolo_servizio',
					'label'             => 'Titolo del Servizio',
					'name'              => 'titolo_servizio',
					'type'              => 'text',
					'instructions'      => 'Il nome principale del servizio.',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => '',
					'placeholder'       => '',
					'prepend'           => '',
					'append'            => '',
					'maxlength'         => '',
				),

				// 2. Sottotitolo (text) -----------------------------------------
				array(
					'key'               => 'field_serv_sottotitolo',
					'label'             => 'Sottotitolo',
					'name'              => 'sottotitolo',
					'type'              => 'text',
					'instructions'      => 'Breve frase descrittiva sotto al titolo.',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => '',
					'placeholder'       => '',
					'prepend'           => '',
					'append'            => '',
					'maxlength'         => '',
				),

				// 3. Icona SVG (textarea) ----------------------------------------
				array(
					'key'               => 'field_serv_icona_svg',
					'label'             => 'Icona SVG',
					'name'              => 'icona_svg',
					'type'              => 'textarea',
					'instructions'      => 'Incolla qui il codice SVG raw dell\u2019icona del servizio.',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => '',
					'placeholder'       => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">...</svg>',
					'maxlength'         => '',
					'rows'              => 4,
					'new_lines'         => '',  // Do not alter raw SVG content.
				),

				// 4. Descrizione (wysiwyg) ----------------------------------------
				array(
					'key'               => 'field_serv_descrizione',
					'label'             => 'Descrizione',
					'name'              => 'descrizione',
					'type'              => 'wysiwyg',
					'instructions'      => 'Descrizione dettagliata del servizio.',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => '',
					'tabs'              => 'all',
					'toolbar'           => 'full',
					'media_upload'      => 1,
					'delay'             => 0,
				),

				// 5. Lista Features (repeater) ------------------------------------
				array(
					'key'               => 'field_serv_lista_features',
					'label'             => 'Lista Features',
					'name'              => 'lista_features',
					'type'              => 'repeater',
					'instructions'      => 'Aggiungi i punti di forza o le caratteristiche incluse nel servizio.',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'collapsed'         => '',
					'min'               => 0,
					'max'               => 0,
					'layout'            => 'table',
					'button_label'      => 'Aggiungi Feature',
					// Sub-fields ------------------------------------------------
					'sub_fields'        => array(

						// 5a. Feature
						array(
							'key'               => 'field_serv_lista_features_feature',
							'label'             => 'Feature',
							'name'              => 'feature',
							'type'              => 'text',
							'instructions'      => 'Descrizione breve di una singola caratteristica.',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => array(
								'width' => '',
								'class' => '',
								'id'    => '',
							),
							'default_value'     => '',
							'placeholder'       => '',
							'prepend'           => '',
							'append'            => '',
							'maxlength'         => '',
						),
					),
				),

				// 6. Prezzo Da (text) -------------------------------------------
				array(
					'key'               => 'field_serv_prezzo_da',
					'label'             => 'Prezzo Da',
					'name'              => 'prezzo_da',
					'type'              => 'text',
					'instructions'      => 'Indicazione testuale del prezzo di partenza (es. "da \u20ac500").',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => '',
					'placeholder'       => 'da \u20ac500',
					'prepend'           => '',
					'append'            => '',
					'maxlength'         => '',
				),

				// 7. CTA Label (text) -------------------------------------------
				array(
					'key'               => 'field_serv_cta_label',
					'label'             => 'CTA Label',
					'name'              => 'cta_label',
					'type'              => 'text',
					'instructions'      => 'Testo del pulsante call-to-action (es. "Richiedi un preventivo").',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => '',
					'placeholder'       => 'Richiedi un preventivo',
					'prepend'           => '',
					'append'            => '',
					'maxlength'         => '',
				),

				// 8. CTA Link (url) ---------------------------------------------
				array(
					'key'               => 'field_serv_cta_link',
					'label'             => 'CTA Link',
					'name'              => 'cta_link',
					'type'              => 'url',
					'instructions'      => 'URL a cui punta il pulsante call-to-action.',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'default_value'     => '',
					'placeholder'       => 'https://',
				),

				// 9. In Evidenza / is_featured (true_false) ----------------------
				array(
					'key'               => 'field_serv_is_featured',
					'label'             => 'In Evidenza',
					'name'              => 'is_featured',
					'type'              => 'true_false',
					'instructions'      => 'Attiva per mettere in evidenza questo servizio nella homepage o in listing speciali.',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => array(
						'width' => '',
						'class' => '',
						'id'    => '',
					),
					'message'           => 'Metti in evidenza questo servizio',
					'default_value'     => 0,   // Off by default.
					'ui'                => 1,   // Render as a toggle switch.
					'ui_on_text'        => 'S\u00ec',
					'ui_off_text'       => 'No',
				),

			), // end fields â group_servizi_details
		)
	);
}
