<?php if ( ! defined( 'ABSPATH' ) ) {die( 'Invalid request.' ); }

require_once ZEUS_INC . 'functions.php';


function getCurrencyRate_callback(){

	$currency_type = $_POST['currencyType'];
	$regular_price_input = $_POST['regularPrice'];
	$sale_price_input = $_POST['salePrice'];

	$currency_rate = 0;
	$currency_rate = calc_currency_rate($currency_type);

	$regular_price = round($regular_price_input * $currency_rate , -3);
	$sale_price = round($sale_price_input * $currency_rate , -3);

	if ($currency_rate !== 0){
		$result['result'] = 'Done';
		$result['currency_rate'] = $currency_rate;
		$result['regular_price'] = $regular_price;
		$result['sale_price'] = $sale_price;
		wp_send_json( $result );
		exit();
	} else {
		$result['result'] = 'Error';
		$result['msg'] = 'error in editing data';
		wp_send_json( $result );
		exit();
	}
}
add_action( 'wp_ajax_getCurrencyRate', 'getCurrencyRate_callback' );
add_action( 'wp_ajax_nopriv_getCurrencyRate', 'getCurrencyRate_callback' );


function getRegularPrice_callback(){

	$currency_type = $_POST['currencyType'];
	$currency_input = $_POST['currencyInput'];

	$currency_rate = 0;
	$currency_rate = calc_currency_rate($currency_type);

	$regular_price = $currency_input * $currency_rate;
	if ($currency_rate !== 0){
		$result['result'] = 'Done';
		$result['regular_price'] = round($regular_price , -3);
		wp_send_json( $result );
		exit();
	} else {
		$result['result'] = 'Error';
		$result['msg'] = 'error in editing data';
		wp_send_json( $result );
		exit();
	}
}
add_action( 'wp_ajax_getRegularPrice', 'getRegularPrice_callback' );
add_action( 'wp_ajax_nopriv_getRegularPrice', 'getRegularPrice_callback' );


function getSalePrice_callback(){

	$currency_type = $_POST['currencyType'];
	$currency_input = $_POST['currencyInput'];

	$currency_rate = 0;
	$currency_rate = calc_currency_rate($currency_type);

	$sale_price = $currency_input * $currency_rate;
	if ($currency_rate !== 0){
		$result['result'] = 'Done';
		$result['sale_price'] = round($sale_price , -3);
		wp_send_json( $result );
		exit();
	} else {
		$result['result'] = 'Error';
		$result['msg'] = 'error in editing data';
		wp_send_json( $result );
		exit();
	}
}
add_action( 'wp_ajax_getSalePrice', 'getSalePrice_callback' );
add_action( 'wp_ajax_nopriv_getSalePrice', 'getSalePrice_callback' );
