<?php get_header(); ?>

  <section id="content">

    <?php // First loop for page content ?>
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <h1 class="front-title">Joanie 4 Jackie</h1>
        <div class="entry-content">
          <?php the_content(); ?>
          <?php edit_post_link( __( 'Edit', 'twentyten' ), '<span class="edit-link">', '</span>' ); ?>
        </div>
      </article>
    <?php endwhile; ?>

    <?php // Second loop for section blurbs ?>
    <?php query_posts('post_type=blurb&order=ASC'); ?>
    <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php $link = get_field('link_address'); ?>
        <a href="<?php echo $link; ?>"><?php the_post_thumbnail('medium'); ?></a>
        <h2><a href="<?php echo $link; ?>"><?php the_title(); ?></a></h2>

        <div class="entry-content">
          <?php the_content(); ?>
        </div>

        <?php edit_post_link('Edit', '<div class="entry-utility">', '</div>' ); ?>
      </article>

    <?php endwhile; ?>

  </section>

<?php get_footer(); ?>