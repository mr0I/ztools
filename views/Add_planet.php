<?php
if ( ! defined( 'ABSPATH' ) ) {die( 'Invalid request.' ); }
$show_posts_link = get_option('ztools_show_planets_url','');
?>


<?php if ( ! is_user_logged_in() ):?>
	<div class="alert alert-danger" role="alert" id="planet_access_alert">
		<?php echo __('You should login before accessing this page.' , 'ztools'); ?> <a href="https://sisoog.com/login/" class="alert-link"><?php echo __('Login to sisoog' , 'ztools'); ?></a>
	</div>
	<?php else:?>

		<script type="text/javascript" src="https://cdn.ckeditor.com/ckeditor5/18.0.0/decoupled-document/ckeditor.js"></script>
		<script type="text/javascript" src="https://cdn.ckeditor.com/ckeditor5/18.0.0/decoupled-document/translations/fa.js"></script>

		<?php
		$args = array(
			'taxonomy' => 'zcategory',
			'orderby' => 'name',
			'order'   => 'ASC',
			'hide_empty'  => 0
		);
		$cats = get_categories($args);
		?>

		<form class="planet_form" action="" method="POST" id="add_planet_frm">
			<fieldset>
				<h5><?php echo __('Add Link Form' , 'ztools'); ?></h5>
				<a class="btn planet_secondary_btn" href="<?php echo $show_posts_link; ?>"><?php echo __('Show My Links' , 'ztools'); ?>
			</a>
		</fieldset>

		<div class="form-group require">
			<label for="planet_title"><?php echo __('Planet Title' , 'ztools'); ?></label>
			<input name="planet_title" id="planet_title" class="rtl right-align" type="text"/>
			<span class="invalid-feedback" id="title_err" role="alert">
				<i class="fa fa-warning"></i>
				<strong><?php echo __('Please enter post title' , 'ztools') ?></strong>
			</span>
		</div>


<!--		<div class="form-group require">-->
<!--			--><?php
//			wp_editor( $distribution, 'distribution', array( 'theme_advanced_buttons1' => 'bold, italic, ul, pH, pH_min', "media_buttons" => true, "textarea_rows" => 8, "tabindex" => 4 ) );
//			?>
<!--		</div>-->


		<div class="form-group require">
			<label for="planet_content"><?php echo __('Planet Content' , 'ztools'); ?></label>
			<div id="toolbar-container"></div>
			<div id="planet_content_div"> <p></p> </div>
			<span class="helper-text"><?php echo __('Max length equals 500' , 'ztools'); ?>
		</span>
		<span class="planet_content_char_counter"></span>
		<span class="invalid-feedback" id="content_err" role="alert">
			<i class="fa fa-warning"></i>
			<strong><?php echo __('Please enter post content' , 'ztools') ?></strong>
		</span>
		<span class="invalid-feedback" id="content_err2" role="alert">
			<i class="fa fa-warning"></i>
			<strong><?php echo __('Text entered is longer than allowed' , 'ztools') ?></strong>
		</span>
	</div>

	<div class="form-group">
		<label for="planet_url"><?php echo __('Planet URL' , 'ztools'); ?></label>
		<input name="planet_url" id="planet_url" class="ltr left-align" type="url"/>
		<span class="helper-text"><?php echo __('like: https://example.com/note/index.html' , 'ztools'); ?>
	</span>
	<span class="invalid-feedback" id="url_err" role="alert">
		<i class="fa fa-warning"></i>
		<strong><?php echo __('The link you entered is not valid' , 'ztools') ?></strong>
	</span>
</div>

<div class="form-group">
	<label for="planet_tags"><?php echo __('Planet Tags' , 'ztools'); ?></label>
	<input name="planet_tags" id="planet_tags_input" class="rtl right-align" type="text"/>
	<span class="helper-text"><?php echo __('Press Enter to add new tag' , 'ztools'); ?>
</span>
<input type="hidden" id="planet_tags">
<ul id="tags_list"></ul>
</div>

<div class="form-group">
	<label for="cat"><?php echo __('Planet Categories' , 'ztools'); ?></label>
	<select data-placeholder="<?php echo __('Choose categories ...' , 'ztools'); ?>" id="cat" name="cat[]" multiple class="chosen-select">
		<?php
		foreach($cats as $cat){
			echo '<option value="'.$cat->term_id .'">'.$cat->name.'</option>';
		}
		?>
	</select>
</div>

<div class="form-group text-left">
	<input type="hidden" name="planet_nonce" id="planet_nonce" value="<?php echo wp_create_nonce('planet-nonce'); ?>"/>
	<button type="button" id="frm_planet_submit"><?php echo __('Submit for review' , 'ztools'); ?>
</button>
</div>

<div class="alert alert-danger" id="planet_frm_err_notify" style="display: none;">
	<p><?php echo __('Correct form errors first!' , 'ztools') ?></p>
</div>
</form>

<!-- jq translates -->
<input type="hidden" id="max_tags_num_message" value="<?php echo __('You can register up to 5 tags at the most!' , 'ztools') ?>">
<input type="hidden" id="duplicate_tags" value="<?php echo __('The tag you entered is a duplicate!' , 'ztools') ?>">
<input type="hidden" id="min_tags_letters" value="<?php echo __('The minimum number of letters per label is 3!' , 'ztools') ?>">
<input type="hidden" id="success_post_submit_message" value="<?php echo __('New entry successfully recorded' , 'ztools') ?>">
<input type="hidden" id="error_send_info" value="<?php echo __('Error sending information' , 'ztools') ?>">
<input type="hidden" id="success_post_edit_message" value="<?php echo __('Content editing done successfully' , 'ztools') ?>">
<input type="hidden" id="submit_btn_text1" value="<?php echo __('Submit for review' , 'ztools') ?>">
<input type="hidden" id="submit_btn_text2" value="<?php echo __('Submitting' , 'ztools') ?>">
<!-- jq translates -->




<script>
	DecoupledEditor
	.create( document.querySelector( '#planet_content_div' ),{
		// plugins: [ Base64UploadAdapter, ... ],
		// toolbar: [ ... ],
		language: 'fa'
	} )
	.then( editor => {
		const toolbarContainer = document.querySelector( '#toolbar-container' );
		toolbarContainer.appendChild( editor.ui.view.toolbar.element );
	} )
	.catch( error => {
		//console.error( error );
	} );
</script>

<?php endif; ?>







