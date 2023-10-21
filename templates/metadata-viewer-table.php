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
