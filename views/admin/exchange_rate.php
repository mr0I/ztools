<?php if ( ! defined( 'ABSPATH' ) ) {die( 'Invalid request.' ); }


add_action( 'woocommerce_product_options_general_product_data', 'ztools_product_options');
function ztools_product_options(){

	echo '<div class="option_group">';

	woocommerce_wp_checkbox( array(
		'id'      => 'ztools_is_rate_dependent',
		'value'   => get_post_meta( get_the_ID(), 'ztools_is_rate_dependent', true ),
		'label'   => 'وابسته به نرخ ارز',
		'desc_tip' => true,
		'description' => 'آیا قیمت بر اساس نرخ ارز محاسبه شود',
	));

	echo '<div class="option_group_fields">';
	woocommerce_wp_select(array(
		'id' => 'ztools_currency_type',
		'label' => 'نوع ارز',
		'desc_tip' => false,
		'description' => '',
		'options' => array(
			'---' => '---',
			'dollar' => 'دلار',
			'yuan' => 'یوان'
		)
	));
	woocommerce_wp_text_input( array(
		'id'                => 'ztools_currency_input',
		'value'             => get_post_meta( get_the_ID(), 'ztools_currency_input', true ),
		'label'             => 'قیمت ارزی',
		'description'       => ''
	));
	woocommerce_wp_text_input( array(
		'id'                => 'ztools_special_currency_input',
		'value'             => get_post_meta( get_the_ID(), 'ztools_special_currency_input', true ),
		'label'             => 'قیمت فروش ویژه ارزی',
		'description'       => ''
	));

	echo '</div></div>';
}

add_action( 'woocommerce_process_product_meta', 'ztools_save_fields', 10, 2 );
function ztools_save_fields( $id, $post ){
	if( !empty( $_POST['ztools_is_rate_dependent'] ) ) {
		update_post_meta( $id, 'ztools_is_rate_dependent', $_POST['ztools_is_rate_dependent'] );
		update_post_meta( $id, 'ztools_currency_type', $_POST['ztools_currency_type'] );
		update_post_meta( $id, 'ztools_currency_input', $_POST['ztools_currency_input'] );
		update_post_meta( $id, 'ztools_special_currency_input', $_POST['ztools_special_currency_input'] );
	}
	else {
		delete_post_meta( $id, 'ztools_is_rate_dependent' );
		delete_post_meta( $id, 'ztools_currency_type' );
		delete_post_meta( $id, 'ztools_currency_input' );
		delete_post_meta( $id, 'ztools_special_currency_input' );
	}
}

add_action('save_post', 'ztools_currency_rate_save');
add_action('save_edit', 'ztools_currency_rate_save');
function ztools_currency_rate_save( $post_id ){

	if (get_post_type($post_id) !== 'product' || !get_post_meta($post_id , 'ztools_currency_type' , true)) return;

	$currency_type = get_post_meta($post_id , 'ztools_currency_type' , true);
	$currency_rate = floatval(get_post_meta($post_id , 'ztools_currency_input' , true));
	$currency_special_rate = floatval(get_post_meta($post_id , 'ztools_special_currency_input' , true));

	if ( $currency_type === '0' ) return;
	// Calculate Currency Rate
	$dollarValue = get_option('Ztools_exrate_dollar', '');
	$yuanValue = get_option('Ztools_exrate_yuan', '');

	$product = wc_get_product( $post_id );
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