<?php if (wp_attachment_is_image()) : ?>
  <article id="post-<?php the_ID(); ?>" <?php post_class('group'); ?>>

    <div class="attachment-image">
     <?php echo do_shortcode('[gallery ids="'. get_the_ID() . '" size="medium"]'); ?>
    </div>

    <div class="attachment-body">
      <?php if ($post->post_parent) : ?>
        <?php $parent = get_post($post->post_parent); ?>
        <?php if ($parent->post_type === 'archive') : ?>
          <div class="search-result-type">
            <?php echo $parent->post_title; ?> Archive Image
          </div>
        <?php elseif ($parent->post_type === 'event') : ?>
          <div class="search-result-type">
            Event Image
          </div>
        <?php endif; ?>
      <?php elseif($term_parent_id = get_post_meta(get_the_ID(), 'attachment_parent_term', true)) : ?>
        <?php $term_parent = get_term($term_parent_id); ?>
        <?php if($term_parent->taxonomy === 'chainletter') :?>
          <div class="search-result-type">
            Chainletter Tape Booklet Image
          </div>
        <?php elseif($term_parent->taxonomy === 'costar') :?>
          <div class="search-result-type">
            Co-Star Tape Booklet Image
          </div>
        <?php endif; ?>
      <?php endif; ?>

      <h2 class="entry-title">
        <a href="<?php the_permalink(); ?>" class="permalink" title="Permalink for this image">
          <?php the_title(); ?>
        </a>
      </h2>

      <div class="entry-content">
        <?php the_excerpt(); ?>
      </div>

      <ul class="attachment-links">
        <?php if (isset($parent)) : ?>
          <li>
            <a href="<?php the_permalink($parent); ?>">
              <?php echo $parent->post_title . ' ' . $parent->post_type; ?> page &raquo;
            </a>
          </li>
        <?php elseif (isset($term_parent)) : ?>
          <li>
            <a href="<?php get_term_link($term_parent->slug, $term_parent->taxonomy); ?>">
              View <?php echo $term_parent->name; ?> &raquo;
            </a>
          </li>
        <?php endif; ?>
        <li class="edit-link"><?php edit_post_link('Edit this image &raquo;'); ?></li>
      </ul>
    </div>

  </article>
<?php endif; ?>