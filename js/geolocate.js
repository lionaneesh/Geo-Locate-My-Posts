function onPositionUpdate(position)
{
	jQuery(document).ready(function($) {

	var data = { action: 'my_action',
				 lat: position.coords.latitude,
				 lon: position.coords.longitude };

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl, data)
	});
}


if(navigator.geolocation)
	navigator.geolocation.getCurrentPosition(onPositionUpdate);