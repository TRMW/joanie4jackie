<?php get_header(); ?>
        <section class="content">
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <h1 class="page-title">
              Oops! That page can&rsquo;t be found.
            </h1>

            <div class="entry-content">
              It looks like nothing was found at this location. Maybe try a search?
            </div>

          </article>
        </section>
      </div><!-- .main -->
    </div><!-- #wrapper -->
    <?php wp_footer(); ?>
  </body>
</html>
