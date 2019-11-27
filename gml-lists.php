<?php

function gml_lists() {
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/gml-map-locations/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Location on the Map</h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <a href="<?php echo admin_url('admin.php?page=gml_create'); ?>">Add New</a>
            </div>
            <br class="clear">
        </div>
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . "locations";

        $rows = $wpdb->get_results("SELECT id,name,lat,lng,html from $table_name");
        ?>
        <table class='wp-list-table widefat fixed striped posts'>
            <tr>
                <th class="manage-column ss-list-width">ID</th>
                <th class="manage-column ss-list-width">Name</th>
                <th class="manage-column ss-list-width">Lat</th>
                <th class="manage-column ss-list-width">Lng</th>
                <th class="manage-column ss-list-width">Html</th>
                <th>&nbsp;</th>
            </tr>
            <?php foreach ($rows as $row) { ?>
                <tr>
                    <td class="manage-column ss-list-width"><?php echo $row->id; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->name; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->lat; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->lng; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->html; ?></td>
                    <td><a href="<?php echo admin_url('admin.php?page=gml_update&id=' . $row->id); ?>">Update</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php
}