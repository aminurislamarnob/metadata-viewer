<?php
/**
 * The template for displaying metadata on posts, pages, and custom post types, user admin pages
 */

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="metadata-viewer-wrapper">
    <div class="metadata-filter">
        <div class="metadata-search-group">
            <div class="icon"><span class="dashicons dashicons-search"></span></div>
            <input type="text" class="regular-text" id="meta_key_filter" placeholder="Search by meta key or value...">
        </div>  
    </div>
    <table class="fixed table-view-list metadata-viewer-table">
        <thead>
            <tr>
                <th><?php echo esc_html__( 'Meta Key', 'metadata-viewer' ); ?></th>
                <th><?php echo esc_html__( 'Meta Value ($single = false)', 'metadata-viewer' ); ?></th>
                <th><?php echo esc_html__( 'Meta Value ($single = true)', 'metadata-viewer' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ( $post_meta as $key => $value ) {
                $unserialized_metadata = WeLabs\MetadataViewer\Helpers::unserialize_metadata_recursive( $value );
                ?>
            <tr>
                <td><pre><?php echo esc_html( $key ); ?></pre></td>
                <td><pre><code class="language-php"><?php echo esc_html( var_export( $unserialized_metadata, true ) ); ?></code></pre></td>
                <td><pre><code class="language-php"><?php echo esc_html( var_export( $unserialized_metadata[0], true ) ); ?></code></pre></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <div class="not-metadata-found hidden">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-binoculars" viewBox="0 0 16 16">
            <path d="M3 2.5A1.5 1.5 0 0 1 4.5 1h1A1.5 1.5 0 0 1 7 2.5V5h2V2.5A1.5 1.5 0 0 1 10.5 1h1A1.5 1.5 0 0 1 13 2.5v2.382a.5.5 0 0 0 .276.447l.895.447A1.5 1.5 0 0 1 15 7.118V14.5a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 14.5v-3a.5.5 0 0 1 .146-.354l.854-.853V9.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v.793l.854.853A.5.5 0 0 1 7 11.5v3A1.5 1.5 0 0 1 5.5 16h-3A1.5 1.5 0 0 1 1 14.5V7.118a1.5 1.5 0 0 1 .83-1.342l.894-.447A.5.5 0 0 0 3 4.882V2.5zM4.5 2a.5.5 0 0 0-.5.5V3h2v-.5a.5.5 0 0 0-.5-.5h-1zM6 4H4v.882a1.5 1.5 0 0 1-.83 1.342l-.894.447A.5.5 0 0 0 2 7.118V13h4v-1.293l-.854-.853A.5.5 0 0 1 5 10.5v-1A1.5 1.5 0 0 1 6.5 8h3A1.5 1.5 0 0 1 11 9.5v1a.5.5 0 0 1-.146.354l-.854.853V13h4V7.118a.5.5 0 0 0-.276-.447l-.895-.447A1.5 1.5 0 0 1 12 4.882V4h-2v1.5a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V4zm4-1h2v-.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5V3zm4 11h-4v.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5V14zm-8 0H2v.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5V14z"/>
        </svg>
        <h2>Sorry! No result found.</h2>
        <p>No search result found for <strong class="search-meta-text"></strong>. Please try another search.</p>
    </div>
</div>
