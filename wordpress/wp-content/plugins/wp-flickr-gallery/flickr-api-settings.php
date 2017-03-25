<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//css
wp_enqueue_style( 'wp-color-picker' );
wp_enqueue_style('awl-fg-bootstrap-css', FG_PLUGIN_URL . 'css/bootstrap.css');
wp_enqueue_style('awl-fg-toogle-button-css', FG_PLUGIN_URL . 'css/toogle-button.css');
wp_enqueue_style('awl-fg-font-awesome-min-css', FG_PLUGIN_URL . 'css/font-awesome.min.css');

//js
wp_enqueue_script('jquery');
wp_enqueue_script( 'awl-fg-bootstrap-js', FG_PLUGIN_URL  . 'js/bootstrap.js', array( 'jquery' ), '', true  );
wp_enqueue_script( 'awl-fg-color-picker-js', FG_PLUGIN_URL .'js/fg-color-picker.js', array( 'wp-color-picker' ), false, true );

//load API Settings
$flickr_api_settings = unserialize(base64_decode(get_option('flickr_api_settings')));

//Set Your Nonce
$fg_ajax_nonce = wp_create_nonce( "fg_api_setting_nonce_key" );
?>
<div class="box">
	<h2><?php _e('Flickr API Settings', 'FGP_TXTDM'); ?></h2>
	<form id="flickr-setting-form">
		<label for="key"><?php _e('Flickr User ID', 'FGP_TXTDM'); ?></label><br><br>
		<?php if(isset($flickr_api_settings['flickr_user_id'])) $flickr_user_id = $flickr_api_settings['flickr_user_id']; else $flickr_user_id = "147476924@N07"; ?>
		<input type="text" name="flickr_user_id" id="flickr_user_id" style="width: 50%;" value="<?php echo $flickr_user_id; ?>">&nbsp;<a href="http://awplife.com/how-to-get-your-user-id-of-flickr/" target="_new"><?php _e('How To Get Your User ID', 'FGP_TXTDM'); ?></a><br><br>					
			
		<label for="key"><?php _e('Flickr API Key', 'FGP_TXTDM'); ?></label><br><br>
		<?php if(isset($flickr_api_settings['flickr_api_key'])) $flickr_api_key = $flickr_api_settings['flickr_api_key']; else $flickr_api_key = "4405cbae4b35b98f14f5e839c6e03599"; ?>
		<input type="text" name="flickr_api_key" id="flickr_api_key" style="width: 50%;" value="<?php echo $flickr_api_key; ?>">&nbsp;<a href="http://awplife.com/how-to-get-your-api-key-of-flickr/" target="_new"z><?php _e('How To Get Your API Key', 'FGP_TXTDM'); ?></a><br><br>					

		<div id="fg_setting_load" name="fg_setting_load" style="display:none;"> 
			<i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw"></i>
			<span class="">Please wait...</span>
		</div>
		&nbsp;&nbsp;&nbsp;<button type="button" id="save_flickr_api_setting" class="btn btn-info" onclick="FlickrAPISaveSettings();">Save</button>
	</form> 
</div>
<style>
label {
	font-weight : bold;
}
.box {
	background:#FFF;
	margin-top:30px;
	padding: 20px;
	border-radius: 25px;
	box-shadow: 0px 0px 30px #008EC2;
	width:100.5%;
}
</style>
<script>
function FlickrAPISaveSettings() {
	jQuery("#fg_setting_load").show();
	jQuery("#save_flickr_api_setting").hide();
	jQuery.ajax({
		dataType : 'html',
		type: 'POST',
		url : ajaxurl,
		cache: false,
		data : jQuery('#flickr-setting-form').serialize() + '&action=api_settings_action' + '&fg_api_security=' + '<?php echo $fg_ajax_nonce; ?>',
		complete : function(){ },
		success: function(data) {
			jQuery("#fg_setting_load").hide();
			jQuery("#save_flickr_api_setting").show();
		}
	});
}
</script>