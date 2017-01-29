<?php get_header(); ?>

  <?php // $post->post_name is the slug, not the post title ?>
  <?php if ((get_post_type() != 'page' || in_array($post->post_name, array('archive'))) && !in_array(get_post_type(), array('video', 'attachment')))  : ?>
    <?php $pagination = get_post_type_sidebar(); ?>
  <?php endif; ?>

  <section class="content">
    <?php while (have_posts()) : the_post(); ?>
      <?php get_template_part('content', get_post_type()); ?>
    <?php endwhile; ?>

    <?php if (get_post_type() == 'event') : ?>
      <?php if ($previous = $pagination['previous']) : ?>
        <a href="<?php echo get_permalink($previous->ID); ?>" class="pagination-link">&laquo; <strong>Previous:</strong> <?php echo $previous->post_title; ?></a>
      <?php endif; ?>
      <?php if ($next = $pagination['next']) : ?>
        <a href="<?php echo get_permalink($next->ID); ?>" class="pagination-link"><strong>Next:</strong> <?php echo $next->post_title; ?> &raquo;</a>
      <?php endif; ?>
    <?php endif; ?>
  </section>

<?php get_footer(); ?>