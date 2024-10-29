<?php

	header('Content-Type: text/html;charset=utf-8');
	
	@ignore_user_abort(true);
	@set_time_limit(0);
	
//	require_once ('../../../wp-load.php');
	require_once ('../../../wp-config.php');
	require_once (dirname(__FILE__) . '/auto-pin-it-button.php');

	$post_id = @$_GET['post_id'];
	$post_apikey = @$_GET['apikey'];
	
	global $wpapib_apikey;
	if ($post_id && ($post_apikey == $wpapib_apikey)) {
		auto_pin_it_internal($post_id);		
	}
	
?>