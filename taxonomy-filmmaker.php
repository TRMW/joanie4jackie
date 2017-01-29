<?php get_header(); ?>

  <section class="content" id="content" role="main">

    <h1 class="page-title">Films by <?php echo get_queried_object()->name; ?></h1>

    <?php if (get_field('filmmaker_website', get_queried_object())) : ?>
      <div class="entry-meta filmmaker-meta">
        <ul class="filmmaker-links">
          <li>
            <a href="<?php the_field('filmmaker_website', get_queried_object()); ?>">
              Filmmaker's Website &raquo;
            </a>
          </li>
          <?php
            $now_response = get_posts(array(
              'numberposts' => 1,
              'post_type' => 'now_response',
              'tax_query' => array(array(
                'taxonomy' => 'filmmaker',
                'field' => 'term_id',
                'terms' => get_queried_object()->term_id
            ))));
          ?>
          <?php if ($now_response) : ?>
            <li>
              <a href="<?php the_permalink($now_response[0]) ?>">
                Where is She Now? &raquo;
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php while (have_posts()) : the_post(); ?>
      <?php // only show videos with this filmmaker tag (not Now interviews) ?>
      <?php if (get_post_type() == 'video') : ?>
        <?php get_template_part('content', 'video'); ?>
      <?php endif; ?>
    <?php endwhile; ?>

  </section>

<?php get_footer(); ?>