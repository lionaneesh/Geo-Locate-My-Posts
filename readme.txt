=== Geolocate My Posts ===
Contributors: lionaneesh
Tags: geo-locate, location, gmaps, Google Maps, add location to posts, locate my posts, geo-locate my posts
Requires at least: 2.6
Tested up to: 3.4.1
Stable tag: 0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A Wordpress plugin that tags the location of your posts using the Google Maps API.

== Description ==

"Geolocate My Posts" adds location to your posts, including a nice map, using geolocation and the Google Maps API.

== Installation ==

You may either install the plugin via the in-built installer in WordPress or follow the manual method below:

1. Upload the extracted `geolocate-my-posts` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enjoy!

== Frequently Asked Questions ==

= How do I change the Map's Image Width ? =
Go to Admin Panel->Settings->Post Geolocation Settings, and do the needful.

= If I disable the plugin, will all the Locations from my Posts be removed? =
No, The locations are appended in your posts as you click publish, thus, You have to devise a way to manually delete the locations if you wish to do so.

== Screenshots ==

* Plugin in Action

== Changelog ==

= 0.2 =
Final Release, No further development expected.

* The geolocation javascript is only enqueued at pages where its necessary.
* Use transients instead of options to store latitude, longitude.
* Added customization.

= 0.1 =
Initial Release