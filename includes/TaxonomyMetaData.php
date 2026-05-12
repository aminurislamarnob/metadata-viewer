<?php

namespace WeLabs\MetadataViewer;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * TaxonomyMetaData class
 *
 * @class TaxonomyMetaData The class that holds term metadata related settings
 */
class TaxonomyMetaData {
	/**
	 * The constructor.
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'register_taxonomy_edit_hooks' ) );

		// Render the metadata table inside the postbox body of the template.
		add_action( 'metadata_viewer_taxonomy_metadata_body', array( $this, 'render_metadata_table' ) );
	}

	/**
	 * Register taxonomy edit screen hooks.
	 *
	 * @return void
	 */
	public function register_taxonomy_edit_hooks() {
		$taxonomies = get_taxonomies( array(), 'names' );

		foreach ( $taxonomies as $taxonomy ) {
			add_action( "{$taxonomy}_edit_form", array( $this, 'render_show_taxonomy_metadata' ), 999, 2 );
		}
	}

	/**
	 * Render metadata table on taxonomy edit screen.
	 *
	 * @param \WP_Term $term Term object.
	 * @param string   $taxonomy Taxonomy key.
	 *
	 * @return void
	 */
	public function render_show_taxonomy_metadata( $term, $taxonomy ) {
		unset( $taxonomy );

		if ( ! isset( $term->term_id ) ) {
			return;
		}

		$term_meta = get_metadata( 'term', (int) $term->term_id );

		welabs_metadata_viewer()->get_template(
			'taxonomy-metadata-viewer.php',
			array(
				'term_meta' => $term_meta,
			)
		);
	}

	/**
	 * Render the metadata table inside the taxonomy viewer postbox body.
	 *
	 * Hooked on `metadata_viewer_taxonomy_metadata_body` from the template.
	 *
	 * @param array $term_meta The term meta array.
	 *
	 * @return void
	 */
	public function render_metadata_table( $term_meta ) {
		Helpers::get_metadata_table_view( $term_meta );
	}
}
