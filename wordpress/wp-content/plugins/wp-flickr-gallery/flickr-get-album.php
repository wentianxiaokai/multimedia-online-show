<?php
// get album by id = https://www.flickr.com/services/api/flickr.photosets.getPhotos.html
$params = array(
	'api_key'		=> $flickr_api_key,
	'user_id'		=> $flickr_user_id,
	'photoset_id'	=> $flickr_album_id,
	'method'		=> $flickr_album_method,
	'per_page'		=> 200,
	'format'		=> 'php_serial',
	'extras'		=>	'date_taken, owner_name, icon_server, original_format, last_update, geo, tags, machine_tags, o_dims, views, media, path_alias, url_sq, url_q, url_t, url_s, url_n, url_m, url_z, url_c, url_l, url_o',
);
$encoded_params = array();
foreach ($params as $k => $v){
	$encoded_params[] = urlencode($k).'='.urlencode($v);
}


# call the API and decode the response
$url = "https://api.flickr.com/services/rest/?".implode('&', $encoded_params);
$rsp = file_get_contents($url);
$rsp_obj = unserialize($rsp);
$res = $rsp_obj['photoset']['photo'];

/* echo "<pre>";
print_r($rsp_obj);
echo "</pre>";
echo "<pre>";
print_r($res);
echo "</pre>"; */
?>
<div id="awp-flickr-album-<?php echo $flickr_gallery_id; ?>" class="awp-flickr-album-<?php echo $flickr_gallery_id; ?>">	
	<?php
	foreach($res as $sp) {
		//if($album_title == "true") {
			$album_title_fetch = $sp['title'];
		//}
		//thumbnail image size
		if($thumb_img_size == "url_sq") if(isset($sp['url_sq'])) $thumbnail_url = $sp['url_sq'];	//	Square 			- 75x75
		if($thumb_img_size == "url_q") if(isset($sp['url_q'])) $thumbnail_url = $sp['url_q'];		//	Large Square 	- 150x150
		if($thumb_img_size == "url_t") if(isset($sp['url_t'])) $thumbnail_url = $sp['url_t'];		//	Thumbnail 		- 100x75
		if($thumb_img_size == "url_s") if(isset($sp['url_s'])) $thumbnail_url = $sp['url_s'];		//	Small 			- 240x180
		
		//light box image size
		if($lightbox_img_size == "url_m") if(isset($sp['url_m'])) $lightboxl_url = $sp['url_m'];		//	Medium 			- 500x375
		if($lightbox_img_size == "url_l") if(isset($sp['url_l'])) $lightboxl_url = $sp['url_l'];		//	Large 			- 1024x768
		
		$image_type = 'image';
		
		if($apply_light_box == "true") {
			//with lightbox
			//light gallery
			if($image_type == "image" && $thumbnail_url && $lightboxl_url) { 
				echo "<a href='$lightboxl_url' class='img-responsive single-album-$flickr_gallery_id  $col_desktops animated $fg_animation_effect' data-sub-html='<h4 class=fg-titile-$flickr_gallery_id>$album_title_fetch</h4>' data-rel='lightcase-$flickr_gallery_id:myCollection:slideshow'>
					<img class='img-responsive photo loading' src='$thumbnail_url' alt='$album_title_fetch' width='auto' height='auto'>
				</a>";
			}
		} else {
			//without lightbox
			if($image_type == "image" && $thumbnail_url) {
				echo "<div class='img-responsive single-album-$flickr_gallery_id $col_desktops animated $fg_animation_effect'>
					<img class='img-responsive photo loading' src='$thumbnail_url' alt='$album_title_fetch' width='auto' height='auto'>
				</div>";
			}
		}
	}
	?>
</div>
<style>
.awp-flickr-album-<?php echo $flickr_gallery_id; ?> a {
	text-decoration: none !important;
	box-shadow: 0px 0px 0 0 currentColor !important;
	padding-bottom: 10px;
}
.awp-flickr-album-<?php echo $flickr_gallery_id; ?> div {
	padding-bottom: 10px;
}
</style>
<!--
<sizes canblog="1" canprint="1" candownload="1">
  <size label="Square" width="75" height="75" source="http://farm2.staticflickr.com/1103/567229075_2cf8456f01_s.jpg" url="http://www.flickr.com/photos/stewart/567229075/sizes/sq/" media="photo" />
  <size label="Large Square" width="150" height="150" source="http://farm2.staticflickr.com/1103/567229075_2cf8456f01_q.jpg" url="http://www.flickr.com/photos/stewart/567229075/sizes/q/" media="photo" />
  <size label="Thumbnail" width="100" height="75" source="http://farm2.staticflickr.com/1103/567229075_2cf8456f01_t.jpg" url="http://www.flickr.com/photos/stewart/567229075/sizes/t/" media="photo" />
  <size label="Small" width="240" height="180" source="http://farm2.staticflickr.com/1103/567229075_2cf8456f01_m.jpg" url="http://www.flickr.com/photos/stewart/567229075/sizes/s/" media="photo" />
  <size label="Small 320" width="320" height="240" source="http://farm2.staticflickr.com/1103/567229075_2cf8456f01_n.jpg" url="http://www.flickr.com/photos/stewart/567229075/sizes/n/" media="photo" />
  <size label="Medium" width="500" height="375" source="http://farm2.staticflickr.com/1103/567229075_2cf8456f01.jpg" url="http://www.flickr.com/photos/stewart/567229075/sizes/m/" media="photo" />
  <size label="Medium 640" width="640" height="480" source="http://farm2.staticflickr.com/1103/567229075_2cf8456f01_z.jpg?zz=1" url="http://www.flickr.com/photos/stewart/567229075/sizes/z/" media="photo" />
  <size label="Medium 800" width="800" height="600" source="http://farm2.staticflickr.com/1103/567229075_2cf8456f01_c.jpg" url="http://www.flickr.com/photos/stewart/567229075/sizes/c/" media="photo" />
  <size label="Large" width="1024" height="768" source="http://farm2.staticflickr.com/1103/567229075_2cf8456f01_b.jpg" url="http://www.flickr.com/photos/stewart/567229075/sizes/l/" media="photo" />
  <size label="Original" width="2400" height="1800" source="http://farm2.staticflickr.com/1103/567229075_6dc09dc6da_o.jpg" url="http://www.flickr.com/photos/stewart/567229075/sizes/o/" media="photo" />
</sizes>
-->
<script>
<?php if($apply_light_box == "true") { ?>
	//light case lightbox js
		jQuery( window ).load(function() {
			jQuery(document).ready(function(jQuery) {
				jQuery('a[data-rel^=lightcase-<?php echo $flickr_gallery_id; ?>]').lightcase({

				});
			});
		}); 	
	<?php }  ?>

// masonary effect
	jQuery(document).ready(function () {
		// isotope effect function
		// Method 1 - Initialize Isotope, then trigger layout after each image loads.
		var fg_isotope = jQuery('.awp-flickr-album-<?php echo $flickr_gallery_id; ?>').isotope({
			// options...
			itemSelector: '.single-album-<?php echo $flickr_gallery_id; ?>',
		});
		// layout Isotope after each image loads
		fg_isotope.imagesLoaded().progress( function() {
			fg_isotope.isotope('layout');
		});	
	});	
</script>