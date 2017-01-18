      </div><!-- .main -->

      <footer class="footer">
          <div class="site-title">
            <a href="<?php echo home_url( '/' ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
          </div>
          <nav class="nav">
            <?php wp_nav_menu(array('container' => '')); ?>
        <form role="search" method="get" class="search-form" action="http://v2.joanie4jackie.com/">
              <input type="text" value="" name="s" id="search-input" placeholder="Search">
            </form>
          </nav>
      </footer>

    </div><!-- #wrapper -->

    <?php wp_footer(); ?>
  </body>
</html>
