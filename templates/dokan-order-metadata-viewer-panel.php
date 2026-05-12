<?php
/**
 * Dokan vendor dashboard "Order Metadata Viewer" panel.
 *
 * @var \WeLabs\MetadataViewer\DokanMetaData $viewer The viewer instance.
 * @var \WC_Order                            $order  The order being viewed.
 */

// don't call the file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="dokan-panel dokan-panel-default">
	<div class="dokan-panel-heading">
		<strong><?php echo esc_html__( 'Order Metadata Viewer', 'metadata-viewer' ); ?></strong>
	</div>
	<div class="dokan-panel-body">
		<?php

		/**
		 * Fires inside the Dokan dashboard order metadata viewer panel body.
		 *
		 * @param \WC_Order                            $order  The order being viewed.
		 * @param \WeLabs\MetadataViewer\DokanMetaData $viewer The viewer instance.
		 */
		do_action( 'metadata_viewer_dokan_order_panel_body', $order, $viewer );
		?>
	</div>
</div>
