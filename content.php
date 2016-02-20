<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

  <?php if (!is_search()) : ?>

    <h1 class="page-title"><?php the_title(); ?></h1>

    <?php if (get_post_type() == 'event') : ?>
      <ul class="entry-meta events-meta">
        <li><?php the_event_date() ?> | <?php the_field('event_venue'); ?></li>
        <li><?php the_field('event_city'); ?></li>
      </ul>
    <?php endif; ?>

    <div class="entry-content">
      <?php the_content(); ?>

      <?php if (get_field('photoset')) : ?>
        <div class="entry-photoset" data-photoset-link="<?php the_field('photoset'); ?>"></div>
      <?php endif; ?>

      <?php $videos = get_field('videos'); ?>
      <?php if ($videos) : ?>
          <?php foreach( $videos as $post): ?>
              <?php setup_postdata($post); ?>
              <?php get_template_part( 'content', 'video' ); ?>
          <?php endforeach; ?>
          <?php wp_reset_postdata(); ?>
      <?php endif; ?>

      <?php edit_post_link('Edit this entry &raquo;', '<div class="entry-utility">', '</div>'); ?>
    </div>

  <?php else: ?>

    <h2 class="page-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <div class="entry-content">
      <?php the_excerpt(); ?>
    </div>

  <?php endif; ?>

</article><!-- #post-## -->
