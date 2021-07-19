<?php if ( ! defined( 'ABSPATH' ) ) {die( 'Invalid request.' ); } ?>


<?php
global $wpdb;
$usersTable = $wpdb->prefix . 'users';
$postsTable = $wpdb->prefix . 'posts';

$user_id = $wpdb->get_var( "SELECT id FROM $usersTable WHERE display_name='$author_name' ");

$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
$limit = 5; // number of rows in page
$offset = ( $pagenum - 1 ) * $limit;
$total = $wpdb->get_var( "SELECT COUNT(`id`) FROM $postsTable WHERE post_author='$user_id' AND post_status='publish' AND post_type='post' ");
$author_posts = $wpdb->get_results( "SELECT * FROM $postsTable WHERE post_author='$user_id' AND post_status='publish' AND post_type='post' ORDER BY post_date DESC LIMIT $offset,$limit");
$num_of_pages = ceil( $total / $limit );
$numrow = ($pagenum - 1) * $offset + 1;
?>



<div class="main-page-wrapper">

	<div class="page-title page-title-default title-size-default title-design-disable color-scheme-light title-blog" >
		<div class="container">
			<div class="yoast-breadcrumb"></div>
		</div>
	</div>




	<div class="container">
		<div class="row content-layout-wrapper align-items-start">
			<div class="site-content col-lg-9 col-12 col-md-9" role="main">
				<?php
				foreach ($author_posts as $post){
					?>
					<div class="wd-blog-holder blog-pagination-pagination" id="60f48c1924b8a" data-paged="1" data-source="main_loop">
						<article id="post-<?= $post->ID ?>" class="blog-design-small-images blog-post-loop blog-style-shadow post-54233 post type-post status-publish format-standard has-post-thumbnail hentry">
							<div class="article-inner">
								<header class="entry-header">
									<figure id="" class="entry-thumbnail">

										<div class="post-img-wrapp">
											<a href="<?= get_permalink($post->ID) ;?>">
												<img width="900" height="431" class="attachment-large wp-post-image lazyloaded" src="<?= get_the_post_thumbnail_url($post->ID , 'full'); ?>"  alt="<?= $post->post_title; ?>" sizes="(max-width: 900px) 100vw, 900px" srcset="">
											</a>
										</div>

										<div class="post-image-mask">
											<span></span>
										</div>
									</figure>
									<div class="post-date wd-post-date wd-style-with-bg woodmart-post-date" onclick="">
										<?php
										// Shamsi Date
										include_once ZEUS_INC . '/libs/jdatetime.class.php';
										$date = new jDateTime(true, true, 'Asia/Tehran');
										$post_date_day = $date->date("d" , strtotime($post->post_date));
										$post_date_month = $date->date("F" , strtotime($post->post_date));
										?>
										<span class="post-date-day"><?= $post_date_day ?></span>
										<span class="post-date-month"><?= $post_date_month ?></span>
									</div>

								</header><!-- .entry-header -->

								<div class="article-body-container">
									<div class="meta-categories-wrapp">
										<div class="meta-post-categories wd-post-cat wd-style-with-bg">
											<?php
											$post_categories = wp_get_post_categories( $post->ID );
											$cats = [];
											$counter1 = 0;
											$counter2 = 0;
											foreach($post_categories as $c){
												$counter1++;
											}
											foreach($post_categories as $c){
												$counter2++;
												$cat = get_category( $c );
												?>
												<a href="<?= get_site_url().'/category/'.$cat->slug; ?>"><?= $cat->name; ?></a>
												<?php
												if($counter2 < $counter1){
													echo ' , ';
												}
											}
											?>
										</div>
									</div>

									<h3 class="wd-entities-title title post-title">
										<a href="<?= get_permalink($post->ID) ?>" rel="bookmark"><?= $post->post_title ?></a>
									</h3>

									<div class="entry-meta wd-entry-meta">
										<ul class="entry-meta-list">
											<li class="meta-author">
												توسط
												<img data-del="avatar" alt="author-avatar" src="<?= get_avatar_url($post->post_author) ; ?>" class="avatar pp-user-avatar avatar-32 photo lazyloaded" height="32" width="32" data-ll-status="loaded">
												<a href="https://sisoog.com/author/<?= $author_name ?>/" rel="author">
											<span class="vcard author author_name">
												<span class="fn"><?= $author_name ?></span>
											</span>
												</a>
											</li>

											<li class="meta-reply">
												<a href="<?= get_permalink($post->ID) ?>#comments">
													<span class="replies-count"><?= get_comments_number($post->ID) ; ?></span> <span class="replies-count-label">دیدگاه</span>
												</a>
											</li>
										</ul>
									</div><!-- .entry-meta -->
									<div class="hovered-social-icons wd-tltp wd-tltp-top">
										<div class="wd-tooltip-label">
											<div class="wd-social-icons woodmart-social-icons text-center icons-design-default icons-size-small color-scheme-light social-share social-form-circle">
												<a rel="noopener noreferrer nofollow" href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(get_permalink($post->ID)); ?>" target="_blank" class=" wd-social-icon social-facebook">
													<span class="wd-icon"></span>
												</a>
												<a rel="noopener noreferrer nofollow" href="https://twitter.com/share?url=<?= urlencode(get_permalink($post->ID)); ?>" target="_blank" class=" wd-social-icon social-twitter">
													<span class="wd-icon"></span>
												</a>
												<a rel="noopener noreferrer nofollow" href="mailto:?subject=Check%20this%20<?= urlencode(get_permalink($post->ID)); ?>" target="_blank" class=" wd-social-icon social-email">
													<span class="wd-icon"></span>
												</a>
												<a rel="noopener noreferrer nofollow" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?= urlencode(get_permalink($post->ID)); ?>" target="_blank" class=" wd-social-icon social-linkedin">
													<span class="wd-icon"></span>
												</a>
												<a rel="noopener noreferrer nofollow" href="https://api.whatsapp.com/send?text=<?= urlencode(get_permalink($post->ID)); ?>" target="_blank" class="whatsapp-desktop  wd-social-icon social-whatsapp">
													<span class="wd-icon"></span>
												</a>
												<a rel="noopener noreferrer nofollow" href="whatsapp://send?text=<?= urlencode(get_permalink($post->ID)); ?>" target="_blank" class="whatsapp-mobile  wd-social-icon social-whatsapp">
													<span class="wd-icon"></span>
												</a>
												<a rel="noopener noreferrer nofollow" href="https://telegram.me/share/url?url=<?= urlencode(get_permalink($post->ID)); ?>" target="_blank" class=" wd-social-icon social-tg">
													<span class="wd-icon"></span>
												</a>
											</div>
										</div>
									</div>

									<div class="entry-content wd-entry-content woodmart-entry-content">
										<?= $post->post_excerpt ?>
										<p class="read-more-section">
											<a class="btn-read-more more-link" href="<?= get_permalink($post->ID); ?>">ادامه مطلب</a>
										</p>
									</div><!-- .entry-content -->

								</div>
							</div>
						</article><!-- #post -->
					</div>

					<?php
				}
				?>

				<div class="wd-loop-footer blog-footer">
					<?php
					$page_links = paginate_links( array(
						'base' => add_query_arg( 'pagenum', '%#%' ),
						'format' => '',
						'prev_text' => '&laquo;',
						'next_text' => '&raquo;',
						'total' => $num_of_pages,
						'current' => $pagenum
					) );
					if ( $page_links ) {
						echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
					}
					?>

					<!--				<nav class="wd-pagination woodmart-pagination">-->
					<!--					<ul>-->
					<!--						<li><span class="current page-numbers">1</span></li><li><a href="https://sisoog.com/category/%D9%85%D9%82%D8%A7%D9%84%D9%87/page/2/" class="page-numbers">2</a></li><li><a href="https://sisoog.com/category/%D9%85%D9%82%D8%A7%D9%84%D9%87/page/3/" class="page-numbers">3</a></li><li><a href="https://sisoog.com/category/%D9%85%D9%82%D8%A7%D9%84%D9%87/page/2/" class="page-numbers">›</a></li><li><a href="https://sisoog.com/category/%D9%85%D9%82%D8%A7%D9%84%D9%87/page/36/" class="page-numbers">»</a></li>-->
					<!--					</ul>-->
					<!--				</nav>-->
				</div>
			</div><!-- .site-content -->

			<div class="col-lg-3 col-12 col-md-3" >
				<?php get_sidebar(); ?>
			</div>
		</div><!-- .main-page-wrapper -->
	</div>

</div>


