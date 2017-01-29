<?php get_header(); ?>

  <nav class="sidebar">
    <?php $pagination = get_taxonomy_sidebar(); ?>
    <?php if (get_queried_object()->taxonomy == 'chainletter') : ?>
      <div class="bard-note">* Bard College Joanie 4 Jackie Tutorial</div>
    <?php endif; ?>
  </nav>

  <section class="content" id="content" role="main">
    <h1 class="page-title">
      <?php echo get_queried_object()->name; ?>
    </h1>

    <div class="entry-meta chainletter-meta">
      <?php the_chainletter_meta(); ?>
      <?php if (!empty(get_queried_object()->description)) : ?>
        <?php echo apply_filters('the_content', get_queried_object()->description); ?>
      <?php endif; ?>
    </div>

    <?php while ( have_posts() ) : the_post(); ?>
      <?php // only show videos with this filmmaker tag (not Now interviews) ?>
      <?php if (get_post_type() == 'video') : ?>
        <?php get_template_part( 'content', 'video' ); ?>
      <?php endif; ?>
    <?php endwhile; ?>

    <?php if ($previous = $pagination['previous']) : ?>
      <a href="<?php echo get_term_link($previous->slug, get_queried_object()->taxonomy); ?>" class="pagination-link">&laquo; <strong>Previous:</strong> <?php echo $previous->name; ?></a>
    <?php endif; ?>
    <?php if ($next = $pagination['next']) : ?>
      <a href="<?php echo get_term_link($next->slug, get_queried_object()->taxonomy); ?>" class="pagination-link"><strong>Next:</strong> <?php echo $next->name; ?> &raquo;</a>
    <?php endif; ?>

  </section>

<?php get_footer(); ?>
