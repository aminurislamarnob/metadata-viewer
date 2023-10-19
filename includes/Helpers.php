<?php

namespace WeLabs\MetadataViewer;

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
        require_once METADATA_VIEWER_TEMPLATE_DIR . '/metadata-viwer-table.php';
    }

    /**
     * Unserialize Metadata array
     *
     * @param array $meta_value
     * @return void
     */
    public static function unserialize_metadata( array $meta_value ) {
        return self::recursive_metadata_array( $meta_value );
    }

    /**
     * Recursive function to unserialize metadata array
     *
     * @param array $meta_value
     * @return void
     */
    public static function recursive_metadata_array( array $meta_value ) {
        foreach ( $meta_value as $key => $value ) {
            $meta_value[ $key ] = is_array( $value ) ? self::recursive_metadata_array( $value ) : maybe_unserialize( $value );
        }
        return $meta_value;
    }
}
