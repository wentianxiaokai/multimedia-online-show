<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	//Flickr Post Settings
	$flickr_gallery_settings = unserialize(base64_decode(get_post_meta( $post->ID, 'awl_fg_post_settings_'.$post->ID, true)));
	//print_r($flickr_gallery_settings);
	//$flickr_gallery_settings_id = $post->ID;
	
	//$flickr_gallery_id = $post->ID;
	//css
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_style('awl-fg-setting-bootstrap-css', FG_PLUGIN_URL . 'css/setting-bootstrap.css');
	wp_enqueue_style('awl-fg-toogle-button-css', FG_PLUGIN_URL . 'css/toogle-button.css');
	wp_enqueue_style('awl-fg-styles-css', FG_PLUGIN_URL . 'css/styles.css');
	wp_enqueue_style( 'awl-fg-font-awesome-min-css', FG_PLUGIN_URL .'css/font-awesome.min.css' );

	//js
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'awl-fg-bootstrap-js', FG_PLUGIN_URL  . 'js/bootstrap.js', array( 'jquery' ), '', true  );
	wp_enqueue_script( 'awl-fg-color-picker-js', FG_PLUGIN_URL .'js/fg-color-picker.js', array( 'wp-color-picker' ), false, true );
	
	//uploader
	wp_enqueue_media();
	wp_enqueue_script('thickbox');
	wp_enqueue_script('em-image-upload');
	wp_enqueue_style('thickbox');
?>
<style>
	.entry-content a, .entry-summary a, .taxonomy-description a, 
	.logged-in-as a, .comment-content a, .pingback .comment-body > a, 
	.textwidget a, .entry-footer a:hover, .site-info a:hover {
		 box-shadow: 0px !important;
		}
	#wpcontent {
		background-color: InactiveBorder;
	}
	.container-fluid{
		background-color:white;
	}
	.cmnt_setting{
		font-size: 16px !important;
		font-family:Geneva;
		padding-left: 4px;
		font: initial;
		margin-top: 5px;
		padding-left:25px;
	}
	.input_setting {
		padding-left: 10px;
		width: 20%;
		margin-left: 18px	
	}
	.wp-color-result {
		height: auto;
		margin: 6px 6px 6px 15px;
	}
	.wp-picker-container input.wp-color-picker[type="text"] {
		width: 80px !important;
		height: 22px !important;
		float: left;
		font-size: 11px !important;
		margin: 8px 0px 6px 0px
	}
	.iris-border .iris-palette-container {
		bottom: 6px;
	}
	.wp-core-ui .button, .wp-core-ui .button.button-large, .wp-core-ui .button.button-small, a.preview, input#publish, input#save-post {
		height: auto !important;
		padding: 0 12px !important;
		margin: 6px;
	}
	.selectbox_position_newslide {
		border-width: 1px 1px 1px 6px !important;
		border-color: #008EC2 !important;
		width: 30% !important; 
		margin-bottom : 3px;
		margin-left: 25px;
		margin-top:-20px;
	}
</style>
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	<div class="container-fluid">
		<h1 class="col-md-12" align="center" style="font-family:Geneva; font-size: 40px;"><?php _e('Flickr Gallery Settings', FGP_TXTDM); ?></h1>
		</br></br></br></br></br>
		<p style="font-size: 20px; padding-left:10px;"><em> First set your Flickr User ID & API Key Into <a href="http://localhost/free/wp-admin/edit.php?post_type=flickr_gallery&page=fg-api-settings">"Flickr API Settings"</a> Page.</em></p>
		<!--Photo Stream & Album Settings Start -->	
					<p class="bg-title"><?php _e('Flickr Gallery Type', FGP_TXTDM); ?></p>
					<p class="switch-field em_size_field"><br>
						<?php if(isset($flickr_gallery_settings['flickr_gallery_type'])) $flickr_gallery_type = $flickr_gallery_settings['flickr_gallery_type']; else $flickr_gallery_type = "photostream"; ?>
						<input type="radio" name="flickr_gallery_type" id="flickr_gallery_type1" value="photostream" <?php if($flickr_gallery_type == "photostream") echo "checked=checked"; ?>>
						<label for="flickr_gallery_type1"><?php _e('Photostream', FGP_TXTDM); ?></label>
						<input type="radio" name="flickr_gallery_type" id="flickr_gallery_type2" value="album" <?php if($flickr_gallery_type == "album") echo "checked=checked"; ?>>
						<label for="flickr_gallery_type2"><?php _e('Album', FGP_TXTDM); ?></label>
					</p><br>
						
					<!-- Album Gallery Settings -->
					<div class="album_gallery">
						<p class="bg-lower-title"><?php _e('I. All My Flickr Albums', FGP_TXTDM); ?></p></br>
						<?php
						 if(isset($flickr_gallery_settings['flickr_album_id'])) $flickr_album_id = $flickr_gallery_settings['flickr_album_id']; else $flickr_album_id = "";
						// php code to fetch users account album list
						$flickr_api_settings = unserialize(base64_decode(get_option('flickr_api_settings')));
						$flickr_api_key = $flickr_api_settings['flickr_api_key'];
						$flickr_user_id = $flickr_api_settings['flickr_user_id'];
						$flickr_method = "flickr.photosets.getList"; // https://www.flickr.com/services/api/flickr.photosets.getList.html
						if($flickr_user_id && $flickr_api_key) {
							$params = array(
								'api_key'		=>	$flickr_api_key,
								'user_id'		=>	$flickr_user_id,
								'method'		=>	$flickr_method,
								'format'		=>	'php_serial',
							);

							$encoded_params = array();
							foreach ($params as $k => $v){
								$encoded_params[] = urlencode($k).'='.urlencode($v);
							}

							# call the API and decode the response
							$url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params);
							$rsp = file_get_contents($url);
							$rsp_obj = unserialize($rsp);
							/* echo "<pre>";
							print_r($rsp_obj);
							echo "</pre>"; */
							if(isset($rsp_obj['photosets']['photoset'])){
								$all_albums = $rsp_obj['photosets']['photoset'];
								echo "<select id='flickr_album_id' name='flickr_album_id' class='selectbox_position_newslide'>";
								foreach($all_albums as $album) {
									$album_id = $album['id'];
									$album_name = $album['title']['_content'];
									$album_description = $album['description']['_content'];
									?>									 
									<option value="<?php echo $album_id; ?>" <?php if($flickr_album_id == $album_id) echo "selected=selected"; ?>><?php echo ucwords($album_name); echo " ($album_description)"; ?></option>
									<?php 							
								}								
								echo "</select>";
							} else {
								echo "No Album Fount into your account.";
							}
						}
						?>
						</br></br>
						<p class="cmnt_setting"><?php _e('Select an album gallery to display into gallery.', FGP_TXTDM); ?></p>
					</div>	
		<!--Photo Stream & Album Settings End -->	
		<!--Title & Column Settings Start -->	
		<p class="bg-title"><?php _e('Gallery Title', FGP_TXTDM); ?></p>
			<p class="switch-field em_size_field">
				<?php if(isset($flickr_gallery_settings['fg_gallery_title'])) $fg_gallery_title = $flickr_gallery_settings['fg_gallery_title']; else $fg_gallery_title = "true"; ?><br>
				<input type="radio" name="fg_gallery_title" id="fg_gallery_title1" value="true" <?php if($fg_gallery_title == "true") echo "checked=checked"; ?>>
				<label for="fg_gallery_title1"><?php _e('Show', FGP_TXTDM); ?></label>
				<input type="radio" name="fg_gallery_title" id="fg_gallery_title2" value="false" <?php if($fg_gallery_title == "false") echo "checked=checked"; ?>>
				<label for="fg_gallery_title2"><?php _e('Hide', FGP_TXTDM); ?></label>
			</p><br/>
		<p class="cmnt_setting"><?php _e('Show / Hide gallery post title.', FGP_TXTDM); ?></p>
	
		<div class="gallery_post_title">
			<p class="bg-lower-in-title"><?php _e('A. Gallery Title Color', FGP_TXTDM); ?></p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php if(isset($flickr_gallery_settings['fg_gallery_titlecolor'])) $fg_gallery_titlecolor = $flickr_gallery_settings['fg_gallery_titlecolor']; else $fg_gallery_titlecolor = "#000000"; ?>
			<input type="text"  class="form-control" id="fg_gallery_titlecolor" name="fg_gallery_titlecolor" placeholder="chose form color" value="<?php echo $fg_gallery_titlecolor; ?>" default-color="<?php echo $fg_gallery_titlecolor; ?>"></br>
			<p class="cmnt_setting"><?php _e('Select the color of the gallery post title.', FGP_TXTDM); ?></p>

			<p class="bg-lower-in-title"><?php _e('B. Gallery Title Font Size', FGP_TXTDM); ?></p>
			<p class="range-slider">
				<?php if(isset($flickr_gallery_settings['fg_gallery_titlesize'])) $fg_gallery_titlesize = $flickr_gallery_settings['fg_gallery_titlesize']; else $fg_gallery_titlesize = 16; ?>
				<input id="fg_gallery_titlesize" name="fg_gallery_titlesize" class="range-slider__range" type="range" value="<?php echo $fg_gallery_titlesize; ?>" min="10" max="50" step="1" style="width: 40% !important; margin-left: 20px;">
				<span class="range-slider__value"><?php echo $fg_gallery_titlesize; ?></span>
			</p>
			<p class="cmnt_setting"><?php _e('Set the gallery post title font size', FGP_TXTDM); ?></p>

			<p class="bg-lower-in-title"><?php _e('C. Gallery Title Text Alignment', FGP_TXTDM); ?></p>
			<p class="switch-field em_size_field">
				<?php if(isset($flickr_gallery_settings['fg_gallery_titlealighment'])) $fg_gallery_titlealighment = $flickr_gallery_settings['fg_gallery_titlealighment']; else $fg_gallery_titlealighment = "left"; ?><br>
				<input type="radio" name="fg_gallery_titlealighment" id="fg_gallery_titlealighment1" value="left" <?php if($fg_gallery_titlealighment == "left") echo "checked=checked"; ?>>
				<label for="fg_gallery_titlealighment1"><?php _e('Left', FGP_TXTDM); ?></label>
				<input type="radio" name="fg_gallery_titlealighment" id="fg_gallery_titlealighment2" value="center" <?php if($fg_gallery_titlealighment == "center") echo "checked=checked"; ?>>
				<label for="fg_gallery_titlealighment2"><?php _e('Center', FGP_TXTDM); ?></label>
				<input type="radio" name="fg_gallery_titlealighment" id="fg_gallery_titlealighment3" value="right" <?php if($fg_gallery_titlealighment == "right") echo "checked=checked"; ?>>
				<label for="fg_gallery_titlealighment3"><?php _e('Right', FGP_TXTDM); ?></label>
			</p><br/>
			<p class="cmnt_setting"><?php _e('Set alignment of gallery post title.', FGP_TXTDM); ?></p>
		</div>

		<p class="bg-title"><?php _e('Colums On Desktops', FGP_TXTDM); ?></p></br></br>
		<?php if(isset($flickr_gallery_settings['col_desktops'])) $col_desktops = $flickr_gallery_settings['col_desktops']; else $col_desktops = "col-md-4"; ?>
		<select id="col_desktops" name="col_desktops" class="selectbox_position_newslide">
			<option value="col-md-4" <?php if($col_desktops == "col-md-4") echo "selected=selected"; ?>>3 Column Layout</option>
			<option value="col-md-3" <?php if($col_desktops == "col-md-3") echo "selected=selected"; ?>>4 Column Layout</option>
		</select></br></br>
		<p class="cmnt_setting"><?php _e('Sets the columns on the Desktops', FGP_TXTDM); ?></p>		
		
		<p class="bg-title"><?php _e('Image Size', FGP_TXTDM); ?></p></br>
		<p class="bg-lower-in-title"><?php _e('A. Thumbnail Image Size', FGP_TXTDM); ?></p></br></br>
		<?php if(isset($flickr_gallery_settings['thumb_img_size'])) $thumb_img_size = $flickr_gallery_settings['thumb_img_size']; else $thumb_img_size = "url_q"; ?>
		<select id="thumb_img_size" name="thumb_img_size" class="selectbox_position_newslide">
			<option value="url_sq" <?php if($thumb_img_size == "url_sq") echo "selected=selected"; ?>>Square - 75x75</option>
			<option value="url_q" <?php if($thumb_img_size == "url_q") echo "selected=selected"; ?>>Large Square - 150x150</option>
			<option value="url_t" <?php if($thumb_img_size == "url_t") echo "selected=selected"; ?>>Thumbnail - 100x75</option>
			<option value="url_s" <?php if($thumb_img_size == "url_s") echo "selected=selected"; ?>>Small - 240x180</option>
		</select></br></br>
		<p class="cmnt_setting"><?php _e('Set thumbnail image size for gallery output', FGP_TXTDM); ?></p>
		
		<p class="bg-lower-in-title"><?php _e('B. Lightbox Preview Image Size', FGP_TXTDM); ?></p></br></br>
		<?php if(isset($flickr_gallery_settings['lightbox_img_size'])) $lightbox_img_size = $flickr_gallery_settings['lightbox_img_size']; else $lightbox_img_size = "url_m"; ?>
		<select id="lightbox_img_size" name="lightbox_img_size" class="selectbox_position_newslide">
			<option value="url_m" <?php if($lightbox_img_size == "url_m") echo "selected=selected"; ?>>Medium - 500x375</option>
			<option value="url_l" <?php if($lightbox_img_size == "url_l") echo "selected=selected"; ?>>Large - 1024x768</option>
		</select></br></br>
		<p class="cmnt_setting"><?php _e('Set lightbox image preview size when click on thumbnail', FGP_TXTDM); ?></p>
		
		<p class="bg-title"><?php _e('Custom CSS', 'FGP_TXTDM'); ?></p></br></br>
		<?php if(isset($flickr_gallery_settings['custom_css'])) $custom_css = $flickr_gallery_settings['custom_css']; else $custom_css = ""; ?>
		<textarea name="custom_css" id="custom_css"  style="width: 60%; height: 150px;"  class="selectbox_position_newslide" ><?php echo $custom_css; ?></textarea>
		<p class="cmnt_setting"><?php _e('Apply own custom CSS code to this shortcode', FGP_TXTDM); ?></p>		
		<!--Title & Column Settings End -->
		
		<!--Light Box Settings Start -->
		<p class="bg-title"><?php _e('Light Image Gallery', FGP_TXTDM); ?></p>
		<p class="switch-field em_size_field">
			<?php if(isset($flickr_gallery_settings['apply_light_box'])) $apply_light_box = $flickr_gallery_settings['apply_light_box']; else $apply_light_box = "true"; ?><br>
			<input type="radio" name="apply_light_box" id="apply_light_box1" value="true" <?php if($apply_light_box == "true") echo "checked=checked"; ?>>
			<label for="apply_light_box1"><?php _e('Enable', FGP_TXTDM); ?></label>
			<input type="radio" name="apply_light_box" id="apply_light_box2" value="false" <?php if($apply_light_box == "false") echo "checked=checked"; ?>>
			<label for="apply_light_box2"><?php _e('Disable', FGP_TXTDM); ?></label>
		</p><br/>
		<p class="cmnt_setting"><?php _e('Show / Hide light gallery image light box.', FGP_TXTDM); ?></p>
		<!--Light Box Settings End -->
	</div>
</div>	
<?php
	// syntax: wp_nonce_field( 'name_of_my_action', 'name_of_nonce_field' );
	wp_nonce_field( 'fg_save_settings', 'fg_save_nonce' );
?>
<script>
// single image uploader
jQuery(document).ready( function( jQuery ) {

    jQuery('#upload_cover_button').click(function() {

        formfield = jQuery('#upload_cover').attr('name');
        tb_show( '', 'media-upload.php?type=image&amp;TB_iframe=true' );
        return false;
    });

    window.send_to_editor = function(html) {

        imgurl = jQuery(html).attr('src');
		if(!(imgurl)) {
			imgurl = jQuery('img', html).attr('src');
		}
        jQuery('#upload_cover').val(imgurl);
        //jQuery('#upload_cover').val(imgurl);
		jQuery("#upload_cover_preview").attr("src", imgurl);
		jQuery("#upload_cover_preview2").remove();
        tb_remove();
    }

});

	//dropdown toggle on change effect
	jQuery(document).ready(function() {
		//accordion icon
		jQuery(function() {
			function toggleSign(e) {
				jQuery(e.target)
				.prev('.panel-heading')
				.find('i')
				.toggleClass('fa fa-chevron-down fa fa-chevron-up');
			}
			jQuery('#accordion').on('hidden.bs.collapse', toggleSign);
			jQuery('#accordion').on('shown.bs.collapse', toggleSign);

		});
	});
	
	//on load
	var profile_s_h = jQuery('input[name="profile_s_h"]:checked').val();

		if(profile_s_h == "true"){
			jQuery('.profile_setting').show();
		}
		if(profile_s_h == "false"){
			jQuery('.profile_setting').hide();
		}
		
	var profile_name = jQuery('input[name="profile_name"]:checked').val();

		if(profile_name == "true"){
			jQuery('.profile_name_setting').show();
		}
		if(profile_name == "false"){
			jQuery('.profile_name_setting').hide();
		}
	//flickr gallery type
	var flickr_gallery_type = jQuery('input[name="flickr_gallery_type"]:checked').val();

		if(flickr_gallery_type == "photostream"){
			jQuery('.photostream_gallery').show();
			jQuery('.album_gallery').hide();
		}
		if(flickr_gallery_type == "album"){
			jQuery('.photostream_gallery').hide();
			jQuery('.album_gallery').show();
		}

	//photostream title settings
	var photostream_title = jQuery('input[name="photostream_title"]:checked').val();

		if(photostream_title == "true"){
			jQuery('.photostream_title_settings').show();
		}
		if(photostream_title == "false"){
			jQuery('.photostream_title_settings').hide();
		}
		
	//album title settings
	var album_title = jQuery('input[name="album_title"]:checked').val();

		if(album_title == "true"){
			jQuery('.album_title_settings').show();
		}
		if(album_title == "false"){
			jQuery('.album_title_settings').hide();
		}
		
 	//lightbox settings Start
		var apply_light_box = jQuery('input[name="apply_light_box"]:checked').val();

			if(apply_light_box == "true"){
				jQuery('.lightbox_s_h').show();
			}
			if(apply_light_box == "false"){
				jQuery('.lightbox_s_h').hide();
			} 
			
		//Light & Fixed lightbox settings
			var fg_lightboxstyle = jQuery('input[name="fg_lightboxstyle"]:checked').val();

				if(fg_lightboxstyle == "fixed_lightbox"){
					jQuery('.lightbox_style_s_h').show();
					jQuery('.lightgallery_setting').show();
					jQuery('.lightcase_setting').hide();
				}
				if(fg_lightboxstyle == "light_lightbox"){
					jQuery('.lightbox_style_s_h').hide();
					jQuery('.lightgallery_setting').show();
					jQuery('.lightcase_setting').hide();
				} 
				if(fg_lightboxstyle == "lightcase_lightbox"){
					jQuery('.lightbox_style_s_h').hide();
					jQuery('.lightgallery_setting').hide();
					jQuery('.lightcase_setting').show();
				} 
	//lightbox settings End
			
		//gallery post title settings
		var fg_gallery_title = jQuery('input[name="fg_gallery_title"]:checked').val();

		if(fg_gallery_title == "true"){
			jQuery('.gallery_post_title').show();
		}
		if(fg_gallery_title == "false"){
			jQuery('.gallery_post_title').hide();
		}
		
		//light case title settings
			var lightcase_title = jQuery('input[name="lightcase_title"]:checked').val();

			if(lightcase_title == "true"){
				jQuery('.lightcase_title_settings').show();
			}
			if(lightcase_title == "false"){
				jQuery('.lightcase_title_settings').hide();
			}
		
	
//on change
	jQuery(document).ready(function() {
		jQuery('input[name="profile_s_h"]').change(function(){
			var profile_s_h = jQuery('input[name="profile_s_h"]:checked').val();
			if(profile_s_h == "true"){
				jQuery('.profile_setting').show();
			}
			if(profile_s_h == "false"){
				jQuery('.profile_setting').hide();
			}
		});
		jQuery('input[name="profile_name"]').change(function(){
			var profile_name = jQuery('input[name="profile_name"]:checked').val();
			if(profile_name == "true"){
				jQuery('.profile_name_setting').show();
			}
			if(profile_name == "false"){
				jQuery('.profile_name_setting').hide();
			}
		});
		//flickr gallery type
		jQuery('input[name="flickr_gallery_type"]').change(function(){
			var flickr_gallery_type = jQuery('input[name="flickr_gallery_type"]:checked').val();
			if(flickr_gallery_type == "photostream"){
				jQuery('.photostream_gallery').show();
				jQuery('.album_gallery').hide();
			}
			if(flickr_gallery_type == "album"){
				jQuery('.photostream_gallery').hide();
				jQuery('.album_gallery').show();
			}
		});
		//photostream title settings
		jQuery('input[name="photostream_title"]').change(function(){
			var photostream_title = jQuery('input[name="photostream_title"]:checked').val();
			if(photostream_title == "true"){
				jQuery('.photostream_title_settings').show();
			}
			if(photostream_title == "false"){
				jQuery('.photostream_title_settings').hide();
			}
		});
		
		//album title settings
		jQuery('input[name="album_title"]').change(function(){
			var album_title = jQuery('input[name="album_title"]:checked').val();
			if(album_title == "true"){
				jQuery('.album_title_settings').show();
			}
			if(album_title == "false"){
				jQuery('.album_title_settings').hide();
			}
		});
		
		//lightbox settings Start
			jQuery('input[name="apply_light_box"]').change(function(){
				var apply_light_box = jQuery('input[name="apply_light_box"]:checked').val();
				if(apply_light_box == "true"){
					jQuery('.lightbox_s_h').show();
				}
				if(apply_light_box == "false"){
					jQuery('.lightbox_s_h').hide();
				} 
			}); 
			//Light & Fixed lightbox settings
				jQuery('input[name="fg_lightboxstyle"]').change(function(){
					var fg_lightboxstyle = jQuery('input[name="fg_lightboxstyle"]:checked').val();
					if(fg_lightboxstyle == "fixed_lightbox"){
						jQuery('.lightbox_style_s_h').show();
						jQuery('.lightgallery_setting').show();
						jQuery('.lightcase_setting').hide();
					}
					if(fg_lightboxstyle == "light_lightbox"){
						jQuery('.lightbox_style_s_h').hide();
						jQuery('.lightgallery_setting').show();
						jQuery('.lightcase_setting').hide();
					} 
					if(fg_lightboxstyle == "lightcase_lightbox"){
						jQuery('.lightbox_style_s_h').hide();
						jQuery('.lightgallery_setting').hide();
						jQuery('.lightcase_setting').show();
					} 
				});
		//lightbox settings End
		
		//gallery post title settings
		jQuery('input[name="fg_gallery_title"]').change(function(){
			var fg_gallery_title = jQuery('input[name="fg_gallery_title"]:checked').val();
			if(fg_gallery_title == "true"){
				jQuery('.gallery_post_title').show();
			}
			if(fg_gallery_title == "false"){
				jQuery('.gallery_post_title').hide();
			}
		});
		
		//light case title settings
		jQuery('input[name="lightcase_title"]').change(function(){
			var lightcase_title = jQuery('input[name="lightcase_title"]:checked').val();
			if(lightcase_title == "true"){
				jQuery('.lightcase_title_settings').show();
			}
			if(lightcase_title == "false"){
				jQuery('.lightcase_title_settings').hide();
			}
		});
	});

	
//hover effect hide and show 
	var effect_type = jQuery('input[name="image_hover_effect_type"]:checked').val();
	if(effect_type == "no") {
		jQuery('.he_one').hide();
		jQuery('.he_two').hide();
	}
	
	if(effect_type == "2d") {
		jQuery('.he_one').show();
		jQuery('.he_two').hide();
	}
	
	if(effect_type == "sg") {
		jQuery('.he_one').hide();
		jQuery('.he_two').show();
	}
	
	//on change effect
	jQuery(document).ready(function() {
		// image hover effect hide show
		jQuery('input[name="image_hover_effect_type"]').change(function(){
			var effect_type = jQuery('input[name="image_hover_effect_type"]:checked').val();
			if(effect_type == "no") {
				jQuery('.he_one').hide();
				jQuery('.he_two').hide();
			
			}
			if(effect_type == "2d") {
				jQuery('.he_one').show();
				jQuery('.he_two').hide();
				
			}
			if(effect_type == "sg") {
				jQuery('.he_one').hide();
				jQuery('.he_two').show();
			}
		})
		
	});
		
// start pulse on page load
	function pulseEff() {
	   jQuery('#shortcode').fadeOut(600).fadeIn(600);
	};
	var Interval;
	Interval = setInterval(pulseEff,1500);

	// stop pulse
	function pulseOff() {
		clearInterval(Interval);
	}
	// start pulse
	function pulseStart() {
		Interval = setInterval(pulseEff,1500);
	}

	//color-picker
	(function( jQuery ) {
		jQuery(function() {
			// Add Color Picker 
			jQuery('#profile_color').wpColorPicker();
			jQuery('#photostream_titlecolor').wpColorPicker();
			jQuery('#photostream_titlebgcolor').wpColorPicker();
			jQuery('#album_titlecolor').wpColorPicker();
			jQuery('#album_titlebgcolor').wpColorPicker();
			jQuery('#tool_color').wpColorPicker();
			jQuery('#title_color').wpColorPicker();
			jQuery('#pg_next_btn_color').wpColorPicker();
			jQuery('#pg_next_btn_bgcolor').wpColorPicker();
			jQuery('#pg_prev_btn_color').wpColorPicker();
			jQuery('#pg_prev_btn_bgcolor').wpColorPicker();
			jQuery('#fg_gallery_titlecolor').wpColorPicker();
			jQuery('#profile_titlecolor').wpColorPicker();
			jQuery('#lightcase_color').wpColorPicker();
			jQuery('#lightcase_bg_icon_color').wpColorPicker();
			jQuery('#lightcase_title_color').wpColorPicker();
			jQuery('#lightcase_bg_color').wpColorPicker();
		});
	})( jQuery );
	jQuery(document).ajaxComplete(function() {
		jQuery('#profile_color','#photostream_titlecolor','#photostream_titlebgcolor','#album_titlecolor','#album_titlebgcolor','#tool_color','#title_color','#pg_next_btn_color','#pg_next_btn_bgcolor','#pg_prev_btn_color','#pg_prev_btn_bgcolor','#fg_gallery_titlecolor','#profile_titlecolor','#lightcase_color','#lightcase_bg_icon_color','#lightcase_title_color','#lightcase_bg_color').wpColorPicker();
	});	
	
	//range slider
	var rangeSlider = function(){
	  var slider = jQuery('.range-slider'),
		  range = jQuery('.range-slider__range'),
		  value = jQuery('.range-slider__value');
		
	  slider.each(function(){

		value.each(function(){
		  var value = jQuery(this).prev().attr('value');
		  jQuery(this).html(value);
		});

		range.on('input', function(){
		  jQuery(this).next(value).html(this.value);
		});
	  });
	};
	rangeSlider();	
</script>