<?php

namespace WeLabs\MetadataViewer;

// don't call the file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * DokanOrderMetaData class
 *
 * Renders the order metadata viewer at the bottom of the Dokan vendor
 * dashboard "Order Details" page, reusing the same table + HPOS-aware
 * meta resolution as the wp-admin order metadata viewer.
 *
 * @class DokanOrderMetaData
 */
class DokanOrderMetaData extends OrderMetaData {
	/**
	 * The constructor.
	 */
	public function __construct() {
		// End of the Dokan dashboard order content wrapper (after the order details template).
		add_action( 'dokan_order_content_inside_after', array( $this, 'render_dokan_order_metadata' ), 20 );
	}

	/**
	 * Output the metadata viewer wrapped in a Dokan dashboard panel.
	 *
	 * The `dokan_order_content_inside_after` hook passes no args, so the order
	 * is resolved from the request the same way Dokan core does.
	 *
	 * @return void
	 */
	public function render_dokan_order_metadata() {
		if ( ! isset( $_GET['order_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only dashboard view, mirrors Dokan core.
			return;
		}

		$order = wc_get_order( absint( wp_unslash( $_GET['order_id'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( ! is_object( $order ) || ! method_exists( $order, 'get_id' ) || empty( $order->get_id() ) ) {
			return;
		}

		$this->enqueue_assets();
		?>
		<div class="dokan-panel dokan-panel-default">
			<div class="dokan-panel-heading">
				<strong><?php echo esc_html__( 'Order Metadata Viewer', 'metadata-viewer' ); ?></strong>
			</div>
			<div class="dokan-panel-body">
				<?php
				add_filter( 'metadata_viewer_order_search_icon_html', array( $this, 'dokan_search_icon_html' ) );
				$this->render_show_order_metadata( $order );
				remove_filter( 'metadata_viewer_order_search_icon_html', array( $this, 'dokan_search_icon_html' ) );
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Search box icon for the Dokan dashboard — use Dokan's Font Awesome icon
	 * instead of the wp-admin dashicon.
	 *
	 * @return string
	 */
	public function dokan_search_icon_html() {
		return '<i class="fas fa-search" aria-hidden="true"></i>';
	}

	/**
	 * Enqueue the metadata viewer assets on the frontend (Dokan dashboard).
	 *
	 * Assets are registered on `init` by Assets::register_all_scripts(); they
	 * are only auto-enqueued in wp-admin, so enqueue them here when the Dokan
	 * order details template renders. Styles/scripts requested this late are
	 * printed in the footer by WordPress.
	 *
	 * @return void
	 */
	private function enqueue_assets() {
		wp_enqueue_style( 'metadata_viewer_admin_style' );
		wp_enqueue_script( 'metadata_viewer_highlight_script' );
		wp_enqueue_script( 'metadata_viewer_admin_script' );
	}
}
