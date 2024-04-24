<?php

namespace WeLabs\MetadataViewer;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Helpers class
 *
 * @class Helpers The class that holds common functions
 */
class Helpers {
    /**
     * The constructor.
     */
    public function __construct() {
    }

    /**
     * Include template of metadata table
     *
     * @param object $post_meta
     * @return void
     */
    public static function get_metadata_table_view( $post_meta ) {
        require_once METADATA_VIEWER_TEMPLATE_DIR . '/metadata-viewer-table.php';
    }

    /**
     * Recursive function to unserialize metadata array
     *
     * @param array $meta_value
     * @return void
     */
    public static function unserialize_metadata_recursive( array $meta_value ) {
        foreach ( $meta_value as $key => $value ) {
            $meta_value[ $key ] = is_array( $value ) ? self::unserialize_metadata_recursive( $value ) : maybe_unserialize( $value );
        }
        return $meta_value;
    }
}
