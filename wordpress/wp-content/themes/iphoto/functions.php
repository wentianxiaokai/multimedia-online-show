<?php
define('THEME_NAME','iphoto');

// LOCALIZATION
load_theme_textdomain( THEME_NAME,TEMPLATEPATH .'/languages');

// 自定义背景
add_custom_background();

// 添加 rss feed 
add_theme_support( 'automatic-feed-links' );

// 文章形式
add_theme_support( 'post-formats', array( 'video'));

// WP 菜单
if ( function_exists('register_nav_menus') ) {
	register_nav_menus(array('primary' => 'header'));
}

// 移除自动保存和修订版本
remove_action('pre_post_update', 'wp_save_post_revision' );
add_action( 'wp_print_scripts', 'disable_autosave' );
function disable_autosave() {
	wp_deregister_script('autosave');
}

// 边栏小工具
add_action( 'widgets_init', 'iphoto_widgets_init' );
function iphoto_widgets_init() {
	register_sidebar(array(
		'name' => __('Primary Widget Area','iphoto'),
		'id' => 'primary-widget-area',
		'description' => __('The primary widget area','iphoto'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2>',
		'after_title' => '</h2>'
	));
}

// 删除 post_meta likes
add_action('delete_post', 'delete_likes_fields');
function delete_likes_fields($post_ID) {
	global $wpdb;
	if(!wp_is_post_revision($post_ID)) {
		delete_post_meta($post_ID, 'likes');
	}
}

// 文章图片数量
function post_img_number(){
	global $post;
	$post_img = '';
	ob_start();
	ob_end_clean();
	$output = preg_match_all('/\<img.+?src="(.+?)".*?\/>/is',$post->post_content,$matches ,PREG_SET_ORDER);
	$cnt = count( $matches );
	return $cnt;
}

function create_recommend_meta_box() {
	if(function_exists('add_meta_box')) {
		add_meta_box( 'recommend-metabox', '推荐', 'recommend_meta_box', 'post', 'side', 'high' );
	}
}

function recommend_meta_box() {
	global $post;
	$checked = '';
	$meta_val = get_post_meta($post->ID, 'recommend', true);
	if($meta_val){
		$checked = 'checked';
	}
	$form =  '<div class="form-wrap">' . "\n";
	$form .= '<div class="form-field">' . "\n";
	$form .= '<label for="enable_recommend">' . "\n";
	$form .= '<input type="checkbox" name="enable_recommend" id="enable_recommend" style="width: auto;" ' . $checked . '>';
	$form .= ' 推荐本文美图</label>' . "\n" . '</div>' . "\n" . '</div>';
	echo $form;
}

function save_recommend_meta_box($post_ID) {
	if(!current_user_can( 'edit_post', $post_ID)) return $post_ID;
	if(isset($_POST['enable_recommend'])) {
		update_post_meta($post_ID, 'recommend', 1);
	}else{
		delete_post_meta($post_ID, 'recommend');
	}
}
add_action('admin_menu', 'create_recommend_meta_box');
add_action('save_post', 'save_recommend_meta_box');


// Ajax load posts
add_action('init', 'ajax_post');
function ajax_post(){
	if( isset($_GET['action'])&& $_GET['action'] == 'ajax_post'){
		$prePage = floor(get_option('posts_per_page')/4);
		if(isset($_GET['meta'])){
			if($_GET['meta'] == "recommend"){
				$args = array(
					'meta_key' => $_GET['meta'],
					'paged' => $_GET['pag'],
					'order' => DESC,
					'showposts' =>$prePage
				);
			}else{
				$args = array(
					'meta_key' => $_GET['meta'],
					'orderby'   => 'meta_value_num',
					'paged' => $_GET['pag'],
					'order' => DESC,
					'showposts' =>$prePage
				);
			}
		}else if(isset($_GET['cat'])){
			$args = array(
				'category_name' => $_GET['cat'],
				'paged' => $_GET['pag'],
				'showposts' =>$prePage
			);
		}else if(isset($_GET['tag'])){
			$args = array(
				'tag' => $_GET['tag'],
				'paged' => $_GET['pag'],
				'showposts' =>$prePage
			);
		}else if(isset($_GET['pag'])){
			$args = array(
				'paged' => $_GET['pag'],
				'showposts' =>$prePage
			);
		}
		query_posts($args);
		if(have_posts()){while (have_posts()):the_post();?>
			<?php get_template_part( 'content', get_post_format() );?>
		<?php endwhile;}else{die();}
		wp_reset_query();
		die();
	}else{return;}
}

function pagenavi( $p = 2 ) {
	if ( is_singular() ) return;
	global $wp_query,$paged;
	$paged = ($paged%4==0)? ($paged/4):(floor($paged/4) + 1);
	$max_page = ($wp_query->max_num_pages%4==0 )? ($wp_query->max_num_pages/4):(floor($wp_query->max_num_pages/4)+1);
	if ( empty( $paged ) ) $paged = 1;
	if ( $paged > 1 ) p_link( $paged - 1, '上一页', '上一页' );
	if ( $paged > $p + 1 ) p_link( 1, '最前页' );
	if ( $paged > $p + 2 ) echo '<span class="page-numbers">...</span>';
	for( $i = $paged - $p; $i <= $paged + $p; $i++ ) {
		if ( $i > 0 && $i <= $max_page ) $i == $paged ? print "<span class='page-numbers current' data-pre='4'>{$i}</span> " : p_link( $i );
	}
	if ( $paged < $max_page - $p - 1 ) echo '<span class="page-numbers">...</span>';
	if ( $paged < $max_page - $p ) p_link( $max_page, '最末页' );
	if ( $paged < $max_page ) p_link( $paged + 1,'下一页', '下一页' );
}
function p_link( $i, $title = '', $linktype = '' ) {
	if ( $title == '' ) $title = "第{$i}页";
	if ( $linktype == '' ) { $linktext = $i; } else { $linktext = $linktype; }
	echo "<a class='page-numbers' href='", esc_html( get_pagenum_link( $i ) ), "' title='{$title}'>{$linktext}</a> ";
}

//Theme comments lists
function iphoto_comment($comment,$args,$depth) {
$GLOBALS['comment'] = $comment;
;echo '	<li ';comment_class();;echo ' id="li-comment-';comment_ID() ;echo '" >
		<div id="comment-';comment_ID();;echo '" class="comment-body">
			<div class="commentmeta">';echo get_avatar( $comment->comment_author_email,$size = '48');;echo '</div>
				';if ($comment->comment_approved == '0') : ;echo '				<em>';_e('Your comment is awaiting moderation.') ;echo '</em><br />
				';endif;;echo '			<div class="commentmetadata">&nbsp;-&nbsp;';printf(__('%1$s %2$s'),get_comment_date('Y.n.d'),get_comment_time('G:i'));;echo '</div>
			<div class="reply">';comment_reply_link(array_merge( $args,array('depth'=>$depth,'max_depth'=>$args['max_depth'],'reply_text'=>__('Reply')))) ;echo '</div>
			<div class="vcard">';printf(__('%s'),get_comment_author_link()) ;echo '</div>
			';comment_text() ;echo '		</div>
';
}
add_action('admin_init', 'iphoto_init');
function iphoto_init() {
	if (isset($_GET['page']) && $_GET['page'] == 'functions.php') {
		$dir = get_bloginfo('template_directory');
		wp_enqueue_script('adminjquery', $dir . '/includes/admin.js', false, '1.0.0', false);
		wp_enqueue_style('admincss', $dir . '/includes/admin.css', false, '1.0.0', 'screen');
	}
}
?>