<?php get_header(); ?>

  <section class="content">

    <?php if (have_posts()) : ?>

      <h1 class="page-title">
        <?php printf('Search Results for &ldquo;%s&rdquo;', get_search_query()); ?>
      </h1>
      <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('content', get_post_type()); ?>
      <?php endwhile; ?>

      <?php the_posts_pagination(array('next_text' => 'Next &raquo;','prev_text' => '&laquo; Previous')); ?>

    <?php else : ?>

      <article class="post no-results not-found">
        <h1 class="page-title">Nothing Found</h1>
        <div class="entry-content">
          <p>Sorry, looks like nothing matched your search. Try again?</p>
        </div>
      </article>

    <?php endif; ?>

  </section>

<?php get_footer(); ?>
