<?php
/**
 * Taxonomy term metadata viewer (taxonomy edit screen).
 *
 * @var array $term_meta The term meta returned by get_metadata( 'term', $term_id ).
 */

// don't call the file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="post-metadata-viewer" class="postbox">
	<div class="postbox-header">
		<h2 class="hndle"><?php echo esc_html__( 'Taxonomy Metadata Viewer', 'metadata-viewer' ); ?></h2>
	</div>
	<div class="inside">
		<?php

		/**
		 * Fires inside the taxonomy metadata viewer postbox body.
		 *
		 * @param array $term_meta The term meta returned by get_metadata( 'term', $term_id ).
		 */
		do_action( 'metadata_viewer_taxonomy_metadata_body', $term_meta );
		?>
	</div>
</div>
