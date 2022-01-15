<?php if ( ! defined( 'ABSPATH' ) ) {die( 'Invalid request.' ); }

require_once ZEUS_INC . 'functions.php';


add_action( 'woocommerce_product_options_general_product_data', 'ztools_product_options');
function ztools_product_options(){

	$currency_type = get_post_meta(get_the_ID() , 'ztools_currency_type' , true);
	switch ($currency_type){
		case 'dollar':
			$currency_rate = get_option('Ztools_exrate_dollar', 25000);
			break;
		case 'yuan':
			$currency_rate = get_option('Ztools_exrate_yuan', 3900);
			break;
		default:
			$currency_rate = get_option('Ztools_exrate_dollar', 25000);
	}
	$regular_price = round((floatval(get_post_meta( get_the_ID(), 'ztools_currency_input', true )) * $currency_rate) , -3);
	$sale_price = round((floatval(get_post_meta( get_the_ID(), 'ztools_special_currency_input', true )) * $currency_rate) , -3);


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
			'0' => '---',
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

	echo '<p class="form-field" id="currency_rate">
		<label>نرخ ارز</label>
		<code>'.$currency_rate.'</code> تومان	</p>';

	echo '<p class="form-field" id="regular_currency_rate">
		<label>قیمت نهایی</label>
		<code>'.$regular_price.'</code> تومان	</p>';

	echo '<p class="form-field" id="sale_currency_rate">
		<label>قیمت فروش ویژه نهایی</label>
		<code>'.$sale_price.'</code> تومان	</p>';

	echo '</div></div>';
}


add_action( 'woocommerce_process_product_meta', 'ztools_save_fields', 10, 2 );
function ztools_save_fields( $id, $post ){
	if( !empty( $_POST['ztools_is_rate_dependent'] ) ) {
		update_post_meta( $id, 'ztools_is_rate_dependent', $_POST['ztools_is_rate_dependent'] );
		update_post_meta( $id, 'ztools_currency_type', $_POST['ztools_currency_type'] );
		update_post_meta( $id, 'ztools_currency_input', $_POST['ztools_currency_input'] );
		update_post_meta( $id, 'ztools_special_currency_input', $_POST['ztools_special_currency_input'] );
	} else {
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

	set_product_price($post_id);
}