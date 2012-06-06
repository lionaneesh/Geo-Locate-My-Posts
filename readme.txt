=== Geolocate My Posts ===
Contributors: Aneesh Dogra
Tags: geo-locate, location, gmaps, Google Maps, add location to posts, locate my posts, geo-locate my posts
Requires at least: 3.0
Stable tag: 0.1

A Wordpress plugin that tags the location of the post using Google Maps.

== Description ==

A Wordpress plugin that adds location to your posts, using Geolocation and the Google Maps API.

== Other Notes ==

= To Do =
[*] Improve CSS

== Installation ==

You may either install the plugin via the in-built installer in WordPress or follow the manual method below:

1. Upload the extracted `Geolocate-My-Posts` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enjoy!

== Frequently Asked Questions ==

= 1. How do I change the Map's Image Width ?
Open up add_location.php in a Text editor like Vim, Gedit, TextMate etc, and Change

if ( !defined( 'GMAPS_IMAGE_WIDTH'  ) ) define( 'GMAPS_IMAGE_WIDTH',  '400' );
to
if ( !defined( 'GMAPS_IMAGE_WIDTH'  ) ) define( 'GMAPS_IMAGE_WIDTH',  'YOUR_WIDTH_HERE' );

Where YOUR_WIDTH_HERE is your desired width

and Similarly, Change

if ( !defined( 'GMAPS_IMAGE_HEIGHT' ) ) define( 'GMAPS_IMAGE_HEIGHT', '200' );
to
if ( !defined( 'GMAPS_IMAGE_HEIGHT' ) ) define( 'GMAPS_IMAGE_HEIGHT', 'YOUR_HEIGHT_HERE' );

Where YOUR_HEIGHT_HERE is your desired Height.

= 2. If I disable the plugin, will all the Locations from my Posts be removed? =
No, The locations are appended in your posts as you click publish, thus, You have to devise a way to manually delete the locations if you wish to do so.

== Screenshots ==

Plugin in Action : https://docs.google.com/open?id=0B5gdkF1UMZiKaHRENTg0Y0ZjSWs

== Change Log ==

= 0.1 =
Initial Release