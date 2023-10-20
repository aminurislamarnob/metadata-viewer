<?php

namespace WeLabs\MetadataViewer;

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
        add_action( 'edit_user_profile', array( $this, 'adding_metadata_viewer_meta_box' ), 999 );
		add_action( 'show_user_profile', array( $this, 'adding_metadata_viewer_meta_box' ), 999 );
    }

    /**
     * Add meta box on wp admin post edit screen
     *
     * @param \WP_User $user
     * @return void
     */
    public function adding_metadata_viewer_meta_box( $user ) {
        $screen = get_current_screen();
        add_meta_box(
            'post-metadata-viewers',
            __( 'Post Metadata Viewers', 'metadata-viewer' ),
            [ $this, 'test' ],
            'post-metadata-viewers',
            'normal',
            'default'
        );

        do_meta_boxes( 'post-metadata-viewers', 'normal', $user );

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( ! isset( $user->ID ) ) {
            return;
        }

        $user_meta = get_metadata( 'user', $user->ID );
        return Helpers::get_metadata_table_view( $user_meta );
    }

    public function test() {
    }
}
