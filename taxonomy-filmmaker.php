<?php get_header(); ?>

  <section id="content" role="main">

    <h1 class="page-title">Films by <?php echo get_queried_object()->name; ?></h1>

    <?php if (get_field('filmmaker_website', 'filmmaker_'.get_queried_object()->term_id)) : ?>
      <div class="entry-meta filmmaker-meta">
        <ul class="filmmaker-links">
          <li><a href="<?php the_field('filmmaker_website', 'filmmaker_'.get_queried_object()->term_id); ?>">Filmmaker's Website &raquo;</a></li>
        </ul>
      </div>
    <?php endif; ?>

    <?php while ( have_posts() ) : the_post(); ?>
      <?php get_template_part( 'content', 'video' ); ?>
    <?php endwhile; ?>

  </section>

<?php get_footer(); ?>