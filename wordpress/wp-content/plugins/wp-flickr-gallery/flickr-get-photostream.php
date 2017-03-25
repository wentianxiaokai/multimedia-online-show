<?php
//get photo stream - https://www.flickr.com/services/api/flickr.people.getPhotos.html

// check if API Setting saved
if($flickr_user_id && $flickr_api_key) {
	$params = array(
		'api_key'		=>	$flickr_api_key,
		'user_id'		=>	$flickr_user_id,
		'method'		=>	$flickr_photostrem_method,
		'per_page'		=>	200,
		'format'		=>	'php_serial',
		'extras'		=>	'date_upload, date_taken, owner_name, icon_server, original_format, last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, url_sq, url_q, url_t, url_s, url_n, url_m, url_z, url_c, url_l, url_o',
	);

	$encoded_params = array();
	foreach ($params as $k => $v){
		$encoded_params[] = urlencode($k).'='.urlencode($v);
	}

	# call the API and decode the response
	$url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params);
	$rsp = file_get_contents($url);
	$rsp_obj = unserialize($rsp);
	$res = $rsp_obj['photos']['photo'];
	/*
	echo "<pre>";
	print_r($res);
	echo "</pre>";
	*/
	?>
	<div id="awp-flickr-photostream-<?php echo $flickr_gallery_id; ?>" class="awp-flickr-photostream-<?php echo $flickr_gallery_id; ?>">	
		<?php
		foreach($res as $sp) {
			$photostream_title_fetch = $sp['title'];
			//thumbnail image size
			if($thumb_img_size == "url_sq") if(isset($sp['url_sq'])) $thumbnail_url = $sp['url_sq'];	//	Square 			- 75x75
			if($thumb_img_size == "url_q") if(isset($sp['url_q'])) $thumbnail_url = $sp['url_q'];		//	Large Square 	- 150x150
			if($thumb_img_size == "url_t") if(isset($sp['url_t'])) $thumbnail_url = $sp['url_t'];		//	Thumbnail 		- 100x75
			if($thumb_img_size == "url_s") if(isset($sp['url_s'])) $thumbnail_url = $sp['url_s'];		//	Small 			- 240x180

			//light box image size
			if($lightbox_img_size == "url_m") if(isset($sp['url_m'])) $lightboxl_url = $sp['url_m'];		//	Medium 			- 500x375
			if($lightbox_img_size == "url_l") if(isset($sp['url_l'])) $lightboxl_url = $sp['url_l'];		//	Large 			- 1024x768
			
			/* if($thumbnail_url) {
				echo "<a href='#' class='single-photostream-$flickr_gallery_id $col_large_desktops $col_desktops $col_tablets $col_phones animated  $fg_animation_effect' style='box-shadow: none;'><img class='photo loading' src='$thumbnail_url' width='auto' height='auto'></a>";
			}
			if($lightboxl_url) {
				//echo "Orignal - ".$count."<br>";
				//echo "<img class='photo' src='$lightboxl_url' width='auto' height='auto'>";
			} */
			
			$image_type = 'image';

			
			if($apply_light_box == "true") {
				//with lightbox
				//light gallery
				if($image_type == "image" && $thumbnail_url && $lightboxl_url) {
					echo "<a href='$lightboxl_url' class='img-responsive single-photostream-$flickr_gallery_id $col_desktops animated $fg_animation_effect' data-sub-html='<h4 class=fg-titile-$flickr_gallery_id>$photostream_title_fetch</h4>' data-rel='lightcase-$flickr_gallery_id:myCollection:slideshow'>
						<img class='img-responsive photo loading' src='$thumbnail_url' alt='$photostream_title_fetch' width='auto' height='auto'>
					</a>";
				}
			} else {
				//without lightbox
				if($image_type == "image" && $thumbnail_url) {
					echo "<div class='img-responsive single-photostream-$flickr_gallery_id $col_desktops animated $fg_animation_effect'>
						<img class='img-responsive photo loading' src='$thumbnail_url' alt='$photostream_title_fetch' width='auto' height='auto'>
					</div>";
				}
			}
		}
		?>
	</div>
	<?php
} else {
	echo "<p class='alert'>Error! Check your Flickr API Settings. May be API Key or User ID incorrect or empty.</p>";
}
?>
<script>
<?php if($apply_light_box == "true") { ?>
		jQuery( window ).load(function() {
			jQuery(document).ready(function(jQuery) {
				jQuery('a[data-rel^=lightcase-<?php echo $flickr_gallery_id; ?>]').lightcase({

				});
			});
		}); 
<?php } ?>

// masonry effect
jQuery(document).ready(function () {
	// isotope effect function
	// Method 1 - Initialize Isotope, then trigger layout after each image loads.
	var fg_isotope = jQuery('#awp-flickr-photostream-<?php echo $flickr_gallery_id; ?>').isotope({
		// options
		itemSelector: '.single-photostream-<?php echo $flickr_gallery_id; ?>',
	});
	// layout Isotope after each image loads
	fg_isotope.imagesLoaded().progress( function() {
		fg_isotope.isotope('layout');
	});	
});
</script>