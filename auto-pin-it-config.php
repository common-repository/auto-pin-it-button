<?php

define('WP_AUTO_PIN_IT_BUTTON', 2);

global $wpapib_site;
$wpapib_site = get_option('siteurl');

global $wpapib_apikey;
$wpapib_apikey = get_option('wpsb_apikey');
if (! $wpapib_apikey) {
	$wpapib_apikey = md5($wpapib_site);
} 

global $wpapib_options;
$wpapib_options = get_option('wpapib_options');

global $pin_to_pinterest_in_background_url;
$pin_to_pinterest_in_background_url = plugins_url('auto-pin-to-pinterest-in-background.php', __FILE__);

//date_default_timezone_set('PRC');

?>