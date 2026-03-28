<?php

namespace WeLabs\MetadataViewer;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CommentMetaData class
 *
 * @class CommentMetaData The class that holds comment metadata related settings
 */
class CommentMetaData {
	/**
	 * The constructor.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes_comment', array( $this, 'adding_metadata_viewer_meta_box' ) );
	}

	/**
	 * Add metadata viewer metabox on comment edit screen.
	 *
	 * @param \WP_Comment $comment Comment object.
	 * @return void
	 */
	public function adding_metadata_viewer_meta_box( $comment ) {
		unset( $comment );

		add_meta_box(
			'comment-metadata-viewer-id',
			__( 'Comment Metadata Viewer', 'metadata-viewer' ),
			array( $this, 'render_show_comment_metadata' ),
			'comment',
			'normal',
			'default'
		);
	}

	/**
	 * Render comment metadata table.
	 *
	 * @param \WP_Comment $comment_object Comment object.
	 * @return void
	 */
	public function render_show_comment_metadata( $comment_object ) {
		if ( ! isset( $comment_object->comment_ID ) ) {
			return;
		}

		$comment_meta = get_metadata( 'comment', (int) $comment_object->comment_ID );
		Helpers::get_metadata_table_view( $comment_meta );
	}
}
