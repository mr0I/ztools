<?php if ( ! defined( 'ABSPATH' ) ) {die( 'Invalid request.' ); }


$ztools_currency_input 	= get_post_meta( $post->ID, '_ztools_currency_input', true );
?>


<p>
    <label for="ztools_metabox_currency_input">قیمت ارزی</label>
    <input type="text" placeholder="" style="width: 80%" name="ztools_metabox_currency_input" id="ztools_metabox_currency_input" class="ltr left"
           value="<?= esc_attr( $ztools_currency_input );?>"/>
</p>
