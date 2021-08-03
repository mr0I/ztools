<?php if ( ! defined( 'ABSPATH' ) ) {die( 'Invalid request.' ); }


add_action('add_meta_boxes', function ($post_type,$post){
	$post_types = array('product');     //limit meta box to certain post types
	global $post;
	$product = wc_get_product( $post->ID );
	if ( in_array( $post_type, $post_types ) && ($product->get_type() == 'simple' ) ) {
		add_meta_box(
			'ztools_currency_rate',
			'اطلاعات دوره',
			function($post){include(ZTOOLS_ADMIN_DIR . 'metabox_echo.php');},
			$post_type,
			'advanced',
			'high'
		);
	}
}, 10, 2);


add_action('save_post', 'ztools_currency_rate_save');
add_action('save_edit', 'ztools_currency_rate_save');
function ztools_currency_rate_save( $post_id ){
	$currency_rate = floatval($_POST['ztools_metabox_currency_input']);

	//if ( !isset( $_POST['hmci_metabox_course_logo'] ) ) return;

	update_post_meta($post_id, '_ztools_currency_input',  $currency_rate );


	$product = wc_get_product( $post_id );
	$product->set_regular_price( $currency_rate );
	$product->set_sale_price( 300 );
	$product->save();

}





