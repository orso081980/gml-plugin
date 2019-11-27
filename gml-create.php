<?php

function gml_create() {

    $name = isset($_POST["name"]) ? $name = $_POST["name"] : '';
    $lat = isset($_POST["lat"]) ? $lat = $_POST["lat"] : '';
    $lng = isset($_POST["lng"]) ? $lng = $_POST["lng"] : '';
    $html = isset($_POST["html"]) ? $html = $_POST["html"] : '';

    if (isset($_POST['insert'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . "locations";

        $exists = $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE name = %s", $_POST['name']
        ) );

        if ( ! $exists ) {
            $wpdb->insert(
                $table_name,
                array(
                    'name' => $name,
                    'lat'  => $lat,
                    'lng'  => $lng,
                    'html' => $html),
                array('%s', '%f', '%f', '%s')
            );
            $message = "Location inserted";
        }
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/gml-map-locations/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Add New Location</h2>
        <?php 
        if (isset($message)): 
            ?>
            <div class="updated"><p><?php echo $message; ?></p></div>
            <a href="<?php echo admin_url('admin.php?page=gml_lists') ?>">&laquo; Back to locations list</a>
            <?php 
        elseif (isset($exists)) :
            ?>
            <div class="updated"><p>The record already exists</p></div>
            <a href="<?php echo admin_url('admin.php?page=gml_lists') ?>">&laquo; Back to locations list</a>
        <?php 
        else: 
            ?>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <p>Three capital letters for the ID</p>
                <table class='wp-list-table widefat fixed'>
                    <tr>
                        <th class="ss-th-width">Name</th>
                        <td><input type="text" name="name" value="<?php echo $name; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <th class="ss-th-width">Latitude</th>
                        <td><input type="text" name="lat" value="<?php echo $lat; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <th class="ss-th-width">Longitude</th>
                        <td><input type="text" name="lng" value="<?php echo $lng; ?>" class="ss-field-width" /></td>
                    </tr>
                    <tr>
                        <th class="ss-th-width">Html</th>
                        <td><input type="text" name="html" value="<?php echo $html; ?>" class="ss-field-width" /></td>
                    </tr>
                </table>
                <input type='submit' name="insert" value='Save' class='button'>
            </form>
        </div>
        <?php 
    endif; 
    ?>
    <?php
}