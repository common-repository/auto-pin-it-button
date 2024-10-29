<?php

if (! class_exists('HttpClient')) {
	include 'http_client.php';
}
if (! class_exists('simple_html_dom')) {
	include 'simple_html_dom.php';
}

/**
 * http://pinterest.com
 * URL：http://pinterest.com/pin/create/button/
 */
class AutoPinToPinterest {

	protected $username;
	
	protected $password;
	
	public function __construct($username = null, $password = null) {
		$this->username = $username;
		$this->password = $password;
	}
	
	public function pin($vars) {
		extract($vars);
		
		$image = $vars['image_url'];
		if (! $image) {
			$this->log($data['url'] . ' - [NO IMAGE]');
			return false;
		}

		//$cookie_file = tempnam("tmp", "curl_cookie");
		echo $cookie_file = dirname(__FILE__).'/cookie.txt';
		file_put_contents($cookie_file, '');

		//login
		$url = 'https://m.pinterest.com/login/?next=%2F';
		
		$request = new HttpClient();
		$response = $request->get($url);

		$hiddens = $this->fetchHidden($response->content());		
		foreach ($hiddens as $hidden) {
			$data[$hidden->name] = $hidden->value;
		}	
				
		$data['email'] = $this->username;
		$data['password'] = $this->password;

		$url = 'https://m.pinterest.com/login/?next=/pin/create/button/';
		$response = $request->post($url, $data, null, 'https://m.pinterest.com/login/?next=%2F');
		
		//submit
		$url = 'http://m.pinterest.com/pin/create/button/';
		$response = $request->get($url);

		$hiddens = $this->fetchHidden($response->content());		
		foreach ($hiddens as $hidden) {
			$data[$hidden->name] = $hidden->value;
		}					
		$data['media_url'] = $image;		
		$data['url'] = $vars['url'];		
		$data['caption'] = $vars['title'];
		
		$response = $request->post($url, $data);
		
		$result = $this->buildSuccessful($response);
		if ($result) {
			$this->log($data['url'] . ' - [SUCCESS]');
		} else {
			$this->log($data['url'] . ' - [ERROR]:' . $request->error());
		}
			
		$request->close();
		return $result;
	}
	
	public function buildSuccessful($response) {
		if ($response->status() != 200) {
			$this->log("Post to Pinterest ==========\n" . $response);
			return FALSE;
		}
		if (strpos($response->content(), 'errorlist')) {
			$this->log("Post to Pinterest ==========\n" . strip_tags($response->body()));
			return FALSE;
		}	
		return TRUE;
	}

	public function fetchHidden($content) {
		$content_pattern = '@<form ([^>]*?)">(.*?)</form>@siu';
		preg_match_all($content_pattern, $content, $matches);
		$match_form = $matches[0];
		if ($match_form) {
			$form_html = '<html>';
			foreach ($match_form as $form) {
				$form_html .= $form;
			}
			$form_html .= '</html>';
		}		
		$hiddens = $this->queryHtml($form_html, 'input[type=hidden]');
		return $hiddens;	
	}
	
	public function queryHtml($html, $selector) {
		$query = str_get_html($html);	
		if (! $query) {
			return array();
		}
		return $query->find($selector);		
	}
	
	function log($message) {	
		if (($fp = @fopen('auto-pin-to-pinterest.log', "a+"))) {
			$message = date('Y-m-d H:i:s') . ' - ' . $message . "\n";
			flock($fp, LOCK_EX);
			fwrite($fp, $message);
			flock($fp, LOCK_UN);
			fclose($fp);
		}	
	}

}

function auto_pin_it_publish($post_id) {
	$is_revision = wp_is_post_revision($post_id);
	if ($is_revision) {
		return;
	}
	$already_posted = get_post_meta($post_id, 'wpapib_already_posted', true);
	if ($already_posted) {
		return;
	}
	add_post_meta($post_id, 'wpapib_already_posted', 'inprocess');	
	
	//auto_pin_it_internal($post_id);
	auto_pin_it_in_background($post_id);
	
	update_post_meta($post_id, 'wpsb_already_posted', 'complated');
}

add_action('publish_post', 'auto_pin_it_publish');
add_action('publish_page', 'auto_pin_it_publish');


?>