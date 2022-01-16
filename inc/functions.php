<?php if ( ! defined( 'ABSPATH' ) ) {die( 'Invalid request.' ); }

require_once plugin_dir_path( __FILE__ ) . '../env.php';



function set_product_price($product_id){
	// Calculate Currency Rate
	$dollarValue = get_option('Ztools_exrate_dollar', getenv('DEFAULT_DOLLAR_EXCHANGE_RATE'));
	$yuanValue = get_option('Ztools_exrate_yuan', getenv('DEFAULT_YUAN_EXCHANGE_RATE'));
	$currency_type = get_post_meta($product_id , 'ztools_currency_type' , true);
	$currency_rate = floatval(get_post_meta($product_id , 'ztools_currency_input' , true));
	$currency_special_rate = floatval(get_post_meta($product_id , 'ztools_special_currency_input' , true));

	if ( $currency_type === '0' ) return;

	$product = wc_get_product( $product_id );
	switch ($currency_type){
		case 'dollar':
			$regular_price = $currency_rate * $dollarValue;
			$sale_price = ($currency_special_rate !== '')?  $currency_special_rate * $dollarValue : null;
			break;
		case 'yuan':
			$regular_price = $currency_rate * $yuanValue;
			$sale_price = ($currency_special_rate !== '')?  $currency_special_rate * $yuanValue : null;
			break;
		default:
			$regular_price = $currency_rate * $dollarValue;
			$sale_price = ($currency_special_rate !== '')?  $currency_special_rate * $dollarValue : null;
	}

	$product->set_regular_price( round($regular_price , -3 ) );
	if ($sale_price == '' || $sale_price == '0' || $sale_price == null) {
		$product->set_sale_price('');
	} else {
		$product->set_sale_price( round($sale_price , -3) );
	}
	$product->save();
}

function calc_currency_rate($currency_type){
	switch ($currency_type){
		case 'dollar':
			$currency_rate = get_option('Ztools_exrate_dollar', getenv('DEFAULT_DOLLAR_EXCHANGE_RATE'));
			break;
		case 'yuan':
			$currency_rate = get_option('Ztools_exrate_yuan', getenv('DEFAULT_YUAN_EXCHANGE_RATE'));
			break;
		default:
			$currency_rate = get_option('Ztools_exrate_dollar', getenv('DEFAULT_DOLLAR_EXCHANGE_RATE'));
	}
	return $currency_rate;
}

function update_all_woo_prices(){
	global $wpdb;
	$posts_table = $wpdb->prefix . 'posts';
	$AllProducts = $wpdb->get_results("SELECT * FROM $posts_table WHERE post_type='product' AND post_status='publish'");
	foreach ($AllProducts as $product){
		$is_rate_dependent = get_post_meta($product->ID , 'ztools_is_rate_dependent' , true);
		if ($is_rate_dependent === 'yes'){
			set_product_price($product->ID);
		}
	}
}





