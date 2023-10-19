<?php
/**
 * Plugin Name: Metadata Viewer
 * Plugin URI:  https://wordpress.org/plugins/metadata-viewer
 * Description: Show post, user metadata (aka custom fields) in a metabox when editing posts / pages - a great tool for debugging issues with post or user metadata.
 * Version: 0.0.1
 * Author: aiarnob
 * Author URI: https://wordpress.org/plugins/metadata-viewer
 * Text Domain: metadata-viewer
 * WC requires at least: 5.0.0
 * Domain Path: /languages/
 * License: GPL2
 */
use WeLabs\MetadataViewer\MetadataViewer;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'METADATA_VIEWER_FILE' ) ) {
    define( 'METADATA_VIEWER_FILE', __FILE__ );
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Load Metadata_Viewer Plugin when all plugins loaded
 *
 * @return \WeLabs\MetadataViewer\MetadataViewer;
 */
function welabs_metadata_viewer() {
    return MetadataViewer::init();
}

// Lets Go....
welabs_metadata_viewer();
