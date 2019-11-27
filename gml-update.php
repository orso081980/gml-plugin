<?php

function gml_update() {

    global $wpdb;

    $table_name = $wpdb->prefix . "locations";
    $id = $_GET["id"];

    $name = isset($_POST["name"]) ? $name = $_POST["name"] : '';
    $lat = isset($_POST["lat"]) ? $lat = $_POST["lat"] : '';
    $lng = isset($_POST["lng"]) ? $lng = $_POST["lng"] : '';
    $html = isset($_POST["html"]) ? $html = $_POST["html"] : '';

    if (isset($_POST['update'])) {
        $wpdb->update(
            $table_name,
            array(
                'name' => $name,
                'lat'  => $lat,
                'lng'  => $lng,
                'html' => $html),
            array('id' => $id)
        );
    }

    else if (isset($_POST['delete'])) {
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %d", $id));
    } else {	
        $locations = $wpdb->get_results($wpdb->prepare("SELECT id,name,lat,lng,html from $table_name where id=%d", $id));
        foreach ($locations as $s) {
            $name = $s->name;
            $lat = $s->lat;
            $lng = $s->lng;
            $html = $s->html;
        }
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/locations/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Locations</h2>

        <?php if (isset($_POST['delete'])) { ?>
            <div class="updated"><p>Locations deleted</p></div>
            <a href="<?php echo admin_url('admin.php?page=gml_lists') ?>">&laquo; Back to locations list</a>

        <?php } else if (isset($_POST['update'])) { ?>
            <div class="updated"><p>Locations updated</p></div>
            <a href="<?php echo admin_url('admin.php?page=gml_lists') ?>">&laquo; Back to locations list</a>

        <?php } else { ?>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
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
                <input type='submit' name="update" value='Save' class='button'> &nbsp;&nbsp;
                <input type='submit' name="delete" value='Delete' class='button' onclick="return confirm('Do you want to delete this element?')">
            </form>
        <?php } ?>

    </div>
    <?php
}