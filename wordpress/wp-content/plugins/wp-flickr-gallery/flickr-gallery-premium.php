<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
@package Flickr Photostream And Album Gallery Premium
Plugin Name: WP Flickr Gallery
Plugin URI: http://awplife.com/
Description: A Newly Amazing Different Most Powerful Responsive Easy To Use Flickr Gallery Plugin For WordPress
Version: 0.0.1
Author: A WP Life
Author URI: http://awplife.com/
Text Domain: FPAG_TXTDM
Domain Path: /languages
*/

if ( ! class_exists ( 'Awl_Flickr_Gallery' ) ) {
	
	class Awl_Flickr_Gallery {
		
		public function __construct() {
			$this->_constants();
			$this->_hooks();
		}
		
		protected function _constants() {
			
			//Plugin Version
			define( 'FG_PLUGIN_VER', '3.0' );
			
			//Plugin Text Domain
			define("FGP_TXTDM","Awl_Flickr_Gallery");
			
			//Plugin Name
			define( 'FG_PLUGIN_NAME', __( 'Flickr Gallery', FGP_TXTDM ) );
			
			//Plugin Slug
			define( 'FG_PLUGIN_SLUG', 'flickr_gallery');
			
			//Plugin Directory Path
			define( 'FG_PLUGIN_DIR', plugin_dir_path(__FILE__) );
			
			//Plugin Driectory URL
			define( 'FG_PLUGIN_URL', plugin_dir_url(__FILE__) );
			
			/**
			 * Create a key for the .htaccess secure download link.
			 * @uses    NONCE_KEY     Defined in the WP root config.php
			 */
			define( 'FGP_SECURE_KEY', md5( NONCE_KEY ) );
			
		} // end of constructor function
		
		/**
		 * Setup the default filters and actions
		 */
		protected function _hooks() {
			
			//Load Text Domain
			add_action( 'plugins_loaded', array( $this , '_load_textdomain' ) );
			
			//add gallery menu item, change menu filter for multisite
			add_action( 'admin_menu', array( $this, 'fg_gallery_menu' ), 101 );
			
			//Create Flicker Gallery Custom Post
			add_action( 'init', array( $this, '_Flickr_Gallery') );
			
			//Add Meta Box To Custom Post
			add_action( 'add_meta_boxes', array( $this, '_fg_admin_add_meta_box') );

			add_action( 'save_post', array( &$this, '_fg_save_post_settings') );
			
			//Shortcode Compatibility in Text Widegts
			add_filter( 'widget_text', 'do_shortcode');
			
			// 
			add_action( 'wp_ajax_api_settings_action', array( &$this, 'save_fg_api_setting') );
			
		} // end of hook function
		
		//saving Flickr gallery api setting
		public function save_fg_api_setting() {
			if ( check_ajax_referer( 'fg_api_setting_nonce_key', 'fg_api_security' ) ) {
				update_option( 'flickr_api_settings', base64_encode(serialize($_POST)));
			}
		}

		public function _load_textdomain() {
			load_plugin_textdomain( FGP_TXTDM, false, dirname( plugin_basename(__FILE__) ) .'/languages' );			
		}
		
		/* Add Gallery menu*/
		public function fg_gallery_menu() {
			$fg_api_setting_menu = add_submenu_page( 'edit.php?post_type='.FG_PLUGIN_SLUG, __( 'Flickr API Settings', 'FGP_TXTDM' ), __( 'Flickr API Settings', 'FGP_TXTDM' ), 'administrator', 'fg-api-settings', array( $this, '_fg_api_settings') );
			$fg_help_menu = add_submenu_page( 'edit.php?post_type='.FG_PLUGIN_SLUG, __( 'Docs', 'FGP_TXTDM' ), __( 'Docs', 'FGP_TXTDM' ), 'administrator', 'ag-doc-page', array( $this, '_fg_doc_page') );
			$fg_help_menu_premium = add_submenu_page( 'edit.php?post_type='.FG_PLUGIN_SLUG, __( 'Buy Premium Plugin', 'FGP_TXTDM' ), __( 'Buy Premium Plugin', 'FGP_TXTDM' ), 'administrator', 'ag-premium-page', array( $this, '_fg_premium_page') );
			$fg_help_menu_featured = add_submenu_page( 'edit.php?post_type='.FG_PLUGIN_SLUG, __( 'Featured Plugin', 'FGP_TXTDM' ), __( 'Featured Plugin', 'FGP_TXTDM' ), 'administrator', 'ag-featured-page', array( $this, '_fg_featured_page') );
		}
		
		/**
		 * Flicker Gallery Custom Post
		 * Create gallery post type in admin dashboard.
		*/
		public function _Flickr_Gallery() {
			$labels = array(
				'name'                => _x( 'Flickr Gallery', 'post type general name', FGP_TXTDM ),
				'singular_name'       => _x( 'Flickr Gallery', 'post type singular name', FGP_TXTDM ),
				'menu_name'           => __( 'Flickr Gallery', FGP_TXTDM ),
				'name_admin_bar'      => __( 'Flickr Gallery', FGP_TXTDM ),
				'parent_item_colon'   => __( 'Parent Item:', FGP_TXTDM ),
				'all_items'           => __( 'All Flickr Gallery', FGP_TXTDM ),
				'add_new_item'        => __( 'Add Flickr Gallery', FGP_TXTDM ),
				'add_new'             => __( 'Add Flickr Gallery', FGP_TXTDM ),
				'new_item'            => __( 'Flickr Gallery', FGP_TXTDM ),
				'edit_item'           => __( 'Edit Flickr Gallery', FGP_TXTDM ),
				'update_item'         => __( 'Update Flickr Gallery', FGP_TXTDM ),
				'search_items'        => __( 'Search Flickr Gallery', FGP_TXTDM ),
				'not_found'           => __( 'Flickr Gallery Not found', FGP_TXTDM ),
				'not_found_in_trash'  => __( 'Flickr Gallery Not found in Trash', FGP_TXTDM ),
			);

			$args = array(
				'label'               => __( 'Flickr Gallery', FGP_TXTDM ),
				'description'         => __( 'Custom Post Type For Flickr Gallery', FGP_TXTDM ),
				'labels'              => $labels,
				'supports'            => array( 'title'),
				'taxonomies'          => array(),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 65,
				'menu_icon'           => 'dashicons-images-alt',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,		
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
			);

			register_post_type( 'flickr_gallery', $args );
		}//end of post type function
		
		/**
		 * Adds Meta Boxes
		*/
		public function _fg_admin_add_meta_box() {
			// Syntax: add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );
			add_meta_box( '1', __('Flickr Gallery Shortcode', FGP_TXTDM), array(&$this, '_fg_shortcode_box'), 'flickr_gallery', 'side', 'default' );
			add_meta_box( '2', __('Flickr Settings', FGP_TXTDM), array(&$this, '_fg_post_settings'), 'flickr_gallery', 'normal', 'default' );
		}
		
		public function _fg_shortcode_box($post) {
			?>
			<p class="input-text-wrap">
				<p><?php _e('Copy & Embed shotcode into any Page/ Post / Text Widget to display your flickr gallery on your site.', FGP_TXTDM); ?><br></p>
				<input type="text" name="shortcode" id="shortcode" value="<?php echo "[FGAL id=".$post->ID."]"; ?>" readonly style="height: 60px; text-align: center; font-size: 18px;  border: 2px dashed;" onmouseover="return pulseOff();" onmouseout="return pulseStart();">
			</p>
			<?php
		}
		
		// displaying post settings
		public function _fg_post_settings($post) {
			wp_enqueue_script('jquery');
			wp_enqueue_script('media-upload');				
			wp_enqueue_style('awl-fg-bootstrap-css', FG_PLUGIN_URL . 'css/bootstrap.css');
			wp_enqueue_media();
			wp_enqueue_style('awl-fg-lightcase-css', FG_PLUGIN_URL.'css/lightcase.css' );
			wp_enqueue_script('awl-fg-lightcase-js', FG_PLUGIN_URL .'js/lightcase.js', array('jquery'), '' , true);
			require_once('flickr-post-settings.php');
		}
		
		public function _fg_save_post_settings($post_id) {
			if(isset($_POST['fg_save_nonce'])) {
				if ( !isset( $_POST['fg_save_nonce'] ) || !wp_verify_nonce( $_POST['fg_save_nonce'], 'fg_save_settings' ) ) {
				   print 'Sorry, your nonce did not verify.';
				   exit;
				} else {
					//print_r($_POST);
					//die;
					$awl_fg_post_settings = "awl_fg_post_settings_".$post_id;
					update_post_meta($post_id, $awl_fg_post_settings, base64_encode(serialize($_POST)));
				}
			}
		}// end save setting
		
		// displaying flickr api settings page
		public function _fg_api_settings() {
			require_once('flickr-api-settings.php');
		}
		
		public function _fg_doc_page() {
			require_once('flickr-docs.php');
		}
		
		public function _fg_premium_page() {
			require_once('buy-flickr-gallery-premium.php');
		}
		
		public function _fg_featured_page() {
			require_once('featured-plugins/featured-plugins.php');
		}
	}// end of class
	
	$fg_gallery_object = new Awl_Flickr_Gallery();
	require_once('shortcode.php');
}
?>
