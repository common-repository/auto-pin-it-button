<?php

add_action('admin_menu', 'auto_pin_it_button_menu');

function auto_pin_it_button_menu() {
	add_options_page('Auto Pin It Button', 'Auto Pin It Button', 'manage_options', __FILE__, 'auto_pin_it_button_setting_page');
	add_action('admin_init', 'auto_pin_it_button_init');
}

function auto_pin_it_button_init() {
	register_setting('wpapib_settings_fields', 'wpapib_options');
}

function auto_pin_it_button_setting_page() {
	$wpapib_options = get_option('wpapib_options');
?>
<style>
textarea,
input[type="text"],
input[type="password"],
select {
	margin: 1px 0px;
	padding: 3px;
	border: 1px solid #ccc;
	font-family: arial;	
	padding: 5px 3px;
	font-size: 1.2em;
}
label {
	padding-top: 12px;
	line-height: 2;
}
.postbox{
	margin: 15px 0px; 
	font-size: 1.1em;
	width: 60%;
}
.h10 {
	overflow: hidden;
	height: 8px;
}
</style>
<div class="wrap">
    <div class="metabox-holder">

      	<h2>Auto Pin It Button</h2>

       	<form method="post" action="options.php">
   		<?php settings_fields('wpapib_settings_fields'); ?>
   		
        <div class="postbox">
         	<h3>Pin It Button For Images</h3>
         	<div class="inside">
	         		
	         		<div class="h10"></div>
	         		<?php if ($wpapib_options['show_pin_button_on_image'] == 'yes') {?>
					<input id="show_pin_button_on_image" name="wpapib_options[show_pin_button_on_image]" value="yes" checked="checked" type="radio"/>
					<?php } else { ?>
					<input id="show_pin_button_on_image" name="wpapib_options[show_pin_button_on_image]" value="yes" type="radio"/>
					<?php } ?>
					Show Pin it button on all images<!-- , Exclude image with "nopinbtn" class -->
	         		<div class="h10"></div>
	         		
	         		<?php if ($wpapib_options['show_pin_button_on_image'] == 'by_class') {?>
					<input id="show_pin_button_on_image_by_class" name="wpapib_options[show_pin_button_on_image]" value="by_class" checked="checked" type="radio"/>
					<?php } else { ?>
					<input id="show_pin_button_on_image_by_class" name="wpapib_options[show_pin_button_on_image]" value="by_class" type="radio"/>
					<?php } ?>
					Show Pin it button only on images with "autopinbtn" class
	         		<div class="h10"></div>
	         		
	         		<label>Pin Button Style: </label><br/>
	         		<?php if ($wpapib_options['image_pin_count'] == 'none') {?>
					<input id="image_pin_count_none" name="wpapib_options[image_pin_count]" value="none" checked="checked" type="radio"/>
					<?php } else { ?>
					<input id="image_pin_count_none" name="wpapib_options[image_pin_count]" value="none" type="radio"/>
					<?php } ?>
					No Pin Count<!--  Not Shown  -->&nbsp;&nbsp;
							
	         		<?php if ($wpapib_options['image_pin_count'] == 'beside') {?>
					<input id="image_pin_count_beside" name="wpapib_options[image_pin_count]" value="beside" checked="checked" type="radio"/>
					<?php } else { ?>
					<input id="image_pin_count_beside" name="wpapib_options[image_pin_count]" value="beside" type="radio"/>
					<?php } ?>
					Pin Count Beside the Button &nbsp;&nbsp;
							
	         		<?php if ($wpapib_options['image_pin_count'] == 'above') {?>
					<input id="image_pin_count_count" name="wpapib_options[image_pin_count]" value="above" checked="checked" type="radio"/>
					<?php } else { ?>
					<input id="image_pin_count_count" name="wpapib_options[image_pin_count]" value="above" type="radio"/>
					<?php } ?>
					Pin Count Above the Button		
	         		<div class="h10"></div>
	         		
	         		<img src="<?php echo plugins_url('images/pinit_preview.png', __FILE__);?>" />

       		</div>
        </div>
		
        <div class="postbox">
         	<h3>Pin It Button For Post/Page</h3>
         	<div class="inside">
	         		
	         		<div class="h10"></div>
	         		<label>Pin Button Placement: </label><br/>
	         		<?php if ($wpapib_options['post_pin_button_above']) {?>
					<input id="post_pin_button_above" name="wpapib_options[post_pin_button_above]" checked="checked" type="checkbox"/>
					<?php } else { ?>
					<input id="post_pin_button_above" name="wpapib_options[post_pin_button_above]" type="checkbox"/>
					<?php } ?>
					Display Pin it button Above Content		
	         		<div class="h10"></div>
	         		
	         		<?php if ($wpapib_options['post_pin_button_below']) {?>
					<input id="post_pin_button_below" name="wpapib_options[post_pin_button_below]" checked="checked" type="checkbox"/>
					<?php } else { ?>
					<input id="post_pin_button_below" name="wpapib_options[post_pin_button_below]" type="checkbox"/>
					<?php } ?>
					Display Pin it button Below Content		
	         		<div class="h10"></div>
	         		
	         		<div class="h10"></div>
	         		<label>Pin Button Style: </label><br/>
	         		<?php if ($wpapib_options['post_pin_count'] == 'none') {?>
					<input id="post_pin_count_none" name="wpapib_options[post_pin_count]" value="none" checked="checked" type="radio"/>
					<?php } else { ?>
					<input id="post_pin_count_none" name="wpapib_options[post_pin_count]" value="none" type="radio"/>
					<?php } ?>
					No Pin Count<!--  Not Shown  --> &nbsp;&nbsp;
							
	         		<?php if ($wpapib_options['post_pin_count'] == 'beside') {?>
					<input id="post_pin_count_beside" name="wpapib_options[post_pin_count]" value="beside" checked="checked" type="radio"/>
					<?php } else { ?>
					<input id="post_pin_count_beside" name="wpapib_options[post_pin_count]" value="beside" type="radio"/>
					<?php } ?>
					Pin Count Beside the Button &nbsp;&nbsp;
							
	         		<?php if ($wpapib_options['post_pin_count'] == 'above') {?>
					<input id="post_pin_count_count" name="wpapib_options[post_pin_count]" value="above" checked="checked" type="radio"/>
					<?php } else { ?>
					<input id="post_pin_count_count" name="wpapib_options[post_pin_count]" value="above" type="radio"/>
					<?php } ?>
					Pin Count Above the Button		
	         		<div class="h10"></div>
	         		<img src="<?php echo plugins_url('images/pinit_preview.png', __FILE__);?>" />
	         		<div class="h10"></div>
	         		
	         		<label>Pin Button Visibility: </label><br/>
	         		<?php if ($wpapib_options['no_pin_button_on_index']) {?>
					<input id="no_pin_button_on_index" name="wpapib_options[no_pin_button_on_index]" checked="checked" type="checkbox"/>
					<?php } else { ?>
					<input id="no_pin_button_on_index" name="wpapib_options[no_pin_button_on_index]" type="checkbox"/>
					<?php } ?>
					Do not show Pin it button on index/home page
					<div class="h10"></div>
							
	         		<?php if ($wpapib_options['no_pin_button_on_category']) {?>
					<input id="no_pin_button_on_category" name="wpapib_options[no_pin_button_on_category]" checked="checked" type="checkbox"/>
					<?php } else { ?>
					<input id="no_pin_button_on_category" name="wpapib_options[no_pin_button_on_category]" type="checkbox"/>
					<?php } ?>
					Do not show Pin it button on category page		
	         		<div class="h10"></div>
	         		
	         		<?php if ($wpapib_options['no_pin_button_on_archive']) {?>
					<input id="no_pin_button_on_archive" name="wpapib_options[no_pin_button_on_archive]" checked="checked" type="checkbox"/>
					<?php } else { ?>
					<input id="no_pin_button_on_archive" name="wpapib_options[no_pin_button_on_archive]" type="checkbox"/>
					<?php } ?>
					Do not show Pin it button on archive page (includes Tag, Author and date-based page)		
	         		<div class="h10"></div>
	         			         		
       		</div>
        </div>
		<!-- 
        <div class="postbox">
         	<h3>Pin your blog posts to Pinterest Automatically</h3>
         	<div class="inside">	         		
         		<div class="h10"></div>
				<label for="pinterest_username">Connect to Pinterest with your email and password</label><br/> 
				<input id="pinterest_username" name="wpapib_options[pinterest_username]" value="<?php echo $wpapib_options['pinterest_username']; ?>" type="text" size="28" maxlength="64" placeholder="Email"/>&nbsp;&nbsp;			
				<input id="pinterest_password" name="wpapib_options[pinterest_password]" value="<?php echo $wpapib_options['pinterest_password']; ?>" type="text" size="28" maxlength="64" placeholder="Password"/>			
				<div class="h10"></div>
				<a href="http://pinterest.com/" target="_blank">http://pinterest.com/</a>
				<div class="h10"></div>
				<div class="h10"></div>
       		</div>
        </div>
        -->
		<div class="h10"></div>
		
       	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		<br/>
		<br/>
        
  		</form>

        <div>
			<h4>For more infomation</h4>
			Plugin URI: <a href="http://wpextends.sinaapp.com/plugins/auto-pin-it-button.html" target="_blank">http://wpextends.sinaapp.com/plugins/auto-pin-it-button.html</a><br/>
			Our Website:<a href="http://wpextends.sinaapp.com" target="_blank">http://wpextends.sinaapp.com</a><br/>
	        <div class="h10"></div>
	        Please contact us at <a href="mailto:support@wordpressextends.com">support@wordpressextends.com</a> whenever you have any questions and comments.
        </div>	
        <div class="h10"></div> 
        		
        <div>
          	<h4>Like this plugin? We need your help to make it better:</h4>
          	<ul>
        		<li>Write a positive review.</li>
        		<li>Tell us some improvements.</li>
          		<li>If you’d like to donate...</li>
          	</ul>
          	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
				<input type="hidden" name="cmd" value="_donations">
				<input type="hidden" name="business" value="market@wordpressextends.com">
				<input type="hidden" name="item_name" value="Auto Pin It Button plugin for Wordpress">
				<input type="hidden" name="currency_code" value="USD">
				<!-- <input type="hidden" name="notify_url" value="link to IPN script"> -->				
				<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
			</form>
			<p>Your support shows that what we’re doing really matters and help this plugin to move forward! Thank you.</p>
        </div>
        <div class="h10"></div>   
	

    </div>
</div>
<?php } ?>