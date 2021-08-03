<?php if ( ! defined( 'ABSPATH' ) ) {die( 'Invalid request.' ); }


$ztools_currency_type 	= get_post_meta( $post->ID, '_ztools_currency_type', true );
$ztools_currency_input 	= get_post_meta( $post->ID, '_ztools_currency_input', true );
$ztools_special_currency_input 	= get_post_meta( $post->ID, '_ztools_special_currency_input', true );
?>


<p>
    <label for="ztools_metabox_currency_type">نوع ارز</label>
    <select name="ztools_metabox_currency_type" id="ztools_metabox_currency_type" class="left" style="width: 80%;float: left ">
        <option value="0">---</option>
        <option value="tomaan" <?= ($ztools_currency_type === 'tomaan')? 'selected' : '' ?> >تومان</option>
        <option value="dollar" <?= ($ztools_currency_type === 'dollar')? 'selected' : '' ?>>دلار</option>
        <option value="yuan" <?= ($ztools_currency_type === 'yuan')? 'selected' : '' ?>>یوان</option>
    </select>
</p>
<p>
    <label for="ztools_metabox_currency_input">قیمت ارزی</label>
    <input type="text" style="width: 80%;float: left " name="ztools_metabox_currency_input" id="ztools_metabox_currency_input" class="ltr left"
           value="<?= esc_attr( $ztools_currency_input );?>"/>
</p>
<p>
    <label for="ztools_metabox_special_currency_input">قیمت فروش ویژه ارزی</label>
    <input type="text" style="width: 80%;float: left " name="ztools_metabox_special_currency_input" id="ztools_metabox_special_currency_input" class="ltr left"
           value="<?= esc_attr( $ztools_special_currency_input ); ?>"/>
</p>
