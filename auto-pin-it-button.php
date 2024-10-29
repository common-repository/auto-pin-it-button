<?php
/**
 * Plugin Name: Auto Pin It Button
 * Plugin URI:  http://wpextends.sinaapp.com/plugins/auto-pin-it-button.html
 * Description: Auto Pin It Button adds a "Pin It" button to your blog posts automatically, so the readers can pin it to Pinterest quickly. 
 * Author:      WPExtends Team
 * Version:     1.9.4
 * Author URI:  http://wpextends.sinaapp.com
 */

include_once 'auto-pin-it-config.php';
include_once 'auto-pin-it-functions.php';

function auto_pin_it_button_the_content($content) {
	$content = auto_pin_it_button_the_images($content);
	
	global $wpapib_options;
	
	if (is_home() && $wpapib_options['no_pin_button_on_index']) {
		return $content;
	}
	
	if (is_category() && $wpapib_options['no_pin_button_on_category']) {
		return $content;
	}
	
	//if (is_archive() && $wpapib_options['no_pin_button_on_archive']) {
	if ((is_tag() || is_author() || is_date() || is_search()) && $wpapib_options['no_pin_button_on_archive']) {
		return $content;
	}
        	
	$pin_button_above = $wpapib_options['post_pin_button_above'];
	$pin_button_below = $wpapib_options['post_pin_button_below'];
	$pin_count = $wpapib_options['post_pin_count'];
	if ($pin_button_above || $pin_button_below) {
		$post_url = get_permalink(); 
		$description = get_the_title();
		$image_url = auto_pin_it_button_featured_image();
		$pinit_link = auto_pin_it_button_generate($image_url, $post_url, $description, $pin_count);
		if ($pin_button_above) {
			$content = $pinit_link . $content;
		}
		if ($pin_button_below) {
			$content = $content . $pinit_link;
		}
	}
	
	return $content;
}

function auto_pin_it_button_the_thumbnail($content) {
	return auto_pin_it_button_the_images($content);
}

function auto_pin_it_button_the_images($content) {
	global $wpapib_options;
	
	if ($wpapib_options['show_pin_button_on_image'] != 'yes') {
		if (strpos($content, 'autopinbtn') === false) {
			return $content;
		}
	}
	return preg_replace_callback('/<img(.*?)src=[\'"](.*?)[\'"](.*?)>/i', 'auto_pin_it_button_the_images_callback', $content);
}

function auto_pin_it_button_the_images_callback($matches) {
	$image = $matches[0];
	$image_url = $matches[2];

	global $wpapib_options;
	if ($wpapib_options['show_pin_button_on_image'] == 'by_class') {
		if (strpos($image, 'autopinbtn') === false) {
			return $image;
		}
	}
		
	$site_url = get_option('siteurl');
	if (strpos($image_url, $site_url) === false) {
		if (strpos($image_url, 'http://') === false) {
			$image_url = $site_url . $image_url;
		}
	}

//	if (! auto_pin_it_button_image_available($image_url)) {
//		return $image;
//	}
	
	$post_url = get_permalink(); 
	$description = get_the_title(); 
	
	$pin_count = $wpapib_options['image_pin_count'];
	$pinit_link = auto_pin_it_button_generate($image_url, $post_url, $description, $pin_count);
	
	return '<div class="auto-pin-it">' . $image . '<div class="auto-pin-it-button">' . $pinit_link . '</div></div>';
}

function auto_pin_it_button_generate($image = 'any', $post_url = null, $description = null, $pin_count = 'none') {
	//$pinit_img = 'http://assets.pinterest.com/images/pidgets/pin_it_button.png';
	$pinit_img = 'http://passets-cdn.pinterest.com/images/pinit_preview_none.png';	
	$pinit_url = 'http://pinterest.com/pin/create/button/'; 
	$pinit_url .= '?url=' . rawurlencode($post_url);
		
	if ($image == 'any') {
		$pinit_conf = ' data-pin-do="buttonBookmark"';
		$pinit_conf .= ' rel="nobox"';
	} else {
		$pinit_url .= '&media=' . rawurlencode($image);
		$pinit_url .= '&description=' . rawurlencode($description);
		$pinit_conf = ' data-pin-do="buttonPin"';
		$pinit_conf .= ' data-pin-config="' . $pin_count . '"';	
		$pinit_conf .= ' rel="nofollow" target="_blank"';	
	}
		
	$pinit_link = '<a href="' . $pinit_url . '"' . $pinit_conf .'><img src="' . $pinit_img . '" /></a>';	
	return $pinit_link;
}

function auto_pin_it_button_css() {
	echo "
	<style type='text/css'>
	.auto-pin-it {
		position: relative;
		display: block;
	}
	.auto-pin-it .auto-pin-it-button {
		position: absolute;
		top: 10px;
		left: 10px;
		display: none;
	}
	.auto-pin-it .auto-pin-it-button img {
		opacity: 0.8;
	}
	.auto-pin-it .auto-pin-it-button img:hover {
		opacity: 1.0;
		cursor: pointer;
	}
	</style>
	";
}

function auto_pin_it_button_script() {
	//echo '<script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script>';
	echo "
	<script type=\"text/javascript\">
		jQuery(document).ready(function($) {
			jQuery('.auto-pin-it').hover(
			       function(){
			           jQuery(this).find('.auto-pin-it-button')
			            .fadeIn('fast');
			       },
			       function(){
			           jQuery(this).find('.auto-pin-it-button')
			            .fadeOut('fast');
			     }
			);
		});
	</script>
	";
}

function auto_pin_it_button_activate() {
	global $wpapib_options;
	$wpapib_options['show_pin_button_on_image'] = 'yes'; 
	$wpapib_options['post_pin_button_below'] = true; 
	$wpapib_options['post_pin_button_above'] = false; 
	$wpapib_options['post_pin_count'] = 'none'; 
	$wpapib_options['image_pin_count'] = 'none'; 
	update_option('wpapib_options', $wpapib_options);	
}

function auto_pin_it_button_deactivate() {}

wp_enqueue_script('jquery');
wp_enqueue_script('pinterest-assets', 'http://assets.pinterest.com/js/pinit.js', array(), false, true);
add_action('wp_head', 'auto_pin_it_button_css');
add_action('wp_head', 'auto_pin_it_button_script');

add_action('the_content', 'auto_pin_it_button_the_content');
add_action('post_thumbnail_html', 'auto_pin_it_button_the_thumbnail');
register_activation_hook(__FILE__, 'auto_pin_it_button_activate');
register_deactivation_hook(__FILE__, 'auto_pin_it_button_deactivate');

//include_once 'auto-pin-to-pinterest.php';
include_once 'auto-pin-it-button-admin.php';

if (! current_theme_supports('post-thumbnails')) {
	add_theme_support('post-thumbnails');
}
	
?>