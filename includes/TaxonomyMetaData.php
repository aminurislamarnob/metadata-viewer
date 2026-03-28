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
		?>
		<div id="post-metadata-viewer" class="postbox">
			<div class="postbox-header">
				<h2 class="hndle"><?php echo esc_html__( 'Taxonomy Metadata Viewer', 'metadata-viewer' ); ?></h2>
			</div>
			<div class="inside">
				<?php Helpers::get_metadata_table_view( $term_meta ); ?>
			</div>
		</div>
		<?php
	}
}
