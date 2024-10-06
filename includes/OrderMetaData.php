<?php

namespace WeLabs\MetadataViewer;

// don't call the file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * OrderMetaData class
 *
 * @class OrderMetaData The class that holds the entire post meta related settings
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
	 * @param string $post_type
	 * @param object $post
	 * @return void
	 */
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
	 * @param object $order_object
	 * @return void
	 */
	public function render_show_order_metadata( $order_object ) {
		if ( empty( $order_object->get_id() ) ) {
			return;
		}
		$post_meta  = array();
		$order_meta = $order_object->get_meta_data();

		// Store the key-value pairs in the array.
		foreach ( $order_meta as $meta ) {
			$post_meta[ $meta->key ] = $order_object->get_meta( $meta->key, $single = false );
		}

		require_once METADATA_VIEWER_TEMPLATE_DIR . '/order-metadata-viewer-table.php';
	}
}
