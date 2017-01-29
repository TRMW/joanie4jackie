<section class="content">
  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php if (!is_search()) : ?>

      <h1 class="page-title">
        <?php the_title(); ?>
      </h1>

      <?php if (get_post_type() == 'event') : ?>
        <ul class="entry-meta events-meta">
          <li>
            <?php the_event_date() ?>
            <?php if ($venue = get_field('event_venue')) : ?>
              | <?php echo $venue; ?>
            <?php endif; ?>
          </li>
          <?php if ($city = get_field('event_city')) : ?>
            <li><?php echo $city; ?></li>
          <?php endif; ?>
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
            <?php get_template_part('content', 'video'); ?>
          <?php endforeach; ?>
          <?php wp_reset_postdata(); ?>
        <?php endif; ?>

        <?php edit_post_link('Edit this entry &raquo;', '<div class="entry-utility">', '</div>'); ?>
      </div>

    <?php else: ?>

      <div class="search-result-type">
        <?php if (in_array(get_post_type(), array('event', 'filmmaker'))) : ?>
          <?php echo ucwords(get_post_type()); ?>
        <?php elseif (in_array(get_post_type(), array('chainletter', 'costar'))) : ?>
          <?php echo ucwords(get_post_type()); ?> Tape
        <?php endif; ?>
      </div>

      <h2 class="entry-title">
        <a href="<?php the_permalink(); ?>">
          <?php the_title(); ?>
        </a>
      </h2>

      <div class="entry-content">
        <?php the_excerpt(); ?>
      </div>

    <?php endif; ?>

  </article><!-- #post-## -->
</section>