<?php
/**
 * Trang con, bài viết (Tin tức) và mọi trang khác ngoài trang chủ.
 */
get_header(); ?>

<main class="page-main">
  <div class="wrap page-wrap">
    <?php if ( have_posts() ) : ?>
      <?php if ( ! is_singular() ) : ?>
        <h1 class="sec-title"><?php echo is_home() ? 'Tin tức' : esc_html( wp_strip_all_tags( get_the_archive_title() ) ); ?></h1>
        <div class="post-list">
          <?php while ( have_posts() ) : the_post(); ?>
            <article class="post-card">
              <?php if ( has_post_thumbnail() ) : ?><a href="<?php the_permalink(); ?>" class="post-thumb"><?php the_post_thumbnail( 'medium_large' ); ?></a><?php endif; ?>
              <div class="post-body">
                <span class="proj-loc"><?php echo esc_html( get_the_date() ); ?></span>
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>
              </div>
            </article>
          <?php endwhile; ?>
        </div>
        <div class="pager"><?php the_posts_pagination( array( 'prev_text' => '← Trước', 'next_text' => 'Sau →' ) ); ?></div>
      <?php else : ?>
        <?php while ( have_posts() ) : the_post(); ?>
          <article class="entry">
            <?php if ( is_single() ) : ?><span class="proj-loc"><?php echo esc_html( get_the_date() ); ?></span><?php endif; ?>
            <h1 class="sec-title"><?php the_title(); ?></h1>
            <?php if ( has_post_thumbnail() ) : ?><div class="entry-thumb"><?php the_post_thumbnail( 'large' ); ?></div><?php endif; ?>
            <div class="entry-content"><?php the_content(); ?></div>
          </article>
        <?php endwhile; ?>
      <?php endif; ?>
    <?php else : ?>
      <h1 class="sec-title">Không tìm thấy trang</h1>
      <p>Trang bạn tìm không tồn tại. <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Về trang chủ</a></p>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>
