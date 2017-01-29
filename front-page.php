<?php get_header(); ?>

  <section id="content">

    <?php // First loop for page content ?>
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="entry-content">
          <?php the_content(); ?>
          <span class="edit-link">
            <?php edit_post_link('Edit'); ?>
          </span>
        </div>
      </article>
    <?php endwhile; ?>

    <?php // Second loop for section blurbs ?>
    <?php query_posts('post_type=blurb&order=ASC'); ?>
    <div class="blurbs">
      <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <?php $link = get_field('link_address'); ?>
          <a href="<?php echo $link; ?>"><?php the_post_thumbnail('medium'); ?></a>
          <h2><a href="<?php echo $link; ?>"><?php the_title(); ?></a></h2>

          <div class="entry-content">
            <a href="<?php echo $link; ?>"><?php the_content(); ?></a>
          </div>

          <div class="entry-utility">
            <?php edit_post_link('Edit'); ?>
          </div>
        </article>

      <?php endwhile; ?>
    </div>
  </section>

<?php get_footer(); ?>