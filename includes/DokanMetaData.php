<?php

namespace WeLabs\MetadataViewer;

// don't call the file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * DokanMetaData class
 *
 * Renders the metadata viewer on Dokan vendor dashboard pages — the "Order
 * Details" page and the "Edit Product" page — reusing the same tables (and
 * HPOS-aware order meta resolution) as the wp-admin viewers.
 *
 * @class DokanMetaData
 */
class DokanMetaData {
	/**
	 * The constructor.
	 */
	public function __construct() {
		// Order details page.
		add_action( 'dokan_order_content_inside_after', array( $this, 'render_order_metadata' ), 20 );
		add_action( 'metadata_viewer_dokan_order_panel_body', array( $this, 'render_order_panel_body' ) );

		// Product edit page.
		add_action( 'dokan_product_content_inside_area_after', array( $this, 'render_product_metadata' ), 20 );
		add_action( 'metadata_viewer_dokan_product_panel_body', array( $this, 'render_product_panel_body' ) );
	}

	/**
	 * Output the order metadata viewer wrapped in a Dokan dashboard panel.
	 *
	 * The `dokan_order_content_inside_after` hook passes no args, so the order
	 * is resolved from the request the same way Dokan core does.
	 *
	 * @return void
	 */
	public function render_order_metadata() {
		if ( ! isset( $_GET['order_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only dashboard view, mirrors Dokan core.
			return;
		}

		$order = wc_get_order( absint( wp_unslash( $_GET['order_id'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( ! is_object( $order ) || ! method_exists( $order, 'get_id' ) || empty( $order->get_id() ) ) {
			return;
		}

		$this->enqueue_assets();

		welabs_metadata_viewer()->get_template(
			'dokan-order-metadata-viewer-panel.php',
			array(
				'viewer' => $this,
				'order'  => $order,
			)
		);
	}

	/**
	 * Render the order metadata table inside the Dokan dashboard panel body.
	 *
	 * Hooked on `metadata_viewer_dokan_order_panel_body` from the panel template.
	 * Delegates to the wp-admin order viewer for HPOS-aware meta resolution.
	 *
	 * @param \WC_Order $order The order being viewed.
	 *
	 * @return void
	 */
	public function render_order_panel_body( $order ) {
		add_filter( 'metadata_viewer_order_search_icon_html', array( $this, 'dokan_search_icon_html' ) );
		welabs_metadata_viewer()->woo_order_meta_data->render_show_order_metadata( $order );
		remove_filter( 'metadata_viewer_order_search_icon_html', array( $this, 'dokan_search_icon_html' ) );
	}

	/**
	 * Output the product metadata viewer wrapped in a Dokan dashboard panel.
	 *
	 * The `dokan_product_content_inside_area_after` hook passes no args, so the
	 * product is resolved from the request the same way Dokan core does.
	 *
	 * @return void
	 */
	public function render_product_metadata() {
		if ( ! isset( $_GET['product_id'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only dashboard view, mirrors Dokan core.
			return;
		}

		$product_id = absint( wp_unslash( $_GET['product_id'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( ! $product_id || 'product' !== get_post_type( $product_id ) ) {
			return;
		}

		$this->enqueue_assets();

		welabs_metadata_viewer()->get_template(
			'dokan-product-metadata-viewer-panel.php',
			array(
				'viewer'     => $this,
				'product_id' => $product_id,
			)
		);
	}

	/**
	 * Render the product metadata table inside the Dokan dashboard panel body.
	 *
	 * Hooked on `metadata_viewer_dokan_product_panel_body` from the panel template.
	 * Always reads from `get_metadata( 'post', ... )` — WooCommerce hoists internal
	 * keys into object props, so `$product->get_meta_data()` would be incomplete.
	 *
	 * @param int $product_id The product ID.
	 *
	 * @return void
	 */
	public function render_product_panel_body( $product_id ) {
		add_filter( 'metadata_viewer_search_icon_html', array( $this, 'dokan_search_icon_html' ) );
		Helpers::get_metadata_table_view( get_metadata( 'post', (int) $product_id ) );
		remove_filter( 'metadata_viewer_search_icon_html', array( $this, 'dokan_search_icon_html' ) );
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
	 * are only auto-enqueued in wp-admin, so enqueue them here when a Dokan
	 * dashboard template renders. Styles/scripts requested this late are
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
