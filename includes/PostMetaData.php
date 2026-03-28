<?php

namespace WeLabs\MetadataViewer;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PostMetaData class
 *
 * @class PostMetaData The class that holds the entire post meta related settings
 */
class PostMetaData {
	/**
	 * The constructor.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'adding_metadata_viewer_meta_box' ), 999, 2 );
	}

	/**
	 * Add meta box on wp admin post edit screen
	 *
	 * @param string   $post_type Post type string.
	 * @param \WP_Post $post      Post object (required by `add_meta_boxes` signature; unused here).
	 * @return void
	 */
	// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed -- WordPress passes $post; only $post_type is needed for add_meta_box screen.
	public function adding_metadata_viewer_meta_box( $post_type, $post ) {

		if ( Helpers::is_comment_edit_screen() ) {
			return;
		}
		
		add_meta_box(
			'post-metadata-viewer',
			__( 'Post Metadata Viewer', 'metadata-viewer' ),
			array( $this, 'render_show_post_metadata' ),
			$post_type,
			'normal',
			'default'
		);
	}

	/**
	 * Generate metadata viewer table
	 *
	 * @param \WP_Post $post_object
	 * @return void
	 */
	public function render_show_post_metadata( $post_object ) {
		if ( empty( $post_object->ID ) ) {
			return;
		}

		$post_meta = get_metadata( 'post', (int) $post_object->ID );

		return Helpers::get_metadata_table_view( $post_meta );
	}
}
