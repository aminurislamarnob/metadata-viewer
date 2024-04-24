<?php

namespace WeLabs\MetadataViewer;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Assets {
    /**
     * The constructor.
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_all_scripts' ], 10 );

        if ( is_admin() ) {
            add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ], 10 );
        }
    }

    /**
     * Register all Dokan scripts and styles.
     *
     * @return void
     */
    public function register_all_scripts() {
        $this->register_styles();
        $this->register_scripts();
    }

    /**
     * Register scripts.
     *
     * @param array $scripts
     *
     * @return void
     */
    public function register_scripts() {
        wp_register_script( 'metadata_viewer_admin_script', METADATA_VIEWER_PLUGIN_ASSET . '/admin/js/script.js', [ 'jquery' ], METADATA_VIEWER_PLUGIN_VERSION, true );
        wp_register_script( 'metadata_viewer_highlight_script', METADATA_VIEWER_PLUGIN_ASSET . '/admin/js/highlight.js', [], METADATA_VIEWER_PLUGIN_VERSION, true );
    }

    /**
     * Register styles.
     *
     * @return void
     */
    public function register_styles() {
        wp_register_style( 'metadata_viewer_admin_style', METADATA_VIEWER_PLUGIN_ASSET . '/admin/css/style.css', [], METADATA_VIEWER_PLUGIN_VERSION );
    }

    /**
     * Enqueue admin scripts.
     *
     * @return void
     */
    public function enqueue_admin_scripts() {
        //highlight css/js
        wp_enqueue_script( 'metadata_viewer_highlight_script' );

        //plugin css/js
        wp_enqueue_style( 'metadata_viewer_admin_style' );
        wp_enqueue_script( 'metadata_viewer_admin_script' );
        // wp_localize_script(
        //     'metadata_viewer_admin_script', 'Metadata_Viewer_Admin', []
        // );
    }
}
