<?php get_header(); ?>

  <nav class="sidebar now-sidebar">
    <ul>
      <li class="now-sidebar__section">
        <div class="now-sidebar__section__title">
          <a href="/now/supporters">Supporters</a>
        </div>
      </li>
      <li class="now-sidebar__section">
        <div class="now-sidebar__section__title">Co-Star Filmmakers/Curators:</div>
        <ul>
          <?php get_now_sidebar_section_items('costar'); ?>
        </ul>
      </li>
      <li class="now-sidebar__section">
        <div class="now-sidebar__section__title">Chainletter Filmmakers:</div>
        <ul>
          <?php get_now_sidebar_section_items('chainletter'); ?>
        </ul>
      </li>
    </ul>
  </nav>

  <section class="content">
    <div class="now-header">
      <h1 class="page-title">Where is She Now?</h1>
      <?php
        $now_response_taxonomies = array_keys(get_the_taxonomies());
        if (in_array('chainletter', $now_response_taxonomies)) {
          $now_response_taxonomy = 'chainletter';
        } elseif (in_array('costar', $now_response_taxonomies)) {
          $now_response_taxonomy = 'costar';
        }
        $now_response_tapes = get_the_terms(get_the_ID(), $now_response_taxonomy);
        $now_response_tape = $now_response_tapes[0];
      ?>
      <h2 class="now-section-heading">
        <?php if ($now_response_taxonomy == 'chainletter') : ?>
          Chainletter Filmmakers:
        <?php elseif ($now_response_taxonomy == 'costar') : ?>
          Co-Star Filmmakers/Curators:
        <?php else : ?>
          Supporters
        <?php endif; ?>
      </h2>
      <?php if ($now_response_taxonomy) : ?>
        <h3>
          <a href="<?php echo get_term_link($now_response_tape); ?>">
            <?php echo $now_response_tape->name; ?>
          </a>
        </h3>
      <?php endif; ?>
    </div>

    <?php while (have_posts()) : the_post(); ?>
      <?php get_template_part('content', 'now_response'); ?>
    <?php endwhile; ?>
  </section>

<?php get_footer(); ?>
