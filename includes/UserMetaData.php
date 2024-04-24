<?php

namespace WeLabs\MetadataViewer;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * UserMetaData class
 *
 * @class UserMetaData The class that holds the entire user meta related settings
 */
class UserMetaData {
    /**
     * The constructor.
     */
    public function __construct() {
        add_action( 'edit_user_profile', array( $this, 'adding_metadata_viewer_meta_box_on_profile' ), 999 );
		add_action( 'show_user_profile', array( $this, 'adding_metadata_viewer_meta_box_on_profile' ), 999 );
    }

    /**
     * Add meta box on wp admin post edit screen
     *
     * @param \WP_User $user
     * @return void
     */
    public function adding_metadata_viewer_meta_box_on_profile( $user ) {
        add_meta_box(
            'user-metadata-viewer-id',
            __( 'User Metadata Viewer', 'metadata-viewer' ),
            [ $this, 'render_show_user_metadata' ],
            'user-metadata-viewer',
            'normal',
            'default'
        );

        do_meta_boxes( 'user-metadata-viewer', 'normal', $user );
    }

    public function render_show_user_metadata( $user_object ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( ! isset( $user_object->ID ) ) {
            return;
        }

        $user_meta = get_metadata( 'user', $user_object->ID );
        return Helpers::get_metadata_table_view( $user_meta );
    }
}
