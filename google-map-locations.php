<?php

/**
* The plugin bootstrap file
*
* This file is read by WordPress to generate the plugin information in the plugin
* admin area. This file also includes all of the dependencies used by the plugin,
* registers the activation and deactivation functions, and defines a function
* that starts the plugin.
*
* @link              http://example.com
* @since             1.0.0
* @package           Plugin_Name
*
* @wordpress-plugin
* Plugin Name:       Google Map Locations
* Description:       A plugin to mess up with google maps
* Version:           1.0.0
* Author:            Marco Maffei
* License:           GPL-2.0+
* License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/

include 'style.php';

function gml_render($atts , $content) {

    wp_enqueue_script('googlemaps');
    wp_enqueue_script('jquery');
    wp_enqueue_script('parsley');
    wp_enqueue_script('my-ajax-script');
    wp_enqueue_style( 'style1' );
    $atts = shortcode_atts( array(
        'data'=>'0'
    ) , $atts);
    $content =  (empty($content))? " " : $content;
    extract($atts);
    ob_start();
    include( dirname(__FILE__) . '/gml.php' );

    return ob_get_clean();
}
add_shortcode('gml_map_frontend' , 'gml_render' );

add_action( 'wp_ajax_gml_show', 'gml_show' );
add_action( 'wp_ajax_nopriv_gml_show', 'gml_show' );

function gml_scripts() {
    $plugin_url = plugin_dir_url( __FILE__ );
    wp_register_script( 'googlemaps', 'https://maps.googleapis.com/maps/api/js?key=key', '', '', true );
    
    wp_deregister_script('jquery');
    wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js', '', '', true );

    wp_register_script('parsley', 'https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.1/parsley.min.js', '', '',true );
    
    // wp_enqueue_style( 'bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' );
    wp_register_script( 'my-ajax-script', WP_PLUGIN_URL . '/google-map-locations/js/ajax.js', '', '',true );
    wp_register_style( 'style1', $plugin_url . 'css/gml-style.css' ); 
    wp_enqueue_style( 'style1', $plugin_url . 'css/gml-style.css' );
    wp_localize_script( 'my-ajax-script', 'my_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}
add_action( 'wp_enqueue_scripts', 'gml_scripts' );

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

//Cookies and security
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function gml_options_install() {

    global $wpdb;
    $table_name = $wpdb->prefix . "locations";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
    `id` INT(10) NOT NULL AUTO_INCREMENT,
    `name` varchar(50) CHARACTER SET utf8 NOT NULL,
    `lat` FLOAT(20, 10) NOT NULL,
    `lng` FLOAT(20, 10) NOT NULL,
    `html` longtext CHARACTER SET utf8 NOT NULL,
    PRIMARY KEY (`id`)
) $charset_collate; ";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta($sql);
}

register_activation_hook(__FILE__, 'gml_options_install');

function gml_options_remove_database() {
   global $wpdb;
   $table_name = $wpdb->prefix . "locations";
   $sql = "DROP TABLE IF EXISTS $table_name";
   $wpdb->query($sql);
   delete_option("gml_options_db_version");
}

register_deactivation_hook( __FILE__, 'gml_options_remove_database' );

//menu items
add_action('admin_menu','gml_modifymenu');
function gml_modifymenu() {

    //this is the main item for the menu
    add_menu_page('Locations', //page title
        'Locations', //menu title
        'manage_options', //capabilities
        'gml_lists', //menu slug
        'gml_lists' //function
    );
    
    //this is a submenu
    add_submenu_page('gml_lists', //parent slug
        'Add New Location', //page title
        'Add New', //menu title
        'manage_options', //capability
        'gml_create', //menu slug
        'gml_create'); //function
    
    //this submenu is HIDDEN, however, we need to add it anyways
    add_submenu_page(null, //parent slug
        'Update Location', //page title
        'Update', //menu title
        'manage_options', //capability
        'gml_update', //menu slug
        'gml_update'); //function
}

define('ROOTDIR', plugin_dir_path(__FILE__));
require_once(ROOTDIR . 'gml-lists.php');
require_once(ROOTDIR . 'gml-create.php');
require_once(ROOTDIR . 'gml-update.php');
require_once(ROOTDIR . 'gml-display.php');
