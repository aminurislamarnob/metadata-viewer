<?php
/**
 * Dokan vendor dashboard "Product Metadata Viewer" panel.
 *
 * @var \WeLabs\MetadataViewer\DokanMetaData $viewer     The viewer instance.
 * @var int                                  $product_id The product being edited.
 */

// don't call the file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="dokan-panel dokan-panel-default">
	<div class="dokan-panel-heading">
		<strong><?php echo esc_html__( 'Product Metadata Viewer', 'metadata-viewer' ); ?></strong>
	</div>
	<div class="dokan-panel-body">
		<?php

		/**
		 * Fires inside the Dokan dashboard product metadata viewer panel body.
		 *
		 * @param int                                  $product_id The product being edited.
		 * @param \WeLabs\MetadataViewer\DokanMetaData $viewer     The viewer instance.
		 */
		do_action( 'metadata_viewer_dokan_product_panel_body', $product_id, $viewer );
		?>
	</div>
</div>
