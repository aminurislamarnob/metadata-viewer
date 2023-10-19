<table class="wp-list-table widefat fixed striped table-view-list">
    <thead>
        <tr>
            <th><?php echo esc_html__( 'Key', 'metadata-viewer' ); ?></th>
            <th><?php echo esc_html__( 'Value', 'metadata-viewer' ); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ( $post_meta as $key => $value ) {
            $unserialized_metadata = WeLabs\MetadataViewer\Helpers::unserialize_metadata( $value );
			?>
        <tr>
            <td><pre><?php echo esc_html( $key ); ?></pre></td>
            <td><pre><?php echo esc_html( var_export( $unserialized_metadata, true ) ); ?></pre></td>
        </tr>
        <?php } ?>
    </tbody>
</table>