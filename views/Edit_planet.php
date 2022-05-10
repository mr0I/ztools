<?php
if ( ! defined( 'ABSPATH' ) ) {die( 'Invalid request.' ); }
$show_posts_link = get_option('ztools_show_planets_url','');
?>


<?php if ( ! is_user_logged_in() ):?>
	<div class="alert alert-danger" role="alert" id="planet_access_alert">
		<?php echo __('You should login before accessing this page.' , 'ztools'); ?> <a href="https://sisoog.com/login/" class="alert-link"><?php echo __('Login to sisoog' , 'ztools'); ?></a>
	</div>
	<?php elseif(! isset($_GET['post_id'] )) :?>
		<script type="text/javascript"> window.location.replace('https://sisoog.com/my_planet/'); </script>
		<?php else:?>
			<?php
			global $post_id , $post;
			$post_id = $_GET['post_id'];
			$current_post = get_post( $post_id );
			if(wp_get_current_user()->ID != get_post( $post_id )->post_author){
				?>
				<script type="text/javascript"> window.location.replace('https://sisoog.com/my_planet/'); </script>
				<?php
				exit();
			}


			?>


			<script type="text/javascript" src="https://cdn.ckeditor.com/ckeditor5/18.0.0/decoupled-document/ckeditor.js"></script>
			<script type="text/javascript" src="https://cdn.ckeditor.com/ckeditor5/18.0.0/decoupled-document/translations/fa.js"></script>

			<?php
			$tags_list = wp_get_post_terms($post_id, 'ztags', array("fields" => "all"));
			$cats_list = wp_get_post_terms($post_id, 'zcategory', array("fields" => "all"));
			foreach($tags_list as $row){
				$tags[] = $row->name;
			}
			$tags_string = implode(',', $tags);

			$args = array(
				'taxonomy' => 'zcategory',
				'orderby' => 'name',
				'order'   => 'ASC',
				'hide_empty'  => 0
			);
			$cats = get_categories($args);
			?>

			<form class="planet_form" action="" method="POST">
				<fieldset>
					<h5><?php echo __('Edit Link Form' , 'ztools'); ?></h5>
				</fieldset>

				<div class="form-group require">
					<label for="planet_title"><?php echo __('Planet Title' , 'ztools'); ?></label>
					<input name="planet_title" id="planet_title" class="rtl right-align" type="text"
					value="<?php echo $current_post->post_title ?>" />
					<span class="invalid-feedback" id="title_err" role="alert">
						<i class="fa fa-warning"></i>
						<strong><?php echo __('Please enter post title' , 'ztools') ?></strong>
					</span>
				</div>

				<div class="form-group require">
					<label for="planet_content"><?php echo __('Planet Content' , 'ztools'); ?></label>
					<div id="toolbar-container"></div>
					<div id="planet_content_div">
						<p><?php echo $current_post->post_content; ?></p>
					</div>
					<div id="planet_content_temp" style="display: none;">
						<p ><?php echo $current_post->post_content; ?></p>
					</div>
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
				<input name="planet_url" id="planet_url" class="ltr left-align" type="url"
				value="<?php echo get_post_meta($post_id, "zplanet-link", true); ?>" />
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
		<input type="hidden" id="planet_tags" value="<?php echo $tags_string.','; ?>">
		<ul id="tags_list">
			<?php
			foreach($tags as $tag){
				?>
				<li class="tags_list_item"><span><?php echo $tag ?></span>
					<a href="#"><i class="fa fa-times-circle"></i></a>
				</li>
				<?php
			}
			?>
		</ul>
	</div>

	<div class="form-group">
		<label for="cat"><?php echo __('Planet Categories' , 'ztools'); ?></label>
		<select data-placeholder="<?php echo __('Choose categories ...' , 'ztools'); ?>" id="cat" name="cat[]" multiple class="chosen-select">
			<?php
			foreach($cats as $cat){
				?>
				<option
				<?php
				foreach($cats_list as $row){
					if($cat->term_id == $row->term_id){
						echo 'selected';
					}
				}
				?>
				value="<?php echo $cat->term_id; ?>"
				>
				<?php echo $cat->name; ?>
			</option>
			<?php
		}
		?>
	</select>
</div>

<div class="form-group text-left">
	<input type="hidden" name="planet_nonce" id="planet_nonce" value="<?php echo wp_create_nonce('planet-nonce'); ?>"/>
	<a class="btn mx-2 planet_secondary_btn" href="<?php echo $show_posts_link; ?>" ><?php echo __('Back to planets list' , 'ztools'); ?></a>
	<button type="button" id="frm_planet_edit"><?php echo __('Edit' , 'ztools'); ?>
</button>
</div>

<input type="hidden" id="planet_postId" value="<?php echo $_GET['post_id'] ?>">
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
<input type="hidden" id="edit_btn_text1" value="<?php echo __('Edit' , 'ztools') ?>">
<input type="hidden" id="edit_btn_text2" value="<?php echo __('Editing' , 'ztools') ?>">
<!-- jq translates -->



<script>
	DecoupledEditor
	.create( document.querySelector( '#planet_content_div' ),{
		language: 'fa',
		//enterMode	: Number(2),
		//shiftEnterMode : DecoupledEditor.ENTER_P
		//enterMode : DecoupledEditor.ENTER_DIV
	} )
	.then( editor => {
		const toolbarContainer = document.querySelector( '#toolbar-container' );
		toolbarContainer.appendChild( editor.ui.view.toolbar.element );
		autoParagraph = false;
	} )
	.catch( error => {
		//console.error( error );
	} );
</script>
<?php endif; ?>




