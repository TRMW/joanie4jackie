<?php get_header(); ?>

  <nav class="sidebar now-sidebar">
    <ul>
      <li class="now-sidebar__section">
        <div class="now-sidebar__section__title <?php echo is_tag('supporter') ? 'current-menu-item' : ''; ?>">
          <a href="/now/supporters">Supporters</a>
        </div>
      </li>
      <li class="now-sidebar__section">
        <div class="now-sidebar__section__title">Co-Star Filmmakers/Curators:</div>
        <ul>
          <?php $costar_pagination = get_now_sidebar_section_items('costar'); ?>
        </ul>
      </li>
      <li class="now-sidebar__section">
        <div class="now-sidebar__section__title">Chainletter Filmmakers:</div>
        <ul>
          <?php $chainletter_pagination = get_now_sidebar_section_items('chainletter'); ?>
        </ul>
      </li>
    </ul>
  </nav>

  <section class="content">
    <div class="now-header">
      <h1 class="page-title">Where is She Now?</h1>

      <div class="now-intro">
        <?php echo apply_filters('the_content', get_post(8776)->post_content); // survey links ?>
        <span class="edit-link"><?php edit_post_link('Edit box text &raquo;', '', '', 8776); ?></span>
      </div>

      <h2 class="now-section-heading">
        <?php if (get_queried_object()->taxonomy == "chainletter") : ?>
          Chainletter Filmmakers:
        <?php elseif (get_queried_object()->taxonomy == "costar") : ?>
          Co-Star Filmmakers/Curators:
        <?php else : ?>
          Supporters
        <?php endif; ?>
      </h2>
      <?php if (!is_tag('supporter')) : ?>
        <h3>
          <a href="<?php echo get_term_link(get_queried_object()); ?>">
            <?php echo get_queried_object()->name; ?>
          </a>
        </h3>
      <?php endif; ?>
    </div>

    <?php if (have_posts()) : ?>
      <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('content', 'now_response'); ?>
      <?php endwhile; ?>

      <?php if (is_tag('supporter')) : ?>
        <?php $next = $costar_pagination['first']; // link to first costar ?>
        <a href="<?php echo get_the_now_link($next, 'costar'); ?>" class="pagination-link"><strong>Next:</strong> <?php echo $next->name; ?> &raquo;</a>
      <?php else : ?>
        <?php if ($previous = get_queried_object()->taxonomy == 'costar' ? $costar_pagination['previous'] : $chainletter_pagination['previous']) : ?>
          <a href="<?php echo get_the_now_link($previous, get_queried_object()->taxonomy); ?>" class="pagination-link">&laquo; <strong>Previous:</strong> <?php echo $previous->name; ?></a>
        <?php elseif (get_queried_object()->taxonomy == 'costar') : // link to supporters ?>
          <a href="/now/supporters" class="pagination-link">&laquo; <strong>Previous:</strong> Supporters</a>
        <?php elseif (get_queried_object()->taxonomy == 'chainletter') : ?>
          <?php $previous = $costar_pagination['last']; // link to last costar ?>
          <a href="<?php echo get_the_now_link($previous, 'costar'); ?>" class="pagination-link">&laquo; <strong>Previous:</strong> <?php echo $previous->name; ?></a>
        <?php endif; ?>
        <?php if ($next = get_queried_object()->taxonomy == 'costar' ? $costar_pagination['next'] : $chainletter_pagination['next']) : ?>
          <a href="<?php echo get_the_now_link($next, get_queried_object()->taxonomy); ?>" class="pagination-link"><strong>Next:</strong> <?php echo $next->name; ?> &raquo;</a>
        <?php elseif (get_queried_object()->taxonomy == 'costar') : ?>
          <?php $next = $chainletter_pagination['first']; // link to first chainletter ?>
          <a href="<?php echo get_the_now_link($next, 'chainletter'); ?>" class="pagination-link"><strong>Next:</strong> <?php echo $next->name; ?> &raquo;</a>
        <?php endif; ?>
      <?php endif; ?>
    <?php else : ?>
      <p>We haven't been able to get in touch with any of the people involved with this tape yet. Please use the links above to get in touch if you are one of them!</p>
    <?php endif; ?>
  </section>

<?php get_footer(); ?>
