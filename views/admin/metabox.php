<?php if ( ! defined( 'ABSPATH' ) ) {die( 'Invalid request.' ); }


add_action('add_meta_boxes', function ($post_type,$post){
	$post_types = array('product');     //limit meta box to certain post types
	global $post;
	$product = wc_get_product( $post->ID );
	if ( in_array( $post_type, $post_types ) && ($product->get_type() == 'simple' ) ) {
		add_meta_box(
			'ztools_currency_rate',
			'نرخ تبدیل ارز',
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
	$currency_type = $_POST['ztools_metabox_currency_type'];
	$currency_rate = floatval($_POST['ztools_metabox_currency_input']);
	$currency_special_rate = floatval($_POST['ztools_metabox_special_currency_input']);

	if ( $currency_type === '0' ) return;

	update_post_meta($post_id, '_ztools_currency_type',  sanitize_text_field($currency_type) );
	update_post_meta($post_id, '_ztools_currency_input',  sanitize_text_field($currency_rate) );
	update_post_meta($post_id, '_ztools_special_currency_input',  sanitize_text_field($currency_special_rate) );


	// Calculate Currency Rate
	$tomaanValue = get_option('Ztools_exrate_tomaan', '');
	$dollarValue = get_option('Ztools_exrate_dollar', '');
	$yuanValue = get_option('Ztools_exrate_yuan', '');



	$product = wc_get_product( $post_id );
	switch ($currency_type){
		case 'tomaan':
			$regular_price = $currency_rate * ($tomaanValue/$tomaanValue);
			$sale_price = ($currency_special_rate !== '')?  $currency_special_rate * ($tomaanValue/$tomaanValue) : null;
			break;
		case 'dollar':
			$regular_price = $currency_rate * ($tomaanValue/$dollarValue);
			$sale_price = ($currency_special_rate !== '')?  $currency_special_rate * ($tomaanValue/$dollarValue) : null;
			break;
		case 'yuan':
			$regular_price = $currency_rate * ($tomaanValue/$yuanValue);
			$sale_price = ($currency_special_rate !== '')?  $currency_special_rate * ($tomaanValue/$yuanValue) : null;
			break;
		default:
			$regular_price = $currency_rate * ($tomaanValue/$tomaanValue);
			$sale_price = ($currency_special_rate !== '')?  $currency_special_rate * ($tomaanValue/$tomaanValue) : null;
	}

	$product->set_regular_price( round($regular_price) );
	if ($sale_price == '' || $sale_price == '0' || $sale_price == null) {
		$product->set_sale_price('');
	} else {
		$product->set_sale_price( round($sale_price) );
	}
	$product->save();

}







