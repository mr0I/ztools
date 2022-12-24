<?php if (!defined('ABSPATH')) {
	die('Invalid request.');
}

require_once ZEUS_INC . 'functions.php';
require_once plugin_dir_path(__FILE__) . '../../env.php';


add_action('woocommerce_product_options_general_product_data', 'ztools_product_options');
function ztools_product_options()
{

	$currency_type = get_post_meta(get_the_ID(), 'ztools_currency_type', true);
	switch ($currency_type) {
		case 'dollar':
			$currency_rate = get_option('Ztools_exrate_dollar', getenv('DEFAULT_DOLLAR_EXCHANGE_RATE'));
			break;
		case 'yuan':
			$currency_rate = get_option('Ztools_exrate_yuan', getenv('DEFAULT_YUAN_EXCHANGE_RATE'));
			break;
		default:
			$currency_rate = get_option('Ztools_exrate_dollar', getenv('DEFAULT_DOLLAR_EXCHANGE_RATE'));
	}
	$regular_price = round((floatval(get_post_meta(get_the_ID(), 'ztools_currency_input', true)) * $currency_rate), -3);
	$sale_price = round((floatval(get_post_meta(get_the_ID(), 'ztools_special_currency_input', true)) * $currency_rate), -3);


	echo '<div class="option_group">';
	woocommerce_wp_checkbox(array(
		'id'      => 'ztools_is_rate_dependent',
		'value'   => get_post_meta(get_the_ID(), 'ztools_is_rate_dependent', true),
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
	woocommerce_wp_text_input(array(
		'id'                => 'ztools_currency_input',
		'value'             => get_post_meta(get_the_ID(), 'ztools_currency_input', true),
		'label'             => 'قیمت ارزی',
		'description'       => ''
	));
	woocommerce_wp_text_input(array(
		'id'                => 'ztools_special_currency_input',
		'value'             => get_post_meta(get_the_ID(), 'ztools_special_currency_input', true),
		'label'             => 'قیمت فروش ویژه ارزی',
		'description'       => ''
	));

	echo '<p class="form-field" id="currency_rate">
		<label>نرخ ارز</label>
		<code>' . $currency_rate . '</code> تومان	</p>';

	echo '<p class="form-field" id="regular_currency_rate">
		<label>قیمت نهایی</label>
		<code>' . $regular_price . '</code> تومان	</p>';

	echo '<p class="form-field" id="sale_currency_rate">
		<label>قیمت فروش ویژه نهایی</label>
		<code>' . $sale_price . '</code> تومان	</p>';

	echo '</div></div>';
}


add_action('woocommerce_process_product_meta', 'ztools_save_fields', 10, 2);
function ztools_save_fields($id, $post)
{
	if (!empty($_POST['ztools_is_rate_dependent'])) {
		update_post_meta($id, 'ztools_is_rate_dependent', $_POST['ztools_is_rate_dependent']);
		update_post_meta($id, 'ztools_currency_type', $_POST['ztools_currency_type']);
		update_post_meta($id, 'ztools_currency_input', $_POST['ztools_currency_input']);
		update_post_meta($id, 'ztools_special_currency_input', $_POST['ztools_special_currency_input']);
	} else {
		delete_post_meta($id, 'ztools_is_rate_dependent');
		delete_post_meta($id, 'ztools_currency_type');
		delete_post_meta($id, 'ztools_currency_input');
		delete_post_meta($id, 'ztools_special_currency_input');
	}
}


add_action('save_post', 'ztools_currency_rate_save');
add_action('save_edit', 'ztools_currency_rate_save');
function ztools_currency_rate_save($post_id)
{
	if (get_post_type($post_id) !== 'product' || !get_post_meta($post_id, 'ztools_currency_type', true)) return;

	set_product_price($post_id);
}

/**
 * add price type column to woocommerce products
 */
add_filter('manage_edit-product_columns', function ($columns) {
	$columns['price_type_column'] = __('نوع قیمت', 'woocommerce');
	return $columns;
}, 10, 1);
add_action('manage_product_posts_custom_column', function ($column, $product_id) {
	if ($column == 'price_type_column') {
		$currencyType = (get_post_meta($product_id, 'ztools_currency_type'))[0];
		switch ($currencyType) {
			case 'dollar':
				echo '<span style="color:#eee;background-color:green">دلار</span>';
				break;
			case 'yuan':
				echo '<span style="color: #757575;background-color:yellow">یوان</span>';
				break;
			default:
				echo '<span style="color:#eee;background-color:red">ریال</span>';
				break;
		}
	}
}, 10, 2);
