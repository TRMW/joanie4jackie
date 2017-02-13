<?php get_header(); ?>

  <section class="content" id="content" role="main">

    <h1 class="page-title">Films by <?php echo get_queried_object()->name; ?></h1>

    <?php $filmmaker_links = get_the_filmmaker_links(); ?>

    <?php if ($filmmaker_links) : ?>
      <div class="entry-meta filmmaker-meta">
        <ul class="filmmaker-links">
          <?php echo $filmmaker_links; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php while (have_posts()) : the_post(); ?>
      <?php // only show videos with this filmmaker tag (not Now interviews) ?>
      <?php get_template_part('content', 'video'); ?>
    <?php endwhile; ?>

  </section>

<?php get_footer(); ?>