<?php if ( ! defined( 'ABSPATH' ) ) { die( 'Invalid request.' ); }

require_once plugin_dir_path( __FILE__ ) . '../env.php';


global $wpdb;
$usersTable = $wpdb->prefix . 'users';
$postsTable = $wpdb->prefix . 'posts';
$postMetaTable = $wpdb->prefix . 'postmeta';
$user_id = $wpdb->get_var( "SELECT id FROM $usersTable WHERE display_name='$author_name' ");
$user = get_userdata($user_id);
$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
$limit = getenv('AUTHOR_POSTS_PAGINATE_COUNT'); // number of rows in page
$offset = ( $pagenum - 1 ) * $limit;

// sort options
if (isset($_GET["filter"])) {
  $sort = $_GET["filter"];

  if ($sort === "newest") {
	$orderBy = 'post_date';
	$order = 'DESC';
	$queryType = 'simple';
  } elseif ($sort === "topviews") {
	$orderBy = 'post_date';
	$order = 'DESC';
	$metaKey = 'views';
	$queryType = 'join';
  } elseif ($sort === "topcomments") {
	$orderBy = 'comment_count';
	$order = 'DESC';
	$queryType = 'simple';
  } elseif ($sort === "toplikes") {
	$orderBy = 'post_date';
	$order = 'DESC';
	$metaKey = 'post_like';
	$queryType = 'join';
  }
} else {
  $orderBy = 'post_date';
  $order = 'DESC';
  $queryType = 'simple';
}


// articles
if ($queryType === 'simple'){
  $author_posts = $wpdb->get_results( "SELECT * FROM $postsTable WHERE post_author='$user_id' AND post_status='publish' AND post_type='post' 
    ORDER BY $orderBy $order LIMIT $offset,$limit");
} else {
  $author_posts = $wpdb->get_results( "SELECT * FROM $postsTable p JOIN $postMetaTable pm ON p.ID=pm.post_id AND pm.meta_key='$metaKey'
    WHERE post_author='$user_id' AND post_status='publish' AND post_type='post' 
     ORDER BY pm.meta_value $order LIMIT $offset,$limit");
}
// projects
if ($queryType === 'simple'){
  $author_projects = $wpdb->get_results( "SELECT * FROM $postsTable WHERE post_author='$user_id' AND post_status='publish' AND post_type='project' 
    ORDER BY $orderBy $order LIMIT $offset,$limit");
} else {
  $author_projects = $wpdb->get_results( "SELECT * FROM $postsTable p JOIN $postMetaTable pm ON p.ID=pm.post_id AND pm.meta_key='$metaKey'
    WHERE post_author='$user_id' AND post_status='publish' AND post_type='project' 
     ORDER BY pm.meta_value $order LIMIT $offset,$limit");
}
$articles_pages_count = ceil( sizeof($author_posts) / $limit );
$projects_pages_count = ceil( sizeof($author_projects) / $limit );
?>
<?php get_header(); ?>


<div class="container">
    <div class="breadcrumbs breadcrumbs--border-top">
        <ul itemprop="breadcrumb" id="breadcrumbs" class="breadcrumbs">
            <li class="item-home">
                <a class="bread-link bread-home" href="<?= site_url() ?>" title="صفحه نخست">صفحه نخست</a>
            </li>
            <li class="item-current item-archive"><span class="bread-current bread-archive"> نوشته های  <?= $author_name ?> </span></li>
        </ul>
    </div>

    <section class="download">
        <div class="container">
            <div class="archive-blog__header">
                <div class="archive-blog__header__top justify-content-between">
                    <div class="archive-blog__header__top__title">
                        <i class="dn-menu2"></i>
                        <h3> نوشته های <?= $author_name ?> </h3>
                    </div>
				  <?php $username = str_replace(' ','-',$user->data->user_login); ?>
                    <p class="ztools-author-profile"><a href="<?= site_url().'/user/'. $username; ?>">پروفایل نویسنده</a></p>
                </div>
                <div class="archive-blog__header__bottom">
                    <form action="" method="get" class="archive-blog__header__bottom__filter">
                        <select name="filter" id="filter-download" class="form-select">
                            <option value="newest" <?= (isset($sort) && $sort == 'newest') ? 'selected' : ''; ?>>جدیدترین ها</option>
                            <option value="topcomments" <?= (isset($sort) && $sort == 'topcomments') ? 'selected' : ''; ?>>پر بحث ترین ها</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Tabs -->
            <div class="search" data-tabindex="search" style="padding-top: 0">
                <div class="search__tabs">
                    <ul class="tab-title">
                        <li class="author-tab active" data-tab="articles" data-parent="search">
                            <span class="tab-title__tlt">مقاله ها</span>
                            <span class="tab-title__count"><?= sizeof($author_posts); ?></span>
                        </li>
                        <li class="author-tab" data-tab="projects" data-parent="search">
                            <span class="tab-title__tlt">پروژه ها</span>
                            <span class="tab-title__count"><?= sizeof($author_projects); ?></span>
                        </li>
                    </ul>
                </div>

                <div class="search__content tab-content">
                    <div class="search__articles" data-tabc="articles" data-parent="search">
                        <div class="download__list">
                            <div class="row">
							  <?php if ($user_id === null || sizeof($author_posts) === 0): ?>
                                  <div class="w-100 text-center" role="alert">
                                      <span class="text-muted small"><?= __('There\'s nothing to show','ztools'); ?></span>
                                  </div>
							  <?php endif; ?>

							  <?php
							  foreach ($author_posts as $post){
								$post_id = $post->ID;
								$post_image = get_the_post_thumbnail_url($post_id);
								$post_link =  get_permalink($post_id);
								$post_title  = get_the_title($post);
								$post_desc = get_the_excerpt($post) . '...';
								$post_like  = post_like_count($post_id, 'like');
								$post_view  = get_views($post_id);
								$post_comments  = get_comments_number($post_id);
								$number_part = get_field('number_part', $post_id);
								?>
                                  <div class="col-lg-4 col-md-6">
                                      <div class="download__list__item">
                                          <a href="<?= $post_link ?>"></a>
                                          <div class="download__list__item__top">
                                              <div class="download__list__item__top__image">
                                                  <img width="70" height="70" src="<?= $post_image ?>" class="attachment-70x70 size-70x70 wp-post-image"
                                                       alt="<?= $post_title ?>" loading="lazy" sizes="(max-width: 70px) 100vw, 70px">                                                </div>
                                              <div class="download__list__item__top__text">
                                                  <div class="download__list__item__top__text__title">
                                                      <h3><?= $post_title ?></h3>
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="download__list__item__desc">
                                              <p><?= $post_desc ?></p>
                                          </div>
                                          <div class="download__list__item__info">
                                              <div class="download__list__item__info__author">
                                                  <img src="<?= get_avatar_url($post->post_author) ; ?>" class="gravatar avatar avatar-25 um-avatar um-avatar-uploaded"
                                                       width="25" height="25" alt="<?= $author_name ?>"
                                                       onerror="if ( ! this.getAttribute('data-load-error') ){ this.setAttribute('data-load-error', '1');this.setAttribute('src', this.getAttribute('data-default'));}">
                                                  <span><?= $author_name ?></span>
                                              </div>
                                              <div class="download__list__item__info__detail">
                                                  <ul>
                                                      <li>
                                                          <i class="dn-comment"></i><?= wp_count_comments($post_id)->approved ?>
                                                      </li>
                                                      <li>
                                                          <i class="dn-bar-chart"></i><?= get_post_meta($post_id, 'views', true) ?>
                                                      </li>
                                                      <li>
                                                          <i class="dn-favorite"></i><?= post_like_count($post_id, 'post_like') ?>
                                                      </li>
                                                  </ul>
                                              </div>
                                              <div class="download__list__item__info__button">
                                                  <a href="<?= $post_link ?>"><i class="dn-download"></i>ادامه و
                                                      دانلود</a>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
								<?php
							  }
							  ?>
                            </div>

                            <div class="pagination__list">
							  <?php
							  $page_links = paginate_links( array(
								  'base' => add_query_arg( 'pagenum', '%#%' ),
								  'format' => '',
								  'type' => 'list',
								  'prev_text' => '&laquo;',
								  'next_text' => '&raquo;',
								  'total' => $articles_pages_count,
								  'current' => $pagenum
							  ) );
							  if ( $page_links ) {
								echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
							  }
							  ?>
                            </div>
                        </div>
                    </div>
                    <div class="search__projects" data-tabc="projects" data-parent="search">
                        <div class="download__list">
                            <div class="row">
							  <?php if ($user_id === null || sizeof($author_projects) === 0): ?>
                                  <div class="w-100 text-center" role="alert">
                                      <span class="text-muted small"><?= __('There\'s nothing to show','ztools'); ?></span>
                                  </div>
							  <?php endif; ?>
							  <?php
							  foreach ($author_projects as $post){
								$post_id = $post->ID;
								$post_image = get_the_post_thumbnail_url($post_id);
								$post_link =  get_permalink($post_id);
								$post_title  = get_the_title($post);
								$post_desc = get_the_excerpt($post) . '...';
								$post_like  = post_like_count($post_id, 'like');
								$post_view  = get_views($post_id);
								$post_comments  = get_comments_number($post_id);
								$number_part = get_field('number_part', $post_id);
								?>
                                  <div class="col-lg-4 col-md-6">
                                      <div class="download__list__item">
                                          <a href="<?= $post_link ?>"></a>
                                          <div class="download__list__item__top">
                                              <div class="download__list__item__top__image">
                                                  <img width="70" height="70" src="<?= $post_image ?>" class="attachment-70x70 size-70x70 wp-post-image"
                                                       alt="<?= $post_title ?>" loading="lazy" sizes="(max-width: 70px) 100vw, 70px">                                                </div>
                                              <div class="download__list__item__top__text">
                                                  <div class="download__list__item__top__text__title">
                                                      <h3><?= $post_title ?></h3>
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="download__list__item__desc">
                                              <p><?= $post_desc ?></p>
                                          </div>
                                          <div class="download__list__item__info">
                                              <div class="download__list__item__info__author">
                                                  <img src="<?= get_avatar_url($post->post_author) ; ?>" class="gravatar avatar avatar-25 um-avatar um-avatar-uploaded"
                                                       width="25" height="25" alt="<?= $author_name ?>"
                                                       onerror="if ( ! this.getAttribute('data-load-error') ){ this.setAttribute('data-load-error', '1');this.setAttribute('src', this.getAttribute('data-default'));}">
                                                  <span><?= $author_name ?></span>
                                              </div>
                                              <div class="download__list__item__info__detail">
                                                  <ul>
                                                      <li>
                                                          <i class="dn-comment"></i><?= wp_count_comments($post_id)->approved ?>
                                                      </li>
                                                      <li>
                                                          <i class="dn-bar-chart"></i><?= get_post_meta($post_id, 'views', true) ?>
                                                      </li>
                                                      <li>
                                                          <i class="dn-favorite"></i><?= post_like_count($post_id, 'post_like') ?>
                                                      </li>
                                                  </ul>
                                              </div>
                                              <div class="download__list__item__info__button">
                                                  <a href="<?= $post_link ?>"><i class="dn-download"></i>ادامه و
                                                      دانلود</a>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
								<?php
							  }
							  ?>
                            </div>

                            <div class="pagination__list">
							  <?php
							  $page_links = paginate_links( array(
								  'base' => add_query_arg( 'pagenum', '%#%' ),
								  'format' => '',
								  'type' => 'list',
								  'prev_text' => '&laquo;',
								  'next_text' => '&raquo;',
								  'total' => $projects_pages_count,
								  'current' => $pagenum
							  ) );
							  if ( $page_links ) {
								echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
							  }
							  ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>


