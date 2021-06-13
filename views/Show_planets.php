<?php
if ( ! defined( 'ABSPATH' ) ) {die( 'Invalid request.' ); }
$add_post_link = get_option('ztools_add_planet_url','');
$posts_per_page = get_option('ztools_post_pp','');
$paged_type = 'paged';
?>


<input type="hidden" id="edit_url_val" value="<?php echo get_option('ztools_edit_planet_url','') ; ?>">

<?php
function wp_bootstrap4_pagination( $args = array() , $paged_type , $posts_per_page ) {
	$user_id = get_current_user_id();
	$paged = ( get_query_var( $paged_type ) ) ? get_query_var( $paged_type ) : '1';
	$args = array(
		'post_type' => 'zplanet' ,
		'author' => $user_id ,
		'orderby' => 'date' ,
		'order' => 'DESC' ,
		'posts_per_page' => $posts_per_page,
		'paged' => $paged,
		'post_status' => 'pending,publish'
	);
	$query = new WP_Query($args);

	$defaults = array(
		'range'           => 5,
		'custom_query'    => $query,
		'previous_string' => '<i class="fa fa-angle-right"></i>',
		'next_string' => '<i class="fa fa-angle-left"></i>',
		'first_string' => '<i class="fa fa-angle-double-right"></i>',
		'last_string' => '<i class="fa fa-angle-double-left"></i>',
		'before_output'   => '<ul class="planets_pagination">',
		'after_output'    => '</ul>'
	);
	wp_reset_postdata();

	$args = wp_parse_args(
		$args,
		apply_filters( 'wp_bootstrap_pagination_defaults', $defaults )
	);

	$args['range'] = (int) $args['range'] - 1;
	if ( !$args['custom_query'] )
		$args['custom_query'] = @$GLOBALS['wp_query'];
	$count = (int) $args['custom_query']->max_num_pages;
	$page  = intval( get_query_var( $paged_type ) );
	$ceil  = ceil( $args['range'] / 2 );

	if ( $count <= 1 )
		return FALSE;
	if ( !$page )
		$page = 1;
	if ( $count > $args['range'] ) {
		if ( $page <= $args['range'] ) {
			$min = 1;
			$max = $args['range'] + 1;
		} elseif ( $page >= ($count - $ceil) ) {
			$min = $count - $args['range'];
			$max = $count;
		} elseif ( $page >= $args['range'] && $page < ($count - $ceil) ) {
			$min = $page - $ceil;
			$max = $page + $ceil;
		}
	} else {
		$min = 1;
		$max = $count;
	}

	$echo = '';
	$previous = intval($page) - 1;
	$previous = esc_attr( get_pagenum_link($previous) );

	$firstpage = esc_attr( get_pagenum_link(1) );
	if ( $firstpage && (1 != $page || true) )
		$echo .= '<li class="page-item previous'.($page == 1 ? ' disabled' : '').'"><a class="page-link" href="' . $firstpage . '" aria-label="'.__( 'First' ).'" title="صفحه اول">' . $args['first_string'] . '</a></li>';
	if ( $previous && (1 != $page || true) )
		$echo .= '<li'.($page == 1 ? ' class="page-item disabled"' : '').'><a class="page-link" href="' . $previous . '" title="' . __( 'Previous Page', 'ztools') . '" aria-label="' . __( 'Previous Page', 'ztools') . '">' . $args['previous_string'] . '</a></li>';


	if ( !empty($min) && !empty($max) ) {
		for( $i = $min; $i <= $max; $i++ ) {
			if ($page == $i) {
				$echo .= sprintf( '<li class="page-item active"><a class="page-link active" href="%s">%s</a></li>', esc_attr( get_pagenum_link($i) ),  $i  );
			} else {
				$echo .= sprintf( '<li class="page-item"><a class="page-link" href="%s">%d</a></li>', esc_attr( get_pagenum_link($i) ), $i );
			}
		}
	}

	$next = intval($page) +1;
	$next = esc_attr( get_pagenum_link($next) );
	if ($next && ($count != $page || true) )
		$echo .= '<li'.($page == $count ? ' class="page-item disabled"' : '').'><a class="page-link" href="' . $next . '" title="' . __( 'Next Page', 'ztools') . '" aria-label="' . __( 'Next Page', 'ztools') . '">' . $args['next_string'] . '</a></li>';

	$lastpage = esc_attr( get_pagenum_link($count) );
	if ( $lastpage ) {
		$echo .= '<li class="page-item next'.($page == $count ? ' disabled' : '').'"><a class="page-link" href="' . $lastpage . '" aria-label="' . __( 'Last') . '" title="صفحه آخر">' . $args['last_string'] . '</a></li>';
	}
	if ( isset($echo) )
		echo $args['before_output'] . $echo . $args['after_output'];
}
?>


<?php if ( ! is_user_logged_in() ):?>
	<div class="alert alert-danger" role="alert" id="planet_access_alert">
		<?php echo __('You should login before accessing this page.' , 'ztools'); ?> <a href="https://sisoog.com/login/" class="alert-link"><?php echo __('Login to sisoog' , 'ztools'); ?></a>
	</div>
	<?php else:?>
		<?php
		$user_id = get_current_user_id();
		$paged = ( get_query_var( $paged_type ) ) ? get_query_var( $paged_type ) : '1';
		$args = array(
			'post_type' => 'zplanet' ,
			'author' => $user_id ,
			'orderby' => 'date' ,
			'order' => 'DESC' ,
			'posts_per_page' => $posts_per_page,
			'paged' => $paged,
			'post_status' => 'pending,publish'
		);
		$category = new WP_Query($args);
		?>

		<div class="planet_loading">
			<img src="<?php echo plugins_url().'/ztools/img/pinewheel.gif' ?>" alt="">
		</div>

		<div class="planet_list_container">
			<div class="list_head">
				<h5><?php echo __('Planets List', 'ztools') ?></h5>
				<a href="<?php echo $add_post_link; ?>" class="btn planet_secondary_btn">
					<?php echo __('Add Link', 'ztools') ?>
					<i class="fa fa-plus" style="vertical-align: bottom;"></i>
				</a>
			</div>

			<?php $i = 1; ?>
			<?php if ( $category->have_posts() ) : ?>

				<div class="table-responsive">
					<table class="table table-borderless table-hover" id="tbl_planets">
						<thead class="thead-light">
							<tr>
								<th scope="col"><?php echo __('Row Number', 'ztools') ?></th>
								<th scope="col"><?php echo __('Title', 'ztools') ?></th>
								<th scope="col"><?php echo __('Post Status', 'ztools') ?></th>
								<th scope="col"><?php echo __('Operations', 'ztools') ?></th>
							</tr>
						</thead>
						<?php while ( $category->have_posts() ) : $category->the_post(); ?>
							<?php
							global $post;
							global $wpdb;
							$totalrow1 = $wpdb->get_results( "SELECT id FROM $wpdb->post_like_table WHERE postid = '$post->ID'");
							$total_like = $wpdb->num_rows;
							?>
							<tbody class="table-hover">
								<tr>
									<th scope="row"><?php echo $i++; ?></th>
									<td>
										<?php
										if($post->post_status == 'publish'){
											?>
											<a href="<?php echo esc_url( get_permalink() ); ?>" target="_blank">
												<?php echo mb_strimwidth($post->post_title, 0, 40, '...'); ?>
											</a>
											<?php
										}else{
											echo mb_strimwidth($post->post_title, 0, 40, '...');
										}
										?>
									</td>
									<td>
										<?php
										if($post->post_status == 'pending'){
											echo '<span class="text-warning">'.__("Pending" , 'ztools').'</span>';
										}elseif ($post->post_status == 'publish'){
											echo '<span class="text-success">'.__("Published" , 'ztools').'</span>';
										}
										?>
									</td>
									<td id="tbl_planets_ops">
										<?php
										if($post->post_status == 'pending'){
											?>
											<a href="#" class="remove_planet mytooltip" data-id="<?php echo $post->ID ?>">
												<span class="tooltiptext"><?php echo __('Remove Planet', 'ztools') ?></span>
												<i class="fa fa-trash"></i>
											</a>
											<input type="hidden" name="remove_planet_nonce" id="remove_planet_nonce" value="<?php echo wp_create_nonce('remove-planet-nonce'. $post->ID ); ?>"/>
											|
											<a href="#" data-id="<?php echo $post->ID ?>" role="button" class="edit_planet mytooltip">
												<span class="tooltiptext"><?php echo __('Edit', 'ztools') ?></span>
												<i class="fa fa-pencil"></i>
											</a>
											<?php
											if(get_post_meta($post->ID, "zplanet-link", true) != ''){
												?>
												|
												<a href="<?php echo get_post_meta($post->ID, "zplanet-link", true); ?>" target="_blank" rel="nofollow" class="link_planet mytooltip">
													<span class="tooltiptext"><?php echo __('Source Link', 'ztools') ?></span>
													<i class="fa fa-link"></i>
												</a>
												<?php
											}
										}else if($post->post_status == 'publish'){
											?>
											<a href="<?php echo esc_url( get_permalink() ); ?>" class="open_planet mytooltip" target="_blank">
												<span class="tooltiptext"><?php echo __('Go to link', 'ztools') ?></span>
												<i class="fa fa-external-link"></i>
											</a>
											|
											<a href="<?php esc_url(the_permalink()); ?>#respond" class="planet_comments mytooltip">
												<span class="tooltiptext">
													<?php echo __('Number of comments: ' , 'ztools' ); ?>
													<?php echo get_comments_number(); ?>
												</span>
												<i class="fa fa-comment-o"></i>
											</a>
											|
											<a class="planet_likes mytooltip">
												<span class="tooltiptext">
													<?php echo __('Number of likes: ' , 'ztools' ); ?>
													<?php echo $total_like; ?>
												</span>
												<i class="fa fa-heart-o"></i>
											</a>
											<?php
										}
										?>
									</td>
								</tr>
							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>
						</tbody>
					</table>
				</div>

				<div class="container">
					<div class="row">
						<div class="planets_list_pagination">
							<?php wp_bootstrap4_pagination( array(
								'previous_string' => '<i class="fa fa-angle-right"></i>',
								'next_string' => '<i class="fa fa-angle-left"></i>',
								'first_string' => '<i class="fa fa-angle-double-right"></i>',
								'last_string' => '<i class="fa fa-angle-double-left"></i>',
								'before_output'   => '<ul class="planets_pagination">',
								'after_output'    => '</ul>'
							)  , $paged_type, $posts_per_page); ?>
						</div>
					</div>
				</div>


				<!-- The Modal -->
				<div id="myModal" class="modal">
					<div class="modal-content">
						<span class="close_modal">&times;</span><br><br>
						<p><?php echo __('Are you sure to delete this item?' , 'ztools') ?></p>
						<div class="modal-footer">
							<a href="#" class="close_modal_btn"><?php echo __('No' , 'ztools') ?></a>
							<a href="#" id="delete_planet" data-id=""><?php echo __('Yes' , 'ztools') ?></a>
						</div>
					</div>
				</div>

				<!-- jq translates -->
				<input type="hidden" id="success_delete_post" value="<?php echo __('Deletion completed successfully' , 'ztools') ?>">
				<input type="hidden" id="error_delete_post" value="<?php echo __('Error deleting item!' , 'ztools') ?>">
				<!-- jq translates -->



				<?php else: ?>
					<div class="container">
						<div class="alert alert-danger">
							<p><?php echo __('No Post Exists' , 'ztools'); ?></p>
						</div>
					</div>
				<?php endif; ?>

			</div>
		<?php endif; ?>
