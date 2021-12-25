<?php
// Variables
$loginUrl = get_option('ztools_planet_loginUrl',''); // آدرس صفحه ورود برای ریدایرکت شدن کاربر وارد نشده بعد از لایک


get_header();
?>

    <section class="archive-blog">
        <div class="container">
		  <?php
		  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		  $cnt = 1;
		  $args = [
			  'post_type' => 'zplanet',
			  'post_status' => 'publish',
			  'posts_per_page' => get_option('ztools_planet_postPP',''),
			  'paged' => $paged
		  ];
		  $args2 = [
			  'post_type' => 'zplanet',
			  'post_status' => 'publish',
			  'posts_per_page' => -1,
			  'paged' => $paged
		  ];
		  if (!empty($page_term->term_id)) {
			$tax_query =
				[
					[
						'taxonomy' => $page_term->taxonomy,
						'field'    => 'term_id',
						'terms' => $page_term->term_id
					]
				];

			$args['tax_query'] = $tax_query;
		  }
		  if (isset($_GET["sort"])) {
			$sort = $_GET["sort"];

			if ($sort == "newest") {
			  $sortarray = [
				  'order' => 'DESC',
				  'orderby' => 'ID'
			  ];
			  $args = $args + $sortarray;
			} elseif ($sort == "topviews") {
			  $sortarray = ['meta_key' => 'views'];
			  $args = $args + $sortarray;
			} elseif ($sort == "topcomments") {
			  $sortarray = [
				  'order' => 'DESC',
				  'orderby' => 'comment_count'
			  ];
			  $args = $args + $sortarray;
			} elseif ($sort == "toplikes") {
			  $sortarray = [
				  'meta_key' => 'post_like',
			  ];
			  $args = $args + $sortarray;
			}
		  } else {
			$sortarray = [
				'order' => 'DESC',
				'orderby' => 'ID'
			];
			$args = $args + $sortarray;
		  }

		  if (isset($_GET["search"])) {
			$search = $_GET["search"];
			$args['s'] = $search;
		  }


		  $query = new WP_Query($args);
		  $query2 = new WP_Query($args2);
		  if ($query->have_posts()) :
		  $count_all_posts = $query2->post_count;
		  $terms = get_the_terms($post_id, 'zcategory');
		  ?>

            <div class="archive-blog__header">
                <form id="search" action="" name="formsearch" method="GET">
                    <div class="archive-blog__header__top">
                        <div class="archive-blog__header__top__title">
                            <i class="dn-menu2"></i>
						  <?php if (!empty($terms)) : ?>
							<?php foreach ($terms as $item) : ?>
                                  <h3> <?= $item->name ?></h3>
							<?php endforeach; ?>
						  <?php endif ?>

                            <span><?= $count_all_posts ?> مورد</span>
                        </div>
                        <div class="archive-blog__header__top__search">
                            <input type="text" name="search" placeholder="جستجو در آموزش ها ..." value="<?= isset($search) ? $search : ''; ?>">
                            <button> <i class="dn-search"></i></button>
                        </div>
                    </div>
                    <div class="archive-blog__header__bottom">
					  <?php if (has_tag()) :  ?>
                          <div class="archive-blog__header__bottom__tags">
							<?php the_tags('<ul><li>', '</li><li>', '</li></ul>'); ?>
                          </div>
					  <?php endif; ?>
                        <div class="archive-blog__header__bottom__filter">
                            <select class="form-select" name="sort">
                                <option value="newest" <?= (isset($sort) && $sort == 'newest') ? 'selected' : ''; ?>>جدیدترین ها</option>
                                <option value="topcomments" <?= (isset($sort) && $sort == 'topcomments') ? 'selected' : ''; ?>>پر بحث ترین ها</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="archive-blog__list">
                <div class="row">
				  <?php while ($query->have_posts()) : $query->the_post();
				  $post_id = $post->ID;
				  $post_image = get_the_post_thumbnail_url($post_id);
				  $post_link =  get_permalink($post_id);
				  $post_title  = get_the_title($post);
				  $post_desc = get_excerpt_content(600);
				  $post_like  = post_like_count($post_id, 'like');
				  $post_view  = get_views($post_id);
				  $post_comments  = get_comments_number($post_id);
				  $number_part = get_field('number_part', $post_id);
				  $terms  = get_the_terms($post_id, 'zcategory');
				  ?>
				  <?php if ($cnt == 9) : ?>
                      <div class="col-lg-12 col-sm-6">
                          <div class="article__item article__item--big1 mb">
                              <div class="article__item__image">
                                  <a href="<?= $post_link ?>" title="<?= $post_title ?>"><img src="<?= $post_image ?>" alt="<?= $post_title ?>"></a>
                              </div>
                              <div class="article__item__text">
                                  <div class="article__item__text__title">
                                      <h3><a href="<?= $post_link ?>" title="<?= $post_title ?>"><?= $post_title ?></a></h3>
                                  </div>
                                  <div class="article__item__text__desc">
                                      <p><?= $post_desc ?></p>
                                  </div>
                                  <div class="article__item__text__detail">
                                      <div class="article__item__text__detail__author">
										<?php echo get_avatar(get_the_author_meta('ID')); ?>
                                          <a href="<?= get_author_posts_url($post->post_author) ?>" title="<?= get_the_author_meta('display_name', $post->post_author) ?>"><span><?= get_the_author_meta('display_name', $post->post_author) ?></span></a>
                                      </div>
                                      <div class="article__item__text__detail__info">
                                          <ul>
                                              <li class="">
                                                  <i class="dn-comment"></i>
												<?= $post_comments ?>
                                              </li>
                                              <li class="">
                                                  <i class="dn-favorite"></i>
												<?= $post_like ?>
                                              </li>
                                              <li>
                                                  <i class="dn-clock"></i>
												<?php echo esc_html(human_time_diff(get_the_time('U'), current_time('timestamp'))) . ' پیش'; ?>
                                              </li>
                                          </ul>
                                      </div>
                                  </div>
                              </div>
                              <div class="article__item__label">
								<?php if (!empty($terms)) :
								  $i = 1;
								  ?>
								  <?php foreach ($terms as $item) :
								  $i++;
								  if($i > 3){
									break;
								  }
								  ?>
                                    <a href=""> <?= $item->name ?></a>
								<?php endforeach; ?>
								<?php endif ?>
                              </div>
                          </div>
                      </div>
				  <?php elseif ($cnt == 14) : ?>
                </div>
            </div>
		  <?php get_template_part('parts/banner1'); ?>
            <div class="archive-blog__list">
                <div class="row">
                    <div class="col-lg-3 col-sm-6">
                        <div class="article__item mb">
                            <div class="article__item__image">
                                <a href="<?= $post_link ?>" title="<?= $post_title ?>"><img src="<?= $post_image ?>" alt=""></a>
                            </div>
                            <div class="article__item__text">
                                <div class="article__item__text__title">
                                    <h3><a href="<?= $post_link ?>" title="<?= $post_title ?>"><?= $post_title ?></a></h3>
                                </div>
                                <div class="article__item__text__desc">
                                    <p><?= $post_desc ?></p>
                                </div>
                                <div class="article__item__text__detail">
                                    <div class="article__item__text__detail__author">
									  <?php echo get_avatar(get_the_author_meta('ID')); ?>
                                        <a href="<?= get_author_posts_url($post->post_author) ?>" title="<?= get_the_author_meta('display_name', $post->post_author) ?>"><span><?= get_the_author_meta('display_name', $post->post_author) ?></span></a>
                                    </div>
                                    <div class="article__item__text__detail__info">
                                        <ul>
                                            <li>
                                                <i class="dn-clock"></i>
											  <?php echo esc_html(human_time_diff(get_the_time('U'), current_time('timestamp'))) . ' پیش'; ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="article__item__label">
							  <?php if (!empty($terms)) :
								$i = 1;
								?>
								<?php foreach ($terms as $item) :
								$i++;
								if($i > 3){
								  break;
								}
								?>
                                  <a href=""> <?= $item->name ?></a>
							  <?php endforeach; ?>
							  <?php endif ?>
                            </div>
                        </div>
                    </div>
				  <?php else : ?>
                      <div class="col-lg-3 col-sm-6">
                          <div class="article__item mb">
                              <div class="article__item__image">
                                  <a href="<?= $post_link ?>"><img src="<?= $post_image ?>" alt="<?= $post_title ?>"></a>
                              </div>
                              <div class="article__item__text">
                                  <div class="article__item__text__title">
                                      <h3><a href="<?= $post_link ?>" title="<?= $post_title ?>"><?= $post_title ?></a></h3>
                                  </div>
                                  <div class="article__item__text__desc">
                                      <p><?= $post_desc ?></p>
                                  </div>
                                  <div class="article__item__text__detail">
                                      <div class="article__item__text__detail__author">
										<?= get_avatar(get_the_author_meta('ID')); ?>
                                          <span><?= get_the_author_meta('display_name', $post->post_author) ?></span>
                                      </div>
                                      <div class="article__item__text__detail__info">
                                          <ul>
                                              <li>
                                                  <i class="dn-clock"></i>
												<?php echo esc_html(human_time_diff(get_the_time('U'), current_time('timestamp'))) . ' پیش'; ?>
                                              </li>
                                          </ul>
                                      </div>
                                  </div>
                              </div>
                              <div class="article__item__label">
								<?php if (!empty($terms)) :
								  $i = 1;
								  ?>
								  <?php foreach ($terms as $item) :
								  $i++;
								  if($i > 3){
									break;
								  }
								  ?>
                                    <a href=""> <?= $item->name ?></a>
								<?php endforeach; ?>
								<?php endif ?>
                              </div>
                          </div>
                      </div>
				  <?php endif; ?>
				  <?php $cnt++ ?>
				  <?php endwhile;
				  wp_reset_query();
				  wp_reset_postdata(); ?>
                </div>
            </div>

            <section class="pagination">
                <div class="container">
                    <div class="pagination__list">
					  <?php
					  if ($query->max_num_pages > 1) {
						$current = $query->query_vars['paged'] > 1 ? $query->query_vars['paged'] : 1;
						echo paginate_links([
							'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
							'format' => '?page=%#%',
							'current' => $current,
							'total' => $query->max_num_pages,
							'type' => 'list',
							'prev_text' => '<i class="dn-arrow-right"></i>',
							'next_text' => '<i class="dn-arrow-left"></i>',
						]);
					  }
					  ?>
                    </div>
                </div>
            </section>
        </div>

	  <?php endif; ?>
    </section>

<?php get_template_part('parts/banner2'); ?>
<?php get_template_part('parts/new', 'product'); ?>
<?php get_template_part('parts/most', 'controversial'); ?>
<?php get_footer(); ?>