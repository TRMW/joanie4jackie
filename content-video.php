<article id="post-<?php the_ID() ?>" <?php post_class('group') ?>>
  <div class="video-thumbnail" style="background-image: url(<?php the_post_thumbnail_url(); ?>)">
    <?php // the_post_video(); ?>
    <?php echo wp_video_shortcode(array(
      'src' => get_the_post_video_url(),
      'poster' => get_the_post_thumbnail_url(),
      'width' => 320,
      'height' => 240
    )); ?>
  </div>

  <div class="video-body">
    <h2 class="entry-title">
      <a href="<?php the_permalink(); ?>" class="permalink" title="Permalink for this video">
        <?php the_title(); ?>
      </a>
    </h2>
    <?php if (!is_tax('filmmaker') || count(wp_get_post_terms($post->ID, 'filmmaker')) > 1 ) : ?>
      <h3 class="entry-author"><?php the_linked_filmmaker_names(); ?></h3>
    <?php endif; ?>
    <?php if (is_search() || is_single() || is_tax('filmmaker')) : ?>
      <?php the_video_chainletter_links('p'); ?>
    <?php endif; ?>

    <div class="entry-content">
      <?php if (is_search()) : ?>
        <?php the_excerpt(); ?>
      <?php else : ?>
        <?php the_content(); ?>
      <?php endif; ?>
    </div><!-- .entry-content -->

    <ul class="video-links">
      <?php if (!is_tax('filmmaker')) : ?>
        <?php the_filmmaker_links(); ?>
      <?php endif; ?>
      <li class="edit-link"><?php edit_post_link('Edit this video &raquo;'); ?></li>
    </ul>
  </div><!-- .video-body -->
</article>