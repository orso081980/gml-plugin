<?php

function gml_show() {

	global $wpdb;
	$table_name = $wpdb->prefix . "locations";
	$gml_result = $wpdb->get_results("SELECT id,name,lat,lng,html from $table_name");
	echo json_encode($gml_result);
	wp_die();
	
}
