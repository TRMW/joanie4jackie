<?php get_header(); ?>

  <?php if (get_post_type() == 'post') : ?>
    <nav class="sidebar">
      <?php get_sidebar(); ?>
    </nav>
  <?php elseif (get_post_type() != 'page' || in_array($post->post_name, array('events', 'archive'))) : ?>
    <nav class="sidebar">
      <?php get_post_type_sidebar(); ?>
    </nav>
  <?php endif; ?>

  <section class="content">
    <?php while ( have_posts() ) : the_post(); ?>
      <?php get_template_part( 'content', get_post_type() ); ?>
    <?php endwhile; ?>
  </section>

<?php get_footer(); ?>