<?php

namespace WeLabs\MetadataViewer;

// don't call the file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * OrderMetaData class
 *
 * @class OrderMetaData The class that holds all the order meta related settings
 */
class OrderMetaData {
	/**
	 * The constructor.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'adding_metadata_viewer_meta_box' ), 999, 2 );
	}

	/**
	 * Add meta box on wp admin post edit screen
	 *
	 * @param string $post_type Screen / post type.
	 * @param object $post      Post or order object (unused; required by hook signature).
	 * @return void
	 */
	// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed -- WordPress passes $post; only $post_type is needed for add_meta_box screen.
	public function adding_metadata_viewer_meta_box( $post_type, $post ) {
		if ( 'woocommerce_page_wc-orders' !== $post_type ) {
			return;
		}
		add_meta_box(
			'post-metadata-viewer',
			__( 'Order Metadata Viewer', 'metadata-viewer' ),
			array( $this, 'render_show_order_metadata' ),
			$post_type,
			'normal',
			'default'
		);
	}

	/**
	 * Generate metadata viewer table
	 *
	 * @param \WC_Abstract_Order $order_object Order or refund object.
	 * @return void
	 */
	public function render_show_order_metadata( $order_object ) {
		if ( ! is_object( $order_object ) || ! method_exists( $order_object, 'get_id' ) || empty( $order_object->get_id() ) ) {
			return;
		}

		$post_meta = $this->get_order_meta_for_viewer( $order_object );
		require_once METADATA_VIEWER_TEMPLATE_DIR . '/order-metadata-viewer-table.php';
	}

	/**
	 * Resolve meta source from HPOS + sync settings (see WooCommerce options with the same names).
	 *
	 * @param \WC_Abstract_Order $order_object
	 * @return array<string, array<int, mixed>>
	 */
	private function get_order_meta_for_viewer( $order_object ) {
		$order_id = (int) $order_object->get_id();

		$hpos_enabled = $this->is_hpos_enabled();
		$sync_enabled = $this->is_hpos_data_sync_enabled();

		if ( ! $hpos_enabled || ( $hpos_enabled && $sync_enabled ) ) {
			return get_metadata( 'post', $order_id );
		}

		// HPOS on, sync off: authoritative store is COT; postmeta may be empty or placeholder — use CRUD meta.
		return $this->get_meta_grouped_from_order_object( $order_object );
	}

	/**
	 * @return bool
	 */
	private function is_hpos_enabled() {
		return 'yes' === get_option( 'woocommerce_custom_orders_table_enabled' );
	}

	/**
	 * @return bool
	 */
	private function is_hpos_data_sync_enabled() {
		return 'yes' === get_option( 'woocommerce_custom_orders_table_data_sync_enabled' );
	}

	/**
	 * Same shape as get_metadata(): meta key => list of values (for the order template + unserialize helper).
	 *
	 * @param \WC_Abstract_Order $order_object
	 * @return array<string, array<int, mixed>>
	 */
	private function get_meta_grouped_from_order_object( $order_object ) {
		$post_meta = array();

		foreach ( $order_object->get_meta_data() as $meta ) {
			$key = $meta->key;
			if ( ! array_key_exists( $key, $post_meta ) ) {
				$post_meta[ $key ] = $order_object->get_meta( $key, false );
			}
		}

		return $post_meta;
	}
}
