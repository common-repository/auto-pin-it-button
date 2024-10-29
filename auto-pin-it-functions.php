<?php

function auto_pin_it_button_featured_image($post_id = null) {
	return wp_get_attachment_url(get_post_thumbnail_id($post_id));
}

function auto_pin_it_button_main_image($content) {
	$pattern = '/<img(.*?)src=[\'"](.*?)[\'"](.*?)>/i';
	//$pattern = '/<img(.*?)src=[\'"](.*?).(bmp|gif|jpeg|jpg|png)[\'"](.*?)>/i';
	preg_match_all($pattern, $content, $matches);

	$main_image = null;
	$main_image_size = 0;	
	$images = $matches[2];
	if (!empty($images)) {
		foreach ($images as $image) {
			if (! $main_image) {
				$main_image = $image;
			}
			$image_size = auto_pin_it_button_get_image_size($image);
			if (! empty($image_size)) {
				$size = $image_size[0] + $image_size[1];
				if ($size > $main_image_size) {
					$main_image = $image;
					$main_image_size = $image_size;
				}
			}			
		}
	}
	return $main_image;
}

function auto_pin_it_button_get_image_size($image) {
	if (! $image) {
		return false;
	}
	
	if (is_file($image)) {
		return getimagesize($image);
	}
						
	$image_file = str_replace(get_option('siteurl'), ABSPATH, $image);
	if (is_file($image_file)) {
		return getimagesize($image_file);
	} 
	
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $image);
    curl_setopt($ch, CURLOPT_RANGE, '0-167');
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $image_data = curl_exec($ch);
    curl_close($ch);
    return  getimagesize('data://image/jpeg;base64,'. base64_encode($image_data)); 					
}

function auto_pin_it_button_image_available($image) {
	$image_size = auto_pin_it_button_get_image_size($image);
	if (empty($image_size)) {
		return false;
	}
	return $image_size[0] > 160;
}

function auto_pin_it_post_data($post_id) {
	$post = get_post($post_id);	
	if (!$post) {
		return false;
	}
			
	$data['name'] = $post->post_name;
	$data['title'] = $post->post_title;
	
	$post_url = get_permalink($post_id);
	if (! $post_url) {
		$post_url = get_option('siteurl') . '/?p=' . $post_id;
	}	
	$data['url'] = $post_url;
	$image_url = auto_pin_it_button_featured_image($post_id);
	if (! $image_url) {
		$image_url = auto_pin_it_button_main_image($post->post_content);
	}
	$data['image_url'] = $image_url;
	
	return $data;
}

function auto_pin_it_internal($post_id) {
	@set_time_limit(0);
	@ignore_user_abort(true);
	
	$data = auto_pin_it_post_data($post_id);
	if (! $data) {
		return;
	}

	global $wpapib_options;
	if (! empty($wpapib_options)) {
		$pinterest_username = $wpapib_options['pinterest_username']; 
		$pinterest_password = $wpapib_options['pinterest_password'];
		if ($pinterest_username && $pinterest_password) {
			$builder = new AutoPinToPinterest($pinterest_username, $pinterest_password);
			$builder->pin($data);
		}
	}	
	
}

function auto_pin_it_in_background($post_id) {
	global $wpapib_apikey, $pin_to_pinterest_in_background_url;
	
	$request_uri = $pin_to_pinterest_in_background_url;	
				
	$timeout = 180;
	$method = 'GET';
	
	$request_params = "?post_id=$post_id&apikey=$wpapib_apikey";
	$url = $request_uri . $request_params;
				
	$bits = parse_url($url);
	$host = $bits['host'];
	$port = isset($bits['port']) ? $bits['port'] : 80;
	$path = isset($bits['path']) ? $bits['path'] : '/';
	if (isset($bits['query'])) {
		$path .= '?' . $bits['query'];
	}
	
	$headers = array();
	$headers[] = "{$method} {$path} HTTP/1.0";
	$headers[] = "Host: {$host}";
	$request = implode("\r\n", $headers) . "\r\n\r\n";
	
	$errno = null;
	$errstr = null;
	$fp = @fsockopen($host, $port, $errno, $errstr, $timeout);
		
	if ($fp) {
		fwrite($fp, $request);
		fclose($fp);
	} else {
		auto_pin_it_internal($post_id);		
	}
}

?>