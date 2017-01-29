<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

  <?php if (!is_search()) : ?>

    <div class="entry-content now-post">
      <p>
        <?php if (has_tag('supporter')) : ?>
          <span class="now-filmmaker"><?php the_title(); ?></span>,
          <?php the_field('supporter_role'); ?>
        <?php else : ?>
          <?php
            $filmmakers = get_the_terms(get_the_ID(), 'filmmaker');
            $filmmaker = $filmmakers[0]; // assume only one filmmamer linked to each interview
          ?>

          <span class="now-filmmaker"><?php echo $filmmaker ? get_linked_filmmaker_name($filmmaker) : get_the_title(); ?></span>,

          <?php if (get_field('is_curator')) : ?>
            <em>curator</em>
          <?php elseif($filmmaker) : ?>
            <?php the_now_video_list($filmmaker); ?>
          <?php endif; ?>
        <?php endif; ?>

        <?php if (get_field('interview_date')) : ?>
         <br><?php echo date('F j, Y', strtotime(get_field('interview_date'))); ?>
        <?php endif; ?>
      </p>

      <?php the_content(); ?>

      <?php edit_post_link('Edit this entry &raquo;', '<div class="entry-utility">', '</div>'); ?>
    </div>

  <?php else : ?>

    <div class="search-result-type">Where is She Now?</div>
    <h2 class="entry-title">
      <a href="<?php the_permalink(); ?>">
        <?php the_title(); ?>
      </a>
    </h2>

    <div class="entry-content">
      <?php the_excerpt(); ?>
    </div>

  <?php endif; ?>

</article>
