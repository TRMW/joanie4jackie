<?php get_header(); ?>

  <nav class="sidebar">
    <?php get_taxonomy_sidebar(); ?>
  </nav>

  <section class="content">

    <h1 class="page-title"><?php echo get_queried_object()->name; ?></h1>

    <div class="entry-meta chainletter-meta">
      <?php the_chainletter_meta(); ?>
      <ul class="chainletter-links">
        <?php $term = get_queried_object(); $acg_id = $term->taxonomy._.$term->term_id; ?>
        <li><a href="/now/#<?php the_now_link($acg_id); ?>">Where are they now &raquo;</a></li>
      </ul>
      <?php if ( !empty($term->description) ) : ?>
        <?php echo apply_filters('the_content', $term->description); ?>
      <?php endif; ?>
    </div>

    <?php while ( have_posts() ) : the_post(); ?>
      <?php get_template_part( 'content', 'video' ); ?>
    <?php endwhile; ?>

  </section>

<?php get_footer(); ?>
