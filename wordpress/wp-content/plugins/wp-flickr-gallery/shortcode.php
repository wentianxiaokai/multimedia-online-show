<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * video Gallery Premium Shortcode
 */
add_shortcode('FGAL', 'awl_flickr_gallery_shortcode');
function awl_flickr_gallery_shortcode($post_id) {
	ob_start();
	
	//css
	wp_enqueue_style('awl-fg-bootstrap-css', FG_PLUGIN_URL .'css/bootstrap.css');
	//wp_enqueue_style('awl-fg-animate-css', FG_PLUGIN_URL.'css/animate.css' );
	wp_enqueue_style('awl-fg-lightcase-css', FG_PLUGIN_URL.'css/lightcase.css' );
	
	//js
	wp_enqueue_script('jquery');
	wp_enqueue_script('media-upload');
	wp_enqueue_script('awl-fg-isotope-js', FG_PLUGIN_URL .'js/isotope.pkgd.js', array('jquery'), '' , true);
	wp_enqueue_script('awl-fg-imagesloaded-js', FG_PLUGIN_URL .'js/imagesloaded.pkgd.js', array('jquery'), '' , true);
	wp_enqueue_script('awl-fg-lightcase-js', FG_PLUGIN_URL .'js/lightcase.js', array('jquery'), '' , true);
	$flickr_gallery_id = $post_id['id'];
	
	//get Flickr API Settings
	$flickr_api_settings = unserialize(base64_decode(get_option('flickr_api_settings')));
	//print_r($flickr_api_settings);
	$flickr_api_key = $flickr_api_settings['flickr_api_key'];
	$flickr_user_id = $flickr_api_settings['flickr_user_id'];
	
	//load post settings
	$flickr_gallery_settings = unserialize(base64_decode(get_post_meta( $flickr_gallery_id, 'awl_fg_post_settings_'.$flickr_gallery_id, true)));
	if(isset($flickr_gallery_settings['flickr_gallery_type'])) $flickr_gallery_type = $flickr_gallery_settings['flickr_gallery_type']; else $flickr_gallery_type = "photostream";	

	//print_r($flickr_gallery_settings);
	
	// photostream settings
	if($flickr_gallery_type == 'photostream') {
		$flickr_photostrem_method = 'flickr.people.getPublicPhotos';
	}
	
	// album settings
	if($flickr_gallery_type == 'album') {
		$flickr_album_method = 'flickr.photosets.getPhotos';
		if(isset($flickr_gallery_settings['flickr_album_id'])) $flickr_album_id = $flickr_gallery_settings['flickr_album_id']; else $flickr_album_id = ""; 	
	}
	
	//common settings
	if(isset($flickr_gallery_settings['col_desktops'])) $col_desktops = $flickr_gallery_settings['col_desktops']; else $col_desktops = "col-md-4";
	if(isset($flickr_gallery_settings['thumb_img_size'])) $thumb_img_size = $flickr_gallery_settings['thumb_img_size']; else $thumb_img_size = "url_q";
	if(isset($flickr_gallery_settings['lightbox_img_size'])) $lightbox_img_size = $flickr_gallery_settings['lightbox_img_size']; else $lightbox_img_size = "url_m";
	if(isset($flickr_gallery_settings['photo_titlestyle'])) $photo_titlestyle = $flickr_gallery_settings['photo_titlestyle']; else $photo_titlestyle = 1;
	if(isset($flickr_gallery_settings['fg_animation_effect'])) $fg_animation_effect = $flickr_gallery_settings['fg_animation_effect']; else $fg_animation_effect = "bounce";
	
	// light box settings
	if(isset($flickr_gallery_settings['apply_light_box'])) $apply_light_box = $flickr_gallery_settings['apply_light_box']; else $apply_light_box = "true"; 
	
	//gallery post title settings
	if(isset($flickr_gallery_settings['post_title'])) $fg_title = $flickr_gallery_settings['post_title']; else $fg_title = "";	
	if(isset($flickr_gallery_settings['fg_gallery_title'])) $fg_gallery_title = $flickr_gallery_settings['fg_gallery_title']; else $fg_gallery_title = "true";
	if(isset($flickr_gallery_settings['fg_gallery_titlecolor'])) $fg_gallery_titlecolor = $flickr_gallery_settings['fg_gallery_titlecolor']; else $fg_gallery_titlecolor = "#000000";
	if(isset($flickr_gallery_settings['fg_gallery_titlesize'])) $fg_gallery_titlesize = $flickr_gallery_settings['fg_gallery_titlesize']; else $fg_gallery_titlesize = 16;
	if(isset($flickr_gallery_settings['fg_gallery_titlealighment'])) $fg_gallery_titlealighment = $flickr_gallery_settings['fg_gallery_titlealighment']; else $fg_gallery_titlealighment = "text-left";
	?>
	<style>
		.entry-content a, .entry-summary a, .taxonomy-description a, 
		.logged-in-as a, .comment-content a, .pingback .comment-body > a, 
		.textwidget a, .entry-footer a:hover, .site-info a:hover {
			box-shadow: none !important;
		}
	
		.single-photostream-<?php echo $flickr_gallery_id ?> {
			padding-bottom: 15px;
		}
	</style>
	<?php
	
	//gallery tile
	if($fg_gallery_title == "true") { ?>
		<p class="awp-flickr-post-title" style="margin-right: 15px; margin-left: 10px; color:<?php echo $fg_gallery_titlecolor; ?>; font-size: <?php echo $fg_gallery_titlesize; ?>px ; text-align:<?php echo $fg_gallery_titlealighment; ?>;"><?php echo $fg_title; ?></p>
		<?php 
	}
	
	//photostream
	if($flickr_gallery_type == 'photostream') {
		require("flickr-get-photostream.php");
	}
	
	//album
	if($flickr_gallery_type == 'album') {
		require("flickr-get-album.php");
	}
	return ob_get_clean();
}
?>
