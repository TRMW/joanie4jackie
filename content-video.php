<article id="post-<?php the_ID(); ?>" <?php post_class('group'); ?>>
  <?php the_video_thumbnail(); ?>

  <div class="video-body">
    <h2 class="entry-title"><?php the_title(); ?></h2>
    <?php if (!is_tax('filmmaker') || count(wp_get_post_terms($post->ID, 'filmmaker')) > 1 ) : ?>
      <h3 class="entry-author"><?php the_filmmaker_names() ?></h3>
    <?php endif; ?>

    <div class="entry-content">
      <?php the_content(); ?>
    </div><!-- .entry-content -->

    <ul class="video-links">
      <?php if (is_tax('filmmaker')) : ?>
        <li><?php the_video_chainletter_links(); ?></li>
      <?php endif; ?>
      <?php if (get_field('clip_link')) : ?>
        <li><a href="<?php the_field('clip_link'); ?>" class="vimeo-link" target="_blank">Watch &raquo;</a></li>
      <?php endif; ?>
      <?php if (!is_tax('filmmaker')) : ?>
        <?php the_filmmaker_links(); ?>
      <?php endif; ?>
      <li class="edit-link"><?php edit_post_link('Edit this video &raquo;'); ?></li>
    </ul>
  </div><!-- .video-body -->
</article>