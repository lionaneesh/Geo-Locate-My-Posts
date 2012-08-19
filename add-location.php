<?php
/**
 * @package 
 * @version 0.2
 */
/*
Plugin Name: Geolocate My Posts
Description: A Wordpress plugin that tags the location of your posts using the Google Maps API.
Author: Aneesh Dogra
Version: 0.2
Author URI: anee.me
*/

if (!get_option('geolocate_my_posts_options'))
	add_option('geolocate_my_posts_options', array("gmaps_image_width" => "400", "gmaps_image_height" => "200"));

function get_lat_lon($hook) {
	if ( 'post-new.php' != $hook and 'post.php' != $hook ) { return; }
	
	wp_enqueue_script( 'geolocation', plugins_url( '/js/geolocate.js', __FILE__ ), array('jquery') );
}

function save_lat_lon() {
	set_transient( 'latitude',  $_POST['lat'], 10); // expire in 10 secs
	set_transient( 'longitude', $_POST['lon'], 10);
}

function addLocation( $data ) {
	$lat = get_transient( 'latitude',  '' );
	$lon = get_transient( 'longitude', '' );
	if ( $data['post_status'] != 'publish' or // only append data when publishing the content. It avoids duplicates
		 $lat == '' or $lon == '' ) {
		return $data;
	}
	$options_array 			= get_option('geolocate_my_posts_options');
	$gmaps_out 				= '';
	$out 					= '';
	$gmap_get_location_url  = "https://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$lon&sensor=false";
	$gmap_image_url 		= "http://maps.googleapis.com/maps/api/staticmap?center=$lat,$lon&zoom=11&size=".$options_array['gmaps_image_width']."x".$options_array['gmaps_image_height']."&sensor=false&markers=color:blue%7C$lat,$lon";
	$gmap_location_url 		= "https://maps.google.com/maps?q=$lat,$lon&num=1&t=h&z=12&iwloc=A";
	$fp 	   				= fopen($gmap_get_location_url, "r");

	delete_transient('latitude');
	delete_transient('longitude');

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
	<img src="$gmap_image_url"><br /><br />
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

function gmp_admin_init() {
	register_setting( 'plugin_options', 'geolocate_my_posts_options', 'geolocate_my_posts_options_validate' );
	add_settings_section('geolocate_my_post_settings', 'Geolocation Settings', 'geolocation_section_text', 'geolocate-my-posts');
	add_settings_field('gmaps_image_width', 'Width of Map Tile (in px)', 'gmaps_image_width_string', 'geolocate-my-posts', 'geolocate_my_post_settings');
	add_settings_field('gmaps_image_height', 'Height of Map Tile (in px)', 'gmaps_image_height_string', 'geolocate-my-posts', 'geolocate_my_post_settings');
}

function gmaps_image_width_string() {
$options = get_option('geolocate_my_posts_options');
echo "<input id='plugin_text_string' name='geolocate_my_posts_options[gmaps_image_width]' size='40' type='text' value='{$options['gmaps_image_width']}' />";
}

function gmaps_image_height_string() {
$options = get_option('geolocate_my_posts_options');
echo "<input id='plugin_text_string' name='geolocate_my_posts_options[gmaps_image_height]' size='40' type='text' value='{$options['gmaps_image_height']}' />";
}

function geolocate_my_posts_options_validate($input) {
	$newinput['gmaps_image_width']  = trim($input['gmaps_image_width']);
	$newinput['gmaps_image_height'] = trim($input['gmaps_image_height']);

	if(!preg_match('/\d+/', $newinput['gmaps_image_width'])) {
		$newinput['gmaps_image_width'] = '50';
	}
	if(!preg_match('/\d+/', $newinput['gmaps_image_height'])) {
		$newinput['gmaps_image_height'] = '50';
	}

	return $newinput;
}

function geolocation_section_text() {
	echo '<p>Settings for the Map tile.</p>';
}
function gmp_plugin_menu() { // gmp == Geolocate My Posts
add_options_page('Post Geolocation', 'Post Geolocation Settings', 'manage_options', 'geolocatePosts', 'gmp_plugin_options');
}

function gmp_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
?>
<div>
<h2>Geolocate My Posts</h2>
Options related to Geolocate my posts plugin.
<form action="options.php" method="post">
<?php settings_fields('plugin_options'); ?>
<?php do_settings_sections('geolocate-my-posts'); ?>

<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
</form></div>

<?php
}

add_action( 'admin_enqueue_scripts',  'get_lat_lon'     );
add_action( 'wp_ajax_save_lat_lon' ,  'save_lat_lon'    );
add_filter( 'wp_insert_post_data'  ,  'addLocation'     );
add_action( 'admin_menu'		   ,  'gmp_plugin_menu' );
add_action( 'admin_init'           ,  'gmp_admin_init'  );
?>