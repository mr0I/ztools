<?php

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

define('ZEUS_INC', plugin_dir_path(__FILE__) . 'inc/');

if(is_admin()){
	include(plugin_dir_path( __FILE__ ).'views/admin/admin_process.php');
	include(plugin_dir_path( __FILE__ ).'views/admin/ajax_requests.php');
}

require_once ZEUS_INC . 'functions.php';



function ZtoolsRegisterHotLinkPostType()
{
	$labels = array(
		'name'               => __('Planet', 'ztools'),
		'singular_name'      => __('link', 'ztools'),
		'add_new'            => __('add link' , 'ztools'),
		'add_new_item'       => __('add link' , 'ztools'),
		'new_item'           => __('new link', 'ztools'),
		'edit_item'          => __('edit link', 'ztools'),
		'view_item'          => __('show link', 'ztools'),
		'all_items'          => __('all links', 'ztools'),
		'search_items'       => __('search link', 'ztools'),
		//'parent_item_colon'  => ,
		'not_found'          => __('links not found', 'ztools'),
		'not_found_in_trash' => __('not link found in trash', 'ztools')
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'planet' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 5,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
		'menu_icon'			 => 'dashicons-admin-site-alt',
	);

	register_post_type( 'zplanet', $args );
}

function Ztools_register_taxonomies()
{
	// Add new taxonomy, make it hierarchical (like categories)
	$labels = array(
		'name'              => __('Planet Category', 'ztools'),
		'singular_name'     => __('category', 'ztools'),
		'menu_name'         => __('Planet Category', 'ztools'),
		'all_items'         => __('all category', 'ztools'),
		'edit_item'         => __('edit category', 'ztools'),
		'view_item'			=> __('view category', 'ztools'),
		'update_item'       => __('update category', 'ztools'),
		'add_new_item'      => __('add new category', 'ztools'),
		'new_item_name'     => __('new category name', 'ztools'),
		'parent_item'       => __('parent', 'ztools'),
		'parent_item_colon' => __('parent:', 'ztools'),
		'search_items'      => __('search', 'ztools'),
	);

	$args = array(
		'labels'             => $labels,
		'public'  			 => true,
		'publicly_queryable' => true,
		'hierarchical'       => true,
		'show_ui'            => true,
		'show_in_menu'		 => true,
		'show_in_nav_menus'	 => true,
		'show_tagcloud'		 => true,
		'show_in_quick_edit' => true,
		'show_admin_column'  => true,
		'capabilities'		 => array(
			'manage_terms'  	=> 'manage_categories',
			'edit_terms'  		=> 'manage_categories',
			'delete_terms'  	=> 'manage_categories',
			'assign_terms'  	=> 'edit_posts',
		),
		'rewrite'           => array( 'slug' => 'planet-category' ),
	);

	register_taxonomy( 'zcategory', array( 'zplanet' ), $args );


	// Add new taxonomy, NOT hierarchical (like tags)
	$labels = array(
		'name'                       => __('tags', 'ztools'),
		'singular_name'              => __('tag', 'ztools'),
		'menu_name'                  => __('Planet Tag', 'ztools'),
		'all_items'                  => __('all tag', 'ztools'),
		'edit_item'                  => __('edit tag', 'ztools'),
		'view_item'					 => __('view tag', 'ztools'),
		'update_item'                => __('update tag', 'ztools'),
		'add_new_item'               => __('add new tag', 'ztools'),
		'new_item_name'              => __('new tag name', 'ztools'),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'search_items'               => __('search tag', 'ztools'),
		'popular_items'              => __('popular tag', 'ztools'),
		'separate_items_with_commas' => __('popular tag', 'ztools'),
		'add_or_remove_items'        => __('separate tag with commas', 'ztools'),
		'choose_from_most_used'      => __('choose from most used', 'ztools'),
		'not_found'                  => __('not found', 'ztools'),

	);

	$args = array(
		'labels'                  => $labels,
		'hierarchical'            => false,
		'public'         		  => true,
		'rewrite'             	  => array( 'slug' => 'planet-tag' ),
	);

	register_taxonomy( 'ztags', 'zplanet', $args );

}

function ZtoolsRegisterHotLinkMetaBox()
{
	add_meta_box('zplanet-metabox', __('URL', 'ztools'), 'ZtoolsPlanetUrlMetabox','zplanet','side','high');
}

function ZtoolsPlanetUrlMetabox($post)
{
	wp_nonce_field(basename(__FILE__), "zplanet-url");
	?>
    <div>
        <input name="zplanet-link" type="url" value="<?php echo get_post_meta($post->ID, "zplanet-link", true); ?>">
        </br> <label for="zplanet-link"><?php echo __('like:','lb_like');?> https://example.com</label>
    </div>
	<?php
}

function ZtoolsSavePostMeta($post_id, $post, $update)
{

	if (!isset($_POST["zplanet-url"]) || !wp_verify_nonce($_POST["zplanet-url"], basename(__FILE__)))
		return $post_id;

	if(!current_user_can("edit_post", $post_id))
		return $post_id;

	if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
		return $post_id;

	$slug = "zplanet";
	if($slug != $post->post_type)
		return $post_id;


	$meta_box_link_value = '';
	if(isset($_POST["zplanet-link"]))
	{
		$meta_box_link_value = esc_url_raw( $_POST["zplanet-link"] );
	}
	update_post_meta($post_id, "zplanet-link", $meta_box_link_value);
}

function  Ztools_Activation()
{
	/*Activation Ztools Plugin*/
	Ztools_HotLinkPostType();
	flush_rewrite_rules();


	$role = get_role( 'subscriber' ); //The role you want to grant the capability
	$role->add_cap( 'upload_files' );
}

function  Ztools_Deactivation()
{
	/*Activation Ztools Plugin*/
	flush_rewrite_rules();
}

function Ztools_setup_menu()
{
	add_menu_page('Zeus Tools', 'Zeus Tools', 'manage_options', __FILE__, 'Ztools_Dashboard' ,'dashicons-smiley'/*plugins_url('/img/icon.svg',__FILE__)*/);
	add_submenu_page(
		__FILE__,          // top level menu page
		'Ztools About',   // title of the settings page
		__('About' , 'ztools'),          // title of the submenu
		'manage_options',  // capability of the user to see this page
		__FILE__.'/About',// slug of the settings page
		'Ztools_About'    // callback function when rendering the page
	);
	add_submenu_page(
		__FILE__,          // top level menu page
		'Ztools Settings',   // title of the settings page
		__('Settings' , 'ztools'),  // title of the submenu
		'manage_options',  // capability of the user to see this page
		'ztools',// slug of the settings page
		'Ztools_Settings'    // callback function when rendering the page
	);
}

function Ztools_Dashboard()
{
	echo "<h1><center>zeus tools add Planet future</center></h1>";
}
function Ztools_About()
{
	echo "<h1><center>My Name Is Zeus</center></h1>";
}
function Ztools_Settings()
{
	?>
    <div class="wrap">
		<?php if( !isset($_GET['tab']) ) $_GET['tab'] = 'main_page';?>
        <h2 class="nav-tab-wrapper">
            <a href="?page=ztools&tab=main_page" class="nav-tab<?php if( $_GET['tab'] == 'main_page'){echo ' nav-tab-active';};?>"><?php echo __('Main Page Settings', 'ztools'); ?></a>
            <a href="?page=ztools&tab=exchange_rate_page" class="nav-tab<?php if( $_GET['tab'] == 'exchange_rate_page'){echo ' nav-tab-active';};?>"><?php echo __('Exchange Rate Settings', 'ztools'); ?></a>
        </h2>

		<?php settings_errors();?>
        <form method="post" action="options.php">
			<?php
			if ( $_GET['tab'] == 'main_page' ){
				settings_fields("Ztools_settings_options");
				do_settings_sections("Ztools_setting");
			} else if ( $_GET['tab'] == 'exchange_rate_page' ) {
				settings_fields('exchange_rate_settings_options');
				do_settings_sections('exchange_rate_settings');

				// Update All Products Prices
				update_all_woo_prices();
			}
			submit_button();
			?>
        </form>
    </div>
	<?php
}


add_action('init', 'ZtoolsRegisterHotLinkPostType');
add_action('init', 'Ztools_register_taxonomies', 0 );
add_action('admin_menu', 'Ztools_setup_menu');
add_action('add_meta_boxes','ZtoolsRegisterHotLinkMetaBox');
add_action("save_post", "ZtoolsSavePostMeta",10,3);
register_activation_hook(__FILE__, 'Ztools_Activation');
register_deactivation_hook(__FILE__, 'Ztools_Deactivation');


// Add Post from frontend //
add_action('init', function(){
	add_shortcode('show_planet_form', 'planet_form');
	add_shortcode('edit_planet_form', 'planet_edit_form');
	add_shortcode('show_planet_posts', 'planet_posts');
});


function planet_form($attr , $content){
	include(plugin_dir_path( __FILE__ ).'views/Add_planet.php');
}
function planet_edit_form($attr , $content){
	include(plugin_dir_path( __FILE__ ).'views/Edit_planet.php');
}
function planet_posts($attr , $content = null){
	include(plugin_dir_path( __FILE__ ).'views/Show_planets.php');
}


add_filter( 'wp_dropdown_cats', 'dropdown_filter', 10, 2);
function dropdown_filter( $output, $r ) {
	//$output = preg_replace( '/<select (.*?) >/', '<select $1 size="3" multiple >', $output);
	$output = preg_replace( '/<select (.*?) >/', '<select $1 >', $output);
	return $output;
}


// Ajax Requests
function submit_planet_frm_callback(){
	check_ajax_referer( '(H+MbPeShVmYq3t6', 'security' );

	if ( wp_verify_nonce($_POST['nonce'], 'planet-nonce'))
	{
		global $planet_err;
		$planet_err = array();

		if ($_POST["post_title"] == '') {
			$planet_err['title'] = 'عنوان مطلب را وارد کنید.';
		}
		if ( $_POST["content_len"] == 0) {
			$planet_err['content'] = 'توضیحات مطلب را وارد کنید!';
		}
		if ( $_POST["content_len"] > $_POST["max_editor_chars"]) {
			$planet_err['contentLength'] = 'طول متن وارد شده بیشتر از حد مجاز است!';
		}
		if( $_POST['post_url'] != '' && ! filter_var($_POST['post_url'], FILTER_VALIDATE_URL)){
			$planet_err['url'] = 'لینک وارد شده معتبر نیست.';
		}

		if(empty($planet_err)){
			$tags = sanitize_text_field($_POST['post_tags']);
			$post_id = wp_insert_post(array (
				'post_type' => 'zplanet',
				'taxonomy' => 'zcategory,ztags',
				'post_title' => sanitize_text_field( $_POST["post_title"] ),
				'post_content' =>  $_POST["post_content"] ,
				'tags_input' => array($tags),
				'post_category' =>  sanitize_text_field($_POST['post_cats']),
				'post_status' => 'pending'
			));

			wp_set_post_terms( $post_id, $_POST['post_tags'], 'ztags', true );
			wp_set_post_terms( $post_id, $_POST['post_cats'], 'zcategory', false );

			if($post_id){
				$meta_box_link_value = '';
				if(isset($_POST["post_url"]))
				{
					$meta_box_link_value = esc_url_raw( $_POST["post_url"] );
				}
				update_post_meta($post_id, "zplanet-link", $meta_box_link_value);
				add_post_meta( $post_id, 'likes_count', 0 , false );

				$data=array( 'res' => 1);
				echo json_encode($data);
				exit();
			}
		}else{
			$data=array( 'res' => 0, 'err' => $planet_err);
			echo json_encode($data);
			exit();
		}
	}else{
		$data=array( 'res' => 2 );
		echo json_encode($data);
		exit();
	}
}
add_action( 'wp_ajax_submit_planet_frm', 'submit_planet_frm_callback' );
add_action( 'wp_ajax_nopriv_submit_planet_frm', 'submit_planet_frm_callback' );


function edit_planet_frm_callback(){
	check_ajax_referer( '(H+MbPeShVmYq3t6', 'security' );

	if ( wp_verify_nonce($_POST['nonce'], 'planet-nonce'))
	{
		global $planet_err;
		$planet_err = array();

		if ($_POST["post_title"] == '') {
			$planet_err['title'] = 'عنوان مطلب را وارد کنید.';
		}
		if ( $_POST["content_len"] == 0) {
			$planet_err['content'] = 'توضیحات مطلب را وارد کنید!';
		}
		if ( $_POST["content_len"] > $_POST["max_editor_chars"]) {
			$planet_err['contentLength'] = 'طول متن وارد شده بیشتر از حد مجاز است!';
		}
		if( $_POST['post_url'] != '' && ! filter_var($_POST['post_url'], FILTER_VALIDATE_URL)){
			$planet_err['url'] = 'لینک وارد شده معتبر نیست.';
		}

		if(empty($planet_err)){
			$tags = sanitize_text_field($_POST['post_tags']);
			$post_id = wp_update_post(array (
				'post_type' => 'zplanet',
				'ID' => $_POST['post_id'],
				'taxonomy' => 'zcategory,ztags',
				'post_title' => sanitize_text_field( $_POST["post_title"] ),
				'post_content' =>  $_POST["post_content"] ,
				'tags_input' => array($tags),
				'post_category' =>  sanitize_text_field($_POST['post_cats']),
				'post_status' => 'pending'
			));

			wp_set_post_terms( $post_id, $_POST['post_tags'], 'ztags', false );
			wp_set_post_terms( $post_id, $_POST['post_cats'], 'zcategory', false );

			if($post_id){
				$meta_box_link_value = '';
				if(isset($_POST["post_url"]))
				{
					$meta_box_link_value = esc_url_raw( $_POST["post_url"] );
				}
				update_post_meta($post_id, "zplanet-link", $meta_box_link_value);
				$data=array( 'res' => 1);
				echo json_encode($data);
				exit();
			}
		}else{
			$data=array( 'res' => 0, 'err' => $planet_err);
			echo json_encode($data);
			exit();
		}
	}else{
		$data=array( 'res' => 2 );
		echo json_encode($data);
		exit();
	}
}
add_action( 'wp_ajax_edit_planet_frm', 'edit_planet_frm_callback' );
add_action( 'wp_ajax_nopriv_edit_planet_frm', 'edit_planet_frm_callback' );


function planet_remove_callback(){
	check_ajax_referer( '(H+MbPeShVmYq3t6', 'security' );

	$result = wp_delete_post( $_POST['post_id'] , false);
	if($result){
		$data=array( 'res' => 1);
		echo json_encode($data);
		exit();
	}else{
		$data=array( 'res' => 0);
		echo json_encode($data);
		exit();
	}
}
add_action( 'wp_ajax_planet_remove', 'planet_remove_callback' );
add_action( 'wp_ajax_nopriv_planet_remove', 'planet_remove_callback' );


function edit_planet_callback(){
	$data=array( 'res' => 1);
	echo json_encode($data);
	exit();
}
add_action( 'wp_ajax_edit_planet', 'edit_planet_callback' );
add_action( 'wp_ajax_nopriv_edit_planet', 'edit_planet_callback' );



// Add Widget
class Ztools_last_planets extends WP_Widget{

	function __construct() {
		parent::__construct(
			'ztools_last_planets_widget',
			__('Last Planets', 'ztools'),
			array(
				'description'   => __('Show last planet posts by ...', 'ztools'),
				'classname'     => 'ztools_last_planets_form_class'
			)
		);

	}


	function form($instance){
		$title = (!isset($instance['title']) || $instance['title'] == '') ? __('Last Planets', 'ztools') : $instance['title'] ;
		$order_by = (!isset($instance['order_by']) || $instance['order_by'] == '') ? 'date' : $instance['order_by'] ;
		$order = (!isset($instance['order']) || $instance['order'] == '') ? 'asc' : $instance['order'] ;
		$count = (!isset($instance['count']) || $instance['count'] == '') ? 5 : $instance['count'] ;
		?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('title', 'ztools')?></label>
            <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title');?>"
                   value="<?php echo esc_attr($title);?>" class="widefat"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('order_by');?>"><?php echo __('Sorting By', 'ztools')?></label>
            <select id="<?php echo $this->get_field_id('order_by');?>" value="<?php echo esc_attr($order_by);?>"
                    name="<?php echo $this->get_field_name('order_by');?>">
                <option value="date" <?php selected($order_by, 'date');?>><?php echo __('Date', 'ztools')?></option>
                <option value="likes_count" <?php selected($order_by, 'likes_count');?>><?php echo __('Likes Count', 'ztools')?></option>
                <option value="comment_count" <?php selected($order_by, 'comment_count');?>><?php echo __('Comments Count', 'ztools')?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('order');?>_asc"><?php echo __('Ascending', 'ztools')?></label>
            <input type="radio" name="<?php echo $this->get_field_name('order');?>" value="asc" id="<?php echo $this->get_field_id('order');?>_asc" <?php checked($order, 'asc');?> />
            <label for="<?php echo $this->get_field_id('order');?>_desc"><?php echo __('Descending', 'ztools')?></label>
            <input type="radio" name="<?php echo $this->get_field_name('order');?>" value="desc" id="<?php echo $this->get_field_id('order');?>_desc" <?php checked($order, 'desc');?> />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>"><?php echo __('Count', 'ztools')?></label>
            <input type="number" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count');?>"
                   value="<?php echo esc_attr($count);?>" class="widefat"/>
        </p>
		<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance[ 'order_by' ] = in_array($new_instance[ 'order_by' ], array('date', 'likes_count', 'comment_count')) ? $new_instance[ 'order_by' ] : 'date';
		$instance[ 'order' ] = in_array($new_instance[ 'order' ], array('desc', 'asc')) ? $new_instance[ 'order' ] : 'desc';
		$instance['count'] = strip_tags($new_instance['count']);
		return $instance;
	}

	function widget($args, $instance) {
		global $before_widget;
		global $before_title;
		global $after_title;
		global $after_widget;

		$title = (!isset($instance['title']) || $instance['title'] == '') ? __('Last Planets', 'ztools') : $instance['title'] ;
		$order_by = (!isset($instance['order_by']) || $instance['order_by'] == '') ? 'date' : $instance['order_by'] ;
		$order = (!isset($instance['order']) || $instance['order'] == '') ? 'desc' : $instance['order'] ;
		$count = (!isset($instance['count']) || $instance['count'] == '') ? 5 : $instance['count'] ;
		extract($args);
		echo $before_widget . $before_title . $title . $after_title;

		if ($order_by === 'likes_count'){
			$args = array(
				'post_type' => 'zplanet' ,
				'order' => $order ,
				'meta_key' => 'likes_count',
				'orderby'   => 'meta_value', //or 'meta_value_num'
				'posts_per_page' => $count
			);
		}else{
			$args = array(
				'post_type' => 'zplanet' ,
				'orderby' =>  $order_by,
				'order' => $order ,
				'posts_per_page' => $count
				//			'fields'       => array('display_name', 'user_email', 'ID'),
			);
		}

		$planets = new WP_Query($args);
		?>
        <ul class="woodmart-recent-posts-list">
		<?php if ( $planets->have_posts() ) : ?>
			<?php while ( $planets->have_posts() ) : $planets->the_post(); ?>
				<?php
				global $post;
				?>
                <li>
					<?php
					if ( !has_post_thumbnail() ) {
						?>
                        <a class="recent-posts-thumbnail" href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
                            <img class="attachment-large wp-post-image " src="<?php echo get_stylesheet_directory_uri().'/images/image_not_found.jpg'; ?>"
                                 width="90" height="60" alt="<?php the_title( '', '', true ); ?>" title="<?php the_title( '', '', true ); ?>">
                        </a>
						<?php
					}else{
						?>
                        <a class="recent-posts-thumbnail" href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
                            <img class="attachment-large wp-post-image " src="<?php echo get_the_post_thumbnail_url($post->ID , 'thumbnail') ?>"
                                 width="90" height="60" alt="<?php the_title( '', '', true ); ?>" title="<?php the_title( '', '', true ); ?>">
                        </a>
						<?php
					}
					?>
                    <div class="recent-posts-info">
                        <h5 class="entry-title">
                            <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php the_title( '', '', true ); ?>" rel="bookmark">
								<?php the_title( '', '', true ); ?>
                            </a>
                        </h5>
                        <time class="recent-posts-time" datetime=""><?php echo get_the_date( '' ); ?></time>
                        <a class="recent-posts-comment" href="<?php esc_url(the_permalink()); ?>#comments">
							<?php echo get_comments_number(); ?> <?php echo __('Comments', 'ztools'); ?></a>
						<?php
						$likes = get_post_meta( $post->ID, 'likes_count', false );
						?>
                        <a class="recent-posts-comment" ><?php echo $likes[0]; ?> <?php echo __('Likes', 'ztools'); ?></a>
                    </div>
                </li>
			<?php endwhile; ?>
            </ul>
			<?php wp_reset_postdata(); ?>
		<?php else: ?>
            <div class="container">
            </div>
		<?php endif; ?>
		<?php

		echo $after_widget;
	}

}
add_action('widgets_init', function(){
	register_widget('Ztools_last_planets');
});

class Ztools_planets_Categories extends WP_Widget{

	function __construct() {
		parent::__construct(
			'Ztools_planets_Categories_widget',
			__('Planets Categories', 'ztools'),
			array(
				'description'   => __('Show Planet Categories.', 'ztools'),
				'classname'     => 'ztools_planets_Categories_class'
			)
		);

	}

	function form($instance){
		$title = (!isset($instance['title']) || $instance['title'] == '') ? __('Planets Categories', 'ztools') : $instance['title'] ;
		?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('title', 'ztools')?></label>
            <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title');?>"
                   value="<?php echo esc_attr($title);?>" class="widefat"/>
        </p>
		<?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

	function widget($args, $instance) {
		global $before_widget;
		global $before_title;
		global $after_title;
		global $after_widget;

		$title = (!isset($instance['title']) || $instance['title'] == '') ? __('Last Planets', 'ztools') : $instance['title'] ;
		extract($args);
		echo $before_widget . $before_title . $title . $after_title;

		$args = array(
			'taxonomy' => 'zcategory',
			'orderby' => 'name',
			'order'   => 'ASC',
			'hide_empty'  => 0
		);
		$cats = get_categories($args);
		?>
        <ul>
			<?php
			foreach($cats as $cat){
				?>
                <li class="cat-item cat-item-8337">
                    <a href="<?php echo 'https://sisoog.com/planet-category/'.$cat->slug; ?>"><?php echo $cat->name; ?></a>
                    <!--                    <a href="--><?php //echo 'http://192.168.30.99/wordpress/planet-category/'.$cat->slug; ?><!--">--><?php //echo $cat->name; ?><!--</a>-->
                </li>
				<?php
			}
			?>
        </ul>
		<?php

		echo $after_widget;
	}

}
add_action('widgets_init', function(){
	register_widget('Ztools_planets_Categories');
});
// Add Widget


/* Add Rewrite rules */
add_action('init', function (){
	add_rewrite_rule(
		'@([^/]+)/?$',
		'index.php?pagename=author_search&s=$matches[1]',
		'top'
	);
});
add_action('template_redirect', function(){
	$pageName = get_query_var('pagename');

	if ( $pageName == 'author_search' ) {
		$author_name = get_query_var('s');
		include(plugin_dir_path(__FILE__) . 'views/author_posts.php');
		exit;
	}
});
/* Add Rewrite rules */


/* Add Exchange Rate to woocommerce forms */
define('ZTOOLS_ADMIN_DIR', plugin_dir_path(__FILE__) . 'views/admin/');
add_action('init','your_function');
function your_function(){
	$current_user = wp_get_current_user();
	if (in_array('administrator' , $current_user->roles) || in_array('editor' , $current_user->roles)){
		require_once ZTOOLS_ADMIN_DIR .'exchange_rate.php';
	}
}
/* Add Exchange Rate to woocommerce forms */




