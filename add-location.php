<?php
/**
 * @package 
 * @version 0.1
 */
/*
Plugin Name: Geo-Locate My Posts
Description: A Wordpress plugin that tags the location of the post using Google Maps.
Author: Aneesh Dogra
Version: 0.1
Author URI: https://plus.google.com/109031586756412438081
*/

if ( !defined( 'GMAPS_IMAGE_WIDTH'  ) ) define( 'GMAPS_IMAGE_WIDTH',  '400' );
if ( !defined( 'GMAPS_IMAGE_HEIGHT' ) ) define( 'GMAPS_IMAGE_HEIGHT', '200' );

function get_lat_lon() {
	wp_enqueue_script( 'geolocation', plugins_url( '/js/geolocate.js', __FILE__ ), array('jquery') );
}

function save_lat_lon() {
	add_option( 'latitude',  $_POST['lat'] );
	add_option( 'longitude', $_POST['lon'] );
}

function addLocation( $data ) {
	$lat = get_option( 'latitude',  '' );
	$lon = get_option( 'longitude', '' );
	if ( $data['post_status'] != 'publish' or
		 $lat == '' or $lon == '' ) {
		return $data;
	}
	$gmaps_out 				= '';
	$out 					= '';
	$gmap_get_location_url  = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lon&sensor=false";
	$gmap_image_url 		= "http://maps.googleapis.com/maps/api/staticmap?center=$lat,$lon&zoom=11&size=".GMAPS_IMAGE_WIDTH."x".GMAPS_IMAGE_HEIGHT."&sensor=false&markers=color:blue%7C$lat,$lon";
	$gmap_location_url 		= "https://maps.google.com/maps?q=$lat,$lon&num=1&t=h&z=12&iwloc=A";

	$fp 	   = fopen($gmap_get_location_url, "r");

	while ( !feof($fp) ) {
		$gmaps_out .= fread($fp, 512);
	}
	fclose($fp);
	$geo_data = json_decode($gmaps_out, True);
	if ( $geo_data['status'] == 'OK' and
     	 get_post_meta(get_the_ID(), '_location_added', true) == '' ) {
			$location = $geo_data['results'][0]['formatted_address'];
			$data['post_content'] .= <<<EOT
<!-- location -->
<div style="padding: 10px; margin-top: 20px;">
	<img src="$gmap_image_url"><br />
	<p style="padding = 10px;">
		At <a href="$gmap_location_url" title="Location">$location</a>
	</p>
</div>
<!-- location_end -->
EOT;
			add_post_meta(get_the_ID(), '_location_added', 'Yes');
	}
	return $data;
}
add_action( 'admin_enqueue_scripts',  'get_lat_lon'  );
add_action( 'wp_ajax_my_action'    ,  'save_lat_lon' );
add_filter( 'wp_insert_post_data'  ,  'addLocation'  );
?>